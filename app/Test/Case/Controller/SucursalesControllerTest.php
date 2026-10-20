<?php
App::uses('SucursalesController', 'Controller');

/**
 * SucursalesController Test Case
 */
class SucursalesControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sucursale',
		'app.stockporsucursale',
		'app.producto',
		'app.categoria',
		'app.proveedore',
		'app.marca',
		'app.tipodemedida',
		'app.usersucursale',
		'app.user'
	);

}
