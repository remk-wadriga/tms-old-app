<?php
/**
 * @var $this ApiController
 * @var $model Event
 */
$hasMacro = $model->scheme->hasMacro;
$funzones = Sector::getFunSectors($model->scheme_id, true);
echo CHtml::hiddenField("event_id", $model->id);
echo CHtml::hiddenField("token", $this->_platform->partner_id);
?>

<div id="editor_cont" class="editor_cont preview" data-hasmacro="<?=$hasMacro?>" <?= !empty($funzones)? "data-funzones=".json_encode($funzones) : "";?>>
        <div id="svg_overflow" style="height:500px;width:100%">
                <div id="svg_cont"  style="width: 0; height: 0;">

                </div>
        </div>
</div>