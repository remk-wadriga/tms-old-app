<?php

/**
 * This is the model class for table "{{delivery}}".
 *
 * The followings are the available columns in table '{{delivery}}':
 * @property integer $id
 * @property integer $city_id
 * @property integer $order_id
 * @property string $address
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property City $city
 * @property Order $order
 */
class Delivery extends CActiveRecord
{
	const TYPE_NP = 0;
	const TYPE_COURIER = 1;
	public $country_id;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Delivery the static model class
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
		return '{{delivery}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id,  address', 'required'),
			array('city_id,  status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, city_id, order_id, address, status', 'safe', 'on'=>'search'),
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
			'order' => array(self::HAS_ONE, 'Order', 'delivery_id'),
		);
	}

	public function afterFind()
	{
		parent::afterFind();
        $this->country_id = $this->city->country_id;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'city_id' => 'City',
			'order_id' => 'Order',
			'address' => 'Address',
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
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
