<?php
App::uses('Stockporsucursale', 'Model');

/**
 * Stockporsucursale Test Case
 */
class StockporsucursaleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.stockporsucursale',
		'app.producto',
		'app.categoria',
		'app.proveedore',
		'app.marca',
		'app.tipodemedida',
		'app.sucursale'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Stockporsucursale = ClassRegistry::init('Stockporsucursale');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Stockporsucursale);

		parent::tearDown();
	}

}
