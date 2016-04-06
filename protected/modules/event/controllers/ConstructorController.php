<?php

class ConstructorController extends Controller
{

//    public $layout = "//layouts/column2";

    const SET_PRICE = 1;

	public static function accessFilters()
	{
        return array(
            "index"=>array(
                "name"=>"Конструктор цін",
                "params"=>array(
                    "access"=>array(
                        "name"=>"Встановлення цін",
                        "params"=>array(
                            self::SET_PRICE=>"Встановлення цін"
                        ),
                        "type"=>Access::TYPE_CHECKBOX,
                        "withEvent"=>true,
						"allow_actions"=>array(
							"/event/constructor/setPrice",
							"/event/constructor/setFunCount",
							"/event/constructor/deletePrice",
							"/event/constructor/updatePriceBlock",
							"/event/constructor/getInvoice",
							"/event/constructor/savePayTypes",
						)
                    )
                ),
                "type"=>"tabs"
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

	public function actionIndex($event_id)
	{
		$model = Event::model()->with('scheme')->findByPk($event_id);
		if (Yii::app()->request->isAjaxRequest)
			Scheme::getVisualInfo($model);


        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mousewheel.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/common.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.import.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl ."/js/moment.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.draggable.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/price_constructor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.parser.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.export.min.js");

        $cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/reset.css");
        $cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/editor.css");

		$cs->registerCoreScript("jquery.ui");
		$cs->registerScript("constructor", "
			$('#price').autocomplete({
				minLength: 0,
				source : ".json_encode(Place::getPrices($event_id))."
			}).on('focus', function(){
                $(this).autocomplete('search');
            });

            $(document).on('click','#btnStartSale', function(){
				var _this = $(this);
            	if(_this.attr('data-open') == 1){
				    var time = moment().format('YYYY-MM-DD HH:mm:ss');
				    $.post('".$this->createUrl('event/stopSale')."',{id : $(this).attr('data-id'), time : time },function(result){
				        if(result) {
                            showAlert('success', 'Подію закрито з продажу');
                            _this.html('<span class=\'glyphicon glyphicon-ok\'></span> Відкрити подію в продаж');
                            _this.attr('data-open','0');
                            _this.toggleClass('btn-danger').toggleClass('btn-success');
                        }
					});
            	} else {
            		var time = moment().format('YYYY-MM-DD HH:mm:ss');
				    $.post('".$this->createUrl('event/startSale')."',{id : $(this).attr('data-id'), time : time },function(result){
				        if(result) {
                            showAlert('success', 'Подію відкрито в продаж');
                            _this.html('<span class=\'glyphicon glyphicon-remove\'></span> Закрити подію з продажу');
                            _this.attr('data-open','1');
                            _this.toggleClass('btn-success').toggleClass('btn-danger');
                        }
					});
            	}
            });

            $('#generateInvoice').on('click', function(e){
            	var selectedType = $('#invoiceType input:checked').val(),
            	    action = '".CController::createAbsoluteUrl('/event/constructor/getInvoice')."';
            	window.open(action+'?type='+selectedType+'&event_id=".$model->id."','_blank');
            });

            $('#submitTypes').on('click', function(e){
            	var data = $('#event_pay_types').serialize();
            	 $.post('".$this->createUrl('savePayTypes')."',data,function(result){
				        if(result) {
							$('#payTypes').modal('hide');
							showAlert('success','Типи оплати успішно збережені');
                        } else {
							showAlert('danger','Виникла помилка, збережено не було');
                        }
				});
            });

		", CClientScript::POS_READY);
		ini_set("memory_limit", "256M");
		$criteria = new CDbCriteria();
		$criteria->with = array('scheme', 'typeSector');
		$criteria->compare("scheme_id", $model->scheme_id);
		$criteria->compare("t.status", Sector::STATUS_ACTIVE);
		$criteria->order = "t.type ASC, t.name ASC";

		$sectors = new CActiveDataProvider('Sector', array(
			"criteria"=>$criteria,
			"pagination"=>false
		));

		$payModel = new EventPayType();
		$payModel->findAndSetByEvent($model->id);

        $this->layout='//layouts/column1';
		$this->render('index', array(
            "model"=>$model,
			"sectors"=>$sectors,
			"payModel"=>$payModel,
        ));
	}

	public function actionSetPrice()
	{
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$data = json_decode($data);
			$success = true;
			$prices = array();
			$price_block = new stdClass();
			$model = Event::model()->findByPk($data->event_id);
			if ($data->price<=0)
				$success = false;
			else {
				$prices = Place::setPrice($data,$model);
				if (!$prices)
					$success = false;
				else
					$price_block = $prices ? $this->priceBlock($model) : $price_block;
			}


			echo json_encode(array(
				"prices"=>$prices,
				"totalInfo"=>$this->getTotalInfo($model),
				"price_block"=>$price_block,
				"success"=>$success,
				"msg"=>$success ? "" : "Неправильна ціна"
			));
			Yii::app()->end();
		}
	}

	private function priceBlock($model)
	{

		return $this->renderPartial("_prices", array("model"=>$model), true);
	}

	private function getTotalInfo($event)
	{
		$funCount = Place::getCountFunWithPrice($event->id);
		$countAll = $event->scheme->getCountPlaces()+$funCount;
		$sumAll = $event->sumPrice;
		$countWithPrice = $event->countPlacePrice;
		return array(
			"countAll"=>$countAll,
			"countWithPrice"=>$countWithPrice,
			"sumAll"=>$sumAll
		);
	}

	public function actionSetFunCount()
	{
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$data = json_decode($data);
			$message = "";
			$success = true;
			$places = array();
			$sector_block = new stdClass();
			$price_block = new stdClass();
			$model = Event::model()->findByPk($data->event_id);
			if ($data->count<=0) {
				$message = "Неправильна кількість";
				$success=false;
			} else {
				$places = Place::setFunCount($data,$model);
				if (isset($places['msg'])) {
					$success = false;
					$message = $places['msg'];
				}
				$price_block = $places ? $this->priceBlock($model) : $price_block;
				$sector_block= $places ? $this->sectorBlock($model) : $sector_block;
			}
			echo json_encode(array(
				"places"=>$places,
				"price_block"=>$price_block,
				"sector_block"=>$sector_block,
				"success"=>$success,
				"msg"=>$message
			));
			Yii::app()->end();
		}
	}

	private function sectorBlock($model)
	{

		$criteria = new CDbCriteria();
		$criteria->with = array('scheme');
		$criteria->compare("scheme_id", $model->scheme_id);
		$criteria->compare("t.status", Sector::STATUS_ACTIVE);
		$criteria->order = "t.type ASC, t.name ASC";

		$sectors = new CActiveDataProvider('Sector', array(
			"criteria"=>$criteria,
			"pagination"=>false
		));

		return $this->renderPartial("sectors", array(
			"model"=>$model,
			"sectors"=>$sectors
		), true);
	}

	public function actionUpdatePriceBlock()
	{
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$model = Event::model()->findByPk($data->event_id);
			echo json_encode($this->renderPartial("_prices", array("model"=>$model), true));
		}
	}

