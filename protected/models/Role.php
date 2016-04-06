<?php

/**
 * This is the model class for table "{{role}}".
 *
 * The followings are the available columns in table '{{role}}':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $description
 * @property integer $entity
 * @property string $legal_detail
 * @property string $date_add
 * @property integer $status
 * @property integer $code_yerdpou
 * @property string $company_name
 * @property string $post
 * @property string $real_name
 *
 * The followings are the available model relations:
 * @property Access[] $accesses
 * @property RoleChild[] $roleChildren
 * @property TemplateRole[] $roleTemplates
 * @property RoleChild[] $roleChildren1
 * @property RoleModel[] $roleModels
 * @property UserRole[] $userRoles
 */
class Role extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const TYPE_ADMIN = 1;
	const TYPE_USER = 0;
	const TYPE_ONE = 1;
	const TYPE_MANY = 0;
	const ENTITY_ROLE = 1;
	const LEVEL_SELF = 1;
    const LEVEL_PARENT = 0;
	public static $type = array(
		self::TYPE_ONE => "Один",
		self::TYPE_MANY => "Безліч"
	);
	public $_rules;
	public $_models = array();
	public $parent_id;
	public $admin_id = array();
	public $templatesList = array();
    public $narrow;

	public static function getRoleAdminAccesses($accesses)
	{
		$result = array();
		if (Yii::app()->user->isAdmin) {
			$metadata = Yii::app()->metadata;
			$modules = $metadata->modules;
			foreach ($modules as $module) {
				$controllers = array_values($metadata->getControllers($module));
				foreach ($controllers as $controller)
					$result[] = $controller;
			}
		} else {
			$role = self::model()->findByPk(self::getRoleId(Yii::app()->user->role));
			$result = $role->getAccesses();
		}

		foreach ($result as $k=>$controller)
			if (in_array($controller, $accesses))
				unset($result[$k]);

		return array_unique(array_filter($result));

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Role the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function getRoleId($name)
	{
		return Yii::app()->db->createCommand()
			->select("id")
			->from(self::model()->tableName())
			->where("name=:name", array(":name" => $name))
			->queryScalar();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{role}}';
	}

	public static function getRoleList($legal=false)
	{
		if($legal)
			return CHtml::listData(self::model()->findAllByAttributes(["entity"=>self::ENTITY_ROLE]), "id", "name");
		return CHtml::listData(self::model()->findAll(), "id", "name");
	}

    public static function getInArray($param,$accesses, $compareName=false)
    {
        if ($compareName)
            $lastpos = strrpos(substr($param, 0, strpos($param, "Controller")), "/");
        else
            $lastpos = strrpos(substr($param, 0, strpos($param, "Controller")), "[");

        $param[$lastpos+1] = lcfirst($param[$lastpos+1]);
        $str = str_replace("Controller", "", $param);
        $accesses = json_decode($accesses);
        return is_array($accesses)&&in_array($str, $accesses);
    }

	public function getAccesses()
	{
		$result = array();
		foreach ($this->accesses as $access)
			$result[] = explode('/', $access->action);

		$result = array_map(function ($access) {
			foreach ($access as $action)
				if (strstr($action, "Controller"))
					return ucfirst($action);
			return false;
		}, $result);

		return array_unique($result);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, parent_id', 'required'),
			array('name', 'unique'),
			array('entity, status, parent_id, code_yerdpou', 'numerical', 'integerOnly' => true),
			array('name, short_name', 'length', 'max' => 45),
			array('company_name, code_yerdpou, post, real_name','validateEntity'),
			array('description, legal_detail, templatesList, company_name, code_yerdpou, admin_id, post, real_name', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_name, description, entity, legal_detail, status, roleTemplates, date_add, company_name, code_yerdpou, post, real_name', 'safe', 'on' => 'search'),
		);
	}

	public function validateEntity($attribute)
	{
		if($this->entity == self::ENTITY_ROLE){
			$labels = $this->attributeLabels();
			if($this->real_name == null ){
				$attribute = 'real_name';
				$this->addError($attribute,"$labels[$attribute] не може бути не заповненим");
			}
			elseif($this->code_yerdpou == null){
				$attribute = 'code_yerdpou';
				$this->addError($attribute,"$labels[$attribute] не може бути не заповненим");
			}
			elseif($this->post == null){
				$attribute = 'post';
				$this->addError($attribute,"$labels[$attribute] не може бути не заповненим");
			}
			elseif($this->company_name == null){
				$attribute = 'company_name';
				$this->addError($attribute,"$labels[$attribute] не може бути не заповненим");
			}
		}
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'short_name' => 'Коротка назва',
			'description' => 'Опис',
			'entity' => 'Юридична одиниця',
			'legal_detail' => 'Юридичні реквізити',
			'status' => 'Активність',
			'parent_id' => 'Батьківська роль',
			'date_add' => 'Дата створення',
			'company_name' => 'Найменування підприємства, організації, установи',
			'code_yerdpou' => 'Ідентифікаційний код ЄДРПОУ ',
			'post' => 'Посада відповідальної особи',
			'real_name' => 'Прізвище, ініціали',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'accesses' => array(self::HAS_MANY, 'Access', 'role_id'),
			'roleChildren' => array(self::MANY_MANY, 'Role', '{{role_child}}(parent, child)'),
			'roleChildren1' => array(self::MANY_MANY, 'Role', '{{role_child}}(child,parent)'),
			'roleModels' => array(self::HAS_MANY, 'RoleModel', 'role_id'),
			'userRoles' => array(self::MANY_MANY, 'User', '{{user_role}}(role_id,user_id)'),
			'roleTemplates' => array(self::MANY_MANY, 'TemplateRole', '{{role_template}}(role_id,template_id)'),
			'quotes' => array(self::HAS_MANY, 'Quote', 'role_to_id')
		);
	}

	public function afterFind()
	{
		parent::afterFind();
		if (!empty($this->roleChildren1)) {
            $this->parent_id = $this->roleChildren1[0]->id;
            $this->narrow = Yii::app()->db->createCommand()
                ->select("narrow")
                ->from("{{role_child}}")
                ->where("child=:id", array(
                    ":id"=>$this->id
                ))
                ->queryScalar();
        }
		$this->admin_id = Yii::app()->db->createCommand()
			->select("user_id")
			->from("{{user_role}}")
            ->where("role_id=:id AND is_admin=:is_admin",
                    array(
                        ":id"=>$this->id,
                        ":is_admin"=>self::TYPE_ADMIN
                    )
                )
            ->queryColumn();
		foreach ($this->roleTemplates as $template) {
			$this->templatesList[] = $template->id;

		}
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if (!$this->isNewRecord)
				$this->deleteRoleTemplates();
			$this->roleTemplates = $this->templatesList;
			return true;
		} else
			return false;
	}

	private function deleteRoleTemplates()
	{
		return Yii::app()->db->createCommand()
			->delete("{{role_template}}", "role_id=:id", array(
				":id" => $this->id
			));
	}

	public function afterSave()
	{
		parent::afterSave();
		if (!$this->isNewRecord) {
            if (!empty($this->_rules)&&$this->narrow == self::LEVEL_SELF) {
                $this->deleteAccesses();
                $this->deleteUserAccess();
            }
			$this->deleteParent();

		}

		/*if (!empty($this->_rules)&&$this->narrow == self::LEVEL_SELF) {
			$this->saveAccessRecursively();
			$this->deleteChildAccessRecursively($this->getAccessActions());
		}*/
		Yii::app()->db->createCommand()
			->delete("{{user_role}}", "is_admin=:is_admin AND role_id=:id", array(
				":id" => $this->id,
				":is_admin" => self::TYPE_ADMIN
		));
		if (!empty($this->admin_id)) {
			$query = array();

			foreach ($this->admin_id as $id) {
				$query[] = "(" . implode(",", array(
						"user_id" => $id,
						"role_id" => $this->id,
						"is_admin" => self::TYPE_ADMIN
					)) . ")";
			}
			$sql = "INSERT INTO {{user_role}} (user_id, role_id, is_admin) VALUES " . implode(",", $query);
			Yii::app()->db->createCommand($sql)->execute();

		}
		if ($this->parent_id) {
			Yii::app()->authManager->addItemChild($this->parent_id, $this->id);

            Yii::app()->db->createCommand()
                ->update("{{role_child}}", array("narrow"=>$this->narrow), "child=:id", array(
                    ":id"=>$this->id
                ));
		}

		return true;
	}

	private function deleteAccesses()
	{
        $ids = Yii::app()->db->createCommand()
            ->select("id")
            ->from("{{access}}")
            ->where("role_id=:id", array(
                ":id"=>$this->id
            ))
            ->queryColumn();
        Yii::app()->db->createCommand()
            ->delete("{{access_event}}", array("in", "access_id", $ids));
		return Yii::app()->db->createCommand()
			->delete("{{access}}", "role_id=:id", array(
				":id" => $this->id
			));
	}

	private function deleteUserAccess()
	{
		return Yii::app()->db->createCommand()
			->delete("{{user_role}}", "role_id=:id AND type=:type", array(
				":id" => $this->id,
				":type" => Role::TYPE_ADMIN
			));
	}

	private function deleteParent()
	{
		return Yii::app()->db->createCommand()
			->delete("{{role_child}}", "child=:id", array(
				":id" => $this->id
			));
	}

	public function saveAccessRecursively($rules = array(), $hasEvents=false)
	{
		$actions = array();
		if (!empty($this->accesses))
			foreach ($this->accesses as $access)
				$actions[] = $access->action;

		$result = array(
            "access"=>"",
            "events"=>array()
        );

		if (empty($rules))
			$rules = $this->_rules;

		foreach ($rules as $module => $controllers) {
            if (strstr($module, "Controller")) {
                $result['access'] .= ($result['access']!='' ? ', ': '').$this->getResultSql($controllers, $module)["access"];
                $result['events'][] = $this->getResultSql($controllers, $module)["events"];
            }
            else
                foreach ($controllers as $controller => $actions) {
                    $result['access'] .= ($result['access']!='' ? ', ': '').$this->getResultSql($actions, $controller, $module)["access"];
                    $result['events'][] = $this->getResultSql($actions, $controller, $module)["events"];
                }

        }
		$sql = "INSERT INTO {{access}} (role_id, action, type, level, `condition`, allow_action) VALUES " . $result["access"];
        if (!$hasEvents)
		    Yii::app()->db->createCommand($sql)->execute();

        $event_ids = array();

        foreach ($result["events"] as $events) {
            foreach ($events as $columns) {
                $i=2;
                $temp = "";
                foreach ($columns as $k=>$column) {
                    if ($k != "event_id")
                        $temp .= " `$k` = $column ";
                    if (count($columns)>$i)
                        $temp .= "AND";
                    $i++;
                }
                $event_ids[$columns['event_id']][] = "(SELECT id FROM {{access}} WHERE ".$temp.")";
            }
        }

        $result = "";
        foreach ($event_ids as $event_id=>$query) {
            foreach ($query as $item) {
                $result[] = "(".implode(",", array($item, $event_id)).")";
            }

        }
		if ($result!="") {
			$sql = "INSERT INTO {{access_event}} (access_id, event_id) VALUES ".implode(",", $result);
			Yii::app()->db->createCommand($sql)->execute();
		}
//		if (isset($this->parent_id)) {
//			$newParent = Role::model()->with(array('accesses' => array("select" => "action")))->findByPk($this->parent_id, "name<>:admin", array(
//				":admin" => "admin"
//			));
//			if ($newParent && $newParent->saveAccessRecursively($rules))
//				return true;
//		}
		return true;
	}

    public function getResultSql($actions, $controller, $module=false)
    {
        $result = "";
        $events = array();
        Yii::import("application.controllers.*");
        Yii::import("application.widgets.accessList.*");
        if ($module)
            Yii::import("application.modules.".$module.".controllers.*");
        foreach ($actions as $action => $key) {
            $allow_actions = $controller::accessFilters();
            $allow_actions = isset($allow_actions[$action]['params']['access']['allow_actions']) ? $allow_actions[$action]['params']['access']['allow_actions']: array();

            $actionName = $module ? '"/' . $module . "/" . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst($action) . '"' : '"/' . lcfirst(str_replace("Controller","",$controller)) . "/" . lcfirst($action) . '"';
            if (is_array($key)) {
                foreach ($key as $k=>$item) {
                    if ($result != "")
                        $result .= ",";
                    $result .= "(" . implode(",", array(
                            "role_id" => $this->id,
                            "action" => $actionName,
                            "type"=>isset($item['event_id'])?Access::TYPE_EVENT:0,
                            "level"=>Access::LEVEL_ROLE,
                            "condition" => "\"$k\"",
                            "allow_action" => "'".json_encode($allow_actions)."'"
                        )) . ")";
                    if (isset($item['event_id'])) {
                        foreach ($item['event_id'] as $event_id=>$event) {
                            $events[] = array(
                                "role_id" => $this->id,
                                "action" => $actionName,
                                "type"=>Access::TYPE_EVENT,
                                "level"=>Access::LEVEL_ROLE,
                                "condition" => "\"$k\"",
                                "event_id"=>$event_id,
                                "allow_action" => "'".json_encode($allow_actions)."'"
                            );
                        }
                    }
                }
            } else {
                if ($result != "")
                    $result .= ",";
                $result .= "(" . implode(",", array(
                        "role_id" => $this->id,
                        "action" => $actionName,
                        "type"=>0,
                        "level"=>Access::LEVEL_ROLE,
                        "condition"=>"NULL",
                        "allow_action" => "'".json_encode($allow_actions)."'"
                    )) . ")";
            }

        }
        return array("access"=>$result, "events"=>$events);
    }

	/**
	 * @param $accesses array Accesses
	 * @var $childrens Role[]
	 * @return bool
	 */
	public function deleteChildAccessRecursively($accesses)
	{
		$childrens = $this->roleChildren;
		foreach ($childrens as $children) {
			$result = array();
			$newAccesses = $children->getAccessActions();

			foreach ($newAccesses as $access)
				if (!in_array($access, $accesses))
					$result[] = "(" . implode(' AND ', array(
							"role_id=" . $children->id,
							"action='" . $access . "'"
						)) . ")";
			if (!empty($result)) {
				$sql = "DELETE FROM " . Access::model()->tableName() . " where " . implode(" OR ", $result);
				Yii::app()->db->createCommand($sql)->execute();
			}
			if (!empty($children->roleChildren) && $children->deleteChildAccessRecursively($accesses))
				return true;
		}
		return true;
	}

