<?php

/**
 * This is the model class for table "{{ticket_temp}}".
 *
 * The followings are the available columns in table '{{ticket_temp}}':
 * @property integer $id
 * @property integer $place_id
 * @property integer $order_temp_id
 * @property double $price
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property OrderTemp $orderTemp
 * @property Place $place
 */
class TicketTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ticket_temp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('place_id, order_temp_id', 'required'),
			array('place_id, order_temp_id, status', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, place_id, order_temp_id, price, status', 'safe', 'on'=>'search'),
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
			'orderTemp' => array(self::BELONGS_TO, 'OrderTemp', 'order_temp_id'),
			'place' => array(self::BELONGS_TO, 'Place', 'place_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'place_id' => 'Place',
			'order_temp_id' => 'Order Temp',
			'price' => 'Price',
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
		$criteria->compare('place_id',$this->place_id);
		$criteria->compare('order_temp_id',$this->order_temp_id);
		$criteria->compare('price',$this->price);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TicketTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
