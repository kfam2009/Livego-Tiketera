<?php
App::uses('AppController', 'Controller');

/**
 * Espectadores de cada evento: asignar existentes, generar accesos nuevos y quitar.
 */
class SuscriptionsController extends AppController
{
	public $components = array('Paginator', 'Session', 'Flash', 'Accesos');

	/** Máximo de accesos que se generan por tanda */
	const MAX_TANDA = 500;

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = "backend";
		$this->loadModel('User');
		$this->loadModel('Event');
		if ($this->Session->read('Auth.User')) {
			if ($this->Auth->user('role') == 'Admin') {
				$this->Auth->allow();
			}
		}
	}

	public function newsuscription($eventId = null)
	{
		if ($this->request->is('post')) {
			$eventId = $this->request->data['Suscription']['event_id'];
			$userId = $this->request->data['Suscription']['user_id'];
			$yaEsta = $this->Suscription->hasAny(array('Suscription.event_id' => $eventId, 'Suscription.user_id' => $userId));
			$this->Suscription->create();
			if ($yaEsta || $this->Suscription->save(array('Suscription' => array('event_id' => $eventId, 'user_id' => $userId)))) {
				$this->Flash->success(__('Espectador agregado al evento.'));
			} else {
				$this->Flash->error(__('No se pudo agregar. Intentalo de nuevo.'));
			}
			return $this->redirect(array('action' => 'newsuscription', $eventId));
		}

		if (!$this->Event->exists($eventId)) {
			throw new NotFoundException(__('Invalid Event'));
		}
		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId), 'recursive' => -1));
		$this->set('Event', $event);
		$this->set('id', $eventId);
		$suscriptionsList = $this->Suscription->find('list', array('fields' => array('id', 'user_id'), 'conditions' => array('Suscription.event_id' => $eventId)));
		$suscriptions = $this->Suscription->find('all', array(
			"joins" => array(
				array(
					"table" => "users",
					"alias" => "Users",
					"type" => "LEFT",
					"conditions" => array("Suscription.user_id = Users.id")
				)
			),
			'fields' => array('Users.name', 'Users.lastname', 'Users.username', 'Suscription.*'),
			'conditions' => array('Suscription.event_id' => $eventId),
			'order' => array('Suscription.id' => 'DESC')
		));

		$this->set('users', $this->User->find('list', array('fields' => array('id', 'username'), 'order' => array('User.username' => 'ASC'), 'conditions' => array('User.activo' => 1, 'User.role' => 'Espectador', "NOT" => array("User.id" => $suscriptionsList)))));
		$this->set('suscriptions', $suscriptions);
		$this->set('prefijoSugerido', $this->Accesos->prefijo(strtok((string)$event['Event']['titulo'], ' ')));
	}

	/**
	 * Crea N espectadores con usuario y clave al azar y los habilita para el evento.
	 * Las claves se muestran UNA sola vez (en la base quedan cifradas).
	 */
	public function generar($eventId = null)
	{
		$this->request->allowMethod('post');
		if (!$this->Event->exists($eventId)) {
			throw new NotFoundException(__('Invalid Event'));
		}
		$data = isset($this->request->data['Generar']) ? $this->request->data['Generar'] : array();
		$cantidad = max(1, min(self::MAX_TANDA, (int)(isset($data['cantidad']) ? $data['cantidad'] : 1)));
		$prefijo = $this->Accesos->prefijo(isset($data['prefijo']) ? $data['prefijo'] : '');
		$nombre = trim(isset($data['nombre']) ? $data['nombre'] : '');

		$accesos = array();
		$ds = $this->User->getDataSource();
		$ds->begin();
		try {
			for ($i = 0; $i < $cantidad; $i++) {
				$username = $this->Accesos->usuarioUnico($this->User, $prefijo);
				$clave = $this->Accesos->clave(6);
				$this->User->create();
				$ok = $this->User->save(array('User' => array(
					'username' => $username,
					'password' => $clave,
					'role' => 'Espectador',
					'name' => ($nombre !== '' ? $nombre : 'Entrada'),
					'lastname' => ($cantidad > 1 ? '#' . ($i + 1) : ''),
					'activo' => 1,
				)), array('validate' => false));
				if (!$ok) {
					throw new InternalErrorException('No se pudo crear el usuario ' . $username);
				}
				$this->Suscription->create();
				$this->Suscription->save(array('Suscription' => array('event_id' => $eventId, 'user_id' => $this->User->id)));
				$accesos[] = array('usuario' => $username, 'clave' => $clave, 'nombre' => ($nombre !== '' ? $nombre : 'Entrada') . ($cantidad > 1 ? ' #' . ($i + 1) : ''));
			}
			$ds->commit();
		} catch (Exception $e) {
			$ds->rollback();
			$this->Flash->error($e->getMessage());
			return $this->redirect(array('action' => 'newsuscription', $eventId));
		}

		$event = $this->Event->find('first', array('conditions' => array('Event.id' => $eventId), 'recursive' => -1));
		$this->set('Event', $event);
		$this->set('accesos', $accesos);
		$this->set('urlIngreso', Router::url(array('controller' => 'users', 'action' => 'login'), true));
	}

	/**
	 * Quita a un espectador del evento (borra la suscripción, NO el evento ni el usuario).
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod('post', 'delete');
		$sus = $this->Suscription->find('first', array('conditions' => array('Suscription.id' => $id), 'recursive' => -1));
		if (empty($sus)) {
			throw new NotFoundException(__('Suscripción inexistente'));
		}
		if ($this->Suscription->delete($id)) {
			$this->Flash->success(__('Se quitó el espectador de este evento.'));
		} else {
			$this->Flash->error(__('No se pudo quitar. Intentalo de nuevo.'));
		}
		return $this->redirect(array('action' => 'newsuscription', $sus['Suscription']['event_id']));
	}
}
