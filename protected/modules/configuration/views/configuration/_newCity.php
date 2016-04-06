<?php
/**
 * @var $this ConfigurationController
 * @var $form TbActiveForm
 * @var $model City*/
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'newCity'));
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'new-city-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnChange'=>true,
            'validateOnSubmit'=>true
        )
    ))
?>

<div class="modal-header">
    <a class="close closeCity" data-dismiss="modal">&times;</a>
    <h4 class="modal-city-caption">Створити Елемент</h4>
</div>

<div class="modal-body">
    <input id="city_id_val" type="hidden" value="0" name="city_id_val">
    <?php
        echo $form->hiddenField($model, "country_id", array(
                'value' => '0'
        ));
        echo $form->textFieldGroup($model, "name");
        echo $form->textFieldGroup($model, "lat");
        echo $form->textFieldGroup($model, "lng");
        echo $form->dropDownListGroup($model, "parent", array(
            'widgetOptions' => array(
                'data' => array(),
                'htmlOptions' => array(
                    'class' => 'city-parent',
                    'empty' => 'Нікому не підпорядковується'
                )
            )
        ));
        echo $form->checkBoxGroup($model, "status");
    ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget("booster.widgets.TbButton",array(
        "buttonType"=>"link",
        "label"=>"Видалити елемент",
        'htmlOptions' => array(
            'class' => 'delete-city hidden',
            'style' => 'float: left; padding: 3px 12px;'
        ),
    ));
    ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Зберегти',
            'url' => '#',
            'buttonType' => 'submit'
        )
    ); ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Закрити',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal', 'class'=>'closeCity'),
        )
    ); ?>
</div>

<?php
    $this->endWidget();
    $this->endWidget();
