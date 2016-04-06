<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 03.06.15
 * Time: 15:44
 *
 * @var $data Ticket
 * @var $event Event
 * @var $place Place
 */
$place = $data->place;
$event = $place->event;

?>
<article class="ticket-item">
    <div class="pull-left">
        <input type="checkbox" <?php echo Yii::app()->shoppingCart->contains($data->getId()) ? "checked" : "" ?>
               class="child_<?= $data->order_id ?> oneTicket" data-status="<?= $data->status ?>"
               data-id="<?= $data->id ?>">
    </div>
    <div class="m-l-lg">
        <div class="pull-left">
            <strong>
                <?= $event->name;?>
            </strong> / <?= $event->scheme->location->city->name;?> /
            <em class="text-sm">
                <?= $event->getStartTime();?>
            </em>
        </div>
        <div class="pull-right">
            <strong class="fs-16 m-r-lg"><?=$data->code?></strong>
            <?php echo CHtml::link("Історія", array("/order/order/getTicketHistory", "model_id"=>$data->id), array(
                    "class"=>"text-mutted text-sm m-r",
                    "target"=>"_blank"
                ));
            echo CHtml::link("Детально", "#", array(
                "class"=>"showTicketDetails text-mutted text-sm",
                "data-id"=>$data->id
            ));
            ?>

        </div>
        <div class="clearfix"></div>
        <div class="row m-t">
            <div class="col-sm-2">
                <div>
                    <?= $place->sector->typeSector ? $place->sector->typeSector->name : "";?>:
                    <strong class="m-l-sm">
                        <?= $place->sector->name;?>
                    </strong>
                </div>
                <div>
                    <?php
                    if ($place->type == Place::TYPE_SEAT) :
                        ?>
                        <?= $place->rowName;?>: <strong class="m-r-lg"><?= $place->editedRow;?></strong>
                        <?=$place->placeName?>: <strong><?=$place->editedPlace?></strong>
                        <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="col-sm-1">
                <strong><?= number_format($data->price-$data->discount, 0, ".", " ");?> грн</strong>
            </div>
            <div class="col-sm-1">
                <strong class="block"><?= $data->payType?></strong>
                <strong class="block<?=($data->pay_status==Ticket::PAY_PAY)?' text-success':''?><?=($data->pay_status==Ticket::PAY_INVITE)?' text-warning':''?>"><?=$data->payStatus?></strong>
            </div>
            <div class="col-sm-2">
                <strong class="block"><?= Ticket::getDeliveryType($data->delivery_type)?></strong>
                <strong class="block"><?= $data->deliveryStatus?></strong>
            </div>
            <div class="col-sm-2">
                <?php
                    $printColor = '';
                    if($data->date_print)
                        $printColor = "style=\"color:green;\""
                ?>
                <strong class="block" <?=$printColor?>><?= $data->statusPrint?></strong>
            </div>
            <div class="col-sm-1">
                <?php
                $statusColor = '';
                if($data->status == Ticket::STATUS_CANCEL)
                    $statusColor = "style=\"color:red;\""
                ?>
                <strong class="block" <?=$statusColor?>><?= $data->getStatus()?></strong>
            </div>
            <div class="col-sm-1">
                <strong class="block"><?= Ticket::getBlankType($data->type_blank)?></strong>
            </div>
            <div class="col-sm-2">
                <strong>Теги:</strong>
                <div>
                    <?php
                    if ($data->tag != "") {
                        $tags = explode(",", $data->tag);
                        foreach ($tags as $tag) {
                            ?>
                            <span class="label bg-light m-r"><?= $tag?> </span>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</article>