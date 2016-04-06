<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 12.11.14
 * Time: 14:11
 * @var $this SectorController
 */
?>

<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b">Візуальний редактор</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <div class="visualEditor">
    <?php echo CHtml::hiddenField("scheme_id", $scheme->id) ?>
    <div class="alert alert-success success-sector-alert" style="display: none" role="alert">Успішно збережено</div>
    <div class="alert alert-danger danger-sector-alert" style="display: none" role="alert">Помилка</div>
    <button id="save_sector" class="btn btn-success" onclick="saveAllSectorVisual();"><i class="fa fa-save m-r-sm"></i> Зберегти</button>
        <?php $this->widget("application.widgets.mapWidget.MapWidget", array(
            "class"=>"editor_cont",
            "hasMacro"=>$scheme->hasMacro,
            "bitMapUrl"=>$this->createUrl("/location/sector/saveBitMap")
        ));?>
    <section id="sectors_list" class="panel sh-box">
        <header class="panel-heading">
            <a href="#" class="panel-toggle text-muted block">Список секторів<i class="fa fa-caret-down text-active pull-right"></i><i class="fa fa-caret-up text pull-right"></i></a>
        </header>
        <div class="panel-body">
            <div class="dropdown_content" data-checkUrl="<?= $this->createUrl("/location/sector/checkHideAccess")?>">
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'itemView'=>'_sectorList',
                    'dataProvider'=>$sectorsActive,
                    'summaryText'=>false,
                    'itemsCssClass'=>'active',
                    'emptyText'=>false
                ));
                ?>
                <hr/>
                <?php
                $this->widget('zii.widgets.CListView', array(
                    'itemView'=>'_sectorList',
                    'dataProvider'=>$sectors,
                    'summaryText'=>false,
                    'itemsCssClass'=>'inactive',
                    'emptyText'=>false
                ));
                ?>
            </div>
        </div>
    </section>
</div>
</div>