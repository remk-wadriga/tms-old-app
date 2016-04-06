<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 08.04.15
 * Time: 15:31
 */

$this->widget('booster.widgets.TbGridView', array(
    'id'=>'role-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'name'
    )
));