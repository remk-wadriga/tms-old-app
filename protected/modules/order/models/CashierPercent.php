<?php

/**
 * This is the model class for table "{{cashier_percent}}".
 *
 * The followings are the available columns in table '{{cashier_percent}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $event_id
 * @property integer $order_cash_print_percent
 * @property integer $cash_print_percent
 * @property integer $print_percent
 * @property array $eventsPercent
 */
class CashierPercent extends CActiveRecord
{

	const NO_EVENT = 0;
	const NO_USER = 0;
	const NO_ROLE = 0;
	const TYPE_FULL_SALE = 1;
	const TYPE_CASH_SALE = 2;
	const TYPE_PRINT_SALE = 3;
	const TYPE_INVITE_SALE = 4;
	public static $types = [
		self::TYPE_FULL_SALE => "Оформлено касиром",
		self::TYPE_CASH_SALE => "Самовивіз з каси",
		self::TYPE_PRINT_SALE => "Касир НЕ отримуваав кошти",
		self::TYPE_INVITE_SALE => "Запрошення",
	];
	public $eventsPercent = [];
	public $statisticTypes = [
		self::TYPE_FULL_SALE,
		self::TYPE_CASH_SALE,
		self::TYPE_PRINT_SALE,
		self::TYPE_INVITE_SALE
	];

	public static function getPercentageByUser($user_id, $role_id, $event_id=self::NO_EVENT)
	{
		$event_percentage = self::model()->findByAttributes(["role_id"=>$role_id,"user_id"=>$user_id,"event_id"=>$event_id]);

		return $event_percentage;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CashierPercent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getPercentageByRole($role_id)
	{
		$event_percentage = self::model()->findByAttributes(["role_id"=>$role_id,"user_id"=>self::NO_USER,"event_id"=>self::NO_EVENT]);

		return $event_percentage;
	}

	public static function calculateUserPercent($user_id,$role_id,$event_id)
	{
		$percentage = self::model()->findByAttributes(["role_id"=>$role_id,"user_id"=>$user_id,"event_id"=>$event_id]);
		if($percentage)
			return $percentage;
		$percentage = self::model()->findByAttributes(["role_id"=>$role_id,"user_id"=>$user_id,"event_id"=>self::NO_EVENT]);
		if($percentage)
			return $percentage;
		$percentage = self::model()->findByAttributes(["role_id"=>$role_id,"user_id"=>self::NO_USER,"event_id"=>self::NO_EVENT]);
		if($percentage)
			return $percentage;

		return false;
	}

	public static function getUserEventsPercentage($role_id, $user_id)
	{
		$result = [];
		$events = Event::getListEvents(false);

		$event_percentage = self::model()->findAllByAttributes(["user_id"=>$user_id,"role_id"=>$role_id],[
			'condition'=>'event_id!=:id',
			'params'=>['id'=>self::NO_EVENT]
			]
		);
		foreach ($event_percentage as $percent) {
			$options = $events["options"][$percent->event_id];
			$result[] = ["label"=>$events["data"][$percent->event_id]." / ".$options["data-city"]." / ".$options["data-date"], "event_id"=>$percent->event_id,
						"fullSale"=>$percent->order_cash_print_percent, "cashSale"=>$percent->cash_print_percent, "printSale"=>$percent->print_percent];
		}

		return $result;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{cashier_percent}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, role_id, event_id', 'numerical', 'integerOnly'=>true),
			array('order_cash_print_percent, cash_print_percent, print_percent', 'numerical'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, role_id, event_id, order_cash_print_percent, cash_print_percent, print_percent', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'role_id' => 'Role',
			'event_id' => 'Event',
			'order_cash_print_percent' => 'Order Cash Print Percent',
			'cash_print_percent' => 'Cash Print Percent',
			'print_percent' => 'Print Percent',
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			$this->order_cash_print_percent = str_replace(',', '.', $this->order_cash_print_percent);
			$this->cash_print_percent = str_replace(',', '.', $this->cash_print_percent);
			$this->print_percent = str_replace(',', '.', $this->print_percent);
			return true;
		} else
			return false;
	}

	public function afterSave()
	{
		parent::afterSave();
		if (!empty($this->eventsPercent)) {
			$percent = $this->eventsPercent;
			$percent['event_id'] = array_values($percent['event_id']);
			$percent['fullSale'] = array_values($percent['fullSale']);
			$percent['cashSale'] = array_values($percent['cashSale']);
			$percent['printSale'] = array_values($percent['printSale']);

			for ($i = 0; $i < count($percent['event_id']); $i++) {
				$exist = self::model()->findByAttributes(["user_id"=>$this->user_id,"role_id"=>$this->role_id, "event_id"=>$percent['event_id'][$i]]);
				if ($exist)
					$model = $exist;
				else
					$model = new CashierPercent();

				$model->event_id = $percent['event_id'][$i];
				$model->user_id = $this->user_id;
				$model->role_id = $this->role_id;
				$model->order_cash_print_percent = $percent['fullSale'][$i];
				$model->cash_print_percent = $percent['cashSale'][$i];
				$model->print_percent = $percent['printSale'][$i];
				$model->save();
			}
		}
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('order_cash_print_percent',$this->order_cash_print_percent);
		$criteria->compare('cash_print_percent',$this->cash_print_percent);
		$criteria->compare('print_percent',$this->print_percent);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
