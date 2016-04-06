<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 22.09.14
 * Time: 11:07
 *
 * @var $model Install
 * @var $form TbActiveForm
 */
?>
<div class="col-sm-6">
<?php
$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
    "id"=>"install-form",
));
    echo $form->textFieldGroup($model, "name");
    echo $form->textFieldGroup($model, "admin_email");
?>
<div>
    <h3>Налаштування БД</h3>
    <?php
    echo $form->textFieldGroup($model, "db_host");
    echo $form->textFieldGroup($model, "db_dbname");
    echo $form->textFieldGroup($model, "db_username");
    echo $form->passwordFieldGroup($model, "db_password");
    echo $form->textFieldGroup($model, "db_tablePrefix");
    ?>
</div>
    <div class="form-group">
        <?php

    $this->widget("booster.widgets.TbButton", array(
        "label"=>"Інсталювати",
        "context"=>"primary",
        "buttonType"=>"submit"
    ));

        ?>
    </div>
    <?php
$this->endWidget();

?>

</div>