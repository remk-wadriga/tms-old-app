<?php
/**
 *
 * @var CashierController $this
 * @var $form TbActiveForm
 * @var $model KasaControl
 */
?>
<div class="col-lg-12">
<h1>Завершення роботи</h1>
    <div class="form-wrapper" style="display:<?= $model->sum!=""&&(Yii::app()->user->hasFlash("close")||Yii::app()->user->hasFlash("notClose"))? "none" : "block"?>">
    <?php

    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
        "id"=>"close-form",
        "enableAjaxValidation"=>true,
        "clientOptions"=>array(
            "validateOnSubmit"=>true
        )
    ));
    ?>
        <div class="col-sm-12 text-center">
            <h3>
                Будь-ласка, вкажіть  суму коштів, що були отримані від реалізації квитків за
                <?= Yii::app()->dateFormatter->format("dd.MM.yyyy", time())?>
            </h3>

        </div>
        <div class="col-sm-offset-2 col-sm-1">
        <?php
    echo CHtml::label("Сума:", "cash");

    ?>
        </div>
        <div class="col-sm-6">
            <?php
                echo $form->textFieldGroup($model, "sum",array(
                    "label"=>false,
                    "htmlOptions"=>array(
                        "class"=>"form-control"
                    )
                ));
                echo CHtml::hiddenField("applyAnyway");
            ?>
        </div>
        <div class="col-sm-3">
        <?php
            $this->widget("booster.widgets.TbButton", array(
                "context"=>"success",
                "label"=>"Вказати",
                "buttonType"=>"submit"
            ));
        ?>
        </div>
        <?php
    $this->endWidget();
    ?>
    </div>
    <?php
    if (Yii::app()->user->hasFlash("close")) :
        ?>
        <div class="col-sm-12 text-center">
            <?php if (!$anyway) {
            ?>
            <h3>
                Сума співпала!
            </h3>
            <?php

            }?>

                <p>
                    За <?= Yii::app()->dateFormatter->format("dd.MM.yyyy", time())?> відзвітовано суму <?= number_format($model->sum, 2, ".", " ")?> грн
                </p>
                <p>
                    Приємного вечора!
                </p>





        </div>
    <?php
    endif;
    ?>
    <?php
    if (Yii::app()->user->hasFlash("notClose")) :
        $cash = Yii::app()->user->getFlash("notClose");
        $limit = $cash-$model->sum > 0 ? "Нестача" : "Надлишок"
        ?>
        <div class="errorSum">
            <div class="col-sm-12 text-center">
                <h3>
                    Сума не співпадає!
                </h3>
                <p>
                    Ви вказали суму <strong><?= number_format($model->sum, 2, ".", " ")?> грн</strong>
                </p>
                <p>
                    За данними системи Ви отримали від покупців кошти в розмірі <strong><?= number_format($cash, 2, ".", " ")?> грн</strong>.
                </p>
                <?=$limit?> коштів: <strong><?= $model->sum-$cash?> грн</strong>


                <hr/>
            </div>
            <div class="col-sm-2 col-sm-offset-4">
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    "context"=>"primary",
                    "label"=>"Вказати ще раз",
                    "htmlOptions"=>array(
                        "class"=>"repeatTry"
                    )
                ))
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo CHtml::htmlButton("Все одно підтвердити суму <b>".number_format($model->sum, 2, ".", " ")." грн</b>", array(
                    "class"=>"btn btn-success applySum"
                ));
                ?>
            </div>
        </div>
    <?php
    endif;
    ?>
</div>
