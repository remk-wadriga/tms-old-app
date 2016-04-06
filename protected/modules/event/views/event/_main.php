<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.06.2015
 * Time: 18:37
 */
?>

<div class="row form">
    <div class="col-lg-12">
        <h3>Загальна інформація</h3>
        <?php

        echo $form->hiddenField($model, 'user_id', array(
            'value'=>Yii::app()->user->id
        ));
        echo $form->hiddenField($model, 'role_id', array(
            'value'=>Role::getRoleId(Yii::app()->user->role)
        ));
        echo  $form->textFieldGroup($model,'name');
        echo  $form->textFieldGroup($model,'sys_name');

        echo $form->dropDownListGroup($model, 'group_id', array(
            'widgetOptions'=>array(
                'data'=>Group::getGroupList(),
                'htmlOptions'=>array(
                    'empty'=>'Подія не входить в жодну групу подій'
                )
            )
        ));
        ?>
        <hr/>
        <h3>Місце проведення <span style="font-size: 13px" class="required"><strong>*</strong></span></h3>
        <div class="row">
            <div class="col-md-6">
            <?php
                if(!$model->hasTickets()) {
                    ?>

                        <div class="form-group">
                            <?php
                            if ($model->isNewRecord) {
                                $number = 0;
                            } else {
                                $number = $model->scheme->location->city_id;
                            }
                            $this->widget('booster.widgets.TbSelect2', array(
                                'data' => $countries,
                                'name' => 'country_id',
                                'htmlOptions' => array(
                                    'placeholder' => 'Виберіть країну',
                                    'class' => 'form-control',
                                    'onchange' => 'js: $(\'#Event_city_id\').select2("enable");',
                                )
                            ));
                            ?>
                        </div>
                        <div class="form-group cities">
                            <?php
                            $data = array();
                            if ($model->isNewRecord)
                                $isEnabled = "disabled";
                            else {
                                $isEnabled = false;
                                $data = array(
                                    "id" => $model->scheme->location->city_id,
                                    "text" => $model->scheme->location->city->name
                                );
                                Yii::app()->clientScript->registerScript("initCity", '
                            $("#Event_city_id").select2({data:[' . json_encode($data) . ']}).val("' . $model->scheme->location->city_id . '").trigger("change")
                        ', CClientScript::POS_LOAD);
                            }
                            $location_id = !$model->isNewRecord ? $model->scheme->location_id : 0;

                            echo $form->select2Group($model, 'city_id', array(
                                'labelOptions' => array(
                                    'label' => false
                                ),
                                'widgetOptions' => array(
                                    'asDropDownList' => false,
                                    'htmlOptions' => array(
                                        'disabled' => $isEnabled,
                                        'multiple' => false,
                                        'placeholder' => 'Виберіть зі списку населений пункт',
                                        'onchange' => "js:$.ajax({
                                    url: '" . CController::createUrl('/event/event/getLocation') . "',
                                    data: {city_id: $(this).val()},
                                    complete: function(result){
                                        $('#Event_scheme_id').find('option').each(function(){
                                            if($(this).val() >= 0)
                                                $(this).remove();
                                            });
                                            $('#location_id').removeAttr('disabled');
                                            $('#location_id').select2('destroy')
                                                .html('<option value=\"\" selected=\"selected\">Виберіть локацію</option>'+result.responseText)
                                                .select2({placeholder: 'Виберіть локацію'});
                                            if($('#country_id').attr('is-old') == 1) {
                                                $('#location_id').select2('val', " . $location_id . ");
                                                $('#location_id').change();
                                            }
                                        }
                                    })"
                                    ),
                                    "options" => array(
                                        'ajax' => array(
                                            'dataType' => 'json',
                                            'url' => CController::createUrl('/location/location/getCitiesById'),
                                            'data' => 'js:function (params) {
                                    return {
                                        id: $("#country_id").val(), // search term
                                        text: params,
                                        all: true
                                    };
                                }',
                                            'results' => 'js:function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true'
                                        ),
                                        'minimumInputLength' => '2',
                                    ),

                                ),

                            ));
                            ?>
                        </div>
                        <?php
                        $scheme_id = !$model->isNewRecord ? $model->scheme_id : 0;
                        $this->widget('booster.widgets.TbSelect2', array(
                            'name' => 'location_id',
                            'htmlOptions' => array(
                                'placeholder' => 'Виберіть локацію',
                                'disabled' => $isEnabled,
                                'class' => 'form-control',
                                'ajax' => array(
                                    'type' => 'POST',
                                    'url' => Yii::app()->createUrl('/event/event/getScheme'),
                                    'data' => 'js:{location_id:$(this).val()}',
                                    'complete' => "js:function(result){
							$('#Event_scheme_id').removeAttr('disabled');
							$('#Event_scheme_id').select2('destroy')
                                    .html('<option value=\'\' selected=\'selected\'>Виберіть схему</option>'+result.responseText)
                                    .select2({placeholder: 'Виберіть схему'});
                            if($('#country_id').attr('is-old') == 1) {
                                $('#Event_scheme_id').select2('val', '" . $scheme_id . "');
                                $('#country_id').attr('is-old', '0');

                            }
							getCoords();
						}",
                                ),
                            )
                        ));
                        echo $form->select2Group($model, 'scheme_id', array(
                            'widgetOptions' => array(
                                'htmlOptions' => array(
                                    'disabled' => $isEnabled,
                                    'placeholder' => 'Виберіть схему',
                                ),
                            ),
                        ));

                        ?>
                    <?php
                } else {
                    echo CHtml::hiddenField('location_id');
                    echo CHtml::hiddenField('Event_city_id');
                    ?>
                    <div class="form-group">
                    <?= CHtml::label('Країна','country_name');?>
                    <?= CHtml::textField('country_name',$model->scheme->location->city->country->name,['class'=>'form-control','disabled'=>true]);?>
                    <?= CHtml::label('Місто','city_name');?>
                    <?= CHtml::textField('city_name',$model->scheme->location->city->name,['class'=>'form-control','disabled'=>true]);?>
                    <?= CHtml::label('Локація','location_name');?>
                    <?= CHtml::textField('location_name',$model->scheme->location->name,['class'=>'form-control','disabled'=>true]);?>
                    <?= CHtml::label('Схема','scheme_name');?>
                    <?= CHtml::textField('scheme_name',$model->scheme->name,['class'=>'form-control','disabled'=>true]);?>
                    </div>
                    <?= CHtml::label('Дані про місце проведення не можна змінити тому, що вже існують квитки на цю подію',null);?>
                <?php
                }
            ?>
            </div>
            <div class="col-md-6">
                <div id="map" style="height: 200px; width: 100%;"></div>
            </div>
        </div>
        <hr/>
        <h3>Таймінг</h3>
        <?php
        $i = 1;
        foreach ($model->timings as $time) {
            $class = $i == count($model->timings) ? "plus newTiming" : "minus removeTiming";
            ?>
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo $form->textFieldGroup($time, '['.$i.']start_sale', array(
                        "widgetOptions"=>array(
                            "htmlOptions"=>array(
                                'placeholder'=>'Початок',
                                'class'=>'dateTimePicker  start_event',
                                'data-attr'=>'start_event'
                            )
                        )
                    ));
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    echo $form->textFieldGroup($time,'['.$i.']stop_sale', array(
                        'widgetOptions'=>array(
                            'htmlOptions'=>array(
                                'placeholder'=>'Закінчення',
                                'class'=>'dateTimePicker end_event',
                                'data-attr'=>'end_event'
                            )
                        )
                    ))
                    ?>
                </div>
                <div class="col-md-4">
                    <?php
                    echo $form->textFieldGroup($time,'['.$i.']entrance', array(
                        'append' => '<a class="glyphicon glyphicon-'.$class.'" style="font-size:12px"></a>',
                        'widgetOptions'=>array(
                            'htmlOptions'=>array(
                                'placeholder'=>'Вхід з',
                                'class'=>'dateTimePicker',
                                'data-attr'=>'entrance'
                            )
                        )
                    ));
                    ?>
                </div>
            </div>
            <?php
            $i++;
        }
        ?>
        <hr/>
        <h3>Продаж</h3>
        <div class="row sales" >
            <div class="col-md-6">
                <?php

                echo $form->textFieldGroup($model, 'start_sale', array(
                    'prepend' => '<i class="glyphicon glyphicon-usd" style="font-size:12px"></i>',
                    'widgetOptions'=>array(
                        'htmlOptions'=>array(
                            'class'=>'dateTimePicker start_sale',
                        )
                    )
                ));

                $this->widget('booster.widgets.TbButton', array(
                    'context' => 'default',
                    'label' => 'Поточний час',
                    'htmlOptions' => array(
                        'class' => 'eventCurrent',
                        'style' => 'margin-left: 20px;'
                    )));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo $form->textFieldGroup($model, 'end_sale', array(
                    'widgetOptions'=>array(
                        'htmlOptions'=>array(
                            'class'=>'dateTimePicker end_sale',
                        )
                    )
                ));

                $this->widget('booster.widgets.TbButton', array(
                    'context' => 'default',
                    'label' => 'Поточний час',
                    'htmlOptions' => array(
                        'class' => 'eventCurrent',
                        'style' => 'margin-left: 20px;'
                )));

                ?>
            </div>
        </div>
        <hr/>
        <h3>Додаткові параметри події</h3>
        <div class="row">
            <?php
            $i = 1;
            if (is_object($model->custom_params)) {
                foreach ((array)$model->custom_params as $param) {
                    $class = $i == count((array)$model->custom_params) ? "plus addCustomParam" : "minus removeCustomParam";
                    ?>
                    <div class="col-md-5">
                        <?php
                        echo CHtml::label('Назва', 'custom_'.$i.'_name');
                        echo CHtml::textField('custom['.$i.'][name]',$param->name, array(
                            'class'=>'form-control',
                            'data-class'=>'custom'
                        ));
                        ?>
                    </div>
                    <div class="col-md-5">
                        <?php
                        echo CHtml::label('Значення', 'custom_'.$i.'_value');
                        echo CHtml::textField('custom['.$i.'][value]', $param->value,array(
                            'class'=>'form-control',
                            'data-class'=>'custom'

                        ))
                        ?>
                    </div>
                    <div class="col-sm-2">
                        <?php
                        echo CHtml::link("", "#", array(
                            "class"=>"glyphicon glyphicon-".$class,
                            "style"=>"top:33px !important"
                        ));
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php
                    $i++;
                }
            } else {
                ?>
                <div class="col-md-5">
                    <?php
                    echo CHtml::label('Назва', 'custom_'.$i.'_name');
                    echo CHtml::textField('custom['.$i.'][name]','', array(
                        'class'=>'form-control',
                        'data-class'=>'custom'
                    ));
                    ?>
                </div>
                <div class="col-md-5">
                    <?php
                    echo CHtml::label('Значення', 'custom_'.$i.'_value');
                    echo CHtml::textField('custom['.$i.'][value]', '',array(
                        'class'=>'form-control',
                        'data-class'=>'custom'
                    ))
                    ?>
                </div>
                <div class="col-md-2">
                    <?php
                    echo CHtml::link("", "#", array(
                        "class"=>"glyphicon glyphicon-plus addCustomParam",
                        "style"=>"top:33px !important"
                    ));
                    ?>
                </div>
                <div class="clearfix"></div>
                <?php
            }
            ?>
        </div>

        <hr/>
        <h3>Активність</h3>
        <?php
        echo $form->dropDownListGroup($model, "status", array(
            "label"=>false,
            "widgetOptions"=>array(
                "data"=>array(
                    $model::STATUS_NO_ACTIVE=>"неактивна",
                    $model::STATUS_ACTIVE=>"активна",
                ),
            ),
        ));
        ?>
        <hr/>
        <?php
        $this->widget("application.widgets.treeWidget.TreeWidget", array(
            "model"=>$model
        ));
        if (!$model->isNewRecord) :
            ?>
            <h3>Афіша</h3>
            <?php
            echo CustomRadioButtonList::radioButtonList("images", $preview != null ? $preview : "", $images, array(
                'template'=>'{beginLabel}{input}{label}{endLabel}',

            ));
            ?>
            <hr/>
            <?php
        endif;
        ?>
        <h3>Мультимедіа та інші файли</h3>
        <div class="row" style="margin-bottom: 20px;">
            <?php
            if (!$model->isNewRecord) {
                $this->renderPartial('_fileBlock', array(
                    'model' => $model,
                    'files' => $files
                ));
            }
            ?>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $this->widget('CMultiFileUpload', array(
                    'name'=>'multimedia',
                    'duplicate' => 'Цей файл вже вибраний',
                    'accept'=>'jpg|gif|png|jpeg',
                    'denied' => 'Невірний формат файлу, на даний момент доступні лише (.jpg .gif .png .jpeg)',
                    'max'=>10,
                    'htmlOptions'=>array(
                        'style'=>'margin-bottom:15px'
                    )
                ));
                ?>

            </div>

        </div>
        <?php
        echo $form->ckEditorGroup($model, 'description_id');
        ?>
        <hr/>
        <?php
        if (!$model->isNewRecord)
            echo CHtml::link("Попередній перегляд", "http://kasa.in.ua/event/hiddenEvent?id=".$model->id."&k=".base64_encode(time()), array(
                "target"=>"_blank"
            ))
        ?>
        <hr/>
        <?php

        echo $form->checkboxGroup($model, "slider_main");
        echo $form->checkboxGroup($model, "slider_city");
        ?>

        <div class="row">
            <div class="col-md-3">
                <?=$form->checkboxGroup($model, "isOnMain");?>
            </div>
            <div class="col-md-6 positionSpinner" style="top: 3px; display: none;">
                <?=$form->textField($model, "position", array(
                    'class'=> "aSpinEdit",
                    'disabled' => true,
                    'positionVal' => $model->position
                ))?>
            </div>

        </div>


        <?php

        $this->widget('booster.widgets.TbButton', array(
            'context'=>'primary',
            'label'=>$model->isNewRecord ? 'Створити' : 'Зберегти',
            'buttonType'=>'submit'
        ));
        if (!$model->isNewRecord) {
            $this->widget('booster.widgets.TbButton', array(
                'context' => 'danger',
                'label' => 'Видалити',
                'id' => 'eventDeleteButton',
                'htmlOptions' => array(
                    'data-id' => $model->id,
                    'style' => 'margin-left: 20px;'
                )));
        }

        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile("http://maps.googleapis.com/maps/api/js?v=3.exp");
        $cs->registerScript('map', "
			function createMap(lat,lng){
				var LatLng = new google.maps.LatLng(lat,lng);

				var mapProp = {
					center: LatLng,
					zoom:15,
					mapTypeId:google.maps.MapTypeId.ROADMAP
				};
				var map=new google.maps.Map(document.getElementById('map'),mapProp);

				//Create a marker here
				var marker=new google.maps.Marker({

					animation:google.maps.Animation.DROP,
					title: 'Marker'
				});

				//setting marker on map
				marker.setPosition(LatLng);
				marker.setMap(map);
			}
			function getCoords() {
			    if($('#location_id').val() != ''){
				$.post('".Yii::app()->createUrl('/event/event/getCoords')."', {
						location_id : $('#location_id').val()
					},function(result){
							var obj = JSON.parse(result);
							createMap(obj.lat, obj.lng);
						}
					);
                }
			}
			", CClientScript::POS_END);
        ?>
    </div>
</div>