<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 02.02.2015
 * Time: 16:21
 */
class TreeController extends Controller{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionIndex()
    {
        Yii::app()->clientScript->registerCoreScript("jquery.ui");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl()."/js/jquery.mjs.nestedSortable.js");
        Yii::app()->clientScript->registerScript('createTree','
            var increment = 1;
            $(".newG").hide();

            function updateNumber(element) {
                var name = element.attr("name"),
                    number = parseInt(name.replace(/\D+/g,"")),
                    newNumber = number+1;

                element.attr("name", name.replace(number, newNumber));
                element.attr("id", element.attr("id").replace(number, newNumber));
                if (element.prev("label").length != 0)
                    element.prev("label").attr("for", element.prev("label").attr("for").replace(number, newNumber));
                else if (element.parent().prev("label").length != 0)
                    element.parent().prev("label").attr("for", element.parent().prev("label").attr("for").replace(number, newNumber));
                else if (element.parent().parent().prev("label").length != 0)
                    element.parent().parent().prev("label").attr("for", element.parent().parent().prev("label").attr("for").replace(number, newNumber));
                if (element.hasClass("dateTimePicker"))
                    element.datetimepicker({"language":"uk"});
            }

            $("#Tree_groupMode_0").on("click", function(e) {
                $(".newG").hide();
                $(".activeG").show();
            });


            $("#Tree_groupMode_1").on("click", function(e) {
                $("#Tree_group").select2("val", "All");
                $(".activeG").hide();
                $(".newG").show();
            });


            $(document).on("click", ".addCustomParam", function(e) {
                e.preventDefault();
                var _this = $(this);
                var id = 0;
                cloned = $(this).parent().parent();
                cloned.find("select").each(function(){
                    id = $(this).val();
                    $(this).select2("destroy");
                });

                cloned.clone().insertAfter(cloned);

                cloned.next("div").find("select").each(function(){
                    updateNumber($(this));
                    $(this).select2();
                    $(this).val("");
                });

                cloned.find("select").each(function(){
                    $(this).change();
                    $(this).select2();
                });
            });

             $(document).on("change", "select.getGroup", function(e) {
                reinitializeModels($(this));
            });

            $(document).on("click", ".removeCustomParam", function(e) {
                e.preventDefault();
                var _this = $(this),
                removed = _this.parent().parent();
                var rules = $(".rules").length;
                var mySelect = removed.find("select.getGroup");
                var value = mySelect.val();
                var selects = $("select").not(mySelect);
                for (var i=0; i<selects.length; i++) {
                    if(value != "")
                    $(selects[i]).find("option[value="+value+"]").removeAttr("disabled");
                }
                if (rules > 1) {
                removed.remove();
                }
            });

             $(document).on("click", "#newTreeButton", function(e){
                var action = $("#actionTypeTree").val();
                if (action == "Edit") {
                    $("#Tree_name").val("");
                    $("#Tree_group").select2("data", "");
                }
                $("#actionTypeTree").val("Create");
                $(".modal-content").css("width","800px");
                $("#tree_label").text("Створити дерево");
                $(".modal-content").css("margin-left","-100px");
                $("#Tree_status_tree").attr( "checked" , "checked" );
            });

            $(document).on("click", ".deleteBranch", function(e) {
                var boole = true;
				if (!confirm("Ви дійсно хочете видалити елемент \""+$(this).prev("a").text()+"\"?")) {
				    boole = false;
				    console.log(boole);
				}

                if (boole) {
                var title = $(this).attr("value");
                $.post("'.$this->createUrl('delete').'", {
                id: title
                }, function(result) {
                        refreshTree();
                        showAlert("success", result);
                });
                }
			});

             $(document).on("click", ".addBranchId", function(e) {
                var title = $(this).attr("value");
                $("#actionTypeBranch").val("Create");
                $("#idInputBranch").val(title);
                $("#editLocTree").select2("val", title);
                $("#branch_label").text("Створити гілку");
                $(".modal-content").css("width","500px");
                $(".modal-content").css("margin-left","0px");
                $("#Tree_status").attr( "checked" , "checked" );
				e.preventDefault();
			});

            $(document).on("click", "#addBranch", function(e) {
			    var title = $("#filterName").val();
                if(!title) {
                     $(".branchSubmit").attr( "disabled" , true);
                } else {
                    $(".branchSubmit").attr( "disabled" , false);
                }
                $("#editLocTree").select2("val", title);
                $("#branch_label").text("Створити гілку");
                $("#idInputBranch").val(title);
                $("#actionTypeBranch").val("Create");
                $(".modal-content").css("width","500px");
                $(".modal-content").css("margin-left","0px")
                $("#Tree_status").attr( "checked" , "checked" );

				e.preventDefault();
			});

            $(document).on("click", ".accordion", function(e) {
				var _this = $(this),
					nextUl = _this.nextAll("ul:first"),
					spanPlus = _this.find("span");

				nextUl.toggle();
				if (nextUl.is(":visible"))
					spanPlus.removeClass("glyphicon-plus").addClass("glyphicon-minus");
				if (nextUl.is(":hidden")) {
					nextUl.find("ol").each(function() {
						var _this = $(this);
						_this.css({"display":"none"}).prevAll("div").find("span:first").removeClass("glyphicon-minus").addClass("glyphicon-plus");
					});
					spanPlus.removeClass("glyphicon-minus").addClass("glyphicon-plus");
				}
				if (nextUl.length==0) {
					spanPlus.removeClass("glyphicon-plus").removeClass("glyphicon-minus")
				}

			}).nextAll("ul").each(function() {
				$(this).css({"display":"none"});
			});

             $(document).on("click", ".editBranchId", function(e){
                var title = $(this).attr("value");
                $("#idInputBranch").val(title);
                $("#actionTypeBranch").val("Edit");
                $("#branch_label").text("Редагувати гілку");
                $(".modal-content").css("width","500px");
                $(".modal-content").css("margin-left","0px");
                $("#editLocTree").select2("container").show();
				e.preventDefault();

                $.post("'.$this->createUrl('getBranchData').'", {
                id: title
                }, function(result){
                    var presult = JSON.parse(result);
                    $("#Tree_name_branch").val(presult.name);
                    $("#Tree_description_branch").val(presult.description);
                    $("#Tree_status_branch").val(presult.status);
                    if (presult.parentId != 0) {
                        $("#editLocTree").select2("val", presult.parentId);
                    }
                    if (presult.status != 0) {
                        $("#Tree_status_branch").attr( "checked" , "checked" );
                    }

                });

            });

            $(".editGroup").on("click", function(e)
            {
                var title = $("#treeGroup").val();
                $("#branch_label").text("Редагувати группу");
                $("#actionTypeBranch").val("Edit");
                $("#groupOrBranch").val("Group");
                $(".modal-content").css("width","500px");
                $(".modal-content").css("margin-left","0px");
                $("#editLocTree").select2("container").hide();
				e.preventDefault();
                if(title){
                $("#idInputBranch").val(title);

                $.post("'.$this->createUrl('getBranchData').'", {
                id: title
                }, function(result){
                    var presult = JSON.parse(result);
                    $("#Tree_name_branch").val(presult.name);
                    $("#Tree_description_branch").val(presult.description);
                    if (presult.status != 0) {
                        $("#Tree_status").attr( "checked" , "checked" );
                    }
                });
                }
            });

            $("#newBranch").on("hidden.bs.modal", function () {
                $("#idInputBranch").val(0);
                $("#groupOrBranch").val("");
                $("#Tree_name_branch").val("");
                $("#Tree_description_branch").val("");
                $("#branch_label").text("Створити гілку");
                $("#editLocTree").select2("data", "All");
            })

            function eraseRules()
            {
                $(".removeCustomParam:not(:last)").click();
                 var selects = $("select.getGroup");
                 selectedArray = [];
                 for (var i=0; i<selects.length; i++) {
                $(selects[i]).find("option").removeAttr("disabled");
                }
                $("select.ruleSelect").select2("destroy")
                                    .val(null)
                                    .select2();
            }

            $(".editTree").on("click", function(e) {
                var title = $("#filterName").val();
                $("#tree_label").text("Редагувати дерево");
                $("#actionTypeTree").val("Edit");
                $(".modal-content").css("width","800px");
                $(".modal-content").css("margin-left","-100px");
                eraseRules();
				e.preventDefault();
                if(title) {
                $("#idInputTree").val(title);

                $.post("'.$this->createUrl('getTreeData').'", {
                id: title
                }, function(result) {
                    var presult = JSON.parse(result);
                    $("#Tree_name").val(presult.name);
                    $("#Tree_description").val(presult.description);
                    $("#Tree_status").val(presult.status);
                    if (presult.status != 0) {
                        $("#Tree_status_tree").attr( "checked" , "checked" );
                    }
                    $("#Tree_group").select2("data", {id:presult.groupId , text:presult.group});
                    $.post("'.$this->createUrl('getTreeRules').'", {
                    id: title
                    }, function(result2){
                        var presult2 = JSON.parse(result2);
                        console.log(presult2.length);

                        var selectRule = "#TreeRule_rule_"+increment;
                        var selectModel = "#TreeRule_model_"+increment;
                        var selectCount = "#TreeRule_count_"+increment;
                        for (res in presult2) {
                            var selectRule = "#TreeRule_rule_"+increment;
                            var selectModel = "#TreeRule_model_"+increment;
                            var selectCount = "#TreeRule_count_"+increment;
                            $(selectModel).select2("val", presult2[res].model);
                            $(selectRule).select2("val", presult2[res].rule);
                            $(selectCount).select2("val", presult2[res].count);
                            $(".addCustomParam:last").click();
                            increment++;
                            console.log(increment);
                        }
                    });
                });
                }
            });

            function hideError(element)
            {
                var errorDiv = element.parent().parent(),
                    elementID = element.attr("id");
                errorDiv.attr("class", "form-group");
                $("#"+ elementID +"_em_").hide();
            }
            
            $("#newTree").on("hidden.bs.modal", function () {
                $("#tree_label").text("Створити дерево");
                $("#idInputTree").val(0);
                var action = $("#actionTypeTree").val();
                if (action == "Edit") {
                    eraseRules();
                }
                var name = $("#Tree_name"),
                    group = $("#Tree_group"),
                    createGroup = $("#Tree_createGroup");
                if (name.val() == "") {
                      hideError(name);
                }
                if (group.val() == "") {
                    hideError(group);
                }
                if (createGroup.val() == "") {
                    hideError(createGroup);
                }
            });

            function reinitializeModels(element)
            {
                $("select.getGroup").find("option").removeAttr("disabled");
                $("select.getGroup").each(function() {
                    var value = $(this).val();
                    if(value == "")
                        value = null;
                    $("select.getGroup").not($(this)).each(function() {
                    $(this).find("option[value="+value+"]").attr("disabled", "disabled");
                    });
                });
            }

            $(".branchSubmit").on("click", function(e)
            {
                var data = $("#new-branch-form").serialize();
                 $.post("'.$this->createUrl('createTree').'",
                    data
                    , function(result2){
                        $(".closeBranch").click();
                        if($("#groupOrBranch").val() == "Group") {
                            var element = $("#treeGroup");
                            select2RefreshGroups(element);
                        } else {
                            refreshTree();
                            var element = $("#filterName").val();
                            hierarhicSelectData(element);
                        }
                        showAlert("success",result2);
                        console.log(result2);

                    });
            });

            function select2RefreshGroups(select)
            {
                if(select) {
                 $.post("'.$this->createUrl('getBranchData').'", {
                     id: select.val()
                     }, function(result){
                        var obj = JSON.parse(result);
                        $(select).select2("destroy");
                        $(select).find("option[value="+obj.id+"]").text(obj.name);
                        $(select).select2();
                        var element2 = $("#Tree_group");
                        $(element2).select2("destroy");
                        $(element2).find("option[value="+obj.id+"]").text(obj.name);
                        $(element2).select2();
                        var element3 = $("#Tree_group_origin");
                        $(element3).select2("destroy");
                        $(element3).find("option[value="+obj.id+"]").text(obj.name);
                        $(element3).select2();
                        var element4 = $("#Tree_group_copy");
                        $(element4).select2("destroy");
                        $(element4).find("option[value="+obj.id+"]").text(obj.name);
                        $(element4).select2();
                 });
                }
            }

            function refreshTree()
            {
                $.post("'.$this->createUrl('getTreeView').'", {tree_id:$("#filterName").val()}
                        , function(result){
                     var obj = JSON.parse(result);
                    $(".treeView").html(obj);
                    $(".treeView .treeName").each(function() {
                        var _this = $(this);
                        _this.editable({
                            pk: _this.attr("data-pk"),
                            url: _this.attr("data-url"),
                            name: _this.attr("data-name")

                        });
                    });
                $(".accordion_tree").nestedSortable({
				handle: "div",
				items: "li",
				toleranceElement: "> div",
				helper: "clone",
				listType: "ul",
				protectRoot: true,
				isTree: true,
				startCollapsible: true,
				placeholder: "placeholder",
				forcePlaceholderSize: true,
				start: function(event ,ui) {
					var item = ui.item,
						parent = item.parent().parent(),
						nextUl = parent.find("ul:first"),
						spanPlus = parent.find("span:first");
					if (nextUl.find("li").length==3) {
						spanPlus.removeClass("glyphicon-plus").removeClass("glyphicon-minus")
					}
				},
				stop: function(event, ui) {
					var item = ui.item,
						parent = item.parent().parent(),
						parent_id = parent.data("id"),
						prev_id = item.prev().data("id"),
						next_id = item.next().data("id"),
						nextUl = parent.find("ul:first"),
						spanPlus = parent.find("span:first");

					if (nextUl.is(":visible")) {
						spanPlus.addClass("glyphicon-minus")
					}
						$.post("'.$this->createUrl('moveBranch').'",{
							item_id: item.data("id"),
							parent_id: parent_id,
							prev_id : prev_id,
							next_id : next_id
						}, function(result) {

						});

				}
			});
            });
            }

            function showAlert(type, text)
            {
                var alert = $("."+type+"-sector-alert");
                alert.text(text);
                alert.css({"display":"block"});
                setTimeout(function(){alert.fadeOut()}, 2000);
            }

            $(".deleteTree").on("click", function()
            {
                if (!confirm("Ви дійсно хочете видалити дерево \""+$("#filterName").select2("data").text+"\"?"))
					e.preventDefault();

                var title = $("#filterName").val();
                 $.post("'.$this->createUrl('delete').'", {
                id: title
                }, function(result){
                    if (result != false) {
                     showAlert("success", result);
                     location.reload();
                     } else {
                        showAlert("danger", "Будь ласка видаліть елементи дерева перед тим як видаляти дерево");
                     }
                });

            });

            function hierarhicSelectData(selectedTree)
            {
                if(selectedTree) {
                 $.post("'.$this->createUrl('getElements').'", {
                     id: selectedTree
                     }, function(result){
                        var obj = JSON.parse(result);
                        $("#editLocTree").empty();
                        for (x in obj) {
                            $("#editLocTree").append("<option value="+x+">"+obj[x]+"</option>");
                        }
                 });
                }
            }

            $("#editLocTree").on("change", function()
            {
                value = $("#actionTypeBranch").val();
                if(value == "Edit"){
                } else {
                var value = $(this).val();
                $("#idInputBranch").val(value);
                }
            });

            $(".refreshForm").on("click", function()
            {
               document.getElementById("new-tree-form").reset();
               eraseRules();
            });

        ', CClientScript::POS_READY);

        $treeName = Yii::app()->request->getParam('filterName');
        $treeModel = new Tree();
        $treeModel->unsetAttributes();

        $ruleModel = new TreeRule();
        $ruleModel->unsetAttributes();
        $rules = TreeRule::$rules;
        $count = TreeRule::$count;

        $models = TreeRule::getModels(array('event','location'));
        $roots = Tree::getRootList();
        $roots2 = Tree::model()->roots()->findAll();
        $treeNames =  $this->actionGetTreesNames($roots2);
        $childrenObjects = Tree::getChildrens($treeName);

        $this->render('tree', array(
            'treeModel'=>$treeModel,
            'ruleModel'=>$ruleModel,
            'roots' =>$roots,
            'models' =>$models,
            'rules' => $rules,
            'count' => $count,
            'treeNames' => $treeNames,
            'childrens'=> $childrenObjects,
        ));
    }

