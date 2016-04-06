<?php
/**
 *
 * @var SiteController $this
 */
?>
<div class="page-menu">
    <div class="sub-menu font-bold m-b">
        <a href="http://localhost/tms/site/test3" class="anim m-r"><i class="fa fa-print fa-2x v-middle"></i> Друк замовлень</a>
        <a href="http://localhost/tms/site/test2" class="anim m-r"><i class="fa fa-star fa-2x v-middle"></i> Список подій</a>
        <a href="http://localhost/tms/site/test1" class="anim m-r active"><i class="fa fa-barcode fa-2x v-middle"></i> Замовлення</a>
        <a href="#" class="anim m-r"><i class="fa fa-bar-chart-o fa-2x v-middle"></i> Статистика</a>
        <a href="#" class="anim m-r"><i class="fa fa-usd fa-2x v-middle"></i> Інкасації</a>
        <a href="#" class="anim"><i class="fa fa-briefcase fa-2x v-middle"></i> Контроль каси</a>
    </div>
    <div class="line line-dashed"></div>
</div>
<div class="page-order-my">
    <form action="#">
        <div class="row-5">
            <div class="col-sm-5">
                <div class="form-group">
                    <label class="block">Подія</label>
                    <select id="list-events">
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
            <div class="col-sm-3">
                <div class="form-group">
                    <label>
                        <input type="checkbox"> Період
                    </label>
                    <div class="row-5">
                        <div class="col-sm-6">
                            <input class="input-sm datepicker-input form-control" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy" >
                        </div>
                        <div class="col-sm-6">
                            <input class="input-sm datepicker-input form-control" size="16" type="text" value="12-02-2013" data-date-format="dd-mm-yyyy" >
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="block">Касир</label>
                    <select id="list-cashier">
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
            <div class="col-sm-2">
                <button class="btn btn-success block m-t-lg btn-full">Показати замовлення</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="m-r-lg"><input type="radio" name="sample" id="sample" value="option1" checked> Усі</label>
                    <label class="m-r-lg"><input type="radio" name="sample" id="sample" value="option1"> Роздруковані</label>
                    <label class="m-r-lg"><input type="radio" name="sample" id="sample" value="option1"> Оформлені касиром</label>
                    <label><input type="radio" name="sample" id="sample" value="option1"> Запрошення</label>
                </div>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading fs-16">
                    Знайдено замовлень <span class="badge bg-success">32</span>
                </header>
                <section class="panel-body">
                    <section class="panel">
                        <header class="panel-heading">
                            <ul class="nav nav-pills pull-right">
                                <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                            </ul>
                            <strong class="fs-16 m-r-lg"># 32567</strong> 3 квитки <strong class="m-l-lg">Петренко Петро</strong>
                            <label class="block m-t-sm"><input type="checkbox"> Вибрати усі квитки</label>
                        </header>
                        <section class="panel-body">
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <ul class="nav nav-pills pull-right">
                                <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                            </ul>
                            <strong class="fs-16 m-r-lg"># 32567</strong> 3 квитки <strong class="m-l-lg">Петренко Петро</strong>
                            <label class="block m-t-sm"><input type="checkbox"> Вибрати усі квитки</label>
                        </header>
                        <section class="panel-body">
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <ul class="nav nav-pills pull-right">
                                <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                            </ul>
                            <strong class="fs-16 m-r-lg"># 32567</strong> 3 квитки <strong class="m-l-lg">Петренко Петро</strong>
                            <label class="block m-t-sm"><input type="checkbox"> Вибрати усі квитки</label>
                        </header>
                        <section class="panel-body">
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <ul class="nav nav-pills pull-right">
                                <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                            </ul>
                            <strong class="fs-16 m-r-lg"># 32567</strong> 3 квитки <strong class="m-l-lg">Петренко Петро</strong>
                            <label class="block m-t-sm"><input type="checkbox"> Вибрати усі квитки</label>
                        </header>
                        <section class="panel-body">
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </section>
                    <section class="panel">
                        <header class="panel-heading">
                            <ul class="nav nav-pills pull-right">
                                <li><a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a></li>
                            </ul>
                            <strong class="fs-16 m-r-lg"># 32567</strong> 3 квитки <strong class="m-l-lg">Петренко Петро</strong>
                            <label class="block m-t-sm"><input type="checkbox"> Вибрати усі квитки</label>
                        </header>
                        <section class="panel-body">
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                            <div class="line line-sm"></div>
                            <article>
                                <div class="fs-16">
                                    <strong>Океан Ельзи "20 років разом"</strong> / Дніпропетровськ / <em class="text-sm">25.05.2015 19:00</em>
                                    <div class="pull-right text-sm">
                                        <a href="#">Детально</a>
                                    </div>
                                </div>
                                <div class="row text-sm">
                                    <div class="col-sm-3">
                                        <div class="pull-left m-r-lg"><input type="checkbox"></div>
                                        <div class="m-l-32">
                                            Сектор: <strong class="m-l-sm">Ліва ложа партеру</strong>
                                            <div class="line line-dashed line-sm"></div>
                                            Ряд: <strong class="m-r-lg">3</strong>Місце: <strong>3</strong>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        Ціна:<strong class="block">1 250 грн</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Оплата:<strong class="block">Оплачено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Статус друку:<strong class="block">Надруковано</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Передача квитка:<strong class="block">Не відправлено</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Активність:<strong class="block">Активний</strong>
                                    </div>
                                    <div class="col-sm-1">
                                        Тип:<strong class="block">Фізичний</strong>
                                    </div>
                                    <div class="col-sm-2">
                                        Коментар:<strong class="block">Потрібно передати власнику в руки...</strong>
                                    </div>
                                </div>
                            </article>
                        </section>
                    </section>
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
</div>
