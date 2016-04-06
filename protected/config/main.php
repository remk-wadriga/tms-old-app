<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii Blog Demo',

	// preloading 'log' component
	'preload'=>array(
		'log',
		'bootstrap',
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.widgets.*',
		'application.extensions.*',
		'ext.barcode.*',
		'ext.before_delete_behavior.*',
		'ext.cart.*',
		'ext.image.*',
		'ext.many_many.*',
		'ext.MobileDetect.*',
		'ext.nested-set.*',
		'ext.np_client.*',
		'ext.php-exel.*',
		'ext.phpbarcode.*',
		'ext.yii2-debug.*',
		'ext.yii-mail.*',
		'ext.yii-pdf.*',
	),
	'aliases' => array(
		'booster' => 'application.extensions.bootstrap',
	),

	'defaultController'=>'post',

	// application components
	'components'=>array(
		'user'=>array(
			'class' => 'WebUser',
			'allowAutoLogin'=>true,
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=d_tms_db_test',
			'emulatePrepare' => true,
			'username' => 'db_user',
			'password' => '6B4p8R6f',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl_',
		),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id\d+>' => '<controller>/<action>',
				'<modules:\w+>/<controller:\w+>/<action:\w+>' => '<modules>/<controller>/<action>',
				'<modules:\w+>/<controller:\w+>/<action:\w+>/<id\d+>' => '<modules>/<controller>/<action>',
			),
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
		'bootstrap' => array(
			'class' => 'ext.bootstrap.components.Booster'
		),
		'cache'=>array(
			'class'=>'system.caching.CFileCache'
		),
		'shoppingCart' => array(
			'class' => 'ext.cart.EShoppingCart',
		),
	),

	'modules' => [
		'configuration',
		'event',
		'location',
		'order',
		'statistics',
	],

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>require(dirname(__FILE__).'/params.php'),
);