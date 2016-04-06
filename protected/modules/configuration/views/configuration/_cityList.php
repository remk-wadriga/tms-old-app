<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 09.11.14
 * Time: 8:51
 * @var $cities City[]
 */
?>


<div class="row">
    <div class="col-md-6">
        <h4>Адміністративна структура</h4>
    </div>
    <div class="col-md-6">
        <?php
            $this->widget("booster.widgets.TbButton", array(
                'context' => 'success',
                'label'=> 'Новий елемент',
                'htmlOptions' => array(
                    'class' => 'new-city pull-right',
                    'data-toggle' => 'modal',
                    'data-target' => '#newCity',
                ),
            ));
        ?>
    </div>
</div>

<?php
    $level=0;
    foreach ($cities as $n=>$city) {
        if ($city->level==$level)
            echo '</li>';
        else if ($city->level>$level)
            echo '<ul class="tree" id="tree">';
        else {
            echo '</li>';
            for ($i=$level-$city->level;$i;$i--) {
                echo '</ul>';
                echo '</li>';
        }
    }

    echo '<li>';
    $parent = $city->region_id;
    echo CHtml::link($city->name, "#", array(
        "id"=>"City_name_".$city->id,
        "data-name"=>"name",
        "data-pk"=>$city->id,
        "data-parent"=>$parent,
        "data-status"=> $city->status,
        "data-type"=>"text",
        "data-lng"=>$city->lng,
        "data-lat"=>$city->lat
    ));
    echo CHtml::link("", '' , array(
        "class"=>"glyphicon glyphicon-wrench update-city icon-button",
        'data-toggle' => 'modal',
        'data-target' => '#newCity',
        ));
    $level=$city->level;
}

for ($i=$level;$i;$i--) {
    echo '</li>';
    echo '</ul>';
}
?>