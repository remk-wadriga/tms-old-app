<?php

/**
 * This is the model class for table "{{scheme}}".
 *
 * The followings are the available columns in table '{{scheme}}':
 * @property integer $id
 * @property string $name
 * @property string $import
 * @property string $params
 * @property integer $location_id
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Location $location
 * @property Sector[] $sectors
 */
class Scheme extends CActiveRecord
{
    public $box;
    const COLOR_WITH_PRICE = "#ff4212";

    public static function getName()
    {
        return "Схема";
    }

    public static function getVisualInfo($model=false,$event_id=false, $quote_ids = false, $inCart=array())
    {
        if (!$event_id)
            $event_id = Yii::app()->request->getParam('event_id');

        if ($model)
            $scheme_id = $model->scheme->id;
        else
            $scheme_id = Yii::app()->request->getParam('scheme_id');
        $sector_id = Yii::app()->request->getParam('sector_id');
        $data = Yii::app()->request->getParam('data');
        if ($sector_id||$data) {
            $sector = Sector::model()->findByPk($sector_id);
            $data = json_decode($data);
            if (isset($data->all)&&$data->all&&($event_id||$scheme_id)&&!$sector_id) {

                $result = false;

                if ($scheme_id&&!$event_id)
                    $result = self::model()->findByPk($scheme_id)->getAllVisual();
                elseif ($quote_ids&&$event_id)
                    $result = $model->scheme->getAllVisual($event_id, $quote_ids);
                elseif ($event_id)
                    $result = $model->scheme->getAllVisual($event_id);
                echo json_encode($result);
                Yii::app()->end();
            }
            if ($scheme_id) {
                $scheme = Scheme::model()->findByPk($scheme_id);
                if (isset($data->getImported)) {
                    $params = $scheme->getParams();
                    if (!in_array(Yii::app()->controller->id,array("sector", "scheme"))) {
                        $scheme->import = $scheme->updateLabels($event_id);
                    }
                    echo json_encode(array("imported"=>$scheme->import)+$params);
                    Yii::app()->end();
                }
            }
            if ($sector){
                switch(Yii::app()->request->requestType) {
                    case "GET" :
                        $result['id'] = $sector_id;
                        if ($quote_ids)
                            $visual = $sector->getVisualQuote($event_id, $quote_ids);
                        else
                            $visual = $sector->getVisual($event_id, $inCart);

                        if($sector->type == Sector::TYPE_SEAT)
                            $result['scheme'] = $visual;
                        else {

                            $result['id'] = "row0col0sector".$result['id'];
                            $result['fun_zone'] = true;
                            $result['sector_id'] = $sector->id;
                            $result['visual'] = $visual;
                            $result['selectable'] = true;
                            if ($visual!="") {
                                $result['class'] = "rect native";
                                $result['fill'] = "#cc0000";
                            }
                        }
                        $params = $sector->getSectorParams();
                        echo json_encode($result+$params);
                        Yii::app()->end();
                        break;
                    default:
                        Yii::app()->end();
                        break;
                }
            }
        }
    }

    /**
     * @param $event_id
     * @return array
     */
    public function updateLabels($event_id)
    {
        $result = array();
        $event = Event::model()->findByPk($event_id);
        foreach ($this->import as $k=>$item) {
            $joinStart = strpos($item, "data-joined-to=\"");
            if (!strpos($item, "makroControl")||$joinStart===false) {
                $result[$k] = $item;
                continue;
            }
            $joinLen = strlen("data-joined-to=\"");
            $joinEnd = strpos($item, "\"", $joinStart+$joinLen);
            $sector_id = substr($item, $joinStart+$joinLen, $joinEnd-$joinStart-$joinLen);
            $sector = Sector::model()->findByPk($sector_id);
            $prices = Place::getPrices($event_id, $sector_id, true);
            $labelStart = strpos($item, "data-label=\"");
            $labelLen = strlen("data-label=\"");
            $labelEnd = strpos($item, "\"", $labelStart+$labelLen);

            $price = false;
            if (!empty($prices)) {
                $colorStart = strpos($item, "fill=\"");
                $colorLen = strlen("fill=\"");
                $colorEnd = strpos($item, "\"", $colorStart+$colorLen);
                $item = substr_replace($item, self::COLOR_WITH_PRICE, $colorStart+$colorLen, $colorEnd-strlen($item));
                $priceMin = current($prices);
                $priceMax = end($prices);
                $price = ", ";
                if ($priceMin == $priceMax)
                    $price .= $priceMin;
                else
                    $price .= $priceMin. " - " .$priceMax;
                $price .= " грн";
            }

            $label = $sector->sectorName .",  Доступно ".$event->getPriceCountBySector($sector_id).($price ? : "");
            $result[$k] = substr_replace($item, $label, $labelStart+$labelLen, $labelEnd-strlen($item));

        }
        return $result;
    }

