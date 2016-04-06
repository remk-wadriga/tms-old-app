<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnChange'=>true,
		'validateOnSubmit'=>true
	)
)); ?>

	<p class="note">Поля позначені <span class="required">*</span> обов’язкові.</p>


	<?php
	echo $form->textFieldGroup($model, "username");

	echo $form->passwordFieldGroup($model, "password");

	if ($model->isNewRecord)
		echo $form->passwordFieldGroup($model, "password_repeat");

	echo $form->emailFieldGroup($model, "email");

	echo $form->textFieldGroup($model, "name");

	echo $form->textFieldGroup($model, "surname");

	echo $form->textFieldGroup($model, "patr_name");

	echo $form->textFieldGroup($model, "phone");

	echo $form->textAreaGroup($model, "description");

	echo $form->select2Group($model, "user_roles", array(
		"widgetOptions"=>array(
			"data"=>User::getRoleNames(),
			"htmlOptions"=>array(
				"empty"=>"",
				"multiple"=>true,
			)
		)
	));



//		echo $form->dropDownListGroup($model, "role", array(
//			'widgetOptions'=>array(
//				"data"=>User::getRoleNames()
//			)
//		));
	echo $form->dropDownListGroup($model, "status", array(
		"widgetOptions"=>array(
			"data"=>array(
				User::STATUS_ACTIVE=>"Активний",
				User::STATUS_NO_ACTIVE=>"Неактивний"
			)
		)
	));

	echo $form->dropDownListGroup($model, "rotate_ticket", array(
		"widgetOptions"=>array(
			"data"=>array(
				User::BLANK_ROTATE=>"Повертати бланк на 180°",
				User::BLANK_NOT_ROTATE=>"Не повертати бланк",
			)
		)
	));
	?>

	<div class="buttons">
		<?php
		$this->widget("booster.widgets.TbButton", array(
			"context"=>"primary",
			"buttonType"=>"submit",
			"label"=>$model->isNewRecord ? "Створити" : "Зберегти"
		));
		?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->