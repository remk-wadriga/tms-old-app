<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 18.12.14
 * Time: 17:19
 * @var $model Event
 */

?>
<section class="panel">
    <div class="panel-body clearfix">
        <div class="row">
            <div class="col-xs-4">

                <?= CHtml::link("Паспорт події", array("/event/event/edit", "event_id"=>$model->id), array('class'=>'btn btn-block btn-success btn-sm'.($model->status==Event::STATUS_DELETED?' disabled':''),'target'=>'_blank')) ?>
                <?= CHtml::link("Попередній перегляд", array("/event/event/preview", "id"=>$model->id), array('class'=>'btn btn-block btn-success btn-sm'.($model->status==Event::STATUS_DELETED?' disabled':''),'target'=>'_blank')) ?>
                <?= CHtml::link("Конструктор цін", array("/event/constructor/index", "event_id"=>$model->id), array('class'=>'btn btn-block btn-success btn-sm')) ?>
            </div>
            <div class="col-xs-4">
                <?= CHtml::link("Список квот", array("/order/quote/allQuotes", "event_id"=>$model->id), array('class'=>'btn btn-block btn-success btn-sm')) ?>
                <a href="#" class="btn btn-block btn-success btn-sm disabled">Менеджер замовлень</a>
                <a href="#" class="btn btn-block btn-success btn-sm disabled">Контроль продажу</a>
            </div>
            <div class="col-xs-4">
                <?= CHtml::link("Статистика", array("/statistics/statistics/basic", "event_id"=>$model->id), array('class'=>'btn btn-block btn-success btn-sm')) ?>
                <?= CHtml::link("Схема залу", array("/location/scheme/index", "scheme_id"=>$model->scheme_id), array('class'=>'btn btn-block btn-success btn-sm')) ?>
            </div>
        </div>
    </div>
</section>