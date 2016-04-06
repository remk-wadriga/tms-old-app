<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 23.07.15
 * Time: 15:51
 * @var $action string
 */
?>
<div class="cashier-menu">
    <?=CHtml::link('<i class="fa fa-plus"></i> Створити замовлення', array("/order/cashier/listEvent"), array("class"=>"anim".($action=="listEvent"?" active":"")));?>
    <?=CHtml::link('<i class="fa fa-search"></i>Пошук замовлення', array("/order/cashier/orders"), array("class"=>"anim".($action=="orders"?" active":"")));?>
    <?=CHtml::link('<i class="fa fa-bar-chart-o"></i> Статистика', array("/order/cashier/statistic"), array("class"=>"anim".($action=="statistic"?" active":"")));?>
    <?=CHtml::link('<i class="fa fa-briefcase"></i> Контроль каси', array("/order/cashier/control"), array("class"=>"anim".($action=="control"?" active":"")));?>
    <?=CHtml::link('<i class="fa fa-share"></i> Завершення роботи', array("/order/cashier/close"), array("class"=>"anim pull-right".($action=="close"?" active":"")));?>
</div>