<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 09.07.15
 * Time: 16:39
 */
$this->widget('zii.widgets.CListView', array(
    'id'=>'ticket-list',
    'dataProvider'=>$dataProvider,
    'itemView'=>'application.modules.order.views.order._e_ticket',
    'template'=>'{items}',

));