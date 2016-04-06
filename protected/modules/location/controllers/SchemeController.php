<?php

class SchemeController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	public function actionIndex($scheme_id)
	{
		$model = Scheme::model()->with('sectors')->findByPk($scheme_id);
		$cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mousewheel.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.draggable.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.parser.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.export.min.js");

		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/reset.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/bootstrap-theme.min.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/editor.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/style.css");

		Scheme::getVisualInfo();

		$this->render('index', array(
			'model'=>$model
		));
	}

	public function actionView($id)
	{
		$model = Scheme::model()->with('sectors')->findByPk($id);
		$this->render('view', array(
			'model'=>$model
		));
	}

	public function actionSectorView($id)
	{
		$model = Scheme::model()->with('sectors')->findByPk($id);
		$this->render('view', array(
			'model'=>$model
		));
	}

	public function actionInformation($id)
	{
		$model = Scheme::model()->with('sectors')->findByPk($id);
		$this->render('view', array(
			'model'=>$model
		));
	}

    public function actionDelete()
    {
        $id = Yii::app()->request->getParam('id');
        $scheme = Scheme::model()->findByPk($id);
        if($scheme->delete())
            echo $this->createAbsoluteUrl('/location/location/index');
    }

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}