<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 25.09.15
 * Time: 11:14
 * @var $this CashierController
 * @var $model Order
 * @var $form TbActiveForm
 */
Yii::app()->clientScript->registerScript("createOrder", '
    $(".ticketDelType").on("change", function(){
        var value = $(".ticketDelType:checked").val(),
            delivery = $(".deliveryData");
        if (value != "'.Order::IN_KASA.'") {
            if (value == "'.Order::NP.'") {
                $("#Order_address").attr("placeholder", "№ відділення НП")
                $(".order_address_label").text("№ відділення НП")
            } else if (value == "'.Order::COURIER.'") {
                $("#Order_address").attr("placeholder", "Адреса")
                $(".order_address_label").text("Адреса")
            }
            delivery.css({"visibility":"visible"});
        } else {
            delivery.css({"visibility":"hidden"});
        }
    });

    $("#Order_payment").on("change", function(){
        var value = $(".orderPayment:checked").val(),
            printButton = $("#save_print_order");
        if (value == "'.Ticket::PAY_PAY.'") {
            printButton.css({"visibility":"visible"});
        } else
            printButton.css({"visibility":"hidden"});
    })
', CClientScript::POS_READY);
?>

<div class="modal fade" id="order-modal" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
                "id"=>"createOrderForm",
                "action"=>$this->createUrl("saveAndPrint"),
                "enableAjaxValidation"=>true,
                "clientOptions"=>array(
                    "validateOnSubmit"=>true,
                    "afterValidate"=>"js:function(form, data, hasError){
                        if (hasError==false && $('#typeSave').val()=='print') {
                            $.post('".$this->createUrl("saveAndPrint")."',
                               $('#createOrderForm').serialize()
                            , function(result){
                            var obj = JSON.parse(result);
                            if (obj.order_id.length > 0) {
                                window.open('".CController::createAbsoluteUrl("/order/order/printTickets")."?order_id='+obj.order_id+'&new=true','printWindow','width=screen.width,height=screen.height');
    
                            }
                        });
                        }
                        return true;
                    }"
                )
            ));
            ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Оформлення замовлення</h4>
            </div>
            <div class="modal-body">

                <h5><strong>Спосіб доставки та оплати</strong></h5>
                <div class="row">
                    <div class="col-xs-6">
                        <?php
                        echo $form->radioButtonList($model, "ticketDeliveryType", array(
                            Order::IN_KASA => "Самовивіз",
                            Order::NP => "Доставка НП",
                            Order::COURIER => "Доставка кур’єром"
                        ), array(
                            "class"=>"ticketDelType"
                        ));
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                        echo $form->hiddenField($model, "pay_method");
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <h5><strong>Контактні дані</strong></h5>
                        <?php
                        echo $form->textFieldGroup($model, "surname", array(
                            "label"=>"Прізвище",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"Прізвище"
                                )
                            )
                        ));
                        echo $form->textFieldGroup($model, "name", array(
                            "label"=>"Ім’я",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"Ім’я"
                                )
                            )
                        ));
                        echo $form->textFieldGroup($model, "patr_name", array(
                            "label"=>"По-батькові",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"По-батькові"
                                )
                            )
                        ));
                        echo $form->textFieldGroup($model, "phone", array(
                            "label"=>"Телефон",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"Телефон"
                                )
                            )
                        ));
                        echo $form->emailFieldGroup($model, "email", array(
                            "label"=>"E-mail",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"E-mail"
                                )
                            )
                        ));
                        echo $form->textFieldGroup($model, "tags", array(
                            "label"=>"Теги",
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"Теги"
                                )
                            )
                        ));

                        echo $form->radioButtonListGroup($model, "payment", array(
                            "wrapperHtmlOptions"=>array(
                                "style"=>"margin-left:20px"
                            ),
                            "widgetOptions"=>array(
                                "data"=>array(
                                    Ticket::PAY_PAY => "Оплачено",
                                    Ticket::PAY_NOT_PAY => "Неоплачено"
                                ),
                                "htmlOptions"=>array(
                                    "class"=>"orderPayment"
                                )
                            )

                        ));

                        echo $form->textAreaGroup($model, "comment");
                        ?>




                    </div>
                    <div class="col-xs-6 deliveryData" style="visibility: hidden">
                        <h5><strong>Дані доставки</strong></h5>

                        <div class="form-group">
                            <label>Країна</label>
                            <?php
                            echo CHtml::dropDownList("country_id", "", Country::getCountryList(), array(
                                "class"=>"to-select2",
                                "placeholder"=>"Виберіть країну",
                                "empty"=>"",
                                "ajax"=>array(
                                    'type' => 'POST',
                                    'url' => CController::createUrl('/configuration/configuration/getRegions'),
                                    'data' => 'js:{id:$(this).val()}',
                                    'complete' => "js:function(result){
                                            $('#region_id').select2('destroy')
                                                .html('<option value=\'\' selected=\'selected\'></option>'+result.responseText)
                                                .select2({placeholder: 'Виберіть зі списку регіон'});
                                            $('#Order_city_id').select2('disable').select2('val','');
                                        }",
                            )));
                            ?>
                        </div>
                        <div class="form-group">
                            <label>Область</label>
                            <?php
                            echo CHtml::dropDownList("region_id","", array(),array(
                                "placeholder"=>"Виберіть регіон",
                                "class"=>"form-control to-select2",
                                "onchange"=>"js:$('#Order_city_id').select2('enable')"
                            ));
                            ?>
                        </div>
                        <div class="form-group">
                            <label>Місто</label>
                            <?php

                            echo $form->dropDownList($model,"city_id", array(),array(
                                "placeholder"=>"Виберіть регіон",
                                "disabled"=>"true",
                                "class"=>"form-control to-select2",
                                "data-ajax-url"=>CController::createUrl('/location/location/getCitiesById')
                            ));
                            ?>
                        </div>
                        <?php
                        ?>
                        <?= $form->textFieldGroup($model, 'address', array(
                            'label'=>'Адреса/№ відділення НП',
                            'labelOptions'=>array(
                                "class"=>"order_address_label"
                            ),
                            'widgetOptions'=>array(
                                'htmlOptions'=>array(
                                    'placeholder'=>''
                                )
                            )
                        ));
                        echo CHtml::hiddenField("type", "", array("id"=>"typeSave"));
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php $this->widget("booster.widgets.TbButton", array(
                    "context"=>"success",
                    "label"=>"Оформити замовлення",
                    "buttonType"=>"submit",
                    "id"=>"save_order",
                    "htmlOptions"=>array(
                        "class"=>"pull-left saveOrderButton",
                        "name"=>"save_order",
                        "value"=>true
                    )
                ));?>

                <?php $this->widget("booster.widgets.TbButton", array(
                    "context"=>"success",
                    "label"=>"Оформити замовлення і роздрукувати",
                    "buttonType"=>"submit",
                    "id"=>"save_print_order",
                    "htmlOptions"=>array(
                        "class"=>"pull-right saveOrderButton",
                        "name"=>"save_print_order",
                        "style"=>"visibility:hidden"
                    )
                ));?>
            </div>
            <?php
            $this->endWidget();
            ?>
        </div>
    </div>
</div>
<?php
Yii::app()->clientScript->registerScript("select2", "
$(\"#Order_city_id\").select2({
        language: \"uk\",
        minimumInputLength: 2,
        ajax: {
            url: $(this).attr('data-ajax-url'),
            dataType: 'json',
            type: \"POST\",
            delay: 250,
            data: function (params) {
                return {
                    id: $(\"#country_id\").val(), // search term
                    region_id: $(\"#region_id\").val(),
                    text: params.term,
                    all: true
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });
",CClientScript::POS_READY);