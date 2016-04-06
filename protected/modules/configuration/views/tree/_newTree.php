<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 03.02.2015
 * Time: 14:53
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'newTree'
    ));
$form = $this->beginWidget("booster.widgets.TbActiveForm",array(
    "id"=>"new-tree-form",
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
    <h4 id="tree_label">Створити дерево</h4>
</div>
<div  class="modal-body">

    <div  >
        <?php
        echo $form->textFieldGroup($treeModel, "name",
            array(
                'labelOptions' => array(
                    'label'=>false
                )
            )
        );
        ?>
        <?php
        echo CHtml::hiddenField('idInputTree' , '0');
        echo CHtml::hiddenField('actionTypeTree' , '');
        ?>
    </div>
    <div class="row">
    <div class="col-sm-4">
        <?php
        // group
        echo $form->radioButtonList($treeModel,'groupMode',array('activeGroup'=>'Існуюча група','newGroup'=>'Нова група'),array(
            'separator'=>"<br/><br/> ",
        )); ?>
    </div>
    <div class="col-sm-8">
        <div class="col-sm-8 col-sm-offset-4 activeG" style="float: none">
            <?php
            echo $form->select2Group($treeModel, 'group', array(
                'widgetOptions'=>array(
                    'data'=>$roots,
                    'htmlOptions'=>array(
                        'multiple'=>false,
                        'placeholder'=>"Виберіть групу",
                        'allowClear'=>true,
                    ),
                ),
                'labelOptions' => array(
                    'label'=>false
                )
            ));
            ?>

        </div>
        <div class="col-sm-8 col-sm-offset-4 newG" style="float: none">
            <?php
            //new group name
            echo $form->textFieldGroup($treeModel, "createGroup",
                array(
                    'labelOptions' => array(
                        'label'=>false
                    )
                )
            );

            ?>
        </div>
    </div>
</div>

    <div  class="rules row">

        <div class="col-sm-3 " style="float: none; display: inline-block;">

            <?php
            $level = 1;
            $class = "plus addCustomParam";
            echo $form->select2Group($ruleModel, 'model[1]', array(
                'widgetOptions'=>array(
                    'data'=>$models,
                    'htmlOptions'=>array(
                        'multiple'=>false,
                        'placeholder'=>"Сутність",
                        'allowClear'=>true,
                        "class"=>"getGroup ruleSelect",
                    ),
                ),
                'labelOptions' => array(
                    'label'=>false
                )
            ));
            ?>
        </div>

        <div class="col-sm-4 " style="float: none; display: inline-block; ">

            <?php
            echo $form->select2Group($ruleModel, 'rule[1]', array(
                'widgetOptions'=>array(
                    'data'=>$rules,
                    'htmlOptions'=>array(
                        'multiple'=>false,
                        'placeholder'=>"Не використовує це дерево",
                        'allowClear'=>true,
                        "class"=>"ruleSelect",
                    ),
                ),
                'labelOptions' => array(
                    'label'=>false
                )
            ));
            ?>
        </div>

        <div class="col-sm-3" style="float: none; display: inline-block;">

            <?php
            echo $form->select2Group($ruleModel, 'count[1]', array(
                'widgetOptions'=>array(
                    'data'=>$count,
                    'htmlOptions'=>array(
                        'multiple'=>false,
                        'placeholder'=>"Кількість",
                        'allowClear'=>true,
                        "class"=>"ruleSelect",
                    ),
                ),
                'labelOptions' => array(
                    'label'=>false
                )
            ));
            ?>
        </div>

        <div class="col-sm-1" style="float: right; display: inline-block; ">
            <?php
            echo CHtml::link("", "#", array(
                "class"=>"glyphicon glyphicon-plus addCustomParam",
            ));

            ?>

            <?php
            echo CHtml::link("", "#", array(
                "class"=>"glyphicon glyphicon-minus removeCustomParam",
            ));

            ?>

        </div>
    </div>
    <div id="description" style="width: 100%">
        <?php
        echo $form->textAreaGroup($treeModel, 'description', array(
            'maxlength' => 300,
            'placeholder' => "Введіть опис",
            'labelOptions' => array(
                'label'=>false
            ),
        ));


        echo $form->checkboxGroup($treeModel, "status", array(
            "widgetOptions"=>array(
                "htmlOptions"=>array(
                    'id'=>'Tree_status_tree'
                )
            )
        ));

        $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Очистити',
                'url' => '#',
                'htmlOptions' => array(
                    'class' => 'refreshForm',
                ),));
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
                'class' => 'closeTree'
            ),
        )
    ); ?>
</div>

<?php
$this->endWidget();
$this->endWidget();
?>
