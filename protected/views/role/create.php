<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.04.15
 * Time: 16:58
 * @var $this RoleController
 * @var $model Role
 */

$this->menu=array(
    array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
//    array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'), 'linkOptions'=>['style'=>'font-weight: bold;'])
);

?>

<h1>Створення Гравця</h1>

<?php
$this->renderPartial("_form", array(
    "model"=>$model,
    "checks"=>array(),
    "templates"=>$templates

));