	public function actionDeletePrice()
	{
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$data = json_decode($data);
			$ids = Place::deletePrice($data);
			$model = Event::model()->findByPk($data->event_id);
			echo json_encode(array(
				"ids"=>$ids,
				"totalInfo"=>$this->getTotalInfo($model),
				"price_block"=>$this->priceBlock($model),
				"success"=>true,
			));

		}
	}

	public function actionGetInvoice($type,$event_id)
	{
		$event = Event::model()->findByPk($event_id);
		$currentDateTime =  Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
		$fileName = $currentDateTime.$event->name.$event->scheme->location->city->name;

		if ($type == ExelGenerator::INVOICE_TYPE_SECTORS_PRICES){
			$generator = new ExelGenerator($event,$fileName);
			$generator->generatePlaceSectorPriceInvoice();
		}
		elseif ($type == ExelGenerator::INVOICE_TYPE_PRICES){
			$generator = new ExelGenerator($event,$fileName);
			$generator->generatePlacePriceInvoice();
		}
		elseif ($type == ExelGenerator::INVOICE_TYPE_SECTORS){
			$generator = new ExelGenerator($event,$fileName);
			$generator->getPlaceSectorInvoice();
		}
		elseif ($type == ExelGenerator::INVOICE_TYPE_DETAIL){
			$generator = new ExelGenerator($event,$fileName);
			$generator->getPlacesDetailInvoice();
		}
		else
			throw new CHttpException("Error! Wrong invoice type");
	}

	public function actionSavePayTypes()
	{
		$data = Yii::app()->request->getParam("EventPayType");
		if($data) {
			$pay_types = new EventPayType();
			$pay_types->event_id = $data["event_id"];
			$pay_types->types = $data["types"];
			echo $pay_types->savePayTypes();
		}
	}

}