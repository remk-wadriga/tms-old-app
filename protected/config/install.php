<?php
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Встановлення системи',
    'charset'=>'utf-8',
    'sourceLanguage' => 'uk',

    'defaultController' => 'install',

    // preloading 'log' component
    'preload'=>array('log', 'booster'),

    // autoloading model and component classes
    'import'=>array(
        'application.components.*',
        'application.models.*',
        'application.extensions.*',
    ),
    'components'=>array(
        'booster' => array(
            'class' => 'ext.bootstrap.components.Booster'
        ),
    )
);