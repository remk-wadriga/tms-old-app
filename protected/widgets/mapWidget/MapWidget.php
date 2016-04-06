<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 16.09.15
 * Time: 13:24
 */
class MapWidget extends CWidget
{
    public $class;
    public $hasMacro;
    public $bitMapUrl = false;
    public $cartUrl = false;
    public $funZones = array();

    public function init()
    {
        $this->render("map", array("class"=>$this->class,
            "macro"=>$this->hasMacro,
            "bitMapUrl"=>$this->bitMapUrl,
            "cartUrl"=>$this->cartUrl,
            "funzones"=>$this->funZones
        ));
    }
}