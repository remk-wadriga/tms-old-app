<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 26.05.2015
 * Time: 16:53
 */

foreach ($files as $file) {
    ?>
    <div class="col-md-2">
        <?php
        echo CHtml::image(Yii::app()->baseUrl."/img/icon_".$file['type'].".png", $file['name']);
        ?>
        <div>
            <?php
            echo CHtml::link("", '#', array(
                "class"=>"glyphicon glyphicon-remove deleteFile",
                'style' => 'margin-top:10px; left:-3px; top:-6px; color:black;',
                'value'=>$file['name'],
            ));
            echo CHtml::link("", '#', array(
                "class"=>"glyphicon glyphicon-edit changeFile",
                'style' => 'margin-left:5px; margin-right:5px; top:-6px; color:black;',
                'value'=>$file['name'],
                'blockType'=>'file',
            ));
            echo CHtml::label($file['fileSize'],'',array(
                'style'=>'margin-right:10px'
            ));
            echo CHtml::tag('br');
            echo CHtml::link($file['name'], $file['demoPath'].$file['name'], array(
                "target" => "_blank"
            ));
            ?>
        </div>
    </div>
    <?php
}
?>