<div class="row main">
    <div class="col-xs-12">
        <?php
        echo CHtml::hiddenField("scheme_id", $model->id);
        ?>
        <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
            "class"=>"editor_cont preview",
            "hasMacro"=>$model->hasMacro,
            "funZones"=>Sector::getFunSectors($model->id, true)
        ));?>
    </div>
</div>