<?php

/**
 * This is the model class for table "{{ticket}}".
 *
 * The followings are the available columns in table '{{ticket}}':
 * @property integer $id
 * @property integer $order_id
 * @property integer $place_id
 * @property string $code
 * @property integer $type
 * @property integer $price
 * @property string $date_add
 * @property string $date_pay
 * @property string $date_print
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $author_print_id
 * @property integer $platform_id
 * @property string $comment
 * @property string $owner_surname
 * @property string $owner_phone
 * @property string $owner_mail
 * @property string $np_number
 * @property string $tag
 * @property integer $appointment_id
 * @property integer $cash_user_id
 * @property string $cancel_day
 * @property string $date_update
 * @property integer $discount
 * @property integer $status
 * @property integer $api
 * @property integer $pay_type
 * @property integer $pay_status
 * @property integer $delivery_status
 * @property integer $delivery_type
 * @property integer $print_role_id
 * @property integer $cash_role_id
 * @property integer $type_blank
 * @property integer $event_id
 *
 * The followings are the available model relations:
 * @property Platform $platform
 * @property Appointment $appointment
 * @property Order $order
 * @property Place $place
 * @property Role $role
 * @property User $user
 * @property User $cashUser
 * @property User $printUser
 */
class Ticket extends CActiveRecord implements IECartPosition
{
	const STATUS_SOLD = 1;
	const STATUS_CANCEL = 0;
	const STATUS_QUOTE_RETURN = 3;
	const STATUS_QUOTE_SOLD = 2;
	const STATUS_QUOTE_ON_SALE = 1;
	const PAY_NOT_PAY = 0;
	const PAY_PAY = 1;
	const PAY_INVITE = 2;
	const TYPE_BLANK = 0;
	const TYPE_A4 = 1;
	const STATUS_PRINTED = 4;
	const STATUS_NOT_PRINTED = 0;
	const STATUS_SEND_TO_EMAIL = 5;
	const DELIVERY_SENT = 1;
	const DELIVERY_NOT_SENT = 0;
	const DELIVERY_RECIEVED = 2;
	const DELIVERY_RETURNED = 3;
	public static $payStatus = array(
		self::PAY_NOT_PAY => "Не оплачено",
		self::PAY_PAY => "Оплачено",
		self::PAY_INVITE => "Запрошення",
	);
	public static $ticketStatus = array(
		self::STATUS_CANCEL => "Скасований",
		self::STATUS_SOLD => "Активний",
		self::STATUS_QUOTE_RETURN => "Повернутий у квоту",
		self::STATUS_QUOTE_SOLD => "Проданий у квоті",
		self::STATUS_QUOTE_ON_SALE => "Продається в квоті",
	);
	public static $ticketFormat = array(
		self::TYPE_BLANK => "бланк",
		self::TYPE_A4 => "А4",
	);
    public static $statusPrint = array(
        self::STATUS_PRINTED => "Роздруковано",
        self::STATUS_SEND_TO_EMAIL => "Відправлено на e-mail",
        self::STATUS_NOT_PRINTED => "Не роздруковано"
    );
    public static $statusDelivery = array(
        self::DELIVERY_NOT_SENT=>" Не відправлявся",
        self::DELIVERY_SENT=>" Надіслано клієнту",
        self::DELIVERY_RECIEVED =>" Отримано клієнтом",
        self::DELIVERY_RETURNED=>" Повернено від клієнта",
		self::STATUS_SEND_TO_EMAIL => "Відправлено на e-mail",
    );
	public $barcode;

    public static function getStatusTicket($status)
    {
        switch($status) {
            case self::STATUS_SOLD:
            case self::STATUS_SEND_TO_EMAIL:
                return "Активний";
                break;
            case self::STATUS_CANCEL:
                return "Скасований";
                break;
            case self::STATUS_QUOTE_RETURN:
                return "Повернений з квоти";
                break;
            case self::STATUS_QUOTE_SOLD:
                return "Проданий у квоті";
                break;
            default:
                return "";
                break;
        }
    }

	/**
	 * @param $positions
	 * @param $order Order
	 * @param bool|false $platform
	 * @param $orderType
	 * @param array $ticket
	 * @param array $userInfo
	 */

