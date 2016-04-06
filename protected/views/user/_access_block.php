<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 28.05.15
 * Time: 10:33
 *
 * @var $this UserController
 * @var $model User
 * @var $role Role
 * @var $admin User
 * @var $form TbActiveForm
 */


$this->widget("application.widgets.accessList.AccessListWidget", array(
    "model"=>$model,
    "role"=>$role
));