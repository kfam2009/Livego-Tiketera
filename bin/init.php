<?php
/**
 * Preparación de la base al arrancar (lo corre el contenedor Docker, o a mano: php bin/init.php).
 *
 *  1. Espera a que la base responda.
 *  2. Si no hay tablas, crea la estructura (db/schema.sql) y, si existe, carga db/datos-originales.sql.
 *  3. Si están ADMIN_USER y ADMIN_PASSWORD, crea o actualiza ese administrador.
 *
 * Funciona con MySQL/MariaDB y PostgreSQL. Usa las mismas variables que la app: DB_DRIVER, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS o DATABASE_URL.
 */
define('LIVEGO_ROOT', dirname(__DIR__));
require LIVEGO_ROOT . '/app/Config/database.php';

// Debe coincidir con Security.salt de app/Config/core.php (las claves viejas dependen de él)
$salt = 'sedt3bdsasdww6gdwwsabWwvniR2G0FgaC9XL';
$coreConfig = @file_get_contents(LIVEGO_ROOT . '/app/Config/core.php');
if (getenv('SECURITY_SALT')) {
	$salt = getenv('SECURITY_SALT');
} elseif ($coreConfig && preg_match("/Configure::write\('Security\.salt',\s*'([^']+)'\)/", $coreConfig, $m)) {
	$salt = $m[1];
}

function say($msg) { fwrite(STDOUT, '[init] ' . $msg . PHP_EOL); }

$c = livego_db_config();
$driver = strpos($c['datasource'], 'Sqlite') !== false ? 'sqlite' : (strpos($c['datasource'], 'Postgres') !== false ? 'pgsql' : 'mysql');

$pdo = null;
for ($i = 1; $i <= 30; $i++) {
	try {
		if ($driver === 'sqlite') {
			$pdo = new PDO('sqlite:' . $c['database']);
		} elseif ($driver === 'pgsql') {
			$dsn = 'pgsql:host=' . $c['host'] . ';dbname=' . $c['database'] . (isset($c['port']) ? ';port=' . $c['port'] : '');
			$pdo = new PDO($dsn, $c['login'], $c['password']);
		} else {
			$dsn = 'mysql:host=' . $c['host'] . ';dbname=' . $c['database'] . ';charset=utf8mb4' . (isset($c['port']) ? ';port=' . $c['port'] : '');
			$pdo = new PDO($dsn, $c['login'], $c['password']);
		}
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		break;
	} catch (PDOException $e) {
		say("Esperando la base ($i/30): " . $e->getMessage());
		sleep(2);
	}
}
if (!$pdo) {
	say('No pude conectar a la base. Revisá DB_HOST / DB_USER / DB_PASS / DB_NAME.');
	exit(1);
}

function tablaExiste(PDO $pdo, $driver, $tabla) {
	if ($driver === 'sqlite') {
		$q = $pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=?");
	} elseif ($driver === 'pgsql') {
		$q = $pdo->prepare("SELECT table_name FROM information_schema.tables WHERE table_schema = current_schema() AND table_name = ?");
	} else {
		$q = $pdo->prepare('SHOW TABLES LIKE ?');
	}
	$q->execute(array($tabla));
	return (bool)$q->fetchColumn();
}

function correrSql(PDO $pdo, $archivo) {
	$sql = file_get_contents($archivo);
	// Separa por ";" al final de línea (los datos no tienen ";\n" adentro de textos)
	$partes = preg_split('/;\s*\n/', $sql);
	$n = 0;
	foreach ($partes as $p) {
		$lineas = array_filter(explode("\n", $p), function ($l) { return strpos(ltrim($l), '--') !== 0; });
		$p = trim(implode("\n", $lineas));
		if ($p === '') continue;
		$pdo->exec($p);
		$n++;
	}
	return $n;
}

if (!tablaExiste($pdo, $driver, 'users')) {
	if ($driver === 'sqlite') {
		say('SQLite sin tablas: usá una base ya preparada.');
		exit(1);
	}
	say('Base vacía: creando tablas…');
	correrSql($pdo, LIVEGO_ROOT . ($driver === 'pgsql' ? '/db/schema.pgsql.sql' : '/db/schema.sql'));
	$datos = LIVEGO_ROOT . '/db/datos-originales.sql';
	if ($driver === 'mysql' && file_exists($datos) && getenv('IMPORTAR_DATOS') !== '0') {
		say('Cargando usuarios y suscripciones del sistema viejo…');
		correrSql($pdo, $datos);
	}
	say('Tablas listas.');
} else {
	say('La base ya tiene tablas.');
	// Actualización: columnas para guardar imágenes en la base
	try {
		$pdo->query('SELECT imagen_data FROM events WHERE 1=0');
	} catch (PDOException $e) {
		$tipoTexto = $driver === 'mysql' ? 'MEDIUMTEXT' : 'TEXT';
		$pdo->exec('ALTER TABLE events ADD COLUMN imagen_tipo VARCHAR(50)');
		$pdo->exec('ALTER TABLE events ADD COLUMN imagen_data ' . $tipoTexto);
		say('Agregadas columnas de imagen a events.');
	}
}

$adminUser = getenv('ADMIN_USER');
$adminPass = getenv('ADMIN_PASSWORD');
if ($adminUser && $adminPass) {
	$hash = sha1($salt . $adminPass);
	$q = $pdo->prepare('SELECT id FROM users WHERE username = ?');
	$q->execute(array($adminUser));
	$id = $q->fetchColumn();
	$ahora = date('Y-m-d H:i:s');
	if ($id) {
		$pdo->prepare("UPDATE users SET password = ?, role = 'Admin', activo = 1, modified = ? WHERE id = ?")->execute(array($hash, $ahora, $id));
		say("Admin '$adminUser' actualizado.");
	} else {
		$pdo->prepare("INSERT INTO users (username, password, role, name, lastname, activo, created, modified) VALUES (?, ?, 'Admin', 'Admin', 'Administrador', 1, ?, ?)")->execute(array($adminUser, $hash, $ahora, $ahora));
		say("Admin '$adminUser' creado.");
	}
} else {
	say('ADMIN_USER / ADMIN_PASSWORD no están definidos: no toco el admin.');
}
