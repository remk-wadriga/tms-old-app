<?php

/**
 * This is the model class for table "{{tag}}".
 *
 * The followings are the available columns in table '{{tag}}':
 * @property string $model_name
 * @property integer $model_id
 * @property integer $relation_id
 * @property string $relation_name
 * @property integer $template_id
 */
class Tag extends CActiveRecord
{
	const EVENT_CATEGORIES_TREE = 32;

	public static function createTag($model_id,$relation_name,$modelName,$relation_id,$template_id)
	{
		if (!empty($relation_id)) {
			$i = 1;
			foreach ($relation_name as $name) {
				if(array_key_exists($i,$relation_id)) {
					$relation = $relation_id[$i];
				} else
					$relation = 0;
				if (!is_array($relation)) {
					if ($relation_name[$i] == "Tree") {
						$template = 0;
					}else {
						$template = $template_id[$i];
					}
					$query[] = "(" . implode(",", array(
							"model_id" =>  "\"" .$model_id. "\"",
							"model_name" => "\"" .$modelName[$i]. "\"",
							"relation_id" => "\"" .$relation. "\"",
							"relation_name" => "\"" .$relation_name[$i]. "\"",
							"template_id" => "\"" .$template. "\"",
						)) . ")";
				} else {
					foreach ($relation as $_relation) {
						if ($relation_name[$i] == "Tree") {
							$template = 0;
						}else {
							$template = $template_id[$i];
						}
						$query[] = "(" . implode(",", array(
								"model_id" => "\"" . $model_id . "\"",
								"model_name" => "\"" . $modelName[$i] . "\"",
								"relation_id" => "\"" . $_relation . "\"",
								"relation_name" => "\"" . $relation_name[$i] . "\"",
								"template_id" => "\"" . $template . "\"",
							)) . ")";
					}
				}
				$i++;
			}
			if (!empty($query)) {
				$sql = "INSERT INTO " . Tag::model()->tableName() . " (model_id, model_name, relation_id, relation_name, template_id) VALUES " . implode(",", $query);
				Yii::app()->db->createCommand($sql)->execute();
			}
		}
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tag}}';
	}

	/**
	 * @return array relational rules.
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tag the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getEventTags($id=false)
	{
		$modelName = CHtml::modelName(Event::model());
		$elementsData = [];

		if ($id) {
			$tree = Tree::model()->findByPk($id);
            if ($tree)
                $elementsData = CHtml::listData($tree->descendants()->findAllByAttributes(["status"=>Event::STATUS_ACTIVE]),'id',function($tree) {
                    return str_repeat(html_entity_decode('&nbsp;&nbsp;&nbsp;&nbsp;'), $tree->level-3).$tree->name;
                });
		} else {
			$treeRule = TreeRule::model()->findAllByAttributes(array("model"=>$modelName));
			foreach ($treeRule as $rule) {
				$tree = Tree::model()->findByPk($rule->tree_id);
                if ($tree)
                    $elementsData += CHtml::listData($tree->descendants()->findAllByAttributes(["status"=>Event::STATUS_ACTIVE]),'id',function($tree) {
                        return str_repeat(html_entity_decode('&nbsp;&nbsp;&nbsp;&nbsp;'), $tree->level-3).$tree->name;
                    });
			}
		}
		$result = array();
		foreach ($elementsData as $k => $value) {
			$result[] = array(
				"id"=>$k,
				"name"=>$value
			);
		}
		return $result;
	}

	public static function getEventsByTag($id,$city_id=null)
	{
		$modelName = CHtml::modelName(Event::model());
		$tags = Tag::model()->findAllByAttributes(["model_name"=>$modelName, "relation_id"=>$id]);
		$event_ids = [];
		if(!empty($tags)) {
			foreach ($tags as $tag) {
				$event_ids[] = $tag->model_id;
			}
			$eventsTemp = Event::model()->with(['poster', 'scheme', 'scheme.location', 'scheme.location.city'])->findAllByPk($event_ids);
			$events = [];
			foreach ($eventsTemp as $event) {
				if($city_id)
				{
					if ($event->status = Event::STATUS_ACTIVE && $event->scheme->location->city->id == $city_id) {
						$events[] = $event;
					}
				}else {
					if ($event->status = Event::STATUS_ACTIVE) {
						$events[] = $event;
					}
				}
			}
			return $events;

		} else
			return false;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model_name, model_id, relation_id, relation_name', 'required'),
			array('model_id, relation_id, template_id', 'numerical', 'integerOnly'=>true),
			array('model_name, relation_name', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model_name, model_id, relation_id, relation_name, template_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
            'id' => 'ID',
			'model_name' => 'Model Name',
			'model_id' => 'Model',
			'relation_id' => 'Relation',
			'relation_name' => 'Relation Name',
			'template_id' => 'Template',
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
		$criteria->compare('model_name',$this->model_name,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('relation_id',$this->relation_id);
		$criteria->compare('relation_name',$this->relation_name,true);
		$criteria->compare('template_id',$this->template_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
