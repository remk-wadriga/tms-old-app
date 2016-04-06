<?php

/**
 * This is the model class for table "{{city}}".
 *
 * The followings are the available columns in table '{{city}}':
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property integer $status
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $vk_id
 * @property integer $region_id
 * @property string $lat
 * @property string $lng
 *
 * The followings are the available model relations:
 * @property Country $country
 * @property Region $region
 * @property Location[] $locations
 */
class City extends CActiveRecord
{

	const STATUS_ACTIVE = 1;
	const STATUS_NOACTIVE = 0;

    public $parent;
    public $translit = null;

    /**
     * @param bool|false $country_id
     * @param bool|false $all
     * @param bool|false $api
     * @return array
     */
    public static function getList($country_id=false, $all=false, $api = false)
    {
		$condition = "";
		$conditionArray = array();
		$inCondition = array();
		if ($country_id) {
			$condition = "country_id=:country_id";
			$conditionArray = array(":country_id"=>$country_id);
		}
        if (!$all || $all != "true") {
            $events = Event::getActiveEvents();
            $cityIds = array_map(function($event){
                return $event->scheme->location->city_id;
            }, $events);
            $inCondition = array("in", "id", $cityIds);
        }
        $result =  Yii::app()->db->createCommand()
            ->select("id, name, level, root, lft, level, rgt, lng, lat")
            ->from("{{city}}")
            ->order("name ASC")
			->where($condition, $conditionArray)
            ->andWhere($inCondition)
            ->queryAll();

        $result = self::sortCity($result);

        $resultArray = array();
        if ($result)
            foreach ($result as $value) {
                $resultArray[$value['id']] = $value['level'] != 1 ? ($api ?  array(
                    "name"=>$value["name"],
                    "lng"=>$value["lng"],
                    "lat"=>$value['lat']
                ) :self::getLevel($value['level']).$value['name'] ): ($api ?  array(
                    "name"=>$value["name"],
                    "lng"=>$value["lng"],
                    "lat"=>$value['lat']
                ) :$value['name']);
            }
        return $resultArray;
    }

    public static function sortCity($result)
    {
//        $result = (array)$result;
//        function cmp($a, $b)
//        {
//            $alphabet = strcmp($a['name'], $b['name']);
//            if ($alphabet <= 0)
//                $alphabet = true;
//            else
//                $alphabet = false;
//
//            return ($a['level']==1&&$b['level']==1&&$alphabet) ||
//                    ($a['root']==$b['root']&&$a['lft']<$b['lft'])
//            ? -1 : 1;
//        }
//        usort($result, "cmp");
        return $result;
    }

	/**
	 * @param $level
	 * @return string
	 */
    public static function getLevel($level)
    {
        $string = '';
        for($i=1; $i<$level; $i++) {
            $string .= "-";
        }
        return $string;
    }

