<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 13.05.2015
 * Time: 13:08
 */
?>
<div class="roleWidget">
    <?php
        echo CHtml::label($templateName,'',array(
                    'class'=>'widgetLabel',
                        'id'=>'role_model_name'
                    ));
        echo CHtml::hiddenField('model_name['.$i.']',$modelName);
        echo CHtml::hiddenField('template_id['.$i.']',$templateRole_id);
        echo CHtml::hiddenField('relation_name['.$i.']','Role');

        if ($count == 0) {
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
                'class'=>'form-control',
                $is=>$is,
            )
        ));
        echo CHtml::link(" Відмінити", '#', array(
            "class"=>"glyphicon glyphicon-remove eraseElement",
            'style' => 'color:black; margin-bottom:10px;',
        ));
    ?>
    <br/>
</div>