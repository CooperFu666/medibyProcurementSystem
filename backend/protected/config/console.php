<?php
require(dirname(__FILE__).'/../../../common/config/aliases.php');
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

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


	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(

		// database settings are configured in database.php
		'db'=>require(dirname(__FILE__) . '/../../../common/config/database.php'),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),

	),
	'params'=>require(dirname(__FILE__) . '/../../../common/config/params.php'),
);
