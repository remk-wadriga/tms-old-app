<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.12.14
 * Time: 14:57
 * @var $model Sector
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
echo $form->radioButtonListGroup($model, 'type', array(
    'widgetOptions' => array(
        'data' => Sector::$type
    ),
    'wrapperHtmlOptions' => array(
        'style' => 'margin-left:20px !important;'
    )
));
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

echo $form->hiddenField($model, 'scheme_id');

echo $form->hiddenField($model, 'places');
?>
<div class="modal-footer">
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Зберегти',
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