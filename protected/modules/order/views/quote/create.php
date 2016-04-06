<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 28.04.15
 * Time: 15:01
 * @var $model Quote
 * @var $event Event
 * @var $this QuoteController
 */
echo CHtml::hiddenField("event_id", $event->id);
?>

<div class="modal fade" id="quote-save" tabindex="-1" role="dialog" aria-labelledby="quote save">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
                $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
                    "action"=>$this->createUrl('create', array("event_id" => $event->id)),
                    "id" => "quote-save-form"
                ));
            ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                    <div class="modal-title" id="quote-save">Збереження квоти</div>
                </div>
                <div class="modal-body">
                    <div class="form-group tms">
                        <?= $form->labelEx($model,'name') ?>
                        <?= $form->textField($model, "name", array('class' => 'form-control','placeholder' => 'Назва квоти')) ?>
                    </div>
                    <div class="row tms">
                        <div class="col-xs-6">
                            <div class="form-group tms">
                                <label>Постачальник</label>
                                <?= $form->dropDownList($model,'role_from_id',$providers, array(
                                    'allowClear'=>true,
                                    'class'=>'to-select2',
                                )) ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group tms">
                                <label>Одержувач</label>
                                <?= $form->dropDownList($model,'role_to_id',$contractors, array(
                                    'allowClear'=>true,
                                    'class'=>'to-select2',
                                )) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row tms">
                        <div class="col-xs-6">
                            <div class="form-group tms">
                                <label>Комісійні контрагента</label>
                                <?= $form->dropDownList($model,'type_payment',array(
                                    Quote::TYPE_PERCENT => Quote::$payTypes[Quote::TYPE_PERCENT],
                                    Quote::TYPE_PAYMENT => Quote::$payTypes[Quote::TYPE_PAYMENT],
                                ), array(
                                    'class'=>'form-control',
                                )) ?>
                            </div>
                            <div class="form-group tms">
                                <?= CHtml::hiddenField('quote_cart_sum', 0) ?>
                                <?= $form->numberField($model, 'percent', array('class' => 'form-control', 'min' => 0, 'value' => 0)) ?>
                                <div class="descr mt5">Сума комісйних: <span id="Quote_payment_value">0</span> грн.</div>
                            </div>
                            <div class="form-group tms">
                                <?= $form->radioButtonList($model,'type',array(
                                    Quote::TYPE_NONE => Quote::$namesTypes[Quote::TYPE_NONE],
                                    Quote::TYPE_EQUOTE => Quote::$namesTypes[Quote::TYPE_EQUOTE],
                                    Quote::TYPE_PHYSICAL => Quote::$namesTypes[Quote::TYPE_PHYSICAL],
                                    Quote::TYPE_RETURN => Quote::$namesTypes[Quote::TYPE_RETURN]
                                ),array(
                                    'template' => '<div class="radio">{beginLabel}{input}{labelTitle}{endLabel}</div>',
                                    'separator' => '',
                                )) ?>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group tms">
                                <?= $form->labelEx($model,'comment') ?>
                                <?= $form->textArea($model, "comment", array('class' => 'form-control row2','placeholder' => 'Коментар для квоти')) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?= CHtml::htmlButton('Скасувати',array(
                        'class' => 'btn tms btn-sm btn-danger',
                        'data-dismiss' => 'modal',
                        'type' => 'reset',
                    )) ?>
                    <?= CHtml::htmlButton('Зберегти',array(
                        'class' => 'btn tms btn-sm btn-success',
                        'type' => 'submit',
                    )) ?>
                </div>
            <?php $this->endWidget() ?>
        </div>
    </div>
</div>

<?php $this->widget('application.widgets.eventWidget.EventWidget'); ?>
<div class="wrapper">
    <div class="add-price">
        <div class="main-block">
            <div class="row tms">
                <div class="col-md-9">
                    <div class="map">
                        <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
                            "class"=>"editor_cont preview",
                            "hasMacro"=>$event->scheme->hasMacro,
                            "funZones"=>Sector::getFunSectors($event->scheme_id, true)
                        ));?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="flex-r m-b">
                        <?= CHtml::htmlButton('Створити квоту',array(
                            'class' => 'btn tms btn-sm btn-success',
                            'type' => 'submit',
                            'data-toggle' => 'modal',
                            'data-target' => '#quote-save',
                        )) ?>
                    </div>
                    <?php $this->widget('application.widgets.cartWidget.CartWidget',array('event_id' => $event->id)); ?>
                </div>
            </div>
        </div>
    </div>
</div>