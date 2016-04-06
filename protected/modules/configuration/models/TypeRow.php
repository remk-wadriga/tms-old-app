<?php

/**
 * This is the model class for table "{{type_row}}".
 *
 * The followings are the available columns in table '{{type_row}}':
 * @property integer $id
 * @property string $name
 * @property integer $status
 */
class TypeRow extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_NO_ACTIVE = 0;

	public static $status = array(
		self::STATUS_NO_ACTIVE=>"Неактивний",
		self::STATUS_ACTIVE=>"Активний"
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{type_row}}';
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
			array('status', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('name', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, status', 'safe', 'on'=>'search'),
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
			'sectors' => array(self::HAS_MANY, 'Sector', 'type_row_id'),
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
			'status' => 'Активність',
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
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TypeRow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'BeforeDeleteBehavior'=>array(
				'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
			),
		);
	}

	/**
	 * @param $name String name of type row
	 * @return int Integer id of type row
	 */
	public static function getTypeIdByName($name)
	{
		$id = Yii::app()->db->createCommand()
			->select("id")
			->from("{{type_row}}")
			->where("name=:name", array(":name"=>$name))
			->queryScalar();
		if (!$id) {
			$model = new self();
			$model->name = $name;
			$model->status = self::STATUS_ACTIVE;
			if ($model->save()) {
				$model->refresh();
				return $model->id;
			}
		} else
			return $id;
	}


	public static function getTypes()
	{
		return CHtml::listData(self::model()->findAllByAttributes(array("status"=>self::STATUS_ACTIVE)), "id", "name");
	}

	public static function getName()
	{
		return "Тип ряда";
	}

}
