<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 25.05.15
 * Time: 17:16
 * @var $model Role
 */
if (isset($model)) :
?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4><?php echo $model->name;?></h4>
</div>

<div class="modal-body">

    <div class="col-lg-12">
        <h4>
				<span class="sysName">
					<?php echo $model->short_name;?>
				</span>
        </h4>
    </div>
    <div class="col-lg-12 description">
        <?php echo $model->description;?>
    </div>
    <div class="col-lg-12 dateCreate">
        <h4>
            Дата створення гравця в системі:
        </h4>
        <span class="date_add">
            <?php echo Yii::app()->dateFormatter->format("HH:mm dd-MM-yyyy", $model->date_add);?>
        </span>
    </div>
    <div class="col-lg-12">
        <h4>
            Поточні ролі гравця:
        </h4>
			<span class="roles">
                <?php
                foreach ($model->roleTemplates as $template)
                    echo $template->name."<br/>";
                ?>
			</span>
    </div>
    <div class="col-lg-12">
        <h4>
            Ролі, які гравець виконував в системі:
        </h4>
			<span class="old_roles">
				---
			</span>
    </div>
    <div class="col-lg-12">
        <h4>
            Кількість об"єктів, в яких був задіяний гравець:
        </h4>
			<span class="objects">
				---
			</span>
    </div>


    <div class="clearfix"></div>

</div>

<div class="modal-footer">
    <?php $this->widget(
        'booster.widgets.TbButton',
        array(
            'label' => 'Close',
            'url' => '#',
            'htmlOptions' => array('data-dismiss' => 'modal'),
        )
    ); ?>
</div>

<?php endif;?>