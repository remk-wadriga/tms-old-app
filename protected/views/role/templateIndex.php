<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 10.04.15
 * Time: 16:57
 * @var $this RoleController
 * @var $dataProvider CActiveDataProvider
 */
$this->menu=array(
    array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Гравці', 'url'=>array('role/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
    array('label'=>'Шаблони ролей'),
    array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex')),
    array('label'=>'Створити шаблон', 'url'=>array('role/templateCreate'))
);

?>

<h1>Шаблони ролей</h1>

<?php

$this->widget('booster.widgets.TbGridView', array(
    'id'=>'role-grid',
    'type'=>'bordered',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
        'id',
        'name',
        'sys_name',
        'description',
        array(
            'class'=>'booster.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
            'afterDelete'=>'function(link,success,data) {
				var obj = JSON.parse(data)
				if (obj.status == "error") {
					alert(obj.message);
					return;
				}
			}',
            'buttons'=>array(
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("role/templateUpdate", array("id"=>$data->id))'
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("role/templateDelete", array("id"=>$data->id))',
                    'options'=>array(
                        'style'=>'margin-left:10px;'
                    )
                )
            )
        ),
    )
));
