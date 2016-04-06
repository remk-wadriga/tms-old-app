<?php
/* @var $this SliderController */
/* @var $model Slider */
?>

<h1>Редагувати слайд</h1>

<?php $this->renderPartial('_form', array('model'=>$model, "events"=>$events, "image"=>$image, "cities" => $cities)); ?>