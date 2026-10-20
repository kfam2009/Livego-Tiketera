<?php
App::uses('Usersucursale', 'Model');

/**
 * Usersucursale Test Case
 */
class UsersucursaleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.usersucursale',
		'app.user',
		'app.sucursale',
		'app.stockporsucursale',
		'app.producto',
		'app.categoria',
		'app.proveedore',
		'app.marca',
		'app.tipodemedida'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Usersucursale = ClassRegistry::init('Usersucursale');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Usersucursale);

		parent::tearDown();
	}

}
