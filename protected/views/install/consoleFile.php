<?php
    /**
     * Created by PhpStorm.
     * User: elvis
     * Date: 05.10.14
     * Time: 17:42
     */
echo "<?php";
echo "
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// application components
	'components'=>array(
//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//		),
		// uncomment the following to use a MySQL database

        'db'=>array(
            'connectionString' => 'mysql:host=".$model->db_host.";dbname=".$model->db_dbname."',
            'emulatePrepare' => true,
            'username' => '".$model->db_username."',
            'password' => '".$model->db_password."',
            'charset' => 'utf8',
            'tablePrefix'=>'".$model->db_tablePrefix."',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
        ),

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
);

";