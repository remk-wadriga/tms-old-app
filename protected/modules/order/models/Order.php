<?php

/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property integer $total
 * @property string $comment
 * @property string $name
 * @property string $surname
 * @property string $patr_name
 * @property string $email
 * @property string $phone
 * @property string $np_number
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $delivery_id
 * @property string $date_add
 * @property string $date_update
 * @property integer $type
 * @property integer $status
 * @property integer $api
 *
 * The followings are the available model relations:
 * @property Role $role
 * @property User $user
 * @property Platform $platform
 * @property Delivery $delivery
 * @property Ticket[] $tickets
 */
class Order extends CActiveRecord
{
	const TYPE_QUOTE = 1;
	const TYPE_ORDER = 0;

	const STATUS_ACTIVE = 1;
	const STATUS_CLOSE = 0;

	const IN_KASA_PAY = 1;
	const IN_KASA_ONLINE =2;

	const NP_PAY = 3;
	const NP_ONLINE = 4;

	const COURIER_PAY = 5;
	const COURIER_ONLINE = 6;

	const E_ONLINE = 8;

	const PAY_ALL = 0;
	const PAY_CASH = 1;
	const PAY_CARD = 2;

    const IN_KASA = 1;
    const NP = 2;
    const COURIER = 3;

	public static $deliveryType = [
		self::IN_KASA_PAY=>" Самовивіз",
		self::NP_PAY=>" Нова пошта з відділення",
		self::COURIER_PAY=>" Кур’єром по місту",
		self::E_ONLINE=>" Електронний квиток",
	];

	public static $orderTypes = array(
		self::TYPE_QUOTE => "квота",
		self::TYPE_ORDER => "замовлення",
	);

	public static $physical = array(
		self::IN_KASA_PAY,
		self::IN_KASA_ONLINE,
		self::NP_ONLINE,
		self::NP_PAY,
		self::COURIER_ONLINE,
		self::COURIER_PAY
	);

    public static $delTypes = array(
        self::IN_KASA_PAY => "Самовивіз",
        self::IN_KASA_ONLINE => "Самовивіз",
        self::NP_ONLINE => "Нова пошта",
        self::NP_PAY => "Нова пошта",
        self::COURIER_ONLINE => "Кур’єр",
        self::COURIER_PAY => "Кур’єр",
        self::E_ONLINE => "Email"
    );

	public static $physicalPay = array(
		self::IN_KASA_PAY,
		self::NP_PAY,
		self::COURIER_PAY
	);

    public static $ePay = array(
        self::IN_KASA_ONLINE,
        self::NP_ONLINE,
        self::COURIER_ONLINE,
        self::E_ONLINE
    );

	public static $eTicket = array(
		self::E_ONLINE
	);
	public static $pay_methods = array(
		self::PAY_ALL => "Усі",
		self::PAY_CARD => "Платіжна система",
		self::PAY_CASH => "Готівкою в касі",
	);
	public $sector;
	public $event_id;
	public $name;
	public $phone;
	public $email;
	public $period;
	public $start_period;
	public $end_period;
	public $code;
	public $tags;
	public $np_id;
	public $creator;
    public $address;
	public $ticketStatus;
	public $payment;
	public $ticketType;
	public $ticketDelivery;
	public $ticketDeliveryType;
	public $ticketPrint;
	public $pay_time;
	public $pay_start;
	public $pay_end;
	public $print_time;
	public $print_start;
	public $print_end;
	public $print_author;
	public $print_role;
	public $city_id;
	public $creator_role;
	public $creator_id;
	public $pay_method;
	public $cash_role;
	public $cash_user_id;

