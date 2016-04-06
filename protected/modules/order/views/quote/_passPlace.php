<?php
/**
 * @var $this QuoteController
 * @var $model Quote
 */


?>
<div class="col-lg-4">
    <?php
        echo CHtml::radioButtonList("typePass", Quote::PASS_TYPE_OLD, array(
//            Quote::PASS_TYPE_NEW=>"Створити нову квоту",
            Quote::PASS_TYPE_OLD=>"Додати до існуючої"
        ), array(
            "ajax"=>array(
                "url"=>$this->createUrl('getContractorsList'),
                "data"=>'js:{
                        typePass : $(this).val(),
                        quote_id : $("#quote_id").val()
                    }',
                "update"=>"#passPlaceContractors",
            ),
            "return"=>"true"
        ));
        echo CHtml::dropDownList("passPlaceContractors", "", $quotes_list, array(
            "class"=>"form-control",
            "empty"=>"Виберіть квоту",
        ));
    ?>
</div>
<div class="col-lg-4">
    <?php
    $this->widget("booster.widgets.TbButton", array(
        "context"=>"success",
        "label"=>"Передати місця",
        /*"buttonType"=>"ajaxButton",*/
        "url"=>$this->createUrl("passPlaces"),
/*        "ajaxOptions"=>array(
            "data"=>"js:{
                    quote_id: $('#quote_id').val(),
                    places : JSON.stringify([{'id':'row2col2sector42','sector_id':'42','price':50,'server_id':586,'type':1}]),
                    typePass: $('#typePass').find(':checked').val(),
                    passPlaceContractors: $('#passPlaceContractors').val()
                }",
            "success"=>"js:function(result,s){
                var obj = JSON.parse(result);
                if (obj.msg == 'redirect_url')
                    window.location.replace(obj.url);
            }"
        ),*/
        "htmlOptions"=>array(
            "data-url"=>$this->createUrl("passPlaces"),
            "id"=>"passPlaces"

        )
    ));
    ?>
</div>
