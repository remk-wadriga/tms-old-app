<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 15.10.15
 * Time: 13:21
 */
Yii::import("application.modules.event.models.*");
class AccessListWidget extends CWidget
{
    const TYPE_TABS = 1;
    const TYPE_CHECKBOX = 2;
    const TYPE_RADIOLIST = 3;
    const TYPE_CHECKBOXLIST = 4;

    public $model;
    public $role;
    public $list;
    public $narrow;
    public $actions = array();
    public $accesses = array();
    public $parentAccesses = array();
    public $parentEventAccess = array();
    public $parentIsAdmin = false;

    public function init()
    {

        Yii::app()->clientScript->registerScript('AccessList', '
            $(document).on("click", ".nav-tabs a", function(){
                var content = $($(this).attr("href")),
                    access = $(this).attr("data-name");

                if (content.text().length==0) {
                    $.post(
                        "'.$this->owner->createUrl("/user/getAccessList").'",
                        {
                            type: '.($this->model instanceof Role ? Access::LEVEL_ROLE:Access::LEVEL_USER).',
                            access: access,
                            id: '.$this->model->id.',
                            role_id: $("#group_id").val()
                        },
                        function(result){
                            content.html(JSON.parse(result));
                        }

                    )
                }
            });

            $(document).on("click", ".access_action, #levelAccess input", function(){
                var _this = $(this),
                    data = {};
                data[_this.attr("name")] = _this.val();
                data["role_id"] = $("#group_id").val();
                data["user_id"] = $("#user_id").val();
                data["type"] = _this.is(":checked") ? 1:0;
                data["levelAccess"] = $("#levelAccess input:checked").val();
                $.post("'.$this->owner->createUrl("/user/saveAccess").'",
                    data,
                function(result){
                    if (result=="OK")
                        showAlert("success", "Зміни збережено")
                }
            )
            });
        ', CClientScript::POS_READY);

        $accesses = array();
        if ($this->model instanceof Role) {
            $this->role = $this->model;
            $level = Access::LEVEL_ROLE;

            $this->accesses = json_encode($this->role->getAccessNames(array($level)));

            $accesses = $this->role->getAccessNames(array($level), false, true);


        }
        else {
            if ($this->role instanceof Role) {
                $this->accesses = json_encode($this->role->getAccessNames(array(Access::LEVEL_USER), $this->model));
                $accesses = $this->role->getAccessNames(array(Access::LEVEL_USER, Access::LEVEL_ROLE), $this->model, true);
            }
        }

        if (empty($this->role->roleChildren1)||!isset($this->role->roleChildren1[0]))
            new CHttpException(400, "user not have parent");
        $parent = $this->model instanceof User ? $this->role : $this->role->roleChildren1[0];
        $this->parentEventAccess = json_encode($parent->getAccessNames(array(Access::LEVEL_ROLE)));
        $this->parentAccesses = $parent->getAccessNames(array(Access::LEVEL_ROLE), false, true);
        if ($parent->narrow == Role::LEVEL_PARENT) {
            $id = $parent->getParentAccessRecursively($parent->id);
            $parent = Role::model()->findByPk($id);
        }
        $this->parentIsAdmin = $parent->name == "admin";

        $list = $this->role->getAccessList();

        if (!empty($list)) {

            foreach ($list as $mod_name=>$module) {
                if (strstr($mod_name, "Controller")) {
                    $this->getAction($module, $mod_name, false, $accesses);
                } else
                    foreach ($module as $k=>$controller)
                        $this->getAction($controller, $k, $mod_name, $accesses);
            }

        }

    }

    public function getAction($controller, $controllerName, $moduleName=false, $accesses=array())
    {

        foreach ($controller as $key=>$action) {

            $name = ($moduleName ? "/".$moduleName : "")."/".$controllerName."/".$key;


            if ((!is_array($this->parentAccesses)&&!$this->parentIsAdmin)||(!$this->parentIsAdmin&&is_array($this->parentAccesses)&&!Yii::app()->controller->getInArray($name, json_encode($this->parentAccesses),true)))
                continue;
            $this->actions[] = array(
                "label"=>$action['name'],
                "content"=>"",
                "active"=>empty($this->actions),
                "linkOptions"=>array(
                    "data-name"=> ($moduleName ? "/".$moduleName : "")."/".strtolower(str_replace("Controller","",$controllerName))."/".$key
                )
            );
//$this->render("table", array("module"=>$moduleName, "controller"=>$controllerName, "action"=>$key,"params"=>$action['params'], "type"=>isset($action['type'])? $action['type']:""), true),
        }
    }

    public function run()
    {

        $this->render("list", array(
            "tabs"=>$this->actions
        ));
    }


}