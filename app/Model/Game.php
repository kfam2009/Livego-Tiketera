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
class Game extends AppModel {
	//public $name = 'Prismagame';
    public $useTable = 'games';
}
