<?php
/**
 * Lectura de configuración por variables de entorno.
 */
class LivegoConfig {

	public static function get($key, $default = null) {
		foreach (array($key, 'REDIRECT_' . $key) as $k) {
			$v = getenv($k);
			if ($v === false && isset($_SERVER[$k])) {
				$v = $_SERVER[$k];
			}
			if ($v !== false && $v !== '') {
				return $v;
			}
		}
		return $default;
	}

	/** URL pública del sitio (Render la define en RENDER_EXTERNAL_URL). */
	public static function baseUrl() {
		$u = self::get('APP_URL', self::get('RENDER_EXTERNAL_URL'));
		if ($u) {
			return rtrim($u, '/');
		}
		return rtrim(Router::url('/', true), '/');
	}

	public static function cloudflareListo() {
		return self::get('CF_ACCOUNT_ID') && self::get('CF_API_TOKEN');
	}

	public static function mercadoPagoListo() {
		return (bool)self::get('MP_ACCESS_TOKEN');
	}

	public static function emailListo() {
		return self::get('BREVO_API_KEY') && self::get('MAIL_FROM');
	}

	/** Cifrado reversible para guardar las claves generadas de una compra (para mostrarlas y reenviarlas). */
	public static function cifrar($texto) {
		$key = hash('sha256', 'livego|' . Configure::read('Security.salt'), true);
		$iv = random_bytes(16);
		return base64_encode($iv . openssl_encrypt($texto, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv));
	}

	public static function descifrar($dato) {
		$bin = base64_decode((string)$dato, true);
		if ($bin === false || strlen($bin) < 17) {
			return null;
		}
		$key = hash('sha256', 'livego|' . Configure::read('Security.salt'), true);
		$r = openssl_decrypt(substr($bin, 16), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, substr($bin, 0, 16));
		return $r === false ? null : $r;
	}
}
