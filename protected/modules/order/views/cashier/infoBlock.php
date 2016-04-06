<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 24.07.15
 * Time: 20:59
 * @var $model Event
 */
 if (isset($model)) :?>
    <div class="row">
        <div class="col-sm-2">
            <img src="<?php echo $model->getPoster("m")?>" alt=""/>
        </div>
        <div class="col-sm-6">
            <h3><?= $model->name?></h3>
            <p>Дата проведення: <strong><?= $model->getStartTime()?></strong></p>
            <p>Місто <strong><?= $model->scheme->location->city->name?></strong></p>
            <p>Місце проведення: <strong><?= $model->scheme->location->name?></strong> <a href="http://maps.google.com/maps?&z=15&q=<?= $model->scheme->location->lat?>+<?= $model->scheme->location->lng?>&ll=<?= $model->scheme->location->lat?>+<?= $model->scheme->location->lng?>" class="pull-right small" target="_blank">Показати на карті</a></p>
            <p>Адреса: <strong><?= $model->scheme->location->address ?></strong></p>
            <p>Початок входу: <strong><?= $model->getEnterTime()?></strong></p>
        </div>
        <div class="col-sm-4">
            <h4>Зв'язані події</h4>
            <!--<p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
            <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
            <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
            <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>
            <p><a href="#">Океан Ельзи "20 років разом". Додтковий концерт</a></p>-->
        </div>
        <div class="col-sm-12">
            <h4 class="m-t-lg">Опис</h4>
            <?= $model->description_id?>
        </div>
    </div>
<?php endif;