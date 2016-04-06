<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 28.11.14
 * Time: 10:19
 * @var $model Scheme
 */
?>

<div class="col-lg-12">
    <div class="col-lg-6">
        <h2>
            Секторів: <?php echo count($model->sectors);?>
            Місць: <?php echo $model->countPlaces;?>
        </h2>

        <br/>
        <h3>Сектори з місцями</h3>
        <br/>
        <?php
            $this->widget('booster.widgets.TbExtendedGridView', array(
                'id'=>'place-grid',
                'type' => 'condensed',
                'dataProvider'=>$model->getProviderSeatPlaces(),
                'template'=>'{items}',
                'columns'=>array(
                    array(
                        'name'=>'name',
                        'header'=>'Назва',
                        'footer'=>'<b>Всього:</b>'
                    ),
                    array(
                        'name'=>'places',
                        'header'=>'Кількість',
                        'class'=>'booster.widgets.TbTotalSumColumn'
                    )
                )
            ));
        ?>
        <br/>
    </div>
    <?php $this->widget('application.widgets.commentWidget.CommentWidget', array(
        "model_id"=>$model->id
    ));?>
</div>