<?php
/**
 *
 * @var QuoteController $this
 */

?>
<div class="col-lg-12 form-group">
    <?php $this->widget("booster.widgets.TbSelect2", array(
        "name"=>"event_id",
        "data"=>$events,
        "options"=>array(
            "placeholder"=>"Виберіть подію",
            "width"=>"100%",


        ),
        "htmlOptions"=>array(
            "ajax"=>array(
                "url"=>$this->createUrl('compare'),
                "data"=>"js:{event_id:$(this).val()}",
                "success"=>"js: function(result,success){
                    $('#left_side').html(result);
                    $('#right_side').html(result);
                }"
            )
        )

));
?>
</div>
<div class="form-group filterTables" data-url="<?php echo $this->createUrl('filterTable');?>">
    <div class="clearfix"></div>
    <div class="col-lg-6 left" data-side="left">


        <?php
        echo CHtml::dropDownList("left_side", "", array(), array(
            "empty"=>"Спочатку виберіть подію",
            "class"=>"form-control",
            "onchange"=>"js:filterTable($(this).parent().data('side'));"
        ));

        echo CHtml::radioButtonList("left_filter", Quote::FILTER_ALL, Quote::$filterTypes, array(
            "separator"=>" ",
        ));
        ?>
    <div class="left_table">

    </div>


    </div>
    <div class="col-lg-6 right" data-side="right">

        <?php echo CHtml::dropDownList("right_side", "", array(), array(
            "empty"=>"Спочатку виберіть подію",
            "class"=>"form-control",
            "onchange"=>"js:filterTable($(this).parent().data('side'));"
        ));
        echo CHtml::radioButtonList("right_filter", Quote::FILTER_ALL, Quote::$filterTypes, array(
            "separator"=>" "
        ));?>
        <div class="right_table">

        </div>
    </div>

</div>
