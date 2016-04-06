<?php
/* @var $this PostController */
/* @var $model Post */

$this->breadcrumbs=array(
	'Posts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);
?>


<h1>Редагувати новину </h1>

<?php $this->renderPartial('_form', array('model'=>$model, "image"=>$image)); ?>