	public static function saveTickets($positions, $order, $platform=false, $orderType, $ticket=array(), $userInfo = array())
	{
        if (empty($userInfo))
            $userInfo = array(
                "surname"=>"",
                "name"=>"",
                "phone"=>"NULL",
                "email"=>"NULL"
            );
		$userName = $userInfo["surname"].' '.$userInfo["name"] != " " ? '"'.$userInfo["surname"].' '.$userInfo["name"].'"' : "NULL";
        $userEmail = $userInfo["email"] == "NULL" ? "NULL" : "'".$userInfo["email"]."'";
		$placeIds = array_map(function($place){
			return $place->id;
		}, $positions);
		$status = is_array($orderType) ? $orderType : self::getStandardPayType($orderType);

        $query = array();
		foreach ($positions as $position)
				$query[] = "(" . implode(",", array(
						"order_id" => $order->id,
						"place_id" => $position->id,
						"code" => $position->code,
						"type" => $position->type,
						"price" => $position->price,
						"user_id" => $order->user_id,
						"role_id" => $platform ? $platform->role_id : $order->role_id,
                        "platform_id" => $platform? $platform->id: 0,
						'owner_surname' => $orderType == Order::E_ONLINE && !empty($ticket) ? '"'.$ticket[$position->id]["surname"]." ".$ticket[$position->id]["name"].'"' : $userName,
						'owner_phone' => $userInfo["phone"],
						'owner_mail' => $userEmail,
						"status" => $status['status'],
						"api" => $platform ? $platform->partner_id : "NULL" ,
						"pay_type"=>$status['pay_type'],
						"pay_status"=>$status['pay_status'],
						"delivery_status"=>$status['delivery_status'],
                        "delivery_type"=>is_array($orderType)? $orderType['delivery_type'] : $orderType ,
                        "type_blank"=>$orderType == Order::E_ONLINE ? Ticket::TYPE_A4 : Ticket::TYPE_BLANK,
						"event_id"=>$position->event_id,
						"sector_id"=>$position->sector_id,
					)) . ")";
		if (!empty($query)) {
			$sql = "INSERT INTO " . Ticket::model()->tableName() . " (order_id, place_id, code, type, price, user_id, role_id, platform_id, owner_surname, owner_phone, owner_mail, status, api, pay_type, pay_status, delivery_status, delivery_type, type_blank, event_id, sector_id) VALUES " . implode(",", $query);
			Yii::app()->db->createCommand($sql)->execute();

			Yii::app()->db->createCommand()->
			update(Place::model()->tableName(), array("status"=>Place::STATUS_SOLD), array("in", "id", $placeIds));

			$order->updateSum();
		}
	}

