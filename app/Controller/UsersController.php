<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 * @property FlashComponent $Flash
 */
class UsersController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator', 'Session', 'Flash', 'Accesos');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = "backend";
		$this->loadModel("Event");

		if ($this->Session->read('Auth.User')) {

			if ($this->Auth->user('role') == 'Admin') {
				$this->Auth->allow();
			}else{
				$this->Auth->allow('login','checksession','logout');
			}
			$this->set('eventos', $this->Event->find('all', array('fields' => array('id', 'titulo'))));
		}
		$this->Auth->allow('checksession','logout');
	}

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{
		$this->User->recursive = -1;
		$this->Paginator->settings = array('order' => array('User.id' => 'DESC'), 'limit' => 50);
		$this->set('users', $this->Paginator->paginate());
	}

	public function checksession()
	{
		$this->layout = false;
		$response['code'] = 404;
		if ($this->Session->read('Auth.User')) {
			$response['code']= 200;
		}
		echo json_encode($response);
	}

	public function login()
	{
		$this->layout = 'auth';
		if ($this->request->is('post')) {
			$this->Auth->logout();
			if ($this->Auth->login()) {
				if ($this->Session->read('Auth.User')) {
					if ($this->Auth->user('role') == 'Admin') {
						return $this->redirect($this->Auth->redirect());
					}
					if ($this->Auth->user('role') == 'Espectador') {
						$this->loadModel("MyCakeSession");
						$user = $this->User->find('first', array('conditions' => array('id' => $this->Session->read('Auth.User.id'))));
						if (!empty($user['User']['cakesessionid'])) {
							if ($this->MyCakeSession->exists($user['User']['cakesessionid'])) {
								$this->MyCakeSession->delete($user['User']['cakesessionid']);
							}
						}
						$this->User->id = $this->Session->read('Auth.User.id');
						$this->User->saveField('cakesessionid', CakeSession::id());
						return $this->redirect(array('controller' => 'Events', 'action' => 'shows'));
					} else {
						$this->Session->setFlash(__('Las Credenciales Ingresadas No cuentan Con permiso Para esta Sucursal'));
						$this->logout();
					}
				}
				return $this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
			$this->Session->setFlash(__('Las Credenciales Ingresadas son Incorrectas'));
		}
	}

	public function forgot()
	{
		$this->layout = 'auth';
	}

	public function change_password()
	{
		$this->layout = 'backend';
	}

	public function logout()
	{
		$this->layout = false;
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null)
	{
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add()
	{
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->saveAll($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		}
	}


	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null)
	{


		if ($this->request->is(array('post', 'put'))) {
			// Si la clave queda vacía, no se cambia
			if (isset($this->request->data['User']['password']) && $this->request->data['User']['password'] === '') {
				unset($this->request->data['User']['password']);
			}
			if ($this->User->save($this->request->data)) {
				$this->Flash->success(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The user could not be saved. Please, try again.'));
			}
		} else {
			if (!$this->User->exists($id)) {
				throw new NotFoundException(__('Invalid user'));
			}
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
			unset($this->request->data['User']['password']);
		}
	}

	/**
	 * Genera una clave nueva para un espectador y la muestra una sola vez.
	 */
	public function resetpass($id = null)
	{
		$this->request->allowMethod('post');
		$user = $this->User->find('first', array('conditions' => array('User.id' => $id), 'recursive' => -1));
		if (empty($user)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($user['User']['role'] == 'Admin') {
			throw new ForbiddenException('La clave del admin se cambia con ADMIN_PASSWORD.');
		}
		$clave = $this->Accesos->clave(6);
		$this->User->id = $id;
		$this->User->saveField('password', $clave);
		$this->Flash->success('Nueva clave para ' . $user['User']['username'] . ': ' . $clave . '  (anotala, no se vuelve a mostrar)');
		return $this->redirect(array('action' => 'index'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->User->delete()) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
