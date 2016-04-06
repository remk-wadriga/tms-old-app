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
    <form action="#">
        <div class="row">
            <div class="col-xs-12">
                <a href="/site/test10" class="btn btn-success btn-link btn-xs m-b"><span class="glyphicon glyphicon-cog"></span> Загальна статистика</a>
                <div class="row">
                    <div class="col-xs-6">
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
                                    <tr>
                                        <td rowspan="2">Самовивіз з каси</td>
                                        <td>грн.</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td>10000</td>
                                        <td>10000</td>
                                        <td>20000</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">Доставка кур'єром</td>
                                        <td>грн.</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td>10000</td>
                                        <td>10000</td>
                                        <td>20000</td>
                                    </tr><tr>
                                        <td rowspan="2">Доставка Нова пошта</td>
                                        <td>грн.</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td>10000</td>
                                        <td>10000</td>
                                        <td>20000</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">Електронний квиток</td>
                                        <td>грн.</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td>10000</td>
                                        <td>10000</td>
                                        <td>20000</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td rowspan="2">Всього</td>
                                        <td>грн.</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                        <td>23456789</td>
                                    </tr>
                                    <tr>
                                        <td>шт.</td>
                                        <td>10000</td>
                                        <td>10000</td>
                                        <td>20000</td>
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

                            </div>
                            <div class="tab-pane" id="tab-4">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="x" value="1">Розбиття по секторах
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="x" value="2">Розбиття по секторах та цінах
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="x" value="3">Розбиття по цінах
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="x" value="4">Деталізований вал, по місцях
                                        </label>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-success btn-link btn-xs m-b m-r">Завантажити .pdf</a>
                                <a href="#" class="btn btn-success btn-link btn-xs m-b">Завантажити .xls</a>
                            </div>
                            <div class="tab-pane" id="tab-5">
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
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>2950,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>48</td>
                                        <td>141 600,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Всього:</td>
                                        <td>506</td>
                                        <td>672 759,00</td>
                                        <td>235</td>
                                        <td>355 950,00</td>
                                        <td>271</td>
                                        <td>316 800,00</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-6">
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
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    <tr>
                                        <td>Билетное агенство КАРАБАС</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>556</td>
                                        <td>152910,00</td>
                                        <td>0</td>
                                        <td>0,00</td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr class="font-bold">
                                        <td>Усього</td>
                                        <td>902</td>
                                        <td>276110,00</td>
                                        <td>899</td>
                                        <td>275540,00</td>
                                        <td>3</td>
                                        <td>570,00</td>
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
    </form>
</div>