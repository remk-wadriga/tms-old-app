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
            <h4 class="m-t m-b pull-left">Розширена статистика</h4>
        </div>
    </div>
</header>
<div class="wrapper">
        <div class="row">
            <div class="col-xs-12">
                <?= CHtml::link("<span class='glyphicon glyphicon-cog'></span> Загальна статистика", array("/statistics/statistics/basic", "event_id"=>$model->id), array('class'=>'btn btn-success btn-link btn-xs m-b')) ?>
                <div class="row">
                    <?php
                    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
                        "id"=>"select-event-form",
                        "action"=>$this->createUrl("extended"),
                        "method"=>"GET"

                    ));
                    ?>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Назва події</label>
                            <div class="pull-right">
<!--                                <label class="checkbox-inline m-r-lg">-->
<!--                                    <input class="m-r-lg" type="checkbox" value="1"> Активні-->
<!--                                </label>-->
<!--                                <label class="checkbox-inline">-->
<!--                                    <input type="checkbox" value="0"> Не активні-->
<!--                                </label>-->
                            </div>
                            <?php
                            $events = Event::getListEvents((is_array($model->status)? $model->status: false), [],
                                Yii::app()->authManager->getAllowedIds("/".$this->getRoute()), false);

                            echo $form->dropDownList($model, "id", ["0"=>"Виберіть подію"]+$events['data'], array(
                                'allowClear' => true,
                                'class' => 'to-select2-ext',
                                'options' => $events['options'],
                            ));
                            ?>
                        </div>
                    </div>
                    <?php
                    $this->endWidget()
                    ?>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-3">
                        <section class="panel">
                            <ul class="nav">
                                <li class="active"><a href="#tab-1" data-toggle="tab">Способи доставки та оплати</a></li>
                                <li><a href="#tab-2" data-toggle="tab">Сектори та цінові категорії</a></li>
                                <li><a href="#tab-3" data-toggle="tab">По контрагентах</a></li>
                                <li><a href="#tab-4" data-toggle="tab">Розцінка залу</a></li>
                                <li><a href="#tab-5" data-toggle="tab">КГ-9</a></li>
                                <li><a href="#tab-6" data-toggle="tab">КГ-10</a></li>
                                <li><a href="#tab-7" data-toggle="tab">Залишки квитків</a></li>
                            </ul>

                        </section>
                    </div>
                    <div class="col-xs-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <div class="m-b-lg">
                                    <a href="#" class="text-mutted m-r-lg">.pdf</a>
                                    <a href="#" class="text-mutted">.xls</a>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>Готівкою</th>
                                        <th>Банківська карта</th>
                                        <th>Всього</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $sumCountByCash = 0;
                                        $sumCountByCard = 0;
                                        $sumSumByCash = 0;
                                        $sumSumByCard = 0;
                                        $sumCount = 0;
                                        $sumSum = 0;
                                        foreach ($deliveryAndPayData as $type=>$data) {
                                            $sumCountByCash += $data["byCashCount"];
                                            $sumCountByCard += $data["byCardCount"];
                                            $sumSumByCash += $data["byCashSum"];
                                            $sumSumByCard += $data["byCardSum"];
                                            ?>
                                            <tr>
                                                <td rowspan="2"><?=$type?></td>
                                                <td>грн.</td>
                                                <td><?=$data["byCashSum"]?></td>
                                                <td><?=$data["byCardSum"]?></td>
                                                <td><?=$data["byCashSum"]+$data["byCardSum"]?></td>
                                            </tr>

                                            <tr>
                                                <td>шт.</td>
                                                <td><?=$data["byCashCount"]?></td>
                                                <td><?=$data["byCardCount"]?></td>
                                                <td><?=$data["byCashCount"]+$data["byCardCount"]?></td>
                                            </tr>
                                        <?php
                                        }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td rowspan="2">Всього</td>
                                        <td>грн.</td>
                                        <td><?=$sumSumByCash?></td>
                                        <td><?=$sumSumByCard?></td>
                                        <td><?=$sumSumByCash+$sumSumByCard?></td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td><?=$sumCountByCash?></td>
                                        <td><?=$sumCountByCard?></td>
                                        <td><?=$sumCountByCash+$sumCountByCard?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">
                                <div class="m-b-lg">
                                    <a href="#" class="text-mutted m-r-lg">.pdf</a>
                                    <a href="#" class="text-mutted">.xls</a>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label>Гравець</label>
                                            <select class="to-select2">
                                                <optgroup label="Alaskan/Hawaiian Time Zone">
                                                    <option value="AK">Alaska</option>
                                                    <option value="HI">Hawaii</option>
                                                </optgroup>
                                                <optgroup label="Pacific Time Zone">
                                                    <option value="CA">California</option>
                                                    <option value="NV">Nevada</option>
                                                    <option value="OR">Oregon</option>
                                                    <option value="WA">Washington</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Фільтр по даті</label>
                                            <div class="input-daterange input-group" id="datepicker">
                                                <input type="text" class="input-sm form-control" name="start" />
                                                <span class="input-group-addon">-</span>
                                                <input type="text" class="input-sm form-control" name="end" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr/>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Сектор</th>
                                        <th>Ціна</th>
                                        <th>Продано</th>
                                        <th>Бронь</th>
                                        <th>Запрошення</th>
                                        <th>Сума</th>
                                        <th>Разом</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>VIP-fans</td>
                                        <td>1250</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>15000 грн</td>
                                        <td>12 шт<br>15000грн</td>
                                    </tr>
                                    <tr>
                                        <td>VIP-fans</td>
                                        <td>1250</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>15000 грн</td>
                                        <td>12 шт<br>15000грн</td>
                                    </tr>
                                    <tr>
                                        <td>VIP-fans</td>
                                        <td>1250</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>15000 грн</td>
                                        <td>12 шт<br>15000грн</td>
                                    </tr>
                                    <tr>
                                        <td>VIP-fans</td>
                                        <td>1250</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>15000 грн</td>
                                        <td>12 шт<br>15000грн</td>
                                    </tr>
                                    <tr>
                                        <td>VIP-fans</td>
                                        <td>1250</td>
                                        <td>12</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>15000 грн</td>
                                        <td>12 шт<br>15000грн</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Разом:</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-3">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>Продано, шт</th>
                                        <th>Бронь, шт</th>
                                        <th>Запрошення, шт</th>
                                        <th>Сума, грн (бронь + продано)</th>
                                        <th>Частка валу, %</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sumSold = 0;
                                    $sumReserved = 0;
                                    $sumInvite = 0;
                                    $sumSum = 0;
                                    $sumVal = 0;
                                    foreach ($rolesData as $roleName => $roleData) {
                                        $sumSold += $roleData["sold"];
                                        $sumReserved += $roleData["reserved"];
                                        $sumInvite += $roleData["invite"];
                                        $sumSum += $roleData["sum"];
                                        $sumVal += $roleData["val"];
                                        ?>
                                        <tr>
                                            <td><?=$roleName?></td>
                                            <td><?=$roleData["sold"]?></td>
                                            <td><?=$roleData["reserved"]?></td>
                                            <td><?=$roleData["invite"]?></td>
                                            <td style="text-align: right;"><?= number_format($roleData["sum"], 2 , ",", " ")?></td>
                                            <td><?=$roleData["val"]?> %</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Разом:</td>
                                        <td><?=$sumSold?></td>
                                        <td><?=$sumReserved?></td>
                                        <td><?=$sumInvite?></td>
                                        <td style="text-align: right;"><?=number_format($sumSum, 2 , ",", " ")?></td>
                                        <td><?=$sumVal?> %</td>
                                    </tr>
                                    </tfoot>
                                </table>
<!--                            </div>-->
                            </div>
                            <div class="tab-pane" id="tab-4">
                                <div class="form-group">
                                    <?php
                                    echo CHtml::radioButtonList('invoiceType',0,ExelGenerator::$types, array(
                                        "style"=>"margin-left:5px",
                                        "id"=>"invoiceType"
                                    ));
                                    ?>
                                </div>
                                <a href="#" class="btn btn-success btn-link btn-xs m-b m-r">Завантажити .pdf</a>
                                <a href="#" class="btn btn-success btn-link btn-xs m-b" id="valXls">Завантажити .xls</a>
                            </div>
                            <div class="tab-pane" id="tab-5">
                                <div class="m-b-lg">
                                    <a href="#" class="text-mutted m-r-lg">.pdf</a>
                                    <?php
                                    echo CHtml::link(".xls",CController::createAbsoluteUrl('/statistics/statistics/generateKG9Xls',array("id"=>$model->id)),array(
                                        "target"=>"_blank",
                                        "class"=>"btn",
                                    ))?>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Вартість квитка,<br>грн</th>
                                        <th colspan="2">Усього до реалізації за абонементами</th>
                                        <th colspan="2">Реалізовано за абонементами</th>
                                        <th colspan="2">Не реалізовано за абонементами</th>
                                    </tr>
                                    <tr>
                                        <th>кільк.</th>
                                        <th>сума, грн.</th>
                                        <th>кільк.</th>
                                        <th>сума, грн.</th>
                                        <th>кільк.</th>
                                        <th>сума, грн.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sumRealizedCount = 0;
                                    $sumRealizedSum = 0;
                                    $sumNotRealizedCount = 0;
                                    $sumNotRealizedSum = 0;
                                    foreach ($KG9Data as $price => $data) {
                                        $sumRealizedCount += $data["realizedCount"];
                                        $sumRealizedSum += $data["realizedSum"];
                                        $sumNotRealizedCount += $data["notRealizedCount"];
                                        $sumNotRealizedSum += $data["notRealizedSum"];

                                        ?>
                                        <tr>
                                            <td><?=number_format($price, 2, ',', ' ')?></td>
                                            <td><?=$data["realizedCount"]+$data["notRealizedCount"]?></td>
                                            <td><?=number_format($data["realizedSum"]+$data["notRealizedSum"], 2, ',', ' ')?></td>
                                            <td><?=$data["realizedCount"]?></td>
                                            <td><?=number_format($data["realizedSum"], 2, ',', ' ')?></td>
                                            <td><?=$data["notRealizedCount"]?></td>
                                            <td><?=number_format($data["notRealizedSum"], 2, ',', ' ')?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Всього:</td>
                                        <td><?=$sumRealizedCount+$sumNotRealizedCount?></td>
                                        <td><?=number_format($sumRealizedSum+$sumNotRealizedSum, 2, ',', ' ')?></td>
                                        <td><?=$sumRealizedCount?></td>
                                        <td><?=number_format($sumRealizedSum, 2, ',', ' ')?></td>
                                        <td><?=$sumNotRealizedCount?></td>
                                        <td><?=number_format($sumNotRealizedSum, 2, ',', ' ')?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-6">
                                <div class="m-b-lg">
                                    <a href="#" class="text-mutted m-r-lg">.pdf</a>
                                    <?php
                                    echo CHtml::link(".xls",CController::createAbsoluteUrl('/statistics/statistics/generateKG10Xls',array("id"=>$model->id)),array(
                                        "target"=>"_blank",
                                        "class"=>"btn",
                                    ))?>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th rowspan="2">Назва контрагента</th>
                                        <th colspan="2">Видано</th>
                                        <th colspan="2">Реалізовано</th>
                                        <th colspan="2">Повернуто</th>
                                    </tr>
                                    <tr>
                                        <th>К-ть місць</th>
                                        <th>Сума, грн</th>
                                        <th>К-ть місць</th>
                                        <th>Сума, грн</th>
                                        <th>К-ть місць</th>
                                        <th>Сума, грн</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $publishedCountSum = 0;
                                    $publishedSumSum = 0;
                                    $realizedCountSum = 0;
                                    $realizedSumSum = 0;
                                    $returnedCountSum = 0;
                                    $returnedSumSum = 0;
                                    foreach ($KG10Data as $data) {
                                        $publishedCountSum += $data["publishedCount"];
                                        $publishedSumSum += $data["publishedSum"];
                                        $realizedCountSum += $data["realizedCount"];
                                        $realizedSumSum += $data["realizedSum"];
                                        $returnedCountSum += $data["returnedCount"];
                                        $returnedSumSum += $data["returnedSum"];
                                            ?>
                                            <tr>
                                                <td><?=$data["roleName"]?></td>
                                                <td><?=$data["publishedCount"]?></td>
                                                <td><?=number_format($data["publishedSum"], 2, ',', ' ')?></td>
                                                <td><?=$data["realizedCount"]?></td>
                                                <td><?=number_format($data["realizedSum"], 2, ',', ' ')?></td>
                                                <td><?=$data["returnedCount"]?></td>
                                                <td><?=number_format($data["returnedSum"], 2, ',', ' ')?></td>
                                            </tr>
                                            <?php
                                        }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Усього</td>
                                        <td><?=$publishedCountSum?></td>
                                        <td><?=number_format($publishedSumSum, 2, ',', ' ')?></td>
                                        <td><?=$realizedCountSum?></td>
                                        <td><?=number_format($realizedSumSum, 2, ',', ' ')?></td>
                                        <td><?=$returnedCountSum?></td>
                                        <td><?=number_format($returnedSumSum, 2, ',', ' ')?></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-7">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>