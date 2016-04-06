<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 23.09.14
 * Time: 11:06
 * @var $model City
 * @var $form TbActiveForm
 * @var $this ConfigurationController
 */
?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b">Міста</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <?php
    $this->renderPartial("_newCountry", array("model"=>$modelCountry));
    $this->renderPartial("_newCity", array("model"=>$model, "countries"=>$countries));
    $this->renderPartial("_delCountry", array("model"=>$modelCountry, "countries"=>$countries));
    ?>


    <div class="row">
        <div class="col-md-12">
            <?php
                $this->widget('booster.widgets.TbAlert', array(
                    'fade' => true,
                    'closeText' => '&times;',
                    'userComponentId' => 'user',
                    'alerts' => array(
                        'success' => array('closeText' => '&times;'),
                        'danger' => array('closeText' => '&times;'),
                    ),
                ));
            ?>
        </div>
    </div>
    <div class="row country-block">
        <div class="col-md-1">
            <label class="form-label" for="country_id">Країна</label>
        </div>
        <div class="col-md-3">
                <?php
                    if (!$countryId) $countryId = 0;
                    $this->widget("booster.widgets.TbSelect2", array(
                        'name' => 'country_id',
                        'data' => $countries,
                        'htmlOptions' => array(
                            'class'=>'form-control',
                            'ajax' => array(
                                'type'=>'POST',
                                'url'=>CController::createUrl('/configuration/configuration/getCityList'),
                                'data'=>'js:{country_id:$(this).val()}',
                                'success'=>'js:function(result){
                                    var obj = JSON.parse(result);
                                    $(".cityList").html(obj);
                                    $("#tree").treed();
                                }',
                            ),
                            'placeholder' => 'Виберіть країну',
                        ),
                        'value'=> $countryId,
                    ));
                ?>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?php
                    $this->widget("booster.widgets.TbButton",array(
                        "context"=>"info",
                        "label"=>"Редагувати країну",
                        'htmlOptions' => array(
                            'class'=> 'update-country hidden',
                            'data-toggle' => 'modal',
                            'data-target' => '#newCountry',
                        ),
                    ));
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php
                    $this->widget("booster.widgets.TbButton",array(
                        "context"=>"success",
                        "label"=>"Нова країна",
                        'htmlOptions' => array(
                            'class'=> 'new-country pull-right',
                            'data-toggle' => 'modal',
                            'data-target' => '#newCountry',
                        ),
                    ));
                ?>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-md-12">
            <div class="cityList"></div>
        </div>

    </div>
</div>
