<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 26.06.2015
 * Time: 13:38
 */
class BeforeDeleteBehavior extends CActiveRecordBehavior
{


    public function beforeDelete($event)
    {
        $owner = $this->owner;
        $usedIn = "";
        $modelName = CHtml::modelName($owner);
        if(isset($owner->name))
            $name = "\"".$owner->name."\"";
        else
            $name = "";
        $message = "Не можна видалити ".$modelName::getName()." ".$name." через те що використовується в -";
        if(strtolower($modelName) == "event"){

            $prevEventData = Event::model()->findByPk($owner->id);
            $sql = "UPDATE {{".strtolower($modelName)."}} SET status = ".Event::STATUS_DELETED.", position = NULL WHERE id=".$owner->id;
            if(Yii::app()->db->createCommand($sql)->execute()) {
                Yii::app()->db->createCommand()->update("{{event}}", array("position"=>new CDbExpression("position - 1")),  "position>=:position", array(":position"=>$prevEventData->position));
                $event->isValid = false;
                echo Yii::app()->createUrl(strtolower($modelName)."/".strtolower($modelName).'/index');
                return true;
            }else {
                $event->isValid = false;
                echo "404";
                return false;
            }
        } elseif (strtolower($modelName) == "sector"){
            $pricedPlace = Place::model()->findAllByAttributes(array('sector_id' => $owner->id));
            if (!empty($pricedPlace)) {
                $event->isValid = false;
                echo $message. " Конструкторі цін";
                return false;
            } else {
//                echo true;
                return true;
            }
        }

        $relations = $owner->relations();

        foreach ($relations as $relationName => $relation) {
            if ($relation[0] == CActiveRecord::HAS_MANY) {
                $relationsData = $owner->$relationName;
                if ($relationsData != null) {
                    foreach ($relationsData as $relData) {
                        $modelName = CHtml::modelName($relData);
                        if(isset($relData->name))
                            $relName = "\"".$relData->name."\"";
                        else
                            $relName = "";
                        $usedIn .= " ".$modelName::getName()." ".$relName.".";
                    }
                }
            }
        }
        if($usedIn != ""){
            $event->isValid = false;
            echo $message.$usedIn;
            return false;
        } else
            return true;

    }

}