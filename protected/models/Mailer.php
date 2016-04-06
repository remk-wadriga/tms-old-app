<?php

/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 01.12.2015
 * Time: 12:46
 */
Yii::import('application.modules.order.models.*');
Yii::import('application.modules.location.models.*');
Yii::import('application.modules.event.models.*');
Yii::import('application.modules.configuration.models.*');
class Mailer
{
    const PAY_INVITE = 'INVITE_';
    const PRINTED = 'PRINT_MESSAGE';
    const CREATE = 'CREATE_MESSAGE';
    const SUCCESS = 'SUCCESS_MESSAGE';
    const NO_MESSAGE = 0;
    const CANCEL_ORDER_MESSAGE = 1;
    const CANCEL_INVITE_MESSAGE = 2;
    const PRINT_INVITE_MESSAGE = 3;
    const IN_KASA_CASH_CREATE_MESSAGE = 4;
    const IN_KASA_CASH_PRINT_MESSAGE = 5;
    const IN_KASA_CASH_SUCCESS_MESSAGE = 6;
    const IN_KASA_CARD_CREATE_MESSAGE = 7;
    const IN_KASA_CARD_PRINT_MESSAGE = 8;
    const IN_KASA_INVITE_CREATE_MESSAGE = 9;
    const IN_KASA_INVITE_PRINT_MESSAGE = 10;
    const IN_KASA_CARD_SUCCESS_MESSAGE = 11;
    const NP_CASH_CREATE_MESSAGE = 12;
    const NP_CASH_PRINT_MESSAGE = 13;
    const NP_CARD_CREATE_MESSAGE = 14;
    const NP_CARD_SUCCESS_MESSAGE = 15;
    const NP_CARD_PRINT_MESSAGE = 16;
    const NP_INVITE_CREATE_MESSAGE = 17;
    const NP_INVITE_PRINT_MESSAGE = 18;
    const E_TICKET_CARD_CREATE_MESSAGE = 19;
    const E_TICKET_CARD_SUCCESS_MESSAGE = 20;
    const E_TICKET_INVITE_CREATE_MESSAGE = 21;
    const COURIER_CASH_CREATE_MESSAGE = 22;
    const COURIER_CASH_PRINT_MESSAGE = 23;
    const COURIER_CARD_CREATE_MESSAGE = 24;
    const COURIER_CARD_SUCCESS_MESSAGE = 25;
    const COURIER_CARD_PRINT_MESSAGE = 26;
    const COURIER_INVITE_CREATE_MESSAGE = 27;
    const COURIER_INVITE_PRINT_MESSAGE = 28;
    public static $deliveryPref = [
        Order::IN_KASA_PAY => "IN_KASA_",
        Order::IN_KASA_ONLINE => "IN_KASA_",
        Order::NP_ONLINE => "NP_",
        Order::NP_PAY => "NP_",
        Order::COURIER_ONLINE => "COURIER_",
        Order::COURIER_PAY => "COURIER_",
        Order::E_ONLINE => "E_TICKET_"
    ];
    public static $payPref = [
        Order::PAY_CARD => "CARD_",
        Order::PAY_CASH => "CASH_",
    ];
    public static $getConst = [
        '' => self::NO_MESSAGE,
        'CANCEL_ORDER_MESSAGE' => self::CANCEL_ORDER_MESSAGE,
        'CANCEL_INVITE_MESSAGE' => self::CANCEL_INVITE_MESSAGE,
        'IN_KASA_CASH_PRINT_MESSAGE' => self::IN_KASA_CASH_PRINT_MESSAGE,
        'IN_KASA_CARD_PRINT_MESSAGE' => self::IN_KASA_CARD_PRINT_MESSAGE,
        'IN_KASA_INVITE_PRINT_MESSAGE' => self::IN_KASA_INVITE_PRINT_MESSAGE,
        'IN_KASA_CASH_SUCCESS_MESSAGE' => self::IN_KASA_CASH_SUCCESS_MESSAGE,
        'IN_KASA_CASH_CREATE_MESSAGE' =>  self::IN_KASA_CASH_CREATE_MESSAGE,
        'IN_KASA_CARD_CREATE_MESSAGE' => self::IN_KASA_CARD_CREATE_MESSAGE,
        'IN_KASA_INVITE_CREATE_MESSAGE' => self::IN_KASA_INVITE_CREATE_MESSAGE,
        'IN_KASA_CARD_SUCCESS_MESSAGE' => self::IN_KASA_CARD_SUCCESS_MESSAGE,
        'NP_CASH_CREATE_MESSAGE' => self::NP_CASH_CREATE_MESSAGE,
        'NP_CASH_SUCCESS_MESSAGE' => self::NP_CASH_CREATE_MESSAGE,
        'NP_CASH_PRINT_MESSAGE' => self::NP_CASH_PRINT_MESSAGE,
        'NP_CARD_CREATE_MESSAGE' => self::NP_CARD_CREATE_MESSAGE,
        'NP_CARD_SUCCESS_MESSAGE' => self::NP_CARD_SUCCESS_MESSAGE,
        'NP_CARD_PRINT_MESSAGE' => self::NP_CARD_PRINT_MESSAGE,
        'NP_INVITE_CREATE_MESSAGE' => self::NP_INVITE_CREATE_MESSAGE,
        'NP_INVITE_PRINT_MESSAGE' => self::NP_INVITE_PRINT_MESSAGE,
        'E_TICKET_CARD_CREATE_MESSAGE' => self::E_TICKET_CARD_CREATE_MESSAGE,
        'E_TICKET_CARD_SUCCESS_MESSAGE' => self::E_TICKET_CARD_SUCCESS_MESSAGE,
        'E_TICKET_INVITE_CREATE_MESSAGE' => self::E_TICKET_INVITE_CREATE_MESSAGE,
        'COURIER_CASH_CREATE_MESSAGE' => self::COURIER_CASH_CREATE_MESSAGE,
        'COURIER_CASH_PRINT_MESSAGE' => self::COURIER_CASH_PRINT_MESSAGE,
        'COURIER_CARD_CREATE_MESSAGE' => self::COURIER_CARD_CREATE_MESSAGE,
        'COURIER_CARD_SUCCESS_MESSAGE' => self::COURIER_CARD_SUCCESS_MESSAGE,
        'COURIER_CARD_PRINT_MESSAGE' => self::COURIER_CARD_PRINT_MESSAGE,
        'COURIER_INVITE_CREATE_MESSAGE' =>  self::COURIER_INVITE_CREATE_MESSAGE,
        'COURIER_INVITE_PRINT_MESSAGE' => self::COURIER_INVITE_PRINT_MESSAGE
    ];

