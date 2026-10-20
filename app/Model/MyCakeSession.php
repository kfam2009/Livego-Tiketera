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
class MyCakeSession  extends AppModel {
    public $useTable = 'cake_sessions';
}
