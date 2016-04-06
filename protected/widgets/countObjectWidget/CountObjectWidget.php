<?php
/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 07.04.2015
 * Time: 0:51
 */

class CountObjectWidget extends CWidget {
    public $class;
    public $module;
    public function init() {
        if (isset($this->module)) {
            switch ($this->module) {
                case "configuration":
                    Yii::import('application.modules.configuration.models.*');
                    break;
                case "location":
                    Yii::import('application.modules.location.models.*');
                    break;
                case "event":
                    Yii::import('application.modules.event.models.*');
                    break;
                default:
                    Yii::import('application.modules.configuration.models.*');
                    Yii::import('application.modules.location.models.*');
                    Yii::import('application.modules.event.models.*');
                    break;
            }
        }
        //Yii::import('application.modules.configuration.models.*');
        Yii::app()->clientScript->registerCss('widget-style',
            '.widget-container {
                border: 1px solid black;
                border-radius: 10px;
                padding: 10px;
            }
            .widget-caption {
                padding: 0px 10px;
            }
            .widget-underline {
                border-bottom: 2px solid black;
            }
            .widget-caption span {
                font-size: 20px;
                font-weight: bold;
            }
            .widget-count {
                padding: 20px 10px;
                text-align: center;
            }
            .widget-count span {
                font-size: 50px;
                font-weight: bold;
            }'
        );
    }

    public function run() {
        $name = "Class";
        $count = 0;
        if (isset($this->class)) {
            $class = $this->class;
            $count = $class::model()->count();
            $name = $class::getName();
        }
        $this->render('count-object',array('name' => $name, 'count' => $count));
    }
}