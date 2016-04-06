<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.05.2015
 * Time: 15:34
 * @var $image
 */
?>


    <?php
    echo CHtml::image($image['path'], $image['file'], array(
        'class' => 'images'
    ));
    ?>
    <br/>
        <?php
        echo CHtml::link("", '#', array(
            "class"=>"glyphicon glyphicon-remove deleteFile",
            'style' => 'margin-top:10px; left:-3px; top:-6px; color:black;',
            'value'=>$image['file']
        ));
        echo CHtml::link("", '#', array(
            "class"=>"glyphicon glyphicon-edit changeFile",
            'style' => 'margin-left:5px; margin-right:5px; top:-6px; color:black;',
            'value'=>$image['file'],
            'blockType'=>'image',
        ));
        echo CHtml::label($image['imageSize'],'',array(
            'style'=>'margin-right:10px'
        ));
        echo CHtml::label($image['imageProp'],'');
        echo CHtml::tag('br');
        echo CHtml::link($image['file'], $image['demoPath'].$image['file'], array(
            "target" => "_blank"
        ));
        ?>




