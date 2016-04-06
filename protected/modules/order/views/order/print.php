<?php
/**
 *
 * @var OrderController $this
 * @var CArrayDataProvider $dataProvider
 */
    $view = "_ticket";
    if ($type == Ticket::TYPE_A4)
        $view = "_e_ticket";
    $this->widget('zii.widgets.CListView', array(
        'id'=>'ticket-list',
        'dataProvider'=>$dataProvider,
        'itemView'=>$view,
        'template'=>'{items}',
        'htmlOptions' => array(
            'class' => ''
        )
    ));
?>



