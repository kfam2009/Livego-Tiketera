<?php
/**
 * Conexión a la base de datos.
 * Se configura con variables de entorno (Render, Docker, hosting) para no dejar claves en el código.
 *   DB_DRIVER   mysql (por defecto) | postgres | sqlite
 *   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
 *   DATABASE_URL  (opcional, ej: mysql://usuario:clave@host:3306/base) — tiene prioridad
 */
function livego_env($key, $default = null) {
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

function livego_db_config() {
	$driver = livego_env('DB_DRIVER', 'mysql');
	$host = livego_env('DB_HOST', 'localhost');
	$port = livego_env('DB_PORT', null);
	$name = livego_env('DB_NAME', 'nuvelar_livego');
	$user = livego_env('DB_USER', 'root');
	$pass = livego_env('DB_PASS', '');

	$url = livego_env('DATABASE_URL');
	if ($url) {
		$p = parse_url($url);
		$scheme = isset($p['scheme']) ? $p['scheme'] : 'mysql';
		$driver = (strpos($scheme, 'postgres') === 0) ? 'postgres' : 'mysql';
		$host = isset($p['host']) ? $p['host'] : $host;
		$port = isset($p['port']) ? $p['port'] : $port;
		$user = isset($p['user']) ? urldecode($p['user']) : $user;
		$pass = isset($p['pass']) ? urldecode($p['pass']) : $pass;
		$name = isset($p['path']) ? ltrim($p['path'], '/') : $name;
	}

	$sources = array('mysql' => 'Database/Mysql', 'postgres' => 'Database/Postgres', 'sqlite' => 'Database/Sqlite');
	$config = array(
		'datasource' => isset($sources[$driver]) ? $sources[$driver] : 'Database/Mysql',
		'persistent' => false,
		'host' => $host,
		'login' => $user,
		'password' => $pass,
		'database' => $name,
		'prefix' => '',
		'encoding' => 'utf8',
	);
	if ($port) {
		$config['port'] = $port;
	}
	if ($driver === 'sqlite') {
		unset($config['encoding']);
	}
	return $config;
}

class DATABASE_CONFIG {
	public $default = array();

	public function __construct() {
		$this->default = livego_db_config();
	}
}
