<?php
/* @var $this PlatformController */
/* @var $model Platform */

$this->breadcrumbs=array(
	'Platforms'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Платформи', 'url'=>array('index')),
	array('label'=>'Створити', 'url'=>array('create')),
);
?>

<h1>Редагування платформи #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>