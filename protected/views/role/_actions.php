<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 14.04.15
 * Time: 12:16
 */
?>

<div>
    <?php
        $id = "/".$module."/".lcfirst($controller)."/".lcfirst($data);
        echo CHtml::checkBox("rules[".$module."][".$controller."][".$data."]", in_array($id, $checks), array(
            "class"=>"childCheckBox",
            "id"=>str_replace("/", "_", $id)
        ));
        echo CHtml::label($data, "rules_".$module."_".$controller."_".$data);
    ?>
</div>
