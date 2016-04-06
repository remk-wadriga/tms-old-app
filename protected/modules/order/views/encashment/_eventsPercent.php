<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 23.11.2015
 * Time: 14:36
 */
?>

<section class="panel">
    <section class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Подія</th>
                <th>Прямий продаж, %</th>
                <th>Самовивіз з каси, %</th>
                <th>Друк оплачених, %</th>
            </tr>
            </thead>
            <tbody class="events-percent-data">
            <?php
            $i = 0;

            foreach ($data as $eventPercent) {
                    ?>
                    <tr>
                        <td><?=CHtml::link("", "#", array("class"=>"glyphicon glyphicon-remove deleteEventPercent",'style' => 'margin-right:20px',));?>
                            <?=CHtml::label($eventPercent["label"],null)?><?=CHtml::hiddenField("EventPercent[event_id][$i]",$eventPercent["event_id"],["class"=>"event_ids"])?></td>
                        <td><?=CHtml::textField("EventPercent[fullSale][$i]",$eventPercent["fullSale"],["class"=>"form-control input-sm m-b-none n-validate"])?></td>
                        <td><?=CHtml::textField("EventPercent[cashSale][$i]",$eventPercent["cashSale"],["class"=>"form-control input-sm m-b-none n-validate"])?></td>
                        <td><?=CHtml::textField("EventPercent[printSale][$i]",$eventPercent["printSale"],["class"=>"form-control input-sm m-b-none n-validate"])?></td>
                    </tr>
                    <?php
                    $i++;
                }
            ?>
            </tbody>
        </table>
    </section>
<!--    <section class="panel-footer">-->
<!--        <div class="block-pagination m-t-sm">-->
<!--            <div class="row">-->
<!--                <div class="col-md-3">-->
<!--                    <ul class="pagination pagination-sm m-n">-->
<!--                        <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>-->
<!--                        <li><a href="#">1</a></li>-->
<!--                        <li><a href="#">2</a></li>-->
<!--                        <li><a href="#">3</a></li>-->
<!--                        <li><a href="#">4</a></li>-->
<!--                        <li><a href="#">5</a></li>-->
<!--                        <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>-->
<!--                    </ul>-->
<!--                </div>-->
<!--                <div class="col-md-3">-->
<!--                    <div class="form-group perpage">-->
<!--                        <label>Показувати сторінок:</label>-->
<!--                        <select name="sample" class="form-control input-sm">-->
<!--                            <option>10</option>-->
<!--                            <option>20</option>-->
<!--                            <option>50</option>-->
<!--                            <option>100</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->
</section>
