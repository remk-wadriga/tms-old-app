<?php
/**
 *
 * @var RoleController $this
 * @var Role $model
 * @var $form TbActiveForm
 */
$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
    "id"=>"save-access-form",
));
echo CHtml::hiddenField("group_id", $model->id);
?>

<div class="col-lg-12">
    <div class="pull-left">
        <h1>
            Доступи
        </h1>
        <h3>
            <?= $model->name?>
        </h3>
    </div>
    <div class="pull-right">
    </div>

</div>
<div class="col-lg-6">
    <h3>
        Батьківська роль:<br/>
        <?= Role::getRoleName($model->parent_id)?>
    </h3>
</div>
<div class="col-lg-6">
    <h4>
        Вкажіть рівень доступу:
    </h4>
    <?= CHtml::radioButtonList("levelAccess", $model->narrow, array(
        Role::LEVEL_SELF=>"Налаштовуваний (менший за доступ батькіського гравця)",
        Role::LEVEL_PARENT=>"Рівний доступу батьківського гравця"
    ))?>
</div>

<div class="col-lg-12">
    <?php

    $this->widget("application.widgets.accessList.AccessListWidget", array(
        "model"=>$model
    ));
    $this->endWidget();
    ?>
</div>
