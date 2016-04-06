<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 07.05.2015
 * Time: 14:25
 */
?>
<div class="treeWidget" style="margin-bottom: 20px;">
    <div class="form-group">
        <?php
            echo CHtml::label('Назва групи :','',array(
                'id'=>'groupLabelStatic',
                'class'=>'widgetLabel',
                'style'=>'margin-right:5px;'
            ));
            echo CHtml::label($groupName,'',array(
                'id'=>'groupLabel',
                'class'=>'widgetLabel'
            ));
        ?>
        <br/>
        <?php
            echo CHtml::label('Назва дерева :','',array(
                'id'=>'treeLabelStatic',
                'class'=>'widgetLabel',
                'style'=>'margin-right:5px;'
            ));
            echo CHtml::label($treeName,'',array(
                'class'=>'widgetLabel',
                'id'=>'treeLabel'
            ));
        ?>
        <br/>
        <div class="widget-underline"></div>
        <?php
            echo CHtml::label('Правило :','',array(
                'id'=>'ruleLabelStatic',
                'class'=>'widgetLabel',
                'style'=>'margin-right:5px;'
            ));
            echo CHtml::label($rule,'',array(
                'class'=>'widgetLabel',
                'id'=>'ruleLabel'
            ));
        ?>
        <br/>
        <?php
            echo CHtml::label('Можна обрати листків дерева :','',array(
                'id'=>'countLabelStatic',
                'class'=>'widgetLabel',
                'style'=>'margin-right:5px;'
            ));
            echo CHtml::label($count,'',array(
                'class'=>'widgetLabel',
                'id'=>'countLabel',
                'style'=>'margin-bottom:20px'
            ));
        ?>
        <br/>
        <?php

            echo CHtml::hiddenField('model_name['.$i.']',$modelName);
            echo CHtml::hiddenField('relation_name['.$i.']','Tree');


            if ($countInt == 0) {
                $is = 'multiple';
                $place = 'Виберіть елементи';
            } else {
                $is = 'des';
                $place = 'Виберіть елемент';
            }

            $this->widget('booster.widgets.TbSelect2', array(
                'name'=>'relation_id['.$i.']',
                'data'=>$data,
                'htmlOptions'=>array(
                    'options'=>$tag,
                    'placeholder'=>$place,
                    'class'=>'form-control selectElements',
                    $is=>$is,
                )
            ));

            echo CHtml::link(" Відмінити", '#', array(
                "class"=>"glyphicon glyphicon-remove eraseElement",
                'style' => 'color:black; margin-bottom:10px;',
            ));

        ?>
        <br/>
        <div class="pathDiv" id="pathDiv_<?=$i?>">
        </div>
    </div>
</div>