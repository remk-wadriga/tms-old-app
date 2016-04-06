<?php

/**
 * This is the model class for table "{{kasa_control}}".
 *
 * The followings are the available columns in table '{{kasa_control}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $sum
 * @property string $date
 *
 * The followings are the available model relations:
 * @property User $user
 */
class KasaControl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{kasa_control}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sum', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('sum', 'numerical', 'numberPattern'=>'/^[0-9]{1,8}(\.[0-9]{0,2})?$/'),
			array('sum', 'length', 'max'=>8),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, sum, date', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	public function beforeSave()
	{
		if (parent::beforeSave()) {
			$this->user_id = Yii::app()->user->id;
			$this->role_id = Role::getRoleId(Yii::app()->user->role);
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
			'user_id' => 'User',
			'sum' => 'Сума',
			'date' => 'Дата',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return KasaControl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function calculateDayCash()
    {
        $user_id = Yii::app()->user->id;
        $lastControlDate = Yii::app()->db->createCommand()
            ->select("MAX(date)")
            ->from(self::model()->tableName())
            ->where("user_id=:user_id", array(
                ":user_id"=>$user_id
            ))
            ->queryScalar() ? :0;
        $sum = Yii::app()->db->createCommand()
            ->select("SUM(price)")
            ->from(Ticket::model()->tableName())
            ->where("cash_user_id=:user_id AND status!=:status AND date_pay>:lastDate", array(
                ":user_id"=>$user_id,
                ":status"=>Ticket::STATUS_CANCEL,
                ":lastDate"=>$lastControlDate
            ))
            ->queryScalar();
        return array(
            "lastControl"=>$lastControlDate,
            "sum"=>$sum
        );
    }
}
