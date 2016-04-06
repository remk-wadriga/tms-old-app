<?php
/**
 *
 * @var $model Quote
 * @var $event Event
 * @var $this QuoteController
 * @var $form TbActiveForm
 */
?>


<?php
    echo CHtml::hiddenField("quote_id", $model->id);
    echo CHtml::hiddenField("page_id", "edit_quote");
    $this->beginWidget("booster.widgets.TbModal", array(
        "id"=>"edit_quote_info"
    ));
?>
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Редагувати дані квоти</h4>
        </div>
        <?php
            $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
                "id"=>"edit_quote_form",
                "enableAjaxValidation"=>true,
                "clientOptions"=>array(
                    "validateOnChange"=>true,
                    "validateOnSubmit"=>true,
                )
            ));
        ?>
            <div class="modal-body">
                <?php
                echo $form->textFieldGroup($model, "name", array(
                    "label"=>"Назва",
                    "widgetOptions"=>array(
                        "htmlOptions"=>array(
                            "placeholder"=>"Назва",
                        )
                    )
                ));
                echo CHtml::hiddenField("totalSum_".$model->role_to_id, $model->order->total);
                ?>
                <div class="col-lg-6">
                    <?php
                    $roles = Role::getRoleList(true);
                    echo CHtml::label("Постачальник","Quote[role_from_id]");
                    echo CHtml::dropDownList("Quote[role_from_id]",$model->role_from_id,$roles,["class"=>"to-select2"]);
                    echo $form->textAreaGroup($model, "from_legal_detail", array(
                        "label"=>"Реквізити",
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(
                                "placeholder"=>"Реквізити",
                            )
                        )
                    ));
                    echo $form->checkboxGroup($model, "printed", array(
                        "label"=>"Буде надруковано",
                    ));
                    echo $form->dropDownListGroup($model, "type_payment", array(
                        "label"=>false,
                        "widgetOptions"=>array(
                            "data"=>Quote::$payTypes,
                            "htmlOptions"=>array(
                                "onchange"=>"js:checkPaymentType($(this).val(), ".$model->role_to_id.")",
                            )
                        )
                    ));
                    echo $form->textFieldGroup($model, "percent", array(
                        "label"=>false,
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(
                                "placeholder"=>false,
                                "onkeyup"=>"js:calculatePayment($(this).val(), ".$model->role_to_id.")",
                            )
                        )
                    ));
                    echo "<span class='commission_block' id='commision_block_".$model->role_to_id."' style='display:".($model->type_payment==Quote::TYPE_PERCENT? 'block': 'none').";'>Комісійні контрагента: <span class='commission'>".($model->type_payment==Quote::TYPE_PERCENT ? (($model->percent/100)*$model->order->total) : "0") ."</span> грн</span>";
                    echo $form->checkboxGroup($model, "statusWait", array(
                        "label"=>"Статус Очікування",
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(
                                "disabled"=>true
                            )
                        )
                    ));
                    ?>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <?php
                        echo CHtml::label("Одержувач","Quote[role_to_id]");
                        echo CHtml::dropDownList("Quote[role_to_id]",$model->role_to_id,$roles,["class"=>"to-select2"]);
                        ?>
                    </div>
                    <?php
                    echo $form->textAreaGroup($model, "to_legal_detail", array(
                        "label"=>"Реквізити",
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(

                                "placeholder"=>"Реквізити"
                            )
                        )
                    ));
                    echo $form->textAreaGroup($model, "comment", array(
                        "label"=>"Коментар",
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(
                                "placeholder"=>"Коментар",
                            )
                        )
                    ));
                    ?>
                    <br/>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <?php $this->widget(
                'booster.widgets.TbButton',
                array(
                    'context' => 'primary',
                    'label' => 'Зберегти',
                    "buttonType"=>"submit",
            //        'htmlOptions' => array('data-dismiss' => 'modal'),
                )
            ); ?>
            <?php $this->widget(
                'booster.widgets.TbButton',
                array(
                    'label' => 'Close',
                    'url' => '#',
                    'htmlOptions' => array('data-dismiss' => 'modal'),
                )
            ); ?>
            </div>
    <?php
        $this->endWidget();
        $this->endWidget();

    ?>
