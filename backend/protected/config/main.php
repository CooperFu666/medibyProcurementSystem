<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'采购系统-管理平台-',
	'language'=>'zh_cn',
	'timeZone'=>'Asia/Chongqing',
	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'common.models.*',
		'common.modules.*',
		'common.components.*',
		'common.extensions.*',
		'common.extensions.sendSms.*',
		'application.components.*',
		'application.extensions.*',
		'application.models.*',
		'common.extensions.phpqrcode.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'role'=>array(
			'defaultController'=>'role'
		)


	),
	'controllerMap'=>array(
		'ueditor'=>array(
			'class'=>'ext.baiduUeditor.UeditorController',
		),
	),
	// application components
	'components'=>array(

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'messages' => array(

			'basePath'=>dirname(__FILE__).'/../../../common/messages',
		),
		'cache' => array (
			'class' => 'system.caching.CFileCache',
			'cachePath'=>dirname(__FILE__).'/../../../common/data/cache/backend',
			'directoryLevel' => 2,
		),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'urlSuffix' => '.html',
			'rules'=>array(
				'role/index'=>'role/role/index',
				'role/create'=>'role/role/create',
				'role/update'=>'role/role/update',
				'role/delete'=>'role/role/delete',

				'nav/index'=>'role/nav/index',
				'nav/add'=>'role/nav/add',
				'nav/update'=>'role/nav/update',
				'nav/delete'=>'role/nav/delete',
				'nav/groupIndex'=>'role/nav/groupIndex',
				'nav/groupUpdate'=>'role/nav/groupUpdate',
				'nav/AddGroup'=>'role/nav/AddGroup',

				'module/index'=>'role/module/index',
				'module/add'=>'role/module/add',
				'module/update'=>'role/module/update',
				'module/delete'=>'role/module/delete',
				'module/deletes'=>'role/module/deletes',
				'module/actions'=>'role/module/actions',
				'module/addWay'=>'role/module/addWay',

				'user/index'=>'role/user/index',
				'user/add'=>'role/user/add',
				'user/update'=>'role/user/update',
				'user/delete'=>'role/user/delete',
				'user/actions'=>'role/user/actions',
				'user/updates'=>'role/user/updates',
			),
		),


		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__) . '/../config/database.php'),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			//'errorAction'=>'site/error',
            'maxSourceLines' => 20,
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages

				array(
					'class'=>'CWebLogRoute',  'levels'=>'trace, info, error, warning',
				),
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__) . '/../../../common/config/params.php'),
);
