<?php
/**
 * @var $tickets Ticket[]
 * @var $result array
 */
?>
<div class="tab-content">
    <div class="tab-pane active" id="tab-1">
        <div class="row">
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Спосіб доставки</label>
                    <?php
                    echo CHtml::dropDownList("delivery_type", $result['del_type'], array(
                        Order::IN_KASA_PAY=>" Самовивіз",
                        Order::NP_PAY=>" Нова пошта з відділення",
                        Order::COURIER_PAY=>" Кур’єром по місту",
                        Order::E_ONLINE=>" Електронний квиток",
                    ), array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <label>Статус доставки</label>
                    <?php
                    echo CHtml::dropDownList("delivery_status", $result['del_status'], Ticket::$statusDelivery, array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <label>Спосіб оплати</label>
                    <?php
                    echo CHtml::dropDownList("cash_type",  $result['pay_method'], array(
                        Order::PAY_CARD=>"Платіжна система",
                        Order::PAY_CASH=>"Готівкою в касі",
                    ), array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <label>Статус оплати</label>
                    <?php
                    echo CHtml::dropDownList("pay_status", $result["pay_status"], array(
                        Ticket::PAY_PAY =>" Оплачено",
                        Ticket::PAY_NOT_PAY=>" Не оплачено",
                        Ticket::PAY_INVITE => " Запрошення"
                    ), array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <label>Статус друку</label>
                    <?php
                    echo CHtml::dropDownList("print_status", $result["print_status"], Ticket::$statusPrint, array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
                <div class="form-group">
                    <label>Активність</label>
                    <?php
                    echo CHtml::dropDownList("status", $result["status"], array(
                        Ticket::STATUS_SOLD =>" Активний",
                        Ticket::STATUS_CANCEL=>" Скасований",
                    ), array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
            </div>
            <div class="col-xs-4">
                <p class="m-b-none"><label>Створив</label><span class="m-l text-muted">покупець</span></p>
                <?php
                foreach ($result['creators'] as $creator) {?>
                    <h6 class="m-t-xs m-b-lg">
                    <?php
                        echo $creator
                    ?>
                    </h6>
                <?php
                }

                ?>
                <div class="clearfix"></div>
                <p class="m-b-none m-t-lg"><label>Платформа та гравець</label></p>
                <?php
                foreach ($result['platforms'] as $platform) {?>
                    <h6 class="m-t-xs m-b-lg">
                        <?php
                        echo $platform
                        ?>
                    </h6>
                    <?php
                }

                ?>
                <div class="clearfix"></div>
                <p class="m-b-none m-t-lg"><label>Отримав оплату</label></p>
                <?php
                foreach ($result['cashiers'] as $cashier) {?>
                    <h6 class="m-t-xs m-b-lg">
                        <?php
                        echo $cashier
                        ?>
                    </h6>
                    <?php
                }
                ?>

                <div class="clearfix"></div>
                <p class="m-b-none m-t-lg"><label>Надрукував</label></p>
                <?php
                foreach ($result['printers'] as $printer) {?>
                    <h6 class="m-t-xs m-b-lg">
                        <?php
                        echo $printer
                        ?>
                    </h6>
                    <?php
                }
                ?>
            </div>
            <div class="col-xs-4">
                <div class="form-group">
                    <label>Буде скасовано через _____ діб</label>
                    <?= CHtml::textField("date_cancel", $result['date_cancel'], array(
                        "class"=>"form-control input-sm",
                        "placeholder"=>"Буде скасовано через _____ діб"
                    ));?>
                </div>
                <div class="form-group">
                    <label>Формат</label>
                    <?php
                    echo CHtml::dropDownList("format", $result['format'], array(
                        Ticket::TYPE_BLANK =>" Бланк",
                        Ticket::TYPE_A4=>" А4",
                    ), array(
                        "class"=>"to-select2",
                        "empty"=>"------"
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <label>Теги (різні теги вписувати через кому)</label>
                    <?php
                    echo CHtml::textField("tags", $result['tags'], array(
                        "class"=>"form-control"
                    ))
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="tab-2">
        <section class="panel">
            <?php
            foreach ($tickets as $ticket) {
                $place = $ticket->place;
                ?>
                <section class="panel-body">
                    <div class="m-b">
                        <strong><?= $ticket->place->event->name?></strong> / <?= $ticket->place->event->scheme->location->city->name?> / <em class="text-sm"><?= $ticket->place->event->getStartTime()?></em>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <strong class="m-r-lg"><?= $ticket->place->sector->getSectorName()?></strong>
                            <span class="m-r-lg"><?= $place->getRowName()." ".$place->getEditedRow()?></span>
                            <span class="m-r-lg"><?= $place->getPlaceName()." ".$place->getEditedPlace()?></span>
                            <div class="pull-right">

                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row">
                                <div class="form-group col-xs-4">
                                    <label>Вартість</label>
                                    <?= CHtml::textField("Ticket[".$ticket->id."][price]", $ticket->price, array(
                                        "class"=>"form-control input-sm",
                                        "placeholder"=>"Вартість"
                                    ))?>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label>Знижка</label>
                                    <?= CHtml::textField("Ticket[".$ticket->id."][discount]", $ticket->discount, array(
                                        "class"=>"form-control input-sm",
                                        "placeholder"=>"Знижка"
                                    ))?>
                                </div>
                                <div class="form-group col-xs-4">
                                    <label>Ціна</label>
                                    <span class="block m-t-xs"><?= $ticket->price-$ticket->discount?> грн</span>
                                </div>
                            </div>

                        </div>

                        <div class="form-group col-xs-12">
                            <?= CHtml::textField("Ticket[".$ticket->id."][owner_surname]", $ticket->owner_surname, array(
                                "class"=>"form-control input-sm",
                                "placeholder"=>"Прізвище та Ім’я відвідувача"
                            ));?>
                        </div>

                    </div>
                </section>


                <?php

            }

            ?>
        </section>

    </div>
</div>