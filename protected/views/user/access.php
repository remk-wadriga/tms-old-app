<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.04.15
 * Time: 17:36
 * @var $model User
 * @var $this UserController
 */
$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
    "id"=>"user-access-form"
))
?>

<div class="col-lg-12">
    <h1>
        Налаштування доступу до функціоналу
    </h1>
    <div class="col-lg-6">
        <?php echo $model->fullName;?>
    </div>
    <div class="col-lg-6">

    </div>
    <div class="clearfix"></div>
    <br/>
    <div class="col-lg-4">Гравці</div>
    <div class="col-lg-4">
        <?php
        echo CHtml::hiddenField("user_id", $model->id);
        echo CHtml::dropDownList("group_id", $role->id, $model->getUserRoles(), array(
            "class"=>"form-control",
            "ajax"=>array(
                "url"=>$this->createUrl('userAccess', array("user_id"=>$model->id)),
                "type"=>"GET",
                "data"=>"js:{id:$(this).val(), change:1}",
                "success"=>"js:function(result){
                    obj = JSON.parse(result)
                    $('#levelAccess input[value='+obj.type+']').prop('checked', true);
                    $('#userAccesses').html(obj.block);

                }"
            )
        ))
        ?>
    </div>
    <div class="col-lg-4">

    </div>
    <div class="clearfix"></div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <h4>
            Вкажіть рівень доступу:
        </h4>
        <?= CHtml::radioButtonList("levelAccess", $model->getIsRoleAdmin($role->id), array(
            Role::LEVEL_SELF=>"Налаштовуваний (менший за доступ батькіського гравця)",
            Role::LEVEL_PARENT=>"Рівний доступу батьківського гравця"
        ))?>
    </div>
    <div class="col-lg-4"></div>
    <div class="clearfix"></div>
    <br/>
    <div class="col-lg-12" id="userAccesses">
        <?php

        $this->widget("application.widgets.accessList.AccessListWidget", array(
            "model"=>$model,
            "role"=>$role
        ));
        ?>
    </div>
</div>
<?php
$this->endWidget();