    /**
     * @param $country_id
     * @param $text
     * @param bool|false $region_id
     * @param bool|false $allCities
     * @return array
     */
    public static function getSearchList($country_id, $text, $region_id=false, $allCities=false)
    {
        $condition = "";
        $conditionArray = array();
        if ($region_id) {
            $condition = "AND region_id=:region_id";
            $conditionArray = array(
                ":region_id"=>$region_id
            );
        }
        $cities = City::model()->with('locations.scheme', 'region')->findAllByAttributes(array("country_id"=>$country_id),
            array(
                "condition"=>"t.name LIKE :text ".$condition,
                "order"=>"t.name ASC",
                "limit"=>10,
                "params"=> array(
                        ":text"=>$text.'%'
                    )+$conditionArray
            )
        );
        if(!$allCities){
            foreach ($cities as $key=>$city) {
                if(!$city->locations)
                    unset($cities[$key]);
            }
        }
        $result = array();
        $count = count($cities);
        foreach ($cities as $city) {
            $result[] = array(
                "id"=>$city->id,
                "text"=>CHtml::decode($city->name).($count>1&&isset($city->region)&&!$region_id?" | ".$city->region->name:"")
            );
        }

        return $result;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getName()
    {
        return "Місто";
    }

    /**
     * @param $cities
     * @return mixed
     */
    public static function getTranslit($cities)
    {
        if (is_array($cities)) {
            foreach ($cities as $city){
                $city->translit = UrlTranslit::translit($city->name);
            }
        } else {
           $cities->translit = UrlTranslit::translit($cities->name);
        }

        return $cities;
    }

    /**
     * @param bool|false $active
     * @param bool|false $city_ids
     * @return array
     */
    public static function getCityList($active = false, $city_ids = false)
    {
        if(!$city_ids){
            if ($active)
                $events = Event::getActiveEvents();
            else
                $events = Event::model()->with("scheme.location")->findAll();
            $city_ids = array_map(function($event){
                return $event->scheme->location->city_id;
            },$events);
        }

        return CHtml::listData(self::model()->findAllByAttributes(array("id"=>$city_ids)), "id", "name");
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, country_id', 'required'),
			array('country_id, parent, status, root, lft, rgt, level', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
            array('name', 'validateName', 'on'=>'create, update'),
            array('lat, lng', 'length', 'max'=>18),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, country_id, status, root, lft, rgt, level', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
            'region' => array(self::BELONGS_TO, 'Region', 'region_id'),
			'locations' => array(self::HAS_MANY, 'Location', 'city_id'),
		);
	}

    public function validateName($attribute)
    {
        if (self::model()->exists("name=:name AND country_id=:country_id AND region_id=:region_id", array(
            ":name"=>$this->name,
            ":country_id"=>$this->country_id,
            ":region_id"=>$this->region_id
        )))
            $this->addError($attribute, "Місто з такою назвою вже існує");
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'country_id' => 'Країна',
			'status' => 'Активність',
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
            'parent' => 'Підпорядкування',
            'lat' => 'Широта',
            'lng' => 'Довгота',
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
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('root',$this->root);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);
		$criteria->compare('level',$this->level);
        $criteria->compare('lat',$this->lat,true);
        $criteria->compare('lng',$this->lng,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return array
	 */
    public function behaviors()
    {
        return array(
//            'nestedSetBehavior'=>array(
//                'class'=>'application.extensions.nested-set.NestedSetBehavior',
//                'leftAttribute'=>'lft',
//                'rightAttribute'=>'rgt',
//                'levelAttribute'=>'level',
//                'rootAttribute'=>'root',
//                'hasManyRoots'=>true
//            ),
            'BeforeDeleteBehavior'=>array(
                'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
            ),
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public function saveCity($id = 0)
    {
        $parent = self::model()->findByPk($this->parent);
        if ($id) {
            $city = self::model()->findByPk($id);
            $city = $city->parent()->find();
            if ($city['id']) {
                $cityParent = $city['id'];
            }  else {
                $cityParent = 0;
            }
            if ($this->parent) {
                if ($this->parent != $cityParent) {
                    if ($this->moveAsLast($parent)) return true;
                }
                else return true;
            }
            else {
                if ($this->parent != $cityParent) {
                    if ($this->moveAsRoot()) return true;
                }
                else return true;
            }
        }
        else {
            if ($this->parent) {
                if ($this->appendTo($parent)) return true;
            }
            else {
                if ($this->saveNode()) return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getHasEvents()
    {
        $ids = array($this->id);

        return Yii::app()->db->createCommand()
            ->select("COUNT(*)")
            ->from(Event::model()->tableName()." e")
            ->join(Scheme::model()->tableName()." s", "s.id=e.scheme_id")
            ->join(Location::model()->tableName()." l", "l.id=s.location_id")
            ->join(City::model()->tableName()." c", "c.id=l.city_id")
            ->where("e.id IS NOT NULL")
            ->andWhere(array("in", "c.id", $ids))
            ->queryScalar();
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{city}}';
	}
}
