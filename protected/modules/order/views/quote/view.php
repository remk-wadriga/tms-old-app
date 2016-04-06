<?php
/**
 * @var Quote $model
 * @var QuoteController $this
 */

?>

<h3>
    Загальна інформація
</h3>
<div class="col-lg-12">
    <div class="col-lg-4">
        <dl class="dl-horizontal">
            <dt>Назва:</dt>
            <dd><?php echo $model->name;?></dd>
            <dt>Дата створення:</dt>
            <dd><?php echo Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm",$model->order->date_add);?></dd>
            <dt>Автор:</dt>
            <dd><?php echo $model->order->user->email;?></dd>
        </dl>
    </div>
    <div class="col-lg-4">
        <dl class="dl-horizontal">
            <dt>Постачальник:</dt>
            <dd><?php echo $model->roleFrom->name;?></dd>
        </dl>

        <?php echo $model->getLegalDetail("from");?>
    </div>
    <div class="col-lg-4">
        <dl class="dl-horizontal">
            <dt>Одержувач:</dt>
            <dd><?php echo $model->roleTo->name;?></dd>
        </dl>
        <?php echo $model->getLegalDetail("to");?>
    </div>
</div>

<div class="clearfix"></div>
<h3>Статистика</h3>
<div class="col-lg-6">
    <?php $this->widget('booster.widgets.TbGridView', array(
        "id"=>"statistic-grid",
        "template"=>"{items}",
        "dataProvider"=>$dataProvider,
        "columns"=>array(
            array(
                "value"=>'$data["name"]',
                "header"=>false,
            ),
            array(
                "value"=>'$data["count"]." шт."',
                "header"=>"Кількість",
            ),
            array(
                "value"=>'$data["sum"]." грн."',
                "header"=>"Сума",
            ),
        )
    ));
    ?>
</div>