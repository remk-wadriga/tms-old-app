<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 19.10.15
 * Time: 16:22
 * @var $dataProvider CArrayDataProvider
 * @var $this AccessListWidget
 */
 $this->widget("booster.widgets.TbButton", array(
    "label"=>"Check",
    "htmlOptions"=>array(
        "onclick"=>"js:$(\"input[type=checkbox]\").each(function(){if ($(this).is(\":visible\")){\$(this).prop(\"checked\", true)}})"
    )

));
$this->widget("booster.widgets.TbButton", array(
    "label"=>"Uncheck",
    "htmlOptions"=>array(
        "onclick"=>"js:$(\"input[type=checkbox]\").each(function(){if ($(this).is(\":visible\")){\$(this).prop(\"checked\", false)}})"
    )
));
    $this->widget("booster.widgets.TbTabs", array(
        "type"=>"tabs",
        "tabs"=>$tabs,
        "placement"=>"left",
        "tabContentHtmlOptions"=>array(
            "class"=>"col-lg-9"
        )
    ));