<div class="wrapper">
    <div class="add-price">
        <?php
        $this->widget('booster.widgets.TbAlert', array(
            'fade' => true,
            'closeText' => '&times;', // false equals no close link
            'events' => array(),
            'htmlOptions' => array(),
            'userComponentId' => 'user',
            'alerts' => array( // configurations per alert type
                'success' => array('closeText' => '&times;'),
                'error' => array('closeText' => '&times;')
            ),
        ));
        ?>
        <div class="alert alert-success success-sector-alert" style="z-index:6;width:100%;display: none" role="alert">Успішно збережено</div>
        <div class="alert alert-danger danger-sector-alert" style="z-index:6;width:100%;display: none" role="alert">Помилка</div>
        <hr/>
        <div class="head-block">
            <div class="info pull-left col-lg-4">
                <h3>Редагування квоти: <span class="text-info"><?= $model->name ?></span> <span class="small"><?= $model->order->date_add ?></span></h3>
                <p>
                    <?php
                    $this->widget("booster.widgets.TbButton", array(
                        "htmlOptions"=>array(
                            "data-toggle"=>"modal",
                            "data-target"=>"#edit_quote_info"
                        ),
                        "label"=>"Редагувати дані квоти"
                    ));
                    echo CHtml::tag('br');
                    $this->widget("booster.widgets.TbButton", array(
                        'buttonType' =>'link',
                        'url'=>Yii::app()->createUrl("/order/order/getInvoice", array("id"=>$model->id)),
                        "htmlOptions"=>array(
                            "style"=>"margin-top:5px",
                        ),
                        "label"=>"Сформувати накладну"
                    ));
                    ?>
                </p>
            </div>
                <div class="block-button col-lg-8">
                    <?php
                    $this->widget("booster.widgets.TbTabs", array(
                        "type"=>"pills",
                        "tabs"=>array(
                            array(
                                'label' => 'Додавання проданих',
                                'content' => $this->renderPartial("_addSold", array("model"=>$model), true),
                                'active' => true
                            ),
                            array(
                                'label' => 'Передача місць',
                                'content' => $this->renderPartial("_passPlace", array("model"=>$model, "quotes_list"=>$quotes_list), true)
                            ),
                            array(
                                'label' => 'Зміна цін',
                                'content' => $this->renderPartial("_changePrice", array("model"=>$model), true)
                            ),
                            array('label' => 'Повернення', 'content' => $this->renderPartial("_return", array("model"=>$model), true)),
                        ),
                    ));
                    ?>
                </div>
            <div class="clearfix"></div>
        </div>
        <hr/>
        <div class="main-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="head">
                        <?php $this->beginWidget("booster.widgets.TbActiveForm", array(
                            "id"=>"amount_info_block",
                            "type"=>"inline",
                            "htmlOptions"=>array(
                                "style"=>"visibility: hidden; margin-top:10px;"
                            )
                        ))?>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="к-сть" id="amount">
                        </div>
                        <div class="summary">
                            <span>Використано: <span id="sold_amount"></span></span>
                            <span>Доступно: <span id="available_amount"></span></span>
                            <span>Всього: <span id="total_amount"></span></span>
                        </div>
                        <?php $this->endWidget();?>
                    </div>
                    <hr/>
                    <div class="map">
                        <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
                            "class"=>"editor_cont preview",
                            "hasMacro"=>$event->scheme->hasMacro,
                            "funZones"=>Sector::getFunSectors($event->scheme_id, true)
                        ));?>
                    </div>
                    <hr/>
                    <div class="foot" id="selected_info_cont" style="visibility: hidden;">
                        <p>Виділено: <span id="select_row_info">рядів - <strong></strong>, </span><span>місць - <strong id="select_seat_amount"></strong>, </span> Вартістю: <strong id="select_price_amount"></strong> грн</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>