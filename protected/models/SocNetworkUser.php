<?php

/**
 * This is the model class for table "{{soc_network_user}}".
 *
 * The followings are the available columns in table '{{soc_network_user}}':
 * @property integer $id
 * @property string $network
 * @property string $network_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 * @property User $user
 */
class SocNetworkUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{soc_network_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('network, network_id, user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('network', 'length', 'max'=>45),
			array('network_id', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, network, network_id, user_id', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'network' => 'Мережа',
			'network_id' => 'ID користувача',
			'user_id' => 'Користувач',
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
		$criteria->compare('network',$this->network,true);
		$criteria->compare('network_id',$this->network_id,true);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SocNetworkUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function newUser($id, $service, $name)
	{
		$user = new User();
		$user->name = $name;
		$user->type = User::TYPE_SOC_USER;

		$user->save(false);

		$socUser = new self;
		$socUser->network = $service;
		$socUser->network_id = $id;
		$socUser->user_id = $user->id;
		$socUser->save(false);
		return $user;
	}
}
