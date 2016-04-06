<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 04.06.15
 * Time: 12:43
 *
 * @var OrderList
 * @var $cashier bool (accessing from cashier find orders)
 * @var $this OrderList
 */

$this->controller->renderPartial("application.widgets.orderListWidget.views._ticketDetail", array(
    "dataProvider"=> new CArrayDataProvider(array())
));
$item = "_orders";
if ($cashier)
    $item = "_ordersCashier";
$this->beginWidget("booster.widgets.TbModal", array(
    "id"=>"order-detail"
));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4></h4>
    </div>

    <div class="modal-body order-body">

    </div>

<div class="modal-footer">

</div>
<?php
$this->endWidget();
$this->widget('CustomListView', array(
    "id"=>"orderList",
    "dataProvider"=>$dataProvider,
    "itemView"=>"$item",
    "summaryText"=>'Знайдено замовлень <span class="badge bg-success">{count}</span>',
    "summaryCssClass"=>"panel-heading fs-16",
    "itemsCssClass"=>"panel-body",
    "pagerCssClass"=>"block-pagination m-t-sm m-b-sm",
    "pager"=>array(
        "pages"=>$pagination instanceof CPagination ? $pagination: null,
        "lastPageLabel"=>false,
        "firstPageLabel"=>false,
        "nextPageLabel"=>">",
        "prevPageLabel"=>"<",
        "header"=>"",
        "htmlOptions"=>array(
            "class"=>"pagination pagination-sm m-n"
        )
    ),
));
