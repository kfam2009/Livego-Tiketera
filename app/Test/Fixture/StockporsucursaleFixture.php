<?php
/**
 * Stockporsucursale Fixture
 */
class StockporsucursaleFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'producto_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'sucursale_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'stock' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'producto_id' => 1,
			'sucursale_id' => 1,
			'stock' => 1,
			'created' => '2018-08-18 21:49:53',
			'modified' => '2018-08-18 21:49:53'
		),
	);

}
