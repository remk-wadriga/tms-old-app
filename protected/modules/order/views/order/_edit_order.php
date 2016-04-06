<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 17.12.15
 * Time: 11:19
 * @var $this OrderController
 * @var $form TbActiveForm
 * @var $model Order
 */
?>
<div style="height: 45px;">
    <div class="pull-left">
        <h4>#<?= $model->id?> - Редагування замовлення</h4>
    </div>
    <div class="pull-right">
        <?= CHtml::link("Перегляд", "#", array(
            "data-id"=>$model->id,
            "class"=>"showOrderDetail"
        ));?>
    </div>
</div>
<?php
$form = $this->beginWidget("booster.widgets.TbActiveForm", array(
    'id'=>'edit-order'.$model->id,
    'method'=>'POST',
    'action'=>$this->createUrl('saveOrderDetail'),
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    )
));

echo $form->hiddenField($model, "id");
echo $form->textFieldGroup($model, "name");
echo $form->textFieldGroup($model, "surname");
echo $form->emailFieldGroup($model, "email");
echo $form->telFieldGroup($model, "phone");

if ($model->delivery) {
    $delivery = $model->delivery;
    $data = array(
        "id"=>$model->delivery->city_id,
        "text"=>$model->delivery->city->name
    );
    Yii::app()->clientScript->registerScript("initCity", '
                            $("#Delivery_city_id").select2({
                                data:['.json_encode($data).']
                            }).val("'.$model->delivery->city_id.'").trigger("change")
                        ', CClientScript::POS_LOAD);
}
echo $form->dropDownListGroup($delivery, "country_id", array(
    "widgetOptions"=>array(
        "data"=>Country::getCountryList(),
        "htmlOptions"=>array(
            "empty"=>"",
            "disabled"=>"disabled"
        )
    )
));



echo $form->select2Group($delivery, "city_id", array(
    'widgetOptions' => array(
        'asDropDownList' => true,
        'htmlOptions' => array(
            'multiple' => false,
            'placeholder' => 'Виберіть зі списку населений пункт',
            "disabled"=>"disabled"
        ),
        "options"=>array(
            'ajax' => array(
                'dataType' => 'json',
                'url' => CController::createUrl('/location/location/getCitiesById'),
                'data' => 'js:function (params) {
                                return {
                                    id: $("#Delivery_country_id").val(), // search term
                                    text: params,
                                    all: true
                                };
                            }',
                'results'=> 'js:function (data) {
                                return {
                                    results: data
                                };
                            },
                            cache: true'
            ),
            'minimumInputLength'=> '2',
        ),

    ),
));
echo $form->textFieldGroup($delivery, "address", array(
    "widgetOptions"=>array(
        "htmlOptions"=>array(
            "disabled"=>"disabled"
        )
    )
));



echo $form->textFieldGroup($model, "np_number");
echo $form->textAreaGroup($model, "comment");
echo CHtml::linkButton('Зберегти', array(
    "class"=>"btn btn-primary saveOrderDetail"
));
//$this->widget(
//    'booster.widgets.TbButton',
//    array(
//        'context' => 'primary',
//        'buttonType' => 'ajaxSubmit',
//        'label' => 'Зберегти',
//        'url' => $this->createUrl('saveOrderDetail'),
//        'htmlOptions' => array(
//
//        )
//    )
//);
$this->widget(
    'booster.widgets.TbButton',
    array(
        'label' => 'Закрити',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    )
);

$this->endWidget();

Yii::app()->clientScript->registerScript("isScript", "
    function isScript()
    {
        return true;
    }
", CClientScript::POS_END);