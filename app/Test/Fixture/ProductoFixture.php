<?php
/**
 * Producto Fixture
 */
class ProductoFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'categoria_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'proveedore_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'marca_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'nombre' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'codigo' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'costoneto' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2', 'unsigned' => false),
		'iva' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2', 'unsigned' => false),
		'descripcion' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'tipodemedida_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'medida' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'disponible' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'imagen1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imagen2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imagen3' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'imagen4' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'descarga' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'descarga2' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'youtube' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
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
			'categoria_id' => 1,
			'proveedore_id' => 1,
			'marca_id' => 1,
			'nombre' => 'Lorem ipsum dolor sit amet',
			'codigo' => 'Lorem ipsum dolor sit amet',
			'costoneto' => 1,
			'iva' => 1,
			'descripcion' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'tipodemedida_id' => 1,
			'medida' => 'Lorem ipsum dolor sit amet',
			'disponible' => 1,
			'imagen1' => 'Lorem ipsum dolor sit amet',
			'imagen2' => 'Lorem ipsum dolor sit amet',
			'imagen3' => 'Lorem ipsum dolor sit amet',
			'imagen4' => 'Lorem ipsum dolor sit amet',
			'descarga' => 'Lorem ipsum dolor sit amet',
			'descarga2' => 'Lorem ipsum dolor sit amet',
			'youtube' => 'Lorem ipsum dolor sit amet',
			'created' => '2018-08-18 21:46:53',
			'modified' => '2018-08-18 21:46:53'
		),
	);

}
