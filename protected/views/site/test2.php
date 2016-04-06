<?php
/**
 *
 * @var SiteController $this
 */
?>
<div class="page-menu">
    <div class="sub-menu font-bold m-b">
        <a href="http://localhost/tms/site/test3" class="anim m-r"><i class="fa fa-print fa-2x v-middle"></i> Друк замовлень</a>
        <a href="http://localhost/tms/site/test2" class="anim m-r active"><i class="fa fa-star fa-2x v-middle"></i> Список подій</a>
        <a href="http://localhost/tms/site/test1" class="anim m-r"><i class="fa fa-barcode fa-2x v-middle"></i> Замовлення</a>
        <a href="#" class="anim m-r"><i class="fa fa-bar-chart-o fa-2x v-middle"></i> Статистика</a>
        <a href="#" class="anim m-r"><i class="fa fa-usd fa-2x v-middle"></i> Інкасації</a>
        <a href="#" class="anim"><i class="fa fa-briefcase fa-2x v-middle"></i> Контроль каси</a>
    </div>
    <div class="line line-dashed"></div>
</div>
<div class="page-event-list">
    <form action="#">
        <div class="row-5">
            <div class="col-sm-6">
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
            <div class="col-sm-2">
                <button class="btn btn-sm btn-success block m-t-23 btn-full">Переглянути</button>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-sm btn-success block m-t-23 btn-full">Додати до обраних</button>
            </div>
            <div class="col-sm-2 m-t-lg">
                <a href="#" class="pull-right small block b0" id="event-fav-btn">Показати / приховати обрані події</a>
            </div>
        </div>
    </form>

    <div class="event-slider" id="event-fav">
        <section class="panel">
            <section class="panel-body">
                <label>Обрані події</label>
                <div class="row-5 col-7">
                    <div class="col-sm-1">
                        <div class="item">
                            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event.jpg" alt=""/>
                            <p class="m-t-sm"><strong>Океан Ельзи "20 років разом"</strong><br/><small>Дніпропетровськ<br/>21.05.2015. 19:00</small></p>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item">
                            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event.jpg" alt=""/>
                            <p class="m-t-sm"><strong>Океан Ельзи "20 років разом"</strong><br/><small>Дніпропетровськ<br/>21.05.2015. 19:00</small></p>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item">
                            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event.jpg" alt=""/>
                            <p class="m-t-sm"><strong>Океан Ельзи "20 років разом"</strong><br/><small>Дніпропетровськ<br/>21.05.2015. 19:00</small></p>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item">
                            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event.jpg" alt=""/>
                            <p class="m-t-sm"><strong>Океан Ельзи "20 років разом"</strong><br/><small>Дніпропетровськ<br/>21.05.2015. 19:00</small></p>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item">
                            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event.jpg" alt=""/>
                            <p class="m-t-sm"><strong>Океан Ельзи "20 років разом"</strong><br/><small>Дніпропетровськ<br/>21.05.2015. 19:00</small></p>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item no">
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event-no.jpg" alt=""/>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="item no">
                            <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event-no.jpg" alt=""/>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <header>
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Схема залу</a></li>
                    <li class=""><a href="#tab2" data-toggle="tab">Інформація про подію</a></li>
                </ul>
            </header>
            <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                    <div class="wrapper bg-white-only">
                         <div class="row">
                             <div class="col-sm-9">
                                 <div style="width: 100%; background: #545e70; height: 500px; text-align: center; color: #f0ffff; font-size: 56px; padding-top: 150px;">СХЕМА ЗАЛУ</div>
                             </div>
                             <div class="col-sm-3">
                                 <div class="cart">
                                     <h4>Кошик <a href="#" class="pull-right fs-10">Очистити</a></h4>
                                     <div class="title bg-light m-b m-t">
                                         <p><strong>Океан Ельзи "20 років разом"</strong></p>
                                         <p>Дніпропетровськ, 21.06.2015р</p>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <div class="pull-right">
                                                 <input type="number" name="quantity" min="1" max="5"> шт.
                                             </div>
                                             <strong>Фан-зона: </strong>
                                             <div class="clearfix"></div>
                                         </div>
                                     </div>
                                     <div class="title bg-light m-b m-t">
                                         <p><strong>Океан Ельзи "20 років разом"</strong></p>
                                         <p>Дніпропетровськ, 21.06.2015р</p>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <strong>Сектор: </strong>Ліва ложа партеру<br/>
                                             <strong>Ряд: </strong>5 <strong>Місце: </strong>5<br/>
                                         </div>
                                     </div>
                                     <div class="item">
                                         <a href="#"><i class="fa fa-times"></i></a>
                                         <div class="cont">
                                             <div class="pull-right">
                                                 <input type="number" name="quantity" min="1" max="5"> шт.
                                             </div>
                                             <strong>Фан-зона: </strong>
                                             <div class="clearfix"></div>
                                         </div>
                                     </div>
                                     <hr/>
                                     <div class="summary">
                                         <p><span>Кількість:</span> <strong>50 шт.</strong></p>
                                         <p><span>Сума:</span> <strong>250 500 грн</strong></p>
                                     </div>
                                     <button class="btn btn-success btn-full">Надрукувати квитки</button>
                                     <div class="checkbox">
                                         <label><input type="checkbox"> Надрукувати як запрошення</label>
                                     </div>
                                     <div class="row">
                                         <div class="col-xs-6 pull-right">
                                             <button class="btn btn-sm btn-success btn-full" data-toggle="modal" data-target="#order-modal">Забронювати</button>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab2">
                    <div class="wrapper bg-white-only">
                        <div class="event-info">
                            <div class="row">
                                <div class="col-sm-2">
                                    <img src="<?php echo Yii::app()->baseUrl; ?>/theme/images/event-1.jpg" alt=""/>
                                </div>
                                <div class="col-sm-6">
                                    <h3>Океан Ельзи "20 років разом". Додтковий концерт</h3>
                                    <p>Дата проведення: <strong>21.05.2015 19:00</strong></p>
                                    <p>Місто <strong>Дніпропетровськ</strong></p>
                                    <p>Місце проведення: <strong>Оперний театр</strong> <a href="#" class="pull-right small">Показати на карті</a></p>
                                    <p>Початок входу: <strong>21.05.2015 17:00</strong></p>
                                </div>
                                <div class="col-sm-4">
                                    <h4>Зв'язані події</h4>
                                    <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
                                    <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
                                    <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
                                    <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
                                    <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
                                </div>
                                <div class="col-sm-12">
                                    <h4 class="m-t-lg">Опис</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
                                    <h4 class="m-t-lg">Особливості події</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis tellus mollis orci, sed rhoncus sapien nunc eget odio.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="order-modal" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Оформлення замовлення</h4>
            </div>
            <div class="modal-body">
                <h5><strong>Спосіб доставки та оплати</strong></h5>
                <ul class="row list-style">
                    <li class="col-xs-4">
                        <div class="checkbox"><label><input type="radio" name="type-name"> Самовивіз з каси</label></div>
                        <ul class="list-style sub">
                            <li><div class="checkbox"><label><input type="radio" name="type-1-name"> Готівка</label></div></li>
                            <li><div class="checkbox"><label><input type="radio" name="type-1-name"> Платіжна картка</label></div></li>
                        </ul>
                    </li>
                    <li class="col-xs-4">
                        <div class="checkbox"><label><input type="radio" name="type-name"> Доставка НП</label></div>
                        <ul class="list-style sub">
                            <li><div class="checkbox"><label><input type="radio" name="type-2-name"> Готівка</label></div></li>
                            <li><div class="checkbox"><label><input type="radio" name="type-2-name"> Платіжна картка</label></div></li>
                        </ul>
                    </li>
                    <li class="col-xs-4">
                        <div class="checkbox"><label><input type="radio" name="type-name"> Доставка кур'єром</label></div>
                        <ul class="list-style sub">
                            <li><div class="checkbox"><label><input type="radio" name="type-2-name"> Готівка</label></div></li>
                            <li><div class="checkbox"><label><input type="radio" name="type-2-name"> Платіжна картка</label></div></li>
                        </ul>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-xs-6">
                        <h5><strong>Контактні дані</strong></h5>
                        <div class="form-group">
                            <label>Ім'я</label>
                            <input type="text" class="form-control input-sm" placeholder="Ім'я">
                        </div>
                        <div class="form-group">
                            <label>Прізвище</label>
                            <input type="text" class="form-control input-sm" placeholder="Прізвище">
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" class="form-control input-sm" placeholder="Телефон">
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control input-sm" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label>Теги</label>
                            <select class="to-select2-sm" multiple="multiple">
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
                        <div class="form-group">
                            <label class="block">Статус оплати</label>
                            <label class="radio-inline">
                                <input type="radio" name="radio-name" value="1"> оплачено
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="radio-name" value="1"> неоплачено
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="radio-name" value="1"> запрошення
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="block">Статус оплати</label>
                            <textarea class="form-control" name="any-name"  rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <h5><strong>Дані доставки</strong></h5>
                        <div class="form-group">
                            <label>Країна</label>
                            <select class="to-select2-sm">
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
                        <div class="form-group">
                            <label>Область</label>
                            <select class="to-select2-sm">
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
                        <div class="form-group">
                            <label>Місто</label>
                            <select class="to-select2-sm">
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
                        <div class="form-group">
                            <label>№ відділення</label>
                            <input type="text" class="form-control input-sm" placeholder="№ відділення">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success pull-left">Оформити замовлення</button>
                <button type="button" class="btn btn-success pull-right">Оформити замовлення і роздрукувати</button>
            </div>
        </div>
    </div>
</div>