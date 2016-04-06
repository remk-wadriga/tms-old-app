<?php
/**
 * @var $form TbActiveForm
 * @var $model Scheme*/

$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'newScheme'));
$form = $this->beginWidget("booster.widgets.TbActiveForm",array(
    "id"=>"new-scheme-form",
    "enableAjaxValidation"=>true,
    "clientOptions"=>array(
        "validateOnSubmit"=>true,
        "validateOnChange"=>true
    )
));
?>

<div class="modal-header">
    <a class="close closeModal" data-dismiss="modal">&times;</a>
    <h4>Створити Схему</h4>
</div>

<div class="modal-body">
    <?php
    echo $form->textFieldGroup($model, "name");
    echo $form->hiddenField($model,"location_id");
    ?>
</div>

<div class="modal-footer">
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Зберегти',
            'url' => '#',
            'buttonType'=>'submit',

        )
    ); ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Закрити',
            'url' => '#',
            'htmlOptions' => array(
                'data-dismiss' => 'modal',
                'class' => 'closeModal'
            ),
        )
    ); ?>
</div>

<?php
$this->endWidget();
$this->endWidget();
?>

