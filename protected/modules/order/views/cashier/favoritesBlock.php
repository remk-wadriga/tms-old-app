<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.07.15
 * Time: 13:32
 */

?>

<div class="event-slider" id="event-fav">
    <section class="panel">
        <section class="panel-body">
            <label>Обрані події</label>
            <?php $this->widget('zii.widgets.CListView', array(
                'id' => 'favorite-event-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_favorite',
                'itemsCssClass'=>'row-5 col-7'
            ));?>
        </section>
    </section>
</div>
