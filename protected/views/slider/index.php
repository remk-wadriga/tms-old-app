<?php
/* @var $this SliderController */

?>

<h1>Слайди</h1>

<?php
$this->widget('booster.widgets.TbButton', [
	'context'=>'primary',
	'buttonType'=>'link',
	'url' => $this->createUrl('create'),
	'label'=>'Створити слайдер'
]);

$this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'slider-grid',
	'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		[
			'cssClassExpression'=>'"col-sm-1"',
			'name'=>'id'
		],
		[
			'name' => 'event_id',
			"value" => '$data->getEventName()'
		],
		'multimedia_id',
		'small_multimedia_id',
		'background_color',
		'text_color',
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
			'buttons'=>array(
				'update',
				'delete'=>array(
					'options'=>array(
						'style'=>'margin-left:10px;'
					)
				),
			)
		),
	),
));
