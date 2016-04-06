<?php
$this->widget("booster.widgets.TbListView", array(
    "id"=>"comment-list",
    "itemView"=>"application.widgets.commentWidget.views._items",
    "dataProvider"=>$dataProvider,
    "template"=>"{items}{pager}"
));