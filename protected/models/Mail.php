<?php

/**
 * This is the model class for table "{{mail}}".
 *
 * The followings are the available columns in table '{{mail}}':
 * @property integer $id
 * @property integer $ticket_id
 * @property string $date_add
 * @property integer $status
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Ticket $ticket
 */
class Mail extends CActiveRecord
{
	public static function saveTurn($tickets,$checkOrder=false)
	{
		if (!empty($tickets)) {
			foreach ($tickets as $ticket) {
				if($checkOrder){
					if($ticket->order->type == Order::TYPE_QUOTE)
						continue;
				}
				if (self::isForMail($ticket)) {
					$turn = new Mail();
					$turn->ticket_id = $ticket->id;
					$turn->type = self::getType($ticket);
					$turn->save();
				}
			}
		}
	}

	public static function isForMail($ticket)
	{
		$histories = History::model()->findAllByAttributes(["model"=>get_class(Ticket::model()), "model_id"=>$ticket->id],['order'=>'id DESC', 'limit'=>2]);

		if (count($histories) < 2)
			return true;
		$current = current($histories);
		$prev = next($histories);
		$currentTicket = $current->state;
		$prevTicket = $prev->state;

		if ($currentTicket["pay_status"] != $prevTicket["pay_status"] || $currentTicket["status"] != $prevTicket["status"]
			|| $currentTicket["delivery_status"] != $prevTicket["delivery_status"] || $currentTicket["date_print"] != $prevTicket["date_print"])
			return true;

		return false;
	}

	private static function getType($ticket)
	{
		if($ticket->status == Ticket::STATUS_CANCEL) {
			if($ticket->pay_status == Ticket::PAY_INVITE)
				return Mailer::CANCEL_INVITE_MESSAGE;
			else
				return Mailer::CANCEL_ORDER_MESSAGE;
		}
		if(isset(Mailer::$deliveryPref[$ticket->delivery_type]))
			$delType = Mailer::$deliveryPref[$ticket->delivery_type];
		else
			return Mailer::NO_MESSAGE;
		if ($ticket->pay_status == Ticket::PAY_INVITE)
		{
			$payType = Mailer::PAY_INVITE;
			if ($ticket->date_print)
				$statusType = Mailer::PRINTED;
			else
				$statusType = Mailer::CREATE;
		}else {
			if (in_array($ticket->pay_type, Order::$physicalPay))
				$payType = Mailer::$payPref[Order::PAY_CASH];
			elseif (in_array($ticket->pay_type, Order::$ePay))
				$payType = Mailer::$payPref[Order::PAY_CARD];
			else
				return Mailer::NO_MESSAGE;


			if ($ticket->pay_status == Ticket::PAY_PAY) {
				$statusType = Mailer::SUCCESS;
				if ($ticket->date_print)
					$statusType = Mailer::PRINTED;
			} elseif ($ticket->date_print)
				$statusType = Mailer::PRINTED;
			else
				$statusType = Mailer::CREATE;
		}
//		$type = Mailer::$getConst[$delType.$payType.$statusType];
		$type = isset(Mailer::$getConst[$delType.$payType.$statusType]) ? Mailer::$getConst[$delType.$payType.$statusType] : Mailer::NO_MESSAGE;
		return $type;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{mail}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ticket_id', 'required'),
			array('ticket_id, status, type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ticket_id, type, date_add, status', 'safe', 'on'=>'search'),
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
			'ticket' => array(self::BELONGS_TO, 'Ticket', 'ticket_id'),
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			$exist = self::model()->findByAttributes(["ticket_id"=>$this->ticket_id]);
			if ($exist) {
				$exist->saveAttributes(["date_add"=>null,"type"=>$this->type]);
				return false;
			}
			return true;
		} else
			return false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ticket_id' => 'Ticket',
			'date_add' => 'Date Add',
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
		$criteria->compare('ticket_id',$this->ticket_id);
		$criteria->compare('date_add',$this->date_add,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
