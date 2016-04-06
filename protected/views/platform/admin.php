<?php
/* @var $this PlatformController */
/* @var $model Platform */



$this->menu=array(
	array('label'=>'Платформи', 'url'=>array('index')),
	array('label'=>'Створити', 'url'=>array('create')),
);

?>

<h1>Платформи</h1>



<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'platform-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'type',
		'partner_id',
		'status',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
		),
	),
)); ?>
