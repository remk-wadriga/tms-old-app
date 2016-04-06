<?php
/**
 *
 * @var CashierController $this
 * @var $form TbActiveForm
 * @var $is_admin @desc current user is admin of current role
 */?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Статистика</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'=>'cashier-statistic'
    ))
    ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-4">
                                <?php
                                echo $form->dropDownListGroup($model, "city_id", array(
                                    'label'=>'Місто проведення',
                                    'widgetOptions'=>array(
                                        "data"=>City::getCityList(),
                                        "htmlOptions"=>array(
                                            "class"=>"to-select2",
                                            "multiple"=>"multiple",
                                            "placeholder"=>"Виберіть місто"
                                        )
                                    )
                                ))
                                ?>
                            </div>
                            <div class="col-xs-8">
                                <div class="form-group">
                                    <label>Назва події</label>
                                    <div class="pull-right">
                                        <?php

                                        echo $form->checkboxListGroup($event, "status", array(
                                            "label"=>false,
                                            "widgetOptions"=>array(
                                                "data"=>array(
                                                    Event::STATUS_ACTIVE=>" Активні",
                                                    Event::STATUS_NO_ACTIVE=>" Не активні"
                                                ),
                                                "htmlOptions"=>array(
                                                    "labelOptions"=>array(
                                                        "class"=>"checkbox-inline m-r-lg"
                                                    ),
                                                )
                                            ),
                                            "groupOptions"=>array(
                                                "style"=>"margin-bottom:0!important;"
                                            )
                                        ));
                                        ?>

                                    </div>
                                    <?php
                                    $events = Event::getListEvents((is_array($event->status)? $event->status: false));

                                    echo $form->dropDownList($model, "event_id",$events['data'], array(
                                        'allowClear' => true,
                                        'class' => 'to-select2-ext',
                                        'options' => $events['options'],
                                        'multiple'=>'multiple'
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="filter-block">
                                    <div class="pull-left filter-icon"><i class="fa fa-users"></i></div>
                                    <div class="filter-cont">
                                        <div class="row">
                                            <div class="col-xs-<?=$is_admin ? 7 : 2 ?>">
                                                <div class="row">
                                                    <?php
                                                        if($is_admin) {
                                                            ?>
                                                            <div class="col-xs-6">
                                                                <div class="form-group">
                                                                    <label class="block m-b-lg">Каси</label>
                                                                    <?php
                                                                    $print_users = Ticket::getPrintUsers(true);
                                                                    echo $form->dropDownList($model, "print_role", $print_users['roles'], array(
                                                                        "empty" => "Виберіть касу",
                                                                        "class" => "to-select2 role",
                                                                        "data-type" => "creator",
//                                                                        "multiple"=>true
                                                                    ));
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-6">
                                                                <div class="form-group">
                                                                    <label class="block m-b-lg">Касири</label>
                                                                    <?php
                                                                    echo $form->dropDownList($model, "print_author", $print_users['users'], array(
                                                                        "empty" => "Виберіть касира",
                                                                        "class" => "to-select2 creator_user",
//                                                                        "multiple"=>true
                                                                    ));
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                    <div class="filter-cont">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="col-xs-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <?= $form->checkBox($model, "period")?>Період створення
                                                        </label>
                                                    </div>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <?php echo $form->textField($model, "start_period", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>
                                                        <span class="input-group-addon">-</span>
                                                        <?php echo $form->textField($model, "end_period", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>

                                                    </div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <?= $form->checkBox($model, "pay_time")?>Період оплати
                                                        </label>
                                                    </div>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <?php echo $form->textField($model, "pay_start", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>
                                                        <span class="input-group-addon">-</span>
                                                        <?php echo $form->textField($model, "pay_end", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>

                                                    </div>
                                                </div>
                                                <div class="col-xs-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <?= $form->checkBox($model, "print_time")?>Період друку
                                                        </label>
                                                    </div>
                                                    <div class="input-daterange input-group" id="datepicker">
                                                        <?php echo $form->textField($model, "print_start", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>
                                                        <span class="input-group-addon">-</span>
                                                        <?php echo $form->textField($model, "print_end", array(
                                                            "class"=>"input-sm form-control"
                                                        ))?>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                    <div class="filter-cont">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <?php
                                                echo $form->checkboxListGroup($model, "ticketStatus", array(
                                                    "label"=>"Cтатус квитка",
                                                    "widgetOptions"=>array(
                                                        "data"=>array(
                                                            Ticket::STATUS_SOLD =>" Активний",
                                                            Ticket::STATUS_CANCEL=>" Скасований",
                                                        ),
                                                        "htmlOptions"=>array(
                                                            "labelOptions"=>array(
                                                                "class"=>"checkbox-inline"
                                                            ),
                                                            "container"=>false,
                                                        )
                                                    )
                                                ));
                                                ?>
                                            </div>
                                            <div class="col-xs-3">
                                                <?= $form->checkboxListGroup($model, 'type',array(
                                                    "label"=>"Типи замовлень",
                                                    "widgetOptions"=>array(
                                                        "data"=>array(
                                                            Order::TYPE_ORDER => "Замовлення",
                                                            Order::TYPE_QUOTE => "Квоти"
                                                        ),
                                                        "htmlOptions"=>array(
                                                            "labelOptions"=>array(
                                                                "class"=>"checkbox-inline"
                                                            ),
                                                            "container"=>false,
                                                        )
                                                    )
                                                ));?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-xs-3">
                        <label>Які квитки враховувати :</label><br/>
                        <?php
                         echo $form->checkBoxList($cashierPercent,"statisticTypes",CashierPercent::$types,["checked"=>true]);
                        ?>
                        <button class="btn btn-success block m-t-lg btn-full">Показати</button>
                    </div>
                </div>
            </div>

        </div>
    <?php $this->endWidget()?>
    <hr/>
    <table class="table table-bordered table-condensed table-statistic">
        <thead>
        <tr>
            <th rowspan="3">№</th>
            <th rowspan="3">Подія/касир</th>
            <?php if(!is_array($cashierPercent->statisticTypes)) $cashierPercent->statisticTypes = [];?>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes) || in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) {
                $colNum = 3;
                if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes) && in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) {
                    $colNum = 6;
                }
                ?><th colspan="<?=$colNum?>">Касир отримав кошти</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><th colspan="3" rowspan="2">Касир не отримував кошти</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_INVITE_SALE,$cashierPercent->statisticTypes)) { ?><th rowspan="2">Запрошення</th><?php }?>
            <th colspan="4" rowspan="2">Разом (без запрошень)</th>
        </tr>
        <tr>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><th colspan="3">оформлено касиром (прямий продаж)</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><th colspan="3">самовивіз з каси</th><?php }?>
        </tr>
        <tr>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><th>шт</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><th>на суму</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><th>%, грн</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><th>шт</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><th>на суму</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><th>%, грн</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><th>шт</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><th>на суму</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><th>%, грн</th><?php }?>
            <?php if (in_array(CashierPercent::TYPE_INVITE_SALE,$cashierPercent->statisticTypes)) { ?><th>шт</th><?php }?>
            <th>шт</th>
            <th>на суму</th>
            <th>%, грн</th>
            <th>до інкасації</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $fullSaleCountSum = 0;
            $fullSaleSumSum = 0;
            $fullSalePercentSum = 0;
            $cashSaleCountSum = 0;
            $cashSaleSumSum = 0;
            $cashSalePercentSum = 0;
            $printSaleCountSum = 0;
            $printSaleSumSum = 0;
            $printSalePercentSum = 0;
            $inviteCountSum = 0;
            $allCountSum = 0;
            $allSumSum = 0;
            $allPercentSum = 0;
            $allSumSum = 0;
            $allSumSumWP = 0;
            foreach ($cashierStatistic as $event => $cashiers ) {

                $i = 1;
                ?>
                <tr class="success">
                    <td colspan="16"><?=$event?></td>
                </tr>
                <?php
                foreach ($cashiers as $cashier=>$data) {

                    $fullSaleCountSum +=$data["fullSaleCount"];
                    $fullSaleSumSum   +=$data["fullSaleSum"];
                    $fullSalePercentSum   +=$data["fullSalePercent"];
                    $cashSaleCountSum   +=$data["cashSaleCount"];
                    $cashSaleSumSum   +=$data["cashSaleSum"];
                    $cashSalePercentSum  +=$data["cashSalePercent"];
                    $printSaleCountSum   +=$data["printSaleCount"];
                    $printSaleSumSum  +=$data["printSaleSum"];
                    $printSalePercentSum   +=$data["printSalePercent"];
                    $inviteCountSum   +=$data["inviteCount"];
                    $allCountSum   +=$data["fullSaleCount"]+$data["cashSaleCount"]+$data["printSaleCount"];
                    $allPercentSum    +=$data["fullSalePercent"]+$data["cashSalePercent"]+$data["printSalePercent"];
                    $allSumSum += ($data["fullSaleSum"]+$data["cashSaleSum"]+$data["printSaleSum"]);
                    $allSumSumWP  +=($data["fullSaleSum"]+$data["cashSaleSum"]+$data["printSaleSum"]) - ($data["fullSalePercent"]+$data["cashSalePercent"]+$data["printSalePercent"]);
                    ?>

                    <tr>
                        <td><?=$i?></td>
                        <td><?=$cashier?></td>
                        <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["fullSaleCount"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["fullSaleSum"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["fullSalePercent"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["cashSaleCount"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["cashSaleSum"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["cashSalePercent"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["printSaleCount"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["printSaleSum"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["printSalePercent"]?></td><?php }?>
                        <?php if (in_array(CashierPercent::TYPE_INVITE_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$data["inviteCount"]?></td><?php }?>
                        <td><?=$data["fullSaleCount"]+$data["cashSaleCount"]+$data["printSaleCount"]?></td>
                        <td><?=$data["fullSaleSum"]+$data["cashSaleSum"]+$data["printSaleSum"]?></td>
                        <td><?=$data["fullSalePercent"]+$data["cashSalePercent"]+$data["printSalePercent"]?></td>
                        <td><?=($data["fullSaleSum"]+$data["cashSaleSum"]+$data["printSaleSum"]) - ($data["fullSalePercent"]+$data["cashSalePercent"]+$data["printSalePercent"])?></td>
                    </tr>
                    <?php
                    $i++;
                }
                    ?>

                <?php
            }
        ?>
        </tbody>
        <tfoot>
        <tr class="info">
            <td></td>
            <td>Всього по звіту:</td>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$fullSaleCountSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$fullSaleSumSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_FULL_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$fullSalePercentSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$cashSaleCountSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$cashSaleSumSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_CASH_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$cashSalePercentSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$printSaleCountSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$printSaleSumSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_PRINT_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$printSalePercentSum?></td><?php }?>
            <?php if (in_array(CashierPercent::TYPE_INVITE_SALE,$cashierPercent->statisticTypes)) { ?><td><?=$inviteCountSum?></td><?php }?>
            <td><?=$allCountSum?></td>
            <td><?=$allSumSum?></td>
            <td><?=$allPercentSum?></td>
            <td><?=$allSumSumWP?></td>
        </tr>
        </tfoot>
    </table>
</div>
