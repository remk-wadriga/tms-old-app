<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 08.07.15
 * Time: 17:55
 * @var $data Ticket
 */

$data->place->event->getTicket();
$ticket = $data->place->event->blank;
$type = $data->place->event->barcode_type;
$barcode = Yii::app()->controller->widget("application.extensions.phpbarcode.PhpBarcodePng", array("code"=>$data->code, "type"=>Event::$barcodeType[$type]));
$data->barcode = 'data:image/png;base64,'.$barcode->image;
echo EventTicket::replace($data->place->event, $ticket['ticket'], $data);
?>
<style>
    <?php if (User::isRotateTicket(Yii::app()->user->id)): ?>
    .ticket_layout {
        -ms-transform: rotate(180deg)!important;
        -webkit-transform: rotate(180deg)!important;
        transform: rotate(180deg)!important;
    }
    <?php endif; ?>
    <?= $ticket['style']?>
</style>
