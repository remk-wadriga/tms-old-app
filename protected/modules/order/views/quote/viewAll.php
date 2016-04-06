<?php
/**
 *
 * @var QuoteController $this
 */
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 28.04.15
 * Time: 15:01

 * @var $model Event
 * @var $this QuoteController
 */
echo CHtml::hiddenField("event_id", $model->id);
echo CHtml::hiddenField("page_id", "view_all_quotes");
?>
<?php $this->widget('application.widgets.eventWidget.EventWidget'); ?>
<div class="wrapper">
    <div class="add-price">
        <div class="head-block">
            <div class="block-button pull-right">
                <?= CHtml::link("Створити квоту", array("/order/quote/create", "event_id"=>$model->id), array("class"=>"btn btn-primary")) ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr/>
        <div class="main-block">
            <div class="row">
                <div class="col-md-8">
                    <div class="head"></div>
                    <hr/>
                    <div class="map">
                        <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
                            "class"=>"editor_cont preview",
                            "hasMacro"=>$model->scheme->hasMacro,
                            "funZones"=>Sector::getFunSectors($model->scheme_id, true)
                        ));?>
                    </div>
                    <hr/>
                    <div class="foot" id="selected_info_cont" style="visibility: hidden;">
                        <p>Виділено: <span id="select_row_info">рядів - <strong></strong>, </span><span>місць - <strong id="select_seat_amount"></strong>, </span> Вартістю: <strong id="select_price_amount"></strong> грн</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter">
                        <hr/>
                        <div class="quote-cart">
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <?php
                                $i = 1;

                                foreach ($model->quotes as $quote) {
                                    if ($quote->order->status == Order::STATUS_ACTIVE) {
                                        $sumCount = $quote->getAllCountSum();
                                        $this->renderPartial("_contractor_block", array(
                                            "contractor"=>$quote->roleTo,
                                            "event_id"=>$model->id,
                                            "num"=>$i,
                                            "count"=>$sumCount['count'],
                                            "sum"=>$sumCount['sum'],
                                            "sectors"=>isset($sectors[$quote->id])? $sectors[$quote->id]:array(),
                                            "quote"=> $quote
                                        ));
                                        $i++;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>