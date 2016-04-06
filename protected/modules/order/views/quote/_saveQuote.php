<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.05.15
 * Time: 16:03
 * @var $this QuoteController
 * @var $model Quote
 * @var $event Event
 * @var $form TbActiveForm
 */

$this->beginWidget("booster.widgets.TbModal", array(
    "id"=>"saveQuoteModal"
));
$blocks = Yii::app()->user->getState("saveBlock_".$event->id);
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h3>
            Зберегти квоту
        </h3>
    </div>
    <div class="modal-body" id="saveTabs" role="tabpanel">
        <ul class="nav-tabs" role="tablist">
            <?php
            if ($blocks) {
                $active = false;
                foreach ($blocks as $k=>$block) {
                    $name = Role::getRoleName($k);
                    ?>
                <li role="presentation" class="<?php echo !$active ? 'active' : ''?>" id="control_<?php echo $name;?>">
                    <?php

                    echo CHtml::link($name, "#".$name, array(
                        "aria-controls"=>$name,
                        "role"=>"tab",
                        "data-toggle"=>"tab"
                    ));
                    echo "</li>";
                    if (!$active)
                        $active = true;
                }
            }
            ?>
        </ul>
        <div class="tab-content">
            <?php
            if ($blocks) {
                $active = false;
                foreach ($blocks as $k=>$block) {
                    $name = Role::getRoleName($k);
                    ?>
                    <div role="tabpanel" class="tab-pane <?php echo !$active ? 'active' : ''?>"  id="<?php echo $name?>">
                    <?php
                    $this->renderPartial("_saveQuoteForm", array(
                        "model"=>$model,
                        "contractor"=>$block,
                        "event_id"=>$event->id
                    ));

                    ?>
                    </div>
                    <?php

                    if (!$active)
                        $active = true;
                }

            }

            ?>

        </div>
    </div>
<div class="clearfix"></div>

    <div class="modal-footer">
        <?php
        $this->widget("booster.widgets.TbButton", array(
            "label"=>"Скасувати",

            "htmlOptions"=>array(
                "data-dismiss"=>"modal",
            )
        ));
        ?>
    </div>
<?php
$this->endWidget();