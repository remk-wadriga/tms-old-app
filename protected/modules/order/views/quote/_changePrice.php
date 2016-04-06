<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 20.05.15
 * Time: 14:22
 * @var $this QuoteController
 */
?>

<div class="col-lg-4">
    <?php
    echo CHtml::textField("price", "", array(
        "class"=>"form-control",
        "placeholder"=>"Ціна"
    ));

    echo CHtml::checkBox("onScheme", false);
    echo CHtml::label("Змінити на схемі", "onScheme");
    ?>
</div>
<div class="col-lg-4">
    <?php
    $this->widget("booster.widgets.TbButton", array(
        "context"=>"success",
        "label"=>"Змінити ціну",
        "htmlOptions"=>array(
            "data-url"=>$this->createUrl("changePrice"),
            "id"=>"change_price"
        )
    ));
    ?>
</div>





