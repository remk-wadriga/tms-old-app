<?php

/**
 * This is the model class for table "{{user_token}}".
 *
 * The followings are the available columns in table '{{user_token}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $token
 * @property integer $platform_id
 * @property string $date_create
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $platform
 * @property User $user
 */
class UserToken extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_token}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, token, platform_id, date_create, status', 'required'),
			array('user_id, token, platform_id, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, token, platform_id, date_create, status', 'safe', 'on'=>'search'),
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
			'platform' => array(self::BELONGS_TO, 'User', 'platform_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'token' => 'Token',
			'platform_id' => 'Platform',
			'date_create' => 'Date Create',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('token',$this->token);
		$criteria->compare('platform_id',$this->platform_id);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserToken the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function generateToken()
	{
		return substr(str_shuffle("1234567891"), 0, 10);
	}

	public static function createToken($user, $platform)
	{
		$model = new self;
		$model->user_id = $user->id;
		$model->platform_id = $platform->id;
		$model->token = self::generateToken();
		$model->save(false);
		return $model->token;
	}
}
