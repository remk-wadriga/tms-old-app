<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 2/15/2016
 * Time: 3:06 PM
 */
?>
<br/>
<div class="col-sx-12">
    <?php
    echo $form->dropDownListGroup($model, "barcode_type", array(
        "widgetOptions"=>array(
            "data"=>Event::$barcodeType
        )
    ));
    echo $form->checkboxGroup($model, "refresh_code");

    ?>
</div>