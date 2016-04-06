<div class="block">
<?php
    if (isset($items)&&is_array($items)&&(!empty($items))) {
?>
        <?php
        foreach ($items as $sector_id => $item) { ?>
                <div class="head flex">
                    <div class="title"><?= $item['name'] ?></div>
                    <div class="descr"><?= $item['count'] ?> шт. <?= $item['sum'] ?> грн.</div>
                </div>
                <?php foreach ($item['rows'] as $row_id => $rows) {
                    foreach ($rows['prices'] as $price => $places) {
                        if ($item['type']==Place::TYPE_SEAT) { ?>
                            <div class="item flex">
                                <div class="price" title="грн"><?= $price ?></div>
                                <div class="place"><span>р: <?= $rows['name'] ?></span>м: <?= $places['places'] ?></div>
                                <div class="count"><?= $places['count'] ?> шт.</div>
                                <div class="delete danger" data-event-id="<?= $event_id ?>" data-sector-id="<?= $sector_id ?>" data-row-id="<?= $row_id ?>" data-price="<?= $price ?>"><i class="fa fa-times"></i></div>
                            </div>
                        <?php } else { ?>
                            <div class="item fun flex">
                                <div class="price"><?= $price ?></div>
                                <div class="place">
                                    <span class="sold" title="Продано"><?=$rows['sold_count']?></span>
                                    <span class="free" title="Вільно"><?=$rows['all_count']-$rows['sold_count']?></span>
                                    <span class="all" title="Всього"><?=$rows['all_count']?></span>
                                </div>
                                <div class="count">
                                    <?php
                                        $model_fake->count = $places['count'];
                                        $this->widget(
                                            'booster.widgets.TbEditableField',
                                            array(
                                                'mode' => 'inline',
                                                'type' => 'text',
                                                'model' => $model_fake,
                                                'attribute' => 'count',
                                                'inputclass' => 'input-number',
                                                'emptytext' => '1',
                                                'validate' => 'js: function(value, newValue) {
                                                    if ($.trim(value) == "")
                                                        return "Це поле є обов\'язковим";
                                                    if (parseInt($.trim(value)) < parseInt($(this).data("min")))
                                                        return {newValue: $(this).data("min")};
                                                    if (parseInt($.trim(value)) > parseInt($(this).data("max")))
                                                        return {newValue: $(this).data("max")};
                                                }',
                                                'onSave' => 'js: function(event, params) {
                                                    var value = parseInt(params.newValue);
                                                    if ($.isNumeric(value) && value>=1 && value<=parseInt($(this).attr("data-max"))) {
                                                        $("#cart-content").html("<div class=\"loading\"><i class=\"fa fa-spinner fa-spin\"></i></div>");
                                                        $.post("'.Yii::app()->controller->createUrl("/order/quote/placeFanToCart").'",
                                                            {
                                                                event_id: $(this).attr("data-event-id"),
                                                                sector_id: $(this).attr("data-sector-id"),
                                                                count: value
                                                            }, function(result) {

                                                                var obj = JSON.parse(result);
                                                                $("#cart-content").html(obj.html);
                                                                $("#quote_cart_sum").val(obj.sum);
                                                                if (parseInt(obj.count)!=0){
                                                                    $(".cart-widget .header .total .count").html(obj.count+" шт.");
                                                                    $(".cart-widget .header .total .sum").html(obj.sum+" грн.");
                                                                } else {
                                                                    $(".cart-widget .header .total .count").html("");
                                                                    $(".cart-widget .header .total .sum").html("");
                                                                }
                                                            }
                                                        );
                                                    }
                                                }',

                                                'htmlOptions' => array (
                                                    'class' => 'fanzone-input',
                                                    'data-event-id' => $event_id,
                                                    'data-sector-id' => $sector_id,
                                                    'data-min' => 1,
                                                    'data-max' => $rows['all_count']-$rows['sold_count']
                                                )
                                            )
                                        );
                                    ?>
                                </div>
                                <div class="delete danger" data-event-id="<?=$event_id?>" data-sector-id="<?=$sector_id?>" data-row-id="<?=$row_id?>" data-price="<?=$price?>"><i class="fa fa-times"></i></div>
                            </div>
                    <?php }
                    }
                } ?>
        <?php } ?>
<?php } else {
        echo '<div class="text">Кошик порожній. Виберіть місця на карті.</div>';
    }
?>
</div>
