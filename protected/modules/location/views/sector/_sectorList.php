<div id="<?php echo $data->id;?>" class="sector_item <?php echo $data->type == Sector::TYPE_FUN ? "fun_zone" : ""?>">
    <span class="visibility"></span>
    <?php echo CHtml::link($data->sectorName, "#");?>
    <div style="display: inline-block;float: right;">
        <input type="checkbox" name="vis_front" class="front changeVisibility" title="Фронтенд" <?= (int)$data->frontend? "checked":""?>/>
        <input type="checkbox" name="vis_back" class="back changeVisibility"  title="Бекенд" <?= (int)$data->backend? "checked":""?>/>
    </div>
    <span class="active_control <?php echo $data->status==Sector::STATUS_ACTIVE ? "make_inactive" : "make_active"?>"></span>
</div>