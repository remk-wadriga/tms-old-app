<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $alias_url
 * @property string $html_header
 * @property string $meta_description
 * @property string $keywords
 * @property integer $multimedia_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Multimedia $multimedia
 */
Yii::import('application.modules.event.models.*');
class Post extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{post}}';
	}

	const STATUS_ACTIVE = 1;
	const STATIS_NO_ACTIVE = 0;

	public static $statusType = [
		self::STATUS_ACTIVE => "Активна",
		self::STATIS_NO_ACTIVE => "Не активна",
	];
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, alias_url, html_header, meta_description, keywords', 'required'),
			array('multimedia_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('id, name, description, alias_url, html_header, meta_description, keywords, multimedia_id, status', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, alias_url, html_header, meta_description, keywords, multimedia_id, status', 'safe', 'on'=>'search'),
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
			'multimedia' => array(self::BELONGS_TO, 'Multimedia', 'multimedia_id'),
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
			'alias_url' => 'Аліаз Url',
			'html_header' => 'Html заголовок',
			'meta_description' => 'Meta опис',
			'keywords' => 'Ключові слова',
			'multimedia_id' => 'Зображення',
			'status' => "Активність",
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
		$criteria->compare('alias_url',$this->alias_url,true);
		$criteria->compare('html_header',$this->html_header,true);
		$criteria->compare('meta_description',$this->meta_description,true);
		$criteria->compare('keywords',$this->keywords,true);
		$criteria->compare('multimedia_id',$this->multimedia_id);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function beforeDelete()
	{
		if ($this->multimedia_id) {
			$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->id."_post".DIRECTORY_SEPARATOR;
			$multimedia_id = $this->multimedia_id;
			$query = "UPDATE {{post}} SET multimedia_id = NULL WHERE id = $this->id";
			Yii::app()->db->createCommand($query)->execute();
			$query = "DELETE FROM {{multimedia}} WHERE id = $multimedia_id";
			if(Yii::app()->db->createCommand($query)->execute()) {
				chmod($dir, 0755);
				$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new RecursiveIteratorIterator($it,
					RecursiveIteratorIterator::CHILD_FIRST);
				foreach($files as $file) {
					if ($file->isDir()){
						rmdir($file->getRealPath());
					} else {
						unlink($file->getRealPath());
					}
				}
				rmdir($dir);
			}
		}
		return parent::beforeDelete();
	}


	public function afterSave()
	{
		parent::afterSave();

		$multimedia = CUploadedFile::getInstancesByName('multimedia');
		$dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$this->id."_post".DIRECTORY_SEPARATOR;

		if (is_array($multimedia) && !empty($multimedia)) {
			if ($this->multimedia_id) {
				$multimedia_id = $this->multimedia_id;
				$query = "UPDATE {{post}} SET multimedia_id = NULL WHERE id = $this->id";
				Yii::app()->db->createCommand($query)->execute();
				$query = "DELETE FROM {{multimedia}} WHERE id = $multimedia_id";
				if(Yii::app()->db->createCommand($query)->execute()) {
					chmod($dir, 0755);
					$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
					$files = new RecursiveIteratorIterator($it,
						RecursiveIteratorIterator::CHILD_FIRST);
					foreach($files as $file) {
						if ($file->isDir()){
							rmdir($file->getRealPath());
						} else {
							unlink($file->getRealPath());
						}
					}
					rmdir($dir);
				}
			}
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			foreach ($multimedia as $key => $_multimedia) {
				$fileExt = $_multimedia->extensionName;
				$filename = uniqid().".".$fileExt;

				if ($_multimedia->saveAs($dir.$filename)) {
					$query = "INSERT INTO ".Multimedia::model()->tableName()." (file,event_id) VALUES (\"$filename\", NULL)";
					$transaction = Yii::app()->db->beginTransaction();
					try {
						if(Yii::app()->db->createCommand($query)->execute())
						{
							$multimedia_id = Yii::app()->db->getLastInsertID();;
							$query = "UPDATE {{post}} SET multimedia_id = $multimedia_id WHERE id = $this->id";
							Yii::app()->db->createCommand($query)->execute();

						}
						$transaction->commit();
					} catch (Exception $e) {
						$transaction->rollBack();
						throw $e;
					}
				}
			}
		}

	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function getImageUrl($api = false)
	{
		$multimedia = Multimedia::model()->findByPk($this->multimedia_id);
		$base = Yii::app()->baseUrl;
		if ($api)
			$base = Yii::app()->getBaseUrl(true);

		$imagePath = $base . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $this->id . "_post" . DIRECTORY_SEPARATOR . $multimedia->file;
		$path = str_replace("\\","/",$imagePath);
		return ["path" => $path, "file" => $multimedia->file];
	}

	public function getDesc()
	{
		if (strlen($this->description) > 100)
			$desc = substr($this->description, 0, 100) . '...';
		else
			$desc = $this->description;

		return $desc;
	}
}
