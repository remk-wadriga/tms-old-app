<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.12.14
 * Time: 10:59
 * @var $form TbActiveForm
 * @var $model Sector
 */

$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id' => 'newSectorForm',
    'action' => Yii::app()->createUrl('/location/sector/saveSector'),
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true
    )
));

//echo $form->textFieldGroup($model, "type_sector_id");
echo $form->dropDownListGroup($model, 'type_sector_id', array(
    'widgetOptions'=>array(
        'data'=>TypeSector::getTypes()
    )
));

echo $form->textFieldGroup($model, 'name');

?>
<div>
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
echo $form->hiddenField($model, 'scheme_id', array(
    'value'=>$scheme->id
))
?>
<div class="modal-footer">
        <?php $this->widget(
    'booster.widgets.TbButton',
    array(
        'context' => 'primary',
        'label' => 'Створити',
        'buttonType' => 'submit',
        'url' => '#',

    )
); ?>
<?php $this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Закрити',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )
); ?>
</div>
<?php
$this->endWidget();