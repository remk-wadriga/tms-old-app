<?php
/* @var $this GroupController
 * @var $form TbActiveForm
 * @var $model Group
 * @var $dataProvider CActiveDataProvider
 */


$this->beginWidget('booster.widgets.TbModal', array(
	"id"=>"newGroupWindow"
));
?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Додати групу</h4>
	</div>
<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	"id"=>"newGroupForm",
	"action"=>Yii::app()->createUrl("/event/group/create"),
	"enableAjaxValidation"=>true,
	"clientOptions"=>array(
		'validateOnChange'=>true,
		'validateOnSubmit'=>true
	)
))
?>
	<div class="modal-body">
		<?php
		echo $form->textFieldGroup($model, 'name');
		echo $form->textAreaGroup($model, 'description');
		echo $form->radioButtonListGroup($model, 'type', array(
			'widgetOptions'=>array(
				'data'=>$model::$types
			),
			'wrapperHtmlOptions' => array(
				'style' => 'margin-left:20px !important;'
			)
		))
		?>
	</div>

	<div class="modal-footer">
		<?php $this->widget(
			'booster.widgets.TbButton',
			array(
				'context' => 'primary',
				'label' => 'Зберегти',
				'buttonType' => 'submit'
			)
		); ?>
		<?php $this->widget(
			'booster.widgets.TbButton',
			array(
				'label' => 'Закрити',
				'url' => '#',
				'htmlOptions' => array('data-dismiss' => 'modal'),
			)
		); ?>
	</div>
<?php

$this->endWidget();
$this->endWidget();


?>

<div class="col-sm-12">
	<div class="col-sm-12">
		<?php

		$this->widget(
			'booster.widgets.TbButton',
			array(
				'label' => '+Додати групу',
				'context' => 'primary',
				'htmlOptions' => array(
					'data-toggle' => 'modal',
					'data-target' => '#newGroupWindow',
				),
			)
		);
		?>
	</div>
	<div class="col-sm-12">
		<?php
		$this->widget('booster.widgets.TbExtendedGridView', array(
			'id'=>'groupGridView',
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
				array(
					'name'=>'id',
					'cssClassExpression'=>'"col-sm-1"'
				),
				array(
					'name' => 'name',
					'class' => 'booster.widgets.TbEditableColumn',
					'filter'=>CHtml::activeTextField($model,'name', array(
						'class'=>'form-control'
					)),
					'editable' => array(
						'type' => 'text',
						'url' => Yii::app()->createUrl('/event/group/update', array('model'=>'Group'))
					),
					'cssClassExpression'=>'"col-sm-2"'
				),
				array(
					'name'=>'description',
					'class' => 'booster.widgets.TbEditableColumn',
					'filter'=>CHtml::activeTextField($model,'description', array(
						'class'=>'form-control'
					)),
					'editable' => array(
						'type' => 'textarea',
						'url' => Yii::app()->createUrl('/event/group/update', array('model'=>'Group'))
					),
					'cssClassExpression'=>'"col-sm-4"'
				),
				array(
					'name'=>'type',
					'class' => 'booster.widgets.TbEditableColumn',
					'filter' => CHtml::activeDropDownList($model, 'type', Group::$types, array(
						"class"=>"form-control",
						"empty"=>"------"
					)),
					'editable' => array(
						'type' => 'select2',
						'model' => $model,
						'attribute' => 'type',
						'source' => Group::$types,
						'url' => Yii::app()->createUrl('/event/group/update', array('model'=>'Group'))
					),
					'cssClassExpression'=>'"col-sm-4"'

				),
				array(
					'htmlOptions' => array('nowrap'=>'nowrap'),
					'class'=>'booster.widgets.TbButtonColumn',
					'template'=>'{delete}',
					'deleteButtonUrl'=>'Yii::app()->createUrl("/event/group/delete", array("id"=>"$data->id"));',
				)
			)
		));
		?>
	</div>

</div>