<?php
/**
 * Cliente HTTP mínimo (JSON) para las APIs externas.
 */
class LivegoHttp {

	public static function request($method, $url, $headers = array(), $body = null, $timeout = 20) {
		$ch = curl_init($url);
		$h = array('Accept: application/json');
		foreach ($headers as $k => $v) {
			$h[] = $k . ': ' . $v;
		}
		if ($body !== null) {
			$h[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_POSTFIELDS, is_string($body) ? $body : json_encode($body));
		}
		curl_setopt_array($ch, array(
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => $h,
			CURLOPT_TIMEOUT => $timeout,
			CURLOPT_CONNECTTIMEOUT => 10,
		));
		$raw = curl_exec($ch);
		$err = curl_error($ch);
		$code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($raw === false) {
			throw new RuntimeException('Sin conexión con ' . parse_url($url, PHP_URL_HOST) . ': ' . $err);
		}
		$json = json_decode($raw, true);
		return array('code' => $code, 'json' => $json, 'raw' => $raw);
	}
}
