<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 16.12.14
 * Time: 12:09
 */

class ManyManyBehavior extends CActiveRecordBehavior {

    public function afterSave($event)
    {
        $owner = $this->owner;
        $relations = $owner->relations();
        foreach ($relations as $key=>$relation) {
            if ($relation[0] == CActiveRecord::MANY_MANY) {
                if (!empty($owner->$key) && !is_object($owner->{$key}[0])) {

                    $parsed = $this->parseRelation($relation[2]);
                    $this->deleteRelations($parsed);
                    $this->saveRelation($parsed, $owner->$key);
                }
            }
        }

    }


    public function saveEditedRelation($attribute=array())
    {
        if (is_array($attribute)) {
            foreach ($this->owner->relations() as $key=>$relation) {
                if ($relation[0] == CActiveRecord::MANY_MANY) {
                    $parsed = $this->parseRelation($relation[2]);
                    if (isset($this->owner->$parsed['keys'][1]) && $attribute[0]==$parsed['keys'][1]) {
                        $this->owner->$key = $this->owner->$parsed['keys'][1];
                        $this->deleteRelations($parsed);
                        return $this->saveRelation($parsed, $this->owner->$key);
                    }
                }
            }

        } else
            return false;
    }

    public function beforeDelete($event)
    {
        $owner = $this->owner;
        $relations = $owner->relations();
        foreach ($relations as $key=>$relation) {
            if ($relation[0] == CActiveRecord::MANY_MANY) {
                    $parsed = $this->parseRelation($relation[2]);
                    $this->deleteRelations($parsed);
            }
        }
        return true;
    }



    public function getIsRelatedAttribute($changed=array())
    {
        if(!empty($changed)&&is_array($changed)) {
            foreach ($this->owner->relations() as $key=>$relation) {
                if ($relation[0] == CActiveRecord::MANY_MANY) {

                    $parsed = $this->parseRelation($relation[2]);
                    if (isset($this->owner->$parsed['keys'][1]) && $changed[0]==$parsed['keys'][1]) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    private function parseRelation($relation)
    {
        $db = substr($relation, 0, strrpos($relation, '('));
        return array(
            'db'=>$db,
            'keys'=>explode(",",substr($relation,strspn($relation,$db)+1, -1))
        );
    }

    private function deleteRelations($parsed)
    {
        return Yii::app()->db->createCommand()
            ->delete($parsed['db'], $parsed['keys'][0]."=:id", array(":id"=>$this->owner->primaryKey));

    }

    private function saveRelation($parsed, $key)
    {
        $db = $parsed['db'];
        $keys = $parsed['keys'];

        $values_str = "";
        foreach ($key as $related) {
            if ($related=='[object Object]')
                continue;
            $values = array(
                $keys[0] => $this->owner->primaryKey,
                $keys[1] => $related
            );
            if ($values_str=="")
                $values_str="(".implode(",",$values).")";
            else
                $values_str.=",(".implode(",",$values).")";
        }

        $sql = 'INSERT INTO '.$db.' ('.implode(',',$keys).') VALUES '.$values_str.'';
        return Yii::app()->db->createCommand($sql)->execute();
    }


}