<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 02.02.2015
 * Time: 16:22
 * @var $model LocationCategory
 * @var $form TbActiveForm
 */
$this->renderPartial("_newTree", array("treeModel"=>$treeModel,'ruleModel'=>$ruleModel, 'roots'=>$roots, 'models' =>$models,'rules' => $rules,'count' => $count));
$this->renderPartial("_newBranch", array("treeModel"=>$treeModel, 'roots'=>$roots));
$this->renderPartial("_copyBranch", array("treeModel"=>$treeModel, 'roots'=>$roots));
?>

<h1>Дерева класифікацій</h1>
<div class="alert alert-success success-sector-alert" style="position:absolute;z-index:5;width:60%;display: none" role="alert">Успішно збережено</div>
<div class="alert alert-danger danger-sector-alert" style="position:absolute;z-index:5;width:60%;display: none" role="alert">Fail</div>
<p class="descr">Створення ієрархічних структур для класифікації сутностей</p>
<div class="row">

    <?php
    $this->widget('booster.widgets.TbAlert', array(
        'fade' => true,
        'closeText' => '&times;', // false equals no close link
        'userComponentId' => 'user',
        'alerts' => array( // configurations per alert type
            // success, info, warning, error or danger
            'success' => array('closeText' => '&times;'),
            'danger' => array('closeText' => '&times;'),
        ),
    ));
    ?>
    <?php $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
        "id"=>"search-form"
    ));?>
    <div class="col-lg-3">
    <?php

    $this->widget('booster.widgets.TbSelect2', array(
        'data'=>$roots,
        'name'=>'treeGroup',
        'htmlOptions'=>array(
            'placeholder'=>"Виберіть групу дерев",
            'class'=>'form-control',
            'ajax' => array(
                'type'=>'POST',
                'url'=>CController::createUrl('/configuration/tree/getTreesNames'),
                'data'=>'js:{group:$(this).val()}',
                'complete'=>"js:function(result){
                $('#filterName').select2('destroy')
                        .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                        .select2({placeholder: 'Виберіть дерево'});
                    $('.treeView').html(null);
                    }",
            ),
        )
    ));
        ?>
    </div>
    <div class="col-lg-1">
    <?php
    echo CHtml::link("", "#", array(
        "class"=>"glyphicon glyphicon-pencil editGroup",
        'style' => 'margin-left:-10px',
        'data-toggle' => 'modal',
        'data-target' => '#newBranch'
    ));

    ?>
    </div>
    <div class="col-lg-3">
        <?php
        $this->widget('booster.widgets.TbSelect2', array(
            'data'=>$treeNames,
            'name'=>'filterName',
            'htmlOptions'=>array(
                'placeholder'=>"Виберіть дерево",
                'class'=>'form-control',
                'ajax' => array(
                    'type'=>'POST',
                    'url'=>CController::createUrl('/configuration/tree/getTreeView'),
                    'data'=>'js:{tree_id:$(this).val()}',
                    'success'=>'js:function(result){
                    var obj = JSON.parse(result);
                    $(".treeView").html(obj);
                    hierarhicSelectData($("#filterName").val());
                    $(".treeView .treeName").each(function() {
                        var _this = $(this);
                        _this.editable({
                            pk: _this.attr("data-pk"),
                            url: _this.attr("data-url"),
                            name: _this.attr("data-name")

                        });
                    });
                    $(".accordion_tree").nestedSortable({
                    handle: "div",
                    items: "li",
                    toleranceElement: "> div",
                    helper: "clone",
                    listType: "ul",
                    protectRoot: true,
                    isTree: true,
                    startCollapsible: true,
                    placeholder: "placeholder",
                    forcePlaceholderSize: true,
                    start: function(event ,ui) {
                        var item = ui.item,
                            parent = item.parent().parent(),
                            nextUl = parent.find("ul:first"),
                            spanPlus = parent.find("span:first");
                        if (nextUl.find("li").length==3) {
                            spanPlus.removeClass("glyphicon-plus").removeClass("glyphicon-minus")
                        }
                    },
                    stop: function(event, ui) {
                        var item = ui.item,
                            parent = item.parent().parent(),
                            parent_id = parent.data("id"),
                            prev_id = item.prev().data("id"),
                            next_id = item.next().data("id"),
                            nextUl = parent.find("ul:first"),
                            spanPlus = parent.find("span:first");

                        if (nextUl.is(":visible")) {
                            spanPlus.addClass("glyphicon-minus")
                        }
                            $.post("'.$this->createUrl('moveBranch').'",{
                                item_id: item.data("id"),
                                parent_id: parent_id,
                                prev_id : prev_id,
                                next_id : next_id
                            }, function(result) {

                            });
                    }
                });
                }',
                ),
            )
        ));
        ?>

    </div>
    <div class="col-lg-1">
        <?php
        echo CHtml::link("", "#", array(
            "class"=>"glyphicon glyphicon-pencil editTree",
            'style' => 'margin-left:-10px',
            'data-toggle' => 'modal',
            'data-target' => '#newTree'
        ));

        ?>
    </div>
    <div class="col-lg-1">
        <?php
        echo CHtml::link("", "#", array(
            "class"=>"glyphicon glyphicon-remove deleteTree",
            'style' => 'margin-left:-70px',
        ));

        ?>
    </div>
    <div class="col-lg-3">
        <?php
        $this->widget("booster.widgets.TbButton",array(
            "context"=>"success",
            "label"=>"+ Додати дерево",
            'htmlOptions' => array(
                'style' => 'margin-left:10px',
                'data-toggle' => 'modal',
                'data-target' => '#newTree',
                 'id' => 'newTreeButton'
            ),
        ));
        ?>
    </div>
    <?php $this->endWidget();?>
</div>
<br/>
<div class="row">

<div class="col-lg-8 treeView">

</div>

<div class="col-lg-4">
        <?php
        $this->widget("booster.widgets.TbButton",array(
            "context"=>"success",
            "label"=>"+ Новий елемент",
            'htmlOptions' => array(
                'style' => 'margin-left:10px',
                'data-toggle' => 'modal',
                'data-target' => '#newBranch',
                'id' => 'addBranch'
            ),
        ));
        $this->widget("booster.widgets.TbButton",array(
            "context"=>"success",
            "label"=>"Копіювати елемент",
            'htmlOptions' => array(
                'style' => 'margin-left:10px',
                'data-toggle' => 'modal',
                'data-target' => '#copyBranch',
            ),
        ));
        ?>
</div>

</div>



