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
            <h4 class="m-t m-b pull-left">Список інкасацій</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#">
        <div class="row">
            <div class="col-xs-5">
                <div class="form-group">
                    <label>Каси</label>
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
            <div class="col-xs-5">
                <div class="form-group">
                    <label>Касири</label>
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
            <div class="col-xs-2">
                <div class="form-group">
                    <label>Період інкасації</label>
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="input-sm form-control" name="start" />
                        <span class="input-group-addon">-</span>
                        <input type="text" class="input-sm form-control" name="end" />
                    </div>
                </div>
            </div>
            <div class="col-xs-4">
                <div class="form-group m-b-none">
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
            <div class="col-xs-6">
                <div class="form-group m-b-none">
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
            <div class="col-xs-2">
                <button class="btn btn-success block mt23 btn-full btn-sm"><i class="fa fa-search m-r-sm"></i> Показати</button>
            </div>
        </div>
    </form>
    <hr/>
    <div class="page-collection-list">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <section class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Дата інкасації</th>
                                <th>Сума інкасації</th>
                                <th>Інкасація на дату</th>
                                <th>Ім'я інкасатора</th>
                                <th>Інкасовані касири</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr><tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            <tr>
                                <td>12.12.2012 15:47</td>
                                <td>120 000,00 грн.</td>
                                <td>12.12.2012 15:47</td>
                                <td>Ім'я Прізвище</td>
                                <td>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище<br>
                                    id123 Ім'я Прізвище
                                </td>
                                <td><a href="#" class="text-sm text-mutted" data-toggle="modal" data-target="#collection-report">Звіт</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </section>
                    <section class="panel-footer">
                        <div class="block-pagination m-t-sm">
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
                    </section>
                </section>

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
