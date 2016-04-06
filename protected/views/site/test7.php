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
            <h4 class="m-t m-b pull-left">Відсоток</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#">
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <label>Каса</label>
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
                    <label>Касир</label>
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
            <div class="col-xs-12">
                <div class="pull-right">
                    <a href="#" class="btn btn-success">Зберегти зміни</a>
                </div>
                <h3>Загальні відсотки винагороди</h3>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-8 text-right">
                                <p class="fs-16 fb m-b-none">Прямий продаж</p>
                                <div>Касир створив замовлення<br>Касир прийняв оплату<br>Касир роздрукував квитки</div>
                            </div>
                            <div class="col-xs-4 form-inline">
                                    <input type="text" class="form-control input-sm m-b w60" placeholder="6"><span class="fs-16 fb m-l">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-8 text-right">
                                <p class="fs-16 fb m-b-none">Самовиві з каси</p>
                                <div>Хтось створив замовлення<br>Касир прийняв оплату<br>Касир роздрукував квитки</div>
                            </div>
                            <div class="col-xs-4 form-inline">
                                <input type="text" class="form-control input-sm m-b w60" placeholder="4"><span class="fs-16 fb m-l">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="row">
                            <div class="col-xs-8 text-right">
                                <p class="fs-16 fb m-b-none">Друк оплачених</p>
                                <div>Не важливо хто створив замовлення<br>НЕ касир прийняв оплату<br>Касир роздрукував квитки</div>
                            </div>
                            <div class="col-xs-4 form-inline">
                                <input type="text" class="form-control input-sm m-b w60" placeholder="5"><span class="fs-16 fb m-l">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <h3>Відсотки винагороди по подіях</h3>
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
                    <div class="col-xs-6">
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
                    <div class="col-xs-2">
                        <a href="#" class="btn block btn-success btn-sm m-t-23">Додати</a>
                    </div>
                </div>
                <section class="panel">
                    <section class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Подія</th>
                                <th>Прямий продаж, %</th>
                                <th>Самовивіз з каси, %</th>
                                <th>Друк оплачених, %</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                            </tr>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                            </tr>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                            </tr>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                            </tr>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                            </tr>
                            <tr>
                                <td>Назва події / МІсто / Дата / Час</td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
                                <td><input type="text" class="form-control input-sm m-b-none" placeholder="5"></td>
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
    </form>
</div>