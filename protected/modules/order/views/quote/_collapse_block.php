<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 06.05.15
 * Time: 16:14
 * @var $this QuoteController
 * @var $contractor Role
 * @var int $num
 */?>

<div class="panel-body">
    <div class="block">
    <?php foreach ($sectors as $sector=>$rows) {
        $model = Sector::model()->findByPk($sector);

        $sectorSum = 0;
        $sectorPlaces = 0;
        $rowBlock = "";
        foreach ($rows as $row=>$places) {
            $i=0;
            $placeString = "";
            $rowSum = 0;
            if ($model->type == Sector::TYPE_FUN)
                $placeString = count($places);
            foreach ($places as $place) {
                $i++;
                if ($model->type == Sector::TYPE_SEAT) {
                    $placeString .= $place['place'];
                    $placeString .= count($places)==$i ? "" : ",";
                }
                $rowSum += $place['price'];
            }

            $sectorPlaces += count($places);
            $sectorSum += $rowSum;
            if ($model->type == Sector::TYPE_SEAT)
                $rowBlock .= "
                <div class=\"item\">
                    <div class=\"pull-left\">".(isset($model->typeRow) ? $model->typeRow->name : '').' '.($row!='' ? $row:"")."</div>
                    <div class=\"pull-right\">".count($places)." шт. ". $rowSum." грн</div>
                    <div class=\"clearfix\"></div>
                    <div class=\"places\">м.: ". $placeString."</div>
                </div>
                ";
            else
                $rowBlock .= "
                <div class=\"item\">

                </div>
                ";
            ?>
    <?php
        }
        ?>
        <div class="title">
            <div class="pull-left"><?php echo $model->sectorName;?></div><div class="pull-right"><?php echo $sectorPlaces;?> шт. <?php echo $sectorSum;?> грн.</div>
            <div class="clearfix"></div>
        </div>
        <?php

        echo $rowBlock;
    }
?>

    </div>
</div>

