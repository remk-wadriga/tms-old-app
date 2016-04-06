<?php
/* @var $this UserController */
/* @var $model User */


$this->menu=array(
	array('label'=>'Створити', 'url'=>array('create')),
	array('label'=>'Керування користувачами', 'url'=>array('index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
	array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
//	array('label'=>'Шаблони ролей', 'url'=>array('role/templateRole'), 'linkOptions'=>['style'=>'font-weight: bold;']),
);
?>

<h1>Редагувати Користувача <?php echo $model->username; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>