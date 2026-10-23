<?php
App::uses('LivegoHttp', 'Lib/Livego');
App::uses('LivegoConfig', 'Lib/Livego');

/**
 * Envío de emails con Brevo. Variables: BREVO_API_KEY, MAIL_FROM, MAIL_FROM_NAME.
 */
class LivegoMailer {

	public static function enviar($para, $nombre, $asunto, $html) {
		if (!LivegoConfig::emailListo()) {
			error_log('[livego] Email no enviado: faltan BREVO_API_KEY o MAIL_FROM');
			return false;
		}
		$base = LivegoConfig::get('BREVO_API_BASE', 'https://api.brevo.com/v3');
		$body = array(
			'sender' => array('email' => LivegoConfig::get('MAIL_FROM'), 'name' => LivegoConfig::get('MAIL_FROM_NAME', 'LiveGo')),
			'to' => array(array('email' => $para, 'name' => $nombre ?: $para)),
			'subject' => $asunto,
			'htmlContent' => $html,
		);
		$r = LivegoHttp::request('POST', $base . '/smtp/email', array('api-key' => LivegoConfig::get('BREVO_API_KEY')), $body);
		if ($r['code'] >= 300) {
			CakeLog::write('error', 'Brevo ' . $r['code'] . ': ' . substr($r['raw'], 0, 300));
			error_log('[livego] Brevo ' . $r['code'] . ' al enviar a ' . $para . ': ' . substr($r['raw'], 0, 300));
			return false;
		}
		error_log('[livego] Email enviado por Brevo a ' . $para . ' (' . $r['code'] . ')');
		return true;
	}

	public static function accesos($para, $nombre, $evento, $fecha, array $accesos, $urlIngreso) {
		$e = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
		$filas = '';
		foreach ($accesos as $a) {
			$filas .= '<tr><td style="padding:8px 12px;border:1px solid #ddd">' . $e($a['nombre']) . '</td>'
				. '<td style="padding:8px 12px;border:1px solid #ddd;font-family:monospace;font-size:16px">' . $e($a['usuario']) . '</td>'
				. '<td style="padding:8px 12px;border:1px solid #ddd;font-family:monospace;font-size:16px">' . $e($a['clave']) . '</td></tr>';
		}
		$html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:auto;color:#222">'
			. '<h2 style="margin:0 0 4px">¡Gracias por tu compra!</h2>'
			. '<p style="margin:0 0 16px">Ya podés ver <strong>' . $e($evento) . '</strong>' . ($fecha ? ' (' . $e($fecha) . ')' : '') . '.</p>'
			. '<table style="border-collapse:collapse;margin:0 0 16px"><tr style="background:#f2f2f2"><th style="padding:8px 12px;border:1px solid #ddd;text-align:left">Entrada</th><th style="padding:8px 12px;border:1px solid #ddd;text-align:left">Usuario</th><th style="padding:8px 12px;border:1px solid #ddd;text-align:left">Clave</th></tr>' . $filas . '</table>'
			. '<p><a href="' . $e($urlIngreso) . '" style="display:inline-block;background:#ed383e;color:#fff;padding:10px 18px;border-radius:4px;text-decoration:none">Entrar a ver el evento</a></p>'
			. '<p style="font-size:13px;color:#666">Cada acceso es personal: si se usa en otro dispositivo, se cierra en el anterior.</p></div>';
		return self::enviar($para, $nombre, 'Tu acceso para ' . $evento, $html);
	}
}
