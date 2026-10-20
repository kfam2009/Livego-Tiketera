<?php
App::uses('ClassRegistry', 'Utility');
App::uses('LivegoConfig', 'Lib/Livego');
App::uses('LivegoCredenciales', 'Lib/Livego');
App::uses('LivegoMercadoPago', 'Lib/Livego');
App::uses('LivegoMailer', 'Lib/Livego');

/**
 * Lógica de ventas: aplica el resultado de un pago a la compra y entrega los accesos.
 */
class LivegoVentas {

	/** Mapea estados de Mercado Pago a los nuestros. */
	public static function estadoDesdeMp($status) {
		switch ($status) {
			case 'approved': return 'aprobado';
			case 'refunded':
			case 'charged_back': return 'devuelto';
			case 'rejected':
			case 'cancelled': return 'rechazado';
			default: return 'pendiente'; // pending, in_process, authorized, in_mediation
		}
	}

	/**
	 * Consulta un pago en Mercado Pago y actualiza la compra. Seguro de llamar varias veces.
	 */
	public static function procesarPago($paymentId) {
		$p = LivegoMercadoPago::pago($paymentId);
		if (empty($p['external_reference']) || !preg_match('/^order-(\d+)$/', $p['external_reference'], $m)) {
			return null;
		}
		return self::aplicar((int)$m[1], $p);
	}

	/** Revisa en Mercado Pago si una compra pendiente ya se pagó. */
	public static function revisarCompra($orderId) {
		$pagos = LivegoMercadoPago::buscarPorReferencia('order-' . $orderId);
		// Prioridad: un pago aprobado; si no, el más reciente
		$elegido = null;
		foreach ($pagos as $p) {
			if ($p['status'] === 'approved') { $elegido = $p; break; }
			if (!$elegido) { $elegido = $p; }
		}
		return $elegido ? self::aplicar($orderId, $elegido) : null;
	}

	protected static function aplicar($orderId, array $pago) {
		$Order = ClassRegistry::init('Order');
		$order = $Order->find('first', array('conditions' => array('Order.id' => $orderId), 'recursive' => -1));
		if (!$order) {
			return null;
		}
		$o = $order['Order'];
		$nuevo = self::estadoDesdeMp($pago['status']);
		$ds = $Order->getDataSource();
		$ahora = date('Y-m-d H:i:s');

		if ($nuevo === 'aprobado') {
			// El monto pagado tiene que coincidir con la compra
			if (isset($pago['transaction_amount']) && round((float)$pago['transaction_amount'], 2) + 0.01 < round((float)$o['monto'], 2)) {
				CakeLog::write('error', "Pago {$pago['id']} por monto menor a la compra {$orderId}");
				return $o;
			}
			if ($o['estado'] === 'aprobado' && !empty($o['user_ids'])) {
				return $o; // ya entregada
			}
			// Toma la compra en exclusiva (evita duplicados si llegan dos avisos juntos)
			$Order->updateAll(
				array('Order.estado' => "'procesando'", 'Order.modified' => $ds->value($ahora, 'string')),
				array('Order.id' => $orderId, 'Order.estado' => array('pendiente', 'rechazado'))
			);
			if ($Order->getAffectedRows() < 1) {
				return $o;
			}
			try {
				$ds->begin();
				$User = ClassRegistry::init('User');
				$Sus = ClassRegistry::init(array('class' => 'Suscription', 'table' => 'suscriptions'));
				$Event = ClassRegistry::init('Event');
				$ev = $Event->find('first', array('conditions' => array('Event.id' => $o['event_id']), 'recursive' => -1, 'fields' => array('Event.id', 'Event.titulo', 'Event.fecha')));
				$prefijo = LivegoCredenciales::prefijo(strtok((string)$ev['Event']['titulo'], ' '));
				$nombre = $o['nombre'] ?: $o['email'];
				$accesos = LivegoCredenciales::crearParaEvento($User, $Sus, $o['event_id'], (int)$o['cantidad'], $prefijo, $nombre, $o['email']);
				$ids = implode(',', array_map(function ($a) { return $a['id']; }, $accesos));
				$guardar = array_map(function ($a) { return array('usuario' => $a['usuario'], 'clave' => $a['clave'], 'nombre' => $a['nombre']); }, $accesos);
				$Order->id = $orderId;
				$Order->save(array('Order' => array(
					'estado' => 'aprobado',
					'mp_payment_id' => (string)$pago['id'],
					'mp_status_detail' => isset($pago['status_detail']) ? $pago['status_detail'] : null,
					'user_ids' => $ids,
					'accesos' => LivegoConfig::cifrar(json_encode($guardar)),
					'modified' => $ahora,
				)), array('validate' => false, 'callbacks' => false));
				$ds->commit();
			} catch (Exception $e) {
				$ds->rollback();
				$Order->updateAll(array('Order.estado' => "'pendiente'"), array('Order.id' => $orderId, 'Order.estado' => 'procesando'));
				throw $e;
			}
			self::enviarAccesos($orderId);
		} elseif ($nuevo === 'devuelto') {
			if (!empty($o['user_ids'])) {
				ClassRegistry::init('User')->updateAll(array('User.activo' => 0), array('User.id' => explode(',', $o['user_ids'])));
			}
			$Order->updateAll(array('Order.estado' => "'devuelto'", 'Order.mp_payment_id' => $ds->value((string)$pago['id'], 'string'), 'Order.modified' => $ds->value($ahora, 'string')), array('Order.id' => $orderId));
		} elseif ($o['estado'] !== 'aprobado') {
			$Order->updateAll(array(
				'Order.estado' => $ds->value($nuevo, 'string'),
				'Order.mp_payment_id' => $ds->value((string)$pago['id'], 'string'),
				'Order.mp_status_detail' => $ds->value(isset($pago['status_detail']) ? $pago['status_detail'] : '', 'string'),
				'Order.modified' => $ds->value($ahora, 'string'),
			), array('Order.id' => $orderId));
		}
		$r = $Order->find('first', array('conditions' => array('Order.id' => $orderId), 'recursive' => -1));
		return $r['Order'];
	}

	public static function accesosDe(array $o) {
		$j = LivegoConfig::descifrar($o['accesos']);
		return $j ? json_decode($j, true) : array();
	}

	public static function enviarAccesos($orderId) {
		$Order = ClassRegistry::init('Order');
		$order = $Order->find('first', array('conditions' => array('Order.id' => $orderId), 'recursive' => -1));
		if (!$order || $order['Order']['estado'] !== 'aprobado') {
			return false;
		}
		$o = $order['Order'];
		$ev = ClassRegistry::init('Event')->find('first', array('conditions' => array('Event.id' => $o['event_id']), 'recursive' => -1, 'fields' => array('Event.titulo', 'Event.fecha')));
		$fecha = !empty($ev['Event']['fecha']) ? date('d/m/Y H:i', strtotime($ev['Event']['fecha'])) . ' hs' : '';
		$ok = LivegoMailer::accesos($o['email'], $o['nombre'], $ev['Event']['titulo'], $fecha, self::accesosDe($o), LivegoConfig::baseUrl() . '/users/login');
		if ($ok) {
			$Order->updateAll(array('Order.email_enviado' => 1), array('Order.id' => $orderId));
		}
		return $ok;
	}
}
