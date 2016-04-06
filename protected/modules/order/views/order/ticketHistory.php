<?php
/**
 *
 * @var OrderController $this
 * @var $dataProvider CActiveDataProvider
 */
Yii::app()->clientScript->registerCss("tableStyle", "
    #history-grid td.strong {
        font-weight: bold
    }
");

?>

<div class="col-lg-12">
        <?php
$this->widget("booster.widgets.TbGridView", array(
    "id"=>"history-grid",
    "dataProvider"=>$dataProvider,
    "htmlOptions"=>array(
        "style"=>"display:block;overflow-y:auto"
    ),
    "columns"=>array(
        array(
            "header"=>"№",
            "type"=>"raw",
            "value"=>'$data->number'
        ),
        array(
            "header"=>"Дата/Час",
            "type"=>"raw",
            "value"=>'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $data->date_create)'
        ),
        array(
            "header"=>"Користувач",
            "type"=>"raw",
            "value"=>'$data->user->fullName'
        ),
        array(
            "header"=>"Ціна",
            "type"=>"raw",
            "value"=>'$data->state["price"]',
            "cssClassExpression"=>'$data->getIsChanged("price") ? "strong": ""'
        ),
        array(
            "header"=>"Дата друку",
            "type"=>"raw",
            "value"=>'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm",$data->state["date_print"])',
            "cssClassExpression"=>'$data->getIsChanged("date_print") ? "strong": ""'
        ),
        array(
            "header"=>"Повторний друк",
            "type"=>"raw",
            "value"=>'$data->getIsChanged("date_print") ? "так":"ні"',
            "cssClassExpression"=>'$data->getIsChanged("date_print") ? "strong": ""'
        ),
        array(
            "header"=>"Дата оплати",
            "type"=>"raw",
            "value"=>'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm",$data->state["date_pay"])',
            "cssClassExpression"=>'$data->getIsChanged("date_pay") ? "strong": ""'
        ),
        array(
            "header"=>"Ім’я власника",
            "type"=>"raw",
            "value"=>'$data->state["owner_surname"]',
            "cssClassExpression"=>'$data->getIsChanged("owner_surname") ? "strong": ""'
        ),
        array(
            "header"=>"Email власника",
            "type"=>"raw",
            "value"=>'$data->state["owner_mail"]',
            "cssClassExpression"=>'$data->getIsChanged("owner_mail") ? "strong": ""'
        ),
        array(
            "header"=>"Статус",
            "type"=>"raw",
            "value"=>'Ticket::getStatusTicket($data->state["status"])',
            "cssClassExpression"=>'$data->getIsChanged("status") ? "strong": ""'
        ),
        array(
            "header"=>"Статус оплати",
            "type"=>"raw",
            "value"=>'Ticket::getStatusPay($data->state["pay_status"])',
            "cssClassExpression"=>'$data->getIsChanged("pay_status") ? "strong": ""'
        ),
        array(
            "header"=>"Статус доставки",
            "type"=>"raw",
            "value"=>'Ticket::getStatusDelivery($data->state["delivery_status"])',
            "cssClassExpression"=>'$data->getIsChanged("delivery_status") ? "strong": ""'
        ),
        array(
            "header"=>"Буде скасовано",
            "type"=>"raw",
            "value"=>'Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm",$data->state["cancel_day"])',
            "cssClassExpression"=>'$data->getIsChanged("cancel_day") ? "strong": ""'
        ),
        array(
            "header"=>"Гравець що роздрукував",
            "type"=>"raw",
            "value"=>'Role::getRoleName($data->state["print_role_id"])',
            "cssClassExpression"=>'$data->getIsChanged("print_role_id") ? "strong": ""'
        ),
        array(
            "header"=>"Користувач що роздрукував",
            "type"=>"raw",
            "value"=>'User::getUserName($data->state["author_print_id"])',
            "cssClassExpression"=>'$data->getIsChanged("author_print_id") ? "strong": ""'
        ),
        array(
            "header"=>"Гравець що отримав кошти",
            "type"=>"raw",
            "value"=>'Role::getRoleName($data->state["cash_role_id"])',
            "cssClassExpression"=>'$data->getIsChanged("cash_role_id") ? "strong": ""'
        ),
        array(
            "header"=>"Користувач що отримав кошти",
            "type"=>"raw",
            "value"=>'User::getUserName($data->state["cash_user_id"])',
            "cssClassExpression"=>'$data->getIsChanged("cash_user_id") ? "strong": ""'
        ),
        array(
            "header"=>"Тип доставки",
            "type"=>"raw",
            "value"=>'Ticket::getDeliveryType($data->state["delivery_type"])',
            "cssClassExpression"=>'$data->getIsChanged("delivery_type") ? "strong": ""'
        ),
        array(
            "header"=>"Тип квитка",
            "type"=>"raw",
            "value"=>'Ticket::getBlankType($data->state["type_blank"])',
            "cssClassExpression"=>'$data->getIsChanged("type_blank") ? "strong": ""'
        ),

    )
));

?>

        </div>
