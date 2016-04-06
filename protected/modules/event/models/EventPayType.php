<?php

/**
 * This is the model class for table "{{event_pay_type}}".
 *
 * The followings are the available columns in table '{{event_pay_type}}':
 * @property integer $id
 * @property integer $event_id
 * @property integer $type
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class EventPayType extends CActiveRecord
{
	public static $typeLabels = [
		Order::IN_KASA_PAY => "Оплата готівкою в касі",
		Order::IN_KASA_ONLINE => "Оплата онлайн платіжною картою каса",
		Order::NP_PAY => "Оплата готівкою у відділенні нової пошти",
		Order::NP_ONLINE => "Оплата онлайн платіжною картою нова пошта",
		Order::E_ONLINE => "Оплата готівкою кур'єру",
		Order::COURIER_PAY => "Оплата онлайн платіжною картою кур'єр",
		Order::COURIER_ONLINE => "Оплата онлайн платіжною картою електронний квиток"
	];
	/**
	 * @return string the associated database table name
	 */

	public $types = [Order::IN_KASA_PAY,Order::IN_KASA_ONLINE,Order::NP_PAY,Order::NP_ONLINE,Order::E_ONLINE,
					Order::COURIER_PAY, Order::COURIER_ONLINE];

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventPayType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, type', 'required'),
			array('event_id, type, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, event_id, type, status', 'safe', 'on'=>'search'),
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
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
		);
	}

	public function findAndSetByEvent($event_id)
	{
		if($event_id) {
			$this->event_id = $event_id;
			$types = $this->findAllByAttributes(["event_id"=>$event_id]);
			if(!empty($types)){
				$this->types = [];
				foreach ($types as $pay_type){
					if (!in_array($pay_type->type,$this->types)) {
						$this->types[] = $pay_type->type;
					}
				}
			}
		}
	}

	public function savePayTypes()
	{
		$this->deleteAllByAttributes(["event_id"=>$this->event_id]);
		$types = [];
		foreach ($this->types as $type) {
			$types[] = "(" . implode(",", array(
				"event_id" => $this->event_id,
				"type" => $type,
			)) . ")";
		}
		if (!empty($types)) {
			$sql = "INSERT INTO " . $this->tableName() . " (event_id, type) VALUES " . implode(",", $types);
			if(Yii::app()->db->createCommand($sql)->execute())
				return true;
			else
				return false;
		}
		return true;
	}

	public function tableName()
	{
		return '{{event_pay_type}}';
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Event',
			'type' => 'Pay Type',
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
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
