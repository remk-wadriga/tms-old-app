<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 22.09.14
 * Time: 10:29
 */
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/install.php';

if (file_exists(dirname(__FILE__).'/protected/config/main.php'))
    header( 'Location: index.php' ) ;

require_once($yii);

Yii::$enableIncludePath = false;
Yii::createWebApplication($config)->run();