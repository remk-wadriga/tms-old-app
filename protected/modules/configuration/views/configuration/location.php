<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 23.09.14
 * Time: 11:07
 * @var $model LocationCategory
 * @var $form TbActiveForm
 */
?>
<h1><?php echo $name ?></h1>
    <div class="alert alert-danger danger-sector-alert" style="display: none" role="alert">Помилка</div>
    <div class="alert alert-success success-sector-alert" style="display: none" role="alert">Успішно збережено</div>
<?php

$this->widget("booster.widgets.TbExtendedGridView", array(
    "id"=>"locationCategoryGrid",
    "dataProvider"=>$model->search(),
    "columns"=>array(
        array(
            'class' => 'booster.widgets.TbEditableColumn',
            'name' => 'name',
            'sortable' => false,
            'editable' => array(
                'url' => $this->createUrl('/configuration/configuration/update', array("model"=>get_class($model))),
                'placement' => 'right',
                'inputclass' => 'span3',
                'type'=>'text'
            )
        ),
        array(
            'class' => 'booster.widgets.TbEditableColumn',
            'name' => 'status',
            'sortable' => false,
            'editable' => array(
                'url' => $this->createUrl('/configuration/configuration/update', array("model"=>get_class($model))),
                'placement' => 'right',
                'inputclass' => 'span3',
                'type'=>'select',
                'source'=>$model::$status
            )
        ),
        array(
            'htmlOptions' => array('nowrap'=>'nowrap'),
            'class'=>'booster.widgets.TbButtonColumn',
            'template'=>'{Видалити}',
            'buttons' => array(
                'Видалити' => array
                    (
                    'icon' => 'glyphicon glyphicon-trash',
                    'options' => array(
                    'confirm' => 'Ви впевнені що хочете видалити префікс?',
                    'style' => 'margin-left:2px;width:12px',
                    'ajax' => array(
                        'type' => 'POST',
                        'url' => CController::createUrl('/configuration/configuration/'.$delUrl),
                        'data'=>'js:{id:$(this).parent().parent().find("a:first").attr("data-pk")}',
                        'success' => 'function(data){
                        if(data.includes("Тип"))
                            showAlert("danger",data,3000);
                        else
                            window.location.replace(data);
                        }'
                    )
                    )
                )
            )
        )
    )
));

?>

<?php $this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'myModal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Створити новий тип</h4>
    </div>
<?php
$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
    "id"=>"new-location-category",
    "enableAjaxValidation"=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true
    )
));

?>
    <div class="modal-body">
        <?php

            echo $form->textFieldGroup($model, "name");
            echo $form->checkboxGroup($model, "status", array(
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "checked"=>$model::STATUS_ACTIVE
                    )
                )
            ));


        ?>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Зберегти',
                'buttonType' => 'submit',
                'url' => '#',
                'htmlOptions' => array('id'=>'submitForm'),
            )
        ); ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Закрити',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal', 'id'=>'closeModal'),
            )
        ); ?>
    </div>

<?php $this->endWidget(); ?>
<?php $this->endWidget(); ?>
<?php $this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => '+ Новий тип ',
        'context' => 'primary',
        'htmlOptions' => array(
            'data-toggle' => 'modal',
            'data-target' => '#myModal',
        ),
    )
);