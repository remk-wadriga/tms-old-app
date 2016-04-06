<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 17.04.15
 * Time: 16:52
 * @var $this RoleController
 */
$this->menu=array(
    array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Гравці',),
    array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Створити гравця', 'url'=>array('role/create')),
//    array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'), 'linkOptions'=>['style'=>'font-weight: bold;'])
);
?>

<h1>
    Редагувати Гравця "<?php echo $model->name;?>"
</h1>

<?php

$this->renderPartial('_form', array(
    "model"=>$model,
    "checks"=>$checks,
    "templates"=>$templates
));
