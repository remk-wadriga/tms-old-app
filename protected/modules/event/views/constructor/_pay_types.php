<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 17.12.2015
 * Time: 15:16
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'payTypes'
    ));
?>

    <div class="modal-header">
        <a class="close closeCountry" data-dismiss="modal">&times;</a>
        <h4 id="tree_label">Обмеження кошика</h4>
    </div>
    <div  class="modal-body">
        <?php
        $form = $this->beginWidget("booster.widgets.TbActiveForm",array(
            "id"=>"event_pay_types",
            )
        );
        ?>
        <div class="radio">
            <?php
            echo $form->hiddenField($model,'event_id');
            echo $form->checkBoxList($model, 'types', EventPayType::$typeLabels,[]);
            ?>
        </div>

    </div>
    <div class="modal-footer">
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'context' => 'primary',
                'label' => 'Зберегти',
                'url' => '#',
                'id' => 'submitTypes',
            )
        );
        ?>
        <?php $this->widget(
            'booster.widgets.TbButton',
            array(
                'label' => 'Закрити',
                'url' => '#',
                'htmlOptions' => array(
                    'data-dismiss' => 'modal',
                ),
            )
        ); ?>
    </div>

<?php
$this->endWidget();
$this->endWidget();
?>