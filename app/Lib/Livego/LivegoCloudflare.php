<?php
App::uses('LivegoHttp', 'Lib/Livego');
App::uses('LivegoConfig', 'Lib/Livego');
App::uses('ClassRegistry', 'Utility');

/**
 * Cloudflare Stream: entradas en vivo (RTMPS/SRT desde vMix u OBS), tokens firmados y grabaciones.
 *
 * Variables: CF_ACCOUNT_ID, CF_API_TOKEN, CF_CUSTOMER_CODE (opcional: se detecta solo).
 */
class LivegoCloudflare {

	protected static function api($method, $path, $body = null) {
		$base = LivegoConfig::get('CF_API_BASE', 'https://api.cloudflare.com/client/v4');
		$url = $base . '/accounts/' . LivegoConfig::get('CF_ACCOUNT_ID') . $path;
		$r = LivegoHttp::request($method, $url, array('Authorization' => 'Bearer ' . LivegoConfig::get('CF_API_TOKEN')), $body);
		$j = $r['json'];
		if ($r['code'] >= 300 || !is_array($j) || empty($j['success'])) {
			$msg = 'Cloudflare respondió ' . $r['code'];
			if (is_array($j) && !empty($j['errors'][0]['message'])) {
				$msg .= ': ' . $j['errors'][0]['message'];
			}
			error_log('[livego] ' . $msg);
			throw new RuntimeException($msg);
		}
		return isset($j['result']) ? $j['result'] : null;
	}

	/* ---------- Ajustes guardados en la base ---------- */

	protected static function setting($clave, $valor = null) {
		$S = ClassRegistry::init('Setting');
		if ($valor === null) {
			$row = $S->find('first', array('conditions' => array('Setting.clave' => $clave), 'recursive' => -1));
			return $row ? $row['Setting']['valor'] : null;
		}
		$S->create();
		$S->save(array('Setting' => array('clave' => $clave, 'valor' => $valor, 'modified' => date('Y-m-d H:i:s'))));
		return $valor;
	}

	/* ---------- Entradas en vivo ---------- */

	/**
	 * Crea la entrada en vivo de un evento. Graba automáticamente y exige token para ver.
	 */
	public static function crearEntrada($titulo, $repeticionDias = 7) {
		$body = array(
			'meta' => array('name' => 'Livego: ' . $titulo),
			'recording' => array(
				'mode' => 'automatic',
				'requireSignedURLs' => true,
				'timeoutSeconds' => 60,
			),
			'deleteRecordingAfterDays' => max(30, (int)$repeticionDias + 30),
		);
		$r = self::api('POST', '/stream/live_inputs', $body);
		return array(
			'cf_input_uid' => $r['uid'],
			'cf_rtmps_url' => isset($r['rtmps']['url']) ? $r['rtmps']['url'] : null,
			'cf_rtmps_key' => isset($r['rtmps']['streamKey']) ? $r['rtmps']['streamKey'] : null,
			'cf_srt_url' => isset($r['srt']['url']) ? $r['srt']['url'] : null,
			'cf_srt_id' => isset($r['srt']['streamId']) ? $r['srt']['streamId'] : null,
			'cf_srt_pass' => isset($r['srt']['passphrase']) ? $r['srt']['passphrase'] : null,
		);
	}

	public static function estadoEntrada($uid) {
		$r = self::api('GET', '/stream/live_inputs/' . rawurlencode($uid));
		$estado = isset($r['status']['current']['state']) ? $r['status']['current']['state'] : null;
		return $estado; // 'connected', 'disconnected', null
	}

	public static function grabaciones($uid) {
		$r = self::api('GET', '/stream/live_inputs/' . rawurlencode($uid) . '/videos');
		$out = array();
		foreach ((array)$r as $v) {
			self::detectarCodigo($v);
			$out[] = array(
				'uid' => $v['uid'],
				'estado' => isset($v['status']['state']) ? $v['status']['state'] : null,
				'duracion' => isset($v['duration']) ? $v['duration'] : null,
				'creado' => isset($v['created']) ? $v['created'] : null,
			);
		}
		return $out;
	}

	public static function borrarEntrada($uid) {
		self::api('DELETE', '/stream/live_inputs/' . rawurlencode($uid));
	}

