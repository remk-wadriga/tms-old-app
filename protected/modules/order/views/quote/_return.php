<?php
/**
 *
 */
?>
<br/>
<div class="col-lg-6">
    <div class="form-group">

        <?php
        /**
         * при кліку на цей чекбокс потрібно виділити всі непродані місця
         */
        echo CHtml::checkBox("selectAll", false);
        ?>
        <?php echo CHtml::label("Вибрати всі непродані місця", "selectAll");?>
    </div>
    <div class="form-group">
        <?php
        //Відправляй мені цей параметр коли робиться повернення
        echo CHtml::radioButtonList("typeReturn", Quote::RETURN_AND_OPEN, array(
            Quote::RETURN_AND_OPEN=>"Повернути та відкрити у продаж",
            Quote::RETURN_AND_CLOSE=>"Повернути та закрити з продажу",
        ));?>
    </div>
</div>
<div class="col-lg-6">
    <?php
    $this->widget("booster.widgets.TbButton", array(
        "context"=>"success",
        "label"=>"Повернути",
        "id"=>"returnSold",
        "htmlOptions"=>array(
            "data-url"=>$this->createUrl("returnSold")
        )
    ));
    ?>
</div>
