<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 07.05.2015
 * Time: 13:55
 */
class TreeWidget extends CWidget {
    public $model;

    public function init()
    {
        Yii::app()->clientScript->registerCss('widget-style',
            '.treeWidget {
                border: 1px solid lightblue;
                border-radius: 10px;
                padding: 10px;
            }
            .widget-underline {
                border-bottom: 2px solid lightblue;
                margin-bottom : 20px;
            }
            .widgetLabel {
                font-size: 14px;
            }'
        );

        Yii::app()->clientScript->registerScript('treeWidgetScript', '

            function generatePath(id, appendDiv) {
                $.post("'.$this->controller->createUrl('/configuration/tree/getElementPath').'", {
                    id: id
                    }, function(result){
                        if (!Array.isArray(id)) {
                            appendDiv.html(null);
                            appendDiv.append("<h5>"+result+"</h5>");
                        } else {
                            var results = JSON.parse(result);
                            appendDiv.html(null);
                            for (myid in results) {
                            appendDiv.append("<h5>"+results[myid]+"</h5>");
                            }
                        }
                    });
            }

            function appendPath(element) {
                var id = element.val(),
                    name = element.attr("name"),
                    number = parseInt(name.replace(/\D+/g,"")),
                    appendDiv = $("#pathDiv_"+number);

                if (id == 0 || id == null) {
                    $("#pathDiv_"+number).html(null);
                } else {
                   generatePath(id, appendDiv);
                }
            }

            $(".selectElements").each(function(){
               appendPath($(this));
            })

            $(document).on("click", ".eraseElement", function(e) {
               var select = $(this).prev(select).select2("val", "");
               select.change();
			});

            $(document).on("change", ".selectElements", function(e) {
                appendPath($(this));
			});

		', CClientScript::POS_READY);

    }

    public function run()
    {
        $modelName = CHtml::modelName($this->model);
//      creating roles
//        $templateRoles = TemplateRole::model()->findAll();
        $i = 1;
        /*foreach ($templateRoles as $tempRole) {
            $type = $tempRole->getTemplateRoleModelType($modelName);
            if(is_array($type))
                $type = intval(end($type));
            $role_ids = $tempRole->getRoleIds();
            $data = array();
            if (!empty($role_ids) && $role_ids !== false) {
                foreach ($role_ids as $role_id) {
                    $role = Role::model()->findByPk($role_id);
                    $data[$role->id] = $role->name;
                }
            }
            if ($type !== false) {
                if (!$this->model->isNewRecord) {
                    $tags = Tag::model()->findAllByAttributes(array("model_name"=>$modelName, "model_id"=>$this->model->id, "relation_name"=>"Role", "template_id"=>$tempRole->id));
                    $tag = array();
                    if (!empty($tags)) {
                        foreach ($tags as $_tag) {
                            $tag[$_tag->relation_id]= array('selected'=>'selected');
                        }
                    } else
                        $tag = null;
                } else {
                    $tag = null;
                }
                $this->render('viewRoleWidget',array("modelName"=>$modelName, "i"=>$i, "templateName"=>$tempRole->name, "templateRole_id"=>$tempRole->id,
                    "count"=>$type, "data"=>$data, "tag"=>$tag));
                $i++;
            }

        }*/
        $treeRule = TreeRule::model()->findAllByAttributes(array("model"=>$modelName));
//        creating trees
        foreach ($treeRule as $rule) {
            $tree = Tree::model()->findByPk($rule->tree_id);
            $demoRule = $rule->rule;
            $demoCount = $rule->count;
            $treeGroup =  $tree->parent()->find();
            $treeName = $tree->name;
            if ($demoCount == 0)
                $countLabel = "необмежено";
            else
                $countLabel = "тільки 1";
            if ($demoRule == 1) {
                $ruleLabel = "обираються листки на кінцях дерева";
                $trees = $tree->descendants()->findAll();
                $data = array();
                foreach ($trees as $_tree) {
                    if (!$_tree->hasDescendants()){
                        array_push($data,$_tree);
                    }
                }
                $elementsData = CHtml::listData($data,'id','name');
            } else {
                $ruleLabel = "обираються будь які листки";

                $elementsData = CHtml::listData($tree->descendants()->findAll(),'id',function($tree) {
                    return str_repeat(html_entity_decode('&nbsp;&nbsp;&nbsp;&nbsp;'), $tree->level-3).$tree->name;
                });
            }
            if (!$this->model->isNewRecord) {
                    $tags = Tag::model()->findAllByAttributes(array("model_name"=>$modelName, "model_id"=>$this->model->id, "relation_name"=>"Tree"));
                    $tag = array();
                    if (!empty($tags)) {
                        foreach ($tags as $_tag) {
                            $tag[$_tag->relation_id]= array('selected'=>'selected');
                        }
                    } else
                        $tag = null;
            } else {
                $tag = null;
            }

            $this->render('viewTreeWidget',array("groupName"=>$treeGroup->name, "treeName"=>$treeName, "rule"=>$ruleLabel, "count"=>$countLabel,"countInt"=>$demoCount,"model"=>$this->model,
                "modelName"=>$modelName, "i"=>$i, "data"=>$elementsData, "tag"=>$tag));
            $i++;
        }
    }

}