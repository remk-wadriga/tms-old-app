<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 07.05.15
 * Time: 13:23
 * @var $form TbActiveForm
 * @var $model Quote
 * @var $this QuoteController
 * @var int $event_id
 * @var $contractor Role
 */


        $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
            "id"=>"saveQuoteForm".$contractor->id,
            "action"=>$this->createUrl('create', array("event_id"=>$event_id)),
            "type"=>"vertical",
            "enableAjaxValidation"=>true,
            "clientOptions"=>array(
                "validateOnSubmit"=>true,
                "afterValidate"=>"js:function(form, data, hasError) {
                    return window.quote_constructor.afterValidate(form, data, hasError,'".$this->createUrl('create', array("event_id"=>$event_id))."',".$contractor->id.");
                }"
            )

        ));
        echo CHtml::hiddenField("event_id", $event_id);
        echo CHtml::hiddenField("totalSum_".$contractor->id, Yii::app()->shoppingCart->getCost());
        echo $form->textFieldGroup($model, "[".$contractor->id."]name", array(
            "label"=>"Назва",
            "widgetOptions"=>array(
                "htmlOptions"=>array(
                    "placeholder"=>"Назва",
                )
            )
        ));

        ?>
        <div class="col-lg-6">
            <?php
            echo $form->dropDownListGroup($model, "[".$contractor->id."]role_from_id", array(
                "label"=>"Постачальник",
                "widgetOptions"=>array(
                    "data"=>$model->getListRoles($event_id),

                )
            ));
            echo $form->textAreaGroup($model, "[".$contractor->id."]from_legal_detail", array(
                "label"=>"Реквізити",
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "placeholder"=>"Реквізити",
                    )
                )
            ));
            echo $form->checkboxGroup($model, "[".$contractor->id."]printed", array(
                "label"=>"Буде надруковано",
            ));
            echo $form->dropDownListGroup($model, "[".$contractor->id."]type_payment", array(
                "label"=>false,
                "widgetOptions"=>array(
                    "data"=>Quote::$payTypes,
                    "htmlOptions"=>array(
                        "onchange"=>"js:checkPaymentType($(this).val(), ".$contractor->id.")",
                    )
                )
            ));

            echo $form->textFieldGroup($model, "[".$contractor->id."]percent", array(
                "label"=>false,
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "placeholder"=>false,
                        "onkeyup"=>"js:calculatePayment($(this).val(), ".$contractor->id.")"
                    )
                )
            ));

            echo "<span class='commission_block' id='commision_block_".$contractor->id."'>Комісійні контрагента: <span class='commission'>0  </span> грн</span>";
            echo $form->checkboxGroup($model, "[".$contractor->id."]statusWait", array(
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
                echo $form->dropDownListGroup($model, "[".$contractor->id."]role_to_id", array(
                    "class"=>"form-control",
                    "label"=>"Одержувач",
                    "widgetOptions"=>array(
                        "data"=>$model->getListRoles($event_id),
                        "htmlOptions"=>array(
                            "options"=>array($contractor->id=>array("selected"=>true))
                        )
                    )
                ));
                ?>
            </div>
            <?php

            echo $form->textAreaGroup($model, "[".$contractor->id."]to_legal_detail", array(
                "label"=>"Реквізити",
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "value"=>$contractor->legal_detail,
                        "placeholder"=>"Реквізити"
                    )
                )
            ));
            echo $form->textAreaGroup($model, "[".$contractor->id."]comment", array(
                "label"=>"Коментар",
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "placeholder"=>"Коментар",
                    )
                )
            ));
            $this->widget("booster.widgets.TbButton", array(
                "context"=>"primary",
                "buttonType"=>"submit",
                "label"=>"Зберегти та продовжити",
            ));
            ?>
            <br/>

        </div>
        <?php

        $this->endWidget();
        ?>