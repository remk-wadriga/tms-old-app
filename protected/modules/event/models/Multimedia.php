<?php

/**
 * This is the model class for table "{{multimedia}}".
 *
 * The followings are the available columns in table '{{multimedia}}':
 * @property integer $id
 * @property string $file
 * @property integer $event_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Event[] $events
 * @property Event[] $events1
 * @property Event $event
 */
class Multimedia extends CActiveRecord
{

	const STATUS_POSTER = 0;
	const STATUS_NO_POSTER = 1;
	/**
	 * @return string the associated database table name
	 */
	private $sizes = array(
		array('sizeName'=>'_s', 'sizeWidth'=>250, 'sizeHeight'=>150),
		array('sizeName'=>'_m', 'sizeWidth'=>220, 'sizeHeight'=>310),
		array('sizeName'=>'_l', 'sizeWidth'=>570, 'sizeHeight'=>375),
	);


	public function tableName()
	{
		return '{{multimedia}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file, event_id', 'required'),
			array('event_id, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, file, event_id, status', 'safe', 'on'=>'search'),
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
			'events' => array(self::HAS_MANY, 'Event', 'poster_id'),
			'events1' => array(self::HAS_MANY, 'Event', 'description_id'),
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
			'file' => 'File',
			'event_id' => 'Event',
			'status' => 'Status',
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
		$criteria->compare('file',$this->file,true);
		$criteria->compare('event_id',$this->event_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Multimedia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function saveFile()
	{
		$parsedFormat = pathinfo($this->file, PATHINFO_EXTENSION);
		$parsedName = pathinfo($this->file, PATHINFO_FILENAME);
		foreach ($this->sizes as $size) {
			$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->event_id.DIRECTORY_SEPARATOR;
			$image = Yii::app()->image->load($dir.$this->file);
			$ratio = (int)$image->height / (int)$image->width;
			$calculatedWidth = $ratio * (int)$size['sizeHeight'];
			$widthDifference = (int)$size['sizeWidth'] - (int)$calculatedWidth;

			if ($widthDifference != 0 && $ratio > 0.6 && $ratio < 1.5 && $size['sizeName'] == "_m")
				$image->resize($size['sizeWidth'] + $widthDifference/2, $size['sizeHeight'] + $widthDifference/2, Image::HEIGHT);
			elseif ($widthDifference != 0 && $ratio >= 1.5 && $size['sizeName'] == "_m")
				$image->crop($size['sizeWidth'], $size['sizeHeight']);
			elseif ($widthDifference != 0 && $ratio > 0.6 && $ratio < 1)
				$image->resize($size['sizeWidth'] , $size['sizeHeight'] + $widthDifference);
			elseif ($widthDifference != 0 && $ratio >= 1)
				$image->resize($size['sizeWidth'], $size['sizeHeight'] + $widthDifference*2);
			else
				$image->resize($size['sizeWidth'], $size['sizeHeight'], Image::HEIGHT);
			$image->crop($size['sizeWidth'], $size['sizeHeight']);
			$image->save($dir.$parsedName.$size['sizeName'].".".$parsedFormat);
		}
		return true;
	}

	protected function beforeDelete()
	{
//		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->event_id.DIRECTORY_SEPARATOR;
		if(file_exists($dir.$this->file)) {
//			$type_name = finfo_file($finfo, $dir . $this->file);
//			$type = substr($type_name, 0, strrpos($type_name, '/'));
			unlink($dir . $this->file);
		}
//		if($type == 'image')
		foreach ($this->sizes as $size) {
			$parsedFormat = strstr($this->file,'.');
			$parsedName = str_replace($parsedFormat,'',$this->file);
			if(file_exists($dir.$parsedName.$size['sizeName'].$parsedFormat)) {
				unlink($dir . $parsedName . $size['sizeName'] . $parsedFormat);
			}
		}
		return parent::beforeDelete();
	}

    public static function getName()
    {
        return "Мультимедія";
    }

	public static function changePreview($newPreview,$event_id)
	{
		$oldPreview = self::model()->findByAttributes(array('event_id'=>$event_id, 'status'=> self::STATUS_POSTER));
		if(!empty($oldPreview))
			$oldPreview->saveAttributes(array("status"=>self::STATUS_NO_POSTER));

		$newPreview = self::model()->findByPk($newPreview);
		$newPreview->saveAttributes(array("status"=>self::STATUS_POSTER));
	}

	public function deletePoster()
	{
		$command = Yii::app()->db->createCommand("UPDATE ".Event::model()->tableName()." SET poster_id=NULL WHERE id=".$this->event_id)->execute();
		$this->delete();
		if($command) {
			$newPoster =  Yii::app()->db->createCommand("SELECT id FROM ".self::model()->tableName()." WHERE event_id=".$this->event_id)->queryScalar();
			if ($newPoster) {
				Yii::app()->db->createCommand("UPDATE ".Event::model()->tableName()." SET poster_id=".$newPoster." WHERE id=".$this->event_id)->execute();
				Yii::app()->db->createCommand("UPDATE ".self::model()->tableName()." SET status=".Multimedia::STATUS_POSTER." WHERE id=".$newPoster)->execute();
				return true;
			}
		}
	}

}
