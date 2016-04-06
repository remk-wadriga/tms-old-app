<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.07.15
 * Time: 13:35
 * @var $data Event
 */

?>

<div class="col-sm-1">
    <div class="item" data-id="<?=$data->id;?>">
        <button type="button" class="close from_favorites" data-id="<?=$data->id;?>" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= CHtml::image($data->getPoster("m"))?>
        <p class="m-t-sm"><strong><?=$data->name?>"</strong><br/><small><?=$data->scheme->location->city->name?><br/><?=$data->getStartTime()?></small></p>
    </div>
</div>
