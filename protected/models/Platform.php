<?php

/**
 * This is the model class for table "{{platform}}".
 *
 * The followings are the available columns in table '{{platform}}':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $partner_id
 * @property integer $status
 * @property integer $role_id
 * @property string $description
 *
 * The followings are the available model relations:
 * @property Role $role
 * @property PlatformPlace[] $platformPlaces
 */
class Platform extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{platform}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, partner_id, role_id', 'required'),
			array('type, partner_id, status, role_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, partner_id, status, role_id, description', 'safe', 'on'=>'search'),
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
			'places' => array(self::MANY_MANY, 'Place', '{{platform_place}}(platform_id, place_id)'),
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
			'type' => 'Тип',
			'partner_id' => 'Токен',
			'status' => 'Статус',
			'role_id' => 'Гравець',
			'description' => 'Опис',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('partner_id',$this->partner_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('description',$this->description);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Platform the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getEvents()
	{
		return Yii::app()->db->createCommand()
			->selectDistinct("event_id")
			->from(Place::model()->tableName()." p")
			->join(PlatformPlace::model()->tableName()." pp ON pp.place_id=p.id")
			->where("pp.platform_id=:id", array(":id"=>$this->id))
			->queryColumn();

	}

	public function getPlaces()
	{
		return Yii::app()->db->createCommand()
			->selectDistinct("place_id")
			->from(PlatformPlace::model()->tableName()." pp")
			->where("pp.platform_id=:id", array(":id"=>$this->id))
			->queryColumn();
	}

	public function getToken()
	{
		$tokens = Yii::app()->db->createCommand()
			->select("partner_id")
			->from(self::model()->tableName())
			->queryColumn();
		$token = $this->generateToken();
		while(in_array($token,$tokens)) {
			$token = $this->generateToken();
		}
		return $token;
	}

	private function generateToken()
	{
		return mt_rand(1000000, 9999999);
	}
}
