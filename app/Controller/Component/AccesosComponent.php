<?php
App::uses('Component', 'Controller');
App::uses('LivegoCredenciales', 'Lib/Livego');

/**
 * Atajo para usar LivegoCredenciales desde los controladores.
 */
class AccesosComponent extends Component {

	public function clave($largo = 6) {
		return LivegoCredenciales::clave($largo);
	}

	public function prefijo($texto) {
		return LivegoCredenciales::prefijo($texto);
	}

	public function usuarioUnico(Model $User, $prefijo) {
		return LivegoCredenciales::usuarioUnico($User, $prefijo);
	}
}
