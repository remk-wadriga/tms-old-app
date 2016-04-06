<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.06.15
 * Time: 12:58
 * @var $this OrderList
 * @var $dataProvider CActiveDataProvider
 */

$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'ticket-detail-modal')
); ?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Детальна інформація про квиток</h4>
    </div>

    <div class="modal-body">
        <?php
        $this->widget("booster.widgets.TbGridView", array(
            "id"=>"ticket-detail-grid",
            "template"=>"{items}",
            "hideHeader"=>true,
            "dataProvider"=>$dataProvider,
            "ajaxUrl"=>$this->createUrl("/order/order/getTicketDetail"),
            "ajaxUpdate"=>"ticket-detail-grid",
            "columns"=>array(
                array(
                    "type"=>"raw",
                    "value"=>'"<strong>".$data["type"]."</strong>"'
                ),
                array(
                    "type"=>"raw",
                    "value"=>'$data["value"]'

                )
            )
        ));
        ?>
    </div>

    <div class="modal-footer">
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Закрити',
                'url' => '#',
                'htmlOptions' => array('data-dismiss' => 'modal'),
            )
        ); ?>
    </div>

<?php $this->endWidget(); ?>