<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'fixture'=>array(
                'class'=>'system.test.CDbFixtureManager',
            ),
            /*'db'=>array(
                    'connectionString' => 'mysql:host=localhost;dbname=test_tms',
                    'emulatePrepare' => true,
                    'username' => 'root',
                    'password' => 'root',
                    'charset' => 'utf8',
                    'tablePrefix'=>'tbl_',
            ),*/
        ),
    )
);