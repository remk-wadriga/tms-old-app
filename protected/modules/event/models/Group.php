<?php

/**
 * This is the model class for table "{{group}}".
 *
 * The followings are the available columns in table '{{group}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $type
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Event[] $events
 */
class Group extends CActiveRecord
{

	const TYPE_SINGLE = 0;
	const TYPE_GROUP = 1;

	public static $types = array(
		self::TYPE_SINGLE =>'Події не можуть відбуватись одночасно',
		self::TYPE_GROUP => 'Події можуть відбуватись одночасно'
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{group}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('type, status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('description', 'safe'),
			array('name', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, type, status', 'safe', 'on'=>'search'),
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
			'events' => array(self::HAS_MANY, 'Event', 'group_id'),
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
			'description' => 'Опис',
			'type' => 'Тип',
			'status' => 'Status',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>20
			)
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Group the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getGroupList()
	{
		return CHtml::listData(self::model()->findAll(), "id", "name");
	}

    public static function getName()
    {
        return "Група";
    }

}
