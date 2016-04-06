<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 28.09.15
 * Time: 12:27
 */
?>
<div class="widget-event">
    <?php
    $this->widget('zii.widgets.CMenu', array(
        'htmlOptions' => array(
            'class' => 'tms-menu bg-light',
        ),
        'activeCssClass' => 'active',
        'items'=>array(
            array('label'=>'Паспорт події', 'url'=>array('/event/event/edit', 'event_id'=>$event)),
            array('label'=>'Конструктор цін', 'url'=>array('/event/constructor/index', 'event_id'=>$event)),
            array('label'=>'Квоти', 'url'=>array('/order/quote/allQuotes', 'event_id'=>$event)),
            array('label'=>'Контроль продажу', 'url'=>array('/order/control/index', 'event_id'=>$event)),
            array('label'=>'Статистика', 'url'=>array('/statistics/statistics/basic', 'event_id'=>$event)),
        ),
    ));
    ?>
    <div class="widget-event-select-block bg-light">
        <div class="row">
            <div class="col-xs-10">
                <?= CHtml::dropDownList('widget-event-select', $event, $events['data'], array(
                    'allowClear' => true,
                    'class' => 'to-select2-ext select2-event',
                    'options' => $events['options']
                ))
                ?>
            </div>
            <div class="col-xs-2">
                <div class="pull-right mt7">
                    <?php
                    echo CHtml::checkBoxList('widget-event-status', '',
                        array(
                            Event::STATUS_ACTIVE => " Активні",
                            Event::STATUS_NO_ACTIVE => " Не активні"
                        ),
                        array(
                            'template' => '{beginLabel}{input}{labelTitle}{endLabel}',
                            'separator' => '',
                            'labelOptions' => array(
                                'class' => 'checkbox-inline'
                            )
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
