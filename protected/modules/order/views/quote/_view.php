<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 01.03.2016
 * Time: 12:20
 */
?>
<div class="wrapper">
    <div class="row">
        <div class="col-xs-6">
            <div class="m-b">
                <?php
                echo CHtml::dropDownList('quote',$model->id,Quote::getQuoteList(),[
                    "class" => "to-select2 form-control quote-select",
                    "block-select"=>"quote-one"
                ]);

                ?>
            </div>
            <div class="quote-one">
                <?php
                if(isset($model))
                    $this->renderPartial('_quoteInfo',["model"=>$model,"data"=>$data]);
                ?>
            </div>

        </div>
        <div class="col-xs-6 ">
            <div class="m-b">
                <?php
                echo CHtml::dropDownList('quote','',[''=>'Виберіть квоту']+Quote::getQuoteList(),[
                    "class" => "to-select2 form-control quote-select",
                    "block-select"=>"quote-two"
                ]);
                ?>
            </div>
            <div class="quote-two">

            </div>

        </div>
    </div>
</div>
