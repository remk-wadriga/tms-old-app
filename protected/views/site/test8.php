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
            <h4 class="m-t m-b pull-left">Статистика</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#">
        <div class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label>Місто проведення</label>
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
                            <div class="col-xs-12">
                                <div class="filter-block">
                                    <div class="pull-left filter-icon"><i class="fa fa-users"></i></div>
                                    <div class="filter-cont">
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label class="block m-b-lg">Каси, що створили</label>
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
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label class="block m-b-lg">Касири, що створили</label>
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
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" value="0">Період створення
                                                    </label>
                                                </div>
                                                <div class="input-daterange input-group" id="datepicker">
                                                    <input type="text" class="input-sm form-control" name="start" />
                                                    <span class="input-group-addon">-</span>
                                                    <input type="text" class="input-sm form-control" name="end" />
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
                                            <div class="col-xs-7">
                                                <div class="row">
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label class="block m-b-lg">Каси, що друкували</label>
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
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label class="block m-b-lg">Касири, що друкували</label>
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
                                                </div>
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" value="0">Період друку
                                                    </label>
                                                </div>
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
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <label>Які квитки враховувати</label>
                        <div class="form-group nb">
                            <label><input type="checkbox"> Касир отримував кошти</label>
                            <label class="m-l-lg"><input type="checkbox"> Оформлено касиром</label>
                            <label class="m-l-lg"><input type="checkbox"> Самовивіз з каси</label>
                            <label><input type="checkbox"> Касир НЕ отримував кошти</label>
                            <label><input type="checkbox"> Запрошення</label>
                        </div>
                        <button class="btn btn-success block m-t-lg btn-full">Показати</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <section class="panel">
                    <section class="panel-body result-block">
                        <h2 class="m-t-none">Результат запиту</h2>
                        <label class="block">вартість</label>
                        <strong>123 456 456 грн</strong>
                        <div class="clearfix"></div>
                        <div class="pull-left sm">
                            <label class="block">квитків</label>
                            <strong>123 456</strong>
                        </div>
                        <div class="pull-right sm">
                            <label class="block">замовлень</label>
                            <strong>123</strong>
                        </div>
                        <div class="clearfix"></div>
                        <div class="m-t">
                            <a href="#" class="text-sm text-mutted">Згенерувати звіт касира: <span class="m-l m-r">.xls</span><span class="m-r">.pdf</span></a>
                        </div>
                    </section>
                </section>

            </div>
        </div>
    </form>
    <hr/>
    <table class="table table-bordered table-condensed table-statistic">
        <thead>
        <tr>
            <th rowspan="3">№</th>
            <th rowspan="3">Подія/касир</th>
            <th colspan="6">Касир отримав кошти</th>
            <th colspan="3" rowspan="2">Касир не отримував кошти</th>
            <th rowspan="2">Запрошення</th>
            <th colspan="4" rowspan="2">Разом (без запрошень)</th>
        </tr>
        <tr>
            <th colspan="3">оформлено касиром (прямий продаж)</th>
            <th colspan="3">самовивіз з каси</th>
        </tr>
        <tr>
            <th>шт</th>
            <th>на суму</th>
            <th>%, грн</th>
            <th>шт</th>
            <th>на суму</th>
            <th>%, грн</th>
            <th>шт</th>
            <th>на суму</th>
            <th>%, грн</th>
            <th>шт</th>
            <th>шт</th>
            <th>на суму</th>
            <th>%, грн</th>
            <th>до інкасації</th>
        </tr>
        </thead>
        <tbody>
        <tr class="success">
            <td colspan="16">Концерт пам'яті Скрябіна / Львів / 21.06.15 / 19:00 </td>
        </tr>
        <tr>
            <td>1</td>
            <td>Мельпомена</td>
            <td>100</td>
            <td>10 000,00</td>
            <td>700,00</td>
            <td>20</td>
            <td>2 000,00</td>
            <td>60,00</td>
            <td>75</td>
            <td>7 500,00</td>
            <td>75,00</td>
            <td>5</td>
            <td>195</td>
            <td>19 500,00</td>
            <td>835,00</td>
            <td>11 165,00</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Інтернет-білет</td>
            <td>80</td>
            <td>8 000,00</td>
            <td>400,00</td>
            <td>15</td>
            <td>1 500,00</td>
            <td>-</td>
            <td>10</td>
            <td>1 000,00</td>
            <td>-</td>
            <td>0</td>
            <td>105</td>
            <td>10 500,00</td>
            <td>400,00</td>
            <td>9 100,00</td>
        </tr>
        <tr>
            <td></td>
            <td>Разом по події</td>
            <td>180</td>
            <td>18 000,00</td>
            <td>1 100,00</td>
            <td>35</td>
            <td>3 500,00</td>
            <td>60,00</td>
            <td>85</td>
            <td>8 500,00</td>
            <td>75,00</td>
            <td>5</td>
            <td>300</td>
            <td>30 000,00</td>
            <td>1 235,00</td>
            <td>20 265,00</td>
        </tr>
        </tbody>
        <tfoot>
            <tr class="info">
                <td></td>
                <td>Всього по звіту:</td>
                <td>360</td>
                <td>36000</td>
                <td>2200</td>
                <td>70</td>
                <td>7000</td>
                <td>120</td>
                <td>170</td>
                <td>17000</td>
                <td>150</td>
                <td>10</td>
                <td>600</td>
                <td>60 000,00</td>
                <td>2 470,00</td>
                <td>40 530,00</td>
            </tr>
        </tfoot>
    </table>
</div>