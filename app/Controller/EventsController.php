<?php
App::uses('AppController', 'Controller');
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
	public function shows()
	{

		// Eventos activos y no terminados en los que está habilitado este espectador
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
					'Event.fechafin >=' => date("Y-m-d H:i:s")
				),
				'fields' => array('Event.id', 'Event.titulo', 'Event.descripcion', 'Event.imagen', 'Event.horario', 'Event.fecha', 'Event.fechafin', 'Event.active'),
				'order' => array('Event.fecha' => 'ASC'),
				'recursive' => -1
			));
			foreach ($rows as $r) {
				$events[] = array('Events' => $r['Event']);
			}
		}
		$suscriptions = $events;
		$this->set('events', $suscriptions);
	}

	public function view($id=null)
	{
		$suscriptions= $this->Suscription->find('first',array('conditions'=>array('Suscription.user_id' => $this->Auth->user('id'), 'Suscription.event_id'=>$id )));

		if (!$this->Event->exists($id) || (empty($suscriptions))) {
			throw new NotFoundException(__('Invalid Event'));
		}
		$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
		$this->request->data = $this->Event->find('first', $options);
		$ahora = date("Y-m-d H:i:s");
		$ev = $this->request->data['Event'];
		if (!$ev['active'] || ($ev['fecha'] > $ahora) || (!empty($ev['fechafin']) && $ev['fechafin'] < $ahora)) {
			throw new NotFoundException(__('Evento Inactivo'));
		}
		$this->set('event', $this->request->data);
		$this->set('id', $id);
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

			if ($this->Event->save($this->request->data)) {
				$this->Flash->success(__('El evento se Ha creado con exito.'));
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

			if ($this->Event->save($this->request->data)) {
				$this->Flash->success(__('El Evento se ha modificado con exito.'));
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
