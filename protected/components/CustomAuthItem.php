<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 22.04.15
 * Time: 13:30
 */

class CustomAuthItem extends CAuthItem{

    private $_auth;
    private $_type;
    private $_name;
    private $_description;
    private $_bizRule;
    private $_data;

    public function __construct($auth,$name,$type=null,$description='',$bizRule=null,$data=null)
    {
        $this->_type=$type;
        $this->_auth=$auth;
        $this->_name=$name;
        $this->_description=$description;
        $this->_bizRule=$bizRule;
        $this->_data=$data;
    }


    /**
     * @return string the item name
     */
    public function getName()
    {
        return $this->_name;
    }
}