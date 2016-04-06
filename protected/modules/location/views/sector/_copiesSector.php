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
    'id'=>'newCopiesSector',
    /*'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnChange'=>true,
        'validateOnSubmit'=>true,
    ),*/
    'action' => Yii::app()->createUrl('/location/sector/saveSector'),
));
?>
<div class="modal-body" >
    <div class="form-group">
        <?php
        echo CHtml::dropDownList('copiesSector_id', '', $scheme->getSectorsList(), array(
            'class'=>'form-control',
            'empty' => 'Виберіть сектор',
            'style' => 'margin-bottom:10px;'
        ));
        echo CHtml::textField('count', '', array(
            'class'=>'form-control',
            'placeholder'=>'Кількість копій',
            'style' => 'margin-bottom:10px;'
        ));
        echo CHtml::label('', 'error', array(
            'style' => 'color: red;font-family: "Times New Roman", Georgia, Serif; display:none; margin-bottom:10px;',
            'id' => 'copiesFormErrorMessage'
        ));
        echo $form->hiddenField($model, 'scheme_id');
        echo CHtml::button('Підтвердити', array(
            'class'=>'form-control',
            'id'=>'okCopyButton',
            'style'=>'margin-bottom:10px;'
        ));
        echo $form->hiddenField($model, 'places');
        ?>
        <div id="copiesPrefix" style="display: none">
            <?php
            echo $form->dropDownListGroup($model, 'type_sector_id', array(
                'widgetOptions'=>array(
                    'data'=>TypeSector::getTypes()
                )
            ));
            ?>
        </div>
        <div id="copiesMinForm" ></div>
        <?php
            echo CHtml::label('', 'errorName', array(
            'style' => 'color: red;font-family: "Times New Roman", Georgia, Serif; display:none; margin-bottom:10px;',
            'id' => 'copiesFormErrorName'
            ));
        ?>
        <div style="display: none">
            <?php
            echo CHtml::hiddenField('type');
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
    </div>
</div>
<div class="modal-footer" >
    <i class="glyphicon glyphicon-th-large gly-spin" style="display:none; color: #005fcc; margin-right: 10px;"></i>
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'context' => 'primary',
            'label' => 'Копіювати',
            'htmlOptions'=>array(
                'class'=>'manyCopiesSubmit'
            )
        )
    );
    ?>
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