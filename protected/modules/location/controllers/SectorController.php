<?php

class SectorController extends Controller
{

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
		$this->render('index');
	}

	public function actionStructure($scheme_id)
	{
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mCustomScrollbar.concat.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/net.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_scheme.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/tools.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/common.js");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/scroll/jquery.mCustomScrollbar.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/net.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/style.css");
		$cs->registerScript("select2SaveButton", "

				$.fn.select2.defaults = $.extend($.fn.select2.defaults, {
					formatNoMatches: function (term) {
						return \"<input type='hidden' class='form-control' id='newTerm' value='\"+term+\"'><a href='#' id='addNew' onclick='add()' class='btn btn-default'>Зберегти</a>\";
					}
				});

				function add() {
				var newTerm = $('#newTerm').val();
				var sel = $(document).find('.select2-container-active').next();
				var url = sel.attr('data-url');

				var type = sel.attr('data-type') + '[name]';
				var str = {};
				str[type] = newTerm;

				$.post(url, str, function(data){
					data = JSON.parse(data);
					if(isNaN(data)) {
						showAlert('danger', 'Помилка!');
					} else {
						sel.append('<option value=\"' + data + '\">' + newTerm + '</option>');
						sel.select2('val',data);
						sel.select2('close');
						showAlert('success', 'Запис успішно додано!');
					}
				});
			}

			$(document).on('click', '#deleteSchemeButton', function() {
			    if(confirm('Ви впевнені що хочете видалити схему?')) {
			     $.post('".$this->createUrl('/location/scheme/delete')."', {
                     id: $(this).attr('data-id')
                     }, function(result){
                     if(result.includes('Схема'))
						showAlert('danger', result, 10000);
					 else
						window.location.replace(result);
                 });
                 }
			});


			$(document).on('click', '#Sector_type_1', function() {
			    var parent = $(this).parent().parent().parent().parent().parent().parent();
			    parent.find('.forSitSector').hide();
			    parent.find('.forFunZone').show();
			});

			$(document).on('click', '#Sector_type_0', function() {
			    var parent = $(this).parent().parent().parent().parent().parent().parent();
			    parent.find('#Sector_amount').val(null);
			    parent.find('.forFunZone').hide();
			    parent.find('.forSitSector').show();
			});

			var savedNames = [];
			function refreshCopiesForm(eraseAll)
			{
			    var message = $('#copiesFormErrorName'),
			    	eraseAll = typeof eraseAll !== 'undefined' ? eraseAll : false;
				savedNames = [];
			    message.insertAfter('#copiesMinForm');
			    if(eraseAll)
			    	$('#copiesMinForm').html(null);
				else {
					var names = $('.copiesNames');
					if(names.length > 0) {
						names.each(function(){
							if($(this).val() != ''){
								savedNames.push($(this).val());
							}
							$(this).remove();
						});
					}
				}
                $('.forFunZone').hide();
                $('.forSitSector').hide();
                $('#copiesPrefix').hide();
                $('#copiesFormErrorMessage').hide();
                message.hide();
			}

			$(document).on('change', '#copiesSector_id', function() {
                refreshCopiesForm(true);
			});

			$(document).on('click', '#okCopyButton', function() {
			    refreshCopiesForm();
                var id = $('#copiesSector_id').val(),
                    countValue = $('#count').val(),
                    errorMessage = $('#copiesFormErrorMessage'),
                    count = $('#count');
                    countValue = parseInt(countValue) || 0;

                if (countValue != '' && id != '' && countValue != 0) {
                    errorMessage.hide();
                    if(countValue > 20)
                        countValue = 20;
                    count.val(countValue);
                    $('#copiesPrefix').show();
                    for (i = 1; i <= countValue; i++) {
                    	if(savedNames[i-1])
							var input = $('<input class=\"form-control copiesNames\" placeholder=\"Назва '+ i +' копії\" style=\"margin-bottom:10px;\" type=\"text\" value=\"'+savedNames[i-1]+'\" name=\"Sector[name]['+i+']\" id=\"Sector_name['+i+']\">');
                        else
							var input = $('<input class=\"form-control copiesNames\" placeholder=\"Назва '+ i +' копії\" style=\"margin-bottom:10px;\" type=\"text\" value=\"\" name=\"Sector[name]['+i+']\" id=\"Sector_name['+i+']\">');
                        input.appendTo($('#copiesMinForm'));
                    }
                    var formBlock2 = $('#newCopiesSector');
                        $.post(
                            'getSectorForm',
                            {
                                sector_id : id
                            }, function(result) {
                                var obj = JSON.parse(result);
                                formBlock2.find('#Sector_type_sector_id').val(obj.type_sector_id);
                                formBlock2.find('#Sector_type_row_id').val(obj.type_row_id);
                                formBlock2.find('#Sector_type_place_id').val(obj.type_place_id);
                                formBlock2.find('#Sector_places').val(obj.places);
                                formBlock2.find('#type').val(obj.type);
                                if (obj.type == 1) {
                                    $('.forSitSector').show();
                                    $('.forFunZone').hide();
                                } else {
                                    $('.forFunZone').show();
                                    $('.forSitSector').hide();
                                    var places = JSON.parse(obj.places);
                                    formBlock2.find('#Sector_amount').val(places.fun_zone.amount);
                                }
                            }
                        );

                } else {
                    errorMessage.hide();
                    if (id == '') {
                        errorMessage.text('Виберіть сектор');
                        errorMessage.show();
                    } else if (countValue == '') {
                        errorMessage.text('Введіть кількість копій (до 10)');
                        errorMessage.show();
                    }
                }
			});

            $(document).on('click', '.manyCopiesSubmit', function() {
                var form = $('#newCopiesSector'),
                    data = form.serialize(),
                    names = form.find('.copiesNames'),
                    message = form.find('#copiesFormErrorName'),
                    i = 0,
                    namesArr = new Array();
                names.each(function() {
                    var value = $(this).val();
                    if (namesArr.indexOf(value) > -1 && value != '') {
                        message.text('Вже існує копія з такою назвою');
                        message.insertAfter($(this));
                        message.show();
                        return false;
                    } else {
                        namesArr.push(value);
                    }
                    if (value == '') {
                        message.text($(this).attr('placeholder')+' не може бути порожньою');
                        message.insertAfter($(this));
                        message.show();
                    } else {
                        i++;
                    }
                });
                if (i == names.length && names.length != 0) {
                   loadingAnimate($('.glyphicon-th-large'));
                    form.submit();
                }
			});

            function loadingAnimate(element)
            {
                element.show();
                var degree = 0, timer;
                rotate();
                function rotate() {
                    element.css({ WebkitTransform: 'rotate(' + degree + 'deg)'});
                    element.css({ '-moz-transform': 'rotate(' + degree + 'deg)'});
                    timer = setTimeout(function() {
                        ++degree; rotate();
                    },5);
                }

                $('input').toggle(function() {
                    clearTimeout(timer);
                }, function() {
                    rotate();
                });
            }

		", CClientScript::POS_BEGIN);

		$data = Yii::app()->request->getParam("data");
		if (!$data)
			$data = Yii::app()->request->getDelete("data");
		$scheme = Scheme::model()->with('location')->findByPk($scheme_id);
		if ($data) {
			$data = json_decode($data);
			if (isset($data->info->sector_id))
				$sector = Sector::model()->findByPk($data->info->sector_id);
		}
		if ($data && $sector)
			switch(Yii::app()->request->requestType) {
				case "POST":
					if ($sector->setSector($data, true)) {
						$sector->places = json_decode($sector->places);
						$result = array(
							"info"=>$this->renderPartial('_settings', array(
								'sector'=>$sector,
								'scheme'=>$scheme
							), true)
						);
						echo json_encode($result);
						Yii::app()->end();
					} else
						echo json_encode(false);
						Yii::app()->end();
					break;
				case "GET":
					$result = $sector->getSector($data);

					$result['info'] = $this->renderPartial('_settings', array(
						'sector'=>$sector,
						'scheme'=>$scheme
					), true);
					echo json_encode($result);
					Yii::app()->end();
					break;
				case "DELETE":
					$sector->delete();
                    Yii::app()->end();
					break;
				default:
					break;
			}


		if (!isset($sector)) {
			$sector = new Sector();
			$sector->scheme_id = $scheme_id;
			if (isset($data)) {
				$result['info'] = $this->renderPartial('_settings', array(
					'sector'=>$sector,
					'scheme'=>$scheme
				), true);
				echo json_encode($result);
				Yii::app()->end();
			}
		}
        $this->layout='//layouts/column1';
		$this->render('structure', array(
			'scheme'=>$scheme,
			'sector'=>$sector
		));
	}


	public function actionVisualScheme()
	{
		$scheme_id = Yii::app()->request->getParam('scheme_id');
		$sector_id = Yii::app()->request->getParam('sector_id');
		$data = Yii::app()->request->getParam('data');
        $status = Yii::app()->request->getParam('status');
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mousewheel.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery-ui.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.draggable.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.parser.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.import.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.export.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.pan-zoom.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl."/js/common.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");

		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/reset.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/editor.css");
		$cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/style.css");

		$cs->registerScript("visibility", '
			$(document).on("change", ".changeVisibility", function(e){
			    e.preventDefault();
			    var _this = $(this);
			    $.post("'.$this->createUrl("changeVisibility").'",
			        {
			            sector_id: _this.parent().parent().attr("id"),
			            val: _this.is(":checked")?1:0,
			            type: _this.attr("name")

			        }, function(result){
			            console.log(result);
			        }
                )

			});
		', CClientScript::POS_READY);
		if ($sector_id||$data) {
            ini_set("memory_limit", "512M");
			$sector = Sector::model()->findByPk($sector_id);
			$data = json_decode($data);
			if ($scheme_id) {
				$scheme = Scheme::model()->findByPk($scheme_id);
				if (isset($data->imported)) {
					$scheme->import = $data->imported;
					if (isset($data->box))
						$scheme->box = $data->box;
					$scheme->saveImported();
                    $params = $scheme->saveParams($data);
					$scheme->saveAttributes(array("import"=>json_encode($data->imported), "params"=>json_encode($params)));
				}
                if (isset($data->getImported)) {
                    $params = $scheme->getParams();
                    echo json_encode(array("imported"=>$scheme->import)+$params);
                    Yii::app()->end();
                }
			}
			if (isset($data->sectors)&&!$sector) {
				foreach ($data->sectors as $sector_data)
					$sector = Sector::model()->findByPk($sector_data->sector_id)->setVisual($sector_data);
				echo json_encode(true);
				Yii::app()->end();
			}

			if ($sector)
				switch(Yii::app()->request->requestType) {
					case "POST" :
                        if ($status==0)
                            $sector->saveAttributes(array("status"=>$status));
						else
							echo json_encode($sector->setVisual($data));
						Yii::app()->end();
						break;
					case "GET" :
                        if ($status)
                            $sector->saveAttributes(array("status"=>$status));
						$result['id'] = $sector_id;
						$visual = $sector->getVisual();
						if($sector->type == Sector::TYPE_SEAT)
                            $result['scheme'] = $visual;


						else {

							$result['id'] = "row0col0sector".$result['id'];
							$result['fun_zone'] = true;
							$result['sector_id'] = $sector->id;
							$result['visual'] = $visual;
							$result['selectable'] = true;
							if ($visual!="") {
								$result['class'] = "rect native";
								$result['fill'] = "#cc0000";
							}
						}
                        $params = $sector->getSectorParams();
						echo json_encode($result+$params);
						Yii::app()->end();
						break;
					case "DELETE" :

						Yii::app()->end();
						break;
					default:
						Yii::app()->end();
						break;
				}
		}

		if (!$scheme_id)
			throw new CHttpException(404, 'Дану схему не знайдено');
		$scheme = Scheme::model()->with('sectors')->findByPk($scheme_id);
        $sectorsActive = array();
        $sectors = array();
        foreach ($scheme->sectors as $sector) {
            if ($sector->status == Sector::STATUS_ACTIVE)
                $sectorsActive[] = $sector;
            else
                $sectors[] = $sector;
        }



        $sectors = new CArrayDataProvider($sectors, array(
			"pagination"=>false
		));
        $sectorsActive =  new CArrayDataProvider($sectorsActive, array(
			"pagination"=>false
		));
        $this->layout='//layouts/column-one-full-width';
		$this->render('visualEditor', array(
			'scheme'=>$scheme,
			'sectors'=>$sectors,
			'sectorsActive'=>$sectorsActive
		));
	}

	public function actionSaveSector()
	{

        $count = Yii::app()->request->getParam('count');
		if (!$count)
			$count = 1;
		if (Yii::app()->request->isAjaxRequest) {
			$model = new Sector();
			$this->performAjaxValidation($model);
		}
		$sector = Yii::app()->request->getParam('Sector');
		$copy = Yii::app()->request->getParam('copySector_id');
		if (!$copy)
			$copy = Yii::app()->request->getParam('copiesSector_id');
		$type = Yii::app()->request->getParam('type');
		$saved = false;
		if ($sector) {

			for ($i=1; $i<=$count; $i++) {
				$model = new Sector();
				$this->performAjaxValidation($model);
				$model->type = isset($sector['type']) ? $sector['type'] : $type;
				$model->scheme_id = $sector['scheme_id'];
				$model->status = Sector::STATUS_NOACTIVE;

				$data = (object)array(
					"info" => (object)array(
						"name" => is_array($sector['name']) ? $sector['name'][$i] : $sector['name'],
						"prefix" => isset($sector['type_sector_id']) ? $sector['type_sector_id'] : "",
						"row_name" => isset($sector['type_row_id']) ? $sector['type_row_id'] : "",
						"col_name" => isset($sector['type_place_id']) ? $sector['type_place_id'] : "",
						"amount" => $sector['amount']
					)
				);

				if ($model->type == Sector::TYPE_SEAT)
					$data->scheme = isset($sector['places']) ? $sector['places'] : json_encode((object)array(
						"cell" => (object)array(
							"simple_cell" => new stdClass()),
						"netDimension" => (object) array(
							"rows" => 30,
							"cols" => 40
						),
						"netMode" => false
					));
				elseif ($model->type == Sector::TYPE_FUN) {
					$data->fun_zone = true;
				}

				if ($copy)
					$data->copy = $copy;

				if (!$model->setSector($data, true)) {
					$saved = false;
					break;
				}
				$saved = true;
			}
			if ($saved)
				$this->redirect(array('structure', "scheme_id"=>$model->scheme_id));
			else
				CVarDumper::dump($model->getErrors(),10,1);
		}
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionSaveBitMap()
	{
		$scheme_id = Yii::app()->request->getParam("scheme_id");
		$svg = Yii::app()->request->getParam("svg");
		if ($svg&&$scheme_id){
			$dir = Yii::getPathOfAlias("webroot.scheme").DIRECTORY_SEPARATOR.$scheme_id;

			file_put_contents($dir.DIRECTORY_SEPARATOR."svg.svg", '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$svg);
			$svg = file_get_contents($dir.DIRECTORY_SEPARATOR."svg.svg");

			$image = new Imagick();
			$image->clear();

			$image->readImageBlob($svg);

			$image->setImageFormat("png24");

			$image->writeImage($dir.'/bitMap.png');
			$image->clear();

            echo json_encode("quest is complete! Sasha ty molodec, djakuem;)");

		}
	}

	public function actionGetSectorForm()
	{
		$sector_id = Yii::app()->request->getParam('sector_id');
		if ($sector_id) {
			$model = Sector::model()->findByPk($sector_id);
			$model->places = json_encode($model->places);
			echo json_encode($model->attributes);
			Yii::app()->end();
		}
	}

    public function actionValidateSectorExists()
    {
        $sectorId = Yii::app()->request->getParam('id');
        $sectorName = Yii::app()->request->getParam('name');
        $sectorType = Yii::app()->request->getParam('type');
        $sector = Sector::model()->findByAttributes(array('name' => $sectorName, 'type_sector_id' => $sectorType));
        if ($sector) {
            if ($sector->id == $sectorId) {
                echo json_encode(true);
            } else echo json_encode(false);
        } else echo json_encode(true);
    }

    public function actionChangeVisibility()
    {
        $type = Yii::app()->request->getParam("type");
        $val = Yii::app()->request->getParam("val");
        $sector_id = Yii::app()->request->getParam("sector_id");
        $result = 0;
        if ($type&&$sector_id) {
            if ($type=="vis_front")
                $name = "frontend";
            elseif ($type=="vis_back")
                $name = "backend";
            if (isset($name))
                $result = Yii::app()->db->createCommand()
                    ->update(Sector::model()->tableName(), array($name=>(int)$val), "id=:id", array(
                        ":id"=>$sector_id
                    ));
        }
        echo json_encode($result);
        Yii::app()->end();
    }

    public function actionCopySectors()
    {

    }

	public function actionCheckHideAccess()
	{
		$id = Yii::app()->request->getParam('id');
		echo json_encode(!Place::model()->exists("sector_id=:sector_id AND status=:status", array(
			":sector_id"=>$id,
			":status"=>Place::STATUS_SOLD
			))
		);
	}

}