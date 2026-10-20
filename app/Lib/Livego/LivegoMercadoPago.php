<?php
App::uses('LivegoHttp', 'Lib/Livego');
App::uses('LivegoConfig', 'Lib/Livego');

/**
 * Mercado Pago Checkout Pro. Variable: MP_ACCESS_TOKEN (de prueba o de producción).
 */
class LivegoMercadoPago {

	protected static function api($method, $path, $body = null) {
		$base = LivegoConfig::get('MP_API_BASE', 'https://api.mercadopago.com');
		$headers = array('Authorization' => 'Bearer ' . LivegoConfig::get('MP_ACCESS_TOKEN'));
		if ($method === 'POST') {
			$headers['X-Idempotency-Key'] = bin2hex(random_bytes(16));
		}
		$r = LivegoHttp::request($method, $base . $path, $headers, $body);
		if ($r['code'] >= 300 || !is_array($r['json'])) {
			$msg = 'Mercado Pago respondió ' . $r['code'];
			if (is_array($r['json']) && !empty($r['json']['message'])) {
				$msg .= ': ' . $r['json']['message'];
			}
			throw new RuntimeException($msg);
		}
		return $r['json'];
	}

	/**
	 * Crea el cobro para una compra. Devuelve ['id' => preference_id, 'url' => link de pago].
	 */
	public static function crearCobro(array $order, array $event, $cantidad) {
		$base = LivegoConfig::baseUrl();
		$volver = $base . '/comprar/gracias/' . $order['token'];
		$item = array(
			'id' => 'evento-' . $event['id'],
			'title' => 'Entrada: ' . $event['titulo'],
			'description' => 'Acceso online a ' . $event['titulo'],
			'quantity' => (int)$cantidad,
			'currency_id' => 'ARS',
			'unit_price' => round((float)$event['precio'], 2),
			'category_id' => 'tickets',
		);
		if (strpos((string)$event['imagen'], 'db:') === 0) {
			$item['picture_url'] = $base . '/events/imagen/' . $event['id'];
		}
		$body = array(
			'items' => array($item),
			'external_reference' => 'order-' . $order['id'],
			'notification_url' => $base . '/pagos/mercadopago',
			'back_urls' => array('success' => $volver, 'pending' => $volver, 'failure' => $volver),
			'auto_return' => 'approved',
			'statement_descriptor' => 'LIVEGO',
			'expires' => true,
			'expiration_date_to' => date('c', strtotime('+2 days')),
		);
		// No se manda el email del comprador: Mercado Pago lo pide en su pantalla y así no choca con cuentas de prueba
		$r = self::api('POST', '/checkout/preferences', $body);
		$url = $r['init_point'];
		if (LivegoConfig::get('MP_SANDBOX') && !empty($r['sandbox_init_point'])) {
			$url = $r['sandbox_init_point'];
		}
		return array('id' => $r['id'], 'url' => $url);
	}

	public static function pago($paymentId) {
		return self::api('GET', '/v1/payments/' . rawurlencode($paymentId));
	}

	/** Busca los pagos de una compra (por si el aviso de Mercado Pago no llegó). */
	public static function buscarPorReferencia($externalReference) {
		$r = self::api('GET', '/v1/payments/search?sort=date_created&criteria=desc&external_reference=' . rawurlencode($externalReference));
		return isset($r['results']) ? $r['results'] : array();
	}
}
