<?php
Yii::import('application.modules.event.models.*');
Yii::import('application.modules.event.controllers.*');
Yii::import('application.modules.order.controllers.*');
Yii::import('application.modules.configuration.controllers.*');
Yii::import('application.modules.statistics.controllers.*');
Yii::import('application.modules.location.controllers.*');
Yii::import('application.controllers.*');
class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

    public static function accessFilters()
    {
        return array(
            "index"=>array(
                "name"=>"Користувачі",
                "params"=>array(
                    "access"=>array(
                        "name"=>"Доступ до сторінки та управління користувачами",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX,
                        "allow_actions"=>array(
                            "/user/access",
                            "/user/saveAccess",
                            "/user/getAccessList"
                        ),
                        "bizrule"=>"User::isAdminOfRole"
                    )
                ),
            ),

        );
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new User('create');

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->save())
				$this->redirect('index');
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionRole()
	{
		$roles = Yii::app()->authManager->roles;
		$dataProvider = new CArrayDataProvider($roles, array(
			"pagination" => array(
				"pageSize" => false
			)
		));

		$this->render('roles', array(
			'dataProvider' => $dataProvider
		));
	}

	public function actionCreateRole()
	{

	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if (isset($_POST['User'])) {
			$model->attributes = $_POST['User'];
			if ($model->save())
				$this->redirect('index');
		}
		$model->password = '';

		$this->render('update', array(
			'model' => $model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model = new User('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['User']))
			$model->attributes = $_GET['User'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

    protected function getAllowUserIds($id=false)
    {
        if (!User::isAdminOfRole())
            throw new CHttpException(403);

        $ids = User::model()->getAllowUserIds();
        if ($id&&!in_array($id, $ids))
            throw new CHttpException(403);
        return $ids;
    }

	public function actionAccess($id)
	{
        $this->getAllowUserIds($id);
		$this->layout = "//layouts/column1";
		$model = $this->loadModel($id);
        $access = Yii::app()->request->getParam('Access');
        if ($access) {
            $role_id = Yii::app()->request->getParam('group_id');
            $levelAccess = Yii::app()->request->getParam('levelAccess');
            $model->_rules = $access;
            $model->_levelAccess = $levelAccess;
            $model->_role_id = $role_id;
            $model->save();
        }

        $roles = $model->getUserRoles();
        if (!isset($role_id))
            foreach ($roles as $k => $role) {
                $role = Role::model()->findByPk($k);
                break;
            }
        else
            $role = Role::model()->findByPk($role_id);

		$this->render('access', array(
			"model" => $model,
            "role" => $role
		));
	}

	public function actionUserAccess($user_id)
	{
		$model = $this->loadModel($user_id);
		$id = Yii::app()->request->getParam("id");
		if ($model && $id) {
			$role = Role::model()->findByPk($id);
            if (Yii::app()->request->getParam("change")) {
                $result['type'] = Yii::app()->db->createCommand()
                    ->select('type')
                    ->from("{{user_role}}")
                    ->where('role_id=:id AND user_id=:user_id', array(
                        ":id"=>$id,
                        ":user_id"=>$user_id
                    ))
                    ->queryScalar();

                $result['block'] = $this->renderPartial("_access_block", array(
                    "model" => $model,
                    "role" => $role,
                ), true);
                echo json_encode($result);
                Yii::app()->end();
            } else
                $this->renderPartial("_access_block", array(
                    "model" => $model,
                    "role" => $role,
                ), false, true);

		}
	}

	public function actionGetEventsByAccess()
	{
		$role = Yii::app()->request->getParam('role_id');
		$user_id = Yii::app()->request->getParam('user_id');
		$controller = Yii::app()->request->getParam('controller');

		if ($role&&$user_id) {
			$events = Event::model()->findAllByAttributes(array("role_id"=>$role));

			$result = array();

			$actions = $controller::getActions();
			$user_accesses = Yii::app()->db->createCommand()
				->select("action")
				->from("{{user_access}}")
				->where("user_id=:user_id AND role_id=:role_id AND controller=:controller", array(
					":user_id"=>$user_id,
					":role_id"=>$role,
					":controller"=>$controller
				))
				->queryColumn();
			foreach ($events as $event) {
				$result[] = array(
					"id"=>$event->id,
					"name"=>$event->name
				) + $actions;
			}

			$dataProvider = new CArrayDataProvider($result);


			$table = $this->renderPartial("access_table", array("user_accesses"=>$user_accesses, "dataProvider"=>$dataProvider, "actions"=>$actions, "controller"=>$controller), true);
			echo json_encode($table);
		}

	}

	public function actionSaveAccess()
	{
		$user_id = Yii::app()->request->getParam("user_id");
		$levelAccess = Yii::app()->request->getParam("levelAccess");
		$role_id = Yii::app()->request->getParam("role_id");
		$access = Yii::app()->request->getParam('Access');
		$type = Yii::app()->request->getParam('type');
        $model = false;
		if ($role_id) {
            $type = (bool)$type;
            $events = array();
            $hasEvents = false;
            if ($user_id&&$role_id) {
                $model = User::model()->findByPk($user_id);
                $model->_role_id = $role_id;
            }
            elseif ($role_id)
                $model = Role::model()->findByPk($role_id);
            if ($user_id&&$access) {
                if ($model) {
                    foreach ($access as $module => $controllers)
                        if (strstr($module, "Controller")) {
                            $actionName = '/' . lcfirst(str_replace("Controller","",$module)) . "/" . lcfirst(key($controllers)) ;
                            $events = $model->getResultSql($controllers, $module)["events"];
                        }
                        else
                            foreach ($controllers as $controller => $actions) {
                                $actionName = $module ? "/" . $module . "/" . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst(key($actions)) : '/' . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst(key($actions)) ;
                                $events = $model->getResultSql($actions, $controller, $module)["events"];
                            }

                    $ids = Yii::app()->db->createCommand()
                        ->select("id")
                        ->from(Access::model()->tableName())
                        ->where("role_id=:role_id AND user_id=:user_id AND action=:action",
                            array(
                                ":role_id"=>$role_id,
                                ":user_id"=>$model->id,
                                ":action"=>$actionName
                            ))
                        ->queryColumn();
                    if (!empty($ids)) {
                        if (!empty($events))
                            Yii::app()->db->createCommand()
                                ->delete("{{access_event}}", "access_id in (:access_ids) AND event_id=:event_id",
                                    array(":access_ids"=>implode(",", $ids),
                                            ":event_id"=>$events[0]['event_id']));

                        $hasEvents = Yii::app()->db->createCommand()->select("count(*)")->from("{{access_event}}")
                                ->where(array("in", "access_id", $ids))->queryScalar()>0;
                        if (!$hasEvents)
                            Yii::app()->db->createCommand()->delete(
                                Access::model()->tableName(), array("in", "id", $ids)
                            );
                    }

                    $model->_rules = $access;
                    if ($type&&$levelAccess==Role::LEVEL_SELF) {
                        $model->setUserAccesses($hasEvents);

                    }
                }
            } elseif ($access) {
                foreach ($access as $module => $controllers)
                    if (strstr($module, "Controller")) {
                        $actionName = '/' . lcfirst(str_replace("Controller","",$module)) . "/" . lcfirst(key($controllers)) ;
                        $events = $model->getResultSql($controllers, $module)["events"];
                    }
                    else
                        foreach ($controllers as $controller => $actions) {
                            $actionName = $module ? "/" . $module . "/" . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst(key($actions)) : '/' . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst(key($actions)) ;
                            $events = $model->getResultSql($actions, $controller, $module)["events"];
                        }
                $ids = Yii::app()->db->createCommand()
                    ->select("id")
                    ->from(Access::model()->tableName())
                    ->where("role_id=:role_id AND user_id IS NULL AND action=:action",
                        array(
                            ":role_id"=>$role_id,
                            ":action"=>$actionName
                        ))
                    ->queryColumn();
                if (!empty($ids)) {
                    if (!empty($events))
                        Yii::app()->db->createCommand()
                            ->delete("{{access_event}}", "access_id in (:access_ids) AND event_id=:event_id",
                                array(":access_ids"=>implode(",", $ids),
                                    ":event_id"=>$events[0]['event_id']));
                    $hasEvents = Yii::app()->db->createCommand()->select("count(access_id)")->from("{{access_event}}")
                            ->where(array("in", "access_id", $ids))->queryScalar()>0;
                    if (!$hasEvents)
                        Yii::app()->db->createCommand()->delete(
                            Access::model()->tableName(), array("in", "id", $ids)
                        );
                }
                $model->_rules = $access;
                if ($type&&$levelAccess==Role::LEVEL_SELF) {
                    $model->saveAccessRecursively($access, $hasEvents);
                    $model->deleteChildAccessRecursively($model->getAccessActions());

                }

            }
            if ($model instanceof User)
                Yii::app()->db->createCommand()
                    ->update("{{user_role}}", array("type"=>$levelAccess), "role_id=:role_id AND user_id=:user_id", array(
                        ":role_id"=>$model->_role_id,
                        ":user_id"=>$model->id
                    ));
            elseif ($model instanceof Role)
                Yii::app()->db->createCommand()
                    ->update("{{role_child}}", array("narrow"=>$levelAccess), "child=:id", array(
                        ":id"=>$model->id
                    ));
//
//			if ($type === "true")
//				Yii::app()->db->createCommand()->insert("{{user_access}}", array(
//					"user_id"=>$user_id,
//					"role_id"=>$role_id,
//					"action"=>$url,
//					"event_id"=>$event_id,
//					"controller"=>$controller
//				));
//			else
//				Yii::app()->db->createCommand()->delete("{{user_access}}", "user_id=:user_id AND role_id=:role_id AND action=:action AND event_id=:event_id AND controller=:controller", array(
//					":user_id"=>$user_id,
//					":role_id"=>$role_id,
//					":action"=>$url,
//					":event_id"=>$event_id,
//					":controller"=>$controller,
//				));

			echo "OK";
		} else {

        }
	}

	public function actionEditable()
	{
		Yii::import('ext.bootstrap.components.TbEditableSaver'); //or you can add import 'ext.editable.*' to config
		$es = new TbEditableSaver('User');  // 'User' is classname of model to be updated
		$es->update();
	}

    public function getInArray($param,$accesses, $compareName=false)
    {
        return Role::getInArray($param,$accesses, $compareName);
    }

	private function optionsList($accesses)
	{
		$result = "";
		foreach ($accesses as $k=>$access) {
			$result .= CHtml::tag("option", array("value"=>$k), $access);
		}
		return $result;
	}

    public $accesses;
    public $role;
    public $model = null;

    public $narrow;
    public $parentAccesses = array();
    public $parentEventAccess = array();
    public $parentIsAdmin = false;


	public function actionGetAccessList() {
		$type = Yii::app()->request->getParam("type");
		$access = Yii::app()->request->getParam("access");
        $id = Yii::app()->request->getParam("id");
        $role_id = Yii::app()->request->getParam("role_id");
        $page = Yii::app()->request->getParam("pager");
        $model = false;
        if ($type!==null&&$access&&$id) {
            if ($type==Access::LEVEL_ROLE) {
                $role_id = $id;
            } else {
                $this->model = User::model()->findByPk($id);
            }
            $this->role = Role::model()->findByPk($role_id);
            $this->accesses = json_encode($this->role->getAccessNames(array($type), isset($this->model) ? $this->model : false));

            if (!$this->model)
                $this->model = $this->role;
            $access = array_values(array_filter(explode("/", $access)));
            $i=0;
            if (count($access)==2)
                $i=1;
            $parent = $this->model instanceof User ? $this->role : $this->role->roleChildren1[0];
            $this->parentEventAccess = json_encode($parent->getAccessNames(array(Access::LEVEL_ROLE)));
            $this->parentAccesses = $parent->getAccessNames(array(Access::LEVEL_ROLE), false, true);

            if ($parent->narrow == Role::LEVEL_PARENT) {
                $id = $parent->getParentAccessRecursively($parent->id);
                $parent = Role::model()->findByPk($id);
            }
            $this->parentIsAdmin = $parent->name == "admin";
            $controllerName = ucfirst($access[1-$i])."Controller";
            $controller = $controllerName::accessFilters();

            $key = false;
            $action = false;
            foreach ($controller as $k => $a) {
                if ($k == $access[2-$i]) {
                    $key = $k;
                    $action  = $a;
                    break;
                }
            }
            $result = $this->renderPartial("table", array(
                "module"=>!$i ? $access[0]:false,
                "controller"=>$controllerName,
                "action"=>$key,
                "params"=>$action['params'],
                "type"=>isset($action['type'])? $action['type']:""),
                !$page, true);
            if (!$page)
                echo json_encode($result);
        }
	}

    public function getWidget($type, $name, $data=array(), $options=array(), $select="")
    {
        switch($type) {
            case Access::TYPE_CHECKBOX:
                echo CHtml::checkBox($name, $select, array_merge($options, array("class"=>"access_action")));
                break;
            case Access::TYPE_CHECKBOXLIST:
                echo CHtml::checkBoxList($name, $select, $data,  array_merge($options, array("class"=>"access_action")));
                break;
            case Access::TYPE_RADIOLIST:
                echo CHtml::radioButtonList($name, $select, $data);
                break;
            default:
                return false;
        }
    }

    /**
     * @param $params
     * @param $type
     * @return CArrayDataProvider
     */
    public function getTable($params, $type, $action, $id) {

        $isUser = $this->model instanceof User;
        $events = CHtml::listData($this->role->getParentEventsRecursively($action, $this->parentIsAdmin, $params, $id, $isUser), "id", function($event){
            return "<b>".$event->name."</b><br/>".$event->scheme->location->city->name."<br/>".$event->startTime;
        });
        $result = [];
        foreach ($events as $k=>$event) {
            $result[] = array("id"=>$k, "name"=>$event, "params"=>$params, "type"=>$type);
        }
        return new CArrayDataProvider($result, array(
            "pagination"=>array(
                "pageSize"=>25,
                "params"=>array(
                    "type"=>$isUser ? Access::LEVEL_USER : Access::LEVEL_ROLE,
                    "access"=>$action,
                    "id"=>$isUser ? $this->model->id: $this->role->id,
                    "role_id"=>$this->role->id,
                    "pager"=>true
                )
            )
        ));
    }
}
