<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 12.03.2015
 * Time: 13:43
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'copyBranch'
    ));
$form = $this->beginWidget("booster.widgets.TbActiveForm",array(
    "id"=>"copy-branch-form",
    "action"=>"copyBranch",
    "enableAjaxValidation"=>true,
    "clientOptions"=>array(
        "validateOnSubmit"=>true,
        "validateOnChange"=>true
    )

));
?>

    <div class="modal-header">
        <a class="close closeCountry" data-dismiss="modal">&times;</a>
        <h4>Копіювання</h4>

    </div>
    <div  class="modal-body">
        <h5>Виберіть елемент чи гілку для копіювання</h5>
        <div>
            <?php
            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_group_origin',
                'data'=>$roots,
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть групу дерев",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;',
                    'ajax' => array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('/configuration/tree/getTreesNames'),
                        'data'=>'js:{group:$(this).val()}',
                        'complete'=>"js:function(result){
                    $('#Tree_name_origin').select2('destroy')
                        .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                        .select2({placeholder: 'Виберіть дерево'});
                    }",
                    ),
                )
            ));
            ?>
        </div>
        <div>
            <?php

            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_name_origin',
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть дерево",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;',
                    'ajax' => array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('/configuration/tree/getBranchNames'),
                        'data'=>'js:{id_origin:$(this).val()}',
                        'complete'=>"js:function(result){
                    $('#Tree_branch_origin').select2('destroy')
                        .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                        .select2({placeholder: 'Виберіть гілку'});
                    }",
                    ),
                )
            ));
            ?>
        </div>
        <div>
            <?php
            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_branch_origin',
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть елемент чи гілку",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;'
                )
            ));
            ?>
        </div>

        <div >
            <?php
            // group
            echo $form->radioButtonList($treeModel,'copyMode',array('copyOne'=>'Копіювати лише цей елемент','copyAll'=>'Копіювати з прив`язаними елементами'),array(
                'separator'=>"<br />"
            )); ?>
        </div>

        <h5>Виберіть елемент дерева до якого прикріпиться зкопійована гілка чи елемент</h5>

        <div>
            <?php
            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_group_copy',
                'data'=>$roots,
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть групу дерев",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;',
                    'ajax' => array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('/configuration/tree/getTreesNames'),
                        'data'=>'js:{group:$(this).val()}',
                        'complete'=>"js:function(result){
                    $('#Tree_name_copy').select2('destroy')
                        .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                        .select2({placeholder: 'Виберіть дерево'});
                    }",
                    ),
                )
            ));
            ?>
        </div>
        <div>
            <?php

            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_name_copy',
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть дерево",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;',
                    'ajax' => array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('/configuration/tree/getBranchNames'),
                        'data'=>'js:{id_copy:$(this).val()}',
                        'complete'=>"js:function(result){
                    $('#Tree_branch_copy').select2('destroy')
                        .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                        .select2({placeholder: 'Виберіть дерево'});
                    }",
                    ),
                )
            ));
            ?>
        </div>
        <div>
            <?php
            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'Tree_branch_copy',
                'htmlOptions'=>array(
                    'placeholder'=>"Виберіть елемент чи гілку",
                    'class'=>'form-control',
                    'style'=>'margin-bottom:10px;'
                )
            ));
            ?>
        </div>

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
        );
        ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Закрити',
                'url' => '#',
                'htmlOptions' => array(
                    'data-dismiss' => 'modal',
                    'class' => 'closeCopy'
                ),
            )
        ); ?>
    </div>

<?php
$this->endWidget();
$this->endWidget();
?>