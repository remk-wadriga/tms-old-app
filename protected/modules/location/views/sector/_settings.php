<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 14.11.14
 * Time: 19:05
 * @var $sector Sector
 * @var $scheme Scheme
 */

?>

<?php if ($scheme->hasSectors) : ?>
    <div class="row">
        <div class="col-xs-12">
            <?php
                echo CHtml::dropDownList('sector_list', $sector->id, $scheme->getSectorsList(), array(
                    'class' => 'form-control input-sm',
                    'onchange' => 'js:changeSector(this)',
                    'empty' =>'Виберіть сектор для редагування',
                    'options' => Sector::getFunSectors($scheme->id),
                ));
            ?>
        </div>
    </div>
<?php endif; ?>
<hr/>
<div class="row sector-description hidden">
    <div class="col-xs-12">
        <h5>Інформація про сектор:</h5>

        <div class='form-group'>
            <?php
            $this->widget("booster.widgets.TbSelect2", array(
                'name' => 'prefix',
                'data' => TypeSector::getTypes(),
                'htmlOptions' => array(
                    'data-url' => Yii::app()->createUrl('configuration/configuration/sector'),
                    'data-type' => 'TypeSector'
                ),
                'value' => !$sector->isNewRecord ? $sector->type_sector_id : ""
            ));
            ?>
        </div>
        <div class='form-group'>
            <?php
            echo CHtml::textField('name', !$sector->isNewRecord ? $sector->name : '', array(
                'class'=>'form-control required input-sm',
                'placeholder'=>'Назва',
                'id'=>'sector_name',
                'data-url'=>Yii::app()->createUrl('location/sector/validateSectorExists')
            ))
            ?>
        </div>
        <?php if ($sector->type == Sector::TYPE_SEAT) : ?>
            <div class='form-group'>
                <?php
                    $this->widget("booster.widgets.TbSelect2", array(
                        'name' => 'row_name',
                        'data' => TypeRow::getTypes(),
                        'htmlOptions'=>array(
                            'empty'=>'Виберіть ряд',
                            'class'=>'required',
                            'data-url'=>Yii::app()->createUrl('configuration/configuration/row'),
                            'data-type'=>'TypeRow'
                        ),
                        'value'=> !$sector->isNewRecord ? $sector->type_row_id : ""
                    ));
                ?>
            </div>
            <div class='form-group'>
                <?php
                    $this->widget("booster.widgets.TbSelect2", array(
                        'name' => 'col_name',
                        'data' => TypePlace::getTypes(),
                        'htmlOptions'=>array(
                            'empty'=>'Виберіть місце',
                            'class'=>'required',
                            'data-url'=>Yii::app()->createUrl('configuration/configuration/place'),
                            'data-type'=>'TypePlace'
                        ),
                        'value'=> !$sector->isNewRecord ? $sector->type_place_id : ""
                    ));
                ?>
            </div>
        <?php endif;?>
        <div class="form-group">
            <?php
                if ($sector->type == Sector::TYPE_FUN) {
                    $amount = $sector->places->fun_zone->amount;
                    echo CHtml::textField('fan_amount', $amount == 0 ? "" : $amount, array(
                        "class"=>"form-control  required",
                        "placeholder"=>"Необмежено"
                    ));

                }
            ?>
        </div>
    </div>
</div>