<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 23.03.15
 * Time: 17:44
 *
 * @var $model Event
 */

$funCount = Place::getCountFunWithPrice($model->id);
$countAll = $model->scheme->getCountPlaces()+$funCount;
$sumAll = $model->sumPrice;
$countWithPrice = $model->countPlacePrice;
$countNullPrice = Place::getNullCount($model->id);

?>


<div class="row item">
    <div class="col-md-5 price-text price-block">
        <i style="color: <?= Place::COLOR_NOPRICE ?>;" class="fa fa-circle"></i><a href="#">Не задіяно</a></div>
    <div class="col-md-7 mt5">
        <?php echo $countAll ? $countAll-$countWithPrice-$countNullPrice : "0"?>
        <span>
            <?php echo $countAll ? round((($countAll-$countWithPrice-$countNullPrice)/$countAll)*100 ,2) : "";?>%
        </span>
    </div>
</div>
<div class="row item">
    <div class="col-md-5 price-text price-block"><i class="fa fa-circle"></i>
        <?php echo CHtml::link("0", "#", array(
                "data-type"=>0,
                "data-price"=>0
            ));
        ?>
    </div>
    <div class="col-md-7 mt5">
        <?php echo $countNullPrice;?>
        <span>
            <?php echo $countAll ? round(($countNullPrice/$countAll)*100 ,2) : "";?>%
        </span>
    </div>
</div>
<hr/>
<div class="row title">
    <div class="col-md-6"><h3>Місця з ціною</h3></div>
    <div class="col-md-6">
        <p><?php echo $model->getCountPlacePrice(Place::TYPE_SEAT)?> місць</p>
        <p><?php echo $model->getSumPrice(Place::TYPE_SEAT)?> грн</p>
    </div>
</div>
<?php
foreach ($model->getPlacesWithPrice(Place::TYPE_SEAT) as $key=>$price) {
    ?>
    <div class="row item">
        <div class="col-md-3 price-text price-block">
            <i class="fa fa-circle" style="color:<?php echo $price['fill']?>"></i>
            <?php echo CHtml::link($key, "#", array(
                "data-type"=>$price['type'],
                "data-price"=>$key
            ));?>
        </div>
        <div class="col-md-4 mt5"><?php echo $price['count']?>
            <span>
                <?php echo $countAll ?  round(($price['count']/$countAll)*100, 2) : ""?>%
            </span>
        </div>
        <div class="col-md-5 mt5"><?php echo $price['count']*$key?> грн
            <span>
                <?php echo $sumAll ? round((($price['count']*$key)/$sumAll)*100, 2) : "";?>%
            </span>
        </div>
    </div>
<?php
}
?>

<hr/>
<div class="row title">
    <div class="col-md-6"><h3>Фан-зони з ціною</h3></div>
    <div class="col-md-6">
        <p><?php echo $model->getCountPlacePrice(Place::TYPE_FUN)?> місць</p>
        <p><?php echo $model->getSumPrice(Place::TYPE_FUN)?> грн</p>
    </div>
</div>
<?php
foreach ($model->getPlacesWithPrice(Place::TYPE_FUN) as $key=>$price) {
    ?>
    <div class="row item">
        <div class="col-md-3 price-text price-block">
            <i class="fa fa-circle" style="color:<?php echo $price['fill']?>"></i>

            <?php
            echo CHtml::link($key, "#", array(
                "data-type"=>$price['type'],
                "data-price"=>$key
            ));?>
        </div>
        <div class="col-md-4 mt5"><?php echo $price['count']?>
            <span>
                <?php echo $countAll ? round(($price['count']/$countAll)*100, 2) : ""?>%
            </span>
        </div>
        <div class="col-md-5 mt5"><?php echo $price['count']*$key?> грн
            <span>
                <?php echo $sumAll ? round((($price['count']*$key)/$sumAll)*100, 2) : "";?>%
            </span>
        </div>
    </div>
<?php
}
?>
