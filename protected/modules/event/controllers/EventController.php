<?php

class EventController extends Controller
{
    protected  $_result;

    public static function getActions()
    {
        return array(
            array(
                "url"=>"/event/eventController/edit",
                "name"=>"Редагування"
            ),
            array(
                "url"=>"/event/eventController/delete",
                "name"=>"Видалення"
            )
        );
    }

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

    public function accessList()
    {
        return array(
            'create',
            'previewTicket',
            'edit',
            'getPreview',
            'getLocation',
            'getScheme',
            'getCoords',
            'delete',
            'update',
            'deleteFile',
            'changeFile',
            'stopSale',
            'startSale',
            'generateAliasUrl',
            'resetTicket',
            'preview',
            'getServerTime',
        );
    }

    public function actionIndex()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/theme/js/jquery.sticky-kit.min.js',CClientScript::POS_END);
        Yii::app()->clientScript->registerScript('eventIndex', '
			$(document).on("click", ".event-block", function(e){
			    $(".event-block").removeClass("active");
			    $(this).addClass("active");
				$.post("'.$this->createUrl('getPreview').'",
				    {
				        id: $(this).attr("data-id")
				    },
				    function(result){
						var obj = JSON.parse(result);
						$(".event-preview").html(obj);
					}
				);
			});

            $(".scrollable").scroll(function(){
                if ($(this).scrollTop() >= 124) {
                    $("#event-preview").css("width", $("#event-preview").width());
                    $("#event-preview").addClass("stick");
                } else {
                    $("#event-preview").removeClass("stick");
                }
            });

			$("#Event_city_id, #Event_id, #Event_status").on("change", function(){
			    filter();
			});

			function filter() {
			    $(".event-preview").html("");
				var data = $("#filter-form").serialize();
				$.fn.yiiListView.update("eventList", {
					data: data
				});

			}

		', CClientScript::POS_READY);
        Yii::app()->clientScript->registerScript('eventSort', '
            $(".filterButton").click();
		', CClientScript::POS_LOAD);
        $event = Yii::app()->request->getParam('Event');
        $model = new Event('search');
        $model->unsetAttributes();

        $cities = City::getCityList();
        $cities = array("Всі міста") + $cities;

        $events = Event::getListEvents();

        if ($event)
            $model->attributes = $event;
        $this->render('index', array(
            'model' => $model,
            'cities' => $cities,
            'events' => $events,
        ));
    }

    public function actionCreate()
    {
        $this->formJs();
        $event = Yii::app()->request->getParam('Event');
        $custom = Yii::app()->request->getParam('custom');
        $timing = Yii::app()->request->getParam('Timing');
        $relation_name = Yii::app()->request->getParam('relation_name');
        $modelName = Yii::app()->request->getParam('model_name');
        $relation_id = Yii::app()->request->getParam('relation_id');
        $template_id = Yii::app()->request->getParam('template_id');

        $model = new Event();
        $model->unsetAttributes();
        $modelTiming = new Timing();
        $this->performAjaxValidation(array($model, $modelTiming));
        if ($event) {
            $model->attributes = $event;
            $model->timings = $timing;
            $model->custom_params = $custom;
            if ($model->save()) {
                Tag::createTag($model->id,$relation_name, $modelName, $relation_id, $template_id);
                $this->redirect(array('edit',"event_id"=>$model->id));
            }
        }
        Yii::app()->clientScript->registerScript("evCreate", "
            $('#country_id').attr('is-old', 0);
            getCoords();
        ", CClientScript::POS_LOAD);

        $timing = new Timing();
        $timing->stop_sale = $timing->entrance = null;
        $model->timings = array($timing);
        $model->getTicket();
        $countries = Country::getListExistLocations();

        $this->render('form', array(
            'model' => $model,
            'countries' => $countries
        ));
    }

    private function formJs()
    {
        $query = "SELECT * FROM tbl_event WHERE position= (SELECT MAX(position) FROM tbl_event)";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        $maxPosition = $result['position']+1;

        $cs = Yii::app()->clientScript;
        $cs->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-responsive.css");
        $cs->registerCssFile(Yii::app()->baseUrl . "/theme/css/bootstrap.css");
        $cs->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-spinedit.css");
        $cs->registerCssFile(Yii::app()->baseUrl . "/css/bootstrap-datetimepicker.min.css");
        $cs->registerCssFile(Yii::app()->baseUrl . "/css/font-awesome/css/font-awesome.css");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/moment.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/bootstrap-datetimepicker.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/bootstrap-spinedit.js");

        $cs->registerScript('createEvent',
            '
                $(".dateTimePicker").each(function(){
					var _this = $(this),
						name = _this.attr("name"),
						number = parseInt(name.replace(/\D+/g,"")),
						useCurrent = _this.hasClass("start_sale") || _this.hasClass("start_event") ? true:false,
						minDate = _this.hasClass("start_sale") || _this.hasClass("start_event") ? new Date("' . date("F d, Y") . '") : 0,
						maxDate = null;
					if (_this.hasClass("end_event")) {
						minDate = $("#Timing_"+number+"_start_sale").data("DateTimePicker").getDate();
					}
					if (_this.hasClass("end_sale")){
					    var lastTiming = $(".end_event:last");
					    if(lastTiming.val() != "") {
					        maxDate = lastTiming.data("DateTimePicker").getDate();
					        if($(".start_sale").val() != "")
					            minDate = $(".start_sale").data("DateTimePicker").getDate();
                        }
					}
					if (_this.hasClass("start_sale")) {
					    var firstTiming = $(".start_event:first");
					    if(firstTiming.val() != "")
					        maxDate = firstTiming.data("DateTimePicker").getDate();
                        minDate = null;
                    }
					_this.datetimepicker({
						"locale":"uk",
						"minDate" : minDate,
						"maxDate" : maxDate,
						"useCurrent" : useCurrent,
					});
				});

				$(document).on("focusout", "#Event_url", function(e){
				    var _this = $(this);
				    $.post("'.$this->createUrl("generateAliasUrl").'", {
                        name : _this.val()
                        }, function (result) {
                            $("#Event_url").val(result);
                            $("#Event_url").change();
                        });
				});

				$(document).on("change", ".dateTimePicker", function(e){
				    var _this = $(this),
				        name = _this.attr("name"),
						number = parseInt(name.replace(/\D+/g,""));
				    if (_this.hasClass("start_event")) {
							var newDate = _this.data("DateTimePicker").getDate();
							$(".start_sale").data("DateTimePicker").setMaxDate(newDate);
                    } else if (_this.hasClass("start_sale")) {
                        var newDate = _this.data("DateTimePicker").getDate(),
                            oldEndDate = $("#Event_end_sale").data("DateTimePicker").getDate();
                        $("#Event_end_sale").data("DateTimePicker").setMinDate(newDate);
                    } else if (_this.hasClass("end_event")){
                        var maxDate = $(".end_event:last").data("DateTimePicker").getDate();
                        $(".end_sale").data("DateTimePicker").setMaxDate(maxDate);
                    }
				});

                $(document).on("change", ".MultiFile-applied", function(e){
                    $(this).hide();
                });

				$(document).on("click", ".deleteFile", function(e){
				   var fileName = $(this).attr("value"),
				       _this = $(this);
                   if(confirm("Ви впевнені що хочете видалити файл \""+fileName+"\" ?"))
                       $.post("'.$this->createUrl("deleteFile").'", {
                       file : fileName
                       }, function(result) {
                            if (result == true) {
                                _this.parent().parent().fadeOut(400, function(){
                                    $(this).remove();
                                });
                            }
                       });
				});
                var blockType = null,
                    imageId = null;
				$(document).on("click", ".changeFile", function(e){
                   var fileName = $(this).attr("value"),
                       _this = $(this),
                       selectInput = $("#changeMultimedia"),
                       value = $(this).attr("value");
                   blockType = $(this).attr("blockType");
                   if(blockType != null){
                       $("#prevMultimediaName").attr("value",value);
                       var format = "."+value.split(".").pop();
                       selectInput.removeAttr("accept");
                       selectInput.attr("accept", format);
                   }
                   selectInput.click();
                   imageId = $(this).attr("value");
				});

                $(document).on("change", "#changeMultimedia", function(e){
                    if($(this).val() != "") {
                       var form_data = new FormData($("#changeMultimediaFile")[0]);
                        $.ajax({
                            url: "'.$this->createUrl('changeFile').'",
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: "post",
                            success: function(result){
                                if(blockType == "image"){
                                  var oldImage = $(".images[alt=\""+imageId+"\"]");
                                  d = new Date();
                                  oldImage.attr("src", result+"?"+d.getTime());
                                } else {
                                  alert("Файл замінено");
                                }
                            }
                        });
                    }
				});


				$(document).on("change", ".start_event", function(e){
				    var _this = $(this),
						name = _this.attr("name"),
						number = parseInt(name.replace(/\D+/g,"")),
						useCurrent = _this.hasClass("start_sale") || _this.hasClass("start_event") ? true:false,
						minDate = _this.hasClass("start_sale") || _this.hasClass("start_event") ? new Date("' . date("F d, Y") . '") : 0;
						var newDate = _this.data("DateTimePicker").getDate();
						if(newDate != null) {
						$("#Timing_"+number+"_entrance").data("DateTimePicker").setMaxDate(_this.data("DateTimePicker").getDate());
                        $("#Timing_"+number+"_entrance").data("DateTimePicker").setDate(newDate);
						$("#Timing_"+number+"_stop_sale").data("DateTimePicker").setMinDate(_this.data("DateTimePicker").getDate());
                        $("#Timing_"+number+"_stop_sale").data("DateTimePicker").setDate(newDate.add(2, "hours"));
                        }
				});

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
						element .datetimepicker({"language":"uk"});

                    if (element.parent().prev("div").length != 0 && element.attr("data-class") != "custom")
                        element.parent().prev("div").attr("id", element.parent().prev("div").attr("id").replace(number, newNumber));
                    else if (element.attr("data-class") != "custom")
                        element.parent().parent().prev("div").attr("id", element.parent().parent().prev("div").attr("id").replace(number, newNumber));

                    return newNumber;
				}
				$(document).on("click", ".addCustomParam", function(e){
					e.preventDefault();
					var _this = $(this),
						cloned = $(this).parent().parent();
					cloned.clone().insertAfter(cloned);
					cloned.next("div").find("input").each(function(){
						updateNumber($(this));
						$(this).val("");
					});
					$("<a href=\"#\" class=\"glyphicon glyphicon-minus removeCustomParam\" style=\"top:33px !important\"></a>").appendTo(_this.parent());
					_this.remove();
				});

				$(".eventCurrent").on("click", function(e){
				    var dateTimePicker = $(this).parent().find(".dateTimePicker");
				    $.post("'.Yii::app()->createUrl('/event/event/getServerTime').'",{}, function(result){
                        var serverTime = moment.unix(result);
                        dateTimePicker.data("DateTimePicker").setDate(serverTime);
				    });
				});

				$(document).on("click", ".newTiming", function(e){
					e.preventDefault()
					var _this = $(this),
						cloned = _this.parent().parent().parent().parent().parent().parent(),
						form_init = [],
						curr_Val = "",
						clones = cloned.clone();

                    clones.insertAfter(cloned);
                    var settings = $("#new-event-form").data("settings");
					cloned.next("div").find("input").each(function(){
						var number = updateNumber($(this));
						var id = $(this).attr("id");
						if (number < 10) {
						    var parsedId = id.substring(9, id.length);
						} else {
						    var parsedId = id.substring(10, id.length);
						}
						settings.attributes.push({
						    "id":"Timing_"+number+"_"+parsedId,
						    "inputID":"Timing_"+number+"_"+parsedId,
						    "errorID":"Timing_"+number+"_"+parsedId+"_em_",
						    "model":"Timing","name":"["+number+"]"+parsedId,
						    "enableAjaxValidation":true,
						    "status":1
						});

					});


                    $("#new-event-form").yiiactiveform(settings);
                    $("<a class=\"glyphicon glyphicon-minus removeTiming\"></a>").appendTo(_this.parent());
					_this.remove();
                    cloned.next("div").find("input").each(function(){
                        var element = $(this);
						   cloned.find("input").each(function(){
						        var _th = $(this),
						            mDate = _th.data("DateTimePicker").getDate();
						        if($(this).attr("data-attr") == element.attr("data-attr"))
                                element.data("DateTimePicker").setDate(mDate.add(24, "hours"));
						   });
					});
                });

				$(document).on("click", ".removeTiming", function(e){
					e.preventDefault();
					$(this).parent().parent().parent().parent().parent().parent().remove();
				});

				$(document).on("click", ".removeCustomParam", function(e){
					e.preventDefault();
					var _this = $(this),
						removed = _this.parent().parent();
					removed.remove();
				});

				$(document).on("click", "#generateUrl", function(e){
				    var eventName = $("#Event_name").val(),
				        city = $("#Event_city_id").val();
                    if(city != "" && eventName != ""){
                        $.post("'.$this->createUrl("generateAliasUrl").'", {
                        name : eventName,
                        cityId : city
                        }, function (result) {
                            $("#Event_url").val(result);
                            $("#Event_url").change();
                        });
                    }
                    else
                       alert("Для генерації url необхідно заповнити Назву і вибрати Місто проведення");
				});

				$(".previewTicket").on("click", function(e){
				    e.preventDefault();
				    var _this = $(this),
				        dataType = _this.attr("data-type");
				    $.post(
				        "'.$this->createUrl('previewTicket').'",
				        {
				            ticket: JSON.stringify($("#Event_"+dataType+"_ticket").val()),
				            style: JSON.stringify($("#Event_"+dataType+"_style").val()),
				            event_id: $("#event_id").val()
				        }, function(result) {
				            var result = JSON.parse(result);
				            switch(dataType){
				                case "e_ticket":
				                    $(".blank_preview").html("");
				                    break;
                                case "blank":
                                    $(".e_ticket_preview").html("");
                                    break;
                                default:
                                    break;
				            }
				            $("."+dataType+"_preview").html(result);
				        }
				    );
				});

				$(".reset").on("click", function(e){
				    e.preventDefault();
				    var _this = $(this),
				        dataType = _this.attr("data-type"),
				        dataContainer = _this.attr("data-container");
				    $.post(
				        "'.$this->createUrl('resetTicket').'",
				        {
				            type: dataType,
				            container: dataContainer
				        }, function(result) {
				            var result = JSON.parse(result);
				            _this.parent().find("textarea").val(result);
				        }
				    );
				});

				$(".aSpinEdit").spinedit({
                    minimum: 1,
                    maximum: '.$maxPosition.',
                    step: 1,
                });

                $("#Event_isOnMain").on("change", function(e){
                    var sDiv = $(".positionSpinner"),
                        spin = sDiv.find("input");
                    if($(this).is(":checked")){
                        sDiv.show();
                        spin.removeAttr("disabled");
                    } else {
                        sDiv.hide();
                        spin.attr("disabled", "true");
                    }
				});

				if($(".positionSpinner").find("input").attr("positionVal") != null)
				    $("#Event_isOnMain").click();

			', CClientScript::POS_READY
        );
    }

    protected function performAjaxValidation($models, $id=null)
    {

        if (isset($_POST['ajax'])) {
            $result = array();
            if (!is_array($models))
                $models = array($models);

            foreach ($models as $model) {
                $modelName = CHtml::modelName($model);
                if ($modelName == "Timing") {
                    $model = new Timing('ajaxValidate');
                    $checkedTimings = array();
                    if($id) {
                        $existedTimings = Timing::model()->findAllByAttributes(array('event_id' => $id));
                        $existedArray = array();
                        foreach ($existedTimings as $exTime) {
                            array_push($existedArray, array('start_sale' => $exTime->start_sale, 'stop_sale' => $exTime->stop_sale, 'entrance' => $exTime->entrance));
                        }
                    }
                    foreach ($_POST[$modelName] as $k => $time) {
                        if(in_array($time,$checkedTimings)) {
                            foreach($time as $attr=>$value)
                                $result[CHtml::activeId($model, $k."_".$attr)] = ["Не може бути дубльованих таймінгів"];
                            continue;
                        }
                        if($id) {
                            if (in_array($time, $existedArray)) {
                                continue;
                            }
                        }
                        $model->setTimingAttributes($time);
                        $model->validate();
                        array_push($checkedTimings,$time);
                        foreach ($model->getErrors() as $attribute => $errors)
                            $result[CHtml::activeId($model, "[".$k."]".$attribute)] = $errors;
                    }
                } else {
                    if (isset($_POST[$modelName]))
                        $model->attributes = $_POST[$modelName];
                    if ($modelName == "Event"){
                        $timing = end($_POST["Timing"]);
                        if($timing["stop_sale"] != '' && $model->end_sale != '') {
                            $timingEnd = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $timing["stop_sale"]);
                            $eventStopSale = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $model->end_sale);
                            if ($eventStopSale > $timingEnd)
                                $result[CHtml::activeId($model, "end_sale")] = ["Закінчення продажів не може бути пізніше закінчення таймінгу події"];
                        }
                    }
                    $model->validate();
                    foreach ($model->getErrors() as $attribute => $errors)
                        $result[CHtml::activeId($model, $attribute)] = $errors;
                }
            }

            echo json_encode($result);
            Yii::app()->end();
        }
    }

    public function actionPreviewTicket()
    {
        $ticket = Yii::app()->request->getParam('ticket');
        $style = Yii::app()->request->getParam('style');
        $event_id = Yii::app()->request->getParam('event_id');
        if ($ticket && $style) {
            $ticket = json_decode($ticket);
            if ($event_id) {
                $event = Event::model()->with(array("scheme", "scheme.location", "scheme.location.city"))->findByPk($event_id);
                if ($event)
                    $ticket = EventTicket::replace($event, $ticket);
            }

            for($i=1; $i<=3; $i++) {
                $this->widget("ext.barcode.Barcode", array('elementId' => "code_".$event_id."_".$i, 'value' => 123456789121, 'type' => "code128", 'settings'=>array(
                    'output'=>'bmp')));
            }

            Yii::app()->clientScript->registerScript('loadBarcodes', '
               $(".barcodeView").each(function(){
                var currContainer = $(this);
                   if(currContainer.is(":empty"))
                        currContainer.html("").show().barcode(value, type, settings);
               });
		    ', CClientScript::POS_READY);

            echo json_encode($this->renderPartial('ticket_preview', array('ticket'=>$ticket, 'style'=>$style), true, true));
            Yii::app()->end();
        }
        echo json_encode("bad request");
    }

    public function actionEdit($event_id)
    {
        $this->formJs();
        $event = Yii::app()->request->getParam('Event');
        $custom = Yii::app()->request->getParam('custom');
        $timing = Yii::app()->request->getParam('Timing');
        $relation_name = Yii::app()->request->getParam('relation_name');
        $modelName = Yii::app()->request->getParam('model_name');
        $relation_id = Yii::app()->request->getParam('relation_id');
        $template_id = Yii::app()->request->getParam('template_id');
        $preview = Yii::app()->request->getParam('images');
        $imageArray = array();
        $docArray = array();

        $model = Event::model()->with(array('scheme.location.city.country', 'timings', 'multimedias', 'tickets'))->findByPk($event_id);
        $modelTiming = new Timing();
        $this->performAjaxValidation(array($model, $modelTiming),$model->id);

        if ($event) {
            $model->attributes = $event;
            $model->timings = $timing;
            $model->custom_params = $custom;
            if ($preview) {
                Multimedia::changePreview($preview,$model->id);
                $model->poster_id = $preview;
                }

            if ($model->save()) {
                $query = "DELETE FROM " . Tag::model()->tableName() . " WHERE model_id=" . $model->id;
                Yii::app()->db->createCommand($query)->execute();
                Tag::createTag($model->id,$relation_name, $modelName, $relation_id, $template_id);
                $this->redirect(array('edit',"event_id"=>$model->id));
            }

        }

        if (!empty($model->multimedias)){
            $dir = Yii::getPathOfAlias("webroot.uploads").DIRECTORY_SEPARATOR.$model->id.DIRECTORY_SEPARATOR;
            $path = Yii::app()->baseUrl.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$model->id.DIRECTORY_SEPARATOR;
            foreach ($model->multimedias as $multimedia) {
                if(!file_exists($dir.$multimedia->file)) {
                    $multimedia->delete();
                    continue;
                }
                clearstatcache();
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $type_name = finfo_file($finfo, $dir.$multimedia->file);
                $type = substr($type_name, 0, strrpos($type_name, '/'));
                if ($type == "image") {
                    $image = Yii::app()->image->load($dir.$multimedia->file);
                    $imageProperties = $image->width . "x" . $image->height;
                    $parsedFormat = strstr($multimedia->file, '.');
                    $parsedName = str_replace($parsedFormat, '', $multimedia->file);
                    $fullPath = $path . $parsedName . '_s' . $parsedFormat;
                    $dirPath = $dir . $parsedName . $parsedFormat;
                    $imageSizeBytes = filesize($dirPath);
                    $imageSizeKB = $imageSizeBytes/1024;
                    $imageSizeKB = round($imageSizeKB, 2);
                    if($imageSizeKB >= 1024) {
                        $imageSizeMB = $imageSizeKB/1024;
                        $imageSizeMB = round($imageSizeMB, 2);
                        $imageSize = $imageSizeMB . " MB";
                    } else
                        $imageSize = $imageSizeKB . " KB";
                    $fullPath = str_replace("\\","/",$fullPath);
                    $path = str_replace("\\","/",$path);
                    array_push($imageArray,array('id'=>$multimedia->id, 'imageProp'=>$imageProperties,'imageSize'=>$imageSize,'demoPath'=>$path,
                        'path'=>$fullPath, 'parsedName'=>$parsedName, 'parsedFormat'=>$parsedFormat, 'file'=>$multimedia->file)) ;
                } else {
                    $iconPath = Yii::app()->baseUrl."/img/icon_".$type.".png";
                    $dirPath = $dir . $multimedia->file;
                    $fileSizeBytes = filesize($dirPath);
                    $fileSizeKB = $fileSizeBytes/1024;
                    $fileSizeKB = round($fileSizeKB, 2);
                    if($fileSizeKB >= 1024) {
                        $fileSizeMB = $fileSizeKB/1024;
                        $fileSizeMB = round($fileSizeMB, 2);
                        $fileSize = $fileSizeMB . " MB";
                    } else
                        $fileSize = $fileSizeKB . " KB";
                    $path = str_replace("\\","/",$path);
                    array_push($docArray,array('fileSize'=>$fileSize, 'demoPath'=>$path, 'path'=>$iconPath, 'name'=>$multimedia->file, 'type'=>$type));
                }
            }
        }


        if (empty($model->timings)) {
            $timing = new Timing();
            $timing->stop_sale = $timing->entrance = null;
            $model->timings = array($timing);
        }
        $optionMessage = ' ';
        if(strtotime($model->end_sale) > time())
            $optionMessage = 'Подія перебуває в продажу!';


        $city = City::model()->findByPk($model->scheme->location->city_id);
        $isOld = $model->isNewRecord ? 0 : 1;

        Yii::app()->clientScript->registerScript("evEdit", "
            var country = $('#country_id');
            if(country.length > 0) {
                $('#country_id').select2('val', " . $city->country_id . ");
                $('#country_id').attr('is-old', ".$isOld.");
                $('#country_id').change();
            } else {
                $('#location_id').val(". $model->scheme->location->id .");
                $('#Event_city_id').val(". $model->scheme->location->id .");
            }

            $(document).on('click', '#eventDeleteButton', function(e){
				    var optionMessage = '".$optionMessage."';
					if(confirm('Ви впевнені що хочете видалити подію? '+optionMessage)) {
                        $.post('".$this->createUrl('delete')."', {
                            id : $(this).attr('data-id')
					    }, function (result) {
                            window.location.replace(result);
                        });
					}
            });
            getCoords();
        ", CClientScript::POS_LOAD);

        $countries = Country::getListExistLocations();

        $resultPreview = array();
        foreach ($imageArray as $image)
            $resultPreview[$image['id']] = $this->renderPartial("_imageBlock", array("image"=>$image), true);

        $preview = Multimedia::model()->findByAttributes(array('event_id'=>$model->id, 'status'=> 0));
        if ($preview)
            $preview = $preview->id;

        $this->render('form', array(
            'model' => $model,
            'preview' => $preview,
            'countries' => $countries,
            'images' => $resultPreview,
            'files' => $docArray,
        ));
    }

    public function actionGetPreview()
    {
        $id = Yii::app()->request->getParam('id');
        if ($id) {
            $model = Event::model()->findByPk($id);
            echo json_encode($this->renderPartial("_preview", array("model" => $model), true));
            Yii::app()->end();
        }
    }

    public function actionGetLocation()
    {
        $city_id = Yii::app()->request->getParam('city_id');
        if ($city_id) {
            $data = Location::model()->findAllByAttributes(array("city_id" => (int)$city_id));
            foreach ($data as $location) {
                echo CHtml::tag('option',
                    array('value' => $location->id), CHtml::encode($location->name), true);
            }
        }
    }

    public function actionGetScheme()
    {
        $location_id = Yii::app()->request->getParam('location_id');
        if ($location_id) {
            $data = Scheme::model()->findAllByAttributes(array("location_id" => (int)$location_id));
            foreach ($data as $scheme) {
                echo CHtml::tag('option',
                    array('value' => $scheme->id), CHtml::encode($scheme->name), true);
            }
        }
    }

    public function actionGetCoords()
    {
        $location_id = Yii::app()->request->getParam('location_id');
        if ($location_id) {
            $location = Location::model()->findByPk($location_id);
            $result['lng'] = $location->lng;
            $result['lat'] = $location->lat;
            echo json_encode($result);
            Yii::app()->end();
        }
    }

    public function actionDelete()
    {
        $id = Yii::app()->request->getParam('id');
        $model = Event::model()->findByPk($id);
        if ($model->delete())
            echo $this->createUrl('index');
        Yii::app()->end();
    }

    /**
     * @param $model
     * @throws CException
     */

	public function actionUpdate($model)
	{
		Yii::import('ext.bootstrap.components.TbEditableSaver');
		$es = new TbEditableSaver($model);
		$es->update();
	}

    public function actionDeleteFile()
    {
        $fileName = Yii::app()->request->getParam('file');
        if(isset($fileName)) {
            $file = Multimedia::model()->findByAttributes(array('file'=>$fileName));
            if (isset($file)){
                if($file->status == Multimedia::STATUS_POSTER) {
                    echo $file->deletePoster();
                } else
                    echo $file->delete();
            }
        } else {
            echo false;
        }
        Yii::app()->end();
    }

    public function actionChangeFile()
    {
        $multimedia = $_FILES;
        $name = Yii::app()->request->getParam('prevMultimediaName');
        $id = Yii::app()->request->getParam('id_event');
        if (is_array($multimedia)) {
            $dir = Yii::getPathOfAlias('webroot.uploads').DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
            $filename = $name;
            move_uploaded_file( $_FILES['changeMultimedia']['tmp_name'], $dir.$filename);
            $_multimedia = new Multimedia();
            $_multimedia->file = $filename;
            $_multimedia->event_id = $id;
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type_name = finfo_file($finfo, $dir.$_multimedia->file);
            $type = substr($type_name, 0, strrpos($type_name, '/'));
            if($type == 'image'){
                if ($_multimedia->saveFile()){
                    //update image
                    $path = Yii::app()->baseUrl.DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR;
                    $parsedFormat = strstr($_multimedia->file, '.');
                    $parsedName = str_replace($parsedFormat, '', $_multimedia->file);
                    $fullPath = $path . $parsedName . '_s' . $parsedFormat;
                    echo $fullPath;
                }
            } else {
                //update file
            }
        }

        Yii::app()->end();
    }

    public function actionStopSale()
    {
        $id = Yii::app()->request->getParam('id');
//        $time = Yii::app()->request->getParam('time');
        $time = time();
        $query = "UPDATE tbl_event SET end_sale='".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss",$time)."' WHERE id=".$id;
        if(Yii::app()->db->createCommand($query)->execute())
            echo 1;
        else
            echo 0;
        Yii::app()->end();
    }

    public function actionStartSale()
    {
        $id = Yii::app()->request->getParam('id');
//        $time = Yii::app()->request->getParam('time');
        $time = time();
        $query = "UPDATE tbl_event SET end_sale='".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss",null)."', start_sale='".Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss",$time)."' WHERE id=".$id;
        if(Yii::app()->db->createCommand($query)->execute())
            echo 1;
        else
            echo 0;
        Yii::app()->end();
    }

    public function actionGenerateAliasUrl()
    {
        $name = Yii::app()->request->getParam("name");
        $cityId = Yii::app()->request->getParam("cityId");
        if($name && $cityId) {
            $city = City::model()->findByPk($cityId);
            echo UrlTranslit::translit($name." ".$city->name);
        } elseif($name){
            echo UrlTranslit::translit($name);
        } else
            echo false;
        Yii::app()->end();
    }

    public function actionResetTicket()
    {
        $type = Yii::app()->request->getParam('type');
        $container = Yii::app()->request->getParam('container');
        $data = file_get_contents(Yii::getPathOfAlias("webroot.theme.".$type."")."/".$container."");

        echo json_encode($data);
        Yii::app()->end();
    }

    public function actionPreview($id)
    {
        $this->redirect("http://kasa.in.ua/event/hiddenEvent?id=".$id."&k=".base64_encode(time()));
    }

    public function actionGetServerTime()
    {
        echo time();
        Yii::app()->end();
    }

}