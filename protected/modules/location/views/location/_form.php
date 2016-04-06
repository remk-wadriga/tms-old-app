<?php
/**
 * @var $form TbActiveForm
 * @var $model Location
 * Created by PhpStorm.
 * User: elvis
 * Date: 26.09.14
 * Time: 5:35
 */

?>
<?php
    $form = $this->beginWidget("application.components.CustomActiveForm", array(
        "id"=>"new-location-form",
        "enableAjaxValidation"=>true,
        "clientOptions"=>array(
            "validateOnChange"=>true,
            "validateOnSubmit"=>true
        )
    ));
?>
<div class="row">
    <div class="col-lg-6">
        <?php
            echo $form->textFieldGroup($model, "name", array('widgetOptions' => array(
                'htmlOptions' => array('placeholder' => 'Введіть назву локації')
            )));
        ?>
        <hr/>
        <?php
            echo $form->textFieldGroup($model, "short_name", array('widgetOptions' => array(
                'htmlOptions' => array('placeholder' => 'Введіть коротку назву локації')
            )));
            echo $form->textFieldGroup($model, "sys_name", array('widgetOptions' => array(
                'htmlOptions' => array('placeholder' => 'Введіть системну назву локації')
            )));
        ?>
        <hr/>
        <?php
            echo $form->dropDownListGroup($model, "location_category_id", array(
                "widgetOptions"=>array(
                    "data"=>array_merge(array(0=>'Виберіть зі списку тип залу'),LocationCategory::getList()),
                    "htmlOptions"=>array(
                        "options"=>array(
                            0=>array(
                                'disabled'=>'disabled',
                                'selected'=>'selected',
                            )
                        )
                    )
                )
            ));
        ?>
        <hr/>
        <?php
            echo $form->checkboxGroup($model, "status", array(
                "widgetOptions"=>array(
                    "htmlOptions"=>array(
                        "checked"=>$model->isNewRecord ? Location::STATUS_ACTIVE : $model->status
                    )
                )
            ));
        ?>
        <hr/>
        <div class="form-group">
            <?php
                if($model->isNewRecord) {
                    $number = 0;
                } else {
                    $number = $model->city_id;
                }
                echo CHtml::label('Країна <span class="required">*</span>',false);
                $this->widget('booster.widgets.TbSelect2', array(
                    'data'=>$countries,
                    'name'=>'country_id',
                    'htmlOptions'=>array(
                        'placeholder' => 'Виберіть країну',
                        'class' => 'form-control',
                        'onchange' => 'js: $(\'#Location_city_id\').select2("enable");'
                    )
                ));
            ?>
        </div>
        <div class="form-group cities">
            <?php
                echo $form->select2Group($model, 'city_id', array(
                    'widgetOptions' => array(
                        'asDropDownList' => false,
                        'htmlOptions' => array(
                            'disabled' => 'disabled',
                            'multiple' => false,
                            'placeholder' => 'Виберіть зі списку населений пункт',
                        ),
                        "options"=>array(
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
                                'results'=> 'js:function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true'
                            ),
                            'minimumInputLength'=> '2',
                        )
                    ),
                ));
            ?>
        </div>
        <?php
            echo $form->textFieldGroup($model, "address");
            echo $form->textFieldGroup($model, "short_address");
            echo $form->hiddenField($model, "lng");
            echo $form->hiddenField($model, "lat");
        ?>
        <hr/>
        <div id = "googleMap" style="width:100%;height:380px;"></div>
        <hr/>
        <div class="alert alert-danger danger-sector-alert" style="display: none" role="alert">Помилка</div>
        <div class="alert alert-success success-sector-alert" style="display: none" role="alert">Успішно збережено</div>
        <div class="form-group">
            <?php
                $this->widget("booster.widgets.TbButton", array(
                    "context"=>"success",
                    "label"=>$model->isNewRecord ? "Створити" : "Зберегти",
                    "buttonType"=>"submit",
                    "htmlOptions"=>array(
                        "class"=>"mr20",
                    )
                ));
                $this->widget("booster.widgets.TbButton", array(
                    "label"=>"Відміна",
                    "htmlOptions"=>array(
                        "onclick"=>"js:history.go(-1);",
                    )
                ));
            ?>
            <div style="float: right">
            <?php
            if (!$model->isNewRecord) {
                $this->widget("booster.widgets.TbButton",array(
                    "buttonType"=>"link",
                    "label"=>"Видалити",
                    'htmlOptions' => array(
                        'data-id' => $model->id,
                        'style' => 'float: left; padding: 3px 12px; margin-bottom: 5px;',
                        'id' => 'deleteLocationButton'
                    ),
                ));
            }

            ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <?php
            if (!$model->isNewRecord)
                $this->widget("application.widgets.commentWidget.CommentWidget", array(
                    "model_id"=>$model->id
                ));
        ?>
    </div>
</div>
<?php $this->endWidget(); ?>

