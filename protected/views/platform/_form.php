<?php
/* @var $this PlatformController */
/* @var $model Platform */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'=>'platform-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
		'validateOnChange'=>true
	)
)); ?>

<?= $form->textFieldGroup($model, "name");?>
<?= $form->textAreaGroup($model, "description");?>
<?= $form->dropDownListGroup($model, "role_id", array(
    "widgetOptions"=>array(
        "htmlOptions"=>array(
            "empty"=>"------",
        ),
        "data"=>Role::getRoleList()
    )
));?>

<?= $form->textFieldGroup($model, "partner_id", array(
	"widgetOptions"=>array(
		"htmlOptions"=>array(
            "readonly"=>"readonly"
        )
	)
));?>

<?= $form->checkboxGroup($model,"status");?>

<?php $this->widget('booster.widgets.TbButton', array(
	"context"=>"primary",
	"label"=>$model->isNewRecord ? "Створити" : "Зберегти",
	"buttonType"=>"submit"
));?>

<?php $this->endWidget(); ?>

</div><!-- form -->