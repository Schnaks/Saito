<?php

// Load Composer autoload.
require ROOT . DS .  'vendor/autoload.php';

// Remove and re-prepend CakePHP's autoloader as Composer thinks it is the
// most important.
// See: http://goo.gl/kKVJO7
spl_autoload_unregister(array('App', 'load'));
spl_autoload_register(array('App', 'load'), true, true);

/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */


if (!isset($engine)) {
	$engine = 'File';
	$prefix = 'saito_';
}

// Setup a 'default' cache configuration for use in the application.
Cache::config(
	'default',
	['engine' => $engine]
);

/**
 * Long term cache for performance cheating
 */
Cache::config(
	'entries',
	[
		'engine'   => $engine,
		'prefix'	 => $prefix,
		'groups'	 => ['entries'],
		'duration' => 3600
	]
);

/**
 * Short term cache for performance cheating
 */
Cache::config(
	'short',
	[
		'engine'   => $engine,
		'prefix'	 => $prefix,
		'groups'	 => ['short'],
		'duration' => 180
	]
);

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models', '/next/path/to/models'),
 *     'Model/Behavior'            => array('/path/to/behaviors', '/next/path/to/behaviors'),
 *     'Model/Datasource'          => array('/path/to/datasources', '/next/path/to/datasources'),
 *     'Model/Datasource/Database' => array('/path/to/databases', '/next/path/to/database'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions', '/next/path/to/sessions'),
 *     'Controller'                => array('/path/to/controllers', '/next/path/to/controllers'),
 *     'Controller/Component'      => array('/path/to/components', '/next/path/to/components'),
 *     'Controller/Component/Auth' => array('/path/to/auths', '/next/path/to/auths'),
 *     'Controller/Component/Acl'  => array('/path/to/acls', '/next/path/to/acls'),
 *     'View'                      => array('/path/to/views', '/next/path/to/views'),
 *     'View/Helper'               => array('/path/to/helpers', '/next/path/to/helpers'),
 *     'Console'                   => array('/path/to/consoles', '/next/path/to/consoles'),
 *     'Console/Command'           => array('/path/to/commands', '/next/path/to/commands'),
 *     'Console/Command/Task'      => array('/path/to/tasks', '/next/path/to/tasks'),
 *     'Lib'                       => array('/path/to/libs', '/next/path/to/libs'),
 *     'Locale'                    => array('/path/to/locales', '/next/path/to/locales'),
 *     'Vendor'                    => array('/path/to/vendors', '/next/path/to/vendors'),
 *     'Plugin'                    => array('/path/to/plugins', '/next/path/to/plugins'),
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

	/**
	 * Cake doesn't handle Smiley <-> Smilies
	 */
Inflector::rules('plural', array(
								'/^(smil)ey$/i' => '\1ies'));
Inflector::rules('singular', array(
								'/^(smil)ies$/i' => '\1ey'));

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

CakePlugin::loadAll(
	[
		'Api' => ['bootstrap' => true, 'routes' => true],
		'M' => ['bootstrap' => true, 'routes' => true],
		'SaitoHelp' => ['routes' => true],
	]
);

/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher',
	'Stopwatch.StopwatchFilter',
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
CakeLog::config('saito', array(
		'engine' => 'FileLog',
		'size' => '1MB',
		'rotate' => '4',
		'types' => ['saito.info'],
		'file' => 'saito'
));

include 'version.php';

/**
 * Sets if additional app runtime information is logged
 */
Configure::write('Saito.Globals.logInfo', false);

/**
 * Empiric number matching the average number of postings per thread
 */
Configure::write('Saito.Globals.postingsPerThread', 10);

	/**
 * Check if the forum is installed
 */
if ( file_exists(APP . 'Config' . DS . 'installed.txt') ) :
	Configure::write('Saito.installed', TRUE);
else :
	Configure::write('Saito.installed', FALSE);
endif;

	/**
 * Activate Saito Cache:
 *
 * true: (default) use cache
 * false: don't use cache
 */
Configure::write('Saito.Cache.Thread', true);

/**
 * Add additional buttons to editor
 * @td document in namespace
 */
Configure::write('Saito.markItUp.nextCssId', 10);

include 'saito_config.php';
/*
Configure::write('Markitup.vendors', array(
		'set'			=> 'bbcode',
		'skin'		=> 'bbcode',
    'bbcode' => array('markitup.bbcode_parser.php'),
));
*/
