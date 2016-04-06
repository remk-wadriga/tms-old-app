<?php

/**
 * This is the model class for table "{{history}}".
 *
 * The followings are the available columns in table '{{history}}':
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property integer $user_id
 * @property string $date_create
 * @property string $state
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $user
 */
class History extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{history}}';
	}

	public $number;
	public $changes = array();

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, model_id, user_id, date_create, state', 'required'),
			array('model_id, user_id, status', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model, model_id, user_id, date_create, state, status', 'safe', 'on'=>'search'),
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

	public function afterFind()
	{
		parent::afterFind();
		$this->state = CJSON::decode($this->state);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'model' => 'Model',
			'model_id' => 'Model',
			'user_id' => 'User',
			'date_create' => 'Date Create',
			'state' => 'State',
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
		$criteria->compare('model',$this->model,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return History the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getIsChanged($attr) {
		return in_array($attr, $this->changes);
	}

	public static function getHistory($model_id)
	{
		$criteria = new CDbCriteria();
		$criteria->compare("model", get_class(Ticket::model()));
		$criteria->compare("model_id", $model_id);
		$criteria->order = "id DESC";

		$dataProvider = new CActiveDataProvider("History", array(
			"criteria"=>$criteria,
			"pagination"=>false
		));

		$i = 1;
		$tickets = $dataProvider->getData();
		foreach ($tickets as $ticket) {
			$state = array();
			if (count($tickets)>1 && $i < count($tickets)) {
				foreach ($ticket->state as $attr=>$value) {
					if ($attr=="date_update")
						continue;
					if ($value != $tickets[$i]->state[$attr]) {
						$state[$attr] = $value? : 'NULL';
						$ticket->changes[] = $attr;
					}
					else
						$state[$attr] = $value;
				}
				$ticket->state = $state;
			}
			$ticket->number = $i;
			$i++;
		}
		return $dataProvider;
	}
}
