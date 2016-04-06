<?php

class SliderController extends Controller
{


	public static function accessFilters()
	{
		return array(
			"index"=>array(
				"name"=>get_called_class(),
				"params"=>array(
					"access"=>array(
						"name"=>"Доступ до функціоналу",
						"params"=>array(),
						"type"=>Access::TYPE_CHECKBOX,
						"allow_actions"=>array(
                            "/slider/create",
                            "/slider/update",
                            "/slider/delete",
                        )
					)
				),
			)
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via Slider request
		);
	}

	public function actionCreate()
	{
		$model = new Slider();
		$this->formJs();
		$slider = Yii::app()->request->getParam("Slider");

		$this->performAjaxValidation($model);

		if(isset($slider))
		{
			if(!isset($slider["id"])) {
				$model->attributes=$slider;
				if($model->save())
					$this->redirect('index');
			}
		}

		$cities = $model->getCities();
		$eventsData = Event::getListEvents();

		$this->render('create',array(
			'model'=>$model,
			"events" => $eventsData,
			"cities" => $cities,
		));
	}

	public function formJs()
	{
		Yii::app()->clientScript->registerScript('form', '
            $(document).on("change", "#checkAll_0", function(e){
            	var cities = $(".cities");
                if($(this).prop("checked")) {
					cities.each(function(){
						if(!$(this).prop("checked"))
							$(this).click();
					});
                } else {
                	cities.each(function(){
						if($(this).prop("checked"))
							$(this).click();
					});
                }
            });

            $(document).on("click", ".slider_save", function(e){
            	var event = $("#Slider_event_id");
                if(event.val() == 0) {
                	e.preventDefault();
                	alert("Виберіть подію");
                }
            });
        ', CClientScript::POS_READY);
	}

	/**
	 * Performs the AJAX validation.
	 * @param Slider $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='slider-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionUpdate($id)
	{

		$model= Slider::model()->findByPk($id);
		$this->formJs();
		$slider = Yii::app()->request->getParam("Slider");
		$this->performAjaxValidation($model);

		if(isset($slider))
		{
			if(!isset($slider["id"])) {
				$model->attributes=$slider;
				if($model->save())
					$this->redirect('index');
			}
		}

		$image = $model->getImageUrl();
		$cities = $model->getCities();
		$eventsData = Event::getListEvents();


		$this->render('update',array(
			'model'=>$model,
			'image'=>$image,
			"events" => $eventsData,
			"cities" => $cities,
		));
	}

	public function actionDelete($id)
	{
		Slider::model()->findByPk($id)->delete();
		$this->redirect("index");
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Slider('search');
		$model->unsetAttributes();
		if(isset($_GET['Slider']))
			$model->attributes=$_GET['Slider'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
}
