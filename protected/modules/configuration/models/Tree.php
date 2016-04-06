<?php

/**
 * This is the model class for table "{{tree}}".
 *
 * The followings are the available columns in table '{{tree}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $root
 * @property integer $lft
 * @property integer $rgt
 * @property integer $level
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property TreeRules[] $treeRules
 */
class Tree extends CActiveRecord
{

    const isActive_NO = 0;
    const isActive_YES = 1;

    public static $status = array(
        self::isActive_NO =>'не активне дерево',
        self::isActive_YES => 'активне дерево'
    );

    public $createGroup;
    public $group;
    //public $existedGroup;
    public $groupMode = 'activeGroup';
    public $copyMode = 'copyOne';
    public $nested = true;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tree}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs. root, lft, rgt, level
		return array(
			array('name', 'required'),
			array('root, lft, rgt, level, status', 'numerical', 'integerOnly'=>true),
			array('name, groupMode', 'length', 'max'=>50),
			array('description', 'safe'),
            array('group, createGroup', 'validateGroup', 'on'=>'createTree, editTree'),
            array('name', 'validateName', 'on'=>'createTree'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, root, lft, rgt, level, status', 'safe', 'on'=>'search'),
		);
	}

    public function validateGroup($attribute, $params)
    {
        $attr = $this->groupMode;
        if($this->group == null && $this->createGroup == null){
            $message = "Виберіть групу або створіть нову";
            switch ($this->groupMode) {
                case 'activeGroup':
                    $attribute = 'group';
                    break;
                case 'newGroup' :
                    $attribute = 'createGroup';
                    break;
                default:
                    break;
            }
            if (!$this->hasErrors($attribute))
                $this->addError($attribute, $message);
        } elseif ($this->createGroup != null && $attr == 'newGroup') {
            $attribute = 'createGroup';
            $message = "Группа з такою назвою вже існує";
            $groups = self::model()->roots()->findAll();
            $hasSame = false;
            foreach ($groups as $group) {
                if (strtolower($group->name) == strtolower($this->createGroup)) {
                    $hasSame = true;
                }
            }
            if ($hasSame) {
                if (!$this->hasErrors($attribute))
                    $this->addError($attribute, $message);
            }

        }
    }

    public function validateName($attribute, $params)
    {
        $attr = $this->groupMode;
        if($this->name == null){
            $message = "Введіть назву дерева";
            $this->addError($attribute, $message);
        } else {
            if($this->group != null && $attr == 'activeGroup'){
                $parent = self::model()->findByPk($this->group);
                $childrens = $parent->children()->findAll();
                $hasSame = false;
                foreach ($childrens as $child) {
                    if (strtolower($child->name) == strtolower($this->name)) {
                        $hasSame = true;
                    }
                }
                if ($hasSame) {
                    $message = "В вибраній групі вже існує дерево з такою назвою";
                    $this->addError($attribute, $message);
                }
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'treeRules' => array(self::HAS_MANY, 'TreeRule', 'tree_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Назва',
			'description' => 'Опис',
			'root' => 'Root',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'level' => 'Level',
			'status' => 'Активність',
            'createGroup' => 'Введіть назву групи',
            'selectedModel' => 'Створити правило',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('root',$this->root);
		$criteria->compare('lft',$this->lft);
		$criteria->compare('rgt',$this->rgt);
		$criteria->compare('level',$this->level);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tree the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(
            'nestedSetBehavior'=>array(
                'class'=>'application.extensions.nested-set.NestedSetBehavior',
                'leftAttribute'=>'lft',
                'rightAttribute'=>'rgt',
                'levelAttribute'=>'level',
                'hasManyRoots'=>true
            ),
            'ManyManyBehavior'=>array(
                'class' => 'application.extensions.many_many.ManyManyBehavior'
            )
        );
    }

    public static function getRootList()
    {
        return CHtml::listData(self::model()->roots()->findAll(), "id", "name");
    }

    public function hasDescendants()
    {
        $descendants = $this->descendants()->findAll();
        return !empty($descendants);
    }

    public function getDescendantsIds()
    {
        $descendants = $this->descendants()->findAll();
        $ids = array();
        foreach ($descendants as $descendant) {
            $ids[] = $descendant->id;
        }
        return $ids;
    }

    public static function getTreeList()
    {//add checking isDescendantOf(otherNode)
        return CHtml::listData(self::model()->findAll(array("order"=>"t.root, t.lft", "condition"=>"t.level > 1")), "id", function($tree) {
            $level = '';
            for ($i=2;$i<$tree->level;$i++)
                $level .= '-';
            return $level.$tree->name;
        });
    }

    public static function getLevel($level)
    {
        $string = '';
        for($i=2; $i<$level; $i++) {
            $string .= "-";
        }
        return $string;
    }

    public static function getTreeRoots()
    {
        return CHtml::listData(self::model()->findAll(array("order"=>"t.root, t.lft", "condition"=>"t.level = 2")), "id", "name");
    }

    public static function getChildrens($treeId)
    {
        $childrenObjects = array();
        $i = 0;

        if ($treeId != null) {
            $tree = self::model()->findByPk($treeId);
            $childrenObjects = $tree->descendants()->findAll();
        }
        else {
            $trees = Tree::model()->findAll(array("order" => "t.root, t.lft"));
            foreach ($trees as $tree) {
                if($tree->lft != 1) {
                    $childrenObjects[$i] = $tree;
                    $i++;
                }
            }
        }
        return $childrenObjects;
    }


    public static function copyBranchWithAll($root, $new)
    {
        if(!empty($root)) {
            $branches = $root->children()->findAll();
            if ($branches)
                foreach ($branches as $branch) {
                    $newBranch = new Tree();
                    $newBranch->name = $branch->name;
                    $newBranch->description = $branch->description;
                    $newBranch->status = $branch->status;
                    $newBranch->appendTo($new);
                    self::copyBranchWithAll($branch, $newBranch);
                }
            return true;
        } else
            return false;
    }

}
