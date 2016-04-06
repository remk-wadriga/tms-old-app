<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.12.14
 * Time: 10:59
 * @var $scheme Scheme
 * @var $form TbActiveForm
 */
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'newCopySector',
    'action' => Yii::app()->createUrl('/location/sector/saveSector'),
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true
    )
));
echo CHtml::dropDownList('copySector_id', '', $scheme->getSectorsList(), array(
    'class'=>'form-control',
    'empty' => 'Виберіть сектор'
));
?>
<div class="copySector">
    <?php
    echo $form->dropDownListGroup($model, 'type_sector_id', array(
        'widgetOptions'=>array(
            'data'=>TypeSector::getTypes()
        )
    ));
    echo $form->textFieldGroup($model, 'name', array(
        'widgetOptions' => array(
            'htmlOptions'=>array(

            )
        )

    ));
    ?>
<div style="display: none">
    <?php
    echo $form->radioButtonListGroup($model, 'type', array(
        'widgetOptions' => array(
            'data' => Sector::$type
        ),
        'wrapperHtmlOptions' => array(
            'style' => 'margin-left:20px !important;'
        )
    ));
    ?>
</div>
<div class="forSitSector" style="display: none">
    <?php
    echo $form->dropDownListGroup($model, 'type_row_id', array(
        'widgetOptions'=>array(
            'data'=>TypeRow::getTypes()
        )
    ));
    echo $form->dropDownListGroup($model, 'type_place_id', array(
        'widgetOptions'=>array(
            'data'=>TypePlace::getTypes()
        )
    ));
    ?>
</div>
<div class="forFunZone" style="display: none">
    <?php
    echo $form->textFieldGroup($model, 'amount');
    ?>
</div>
    <?php
    echo $form->hiddenField($model, 'scheme_id');

    echo $form->hiddenField($model, 'places');

    ?>
</div>
<div class="modal-footer">
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Копіювати',
            'buttonType'=>'submit',
            'url' => '#',
//            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Закрити',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    );
?>
</div>
<?php
$this->endWidget();