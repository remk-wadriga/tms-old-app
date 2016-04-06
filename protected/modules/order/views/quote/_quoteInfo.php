<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.03.2016
 * Time: 12:23
 */
?>
    <div class="group">
        <label>Назва</label>
        <div class="value">
            <kbd># <?=$model->id?></kbd> <?=$model->name?> <i class="fa fa-info-circle switch" tabindex="0" role="button" data-html="true" data-toggle="popover" data-placement="bottom" data-trigger="focus" data-content="<label>Створив</label><div class='st'><kbd># <?=$model->roleFrom->id?></kbd> <?=$model->roleFrom->name?> <span class='digital m-l'><?=date("d.m.Y - H:i",strtotime($model->order->date_add))?></span></div><label>Редагував востаннє</label><div class='st'><kbd># 21</kbd> Ім'я Прізвище <span class='digital m-l'><?=date("d.m.Y - H:i",strtotime($model->order->date_update))?></span></div><label>Передав</label><div class='st'><kbd># <?=$model->roleFrom->id?></kbd> <?=$model->roleFrom->name?> <span class='digital m-l'><?=date("d.m.Y - H:i",strtotime($model->order->date_add))?></span></div>"></i>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="group">
                <label>Статус</label>
                <div class="value">
                    <?php
                        if($model->type != Quote::TYPE_NONE)
                            echo "Передана";
                        else
                            echo "Не передана";
                    ?>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="group">
                <label>Тип</label>
                <div class="value">
                    <?php
                    if($model->order->status == Order::STATUS_CLOSE)
                        echo "Закрита";
                    else
                        echo Quote::$namesTypes[$model->type];
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="group">
                <label>Постачальник</label>
                <div class="value">
                    <?=$model->roleFrom->name?>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="group">
                <label>Отримувач</label>
                <div class="value">
                    <?=$model->roleTo->name?>
                </div>
            </div>
        </div>
    </div>
    <div class="group">
        <label>Розмір квоти</label>
        <div class="value">
            <?=number_format($model->order->total, 0, ",", " ")?> грн.<!--<span class="digital m-l">123 456 шт.</span>-->
        </div>
    </div>
    <div class="group">
        <label>Очікувана винагорода</label>
        <div class="value">
            <?php
            $percent = $model->order->total / 100 * $model->percent;
            $finalMoney = $model->order->total - $percent;
            ?>
            <?=number_format($percent, 0, ",", " ")?> грн.<span class="digital m-l"><?=$model->percent?>%</span>
        </div>
    </div>
    <div class="group">
        <label>Фактична винагорода (відсоток з проданих)</label>
        <div class="value">
            <?=number_format($finalMoney, 0, ",", " ")?> грн.
        </div>
    </div>
    <div class="cleafix"></div>
    <?php
        if($model->order->status == Order::STATUS_CLOSE) {
            //download file here later
            ?>

            <?php
        }elseif($model->type == Quote::TYPE_NONE) {
            ?>
            <button class="btn btn-success btn-xs m-b btn-close-quote" data-id="<?=$model->id?>">Закрити</button>
            <button class="btn btn-success btn-xs m-b"><a href="<?=Yii::app()->createUrl("/order/quote/update", array("quote_id"=>$model->id))?>" style="color: white">Вилучити місця</a></button>
            <button class="btn btn-success btn-xs m-b btn-modal-edit" data-id="<?=$model->id?>">Передати квоту</button>
            <?php
        }elseif($model->type == Quote::TYPE_PHYSICAL) {
            ?>
            <button class="btn btn-success btn-xs m-b btn-close-quote" data-id="<?=$model->id?>">Закрити</button>
            <button class="btn btn-success btn-xs m-b btn-print-quote" data-id="<?=$model->id?>">Надрукувати квитки</button>
            <button class="btn btn-success btn-xs m-b btn-do-type-none" data-id="<?=$model->id?>">Зробити непереданою</button>
            <?php
        }else{
            ?>
            <button class="btn btn-success btn-xs m-b btn-close-quote" data-id="<?=$model->id?>">Закрити</button>
            <button class="btn btn-success btn-xs m-b btn-do-type-none" data-id="<?=$model->id?>">Зробити непереданою</button>
            <?php
        }
    ?>
    <div class="cleafix"></div>

    <section class="panel">
        <header class="panel-heading">
            <?php
                echo CHtml::checkBoxList('filterByStatus',[1,0],[
                    1 => "Продані",
                    0 => "Не продані",
                    2 => "Повернуті",
                ],[
                    'class'=>'checkbox-filter',
                    'type-data'=>$model->id,
                    'separator' => '',
                    'style' => 'margin-left:5px;',
                ])
            ?>
            <button class="btn btn-success btn-xs m-b pull-right"><a href="<?=Yii::app()->createUrl("/order/order/getInvoice", array("id"=>$model->id))?>" style="color: white">Завантажити накладну</a></button>
        </header>
        <table class="table m-b-none text-sm">
            <thead>
            <tr>
                <th>Сектор</th>
                <th>Ряд</th>
                <th>Місце з</th>
                <th>Місце по</th>
                <th>Ціна</th>
                <th>Кількість</th>
            </tr>
            </thead>
            <tbody class="data-rows-<?=$model->id?>">
                <?php
                $this->renderPartial("_dataRows",["data"=>$data,"model"=>$model]);
                ?>
            </tbody>
            <tfoot>
            <div class="data-sum-rows-<?=$model->id?>">
                <?php
                $this->renderPartial("_dataSumRows",["data"=>$data,"model"=>$model]);
                ?>
            </div>
            </tfoot>
        </table>
    </section>