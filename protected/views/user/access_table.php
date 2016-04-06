<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 17.07.15
 * Time: 17:15
 * @var $dataProvider CArrayDataProvider
 * @var $actions array
 */
$columns = array(
    array(
        "class"=>"CCheckBoxColumn",
        "value"=>'$data["id"]'
    ),
    array(
        "header"=>"Подія",
        "value"=>'$data["id"]." - ".$data["name"]'
    )
);

if (count($actions))
    for($i = 0; $i<count($actions); $i++) {
        $url = $actions[$i]['url'];
        $columns[] = array(
            "header"=>$actions[$i]['name'],
            "value"=>'CHtml::checkBox("options['.$i.']", "'.in_array($url, $user_accesses).'", array(
                "data-access"=>$data['.$i.']["url"],
                "class"=>"access_checkbox",
                "data-event_id"=>$data["id"]
            ))',
            "type"=>'raw'
        );
    }
echo CHtml::hiddenField("controller", $controller);
echo CHtml::radioButtonList("levelAccess", "", array(
        User::ACCESS_ALL=>"Доступ до всіх подій групи в межах цього функціоналу",
        User::ACCESS_SELECTED=>"Доступ до обраних подій"
    ), array(
        "separator"=>" "
));

$this->widget("booster.widgets.TbButton", array(
    "context"=>"primary",
    "label"=>"Змінити рівень доступу для виділених подій"
));
?>
&nbsp;
<?php
$this->widget("booster.widgets.TbButton", array(
    "context"=>"primary",
    "label"=>"+Доступ до нової події"
));

$this->widget("booster.widgets.TbGridView", array(
    "id"=>"access-grid",
    "dataProvider"=>$dataProvider,
    "columns"=>$columns
));