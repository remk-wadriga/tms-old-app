<?php
/**
 * @var $this LocationController
 * @var $model Location
 * Created by PhpStorm.
 * User: elvis
 * Date: 26.09.14
 * Time: 5:33
 */
?>

<header class="header b-b bg-dark">
    <div class="row">
        <div class="col-xs-12">
            <h4 class="m-t m-b">Створення локації</h4>
        </div>
    </div>
</header>
<div class="wrapper">
    <?php $this->renderPartial("_form", array("model"=>$model,'countries'=>$countries)); ?>
</div>
