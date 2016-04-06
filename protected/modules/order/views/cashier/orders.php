<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.06.15
 * Time: 11:38
 * @var $this CashierController
 * @var $dataProvider CActiveDataProvider
 * @var $model Order
 * @var $form TbActiveForm
 */
?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b">Пошук замовлення</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <div class="page-order-my">
    <?php
    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
        "id"=>"my-order-filter-form"
    ));        ?>
    <div class="row-5">
        <div class="col-sm-3 form-with-icon">
            <div class="pull-left"><i class="fa fa-list-alt"></i></div>
            <div class="group">
                <label># Замовлення</label>
                <?php echo $form->textFieldGroup($model, "id", array(
                    "label"=>false,
                    "widgetOptions"=>array(
                        "htmlOptions"=>array(
                            "class"=>"input-sm",
                            "placeholder"=>"# Замовлення"
                        )
                    )
                ))?>
            </div>
        </div>
        <div class="col-sm-3 form-with-icon">
            <div class="pull-left"><i class="fa fa-phone"></i></div>
            <div class="group">
                <label>Телефон власника</label>
                <?php
                echo $form->textFieldGroup($model, "phone", array(
                    "label"=>false,
                    "widgetOptions"=>array(
                        "htmlOptions"=>array(
                            "placeholder"=>"Телефон власника",
                            "class"=>"input-sm"
                        )
                    )));
                ?>
            </div>
        </div>
        <div class="col-sm-2 form-with-icon">
            <div class="pull-left"><i class="fa fa-user"></i></div>
            <div class="group">
                <label>Прізвище, ім'я власника</label>
                <?php
                echo $form->textFieldGroup($model, "name", array(
                    "label"=>false,
                    "widgetOptions"=>array(
                        "htmlOptions"=>array(
                            "placeholder"=>"Прізвище, ім'я власника",
                            "class"=>"input-sm"
                        )
                    )));
                ?>
            </div>
        </div>
        <div class="col-sm-2 form-with-icon">
            <div class="pull-left"><i class="fa fa-envelope"></i></div>
            <div class="group">
                <label>E-mail власника</label>
                <?php
                echo $form->textFieldGroup($model, "email", array(
                    "label"=>false,
                    "widgetOptions"=>array(
                        "htmlOptions"=>array(
                            "placeholder"=>"E-mail власника",
                            "class"=>"input-sm"
                        )
                    )));
                ?>
            </div>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-success block m-t-lg btn-full">Показати замовлення</button>
        </div>
    </div>

    <?php
    $this->endWidget();
    ?>
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <?php $this->widget("application.widgets.orderListWidget.OrderList", array(
                    "dataProvider"=>$dataProvider,
                    "cashier"=>true,
                    "pagination"=>$model->pagination
                ));?>
            </section>
        </div>
    </div>
        <div class="cart-fixed">
            <?php
            $count = Yii::app()->shoppingCart->getCount();
            $sum = Yii::app()->shoppingCart->getCost();
            ?>
            <div class="cart-hide bg-dark">
                <i class="fa fa-shopping-cart"></i>
                <div>к</div>
                <div>о</div>
                <div>ш</div>
                <div>и</div>
                <div>к</div>
            </div>
            <section class="panel cart-block bxs">
                <div class="panel-body">
                    <button type="button" class="close cart-hide-button">закрити <i class="fa fa-times"></i></button>
                    <p class="m-b-xs"><span class="text">Квитків:</span><span class="cartCount"><?=$count?></span></p>
                    <p class="m-b-xs"><span class="text">На суму:</span><span class="cartSum"><?=$sum?></span> грн</p>
                    <p class="m-b"><span class="text">Знижка:</span>0 грн</p>
                    <p class="lg"><span class="text"><strong>До оплати: </strong></span><span class="cartToPay"><?=$sum?></span> грн</p>
                    <div class="pull-right">
                        <?= CHtml::link("очистити кошик", "#", array(
                            "class"=>"text-sm m-t m-b-sm block clearCart"
                        ))?>
                    </div>
                    <?php echo CHtml::htmlButton('<i class="fa fa-print m-r-sm"></i> Надрукувати', array(
                        "class"=>"btn btn-success block m-t-lg m-b-lg btn-full",
                        "id"=>"printButton",
                        "onclick"=>"window.open('".CController::createAbsoluteUrl('/order/order/printTickets')."','printWindow','width=screen.width,height=screen.height')"
                    ))?>

                    <div class="clearfix"></div>
                    <div class="pull-right">
                        <button class="btn btn-xs btn-success" data-toggle="modal" id="cart-cancel-tickets">Скасувати</button>
                    </div>
                </div>
            </section>
        </div>

    </div>
</div>
<div class="modal fade" id="cart-edit-modal" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php $modalForm = $this->beginWidget("booster.widgets.TbActiveForm", array(
                "id"=>"cart-edit-form"
            ));?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Редагування квитків</h4>
                <span class="text-mutted text-sm">Увага! Зміни буде внесено до усіх обраних квитків</span>
            </div>
            <div class="modal-body">

                <section class="panel">
                    <header class="panel-heading bg-light">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#tab-1" data-toggle="tab">Основна інформація</a></li>
                            <li><a href="#tab-2" data-toggle="tab">Обрані квитки</a></li>
                        </ul>
                    </header>
                    <div class="loading text-center">
                        <i class="fa fa-spinner fa-spin fa-pulse" style="font-size: 36px;"></i>
                    </div>
                    <div class="panel-body ticketsInfoPanel">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">

                            </div>
                            <div class="tab-pane" id="tab-2">

                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left">Скасувати</button>
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    "context"=>"success",
                    "label"=>"Зберегти",
                    "htmlOptions"=>array(
                        "class"=>"pull-right saveTicketsInfo"
                    )
                ))
                ?>
            </div>
            <?php $this->endWidget();?>
        </div>
    </div>
</div>
<!--</div>-->
<!--</div>-->