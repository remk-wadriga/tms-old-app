<?php

class RoleController extends Controller
{
    public $layout = "//layouts/column2";


    public static function accessFilters()
    {
        return array(
            "index"=>array(
                "name"=>"Гравці",
                "params"=>array(
                    "access"=>array(
                        "name"=>"Доступ до сторінки та управління гравцями",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX,
                        "allow_actions"=>array(
                            "/role/update",
                            "/role/getChecks",
                            "/role/getRoleInfo",
                            "/role/access",
                            "/user/saveAccess",
                            "/user/getAccessList"
                        ),
                        "bizrule"=>"User::isAdminOfRole"
                    )
                ),
            ),

        );
    }

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

	public function actionIndex()
	{
        Yii::app()->clientScript->registerScript('index', '
            $(document).on("click", ".showInfo", function(e){
                $.post(
                    "'.$this->createUrl('getRoleInfo').'",
                    {
                        role_id : $(this).attr("href")
                    }, function(result) {
                        var obj = JSON.parse(result);
                        $(".modal-content").html(obj);
                    }
                )
            });
            function filterRoleGrid() {
                $.fn.yiiGridView.update("role-grid", {data:$("#searchRoleForm").serialize()})
            }

            $("#searchRoleForm input,select").on("change", filterRoleGrid);
        ', CClientScript::POS_READY);

        $role_name = Yii::app()->request->getParam("role_name");
        $template_role_name = Yii::app()->request->getParam("template_role_name");

        $criteria = new CDbCriteria();


        $ids = Role::getRoleAccess();
        if (!empty($ids))
            $criteria->compare("t.id", $ids);

        if (Yii::app()->request->isAjaxRequest) {
            $criteria->with = "roleTemplates";
            $criteria->together = true;
            $criteria->compare("t.name", $role_name, true);
            $criteria->compare("roleTemplates.id", $template_role_name);
        }

        $dataProvider = new CActiveDataProvider("Role", array(
            "criteria"=>$criteria,
            "pagination"=>array(
                "pageSize"=>10
            )
        ));
		$this->render('index', array(
            'dataProvider'=>$dataProvider,

        ));
	}

	public function actionCreate()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.DIRECTORY_SEPARATOR."js/role_accordion.js");
        $role = Yii::app()->request->getParam('Role');
        $rules = Yii::app()->request->getParam('rules');

        $model = new Role();

        if ($role) {
            $this->performAjaxValidation($model);
            $model->attributes = $role;
            $model->_rules = $rules;
            if ($model->save())
                $this->redirect('index');
        }
        $templates = TemplateRole::getTemplates();

        $this->render('create', array(
            "model"=>$model,
            'templates'=>$templates
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param TemplateRole|Role $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUpdate($id)
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.DIRECTORY_SEPARATOR."js/role_accordion.js");
        $role = Yii::app()->request->getParam('Role');
        $rules = Yii::app()->request->getParam('rules');

        Role::getRoleAccess($id);

        $model = Role::model()->findByPk($id);

        if ($role) {
            $this->performAjaxValidation($model);
            $model->attributes = $role;
            $model->_rules = $rules;
            if ($model->save())
                $this->redirect('index');
        } else
            $model->admin_id = $model->getUserAdmin();
        $checks = array();
        if (!$rules)
            foreach ($model->accesses as $access)
                $checks[] = $access->action;

        $templates = TemplateRole::getTemplates();

        $this->render("update", array(
            "model"=>$model,
            "checks"=>$checks,
            "templates"=>$templates
        ));
    }

    public function actionDelete($id)
    {

        $model = Role::model()->findByPk($id);
        if ($model)
            if ($model->delete()) {
                if(!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            } else
                echo json_encode(array("status"=>"error", "message"=>"Неможливо видалити елемент в якого є дочірні"));
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser

    }

    public function actionTemplateIndex()
    {

        $dataProvider = new CActiveDataProvider('TemplateRole', array(
            "pagination"=>array(
                "pageSize"=>10
            )
        ));
        $this->render('templateIndex', array(
            'dataProvider'=>$dataProvider
        ));
    }

    public function actionTemplateCreate()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.DIRECTORY_SEPARATOR."js/role_accordion.js");
        $templateRole = Yii::app()->request->getParam('TemplateRole');
        $rules = Yii::app()->request->getParam('rules');
        $model = new TemplateRole();

        if ($templateRole) {
            $this->performAjaxValidation($model);
            $model->attributes = $templateRole;
            $model->_rules = $rules;
            if ($model->save())
                $this->redirect('templateIndex');
        }

        $this->render('templateCreate', array(
            "model"=>$model
        ));
    }

    public function actionTemplateUpdate($id)
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.DIRECTORY_SEPARATOR."js/role_accordion.js");
        $templateRole = Yii::app()->request->getParam('TemplateRole');
        $rules = Yii::app()->request->getParam('rules');
        $model = TemplateRole::model()->with("templateRoleAccesses")->findByPk($id);

        if ($templateRole) {
            $this->performAjaxValidation($model);
            $model->attributes = $templateRole;
            $model->_rules = $rules;
            if ($model->save())
                $this->redirect('templateIndex');
        }
        $checks = array();
        if (!$rules)
            foreach ($model->templateRoleAccesses as $access)
                $checks[] = $access->action;
        $modelChecks = array();

        foreach ($model->templateRoleModels as $modelTemplate)
            $modelChecks[$modelTemplate['model']] = $modelTemplate['type'];

        $this->render('templateUpdate', array(
            "model"=>$model,
            "checks"=>$checks,
            "modelChecks"=>$modelChecks
        ));
    }

    public function actionTemplateDelete($id)
    {
        $model = TemplateRole::model()->findByPk($id);
        if ($model)
            if ($model->delete()) {
                if(!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            } else
                echo json_encode(array("status"=>"error", "message"=>"Шаблон використовується системою, видаліть дочірні елементи"));
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    }

    public function actionGetChecks()
    {
        $template_id = Yii::app()->request->getParam('template_id');
        if ($template_id) {
            $model = TemplateRole::model()->findByPk($template_id);
            $checks = array();
            foreach ($model->templateRoleAccesses as $access)
                $checks[] = str_replace("/", "_", $access->action);

            echo json_encode($checks);
        }
        Yii::app()->end();
    }

    public function actionGetRoleInfo()
    {
        $role_id = Yii::app()->request->getParam('role_id');
        if ($role_id) {
            $model = Role::model()->findByPk($role_id);
            $result =$this->renderPartial("_role_info", array(
                    "model"=>$model
                ), true);
            echo json_encode($result);
        }
    }

    public function actionAccess($id)
    {
        $this->layout = "//layouts/column1";
        $model = Role::model()->findByPk($id);
        $access = Yii::app()->request->getParam('Access');
        $levelAccess = Yii::app()->request->getParam('levelAccess');
        if ($access) {
            $model->_rules = $access;
            $model->narrow = $levelAccess;
            if ($model->save())
                Yii::app()->user->setFlash("alert_success", "Успішно збережено");
        }

        $this->render("access", array(
            "model"=>$model
        ));
    }

    public function getInArray($param,$accesses, $compareName=false)
    {
        return Role::getInArray($param,$accesses, $compareName);

    }

}