	/** Pide el MP4 de una grabación. Devuelve ['estado' => 'inprogress'|'ready', 'url' => ...]. */
	public static function descarga($videoUid) {
		try {
			$r = self::api('GET', '/stream/' . rawurlencode($videoUid) . '/downloads');
		} catch (RuntimeException $e) {
			$r = null;
		}
		if (empty($r['default'])) {
			$r = self::api('POST', '/stream/' . rawurlencode($videoUid) . '/downloads');
		}
		$d = isset($r['default']) ? $r['default'] : array();
		$url = isset($d['url']) ? $d['url'] : null;
		$codigo = self::codigoCliente();
		if ($url && $codigo) {
			// Los videos protegidos se bajan con un token que permita descargar
			$url = 'https://customer-' . $codigo . '.cloudflarestream.com/' . self::token($videoUid, 2, array('downloadable' => true)) . '/downloads/default.mp4';
		}
		return array(
			'estado' => isset($d['status']) ? $d['status'] : 'inprogress',
			'url' => $url,
			'progreso' => isset($d['percentComplete']) ? $d['percentComplete'] : null,
		);
	}

	/* ---------- Reproducción con token ---------- */

	protected static function detectarCodigo($video) {
		if (self::setting('cf_customer_code') || empty($video['playback']['hls'])) {
			return;
		}
		if (preg_match('#//customer-([a-z0-9]+)\.cloudflarestream\.com#', $video['playback']['hls'], $m)) {
			self::setting('cf_customer_code', $m[1]);
		}
	}

	public static function codigoCliente() {
		$c = LivegoConfig::get('CF_CUSTOMER_CODE');
		if ($c) {
			return preg_replace('/^customer-|\.cloudflarestream\.com.*$/', '', $c);
		}
		$c = self::setting('cf_customer_code');
		if ($c) {
			return $c;
		}
		// Se detecta leyendo cualquier video de la cuenta
		try {
			$r = self::api('GET', '/stream?limit=1');
			foreach ((array)$r as $v) {
				self::detectarCodigo($v);
			}
		} catch (Exception $e) {
		}
		return self::setting('cf_customer_code');
	}

	/** Clave de firma (se crea una sola vez y queda guardada en la base). */
	protected static function claveFirma() {
		$id = self::setting('cf_sign_key_id');
		$pem = self::setting('cf_sign_key_pem');
		if ($id && $pem) {
			return array($id, LivegoConfig::descifrar($pem));
		}
		$r = self::api('POST', '/stream/keys');
		$pemTxt = base64_decode($r['pem']);
		self::setting('cf_sign_key_pem', LivegoConfig::cifrar($pemTxt));
		self::setting('cf_sign_key_id', $r['id']);
		return array($r['id'], $pemTxt);
	}

	protected static function b64url($s) {
		return rtrim(strtr(base64_encode($s), '+/', '-_'), '=');
	}

	/** Token firmado para ver un video o una entrada en vivo (válido unas horas). */
	public static function token($uid, $horas = 6, $extra = array()) {
		list($kid, $pem) = self::claveFirma();
		$header = self::b64url(json_encode(array('alg' => 'RS256', 'kid' => $kid)));
		$payload = self::b64url(json_encode(array_merge(array(
			'sub' => $uid,
			'kid' => $kid,
			'exp' => time() + min(24, $horas) * 3600,
			'nbf' => time() - 60,
		), $extra)));
		$firma = '';
		if (!openssl_sign($header . '.' . $payload, $firma, $pem, OPENSSL_ALGO_SHA256)) {
			throw new RuntimeException('No se pudo firmar el token de video.');
		}
		return $header . '.' . $payload . '.' . self::b64url($firma);
	}

	public static function iframeUrl($uid, $opciones = array()) {
		$codigo = self::codigoCliente();
		$host = $codigo ? 'https://customer-' . $codigo . '.cloudflarestream.com' : 'https://iframe.videodelivery.net';
		$url = $host . '/' . self::token($uid) . ($codigo ? '/iframe' : '');
		$q = array_merge(array('autoplay' => 'true', 'muted' => 'false', 'preload' => 'auto'), $opciones);
		return $url . '?' . http_build_query($q);
	}
}