    public static $message = [
        self::NO_MESSAGE => "В цьому листі містяться дані про квитки з вашого замовлення.",
        self::CANCEL_ORDER_MESSAGE => "Ваше замовлення на сайті kasa.in.ua було скасовано.",
        self::CANCEL_INVITE_MESSAGE => "Ваші квитки-запрошення були скасовані.",
        self::IN_KASA_CASH_PRINT_MESSAGE => "Ваше замовлення оплачено та роздруковано!",
        self::IN_KASA_CARD_PRINT_MESSAGE => "Ваші квитки було роздруковано.",
        self::IN_KASA_INVITE_PRINT_MESSAGE => "Ваші квитки-запрошення було роздруковані.",
        self::IN_KASA_CASH_SUCCESS_MESSAGE => "Ваше замовлення оплачено!",
        self::IN_KASA_CASH_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                            Будь-ласка, зверніться в найближчу касу для здійснення оплати та отримання квитків.",
        self::IN_KASA_CARD_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                            Будь-ласка, здійсніть оплату квитків онлайн, перейшовши за цим посиланням.
                                            Або зверніться в найближчу касу оплати та отримання квитків.",
        self::IN_KASA_INVITE_CREATE_MESSAGE => "Вам надано квитки-запрошення!
                                                Зверніться в найближчу касу для отримання квитків.",
        self::IN_KASA_CARD_SUCCESS_MESSAGE => "Ви здійснили оплату квитків.
                                                Зверніться в найближчу касу для отримання квитків!",
        self::NP_CASH_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                        Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::NP_CASH_PRINT_MESSAGE => "Ваші квитки були надруковані та надіслані на вказане Вами відділення Нової пошти:
                                    Країна, Місто, Відділення Нової пошти NoХ",
        self::NP_CARD_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                        Будь-ласка, здійсніть оплату квитків онлайн, перейшовши за цим посиланням.
                                        Після цього Ваші квитки будуть надіслані Вам на вказане Вами відділення Нової пошти.",
        self::NP_CARD_SUCCESS_MESSAGE => "Ви здійснили оплату квитків.
                                        Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::NP_CARD_PRINT_MESSAGE => "Ваші квитки були надруковані та надіслані на вказане відділення Нової пошти:
                                        Країна, Місто, Відділення Нової пошти NoХ",
        self::NP_INVITE_CREATE_MESSAGE => "Вам надано квитки-запрошення.
                                        Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::NP_INVITE_PRINT_MESSAGE => "Ваші квитки були надруковані та надіслані на вказане відділення Нової пошти:
                                        Країна, Місто, Відділення Нової пошти NoХ",
        self::E_TICKET_CARD_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                            Будь-ласка, здійсніть оплату квитків онлайн, перейшовши за цим посиланням.",
        self::E_TICKET_CARD_SUCCESS_MESSAGE => "Квитки оплачено!
                                                Ваші електронні квитки прийдуть на вказану пошту.",
        self::E_TICKET_INVITE_CREATE_MESSAGE => "Вам надано квитки-запрошення.
                                                Ваші електронні квитки прийдуть на вказану пошту.",
        self::COURIER_CASH_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                            Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::COURIER_CASH_PRINT_MESSAGE => "Ваші квитки надруковані та будуть надіслані за вказаною адресою:
                                            Країна, Місто
                                            Адреса
                                            Прізвище Ім’я По батькові",
        self::COURIER_CARD_CREATE_MESSAGE => "Цей лист є підтвердженням створення замовлення на сайті kasa.in.ua!
                                            Будь-ласка, здійсніть оплату квитків онлайн, перейшовши за цим посиланням.
                                            Після цього Ваші квитки будуть надіслані Вам за вказаною адресою.",
        self::COURIER_CARD_SUCCESS_MESSAGE => "Ви здійснили оплату квитків.
                                            Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::COURIER_CARD_PRINT_MESSAGE => "Ваші квитки надруковані та будуть надіслані за вказаною адресою:
                                            Країна, Місто
                                            Адреса
                                            Прізвище Ім’я По батькові",
        self::COURIER_INVITE_CREATE_MESSAGE => "Вам надано квитки-запрошення.
                                                Найближчим часом Ви отримаєте повідомлення про відправку квитків.",
        self::COURIER_INVITE_PRINT_MESSAGE => "Ваші квитки-запрошення надруковані та будуть надіслані за вказаною адресою:
                                                Країна, Місто
                                                Адреса
                                                Прізвище Ім’я По батькові"
    ];


