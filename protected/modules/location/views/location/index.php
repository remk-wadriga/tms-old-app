<?php
/**
 * @var $cities Array()
 * @var $categories Array()
 * @var $form TbActiveForm
 * Created by PhpStorm.
 * User: elvis
 * Date: 26.09.14
 * Time: 4:31
 */

?>
<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b pull-left">Список локацій</h4>
            <div class="pull-right">
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    'context' => 'primary',
                    'url' => Yii::app()->createUrl('/location/location/create'),
                    'label' => ' Додати локацію',
                    'icon' => 'plus',
                    'buttonType' => 'link',
                    'context' => 'success',
                    'htmlOptions' => array(
                        'class' => 'mt7'
                    )
                ));
                ?>
            </div>
        </div>
    </div>
</header>
<div class="wrapper">
        <?php
        $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
            'id'=>'location-filter',
            'htmlOptions'=>array(
            )
        ));
        ?>
        <div class="row-5">
            <div class="col-sm-2">
                <?php
                echo CHtml::dropDownList("city_id", $city, $cities,array(
                    'class' => 'form-control',
                    'empty' => 'Усі міста'
                ))
                ?>
            </div>
            <div class="col-sm-2">
                <?php
                echo CHtml::dropDownList('category_id', $category, $categories, array(
                    'class' => 'form-control',
                    'empty' => 'Усі типи'
                ))
                ?>
            </div>
            <div class="col-sm-3">
                <?php
                echo CHtml::textField('name', $name, array(
                    'class' =>'form-control'
                ))
                ?>
            </div>
            <div class="col-md-1">
                <?php
                $this->widget("booster.widgets.TbButton", array(
                    'context' => 'primary',
                    'label' => 'Пошук',
                    'buttonType' => 'submit'
                ))
                ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>

<hr/>
<?php
    $this->widget(
        'booster.widgets.TbListView',
        array(
            'dataProvider' => $dataProvider,
            'itemView' => '_list',
            'summaryText' => false,
            'htmlOptions' => array(
                'class' => 'panel-group',
                'role' => 'tablist',
                'aria-multiselectable' => 'true'
            )
        )
    );
    $this->renderPartial('_newScheme', array(
        'model' => $scheme
    ));
?>
</div>