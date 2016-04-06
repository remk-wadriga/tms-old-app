<?php
/**
 * @var $this QuoteController
 */
?>
<br/>
<?php
$this->widget("booster.widgets.TbButton", array(
    "context"=>"danger",
    "label"=>"Продано контрагентом",
    "id"=>"addSold",
    "htmlOptions"=>array(
        "data-url"=>$this->createUrl("addSold")
    )
));
echo " ";
$this->widget("booster.widgets.TbButton", array(
    "context"=>"warning",
    "label"=>"Не продано контрагентом",
    "id"=>"returnInSale",
    "htmlOptions"=>array(
        "data-url"=>$this->createUrl("returnInSale")
    )
));
?>

