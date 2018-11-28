<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'医加商城',
	'timeZone'=>'Asia/Chongqing',
	// preloading 'log' component
	'preload'=>array('log'),
	'language'=>'zh_cn',
	'charset'=>'utf-8',
	// autoloading model and component classes
	'import'=>array(
		'application.components.*',
		'application.extensions.*',
        'application.models.*',
		'common.models.*',
		'common.components.*',
		'common.extensions.*',
		'common.extensions.sendSms.*',
		'common.extensions.phpqrcode.*',
		'common.extensions.tcpdf.*'
	),
	'modules'=>array(
			'oauth'=>array(
					'defaultController'=>'index'
			),
		// uncomment the following to enable the Gii tool

//		'gii'=>array(
//			'class'=>'system.gii.GiiModule',
//			'password'=>'123456',
//			// If removed, Gii defaults to localhost only. Edit carefully to taste.
//			'ipFilters'=>array('127.0.0.1','::1'),
//		),

	),

	// application components
	'components'=>array(
		'messages' => array(

			'basePath'=>dirname(__FILE__).'/../../common/messages',
		),
		'cache' => array (
			'class' => 'system.caching.CFileCache',
			'cachePath'=>dirname(__FILE__).'/../../common/data/cache/pc',
			'directoryLevel' => 2,
		),
        "redis" => array(
            "class" => "CRedisCache",
            "hostname" => "47.52.91.13",
            "port" => 6379,
            "database" => 0,//数据库
            "password" => 'QwE()666',//如果有密码，要加上密码
        ),
		'request'=>array(
			// Enable Yii Validate CSRF Token
			'class' => 'common.components.HttpRequest',
			'enableCsrfValidation' => false,
			'csrfTokenName'=>'csrf',
			'noCsrfValidationRoutes'=>array(
				'site/index',
			),
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		// uncomment the following to enable URLs in path-format

		 'urlManager'=>array(
			 'urlFormat'=>'path',
			 'showScriptName'=>false,
			 'urlSuffix' => '.html',
			 'rules'=>array(
				 '<controller:\w+>/<id:\d+>'=>'<controller>/view',
				 '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				 '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			 ),
		 ),


		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__) . '/../../common/config/database.php'),
		//'db1'=>require(dirname(__FILE__) . '/../../common/config/spider_database.php'),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'maxSourceLines' => 20,
//            'errorAction'=>'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__) . '/../../common/config/params.php'),
);
