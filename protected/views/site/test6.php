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
            <h4 class="m-t m-b pull-left">Інкасація</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#">
        <div class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Каси, що друкували</label>
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
                            <label>Касири, що друкували</label>
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
                            <label>Подія</label>
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
            </div>
            <div class="col-xs-3">
                <p class="fs-16 m-b-none"><strong>Всього до інкасації</strong></p>
                <div class="fs-26 m-b-xs">5 000 грн.</div>
                <p class="fs-16 m-b-none"><strong>Інкасовано раніше</strong></p>
                <div class="fs-26 m-b-xs">4 000 грн.</div>
                <p class="fs-16 m-b-none"><strong>Доступно до інкасації</strong></p>
                <div class="fs-26 m-b-xs">1 000 грн.</div>
            </div>
        </div>
    </form>
    <hr/>
    <div class="page-collection-list">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2">
                <div class="row">
                    <div class="col-xs-4"><p class="fs-16 m-b-none m-t-xs text-right"><strong>Сума інкасації:</strong></p></div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control m-b" placeholder="1000 грн">
                        <div class="checkbox">
                            <label class="checkbox-custom">
                                <input type="checkbox" name="checkboxA" checked="checked">
                                <i class="fa fa-square-o checked"></i>
                                Інкасація на дату
                            </label>
                        </div>
                        <input class="input-sm input-s datepicker-input form-control" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy">
                    </div>
                    <div class="col-xs-4">
                        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#collection-report">Звіт по інкасації</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="collection-report" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="pull-right">
                    <a href="#" class="text-sm text-mutted m-r-lg">сформувати .xls</a>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-xs-6">
                        <label>Дата інкасації</label>
                        <p class="fs-16 m-b">12.12.2012 15:47</p>
                        <label>Інкасація на дату</label>
                        <p class="fs-16">12.12.2012 15:47</p>
                    </div>
                    <div class="col-xs-6">
                        <label>Ім'я інкасатора</label>
                        <p class="fs-16 m-b">Ім'я Прізвище</p>
                        <label>Сума інкасації</label>
                        <p class="fs-16"><strong>120 000,00 грн.</strong></p>
                    </div>
                </div>
                <hr/>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id344 - Ім'я Прізвище касира / Гравець</th>
                        <th>14 340 344,23 грн.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id344 - Ім'я Прізвище касира / Гравець</th>
                        <th>14 340 344,23 грн.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                    <tr>
                        <th>id344 - Ім'я Прізвище касира / Гравець</th>
                        <th>14 340 344,23 грн.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    <tr>
                        <td>Океан Ельзи - "20 років разом"</td>
                        <td>14 340 344,23 грн.</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
