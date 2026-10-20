<?php
App::uses('AppController', 'Controller');
App::uses('LivegoConfig', 'Lib/Livego');
App::uses('LivegoVentas', 'Lib/Livego');

/**
 * Admin: ventas por Mercado Pago.
 */
class OrdersController extends AppController
{
	public $components = array('Paginator', 'Flash');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'backend';
		if ($this->Auth->user('role') == 'Admin') {
			$this->Auth->allow();
		}
	}

	public function index()
	{
		$cond = array();
		$evento = isset($this->request->query['evento']) ? (int)$this->request->query['evento'] : 0;
		$estado = isset($this->request->query['estado']) ? $this->request->query['estado'] : '';
		if ($evento) {
			$cond['Order.event_id'] = $evento;
		}
		if (in_array($estado, array('aprobado', 'pendiente', 'rechazado', 'devuelto'), true)) {
			$cond['Order.estado'] = $estado;
		}
		$this->Paginator->settings = array('conditions' => $cond, 'order' => array('Order.id' => 'DESC'), 'limit' => 50, 'recursive' => 0);
		$this->set('orders', $this->Paginator->paginate('Order'));
		$tot = $this->Order->find('all', array(
			'conditions' => array_merge($cond, array('Order.estado' => 'aprobado')),
			'fields' => array('COUNT(Order.id) AS compras', 'SUM(Order.cantidad) AS entradas', 'SUM(Order.monto) AS monto'),
			'recursive' => -1,
		));
		$this->set('totales', $tot[0][0]);
		$this->set('eventos', $this->Order->Event->find('list', array('fields' => array('Event.id', 'Event.titulo'), 'order' => array('Event.fecha' => 'DESC'))));
		$this->set(compact('evento', 'estado'));
		$this->set('mpListo', LivegoConfig::mercadoPagoListo());
		$this->set('emailListo', LivegoConfig::emailListo());
	}

	/** Diagnóstico de la conexión con Mercado Pago (no muestra el token completo). */
	public function diagnostico()
	{
		$this->autoRender = false;
		App::uses('LivegoHttp', 'Lib/Livego');
		$tok = (string)LivegoConfig::get('MP_ACCESS_TOKEN');
		$out = array(
			'token_prefijo' => substr($tok, 0, 12),
			'token_final' => substr($tok, -10),
			'token_largo' => strlen($tok),
			'token_espacios' => (bool)preg_match('/\s/', $tok),
		);
		try {
			$r = LivegoHttp::request('GET', 'https://api.mercadopago.com/users/me', array('Authorization' => 'Bearer ' . trim($tok)));
			$out['users_me_status'] = $r['code'];
			$out['users_me'] = is_array($r['json']) ? array_intersect_key($r['json'], array_flip(array('id', 'nickname', 'site_id', 'tags', 'status', 'message', 'error', 'code', 'blocked_by'))) : substr($r['raw'], 0, 200);
		} catch (Exception $e) {
			$out['error'] = $e->getMessage();
		}
		$this->response->type('json');
		$this->response->body(json_encode($out, JSON_PRETTY_PRINT));
		return $this->response;
	}

	/** Vuelve a mandar el email con los accesos. */
	public function reenviar($id = null)
	{
		$this->request->allowMethod('post');
		if (LivegoVentas::enviarAccesos($id)) {
			$this->Flash->success('Email reenviado.');
		} else {
			$this->Flash->error('No se pudo mandar el email (¿está configurado Brevo y la compra aprobada?).');
		}
		return $this->redirect($this->referer(array('action' => 'index')));
	}

	/** Consulta a Mercado Pago si una compra pendiente ya se pagó. */
	public function revisar($id = null)
	{
		$this->request->allowMethod('post');
		try {
			$o = LivegoVentas::revisarCompra((int)$id);
			$this->Flash->success($o ? 'Estado actual: ' . $o['estado'] . '.' : 'Mercado Pago no tiene pagos para esta compra.');
		} catch (Exception $e) {
			$this->Flash->error('No se pudo consultar Mercado Pago: ' . $e->getMessage());
		}
		return $this->redirect($this->referer(array('action' => 'index')));
	}

	/** Muestra los accesos de una compra (para mandarlos a mano por WhatsApp). */
	public function ver($id = null)
	{
		$order = $this->Order->find('first', array('conditions' => array('Order.id' => $id), 'recursive' => 0));
		if (!$order) {
			throw new NotFoundException();
		}
		$this->set('order', $order);
		$this->set('accesos', $order['Order']['estado'] === 'aprobado' ? LivegoVentas::accesosDe($order['Order']) : array());
		$this->set('urlIngreso', LivegoConfig::baseUrl() . '/users/login');
		$this->set('urlGracias', LivegoConfig::baseUrl() . '/comprar/gracias/' . $order['Order']['token']);
	}
}
