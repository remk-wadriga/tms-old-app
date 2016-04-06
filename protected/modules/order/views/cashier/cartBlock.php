<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.07.15
 * Time: 21:09
 * @var $place Place
 * @var $this CashierController
 * @var $maxFun int|bool
 */
$shoppingCart = Yii::app()->shoppingCart;
?>

<div class="cart">
    <h4>Кошик <a href="#" class="pull-right fs-10 clearCart">Очистити</a></h4>
    <div class="content">
        <?php
        foreach ($places as $event) {
            $eventArray = $event['event'];
            ?>
            <div class="title bg-light m-b m-t">
                <p><strong><?= $eventArray['name']?></strong></p>
                <p><?= $eventArray['city'].", ".$eventArray['start']?></p>
            </div>
            <?php
            foreach ($event['places'] as $place) {
                if ($place->type == Place::TYPE_SEAT) {
                    ?>
                    <div class="item">
                        <a href="#" class="deleteFromCart" data-id="<?= $place->id; ?>"><i class="fa fa-times"></i></a>
                        <div class="cont">
                            <strong><?= $place->sector->typeSector ? $place->sector->typeSector->name . ":" : "" ?> </strong><?= $place->sector->name ?>
                            <br/>
                            <strong><?= $place->getRowName() ?>: </strong><?= $place->getEditedRow() ?>
                            <strong><?= $place->getPlaceName() ?>: </strong><?= $place->getEditedPlace() ?><br/>

                        </div>
                    </div>
                    <?php
                } else {
                    $item = $shoppingCart->itemAt($place->getId());
                    ?>
                    <div class="item">
                        <a href="#" class="deleteFromCart" data-sector_id="<?= $place->id?>" data-type="<?= $place->type?>"><i class="fa fa-times"></i></a>
                        <div class="cont">
                            <div class="pull-right">
                                <?= CHtml::numberField("FunCount[".$place->id."]", $item->getQuantity(), array(
                                    "min"=>1,
                                    "class"=>"funCount",
                                    "data-sector_id"=>$place->id,
                                    "data-event_id"=>$item->event_id
                                ))?> шт.
                            </div>
                            <strong><?= $place->typeSector ? $place->typeSector->name . ":" : "" ?> </strong><?= $place->name ?>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <?php
                }
            }
        }
        ?>
    </div>
    <hr/>
    <div class="summary">
        <p><span>Кількість:</span> <strong><?= $shoppingCart->getItemsCount();?> шт.</strong></p>
        <p><span>Сума:</span> <strong><?= number_format($shoppingCart->getCost(), 0, ".", " ")?> грн</strong></p>
    </div>
    <?= CHtml::link("Надрукувати квитки", array("/order/cashier/saveAndPrint"), array(
        "class"=>"btn btn-success btn-full saveAndPrint",
        "target"=>"_blank"
    ));?>
    <div class="checkbox">
    </div>
    <div class="row">
        <div class="col-xs-6 pull-right">
            <button class="btn btn-xs btn-success btn-full" data-toggle="modal" data-target="#order-modal">Забронювати</button>
        </div>
    </div>
</div>
