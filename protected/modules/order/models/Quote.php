<?php

/**
 * This is the model class for table "{{quote_info}}".
 *
 * The followings are the available columns in table '{{quote_info}}':
 * @property integer $id
 * @property string $name
 * @property integer $order_id
 * @property integer $role_from_id
 * @property integer $role_to_id
 * @property integer $event_id
 * @property string $from_legal_detail
 * @property string $to_legal_detail
 * @property integer $percent
 * @property integer $type_payment
 * @property integer $printed
 * @property string $comment
 * @property integer $status
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property Role $roleTo
 * @property Role $roleFrom
 * @property Order $order
 * @property Event $event
 */
class Quote extends CActiveRecord
{
	const ORDER_BY_DATE = 1;
	const ORDER_BY_UPDATE = 2;
	const ORDER_BY_COUNT = 3;

	const TYPE_PERCENT = 1;
	const TYPE_PAYMENT = 2;

	const RETURN_AND_OPEN = 1;
	const RETURN_AND_CLOSE = 0;

	const STATUS_PRINTED = 1;
	const STATUS_NO_PRINTED = 0;

	const FILTER_ALL = 1;
	const FILTER_SOLD = 2;
	const FILTER_RETURN = 3;
	const FILTER_UNDEFINED = 4;

	const PASS_TYPE_NEW = 1;
	const PASS_TYPE_OLD = 0;

    const STATUS_NO_PASS = 0;
    const STATUS_PASS = 1;

    const TYPE_NONE = 0;
    const TYPE_EQUOTE = 1;
    const TYPE_PHYSICAL = 2;
    const TYPE_RETURN = 3;

	public static $filterTypes = array(
		self::FILTER_ALL => "Всього",
		self::FILTER_SOLD => "Продані",
		self::FILTER_RETURN => "Повернені",
		self::FILTER_UNDEFINED => "Доля невідома",
	);

	public static $payTypes = array(
		self::TYPE_PERCENT => "% від продажу",
		self::TYPE_PAYMENT => "винагорода за продаж"
	);

	public static $order = array(
		self::ORDER_BY_DATE=>"За датою створення",
		self::ORDER_BY_UPDATE=>"За датою оновлення",
		self::ORDER_BY_COUNT=>"За розміром",
	);

    public static $namesStatus = array(
        self::STATUS_NO_PASS => "Не передана",
        self::STATUS_PASS => "Передана",
    );

    public static $namesTypes = array(
        self::TYPE_NONE => "Проект квоти",
        self::TYPE_EQUOTE => "Електронна квота",
        self::TYPE_PHYSICAL => "Фізична квота",
        self::TYPE_RETURN => "Накладна повернення організатору",
    );

	public $roleRelation;
	public $statusWait;
	public $contractor_id;
	public $sorting;
	public $status;

