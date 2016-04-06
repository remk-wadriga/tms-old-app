<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 14.04.15
 * Time: 12:16
 */
?>
<div class="parent">
    <?php
    echo CHtml::checkBox($data, "", array(
        "class"=>"controllerCheckBox",
        "onclick"=>"js:checkChild($(this));"
    ));
    echo ucfirst($data);
    ?>
</div>
<div class="children">
    <?php
    $actions = array_values($metadata->getActions($data,$module));
    $this->widget("zii.widgets.CListView", array(
        "dataProvider"=>new CArrayDataProvider($actions, array(
            "keyField"=>false,
            "pagination"=>false,
        )),
        "itemView"=>"_actions",
        "template"=>"{items}",
        "viewData"=>array(
            "controller"=>$data,
            "module"=>$module,
            "checks"=>$checks
        )
    ));
    ?>
</div>