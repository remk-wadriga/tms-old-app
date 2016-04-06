<?php

/**
 * This is the model class for table "{{sector}}".
 *
 * The followings are the available columns in table '{{sector}}':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $status
 * @property integer $scheme_id
 * @property string $places
 * @property string $params
 * @property string $date_update
 * @property integer $type_sector_id
 * @property integer $type_row_id
 * @property integer $type_place_id
 * @property integer $backend
 * @property integer $frontend
 *
 * The followings are the available model relations:
 * @property Place[] $places0
 * @property TypeSector $typeSector
 * @property TypePlace $typePlace
 * @property TypeRow $typeRow
 * @property Scheme $scheme
 */
class Sector extends CActiveRecord implements IECartPosition
{

	const TYPE_SEAT = 1;
	const TYPE_FUN = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_NOACTIVE = 0;
    const MAX_FUN = 5;
	public static $type = array(
		self::TYPE_SEAT => 'Сидячий сектор',
		self::TYPE_FUN => 'Фан-зона'
	);
    public $price = 0;
    public $amount;
	public $row_name;
	public $col_name;
    public $event_id;
	public $_colors = array();

	public static function getFunSectors($scheme_id, $getIds = false)
	{
		$sectors = self::model()->findAllByAttributes(array(
			"type"=>self::TYPE_FUN,
			"scheme_id"=>$scheme_id
		));
		$result = array();
        if ($getIds) {
            $result = array_map(function($sector){
                return (int)$sector->id;
            }, $sectors);
        } else {
            foreach ($sectors as $sector)
                $result[$sector->id] = array(
                    "class"=>"fun_zone"
                );
        }
		return $result;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sector the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getName()
    {
        return "Сектор";
    }

    /**
     * @param $place
     * @return string Parsed Row Place Sector ids from scheme place
     */
    public static function encodePlace($place)
    {
        return "row".$place['row']."col".$place['place']."sector".$place['sector_id'];
    }

	public static function rgb2hex($color)
	{
		$rgb = explode(",", $color);
		$hex = "#";
		$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

		return $hex;
	}

	public static function getSectorNameById($id)
	{
		$model = self::model()->findByPk($id);
		return $model->sectorName;
	}

	public static function getFunAmount($sector_id)
	{
		$model = self::model()->findByPk($sector_id);
		if ($model && $model->type == self::TYPE_FUN)
			return $model->places->fun_zone->amount;
		return false;
	}

    function getId()
    {
        return 'Sector' . $this->id;
    }

    function getPrice()
    {
        if ($this->price==0&&$this->type == self::TYPE_FUN) {
            $this->price = Yii::app()->db->createCommand()
                ->select("MAX(price)")
                ->from(Place::model()->tableName())
                ->where("sector_id=:sector_id AND event_id=:event_id", array(
                    ":sector_id"=>$this->id,
                    ":event_id"=>$this->event_id
                ))
                ->queryScalar();
        }
        return $this->price;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sector}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, scheme_id', 'required'),
			array('type, status, scheme_id, type_row_id, type_place_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('places, type_sector_id, params, price, event_id', 'safe'),
			array('name', 'validateName'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, type, status, scheme_id, places, frontend, backend, params, type_sector_id, type_row_id, type_place_id', 'safe', 'on'=>'search'),
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
			'typeSector' => array(self::BELONGS_TO, 'TypeSector', 'type_sector_id'),
			'typeRow' => array(self::BELONGS_TO, 'TypeRow', 'type_row_id'),
			'typePlace' => array(self::BELONGS_TO, 'TypePlace', 'type_place_id'),
			'scheme' => array(self::BELONGS_TO, 'Scheme', 'scheme_id'),
		);
	}

	public function validateName($attribute, $params)
	{
		if(self::model()->exists("name=:name AND scheme_id=:scheme_id AND id!=:id AND type_sector_id=:type_sector_id", array(":name"=>$this->name, ":type_sector_id"=>$this->type_sector_id, ":scheme_id"=>$this->scheme_id, ":id"=> !$this->isNewRecord ? $this->id : "0")))
			$this->addError($attribute, "Сектор з такою назвою вже існує") ;
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->type_sector_id != "" && $this->type_sector_id !== 0 && !TypeSector::model()->exists("id=:id", array("id"=>$this->type_sector_id))) {
				$type = TypeSector::model()->findByAttributes(array("name"=>$this->type_sector_id));
				if ($type)
					$this->type_sector_id = $type->id;
				else {
					$model = new TypeSector();
					$model->name = $this->type_sector_id;
					if ($model->save())
						$this->type_sector_id = $model->id;
				}
			}

			$this->places = json_encode($this->places);
			return true;
		} else
			return false;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->places = json_decode($this->places);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'type' => 'Тип',
			'status' => 'Status',
			'scheme_id' => 'Схема',
			'places' => 'Місця',
			'type_sector_id' => 'Префікс сектора',
			'type_row_id' => 'Тип ряду',
			'type_place_id' => 'Тип місця',
            'amount' => 'Кількість місць',
            'params' => 'Параметри',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('status',$this->status);
		$criteria->compare('scheme_id',$this->scheme_id);
		$criteria->compare('places',$this->places,true);
		$criteria->compare('type_sector_id',$this->type_sector_id);
		$criteria->compare('type_row_id',$this->type_row_id);
		$criteria->compare('type_place_id',$this->type_place_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function behaviors()
	{
		return array(
			'BeforeDeleteBehavior'=>array(
				'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
			),
		);
	}

	/**
	 * @param $data
	 * @param bool $intName
	 * @return bool boolean return when saved or no
	 */
	public function setSector($data, $intName = false)
	{

		$info = $data->info;
		$this->name = $info->name;
		$this->type = isset($info->type) ? $info->type : self::TYPE_SEAT;
		$this->type_sector_id = $info->prefix != "" && $info->prefix !== 0 ? ($intName ? $info->prefix : TypeSector::getTypeIdByName($info->prefix)) : "";


		if (isset($data->scheme)) {
            $this->type_row_id = $intName ? $info->row_name : TypeRow::getTypeIdByName($info->row_name);
            $this->type_place_id = $intName ? $info->col_name : TypePlace::getTypeIdByName($info->col_name);
			$places = json_decode($data->scheme);
			if (!$this->isNewRecord) {
				if (isset($places->cell->simple_cell)) {
					$new_places = $places->cell->simple_cell;
					if (!isset($this->places->cell->simple_cell))
						$this->places->cell->simple_cell = new stdClass();
					foreach ($this->places->cell->simple_cell as $place) {
						foreach ($new_places as $k=>$new_place) {
							if ($new_place->id == $place->id) {
								if (isset($place->visual))
									$new_place->visual = $place->visual;
								else
									$new_place->visual = new stdClass();
								break;
							}
						}
					}
				}
				if (isset($this->places->visualChanges))
					$places->visualChanges = $this->places->visualChanges;
			}
		} elseif (isset($data->fun_zone)) {
			$this->type = self::TYPE_FUN;
            $amount = isset($info->amount) ? $info->amount : 0;
            $result['fun_zone'] = array("amount"=>(int)$amount);
            if (isset($this->places->visual))
                $result['visual'] = $this->places->visual;
			$places = $result;
		}
		$this->places = $places;
		$this->date_update = null;
		if ($this->save()) {
			if (isset($data->copy))
            	$this->refreshSector($data->copy);
			return true;
		} else {
			return false;
		}
	}

    public function refreshSector($copy_id)
    {

        if ($this->type == self::TYPE_SEAT) {
			$this->saveAttributes(array("places"=>str_replace("sector".$copy_id, "sector".$this->id, $this->places)));
        } else {
            return true;
        }
    }

	/**
	 * @param $data
	 * @return array
	 */
	public function getSector($data)
	{
		$result = array();

		if(isset($data->all) && $data->all == true) {
			$result['id'] = $this->id;
			switch($this->type) {
				case self::TYPE_FUN:
					$result['fun_zone'] = $this->places;
					break;
				case self::TYPE_SEAT:
					$result['scheme'] = $this->places;
					break;
				default:
					break;
			}
		}
		return $result;
	}

	/**
	 * @param bool|integer $event_id
	 * @param array $inCart
	 * @return array
	 */
	public function getVisual($event_id=false, $inCart = array())
	{
		$prices = array();
		$tickets = array();
		$reserve = array();

		if ($event_id) {
//            $dependency = new CDbCacheDependency("SELECT MAX(date_add) from tbl_place where event_id=".$event_id." AND sector_id=".$this->id);
			$prices = Yii::app()->db->createCommand()
				->select("*")
				->from("{{place}}")
				->where("event_id=:event_id AND sector_id=:sector_id AND status!=:status", array(
					":event_id"=>$event_id,
					":sector_id"=>$this->id,
					":status"=>Place::STATUS_CLOSE,
				))
				->queryAll();
			$tickets = $this->getSoldPlaces($event_id, $this->id);
			$reserve = $this->getReservePlaces($event_id, $this->id);
		}

		return $this->getVisualInfo($event_id, $prices, $tickets, $inCart, false, $reserve);
	}

	public function getSoldPlaces($event_id, $sector_id, $quote=false)
	{

		if ($quote)
			$condition = "status=".Ticket::STATUS_QUOTE_SOLD;
        else
			$condition = "pay_status=".Ticket::PAY_PAY;


        $sql = Yii::app()->db->createCommand()
            ->select("place_id")
            ->from(Ticket::model()->tableName())
            ->where("event_id=$event_id AND sector_id=$sector_id")
            ->andWhere("status!=".Ticket::STATUS_CANCEL." AND status!=".Ticket::STATUS_QUOTE_RETURN)
            ->andWhere($condition)->getText();
        $dependency = new CDbCacheDependency("SELECT MAX(date_update) from tbl_ticket where event_id=".$event_id." AND sector_id=".$sector_id);

		$tickets = Yii::app()->db->cache(6000,$dependency)->createCommand($sql)->queryColumn();
        $dependency = new CDbCacheDependency("SELECT MAX(id) from tbl_ticket_temp where event_id=".$event_id." AND sector_id=".$sector_id);

        $sql = Yii::app()->db->createCommand()
            ->select("place_id")
            ->from(TicketTemp::model()->tableName())
            ->where("event_id=$event_id AND sector_id=$sector_id")
            ->getText();
        $tempTickets = Yii::app()->db->cache(6000,$dependency)->createCommand($sql)->queryColumn();
		return array_merge($tickets,$tempTickets);
	}

	public function getReservePlaces($event_id, $sector_id)
	{
        $sql =  Yii::app()->db->createCommand()
            ->select("place_id")
            ->from(Ticket::model()->tableName())
            ->where("event_id=$event_id AND sector_id=$sector_id")
            ->andWhere("status!=".Ticket::STATUS_CANCEL." AND status!=".Ticket::STATUS_QUOTE_RETURN)
            ->andWhere("pay_status=".Ticket::PAY_NOT_PAY)
            ->getText();
        $dependency = new CDbCacheDependency("SELECT MAX(date_update) from tbl_ticket  where event_id=".$event_id." AND sector_id=".$sector_id);

		$places = Yii::app()->db->cache(6000,$dependency)->createCommand($sql)->queryColumn();
		$dependency = new CDbCacheDependency("SELECT MAX(id) FROM tbl_ticket_temp");
		$tempPlaces = Yii::app()->db->cache(6000, $dependency)->createCommand()
			->select("place_id")
			->from(TicketTemp::model()->tableName())
			->where("event_id=:event_id AND sector_id=:sector_id",array(
				":event_id"=>$event_id,
				":sector_id"=>$sector_id
			))
			->queryColumn();

		return array_merge($places, $tempPlaces);
	}


	public function getVisualInfo($event_id=false, $prices=array(), $ticket_ids=array(), $inCart=array(), $quote = false, $reserve=array())
	{
		$k = false;
		$key = false;
		$haystack = array();
		$places = $this->places;
		$controller_id = Yii::app()->controller->id;
        $result = Yii::app()->cache->get($controller_id."sector_".$this->id.serialize($prices).$event_id.serialize($ticket_ids).serialize($inCart).serialize($reserve).serialize($quote));

        if (!$result) {
            if (isset($places->visualChanges))
                $result['visualChanges'] = $places->visualChanges;
			if (empty($this->_colors)&&$event_id)
				$this->setColors(Place::getPrices($event_id));
			if ($event_id&&is_array($prices)&&!empty($prices))
				$haystack = array_map(function($item){
					return array("row"=>$item['row'], "place"=>$item['place'], "sector"=>$this->id);
				}, $prices);

			if ($this->type == self::TYPE_SEAT) {
				$rowColNames = array("row"=>array(),"col"=>array());

				foreach ($places->cell->header_col_cell as $item) {
					$start = strpos($item->id, "col")+3;
					$end = strpos($item->id, "sector")!==false ? strpos($item->id, "sector")-$start : strlen($item->id)-1-$start;
					$rowColNames['col'][substr($item->id, $start, $end)] = $item->edited->col_info;
				}
				foreach ($places->cell->header_row_cell as $item) {
					$start = strpos($item->id, "row")+3;
					$end = strpos($item->id, "col")!==false ? strpos($item->id, "col")-$start : strlen($item->id)-1-$start;
					$rowColNames['row'][substr($item->id, $start, $end)] = $item->edited->row_info;
				}

				foreach ($places->cell->simple_cell as $place) {
					$sold = false;
					$isInCart = false;
					$reserved = false;
					if ($event_id) {
						$id = self::getRowPlaceSector($place->id);
						$k = array_search(array("row"=>$id['row'], "place"=>$id['place'], "sector"=>$id['sector_id']), $haystack);

						if ($k!==false) {
							$sold = in_array($prices[$k]['id'], $ticket_ids)||$prices[$k]['status']==Place::STATUS_SOLD||$prices[$k]['status']==Place::STATUS_CLOSE;
							$reserved = in_array($prices[$k]['id'], $reserve);
							$isInCart = in_array($prices[$k]['id'], $inCart);
							$key = array(
								"price" => $prices[$k]['price'],
								"server_id" => $prices[$k]['id'],
								"fill" => $sold&&!$isInCart&&!$reserved ? Place::COLOR_SOLD : ($reserved ? Place::COLOR_RESERVED : ($isInCart&&$controller_id!="cashier" ? "#05ca25" : $this->getColor($prices[$k]['price']))),
								"partner" => new stdClass(),
								"status" => $prices[$k]['status'],
							);
						}
						if (!$k&&Yii::app()->controller->id=="quote")
							$price_info = array(
								"fill"=>"#cccccc"
							);
					}
					$placeName = self::getRowPlaceSector($place->id);
					if (!empty($rowColNames)) {
						if(isset($rowColNames['row'][$placeName['row']]))
							$placeName['row'] = $rowColNames['row'][$placeName['row']];
						if(isset($rowColNames['col'][$placeName['place']]))
							$placeName['place'] = $rowColNames['col'][$placeName['place']];

					}
					if (isset($place->edited)) {
						if(isset($place->edited->row_info))
							$placeName['row'] = $place->edited->row_info;
						if(isset($place->edited->col_info))
							$placeName['place'] = $place->edited->col_info;
					}
					$label = $this->getPlaceName($placeName);
					$price = $k!==false? $label.", ціна: ".$key['price'] : "";
					$system_controller = in_array($controller_id, array("sector", "constructor", "quote"));
					$result["cell"]["simple_cell"][] = array(
						"id"=>$place->id,
						"visual"=>isset($place->visual) ? $place->visual : new stdClass(),
						"price_info"=>$k!==false ? (object)$key : (isset($price_info) ? $price_info : new stdClass()),
						"type"=>$this->type,
						"label"=>$sold&&!$reserved? $label." Продано".($system_controller ? " ".$price:""):($reserved ? $label." Заброньовано".($system_controller ? " ".$price:"") : ($k!==false? $label.", ціна: ".$key['price'] : ($system_controller ? $label:""))),
						"selectable"=>(!empty($prices)&&($k!==false||$controller_id=='constructor')&&(!$sold||$quote)&&!$isInCart)||($isInCart&&$controller_id=="cashier") ? true :
							($controller_id=="sector"||(($controller_id=="constructor"||$controller_id=="scheme")&&empty($prices)) ? true:false),
						"quote_id" => $k !== false && $quote!=false && isset($prices[$k]['quote_id']) ? $prices[$k]['quote_id'] : array()
					);

				}

				$result = $this->getSvgResponse($result);

			} elseif ($this->type == self::TYPE_FUN) {

				$price_info = array();
				if ($event_id) {

					$price_info = $this->getPriceInfo($event_id, $quote);

				}

				$result = $this->getSvgResponse((object)$price_info);

			}
            if ($event_id)
                Yii::app()->cache->set($controller_id."sector_".$this->id.serialize($prices).$event_id.serialize($ticket_ids).serialize($inCart).serialize($reserve).serialize($quote), $result, 6000,
                    new CDbCacheDependency("SELECT MAX(date_update) FROM tbl_sector")
                );
        }

        return $result;
	}

	public function setColors($prices)
	{
		$colors = array('#67f5f9','#05e2d6','#1d9973','#21ccf4','#2792e2','#a9a6d2','#7075c4','#f96ed9','#f94161','#fcd363','#f99653','#5cbb5c','#92af69','#e33afc');
		foreach ($prices as $k=>$price) {
			$this->_colors[$price] = current($colors);
			if(next($colors))
				continue;
			else
				reset($colors);
		}
		return $this->_colors;
	}

    /**
     * @param $idRowSector
     * @return array Parsed Row Place Sector ids from scheme place
     */
    public static function getRowPlaceSector($idRowSector)
    {
        $id = preg_split("/\D+/", $idRowSector);
        array_shift($id);
        return array(
            "row"=>$id[0],
            "place"=>$id[1],
            "sector_id"=>$id[2]
        );
    }

	public function getColor($price)
	{
		return isset($this->_colors[$price]) ? $this->_colors[$price] : "";
	}

	/**
	 * @param $placeName Place
	 * @return string
	 */
	public function getPlaceName($placeName)
	{
		return $this->sectorName.", ".$this->typeRow->name.": ".$placeName['row'].", ".$this->typePlace->name.": ".$placeName['place'];
	}

	public function getSvgResponse($result)
	{
		$controller_id = Yii::app()->controller->id;
		$parsed = array();
		if ($this->type == self::TYPE_SEAT && isset($result['cell'])) {
			foreach ($result['cell']['simple_cell']as $k=>$place) {
				if (isset($place['visual']->context)) {
					$info = $this->parseVisual($place['visual']->context);
					$params = $info['params'];
					$place = (object)$place;
					$data_info = array(
						"id"=>$place->id,
						"sector_id"=>$this->id,
						"data-selectable"=>$place->selectable ? "true" : "false",
						"data-quote-id"=>!empty($place->quote_id) ? (is_array($place->quote_id) ? $place->quote_id[0] : $place->quote_id) : "",
						"data-label"=>$place->label,
						"data-server_id"=>isset($place->price_info->server_id) ? $place->price_info->server_id : "",
						"fill"=>isset($place->price_info->fill) ? $place->price_info->fill : (in_array($controller_id, array(
							"constructor", "quote", "api", "cashier"
							))? "#cccccc" : "#cc0000"),
						"data-price"=>isset($place->price_info->price) ? $place->price_info->price : "",
						"data-status"=>isset($place->price_info->status) ? $place->price_info->status : "",
						"data-type"=>$place->type,
					);
					$parsed[] = CHtml::tag($info["tag"], $params+$data_info);
					unset($result['cell']['simple_cell'][$k]);
				} else {
                    $result['cell']['simple_cell'][$k] = $result['cell']['simple_cell'][$k]+array(
                            "fill"=>"#cc0000"
                        );
                }
			}
			$result['cell']['simple_cell'] = array_values($result['cell']['simple_cell']);

			$result['visual'] =  implode("", $parsed);

		} else {
			if(isset($this->places->visual)) {
				$info = $this->parseVisual($this->places->visual->context);
				$params = $info['params'];

				$label = !in_array($controller_id, array("api", "cashier"))||isset($result->count)&&isset($result->sold_count)&&$result->count-$result->sold_count!=0 ?
					$this->sectorName . (isset($result->price) ? ', ціна: '.$result->price : '') :
					($result->count!=0 ? "Немає вільних місць" : "");
				$data_info = array(
					"id"=>"row0col0sector".$this->id,
					"sector_id"=>$this->id,
					"data-amount"=>isset($result->amount) ? $result->amount : "",
					"data-count"=>isset($result->count) ? $result->count : "",
					"data-quote_id"=>isset($result->quote_id) ? json_encode($result->quote_id) : "",
					"data-label"=>$label,
					"fill"=>isset($result->fill) ? $result->fill : "#cccccc",
					"data-price"=>isset($result->price) ? $result->price : "",
					"data-type"=>self::TYPE_FUN ,
					"data-selectable" => isset($result->price)||!isset($quote_id)||($controller_id=='constructor'||$controller_id=='scheme') ? "true" : "false"
				);

				$result = CHtml::tag($info["tag"], $params+$data_info);
			}
		}
		return $result;
	}

	private function parseVisual($context) {
		$startD = strpos($context, " d=\"");
		$startClass = strpos($context, " class=\"");
		$transformStart = strpos($context, " transform=\"");
		$startX = strpos($context, " x=\"");
		$startY = strpos($context, " y=\"");
		$startW = strpos($context, " width=\"");
		$startH = strpos($context, " height=\"");
		$startOp = strpos($context, " opacity=\"");
		$tag = str_word_count($context, 1)[0];
		$class = "";
		$params = array();
		if ($startD) {
			$len = strlen(" d=\"");
			$end = strpos($context, "\"", $startD+$len);
			$d = substr($context, $startD+$len, $end-$startD-$len);
			if ($d != "")
				$params =$params + array("d"=>$d);
		}
		if ($startClass) {
			$len = strlen(" class=\"");
			$end = strpos($context, "\"", $startClass+$len);
			$class = substr($context, $startClass+$len, $end-$startClass-$len);
			if ($class == "rect")
				$params =$params + array("class"=>$class." native");
			elseif ($class != "")
				$params =$params + array("class"=>$class);
		}
		if ($startX) {
			$len = strlen(" x=\"");
			$end = strpos($context, "\"", $startX+$len);
			$x = substr($context, $startX+$len, $end-$startX-$len);
			if ($x != "")
				$params =$params + array("x"=>$x);
		}
		if ($startY) {
			$len = strlen(" y=\"");
			$end = strpos($context, "\"", $startY+$len);
			$y = substr($context, $startY+$len, $end-$startY-$len);
			if ($y != "")
				$params =$params + array("y"=>$y);
		}
		if ($startW) {
			$len = strlen(" width=\"");
			$end = strpos($context, "\"", $startW+$len);
			$width = substr($context, $startW+$len, $end-$startW-$len);
			if ($width != "")
				$params =$params + array("width"=>$width);
		}
		if ($startH) {
			$len = strlen(" height=\"");
			$end = strpos($context, "\"", $startH+$len);
			$height = substr($context, $startH+$len, $end-$startH-$len);
			if ($height != "")
				$params =$params + array("height"=>$height);
		}
		if ($transformStart) {
			$len = strlen(" transform=\"");
			$end = strpos($context, "\"", $transformStart+$len);
			$transform = substr($context, $transformStart+$len, $end-$transformStart-$len);
			if ($transform != "")
				$params =$params + array("transform"=>$transform);
		}
		if ($startOp) {
			$len = strlen(" opacity=\"");
			$end = strpos($context, "\"", $startOp+$len);
			$opacity = substr($context, $startOp+$len, $end-$startOp-$len);
			if ($opacity != "")
				$params =$params + array("opacity"=>$opacity);
		}
//
//		if ($class == "rect native" || $class == "rect" ) {
//			$params = $params+array(
//					"rx"=>20
//				);
//		}
		return array("tag"=>$tag, "params"=>$params);

	}

	public function getPriceInfo($event_id, $quote = false)
	{
		if (empty($this->_colors)&&$event_id)
			$this->setColors(Place::getPrices($event_id));

		$price = Place::getFunPrice($this->id, $event_id);
		$count = Place::getFunCount($this->id, $event_id);

		$color = $price ? $this->getColor($price) : "#cccccc";

		$inQuote = false;
		$quote_ids = array();
		if ($quote) {
			$inQuote = Quote::getIsInQuote($quote, $this->id);
			$color = !$inQuote ? "#cccccc" : $color;
			$soldCount = Quote::getFunSoldCount($this->id, $quote);
			$quote_ids = Quote::getIdsBySector($this->id, $event_id);
		}

		$price_info = array(
			"amount"=>$this->places->fun_zone->amount,
			"count"=>$inQuote ? $inQuote : $count,
			"sold_count"=>isset($soldCount) ? $soldCount : Place::getSoldFunCount($this->id, $event_id),
			"fill"=>$color,
		);
		if ($count)
			$price_info['price'] = $price;
		if ($quote) {
			$price_info['inQuote'] = (bool)$inQuote;
			$price_info['quote_id'] = $quote_ids;
		}
		return (object)$price_info;
	}

	public function getVisualQuote($event_id, $quote_id)
	{

		$inCondition = array("in", "q.id", (array)$quote_id);
		$places = Yii::app()->db->createCommand()
			->select("p.*, t.status as status, t.price as price, q.id as quote_id")
			->from(Place::model()->tableName()." p")
			->join(Ticket::model()->tableName()." t", "t.place_id=p.id")
			->join(Quote::model()->tableName()." q", "q.order_id=t.order_id")
			->where("p.sector_id=:sector_id AND t.status != :statusReturn AND p.status!=:statusClose", array(
				":sector_id"=>$this->id,
				":statusReturn"=>Ticket::STATUS_QUOTE_RETURN,
				":statusClose"=>Place::STATUS_CLOSE
			))
			->andWhere($inCondition)
			->queryAll();
		$tickets = $this->getSoldPlaces($event_id, $this->id, true);

		return $this->getVisualInfo($event_id, $places, $tickets, array(), $quote_id);
	}

	public function getPlacesInCart($event_id)
	{
	}

	/**
	 * @param $data
	 * @return bool
	 * @throws CDbException
	 */
	public function setVisual($data)
	{
		$places = $this->places;
		$params = array();
		switch ($this->type) {
			case self::TYPE_FUN :
				$places = (object)array(
					"fun_zone" => $places->fun_zone,
					"visual" => (object)$data->visual
				);
				break;
			case self::TYPE_SEAT :
				$new_places = $data->scheme->cell->simple_cell;
				$places->visualChanges = true;
				foreach ($places->cell->simple_cell as $k=>&$place)
					foreach ($new_places as $key=>$new_place)
						if ($place->id == $new_place->id) {
							$place->visual = $new_place->visual;
							unset($new_places[$key]);
							break;
						}


				break;
			default:
				break;
		}
        $params = $this->saveParams($data);
		$this->saveAttributes(array("places"=>json_encode($places), "params"=>json_encode($params)));
		return true;
	}

	public function saveParams($data)
	{
		$params = $this->getSectorParams();
		foreach ((array)$data as $k=>$param)
			if (!in_array($k, array("sector_id", "status", "scheme", "visual", "fun_zone"))) {
				if ($param==="") {
					unset($params[$k]);
					continue;
				}
				$params[$k] = $param;
			}
		return $params;
	}

    public function getSectorParams()
    {
        $result = array();
        $params = (array)json_decode($this->params);
        if (!empty($params)&&is_array($params)) {
            foreach ($params as $key=>$param) {
                $result[$key] = $param;
            }
        }
        return $result;
    }

	public function getPlacesCount()
	{
		switch ($this->type) {
			case self::TYPE_SEAT :
				return count($this->places->cell->simple_cell);
				break;
			case self::TYPE_FUN :
				return $this->places->fun_zone->amount;
				break;
			default:
				return 0;
				break;
		}
	}

	public function getSectorName()
	{
		return ($this->typeSector ? $this->typeSector->name." " : "").$this->name;
	}

	public function getSectorNameValues()
	{
		return array(
			"sector_name"=>$this->sectorName,
			"row_name"=>$this->typeRow->name,
			"place_name"=>$this->typePlace->name
		);
	}

    public function getMaxFun()
    {
        $inSale = Yii::app()->db->createCommand()
            ->select("COUNT(*)")
            ->from(Place::model()->tableName())
            ->where("sector_id=:sector_id AND status=:status", array(
                ":sector_id"=>$this->id,
                ":status"=>Place::STATUS_SALE
            ))
            ->queryScalar();
        if ($inSale<6&&$inSale>0)
            return $inSale;
        elseif ($inSale == 0)
            return 0;
        return self::MAX_FUN;
    }


}
