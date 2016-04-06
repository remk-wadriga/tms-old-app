<?php

/**
 * This is the model class for table "{{place}}".
 *
 * The followings are the available columns in table '{{place}}':
 * @property integer $id
 * @property integer $row
 * @property integer $place
 * @property integer $event_id
 * @property integer $sector_id
 * @property integer $price
 * @property string $code
 * @property integer $type
 * @property string $edited_row
 * @property string $edited_place
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Sector $sector
 * @property Event $event
 * @property Platform[] $platforms
 */
class Place extends CActiveRecord implements IECartPosition
{

	const STATUS_SOLD = 2;
	const STATUS_SALE = 1;
	const STATUS_CLOSE = 0;
	const TYPE_FUN = 2;
	const TYPE_SEAT = 1;
	const PRICE_SET = 1;
	const PRICE_UP = 2;
	const PRICE_DOWN = 3;
	const PLACE_UP = 1;
	const PLACE_DOWN = 2;
    const COLOR_NOPRICE = "#cccccc";
	const COLOR_SOLD = "#a5bfbd";
	const COLOR_RESERVED = "#ffef00";
	public static $place = array(
		self::PLACE_UP => "Додати місця",
		self::PLACE_DOWN => "Прибрати місця"
	);
	public static $price = array(
		self::PRICE_SET => "Встановити ціну",
		self::PRICE_UP => "Підняти ціну на",
		self::PRICE_DOWN => "Опустити ціну на"
	);
	public $_code;
	public $type_code;

