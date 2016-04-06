<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 08.11.14
 * Time: 17:13
 * @var $form TbActiveForm
 * @var $scheme Scheme
 * @var $sector Sector
 * @var $this SectorController
 */
?>

<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b">Структурний редактор</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <div class="st-editor" style="position: relative;">
    <div class="alert alert-danger danger-sector-alert" style="display: none" role="alert">Помилка</div>
    <div class="alert alert-success success-sector-alert" style="display: none" role="alert">Успішно збережено</div>
    <?php
    $this->renderPartial("_addSector", array("model"=>$sector, "scheme"=>$scheme));
    $form = $this->beginWidget("booster.widgets.TbActiveForm", array(
        "id"=>"add_hall",
        "htmlOptions"=>array(
            "onsubmit"=>"return saveScheme(this);"
        )
    ));
    ?>
    <?php echo CHtml::hiddenField("scheme_id", $scheme->id); ?>

    <section class="panel sidebar-pannel">
        <div class="panel-body">
            <div class="info">
                Схема: <strong><?php echo $scheme->name ?></strong><br/>
                Локація: <strong><?php echo $scheme->location->name ?></strong><br/>
                Місто: <strong><?php echo $scheme->location->city->name ?></strong>
            </div>
            <hr/>
            <div class="row-5">
                <div class="col-xs-6">
                    <?php
                    $this->widget('booster.widgets.TbButton', array(
                        'buttonType' => 'link',
                        'context' => 'success',
                        'size' => 'extra_small',
                        'label' => 'Видалити схему',
                        'icon' => 'trash',
                        'htmlOptions' => array(
                            'class' => 'btn-block m-b-sm',
                            'id' => 'deleteSchemeButton',
                            'data-id' => $scheme->id,
                        )
                    ));
                    ?>
                </div>
                <div class="col-xs-6">
                    <?php
                    $this->widget("booster.widgets.TbButton", array(
                        'context' => 'primary',
                        'url' => $this->createUrl('/location/sector/visualScheme', array('scheme_id' => $scheme->id)),
                        'label'=>' Візуал. ред.',
                        'icon' => 'fire',
                        'buttonType' => 'link',
                        'context' => 'success',
                        'size' => 'extra_small',
                        'htmlOptions' => array(
                            'class' => 'btn-block m-b-sm',
                        ),
                    ));
                    ?>
                </div>
                <div class="col-xs-6">
                    <?php
                    $this->widget('booster.widgets.TbButton', array(
                        'context' => 'primary',
                        'label' => 'Додати сектор',
                        'icon' => 'plus',
                        'context' => 'success',
                        'size' => 'extra_small',
                        'htmlOptions' => array(
                            'data-toggle' => 'modal',
                            'data-target' => '#newSectorModal',
                            'class' => 'btn-block',
                        ),
                    ));
                    ?>
                </div>
            </div>
            <div class="sector-buttons hidden">
                <div class="row-5">
                    <div class="col-xs-6">
                        <?php
                        $this->widget('booster.widgets.TbButton', array(
                            'context' => 'primary',
                            'buttonType' => 'submit',
                            'label' => 'Зберегти сектор',
                            'icon' => 'floppy-disk',
                            'size' => 'extra_small',
                            'htmlOptions' => array(
                                'class' => 'btn-block m-t-sm',
                            )
                        ));
                        ?>
                    </div>
                    <div class="col-xs-6">
                        <?php
                        $this->widget('booster.widgets.TbButton', array(
                            'context' => 'primary',
                            'label' => 'Видалити сектор',
                            'icon' => 'trash',
                            'size' => 'extra_small',
                            'htmlOptions' => array(
                                'class' => 'btn-block m-t-sm',
                                'onclick' => "deleteScheme($(this).parents('form'))",
                            )
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="settingsBlock">
                <?php $this->renderPartial('_settings', array( 'sector'=>$sector, 'scheme'=>$scheme )); ?>
            </div>
        </div>
    </section>
    <div class="editor-content">
        <div class="row">
            <div class="col-xs-12 scheme-container hidden">
                <div id="sector_net" class="net_container m-t-lg"></div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>
</div>