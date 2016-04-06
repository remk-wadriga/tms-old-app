<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.04.15
 * Time: 16:58
 * @var $this RoleController
 * @var $model TemplateRole
 * @var $form TbActiveForm
 */

$this->menu=array(
    array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Шаблони ролей'),
    array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'))
);

?>

<h1>Створити шаблон Гравці</h1>

<?php

$this->renderPartial('_templateForm', array(
    'model'=>$model,
    'checks'=>array(),
    'modelChecks'=>array(),
));


