<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm  */
?>
<?php
    $form=$this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation'=>true,
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions' => array(
            'class' => 'panel-body'
        ),
    ));
?>
    <div class="error-block text-center">
        <?php echo $form->error($model, 'username'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->textField($model, "username", array('class' => 'form-control', 'placeholder' => 'логін')); ?>
    </div>
    <div class="form-group">
        <?php echo $form->passwordField($model, "password", array('class' => 'form-control', 'placeholder' => 'пароль')); ?>
    </div>
    <div class="checkbox">
        <label>
            <?php echo $form->checkbox($model, "rememberMe");?> <?php echo $form->labelEx($model, "rememberMe"); ?>
        </label>
    </div>
    <div class="text-center">
        <?php
            echo CHtml::htmlButton('Вхід',
                array(
                    'class' => 'btn btn-white btn-sm',
                    'type' => 'submit'
                ));
        ?>
    </div>
<?php $this->endWidget(); ?>