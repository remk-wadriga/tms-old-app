<?php
/**
 *
 * @var EncashmentController $this
 */

?>

<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Відсоток</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <div class="alert alert-success success-sector-alert" style="z-index:5;width:50%;display: none" role="alert">Успішно збережено</div>
    <div class="alert alert-danger danger-sector-alert" style="z-index:5;width:50%;display: none" role="alert">Fail</div>
    <?php
    $form = $this->beginWidget('application.components.CustomActiveForm',array(
        'id'=>'percent-form',
        'htmlOptions'=>array(
            'enctype'=>'multipart/form-data'
        ),
//        'enableAjaxValidation'=>true,
//        'clientOptions'=>array(
//            'validateOnSubmit'=>true,
//            'validateOnChange'=>true
//        )
    ));
    ?>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label>Каса</label>
                    <?php
                    echo CHtml::dropDownList("CashierPercent[role_id]",null,[""=>"Виберіть касу"]+Role::getRoleList(true), [
                        'allowClear' => true,
                        'class' => 'select2 form-control kasa',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => Yii::app()->createUrl('/order/encashment/getCashier'),
                            'data' => 'js:{role_id:$(this).val()}',
                            'complete' => "js:function(result){
                            $('.data-events-percent').html(null);
                            $('.event-div').hide();
							$('#CashierPercent_user_id').select2('destroy')
                                    .html('<option value=\'\' selected=\'selected\'>Виберіть касира</option>'+result.responseText)
                                    .select2({placeholder: 'Виберіть касира'});
                            var _this = $('#CashierPercent_role_id');
                            $.post(
                                '".$this->createUrl('getPercentData')."',
                                {
                                    role_id: _this.val()
                                }, function(result) {
                                    var result = JSON.parse(result);
                                    $('.data-percent').html(result);
                                }
                            );

						}",
                        ),
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <label>Касир</label>
                    <?php
                    echo CHtml::dropDownList("CashierPercent[user_id]",null,[], [
                        'allowClear' => true,
                        'class' => 'select2 form-control cashier',
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="pull-right">
                    <?php
                    echo CHtml::link("Зберегти зміни", "#", array(
                        "class"=>"btn btn-success percent-submit",
                    ));
                    ?>
                </div>
                <div class="data-percent">
                    <!--data from ajax here-->
                </div>
                <div class="event-div">
                <h3>Відсотки винагороди по подіях</h3>
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label>Назва події</label>
                        <?php
                        $events = Event::getListEvents(false);

                        echo CHtml::dropDownList("event_id",null,[''=>"Виберіть подію"]+$events['data'], [
                            'allowClear' => true,
                            'class' => 'to-select2-ext',
                            'options' => $events['options'],
                        ]);
                        ?>
                    </div>
                    <div class="col-xs-2">
                        <?php
                        echo CHtml::link("Додати", "#", array(
                            "class"=>"btn block btn-success btn-sm m-t-23 addEvent",
                        ));
                        ?>
<!--                        <a href="#" class="btn block btn-success btn-sm m-t-23">Додати</a>-->
                    </div>
                </div>
                </div>
                <div class="data-events-percent">
                    <!--data from ajax here-->
                </div>

            </div>
        </div>
    <?php
    $this->endWidget();
    ?>
</div>
