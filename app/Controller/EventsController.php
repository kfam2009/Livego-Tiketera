<?php
App::uses('AppController', 'Controller');
App::uses('LivegoConfig', 'Lib/Livego');
App::uses('LivegoCloudflare', 'Lib/Livego');
/**
 * Events Controller
 *
 * @property Producto $Producto
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class EventsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Session', 'Flash');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = "backend";
		$this->loadModel('User');
		$this->loadModel('Suscription');
		$this->Auth->allow('imagen');
		if ($this->Session->read('Auth.User')) {
			if ($this->Auth->user('role') == 'Admin') {
				$this->Auth->allow();
			}else{
				$this->Auth->allow(array('shows','view'));
				// Si la entrada se dio de baja (devolución), se corta la sesión
				if (!$this->User->hasAny(array('User.id' => $this->Auth->user('id'), 'User.activo' => 1))) {
					$this->Flash->error('Tu acceso ya no está activo.');
					return $this->redirect($this->Auth->logout());
				}
			}
		}
	}
	/**
	 * Toma la imagen subida en el formulario y la guarda en la base (base64).
	 */
	protected function _procesarImagen()
	{
		$f = isset($this->request->data['Event']['imagen']) ? $this->request->data['Event']['imagen'] : null;
		if (!is_array($f) || !isset($f['error']) || $f['error'] === UPLOAD_ERR_NO_FILE) {
			unset($this->request->data['Event']['imagen']);
			return;
		}
		if ($f['error'] !== UPLOAD_ERR_OK || $f['size'] > 5 * 1024 * 1024) {
			unset($this->request->data['Event']['imagen']);
			$this->Flash->error('La imagen no se pudo subir (máximo 5 MB).');
			return;
		}
		$bin = file_get_contents($f['tmp_name']);
		$info = @getimagesizefromstring($bin);
		$tipos = array(IMAGETYPE_JPEG => 'image/jpeg', IMAGETYPE_PNG => 'image/png', IMAGETYPE_GIF => 'image/gif', IMAGETYPE_WEBP => 'image/webp');
		if (!$info || !isset($tipos[$info[2]])) {
			unset($this->request->data['Event']['imagen']);
			$this->Flash->error('La imagen tiene que ser JPG, PNG, GIF o WEBP.');
			return;
		}
		$this->request->data['Event']['imagen'] = 'db:' . substr(md5($bin), 0, 10);
		$this->request->data['Event']['imagen_tipo'] = $tipos[$info[2]];
		$this->request->data['Event']['imagen_data'] = base64_encode($bin);
	}

	/**
	 * Sirve la imagen de un evento guardada en la base.
	 */
	public function imagen($id = null)
	{
		$this->autoRender = false;
		$ev = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'fields' => array('Event.imagen_tipo', 'Event.imagen_data'), 'recursive' => -1));
		if (empty($ev['Event']['imagen_data'])) {
			throw new NotFoundException();
		}
		$this->response->type($ev['Event']['imagen_tipo'] ?: 'image/jpeg');
		$this->response->body(base64_decode($ev['Event']['imagen_data']));
		$this->response->cache('-1 minute', '+30 days');
		return $this->response;
	}

	/**
	 *    Metodos para el  Espectador
	 *
	 * */
	/** Hasta cuándo puede entrar el espectador (fin del evento + días de repetición si la transmisión es propia). */
	protected function _hasta(array $ev)
	{
		$fin = !empty($ev['fechafin']) ? strtotime($ev['fechafin']) : PHP_INT_MAX;
		if (isset($ev['fuente']) && $ev['fuente'] === 'cloudflare' && !empty($ev['repeticion_dias'])) {
			$fin += (int)$ev['repeticion_dias'] * 86400;
		}
		return $fin;
	}

	public function shows()
	{
		// Eventos activos (en curso, por venir o con repetición disponible) en los que está habilitado este espectador
		$eventIds = $this->Suscription->find('list', array(
			'fields' => array('Suscription.id', 'Suscription.event_id'),
			'conditions' => array('Suscription.user_id' => $this->Auth->user('id'))
		));
		$events = array();
		if (!empty($eventIds)) {
			$rows = $this->Event->find('all', array(
				'conditions' => array(
					'Event.id' => array_values(array_unique($eventIds)),
					'Event.active' => 1,
				),
				'fields' => array('Event.id', 'Event.titulo', 'Event.descripcion', 'Event.imagen', 'Event.horario', 'Event.fecha', 'Event.fechafin', 'Event.active', 'Event.fuente', 'Event.repeticion_dias'),
				'order' => array('Event.fecha' => 'ASC'),
				'recursive' => -1
			));
			foreach ($rows as $r) {
				if ($this->_hasta($r['Event']) >= time()) {
					$events[] = array('Events' => $r['Event']);
				}
			}
		}
		$this->set('events', $events);
	}

	public function view($id = null)
	{
		$suscriptions = $this->Suscription->find('first', array('conditions' => array('Suscription.user_id' => $this->Auth->user('id'), 'Suscription.event_id' => $id)));
		if (!$this->Event->exists($id) || (empty($suscriptions) && $this->Auth->user('role') !== 'Admin')) {
			throw new NotFoundException(__('Invalid Event'));
		}
		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'recursive' => -1));
		$ev = $event['Event'];
		$ahora = time();
		$esAdmin = $this->Auth->user('role') === 'Admin';
		if (!$esAdmin && (!$ev['active'] || strtotime($ev['fecha']) > $ahora || $this->_hasta($ev) < $ahora)) {
			throw new NotFoundException(__('Evento Inactivo'));
		}
		$modo = 'link';
		$player = null;
		$grabaciones = array();
		$error = null;
		if ($ev['fuente'] === 'cloudflare') {
			if (empty($ev['cf_input_uid'])) {
				$error = 'La transmisión todavía no está lista.';
			} else {
				try {
					$terminado = !empty($ev['fechafin']) && strtotime($ev['fechafin']) < $ahora;
					if ($terminado) {
						$modo = 'repeticion';
						$grabaciones = Cache::remember('cf_rec_' . $ev['cf_input_uid'], function () use ($ev) {
							return LivegoCloudflare::grabaciones($ev['cf_input_uid']);
						}, 'livego');
						$grabaciones = array_values(array_filter($grabaciones, function ($g) { return $g['estado'] === 'ready'; }));
						$elegida = isset($this->request->query['g']) ? $this->request->query['g'] : null;
						$uid = null;
						foreach ($grabaciones as $g) {
							if ($g['uid'] === $elegida) { $uid = $g['uid']; }
						}
						if (!$uid && $grabaciones) {
							$uid = $grabaciones[0]['uid'];
						}
						$player = $uid ? LivegoCloudflare::iframeUrl($uid, array('autoplay' => 'false')) : null;
						if (!$player) {
							$error = 'La repetición todavía se está procesando. Probá en unos minutos.';
						}
						$this->set('grabacionActual', $uid);
					} else {
						$modo = 'vivo';
						$player = LivegoCloudflare::iframeUrl($ev['cf_input_uid']);
					}
				} catch (Exception $e) {
					CakeLog::write('error', 'Cloudflare view ' . $id . ': ' . $e->getMessage());
					$error = 'No pudimos cargar el video. Recargá la página en unos segundos.';
				}
			}
		} else {
			$player = livego_embed_url($ev['link']);
		}
		$this->set(compact('event', 'id', 'modo', 'player', 'grabaciones', 'error'));
	}

	/** Normaliza campos del formulario antes de guardar. */
	protected function _normalizar()
	{
		$d =& $this->request->data['Event'];
		if (empty($d['fuente']) || !in_array($d['fuente'], array('link', 'cloudflare'), true)) {
			$d['fuente'] = 'link';
		}
		foreach (array('precio', 'cupo') as $k) {
			if (isset($d[$k]) && trim((string)$d[$k]) === '') {
				$d[$k] = null;
			}
		}
		if (isset($d['repeticion_dias']) && trim((string)$d['repeticion_dias']) === '') {
			$d['repeticion_dias'] = 0;
		}
		// Datos de la transmisión: nunca desde el formulario
		foreach (array('cf_input_uid', 'cf_rtmps_url', 'cf_rtmps_key', 'cf_srt_url', 'cf_srt_id', 'cf_srt_pass') as $k) {
			unset($d[$k]);
		}
	}

	/** Si el evento usa transmisión propia y todavía no la tiene, la crea en Cloudflare. */
	protected function _prepararTransmision($id)
	{
		$ev = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'recursive' => -1, 'fields' => array('Event.id', 'Event.titulo', 'Event.fuente', 'Event.cf_input_uid', 'Event.repeticion_dias')));
		$ev = $ev['Event'];
		if ($ev['fuente'] !== 'cloudflare' || !empty($ev['cf_input_uid'])) {
			return;
		}
		if (!LivegoConfig::cloudflareListo()) {
			$this->Flash->error('El evento se guardó, pero falta configurar Cloudflare (CF_ACCOUNT_ID y CF_API_TOKEN en Render) para crear la transmisión.');
			return;
		}
		try {
			$datos = LivegoCloudflare::crearEntrada($ev['titulo'], (int)$ev['repeticion_dias']);
			$this->Event->id = $id;
			$this->Event->save(array('Event' => $datos), array('validate' => false, 'callbacks' => false));
		} catch (Exception $e) {
			CakeLog::write('error', 'Cloudflare crearEntrada: ' . $e->getMessage());
			$this->Flash->error('El evento se guardó, pero no se pudo crear la transmisión en Cloudflare: ' . $e->getMessage());
		}
	}

	/** Admin: datos para vMix / OBS, estado de la señal y grabaciones. */
	public function transmision($id = null)
	{
		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'recursive' => -1));
		if (!$event) {
			throw new NotFoundException(__('Invalid Event'));
		}
		$ev = $event['Event'];
		if ($this->request->is('post')) {
			$this->_prepararTransmision($id);
			return $this->redirect(array('action' => 'transmision', $id));
		}
		$estado = null;
		$grabaciones = array();
		$error = null;
		if ($ev['fuente'] === 'cloudflare' && !empty($ev['cf_input_uid'])) {
			try {
				$estado = LivegoCloudflare::estadoEntrada($ev['cf_input_uid']);
				$grabaciones = LivegoCloudflare::grabaciones($ev['cf_input_uid']);
				Cache::write('cf_rec_' . $ev['cf_input_uid'], $grabaciones, 'livego');
			} catch (Exception $e) {
				$error = $e->getMessage();
			}
		}
		$this->set(compact('event', 'estado', 'grabaciones', 'error'));
		$this->set('cfListo', LivegoConfig::cloudflareListo());
		$this->set('urlCompra', LivegoConfig::baseUrl() . '/comprar/' . $id);
	}

	/** Admin: pide/descarga el MP4 de una grabación. */
	public function descarga($id = null, $videoUid = null)
	{
		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $id), 'recursive' => -1, 'fields' => array('Event.cf_input_uid')));
		if (!$event || !preg_match('/^[a-f0-9]{32}$/', (string)$videoUid)) {
			throw new NotFoundException();
		}
		try {
			$d = LivegoCloudflare::descarga($videoUid);
		} catch (Exception $e) {
			$this->Flash->error('No se pudo pedir la descarga: ' . $e->getMessage());
			return $this->redirect(array('action' => 'transmision', $id));
		}
		if ($d['estado'] === 'ready' && $d['url']) {
			return $this->redirect($d['url']);
		}
		$this->Flash->success('Cloudflare está preparando el MP4' . ($d['progreso'] !== null ? ' (' . round($d['progreso']) . '%)' : '') . '. Volvé a tocar "Bajar MP4" en un rato.');
		return $this->redirect(array('action' => 'transmision', $id));
	}

	/**
	 * Metodos del Admin
	 *
	 * @return void
	 */
	public function index()
	{
		$this->Event->recursive = 0;
		$this->Paginator->settings = array(
			'conditions' => array('Event.active' => true),
			'order' => array('Event.fecha' => 'ASC'),
			'limit' => 1000
		);
		$this->set('events', $this->Paginator->paginate());
	}


	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function add()
	{

		if ($this->request->is(array('post', 'put'))) {
			$this->_procesarImagen();
			$this->_normalizar();

			if ($this->Event->save($this->request->data)) {
				$this->_prepararTransmision($this->Event->id);
				$this->Flash->success(__('El evento se creó con éxito.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('El evento no se pudo crear. Intentelo nuevamente.'));
			}
		}
	}
	public function newsuscription($eventId = null)
	{
		if ($this->request->is('post')) {
			$this->Suscriprion->create();

			if ($this->User->saveAll($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'newsuscription',$this->request->data['Event']['id']));
			} else {
				$this->Flash->error(__('The Event could not be saved. Please, try again.'));
			}
		} else {
			if (!$this->Event->exists($eventId)) {
				throw new NotFoundException(__('Invalid Event'));
			}
			$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $eventId));
			$this->request->data = $this->Event->find('first', $options);
			$this->set('Event', $this->request->data);
			$this->set('id', $eventId);
			$suscriptions = $this->Suscription->find('list', array('fields' => array('id', 'id'), 'conditions' => array('Suscription.event_id' => $eventId)));
			$this->set('users',  $this->User->find('list', array('fields' => array('id', 'username'), 'conditions' => array('User.activo' => 1, 'User.role' => 'Espectador', "NOT" => array("User.id" => $suscriptions)))));
			$this->set('suscriptions', $suscriptions);
		}
	}

	public function edit($id = null)
	{

		if ($this->request->is(array('post', 'put'))) {
			$this->_procesarImagen();
			$this->_normalizar();

			if ($this->Event->save($this->request->data)) {
				$this->_prepararTransmision($this->Event->id);
				$this->Flash->success(__('El evento se modificó con éxito.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('El Evento no se pudo modificar. Intentelo nuevamente.'));
			}
		} else {
			if (!$this->Event->exists($id)) {
				throw new NotFoundException(__('Invalid Event'));
			}
			$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
			$this->request->data = $this->Event->find('first', $options);
			$this->set('Event', $this->request->data);
			$this->set('id', $id);
		}
	}

	/**
	 * delete method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null)
	{
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid Event'));
		}

		if ($this->Event->delete()) {
			$this->Flash->success(__('The Event has been deleted.'));
		} else {
			$this->Flash->error(__('The Event could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	public function deleteAll($id = null)
	{

		if ($id == null) {
			throw new NotFoundException(__('Invalid GAME ID'));
		}

		if ($this->Event->deleteAll(array('Event.game_id' => $id), false)) {
			$this->Flash->success(__('Todos Los Puntajes se eliminaron con exito.'));
		} else {
			$this->Flash->error(__('The Scores could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
