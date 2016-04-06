<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 19.10.15
 * Time: 16:22
 *
 * @var $params array
 * @var $type string
 * @var $this UserController
 * @var $model
 * @var $module
 * @var $controller
 * @var $action
 */
?>

<div class="checkBoxGroup row">
    <?php

    $class = "";
    if ($type=="tabs") {
        $tabs = array();
        $i = 0;
        foreach ($params as $k => $param) {
            $tabs[] = array(
                "label"=>$param['name'],
                "content"=>$this->renderPartial("table", array("module"=>$module, "controller"=>$controller, "action"=>$action,"params"=>$params[$k]+["id"=>$k], "type"=>""), true),
                "active"=>$i==0
            );
            $i++;
        }
        $this->widget("booster.widgets.TbTabs", array(
            "type"=>"tabs",
            "tabs"=>$tabs,
            "placement"=>"top",
            "tabContentHtmlOptions"=>array(
                "class"=>"col-lg-10"
            )
        ));
    } else {

        $isUser = $this->model instanceof User;
        if (isset($params['withEvent'])&&$params['withEvent']) {
            $columns = array();
            foreach ($params['params'] as $k=>$param) {

                $name = $module? "Access[$module]\[$controller\]\[$action\]\[$params[id]$k\]" : "Access[$controller]\[$action\]\[$params[id]$k\]";
                $column = stripslashes($name);
                $columns[] = array(
                    "header"=>"$param",
                    "type"=>"raw",
                    "value"=>"CHtml::checkBox('$column'.'[event_id]['.\$data['id'].']', ".($this->role->narrow == Role::LEVEL_PARENT && $this->parentIsAdmin && !$isUser? "true":"Yii::app()->controller->getInArray('$column'.'[event_id]['.\$data['id'].']', '$this->accesses')").", array_merge(".(!$this->parentIsAdmin ? "array(
                        'disabled'=>!Yii::app()->controller->getInArray('$column'.'[event_id]['.\$data['id'].']', '$this->parentEventAccess')
                    )" : "array()").", array('class'=>'access_action')))"
                );
            }
            $name_main = $module? "/$module/".lcfirst(str_replace("Controller", "", $controller))."/$action" : "/".lcfirst(str_replace("Controller", "", $controller))."/$action";
            $this->widget("booster.widgets.TbGridView", array(
                "id"=>$params['id']."_grid_view",
                "dataProvider"=>$this->getTable($params['params'],$params['type'], $name_main, $params["id"]),
                "columns"=>array_merge(array(
                    "id",
                    array(
                        "name"=>"name",
                        "type"=>"raw"
                    ),
                ),$columns)
            ));

        } else
            foreach ($params as $k=>$param) {

                $class = $k;
                $count = count($params);
                $width = floor(12/$count);
                $compareName = $module? "Access[$module]\[".lcfirst(str_replace("Controller", "", $controller))."\]\[$action\]\[$k\]" : "Access[".lcfirst(str_replace("Controller", "", $controller))."]\[$action\]\[$k\]";
                $name = $module? "Access[$module]\[$controller\]\[$action\]\[$k\]" : "Access[$controller]\[$action\]\[$k\]";
                $column = stripslashes($name);
                ?>
                <div class="col-lg-<?=$width?>">
                    <?php
                    if (isset($param['params']) && is_array($param['params']))
                        $this->getWidget($param['type'], $column, $param['params'], (!$this->parentIsAdmin ? array(
                            "disabled"=>!$this->getInArray(stripslashes($compareName), $this->parentEventAccess)
                        ) : array()), ($this->role->narrow == Role::LEVEL_PARENT && $this->parentIsAdmin && !$isUser? :$this->getInArray(stripslashes($compareName),$this->accesses)));

                    if (isset($param['name']))
                        echo CHtml::label($param['name'], $column);
                    ?>

                </div>
                <?php
            }
    }

    ?>
</div>
