<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="<?=Yii::app()->baseUrl ?>/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/font.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/js/select2/select2.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/js/fuelux/fuelux.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/js/datepicker/datepicker.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/js/slider/slider.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/js/jquery.bxslider/jquery.bxslider.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/plugin.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/app.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/style.css" type="text/css" />

    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/respond.min.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/html5.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/fix.js" cache="false"></script>
    <![endif]-->
</head>
<body>
    <section class="hbox stretch">
        <section id="content">
            <section class="vbox">
                <header class="header bg-black">
                    <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
                        <i class="fa fa-bars"></i>
                    </a>
                    <a class="btn btn-link visible-xs" data-toggle="collapse" data-target=".navbar-collapse">
                        <i class="fa fa-comment-o"></i>
                    </a>
                    <a href="<?= Yii::app()->baseUrl ?>" class="navbar-brand"><img src="<?php echo Yii::app()->baseUrl; ?>/img/logo-inv.png" alt=""></a>
                    <div class="collapse navbar-collapse pull-in">
                        <?php
                            $this->widget('CustomCMenu', array(
                                'htmlOptions' => array(
                                    'class' => 'nav navbar-nav m-l-n',
                                ),
                                'hideEmptyItems'=>true,
                                'encodeLabel' => false,
                                'items' => array(
                                    array('label' => 'Події', 'url' => array('/event/event/index')),
                                    array('label' => 'Статистика', 'url' => array('/statistics/statistics/basic')),
                                    array(
                                        'label' => 'Квоти'.'<b class="caret"></b>',
                                        'itemOptions' => array('class' => 'dropdown'),
                                        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown-menu'),
                                        'items'=>array(
                                            array('label'=>'Список квот', 'url'=>array('/order/quote/index')),
                                            array('label'=>'Порівняння квот', 'url'=>array('/order/quote/compare')),
                                        )
                                    ),
                                    array('label' => 'Локації', 'url' => array('/location/location/index')),
                                    array(
                                        'label' => 'Конфігурації'.'<b class="caret"></b>',
                                        'itemOptions' => array('class' => 'dropdown'),
                                        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown-menu'),
                                        'items'=>array(
                                            array('label'=>'Користувачі', 'url'=>array('/user/index')),
                                            array('label'=>'Гравці', 'url'=>array('/role/index')),
                                            array('label'=>'Платформи API', 'url'=>array('/platform/index')),
                                            array('label'=>'Міста', 'url'=>array('/configuration/configuration/city')),
                                            array('label'=>'Типи локацій', 'url'=>array('/configuration/configuration/location')),
                                            array('label'=>'Префікси секторів', 'url'=>array('/configuration/configuration/sector')),
                                            array('label'=>'Ряди', 'url'=>array('/configuration/configuration/row')),
                                            array('label'=>'Місця', 'url'=>array('/configuration/configuration/place')),
                                            array('label'=>'Дерева', 'url'=>array('/configuration/tree/index')),
                                        ),
                                    ),
                                    array('label'=>'Менеджер замовлень', 'url'=>array('/order/order/index')),
                                    array(
                                        'label'=>'Модуль касира'.'<b class="caret"></b>',
                                        'itemOptions' => array('class' => 'dropdown'),
                                        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown-menu'),
                                        'items'=>array(
                                            array('label'=>'Створити замовлення', 'url'=>array('/order/cashier/listEvent')),
                                            array('label'=>'Статистика', 'url'=>array('/order/cashier/statistic')),
                                            array('label'=>'Пошук замовлення', 'url'=>array("/order/cashier/orders")),
                                            array('label'=>'Завершення роботи', 'url'=>array("/order/cashier/close")),
                                            array('label'=>'Контроль каси', 'url'=>array("/order/cashier/control")),
                                        )
                                    ),
                                    array(
                                        'label'=>'Інкасації'.'<b class="caret"></b>',
                                        'itemOptions' => array('class' => 'dropdown'),
                                        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown-menu'),
                                        'items'=>array(
                                            array('label'=>'Список Інкасацій', 'url'=>array("/order/encashment/index")),
                                            array('label'=>'Інкасація', 'url'=>array("/order/encashment/collection")),
                                            array('label'=>'Відсоток', 'url'=>array("/order/encashment/percent")),
                                        )
                                    ),
                                    array(
                                        'label'=>'Сайт'.'<b class="caret"></b>',
                                        'itemOptions' => array('class' => 'dropdown'),
                                        'linkOptions' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                                        'submenuOptions' => array('class' => 'dropdown-menu'),
                                        'items'=>array(
                                            array('label'=>'Новини', 'url'=>array("/post/index")),
                                            array('label'=>'Слайди', 'url'=>array("/slider/index")),
                                        )
                                    )
                                )
                            ));
                        ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="hidden-xs">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o text-white"></i>
                                </a>
                                <section class="dropdown-menu animated fadeInUp input-s-lg">
                                    <section class="panel bg-white">
                                        <header class="panel-heading">
                                            <strong>Ваші Гравці</strong>
                                        </header>

                                        <div class="list-group">
                                            <?php
                                            $currentRole = Yii::app()->user->role;
                                            foreach (Yii::app()->user->userRolesList as $role) {

                                                $isCurrent = $role == $currentRole;
                                                echo CHtml::link('<span class="media-body block m-b-none">
                                                '.($isCurrent ? "<b>" : "" ).$role.($isCurrent ? "</b>" : "" ).'
                                                </span>', array("/site/changeCurrentRole", "role"=>$role, "returnUrl"=>Yii::app()->request->requestUri), array(
                                                    "class"=>"media list-group-item changeRole"
                                                ));

                                            }
                                            ?>
                                        </div>
                                    </section>
                                </section>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-user text-white m-r"></i>
                                    <?= !Yii::app()->user->isGuest ? (Yii::app()->user->isAdmin?Yii::app()->user->name:User::getNameById(Yii::app()->user->id)):"" ?> <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu animated fadeInLeft">
                                    <li><?= CHtml::link("Вийти", array("/site/logout")) ?></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </header>
                <section class="scrollable">
                    <div class="alert-block">
                        <?php if(Yii::app()->user->hasFlash('alert_success')): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Закрити"><span aria-hidden="true">&times;</span></button>
                                <?=Yii::app()->user->getFlash('alert_success')?>
                            </div>
                            <script type="text/javascript">$(document).ready(function(){alertTimeout()})</script>
                        <?php endif; ?>
                        <?php if(Yii::app()->user->hasFlash('alert_error')): ?>
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Закрити"><span aria-hidden="true">&times;</span></button>
                                <?=Yii::app()->user->getFlash('alert_error')?>
                            </div>
                            <script type="text/javascript">$(document).ready(function(){alertTimeout()})</script>
                        <?php endif; ?>
                    </div>
                    <?php echo $content; ?>
                </section>
            </section>
            <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
        </section>
    </section>


    <?php
        // echo '©'.date('Y').' WebClever';
        // $version_file = Yii::getPathOfAlias('application.config').'/version.php';
        // if (file_exists($version_file)) {
        //   $version = include_once($version_file);
        //   echo "Build #".$version['build']. " Date: ".$version['date'];
        // }
    ?>

    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/charts/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/app.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/app.plugin.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/app.data.js"></script>

    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/libs/jquery.pjax.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/charts/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/charts/morris/raphael-min.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/charts/morris/morris.min.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/select2/select2.full.min.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/jquery.bxslider/jquery.bxslider.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/bootstrap-datepicker/locales/bootstrap-datepicker.uk.min.js" charset="UTF-8"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/script.js"></script>
    <div class="loading"><div class="spinner-wrap"><div class="spinner-inner"><i class="fa fa-spinner fa-spin"></i></div></div></div>
</body>
</html>
