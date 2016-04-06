<?php

/**
 * This is the model class for table "{{country}}".
 *
 * The followings are the available columns in table '{{country}}':
 * @property integer $id
 * @property string $name
 * @property integer $vk_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property City[] $cities
 */
class Country extends CActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_NOACTIVE = 0;
    public $country_id_del;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{country}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('status', 'numerical', 'integerOnly' => true),
            array('name', 'unique'),
            array('country_id_del', 'validateDelete' , 'on'=>'delete'),
            array('name', 'length', 'max' => 128),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, status, country_id_del', 'safe', 'on' => 'search'),
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
            'cities' => array(self::HAS_MANY, 'City', 'country_id'),
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
            'status' => 'Активність',
        );
    }

    public function validateDelete($attribute, $params) {
        if (!empty($this->cities))
            $this->addError($attribute, "Неможливо видалити країну, якщо в ній є міста");
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
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Country the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param $name
	 * @return int
	 */
    public static function getIdByName($name)
    {
        $model = self::model()->findByAttributes(array("name"=>$name));
        if (!$model){
            $model = new Country();
            $model->name = $name;
            if ($model->save)
                $model->refresh();
        }
        return $model->id;
    }

	/**
	 * @return mixed
	 */
    public static function getListAll()
    {
        return Yii::app()->db->createCommand()
            ->select("id, name as value")
            ->from("{{country}}")
            ->queryAll();
    }

    public static function getListExistLocations()
    {
        $locations = Location::model()->with("city")->findAll();
        $country_ids = array();
        foreach ($locations as $location){
            if(in_array($location->city->country_id,$country_ids))
                continue;
            $country_ids[] = $location->city->country_id;
        }
        $ids = implode(",",$country_ids);
        return CHtml::listData(self::model()->findAll("id in (".$ids.")"), "id", "name");
    }

    public static function getCountryList()
    {
        return CHtml::listData(self::model()->findAllByAttributes(array("status"=>self::STATUS_ACTIVE)), "id", "name");
    }

    public function deleteCitiesByCountry()
    {
        $connection = Yii::app()->db;
        $sql = "DELETE FROM {{city}} WHERE country_id = :country_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":country_id", $this->id, PDO::PARAM_INT);
        $command->execute();
    }

    public static function getName()
    {
        return "Країна";
    }

    public function getHasEvents()
    {
        return Yii::app()->db->createCommand()
            ->select("COUNT(*)")
            ->from(Event::model()->tableName()." e")
            ->join(Scheme::model()->tableName()." s", "s.id=e.scheme_id")
            ->join(Location::model()->tableName()." l", "l.id=s.location_id")
            ->join(City::model()->tableName()." c", "c.id=l.city_id")
            ->join(Country::model()->tableName()." ct", "ct.id=c.country_id")
            ->where("country_id=:country_id AND e.id IS NOT NULL", array(
                ":country_id"=>$this->id
            ))
            ->queryScalar();
    }
}
