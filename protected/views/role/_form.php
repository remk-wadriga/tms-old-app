<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 17.04.15
 * Time: 13:37
 * @var $this RoleController
 * @var $form TbActiveForm
 * @var $model Role
 */
$metadata = Yii::app()->metadata;
$modules = $metadata->modules;
?>
<div class="wrapper">
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'role-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
            'validateOnChange'=>true
        )
    ));

    echo $form->textFieldGroup($model, "name");

    echo $form->textFieldGroup($model, "short_name");

    echo $form->textAreaGroup($model, "description");

    echo $form->checkboxGroup($model, "entity");

    echo $form->textAreaGroup($model, "legal_detail", array(
        "widgetOptions"=>array(
            "htmlOptions"=>array(
                "disabled"=>$model->isNewRecord ? true : !$model->legal_detail
            )
        )
    ));

    echo $form->textFieldGroup($model, "company_name", array(
        "widgetOptions"=>array(
            "htmlOptions"=>array(
                "disabled"=>$model->isNewRecord ? true : !$model->company_name
            )
        )
    ));
    echo $form->textFieldGroup($model, "code_yerdpou", array(
        "widgetOptions"=>array(
            "htmlOptions"=>array(
                "disabled"=>$model->isNewRecord ? true : !$model->code_yerdpou
            )
        )
    ));
    echo $form->textFieldGroup($model, "post", array(
        "widgetOptions"=>array(
            "htmlOptions"=>array(
                "disabled"=>$model->isNewRecord ? true : !$model->post
            )
        )
    ));
    echo $form->textFieldGroup($model, "real_name", array(
        "widgetOptions"=>array(
            "htmlOptions"=>array(
                "disabled"=>$model->isNewRecord ? true : !$model->real_name
            )
        )
    ));

    ?>
    <div class="form-group">
        <?php

        echo $form->dropDownListGroup($model, "parent_id", array(
                "widgetOptions"=>array(
                    "data"=>$model->getParentList(),
                    "htmlOptions"=>array(
                        "empty"=>"Оберіть батька серед існуючих ролей",
                    ),
                )
            ));

        ?>
    </div>
    <div class="form-group">
        <h3>
            Вкажіть e-mail користувача, який буде адміністратором Гравця
        </h3>
        <?php
            echo $form->select2Group($model, 'admin_id', array(
                    'label'=>false,
                    'widgetOptions'=>array(
                        "data"=>User::getUsers(),
                        "htmlOptions"=>array(
                            "multiple"=>true,
                        )
                    )
                ));
        ?>
    </div>

    <?php

    echo $form->checkboxGroup($model, "status");

    $this->widget('booster.widgets.TbButton', array(
        'context'=>'primary',
        'buttonType'=>'submit',
        'label'=>'Зберегти'
    ));
    $this->endWidget();
    ?>
</div>
