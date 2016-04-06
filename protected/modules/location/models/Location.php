<?php

/**
 * This is the model class for table "{{location}}".
 *
 * The followings are the available columns in table '{{location}}':
 * @property integer $id
 * @property string $name
 * @property string $short_name
 * @property string $sys_name
 * @property integer $location_category_id
 * @property integer $city_id
 * @property string $address
 * @property string $short_address
 * @property string $lat
 * @property string $lng
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property City $city
 * @property LocationCategory $locationCategory
 */
class Location extends CActiveRecord
{
	public $roleRelation;
    const STATUS_ACTIVE = 1;
    const STATUS_NO_ACTIVE = 0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{location}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, location_category_id, city_id, address, short_address', 'required', 'message'=>'Це поле не може бути порожнім.'),
			array('location_category_id, city_id, status', 'numerical', 'integerOnly'=>true),
			array('name, short_name, sys_name', 'length', 'max'=>128),
			array('lat, lng', 'length', 'max'=>18),
			array('name', 'validateName'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, short_name, sys_name, location_category_id, city_id, address, short_address, lat, lng, status', 'safe', 'on'=>'search'),
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
			'city' => array(self::BELONGS_TO, 'City', 'city_id'),
			'locationCategory' => array(self::BELONGS_TO, 'LocationCategory', 'location_category_id'),
			'scheme' => array(self::HAS_MANY, 'Scheme', 'location_id')
		);
	}

	public function validateName($attribute, $params)
	{
		if(self::model()->exists("name=:name AND city_id=:city_id AND id!=:id", array(":name"=>$this->name, ":city_id"=>$this->city_id, ":id"=> !$this->isNewRecord ? $this->id : "0")))
			$this->addError($attribute, "Локація з такою назвою вже існує") ;
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
			'sys_name' => 'Системна назва',
			'location_category_id' => 'Тип локації',
			'city_id' => 'Населений пункт',
			'address' => 'Адреса',
			'short_address' => 'Коротка адреса',
			'lat' => 'Широта',
			'lng' => 'Довгота',
			'status' => 'Активна',
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
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('sys_name',$this->sys_name,true);
		$criteria->compare('location_category_id',$this->location_category_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('short_address',$this->short_address,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Location the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'ManyManyBehavior'=>array(
				'class' => 'application.extensions.many_many.ManyManyBehavior'
			),
			'BeforeDeleteBehavior'=>array(
				'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
			),
		);
	}


    public function getStatus()
    {
        switch($this->status) {
            case self::STATUS_ACTIVE:
                return "Локація активна";
            break;
            case self::STATUS_NO_ACTIVE:
                return "Локація не активна";
            break;
            default:
                return "";
                break;
        }
    }

    public static function getName()
    {
        return "Локація";
    }
}
