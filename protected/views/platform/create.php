<?php
/* @var $this PlatformController */
/* @var $model Platform */



$this->menu=array(
	array('label'=>'Платформи', 'url'=>array('index')),
);
?>

<h1>Створити платформу</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>