    public $ticketsInfo = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Order the static model class
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
		return '{{order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('total, user_id, role_id', 'required'),
			array('total, user_id, role_id, type, status, api, phone, payment, pay_method, delivery_id', 'numerical', 'integerOnly'=>true),
            array('email', 'email'),
			array('comment, date_add, date_update, tags, email, np_number, surname, name, patr_name, phone', 'safe'),
			array('name, phone, ticketDeliveryType, payment', 'required', 'on'=>'createNewOrder'),
            array('city_id, address', 'validateCity', 'on'=>'createNewOrder', 'message'=>'Атрибут не може бути порожнім'),
//            array('address', 'validateAddress', 'on'=>'createNewOrder'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, total, comment, user_id, role_id, type, status', 'safe', 'on'=>'search'),
			array('code, id, total, comment, user_id, print_time, delivery_id, np_number, print_author, print_role, print_start, print_end, cash_role, cash_user_id, pay_method, creator_role, creator_id, creator, role_id, date_add, city_id, date_update, type, api, status, sector, event_id, name, surname, patr_name, phone, email, period, start_period, end_period, payment, ticketStatus, ticketType, ticketDelivery, ticketDeliveryType, ticketPrint, pay_start, pay_end, pay_time', 'safe', 'on'=>'searchOrders'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'tickets' => array(self::HAS_MANY, 'Ticket', 'order_id'),
			'quote' => array(self::HAS_ONE, 'Quote', 'order_id'),
            'platform' => array(self::BELONGS_TO, 'Platform', 'api'),
            'delivery' => array(self::BELONGS_TO, 'Delivery', 'delivery_id'),
		);
	}

    public function validateCity($attribute, $params)
    {
        if ($this->ticketDeliveryType!=Order::IN_KASA&&$this->$attribute==null)
            $this->addError($attribute, $params['message']);
    }

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if (!$this->isNewRecord)
				$this->date_update = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());

			if ($this->ticketDeliveryType!=Order::IN_KASA&&$this->city_id!=null) {
				$delivery = new Delivery();
				$delivery->city_id = $this->city_id;
				$delivery->address = $this->np_id? :$this->address;
				$delivery->status = $this->np_id ? Delivery::TYPE_NP : Delivery::TYPE_COURIER;
				$delivery->save(false);
                $this->delivery_id = $delivery->id;
			}
			return true;
		} else
			return false;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '# Замовлення',
			'total' => 'Загальна сума',
			'comment' => 'Коментар',
			'user_id' => 'Користувач',
			'role_id' => 'Гравець',
			'date_add' => 'Дата створення',
			'type' => 'Відображати квоти',
			'status' => 'Статус',
			'event_id' => 'Подія',
			'api' => 'API',
			'name' => 'Ім’я',
			'surname' => 'Прізвище',
			'patr_name' => 'По-батькові',
			'phone' => 'Телефон',
			'tags' => 'Теги',
			'np_number' => 'ТТН',
			'payment' => 'Статус оплати'
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
		$criteria->compare('total',$this->total);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('date_add',$this->date_add,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('api',$this->api);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function updateSum()
	{
		$sum = 0;
		foreach ($this->tickets as $ticket)
			if ($ticket->status==Ticket::STATUS_QUOTE_SOLD||$ticket->status==Ticket::STATUS_QUOTE_ON_SALE)
				$sum += ($ticket->price-$ticket->discount);
			else
				$sum += $ticket->price;
		$this->saveAttributes(array("total"=>$sum,"date_add"=>$this->date_add));
	}

    public $ticketIds = array();
    public $orderIds = array();
    public $pagination = false;
    public $searchCriteria = false;
	public function searchOrders($pagination=array(), $page = 1, $pageSize = 10, $xls = false, $count=false)
	{
		if (is_array($pagination))
			$pagination = array(
				"pageSize"=>$pageSize,
				"currentPage"=>$page-1
			);

		$criteria = new CDbCriteria();

		if ($this->name)
			$criteria->addCondition("t.surname like '%$this->name%' OR t.name like '%$this->name%' OR t.patr_name like '%$this->name%'");

		if ($this->phone)
			$criteria->compare("t.phone", $this->phone, true);

		if ($this->email)
			$criteria->compare("t.email", $this->email, true);

		if ($this->np_number)
			$criteria->compare("t.np_number", $this->np_number, true);

		if ($this->period) {
			if ($this->start_period)
				$criteria->addCondition("t.date_add >= '".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->start_period)."'");
			if ($this->end_period)
				$criteria->addCondition("t.date_add <= '". Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", (strtotime($this->end_period)+(60*60*24)-1))."'");
		}

		$order_ids = array();

		$builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
		$ids = false;

		if (isset($this->event_id)|| $this->sector || $this->payment ||
			$this->ticketStatus || $this->ticketType ||
			$this->ticketDelivery || $this->ticketDeliveryType ||
			$this->ticketPrint || $this->code || $this->np_number ||
			$this->city_id || $this->pay_time || $this->print_time ||
			$this->print_author || $this->cash_role || $this->print_role ||
			$this->cash_user_id || $this->pay_method || $this->tags ||
			$this->creator!=User::TYPE_ALL || $this->creator_role || $xls
		) {
			$ids = true;
//			$criteria = new CDbCriteria();
            $criteria->alias = "ticket";
			$criteria->join .= "JOIN ".Place::model()->tableName()." place ON ticket.place_id=place.id";
			$criteria->join .= " JOIN ".Order::model()->tableName(). " t ON t.id=ticket.order_id";

			if ($xls||$this->creator!=User::TYPE_ALL)
				$criteria->join .= " JOIN ".User::model()->tableName(). " user ON user.id=t.user_id";
            if ($xls) {
                if ($page!==false&&$pageSize!=false) {
                    $criteria->limit = $pageSize;
                    $criteria->offset = $page;
                }

                if ($count)
                    $criteria->select = "count(*) as count";
                else
                    $criteria->select = "ticket.id, CONCAT(event.name, ' ', city.name, ' ') as fullName, (SELECT MIN(start_sale) FROM tbl_timing WHERE event_id=event.id) as start_time,  CONCAT(IFNULL(ts.name, ''), ' ', s.name) as sector,
                        place.row, place.place, ticket.price, ticket.code, ticket.order_id, role.name as role_from,
                         CONCAT(user.id, ' - ', user.name, ' ', user.surname) as user_from, ticket.date_add as date_create,
                        ticket.delivery_type, ticket.delivery_status, c_role.name as role_to, CONCAT(c_user.id, ' - ', c_user.name, ' ', c_user.surname) as user_to,
                        ticket.pay_type, ticket.pay_status, ticket.date_pay as pay_date, p_role.name as role_print, CONCAT(p_user.id, ' - ', p_user.name, ' ', p_user.surname) as user_print,
                        ticket.date_print as print_date, ticket.status as ticket_status, t.type as order_type, ticket.type_blank as ticket_format,
                        ticket.owner_surname, ticket.owner_phone, ticket.owner_mail, country.name as country, region.name as region, city.name as city, ticket.tag, event.id as event_id, ticket.author_print_id,
                        ticket.cash_user_id, ticket.user_id, ticket.role_id, ticket.cash_role_id, ticket.print_role_id";
            } else
    			$criteria->select = "ticket.order_id, ticket.id, ticket.price";

            $criteria->order="t.date_add DESC";

			if (($this->city_id && !empty($this->city_id)) || $xls) {
				$criteria->join .= " JOIN ".Event::model()->tableName(). " event ON event.id=place.event_id";
				$criteria->join .= " JOIN ".Scheme::model()->tableName(). " scheme ON event.scheme_id=scheme.id";
				$criteria->join .= " JOIN ".Location::model()->tableName(). " location ON location.id=scheme.location_id";
                if ($xls) {
                    $criteria->join .= " JOIN ".Sector::model()->tableName(). " s ON s.id=place.sector_id";
					$criteria->join .= " LEFT JOIN ".TypeSector::model()->tableName(). " ts ON ts.id=s.type_sector_id";
                    $criteria->join .= " LEFT JOIN ".City::model()->tableName(). " city ON city.id=location.city_id";
                    $criteria->join .= " LEFT JOIN ".Region::model()->tableName(). " region ON region.id=city.region_id";
                    $criteria->join .= " JOIN ".Country::model()->tableName(). " country ON country.id=city.country_id";
                    $criteria->join .= " LEFT JOIN ".User::model()->tableName(). " c_user ON c_user.id=ticket.cash_user_id";
                    $criteria->join .= " LEFT JOIN ".User::model()->tableName(). " p_user ON p_user.id=ticket.author_print_id";
                    $criteria->join .= " JOIN ".Role::model()->tableName(). " role ON role.id=t.role_id";
                    $criteria->join .= " LEFT JOIN ".Role::model()->tableName(). " c_role ON c_role.id=ticket.cash_role_id";
                    $criteria->join .= " LEFT JOIN ".Role::model()->tableName(). " p_role ON p_role.id=ticket.print_role_id";
                }
                if ($this->city_id && !empty($this->city_id))
				    $criteria->addInCondition("location.city_id", $this->city_id);
			}

			if ($this->print_author)
				$criteria->compare("ticket.author_print_id", $this->print_author);

			if ($this->tags)
				$criteria->compare("ticket.tag", $this->tags, true);

			if ($this->print_role)
				$criteria->compare("ticket.print_role_id", $this->print_role);
			if ($this->cash_role)
				$criteria->compare("ticket.cash_role_id", $this->cash_role);

			if ($this->cash_user_id)
				$criteria->compare("ticket.cash_user_id", $this->cash_user_id);

			if ($this->pay_time) {
				$pay_start = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss",$this->pay_start);
				$pay_end = Yii::app()->dateFormatter->format("yyyy-MM-dd",$this->pay_end). " 23:59:59";
				$criteria->addBetweenCondition("ticket.date_pay", $pay_start, $this->pay_end ? $pay_end:time());
			}

			if ($this->pay_method) {
				switch ($this->pay_method) {
					case self::PAY_CASH:
						$criteria->addInCondition("ticket.pay_type", self::$physicalPay);
						break;
					case self::PAY_CARD:
						$criteria->addNotInCondition("ticket.pay_type", self::$physicalPay);
						break;
					default:
						break;
				}
			}

			if ($this->print_time) {
				$print_start = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss",$this->print_start);
				$print_end = Yii::app()->dateFormatter->format("yyyy-MM-dd",$this->print_end). " 23:59:59";
				$criteria->addBetweenCondition("ticket.date_print", $print_start, $this->print_end ? $print_end:time());
			}

			if ($this->creator == User::TYPE_USER) {
				if($this->creator_id&&$this->creator_id!='') {
                    $criteria->compare("ticket.user_id", $this->creator_id);
                    $criteria->compare("user.type", User::TYPE_USER);
                }

				if($this->creator_role&&$this->creator_role!='')
					$criteria->compare("ticket.role_id", $this->creator_role);
			} elseif ($this->creator == User::TYPE_SOC_USER) {
				$criteria->addCondition("ticket.api IS NOT NULL");
                $criteria->compare("user.type", User::TYPE_SOC_USER);
			}


			if ($this->code)
				$criteria->compare("ticket.code", $this->code);
			if ($this->id)
				$criteria->compare("ticket.order_id", $this->id);

			if (isset($this->event_id))
				$criteria->addInCondition("ticket.event_id", $this->event_id);

			if ($this->sector)
				$criteria->addInCondition("ticket.sector_id", $this->sector);


			if ($this->ticketStatus) {
				if (in_array(Ticket::STATUS_SOLD, $this->ticketStatus))
					array_push($this->ticketStatus, Ticket::STATUS_SEND_TO_EMAIL);
				$criteria->compare("ticket.status", $this->ticketStatus);
			}
			if ($this->payment)
				$criteria->compare("ticket.pay_status", $this->payment);
			if ($this->ticketDeliveryType) {
				$type = array();
				foreach ($this->ticketDeliveryType as $value) {
					$type[] = $value;
					if ($value == self::IN_KASA_PAY)
						$type[] = self::IN_KASA_ONLINE;
					elseif ($value == self::NP_PAY)
						$type[] = self::NP_ONLINE;
				 	elseif ($value == self::COURIER_PAY)
						$type[] = self::COURIER_ONLINE;
 				}

				$criteria->addInCondition("ticket.delivery_type", $type);
			}
			if ($this->ticketDelivery)
				$criteria->compare("ticket.delivery_status", $this->ticketDelivery);
			if ($this->ticketType) {
				if (in_array(Ticket::TYPE_BLANK, $this->ticketType)&&in_array(Ticket::TYPE_A4, $this->ticketType))
					$criteria->compare("ticket.pay_type", array_merge(self::$physical,self::$eTicket));
				elseif (in_array(Ticket::TYPE_BLANK, $this->ticketType))
					$criteria->compare("ticket.pay_type", self::$physical);
				elseif (in_array(Ticket::TYPE_A4, $this->ticketType))
					$criteria->compare("ticket.pay_type", self::$eTicket);
			}
			if ($this->ticketPrint)
				$criteria->compare("ticket.status", $this->ticketPrint, false, "OR");
			if (is_array($this->type)) {
                $criteria->compare("t.type", $this->type);

			}
			$command = $builder->createFindCommand(Ticket::model()->tableName(), $criteria);

            if ($count)
                $tickets = $command->queryScalar();
            else
			    $tickets = $command->queryAll();

            if ($xls) {
                return $tickets;
            }

            $sum = 0;
            $i=0;
            $prevCount=0;
            $ticket_ids = [];
            foreach ($tickets as $ticket) {
                if (!in_array($ticket['order_id'],$order_ids))
                    $order_ids[] = $ticket['order_id'];
                $count = count($order_ids);
                $sum += $ticket['price'];
                $ticket_ids[] = $ticket['id'];
                if ($count>(($page-1)*$pageSize)&&$i<$pageSize||$count==$prevCount) {
                    $this->orderIds[$ticket['order_id']] = $ticket['order_id'];
                    if ($prevCount<$count)
                        $i++;
                    $prevCount = $count;
                    $this->ticketIds[] = $ticket['id'];
                }
            }

            $this->orderIds = array_unique($this->orderIds);
            $this->ticketsInfo = array(
                "sum"=>number_format($sum, 0, ".", " "),
                "count"=>number_format(count($tickets), 0, ".", " "),
                "ordersCount"=>count($order_ids),
                "ids"=>$ticket_ids
            );

		}
        $criteria->join = "JOIN ".Ticket::model()->tableName()." ticket ON ticket.order_id=t.id";
//        $criteria->join = "";
//		if ($this->type != self::TYPE_QUOTE)
//			$criteria->compare("t.type", $this->type);

//        if ($this->name|| $this->phone||$this->email)
//            $this->ticketsInfo = array();


		if (($ids&&$this->id&&in_array($this->id,$order_ids))||($ids&&!empty($order_ids)&&!$this->id))
			$criteria->addInCondition("t.id", $this->orderIds);
		if ($this->id&&!$ids)
			$criteria->compare("t.id", $this->id);
		elseif (!$ids&&empty($order_ids)) {
		}
//        else
//			$criteria->compare("t.id", 0);

        $criteria->alias = "t";
        $criteria->select = "count(t.id)";
        $ordersCount = count($order_ids);
        if (is_array($pagination)&&!empty($ticket_ids)) {
            $this->pagination = new CPagination($ordersCount);
            $this->pagination->pageSize = $pageSize;
            $this->pagination->currentPage = $page-1;
            $this->pagination->pageVar = "Order_page";
			$this->pagination->validateCurrentPage = false;
        }
        $criteria->limit = $pageSize;

		if (is_array($pagination))
			$criteria->select = "t.*";
		else
			$criteria->select = "t.id";
		$criteria->order = "t.date_add DESC";
        $criteria->with = array_merge(array(

            'tickets'=>array("condition"=>!empty($this->ticketIds)? "tickets.id IN (".implode(",", $this->ticketIds).")":""),

        ), $pageSize > -1 ? array(

			'tickets.place',
			'tickets.place.sector',
			'tickets.place.sector.typeSector',
			'tickets.place.sector.typeRow',
			'tickets.place.sector.typePlace',
			'user',
			'role',
			'tickets.place.event.scheme',
			'tickets.place.event.scheme.location',
			'tickets.place.event.scheme.location.city',
		): array());
		if ($pageSize !== -1)
        	$criteria->group = "t.id";
		else
			$criteria->select = "t.id";

		return new CActiveDataProvider('Order', array(
			"criteria"=>$criteria,
			"pagination"=>!$this->pagination ? $pagination : false
		));
	}

	/**
	 * @param $dataProvider CActiveDataProvider
	 * @return integer
	 */
	public function getCost($dataProvider)
	{
		$sum = 0;
		foreach ($dataProvider->getData() as $orders) {
			foreach ($orders->tickets as $ticket)
				$sum += $ticket->price;
		}
		return number_format($sum, 0, ".", " ");
	}

	/**
	 * @param $dataProvider CActiveDataProvider
	 * @return string
	 */
	public function getTicketsInfo()
	{
		$sum = 0;
		$count = 0;

		$ids = array();
		$data = $this->searchOrders(false, 1, -1);
		$data = $data->getData();
		$ordersCount = count($data);
		foreach ($data as $orders) {
			$count += count($orders->tickets);
			foreach ($orders->tickets as $ticket) {
				$sum += $ticket->price;
				$ids[] = $ticket->id;
			}
		}
		if(is_array($ids))
			$ids = implode('&',$ids);

		return array(
			"sum"=>number_format($sum, 0, ".", " "),
			"count"=>number_format($count, 0, ".", " "),
			"ordersCount"=>$ordersCount,
			"ids"=>$ids
		);
	}

	public static function checkPlaceStatus(&$places, $temp=false, &$_ticket=array())
	{
		$events = array();
		$placeIds = array();
        $keys = array();
		foreach ($places as $place) {
			if (!isset($events[$place->event_id]))
				$events[$place->event_id] = $place->event_id;
            if (!isset($place->CartPosition))
                $keys[] = $place->id;
			$placeIds[] = $place->id;
		}
		$tempPlaces = array();
		if ($temp)
			$tempPlaces = Yii::app()->db->createCommand()
				->select("place_id")
				->from(TicketTemp::model()->tableName())
				->where(array("in","place_id", $placeIds))
				->queryColumn();
		$ticketPlaces = Yii::app()->db->createCommand()
			->select("place_id")
			->from(Ticket::model()->tableName())
			->where(array("in","place_id", $placeIds))
			->andWhere("status!=:status AND status!=:statusReturn", array(
				":status"=>Ticket::STATUS_CANCEL,
				":statusReturn"=>Ticket::STATUS_QUOTE_RETURN
			))
			->queryColumn();
		$check = array();
		$tempPlaces = array_merge($tempPlaces, $ticketPlaces);


		$events = self::checkIsInSale($events);
		$tempFunIds = array();
        if (!empty($keys))
            $places = array_combine($keys, $places);
		foreach ($places as $k=>$place) {
            if (!empty($keys)&&!in_array($k, $keys))
                continue;
			if (in_array($place->id, $tempPlaces)||in_array($place->id, $tempFunIds)||in_array($place->event_id, $events)||$place->status != Place::STATUS_SALE) {
				if ($place->type == Place::TYPE_FUN) {
					$criteria = new CDbCriteria();
					$criteria->compare("sector_id", $place->sector_id);
					$criteria->compare("event_id", $place->event_id);
					$criteria->compare("status", Place::STATUS_SALE);
					$criteria->compare("price", $place->price);
					$criteria->addNotInCondition("id", array_merge($tempPlaces, $events, $tempFunIds, array_keys($places)));
					$newPlace = Place::model()->find($criteria);
					if ($newPlace) {
                        if (isset($place->CartPosition)) {
                            Yii::app()->shoppingCart->put($newPlace);
                        } else
                            $places[$newPlace->id] = $newPlace;
                        $tempFunIds[] = $newPlace->id;
						if (!empty($_ticket)) {
							$ticket = $_ticket[$place->id];
							$_ticket[$newPlace->id] = $ticket;
							unset($_ticket[$place->id]);
						}
                        if (isset($place->CartPosition))
                            Yii::app()->shoppingCart->remove($k);
                        unset($places[$k]);
						continue;
					}
				}
                if (isset($place->CartPosition))
                    $check[] = $k;
                else
                    $check[] = $place->id;
			}
		}
		if (empty($check)&&empty($events))
			return true;
		else
			return $check;
	}

	public static function checkIsInSale($event_ids)
	{
		$events = Event::model()->findAllByAttributes(array("id"=>$event_ids));
		$notInSale = array();
		foreach ($events as $event) {
			if (!$event->isInSale)
				$notInSale[] = $event->id;
		}
		return $notInSale;
	}

	public function getTicketsDeliveries()
    {
        $result = array();
        foreach ($this->tickets as $ticket)
            if (!in_array($ticket->delivery_type, $result))
                $result[] = $ticket->delivery_type;
        $result = array_filter($result);
        return implode(",",array_map(function($result){return self::$delTypes[$result];}, $result));
    }

    public function getTicketsPayTypes()
    {
        $result = array();

        foreach ($this->tickets as $ticket)
            if (!in_array($ticket->pay_type, $result)&&$ticket->pay_type!="")
                $result[] = $ticket->pay_type;

        return implode(",",array_map(function($result){

            switch($result) {
                case in_array($result, self::$physicalPay):
                    return "Готівка";
                case in_array($result, self::$ePay):
                    return "Платіжна картка";
                default:
                    return false;
            }
        }, $result));
    }

	public function getTicketsDeliveryStatus()
	{
		$result = array();
		foreach ($this->tickets as $ticket)
			if (!in_array($ticket->delivery_status, $result))
				$result[] = $ticket->delivery_status;

        return implode(",",array_map(function($result) {
			return Ticket::getStatusDelivery($result);
		}, $result));
	}

	public function getTicketsPayStatus()
	{
		$result = array();
		foreach ($this->tickets as $ticket)
			if (!in_array($ticket->pay_status, $result))
				$result[] = $ticket->pay_status;

		return implode(",",array_map(function($result) {
			return Ticket::getStatusPay($result);
		}, $result));
	}

    public function getSuccessMessage($type)
    {
        switch($type) {
            case self::IN_KASA_PAY:
                return "Здійснити оплату та отримати квитки Ви зможете
                    в найближчій касі.";
            case self::IN_KASA_ONLINE:
                return "Отримати квитки Ви зможете в найближчій касі.";
            case self::NP_PAY:
                return "Квитки будуть надіслані на відповідне відділення
                        Нової пошти протягом трьох робочих днів.
                        Сума до сплати у відділенні Нової пошти
                        ".$this->total." грн.";
            case self::NP_ONLINE:
                return "Квитки будуть надіслані на відповідне відділення
                    Нової пошти протягом трьох робочих днів.";
            case self::COURIER_PAY:
                return "Квитки будуть доставлені Вам кур'єром протягом
                    трьох робочих днів.
                    Сума до сплати: ".$this->total." грн.";
            case self::COURIER_ONLINE:
                return "Квитки будуть доставлені Вам кур'єром протягом
                    трьох робочих днів.";
            case self::E_ONLINE:
                return "Квитки відправлені Вам на пошту в електронному
                    вигляді.
                    Ознайомтесь з правилами користування
                    електронними квитками у вкладенні листа.";
            default:
                return "";
        }
    }

	public function sendTickets($places)
	{
		$dataProvider = self::getTicketCriteria($places);
		$attachments = array();
		$viewData = Mailer::mail($dataProvider->getData());
		$messageText = Yii::app()->controller->renderPartial("application.views.mail._view", ["orderData" => $viewData], true, true);;
		$order_number = 0;
		$ticket_ids = [];
		$barcodes = array();
		foreach ($dataProvider->getData() as $data) {
			if($order_number == 0)
				$order_number = $data->order_id;
			$ticket_ids[] = $data->id;
			$barcode = Yii::app()->controller->widget("application.extensions.phpbarcode.PhpBarcode", array("code"=>$data->code));
			$barcodes[$data->id] = $barcode->image;
			$message = Yii::app()->controller->renderPartial("application.modules.order.views.order._e_ticket", array(
				"data"=>$data,
				"barcode"=>$barcodes[$data->id],
				"pdf" => true
			), true);
			$mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
			$mPDF1->autoPageBreak = false;
			$data->place->event->getTicket();

			$stylesheet = $data->place->event->e_ticket['style'];
			$mPDF1->WriteHTML($stylesheet, 1);
			$mPDF1->WriteHTML($message);
			$transSurname = UrlTranslit::translit($data->owner_surname);
			$name = uniqid($transSurname."_".$this->id).".pdf";
			$mPDF1->Output($name, 'f');
			$attachments[] = Yii::getPathOfAlias("webroot")."/".$name;
		}
		$this->sendETicket($messageText, $attachments, $viewData);

		foreach ($attachments as $file)
			unlink($file);

		foreach ($barcodes as $barcode)
			unlink($barcode);

		Mail::model()->deleteAllByAttributes(["ticket_id"=>$ticket_ids]);
	}

	public static function getTicketCriteria($positions, $eTicket=false)
	{
		$condId = $eTicket ? "t.place_id" : "t.id";

		$ids = array_map(function($ticket){
			if(is_object($ticket))
				return $ticket->id;
			elseif(is_array($ticket))
				return $ticket['id'];
			else
				return $ticket;
		}, $positions);
		$criteria = new CDbCriteria();
		$criteria->addInCondition($condId, $ids);
		$criteria->with = array("place", "place.event", "place.event.scheme", "place.sector", "place.event.scheme.location", "place.event.scheme.location.city");
		$criteria->select = "t.*, place.*, event.*, scheme.*, location.*, city.*, sector.*";

		return new CActiveDataProvider('Ticket', array(
			"criteria"=>$criteria,
			"pagination"=>false
		));
	}

	public function sendETicket($message, $attachments = false, $order)
	{
		User::mailsend($order["owner_mail"], Yii::app()->params['adminEmail'], 'Замовлення №'.$order["order_number"], $message, $attachments);
	}
}
