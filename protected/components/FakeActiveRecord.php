<?php

/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 15.01.16
 * Time: 18:02
 */
class FakeActiveRecord
{
    public $isNewRecord = false;
    public $primaryKey = 'myid';
    public $myid;
    public $count;

    public function isAttributeSafe()
    {
        return true;
    }

    public function getAttributeLabel()
    {
        return 'Text Field';
    }

    public function getScenario() {
        return 'update';
    }
}