<?php
App::uses('AppController', 'Controller');
App::uses('LivegoConfig', 'Lib/Livego');
App::uses('LivegoMercadoPago', 'Lib/Livego');
App::uses('LivegoVentas', 'Lib/Livego');

/**
 * Páginas públicas: cartelera, compra de entradas, aviso de Mercado Pago y eventos anteriores.
 */
class PublicController extends AppController
{
	public $uses = array('Event', 'Order');
	public $components = array('Flash');

	const MAX_POR_COMPRA = 10;

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('index', 'comprar', 'gracias', 'estado', 'archivo', 'mercadopago', 'nosotros', 'recuperar');
		$this->layout = 'publico';
	}

	protected function vendidas($eventId)
	{
		$r = $this->Order->find('first', array(
			'conditions' => array('Order.event_id' => $eventId, 'Order.estado' => array('aprobado', 'procesando')),
			'fields' => array('SUM(Order.cantidad) AS total'),
			'recursive' => -1,
		));
		return isset($r[0]['total']) ? (int)$r[0]['total'] : 0;
	}

	protected function enVenta()
	{
		return array(
			'Event.active' => 1,
			'Event.venta_activa' => 1,
			'Event.precio >' => 0,
			'Event.fechafin >=' => date('Y-m-d H:i:s'),
		);
	}

	/** Cartelera: eventos a la venta. */
	public function index()
	{
		$this->set('title_for_layout', 'Próximos eventos');
		$events = $this->Event->find('all', array(
			'conditions' => $this->enVenta(),
			'fields' => array('Event.id', 'Event.titulo', 'Event.descripcion', 'Event.imagen', 'Event.fecha', 'Event.fechafin', 'Event.precio', 'Event.cupo'),
			'order' => array('Event.fecha' => 'ASC'),
			'recursive' => -1,
		));
		foreach ($events as &$e) {
			$e['Event']['agotado'] = !empty($e['Event']['cupo']) && $this->vendidas($e['Event']['id']) >= (int)$e['Event']['cupo'];
		}
		$this->set('events', $events);
		$this->set('ventaLista', LivegoConfig::mercadoPagoListo());
	}

	/** Página del evento con formulario de compra. */
	public function comprar($id = null)
	{
		$event = $this->Event->find('first', array(
			'conditions' => array_merge(array('Event.id' => $id), $this->enVenta()),
			'fields' => array('Event.id', 'Event.titulo', 'Event.descripcion', 'Event.horario', 'Event.imagen', 'Event.fecha', 'Event.fechafin', 'Event.precio', 'Event.cupo'),
			'recursive' => -1,
		));
		if (!$event) {
			throw new NotFoundException('Este evento no está a la venta.');
		}
		$ev = $event['Event'];
		$quedan = empty($ev['cupo']) ? self::MAX_POR_COMPRA : max(0, (int)$ev['cupo'] - $this->vendidas($ev['id']));
		$this->set(compact('event', 'quedan'));
		$this->set('title_for_layout', $ev['titulo']);
		$this->set('ventaLista', LivegoConfig::mercadoPagoListo());

		if (!$this->request->is('post')) {
			return;
		}
		$d = isset($this->request->data['Compra']) ? $this->request->data['Compra'] : array();
		$email = strtolower(trim(isset($d['email']) ? $d['email'] : ''));
		$cantidad = (int)(isset($d['cantidad']) ? $d['cantidad'] : 1);
		$errores = array();
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errores[] = 'Revisá el email: ahí te llegan el usuario y la clave.';
		}
		if ($cantidad < 1 || $cantidad > min(self::MAX_POR_COMPRA, $quedan)) {
			$errores[] = $quedan < 1 ? 'No quedan entradas para este evento.' : 'Elegí entre 1 y ' . min(self::MAX_POR_COMPRA, $quedan) . ' entradas.';
		}
		if (!LivegoConfig::mercadoPagoListo()) {
			$errores[] = 'La venta online todavía no está habilitada.';
		}
		if ($errores) {
			$this->set('errores', $errores);
			return;
		}
		$token = bin2hex(random_bytes(16));
		$this->Order->create();
		$this->Order->save(array('Order' => array(
			'event_id' => $ev['id'],
			'email' => $email,
			'nombre' => mb_substr(trim(isset($d['nombre']) ? $d['nombre'] : ''), 0, 120),
			'telefono' => mb_substr(preg_replace('/[^0-9+ ]/', '', isset($d['telefono']) ? $d['telefono'] : ''), 0, 30),
			'cantidad' => $cantidad,
			'monto' => round($cantidad * (float)$ev['precio'], 2),
			'estado' => 'pendiente',
			'token' => $token,
			'created' => date('Y-m-d H:i:s'),
			'modified' => date('Y-m-d H:i:s'),
		)), array('validate' => false));
		$order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Order->id), 'recursive' => -1));
		try {
			$full = $this->Event->find('first', array('conditions' => array('Event.id' => $ev['id']), 'fields' => array('Event.id', 'Event.titulo', 'Event.precio', 'Event.imagen'), 'recursive' => -1));
			$cobro = LivegoMercadoPago::crearCobro($order['Order'], $full['Event'], $cantidad);
		} catch (Exception $e) {
			CakeLog::write('error', 'Mercado Pago: ' . $e->getMessage());
			$this->set('errores', array('No pudimos conectar con Mercado Pago. Probá de nuevo en un minuto.'));
			return;
		}
		$this->Order->id = $order['Order']['id'];
		$this->Order->saveField('mp_preference_id', $cobro['id']);
		return $this->redirect($cobro['url']);
	}

	protected function buscarCompra($token)
	{
		if (!preg_match('/^[a-f0-9]{32}$/', (string)$token)) {
			throw new NotFoundException();
		}
		$order = $this->Order->find('first', array('conditions' => array('Order.token' => $token), 'recursive' => 0));
		if (!$order) {
			throw new NotFoundException();
		}
		return $order;
	}

	/** Vuelta desde Mercado Pago: muestra los accesos o el estado del pago. */
	public function gracias($token = null)
	{
		$order = $this->buscarCompra($token);
		$o = $order['Order'];
		// Si volvió con el pago en la URL y todavía no nos avisaron, lo consultamos nosotros
		$paymentId = isset($this->request->query['payment_id']) ? $this->request->query['payment_id'] : (isset($this->request->query['collection_id']) ? $this->request->query['collection_id'] : null);
		if ($o['estado'] !== 'aprobado') {
			try {
				if ($paymentId && ctype_digit((string)$paymentId)) {
					$r = LivegoVentas::procesarPago($paymentId);
				} else {
					$r = LivegoVentas::revisarCompra($o['id']);
				}
				if ($r) {
					$order = $this->buscarCompra($token);
					$o = $order['Order'];
				}
			} catch (Exception $e) {
				CakeLog::write('error', 'Revisión de compra ' . $o['id'] . ': ' . $e->getMessage());
			}
		}
		$this->set('order', $order);
		$this->set('accesos', $o['estado'] === 'aprobado' ? LivegoVentas::accesosDe($o) : array());
		$this->set('urlIngreso', LivegoConfig::baseUrl() . '/users/login');
		$this->set('emailListo', LivegoConfig::emailListo());
		$this->set('title_for_layout', 'Tu compra');
	}

	/** Para que la página de "pago pendiente" se actualice sola. */
	public function estado($token = null)
	{
		$order = $this->buscarCompra($token);
		$this->autoRender = false;
		$this->response->type('json');
		$this->response->body(json_encode(array('estado' => $order['Order']['estado'])));
		return $this->response;
	}

	/**
	 * "¿Olvidaste tu usuario o clave?": reenvía por mail los accesos de las compras aprobadas.
	 * Siempre responde lo mismo para no revelar qué mails compraron, y limita los intentos.
	 */
	public function recuperar()
	{
		$this->layout = 'auth';
		$this->set('title_for_layout', 'Recuperar acceso');
		$this->set('enviado', false);
		if (!$this->request->is('post')) {
			return;
		}
		$d = isset($this->request->data['Recuperar']) ? $this->request->data['Recuperar'] : array();
		$email = strtolower(trim(isset($d['email']) ? $d['email'] : ''));
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->set('error', 'Revisá el email.');
			return;
		}
		$ip = $this->request->clientIp();
		$ahora = time();
		$limites = array('ip_' . md5($ip) => 5, 'mail_' . md5($email) => 3); // por hora
		foreach ($limites as $k => $max) {
			$usos = array_filter((array)Cache::read('recuperar_' . $k, 'default'), function ($t) use ($ahora) { return $t > $ahora - 3600; });
			if (count($usos) >= $max) {
				$this->set('error', 'Ya pediste varios reenvíos. Esperá un rato y revisá también la carpeta de spam.');
				return;
			}
			$usos[] = $ahora;
			Cache::write('recuperar_' . $k, array_values($usos), 'default');
		}
		$orders = $this->Order->find('all', array(
			'conditions' => array('LOWER(Order.email)' => $email, 'Order.estado' => 'aprobado', 'Order.created >=' => date('Y-m-d H:i:s', strtotime('-180 days'))),
			'fields' => array('Order.id'),
			'order' => array('Order.id' => 'DESC'),
			'limit' => 10,
			'recursive' => -1,
		));
		foreach ($orders as $o) {
			try {
				LivegoVentas::enviarAccesos($o['Order']['id']);
			} catch (Exception $e) {
				CakeLog::write('error', 'Recuperar acceso ' . $o['Order']['id'] . ': ' . $e->getMessage());
			}
		}
		error_log('[livego] Recuperar acceso: ' . count($orders) . ' compra(s) para el mail pedido');
		$this->set('enviado', true);
		$this->set('email', $email);
	}

	/** Quiénes somos. */
	public function nosotros()
	{
		$this->set('title_for_layout', 'Quiénes somos');
	}

	/** Eventos anteriores publicados en YouTube. */
	public function archivo()
	{
		$this->set('title_for_layout', 'Eventos anteriores');
		$this->set('events', $this->Event->find('all', array(
			'conditions' => array('Event.youtube_archivo <>' => '', 'Event.youtube_archivo IS NOT NULL'),
			'fields' => array('Event.id', 'Event.titulo', 'Event.fecha', 'Event.descripcion', 'Event.youtube_archivo', 'Event.imagen'),
			'order' => array('Event.fecha' => 'DESC'),
			'recursive' => -1,
		)));
	}

	/**
	 * Aviso de Mercado Pago (webhook). Siempre se verifica el pago contra la API, así que un aviso falso no habilita nada.
	 */
	public function mercadopago()
	{
		$this->autoRender = false;
		$q = $this->request->query;
		$body = json_decode((string)$this->request->input(), true) ?: array();
		$tipo = isset($body['type']) ? $body['type'] : (isset($q['type']) ? $q['type'] : (isset($q['topic']) ? $q['topic'] : null));
		$id = isset($body['data']['id']) ? $body['data']['id'] : (isset($q['data_id']) ? $q['data_id'] : (isset($q['data.id']) ? $q['data.id'] : (isset($q['id']) ? $q['id'] : null)));
		if ($tipo === 'payment' && $id && ctype_digit((string)$id)) {
			try {
				LivegoVentas::procesarPago($id);
			} catch (Exception $e) {
				CakeLog::write('error', 'Webhook Mercado Pago ' . $id . ': ' . $e->getMessage());
				if (strpos($e->getMessage(), 'respondió 404') !== false) {
					$this->response->statusCode(200); // pago inexistente: no reintentar
					return $this->response;
				}
				$this->response->statusCode(500); // Mercado Pago reintenta
				return $this->response;
			}
		}
		$this->response->statusCode(200);
		$this->response->body('ok');
		return $this->response;
	}
}
