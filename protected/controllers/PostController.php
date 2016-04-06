<?php

class PostController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	/**
	 * @return array action filters
	 */
	public static function accessFilters()
	{
		return array(
			"index"=>array(
				"name"=>"Новини",
				"params"=>array(
					"access"=>array(
						"name"=>"Доступ до функціоналу",
						"params"=>array(),
						"type"=>Access::TYPE_CHECKBOX,
						"allow_actions"=>array(
							"/post/create",
							"/post/update",
							"/post/delete",
							"/post/generateAlias",
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
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->formJs();
		$model = new Post();
		$post = Yii::app()->request->getParam("Post");
		$this->performAjaxValidation($model);

		if(isset($post))
		{
			$model->attributes=$post;
			if($model->save())
				$this->redirect("index");
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function formJs()
	{
		Yii::app()->clientScript->registerScript('form', '
            $(document).on("click", "#aliasGenerate", function(e){
                $.post(
                    "'.$this->createUrl('generateAlias').'",
                    {
                        name : $("#Post_name").val()
                    }, function(result) {
                        $("#Post_alias_url").val(result);
                    }
                )
            });
        ', CClientScript::POS_READY);
	}

	/**
	 * Performs the AJAX validation.
	 * @param Post $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->formJs();
		$model= Post::model()->findByPk($id);
		$post = Yii::app()->request->getParam("Post");
		$this->performAjaxValidation($model);

		if(isset($post))
		{
			$model->attributes=$post;
			if($model->save())
				$this->redirect('index');
		}
		if ($model->multimedia_id) {
			$image = $model->getImageUrl();
		} else
			$image = [];
		$this->render('update',array(
			'model'=>$model,
			'image'=>$image,
		));
	}

	public function actionGenerateAlias()
	{
		$name = Yii::app()->request->getParam("name");
		$alias = '';
		if ($name)
			$alias = UrlTranslit::translit($name);
		echo $alias;
		Yii::app()->end();
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Post::model()->findByPk($id)->delete();
		$this->redirect("index");
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Post('search');
		$model->unsetAttributes();
		if(isset($_GET['Post']))
			$model->attributes=$_GET['Post'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
}
