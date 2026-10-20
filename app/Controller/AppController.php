<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {


	public $helpers = array('Html', 'Form', 'Session');

	public $components = array(
        'Session',
        'Auth' => array(
            'loginAction'=>array('controller' => 'Users', 'action' => 'login'),
            'loginRedirect' => array('controller' => 'Events', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'Users', 'action' => 'login'),
            'authorize' => array('Controller'),
            // Solo usuarios activos (las entradas devueltas quedan inactivas)
            'authenticate' => array('Form' => array('scope' => array('User.activo' => 1)))
        )
    );


    public function beforeFilter() {
		parent::beforeFilter();
		$this->response->disableCache();
		if ($this->Session->read('Auth.User')) {

			$this->set('rol_label', $this->Auth->user('role'));
			$this->set('username_label', $this->Auth->user('username'));
		}
        $this->Auth->allow('logout','login','forgot','change_password');
    }

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        $this->Session->setFlash(__('No tienes permisos para esta accion'), 'default', array('id' => 'notacces'), 'notacces');
        return false;
    }
}
