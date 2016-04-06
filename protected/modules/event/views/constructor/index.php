<?php
/* @var $this ConstructorController
 * @var $model Event
 * @var $sectors CActiveDataProvider
 */
$this->renderPartial("_invoice", array());
$this->renderPartial("_pay_types", array("model"=>$payModel));
?>

<?php $this->widget('application.widgets.eventWidget.EventWidget'); ?>
<div class="wrapper">
    <div class="add-price">
        <?php
            $funCount = Place::getCountFunWithPrice($model->id);
            $countAll = $model->scheme->getCountPlaces()+$funCount;
            $sumAll = ($model->sumPrice == '')?' - ':$model->sumPrice.' грн';
            $countWithPrice = $model->countPlacePrice;
            echo CHtml::hiddenField('event_id', $model->id);
        ?>
        <div class="head-block">
            <div class="block-button pull-right">
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    'url' => '#',
                    'label' => ' Доставка та оплата',
                    'buttonType' => 'link',
                    'context' => 'success',
                    'size' => 'extra_small',
                    'htmlOptions' => array(
                        'class' => 'm-r v-a-b',
                        'data-toggle' => "modal",
                        'data-target' => "#payTypes",
                        'id' => 'getPayTypes'
                    ),
                ));
                $this->widget("booster.widgets.TbButton", array(
                    'url' => Yii::app()->createUrl('/event/event/preview',array('id'=>$model->id)),
                    'label' => ' Попередній перегляд',
                    'icon' => 'eye-open',
                    'buttonType' => 'link',
                    'context' => 'success',
                    'size' => 'extra_small',
                    'htmlOptions' => array(
                        'class' => 'm-r v-a-b',
                        'target' => '_blank',
                    ),
                ));
                $this->widget("booster.widgets.TbButton", array(
                    'url' => Yii::app()->createUrl('/event/event/create'),
                    'label' => $model->getOpenCloseMessage(),
                    'encodeLabel'=>false,
                    'icon' => $model->isInSale?'remove':'ok',
                    'context' => $model->isInSale?'danger':'success',
                    'htmlOptions' => array(
                        'id' => 'btnStartSale',
                        'data-id' => $model->id,
                        'data-open' => $model->isInSale,
                    )
                ));
                ?>

            </div>
            <div class="clearfix"></div>
        </div>
        <hr/>
        <div class="main-block">
            <div class="map-content">
                <div class="head">
                    <div class="row">
                        <div class="col-xs-5">
                            <?php $this->beginWidget("booster.widgets.TbActiveForm", array("type" => "inline")) ?>
                            <div class="form-group">
                                <?= CHtml::textField("price", "", array('class' => 'form-control input-sm')) ?>
                                <?= CHtml::dropDownList("typeSet", "", Place::$price, array('class' => 'form-control input-sm')) ?>
                                <?php
                                $this->widget('booster.widgets.TbButton', array(
                                    "buttonType"=>"submit",
                                    "context"=>"primary",
                                    "label"=>"OK",
                                    'size' => 'small',
                                    "htmlOptions"=>array(
                                        "id"=>"setPrice",
                                        "data-url"=>$this->createUrl("setPrice"),
                                    )
                                ));
                                $this->widget('booster.widgets.TbButton', array(
                                    "buttonType"=>"button",
                                    "context"=>"warning",
                                    "label"=>"Прибрати ціну",
                                    'size' => 'small',
                                    "htmlOptions"=>array(
                                        "id"=>"delPrice",
                                        "data-url"=>$this->createUrl("deletePrice")
                                    )
                                ));
                                ?>
                            </div>
                            <?php $this->endWidget()?>
                        </div>
                        <div class="col-xs-7">
                            <?php $this->beginWidget("booster.widgets.TbActiveForm", array(
                                "id"=>"amount_info_block",
                                "type"=>"inline",
                                "htmlOptions"=>array(
                                    "style"=>"visibility: hidden;"
                                )
                            ))?>
                            <div class="form-group">
                                <?= CHtml::dropDownList("actionType", "", Place::$place, array('class' => 'form-control input-sm')) ?>
                                <input type="text" class="form-control input-sm" placeholder="к-сть" id="amount">
                                <?php $this->widget('booster.widgets.TbButton', array(
                                    "buttonType"=>"submit",
                                    "context"=>"primary",
                                    "label"=>"OK",
                                    'size' => 'small',
                                    "htmlOptions"=>array(
                                        "id"=>"setFunCount",
                                        "data-url"=>$this->createUrl("setFunCount")
                                    )
                                )) ?>
                            </div>
                            <div class="summary text-sm">
                                <span>Використано: <span id="sold_amount"></span></span>
                                <span>Доступно: <span id="available_amount"></span></span>
                                <span>Всього: <span id="total_amount"></span></span>
                            </div>
                            <?php $this->endWidget();?>
                        </div>
                    </div>
                </div>
                <div class="map">
                    <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
                        "class"=>"editor_cont preview",
                        "hasMacro"=>$model->scheme->hasMacro,
                        "funZones"=>Sector::getFunSectors($model->scheme_id, true)
                    ));?>
                </div>
                <div class="foot" id="selected_info_cont" style="visibility: hidden;">
                    <p>Виділено: <span id="select_row_info">рядів - <strong></strong>, </span><span>місць - <strong id="select_seat_amount"></strong>, </span> Вартістю: <strong id="select_price_amount"></strong> грн</p>
                </div>
            </div>
            <div class="sidebar">
                <div class="filter">
                    <section class="panel no-borders widget-constructor">
                        <div class="block-button">
                            <button type="button" class="btn btn-primary btn-icon m-r-xs" id="invoiceModal" data-toggle="modal" data-target="#getInvoice"><i class="fa fa-file-text"></i></button>
                            <button type="button" class="btn btn-primary btn-icon" data-toggle="modal" data-target="#modal-chart"><i class="fa fa-pie-chart"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="wrapper">
                                    <p class="m-n">Всього</p>
                                    <p class="h3 font-bold m-b-sm countAll"><?= $countAll ?></p>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <small>З ціною</small>
                                            <p class="h4 font-bold countWithPrice"><?= $countWithPrice ?></p>
                                        </div>
                                        <div class="col-xs-8">
                                            <small>На суму</small>
                                            <p class="h4 font-bold sumAll"><?= $sumAll ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading1">
                                <a class="collapsed bg-dark" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">Ціни</a>
                            </div>
                            <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                                <div class="panel-body">
                                    <div class="prices">
                                        <?php $this->renderPartial("_prices", array("model"=>$model)) ?>
                                    </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading2">
                                <a class="collapsed bg-dark" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">Сектори</a>
                            </div>
                            <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                                <div class="panel-body">
                                    <div class="sectors">
                                        <?php
                                        $this->renderPartial("sectors", array("model"=>$model, "sectors"=>$sectors));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading3">
                                <a class="collapsed bg-dark" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">Партнери (API)</a>
                            </div>
                            <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                                <div class="panel-body">
                                    --- немає даних ---
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading4">
                               <a class="collapsed bg-dark" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="false" aria-controls="collapse4">Статуси</a>
                            </div>
                            <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                                <div class="panel-body">
                                    --- немає даних ---
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-chart" tabindex="-1" role="dialog" aria-labelledby="Chart">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Діаграма цін</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-4">
                        <h5><strong>Діаграма по:</strong></h5>
                        <div class="radio m-l-lg">
                            <label>
                                <input type="radio" name="x" value="0" checked="">
                                кількіості
                            </label>
                        </div>
                        <div class="radio m-l-lg">
                            <label>
                                <input type="radio" name="x" value="1" checked="">
                                сумі
                            </label>
                        </div>
                        <h5 class="m-t-lg"><strong>Враховуються:</strong></h5>
                        <div class="radio">
                            <label>
                                <input type="checkbox" name="z" value="0" checked="">
                                фан-зона
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="checkbox" name="z" value="1" checked="">
                                сидячі місця
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <canvas id="chart" width="300" height="300"></canvas>
                        <p>Усього місць: <strong>156 485</strong>, на суму <strong>233 455 454 грн</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/chart.min.js" cache="false"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var data = [
            {
                value: 300,
                color: "#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            }
        ];
        $('#modal-chart').on('shown.bs.modal',function(e){
            var ctx = $("#chart").get(0).getContext("2d");
            var myPieChart = new Chart(ctx).Pie(data);
        });


    });
</script>
