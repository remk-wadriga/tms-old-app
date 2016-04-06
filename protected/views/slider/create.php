<?php
/* @var $this SliderController */
/* @var $model Slider */

?>

<h1>Створити слайд</h1>

<?php $this->renderPartial('_form', array('model'=>$model, "events"=>$events,"cities" => $cities)); ?>