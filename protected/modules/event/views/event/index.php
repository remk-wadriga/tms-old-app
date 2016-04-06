<?php
/* @var $this EventController
 * @var $model Event
 * @var $form TbActiveForm
 *
 * */
?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Список подій</h4>
            <div class="pull-right">
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    'context' => 'primary',
                    'url' => Yii::app()->createUrl('/event/event/create'),
                    'label' => ' Додати подію',
                    'icon' => 'plus',
                    'buttonType' => 'link',
                    'context' => 'success',
                    'htmlOptions' => array(
                        'class' => 'mt7'
                    )
                ));
                ?>
            </div>
        </div>
    </div>
</header>
<div class="wrapper">
    <?php $form = $this->beginWidget('booster.widgets.TbActiveForm', array('id' => 'filter-form')); ?>
    <div class="row">
        <div class="col-xs-3">
            <?= $form->DropDownList($model, 'city_id', $cities, array(
                'allowClear'=>true,
                'class'=>'to-select2',
            ))
            ?>
        </div>
        <div class="col-xs-6">
            <?= $form->dropDownList($model, 'id', array("Всі події")+$events['data'], array(
                'allowClear' => true,
                'class' => 'to-select2-ext select2-new-event',
                'options' => $events['options']
            )) ?>
        </div>
        <div class="col-xs-3">
            <div class="mt3">
                <?= $form->checkBoxList($model, 'status', Event::$status, array(
                    'template' => '{beginLabel}{input}{labelTitle}{endLabel}',
                    'separator'=>'',
                    'labelOptions' => array(
                        'class' => 'checkbox-inline'
                    )
                ))
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php $this->endWidget();?>
    <hr/>

    <div class="row">
        <div class="col-xs-6">
            <section class="panel">
                <div class="panel-body clearfix panel-list">
                    <?php
                    $this->widget('zii.widgets.CListView', array(
                        'dataProvider'=>$model->search(),
                        'itemView'=>'_event',
                        'id'=>'eventList',
                        'summaryText'=>'<span class="text-muted pull-left">Знайдено: {count}</span><span class="text-muted pull-right">Показано: {start} - {end}</span><div class="clearfix"></div>',
                        'itemsCssClass'=>'list-group m-b list-group-lg list-group-sp',
                        'pagerCssClass'=>'pagination-wrap',
                        'pager'=>array(
                            'header'=>'',
                            'prevPageLabel'=>'<i class="fa fa-angle-left"></i>',
                            'nextPageLabel'=>'<i class="fa fa-angle-right"></i>',
                            'firstPageLabel'=>'<i class="fa fa-angle-double-left"></i>',
                            'lastPageLabel'=>'<i class="fa fa-angle-double-right"></i>',
                            'htmlOptions'=>array(
                                'class'=>'pagination pagination-sm'
                            )
                        )
                    ));
                    ?>
                </div>
            </section>
        </div>
        <div class="col-xs-6" >
            <div class="event-preview" id="event-preview"></div>
        </div>
    </div>
</div>