<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models/', '/next/path/to/models/'),
 *     'Model/Behavior'            => array('/path/to/behaviors/', '/next/path/to/behaviors/'),
 *     'Model/Datasource'          => array('/path/to/datasources/', '/next/path/to/datasources/'),
 *     'Model/Datasource/Database' => array('/path/to/databases/', '/next/path/to/database/'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions/', '/next/path/to/sessions/'),
 *     'Controller'                => array('/path/to/controllers/', '/next/path/to/controllers/'),
 *     'Controller/Component'      => array('/path/to/components/', '/next/path/to/components/'),
 *     'Controller/Component/Auth' => array('/path/to/auths/', '/next/path/to/auths/'),
 *     'Controller/Component/Acl'  => array('/path/to/acls/', '/next/path/to/acls/'),
 *     'View'                      => array('/path/to/views/', '/next/path/to/views/'),
 *     'View/Helper'               => array('/path/to/helpers/', '/next/path/to/helpers/'),
 *     'Console'                   => array('/path/to/consoles/', '/next/path/to/consoles/'),
 *     'Console/Command'           => array('/path/to/commands/', '/next/path/to/commands/'),
 *     'Console/Command/Task'      => array('/path/to/tasks/', '/next/path/to/tasks/'),
 *     'Lib'                       => array('/path/to/libs/', '/next/path/to/libs/'),
 *     'Locale'                    => array('/path/to/locales/', '/next/path/to/locales/'),
 *     'Vendor'                    => array('/path/to/vendors/', '/next/path/to/vendors/'),
 *     'Plugin'                    => array('/path/to/plugins/', '/next/path/to/plugins/'),
 * ));
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); // Loads a single plugin named DebugKit
 */

/**
 * To prefer app translation over plugin translation, you can set
 *
 * Configure::write('I18n.preferApp', true);
 */

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

// Hora de Argentina para comparar inicio/fin de los eventos (se puede cambiar con TZ)
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'America/Argentina/Buenos_Aires');

// PHP 8 compat: strftime polyfill
require_once ROOT . DS . 'vendors' . DS . 'strftime' . DS . 'php-8.1-strftime.php';

/**
 * URL de la imagen de un evento.
 * Las imágenes nuevas se guardan en la base (imagen = "db:xxxx") para no depender del disco del servidor;
 * las viejas siguen saliendo de app/webroot/img/eventos.
 */
function livego_imagen_url($event) {
	$img = isset($event['imagen']) ? (string)$event['imagen'] : '';
	if (strpos($img, 'db:') === 0) {
		return Router::url(array('controller' => 'events', 'action' => 'imagen', $event['id'], '?' => array('v' => substr($img, 3))));
	}
	return 'eventos/' . ($img !== '' ? $img : 'banner.jpeg');
}

/**
 * Convierte el link que pega el admin en uno que se pueda embeber en la página.
 * YouTube (watch, youtu.be, /live/, /shorts/, canal), Vimeo, Facebook, Twitch y Kick.
 * Si no lo reconoce, lo devuelve tal cual (puede ser ya un link de "embed" o un reproductor propio).
 */
function livego_embed_url($link, $host = null) {
	$link = trim((string)$link);
	if ($link === '') {
		return '';
	}
	if (!preg_match('#^https?://#i', $link)) {
		$link = 'https://' . $link;
	}
	$p = parse_url($link);
	$h = isset($p['host']) ? strtolower(preg_replace('/^(www\.|m\.)/', '', $p['host'])) : '';
	$path = isset($p['path']) ? $p['path'] : '';
	parse_str(isset($p['query']) ? $p['query'] : '', $q);

	// YouTube
	if ($h === 'youtu.be') {
		$id = trim($path, '/');
		return 'https://www.youtube.com/embed/' . rawurlencode($id) . '?autoplay=1&rel=0';
	}
	if ($h === 'youtube.com' || $h === 'youtube-nocookie.com' || $h === 'music.youtube.com') {
		if (strpos($path, '/embed/') === 0) {
			return $link;
		}
		if (!empty($q['v'])) {
			return 'https://www.youtube.com/embed/' . rawurlencode($q['v']) . '?autoplay=1&rel=0';
		}
		if (preg_match('#^/(live|shorts)/([^/?]+)#', $path, $m)) {
			return 'https://www.youtube.com/embed/' . rawurlencode($m[2]) . '?autoplay=1&rel=0';
		}
		if (preg_match('#^/channel/([^/?]+)#', $path, $m)) {
			return 'https://www.youtube.com/embed/live_stream?channel=' . rawurlencode($m[1]) . '&autoplay=1';
		}
	}
	// Vimeo
	if ($h === 'vimeo.com' && preg_match('#^/(?:event/)?(\d+)#', $path, $m)) {
		return (strpos($path, '/event/') === 0)
			? 'https://vimeo.com/event/' . $m[1] . '/embed'
			: 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
	}
	// Facebook (videos y vivos)
	if (in_array($h, array('facebook.com', 'fb.watch', 'web.facebook.com'), true) && strpos($path, '/plugins/') !== 0) {
		return 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($link) . '&show_text=false&autoplay=true';
	}
	// Twitch (necesita el dominio donde se muestra)
	if ($h === 'twitch.tv' && preg_match('#^/([A-Za-z0-9_]+)/?$#', $path, $m)) {
		$parent = $host ?: (isset($_SERVER['HTTP_HOST']) ? preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST']) : 'localhost');
		return 'https://player.twitch.tv/?channel=' . rawurlencode($m[1]) . '&parent=' . rawurlencode($parent);
	}
	// Kick
	if ($h === 'kick.com' && preg_match('#^/([A-Za-z0-9_-]+)/?$#', $path, $m)) {
		return 'https://player.kick.com/' . rawurlencode($m[1]);
	}
	return $link;
}
