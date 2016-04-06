<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 18.12.14
 * Time: 16:56
 * @var $data Event
 */
?>
<div class="list-group-item event-block" data-id="<?php echo $data->id?>">
    <div class="row">
        <div class="col-xs-2">
            <?php echo CHtml::image(($data->getPoster('m'))?$data->getPoster('m'):Yii::app()->getBaseUrl(true).DIRECTORY_SEPARATOR.'theme/images/event-default.jpg','',array('class' => 'img-full'));?>
        </div>
        <div class="col-xs-10">
            <div class="title"><h3 class="m-t-none"><?= $data->name ?></h3></div>
            <div class="location"><?= '<strong>'.$data->scheme->location->city->name.'</strong>, <span>'.$data->scheme->location->name.'</span>' ?></div>
            <div class="date"><?= '<span class="m-r">'.Yii::app()->dateFormatter->format("dd.MM.yyyy",$data->getStartTime()).'</span>'.Yii::app()->dateFormatter->format("HH:mm",$data->getStartTime()) ?></div>
            <div class="m-t">
                <span class="label bg-primary m-r"><?= $data->getStatusText() ?></span>
                <span class="label bg-primary"><?= $data->getSaleStatusText() ?></span>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
