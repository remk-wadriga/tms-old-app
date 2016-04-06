<?php

/**
 * This is the model class for table "{{slider}}".
 *
 * The followings are the available columns in table '{{slider}}':
 * @property integer $id
 * @property integer $event_id
 * @property integer $multimedia_id
 * @property integer $is_on_main
 * @property integer $small_multimedia_id
 * @property integer $status
 * @property string $background_color
 * @property string $text_color
 *
 * The followings are the available model relations:
 * @property Event $event
 * @property Multimedia $multimedia
 * @property Multimedia $smallMultimedia
 * @property SliderCity[] $sliderCities
 */
Yii::import('application.modules.event.models.*');
Yii::import('application.modules.location.models.*');
Yii::import('application.modules.configuration.models.*');

class Slider extends CActiveRecord
{
	const STATUS_ON_MAIN = 1;
	const STATUS_NOT_ON_MAIN = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_NO_ACTIVE = 0;
	public $sliderCities;

	public static function getEventData($id)
	{
		if (is_array($id)) 	{
			$result = Yii::app()->db->createCommand()
				->select("t.id, t.name, t.url, (SELECT MIN(start_sale) from {{timing}} tm WHERE tm.event_id=t.id) as start,
			        location.name as location_name, location.short_name as location_short_name, city.name as city_name")
				->from("{{event}}  t")
				->join("{{scheme}}  scheme","t.scheme_id=scheme.id")
				->join("{{location}}  location", "scheme.location_id=location.id")
				->join("{{city}}  city", "city.id=location.city_id")
				->andWhere(["in", "t.id", $id])
				->queryAll();
		} else {
			$result = Yii::app()->db->createCommand()
				->select("t.id, t.name, t.url, (SELECT MIN(start_sale) from {{timing}} tm WHERE tm.event_id=t.id) as start,
			        location.name as location_name, location.short_name as location_short_name, city.name as city_name")
				->from("{{event}}  t")
				->join("{{scheme}}  scheme","t.scheme_id=scheme.id")
				->join("{{location}}  location", "scheme.location_id=location.id")
				->join("{{city}}  city", "city.id=location.city_id")
				->andWhere("t.id=:id",["id"=>$id])
				->queryAll();
		}
		return $result;
	}

	public static function getSliderByCity($city_id)
	{
		if ($city_id) {
			$slider_id = Yii::app()->db->createCommand()
				->select("t.slider_id")
				->from("{{slider_city}}  t")
				->andWhere("t.city_id=:id",["id"=>$city_id])
				->queryColumn();
			if (!empty($slider_id))
				return self::model()->findAllByAttributes(["id"=>$slider_id, "status"=>self::STATUS_ACTIVE]);
			else
				return false;
		}
		return false;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Slider the static model class
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
		return '{{slider}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, background_color, text_color', 'required'),
			array('event_id, multimedia_id, small_multimedia_id, is_on_main, status', 'numerical', 'integerOnly'=>true),
				array('background_color, text_color', 'length', 'max'=>50),
			array('id, event_id, multimedia_id, small_multimedia_id, background_color, text_color, sliderCities, is_on_main, status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, event_id, multimedia_id, small_multimedia_id, background_color, text_color, is_on_main, status', 'safe', 'on'=>'search'),
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
			'multimedia' => array(self::BELONGS_TO, 'Multimedia', 'multimedia_id'),
			'smallMultimedia' => array(self::BELONGS_TO, 'Multimedia', 'small_multimedia_id'),
			'sliderCities' => array(self::HAS_MANY, 'SliderCity', 'slider_id'),
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
			'multimedia_id' => 'Слайд',
			'small_multimedia_id' => 'Малий слайд',
			'background_color' => 'Колір бекграунду',
			'text_color' => 'Колір тексту',
			'is_on_main' => 'Відображати на головній сторінці'
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
		$criteria->compare('multimedia_id',$this->multimedia_id);
		$criteria->compare('small_multimedia_id',$this->small_multimedia_id);
		$criteria->compare('background_color',$this->background_color,true);
		$criteria->compare('text_color',$this->text_color,true);
		$criteria->compare('is_on_main',$this->is_on_main);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterFind()
	{
		if (!$this->isNewRecord) {
			$cities = Yii::app()->db->createCommand()
				->select("city_id")
				->from("{{slider_city}}")
//				->join("{{city}} c","c.id=t.city_id")
				->andWhere("slider_id=:slider_id", array("slider_id" => $this->id))
				->queryAll();
			$result = [];
			foreach ($cities as $city)
				$result[$city["city_id"]] = $city["city_id"];
			$this->sliderCities = $result;
		}
		parent::beforeFind();
	}

	public function beforeDelete()
	{
		if ($this->multimedia_id) {
			$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->id."_slider".DIRECTORY_SEPARATOR;
			$multimedia_id = $this->multimedia_id;
			$small_multimedia_id = $this->small_multimedia_id;
			$query = "UPDATE {{slider}} SET multimedia_id = NULL, small_multimedia_id = NULL WHERE id = $this->id";
			Yii::app()->db->createCommand($query)->execute();
			$query = "DELETE FROM {{multimedia}} WHERE id in ($multimedia_id,$small_multimedia_id)";
			if(Yii::app()->db->createCommand($query)->execute()) {
				chmod($dir, 0755);
				$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new RecursiveIteratorIterator($it,
					RecursiveIteratorIterator::CHILD_FIRST);
				foreach($files as $file) {
					if ($file->isDir()){
						rmdir($file->getRealPath());
					} else {
						unlink($file->getRealPath());
					}
				}
				rmdir($dir);
			}
		}
			$query = "DELETE FROM {{slider_city}} WHERE slider_id=$this->id";
			Yii::app()->db->createCommand($query)->execute();
			return parent::beforeDelete();
	}

	public function afterSave()
	{
		parent::afterSave();

		$multimedia = CUploadedFile::getInstancesByName('multimedia');
		$small_multimedia = CUploadedFile::getInstancesByName('small_multimedia');

		if (is_array($multimedia) && !empty($multimedia)) {
			$dir = Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR;

			if ($this->multimedia_id) {
				$pic = Multimedia::model()->findByPk($this->multimedia_id);
				$dirFile = Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR . $pic->file;

				$multimedia_id = $this->multimedia_id;
				$query = "UPDATE {{slider}} SET multimedia_id = NULL WHERE id = $this->id";
				Yii::app()->db->createCommand($query)->execute();
				$query = "DELETE FROM {{multimedia}} WHERE id = $multimedia_id";
				if (Yii::app()->db->createCommand($query)->execute()) {
					unlink($dirFile);
				}
			}
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			foreach ($multimedia as $key => $_multimedia) {
				$fileExt = $_multimedia->extensionName;
				$filename = uniqid() . "." . $fileExt;

				if ($_multimedia->saveAs($dir . $filename)) {
					$query = "INSERT INTO " . Multimedia::model()->tableName() . " (file,event_id) VALUES (\"$filename\", NULL)";
					$transaction = Yii::app()->db->beginTransaction();
					try {
						if (Yii::app()->db->createCommand($query)->execute()) {
							$multimedia_id = Yii::app()->db->getLastInsertID();;
							$query = "UPDATE {{slider}} SET multimedia_id = $multimedia_id WHERE id = $this->id";
							Yii::app()->db->createCommand($query)->execute();

						}
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollBack();
						throw $e;
					}
				}
			}
		}
		if (is_array($small_multimedia) && !empty($small_multimedia)) {
			$dir = Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR;
			if ($this->small_multimedia_id) {
				$pic = Multimedia::model()->findByPk($this->small_multimedia_id);
				$dirFile = Yii::getPathOfAlias('webroot.uploads') . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR . $pic->file;

				$multimedia_id = $this->small_multimedia_id;
				$query = "UPDATE {{slider}} SET small_multimedia_id = NULL WHERE id = $this->id";
				Yii::app()->db->createCommand($query)->execute();
				$query = "DELETE FROM {{multimedia}} WHERE id = $multimedia_id";
				if (Yii::app()->db->createCommand($query)->execute()) {
					unlink($dirFile);
				}
			}
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			foreach ($small_multimedia as $key => $_multimedia) {
				$fileExt = $_multimedia->extensionName;
				$filename = uniqid() . "." . $fileExt;

				if ($_multimedia->saveAs($dir . $filename)) {
					$query = "INSERT INTO " . Multimedia::model()->tableName() . " (file,event_id) VALUES (\"$filename\", NULL)";
					$transaction = Yii::app()->db->beginTransaction();
					try {
						if (Yii::app()->db->createCommand($query)->execute()) {
							$multimedia_id = Yii::app()->db->getLastInsertID();;
							$query = "UPDATE {{slider}} SET small_multimedia_id = $multimedia_id WHERE id = $this->id";
							Yii::app()->db->createCommand($query)->execute();

						}
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollBack();
						throw $e;
					}
				}
			}
		}

		$params = [];
		$query = "DELETE FROM {{slider_city}} WHERE slider_id=$this->id";
		Yii::app()->db->createCommand($query)->execute();
		if (!empty($this->sliderCities)) {
			foreach ($this->sliderCities as $city) {
				$params[] = "($city,$this->id) ";
			}
			$query = "INSERT INTO {{slider_city}} (city_id,slider_id) VALUES " . implode(",", $params);
			Yii::app()->db->createCommand($query)->execute();
		}
	}

	public function getImageUrl($api = false)
	{
		$multimedia = Multimedia::model()->findByPk($this->multimedia_id);
		$small_multimedia = Multimedia::model()->findByPk($this->small_multimedia_id);
		$base = Yii::app()->baseUrl;
		if ($api)
			$base = Yii::app()->getBaseUrl(true);

		if ($multimedia){
			$imagePath = $base . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR . $multimedia->file;
			$path = str_replace("\\","/",$imagePath);
		}else
			$path = null;
		if ($small_multimedia) {
			$small_imagePath = $base . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $this->id . "_slider" . DIRECTORY_SEPARATOR . $small_multimedia->file;
			$small_path = str_replace("\\","/",$small_imagePath);
		}else
			$small_path = null;
		$multimedia_file = $multimedia ? $multimedia->file : null;
		$small_multimedia_file = $small_multimedia ? $small_multimedia->file : null;
		return ["path_full" => $path, "file_full" => $multimedia_file, "path_small"=>$small_path, "file_small" => $small_multimedia_file];
	}

	public function getEventName()
	{
		$event = Event::model()->findByPk($this->event_id);
		if ($event)
			return $event->name;
		else
			return "no such event";
	}

	public function getCities()
	{
		$cities = Yii::app()->db->createCommand()
			->select("l.city_id as city_id, c.name")
			->from("{{event}} as t")
			->join("{{scheme}} s","s.id=t.scheme_id")
			->join("{{location}} l","s.location_id=l.id")
			->join("{{city}} c","l.city_id=c.id")
			->andWhere("t.status=:status", array("status" => Event::STATUS_ACTIVE))
			->queryAll();

		$result = [];
		foreach ($cities as $city) {
			$result[$city["city_id"]] = $city["name"];
		}

		return $result;
	}
}
