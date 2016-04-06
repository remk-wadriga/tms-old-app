<?php

/**
 * This is the model class for table "{{event}}".
 *
 * The followings are the available columns in table '{{event}}':
 * @property integer $id
 * @property string $name
 * @property string $sys_name
 * @property string $start_sale
 * @property string $end_sale
 * @property string $date_add
 * @property string $custom_params
 * @property integer $poster_id
 * @property string $description_id
 * @property integer $group_id
 * @property integer $scheme_id
 * @property integer $user_id
 * @property integer $role_id
 * @property integer $status
 * @property integer $barcode_type
 * @property integer $slider_main
 * @property integer $slider_city
 * @property integer $position
 * @property integer $refresh_code
 * @property string $url
 * @property string $html_header
 * @property string $meta_description
 * @property string $keywords
 *
 *
 * The followings are the available model relations:
 * @property Group $group
 * @property Multimedia $poster
 * @property Multimedia $description
 * @property Scheme $scheme
 * @property User $user
 * @property $Role $role
 * @property Multimedia[] $multimedias
 * @property Timing[] $timings
 * @property Place[] $places1
 * @property Quote[] $quotes
 * @property EventTicket[] tickets
 */
class Event extends CActiveRecord
{
	const STATUS_DELETED = 2;
	const STATUS_ACTIVE = 1;
	const STATUS_NO_ACTIVE = 0;
	const STATUS_SALE = 1;
	const STATUS_NOT_SALE = 0;
	const STATUS_SALE_END = 2;
	const ACTION_UPDATE = "update";
	const ACTION_DELETE = "delete";
	public static $status = array(
		self::STATUS_ACTIVE=>"Активна",
		self::STATUS_NO_ACTIVE=>"Не активна",
		self::STATUS_DELETED=>"Видалена"
	);
	const BARCODE_CODE128 = 0;
	const BARCODE_EAN13 = 1;
	public static $barcodeType = [
		self::BARCODE_CODE128 => "code128",
		self::BARCODE_EAN13 => "ean13",
	];
	public $classification_id;
	public $cluster_id;
	public $artist_id;
	public $organizer_id;
	public $multimedia;
	public $sortName = 'nameFilter';
	public $city_id;
	public $start_event;
	public $stop_event;
	public $roleRelation;
	public $isOnMain;
	public $classification_list = array();
	public $cluster_list = array();
	public $artist_list = array();
	public $organizer_list = array();
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
	public $minDate;
	public $blank;
	public $e_ticket;

	/**
	 * @return string translated Name of module
	 */
	public static function getName()
	{
		return "Подія";
	}

	public static function getListEvents($status=array(), $city=array(), $ids=[], $return = true)
	{
		$result = array();
		$list_events = array();
		$list_events_options = array();
		$status = !empty($status) ? array("in", "t.status", $status): array();
		$ids = !empty($ids) ? array("in", "t.id", $ids): array();
		$events = Yii::app()->db->createCommand()
			->select("t.id, t.scheme_id, t.name, (SELECT MIN(start_sale) from {{timing}} tm WHERE tm.event_id=t.id) as timing, c.name as city_name, l.name as location_name")
			->from(self::model()->tableName()." t")
			->join(Scheme::model()->tableName()." sh", "t.scheme_id=sh.id")
			->join(Location::model()->tableName()." l", "sh.location_id=l.id")
			->join(City::model()->tableName()." c", "c.id=l.city_id")
			->where($status)
			->andWhere($city)
			->andWhere($ids)
			->order("t.name ASC")
			->queryAll();
		if(!empty($ids)&&$return)
			return $events;
		foreach ($events as $event) {
			$list_events[$event["id"]] = $event["name"];
			$list_events_options[$event["id"]] = array("data-city" => $event["city_name"], "data-location" => $event["location_name"], "data-date" => $event['timing']);
		}
		$result['data'] = $list_events;
		$result['options'] = $list_events_options;
		return $result;
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{event}}';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Event the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getActiveEventsList()
	{
		return CHtml::listData(self::getActiveEvents(), "id", "name");
	}

