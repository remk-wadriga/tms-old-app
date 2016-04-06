<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 09.07.15
 * Time: 12:29
 * @var $data Ticket
 */

$data->place->event->getTicket();
$ticket = $data->place->event->e_ticket;
$type = $data->place->event->barcode_type;
if (isset($barcode)) {
    $data->barcode = $barcode;
} else {
    $barcode = Yii::app()->controller->widget("application.extensions.phpbarcode.PhpBarcodePng", array("code" => $data->code, "type" => Event::$barcodeType[$type]));
    $data->barcode = 'data:image/png;base64,' . $barcode->image;
}
if (isset($pdf) && $pdf) echo '<div class="pdf">';
echo EventTicket::replace($data->place->event, $ticket['ticket'], $data);
if (isset($pdf) && $pdf) echo '</div>';
?>
<style>
    <?= $ticket['style']?>
</style>