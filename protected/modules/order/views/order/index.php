<?php
/* @var $this OrderController
 * @var $model Order
 * @var $form TbActiveForm
 */
?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Менеджер замовлень</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <?php
    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
        "id"=>"order-filter-form"
    ));
    echo CHtml::hiddenField("search", true);
    echo CHtml::hiddenField("filterName", true);
    echo CHtml::hiddenField("Order_page", $page);
    echo CHtml::hiddenField("maxPageSize", $maxPage);
    ?>
        <div class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-3">
                        <?php
                        echo $form->dropDownListGroup($model, "city_id", array(
                            'label'=>'Місто проведення',
                            'widgetOptions'=>array(
                                "data"=>City::getCityList(),
                                "htmlOptions"=>array(
                                    "class"=>"to-select2",
                                    "multiple"=>"multiple",
                                    "placeholder"=>"Виберіть місто"
                                )
                            )
                        ))
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Назва події</label>
                            <div class="pull-right">
                                <?php

                                echo $form->checkboxListGroup($event, "status", array(
                                    "label"=>false,
                                    "widgetOptions"=>array(
                                        "data"=>array(
                                            Event::STATUS_ACTIVE=>" Активні",
                                            Event::STATUS_NO_ACTIVE=>" Не активні"
                                        ),
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                        )
                                    ),
                                    "groupOptions"=>array(
                                        "style"=>"margin-bottom:0!important;"
                                    )
                                ));
                                ?>

                            </div>
                            <?php
                            $events = Event::getListEvents((is_array($event->status)? $event->status: false));

                            echo $form->dropDownList($model, "event_id",$events['data'], array(
                                'allowClear' => true,
                                'class' => 'to-select2-ext',
                                'options' => $events['options'],
                                'multiple'=>'multiple'
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Сектор залу</label>
                            <?php echo $form->dropDownList($model, "sector", $sectors, array(
                                "class"=>"to-select2",
                                "multiple"=>"multiple"
                            ))?>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-users"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php echo $form->radioButtonListGroup($model, "creator", array(
                                            "label"=>"Хто створив",
                                            "widgetOptions"=>array(
                                                "data"=>User::$userType,
                                            ),
                                            "inline"=>true
                                        ))?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php
                                        $creators = Ticket::getUserCreator();
                                        echo $form->dropDownList($model, "creator_role", $creators['roles'], array(
                                            "empty"=>"Виберіть гравця",
                                            "class"=>"to-select2 role",
                                            "data-type"=>"creator",
                                            "disabled"=>$model->creator != User::TYPE_USER
                                        ));
                                        ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php
                                        echo $form->dropDownList($model, "creator_id", $creators['users'], array(
                                            "empty"=>"Виберіть користувача",
                                            "class"=>"to-select2 creator_user",
                                            "disabled"=>$model->creator != User::TYPE_USER
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">&nbsp;</div>
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <label>
                                        <?= $form->checkBox($model, "period")?>Період створення
                                    </label>
                                </div>
                                <div class="input-daterange input-group" id="datepicker">
                                    <?php echo $form->textField($model, "start_period", array(
                                        "class"=>"input-sm form-control"
                                    ))?>
                                    <span class="input-group-addon">-</span>
                                    <?php echo $form->textField($model, "end_period", array(
                                        "class"=>"input-sm form-control"
                                    ))?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-truck"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-6">
                                <?php
                                echo $form->checkboxListGroup($model, "ticketDeliveryType", array(
                                    "label"=>"Спосіб доставки",
                                    "widgetOptions"=>array(
                                        "data"=> Order::$deliveryType,
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ))
                                ?>
                            </div>
                            <div class="col-xs-6">
                                <?php
                                echo $form->checkboxListGroup($model, "ticketDelivery", array(
                                    "label"=>"Статус доставки",
                                    "widgetOptions"=>array(
                                        "data"=>Ticket::$statusDelivery,
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ));
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-money"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                        echo $form->radioButtonListGroup($model, "pay_method", array(
                                            "label"=>"Спосіб оплати",
                                            "widgetOptions"=>array(
                                                "data"=>Order::$pay_methods,
                                                "htmlOptions"=>array(
                                                    "labelOptions"=>array(
                                                        "class"=>"radio-inline"
                                                    ),
                                                )
                                            )
                                        ));
                                        ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php
                                        $cash_users =  Ticket::getCashUsers();
                                        echo $form->dropDownList($model, "cash_role", $cash_users['roles'], array(
                                            "empty"=>"Виберіть гравця",
                                            "class"=>"to-select2 role",
                                            "data-type"=>"cash",
                                            "disabled"=>$model->pay_method != Order::PAY_CASH
                                        ));
                                        ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php
                                        echo $form->dropDownList($model, "cash_user_id", $cash_users['users'], array(
                                            "empty"=>"Виберіть користувача",
                                            "class"=>"to-select2 cash_user",
                                            "disabled"=>$model->pay_method != Order::PAY_CASH
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <?php
                                echo $form->checkboxListGroup($model, "payment", array(
                                    "label"=>"Статус оплати",
                                    "widgetOptions"=>array(
                                        "data"=>array(
                                            Ticket::PAY_PAY =>" Оплачено",
                                            Ticket::PAY_NOT_PAY=>" Не оплачено",
                                            Ticket::PAY_INVITE => " Запрошення"
                                        ),
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ));?>
                            </div>
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <label>
                                        <?= $form->checkBox($model, "pay_time")?>Період оплати
                                    </label>
                                </div>
                                <div class="input-daterange input-group" id="datepicker">
                                    <?php echo $form->textField($model, "pay_start", array(
                                        "class"=>"input-sm form-control"
                                    ))?>
                                    <span class="input-group-addon">-</span>
                                    <?php echo $form->textField($model, "pay_end", array(
                                        "class"=>"input-sm form-control"
                                    ))?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-print"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-5">
                                <label class="block">Хто роздрукував</label>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <?php
                                        $print_users = Ticket::getPrintUsers();
                                        echo $form->dropDownListGroup($model, "print_role", array(
                                            "label"=>false,
                                            "widgetOptions"=>array(
                                                "data"=>$print_users['roles'],
                                                "htmlOptions"=>array(
                                                    "class"=>"to-select2 role",
                                                    "data-type"=>"print",
                                                    "empty"=>"Виберіть гравця"
                                                )
                                            )
                                        ))
                                        ?>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php
                                        echo $form->dropDownListGroup($model, "print_author", array(
                                            "label"=>false,
                                            "widgetOptions"=>array(
                                                "data"=>$print_users['users'],
                                                "htmlOptions"=>array(
                                                    "class"=>"to-select2 print_user",
                                                    "empty"=>"Виберіть користувача"
                                                )
                                            )
                                        ))
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <?php
                                echo $form->checkboxListGroup($model, "ticketType", array(
                                    "label"=>"Формат квитка",
                                    "inline"=>true,
                                    "widgetOptions"=>array(
                                        "data"=>array(
                                            Ticket::TYPE_BLANK =>" Бланк",
                                            Ticket::TYPE_A4=>" А4",
                                        ),
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ));
                                ?>
                            </div>
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <label>
                                        <?= $form->checkBox($model, "print_time")?>Період друку
                                    </label>
                                </div>
                                <div class="input-daterange input-group" id="datepicker">
                                    <?php echo $form->textField($model, "print_start", array(
                                        "class"=>"input-sm form-control"
                                    ))?>
                                    <span class="input-group-addon">-</span>
                                    <?php echo $form->textField($model, "print_end", array(
                                        "class"=>"input-sm form-control"
                                    ))?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-gear"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-3">
                                <?php
                                echo $form->checkboxListGroup($model, "ticketStatus", array(
                                    "label"=>"Cтатус квитка",
                                    "widgetOptions"=>array(
                                        "data"=>array(
                                            Ticket::STATUS_SOLD =>" Активний",
                                            Ticket::STATUS_CANCEL=>" Скасований",
                                        ),
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ));
                                ?>
                            </div>
                            <div class="col-xs-3">
                                <?= $form->checkboxListGroup($model, 'type',array(
                                    "label"=>"Типи замовлень",
                                    "widgetOptions"=>array(
                                        "data"=>array(
                                            Order::TYPE_ORDER => "Замовлення",
                                            Order::TYPE_QUOTE => "Квоти"
                                        ),
                                        "htmlOptions"=>array(
                                            "labelOptions"=>array(
                                                "class"=>"checkbox-inline"
                                            ),
                                            "container"=>false,
                                        )
                                    )
                                ));?>

                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-4">
                        <?php echo CHtml::link("Скинути фільтри", "#", array(
                            "class"=>"text-sm m-b-sm pull-right dropFilters"
                        ));
                        ?>
                        <button class="btn btn-success block m-t-sm btn-full"><i class="fa fa-search m-r-sm"></i> Знайти</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs m-b m-r-lg" type="button" data-toggle="modal" data-target="#filter-profile">завантажити профіль</button>
                    <button class="btn btn-primary btn-xs m-b">менше фільтрів <i class="fa fa-filter"></i></button>
                </div>
                <div class="clearfix"></div>
                <section class="panel">
                    <section class="panel-body result-block">
                        <h2 class="m-t-none">Результат запиту</h2>
                        <div>
                            <label class="block">вартість</label>
                            <strong><?= $ticketsInfo['sum']?> грн</strong>
                        </div>
                        <div class="pull-left sm">
                            <label class="block">квитків</label>
                            <strong><?= $ticketsInfo['count']?></strong>
                        </div>
                        <div class="pull-right sm">
                            <label class="block">замовлень</label>
                            <strong><?= $ticketsInfo['ordersCount']?></strong>
                        </div>
                    </section>
                    <?php
                    $urlParameters = isset($ticketsInfo["ids"]) ? $ticketsInfo["ids"]:null;
                    echo CHtml::link(".xls",['generateTicketXls'],array(
//                        "target"=>"_blank",
                        "class"=>"btn generateDoc",
                    ));
                    echo CHtml::link(".csv",['generateTicketCsv'],array(
//                        "target"=>"_blank",
                        "class"=>"btn generateDoc",
                    ))?>
                </section>
                <hr/>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-list-alt"></i></div>
                    <div class="group">
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-phone"></i></div>
                    <div class="group">
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-envelope"></i></div>
                    <div class="group">
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-user"></i></div>
                    <div class="group">
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/np.png"></i></div>
                    <div class="group">
                        <?php
                        echo $form->textFieldGroup($model, "np_number", array(
                            "label"=>false,
                            "widgetOptions"=>array(
                                "htmlOptions"=>array(
                                    "placeholder"=>"ТТН",
                                    "class"=>"input-sm"
                                )
                            )));
                        ?>
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-tags"></i></div>
                    <div class="group">
                        <?php echo $form->textField($model, "tags", array(
                            "placeholder"=>"Теги",
                            "class"=>"input-sm form-control"
                        ));?>
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-barcode"></i></div>
                    <div class="group">
                        <?php echo $form->textField($model, "code", array(
                            "placeholder"=>"Штрих-код квитка",
                            "class"=>"input-sm form-control",
                        ));?>
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Країна</label>
                        <?php
                        echo CHtml::link('<i class="fa fa-times"></i>', "#", array(
                            "class"=>"clear-select2-location clear-country"
                        ));
                        echo CHtml::dropDownList("country", "", Country::getCountryList(), array(
                            "class"=>"to-select2",
                            "empty"=>"",
                            "multiple"=>true,
                            "ajax"=>array(
                                'type' => 'POST',
                                'url' => CController::createUrl('/configuration/configuration/getRegions'),
                                'data' => 'js:{id:$(this).val()}',
                                'complete' => "js:function(result){
                                            $('#region').select2('destroy')
                                                .html(result.responseText)
                                                .prop('disabled', false).select2({placeholder: 'Виберіть зі списку регіон'});
                                            $('#city').select2().val(null);
                                        }",
                            ),
                            "onchange"=>"
                                if ($(this).val() != null)
                                    $('.clear-country').css({'visibility':'visible'});
                                else
                                    $('.clear-country').css({'visibility':'hidden'});
                            "
                        ));
                        ?>
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Область</label>
                        <?php
                        echo CHtml::link('<i class="fa fa-times"></i>', "#", array(
                            "class"=>"clear-select2-location clear-region",

                        ));
                        echo CHtml::dropDownList("region", "", array(), array(
                            "class"=>"to-select2",
                            "multiple"=>true,
                            "disabled"=>true
                        ));
                        ?>

                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Місто</label>

                        <?php
                        echo CHtml::link('<i class="fa fa-times"></i>', "#", array(
                            "class"=>"clear-select2-location clear-city"
                        ));
                        echo CHtml::dropDownList("city", "", array(), array(
                            "class"=>"to-select2",
                            "multiple"=>true,
                            "disabled"=>true
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>


    <?php $this->endWidget()?>
    <div class="page-order-management">



        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
    <?php
    $this->widget("application.widgets.orderListWidget.OrderList", array(
        "dataProvider"=>$dataProvider,
        "pagination"=>$model->pagination
    ));
    ?>

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
                    ))?>

                    <div class="clearfix"></div>
                    <div class="pull-left">

                    </div>
                    <div class="pull-right">
                        <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#cart-edit-modal">Редагувати</button>
                    </div>
                    <div class="pull-right" style="margin-right: 20px;">
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
                <button type="button" class="btn btn-success pull-left" data-dismiss="modal">Скасувати</button>
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
<div class="modal fade" id="filter-profile" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Збережені налаштування фільтру
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST">
                        <div class="col-xs-8">
                            <?php
                                echo CHtml::dropDownList("filter_id", $filter_id, OrderFilter::getOrderList(), array(
                                    "class"=>"to-select2",
                                ));
                            ?>
                        </div>
                        <div class="col-xs-4">
                            <?php
                            $this->widget("booster.widgets.TbButton", array(
                                "context"=>"success",
                                "label"=>"Застосувати налаштування",
                                "buttonType"=>"submit",
                                "htmlOptions"=>array(
                                    "class"=>"text-sm text-mutted applyFilter",
                                )
                            ));
                            ?>
                        </div>
                    </form>
                    <div class="col-xs-6 m-t">
                        <a href="#" class="text-sm text-mutted saveFilter">Зберегти поточне налаштування фільтру</a>
                    </div>
                    <div class="col-xs-6 m-t">
                        <a href="#" class="text-sm text-mutted deleteFilter">Видалити обраний фільтр</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="footer">
    <div class="col-xs-4"></div>
    <div class="col-xs-4">
        <?php
        $pagerData = [1=>"1 замовлення",5=>"5 замовлень",10=>"10 замовлень",20=>"20 замовлень",50=>"50 замовлень"];
        echo CHtml::dropDownList("maxPage",$maxPage,$pagerData,["class"=>"to-select2"]);
        ?>
    </div>
    <div class="col-xs-4"></div>
</div>