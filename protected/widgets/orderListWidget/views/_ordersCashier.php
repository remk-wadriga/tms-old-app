<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 21.12.2015
 * Time: 13:25
 *
 * @var $data Order
 * @var $this OrderController
 */
$tickets = $data->tickets;
$active = false;
$countTickets = count($tickets);
$name = $data->surname." ".$data->name." ".$data->patr_name;
$phone = $data->phone? " | ".$data->phone: "";
$email = $data->email? " | ".$data->email: "";

foreach ($tickets as $ticket){
    if($ticket->status !== Ticket::STATUS_CANCEL && !$ticket->date_print) {
        $active = true;
        break;
    }
}
?>
<section class="panel">
    <header class="panel-heading panel-order">
        <ul class="nav nav-pills pull-right">
            <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
        </ul>
        <div class="row">
            <div class="col-xs-1">
                <label class="block">
                    <?php
                        if($active){
                    ?>
                            <input type="checkbox" class="selectChild" data-id="<?= $data->id ?>">
                    <?php
                        }
                    ?>
                    # <?= $data->id ?></label>
            </div>
            <div class="col-xs-2">
                <div><?= number_format($data->total, 0, ".", " ");?> грн</div>
                <div><?= $countTickets. " ".User::plural_form($countTickets, array("квиток", "квитки", "квитків"));?></div>
            </div>
            <div class="col-xs-3">
                <div><span class="text-muted">Доставка:</span> <?= $data->getTicketsDeliveries()?></div>
                <div><span class="text-muted">Оплата:</span> <?= $data->getTicketsPayTypes()?></div>
            </div>
            <div class="col-xs-4">
                <div><strong><?= $name.$phone.$email." ".($data->quote ? "Квота (не для друку)" :"");?> </strong></div>
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
            "itemView"=>"_ticketsCashier",
            "separator"=>'<div class="line line-sm"></div>',
            "id"=>"listOrder_".$data->id

        ));
        ?>
    </section>
</section>