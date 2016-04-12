<?php

$db = require(__DIR__ . DIRECTORY_SEPARATOR . 'db_local.php');

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

        'db' => $db,

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