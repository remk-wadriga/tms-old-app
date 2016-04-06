<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.04.15
 * Time: 17:40
 * @var $model TemplateRole
 * @var $this RoleController
 */

$this->menu=array(
    array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Шаблони ролей'),
    array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex')),
    array('label'=>'Створити шаблон', 'url'=>array('role/templateCreate'))
);
?>

<h1>Оновити роль #<?php echo $model->id?></h1>

<?php

$this->renderPartial('_templateForm', array(
    'model'=>$model,
    'checks'=>$checks,
    'modelChecks'=>$modelChecks
));