//	public function defaultScope() {
//		return array(
//			'condition'=>"name<>'admin'"
//		);
//	}

	public function getAccessActions()
	{
		return Yii::app()->db->createCommand()
			->select("action")
			->from(Access::model()->tableName())
			->where("role_id=:id", array(
				":id" => $this->id
			))
			->queryColumn();
	}

    public function getAbsoluteParentRecursively($id, $prev=0)
    {
        if($prev == 0)
            $prev = $id;

        $parent = Yii::app()->db->createCommand()
            ->select("parent")
            ->from("{{role_child}}")
            ->where("child=:id", array(
                ":id"=>$id
            ))
            ->queryColumn();

        if (!empty($parent))
            $id = $this->getAbsoluteParentRecursively(current($parent), $id);
        else
            return $prev;

        return $id;
    }

    public function getAllContractor($id,$role_id=0)
    {
        $contractors = array();
        $roles = ($role_id)?array($role_id => Role::getRoleName($role_id)):User::model()->findByPk($id)->getUserRoles();
        foreach ($roles as $role_id => $role) {
            if (Role::getRoleIsEntity($role_id))
                $contractors[$role_id] = $role;
            $parent = Role::getRoleParent($role_id);
            while($parent) {
                if (Role::getRoleIsEntity($parent))
                    $contractors[$parent] = Role::getRoleName($parent);
                $parent = Role::getRoleParent($parent);
            }
        }
        return $contractors;
    }

	public static function getRoleName($id)
	{
		return Yii::app()->db->createCommand()
			->select("name")
			->from(self::model()->tableName())
			->where("id=:id", array(":id" => $id))
			->queryScalar();
	}

    public function getRoleIsEntity($id)
    {
        return Yii::app()->db->createCommand()
            ->select("entity")
            ->from("{{role}}")
            ->where("id=:id and entity=:entity", array(":id"=>$id, "entity"=>self::ENTITY_ROLE))
            ->queryScalar();
    }

    public function getRoleParent($id)
    {
        return Yii::app()->db->createCommand()
            ->select("parent")
            ->from("{{role_child}}")
            ->where("child=:id", array(":id"=>$id))
            ->queryScalar();
    }

	public function beforeDelete()
	{
		if (parent::beforeDelete()) {
			if (!empty($this->roleChildren))
				return false;
			$this->deleteParent();
			$this->deleteAccesses();
			$this->deleteUserAccess();
			return true;
		} else
			return false;
	}

	public function getUserAdmin()
	{
		return Yii::app()->db->createCommand()
			->select("user_id")
			->from("{{user_role}}")
			->where("role_id=:role_id AND is_admin=:is_admin", array(
				":role_id" => $this->id,
				":is_admin" => self::TYPE_ADMIN
			))
			->queryColumn();
	}

    /**
     * @param $level
     * @param User|false $user
     * @return array
     */
    public function getAccessNames($level, $user = false, $names=false)
    {
        $result = array();

        $isNarrow = false;
        if ($user) {
            $isNarrow = (int)$user->getIsRoleAdmin($this->id) == Role::LEVEL_SELF;
            if (!$isNarrow)
                $level = array(Access::LEVEL_ROLE, Access::LEVEL_USER);
        }

        $id = $this->id;
        if ($this->narrow == self::LEVEL_PARENT && !$isNarrow)
            $id = $this->getParentAccessRecursively($this->parent_id);
        $andWhere ="role_id=:id";
        $andWhereParams = array(":id"=>$id);
        if ($isNarrow) {
            $andWhere = "user_id=:user_id OR role_id=:role_id";
            $andWhereParams = array(
                ":user_id"=>$user->id,
                ":role_id"=>$id
            );
        }

        $accesses = Yii::app()->db->createCommand()
            ->select("action, type, condition, event_id")
            ->leftJoin("{{access_event}} ae", "ae.access_id=t.id")
            ->from("{{access}} t")
            ->where($andWhere, $andWhereParams)
            ->andWhere(array("in", "level", $level))
            ->queryAll();

        foreach ($accesses as $access) {
            if ($names) {
                $result[] = $access['action'];
                continue;
            }
            $routes = array_filter(explode("/", $access['action']));

            $action = "[";
            foreach ($routes as $route)
                    $action .= ($action!="[" ? "[" :"").$route ."]";

            if (!$names)
                $result[] = "Access".$action."[".$access["condition"]."]".($access['type'] == Access::TYPE_EVENT && $access['event_id']? "[event_id][".$access['event_id']."]":"");
        }
        if (empty($result))
            return $id;

        return $result;
    }

    public function getParentAccessRecursively($id)
    {
        $parent = Yii::app()->db->createCommand()
            ->select("parent, narrow")
            ->from("{{role_child}}")
            ->where("child=:id", array(
                ":id"=>$id
            ))
            ->queryRow();
        if (!$parent)
            return $id;
        elseif ($parent['narrow'] == self::LEVEL_PARENT)
            return $this->getParentAccessRecursively($parent['parent']);
        else
            return $id;

    }

    public function getParentEventsRecursively($action, $parentIsAdmin=false, $params=array(), $condition, $user = false)
    {
        Yii::import("application.modules.configuration.models.*");
        Yii::import("application.modules.location.models.*");
        if ($parentIsAdmin)
            return Event::model()->with("scheme.location.city")->findAll();
        if (!$user)
            $id = $this->getParentAccessRecursively($this->parent_id);
        else
            $id = $this->id;
        $conditions = array();
        foreach ($params as $k=>$param)
            $conditions[] = $condition.$k;
        $eventIds = Yii::app()->db->createCommand()
            ->select("event_id")
            ->join("{{access_event}} ae", "ae.access_id=t.id")
            ->from("{{access}} t")
            ->where("action=:action AND role_id=:role_id AND level=:level",
                array(
                    ":action"=>$action,
                    ":role_id"=>$id,
                    ":level"=>Access::LEVEL_ROLE
                ))
            ->andWhere(array("in", "condition", $conditions))
            ->queryColumn();
		$events = Yii::app()->cache->get($action."role_id".$id.serialize($eventIds));

		if (!$events) {
			$events = Event::model()->with("scheme.location.city")->findAllByAttributes(array(
				"id"=>$eventIds
			));
			Yii::app()->cache->set($action."role_id".$id.serialize($eventIds),$events, 6000);
		}

		return $events;
    }

	public function getParentList()
	{
        $criteria = new CDbCriteria();
        $childs = $this->getChildrenRecursively($this->id);
        $criteria->addNotInCondition("id", $childs);
		return CHtml::listData(self::model()->findAll($criteria), "id", "name");
	}



	public static function getRoleAccess($id=false)
	{
		if (Yii::app()->user->isAdmin)
			return array();
		if (!User::isAdminOfRole())
			throw new CHttpException(403);

		$ids = Role::model()->getChildrenRecursively(Yii::app()->user->currentRoleId);
		if ($id&&!in_array($id, $ids))
			throw new CHttpException(403);
		return $ids;
	}

    public function getChildrenRecursively($id, $result=array())
    {

        $childs = Yii::app()->db->createCommand()
            ->select("child")
            ->from("{{role_child}}")
            ->where("parent=:id", array(
                ":id"=>$id
            ))
            ->queryColumn();

        $result[] = $id;
        if ($childs)
            foreach ($childs as $child)
                $result = $result+$this->getChildrenRecursively($child, $result);

        return $result;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('short_name', $this->short_name, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('entity', $this->entity);
		$criteria->compare('legal_detail', $this->legal_detail, true);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function behaviors()
	{
		return array(
			'ManyManyBehavior' => array(
				'class' => 'application.extensions.many_many.ManyManyBehavior'
			)
		);
	}

	public function getAccessList()
	{
        $result = array();
        $metadata = Yii::app()->metadata;
        $modules = $metadata->modules;
        foreach ($modules as $module) {
			Yii::import("application.modules.".$module.".controllers.*");
            $controllers = array_values($metadata->getControllers($module));
            $result = array_merge($result, self::getFilters($controllers, $module));

        }
        Yii::import("application.controllers.*");
        $controllers = array_values($metadata->getControllers());
        $key = array_search("ApiController", $controllers);
        unset($controllers[$key]);
        $key = array_search("InstallController", $controllers);
        unset($controllers[$key]);
		$filters = self::getFilters($controllers);

        return array_merge($result, $filters);
	}

    public static function getFilters($controllers, $module=false)
    {
        $result = array();

        foreach ($controllers as $controller) {
            $filters = $controller::accessFilters();
            if (!empty($filters))
                if ($module)
                    $result[$module][$controller] = $filters;
                else
                    $result[$controller] = $filters;
        }
        return $result;
    }
}
