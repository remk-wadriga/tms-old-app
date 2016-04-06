<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 02.04.15
 * Time: 12:38
 *
 * @var $sectors CActiveDataProvider
 * @var $model Event
 */
$funCount = Place::getCountFunWithPrice($model->id);
$countAll = $model->scheme->getCountPlaces()+$funCount;
//CVarDumper::dump($sectors->getData(),10,1);
//exit;
?>

    <div class="row item">
        <div class="col-md-5 price-text sector-block"><a href="#" data-sector_id="all">Усі</a></div>
        <div class="col-md-7 mt5">
            <?php echo $countAll?>
            <span>100%</span>
        </div>
    </div>
    <?php
    /*$this->widget('zii.widgets.CListView', array(
        'dataProvider'=>$sectors,
        'itemView'=>"_sectors",
        'template'=>'{items}',
        'viewData'=>array(
            "model"=>$model,
            "countAll"=>$countAll
        ),
        'htmlOptions'=>array(
            'class'=>"row item"
        )
    ))*/
    ?>
