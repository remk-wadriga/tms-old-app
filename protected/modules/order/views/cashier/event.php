<?php
/**
 *
 * @var CashierController $this
 */
?>
<?php $this->renderPartial("sub_menu", array("action"=>"listEvent")) ?>
<div class="wrapper">
    <div class="page-event-list">
        <?php
        $this->beginWidget("booster.widgets.TbActiveForm", array(
            "id"=>"event-search",
            "method"=>"POST"
        ));

        echo CHtml::hiddenField("current_event_id", $event_id);
        ?>
        <div class="row-5">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="block">Подія</label>
                    <?= CHtml::dropDownList('event_id', $event_id, $events['data'], array(
                        'allowClear' => true,
                        'class' => 'to-select2-ext',
                        'options' => $events['options'],
                        'empty' => 'Виберіть подію'
                    )) ?>
                </div>
            </div>
            <div class="col-sm-4 mt23">
                <?= CHtml::htmlButton('<i class="fa fa-star"></i>',array(
                    'class' => 'btn btn-img to_favorites',
                    'type' => 'submit'
                ))?>
            </div>
            <div class="col-sm-2 m-t-lg">
                <a href="#" class="pull-right small block b0" id="event-fav-btn">Показати / приховати обрані події</a>
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
        <?php $this->renderPartial('favoritesBlock', array('dataProvider'=>$favorites));?>
        <div class="row event_info">
            <?php
            if ($model)
                $this->renderPartial('_event_info', array("places"=>$places, "model"=>$model))
            ?>
        </div>
    </div>
</div>
<?php $this->renderPartial("_createOrder", array("model"=>$order)) ?>