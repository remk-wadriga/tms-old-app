<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.04.15
 * Time: 17:40
 * @var $this RoleController
 * @var $form TbActiveForm
 * @var $model TemplateRole
 * @var $metadata Metadata
 */

$metadata = Yii::app()->metadata;
$modules = $metadata->modules;

$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'template-role-form',
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
        'validateOnChange'=>true
    ),
    'type'=>'vertical'
));

    echo $form->textFieldGroup($model, "name");

    echo $form->textFieldGroup($model, "sys_name");

    echo $form->textAreaGroup($model, "description");
?>
    <h3>Відношення до об’єктів різних типів</h3>
    <div class="form-group roleRelation">
<?php
    foreach ($modules as $module) {
        Yii::import("application.modules.".$module.".models.*");
        foreach ($metadata->getModels($module) as $prop_model) {
            if (property_exists($prop_model, "roleRelation")) {?>
                <div class="col-lg-12">
                    <div class="col-lg-3 col-lg-offset-1">
                        <?php
                        echo $form->checkboxGroup($model, "_models[$prop_model][]", array(
                            "label"=>$prop_model::getName(),
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "onchange"=>"js:showTypeCount($(this).attr('id'));",
                                    "checked"=>isset($modelChecks[$prop_model])
                                )
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-lg-3 typeCount <?php echo isset($modelChecks[$prop_model]) ? "showBlock" : "hideBlock"?>">
                        <?php
                        $options = isset($modelChecks[$prop_model])? array($modelChecks[$prop_model] => array('selected'=>true)) : array();
                        echo $form->dropDownListGroup($model, "_models[$prop_model][type]", array(
                            "label"=>false,

                            "widgetOptions"=>array(
                                "data"=>Role::$type,
                                "htmlOptions"=>array(
                                    "options"=>$options,
                                )
                            )
                        ));
                        ?>
                    </div>
                </div>
                <?php
            }
        }
    }
?>
    </div>
    <h3>Моделі в яких буде використовуватись шаблон</h3>
    <div class="form-group modules-list">

        <?php

        foreach ($modules as $module) {
            ?>
            <div class="parent moduleParent">
                <?php
                echo CHtml::checkBox($module, "", array(
                    "class"=>"moduleCheckbox",
                    "onclick"=>"js:checkChild($(this));"
                ));
                echo ucfirst($module);
                ?>
            </div>
            <div class="children">
                <?php
                $controllers = array_values($metadata->getControllers($module));
                $this->widget("zii.widgets.CListView", array(
                    "dataProvider"=>new CArrayDataProvider($controllers, array(
                        "keyField"=>false,
                        "pagination"=>false,
                    )),
                    "htmlOptions"=>array(
                        "class"=>"items"
                    ),
                    "itemView"=>"_controllers",
                    "template"=>"{items}",
                    "viewData"=>array(
                        "metadata"=>$metadata,
                        "module"=>$module,
                        "checks"=>$checks,
                    )
                ));
                ?>
            </div>

            <?php
        }

        ?>
        <?php




        ?>
    </div>

<?php

    echo $form->checkboxGroup($model, "status");

    $this->widget('booster.widgets.TbButton', array(
        'label'=>'Зберегти',
        'context'=>'primary',
        'buttonType'=>'submit'
    ));

$this->endWidget();