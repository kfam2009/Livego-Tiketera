<?php
App::uses('Tipodemedida', 'Model');

/**
 * Tipodemedida Test Case
 */
class TipodemedidaTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.tipodemedida',
		'app.producto',
		'app.categoria',
		'app.proveedore',
		'app.marca',
		'app.stockporsucursale',
		'app.sucursale',
		'app.usersucursale'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Tipodemedida = ClassRegistry::init('Tipodemedida');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tipodemedida);

		parent::tearDown();
	}

}