	public static function getActiveEvents()
	{
		$criteria = new CDbCriteria();
		$criteria->with = array("scheme", "scheme.location");
		$criteria->addCondition("(SELECT MAX(stop_sale) FROM {{timing}} WHERE {{timing}}.event_id=t.id) >= NOW()");
		$criteria->compare("t.status", Event::STATUS_ACTIVE);
		$criteria->addCondition("start_sale<=NOW() AND start_sale<>0 AND (end_sale=0 OR end_sale>=NOW())");
		$criteria->select = "t.*, scheme.*, location.*";
		return self::model()->findAll($criteria);
	}

	/**
	 * @param array $event_ids List Event ids
	 * @return array
	 */
	public static function getListSectors($event_ids)
	{
		$result = Yii::app()->cache->get("list_sectors".serialize($event_ids));
		if (!$result) {
			$events = self::model()->findAllByAttributes(array("id"=>$event_ids));
			if (empty($events))
				return array();
			$scheme_ids = array_map(function($event){return $event->scheme_id;}, $events);
			$sectors = Yii::app()->db->createCommand()
				->select("CONCAT(IFNULL(st.name, ''), ' ', t.name) as sector_name, t.id")
				->from(Sector::model()->tableName()." t")
				->leftJoin(TypeSector::model()->tableName()." st", "st.id=t.type_sector_id")
				->where(array("in", "t.scheme_id", $scheme_ids))
				->queryAll();
			foreach ($sectors as $sector) {
				$result[$sector['id']] = " " . $sector['sector_name'];
			}

			Yii::app()->cache->set("list_sectors".serialize($event_ids), $result, 60*60*24, new CDbCacheDependency("SELECT MAX(date_update) from tbl_sector"));
		}
		return $result;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, scheme_id, url, user_id, role_id', 'required'),
			array('poster_id, group_id, scheme_id, user_id, status, slider_main, slider_city, refresh_code, barcode_type', 'numerical', 'integerOnly'=>true),
			array('name, sys_name', 'length', 'max'=>128),
			array('blank, e_ticket, date_add, url, html_header, meta_description, keywords, start_sale, end_sale, custom_params, cluster_id, classification_id, multimedia, artist_id, organizer_id, description_id, position, isOnMain, barcode_type', 'safe'),
			array('start_sale, end_sale', 'validateSales'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, url, html_header, meta_description, keywords, name, sys_name, date_add, start_sale, end_sale, custom_params, poster_id, description_id, group_id, scheme_id, user_id, status, city_id, sortName, role_id, start_event, stop_event, position, isOnMain, barcode_type', 'safe', 'on'=>'search'),
			array('url', 'validateUrl'),
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
			'group' => array(self::BELONGS_TO, 'Group', 'group_id'),
			'poster' => array(self::BELONGS_TO, 'Multimedia', 'poster_id'),
//			'description' => array(self::BELONGS_TO, 'Multimedia', 'description_id'),
			'scheme' => array(self::BELONGS_TO, 'Scheme', 'scheme_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
			'multimedias' => array(self::HAS_MANY, 'Multimedia', 'event_id'),
			'timings' => array(self::HAS_MANY, 'Timing', 'event_id'),
			'places1' => array(self::HAS_MANY, 'Place', 'event_id'),
			'quotes' => array(self::HAS_MANY, 'Quote', 'event_id'),
			'tickets' => array(self::HAS_MANY, 'EventTicket', 'event_id'),
			'pay_types' => array(self::HAS_MANY, 'EventPayType', 'event_id')
		);
	}

	public function validateUrl($attribute)
	{
		if($this->url != '') {
			if($this->isNewRecord)
				$id = 0;
			else
				$id = $this->id;
			$url = UrlTranslit::translit($this->url);
			$existed = Yii::app()->db->createCommand()
				->select("t.id")
				->from(self::model()->tableName()." t")
				->andWhere("t.id!=:id",array("id"=>$id))
				->andWhere("t.status!=:status",array("status"=>self::STATUS_DELETED))
				->andWhere("t.url=:url",array("url"=>$url))
				->queryScalar();
			if ($existed)
				$this->addError($attribute, "Даний url вже використовується");

			if (strlen($this->url) > 120)
				$this->addError($attribute, "url не може бути більше 120 символів");
		}

	}

	public function getPlaces()
	{
		$places = $this->places1;
		$sector_ids = Yii::app()->db->createCommand()
			->select("id")
			->from("{{sector}}")
			->where("scheme_id=:scheme_id", array(
				":scheme_id"=>$this->scheme_id
			))
			->queryColumn();

		foreach ($places as $k=>$place)
			if (!in_array($place->sector_id, $sector_ids))
				unset($places[$k]);

		return $places;
	}

	public function validateSales($attribute)
	{
		if($this->start_sale != null && $this->end_sale == null)
			$this->addError('end_sale', "Якщо вказаний початок продажів має бути вказаний і кінець");
		elseif (strtotime($this->start_sale) < strtotime("-1 minute",time()) && strtotime($this->end_sale) < strtotime("-1 hour",time()) && $this->end_sale != null && $this->isNewRecord)
			$this->addError($attribute, "Початок продажів і кінець не можуть бути раніше ніж теперішня дата");
		elseif (strtotime($this->start_sale) < strtotime("-1 minute",time()) && $this->start_sale != null && $this->isNewRecord)
			$this->addError($attribute, "Початок продажів не може бути раніше ніж теперішня дата");
		elseif (strtotime($this->start_sale) > strtotime($this->end_sale) && $this->end_sale != null && $this->start_sale != null)
			$this->addError($attribute, "Закінчення продажів не може бути раніше початку");
	}

	public function beforeSave()
	{
		if (parent::beforeSave()){
			$params = (array)$this->custom_params;
			foreach ($params as $k=>$param) {
				if (empty($param['name']) || empty($param['value']))
					unset($params[$k]);
			}
			if($this->sys_name == ""){
				$this->sys_name = $this->name;
			}
			if($this->url == ''){
				if($this->isNewRecord)
					$this->url = UrlTranslit::translit($this->name." ".$this->scheme->location->city->name);
				else {
					$prevDataUrl = self::model()->findByPk($this->id);
					$this->url = $prevDataUrl->url;
				}
			}
			$this->url = UrlTranslit::translit($this->url);
			if(!$this->isNewRecord) {
				$prevEventData = self::model()->findByPk($this->id);
				$prevPosition = $prevEventData->position;
			} else
				$prevPosition = null;

			if($prevPosition != null) {
				if ($this->isOnMain != 0) {
					$existed = Event::model()->findByAttributes(array('position'=>$this->position));
					if(!$existed){
						//nothing to do
					}elseif($prevPosition == $this->position) {
						//nothing to do
					} elseif ($prevPosition > $this->position) {
						Yii::app()->db->createCommand()->update("{{event}}", array("position"=>new CDbExpression("position + 1")),  "position>=:position AND position<:prevPosition", array(":position"=>$this->position,":prevPosition"=>$prevEventData->position));

					} elseif ($prevPosition < $this->position ) {
						Yii::app()->db->createCommand()->update("{{event}}", array("position"=>new CDbExpression("position - 1")),  "position<=:position AND position>:prevPosition", array(":position"=>$this->position,":prevPosition"=>$prevEventData->position));
					}
				} else {
					Yii::app()->db->createCommand()->update("{{event}}", array("position"=>new CDbExpression("position - 1")),  "position>=:position", array(":position"=>$prevPosition));
					$this->position = null;
				}
			} else {
				if ($this->isOnMain != 0) {
					$existed = Event::model()->findByAttributes(array('position'=>$this->position));
					if(!$existed){
						//nothing to do
					} else
						Yii::app()->db->createCommand()->update("{{event}}", array("position"=>new CDbExpression("position + 1")),  "position>=:position", array(":position"=>$this->position));
				} else {
					$this->position = null;
				}
			}


			if($this->start_sale != null)
				$this->start_sale = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->start_sale);
			if($this->end_sale != null)
				$this->end_sale = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->end_sale);

			if ($this->isNewRecord)
				$this->date_add = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());