    public static function mail($e_tickets = [])
    {
        $changedTicketsData = Mail::model()->with("ticket")->findAll(array("limit"=>300));
        $order_ids = [];
        $changedTicketsByOrder = [];
        $changed_ids = [];
        foreach ($changedTicketsData as $changedTicket) {
            if(!in_array($changedTicket->ticket->order_id,$order_ids))
                $order_ids[] = $changedTicket->ticket->order_id;
            if(isset($changedTicketsByOrder[$changedTicket->ticket->order_id])) {
                $pastT = $changedTicketsByOrder[$changedTicket->ticket->order_id];
                if (strtotime($pastT->date_add) < strtotime($changedTicket->date_add))
                    $changedTicketsByOrder[$changedTicket->ticket->order_id] = $changedTicket;
            } else
                $changedTicketsByOrder[$changedTicket->ticket->order_id] = $changedTicket;

            if(!in_array($changedTicket->ticket->id,$changed_ids))
                $changed_ids[] = $changedTicket->ticket->id;
        }
        if(empty($e_tickets))
            $tickets = Ticket::model()->with(["order.delivery.city.country", "place"])->findAllByAttributes(["order_id"=>$order_ids]);
        else {
            $tickets = $e_tickets;
            $tempT = current($tickets);
            $order_ids[] = $tempT->order_id;
        }

        if (!$tickets)
            return false;
        $filterByOwnerOrders = [];
        $event_ids = [];
        $events = [];
        $orders = [];
        foreach ($tickets as $ticket) {
            if (isset($filterByOwnerOrders[$ticket->order_id]))
                $filterByOwnerOrders[$ticket->order_id]["tickets"][] = $ticket;
            else
                $filterByOwnerOrders[$ticket->order_id]["tickets"] = [$ticket];

            if(!in_array($ticket->event_id, $event_ids))
                $event_ids[] = $ticket->event_id;

            if(!isset($orders[$ticket->order_id]))
                $orders[$ticket->order_id] = $ticket->order;

        }
        $eventsData = Event::getListEvents([],[],$event_ids);
        foreach ($eventsData as $event)
            $events[$event["id"]] = $event;
        foreach ($filterByOwnerOrders as $order_id=>$orderData) {
            $result = [];
            $filterByEvent = [];
            $curr_tickets = $orderData["tickets"];
            $filterByHeader = self::filterHeaders($curr_tickets);
            if(empty($e_tickets)) {
                $changed = $changedTicketsByOrder[$order_id];
                $message = self::$message[$changed->type];
            } else {
                $message = "Квитки оплачено! <br/>
                            <span style='color:red'>Ваші електронні квитки у вкладенні цього листа.</span>";
            }

            $owner_name = '';
            $owner_phone = '';
            $owner_email = '';
            $order_price = 0;
            $order_count = 0;
            $order_date = '';

            foreach ($filterByHeader as $key=>$tickets) {
                foreach ($tickets as $ticket) {
                    if(isset($filterByEvent[$key][$ticket->event_id]))
                        $filterByEvent[$key][$ticket->event_id][] = $ticket;
                    else
                        $filterByEvent[$key][$ticket->event_id] = [$ticket];
                }
            }
            $eTickets = [];
            foreach ($filterByEvent as $header_key=>$eventData) {


                $result["headers"][$header_key] = [];
                $tempData = current(current($filterByEvent[$header_key]));
                $result["headers"][$header_key]["pay_status"] = isset(Ticket::$payStatus[$tempData->pay_status]) ? Ticket::$payStatus[$tempData->pay_status] : "undefined";
                $result["headers"][$header_key]["pay_type"] =  in_array($tempData->pay_type, Order::$physicalPay) ? "Готівкою" : 'Платіжною картою';
                $result["headers"][$header_key]["delivery_status"] = isset(Ticket::$statusDelivery[$tempData->delivery_status]) ? Ticket::$statusDelivery[$tempData->delivery_status] : "undefined";
                $result["headers"][$header_key]["delivery_type"] = isset(Order::$delTypes[$tempData->delivery_type]) ? Order::$delTypes[$tempData->delivery_type] : "undefined";

                foreach ($eventData as $event_id=>$tickets) {
                    $event = $events[$event_id];
                    $date = Yii::app()->dateFormatter->format("dd.MM.yyyy", $event["timing"]);
                    $time = Yii::app()->dateFormatter->format("HH:mm", $event["timing"]);
                    $result["headers"][$header_key]["events"][$event_id]["event_name"] = $event["name"];
                    $result["headers"][$header_key]["events"][$event_id]["event_date"] = $date;
                    $result["headers"][$header_key]["events"][$event_id]["event_time"] = $time;
                    $result["headers"][$header_key]["events"][$event_id]["event_city"] = $event["city_name"];
                    $result["headers"][$header_key]["events"][$event_id]["event_location"] = $event["location_name"];
                    $result["headers"][$header_key]["events"][$event_id]["tickets"] = [];

                    foreach ($tickets as $ticket) {
                        if($ticket->type_blank == Ticket::TYPE_A4)
                            $eTickets[] = $ticket;
                        if($ticket->order->email && !$owner_email)
                            $owner_email = $ticket->order->email;
                        if($ticket->order->name && !$owner_name)
                            $owner_name = $ticket->order->surname." ".$ticket->order->name." ".$ticket->order->patr_name;
                        if($ticket->order->phone && !$owner_phone)
                            $owner_phone = $ticket->order->phone;
                        if(!$order_date)
                            $order_date = $ticket->order->date_add;

                        $price = $ticket->price;
                        $pref = '';

                        if (isset(self::$sectors[$ticket->sector_id]->typeSector))
                            $pref = self::$sectors[$ticket->sector_id]->typeSector->name. " ";


                        if ($ticket->status == Ticket::STATUS_CANCEL)
                            $price = false;
                        $order_price += $price;
                        $order_count++;

                        $result["headers"][$header_key]["events"][$event_id]["tickets"][] =
                            ["sector"=>$pref.(isset(self::$sectors[$ticket->sector_id]->name)?self::$sectors[$ticket->sector_id]->name:""),
                            "row"=>(isset($ticket->place)?$ticket->place->row:""),
                            "place"=>(isset($ticket->place)?$ticket->place->place:""),
                                                                                "price"=> $price !== false ? $price : "скасований"];

                    }
                }

            }

            $result["ticketsCount"] = $order_count;
            $result["ticketsCountLabel"] = self::generateTicketCountLabel($order_count);
            $result["ticketsPrice"] = $order_price;
            $result["owner_name"] = $owner_name;
            $result["owner_phone"] = $owner_phone;
            $result["owner_mail"] = $owner_email;
            $result["order_number"] = $order_id;
            $order = $orders[$order_id];
            if($order->delivery){
                $result["owner_city"] = $order->delivery->city->name;
                $result["owner_country"] = $order->delivery->city->country->name;
                $result["owner_address"] = $order->delivery->address;
            } else {
                $result["owner_city"] = '';
                $result["owner_country"] = '';
                $result["owner_address"] = '';
            }

            $result["order_date"] = Yii::app()->dateFormatter->format("dd.MM.yyyy", $order_date);
            $result["order_time"] = Yii::app()->dateFormatter->format("HH:mm", $order_date);

            $from = [
                "/найближчу касу/",
                "/Місто/",
                "/Країна/",
                "/Прізвище Ім’я По батькові/",
                "/NoХ/",
                "/посиланням/",
                "/Адреса/",
            ];
            $to = [
                "<a href='http://kasa.in.ua/payoffice'>найближчу касу</a>",
                $result["owner_city"],
                $result["owner_country"],
                $result["owner_name"],
                "No".$result["owner_address"],
                "<a href='http://kasa.in.ua/event/pay?order_id=$order_id'>посиланням</a>",
                $result["owner_address"],
            ];
            $message = preg_replace($from, $to, $message);
            $result["message"] = $message;
             if(empty($e_tickets))
                 self::generateMail($result,$eTickets);
             else{
 //                if call from Order->sendTickets then return message, only 1 order allowed
                 return $result;
             }

        }
        Mail::model()->deleteAllByAttributes(["ticket_id"=>$changed_ids]);
    }

