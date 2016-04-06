<div class="comments">
    <div class="head-title">Коментарі</div>
    <div class="row">
        <div class="col-lg-12">
            <?php
                $this->widget("booster.widgets.TbListView", array(
                    "id"=>"comment-list",
                    "itemView"=>"_items",
                    "dataProvider"=>$dataProvider,
                    "template"=>"{items}{pager}"
                ));
            ?>
        </div>
    </div>
    <hr/>
    <div class="form-group">
        <?php
            echo CHtml::textArea("comment", "", array(
                "class"=>"form-control"
            ));
        ?>
        <?php
            $this->widget("booster.widgets.TbButton", array(
                "context"=>"primary",
                "label"=>"Залишити коментар",
                "htmlOptions"=>array(
                    "class"=>"addComment"
                )
            ));
        ?>
    </div>
</div>