	public static function getNullCount($event_id)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(self::model()->tableName())
			->where("event_id=:event_id AND price=0", array(":event_id" => $event_id))
			->queryScalar();
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{place}}';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Place the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public static function setPrice($data,$event)
	{
		$table = self::model()->tableName();
		$query = 'SELECT * from ' . $table . ' WHERE event_id=' . $data->event_id . " AND ";
		$places = array();
		$response_1 = array();
		$response_2 = array();

		$result = self::getConditionResult($data->places);
		$result = array_merge($result["result_seat"], $result["result_fun"]);
		switch ($data->typeSet) {
			case self::PRICE_UP :
				$price = "IFNULL(`price`,0)+" . $data->price;
				break;
			case self::PRICE_DOWN :
				$price = "`price`-" . $data->price;
				break;
			default:
				$price = $data->price;
				break;
		}

		if (!empty($result)) {
			$query .= "(" . implode(" OR ", $result) . ")";
			$places = Yii::app()->db->createCommand($query)->queryAll();
		}

		$result_fun = array();
		if (!empty($places)) {
			$query = 'UPDATE ' . $table . ' set price=' . $price . '  WHERE event_id=' . $data->event_id . " AND ";
			$result = array();

			foreach ($places as $place) {
				if ($data->typeSet == self::PRICE_DOWN && $place['price'] - $data->price <= 0)
					return false;

				if ($place['type'] == self::TYPE_SEAT) {
					$result[] = "(id=" . $place['id'] . ")";
					$keys = array_keys($data->places, (object)array(
						"id" => "row" . $place['row'] . "col" . $place['place'] . "sector" . $place['sector_id'],
						"sector_id" => $place['sector_id'],
						"server_id" => $place['id'],
						"type" => $place['type']
					));
				} else {
					$str = "(sector_id=" . $place['sector_id'] . ")";
					if (in_array($str, $result_fun))
						continue;

					$result_fun[] = $str;
					$keys = array_keys($data->places, (object)array(
						"id" => "row0col0sector" . $place['sector_id'],
						"sector_id" => $place['sector_id'],
						"type" => $place['type']
					));
				}
				foreach ($keys as $key)
					unset($data->places[$key]);

				if (empty($data->places))
					break;
			}
			$query .= "(" . implode(" OR ", array_merge($result, $result_fun)) . ")";

			$saved = Yii::app()->db->createCommand($query)->execute();

			if ($saved && !empty($result)) {
				$query = "SELECT `id` FROM " . $table . " WHERE " . implode(" OR ", $result);
				$response_1 = Yii::app()->db->createCommand($query)->queryColumn();
			} elseif (!$saved)
				unset($result_fun);
		}
		if (!empty($data->places) && $data->typeSet != self::PRICE_DOWN) {
			$result = array();
			$search_result = array();
			$barCodes = array();
			foreach ($data->places as $place) {
				$code = self::model()->generateCode($event, $barCodes);
				$barCodes[] = $code;
				$id = Sector::getRowPlaceSector($place->id);
				$result[] = "(" . implode(",", array(
						"row" => $id['row'],
						"place" => $id['place'],
						"event_id" => $data->event_id,
						"sector_id" => $place->sector_id,
						"price" => $data->price,
						"code" => $code,
						"type" => $place->type
					)) . ")";
				$search_result[] = array(
					"code=" . $code,
					"event_id=" . $data->event_id
				);
			}

			$columns = "(row, place, event_id, sector_id, price, code, type)";
			$sql = "INSERT into " . $table . " " . $columns . " VALUES " . implode(",", $result);

			$saved = Yii::app()->db->createCommand($sql)->execute();
			if ($saved) {
				$result = array();
				foreach ($search_result as $search)
					$result[] = "(" . implode(" AND ", $search) . ")";

				$query = "SELECT `id` FROM " . $table . " WHERE " . implode(" OR ", $result);
				$response_2 = Yii::app()->db->createCommand($query)->queryColumn();
			}
		}
		$ids = array_merge($response_1, $response_2);
		$response = array();
		if (!empty($ids)) {
			$sector = Sector::model();
			$sector->setColors(self::getPrices($data->event_id));
			$places = Yii::app()->db->createCommand()
				->select('id as server_id, row, place, sector_id, status, price')
				->from($table)
				->where(array("in", "id", $ids))
				->queryAll();
			$sectors = array();
			foreach ($places as $place) {
				if (!isset($sectors[$place['sector_id']]))
					$sectors[$place['sector_id']] = Sector::model()->findByPk($place['sector_id']);
				$response[] = array(
					"id" => "row" . $place['row'] . "col" . $place['place'] . "sector" . $place['sector_id'],
					"price_info" => (object)array(
						"price" => $place['price'],
						"server_id" => $place['server_id'],
						"fill" => $sector->getColor($place['price']),
						"partner" => new stdClass(),
						"status" => $place['status']
					),
					"label" => $sectors[$place['sector_id']]->getPlaceName($place) . ", ціна: " . $place['price']
				);
			}
		}

		if (!empty($result_fun)) {
			$ids = array();
			foreach ($result_fun as $fun)
				$ids[] = preg_replace("/[^0-9]+/", '', $fun);

			$sectors = Sector::model()->findAllByPk($ids);
			foreach ($sectors as $sector) {
				$visual = (object)$sector->getPriceInfo($data->event_id);
				$response[] = array(
					"id" => "row0col0sector" . $sector->id,
					"price_info" => $visual,
					"label" => $sector->sectorName . ", ціна: " . $visual->price

				);
			}
		}
		return $response;
	}

	private static function getConditionResult($places)
	{
		$result_seat = array();
		$result_fun = array();
		foreach ($places as $place) {
			switch ($place->type) {
				case self::TYPE_SEAT :
					if ($place->server_id)
						$result_seat[] = "(id=" . $place->server_id . ")";
					break;
				case self::TYPE_FUN :
					if (isset($place->sector_id))
						$result_fun[] = "(sector_id=" . $place->sector_id . ")";
					break;
				default:
					break;
			}
		}
		return array(
			"result_seat" => $result_seat,
			"result_fun" => $result_fun
		);
	}

	/**
	 * Generate unique barCode to place in selected Event
	 * @param array $array array of generated BarCodes
	 * @param $event Event
	 * @return string Barcode
	 */
	private function generateCode($event, $array = array())
	{
        $this->type_code = $event->barcode_type;
        $code = $this->getBarCode();
		if (!isset($this->_code))
			$this->_code = Yii::app()->db->createCommand()->select("code")->from(self::model()->tableName())->where("event_id=:event_id", array(":event_id"=>$event->id))->queryColumn();

		while (in_array($code, $this->_code) || in_array($code, $array))
			$code = $this->getBarCode();
		return $code;
	}

	private function ean13_check_digit($digits){
		//first change digits to a string so that we can access individual numbers
		$digits =(string)$digits;
		// 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
		$even_sum = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
		// 2. Multiply this result by 3.
		$even_sum_three = $even_sum * 3;
		// 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
		$odd_sum = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
		// 4. Sum the results of steps 2 and 3.
		$total_sum = $even_sum_three + $odd_sum;
		// 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
		$next_ten = (ceil($total_sum/10))*10;
		$check_digit = $next_ten - $total_sum;
		return $digits . $check_digit;
	}

	/**
	 * Generate 12 digits barCode
	 * @return string
	 */
	private function getBarCode()
	{
		$result = "";
		for ($i = 1; $i <= 12; $i++)
			$result .= mt_rand(1, 9);
        if ($this->type_code == Event::BARCODE_EAN13)
            $result = $this->ean13_check_digit($result);
		return $result;
	}

	/**
	 * @param $event_id integer Event id to select array of prices
     * @param $sector_id integer|bool Sector id to select array of prices by sector
     * @param $sale integer|bool
	 * @return mixed|array array of prices
	 */
	public static function getPrices($event_id, $sector_id=false, $sale=false)
	{
        $condition = "";
		$conditionSale = "";
        if ($sector_id)
            $condition = "t.sector_id=".$sector_id;
		if ($sale)
			$conditionSale = "t.status=".self::STATUS_SALE;
        $scheme_id = Yii::app()->db->createCommand()
            ->select("scheme_id")
            ->from(Event::model()->tableName())
            ->where("id=:id", array(
                ":id"=>$event_id
            ))
            ->queryScalar();

        $sql = Yii::app()->db->createCommand()
            ->selectDistinct("price")
            ->from(self::model()->tableName()." t")
            ->join(Sector::model()->tableName()." s", "s.id=t.sector_id")
            ->where("t.event_id=".$event_id." AND s.scheme_id=".$scheme_id)
            ->andWhere($condition)
            ->andWhere($conditionSale)
            ->order("price ASC")
            ->getText();
        $dependency = new CDbCacheDependency("SELECT MAX(date_add) FROM tbl_place WHERE event_id=".$event_id);
		return Yii::app()->db->cache(6000,$dependency)->createCommand($sql)->queryColumn();
	}

	public static function setFunCount($data,$event)
	{
		$result = array();
		$values = array();
		$codes = array();
		$ids = array();
		$save = false;

		foreach ($data->fun_zones as $fun_zone) {
			$price = self::getFunPrice($fun_zone->sector_id, $data->event_id);
			$ids[] = $fun_zone->sector_id;
			if ($data->actionType == self::PLACE_UP) {
				$amount = Sector::getFunAmount($fun_zone->sector_id);
				$count = Place::getFunCount($fun_zone->sector_id, $data->event_id);
				if ($data->count>$amount-$count&&$amount!=0)
					return array("msg"=>"Перевищення ліміту фан-зони");
				for ($i = 0; $i < $data->count; $i++) {
					$code = self::model()->generateCode($event, $codes);
					$codes[] = $code;
					$values[] = "(" . implode(", ", array(
							"row" => 0,
							"place" => 0,
							"event_id" => $data->event_id,
							"sector_id" => $fun_zone->sector_id,
							"price" => $price ? $price : 'NULL',
							"code" => $code,
							"type" => self::TYPE_FUN
						)) . ")";
				}
				$sql = "INSERT INTO " . self::model()->tableName() . " (row, place, event_id, sector_id, price, code, type) VALUES " . implode(",", $values);


			} else {
				Yii::app()->db->createCommand()->delete(self::model()->tableName(), "event_id=:event_id AND sector_id=:sector_id AND status=:status LIMIT " . $data->count, array(
					":event_id" => $data->event_id,
					":sector_id" => $fun_zone->sector_id,
					":status" => self::STATUS_SALE
				));
				$save = true;
			}
		}
		if (!$save)
			$save = Yii::app()->db->createCommand($sql)->execute();
		if ($save) {
			$result = self::getFunInfo($ids, $data->event_id);
		}
		return $result;
	}

	public static function getFunPrice($sector_id, $event_id)
	{
		$sql =  Yii::app()->db->createCommand()
			->select("price")
			->from(self::model()->tableName())
			->where("sector_id=".$sector_id." AND event_id=".$event_id." AND status=".self::STATUS_SALE)
			->getText();

		$dependency = new CDbCacheDependency("SELECT MAX(date_add) from tbl_place where event_id=".$event_id." AND sector_id=".$sector_id);

		$price = Yii::app()->db->cache(6000, $dependency)->createCommand($sql)
			->queryScalar();
		return $price ? : 0;
	}

	public static function getFunCount($sector_id, $event_id)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(self::model()->tableName())
			->where("sector_id=:sector_id AND event_id=:event_id AND status!=:status", array(
				":sector_id" => $sector_id,
				":event_id" => $event_id,
				":status"=>Place::STATUS_CLOSE
			))
			->queryScalar();
	}

	public static function getFunInfo($ids, $event_id)
	{
		$result = array();
		$sectors = Sector::model()->findAllByAttributes(array("id" => $ids));
		foreach ($sectors as $sector) {
			$result[] = array(
					"id" => "row0col0sector" . $sector->id,
					"sector_id" => $sector->id,
					"fun_zone" => true,
					"type" => Sector::TYPE_FUN,
					"price_info" => (object)$sector->getPriceInfo($event_id),
					"visual" => $sector->getVisual($event_id)
			)+$sector->getSectorParams();

		}

		return $result;
	}

    public static function getSectors($event_id)
    {
        $sector_ids = Yii::app()->db->createCommand()
            ->selectDistinct("sector_id")
            ->from(self::model()->tableName())
            ->where("event_id=:event_id AND status!=:status", array(
                ":event_id"=>$event_id,
                ":status"=>self::STATUS_CLOSE
            ))
            ->queryColumn();
        return Sector::model()->findAllByAttributes(array(
            "id"=>$sector_ids
        ), array("select"=>"id"));

    }

	public static function getCountFunWithPrice($event_id)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(self::model()->tableName())
			->where("event_id=:event_id AND type=:type", array(
				":event_id" => $event_id,
				":type" => self::TYPE_FUN
			))
			->queryScalar();
	}

	public static function getSoldFunCount($sector_id, $event_id)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(self::model()->tableName())
			->where("sector_id=:sector_id AND event_id=:event_id AND status=:status", array(
				":sector_id" => $sector_id,
				":event_id" => $event_id,
				":status" => self::STATUS_SOLD
			))
			->queryScalar();
	}

	public static function getName()
	{
		return "Місце";
	}

	public static function deletePrice($data)
	{
		$ids = array();

		$result = self::getConditionResult($data->places);
		if (!empty($result['result_seat'])) {
			$sql = "DELETE FROM " . self::model()->tableName() . " WHERE event_id=" . $data->event_id . " AND (" . implode(" OR ", $result['result_seat']) . ")";
			if (Yii::app()->db->createCommand($sql)->execute()) {
				foreach ($data->places as $place)
					if ($place->type == self::TYPE_SEAT)
						$ids[$place->sector_id][] = $place->id;
			}
			$ids = self::getIdsWithLabels($ids);
		}
		if (!empty($result['result_fun'])) {
			$sql = "UPDATE " . self::model()->tableName() . " set price = NULL WHERE event_id=" . $data->event_id . " AND (" . implode(' OR ', $result['result_fun']) . ")";
			if (Yii::app()->db->createCommand($sql)->execute())
				foreach ($data->places as $place) {
					if ($place->type == self::TYPE_FUN)
						$ids[] = $place->id;
				}
		}
		return array_filter($ids);
	}

	public static function getIdsWithLabels($ids)
	{

		$result = array();
		foreach ($ids as $sector => $places) {
			$sector = Sector::model()->with('typeSector', 'typeRow', 'typePlace')->findByPk($sector);
			foreach ($places as $place) {
				$result[] = array(
					"id" => $place,
					"label" => $sector->getPlaceName(Sector::getRowPlaceSector($place))
				);
			}
		}
		return $result;

	}

	/**
	 * @param array $ids
	 * @param integer $ticket_status
	 * @param integer $ticket_old_status
	 * @param integer|bool $place_status
	 */
	public static function setPlaceStatus($ids, $ticket_status, $ticket_old_status, $place_status=false)
	{
		$condition = "";
		$conditionArray = array();

		if ($ticket_old_status) {
			$condition = "status=:oldStatus";
			$conditionArray = array(":oldStatus"=>$ticket_old_status);
		}
		$ticketIds = Yii::app()->db->createCommand()
			->select("id")
			->from(Ticket::model()->tableName())
			->where($condition, $conditionArray)
			->andWhere(array("in", "place_id", $ids))
			->queryColumn();

		$result = Yii::app()->db->createCommand()
			->update(Ticket::model()->tableName(), array(
				"status"=>$ticket_status,
				"date_update"=>Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time())
			), array("in", "id", $ticketIds));

		if ($place_status!==false)
			$result += Yii::app()->db->createCommand()
				->update(Place::model()->tableName(), array("status"=>$place_status), array("in", "id", $ids));

		return $result;
	}

	public static function refreshCode($ids)
	{
		if (!empty($ids)) {
			$barCodes = array();
			$sql = "CASE id ";
			$places = Yii::app()->db->createCommand()
				->select("p.id, p.event_id")
				->from(self::model()->tableName(). " p")
				->join(Event::model()->tableName()." e", "e.id=p.event_id")
				->where("e.refresh_code=:code", array(
					":code"=>1
				))
				->andWhere(array("in", "p.id", $ids))
				->queryAll();
            if (!empty($places)) {
                $event_ids = array_filter(array_map(function($place){return $place['event_id'];},$places));
                $ids = array_map(function($place){return $place['id'];},$places);
                $models = Event::model()->findAllByPk($event_ids);
                $events = array();
                foreach ($models as $model)
                    $events[$model->id] = $model;


                foreach ($places as $place) {
                    $code = self::model()->generateCode($events[$place['event_id']], $barCodes);
                    $barCodes[] = $code;
                    $sql .= " WHEN ".$place['id']." THEN ".$code;
                }

                $query = "UPDATE ".Place::model()->tableName()." SET code=".$sql." END WHERE id IN (".implode(",", $ids).")";
                return Yii::app()->db->createCommand($query)->execute();
            }

		}
		return false;
	}

	public static function getPlaceQuoteStatus($ids)
	{

	}

	public static function encodePlaces($places)
	{
		$newArray = array();
		$temp = "";
		$i = 0;
		$tempCount = 0;
		$places = array_values($places);
		$separator = ":";

//		foreach ($places as $place) {
//			$i++;
//			$place = (array)$place;
//			if (!isset($newArray[$place])) {
//				if ($temp != '') {
//					$temp .= PHP_EOL.$separator.$place;
//				} else
//					$temp = $separator.$place;
//			} else {
//				$check = $place-1;
//				if ($check != $newArray[$place]) {
//					$temp .= $tempCount > 1 ? "-".$place : PHP_EOL.$place['row'].$separator.$place['place'];
//				}
//				$nextKey = count($places) != $i ? (array)$places[$i] : "";
//
//				if ( count($places)==$i || $nextKey['row']!=$place['row']) {
//					$str = substr($temp, strrpos($temp, $separator)+1);
//					$temp .= (int)$str != $place['place'] && $tempCount >= 1 || count($places)==$i? "-".$place['place'] : "";
//					$tempCount = 0;
//					unset($newArray[$place['row']]);
//					continue;
//				}
//			}
//			$tempCount++;
//			$newArray[$place['row']] = $place['place'];
//		}
//		return $temp;
	}

	function getId()
	{
		return 'Place' . $this->id;
	}

	function getPrice()
	{
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
			array('row, place, event_id, sector_id', 'required'),
			array('row, place, event_id, sector_id, price, type, status', 'numerical', 'integerOnly' => true),
			array('code', 'length', 'max' => 45),
			array('edited_row, edited_place', 'length', 'max' => 128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, row, place, event_id, sector_id, price, code, type, edited_row, edited_place, status', 'safe', 'on' => 'search'),
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
			'sector' => array(self::BELONGS_TO, 'Sector', 'sector_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'platforms' => array(self::MANY_MANY, 'Platform', '{{platform_place}}(place_id,platform_id)'),
			'ticket' => array(self::HAS_ONE, 'Ticket', 'place_id'),
		);
	}

    public function afterFind()
    {
        parent::afterFind();
        if ($this->type == self::TYPE_SEAT) {
            $sector_edit = Yii::app()->cache->get("editedInfo_".$this->sector_id);
            if (!$sector_edit) {
                $places = !is_object($this->sector->places) ? json_decode($this->sector->places) : $this->sector->places;
                $rowColNames = array("row"=>array(),"col"=>array());
                $placeEdited = array("row"=>array(),"col"=>array());

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

                foreach ($places->cell->simple_cell as $item) {
                    if (isset($item->edited)) {
                        if (isset($item->edited->row_info))
                            $placeEdited['row'][$item->id] = $item->edited->row_info;
                        if (isset($item->edited->col_info))
                            $placeEdited['col'][$item->id] = $item->edited->col_info;
                    }

                }

                $sector_edit = array("rowColNames"=>$rowColNames, "placeEdited"=>$placeEdited);
                Yii::app()->cache->set("editedInfo_".$this->sector_id, $sector_edit, 60*60*24, new CDbCacheDependency("SELECT max(date_update) from tbl_sector where id=".$this->sector_id));
            }
            if (!empty($sector_edit)) {
                $place_id = Sector::encodePlace(array(
                    "row"=>$this->row,
                    "place"=>$this->place,
                    "sector_id"=>$this->sector_id
                ));
                if(isset($sector_edit["rowColNames"]['row'][$this->row]))
                    $this->edited_row = $sector_edit["rowColNames"]['row'][$this->row];
                if(isset($sector_edit['rowColNames']['col'][$this->place]))
                    $this->edited_place = $sector_edit["rowColNames"]['col'][$this->place];
                if(isset($sector_edit["placeEdited"]['row'][$place_id]))
                    $this->edited_row = $sector_edit["placeEdited"]['row'][$place_id];
                if(isset($sector_edit['placeEdited']['col'][$place_id]))
                    $this->edited_place = $sector_edit["placeEdited"]['col'][$place_id];
            }
        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'row' => 'Ряд',
			'place' => 'Місце',
			'event_id' => 'Подія',
			'sector_id' => 'Сектор',
			'price' => 'Ціна',
			'code' => 'Штрих-код',
			'type' => 'Тип',
			'edited_row' => 'Edited Row',
			'edited_place' => 'Edited Place',
			'status' => 'Статус',
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
		$criteria->compare('row', $this->row);
		$criteria->compare('place', $this->place);
		$criteria->compare('event_id', $this->event_id);
		$criteria->compare('sector_id', $this->sector_id);
		$criteria->compare('price', $this->price);
		$criteria->compare('code', $this->code, true);
		$criteria->compare('type', $this->type);
		$criteria->compare('edited_row', $this->edited_row, true);
		$criteria->compare('edited_place', $this->edited_place, true);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function scopes()
	{
		return array(
			"sold" => array(
				"condition" => "status!=" . Ticket::STATUS_CANCEL
			)
		);
	}

	public function getRowName()
	{
		return isset($this->sector->typeRow)? $this->sector->typeRow->name : "";
	}

	public function getPlaceName()
	{
		return isset($this->sector->typePlace)?$this->sector->typePlace->name:"";
	}

	public function getEditedRow()
	{
//		CVarDumper::dump($this);exit;
		return $this->edited_row ? : $this->row;
	}

	public function getEditedPlace()
	{
		return $this->edited_place ? : $this->place;
	}
}