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
            <h4 class="m-t m-b pull-left">Контроль каси</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#">
        <div class="row">
            <div class="col-xs-offset-2 col-xs-8">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Каса</label>
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
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Касир</label>
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
                        <p>Старший касир для цього касира</p>
                        <h4>id123 - Ім'я Прізвище</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <label>Фільтр по даті</label>
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" name="start" />
                            <span class="input-group-addon">-</span>
                            <input type="text" class="input-sm form-control" name="end" />
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <div class="pull-right m-t-lg">
                            <a href="#">.xls</a>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="tr-head">
                                    <td>Всього:</td>
                                    <td>123 456,00 грн.</td>
                                    <td>122 456,00 грн.</td>
                                    <td>1 000,00 грн.</td>
                                </tr>
                                <tr><td colspan="4">&nbsp;</td></tr>
                                <tr>
                                    <th>Дата</th>
                                    <th>Сума згідно системних підрахунків</th>
                                    <th>Заявлено касиром</th>
                                    <th>Недостача</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 250,00</td>
                                    <td class="text-right"><span class="text-danger">250</span></td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 250,00</td>
                                    <td class="text-right"><span class="text-danger">250</span></td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr>
                                    <td>20.11.2014</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">12 500,00</td>
                                    <td class="text-right">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr/>
                <div class="block-pagination m-t-sm m-b-sm">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="pagination pagination-sm m-n">
                                <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group perpage">
                                <label>Показувати сторінок:</label>
                                <select name="sample" class="form-control input-sm">
                                    <option>10</option>
                                    <option>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>