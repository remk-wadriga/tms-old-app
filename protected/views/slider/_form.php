<?php
/* @var $this SliderController */
/* @var $model Slider */
/* @var $form CActiveForm */
?>

<div class="wrapper">

<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm',[
		'id'=>'slider-form',
		'htmlOptions' => ['enctype' => 'multipart/form-data'],
		'enableAjaxValidation'=>true,
		'clientOptions'=>[
			'validateOnSubmit'=>true,
			'validateOnChange'=>true
		],
	]); ?>

	<div class="row" style="margin-bottom:15px;">
		<?= $form->dropDownList($model, 'event_id', array("Виберіть подію")+$events['data'], array(
			'allowClear' => true,
			'class' => 'to-select2-ext',
			'options' => $events['options']
		))?>
	</div>

	<div class="row" style="margin-bottom: 15px;">
		<?php
		echo CHtml::label("Виберіть слайд","multimedia",["class"=>"btn btn-info"]);
		$this->widget('CMultiFileUpload', [
			'name'=>'multimedia',
			'max' => 1,
			'accept' => 'jpeg|jpg|gif|png',
			'duplicate' => 'Цей файл вже вибраний',
			'htmlOptions'=>[
				'style'=>'margin-bottom:15px; display:none;'
			],
			'denied' => 'Не вірний формат зображення',
		]);

		?>
		<?php
		if (!empty($image) && isset($image['path_full']) && isset($image['file_full'])) {
			echo CHtml::image($image['path_full'], $image['file_full'], array(
				'class' => 'images',
				"style" => "width:300px; margin-bottom: 15px;"
			));
		}
		?>
	</div>

	<div class="row" style="margin-bottom: 15px;">
		<?php
		echo CHtml::label("Виберіть малий слайд","small_multimedia",["class"=>"btn btn-info"]);
		$this->widget('CMultiFileUpload', [
			'name'=>'small_multimedia',
			'max' => 1,
			'accept' => 'jpeg|jpg|gif|png',
			'duplicate' => 'Цей файл вже вибраний',
			'htmlOptions'=>[
				'style'=>'margin-bottom:15px; display:none;'
			],
			'denied' => 'Не вірний формат зображення',
		]);

		?>
		<?php
		if (!empty($image) && isset($image['path_small']) && isset($image['file_small'])) {
			echo CHtml::image($image['path_small'], $image['file_small'], array(
				'class' => 'images',
				"style" => "width:300px; margin-bottom: 15px;"
			));
		}
		?>
	</div>

	<div class="row">
		<?php echo $form->textFieldGroup($model,'background_color'); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldGroup($model,'text_color'); ?>
	</div>

	<div class="row">
		<?php echo $form->checkBox($model,"is_on_main"); ?>
		<?php echo $form->label($model, 'is_on_main'); ?>
	</div>
	<br/>
	<div class="row" style="margin-left: 10px;">
		<?php

		echo CHtml::checkBoxList('checkAll',false,["0"=>"Вибрати все"],["style"=>"margin-left:-20px;"]);

		echo $form->checkboxListGroup($model, "sliderCities", array(
			"label"=>false,
			"widgetOptions"=>array(
				"data"=>$cities,
				"htmlOptions"=>array(
					"class" => "cities"
				)
			)
		));

		?>
	</div>

	<h3>Активність</h3>
	<?php
	echo $form->dropDownListGroup($model, "status", array(
		"label"=>false,
		"widgetOptions"=>array(
			"data"=>array(
				$model::STATUS_NO_ACTIVE=>"неактивний",
				$model::STATUS_ACTIVE=>"активний",
			),
		),
	));
	?>

	<div class="row">
		<?php $this->widget('booster.widgets.TbButton', [
			'context'=>'primary',
			'buttonType'=>'submit',
			'label'=>'Зберегти',
			'htmlOptions'=>[
				'class'=>"slider_save",
			]
		]); ?>
	</div>

<?php $this->endWidget(); ?>

</div>