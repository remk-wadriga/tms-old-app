<?php

/**
 * This is the model class for table "{{template_role}}".
 *
 * The followings are the available columns in table '{{template_role}}':
 * @property integer $id
 * @property string $name
 * @property string $sys_name
 * @property string $description
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property TemplateRoleAccess[] $templateRoleAccesses
 * @property Role[] $templateRoles
 */
class TemplateRole extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{template_role}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, sys_name', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('name, sys_name', 'length', 'max'=>45),
			array('description, _models', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, sys_name, description, status', 'safe', 'on'=>'search'),
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
			'templateRoleAccesses' => array(self::HAS_MANY, 'TemplateRoleAccess', 'template_role_id'),
//			'templateRoleModels' => array(self::HAS_MANY, 'TemplateRoleModel', 'template_role_id'),
			'templateRoles' => array(self::MANY_MANY, 'Role', '{{role_template}}(template_id,role_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'sys_name' => 'Системна назва',
			'description' => 'Опис',
			'status' => 'Активність',
		);
	}

	public $_models = array();
    public $_rules = array();
	public function afterSave() {
		parent::afterSave();
        if (!$this->isNewRecord) {
			$this->deleteAccesses();
			$this->deleteRelatedModels();
		}

        if (!empty($this->_rules)) {
            $result = "";
            foreach ($this->_rules as $module => $controllers)
                foreach ($controllers as $controller => $actions)
                    foreach ($actions as $action => $key) {
                        if ($result != "")
                            $result .= ",";
                        $result .= "(" . implode(",", array(
                                "action" => '"/' . $module . "/" . lcfirst($controller) . "/" . lcfirst($action).'"',
                                "template_role_id" => $this->id
                            )) . ")";
                    }
            $sql = "INSERT INTO {{template_role_access}} (action, template_role_id) VALUES ".$result;
            Yii::app()->db->createCommand($sql)->execute();
        }
		if (!empty($this->_models)) {
			$result = array();
			foreach ($this->_models as $k=>$model)
				if (isset($model[1]))
					$result[] = "(".implode(",", array(
							"model"=>"'$k'",
							"template_role_id"=>$this->id,
							"type"=>$model['type']
						)).")";

			if (!empty($result)) {
				$sql = "INSERT INTO {{template_role_model}} (model, template_role_id, type) VALUES ".implode(",", $result);
				Yii::app()->db->createCommand($sql)->execute();
			}
		}
        return true;
	}

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
			if (!empty($this->templateRoles))
				return false;
            $this->deleteAccesses();
			$this->deleteRelatedModels();
            return true;
        } else
            return false;
    }

    private function deleteAccesses()
    {
        return Yii::app()->db->createCommand()
            ->delete("{{template_role_access}}", "template_role_id=:id", array(
                ":id"=>$this->id
            ));
    }

	private function deleteRelatedModels()
	{
		return Yii::app()->db->createCommand()
			->delete("{{template_role_model}}", "template_role_id=:id", array(
				":id"=>$this->id
			));
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

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('sys_name',$this->sys_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TemplateRole the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getTemplates()
	{
		$templates = Yii::app()->db->createCommand()
			->select("id, name")
			->from(TemplateRole::model()->tableName())
			->where("status=:status", array(":status"=>TemplateRole::STATUS_ACTIVE))
			->queryAll();
		$keys = array();
		$values = array();

		foreach ($templates as $template) {
			$keys[] = $template['id'];
			$values[] = $template['name'];
		}

		return array_combine($keys, $values);
	}

    public function getTemplateRoleModelType($modelName)
    {
        $result = Yii::app()->db->createCommand()
            ->select("type")
            ->from("{{template_role_model}}")
            ->where("model=:modelName AND template_role_id=:id", array(
                ":modelName"=>$modelName,
                ":id"=>$this->id
            ))
            ->queryColumn();
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }

	public function getTemplateRoleModels()
	{
		return Yii::app()->db->createCommand()
			->select("model, type")
			->from("{{template_role_model}}")
			->where("template_role_id=:id", array(
				":id"=>$this->id
			))
			->queryAll();
	}

    public function getRoleIds()
    {
        $result = Yii::app()->db->createCommand()
            ->select("role_id")
            ->from("{{role_template}}")
            ->where("template_id=:id", array(
                ":id"=>$this->id
            ))
            ->queryColumn();
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }


}
