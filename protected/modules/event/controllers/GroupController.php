<?php

class GroupController extends Controller
{
	public function actionIndex()
	{
		$group = Yii::app()->request->getParam('Group');
		$model = new Group('search');

		$model->unsetAttributes();

		$model->attributes = $group;
		$this->render('index', array(
			'model'=>$model,
		));
	}

	public function actionCreate()
	{
		$group = Yii::app()->request->getParam('Group');
		$model = new Group();

		$this->performAjaxValidation($model);
		if ($group) {
			$model->attributes = $group;
			if ($model->save())
				$this->redirect('index');
		}

	}

	public function actionDelete($id)
	{
		Group::model()->findByPk($id)->delete();
		$this->redirect('index');
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


	public function actionUpdate($model)
	{
		Yii::import('ext.bootstrap.components.TbEditableSaver');
		$es = new TbEditableSaver($model);
		$es->update();
	}

}