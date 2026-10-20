<?php
App::uses('AppModel', 'Model');

/**
 * Compras de entradas por Mercado Pago.
 */
class Order extends AppModel {
	public $useTable = 'orders';
	public $belongsTo = array('Event' => array('className' => 'Event', 'foreignKey' => 'event_id', 'fields' => array('Event.id', 'Event.titulo', 'Event.fecha')));
}
