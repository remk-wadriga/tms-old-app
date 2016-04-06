<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 26.02.2015
 * Time: 14:39
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'newBranch'
    ));
$form = $this->beginWidget("booster.widgets.TbActiveForm",array(
    "id"=>"new-branch-form",
    "action"=>"createTree",
    "enableAjaxValidation"=>true,
    "clientOptions"=>array(
        "validateOnSubmit"=>true,
        "validateOnChange"=>true
    )

));
?>

    <div class="modal-header">
        <a class="close closeCountry" data-dismiss="modal">&times;</a>
        <h4 id="branch_label">Створити гілку</h4>
    </div>

    <div class="modal-body" id="form_">
        <div  >
            <?php
            echo $form->textFieldGroup($treeModel, "name",
                array(
                    'widgetOptions'=>array(
                        'htmlOptions'=>array(
                            'id' => 'Tree_name_branch'
                        )
                    )
                )
            );
            ?>
        </div>
        <?php
        echo CHtml::hiddenField('idInputBranch' , '0');
        echo CHtml::hiddenField('groupOrBranch' , '');
        echo CHtml::hiddenField('actionTypeBranch' , '');
        ?>
    </div>
    <div class="modal-body">
        <?php
        $this->widget('booster.widgets.TbSelect2', array(
            'name'=>'editLocTree',
            'htmlOptions'=>array(
                'placeholder'=>"Виберіть батьківський елемент",
                'class'=>'form-control'
            )
        ));
        ?>

    </div>
    <div class="modal-body">
        <?php
        echo $form->textAreaGroup($treeModel, 'description', array(
            'maxlength' => 300,
            'placeholder' => "Введіть опис",
            'labelOptions' => array(
                    'label'=>false
                ),
            'widgetOptions'=>array(
                'htmlOptions'=>array(
                    'id' => 'Tree_description_branch'
                )
            )
        ));


        echo $form->checkboxGroup($treeModel, "status");

        ?>

    </div>
    <div class="modal-footer">
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Зберегти',
                'htmlOptions'=>array(
                    'class'=>'branchSubmit'
                )
            )
        );
        ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Закрити',
                'url' => '#',
                'htmlOptions' => array(
                    'data-dismiss' => 'modal',
                    'class' => 'closeBranch'
                ),
            )
        ); ?>
    </div>

<?php
$this->endWidget();
$this->endWidget();
?>