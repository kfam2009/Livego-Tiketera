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
class Subscription extends AppModel
{


	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)

	);


}