			$this->custom_params = json_encode($params);

			return true;
		} else
			return false;
	}

	public function afterSave()
	{
		parent::afterSave();
		if (!empty($this->timings)) {
			Yii::app()->db->createCommand()
				->delete("{{timing}}", "event_id=:id", array(":id"=>$this->id));
			foreach ($this->timings as $time) {
				$timing = new Timing();
				$timing->attributes = $time;
				$timing->event_id = $this->id;
				$timing->save();
			}
		}
		$multimedia = CUploadedFile::getInstancesByName('multimedia');
		$existPoster = $this->poster_id != null ? true : false;
		if (is_array($multimedia)) {
			$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			foreach ($multimedia as $key => $_multimedia) {
				$fileExt = $_multimedia->extensionName;
				$filename = uniqid().".".$fileExt;

				if ($_multimedia->saveAs($dir.$filename)) {
					$_multimedia = new Multimedia();
					$_multimedia->file = $filename;
					$_multimedia->event_id = $this->id;
					if($_multimedia->save())
					{
						if(!$existPoster && $this->isNewRecord) {
							$query = "UPDATE {{event}} SET poster_id = $_multimedia->id WHERE id = $this->id";
							$queryMultimedia = "UPDATE {{multimedia}} SET status = ".Multimedia::STATUS_POSTER." WHERE id = $_multimedia->id";
							if(Yii::app()->db->createCommand($query)->execute())
								Yii::app()->db->createCommand($queryMultimedia)->execute();

							$existPoster = true;
						}
					}
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$type_name = finfo_file($finfo, $dir.$_multimedia->file);
					$type = substr($type_name, 0, strrpos($type_name, '/'));
					if($type == 'image'){
						if ($_multimedia->saveFile())
							continue;
					}

				}
			}
		}

		if(!$existPoster && !$this->isNewRecord) {
			$multimedia_id_query = "SELECT MIN(id) FROM ".Multimedia::model()->tableName()." WHERE event_id= $this->id ";
			$multimedia_id = Yii::app()->db->createCommand($multimedia_id_query)->queryScalar();

			if($multimedia_id){
				$query = "
				UPDATE ".Event::model()->tableName()." SET poster_id = $multimedia_id WHERE id = $this->id ;
				UPDATE ".Multimedia::model()->tableName()." SET status = ".Multimedia::STATUS_POSTER." WHERE id = $multimedia_id;
				";
				Yii::app()->db->createCommand($query)->execute();
			}
		}

		$this->formTicket();
		$pay_types = new EventPayType();
		$pay_types->event_id = $this->id;
		$pay_types->savePayTypes();
	}

	public function formTicket()
	{
		$blankFile = file(Yii::app()->getBaseUrl(true)."/theme/ticket/index.html", FILE_IGNORE_NEW_LINES);
		$eTicketFile = file(Yii::app()->getBaseUrl(true)."/theme/e_ticket/index.html", FILE_IGNORE_NEW_LINES);
		$blankStyleFile = file(Yii::app()->getBaseUrl(true)."/theme/ticket/ticket.css", FILE_IGNORE_NEW_LINES);
		$eStyleFile = file(Yii::app()->getBaseUrl(true)."/theme/e_ticket/e_ticket.css", FILE_IGNORE_NEW_LINES);

		$newBlankFile = $this->trimRow($blankFile);
		$newEFile = $this->trimRow($eTicketFile);
		$newBlankStyleFile = $this->trimRow($blankStyleFile);
		$newEStyle = $this->trimRow($eStyleFile);

		$blankText = explode("\n", trim($this->blank['ticket']));
		$blankStyle = explode("\n", trim($this->blank['style']));
		$eTicketText = explode("\n", trim($this->e_ticket['ticket']));
		$eStyle = explode("\n", trim($this->e_ticket['style']));

		$newBlankText = $this->trimRow($blankText);
		$newBlankStyle = $this->trimRow($blankStyle);
		$newETicketText = $this->trimRow($eTicketText);
		$newETicketStyle = $this->trimRow($eStyle);

		Yii::app()->db->createCommand()->delete("{{event_ticket}}", "event_id=:event_id", array(
			":event_id"=>$this->id,
		));

		$isBlankText = $newBlankFile == $newBlankText;
		$isBlankStyle = $newBlankStyleFile == $newBlankStyle;
		$isE_Text = $newEFile == $newETicketText;
		$isE_Style = $newEStyle == $newETicketStyle;

		if (!empty($this->blank)&&(($this->blank['ticket']!='' && !$isBlankText) ||
				($this->blank['style']!='' && !$isBlankStyle))) {
			$eventTicket = new EventTicket();
			$eventTicket->event_id = $this->id;
			$eventTicket->ticket = CHtml::encode($this->blank['ticket']);
			$eventTicket->style = CHtml::encode($this->blank['style']);
			$eventTicket->type = EventTicket::TYPE_BLANK;
			$eventTicket->save(false);
		}
		if (!empty($this->e_ticket) &&(($this->e_ticket['ticket'] != '' && !$isE_Text) ||
				($this->e_ticket['style'] != '' && !$isE_Style))) {
			$eventTicket = new EventTicket();
			$eventTicket->event_id = $this->id;
			$eventTicket->ticket = CHtml::encode($this->e_ticket['ticket']);
			$eventTicket->style = CHtml::encode($this->e_ticket['style']);
			$eventTicket->type = EventTicket::TYPE_E_TICKET;
			$eventTicket->save(false);
		}
	}

	private function trimRow($rows)
	{
		$result = array();
		foreach ($rows as $row) {
			if (trim($row) == "")
				continue;
			$result[] = trim($row);
		}
		return $result;
	}

	public function afterFind()
	{
		parent::afterFind();

		$controller = ucfirst(Yii::app()->controller->id)."Controller";
		$actions = $controller::getActions();

		$this->getTicket();
		if($this->start_sale == '0000-00-00 00:00:00')
			$this->start_sale = null;
		else
			$this->start_sale = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $this->start_sale);
		if($this->end_sale == '0000-00-00 00:00:00')
			$this->end_sale = null;
		else
			$this->end_sale = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $this->end_sale);
		$this->custom_params = json_decode($this->custom_params);
	}

	public function getTicket()
	{
		if (!empty($this->tickets)) {
			foreach ($this->tickets as $ticket) {
				if ($ticket->type == EventTicket::TYPE_BLANK)
					$this->blank = array(
						"ticket"=>CHtml::decode($ticket->ticket),
						"style"=>CHtml::decode($ticket->style)
					);
				else
					$this->e_ticket = array(
						"ticket"=>CHtml::decode($ticket->ticket),
						"style"=>CHtml::decode($ticket->style)
					);
			}
		}
		if (!$this->blank)
			$this->blank = array(
				"ticket"=>file_get_contents(Yii::getPathOfAlias("webroot.theme.ticket")."/index.html"),
				"style"=>file_get_contents(Yii::getPathOfAlias("webroot.theme.ticket")."/ticket.css")
			);
		if (!$this->e_ticket)
			$this->e_ticket = array(
				"ticket"=>file_get_contents(Yii::getPathOfAlias("webroot.theme.e_ticket")."/index.html"),
				"style"=>file_get_contents(Yii::getPathOfAlias("webroot.theme.e_ticket")."/e_ticket.css")
			);

	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'sys_name' => 'Системна назва',
			'start_sale' => 'Початок продажів',
			'end_sale' => 'Закінчення продажів',
			'custom_params' => 'Додаткові параметри',
			'poster_id' => 'Афіша',
			'description_id' => 'Опис',
			'group_id' => 'Група',
			'scheme_id' => 'Схема',
			'user_id' => 'Користувач',
			'status' => 'Статус',
			'classification_id' => 'Класифікації',
			'organizer_id' => 'Організатор',
			'artist_id' => 'Виконавець',
			'cluster_id' => 'Теги',
			'date_add' => 'dateAdd',
			'url' => 'Аліаз для url',
			'html_header' => 'HTML заголовок',
			'meta_description' => 'Meta опис',
			'keywords' => 'Ключові слова',
			'slider_main' => 'Головний слайдер',
			'slider_city' => 'Слайдер по місту',
			'isOnMain' => 'Відображати на головній сторінці сайту',
			'refresh_code' => 'Оновляти штрихкод після скасування квитка',
			'barcode_type' => 'Тип штрих-коду'
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
//		if($this->status == '')
//			$this->status = Event::STATUS_ACTIVE;


		$criteria=new CDbCriteria;

		$criteria->join = "JOIN {{scheme}} sh ON sh.id=t.scheme_id JOIN {{location}} l ON l.id=sh.location_id";
		$criteria->select = "t.*, l.city_id, (SELECT MIN(start_sale) FROM {{timing}} WHERE {{timing}}.event_id=t.id) as minDate";
		if ($this->id)
			$criteria->compare('t.id',$this->id);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('sys_name',$this->sys_name,true);
		$criteria->compare('start_sale',$this->start_sale,true);
		$criteria->compare('end_sale',$this->end_sale,true);
		$criteria->compare('date_add',$this->date_add,true);
		$criteria->compare('custom_params',$this->custom_params,true);
		$criteria->compare('poster_id',$this->poster_id);
		$criteria->compare('description_id',$this->description_id);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('scheme_id',$this->scheme_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('t.status',$this->status);
		if ($this->city_id != 0)
			$criteria->compare('l.city_id',$this->city_id);
		$criteria->compare('url',$this->url);
		$criteria->compare('html_header',$this->html_header);
		$criteria->compare('meta_description',$this->meta_description);
		$criteria->compare('keywords',$this->keywords);

		if (!Yii::app()->user->isAdmin) {
//            $criteria->compare("t.id", $this->getUserAccessEvent("event"));
		}
		if ($this->start_event)
			$criteria->addCondition("(SELECT MIN(start_sale) FROM {{timing}} WHERE {{timing}}.event_id=t.id) >= '".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->start_event)."'");
		if ($this->stop_event)
			$criteria->addCondition("(SELECT MAX(stop_sale) FROM {{timing}} WHERE {{timing}}.event_id=t.id) <= '".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->stop_event)."'");

		if($this->sortName == "nameFilter")
		{
			$criteria->order = 't.name ASC';
		}
		if($this->sortName == "dateCreateFilter")
		{
			$criteria->order = 'date_add ASC';
		}
		if($this->sortName == "dateGoonFilter")
		{
			$criteria->order = 'minDate DESC';
		}

		$result =  new CActiveDataProvider($this, array(
			'criteria'=>$criteria
		));
		return $result;
	}

	public function getUserAccessEvent($controller)
	{

		/*$ids = Yii::app()->db->createCommand()
			->select("event_id")
			->from("{{access_event}}")
			->where("role_id=:role_id AND user_id=:id", array(
				":id"=>$this->id,
                ":role_id"=>Yii::app()->user->currentRoleId
			))
			->queryColumn();
		return Yii::app()->db->createCommand()
			->select("event_id")
			->from("{{access_event}}")
			->where(array("in", "id", $ids))
			->queryColumn();*/
	}

	public function behaviors()
	{
		return array(
			'ManyManyBehavior'=>array(
				'class' => 'application.extensions.many_many.ManyManyBehavior'
			),
			'BeforeDeleteBehavior'=>array(
				'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
			),
		);
	}

	/**
	 * @return array List locations in selected city id=>name
	 */
	public function getLocationList()
	{
		if ($this->isNewRecord)
			return array();
		$data = Location::model()->findAllByAttributes(array("city_id"=>$this->scheme->location->city_id));
		return CHtml::listData($data, "id", "name");
	}

	/**
	 * @return array List of schemes in location id=>name
	 */
	public function getSchemeList()
	{
		if ($this->isNewRecord)
			return array();
		$data = Scheme::model()->findAllByAttributes(array("location_id"=>$this->scheme->location_id));
		return CHtml::listData($data, "id", "name");
	}

	public function getCountPlacePrice($type=false)
	{
		$condition = array();
		if ($type)
			$condition = array("in", "p.type", $type);
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(Place::model()->tableName()." p")
			->join(Sector::model()->tableName()." s", "s.id=p.sector_id")
			->join(Scheme::model()->tableName()." sc", "sc.id=s.scheme_id")
			->where("event_id=:event_id and price<>0 AND s.status=:status AND sc.id=:scheme_id AND p.status!=:statusClose", array(
				":event_id"=>$this->id,
				":scheme_id"=>$this->scheme_id,
				":status"=>Sector::STATUS_ACTIVE,
				":statusClose"=>Place::STATUS_CLOSE
			))
			->andWhere($condition)
			->queryScalar();
	}

	public function getSumPrice($type=false)
	{
		$condition = array();
		if ($type)
			$condition = array("in", "p.type", $type);
		return Yii::app()->db->createCommand()
			->select("SUM(price)")
			->from(Place::model()->tableName()." p")
			->join(Sector::model()->tableName()." s", "s.id=p.sector_id")
			->join(Scheme::model()->tableName()." sc", "sc.id=s.scheme_id")
			->where("event_id=:event_id and price<>0 AND s.status=:status AND sc.id=:scheme_id AND p.status!=:statusClose", array(
				":event_id"=>$this->id,
				":scheme_id"=>$this->scheme_id,
				":status"=>Sector::STATUS_ACTIVE,
				":statusClose"=>Place::STATUS_CLOSE
			))
			->andWhere($condition)
			->queryScalar();
	}

	/**
	 * @param integer $type Place type fun_zone or seat
	 * @return array
	 */
	public function getPlacesWithPrice($type)
	{
		$places = Yii::app()->db->createCommand()
			->select("p.*")
			->from(Place::model()->tableName()." p")
			->join(Sector::model()->tableName()." s", "s.id=p.sector_id")
			->join(Scheme::model()->tableName()." sc", "sc.id=s.scheme_id")
			->where("event_id=:event_id AND p.type=:type AND price <> 0 AND s.status=:status AND sc.id=:scheme_id AND p.status!=:statusClose", array(
				":event_id"=>$this->id,
				":type"=>$type,
				":scheme_id"=>$this->scheme_id,
				":status"=>Sector::STATUS_ACTIVE,
				":statusClose"=>Place::STATUS_CLOSE
			))
			->order("price ASC")
			->queryAll();
		$result = array();
		$sector = new Sector();
		$sector->setColors(Place::getPrices($this->id));
		foreach ($places as $place) {
			if (!isset($result[$place['price']]))
				$result[$place['price']] = array(
					"fill"=>$sector->getColor($place['price']),
					"type"=>$place['type'],
					"count"=>1
				);
			else
				$result[$place['price']]['count'] = $result[$place['price']]['count']+1;
		}
		return $result;
	}

	public function getPriceCountBySector($sector_id)
	{
		return Yii::app()->db->createCommand()
			->select("COUNT(*)")
			->from(Place::model()->tableName())
			->where("event_id=:event_id AND sector_id=:sector_id AND status=:status", array(
				":event_id"=>$this->id,
				":sector_id"=>$sector_id,
				":status"=>Place::STATUS_SALE
			))
			->queryScalar();
	}

	public function getStartTime()
	{
		$start_time = false;
		if (isset($this->timings))
			foreach ($this->timings as $timing) {
				if ($timing->start_sale) {
					$start_time =  $timing->start_sale;
					break;
				}
			}
		return $start_time;

	}

	public function getEnterTime()
	{
		$start_time = false;
		if (isset($this->timings))
			foreach ($this->timings as $timing) {
				if ($timing->start_sale) {
					$start_time =  $timing->entrance;
					break;
				}
			}
		return $start_time;
	}

	public function getMinMaxPrice()
	{
		return Yii::app()->db->createCommand()
			->select("MIN(price) as min, MAX(price) as max")
			->from('{{place}}')
			->where('event_id=:event_id AND status=:status', array(
				":event_id"=>$this->id,
				":status"=>Place::STATUS_SALE
			))
			->queryRow();
	}

	public function getImages()
	{
		$dir = Yii::getPathOfAlias("webroot.uploads").DIRECTORY_SEPARATOR.$this->id.DIRECTORY_SEPARATOR;
		$url = Yii::app()->getBaseUrl(true)."/uploads/".$this->id."/";
		$result = array();
		foreach ($this->multimedias as $multimedia) {
			clearstatcache();
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$type_name = finfo_file($finfo, $dir . $multimedia->file);
			$type = substr($type_name, 0, strrpos($type_name, '/'));
			if ($type == "image") {
				$name = pathinfo($multimedia->file,PATHINFO_FILENAME);
				$ext = ".".pathinfo($multimedia->file, PATHINFO_EXTENSION);
				$result[] = array(
					"o"=>$url.$name.$ext,
					"l"=>file_exists($dir.$name."_l".$ext) ? $url.$name."_l".$ext : "",
					"m"=>file_exists($dir.$name."_m".$ext) ? $url.$name."_m".$ext : "",
					"s"=>file_exists($dir.$name."_s".$ext) ? $url.$name."_s".$ext : "",
				);
			}
		}
		return $result;
	}

	public function getStatusText()
	{
		switch ($this->status) {
			case self::STATUS_ACTIVE:
				return 'Активна';
			case self::STATUS_NO_ACTIVE:
				return 'Не активна';
			case self::STATUS_DELETED:
				return 'Видалена';
			default:
				return 'Не визначений статус';
		}
	}


	public function getSaleStatusText()
	{
		//return 'CT: '.date('Y-m-d H:i:s').'   SS: '.$this->start_sale.'    FS: '.$this->end_sale;

		if (time() < strtotime($this->start_sale)) {
			return 'Продаж не розпочато';
		}elseif ((strtotime($this->start_sale) <= time())&&(time() <= strtotime($this->end_sale))) {
			return 'Триває продаж';
		}elseif (strtotime($this->end_sale) < time()) {
			return 'Продаж завершено';
		}
	}

	public function getSaleStatus()
	{
		if (time() < strtotime($this->start_sale)) {
			return self::STATUS_NOT_SALE;
		}elseif ((strtotime($this->start_sale) <= time())&&(time() <= strtotime($this->end_sale))) {
			return self::STATUS_SALE;
		}elseif (strtotime($this->end_sale) < time()) {
			return self::STATUS_SALE_END;
		}
	}

	public function getPoster($size=false)
	{
		$dir = Yii::app()->getBaseUrl(true).DIRECTORY_SEPARATOR."uploads/".$this->id.DIRECTORY_SEPARATOR;
		if (!$this->poster)
			return null;
		$ext = pathinfo($this->poster->file, PATHINFO_EXTENSION);
		$name = pathinfo($this->poster->file,PATHINFO_FILENAME);
		$result =  array(
			"o" => $dir.$this->poster->file,
			"l" => $dir.$name."_l.".$ext,
			"m" => $dir.$name."_m.".$ext,
			"s" => $dir.$name."_s.".$ext
		);
		if (empty($result))
			return null;
		elseif ($size)
			return isset($result[$size]) ? $result[$size] : null;
		else
			return $result;
	}

	public function compareTicketCss()
	{

	}

	public function getOpenCloseMessage()
	{

		return $this->isInSale ? 'Закрити подію з продажу' : 'Відкрити подію в продаж';
	}

	public function getIsInSale()
	{
		return ((strtotime($this->start_sale) != null && strtotime($this->end_sale) < time() && strtotime($this->end_sale) != null)||strtotime($this->start_sale) == null)? false: true;

	}

	public function hasTickets()
	{
		return Ticket::model()->exists("event_id=:event_id AND status!=:status", array(
			":event_id"=>$this->id,
			":status"=>Ticket::STATUS_CANCEL
			));
	}

}
