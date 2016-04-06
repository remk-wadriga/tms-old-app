<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=PT Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!-- blueprint CSS framework -->
<!--	<link rel="stylesheet" type="text/css" href="--><?php //echo Yii::app()->request->baseUrl; ?><!--/css/screen.css" media="screen, projection" />-->
<!--	<link rel="stylesheet" type="text/css" href="--><?php //echo Yii::app()->request->baseUrl; ?><!--/css/print.css" media="print" />-->
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/style.css" media="screen, projection" />

</head>
<body>
	<div id="header">
            <div class="container">
                <?php
                $this->widget(
                    'booster.widgets.TbNavbar',
                    array(
                        'brand' => CHtml::image(Yii::app()->baseUrl."/img/logo.png"),
                        'fixed' => 'top',
                        'items' => array(
                            array(
                                'class' => 'booster.widgets.TbMenu',
                                'type' => 'navbar',
                                'items' => array(
                                    array('label' => 'Події', 'items'=>array(
                                        array('label'=>'Список подій', 'url'=>array('/event/event/index')),
                                        array('label'=>'Створити подію', 'url'=>array('/event/event/create')),
                                    )),

                                    array('label' => 'Квоти', 'items'=>array(
                                        array('label'=>'Список квот', 'url'=>array('/order/quote/index')),
                                        array('label'=>'Порівняння квот', 'url'=>array('/order/quote/compare')),
                                    )),

                                    array('label' => 'Локації', 'url' => array('/location/location/index')),

                                    array('label' => 'Конфігурації', 'items'=>array(
                                        array('label'=>'Користувачі', 'url'=>array('/user/index')),
                                        array('label'=>'Міста', 'url'=>array('/configuration/configuration/city')),
                                        array('label'=>'Типи локацій', 'url'=>array('/configuration/configuration/location')),
                                        array('label'=>'Сектори', 'url'=>array('/configuration/configuration/sector')),
                                        array('label'=>'Ряди', 'url'=>array('/configuration/configuration/row')),
                                        array('label'=>'Місця', 'url'=>array('/configuration/configuration/place')),
                                        array('label'=>'Дерева', 'url'=>array('/configuration/tree/index')),
                                    )),
                                    array('label'=>'Менеджер замовлень', 'url'=>array('/order/order/index')),
                                    array(
                                        'label'=>'Модуль касира',
                                        'items'=>array(
                                            array('label'=>'Друк замовлень', 'url'=>'#'),
                                            array('label'=>'Список подій', 'url'=>'#'),
                                            array('label'=>'Пошук замовлення', 'url'=>'#'),
                                        )
                                    )
                                )
                            ),
                            array(
                                'class' => 'booster.widgets.TbMenu',
                                'type' => 'navbar',
                                'htmlOptions' => array(
                                    'class'=>'pull-right'
                                ),
                                'items' => array(
                                    array('label'=>'Вийти', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                                )
                            )
                        )
                    )
                );
                ?>
            </div>
	</div>
    <?php echo $content; ?>
    <footer>
        <div class="container">
            <div class="inner">
                <div class="pull-left">
                    <?php echo '©'.date('Y').' WebClever'; ?>
                </div>
                <div class="pull-right">
                    <?php
                        $version_file = Yii::getPathOfAlias('application.config').'/version.php';
                        if (file_exists($version_file)) {
                            $version = include_once($version_file);
                            echo "Build #".$version['build']. " Date: ".$version['date'];
                        }
                    ?>
                </div>
            </div>
        </div>
    </footer>
    <div class="loading"><div class="spinner-wrap"><div class="spinner-inner"><i class="fa fa-spinner fa-spin"></i></div></div></div>
</body>
</html>
