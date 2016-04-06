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
            <h4 class="m-t m-b pull-left">Менеджер замовлень</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <form action="#" id="filter-less">
        <div class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-3">
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
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Сектор залу</label>
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
                <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-list-alt"></i></div>
                                <div class="group">
                                    <label># Замовлення</label>
                                    <input type="text" class="form-control input-sm" placeholder="# Замовлення">
                                </div>
                            </div>
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-user"></i></div>
                                <div class="group">
                                    <label>Прізвище, ім'я власника</label>
                                    <input type="text" class="form-control input-sm" placeholder="Прізвище, ім'я власника">
                                </div>
                            </div>
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-tags"></i></div>
                                <div class="group">
                                    <label>Теги:</label>
                                    <input type="text" class="form-control input-sm" placeholder="Теги">
                                </div>
                            </div>
                            <a href="#" class="text-sm m-b-xs pull-right text-muted">Скинути фільтри</a>
                            <button class="btn btn-success block m-t-sm btn-full"><i class="fa fa-search m-r-sm"></i> Знайти</button>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-phone"></i></div>
                                <div class="group">
                                    <label>Телефон власника</label>
                                    <input type="text" class="form-control input-sm" placeholder="Телефон власника">
                                </div>
                            </div>
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-envelope"></i></div>
                                <div class="group">
                                    <label>E-mail власника</label>
                                    <input type="text" class="form-control input-sm" placeholder="E-mail власника">
                                </div>
                            </div>
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-barcode"></i></div>
                                <div class="group">
                                    <label>Штрих-код квитка</label>
                                    <input type="text" class="form-control input-sm" placeholder="Штрих-код квитка">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                                <div class="group">
                                    <label>Країна</label>
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
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                                <div class="group">
                                    <label>Область</label>
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
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                                <div class="group">
                                    <label>Місто</label>
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
                            <div class="form-group form-with-icon">
                                <div class="pull-left"><img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/np.png"></i></div>
                                <div class="group">
                                    <label>ТТН</label>
                                    <input type="text" class="form-control input-sm" placeholder="ТТН">
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-xs-3">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs m-b m-r-lg" type="button" data-toggle="modal" data-target="#filtr-profile">завантажити профіль</button>
                    <button class="btn btn-primary btn-xs m-b" id="filter-less-button" type="button">більше фільтрів <i class="fa fa-filter"></i></button>
                </div>
                <div class="clearfix"></div>
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
                            <a href="#" class="text-sm text-mutted">сформувати .xls</a>
                        </div>
                    </section>
                </section>

            </div>
        </div>
    </form>
    <form action="#" id="filter-more">
        <div class="row">
            <div class="col-xs-9">
                <div class="row">
                    <div class="col-xs-3">
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
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label>Сектор залу</label>
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
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-users"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="block">Хто створив</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Усі
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Клієнти
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Касири
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
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
                            <div class="col-xs-3">&nbsp;</div>
                            <div class="col-xs-4">
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
                    <div class="pull-left filter-icon"><i class="fa fa-truck"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="block">Спосіб доставки</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> Самовивіз
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Нова пошта з відділення
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="3"> Кур'єром по місту
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="4"> Електронний квиток
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label class="block">Статус доставки</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> Не відправлявся
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Надіслано клієнту
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="3"> Отримано клієнтом
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="4"> Повернено від клієнта
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-money"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-5">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="block">Спосіб оплати</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Усі
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Платіжна система
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="demo-1" value="1"> Готівкою в касі
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
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
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="block">Статус оплати</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> Не оплачено
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Оплачено
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="3"> Запрошення
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="0">Період оплати
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
                            <div class="col-xs-5">
                                <label class="block">Хто роздрукував</label>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
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
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="block">Формат квитка</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> A4
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Бланк
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
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
                <hr/>
                <div class="filter-block">
                    <div class="pull-left filter-icon"><i class="fa fa-gear"></i></div>
                    <div class="filter-cont">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="block">Статус квитка</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> Активний
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Скасований
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="form-group">
                                    <label class="block">Типи замовлень</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="1"> Замовлення
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" value="2"> Квоти
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-4">
                        <a href="#" class="text-sm m-b-xs pull-right text-muted">Скинути фільтри</a>
                        <button class="btn btn-success block m-t-sm btn-full"><i class="fa fa-search m-r-sm"></i> Знайти</button>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="pull-right">
                    <button class="btn btn-primary btn-xs m-b m-r-lg" type="button" data-toggle="modal" data-target="#filtr-profile">завантажити профіль</button>
                    <button class="btn btn-primary btn-xs m-b" id="filter-more-button" type="button">менше фільтрів <i class="fa fa-filter"></i></button>
                </div>
                <div class="clearfix"></div>
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
                            <a href="#" class="text-sm text-mutted">сформувати .xls</a>
                        </div>
                    </section>
                </section>
                <hr/>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-list-alt"></i></div>
                    <div class="group">
                        <label># Замовлення</label>
                        <input type="text" class="form-control input-sm" placeholder="# Замовлення">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-phone"></i></div>
                    <div class="group">
                        <label>Телефон власника</label>
                        <input type="text" class="form-control input-sm" placeholder="Телефон власника">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-envelope"></i></div>
                    <div class="group">
                        <label>E-mail власника</label>
                        <input type="text" class="form-control input-sm" placeholder="E-mail власника">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-user"></i></div>
                    <div class="group">
                        <label>Прізвище, ім'я власника</label>
                        <input type="text" class="form-control input-sm" placeholder="Прізвище, ім'я власника">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/np.png"></i></div>
                    <div class="group">
                        <label>ТТН</label>
                        <input type="text" class="form-control input-sm" placeholder="ТТН">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-tags"></i></div>
                    <div class="group">
                        <label>Теги:</label>
                        <input type="text" class="form-control input-sm" placeholder="Теги">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-barcode"></i></div>
                    <div class="group">
                        <label>Штрих-код квитка</label>
                        <input type="text" class="form-control input-sm" placeholder="Штрих-код квитка">
                    </div>
                </div>
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Країна</label>
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Область</label>
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
                <div class="form-group form-with-icon">
                    <div class="pull-left"><i class="fa fa-map-marker"></i></div>
                    <div class="group">
                        <label>Місто</label>
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
    </form>
    <hr/>
    <div class="page-order-my">
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        <ul class="nav nav-pills pull-right">
                            <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                        </ul>
                        <div class="row">
                            <div class="col-xs-1">
                                <label class="block"><input type="checkbox"> # 32567</label>
                            </div>
                            <div class="col-xs-2">
                                <div>12 500 грн</div>
                                <div>3 квитки</div>
                            </div>
                            <div class="col-xs-3">
                                <div><span class="text-muted">Доставка:</span> Новою поштою, Самовивіз з каси</div>
                                <div><span class="text-muted">Оплата:</span> Платіжною картою</div>
                            </div>
                            <div class="col-xs-4">
                                <div><strong>Богдан Муравський</strong></div>
                                <div><span class="text-muted">Коментар:</span> <span title="При наведенні відображається увесь коментар до замовлення. Це штука для зручності.">При наведенні відображається увесь ...</span></div>
                            </div>
                            <div class="col-xs-2">
                                <div>15.03.2015 15:34</div>
                            </div>
                        </div>
                    </header>
                    <section class="panel-body">
                        <article class="ticket-item">
                            <div class="pull-left"><input type="checkbox"></div>
                            <div class="m-l-lg">
                                <div class="pull-left">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                </div>
                                <div class="pull-right">
                                    <strong class="fs-16 m-r-lg">123123123123</strong>
                                    <a href="#" class="text-mutted text-sm m-r" data-toggle="modal" data-target="#ticket-history">Історія</a>
                                    <a href="#" class="text-mutted text-sm" data-toggle="modal" data-target="#ticket-detail">Детально</a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row m-t">
                                    <div class="col-sm-2">
                                        <div>Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong></div>
                                        <div>Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong></div>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong>1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Платіжна картка</strong>
                                        <strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong class="block">Електронний квиток</strong>
                                        <strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Бланк</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong>Теги:</strong>
                                        <div><span class="label bg-light m-r">службовцям</span><span class="label bg-light m-r">Для Облради</span></div>
                                    </div>
                                </div>
                            </div>
                        </article>
                        <article class="ticket-item">
                            <div class="pull-left"><input type="checkbox"></div>
                            <div class="m-l-lg">
                                <div class="pull-left">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                </div>
                                <div class="pull-right">
                                    <strong class="fs-16 m-r-lg">123123123123</strong>
                                    <a href="#" class="text-mutted text-sm m-r" data-toggle="modal" data-target="#ticket-history">Історія</a>
                                    <a href="#" class="text-mutted text-sm" data-toggle="modal" data-target="#ticket-detail">Детально</a>
                                </div>
                                <div class="clearfix"></div>
                                <div class="row m-t">
                                    <div class="col-sm-2">
                                        <div>Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong></div>
                                        <div>Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong></div>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong>1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Платіжна картка</strong>
                                        <strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong class="block">Електронний квиток</strong>
                                        <strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        <strong class="block">Бланк</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        <strong>Теги:</strong>
                                        <div><span class="label bg-light m-r">службовцям</span><span class="label bg-light m-r">Для Облради</span></div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </section>
                </section>
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
        <div class="cart-fixed">
            <div class="cart-hide bg-dark">
                <i class="fa fa-shopping-cart"></i>
                <div>к</div>
                <div>о</div>
                <div>ш</div>
                <div>и</div>
                <div>к</div>
            </div>
            <section class="panel cart-block bxs">
                <div class="panel-body">
                    <button type="button" class="close cart-hide-button">закрити <i class="fa fa-times"></i></button>
                    <p class="m-b-xs"><span>Квитків:</span>5</p>
                    <p class="m-b-xs"><span>На суму:</span>2 600 грн</p>
                    <p class="m-b"><span>Знижка:</span>100 грн</p>
                    <p class="lg"><span><strong>До оплати: </strong></span>2 500 грн</p>
                    <div class="pull-right">
                        <a href="#" class="text-sm m-t m-b-sm block">очистити кошик</a>
                    </div>
                    <button class="btn btn-success block m-t-lg m-b-sm btn-full"><i class="fa fa-print m-r-sm"></i> Надрукувати</button>
                    <div class="clearfix"></div>
                    <div class="pull-right">
                        <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#cart-edit-modal">Редагувати</button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<div class="modal fade" id="cart-edit-modal" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Редагування квитків</h4>
                <span class="text-mutted text-sm">Увага! Зміни буде внесено до усіх обраних квитків</span>
            </div>
            <div class="modal-body">
                <section class="panel">
                    <header class="panel-heading bg-light">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active"><a href="#tab-1" data-toggle="tab">Основна інформація</a></li>
                            <li><a href="#tab-2" data-toggle="tab">Обрані квитки</a></li>
                        </ul>
                    </header>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Спосіб доставки</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Статус доставки</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Спосіб оплати</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Статус оплати</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Статус друку</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Активність</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <p class="m-b-none"><label>Створив</label><span class="m-l text-muted">покупець</span></p>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <div class="clearfix"></div>
                                        <p class="m-b-none m-t-lg"><label>Платформа та гравець</label></p>
                                        <h6 class="m-t-xs m-b-lg">iOS додаток - kasa.in.ua</h6>
                                        <h6 class="m-t-xs m-b-lg">iOS додаток - kasa.in.ua</h6>
                                        <h6 class="m-t-xs m-b-lg">iOS додаток - kasa.in.ua</h6>
                                        <h6 class="m-t-xs m-b-lg">iOS додаток - kasa.in.ua</h6>
                                        <div class="clearfix"></div>
                                        <p class="m-b-none m-t-lg"><label>Отримав оплату</label></p>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <div class="clearfix"></div>
                                        <p class="m-b-none m-t-lg"><label>Надрукував</label></p>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <h6 class="m-t-xs m-b-lg">id234 - Ім'я Прізвище</h6>
                                        <div class="form-group">
                                            <label>Буде скасовано через _____ днів</label>
                                            <input type="text" class="form-control input-sm" placeholder="Буде скасовано через _____ днів">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Формат</label>
                                            <select class="to-select2">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label>Теги (різні теги вписувати через кому)</label>
                                            <input type="text" class="form-control input-sm" placeholder="Теги">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-2">
                                <section class="panel">
                                    <section class="panel-body">
                                        <div class="m-b">
                                            <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <strong class="m-r-lg">Сектор 1</strong>
                                                <span class="m-r-lg">Ряд 14</span>
                                                <span class="m-r-lg">Місце 8</span>
                                                <div class="pull-right">
                                                    123123123123
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="row">
                                                    <div class="form-group col-xs-4">
                                                        <label>Вартість</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Вартість">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Знижка</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Знижка">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Ціна</label>
                                                        <span class="block m-t-xs">540 грн</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Прізвище відвідувача">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Ім'я відвідувача">
                                            </div>
                                        </div>
                                    </section>
                                </section>
                                <section class="panel">
                                    <section class="panel-body">
                                        <div class="m-b">
                                            <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <strong class="m-r-lg">Сектор 1</strong>
                                                <span class="m-r-lg">Ряд 14</span>
                                                <span class="m-r-lg">Місце 8</span>
                                                <div class="pull-right">
                                                    123123123123
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="row">
                                                    <div class="form-group col-xs-4">
                                                        <label>Вартість</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Вартість">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Знижка</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Знижка">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Ціна</label>
                                                        <span class="block m-t-xs">540 грн</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Прізвище відвідувача">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Ім'я відвідувача">
                                            </div>
                                        </div>
                                    </section>
                                </section>
                                <section class="panel">
                                    <section class="panel-body">
                                        <div class="m-b">
                                            <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <strong class="m-r-lg">Сектор 1</strong>
                                                <span class="m-r-lg">Ряд 14</span>
                                                <span class="m-r-lg">Місце 8</span>
                                                <div class="pull-right">
                                                    123123123123
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="row">
                                                    <div class="form-group col-xs-4">
                                                        <label>Вартість</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Вартість">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Знижка</label>
                                                        <input type="text" class="form-control input-sm" placeholder="Знижка">
                                                    </div>
                                                    <div class="form-group col-xs-4">
                                                        <label>Ціна</label>
                                                        <span class="block m-t-xs">540 грн</span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Прізвище відвідувача">
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <input type="text" class="form-control input-sm" placeholder="Ім'я відвідувача">
                                            </div>
                                        </div>
                                    </section>
                                </section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left">Скасувати</button>
                <button type="button" class="btn btn-success pull-right">Зберегти</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ticket-detail" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Подія:</div>
                    <div class="col-xs-9">Океан Ельзи "20 років разом"/Львів / 21.12.12 /19:00</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Сектор:</div>
                    <div class="col-xs-9">Фан-зона</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Ряд:</div>
                    <div class="col-xs-9">0</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Місце:</div>
                    <div class="col-xs-9">0</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Ціна:</div>
                    <div class="col-xs-9">300 грн.</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Активність:</div>
                    <div class="col-xs-9">Активний</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Формат:</div>
                    <div class="col-xs-9">Бланк</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Прізвище власника:</div>
                    <div class="col-xs-9">Петров</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Ім'я власника:</div>
                    <div class="col-xs-9">Іван</div>
                </div>
                <div class="clearfix m-t"></div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Дата створення:</div>
                    <div class="col-xs-9">12.02.2014 18:26</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Створив:</div>
                    <div class="col-xs-9">Ім'я Прізвище творця</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Гравець, що створив:</div>
                    <div class="col-xs-9">Карабас</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Платформа створення:</div>
                    <div class="col-xs-9">iOS додаток</div>
                </div>
                <div class="clearfix m-t"></div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Гравець автора друку:</div>
                    <div class="col-xs-9">Карабас/Західний відділ</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Автор друку:</div>
                    <div class="col-xs-9">id12333 - Петро Петрів</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Статус друку:</div>
                    <div class="col-xs-9">Надруковано</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Дата друку:</div>
                    <div class="col-xs-9">12.02.2014 18:26</div>
                </div>
                <div class="clearfix m-t"></div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Спосіб оплати:</div>
                    <div class="col-xs-9">Платіжна картка</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Статус оплати:</div>
                    <div class="col-xs-9">Оплачено</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Дата оплати:</div>
                    <div class="col-xs-9">12.02.2015 18:26</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Прийняв оплату:</div>
                    <div class="col-xs-9">платіжна система або Ім'я корисстувача</div>
                </div>
                <div class="clearfix m-t"></div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Спосіб доставки:</div>
                    <div class="col-xs-9">Нова пошта</div>
                </div>
                <div class="row">
                    <div class="col-xs-3 fb text-right">Статус доствки:</div>
                    <div class="col-xs-9">отримано клієнтом</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ticket-history" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="clearfix"></div>
                <div class="fs-16">
                    <p>Назва події / МІсто / Дата / Час</p>
                    <p>Сектор 4 | Ряд 3 | Місце 33</p>
                    <p><strong>123123123123</strong></p>
                </div>
                <hr/>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата/Час</th>
                        <th>Користувач</th>
                        <th>Назва поля</th>
                        <th>Назва поля</th>
                        <th><b>Нове значення</b></th>
                        <th>Назва поля</th>
                        <th>Назва поля</th>
                        <th>Назва поля</th>
                        <th>Назва поля</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">5</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td><b>Нове значення</b></td>
                    </tr>
                    <tr>
                        <th scope="row">4</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td><b>Нове значення</b></td>
                        <td>Значення</td>
                        <td><b>Нове значення</b></td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td><b>Нове значення</b></td>
                        <td>Значення</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td><b>Нове значення</b></td>
                    </tr>
                    <tr>
                        <th scope="row">1</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td><b>Нове значення</b></td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                    </tr>
                    <tr>
                        <th scope="row">0</th>
                        <td>30.07.2015 17:29</td>
                        <td>id 1234 Ім'я Прізвище</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                        <td>Значення</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="filtr-profile" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Збережені налаштування фільтру
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-8">
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
                    <div class="col-xs-4">
                        <a href="#" class="btn btn-success btn-sm">Застосувати налаштування</a>
                    </div>
                    <div class="col-xs-6 m-t">
                        <a href="#" class="text-sm text-mutted">Зберегти поточне налаштування фільтру</a>
                    </div>
                    <div class="col-xs-6 m-t">
                        <a href="#" class="text-sm text-mutted">Видалити обраний фільтр</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>