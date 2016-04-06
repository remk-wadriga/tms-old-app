<?php
/**
 * @var $data Sector
 * @var $model Event
 * @var $countAll
 */

$count = $data->placesCount;
if ($data->type==Place::TYPE_FUN)
    $count = Place::getFunCount($data->id, $model->id);
?>
<div class="row item">
    <div class="col-md-5 price-text  sector-block">
        <?php echo CHtml::link($data->sectorName, "#", array(
            "data-sector_id"=>$data->id
        ));?>
    </div>
    <div class="col-md-7 mt5">
        <?php echo $count?>
        <span>
            <?php echo $countAll ? round(($count/($countAll))*100, 2) : "0"?>%
        </span>

    </div>
</div>