    public function actionGetModels()
    {
        $models = TreeRule::getModels(array('event','location'));
        echo json_encode($models);
        Yii::app()->end();
    }

    public function actionGetElements()
    {
        $id = Yii::app()->request->getParam('id');
        $tree = Tree::model()->findByPk($id);
        $result = array();
        $result[$tree->id] = $tree->name;
        $elements = $tree->descendants()->findAll();
        if(!empty($elements)) {

            foreach ($elements as $element) {
                $preName = Tree::getLevel($element->level);
                $result[$element->id] = $preName.$element->name;
            }
        }

        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionGetTreesNames($roots=null)
    {
        $result = array();
        if ($roots != null) {
            foreach($roots as $root)
            {
                $childrens = $root->children()->findAll();
                foreach($childrens as $children)
                {
                    $result[$children->id] = $children->name;
                }
            }
            return $result;
        }
            $treeParam = Yii::app()->request->getParam('group');
            $root = Tree::model()->findByPk((int)$treeParam);

        if ($root) {
            $data = $root->children()->findAll();
            foreach($data as $tree)
            {
                echo CHtml::tag('option',
                    array('value'=>$tree->id),CHtml::encode($tree->name),true);
            }

            Yii::app()->end();
        }
    }

    public function actionGetBranchNames()
    {
        $copy = false;
        $id_origin = Yii::app()->request->getParam('id_origin');
        $id_copy = Yii::app()->request->getParam('id_copy');
        $id = 0;
        if ($id_origin) {
            $id = $id_origin;
            $copy = false;
        }
        if ($id_copy) {
            $id = $id_copy;
            $copy = true;
        }

        $root = Tree::model()->findByPk((int)$id);
        if ($root) {
            $data = $root->descendants()->findAll();

            foreach ($data as $tree) {
                $preName = Tree::getLevel($tree->level);
                $tree->name = $preName . $tree->name;
            }

            if ($copy == true)
                $data = array_merge(array(0=>(object)array('id'=>0, 'name'=>'В корінь дерева')), $data);
            else
                $data = array_merge(array(0=>(object)array('id'=>0, 'name'=>'Всі елементи дерева')), $data);


            foreach ($data as $tree)
                echo CHtml::tag('option',
                    array('value'=>$tree->id),CHtml::encode($tree->name),true);
        }
    }


    public function actionCreateTree()
    {
        $branch = Yii::app()->request->getParam('inInputBranch');
        $action = Yii::app()->request->getParam('actionTypeTree');
        if ($action == "Edit") {
            $tree = new Tree('editTree');
        } else {
            if ($branch != 0) {
                $tree = new Tree();
            } else {
                $tree = new Tree('createTree');
            }
        }

        $currentGroup = null;
        $this->performAjaxValidation($tree);
        $treeModel = Yii::app()->request->getParam('Tree');
        $ruleModel = Yii::app()->request->getParam('TreeRule');
        $branchId = Yii::app()->request->getParam('idInputBranch');
        $treeId = Yii::app()->request->getParam('idInputTree');

        $category = new Tree();
        $category->attributes = $treeModel;
        if ($branchId != 0) {
        //create branch
            $action = Yii::app()->request->getParam('actionTypeBranch');
            if ($action == "Edit") {
                $currentParent = Yii::app()->request->getParam('editLocTree');
                $tree = Tree::model()->findByPk($branchId);
                $treeParent = $tree->parent()->find();
                $tree->attributes = $treeModel;
                if ($treeParent && $currentParent != $treeParent->id) {
                    $newParent = Tree::model()->findByPk($currentParent);
                    $tree->moveAsFirst($newParent);
                    $message = "Гілка \"".$tree->name."\" збережена";
                    if (Yii::app()->request->isAjaxRequest) {
                        echo $message;
                        Yii::app()->end();
                    }
                    Yii::app()->user->setFlash("success", "Гілка \"".$tree->name."\" збережена");
                    $this->redirect('index');

                } else {
                    if ($tree->saveNode()) {
                        $message = "Гілка \"".$tree->name."\" збережена";
                        if (Yii::app()->request->isAjaxRequest) {
                            echo $message;
                            Yii::app()->end();
                        }
                        Yii::app()->user->setFlash("success", $message);
                        $this->redirect('index');
                    }
                }
            }
            if ($action == "Create") {
                $parent = Tree::model()->findByPk($branchId);
                if ($category->appendTo($parent)) {
                    $message = "Гілка \"".$category->name."\" збережена";
                    if (Yii::app()->request->isAjaxRequest) {
                        echo $message;
                        Yii::app()->end();
                    }
                    Yii::app()->user->setFlash("success", $message);
                    $this->redirect('index');
                }
            }
        }
//      create or select a group
        if ($treeModel["groupMode"] == "newGroup") {
            $newroot = new Tree();
            $newroot->name = $treeModel["createGroup"];
            if ($newroot->saveNode()) {
                $findId=Tree::model()->find('name=:name', array(':name'=>$treeModel["createGroup"]));
                $currentGroup = $findId->id;
            }
        }
        if ($treeModel["groupMode"]  == "activeGroup")
            if ($treeModel["group"] != null)
                $currentGroup = $treeModel["group"];

//        editing a tree
        if ($treeId != 0) {
            $tree = Tree::model()->findByPk($treeId);
            $tree->attributes = $treeModel;
            $treeParent = $tree->parent()->find();
            if ($currentGroup != $treeParent->id) {
                $newParent = Tree::model()->findByPk($currentGroup);
                if ($tree->moveAsFirst($newParent)) {
                    Yii::app()->user->setFlash("success", "Дерево \"".$tree->name."\" збережено");
                }
            }
            else {
                if($tree->saveNode()) {
                    Yii::app()->user->setFlash("success", "Дерево \"".$tree->name."\" збережено");
                }
            }
//            editing rules
            $rules = TreeRule::model()->findAll('tree_id=:id', array(':id'=>$treeId));
            if (!empty($rules)) {
                foreach ($rules as $rule) {
                    $rule->delete();
                }
            }

            if (!empty($ruleModel['model'])) {

                $ruleModel['model'] = array_values($ruleModel['model']);
                $ruleModel['rule'] = array_values($ruleModel['rule']);
                $ruleModel['count'] = array_values($ruleModel['count']);
                $newTreeRules = $treeId;
                for ($i = 0; $i < count($ruleModel['model']); $i++) {
                    $model = new TreeRule();
                    $model->tree_id = $newTreeRules;
                    $model->model = $ruleModel['model'][$i];
                    $model->rule = $ruleModel['rule'][$i];
                    $model->count = $ruleModel['count'][$i];
                    $model->save();
                    if ($i == count($ruleModel['model'])-1) {
                        Yii::app()->user->setFlash("success", "Дерево  \"".$tree->name."\" і правила збережені");
                        $this->redirect('index');
                    }
                }
            }

        }
//        creating new tree
        $root = Tree::model()->findByPk($currentGroup);
        $category = new Tree();
        $category->attributes = $treeModel;
        if ($category->appendTo($root)) {
            Yii::app()->user->setFlash("success", "Дерево  \"".$category->name."\" збережено");
            $newId = $category->id;
        }

//        creating rules
        if (!empty($ruleModel['model'])) {
            $ruleModel['model'] = array_values($ruleModel['model']);
            $ruleModel['rule'] = array_values($ruleModel['rule']);
            $ruleModel['count'] = array_values($ruleModel['count']);
            for ($i = 0; $i < count($ruleModel['model']); $i++) {
                $model = new TreeRule();
                $model->tree_id = $newId;
                $model->model = $ruleModel['model'][$i];
                $model->rule = $ruleModel['rule'][$i];
                $model->count = $ruleModel['count'][$i];
                $model->save();
                if ($i == count($ruleModel['model'])) {
                    Yii::app()->user->setFlash("success", "Дерево \"".$category->name."\" і правила збережені");
                }
            }
        }
        $this->redirect('index');
        Yii::app()->end();
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionDelete()
    {
        $id = Yii::app()->request->getParam('id');
        $tree = Tree::model()->findByPk($id);
        $treeName = $tree->name;
        if(!empty($tree->treeRules)) {
            $connection = Yii::app()->db;
            $sql = "DELETE FROM {{tree_rule}} WHERE tree_id = :id";
            $command = $connection->createCommand($sql);
            $command->bindParam(":id", $id, PDO::PARAM_INT);
            $command->execute();
        }

        $tree->deleteNode();
        $groups = Tree::model()->findAll('level=:level', array(':level' => 1));
        foreach($groups as $group) {
            $cgroup = $group->children()->findAll();
            if (empty($cgroup)) {
                $group->deleteNode();
            }
        }
        $message = "\"".$treeName."\" видалено успішно";
        if (Yii::app()->request->isAjaxRequest) {
            echo $message;
            Yii::app()->end();
        }
    }

    public function actionUpdate($model)
    {
        Yii::import('ext.bootstrap.components.TbEditableSaver');
        $es = new TbEditableSaver($model);
        $es->update();
        Yii::app()->end();
    }

    public function actionMoveBranch()
    {
        $item_id = Yii::app()->request->getParam('item_id');
        $parent_id = Yii::app()->request->getParam('parent_id');
        $prev_id = Yii::app()->request->getParam('prev_id');
        $next_id = Yii::app()->request->getParam('next_id');
        if ($item_id) {
            $item = Tree::model()->findByPk($item_id);
            if ($prev_id) {
                $prev = Tree::model()->findByPk($prev_id);
                $item->moveAfter($prev);
            } elseif ($next_id) {
                $next = Tree::model()->findByPk($next_id);
                $item->moveBefore($next);
            } elseif ($parent_id) {
                $parent = Tree::model()->findByPk($parent_id);
                $item->moveAsFirst($parent);
            } else
                echo json_encode("false");
            Yii::app()->end();
        }
    }

    public function actionGetBranchData()
    {
        $id = Yii::app()->request->getParam('id');

        $data = Tree::model()->findByPk($id);
        if ($data->isRoot()) {
            $result = array ("id"=>$data->id,"name"=>$data->name, "description"=>$data->description, "status"=>$data->status, "parentId"=>0);
        }
        else {
            $parentData = $data->parent()->find();
            $result = array ("id"=>$data->id,"name"=>$data->name, "description"=>$data->description, "status"=>$data->status, "parentId"=>$parentData->id);
        }
        echo  json_encode($result);
        Yii::app()->end();
    }

    public function actionCopyBranch()
    {
        $originTree = Yii::app()->request->getParam('Tree_name_origin');
        $originBranch = Yii::app()->request->getParam('Tree_branch_origin');
        $copyTree = Yii::app()->request->getParam('Tree_name_copy');
        $copyBranch = Yii::app()->request->getParam('Tree_branch_copy');
        $treeModel = Yii::app()->request->getParam('Tree');

        if ($copyBranch == 0) {
            $root = Tree::model()->findByPk($copyTree);
        } else{
            $root = Tree::model()->findByPk($copyBranch);
            $parentTree = Tree::model()->findByPk($copyTree);
        }

        if ($treeModel['copyMode'] == 'copyAll') {
            if ($originBranch == 0) {
                $tree = Tree::model()->findByPk($originTree);
                $branchortree = "дерева";
                if ($copyBranch == 0) {
                    $new = Tree::model()->findByPk($copyTree);
                } else {
                    $new = Tree::model()->findByPk($copyBranch);
                }
            }
            else {
                $tree = Tree::model()->findByPk($originBranch);
                $branchortree = "гілки";
                $children = $tree->descendants()->findByAttributes(array("id"=>$root->id));
                if($children){
                    Yii::app()->user->setFlash("danger", "Гілка \"".$tree->name."\" зі всіма дочірніми елементами не може бути скопійована в свій дочірній елемент.");
                    $this->redirect('index');
                }
                $new = new Tree();
                $new->name = $tree->name;
                $new->description = $tree->description;
                $new->status = $tree->status;
                $new->appendTo($root);
            }

                if (Tree::copyBranchWithAll($tree,$new)) {
                    if ($copyBranch != 0) {
                        if ($originBranch != 0) {
                            $parentOrigin = Tree::model()->findByPk($originTree);
                            $secondParent = "";
                            if ($copyBranch != 0)
                                $secondParent =  " яке належить дереву \"".$parentTree->name."\"";
                            Yii::app()->user->setFlash("success", "Елементи ".$branchortree." \"".$tree->name."\" дерева \"".$parentOrigin->name."\" скопійовані в  \"".$root->name."\"".$secondParent."");
                        } else {
                            Yii::app()->user->setFlash("success", "Елементи ".$branchortree." \"".$tree->name."\" скопійовані в  \"".$root->name."\" яке належить дереву \"".$parentTree->name."\"");
                        }
                    } else {
                        Yii::app()->user->setFlash("success", "Елементи ".$branchortree." \"".$tree->name."\" скопійовані в  \"".$root->name."\"");
                    }
                    $this->redirect('index');
                }
                else {
                    Yii::app()->user->setFlash("danger", "Дерево \"".$tree->name."\" не має елементів для копіювання");
                    $this->redirect('index');
                }
        }

        if ($treeModel['copyMode'] == 'copyOne') {
            if ($originBranch == 0 ) {
                $tree = Tree::model()->findByPk($originTree);
                $branches = $tree->children()->findAll();
                if (!empty($branches))  {
                    foreach ($branches as $branch) {
                        $newBranch = new Tree();
                        $newBranch->name = $branch->name;
                        $newBranch->description = $branch->description;
                        $newBranch->status = $branch->status;
                        $newBranch->appendTo($root);
                    }
                    if ($copyBranch != 0) {
                        if ($originBranch != 0) {
                            $parentOrigin = Tree::model()->findByPk($originTree);
                            $secondParent = "";
                            if ($copyBranch != 0)
                                $secondParent =  " яке належить дереву \"".$parentTree->name."\"";
                            Yii::app()->user->setFlash("success", "Елемент  \"".$tree->name."\" дерева \"".$parentOrigin->name."\" скопійований в  \"".$root->name."\"".$secondParent."");
                        } else {
                            Yii::app()->user->setFlash("success", "Дерево  \"".$tree->name."\" скопійоване в  \"".$root->name."\" яке належить дереву \"".$parentTree->name."\"");
                        }
                    } else {
                        Yii::app()->user->setFlash("success", "Дерево \"".$tree->name."\" (головні елементи) скопійоване в дерево \"".$root->name."\"");
                    }
                    $this->redirect('index');
                }
                else {
                    Yii::app()->user->setFlash("danger", "Дерево \"".$tree->name."\" не має елементів для копіювання");
                    $this->redirect('index');
                }
            } else {
                $branch = Tree::model()->findByPk($originBranch);
                $newBranch = new Tree();
                $newBranch->name = $branch->name;
                $newBranch->description = $branch->description;
                $newBranch->status = $branch->status;
                if ($newBranch->appendTo($root)) {
                    if ($copyBranch != 0) {
                        Yii::app()->user->setFlash("success", "Елементи гілки \"".$branch->name."\" скопійовані в  \"".$root->name."\" яке належить дереву \"".$parentTree->name."\"");
                    }
                    else {
                        Yii::app()->user->setFlash("success", "Елементи гілки \"".$branch->name."\" скопійовані в  \"".$root->name."\"");
                    }
                    $this->redirect('index');

                }
            }
        }

    }

    public function actionGetTreeData()
    {
        $id = Yii::app()->request->getParam('id');
        $data = Tree::model()->findByPk($id);
        $parent=$data->parent()->find();
        $result = array ("name"=>$data->name, "description"=>$data->description, "status"=>$data->status, "group"=>$parent->name, "groupId"=>$parent->id);
        echo  json_encode($result);
        Yii::app()->end();
    }

    public function actionGetTreeRules()
    {
        $id = Yii::app()->request->getParam('id');
        $rules = TreeRule::model()->findAll('tree_id=:id', array(':id'=>$id));
        $result = array();
        foreach($rules as $rule) {
            $result[$rule->id] = array("model"=>$rule->model, "rule"=>$rule->rule, "count"=>$rule->count);
        }
        echo  json_encode($result);
        Yii::app()->end();
    }

    public function actionGetTreeView()
    {
        $tree_id = Yii::app()->request->getParam('tree_id');
        $trees = Tree::getChildrens($tree_id);
        echo json_encode($this->renderPartial('_treeView', array("trees"=>$trees), true));
        Yii::app()->end();
    }

    public function actionGetBranches()
    {
        $result = Tree::getTreeList();
        echo  json_encode($result);
        Yii::app()->end();
    }

    /**
     * @param $element
     * @return string
     */
    public function actionGetElementPath()
    {
        $id = Yii::app()->request->getParam('id');
        if (is_array($id)) {
            $path = array();
            $trees = Tree::model()->findAllByAttributes(array("id"=>$id), array("order"=>"root, lft"));
            foreach ($trees as $tree) {
                $path[$tree->id] = $this->elementPath($tree);
            }
            echo json_encode($path);
        } else {
            $element = Tree::model()->findByPk($id);
            $path = $this->elementPath($element);
            echo $path;
        }
        Yii::app()->end();
    }


    private function elementPath($element)
    {
        $dispatcher = "/";
        $pathArray = $element->ancestors()->findAll();
        $arrayNames = array();
        foreach ($pathArray as $tree) {
            if($tree->level > 2)
                $arrayNames[] = $tree->name;
        }

        $path = implode($dispatcher,$arrayNames);
        if ($element->level == 3)
            $totalPath = $path.$dispatcher.$element->name;
        else
            $totalPath = $dispatcher.$path.$dispatcher.$element->name;
        return $totalPath;
    }


}