<?php
/**
 * @var $model Location
 * @var $this LocationController
 */
?>

<h1>Перегляд локації</h1>
<div class="row">
    <div class="col-lg-12 text-right">
        <?php
        $this->widget("booster.widgets.TbButton", array(
            "context"=>"primary",
            "url"=>Yii::app()->createUrl("/location/location/update", array("id"=>$model->id)),
            "label"=>"Редагувати локацію",
            "buttonType"=>"link",
        ));
        ?>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <p class="text-value <?php echo $model->status==Location::STATUS_ACTIVE ? "text-success" : "text-warning" ?>"><?php echo $model->getStatus() ?></p>
        </div>
        <hr/>
        <div class="form-group">
            <p class="text-label">Назва локації</p>
            <p class="text-value"><?php echo $model->name;?></p>
        </div>
        <div class="form-group">
            <p class="text-label">Коротка назва</p>
            <p class="text-value"><?php echo $model->short_name;?></p>
        </div>
        <div class="form-group">
            <p class="text-label">Системна назва</p>
            <p class="text-value"><?php echo $model->sys_name;?></p>
        </div>
        <hr/>
        <div class="form-group">
            <p class="text-label">Тип локації</p>
            <p class="text-value"><?php echo $model->locationCategory->name;?></p>
        </div>
        <hr/>
        <div class="form-group">
            <p class="text-label">Населений пункт</p>
            <p class="text-value"><?php echo $model->city->name ?></p>
        </div>
        <div class="form-group">
            <p class="text-label">Адреса</p>
            <p class="text-value"><?php echo $model->address;?></p>
        </div>
        <div class="form-group">
            <p class="text-label">Скорочена адреса</p>
            <p class="text-value"><?php echo $model->short_address;?></p>
        </div>
        <div class="form-group">
            <p class="text-label">Карта</p>
            <div id="googleMap" class="map-value" style="width:100%;height:380px;"></div>
        </div>
    </div>
    <div class="col-lg-6">
        <?php
        $this->widget("application.widgets.commentWidget.CommentWidget", array(
            "model_id"=>$model->id
        ));
        ?>
    </div>
</div>
