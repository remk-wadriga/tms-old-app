<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 23.11.2015
 * Time: 13:28
 */
?>
<h3>Загальні відсотки винагороди</h3>
<div class="row">
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-8 text-right">
                <p class="fs-16 fb m-b-none">Прямий продаж</p>
                <div>Касир створив замовлення<br>Касир прийняв оплату<br>Касир роздрукував квитки</div>
            </div>
            <div class="col-xs-4 form-inline">
                <?=CHtml::textField('CashierPercent[order_cash_print_percent]',$data["order_cash_print_percent"],['class'=>'form-control input-sm m-b w60 n-validate'])?><span class="fs-16 fb m-l">%</span>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-8 text-right">
                <p class="fs-16 fb m-b-none">Самовивіз з каси</p>
                <div>Хтось створив замовлення<br>Касир прийняв оплату<br>Касир роздрукував квитки</div>
            </div>
            <div class="col-xs-4 form-inline">
                <?=CHtml::textField('CashierPercent[cash_print_percent]',$data["cash_print_percent"],['class'=>'form-control input-sm m-b w60 n-validate'])?><span class="fs-16 fb m-l">%</span>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="row">
            <div class="col-xs-8 text-right">
                <p class="fs-16 fb m-b-none">Друк оплачених</p>
                <div>Не важливо хто створив замовлення<br>НЕ касир прийняв оплату<br>Касир роздрукував квитки</div>
            </div>
            <div class="col-xs-4 form-inline">
                <?=CHtml::textField('CashierPercent[print_percent]',$data["print_percent"],['class'=>'form-control input-sm m-b w60 n-validate'])?><span class="fs-16 fb m-l">%</span>
            </div>
        </div>
    </div>
</div>