<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 18.12.15
 * Time: 16:24
 * @var $this CashierController
 * @var $model Order
 */

?>
<div class="pull-left">
    <h4>#<?= $model->id?> - Перегляд замовлення</h4>
</div>
<div class="pull-right">
    <?= CHtml::link("Редагувати", "#", array(
        "data-id"=>$model->id,
        "class"=>"edit_order"
    ));?>
</div>
<?php
$delivery = array();
if ($model->delivery) {
    $delivery = array(
        array(
            "label"=>"Країна",
            "type"=>"raw",
            "value"=>$model->delivery->city->country->name
        ),
        array(
            "label"=>"Область",
            "type"=>"raw",
            "value"=>$model->delivery->city->region ? $model->delivery->city->region->name : null
        ),
        array(
            "label"=>"Місто",
            "type"=>"raw",
            "value"=>$model->delivery->city->name
        ),
        array(
            "label"=>"Адреса/№ Відділення НП",
            "type"=>"raw",
            "value"=>$model->delivery->address
        ),
    );
} else
    $delivery = array(
        array(
            "label"=>"Країна",
            "type"=>"raw",
            "value"=>""
        ),
        array(
            "label"=>"Область",
            "type"=>"raw",
            "value"=>""
        ),
        array(
            "label"=>"Місто",
            "type"=>"raw",
            "value"=>""
        ),
        array(
            "label"=>"Адреса/№ Відділення НП",
            "type"=>"raw",
            "value"=>""
        ),
    );
$this->widget("booster.widgets.TbDetailView", array(
    "data"=>$model,
    "attributes"=>array_merge(array(
        "surname",
        "name",
        "patr_name",
        "phone",
        "email",
        "np_number",
        "comment"
    ), $delivery)
));

$this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Закрити',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )
);