	public static function getStandardPayType($orderType)
	{
		$pay_type = $orderType;
		$pay_status = self::PAY_NOT_PAY;
		$delivery_status = 0;
		$status = self::STATUS_SOLD;
		switch($orderType) {
			case Order::IN_KASA_PAY:
			case Order::NP_PAY:
			case Order::COURIER_PAY:
//				$pay_status = self::PAY_NOT_PAY;
				$delivery_status = self::DELIVERY_NOT_SENT;
				break;
			case Order::IN_KASA_ONLINE:
			case Order::NP_ONLINE:
			case Order::COURIER_ONLINE:
//				$pay_status = self::PAY_PAY;
				$delivery_status = self::DELIVERY_NOT_SENT;
				break;
			case Order::E_ONLINE:
//				$pay_status = self::PAY_PAY;
//				$delivery_status = self::STATUS_SEND_TO_EMAIL;
//				$status = self::STATUS_SOLD;
				break;
			default:
				break;
		}
		return array(
			"pay_type"=>$pay_type,
			"pay_status"=>$pay_status,
			"delivery_status"=>$delivery_status,
			"status"=>$status
		);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ticket}}';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Ticket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function printTicketsGetCash($ids, $order_id)
    {
        $ticket_ids = Yii::app()->db->createCommand()
            ->select("id")
            ->from(self::model()->tableName())
            ->where(array("in", "place_id", $ids))
            ->andWhere("order_id=:order_id", array(
                "order_id"=>$order_id
            ))
            ->queryColumn();
        $user_id = Yii::app()->user->id;
        $role_id = Yii::app()->user->currentRoleId;
        $now = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
        Yii::app()->db->createCommand()
            ->update(self::model()->tableName(), array(
                "cash_user_id"=>$user_id,
                "cash_role_id"=>$role_id,
                "date_pay"=>$now,
				"date_update"=>$now
            ), array("in", "id", $ticket_ids));
    }

    public static function getBlankType($type) {
        return $type == self::TYPE_BLANK ? "Бланк":"А4";
    }

	public static function getStatusPay($pay_status)
	{
		return $pay_status ? "Оплачено" : "Не оплачено";
	}

    public static function getStatusDelivery($deliveryStatus)
    {
        switch($deliveryStatus) {
            case self::DELIVERY_NOT_SENT:
                return "Не відправлявся";
            case self::DELIVERY_SENT:
                return "Надіслано клієнту";
            case self::DELIVERY_RECIEVED:
                return "Отримано клієнтом";
            case self::DELIVERY_RETURNED:
                return "Повернено від клієнта";
            default:
                return "";
        }
    }

	public static function getPrintAuthors()
	{
		$ids = Yii::app()->db->createCommand()
			->selectDistinct("author_print_id")
			->from(self::model()->tableName())
			->where("date_print is not null AND status!=:status AND author_print_id is not null", array(":status"=>self::STATUS_CANCEL))
			->queryColumn();

		return CHtml::listData(User::model()->findAllByAttributes(array("id"=>$ids)), "id", "email");
	}

	public static function getUserCreator($byAccess=false)
	{
        $usersArr = Yii::app()->db->CreateCommand()
			->selectDistinct("user_id, t.role_id")
			->from(self::model()->tableName()." t")
			->join("{{user}} user", "user.id=t.user_id")
			->where("user.type=:type", array(":type"=>User::TYPE_USER))
			->queryAll();
		if($byAccess) {
			$role = new Role();
			$enabledRoles = $role->getChildrenRecursively(Yii::app()->user->currentRoleId);
		}
        $roles = [];
        $users = [];
        foreach ($usersArr as $user) {
			if($byAccess) {
				if(in_array($user["role_id"],$enabledRoles)){
					$roles[] = $user['role_id'];
					$users[] = $user['user_id'];
				}
			} else {
				$roles[] = $user['role_id'];
				$users[] = $user['user_id'];
			}
        }
		$result['roles'] = CHtml::listData(Role::model()->findAllByAttributes(array("id"=>$roles)),"id","name");
		$result['users'] = self::getUsersList($users);
//			CHtml::listData(User::model()->findAllByAttributes(array("id"=>$users)),"id","email");
		return $result;
	}

    public static function getCashUsers($byAccess=false)
    {
        $usersArr = Yii::app()->db->CreateCommand()
            ->selectDistinct("cash_user_id, t.cash_role_id")
            ->from(self::model()->tableName()." t")
            ->join("{{user}} user", "user.id=t.user_id")
            ->where("user.type=:type", array(":type"=>User::TYPE_USER))
            ->queryAll();
		if($byAccess) {
			$role = new Role();
			$enabledRoles = $role->getChildrenRecursively(Yii::app()->user->currentRoleId);
		}
        $roles = [];
        $users = [];
        foreach ($usersArr as $user) {
			if($byAccess) {
				if(in_array($user["cash_role_id"],$enabledRoles)){
					$roles[] = $user['cash_role_id'];
					$users[] = $user['cash_user_id'];
				}
			} else {
				$roles[] = $user['cash_role_id'];
				$users[] = $user['cash_user_id'];
			}
        }

        $roles = array_filter($roles);
        $users = array_filter($users);

        $result['roles'] = CHtml::listData(Role::model()->findAllByAttributes(array("id"=>$roles)),"id","name");
		$result['users'] = self::getUsersList($users);
		return $result;
    }

	private static function getUsersList($ids)
	{
		return CHtml::listData(User::model()->findAllByPk($ids),"id",function($user){return "#$user->id - $user->fullName";});
	}

    public static function getPrintUsers($byAccess=false)
    {
        $usersArr = Yii::app()->db->CreateCommand()
            ->selectDistinct("author_print_id, t.print_role_id")
            ->from(self::model()->tableName()." t")
            ->join("{{user}} user", "user.id=t.user_id")
            ->where("user.type=:type AND date_print is not null AND author_print_id is not null AND print_role_id is not null",
                array(":type"=>User::TYPE_USER))
            ->queryAll();
		if($byAccess) {
			$role = new Role();
			$enabledRoles = $role->getChildrenRecursively(Yii::app()->user->currentRoleId);
		}
        $roles = [];
        $users = [];
        foreach ($usersArr as $user) {
			if($byAccess) {
				if(in_array($user["print_role_id"],$enabledRoles)){
					$roles[] = $user['print_role_id'];
					$users[] = $user['author_print_id'];
				}
			} else {
				$roles[] = $user['print_role_id'];
				$users[] = $user['author_print_id'];
			}
        }

        $roles = array_filter($roles);
        $users = array_filter($users);

        $result['roles'] = CHtml::listData(Role::model()->findAllByAttributes(array("id"=>$roles)),"id","name");
		$result['users'] = self::getUsersList($users);
		return $result;
    }

	public static function getPrintRoles()
	{
		$ids = Yii::app()->db->createCommand()
			->selectDistinct("print_role_id")
			->from(self::model()->tableName())
			->where("date_print is not null AND author_print_id is not null AND print_role_id is not null")
			->queryColumn();
		return CHtml::listData(Role::model()->findAllByAttributes(array("id"=>$ids)), "id", "name");
	}

	public static function saveTicketsInfo($data) {
		$positions = Yii::app()->shoppingCart->getPositions();
		$ids = array_map(function($ticket){return $ticket->id;}, $positions);
		$tickets = Ticket::model()->findAllByPk($ids);

		$sql = "";
		$user_id = Yii::app()->user->id;
		$role_id = Yii::app()->user->currentRoleId;
		$now = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
		$placeIds = array();
		foreach ($tickets as $ticket) {
			$placeIds[] = $ticket->place_id;
			$ticket->delivery_type = $data['delivery_type']!=="" ? $data['delivery_type'] : $ticket->delivery_type;
			$ticket->delivery_status = $data['delivery_status']!=="" ? $data['delivery_status'] : $ticket->delivery_status;
			if ($data['pay_status']!=="") {
				if (($data['pay_status']==Ticket::PAY_PAY && $ticket->pay_status != Ticket::PAY_PAY) ||
						($data['pay_status']==Ticket::PAY_INVITE && $ticket->pay_status != Ticket::PAY_INVITE)) {

					$ticket->pay_status = $data['pay_status'];
					$ticket->cash_user_id = $user_id;
					$ticket->cash_role_id = $role_id;
					$ticket->date_pay = $now;

				} elseif ($data['pay_status']==Ticket::PAY_NOT_PAY && $ticket->pay_status !=Ticket::PAY_NOT_PAY) {
					$ticket->pay_status = $data['pay_status'];
					$ticket->cash_user_id = null;
					$ticket->cash_role_id = null;
					$ticket->date_pay = null;
				}
			}
			if ($data['print_status']!=="") {
				$ticket_printStatus = $ticket->getPrintStatus();
				if ($data['print_status'] == Ticket::STATUS_PRINTED && $ticket_printStatus != Ticket::STATUS_PRINTED) {
					$ticket->status = Ticket::STATUS_SOLD;
					$ticket->author_print_id = $user_id;
					$ticket->print_role_id = $role_id;
					$ticket->date_print = $now;

				} elseif ($data['print_status']==Ticket::STATUS_SEND_TO_EMAIL && $ticket_printStatus!=Ticket::STATUS_SEND_TO_EMAIL) {
					$ticket->status = Ticket::STATUS_SEND_TO_EMAIL;
					$ticket->author_print_id = null;
					$ticket->print_role_id = null;
					$ticket->date_print = $now;
				} elseif ($data['print_status'] == Ticket::STATUS_NOT_PRINTED && $ticket_printStatus != Ticket::STATUS_NOT_PRINTED) {
					$ticket->status = Ticket::STATUS_SOLD;
					$ticket->author_print_id = null;
					$ticket->print_role_id = null;
					$ticket->date_print = null;
				}

			}


			$ticket->type_blank = $data['format'] !=="" ? $data['format'] : $ticket->type_blank;

			if ($data['cash_type'] !== "") {
				if ($data['cash_type'] == Order::PAY_CARD) {
					switch($data['delivery_type']) {
						case Order::IN_KASA_PAY:
							$ticket->pay_type = Order::IN_KASA_ONLINE;
							break;
						case Order::NP_PAY:
							$ticket->pay_type = Order::NP_ONLINE;
							break;
						case Order::COURIER_PAY:
							$ticket->pay_type = Order::COURIER_ONLINE;
							break;
						default;
							$ticket->pay_type = Order::E_ONLINE;
							break;
					}
				} else
					$ticket->pay_type = $data['delivery_type'];
			}

//            CVarDumper::dump($tickets,10,1);
//            CVarDumper::dump($data,10,1);
//            exit;
			if ($data['status']!=="") {
				$ticket->status = $data['status']==Ticket::STATUS_SOLD && ($ticket->status == Ticket::STATUS_SOLD || $ticket->status == Ticket::STATUS_SEND_TO_EMAIL) ? $ticket->status : Ticket::STATUS_CANCEL;
			}

			$ticket->tag = $data['tags'];

			$ticket->price = $data['Ticket'][$ticket->id]['price'];
			$ticket->owner_surname = $data['Ticket'][$ticket->id]['owner_surname'];
			$ticket->discount = $data['Ticket'][$ticket->id]['discount'];

            if ($data["date_cancel"]!=="")
                $ticket->cancel_day = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm", time()+(60*60*24*$data["date_cancel"]));
			$ticket->date_update = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm", time());
		}

		$sqlColumns = array(
				"delivery_type",
				"delivery_status",
				"pay_status",
				"author_print_id",
				"print_role_id",
				"date_add",
				"date_print",
				"pay_type",
				"status",
				"type_blank",
				"tag",
				"price",
				"owner_surname",
				"cash_role_id",
				"cash_user_id",
				"date_pay",
				"discount",
                "cancel_day",
				"date_update"
		);
		$i = 1;
        $orderIds = array();
		foreach ($sqlColumns as $column) {
			$sql .= $column." = CASE";
			$temp = array();
			$encode = $column == "owner_surname";
			foreach ($tickets as $ticket) {
                $orderIds[] = $ticket->order_id;
				$temp[] = $ticket->id;
				$ticket->$column = $encode ? CHtml::encode($ticket->$column) : $ticket->$column;
				$sql .= " WHEN id = ".$ticket->id." THEN ".($ticket->$column!=="" && $ticket->$column !== null? "'".$ticket->$column."'" : "NULL");
			}

			$sql .= " END".(count($sqlColumns)==$i ? " " : ", ");
			$i++;
		}
        $orderIds = array_unique($orderIds);
		$query = "UPDATE ".Ticket::model()->tableName()." SET ".$sql."  WHERE id IN (".implode(",", $ids).")";
		$result =  Yii::app()->db->createCommand($query)->execute();
		if ($data['status'] == Ticket::STATUS_CANCEL) {
			$tickets = Yii::app()->db->createCommand()
				->select("id, place_id")
				->from(self::model()->tableName())
				->where(array("in", "place_id", $placeIds))
				->andWhere("status!=:status", array(":status"=>Ticket::STATUS_CANCEL))
				->queryAll();
			foreach ($tickets as $ticket)
					$toDel[] = $ticket['place_id'];
			if (!empty($toDel))
				foreach ($toDel as $item)
					unset($placeIds[array_search($item, $placeIds)]);
			if (!empty($placeIds)) {
				Place::setPlaceStatus($placeIds, Ticket::STATUS_CANCEL, false, Place::STATUS_SALE);
				Place::refreshCode($placeIds);
			}
		}

        if ($result)
            self::saveState($ids);

        if (!empty($orderIds)) {
            $orders = Order::model()->findAllByPk($orderIds);
            foreach ($orders as $order)
                $order->updateSum();
        }

        return $result;
	}

	public static function saveState($ids, $new = false, $user_id=false) {
        $sql = array();
        $attribute = $new ? "place_id" : "id";
        $condition = $new ? "status!=:status" :"";
        $conditionArray = $new? array(":status"=>Ticket::STATUS_CANCEL) :array();
        $tickets = self::model()->findAllByAttributes(array($attribute=>$ids), $condition, $conditionArray);
        $modelName = __CLASS__;

        if (!$user_id)
            $user_id = Yii::app()->user->id;

        foreach ($tickets as $ticket) {
            $sql[] ="(".implode("," , array(
                    "model"=>"'".$modelName."'",
                    "model_id"=>$ticket->id,
                    "user_id"=>$user_id,
                    "state"=>"'".CJSON::encode($ticket)."'"
                )).")";
        }
        $query = "INSERT INTO ".History::model()->tableName()." (model, model_id, user_id, state) VALUES ".implode(",", $sql);
        if(Yii::app()->db->createCommand($query)->execute())
			Mail::saveTurn($tickets,true);
    }

    public static function getDeliveryType($delivery_type)
    {
        switch($delivery_type) {
            case Order::IN_KASA_ONLINE:
                $del_type = Order::IN_KASA_PAY;
                break;
            case Order::NP_ONLINE:
                $del_type = Order::NP_PAY;
                break;
            case Order::COURIER_ONLINE:
                $del_type = Order::COURIER_PAY;
                break;
            default:
                $del_type = $delivery_type;
        }
        $types = array(
            Order::IN_KASA_PAY=>" Самовивіз",
            Order::NP_PAY=>" Нова пошта з відділення",
            Order::COURIER_PAY=>" Кур’єром по місту",
            Order::E_ONLINE=>" Електронний квиток",
        );
        return isset($types[$del_type])?$types[$del_type] : "";
    }

	public static function generateCsv($tickets)
	{
		$filename = 'csv_' . date('Ymd') .'_' . date('His');

		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Description: File Transfer');
		header('Content-Encoding: UTF-8');
		header("Content-type: text/csv;charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"$filename.csv\";" );
		header("Content-Transfer-Encoding: binary");

		$headings = array("barcode","sector","price","rowx","placey","order","seller","dateprint");

		$fh = fopen('php://output', 'w');
		fputs($fh, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
		fputcsv($fh, $headings, ';');

		if ($tickets)
			foreach ($tickets as $ticket)
				fputcsv($fh, [$ticket['code'],$ticket['sector'],
								$ticket['price'],$ticket['row'],$ticket['place'],$ticket['order_id'],$ticket['role_from'],$ticket['print_date']], ';');

		fclose($fh);

	}

    public function getPrintStatus()
    {
        if ($this->date_print && $this->status != self::STATUS_SEND_TO_EMAIL)
            return self::STATUS_PRINTED;
        elseif($this->pay_type == Order::E_ONLINE)
            return self::STATUS_SEND_TO_EMAIL;
        else
            return self::STATUS_NOT_PRINTED;
    }

	public function getId() {
		return "Ticket_".$this->id;
	}

	public function getPrice() {
		return $this->price;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, place_id, code, type, price, user_id, role_id', 'required'),
			array('order_id, place_id, type, price, user_id, role_id, print_role_id, type_blank, cash_role_id, author_print_id, platform_id, appointment_id, discount, status, api, delivery_status, pay_status, pay_type, cash_user_id, delivery_type', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>45),
			array('owner_surname, owner_phone, owner_mail, np_number', 'length', 'max'=>128),
			array('date_add, date_pay, date_print, cancel_day, comment, tag', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, place_id, code, type, price, date_add, date_pay, tag, print_role_id, type_blank, cash_role_id, cash_user_id, date_print, user_id, role_id, author_print_id, api, platform_id, comment, owner_surname, owner_phone, owner_mail, appointment_id, delivery_type, discount, status', 'safe', 'on'=>'search'),
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
			'platform' => array(self::BELONGS_TO, 'Platform', 'platform_id'),
			'appointment' => array(self::BELONGS_TO, 'Appointment', 'appointment_id'),
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'place' => array(self::BELONGS_TO, 'Place', 'place_id'),
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'cashUser' => array(self::BELONGS_TO, 'User', 'cash_user_id'),
			'printUser' => array(self::BELONGS_TO, 'User', 'author_print_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'order_id' => 'Order',
			'place_id' => 'Place',
			'code' => 'Code',
			'type' => 'Type',
			'price' => 'Price',
			'date_add' => 'Date Add',
			'date_pay' => 'Date Pay',
			'date_print' => 'Date Print',
			'user_id' => 'User',
			'role_id' => 'Role',
			'author_print_id' => 'Author Print',
			'platform_id' => 'Platform',
			'comment' => 'Comment',
			'owner_surname' => 'Owner Surname',
			'owner_phone' => 'Owner Phone',
			'owner_mail' => 'Owner Mail',
			'appointment_id' => 'Appointment',
			'discount' => 'Discount',
			'status' => 'Status',
			'api' => 'API',
			'np_number' => 'ТТН',
			'pay_status' => 'Оплата',
			'pay_type' => 'Тип оплати',
			'delivery_status' => 'Передача квитка',
		);
	}

	public function afterSave()
	{
		parent::afterSave();
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('place_id',$this->place_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('price',$this->price);
		$criteria->compare('date_add',$this->date_add,true);
		$criteria->compare('date_pay',$this->date_pay,true);
		$criteria->compare('date_print',$this->date_print,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('author_print_id',$this->author_print_id);
		$criteria->compare('platform_id',$this->platform_id);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('owner_surname',$this->owner_surname,true);
		$criteria->compare('owner_phone',$this->owner_phone,true);
		$criteria->compare('owner_mail',$this->owner_mail,true);
		$criteria->compare('appointment_id',$this->appointment_id);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('status',$this->status);
		$criteria->compare('api',$this->api);
		$criteria->compare('np_number',$this->np_number);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('pay_status',$this->pay_status);
		$criteria->compare('delivery_status',$this->delivery_status);
		$criteria->compare('type_blank',$this->type_blank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * 	const STATUS_SOLD = 1;
	const STATUS_CANCEL = 0;
	const STATUS_QUOTE_RETURN = 3;
	const STATUS_QUOTE_SOLD = 2;
	const STATUS_QUOTE_ON_SALE = 1;
	 */
	public function getStatus()
	{
		switch($this->status) {
			case self::STATUS_SOLD:
			case self::STATUS_SEND_TO_EMAIL:
				return "Активний";
				break;
			case self::STATUS_CANCEL:
				return "Скасований";
				break;
			case self::STATUS_QUOTE_RETURN:
				return "Повернений з квоти";
				break;
			case self::STATUS_QUOTE_SOLD:
				return "Проданий у квоті";
				break;
			default:
				return "";
				break;
		}
	}

	public function getPayType()
	{
        switch($this->pay_type) {
            case in_array($this->pay_type, Order::$physicalPay):
                return "Готівка";
            case in_array($this->pay_type, Order::$ePay):
                return "Платіжна картка";
            default:
                return false;
        }
	}

	public function getPayStatus()
	{
		return $this->pay_status == Ticket::PAY_INVITE ? "Запрошення" : ($this->pay_status ? "Оплачено" : "Не оплачено");
	}

	public function getDeliveryStatus()
	{
		switch($this->delivery_status) {
			case self::DELIVERY_NOT_SENT:
				return "Не відправлявся";
			case self::DELIVERY_SENT:
				return "Надіслано клієнту";
			case self::DELIVERY_RECIEVED:
				return "Отримано клієнтом";
			case self::DELIVERY_RETURNED:
				return "Повернено від клієнта";
			default:
				return "";
		}
	}

	public function getStatusPrint()
	{
		if ($this->date_print && $this->status != self::STATUS_SEND_TO_EMAIL)
			return self::$statusPrint[self::STATUS_PRINTED];
		elseif($this->pay_type == Order::E_ONLINE || $this->status == self::STATUS_SEND_TO_EMAIL)
			return self::$statusPrint[self::STATUS_SEND_TO_EMAIL];
		else
			return self::$statusPrint[self::STATUS_NOT_PRINTED];
	}



}
