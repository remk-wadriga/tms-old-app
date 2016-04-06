<?php
/* @var $this RoleController
 * @var $dataProvider CActiveDataProvider
 *
 */
?>
<?php $this->beginWidget(
	'booster.widgets.TbModal',
	array('id' => 'roleModal')
);
$this->renderPartial("_role_info")
?>



<?php $this->endWidget(); ?>

<?php
$this->menu=array(
	array('label'=>'Користувачі', 'url'=>array('user/index'), 'linkOptions'=>['style'=>'font-weight: bold;']),
	array('label'=>'Гравці', 'linkOptions'=>['style'=>'font-weight: bold;']),
	array('label'=>'Гравці', 'url'=>array('role/index')),
	array('label'=>'Створити гравця', 'url'=>array('role/create')),
//	array('label'=>'Шаблони ролей', 'url'=>array('role/templateIndex'), 'linkOptions'=>['style'=>'font-weight: bold;'])
);
?>
<div class="wrapper">
<h1>Гравці</h1>

<div class="col-lg-12">
	<?php
	echo CHtml::form("", "post", array(
		"id"=>"searchRoleForm"
	));
?>
	<div class="col-lg-4">
		<?php
		echo CHtml::textField("role_name", "", array(
			"class"=>"form-control",
		));
		?>
	</div>
	<div class="col-lg-4">
		<?php
		echo CHtml::dropDownList("template_role_name", "", TemplateRole::getTemplates() , array(
			"class"=>"form-control",
			"empty"=>"Фільтр по ролях"
		));
		?>
	</div>
	<div class="col-lg-4">
		<?php
		$this->widget("booster.widgets.TbButton", array(
			"context"=>"primary",
			"htmlOptions"=>array(
				"class"=>"glyphicon glyphicon-search"
			)
		))
		?>
	</div>



<?php
	echo CHtml::endForm();
	?>
</div>

<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'role-grid',
	'type'=>'bordered',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		'id',
		'name',
		'short_name',
		array(
			'name'=>'description',
			'sortable'=>false,
		),
		array(
			'class'=>'booster.widgets.TbButtonColumn',
			'template'=>'{info}<br/>{update}<br/>{delete}<br/>{access}',
			'afterDelete'=>'function(link,success,data) {
				var obj = JSON.parse(data)
				if (obj.status == "error") {
					alert(obj.message);
					return;
				}
			}',
			'buttons'=>array(
				'delete'=>array(
					'visible'=>'$data->name=="admin" ? false : true'
				),
				'update'=>array(
					'visible'=>'$data->name=="admin" ? false : true'
				),
				'info'=>array(
					'label'=>'Info',
					'url'=>'$data->id',
					'options'=>array(
						'data-toggle'=>'modal',
						'data-target'=>'#roleModal',
						'class'=>"showInfo"
					),
				),
				'access'=>array(
					'label'=>'Доступи',
					'url'=>'Yii::app()->createUrl("role/access", array("id"=>$data->id))',
                    'visible'=>'$data->name=="admin" ? false : true'
				)
			)
		),
	)
));
?>
</div>
