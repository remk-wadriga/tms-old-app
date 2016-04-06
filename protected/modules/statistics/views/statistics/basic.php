<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 09.06.15
 * Time: 20:22
 * @var SiteController $this
 */
?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Загальна статистика</h4>
        </div>
    </div>
</header>
<div class="wrapper">
        <div class="row">
            <div class="col-xs-12">
                <?= CHtml::link("<span class='glyphicon glyphicon-cog'></span>Розширена статистика", array("/statistics/statistics/extended", "event_id"=>$model->event_id), array('class'=>'btn btn-success btn-link btn-xs m-b')) ?>
                <div class="row">
                    <?php
                    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
                        "id"=>"select-event-form",
                        "action"=>$this->createUrl("basic"),
                        "method"=>"GET"
                    ));
                    ?>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Назва події</label>
                            <div class="pull-right">
                                <?php
//                                echo $form->checkboxListGroup($event, "status", array(
//                                    "label"=>false,
//                                    "widgetOptions"=>array(
//                                        "data"=>array(
//                                            Event::STATUS_ACTIVE=>" Активні",
//                                            Event::STATUS_NO_ACTIVE=>" Не активні"
//                                        ),
//                                        "htmlOptions"=>array(
//                                            "labelOptions"=>array(
//                                                "class"=>"checkbox-inline"
//                                            ),
//                                        )
//                                    )
//                                ));
                                ?>
                            </div>
                            <?php
                                $events = Event::getListEvents((is_array($event->status)? $event->status: false), [],
                                    Yii::app()->authManager->getAllowedIds("/".$this->getRoute()), false
                                );

                                echo $form->dropDownList($model, "event_id",$events['data'], array(
                                    'allowClear' => true,
                                    'empty'=>'Виберіть подію',
                                    'class' => 'to-select2-ext',
                                    'options' => $events['options'],
                                ));
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Фільтр по даті</label>
                                    <div class="input-daterange input-group" id="datepicker">
                                        <?php echo $form->textField($model, "start_period", array(
                                            "class"=>"input-sm form-control order-period"
                                        ))?>
                                        <span class="input-group-addon">-</span>
                                        <?php echo $form->textField($model, "end_period", array(
                                            "class"=>"input-sm form-control order-period"
                                        ))?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $this->endWidget()
                    ?>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-4">
                        <section class="panel no-borders hbox">
                                <div class="text-center m-b-lg m-t">
                                    <div class="font-bold h4 m-b-sm">ПРОДАНО</div>
                                    <p class="h1"><?=$placesData["placesSold"]["count"]?><span class="text-sm m-l"><?=$placesData["placesSold"]["percent"]?> %</span></p>
                                    <p class="h4"><?=$placesData["placesSold"]["sum"]?> грн.</p>
                                </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ВСЬОГО З ЦІНОЮ</div>
                                        <p class="h3"><?=$placesData["placesWithPrice"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesWithPrice"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesWithPrice"]["sum"]?> грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">У ПРОДАЖУ</div>
                                        <p class="h3"><?=$placesData["placesOnSale"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesOnSale"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesOnSale"]["sum"]?> грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАБРОНЬОВАНО</div>
                                        <p>(не оплачено)</p>
                                        <p class="h3"><?=$placesData["placesReserved"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesReserved"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesReserved"]["sum"]?> грн.</div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАПРОШЕННЯ</div>
                                        <p class="h3"><?=$placesData["placesInvite"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesInvite"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesInvite"]["sum"]?> грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ПЕРЕДАНО НА РЕАЛІЗАЦІЮ</div>
                                        <p class="h3"><?=$placesData["placesReservedQuote"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesReservedQuote"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesReservedQuote"]["sum"]?> грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАКРИТО З ПРОДАЖУ</div>
                                        <p class="h3"><?=$placesData["placesClosedFromSale"]["count"]?> <span class="text-sm m-l"><?=$placesData["placesClosedFromSale"]["percent"]?> %</span></p>
                                        <div class="text-sm"><?=$placesData["placesClosedFromSale"]["sum"]?> грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">НЕ ЗАДІЯНО</div>
                                        <p class="h3"><?=$placesData["withNoPriceCount"]?> </p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-8">
                        <div class="m-b-lg">
                            <div class="h4 text-center m-b m-t">Динаміка продажу, без врахування квот</div>
                            <!--                <canvas id="chart-line" style="width: 100%; height: 300px;"></canvas>-->

                            <div id="highChart" style="min-width: 310px; height: 380px; margin: 0 auto"></div>