	/**
	 * @return string translated Name of module
	 */
	public static function getName()
	{
		return "Квота";
	}


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{quote_info}}';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Quote the static model class
     */
    public static function model($className = __CLASS__)
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
			array('name, role_from_id, role_to_id, percent', 'required'),
			array('order_id, role_from_id, role_to_id, percent, event_id, type_payment, printed, status, type', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 128),
			array('from_legal_detail, to_legal_detail,role_from_id, role_to_id,  comment, role_from_id, role_to_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, order_id, role_from_id, role_to_id, from_legal_detail, to_legal_detail, percent, comment,event_id, printed', 'safe', 'on' => 'search'),
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
			'roleTo' => array(self::BELONGS_TO, 'Role', 'role_to_id'),
			'roleFrom' => array(self::BELONGS_TO, 'Role', 'role_from_id'),
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id')
		);
	}

	/**
	 * @return bool
	 */
	public function beforeSave()
	{
		if (parent::beforeSave()) {
			Yii::app()->user->setState("currentCartId", "cartId_" . $this->event_id);
			$positions = Yii::app()->shoppingCart->getPositions();
			if (empty($positions))
				return false;
			$model = new Order();
			$model->type = Order::TYPE_QUOTE;
			$model->user_id = Yii::app()->user->id;
			$model->role_id = Yii::app()->user->currentRoleId;
			$model->status = Order::STATUS_ACTIVE;
			$model->total = Yii::app()->shoppingCart->getCost();
			if ($model->save(false)) {
				$this->order_id = $model->id;
				$query = array();
				$placeIds = array();
				foreach ($positions as $position) {
					if ($position->type == Place::TYPE_FUN) {
						$places = Place::model()->findAllByAttributes(
							array(
								"sector_id" => $position->sector_id,
								"event_id" => $position->event_id,
								"status" => Place::STATUS_SALE
							),
							array(
								"limit" => $position->getQuantity()
							));
						foreach ($places as $place) {
							$query[] = "(" . implode(",", array(
									"order_id" => $model->id,
									"place_id" => $place->id,
									"code" => $place->code,
									"type" => $place->type,
									"price" => $place->price,
									"user_id" => Yii::app()->user->id,
									"role_id" => Yii::app()->user->currentRoleId,
									"status" => Ticket::STATUS_SOLD,
									"event_id" => $place->event_id,
									"sector_id" => $place->sector_id,
								)) . ")";
							array_push($placeIds,$place->id);
						}
					} else {
						$query[] = "(" . implode(",", array(
								"order_id" => $model->id,
								"place_id" => $position->id,
								"code" => $position->code,
								"type" => $position->type,
								"price" => $position->price,
								"user_id" => Yii::app()->user->id,
								"role_id" => Yii::app()->user->currentRoleId,
								"status" => Ticket::STATUS_SOLD,
								"event_id" => $position->event_id,
								"sector_id" => $position->sector_id,
							)) . ")";
						array_push($placeIds,$position->id);
					}
				}
				if (!empty($query)) {
					$sql = "INSERT INTO " . Ticket::model()->tableName() . " (order_id, place_id, code, type, price, user_id, role_id, status, event_id, sector_id) VALUES " . implode(",", $query);
					Yii::app()->db->createCommand($sql)->execute();
					Yii::app()->db->createCommand()->
					update(Place::model()->tableName(), array("status"=>Place::STATUS_SOLD), array("in", "id", $placeIds));
				}
			} else return false;
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
			'id' => 'ID',
			'name' => 'Назва',
			'order_id' => 'Order',
			'event_id' => 'Подія',
			'role_from_id' => 'Постачальник',
			'role_to_id' => 'Одержувач',
			'from_legal_detail' => 'Реквізити',
			'to_legal_detail' => 'Реквізити',
			'percent' => 'Винагорода',
			'type_payment' => 'Percent',
			'comment' => 'Коментар',
			'statusWait' => 'Статус Очікування',
			'printed' => 'Буде надруковано'
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('order_id', $this->order_id);
		$criteria->compare('event_id', $this->event_id);
		$criteria->compare('role_from_id', $this->role_from_id);
		$criteria->compare('role_to_id', $this->role_to_id);
		$criteria->compare('from_legal_detail', $this->from_legal_detail, true);
		$criteria->compare('to_legal_detail', $this->to_legal_detail, true);
		$criteria->compare('percent', $this->percent);
		$criteria->compare('comment', $this->comment, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public static function setContractorsBlock($result, $contractor, $event_id, $delete=false)
	{
		self::setCartBlock($contractor,$event_id, $delete);
		self::setSaveWindow($contractor, $event_id, $delete);
		if ($state_result = Yii::app()->user->getState("contractorsBlock_".$event_id)) {
			if ($delete) {

				unset($state_result[$contractor->id]);
				Yii::app()->user->setState("currentCartId", "cartId_".$contractor->id."_".$event_id);
				$sector = new Sector();
				$sector->setColors(Place::getPrices($event_id));
				$ids = array_map(function($place){
					return array("id"=>"row".$place['row']."col".$place['place']."sector".$place['sector_id'],
						"fill"=>$place['price'],
						"status"=>$place['status']
					);
				}, Yii::app()->shoppingCart->getPositions());
				Yii::app()->shoppingCart->clear();
				Yii::app()->user->setState("funIdsInCart", null, null);
				foreach ($ids as &$place) {
					$status = $place['status'];
					$place['fill'] = $status == Place::STATUS_SALE ? $sector->getColor($place['fill']) : ($status == Place::STATUS_SOLD ? Place::COLOR_SOLD : "#cccccc");
					$place['selectable'] = $status == Place::STATUS_SALE;
					unset($place['status']);
				}

				Yii::app()->user->setState("contractorsBlock_".$event_id, !empty($state_result) ? $state_result : null);
				if (Yii::app()->request->isAjaxRequest) {
					echo json_encode(array("deleted"=>true, "enableSelect"=>array_values($ids)));
					Yii::app()->end();
				} else
					return;
			}
			if (!array_key_exists($contractor->id, $state_result))
				Yii::app()->user->setState("contractorsBlock_".$event_id, $state_result+array($contractor->id=>$result));
			else {
				if ($state_result[$contractor->id]==$result) {
					echo json_encode("error");
					Yii::app()->end();
				}
				$state_result[$contractor->id] = $result;
				Yii::app()->user->setState("contractorsBlock_".$event_id, $state_result);
			}
		} else
			Yii::app()->user->setState("contractorsBlock_".$event_id, array($contractor->id=>$result));
	}

	public static function setCartBlock($contractor, $event_id, $delete=false)
	{
		$result = Yii::app()->controller->renderPartial("_cart_block", array("contractor"=>$contractor), true);
		if ($cart_block = Yii::app()->user->getState("cartBlock_".$event_id)) {
			if ($delete) {
				unset($cart_block[$contractor->id]);
				Yii::app()->user->setState("cartBlock_".$event_id, !empty($cart_block) ? $cart_block : null);
				return;
			}
			if (!array_key_exists($contractor->id, $cart_block))
				Yii::app()->user->setState("cartBlock_".$event_id, $cart_block+array($contractor->id=>$result));
		} else
			Yii::app()->user->setState("cartBlock_".$event_id, array($contractor->id=>$result));
	}

	public static function setSaveWindow($contractor, $event_id, $delete)
	{
		$stateName = "saveBlock_".$event_id;
		if ($save_state = Yii::app()->user->getState($stateName)) {
			if ($delete) {
				unset($save_state[$contractor->id]);
				Yii::app()->user->setState($stateName, !empty($save_state) ? $save_state : null);
				return;
			}
			Yii::app()->user->setState($stateName, $save_state+array($contractor->id=>$contractor));
		} else
			Yii::app()->user->setState($stateName, array($contractor->id=>$contractor));
	}

	public static function getEventList()
	{
		return CHtml::listData(Event::model()->with(array("quotes"=>array("condition"=>"quotes.id IS NOT NULL")))->findAll(), "id", "name");
	}

	public static function getContractorList()
	{
		return CHtml::listData(Role::model()->with(array("quotes"=>array("condition"=>"quotes.id IS NOT NULL")))->findAll(), "id", "name");
	}

	public static function getInCart($event_id)
	{
		$result = array();
		if (Yii::app()->user->hasState("contractorsBlock_" . $event_id))
			if ($contractors = Yii::app()->user->getState("contractorsBlock_" . $event_id)) {
				foreach ($contractors as $k=>$contractor) {
					Yii::app()->user->setState("currentCartId", "cartId_" . $k . "_" . $event_id);
					Yii::app()->shoppingCart->init();
					$positions = Yii::app()->shoppingCart->getPositions();
					$tmp = array_map(function($item) {
						return $item->id;
					}, $positions);
					$result = array_merge($result, $tmp);

				}
			}
		if (empty($result)) {
			Yii::app()->user->setState('currentCartId', "cartId_event_list");
			$positions = Yii::app()->shoppingCart->getPositions();
			$result = array_map(function($item) {
				return $item->id;
			}, $positions);
		}
		return array_values($result);
	}

	public static function getIsInQuote($quote_id, $sector_id)
	{
		return Yii::app()->db->createCommand()
			->select("count(*)")
			->from(Ticket::model()->tableName()." t")
			->join(Place::model()->tableName()." p", "p.id=t.place_id")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("p.sector_id=:sector_id AND t.status!=:returnStatus AND t.status!=:cancelStatus", array(
				":sector_id"=>$sector_id,
				":returnStatus"=>Ticket::STATUS_QUOTE_RETURN,
				":cancelStatus"=>Ticket::STATUS_CANCEL,
			))
			->andWhere(array("in", "q.id", (array)$quote_id))
			->queryScalar();
	}

    public static function getIdsBySector($sector_id, $event_id)
    {
        return Yii::app()->db->createCommand()
            ->selectDistinct("q.id")
            ->from(self::model()->tableName()." q")
            ->join(Ticket::model()->tableName()." t", "t.order_id=q.order_id")
            ->join(Place::model()->tableName()." p", "p.id=t.place_id")
            ->where("p.sector_id=:sector_id AND p.event_id=:event_id AND p.status!=:statusClose", array(
                ":sector_id"=>$sector_id,
                ":event_id"=>$event_id,
                ":statusClose"=>Place::STATUS_CLOSE
            ))
            ->queryColumn();
    }

    public static function placeToCart($places, $contractor, $event_id)
    {
        $funZones = array();
        foreach ($places as $place) {
            if ($place->type == Sector::TYPE_FUN) {
                $placeIds = Yii::app()->user->hasState("funIdsInCart") ? Yii::app()->user->getState("funIdsInCart") : array();
                $models = Place::model()->findAllByAttributes(array(
                    "sector_id"=>$place->sector_id,
                    "event_id"=>$event_id,
                    "status"=>Place::STATUS_SALE
                ), array(
                    "limit"=>$place->amount,
                    "condition"=>!empty($placeIds) ? "id NOT IN (".implode(",", $placeIds).")" : ""
                ));

                foreach ($models as $model) {
                    $ids[] = $model->id;
                    $model->price = $place->price;
                    Yii::app()->shoppingCart->put($model, 1, "cartId_" . $contractor->id . "_" . $event_id);
                }
                Yii::app()->user->setState("funIdsInCart", array_merge($placeIds,$ids));
                $funZones[] = $place->sector_id;
                continue;
            }
            $model = Place::model()->findByPk($place->server_id);
            Yii::app()->shoppingCart->put($model, 1, "cartId_" . $contractor->id . "_" . $event_id);
        }
        return $funZones;
    }

    public static function renderCart($event_id)
    {

        Yii::app()->user->setState("currentCartId", "cartId_" . $event_id);
        $positions = Yii::app()->shoppingCart->getPositions();
        $items = array();
        foreach ($positions as $position) {
            $sector_id = $position->sector_id;
            $row = $position->row;
            $price = $position->price;
            if (!isset($items[$sector_id]['rows'][$row]['prices'][$price])) {
                if (!isset($items[$sector_id])) $items[$sector_id] = array('name' => $position->sector->sectorName, 'type' => $position->type, 'rows' => array());
                if (!isset($items[$sector_id]['rows'][$row])) $items[$sector_id]['rows'][$row] = array('name' => $position->editedRow, 'sold_count' => Place::getSoldFunCount($position->sector_id, $position->event_id), 'all_count' => Place::getFunCount($position->sector_id, $position->event_id), 'prices' => array());
                $items[$sector_id]['rows'][$row]['prices'][$price] = array(
                    'count' => $position->getQuantity(),
                    'places' => array($position->place => $position->editedPlace),
                );
            } else {
                $items[$position->sector_id]['rows'][$position->row]['prices'][$position->price]['count']++;
                $items[$position->sector_id]['rows'][$position->row]['prices'][$position->price]['places'][$position->place] = $position->editedPlace;
            }
        }
        foreach ($items as &$item) {
            $sum = 0;
            $count = 0;
            foreach ($item['rows'] as &$row) {
                foreach ($row['prices'] as $price => &$prices) {
                    $sum += $prices['count']*$price;
                    $count += $prices['count'];
                    ksort($prices['places']);
                    $place_text = '';
                    $buf_text = '';
                    $last_key = 0;
                    foreach ($prices['places'] as $place_id => $place_name) {
                        if ($last_key==0) {
                            $place_text = $place_name;
                            $last_key = $place_id;
                            continue;
                        }
                        if (($place_id-$last_key) == 1) {
                            $buf_text = ($buf_text=='')?','.$place_name:'-'.$place_name;
                        }else{
                            $place_text .= $buf_text.','.$place_name;
                            $buf_text = '';
                        }
                        $last_key = $place_id;
                    }
                    $place_text .= $buf_text;
                    $prices['places'] = $place_text;
                }
            }
            $item['sum'] = $sum;
            $item['count'] = $count;
            ksort($item['rows']);
        }
        $model_fake = new FakeActiveRecord;
        $model_fake->myid = 1;
        return json_encode(array(
            'html' => Yii::app()->controller->renderPartial('application.widgets.cartWidget.views._tickets', array(
                "items" => $items,
                "event_id" => $event_id,
                "model_fake" => $model_fake
            ), true, true),
            'sum' => Yii::app()->shoppingCart->getCost(),
			'count' => Yii::app()->shoppingCart->getItemsCount(),
        ));
    }


	public function getListOrganizers($event_id)
	{
		$result = array();
		$list = $this->getListRoles($event_id);
		if (!empty($list)) {
			$ids = array();
			foreach ($list as $k=>$role)
				$ids[] = $k;
			$result = Yii::app()->db->createCommand()
				->select("r.id, r.name")
				->from(Role::model()->tableName()." r")
				->join("{{role_template}} rt", "rt.role_id=r.id")
				->join(TemplateRoleAccess::model()->tableName()." a", "a.template_role_id=rt.template_id")
				->where(array("in", "r.id", $ids))
				->andWhere("a.action=:action", array(
					":action"=>"/event/eventController/create"
				))
				->queryAll();
		}
		return CHtml::listData($result, "id", "name");
	}

	/**
	 * @param int|bool $event_id
	 * @return array
	 */
	public function getListRoles($event_id = false)
	{
		$roles = Yii::app()->db->createCommand()
			->selectDistinct('r.id, r.name')
			->from('{{role}} r')
			->join('{{role_template}} rt', 'rt.role_id=r.id')
			->join('{{template_role_model}} trm', 'rt.template_id=trm.template_role_id')
			->join('{{tag}} t', 't.template_id=rt.template_id')
			->where('trm.model=:model AND t.relation_name=:relationName AND t.model_name=:modelName AND t.model_id=:event_id', array(
				":model" => get_class($this),
				":relationName" => "Role",
				":modelName" => "Event",
				":event_id"=>$event_id
			))
			->queryAll();
		$result = array();

		foreach ($roles as $role)
			$result[$role['id']] = $role['name'];
		return $result;
	}

	public function setQuoteAttributes($attributes)
	{
		foreach ($attributes as $attribute => $value)
			$this->$attribute = $value;
	}

	public function closeQuote($saveData=null)
	{
		$this->order->status = Order::STATUS_CLOSE;
		$this->order->save();
		if($saveData)
			return true;
		$ids = array_map(function($ticket){
			return $ticket->id;
		}, $this->order->tickets);
		$placeIds = array_map(function($ticket){
			return $ticket->place_id;
		}, $this->order->tickets);

		Yii::app()->db->createCommand()
			->update(Ticket::model()->tableName(), array(
                "status"=>Ticket::STATUS_CANCEL,
                "date_update"=>Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time())
            ), array("in", "id", $ids));
		Yii::app()->db->createCommand()
			->update(Place::model()->tableName(), array("status"=>Place::STATUS_SALE), array("in", "id", $placeIds));
	}

	public function getLegalDetail($type)
	{
		$legal_detail = $type."_legal_detail";
		$roleType = "role".ucfirst($type);
		return $this->$legal_detail!="" ? $this->$legal_detail : $this->$roleType->legal_detail;
	}

	public function getPlacesInit($ids)
	{
		$places = Yii::app()->db->createCommand()
			->select("p.*, tc.status as ticketStatus, tc.price as ticketPrice, tc.order_id")
			->from(Place::model()->tableName()." p")
			->join(Ticket::model()->tableName(). " tc"," tc.place_id=p.id")
			->where(array("in", "p.id", $ids))
//			->andWhere("p.status!=:statusClose", array(
//				":statusClose"=>Place::STATUS_CLOSE
//			))
			->queryAll();

		$event_id = $this->event_id;
		$sector = new Sector();
		$sector->setColors(Place::getPrices($event_id));
		$result = array();
		$sectors = $this->event->scheme->sectors;
		$sectorNames = array();

		foreach ($sectors as $oneSector)
			if ($oneSector->type == Sector::TYPE_SEAT)
				$sectorNames[$oneSector->id] = $oneSector->getSectorNameValues();


		foreach ($places as $place) {

			$placeColor = $place["ticketStatus"]==Ticket::STATUS_QUOTE_SOLD ? Place::COLOR_SOLD :($place["ticketStatus"] == Ticket::STATUS_QUOTE_RETURN || $place['order_id']!=$this->order_id ? "#cccccc" : $sector->getColor($place['price']));
			if ($place['type'] == Place::TYPE_SEAT) {
				$sectorName = $sectorNames[$place['sector_id']];
				$result[] = array(
					"id"=>Sector::encodePlace($place),
					"selectable"=>$place["ticketStatus"] != Ticket::STATUS_QUOTE_RETURN && $place["ticketStatus"] != Ticket::STATUS_CANCEL && $place['order_id']==$this->order_id ,
					"fill"=>$placeColor,
					"price"=>$place['ticketPrice'],
					"label"=>$sectorName['sector_name'].", ".$sectorName['row_name'].": ".$place['row'].", ".$sectorName['place_name'].': '.$place['place'].($place['ticketStatus'] != Ticket::STATUS_QUOTE_RETURN ? ", ціна: ".$place['ticketPrice'] : "")
				);
			}
			elseif (!isset($result["fun_zone_".$place['sector_id']])) {

				$sold = self::getFunSoldCount($place['sector_id'],$this->id);
				$hasPlaces = self::getFunCount($place['sector_id'], $this->id)-$sold > 0;

				$result["fun_zone_".$place['sector_id']] = array(
					"id"=>Sector::encodePlace($place),
					"selectable"=>$hasPlaces ? true : false,
					"fill"=>$hasPlaces ? $sector->getColor($place['price']) : "#cccccc",
					"price"=>$place['ticketPrice'],
					"sold" => $sold,
					"label"=>Sector::getSectorNameById($place['sector_id'])." ціна: ".$place['ticketPrice']
				);
			}
		}

		return array_values($result);
	}

	public static function getFunSoldCount($sector_id, $quote_id)
	{
		return Yii::app()->db->createCommand()
			->select("count(*)")
			->from(Ticket::model()->tableName()." t")
			->join(Place::model()->tableName()." p", "p.id=t.place_id")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("p.sector_id=:sector_id AND t.status=:status", array(
				":sector_id"=>$sector_id,
				":status"=>Ticket::STATUS_QUOTE_SOLD,
			))
			->andWhere(array("in", "q.id", (array)$quote_id))
			->queryScalar();
	}

	public static function getFunCount($sector_id, $quote_id)
	{
		return Yii::app()->db->createCommand()
			->select("count(*)")
			->from(Ticket::model()->tableName()." t")
			->join(Place::model()->tableName()." p", "p.id=t.place_id")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("q.id=:quote_id AND p.sector_id=:sector_id AND t.status!=:returnStatus AND t.status!=:cancelStatus", array(
				":quote_id"=>$quote_id,
				":sector_id"=>$sector_id,
				":returnStatus"=>Ticket::STATUS_QUOTE_RETURN,
				":cancelStatus"=>Ticket::STATUS_CANCEL,
			))
			->queryScalar();
	}

	public function getFunPlaceIds($ids, $status)
	{
		$result = array();
		foreach ($ids as $k=>$id) {
			if (is_array($id)) {
				$temp_ids = Yii::app()->db->createCommand()
					->select("p.id")
					->from(Place::model()->tableName()." p")
					->join(Ticket::model()->tableName()." t", "p.id=t.place_id")
					->where("p.event_id=:event_id AND p.sector_id=:sector_id AND t.status=:status AND t.order_id=:order_id", array(
						":event_id"=>$this->event_id,
						":sector_id"=>$id['id'],
						":status"=>$status,
						":order_id"=>$this->order_id
					))
					->limit($id['count'])
					->queryColumn();
				unset($ids[$k]);
				$result = array_merge($temp_ids, $result);

			}
		}

		return array_merge($ids, $result);
	}

	public function setNewPrice($ids, $price, $onScheme = false)
	{
		$criteria = new CDbCriteria();
		$criteria->compare("status", Ticket::STATUS_QUOTE_ON_SALE);
		$criteria->addInCondition("place_id", $ids);
		$builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
		$command = $builder->createUpdateCommand(Ticket::model()->tableName(), array(
            "price"=>$price,
            "date_update"=>Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time())
        ), $criteria);
		$result = $command->execute();



		if ($onScheme) {
			$criteria = new CDbCriteria();
			$criteria->compare("status", Place::STATUS_SOLD);
			$criteria->addInCondition("id", $ids);
			$builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
			$command = $builder->createUpdateCommand(Place::model()->tableName(), array(
                "price"=>$price
            ), $criteria);
			$result += $command->execute();
		}

		$this->order->updateSum();

		return $result;
	}

	public function getContractorsList($type)
	{
		$roles = array();
		if ($type == Quote::PASS_TYPE_NEW) {
            $roles = Role::getRoleList(true);
			//$roles = $this->getListRoles($this->event_id);
			unset($roles[$this->role_to_id]);
		} else {
			$quotes = Quote::model()->with(array("roleTo"))->findAllByAttributes(array("event_id"=>$this->event_id));
			foreach ($quotes as $quote) {
				$countSum = $quote->getAllCountSum();
				$roles[$quote->id] = "[".$quote->id."] ".$quote->roleTo->name." | ".$quote->typeQuote." | ".$countSum['count']." шт";
			}
		}
		return $roles;
	}

	public function getAllCountSum()
	{
		$sum = $this->getSum(Ticket::STATUS_QUOTE_ON_SALE)+$this->getSum(Ticket::STATUS_QUOTE_SOLD);

		$count = $this->getCount(Ticket::STATUS_QUOTE_ON_SALE)+$this->getCount(Ticket::STATUS_QUOTE_SOLD);

		return array(
			"sum"=>$sum,
			"count"=>$count
		);
	}

	public function getSum($status)
	{
		$sum =  Yii::app()->db->createCommand()
			->select("SUM(t.price)")
			->from(Ticket::model()->tableName()." t")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("q.id=:id AND t.status=:status", array("id"=>$this->id, ":status"=>$status))
			->queryScalar();
		return $sum ? $sum : 0;
	}

	public function getCount($status)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(Ticket::model()->tableName()." t")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("q.id=:id AND t.status=:status", array("id"=>$this->id, ":status"=>$status))
			->queryScalar();
	}

	public function getTypeQuote()
	{
		switch($this->printed) {
			case self::STATUS_PRINTED:
				return "Фізичний продаж";
			case self::STATUS_NO_PRINTED:
				return "Продаж на сайті";
			default :
				return "";
		}
	}

	public static function getQuoteList()
	{
		$quotesData =  Yii::app()->db->createCommand()
			->select("t.id, t.order_id, t.name, r.name as roleName")
			->from(Quote::model()->tableName()." t")
			->join(Role::model()->tableName()." r", "r.id=t.role_to_id")
			->queryAll();
		$order_ids = [];
		foreach ($quotesData as $qD)
			$order_ids[] = $qD["order_id"];
		$ticketsData =  Yii::app()->db->createCommand()
			->select("t.order_id, t.id")
			->from(Ticket::model()->tableName()." t")
			->where(['in', 'order_id', $order_ids])
			->queryAll();
		$counts = [];
		foreach ($ticketsData as $ticket) {
			if(isset($counts[$ticket["order_id"]]))
				$counts[$ticket["order_id"]] += 1;
			else
				$counts[$ticket["order_id"]] = 1;
		}
		$quotes = [];
		foreach ($quotesData as $quote) {
			if(isset($counts[$quote["order_id"]]))
				$count = $counts[$quote["order_id"]];
			else
				$count = 0;
			$quotes[$quote["id"]] = $quote["id"]." ".$quote["name"]." / ".$quote["roleName"]." / ".$count." шт.";
		}
		return $quotes;
	}

}
