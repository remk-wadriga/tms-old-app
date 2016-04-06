<?php
/* @var $this PostController */
/* @var $model Post */

?>

<h1>Новини</h1>

<?php
$this->widget('booster.widgets.TbButton', [
	'context'=>'primary',
	'buttonType'=>'link',
	'url' => $this->createUrl('create'),
	'label'=>'Створити новину'
]);

$this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'post-grid',
	'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>[
		[
			'cssClassExpression'=>'"col-sm-1"',
			'name'=>'id'
		],
		'name',
		[
			'name'=>'description',
			'value'=>'$data->getDesc()'
		],
		'alias_url',
		'html_header',
		'meta_description',
		'keywords',
//		'multimedia_id',
		[
			'cssClassExpression'=>'"col-sm-1"',
			'name'=>'status',
			'value'=>'Post::$statusType[$data->status]'
		],
		[
			'class'=>'booster.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
			'buttons'=>[
				'update',
				'delete'=>[
					'options'=>[
						'style'=>'margin-left:10px;'
					]
				],
			]
		],
	],
));
?>
