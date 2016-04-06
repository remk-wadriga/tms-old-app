<?php /**
 * @var $this QuoteController
 * @var $contractor Role
 * @var int $num
 * @var int $count
 * @var int $sum
 * @var int $event_id
 * @var $quote Quote
 */
?>

<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading<?php echo $num?>" data-num="<?php echo $num?>" <?= isset($quote) ? "data-quote=\"$quote->id\"": "";?>>
        <?php

        if (!isset($count)&&!isset($sum)) {
            $count = Yii::app()->shoppingCart->getCount();
            $sum = Yii::app()->shoppingCart->getCost();
        }

        ?>
        <div class="collapseBlock-left">
            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $num?>" aria-expanded="false" aria-controls="collapse<?php echo $num?>">
                <span><?php echo $contractor->name?></span>
                <strong><?php echo isset($quote)&&$quote->name!="" ? " : ".$quote->name :""?></strong>
                <br>
                <strong><span class="count">
                    <?php echo $count;?>
            </span> шт.</strong> на суму:
                <strong><span class="sum">
                    <?php echo $sum;?>
                </span> грн.</strong>
            </a>
            <?php
            if (isset($quote)):
            ?>
                <br/>
                    <span>
                        <small>
                            <?php echo CHtml::link("Редагувати", array("/order/quote/update", "quote_id"=>$quote->id), array(
                                "class"=>"small"
                            ));?>
                        </small>
                    </span>
                &nbsp;
                    <span>
                        <small>
                            <?php echo CHtml::link("Переглянути", array("/order/quote/view", "quote_id"=>$quote->id), array(
                                "class"=>"small"
                            ));?>
                        </small>
                    </span>
                &nbsp;

                    <span>
                        <small>
                            <?php echo CHtml::link("Порівняти", array("/order/quote/compare"), array(
                                "class"=>"small"
                            ));?>
                        </small>
                    </span>


        </div>
        <div  class="collapseBlock-right">
            <?php
            echo CHtml::link("", "#", array(
                "class"=>"quote_control glyphicon glyphicon-eye-open",
                "data-quote_id"=>$quote->id
            ));
            endif;
            if (!isset($quote))
                echo CHtml::link("&times;", "#",array(
                    "class"=>"close",
                    "onclick"=>"window.quote_constructor.deleteFromCart(".$contractor->id.")",
                    "style"=>"
                    margin-top: -30px;
                    font-size: 24px;
                "
                ));
            ?>
        </div>
    </div>
    <div id="collapse<?php echo $num?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $num?>">
    <?php
        $this->renderPartial("_collapse_block", array(
            "contractor"=>$contractor,
            "event_id"=>$event_id,
            "sectors"=>$sectors
        ))
    ?>
    </div>
</div>



