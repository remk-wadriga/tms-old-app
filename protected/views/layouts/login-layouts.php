<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="<?=Yii::app()->baseUrl ?>/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/font.css" type="text/css" cache="false" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/plugin.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/app.css" type="text/css" />
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/respond.min.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/html5.js" cache="false"></script>
    <script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/ie/fix.js" cache="false"></script>
    <![endif]-->
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl; ?>/theme/css/style.css" type="text/css" />
</head>
<body class="login-page">
    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
        <div class="row m-n">
            <div class="col-md-4 col-md-offset-4 m-t-lg">
                <a class="nav-brand m-b-lg" href="<?php echo Yii::app()->baseUrl; ?>"><img src="<?php echo Yii::app()->baseUrl; ?>/img/logo-inv.png" alt=""/></a>
                <section class="panel transparent">
                    <?php echo $content; ?>
                </section>
            </div>
        </div>
    </section>
    <footer id="footer">
        <div class="text-center padder clearfix copyright">
            <p>
                <small>Ticket Management System<br><?php echo '© '.date('Y') ?><br>
                <?php
                    $version_file = Yii::getPathOfAlias('application.config').'/version.php';
                    if (file_exists($version_file)) {
                        $version = include_once($version_file);
                        echo "Build #".$version['build']. " Date: ".$version['date'];
                    }
                ?>
                </small></p>
        </div>
    </footer>
</body>
</html>
