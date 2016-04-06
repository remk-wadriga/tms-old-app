<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 08.04.15
 * Time: 14:49
 *
 * @var $form TbActiveForm
 */


$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'type'=>'vertical',
));

    echo CHtml::textField("roleName", "", array(
        "class"=>"form-control"
    ));


$this->endWidget();