    public static $sectors = array();
    private static function filterHeaders($tickets)
    {
        $types = [1];
        $array = [];
        foreach ($tickets as $ticket) {
            if (!isset(self::$sectors[$ticket->sector_id])&&isset($ticket->place))
                self::$sectors[$ticket->sector_id] = $ticket->place->sector;
            reset($types);
            if (empty($array)) {
                $array[current($types)] = [$ticket];
                continue;
            }
            $found = false;
            foreach ($types as $type) {
                $tempTicket = current($array[$type]);
                if ($ticket->pay_type == $tempTicket->pay_type && $ticket->pay_status == $tempTicket->pay_status
                    && $ticket->delivery_type == $tempTicket->delivery_type && $ticket->delivery_status == $tempTicket->delivery_status) {
                    $array[$type][] = $ticket;
                    $found = true;
                }
            }

            if(!$found) {
                $lastType = end($types);
                $newType = $lastType + 1;
                $array[$newType] = [$ticket];
                $types[] = $newType;
            }
        }
        return $array;
    }

    public static function generateTicketCountLabel($count)
    {
//        as we know max order size is 50 tickets at now, if it will be 100+ then u need to change this method a little
        $lastDigit = $count % 10;
        if($count >= 5 && $count<=20)
            return "квитків";
        else {
            if($lastDigit == 1)
                return "квиток";
            if($lastDigit > 1 && $lastDigit < 5)
                return "квитка";
            return "квитків";
        }
    }

