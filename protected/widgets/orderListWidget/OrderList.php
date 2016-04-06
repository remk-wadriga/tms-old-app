
<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 04.06.15
 * Time: 12:41
 */
class OrderList extends CWidget
{
    public $dataProvider;
    public $cashier = false;
    public $pagination = array();

    public function init()
    {
        if (Yii::app()->user->getState('currentCartId') != "cartId_orderManager")
            Yii::app()->user->setState("currentCartId", "cartId_orderManager");
        Yii::app()->clientScript->registerScript("viewTicketDetail", "
            $(document).on('click', '.showTicketDetails', function(e){
                $.fn.yiiGridView.update('ticket-detail-grid', {
                    data : {
                        id : $(this).data('id')
                    },
                    beforeSend: function() {
                        $('#ticket-detail-grid').html('');
                        $('#ticket-detail-modal').modal('show');
                    }
                });
            });

            $(document).on('click', '.showOrderDetail', function(e){
                $.post('".$this->controller->createUrl('/order/order/getOrderDetail')."',
                    {
                        order_id: $(this).attr('data-id')
                    }, function(result) {
                        var obj = JSON.parse(result),
                            order_modal = $('#order-detail');
                        order_modal.find('.order-body').html(obj);
                        order_modal.modal('show');
                    }
                )
            });
            $(document).on('click', '.edit_order', function(){
                var is_script = typeof isScript !== 'undefined' && $.isFunction(isScript)
                $.post('".$this->controller->createUrl('/order/order/getOrderDetail')."',
                    {
                        order_id: $(this).attr('data-id'),
                        edit: true,
                        isScript: is_script
                    }, function(result) {
                        var obj = JSON.parse(result),
                            order_modal = $('#order-detail');
                        order_modal.find('.order-body').html(obj);
                        order_modal.modal('show');
                    }
                )
            });

            $(document).on('click', '.saveOrderDetail', function(e){
                e.preventDefault();
                $.post(
                    '".$this->controller->createUrl('/order/order/saveOrderDetail')."',
                    $(this).parents('form').serialize(),
                    function(o) {
                        var obj = JSON.parse(o);
                        if (obj == 'ok') {
                            showAlert('success', 'Успішно збережено');
                            var filter_form = $('#order-filter-form').length>0 ? $('#order-filter-form'): $('#my-order-filter-form');
                            $.fn.yiiListView.update('orderList', {
                            data:  filter_form.serialize()
                        });
                        }
                        else
                            showAlert('danger', 'Помилка збереження');

                        $('#order-detail').modal('hide');
                    }
                )
            })
        ", CClientScript::POS_READY);

    }

    public function run() {
        $this->render("orderList", array(
            "dataProvider"=>$this->dataProvider,
            "cashier"=>$this->cashier,
            "pagination"=>$this->pagination
        ));
    }
}