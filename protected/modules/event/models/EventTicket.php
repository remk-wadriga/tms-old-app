<?php

/**
 * This is the model class for table "{{event_ticket}}".
 *
 * The followings are the available columns in table '{{event_ticket}}':
 * @property integer $id
 * @property integer $event_id
 * @property string $ticket
 * @property string $style
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class EventTicket extends CActiveRecord
{
	const TYPE_E_TICKET = 1;
	const TYPE_BLANK = 0;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EventTicket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getName()
	{
		return "Білет";
	}

	/**
	 * @param Event $event
	 * @param $ticket
	 * @param Ticket|bool $data
	 * @return mixed
	 */
	public static function replace($event, $ticket, $data=false)
	{
		$price = "{price}";
        $sector_prefix = "{sector_prefix}";
		$sector_name = "{sector_name}";
		$row_prefix = "{row_prefix}";
		$place_prefix = "{place_prefix}";
		$row = "{row}";
		$place = "{place}";
        $owner_name = "{owner_name}";
		$row_prefix_short = "{row_prefix_short}";
		$place_prefix_short = "{place_prefix_short}";
		$code = 123456789123;
		$author_print_id = "{cashier_id}";
		$date_print = "{date_print}";
		$order_id = "{order_id}";
		$name = $event->name;
		$maxNameLength = 55;
		$maxSectorLength = 45;
		$barcode = "";


		if(strlen($name) > $maxNameLength)
			$name = substr_replace($name, "...", $maxNameLength);

		if(strlen($sector_name) > $maxSectorLength)
			$sector_name = substr_replace($name, "...", $maxSectorLength);

		if ($data) {
			$isFanZone = $data->place->type == Place::TYPE_FUN;
			$price = $data->price." грн";
			if($data->pay_status == Ticket::PAY_INVITE)
				$price = "запрошення";
            $sector_prefix = $data->place->sector->typeSector ? $data->place->sector->typeSector->name.":": "";
			$sector_name = $data->place->sector->name;
			$row_prefix = $isFanZone ? "":$data->place->rowName.":";
			$place_prefix = $isFanZone ? "":$data->place->placeName.":";
			$row = $isFanZone ? "":$data->place->editedRow;
			$place = $isFanZone ? "":$data->place->editedPlace;
            $owner_name = $data->owner_surname;
			$row_prefix_short = $isFanZone ? "":ucfirst(mb_substr($data->place->rowName,0,1,"UTF-8")).":";
			$place_prefix_short = $isFanZone ? "":ucfirst(mb_substr($data->place->placeName,0,1,"UTF-8")).":";
			$code = $data->code;
			$author_print_id = $data->author_print_id;
			$order_id = $data->order->id;
			$date_print = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", time());
			$barcode = isset($data->barcode)? CHtml::image($data->barcode, "") : "";
		}
		$replacement = array(
			"{location_name}",
			"{city}",
			"{address}",
			"{event_name}",
			"{date}",
			"{enter}",
			"{start_time}",
			"{price}",
            "{owner_name}",
            "{sector_prefix}",
            "{sector_name}",
            "{row_prefix}",
            "{place_prefix}",
            "{row}",
            "{place}",
			"{bar_code1}",
			"{bar_code2}",
			"{bar_code3}",
			"{code}",
			"{logo}",
			"{row_prefix_short}",
            "{place_prefix_short}",
			"{cashier_id}",
			"{date_print}",
			"{order_id}",
			"{full_event_name}",
			"{curr_date}",
		);
		$replace = array(
			$event->scheme->location->name,
			$event->scheme->location->city->name,
			$event->scheme->location->address,
			$name,
			Yii::app()->dateFormatter->format("dd.MM.yyyy", $event->getStartTime()),
			Yii::app()->dateFormatter->format("HH:mm", $event->enterTime),
			Yii::app()->dateFormatter->format("HH:mm", $event->getStartTime()),
			$price,
            $owner_name,
            $sector_prefix,
			$sector_name,
			$row_prefix,
			$place_prefix,
			$row,
			$place,
			CHtml::tag("div", array("id"=>"code_".($data ? $data->id : $event->id)."_1", "class"=>"sub_2 barcodeView"),$barcode),
			CHtml::tag("div", array("id"=>"code_".($data ? $data->id : $event->id)."_2", "class"=>"sub_1 barcodeView"),$barcode),
			CHtml::tag("div", array("id"=>"code_".($data ? $data->id : $event->id)."_3", "class"=>"sub_4 barcodeView"),$barcode),
			$code,
			Yii::app()->getBaseUrl(true)."/css/e_ticket/img/logo.png",
			$row_prefix_short,
			$place_prefix_short,
			$author_print_id,
			$date_print,
			$order_id,
			$event->name,
			Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", time()),
		);
		return str_replace($replacement, $replace, $ticket);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{event_ticket}}';
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
			array('event_id, type', 'numerical', 'integerOnly'=>true),
			array('ticket, style', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, event_id, ticket, style, type', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'event_id' => 'Подія',
			'ticket' => 'Ticket',
			'style' => 'Style',
			'type' => 'Тип',
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
		$criteria->compare('ticket',$this->ticket,true);
		$criteria->compare('style',$this->style,true);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

}
