<?php
App::uses('AppModel', 'Model');

/**
 * Ajustes internos guardados en la base (clave de firma de Cloudflare, etc.).
 */
class Setting extends AppModel {
	public $useTable = 'settings';
	public $primaryKey = 'clave';
}
