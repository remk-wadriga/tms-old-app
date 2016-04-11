<?php
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'ticket management system',
    'charset'=>'utf-8',
    'sourceLanguage' => 'en',
    'language' => 'uk',
    'timeZone' => 'Europe/Kiev',

    // preloading 'log' component
    'preload'=>array('log', 'booster', 'debug'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.modules.*',
        'application.components.*',
        'application.helpers.*',
        'application.extensions.*',
        'application.extensions.cart.*',
        'application.extensions.yii-mail.*',
        'application.extensions.php-exel.*',
        'application.extensions.phpbarcode.*',
        'application.extensions.MobileDetect.lib.*'
    ),

    'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'1111',
        ),
        'configuration',
        'location',
        'event',
        'order',
        'statistics'
    ),

    // application components
    'components'=>array(
        'detect' => array(
            'class' => 'ext.MobileDetect.MobileDetect'
        ),
        'ePdf' => array(
            'class'         => 'application.extensions.yii-pdf.EYiiPdf',
            'params'        => array(
                'mpdf'     => array(
                    'librarySourcePath' => 'webroot.vendors.mpdf.*',
                    'constants'         => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class'=>'mpdf', // Для некоторых 'регистрочувствительных' систем
                    /*'defaultParams'     => array( // Детальней: http://mpdf1.com/manual/index.php?tid=184
                        'mode'              => '', //  This parameter specifies the mode of the new document.
                        'format'            => 'A4', // format A4, A5, ...
                        'default_font_size' => 0, // Sets the default document font size in points (pt)
                        'default_font'      => '', // Sets the default font-family for the new document.
                        'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                        'mgr'               => 15, // margin_right
                        'mgt'               => 16, // margin_top
                        'mgb'               => 16, // margin_bottom
                        'mgh'               => 9, // margin_header
                        'mgf'               => 9, // margin_footer
                        'orientation'       => 'P', // landscape or portrait orientation
                    )*/
                ),

            ),
        ),
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php'
        ),
        'image'=>array(
            'class'=>'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver'=>'GD',
            // ImageMagick setup path
            'params'=>array('directory'=>'/opt/local/bin'),
        ),
        'shoppingCart' =>array(
            'class' => 'application.components.CustomShoppingCart',
        ),
        'metadata' => array(
            'class' => 'Metadata'
        ),
        'authManager' => array(
            'class' => 'CustomAuthManager',
            'connectionID'=>'db',
            'itemTable' => '{{role}}',
            'itemChildTable' => '{{role_child}}',
            'assignmentTable' => '{{user_role}}',
            'defaultRoles' => array('guest'),
        ),
        'booster' => array(
            'class' => 'ext.bootstrap.components.Booster'
        ),
        'user'=>array(
            'class'=>'WebUser',
            'allowAutoLogin'=>true,
            'loginUrl'=>array('site/login'),
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'showScriptName'=>false,
            'rules'=>array(
                '/'=>'event/event/index',
                'admin'=>'/admin/default/index',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '/<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>'
            ),
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=127.0.0.1;dbname=tms_db',
            'emulatePrepare' => true,
            'username' => 'tms_u',
            'password' => 'OlktHuifUyt4R',
            'charset' => 'utf8',
            'tablePrefix'=>'tbl_',
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
            'queryCacheID'=>'cache',
            'schemaCachingDuration'=>60*60*24
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache'
        ),
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'debug' => array(
            'class' => 'ext.yii2-debug.Yii2Debug',
            'allowedIPs' => array('127.0.0.1')
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
//                array(
//                    'class'=>'CWebLogRoute',
//                ),
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'info@kasa.in.ua',
    ),
);