    private static function generateMail($order,$eTickets=[])
    {
        if($order["owner_mail"]) {
            $message = Yii::app()->controller->renderPartial("application.views.mail._view", ["orderData" => $order], true, true);
//            if (!empty($eTickets)) {
//                self::sendTickets($eTickets, $message, $order["owner_mail"]);
//            } else {
                self::send($order["owner_mail"], Yii::app()->params['adminEmail'], $message, 'Замовлення №'.$order["order_number"]);
//            }
        }
    }

    private static function send($to,$from,$text,$subject='kasa.in.ua',$attachments = []){
        $message = new YiiMailMessage;
        $cid = $message->embed(Swift_Image::fromPath('img/logo_kasa_tg.png'));
        $message->setBody(str_replace("[[[cid]]]", $cid, $text), 'text/html');
        $message->subject = $subject;
        $message->addTo($to);
        $message->from = $from;
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $message->attach(Swift_Attachment::fromPath($attachment));
            }
        }
        Yii::app()->mail->send($message);
    }

    public function sendTickets($tickets,$text, $email)
    {

        $dataProvider = Order::getTicketCriteria($tickets);
        $attachments = array();
        $barcodes = array();
        foreach ($dataProvider->getData() as $data) {
            $barcode = Yii::app()->controller->widget("application.extensions.phpbarcode.PhpBarcode", array(
                "code"=>$data->code,
                "type"=>Event::$barcodeType[$data->place->event->barcode_type]
            ));
            $barcodes[$data->id] = $barcode->image;
            $message = Yii::app()->controller->renderPartial("application.modules.order.views.order._e_ticket", array(
                "data"=>$data,
                "barcode"=>$barcodes[$data->id]
            ), true);
            $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
            $data->place->event->getTicket();

            $stylesheet = $data->place->event->e_ticket['style'];
            $mPDF1->WriteHTML($stylesheet, 1);
            $mPDF1->WriteHTML($message);
            $name = uniqid($data->owner_surname."_".$data->id).".pdf";
            $mPDF1->Output($name, 'f');
            $attachments[] = Yii::getPathOfAlias("webroot")."/".$name;
        }
        self::send($email,Yii::app()->params['adminEmail'],$text,'kasa.in.ua',$attachments);

        foreach ($attachments as $file)
            unlink($file);

        foreach ($barcodes as $barcode)
            unlink($barcode);

    }


}