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

<h1>Редагування локації</h1>
<?php $this->renderPartial("_form", array("model"=>$model,'countries'=>$countries));