<?php

class CommentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'addComment'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete', 'delComment'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}



	public function actionAddComment()
	{
		$user_id = Yii::app()->request->getParam("user_id");
		$model_name = Yii::app()->request->getParam("model_name");
		$model_id = Yii::app()->request->getParam("model_id");
		$text = Yii::app()->request->getParam("text");

		if ($user_id && $model_id && $model_name && $text) {
			$model = new Comment();
			$model->user_id = $user_id;
			$model->model = $model_name;
			$model->model_id = $model_id;
			$model->text = CHtml::encode($text);
			if ($model->save()) {
				$this->echoComments($model_name, $model_id);
			}
		}
	}

	public function actionDelComment()
	{
		$id = Yii::app()->request->getParam("id");
		if ($id) {
			$model = $this->loadModel($id);
			$model_name = $model->model;
			$model_id = $model->model_id;
			$model->delete();
			$this->echoComments($model_name, $model_id);
		}
	}

	public function echoComments($model_name, $model_id)
	{
		$comments = Comment::model()->findAllByAttributes(array("model"=>$model_name, "model_id"=>$model_id), array("order"=>"dateadd DESC"));
		$dataProvider = new CArrayDataProvider($comments);
		$this->render("comment", array("dataProvider"=>$dataProvider));
	}

	public function actionUpdate()
	{
		$id = Yii::app()->request->getParam("pk");
		$attribute = Yii::app()->request->getParam("name");
		$value = Yii::app()->request->getParam("value");

		if ($id && $attribute && $value) {
			$model = Comment::model()->findByPk($id);
			$model->saveAttributes(array($attribute=>$value));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Comment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
