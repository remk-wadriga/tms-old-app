<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once(dirname(__FILE__).'/TestCase.php');
require_once(dirname(__FILE__).'/DbTestCase.php');
require_once($yiit);

Yii::createWebApplication($config);
