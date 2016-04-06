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
    <form action="#">
        <div class="row">
            <div class="col-xs-12">
                <a href="/site/test11" class="btn btn-success btn-link btn-xs m-b"><span class="glyphicon glyphicon-cog"></span> Розширена статистика</a>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Назва події</label>
                            <div class="pull-right">
                                <label class="checkbox-inline m-r-lg">
                                    <input class="m-r-lg" type="checkbox" value="1"> Активні
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" value="0"> Не активні
                                </label>
                            </div>
                            <select class="to-select2" multiple="multiple">
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
                    <div class="col-xs-8">
                        <div class="row">
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
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-4">
                        <section class="panel no-borders hbox">
                                <div class="text-center m-b-lg m-t">
                                    <div class="font-bold h4 m-b-sm">ПРОДАНО</div>
                                    <p class="h1">123 456 <span class="text-sm m-l">20 %</span></p>
                                    <p class="h4">123 456 789 123 грн.</p>
                                </div>
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ВСЬОГО З ЦІНОЮ</div>
                                        <p class="h3">123 456 <span class="text-sm m-l">20 %</span></p>
                                        <div class="text-sm">123 456 789 123 грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">У ПРОДАЖУ</div>
                                        <p class="h3">123 456 <span class="text-sm m-l">20 %</span></p>
                                        <div class="text-sm">123 456 789 123 грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАБРОНЬОВАНО</div>
                                        <p class="h3">123 456 <span class="text-sm m-l">20 %</span></p>
                                        <div class="text-sm">123 456 789 123 грн.</div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАПРОШЕННЯ</div>
                                        <p class="h3">123 456 <span class="text-sm m-l">20 %</span></p>
                                        <div class="text-sm">123 456 789 123 грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">ЗАКРИТО З ПРОДАЖУ</div>
                                        <p class="h3">123 456 <span class="text-sm m-l">20 %</span></p>
                                        <div class="text-sm">123 456 789 123 грн.</div>
                                    </div>
                                    <div class="m-b-lg text-center">
                                        <div class="font-bold h6 m-b-xs">НЕ ЗАДІЯНО</div>
                                        <p class="h3">123 456</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-8">
                        <div class="m-b-lg">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Продано</th>
                                    <th>Бронь</th>
                                    <th>Запрошення</th>
                                    <th>Сума</th>
                                    <th>Чатка валу, %</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>kasa.in.ua</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Карабас</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>Concert.UA</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
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
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Група зон</th>
                                <th>Продано</th>
                                <th>Бронь</th>
                                <th>Запрошення</th>
                                <th>Сума</th>
                                <th>Кількість</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Сектор</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Фан-зона</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Sky-box</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Партер</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
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
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <hr/>
                <div class="h4 text-center m-b m-t">Динаміка продажу, без врахування квот</div>
                <canvas id="chart-line" style="width: 100%; height: 300px;"></canvas>
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
                    <tr>
                        <td rowspan="3">Фан-зона 1</td>
                        <td class="td-a">150</td>
                        <td class="td-b">10000</td>
                        <td class="td-b">1500000</td>
                        <td class="td-b" rowspan="3">10082</td>
                        <td class="td-b" rowspan="3">1527400</td>
                        <td class="td-c">3000</td>
                        <td class="td-c">450000</td>
                        <td class="td-b" rowspan="3">10000</td>
                        <td class="td-b" rowspan="3">37,150.00</td>
                        <td class="td-d" rowspan="3">-</td>
                    </tr>
                    <tr>
                        <td class="td-a">250</td>
                        <td class="td-b">13</td>
                        <td class="td-b">3250</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                    </tr>
                    <tr>
                        <td class="td-a">350</td>
                        <td class="td-b">69</td>
                        <td class="td-b">24150</td>
                        <td class="td-c">15</td>
                        <td class="td-c">5250</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Фан-зона 2</td>
                        <td class="td-a">80</td>
                        <td class="td-b">30</td>
                        <td class="td-b">2400</td>
                        <td class="td-b" rowspan="2">56</td>
                        <td class="td-b" rowspan="2">5000</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                        <td class="td-b" rowspan="2">56</td>
                        <td class="td-b" rowspan="2">5,000.00</td>
                        <td class="td-d" rowspan="2">-</td>
                    </tr>
                    <tr>
                        <td class="td-a">100</td>
                        <td class="td-b">26</td>
                        <td class="td-b">2600</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                    </tr>
                    <tr>
                        <td rowspan="3">Сектор 1</td>
                        <td class="td-a">280</td>
                        <td class="td-b">30</td>
                        <td class="td-b">8400</td>
                        <td class="td-b" rowspan="3">82</td>
                        <td class="td-b" rowspan="3">42680</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                        <td class="td-b" rowspan="3">104</td>
                        <td class="td-b" rowspan="3">59,260.00</td>
                        <td class="td-d" rowspan="3">-</td>
                    </tr>
                    <tr>
                        <td class="td-a">590</td>
                        <td class="td-b">40</td>
                        <td class="td-b">23600</td>
                        <td class="td-c">10</td>
                        <td class="td-c">5900</td>
                    </tr>
                    <tr>
                        <td class="td-a">890</td>
                        <td class="td-b">12</td>
                        <td class="td-b">10680</td>
                        <td class="td-c">12</td>
                        <td class="td-c">10680</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Сектор 3</td>
                        <td class="td-a">700</td>
                        <td class="td-b">10</td>
                        <td class="td-b">7000</td>
                        <td class="td-b" rowspan="2">30</td>
                        <td class="td-b" rowspan="2">20000</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                        <td class="td-b" rowspan="2">41</td>
                        <td class="td-b" rowspan="2">31,500.00</td>
                        <td class="td-d" rowspan="2">-</td>
                    </tr>
                    <tr>
                        <td class="td-a">650</td>
                        <td class="td-b">20</td>
                        <td class="td-b">13000</td>
                        <td class="td-c">10</td>
                        <td class="td-c">6500</td>
                    </tr>
                    <tr>
                        <td>Sky box 11</td>
                        <td class="td-a">12,00</td>
                        <td class="td-b">1</td>
                        <td class="td-b">12000</td>
                        <td class="td-b">1</td>
                        <td class="td-b">12000</td>
                        <td class="td-c">0</td>
                        <td class="td-c">0</td>
                        <td class="td-b"></td>
                        <td class="td-b"></td>
                        <td class="td-d">-</td>
                    </tr>
                    <tr>
                        <td>VIP фан-зона</td>
                        <td class="td-a">1,200</td>
                        <td class="td-b">300</td>
                        <td class="td-b">360000</td>
                        <td class="td-b">300</td>
                        <td class="td-b">360000</td>
                        <td class="td-c">1</td>
                        <td class="td-c">1200</td>
                        <td class="td-b">301</td>
                        <td class="td-b">361200</td>
                        <td class="td-d">5</td>
                    </tr>
                    </tbody>
                    <tfoot>
                        <tr class="tr-2">
                            <td>ВСЬОГО</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>10551</td>
                            <td>1967080</td>
                            <td>3048</td>
                            <td>479530</td>
                            <td>10502</td>
                            <td>494110</td>
                            <td>5</td>
                        </tr>
                    </tfoot>
                </table>
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
                        <tr>
                            <td>kasa.in.ua</td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-c"></td>
                        </tr>
                        <tr>
                            <td>Карабас</td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-c"></td>
                        </tr>
                        <tr>
                            <td>Concert.ua</td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-b"></td>
                            <td class="td-c"></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="tr-2">
                            <td>ВСЬОГО</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </form>
</div>
<script src="<?php echo Yii::app()->baseUrl; ?>/theme/js/chart.min.js" cache="false"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var data = {
            labels: ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень"],
            datasets: [
                {
                    label: "Продано",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label: "Заброньовано",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [28, 48, 40, 19, 86, 27, 90]
                }
            ]
        };
        var ctx = $("#chart-line").get(0).getContext("2d");
        var myPieChart = new Chart(ctx).Line(data);
    });
</script>
