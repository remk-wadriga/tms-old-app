<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $surname
 * @property string $name
 * @property string $patr_name
 * @property string $phone
 * @property string $description
 * @property string $email
 * @property string $reg_date
 * @property string $role
 * @property integer $status
 * @property integer $type
 * @property integer $np_id
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Role[] $roles
 * @property SocNetworkUser $socUser
 */
class User extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_NO_ACTIVE = 0;

	const ACCESS_ALL = 1;
	const ACCESS_SELECTED = 0;

	const BLANK_ROTATE = 1;
	const BLANK_NOT_ROTATE = 0;

	const TYPE_ADD = 1;
	const TYPE_REMOVE = 0;

	const TYPE_SOC_USER = 1;
	const TYPE_USER = 0;
	const TYPE_ALL = 2;
	public static $userType = array(
		self::TYPE_ALL => "Усі",
		self::TYPE_USER => "Касири",
		self::TYPE_SOC_USER => "Клієнти"
	);
	public static $statusList = array(
		self::STATUS_ACTIVE=>"Активний",
		self::STATUS_NO_ACTIVE=>"Неактивний"
	);
	public $_rules;
	public $_levelAccess;
    public $_role_id;
	public $password_repeat;
	public $accesses = array();
	public $oldPassword;
	public $oldRole;
	public $events_id;
	public $user_roles;


	/**
	 * @return array
	 */
	public static function getRoleNames()
	{
		$auth = Yii::app()->authManager->getRoles();
		$newRoles = array();
		foreach ($auth as $item) {
			$newRoles[$item->name] = $item->name;
		}
		return $newRoles;
	}

	public static function getUsers()
	{
		$users = self::model()->findAllByAttributes(array("status"=>self::STATUS_ACTIVE));
		return CHtml::listData($users, "id", function($user) {
			return $user->username." ".$user->email;
		});
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getNameById($id)
	{
		$user = self::model()->findByPk($id);
		return $user->fullName;
	}

	/**
	 * @param integer $n
	 * @param array $forms
	 * @return mixed
	 */
	public static function plural_form($n, $forms)
	{
		return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
	}

	/**
	 * @param string $model
	 * @return array Models
     */
	public static function getUserFavorites($model)
	{
		$result = array();
		$ids = Yii::app()->db->createCommand()
			->select("model_id")
			->from("{{user_favorites}}")
			->where("user_id=:user_id AND model=:model", array(
				":user_id"=>Yii::app()->user->id,
				":model"=>$model
			))
			->queryColumn();
		if (!empty($ids))
			$result = $model::model()->with(array("scheme", "scheme.location", "scheme.location.city"))->findAllByPk($ids);
		return $result;
	}

	/**
	 * @param $model
	 * @param $id
	 * @return mixed
     */
	public static function deleteUserFavorites($model, $id)
	{
		return Yii::app()->db->createCommand()
			->delete("{{user_favorites}}", "user_id=:user_id AND model=:model AND model_id=:model_id", array(
					":user_id"=>Yii::app()->user->id,
					":model"=>$model,
					":model_id"=>$id
				));
	}

	/**
	 * @param $models
     */
	public static function setUserFavorites($models)
	{
		$query = array();
		if (!is_array($models))
			$models = array($models);
		foreach ($models as $model) {
			$modelName=CHtml::modelName($model);
			$query[] = implode(",", array(
					"user_id"=>Yii::app()->user->id,
					"model"=>"'".$modelName."'",
					"model_id"=>$model->id
				));
		}

		if (!empty($query)) {

			$sql = "INSERT IGNORE into {{user_favorites}} (user_id, model, model_id) VALUES (".implode(",", $query).")";
			return Yii::app()->db->createCommand($sql)->execute();
		}
	}

	/**
	 * @param $to
	 * @param $from
	 * @param $subject
	 * @param $text
     * @param $attachments
	 */
	public static function mailsend($to,$from,$subject,$text,$attachments = array()){
		$message = new YiiMailMessage;
		$message->setBody($text, 'text/html');
		$message->subject = $subject;
		$message->addTo($to);
		$message->from = $from;
		if ($attachments) {
			foreach ($attachments as $attachment) {
				$message->attach(Swift_Attachment::fromPath($attachment));
			}
		}
		Yii::app()->mail->send($message);
	}

	public static function getUserName($id)
    {
        return Yii::app()->db->createCommand()
            ->select("email")
            ->from(self::model()->tableName())
            ->where("id=:id", array(
                ":id"=>$id
            ))
            ->queryScalar();
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	public static function getUsersByRole($role_id) {
		$users = Yii::app()->db->createCommand()
				->select("id, surname, name, patr_name, email")
				->from(self::model()->tableName()." t")
				->join("{{user_role}} ur", "ur.user_id=t.id")
				->where("ur.role_id=:role_id", array(
					":role_id"=>$role_id
				))
				->queryAll();
		$result = array();
		foreach ($users as $user) {
			$result[$user['id']]=$user['surname']." ".$user['name']." ".$user['patr_name']." | ".$user['email'];
		}
		return $result;
	}

	public function getRolesList()
	{
		$result = array();
        foreach ($this->roles as $role)
            $result[] = $role->name;
        return implode(", ", $result);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email, name, surname', 'required'),
			array('username, email', 'unique'),
			array('status, type, city_id, country_id, np_id, rotate_ticket', 'numerical', 'integerOnly'=>true),
			array('name, email, phone, surname, patr_name', 'length', 'max'=>128),
			array('password, username', 'length', 'max'=>50),
			array('email', 'email'),
			array('username', 'match', 'pattern'=>'/^([0-9A-Za-z!@.]{3,}+)$/', 'message'=>'Має бути 3 або більше символів (A-Z,a-z,0-9)'),
			array('password', 'match', 'pattern'=>'/^([0-9A-Za-z!@#$%*]+)$/', 'message'=>'Недопустимий формат паролю'),
			array('password', 'match', 'pattern'=>'/^([0-9A-Za-z!@#$%*]{8,}+)$/', 'message'=>'Має бути 8 або більше символів'),
			array('phone', 'match', 'pattern'=>'/^([+]?[0-9 ]+)$/', 'message'=>'Невірний формат номеру'),
			array('password_repeat, password', 'required', 'on'=>'create'),
//			array('password', 'compare', 'on'=>'create', 'strict'=>true),
			array('password, password_repeat', 'validatePass'),
			array('salt, role', 'length', 'max'=>45),
			array('description, _rules, user_roles', 'safe'),
			array('phone', 'match', 'pattern'=>'/^([+]?[0-9 ]+)$/'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, salt, name, email, reg_date, role, status, rotate_ticket', 'safe', 'on'=>'search'),
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
			'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
			'socUser' => array(self::HAS_ONE, 'SocNetworkUser', 'user_id'),
			'roles' => array(self::MANY_MANY, 'Role', '{{user_role}}(user_id,role_id)')
		);
	}

	public function validatePass($attribute)
	{
		if($this->password != $this->password_repeat && $this->password_repeat != '') {
			$attribute = 'password_repeat';
			$this->addError($attribute, "Паролі не співпадають");
		}
	}

	/**
	 * @return bool
	 */
	public function beforeDelete()
	{
		if (parent::beforeDelete())
		{
			if ($this->id==1)
				return false;
			return true;
		} else
			return false;
	}

	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		if (parent::beforeValidate())
		{
			if (!$this->isNewRecord && $this->password == '') {
				$this->password = $this->oldPassword;
				$this->password_repeat = $this->oldPassword;
			}
			return true;
		} else
			return false;
	}

	/**
	 * @return bool
	 */
	public function beforeSave()
	{
		if (parent::beforeSave())
		{
			if ($this->isNewRecord) {
				$this->salt = self::generateRandomString(10);
				$this->password = $this->hashPassword($this->password);
			}
			if (!$this->isNewRecord&&$this->password!=$this->oldPassword) {
				$this->reg_date = $this->_reg_date;

				$this->password = $this->hashPassword($this->password);
			}

			return true;
		} else
			return false;
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public static function generateRandomString($length = 10) {
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

	/**
	 * @param $password
	 * @return string
	 */
	public function hashPassword($password)
	{
		return sha1($this->salt.$password);
	}

	public function afterSave()
	{

		parent::afterSave();
		if ($this->user_roles && ($this->id != 1 || $this->isNewRecord)) {
            $authManager = Yii::app()->authManager;
            $toDel = $this->isNewRecord ? array() : array_diff($this->oldRole, $this->user_roles);
            $toSet = array_diff($this->user_roles, $this->oldRole);

            if (!$this->isNewRecord && $this->oldRole && !empty($this->user_roles) && !empty($toDel)) {
                $old_roles = Role::model()->findAllByAttributes(array("name" => $toDel));
                foreach ($old_roles as $old_role)
                    $authManager->revoke($old_role->id, $this->id);
            }
            if ($this->user_roles && !empty($toSet)) {
                $roles = Role::model()->findAllByAttributes(array("name" => $toSet));
                foreach ($roles as $role)
                    $authManager->assign($role->id, $this->id);
            }
        }

		/*if (!$this->isNewRecord&&!empty($this->_rules))
			$this->deleteUserAccesses();

        if (!empty($this->_rules)&&$this->_levelAccess==Role::LEVEL_SELF)
            $this->setUserAccesses();*/

        /*Yii::app()->db->createCommand()
            ->update("{{user_role}}", array("type"=>$this->_levelAccess), "role_id=:role_id AND user_id=:user_id", array(
                ":role_id"=>$this->_role_id,
                ":user_id"=>$this->id
            ));*/
	}

	public function deleteUserAccesses()
	{
        $ids = Yii::app()->db->createCommand()
            ->select("id")
            ->from("{{access}}")
            ->where("user_id=:id", array(
                ":id"=>$this->id
            ))
            ->queryColumn();
        Yii::app()->db->createCommand()
            ->delete("{{access_event}}", array("in", "access_id", $ids));
        return Yii::app()->db->createCommand()
            ->delete("{{access}}", "user_id=:id", array(
                ":id" => $this->id
            ));
	}

	public function setUserAccesses($hasEvents=false)
	{
//		$query = array();
        $result = array(
            "access"=>"",
            "events"=>array()
        );
		if (!empty($this->_rules)) {
            foreach ($this->_rules as $module => $controllers) {
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

            $sql = "INSERT INTO {{access}} (role_id, user_id, action, type, level, `condition`,allow_action) VALUES " . $result["access"];
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
            foreach ($event_ids as $event_id=>$query)
                foreach ($query as $item)
                    $result[] = "(".implode(",", array($item, $event_id)).")";


            if ($result!="") {
                $sql = "INSERT INTO {{access_event}} (access_id, event_id) VALUES ".implode(",", $result);
                Yii::app()->db->createCommand($sql)->execute();
            }

//			foreach ($this->accesses as $access) {
//				$query[] = array(
//					"action"=>$access['action'],
//					"user_id"=>$this->id,
//					"role_id"=>$this->_role_id,
//					"condition"=>$access['controller']
//				);
//			}
//			if (!empty($query)) {
//				$builder = Yii::app()->db->schema->commandBuilder;
//				$command = $builder->createMultipleInsertCommand(
//					"{{access}}",
//					$query
//				);
//				$command->execute();
//			}
		}
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
                            "role_id" => $this->_role_id,
                            "user_id" => $this->id,
                            "action" => $actionName,
                            "type"=>isset($item['event_id'])?Access::TYPE_EVENT:0,
                            "level"=>Access::LEVEL_USER,
                            "condition" => "\"$k\"",
                            "allow_action" => "'".json_encode($allow_actions)."'"
                        )) . ")";
                    if (isset($item['event_id'])) {
                        foreach ($item['event_id'] as $event_id=>$event) {
                            $events[] = array(
                                "role_id" => $this->_role_id,
                                "user_id" => $this->id,
                                "action" => $actionName,
                                "type"=>Access::TYPE_EVENT,
                                "level"=>Access::LEVEL_USER,
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
                        "role_id" => $this->_role_id,
                        "user_id" => $this->id,
                        "action" => $actionName,
                        "type"=>0,
                        "level"=>Access::LEVEL_USER,
                        "condition"=>"NULL",
                        "allow_action" => "'".json_encode($allow_actions)."'"
                    )) . ")";
            }

        }
        return array("access"=>$result, "events"=>$events);
    }

	public $_reg_date;
	public function afterFind()
	{
		parent::afterFind();

		$this->oldPassword = $this->password;
		$this->_reg_date = $this->reg_date;
        $this->user_roles = CHtml::listData($this->roles, "id", "name");


//		$roles = Yii::app()->authManager->getRoles($this->id);
//		reset($roles);
//		$this->user_roles = $this->getRoleNames();
		$this->oldRole = $this->user_roles ;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Логін',
			'password' => 'Пароль',
			'salt' => 'Salt',
			'name' => 'Ім’я',
			'email' => 'Email',
			'reg_date' => 'Дата реєстрації',
			'role' => 'Роль',
			'status' => 'Статус',
			'password_repeat' => 'Повтор паролю',
			'surname' => 'Прізвище',
			'patr_name' => 'По батькові',
			'description' => 'Коментар, Опис',
			'phone' => 'Телефон',
			'type'=>'Тип',
			'city_id'=>'Місто',
			'country_id'=>'Країна',
			'address'=>'Адреса доставки до дверей',
			'np_id'=>'Номер найближчого відділення Нової пошти',
			'rotate_ticket'=>'Друк бланку'
		);
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

		$criteria=new CDbCriteria;
        if (self::isAdminOfRole()&&!Yii::app()->user->isAdmin) {

            $user_ids = $this->getAllowUserIds();

            $criteria->compare("id", array_unique($user_ids));
        }
		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('reg_date',$this->reg_date,true);
        if ($this->role!="") {
            $role = Role::model()->findByAttributes(array("name"=>$this->role));
            if (Yii::app()->user->isAdmin||in_array($role->id,$this->getAllowUserIds())) {

                $user_ids = Yii::app()->db->createCommand()
                    ->select("user_id")
                    ->from("{{user_role}}")
                    ->where("role_id=:role_id", array(
                        ":role_id"=>$role->id
                    ))
                    ->queryColumn();
                $criteria->addInCondition("id", $user_ids);
            }

        }

		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getAllowUserIds()
    {
        $ids = Role::model()->getChildrenRecursively(Yii::app()->user->currentRoleId);
        return Yii::app()->db->createCommand()
            ->select("user_id")
            ->from("{{user_role}}")
            ->where(array("in","role_id", $ids))
            ->queryColumn();
    }

	/**
	 * @param $password
	 * @return bool
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password)===$this->password;
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		switch($this->status) {
			case self::STATUS_ACTIVE:
				return "Активний";
			break;
			case self::STATUS_NO_ACTIVE:
				return "Неактивний";
			break;
			default :
				return "";
			break;
		}
	}

	public function getFullName()
	{
		return trim($this->surname." ".$this->name." ".$this->patr_name);
	}

	public function getUserRoles()
	{
        $roles = array();
        if (!Yii::app()->user->isAdmin&&User::isAdminOfRole()) {
            $ids = Role::model()->getChildrenRecursively(Yii::app()->user->currentRoleId);
            foreach ($this->roles as $role)
                if (in_array($role->id, $ids))
                    $roles[] = $role;
        } else
            $roles = $this->roles;
		return CHtml::listData($roles, "id", "name");
	}

	public function getIsRoleAdmin($role_id)
	{
		return Yii::app()->db->createCommand()
			->select("type")
			->from("{{user_role}}")
			->where("user_id=:user_id AND role_id=:role_id", array(
				":user_id"=>$this->id,
				":role_id"=>$role_id
			))->queryScalar();
	}

	public function getUserAccesses($controller)
	{
		$events = Yii::app()->db->createCommand()
			->select("ua.*, e.*")
			->from("{{user_access}} ua")
			->event(Event::model()->tableName()." e", "ua.event_id=e.id")
			->where("user_id=:user_id AND controller=:controller", array(
				":user_id"=>$this->id,
				"controller"=>$controller
			))
			->order("ua.action ASC")
			->queryAll();
		$result = array();
		$controllerActions = $controller::getParams();
		foreach ($events as $event) {
			if (!isset($result[$event['id']])) {
				$result[$event['id']] = array(
					"id"=>$event->id,
				)+$controllerActions;
			}
			$result[$event['id']][$event['action']] = true;
		}

		return $result;

	}

	public static function isAdminOfRole()
	{
		if(Yii::app()->user->isAdmin)
			return true;

		$user_id = Yii::app()->user->id;
		$role_id = Yii::app()->user->currentRoleId;

		$admin = Yii::app()->db->createCommand()
			->select("user_id")
			->from("{{user_role}}")
			->where("user_id=:user_id AND role_id=:role_id AND is_admin=:is_admin", array(
				":user_id"=>$user_id,
				":role_id"=>$role_id,
				":is_admin"=>Role::TYPE_ADMIN
			))
			->queryAll();

		if(!empty($admin))
			return true;
		else
			return false;
	}

	public static function isRotateTicket($id)
	{
        return Yii::app()->db->createCommand()
            ->select("rotate_ticket")
            ->from(self::model()->tableName())
            ->where("id=:id", array(
                ":id"=>$id
            ))
            ->queryScalar();
	}

}