<!--                        <table class="table table-bordered">-->
<!--                            <thead>-->
<!--                            <tr>-->
<!--                                <th>Група зон</th>-->
<!--                                <th>Продано, шт</th>-->
<!--                                <th>Бронь, шт</th>-->
<!--                                <th>Запрошення, шт</th>-->
<!--                                <th>Сума, грн</th>-->
<!--                                <th>Кількість, %</th>-->
<!--                            </tr>-->
<!--                            </thead>-->
<!--                            <tbody>-->
<!--                            --><?php
//                            $sumSold = 0;
//                            $sumReserved = 0;
//                            $sumInvite = 0;
//                            $sumSum = 0;
//                            $sumVal = 0;
//                            foreach ($sectorsData as $sectorName => $sectorData) {
//                                $sumSold += $sectorData["sold"];
//                                $sumReserved += $sectorData["reserved"];
//                                $sumInvite += $sectorData["invite"];
//                                $sumSum += $sectorData["sum"];
//                                $sumVal += $sectorData["val"];
//                                ?>
<!--                                <tr>-->
<!--                                    <td>--><?//=$sectorName?><!--</td>-->
<!--                                    <td>--><?//=$sectorData["sold"]?><!--</td>-->
<!--                                    <td>--><?//=$sectorData["reserved"]?><!--</td>-->
<!--                                    <td>--><?//=$sectorData["invite"]?><!--</td>-->
<!--                                    <td style="text-align: right;">--><?//=number_format($sectorData["sum"], 2 , ",", " ")?><!--</td>-->
<!--                                    <td>--><?//=$sectorData["val"]?><!-- %</td>-->
<!--                                </tr>-->
<!--                                --><?php
//                            }
//                            ?>
<!--                            </tbody>-->
<!--                            <tfoot>-->
<!--                            <tr class="font-bold">-->
<!--                                <td>Разом:</td>-->
<!--                                <td>--><?//=$sumSold?><!--</td>-->
<!--                                <td>--><?//=$sumReserved?><!--</td>-->
<!--                                <td>--><?//=$sumInvite?><!--</td>-->
<!--                                <td style="text-align: right;">--><?//=number_format($sumSum, 2 , ",", " ")?><!--</td>-->
<!--                                <td>--><?//=$sumVal?><!-- %</td>-->
<!--                            </tr>-->
<!--                            </tfoot>-->
<!--                        </table>-->
                    </div>
                </div>

                <div class="col-xs-12">
                    <?php
                    if(!empty($customTable)) {
                        ?>
                        <hr/>
                        <h3>Загальні продажі контрагентів</h3>
                        <table class="table table-bordered table-style-1">
                            <thead>
                            <tr class="tr-1">
                                <th rowspan="2">Контрагент</th>
                                <th>Продано</th>
                                <th>Бронь</th>
                                <th>Передано на реалізацію</th>
                                <th>Запрошення</th>
                                <th>Сума (продано)</th>
                                <th>Частка валу</th>
                            </tr>
                            <tr class="tr-1">
                                <td>шт</td>
                                <td>шт</td>
                                <td>шт</td>
                                <td>шт</td>
                                <td>грн</td>
                                <td>%</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $allSold = 0;
                            $allReserved = 0;
                            $allRealization = 0;
                            $allInvite = 0;
                            $allSum = 0;
                            $allVal = 0;
                            foreach ($customTable as $key=>$rowData) {
                                $allSold += $rowData["sold"];
                                $allReserved += $rowData["reserved"];
                                $allRealization += $rowData["quoteRealization"];
                                $allInvite += $rowData["invite"];
                                $allSum += $rowData["sum"];
                                $allVal += $rowData["val"];
                                ?>
                                <tr>
                                    <td><?=$key?></td>
                                    <td class="td-b"><?=number_format($rowData["sold"], 0, ",", " ")?></td>
                                    <td class="td-b"><?=number_format($rowData["reserved"], 0, ",", " ")?></td>
                                    <td class="td-b"><?=number_format($rowData["quoteRealization"], 0, ",", " ")?></td>
                                    <td class="td-b"><?=number_format($rowData["invite"], 0, ",", " ")?></td>
                                    <td class="td-b"><?=number_format($rowData["sum"], 2, ",", " ")?></td>
                                    <td class="td-c"><?=number_format($rowData["val"], 0, ",", " ")?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr class="tr-2">
                                <td>ВСЬОГО</td>
                                <td><?=number_format($allSold, 0, ",", " ")?></td>
                                <td><?=number_format($allReserved, 0, ",", " ")?></td>
                                <td><?=number_format($allRealization, 0, ",", " ")?></td>
                                <td><?=number_format($allInvite, 0, ",", " ")?></td>
                                <td><?=number_format($allSum, 2, ",", " ")?></td>
                                <td><?=number_format($allVal, 0, ",", " ")?></td>
                            </tr>
                            </tfoot>
                        </table>
                        <?php
                    }
                    ?>
                    <hr/>
                    <table class="table table-bordered table-style-1">
                        <thead>
                        <tr class="tr-1">
                            <th rowspan="2"></th>
                            <th rowspan="2"> Ціна</th>
                            <th colspan="2">Продано</th>
                            <th colspan="2">Разом продано</th>
                            <th colspan="2">Бронь</th>
                            <th colspan="2">Разом (продано+бронь)</th>
                            <th rowspan="2">Запрошення, шт</th>
                        </tr>
                        <tr class="tr-1">
                            <td>шт</td>
                            <td>грн</td>
                            <td>шт</td>
                            <td>грн</td>
                            <td>шт</td>
                            <td>грн</td>
                            <td>шт</td>
                            <td>грн</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $allSold = 0;
                        $allSoldSum = 0;
                        $allReserved = 0;
                        $allReservedSum = 0;
                        $allInvited = 0;
                        $allCount = 0;
                        $allSum = 0;
                        foreach ($sectorsWithPriceData as $sectorName => $placesData) {
                            if (!empty($placesData)) {
                                $placesCount = count($placesData);
                                $sectorSold = 0;
                                $sectorSoldSum = 0;
                                $sectorReserved = 0;
                                $sectorReservedSum = 0;
                                $sectorInvite = 0;
                                foreach ($placesData as $price => $place) {
                                    $sectorSold += $place["sold"];
                                    $sectorSoldSum += $place["sold"]*$price;
                                    $sectorReserved += $place["reserved"];
                                    $sectorReservedSum += $place["reserved"]*$price;
                                    $sectorInvite += $place['invited'];
                                }
                                ?>
                                <?php
                                $i = 1;

                                foreach ($placesData as $price => $place) {
                                    $allSold += $place["sold"];
                                    $allSoldSum += $place["sold"]*$price;
                                    $allReserved += $place["reserved"];
                                    $allReservedSum += $place["reserved"]*$price;
                                    $allInvited += $place["invited"];
                                    $allSum += $sectorReservedSum+$sectorSoldSum;
                                    $allCount += $place["sold"] + $place["reserved"];
                                    ?>
                                    <tr>
                                        <?php if($i < 2) { ?> <td rowspan="<?=$placesCount?>"><?=$sectorName?></td><?php } ?>
                                        <td class="td-a"><?=number_format($price, 0, ",", " ")?></td>
                                        <td class="td-b"><?=number_format($place["sold"], 0, ",", " ")?></td>
                                        <td class="td-b"><?=number_format($place["sold"] * $price, 2, ",", " ")?></td>
                                        <?php if($i < 2) { ?> <td class="td-b" rowspan="<?=number_format($placesCount, 0, ",", " ")?>"><?=number_format($sectorSold, 0, ",", " ")?></td><?php } ?>
                                        <?php if($i < 2) { ?> <td class="td-b" rowspan="<?=number_format($placesCount, 0, ",", " ")?>"><?=number_format($sectorSoldSum, 0, ",", " ")?></td><?php } ?>
                                        <td class="td-c"><?=number_format($place["reserved"], 0, ",", " ")?></td>
                                        <td class="td-c"><?=number_format($place["reserved"] * $price, 2, ",", " ")?></td>
                                        <?php if($i < 2) { ?> <td class="td-b" rowspan="<?=number_format($placesCount, 0, ",", " ")?>"><?=number_format($sectorReserved+$sectorSold, 0, ",", " ")?></td><?php } ?>
                                        <?php if($i < 2) { ?> <td class="td-b" rowspan="<?=number_format($placesCount, 0, ",", " ")?>"><?=number_format($sectorReservedSum+$sectorSoldSum, 2, ",", " ")?></td><?php } ?>
                                        <?php if($i < 2) { ?> <td class="td-d" rowspan="<?=number_format($placesCount, 0, ",", " ")?>"><?= $sectorInvite > 0 ? number_format($sectorInvite, 0, ",", " ") : "-" ?></td><?php } ?>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                <?php
                            }
                        }
                        ?>

                        </tbody>
                        <tfoot>
                        <tr class="tr-2">
                            <td>ВСЬОГО</td>
                            <td>-</td>
                            <td><?=number_format($allSold, 0, ",", " ")?></td>
                            <td><?=number_format($allSoldSum, 2, ",", " ")?></td>
                            <td><?=number_format($allSold, 0, ",", " ")?></td>
                            <td><?=number_format($allSoldSum, 2, ",", " ")?></td>
                            <td><?=number_format($allReserved, 0, ",", " ")?></td>
                            <td><?=number_format($allReservedSum, 2, ",", " ")?></td>
                            <td><?=number_format($allCount, 0, ",", " ")?></td>
                            <td><?=number_format($allReservedSum+$allSoldSum, 2, ",", " ")?></td>
                            <td><?=number_format($allInvited, 0, ",", " ")?></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
</div>
<!--<script src="--><?php //echo Yii::app()->baseUrl; ?><!--/theme/js/chart.min.js" cache="false"></script>-->
