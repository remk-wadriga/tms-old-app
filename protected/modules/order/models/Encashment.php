<?php

/**
 * This is the model class for table "{{encashment}}".
 *
 * The followings are the available columns in table '{{encashment}}':
 * @property integer $id
 * @property string $date_create
 * @property string $sum
 * @property integer $collector_id
 * @property integer $event_id
 *
 * The followings are the available model relations:
 * @property CashierEncashment[] $cashierEncashments
 * @property Event $event
 * @property User $collector
 */
class Encashment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{encashment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_create, sum, collector_id', 'required'),
			array('collector_id, event_id', 'numerical', 'integerOnly'=>true),
			array('sum', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, date_create, sum, collector_id, event_id', 'safe', 'on'=>'search'),
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
			'cashiers' => array(self::HAS_MANY, 'CashierEncashment', 'encashment_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'collector' => array(self::BELONGS_TO, 'User', 'collector_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_create' => 'Дата ',
			'sum' => 'Sum',
			'collector_id' => 'Collector',
			'event_id' => 'Event',
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
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('collector_id',$this->collector_id);
		$criteria->compare('event_id',$this->event_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Encashment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
