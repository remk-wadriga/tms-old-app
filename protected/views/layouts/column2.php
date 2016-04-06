<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/nt-main'); ?>
    <div class="row">
        <div class="col-md-9">
            <?php echo $content; ?>
        </div>
        <div class="col-md-3">
            <div id="sidebar">
                <?php
                $this->widget('booster.widgets.TbMenu',
                    array(
                        'type' => 'list',
                        'items' => $this->menu,
                    ));
                ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
<?php $this->endContent(); ?>