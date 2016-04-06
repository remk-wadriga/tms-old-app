<?php
/* @var $this UserController */
/* @var $model User */



$this->menu=array(
	array('label'=>'Створити користувача', 'url'=>array('create')),
	array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
//	array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'), 'linkOptions'=>['style'=>'font-weight: bold;'])
);

?>

<h1>Керування Користувачами</h1>

<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'user-grid',
	'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'cssClassExpression'=>'"col-sm-1"',
			'name'=>'id'
		),
		'username',
		'email',
		'surname',
		'name',
		array(
			'name'=>'reg_date',
			'value'=>'Yii::app()->dateFormatter->format("dd-MM-yyyy HH:mm:ss", $data->reg_date)',
			'cssClassExpression'=>'"col-sm-1"',
		),
		array(
			'name'=>'user_roles',
			'filter'=> CHtml::activeDropDownList($model,'role',
			User::getRoleNames(),
			array('empty'=>'------', 'class'=>'form-control',
				"multiselect"=>true)
			),
			'value'=>'$data->getRolesList()',
			'cssClassExpression'=>'"col-sm-2"',
		),
		array(
			'class'=>'booster.widgets.TbEditableColumn',
			'name'=>'status',
			'value'=>'$data->getStatus()',
			'filter'=> CHtml::activeDropDownList($model,'status',
			User::$statusList,
			array('empty'=>'------', 'class'=>'form-control')
			),
			'editable'=>array(
				'type' => 'select2',
				'source'=>User::$statusList,
				'url'=>'editable'
			),
			'cssClassExpression'=>'"col-sm-2"',
		),
		/*
		'role',
		'status',
		*/
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template'=>'{update}{delete}{access}',
			'buttons'=>array(
				'update'=>array(
//					'visible'=>'$data->id==Yii::app()->user->id ? false:true'
				),
				'delete'=>array(
//					'visible'=>'$data->id==Yii::app()->user->id ? false:true',
					'options'=>array(
						'style'=>'margin-left:10px;'
					)
				),
				'access'=>array(
					'label'=>'Доступ',
					'url'=>'Yii::app()->createUrl("user/access", array("id"=>$data->id))',
//					'visible'=>'$data->id==Yii::app()->user->id ? false:true'
				)
			)
		),
	),
)); ?>
