<?php
/* @var $this UserController */
/* @var $model User */


$this->menu=array(
	array('label'=>'Керування Користувачами', 'url'=>array('index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
	array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
//	array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'), 'linkOptions'=>['style'=>'font-weight: bold;']),
);
?>

<h1>Створити Користувача</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>