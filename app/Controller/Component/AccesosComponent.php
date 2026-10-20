<?php
App::uses('Component', 'Controller');

/**
 * Genera usuarios y claves fáciles de tipear desde el celular.
 * Sin letras ni números que se confunden (0/o, 1/l/i).
 */
class AccesosComponent extends Component {

	const LETRAS = 'abcdefghjkmnpqrstuvwxyz';
	const DIGITOS = '23456789';

	public function clave($largo = 6) {
		$chars = self::LETRAS . self::DIGITOS;
		$out = '';
		for ($i = 0; $i < $largo; $i++) {
			$out .= $chars[random_int(0, strlen($chars) - 1)];
		}
		return $out;
	}

	public function prefijo($texto) {
		$texto = strtolower(Inflector::slug((string)$texto, ''));
		$texto = preg_replace('/[^a-z0-9]/', '', $texto);
		$texto = substr($texto, 0, 12);
		return $texto === '' ? 'livego' : $texto;
	}

	/**
	 * Usuario único: prefijo + 4 dígitos (ej: final4827).
	 */
	public function usuarioUnico(Model $User, $prefijo) {
		for ($intento = 0; $intento < 50; $intento++) {
			$digitos = $intento < 30 ? 4 : 6;
			$num = '';
			for ($i = 0; $i < $digitos; $i++) {
				$num .= self::DIGITOS[random_int(0, strlen(self::DIGITOS) - 1)];
			}
			$username = $prefijo . $num;
			if (!$User->hasAny(array('User.username' => $username))) {
				return $username;
			}
		}
		throw new InternalErrorException('No se pudo generar un usuario único. Probá con otro prefijo.');
	}
}
