<?php

/**
 * This is the model class for table "{{access}}".
 *
 * The followings are the available columns in table '{{access}}':
 * @property integer $id
 * @property integer $role_id
 * @property integer $type
 * @property integer $level
 * @property integer $user_id
 * @property string $action
 * @property string $condition
 * @property string $allow_action
 *
 * The followings are the available model relations:
 * @property Role $role
 */
class Access extends CActiveRecord
{

	const TYPE_TABS = 1;
	const TYPE_CHECKBOX = 2;
	const TYPE_RADIOLIST = 3;
	const TYPE_CHECKBOXLIST = 4;

	const TYPE_EVENT = 1;

	const LEVEL_USER = 1;
	const LEVEL_ROLE = 0;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Access the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{access}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id, action', 'required'),
			array('role_id, user_id, type, level', 'numerical', 'integerOnly'=>true),
			array('action, condition', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, role_id, action', 'safe', 'on'=>'search'),
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
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
			'events' => array(self::MANY_MANY, 'Event', '{{access_event}}(access_id,event_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_id' => 'Role',
			'action' => 'Action',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('action',$this->action,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
