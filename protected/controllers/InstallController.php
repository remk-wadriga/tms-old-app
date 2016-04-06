<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 18.09.14
 * Time: 12:08
 */

class InstallController extends CController {

    public $layout = "//layouts/install";

    public function actionIndex()
    {
        $model = new Install();
        $install = Yii::app()->request->getParam("Install");
        if ($install) {
            $model->attributes = $install;
            if ($model->setup())
                $this->redirect(array("/"));
        }
        $this->render("install", array(
            "model"=>$model
        ));
    }

    public function actionSuccess()
    {
        $this->render("success");
    }
} 