<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>
<div class="wrapper">
    <section id="content" class="page-error">
        <div class="row m-n">
            <div class="col-sm-4 col-sm-offset-4 text-center">
                <div class="m-b-lg">
                    <div class="h text-white animated bounceInDown"><?php echo $code; ?></div>
                </div>
                <p class="message"><?php echo CHtml::encode($message); ?></p>
                <p><a href="<?= Yii::app()->baseUrl ?>" class="btn btn-xs btn-success"><i class="fa fa-home"></i> перейти на головну сторінку</a></p>
            </div>
        </div>
    </section>
</div>