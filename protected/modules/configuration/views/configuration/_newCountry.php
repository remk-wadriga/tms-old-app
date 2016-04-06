<?php
/**
  * @var $form TbActiveForm
 * @var $model Country*/

$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'newCountry'));
    $form = $this->beginWidget("booster.widgets.TbActiveForm",array(
        "id"=>"new-country-form",
        "enableAjaxValidation"=>true,
        "clientOptions"=>array(
            "validateOnSubmit"=>true,
            "validateOnChange"=>true
        )
    ));
 ?>

    <div class="modal-header">
        <a class="close closeCountry" data-dismiss="modal">&times;</a>
        <h4 class="modal-country-caption">Створити країну</h4>
    </div>

    <div class="modal-body">
        <?php
            echo $form->hiddenField($model, "id");
            echo $form->textFieldGroup($model, "name");
            echo $form->checkBoxGroup($model, "status");
        //            echo CHtml::hiddenField("ajaxCountry", true);
        ?>
    </div>

    <div class="modal-footer">
<!--        <a class="btn btn-danger delete-country hidden" href="" style="float:left; padding: 3px 12px;">Видалити країну</a>-->
        <?php
        $this->widget("booster.widgets.TbButton",array(
            "buttonType"=>"link",
            "label"=>"Видалити країну",
            'htmlOptions' => array(
                'class' => 'delete-country hidden',
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
                    'class' => 'closeCountry'
                ),
            )
        ); ?>
    </div>

<?php
    $this->endWidget();
    $this->endWidget();
?>

