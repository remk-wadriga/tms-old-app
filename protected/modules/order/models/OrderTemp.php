<?php

/**
 * This is the model class for table "{{order_temp}}".
 *
 * The followings are the available columns in table '{{order_temp}}':
 * @property integer $id
 * @property double $total
 * @property integer $user_id
 * @property integer $role_id
 * @property string $date_add
 * @property string $token
 * @property integer $api
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Platform $api0
 * @property Role $role
 * @property User $user
 * @property TicketTemp[] $ticketTemps
 */
class OrderTemp extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{order_temp}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_id', 'required'),
			array('user_id, role_id, api, status', 'numerical', 'integerOnly'=>true),
			array('total', 'numerical'),
            array('token', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, total, user_id, role_id, date_add, api, status', 'safe', 'on'=>'search'),
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
			'api0' => array(self::BELONGS_TO, 'Platform', 'api'),
			'role' => array(self::BELONGS_TO, 'Role', 'role_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'ticketTemps' => array(self::HAS_MANY, 'TicketTemp', 'order_temp_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'total' => 'Total',
			'user_id' => 'User',
			'role_id' => 'Role',
			'token' => 'Token',
			'date_add' => 'Date Add',
			'api' => 'Api',
			'status' => 'Status',
		);
	}

    public $_tickets;
    public $_total;

	public function afterSave()
    {
        parent::afterSave();
        if (!empty($this->_tickets)) {
            $result = array();
            $this->_total = 0;
            foreach ($this->_tickets as $place) {
                $this->_total += $place->price;
                $result[] = array(
                    "place_id"=>$place->id,
                    "order_temp_id"=>$this->id,
                    "price"=>$place->price,
					"event_id"=>$place->event_id,
					"sector_id"=>$place->sector_id
                );
            }
            $builder = Yii::app()->db->schema->commandBuilder;
            $command = $builder->createMultipleInsertCommand(
                "{{ticket_temp}}",
                $result
            );
            $command->execute();
        }
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
		$criteria->compare('total',$this->total);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('date_add',$this->date_add,true);
		$criteria->compare('api',$this->api);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderTemp the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function generateRandomString($length) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }
}
