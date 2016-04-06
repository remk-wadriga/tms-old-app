<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.07.15
 * Time: 17:16
 * @var $model Event
 * @var $this CashierController
 */
?>
    <div class="col-sm-12">
        <header>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1" data-toggle="tab">Схема залу</a></li>
                <li class=""><a href="#tab2" data-toggle="tab">Інформація про подію</a></li>
            </ul>
        </header>
        <div class="tab-content">
            <div class="tab-pane active" id="tab1">
                <div class="wrapper bg-white-only">
                    <div class="row">
                        <div class="col-sm-9">
                            <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
                                "class"=>"editor_cont preview production",
                                "hasMacro"=>$model->scheme->hasMacro,
                                "cartUrl"=>$this->createUrl("eventListToCart"),
                                "funZones"=>Sector::getFunSectors($model->scheme_id, true)
                            ));?>
                        </div>
                        <div class="col-sm-3">
                            <div class="cart-wrap">
                                <?php $this->renderPartial("cartBlock", array("places"=>$places))?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tab2">
                <div class="wrapper bg-white-only">
                    <div class="event-info">
                        <?php $this->renderPartial("infoBlock", array("model"=>$model));?>
                    </div>
                </div>
            </div>
        </div>
    </div>