    /**
     * @param integer|bool $event_id
     * @param integer|bool $quote_id
     * @return array Sectors for visual redactor
     */
    public function getAllVisual($event_id = false, $quote_id = false)
    {
        $result = array();
        $result['scheme_id'] = $this->id;
        $result['imported'] = $this->import;
        $result['box'] = $this->box;
        $inCart = array();
        if ($event_id)
            $inCart = Quote::getInCart($event_id);


        if (is_array($this->sectors) && !empty($this->sectors)) {
            foreach ($this->sectors as $sector) {
                if ($sector->status == Sector::STATUS_NOACTIVE)
                    continue;
                if ($quote_id)
                    $visual = $sector->getVisualQuote($event_id, $quote_id);
                else
                    $visual = $sector->getVisual($event_id, $inCart);

                if (!$visual)
                    continue;

                if ($sector->type == Sector::TYPE_FUN) {


                    $result['sectors'][$sector->id] = array(
                        "id" => "row0col0sector" . $sector->id,
                        "sector_id" => $sector->id,
                        "fun_zone" => true,
                        "type" => Sector::TYPE_FUN,

                        "visual" => isset($visual) ? $visual : new stdClass()
                    );
                } elseif ($sector->type == Sector::TYPE_SEAT)
                    $result['sectors'][$sector->id] = array(
                        "id" => $sector->id,
                        "scheme" => $visual
                    );
                $result['sectors'][$sector->id] = $result['sectors'][$sector->id] + $sector->getSectorParams();

            }
        }
        $result['sectors'] = array_values($result['sectors']);
        return $result;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Scheme the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getParams()
    {
        $result = array();
        $params = (array)json_decode($this->params);
        if (!empty($params) && is_array($params)) {
            foreach ($params as $key => $param) {
                $result[$key] = $param;
            }
        }
        return $result;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{scheme}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, location_id', 'required'),
            array('location_id, status', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 45),
            array('name', 'validateName'),
            array('import, params', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, location_id, status, import, params', 'safe', 'on' => 'search'),
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
            'location' => array(self::BELONGS_TO, 'Location', 'location_id'),
            'sectors' => array(self::HAS_MANY, 'Sector', 'scheme_id'),
            'events' => array(self::HAS_MANY, 'Event', 'scheme_id'),
        );
    }

    public function validateName($attributes)
    {
        $existed = self::model()->findByAttributes(array("name" => $this->name, "location_id" => $this->location->id));
        if ($existed)
            $this->addError($attributes, "Схема з такою назвою вже існує");
    }

    public function afterFind()
    {
        parent::afterFind();
        $importFile = Yii::getPathOfAlias("webroot.scheme") . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . $this->id;
        $importFileBox = Yii::getPathOfAlias("webroot.scheme") . DIRECTORY_SEPARATOR . $this->id . DIRECTORY_SEPARATOR . "box_" . $this->id;
        if (file_exists($importFile))
            $this->import = json_decode(file_get_contents($importFile));
        if (file_exists($importFileBox))
            $this->box = json_decode(file_get_contents($importFileBox));
    }

    public function beforeSave()
    {
        if (parent::beforeSave()) {
            if (!$this->isNewRecord)
                $this->saveImported();
            return true;
        } else
            return false;
    }

    public function saveImported()
    {
        $dir = Yii::getPathOfAlias("webroot.scheme") . DIRECTORY_SEPARATOR . $this->id;

        if (!is_dir($dir))
            mkdir($dir, 0777, true);


        file_put_contents($dir . DIRECTORY_SEPARATOR . $this->id, json_encode($this->import));
        if (isset($this->box))
            file_put_contents($dir . DIRECTORY_SEPARATOR . "box_" . $this->id, json_encode($this->box));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Назва',
            'location_id' => 'Location',
            'import' => 'Import',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('import', $this->import, true);
        $criteria->compare('location_id', $this->location_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'BeforeDeleteBehavior' => array(
                'class' => 'application.extensions.before_delete_behavior.BeforeDeleteBehavior'
            ),
        );
    }

    public function beforeDelete()
    {
        if ($this->events == null) {
            $sectors = Sector::model()->findAllByAttributes(array('scheme_id' => $this->id));
            if (!empty($sectors)) {
                foreach ($sectors as $sector) {
                    $sector->delete();
                }
            }
            parent::beforeDelete();
            return true;
        } else
            return parent::beforeDelete();

    }

    /**
     * Checks that has scheme saved sectors
     * @return bool
     */
    public function getHasSectors()
    {
        return Sector::model()->exists("scheme_id=:scheme_id", array(":scheme_id"=>$this->id));
    }

    /**
     * @return array ListData sectors of this Scheme
     */
    public function getSectorsList()
    {
        if ($this->hasSectors) {
            return CHtml::listData($this->sectors, "id", function ($sector) {
                return $sector->sectorName;
            });
        } else
            return array();
    }

    /**
     * @param $old Scheme instance to copy
     * @return bool
     */
    public function saveCopy($old)
    {
        if ($this->save(false)) {
            $this->refresh();
            $sectors = $old->sectors;
            foreach ($sectors as $sector) {
                $newSector = new Sector();
                $newSector->name = $sector->name;
                $newSector->type = (int)$sector->type;
                $newSector->scheme_id = (int)$this->id;
                $newSector->places = $sector->places;
                $newSector->type_sector_id = (int)$sector->type_sector_id;
                $newSector->type_row_id = TypeRow::getTypeIdByName($sector->type_row_id);
                $newSector->type_place_id = TypePlace::getTypeIdByName($sector->type_place_id);
                $newSector->save();
                if ($newSector->type == Sector::TYPE_SEAT) {
                    $newSector->refresh();
                    foreach ($newSector->places->cell->simple_cell as $k => $place) {
                        $id = Sector::getRowPlaceSector($place->id);
                        $newSector->places->cell->simple_cell[$k]->id = "row" . $id['row'] . "col" . $id['place'] . "sector" . $newSector->id;
                    }
                    $newSector->saveAttributes(array("places" => json_encode($newSector->places)));
                }
            }
            return true;
        }
    }

    /**
     * @return integer Count Places in scheme
     */
    public function getCountPlaces()
    {
        $count = Yii::app()->cache->get("scheme_".$this->id."_sectors_count");
        if (!$count) {
            foreach ($this->sectors as $sector) {
                if ($sector->status == Sector::STATUS_ACTIVE)
                    if (isset($sector->places->cell->simple_cell))
                        $count += count($sector->places->cell->simple_cell);
            }

            Yii::app()->cache->set("scheme_".$this->id."_sectors_count", $count, 60*60*24, new CDbCacheDependency("SELECT MAX(date_update) FROM tbl_sector where scheme_id=".$this->id));
        }
        return $count;
    }

    /**
     * Used on Scheme technical statistic page
     * @return CArrayDataProvider list of sectors with count
     */
    public function getProviderSeatPlaces()
    {
        $result = array();
        foreach ($this->sectors as $sector) {
            if ($sector->status == Sector::STATUS_ACTIVE)
                $result[] = array(
                    'id' => $sector->id,
                    'name' => $sector->sectorName,
                    'places' => isset($sector->places->cell->simple_cell) ? count($sector->places->cell->simple_cell) : ($sector->places->fun_zone->amount != 0 ? $sector->places->fun_zone->amount : "Необмежено")
                );
        }
        $result = array_values($result);
        return new CArrayDataProvider($result, array(
            'pagination' => false
        ));
    }

    public function getHasMacro()
    {
        $params = (array)json_decode($this->params);
        if (empty($params))
            return "false";
        return $params['hasMacro'] ? "true" : "false";
    }

    public function saveParams($data)
    {
        $params = $this->getParams();
        foreach ((array)$data as $k=>$param)
            if (!in_array($k, array("scheme_id", "sectors", "imported"))) {
                if ($param==="") {
                    unset($params[$k]);
                    continue;
                }
                $params[$k] = $param;
            }
        return $params;
    }
}
