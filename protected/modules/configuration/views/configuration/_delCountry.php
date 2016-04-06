<?php
/**
 * @var $form TbActiveForm
 * @var $model Country*/

$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'delCountry'));
$form = $this->beginWidget("booster.widgets.TbActiveForm",array(
    "id"=>"del-country-form",
    "action"=>"deleteCountry",
    "enableAjaxValidation"=>true,
    "clientOptions"=>array(
        "validateOnSubmit"=>true,
        "validateOnChange"=>true
    )
));
?>
<div class="modal-header">
    <a class="close closeCountry" data-dismiss="modal">&times;</a>
    <h4>Видалити країну</h4>
</div>

<div class="modal-body">
    <?php
            echo $form->select2Group($model, 'country_id_del', array(
            'widgetOptions'=>array(
                'data'=>$countries,
                'htmlOptions'=>array(
                    'multiple'=>false,
                    'placeholder'=>"Виберіть країну",
                    'allowClear'=>true,
                    "class"=>"deleteCountry",
                ),
            ),
            'labelOptions' => array(
                'label'=>false
            )
        ));
   ?>
</div>

<div class="modal-footer">

    <?php
        $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Видалити',
            'id' => 'deleteCountrySubmit',
            'url' => '#',
            'buttonType'=>'submit',

    )); ?>

    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Закрити',
            'url' => '#',
            'htmlOptions' => array(
                'data-dismiss' => 'modal',
                'class' => 'closeCountry'
            ),
        )
    ); ?>
</div>

<?php
$this->endWidget();
$this->endWidget();
?>

