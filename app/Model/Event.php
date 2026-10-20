<?php
App::uses('AppModel', 'Model');
/**
 * Producto Model
 *
 * @property Categoria $Categoria
 * @property Proveedore $Proveedore
 * @property Marca $Marca
 * @property Tipodemedida $Tipodemedida
 * @property Stockporsucursale $Stockporsucursale
 */
class Event extends AppModel
{

	public $useTable = 'events';

	public $hasMany = array(
		'Suscription' => array(
			'className' => 'Suscription',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


}
