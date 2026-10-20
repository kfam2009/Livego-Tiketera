<?php
/**
 * Usuarios y claves fáciles de tipear desde el celular (sin 0/o, 1/l/i).
 */
class LivegoCredenciales {

	const LETRAS = 'abcdefghjkmnpqrstuvwxyz';
	const DIGITOS = '23456789';

	public static function clave($largo = 6) {
		$chars = self::LETRAS . self::DIGITOS;
		$out = '';
		for ($i = 0; $i < $largo; $i++) {
			$out .= $chars[random_int(0, strlen($chars) - 1)];
		}
		return $out;
	}

	public static function prefijo($texto) {
		$texto = strtolower(Inflector::slug((string)$texto, ''));
		$texto = preg_replace('/[^a-z0-9]/', '', $texto);
		$texto = substr($texto, 0, 12);
		return $texto === '' ? 'livego' : $texto;
	}

	public static function usuarioUnico(Model $User, $prefijo) {
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
		throw new InternalErrorException('No se pudo generar un usuario único.');
	}

	/**
	 * Crea $cantidad espectadores habilitados para el evento. Devuelve [['id','usuario','clave','nombre'], ...]
	 */
	public static function crearParaEvento(Model $User, Model $Suscription, $eventId, $cantidad, $prefijo, $nombre = '', $apellidoBase = '') {
		$accesos = array();
		for ($i = 0; $i < $cantidad; $i++) {
			$username = self::usuarioUnico($User, $prefijo);
			$clave = self::clave(6);
			$nom = ($nombre !== '' ? $nombre : 'Entrada');
			$ape = $apellidoBase !== '' ? $apellidoBase : ($cantidad > 1 ? '#' . ($i + 1) : '');
			$User->create();
			$ok = $User->save(array('User' => array(
				'username' => $username,
				'password' => $clave,
				'role' => 'Espectador',
				'name' => $nom,
				'lastname' => $ape,
				'activo' => 1,
			)), array('validate' => false));
			if (!$ok) {
				throw new InternalErrorException('No se pudo crear el usuario ' . $username);
			}
			$Suscription->create();
			$Suscription->save(array('Suscription' => array('event_id' => $eventId, 'user_id' => $User->id)));
			$accesos[] = array('id' => $User->id, 'usuario' => $username, 'clave' => $clave, 'nombre' => $nom . ($cantidad > 1 ? ' #' . ($i + 1) : ''));
		}
		return $accesos;
	}
}
