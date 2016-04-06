<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 02.12.14
 * Time: 12:50
 * @var $this SectorController
 * @var $form TbActiveForm
 * @var $model Sector
 * @var $scheme Scheme
 */
$this->beginWidget('booster.widgets.TbModal',array(
    'id'=>'newSectorModal'
));


?>

    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Створення нового сектору</h4>
    </div>

    <div class="modal-body">
        <?php
        $this->widget(
            'booster.widgets.TbTabs',
            array(
                'type' => 'pills',
                'justified' => true,
                'tabs' => array(
                    array('label' => 'Новий сектор', 'content'=>$this->renderPartial("_newSector", array("scheme"=>$scheme, "model"=>$model), true), 'active'=>true),
                    array('label' => 'Копія існуючого', 'content' => $this->renderPartial("_copySector", array("scheme"=>$scheme, "model"=>$model), true)),
                    array('label' => 'Багато копій існуючого', 'content' => $this->renderPartial("_copiesSector", array("scheme"=>$scheme, "model"=>$model), true)),
                )
            )
        );

        ?>
    </div>



<?php


$this->endWidget();

