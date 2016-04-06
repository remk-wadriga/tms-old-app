<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div class="wrapper">

<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm',[
		'id'=>'post-form',
		'htmlOptions' => ['enctype' => 'multipart/form-data'],
		'enableAjaxValidation'=>true,
		'clientOptions'=>[
			'validateOnSubmit'=>true,
			'validateOnChange'=>true
		],
	]);
?>
	<div class="row">
		<?php echo $form->textFieldGroup($model,'name'); ?>
	</div>

	<div class="row">
		<?php
		echo $form->ckEditorGroup($model, 'description', ["labelOptions"=>["label"=>"Опис <span class='required'>*</span>"]]);
		?>
	</div>

	<div class="row" style="margin-bottom: 15px;">
		<?php echo $form->textFieldGroup($model,'alias_url'); ?>
		<input type="button" class="btn btn-info" id="aliasGenerate" value="Згенерувати alias url">
	</div>

	<div class="row">
		<?php echo $form->textFieldGroup($model,'html_header'); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldGroup($model,'meta_description'); ?>
	</div>

	<div class="row">
		<?php echo $form->textFieldGroup($model,'keywords'); ?>
	</div>

	<div class="row" style="margin-bottom: 15px;">

		<?php
		echo CHtml::label("виберіть зображення","multimedia",["class"=>"btn btn-info"]);
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
			if (!empty($image)) {
				echo CHtml::image($image['path'], $image['file'], array(
					'class' => 'images',
					"style" => "width:300px; margin-bottom: 15px;"
				));
			}
			?>
	</div>

	<div class="row buttons">
		<?php $this->widget('booster.widgets.TbButton', [
			'context'=>'primary',
			'buttonType'=>'submit',
			'label'=>'Зберегти'
		]);
		echo $form->checkBox($model,"status",[
			"style"=>"margin-left:15px;",
			"checked"=>Post::STATUS_ACTIVE,
		]);
		echo $form->label($model, 'status');
		?>

	</div>

	<?php $this->endWidget(); ?>

</div>