<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 03.06.15
 * Time: 13:56
 *
 * @var $data Order
 * @var $this OrderController
 */
$tickets = $data->tickets;

$total = array_sum(array_map(function($ticket){return $ticket->price;}, $tickets));

$countTickets = count($tickets);
$name = $data->surname." ".$data->name." ".$data->patr_name;
$phone = $data->phone? " | ".$data->phone: "";
$email = $data->email? " | ".$data->email: "";

$notInTotal = isset($this->owner->ordersTotal) && !empty($this->owner->ordersTotal) && $countTickets!=$this->owner->ordersTotal[$data->id] ? $this->owner->ordersTotal[$data->id]-$countTickets : 0;
?>
<section class="panel">
    <header class="panel-heading panel-order">
        <ul class="nav nav-pills pull-right">
            <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
        </ul>
        <div class="row">
            <div class="col-xs-1">
                <label class="block"><input type="checkbox" class="selectChild" data-id="<?= $data->id?>"> # <?= $data->id?></label>
            </div>
            <div class="col-xs-2">
                <div><?= number_format($data->total, 0, ".", " ");?> грн</div>
                <div><?= $countTickets. " ".User::plural_form($countTickets, array("квиток", "квитки", "квитків"));?>
                <?= isset($this->owner->ordersTotal) && !empty($this->owner->ordersTotal) && $countTickets!=$this->owner->ordersTotal[$data->id] ?
                        "<span class='danger'
                            data-toggle=\"tooltip\"
                            title=\"".$notInTotal." ".User::plural_form($notInTotal, array("квиток", "квитки", "квитків"))."
                             в замовленні  не ".
                             User::plural_form($notInTotal, array("задовільняє", "задовільняють", "задовільняють"))
                              ." критерій пошуку\"
                            data-placement=\"bottom\" >
                                (".$this->owner->ordersTotal[$data->id].")
                        </span>":
                        ""?>
                </div>
            </div>
            <div class="col-xs-3">
                <div><span class="text-muted">Доставка:</span> <?= $data->getTicketsDeliveries()?></div>
                <div><span class="text-muted">Оплата:</span> <?= $data->getTicketsPayTypes()?></div>
            </div>
            <div class="col-xs-4">
                <div><strong><?= $name.$phone.$email." ".($data->quote ? "Квота (не для друку)" :"");?>  </strong></div>
                <div><span class="text-muted">Коментар:</span> <span title="<?= $data->comment?>"><?php echo substr($data->comment, 0, 30)." ..."?></span></div>
            </div>
            <div class="col-xs-2">

                <div><?= $data->date_add?> | <?= CHtml::link("Детально", "#", array(
                        'class'=>'showOrderDetail',
                        'data-id'=>$data->id
                    ))?></div>
            </div>
        </div>
    </header>

    <section class="panel-body">
        <?php

        $this->widget("booster.widgets.CListView", array(
            "dataProvider"=>new CArrayDataProvider($tickets, array(
                    "pagination"=>false
                )
            ),
            "summaryText"=>false,
            "itemView"=>"_tickets",
            "separator"=>'<div class="line line-sm"></div>',
            "id"=>"listOrder_".$data->id

        ));
        ?>
    </section>
</section>
