<?php

/**
 * This is the model class for table "{{timing}}".
 *
 * The followings are the available columns in table '{{timing}}':
 * @property integer $id
 * @property string $start_sale
 * @property string $stop_sale
 * @property string $entrance
 * @property integer $event_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class Timing extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{timing}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('start_sale, stop_sale', 'required'),
			array('event_id, status', 'numerical', 'integerOnly'=>true),
			array('stop_sale, entrance', 'safe'),
			array('start_sale, stop_sale, entrance', 'validateTime', 'on'=>'ajaxValidate'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, start_sale, stop_sale, entrance, event_id, status', 'safe', 'on'=>'search'),
		);
	}

    public function setTimingAttributes($attributes)
    {
        if (empty($attributes))
            return false;
        else
            foreach ($attributes as $attribute => $value)
            $this->$attribute = $value;
    }

	public function validateTime($attribute)
   {
	   if (strtotime($this->start_sale) > strtotime($this->stop_sale))
		   $this->addError($attribute, "Закінчення не може бути раніше початку");
	   elseif (strtotime($this->start_sale) == null)
		   $this->addError($attribute, "Початок не може бути пустим");
	   elseif (strtotime($this->start_sale) < strtotime($this->entrance))
		   $this->addError($attribute, "Вхід не може бути пізніше початку");
	   elseif (strtotime($this->start_sale) < strtotime("-1 minute",time()) && strtotime($this->stop_sale) < strtotime("-1 minute",time()) && $this->isNewRecord)
		   $this->addError($attribute, "Початок і кінець не можуть бути раніше ніж теперішня дата");
	   elseif (strtotime($this->start_sale) < strtotime("-1 minute",time()) && $this->isNewRecord)
		   $this->addError($attribute, "Початок не може бути раніше ніж теперішня дата");
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
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {

			$this->start_sale = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->start_sale);
			$this->stop_sale = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->stop_sale);
			$this->entrance = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $this->entrance);

			return true;
		} else
			return false;
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->start_sale = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $this->start_sale);
		$this->stop_sale = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $this->stop_sale);
		$this->entrance = Yii::app()->dateFormatter->format("dd.MM.yyyy HH:mm", $this->entrance);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'start_sale' => 'Початок',
			'start_sale[]' => 'Початок',
			'stop_sale' => 'Закінчення',
			'stop_sale[]' => 'Закінчення',
			'entrance' => 'Вхід з',
			'entrance[]' => 'Вхід з',
			'event_id' => 'Подія',
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
		$criteria->compare('start_sale',$this->start_sale,true);
		$criteria->compare('stop_sale',$this->stop_sale,true);
		$criteria->compare('entrance',$this->entrance,true);
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
	 * @return Timing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getName()
    {
        return "Таймінг";
    }
}
