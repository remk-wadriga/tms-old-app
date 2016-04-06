<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 25.09.2015
 * Time: 14:31
 */
$this->beginWidget(
    'booster.widgets.TbModal',
    array('id' => 'getInvoice'
    ));
?>

<div class="modal-header">
    <a class="close closeCountry" data-dismiss="modal">&times;</a>
    <h4 id="tree_label">Створення звіту з валом</h4>
</div>
<div  class="modal-body">
    <p>Оберіть вигляд таблиці, який Ви хочете отримати.<br/>Формат вихідного файлу .xls</p>
    <div class="radio">
        <?php
        echo CHtml::radioButtonList('invoiceType',0,ExelGenerator::$types, array(
                "style"=>"margin-left:5px",
                "id"=>"invoiceType"
        ));
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
            'id' => 'generateInvoice',
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
                'class' => 'closeTree'
            ),
        )
    ); ?>
</div>

<?php
$this->endWidget();
?>
