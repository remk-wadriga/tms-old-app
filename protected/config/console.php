<?php
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',

    // preloading 'log' component
    'preload'=>array('log'),

    // application components
    'components'=>array(
//              'db'=>array(
//                      'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//              ),
        // uncomment the following to use a MySQL database

        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=tms_db',
            'emulatePrepare' => true,
            'username' => 'tms_u',
            'password' => 'ESXwudzg',
            'charset' => 'utf8',
            'tablePrefix'=>'tbl_',
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