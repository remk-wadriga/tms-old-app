<?php

/**
 * This is the model class for table "{{tree_rule}}".
 *
 * The followings are the available columns in table '{{tree_rule}}':
 * @property integer $id
 * @property string $model
 * @property string $rule
 * @property integer $count
 * @property integer $tree_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Tree $tree
 */
class TreeRule extends CActiveRecord
{

//    const noLeaves_rule = 0;
    const bottomLeaves_rule = 1;
    const allLeaves_rule = 2;
    const any_count = 0;
    const one_count = 1;

    public static $rules = array(
//        self::noLeaves_rule =>'не використовує це дерево',
        self::bottomLeaves_rule => 'обираються листки на кінцях дерева',
        self::allLeaves_rule => 'обираються будь які листки'
    );

    public static $count = array(
        self::any_count =>'необмежена кількість',
        self::one_count => 'тільки 1 елемент дерева'
    );


    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tree_rule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, rule, tree_id', 'required'),
			array('count, tree_id, status', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model, rule, count, tree_id, status', 'safe', 'on'=>'search'),
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
			'tree' => array(self::BELONGS_TO, 'Tree', 'tree_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'model' => 'Model',
			'rule' => 'Rule',
			'count' => 'Count',
			'tree_id' => 'Tree',
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
		$criteria->compare('rule',$this->rule,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('tree_id',$this->tree_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descenda nts!
	 * @param string $className active record class name.
	 * @return TreeRule the static model class
	 */

    public static function getModels($modules = array())
    {
        $mid_result = array();
        $result = array();
        foreach ($modules as $module) {
            $min_result = Yii::app()->metadata->getModels($module);
            foreach ($min_result as $res) {
                array_push($mid_result,$res);
            }
        }
        for($i=0;$i<count($mid_result); $i++)
        {
            $result = array_merge($result,array($mid_result[$i] => self::getModelName($mid_result[$i])));
        }
        return $result;
    }

    public static function getModelName($model)
    {
        return $model::getName();
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
