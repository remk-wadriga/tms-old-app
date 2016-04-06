<?php

class QuoteController extends Controller
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

	public function accessList()
	{
		return array(
			'create',
			'placeToCart',
			'placeFanToCart',
			'deleteToCart',
			'closeQuote',
			'placeToContractor',
			'view',
			'update',
			'addSold',
			'returnInSale',
			'returnSold',
			'changePrice',
			'startSale',
			'allQuotes',
			'compare',
			'filterTable',
			'getContractorsList',
			'passPlaces',
			'checkOrder',
			'ajaxCloseQuote',


		);
	}

	public function actionIndex()
	{
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/common.js");
		$data = Yii::app()->request->getParam('Quote');
		$criteria = new CDbCriteria();
		$criteria->with = array("roleTo");
		if ($data) {
			$criteria->join =  "JOIN {{order}} o ON `o`.`id`=`t`.`order_id` ";
			if ($data['status']==Order::STATUS_ACTIVE)
			$criteria->compare("o.status", $data["status"]);
			$criteria->compare("event_id", $data['event_id']);
			$criteria->compare('role_to_id', $data['contractor_id']);
			$criteria->order = "o.date_add DESC";
			if (isset($data['sorting'])) {
				$sorting = false;
				switch($data['sorting']) {
					case Quote::ORDER_BY_DATE :
						$sorting = "o.date_add DESC";
						break;
					case Quote::ORDER_BY_UPDATE :
						$sorting = "o.date_update DESC";
						break;
					case Quote::ORDER_BY_COUNT :
						$criteria->select .= ", count(ticket.id) as ticketCount";
						$criteria->join .= "JOIN {{ticket}} ticket ON o.id=ticket.order_id";
						$criteria->group = "o.id";
						$criteria->order = "ticketCount DESC";
						break;
					default:
						break;
				}
				if ($sorting)
					$criteria->order = $sorting;
			}
		}

		$dataProvider = new CActiveDataProvider("Quote", array(
			"criteria" => $criteria
		));
		$model = new Quote;

		$model->sorting = Quote::ORDER_BY_DATE;

		$this->render('index', array(
			"dataProvider" => $dataProvider,
			"model" => $model
		));
	}

	public function actionCreate($event_id)
	{
		$quote = Yii::app()->request->getParam("Quote");
		$event = Event::model()->with("scheme")->findByPk($event_id);
		$model = new Quote();
		if (Yii::app()->request->isAjaxRequest)
			if (!$quote)
				Scheme::getVisualInfo($event);
		if ($quote) {
//            $this->performAjaxValidation($model, $k);
            $model->setQuoteAttributes($quote);
            if ($model->type != '0') $model->status = 1;
            $model->event_id = $event_id;
            if ($model->save()) {
                if (Yii::app()->request->isAjaxRequest)
                    Yii::app()->end();
                Yii::app()->user->setFlash('alert_success','<strong>Успішно!</strong> Квота #'.$model->id.' успішно створена.');
                $this->redirect("create?event_id=".$event_id);
            }
		}

		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery.mousewheel.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/common.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/config.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.draggable.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/webservice_editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/script.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.parser.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.export.min.js");

		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/reset.css");
		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/editor.css");

		$cs->registerCoreScript("jquery.ui");

        $cs->registerScript('quote_create_script', '
            $("#quote-save").on("hidden.bs.modal",function(){
                document.getElementById("quote-save-form").reset();
            })
            $("#Quote_type_payment").change(function(){
                quote_payment_calculate();
            });
            $("#Quote_percent").on("keyup change",function(){
                quote_payment_calculate();
            });
            function quote_payment_calculate(){
                if ($("#Quote_type_payment").val()==1){
                    if (parseInt($("#Quote_percent").val())>0 && $.isNumeric($("#Quote_percent").val())) {
                        $("#Quote_payment_value").html(parseInt(parseInt($("#quote_cart_sum").val())*parseInt($("#Quote_percent").val())/100));
                    } else {
                        $("#Quote_payment_value").html(0);
                    }
                }
                if ($("#Quote_type_payment").val()==2){ $("#Quote_payment_value").html($("#Quote_percent").val()) }
            }
        ');
        $providers = (Yii::app()->user->currentRoleId==1)?Role::getRoleList(true):Role::getAllContractor(Yii::app()->user->id,Yii::app()->user->currentRoleId);
        $contractors = Role::getRoleList(true);

		$this->render("create", array(
            "providers" => $providers,
            "contractors" => $contractors,
			"event" => $event,
			"model" => $model,
		));
	}

	public function actionPlaceToCart()
	{
		$places = json_decode(Yii::app()->request->getParam('places'));
		$event_id = Yii::app()->request->getParam('event_id');
		if ($event_id) {
			Yii::app()->user->setState("currentCartId", "cartId_" . $event_id);
            Yii::app()->shoppingCart->clear();
            foreach ($places as $place) {
                if ($place->type == Sector::TYPE_FUN) {
                    $model = Place::model()->findByAttributes(array(
                        "sector_id"=>$place->sector_id,
                        "event_id"=>$event_id,
                        "status"=>Place::STATUS_SALE
                    ));
                    if ($model) {
                        Yii::app()->shoppingCart->put($model, 1, "cartId_" . $event_id);
                        if (isset($place->count))
                            Yii::app()->shoppingCart->update($model, $place->count, "cartId_" . $event_id);
                    }

                }else{
                    $model = Place::model()->findByPk($place->server_id);
                    if ($model)
                        Yii::app()->shoppingCart->put($model, 1, "cartId_" . $event_id);

                }
            }
            echo Quote::renderCart($event_id);
		}
		Yii::app()->end();
	}

    public function actionPlaceFanToCart()
    {
        $event_id = Yii::app()->request->getParam('event_id');
        $sector_id = Yii::app()->request->getParam('sector_id');
        $count = Yii::app()->request->getParam('count');
        if ($event_id && $sector_id && $count) {
            Yii::app()->user->setState("currentCartId", "cartId_" . $event_id);
            $model = Place::model()->findByAttributes(array(
                "sector_id"=>$sector_id,
                "event_id"=>$event_id,
                "status"=>Place::STATUS_SALE
            ));
            Yii::app()->shoppingCart->update($model, $count, "cartId_" . $event_id);
            echo Quote::renderCart($event_id);
        }
        Yii::app()->end();
    }

    public function actionDeleteToCart()
    {
        $event_id = Yii::app()->request->getParam('event_id');
        $sector_id = Yii::app()->request->getParam('sector_id');
        $row_id = Yii::app()->request->getParam('row_id');
        $price = Yii::app()->request->getParam('price');
        if(isset($event_id) && isset($sector_id) && isset($row_id) && isset($price)) {
            Yii::app()->user->setState("currentCartId", "cartId_" . $event_id);
            $positions = Yii::app()->shoppingCart->getPositions();
            foreach ($positions as $position) {
                if ($position->event_id==$event_id && $position->sector_id==$sector_id && $position->row==$row_id && $position->price==$price){
                    if (Yii::app()->shoppingCart->contains($position->getId())) {
                        Yii::app()->shoppingCart->remove($position->getId());
                    }
                }
            }
            echo Quote::renderCart($event_id);
        }
        Yii::app()->end();
    }

	public function actionCloseQuote($id)
	{
		if($id){
			$model = Quote::model()->findByPk($id);
			$model->closeQuote();
			$this->redirect('index');
		}
	}

	public function actionPlaceToContractor()
	{

	}

	public function actionGetQuoteBlock()
	{
		$id = Yii::app()->request->getParam("quote_id");
		$model = Quote::model()->with(array("order", "roleTo", "roleFrom"))->findByPk($id);
		if($model){
			$dataGenerator = new ExelGenerator(false,false,false,true);
			$data = $dataGenerator->getDataFromTickets($model->order_id,$model->event_id,true,[Ticket::STATUS_QUOTE_RETURN,Ticket::STATUS_CANCEL]);
//			CVarDumper::dump($data);
//			die;
		} else
			throw new CHttpException(404,"quote not found");
		echo json_encode($this->renderPartial("_quoteInfo", array("data"=>$data, "model"=>$model), true));
		Yii::app()->end();
	}

	public function actionView($quote_id)
	{
		$model = Quote::model()->with(array("order", "roleTo", "roleFrom"))->findByPk($quote_id);
		if (!$model)
			throw new CHttpException(404,"Квоту не знайдено");

		Yii::app()->clientScript->registerScript("quoteView", "
			 $(document).on('change', '.quote-select', function(){
                var _this = $(this),
                    _block = _this.parent().find('div.quote-one');
				console.log(_block);
				$.post('".$this->createUrl('getQuoteBlock')."',
					{
						quote_id: _this.val()
					}, function (result){
						$('.'+_this.attr('block-select')).html(JSON.parse(result));
					}
				)
             });

			  $(document).on('change', '.checkbox-filter', function(){
			  	var type = $(this).attr('type-data'),
				    checkValues = $('input[type-data=\"'+type+'\"]:checked').map(function()
									{
									return $(this).val();
									}).get(),
					allSum = 0,
					allCount = 0;
				$.post('".$this->createUrl('refreshQuoteTableView')."',
					{
						quote_id: type,
						checkedV: checkValues
					}, function (result){
						$('.data-rows-'+type).html(JSON.parse(result));
						$('.row-sum-'+type).each(function(){
							allSum += parseInt($(this).text());
						});
						$('.row-count-'+type).each(function(){
							allCount += parseInt($(this).text());
						});
						$('.all-sum-'+type).text(allSum);
						$('.all-count-'+type).text(allCount);
					}
				)
			 });
		", CClientScript::POS_BEGIN);

		$dataGenerator = new ExelGenerator(false,false,false,true);
		$data = $dataGenerator->getDataFromTickets($model->order_id,$model->event_id,true,[Ticket::STATUS_QUOTE_RETURN,Ticket::STATUS_CANCEL]);

		$this->render("_view", array(
			"model"=>$model,
			"data"=>$data
		));

	}

	public function actionRefreshQuoteTableView()
	{
		$id = Yii::app()->request->getParam("quote_id");
		$checkedStatuses = Yii::app()->request->getParam("checkedV");
		$sold = 1;
		$notSold = 0;
		$return = 2;
		$model = Quote::model()->findByPk($id);
		$defaultExeptStatuses = [Ticket::STATUS_QUOTE_RETURN,Ticket::STATUS_QUOTE_ON_SALE,
								Ticket::STATUS_QUOTE_SOLD, Ticket::STATUS_SEND_TO_EMAIL, Ticket::STATUS_CANCEL];
		$defaultExeptStatuses = array_combine($defaultExeptStatuses, $defaultExeptStatuses);
		if($checkedStatuses) {
			if(in_array($sold,$checkedStatuses)){
				unset($defaultExeptStatuses[Ticket::STATUS_SEND_TO_EMAIL]);
				unset($defaultExeptStatuses[Ticket::STATUS_QUOTE_SOLD]);
			}
			if(in_array($notSold,$checkedStatuses)){
				unset($defaultExeptStatuses[Ticket::STATUS_QUOTE_ON_SALE]);
			}
			if(in_array($return,$checkedStatuses)){
				unset($defaultExeptStatuses[Ticket::STATUS_CANCEL]);
				unset($defaultExeptStatuses[Ticket::STATUS_QUOTE_RETURN]);
			}
		}

		$dataGenerator = new ExelGenerator(false,false,false,true);
		$data = $dataGenerator->getDataFromTickets($model->order_id,$model->event_id,true,$defaultExeptStatuses);
		echo json_encode($this->renderPartial("_dataRows", array("data"=>$data, "model"=>$model), true));
		Yii::app()->end();
	}

	public function actionUpdate($quote_id)
	{
		$model = Quote::model()->with(array("order", "order.tickets", "roleTo", "roleFrom"))->findByPk($quote_id);
		$quote = Yii::app()->request->getParam('Quote');
		if ($quote) {
			$this->standardAjaxValidation($model);
			$order = Order::model()->findByPk($model->order_id);
			$order->save();
			$model->attributes = $quote;
			if ($model->saveAttributes($quote)) {
				Yii::app()->user->setFlash("success", "Успішно збережено");
				$this->redirect(array("update", "quote_id"=>$model->id));
			}
		}
		if (!$model)
			throw new CHttpException(404,"Квоту не знайдено");
		if (Yii::app()->request->isAjaxRequest) {

            Scheme::getVisualInfo($model->event, $model->event_id, $quote_id);
        }


        // TODO add edit class
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery.mousewheel.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/common.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/config.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.draggable.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/edit_quote_constructor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/webservice_editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/script.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.parser.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.export.min.js");
		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/reset.css");
		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/editor.css");

		Yii::app()->clientScript->registerScript('index', '

        ', CClientScript::POS_BEGIN);


		$quotes_list = array();
		$quotes = Quote::model()->with(array("roleTo"))->findAllByAttributes(array("event_id"=>$model->event_id));
		foreach ($quotes as $quote) {
			$countSum = $quote->getAllCountSum();
			$quotes_list[$quote->id] = "[".$quote->id."] ".$quote->roleTo->name." | ".$quote->typeQuote." | ".$countSum['count']." шт";
		}


		$this->render("update", array(
			"model"=>$model,
            "event"=>$model->event,
			"quotes_list" => $quotes_list,
		));
	}

	protected function standardAjaxValidation($model)
	{
		if(isset($_POST['ajax']))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionAddSold()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$data = Yii::app()->request->getParam('places');
		if ($quote_id&&$data) {
			$places = json_decode($data);
			$quote = Quote::model()->findByPk($quote_id);
			$ids = array_map(function($place) {
				if (isset($place->sector_id))
					return array(
						"id"=>$place->sector_id,
						"count"=>$place->count
					);
				return $place->server_id;
			}, $places);
			$ids = $quote->getFunPlaceIds($ids, Ticket::STATUS_QUOTE_ON_SALE);

			$result = Place::setPlaceStatus($ids, Ticket::STATUS_QUOTE_SOLD, Ticket::STATUS_QUOTE_ON_SALE);

			$result = $result ? $quote->getPlacesInit($ids) : "error";
			$quote->order->updateSum();
			echo json_encode($result);
			Yii::app()->end();
		}

	}

	public function actionReturnInSale()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$data = Yii::app()->request->getParam('places');
		if ($quote_id&&$data) {
			$places = json_decode($data);

			$quote = Quote::model()->findByPk($quote_id);
			$ids = array_map(function($place) {
				if (isset($place->sector_id))
					return array(
						"id"=>$place->sector_id,
						"count"=>$place->count
					);
				return $place->server_id;
			}, $places);
			$ids = $quote->getFunPlaceIds($ids, Ticket::STATUS_QUOTE_SOLD);
			$result = Place::setPlaceStatus($ids, Ticket::STATUS_QUOTE_ON_SALE, Ticket::STATUS_QUOTE_SOLD);
			$result = $result ? $quote->getPlacesInit($ids) : "error";
			$quote->order->updateSum();
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	public function actionReturnSold()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$data = Yii::app()->request->getParam('places');
		$typeReturn = Yii::app()->request->getParam('typeReturn');
		if ($quote_id&&$data) {
			$places = json_decode($data);
			$quote = Quote::model()->findByPk($quote_id);
			$ids = array_map(function($place) {
				if (isset($place->sector_id))
					return array(
						"id"=>$place->sector_id,
						"count"=>$place->count
					);
				return $place->server_id;
			}, $places);
			$ids = $quote->getFunPlaceIds($ids, Ticket::STATUS_QUOTE_ON_SALE);

			$place_status = $typeReturn == Quote::RETURN_AND_OPEN ? Place::STATUS_SALE : Place::STATUS_CLOSE;

			$result = Place::setPlaceStatus($ids, Ticket::STATUS_QUOTE_RETURN, Ticket::STATUS_QUOTE_ON_SALE, $place_status);
			$quote->order->updateSum();
			if ($place_status == Place::STATUS_SALE)
				Place::refreshCode($ids);
			$result = $result ? $quote->getPlacesInit($ids) : "error";
			$quote->order->updateSum();
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	public function actionChangePrice()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$data = Yii::app()->request->getParam('places');
		$onScheme = Yii::app()->request->getParam('onScheme');
		$price = Yii::app()->request->getParam('price');


		if ($quote_id&&$data&&$price) {
			$places = json_decode($data);
			$quote = Quote::model()->findByPk($quote_id);
			$ids = array_map(function($place) {
				if (isset($place->sector_id))
					return array(
						"id"=>$place->sector_id,
						"count"=>$place->count
					);
				return $place->server_id;
			}, $places);
			$ids = $quote->getFunPlaceIds($ids, Ticket::STATUS_QUOTE_ON_SALE);


			$result = $quote->setNewPrice($ids, $price, filter_var($onScheme, FILTER_VALIDATE_BOOLEAN));
			$result = $result ? $quote->getPlacesInit($ids) : "error";
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	public function actionAllQuotes($event_id)
	{
		$event = Event::model()->with("quotes")->findByPk($event_id);

		if (Yii::app()->request->isAjaxRequest) {
			$quote_ids = false;
			foreach ($event->quotes as $quote) {
				$quote_ids[] = $quote->id;
			}
            Scheme::getVisualInfo($event, $event_id, $quote_ids);
		}

		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/jquery.mousewheel.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/common.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/config.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.draggable.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/view_all_quotes.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/webservice_editor.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/script.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.parser.min.js");
		$cs->registerScriptFile(Yii::app()->baseUrl . "/js/svg.export.min.js");

		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/reset.css");
		$cs->registerCssFile(Yii::app()->baseUrl . "/css/redactor/editor.css");

        $cs->registerScript("collapse", "
            $(document).on('click', 'div.panel-heading', function(){
                var _this = $(this),
                    _block = _this.next('.panel-collapse').find('.block');
                if (_block.html().trim().length==0)
                    $.post('".$this->createUrl('getSectorsBlock')."',
                        {
                            quote_id:$(this).attr('data-quote')
                        }, function (result){
                            _block.html(JSON.parse(result));
                        }
                    )
            });
        ", CClientScript::POS_READY);

		$sectors = array();
		/*foreach ($event->quotes as $quote)
			if ($quote->order->status == Order::STATUS_ACTIVE)
				foreach ($quote->order->tickets as $ticket)
					if (in_array($ticket->status, array(Ticket::STATUS_QUOTE_ON_SALE, Ticket::STATUS_QUOTE_SOLD)))
						$sectors[$quote->id][$ticket->place->sector_id][$ticket->place->row][] = array(
							"place"=>$ticket->place->place,
							"price"=>$ticket->place->price
						);*/

		$this->render("viewAll", array(
			"model"=>$event,
			"sectors"=>$sectors
		));
	}

	public function actionGetSectorsBlock()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
        $sectors = array();
		if ($quote_id) {
			$quote = Quote::model()->findByPk($quote_id);

            if ($quote->order->status == Order::STATUS_ACTIVE)
                foreach ($quote->order->tickets as $ticket)
                    if (in_array($ticket->status, array(Ticket::STATUS_QUOTE_ON_SALE, Ticket::STATUS_QUOTE_SOLD)))
                        $sectors[$ticket->place->sector_id][$ticket->place->row][] = array(
                            "place"=>$ticket->place->editedPlace,
                            "price"=>$ticket->place->price
                        );

        }
        echo json_encode($this->renderPartial("_collapse_block", array("sectors"=>$sectors), true));
        Yii::app()->end();
	}

	public function actionCompare()
	{
		$event_id = Yii::app()->request->getParam('event_id');
		if ($event_id) {

			$role_ids = Yii::app()->db->createCommand()
				->select("role_to_id")
				->from(Quote::model()->tableName())
				->where("event_id=:event_id", array(":event_id"=>$event_id))
				->queryColumn();

			$result = CHtml::listData(Role::model()->findAllByAttributes(array("id"=>$role_ids)), "id", "name");

			$result = array("Виберіть контрагента")+$result;
			foreach($result as $value=>$name)
			{
				echo CHtml::tag('option',
					array('value'=>$value),CHtml::encode($name),true);
			}
			Yii::app()->end();
		}

		Yii::app()->clientScript->registerScript("filterTable", '
			function filterTable(side) {
				$.post(
					$(".filterTables").data("url"),
					{
						event_id : $("#event_id").val(),
						contractor_id : $("#"+side+"_side").val(),
						type : $("#"+side+"_filter input[checked]").val()
					}, function(result) {
						console.log(result);
					}
				);
			}
		', CClientScript::POS_BEGIN);
		$events = CHtml::listData(Event::model()->findAll(), "id", "name");
		$this->render("compare", array("events"=>$events));
	}

	public function actionFilterTable()
	{
		$event_id = Yii::app()->request->getParam("event_id");


	}

	public function actionGetContractorsList()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$type = Yii::app()->request->getParam('typePass');
		if ($quote_id&&$type!==null) {
			$quote = Quote::model()->findByPk($quote_id);
			$result = $quote->getContractorsList($type);
			foreach($result as $k=>$contractor)
			{
				echo CHtml::tag('option',
					array('value'=>$k),CHtml::encode($contractor),true);
			}
			Yii::app()->end();
		}
	}

	public function actionPassPlaces()
	{
		$quote_id = Yii::app()->request->getParam('quote_id');
		$places = Yii::app()->request->getParam('places');
		$type = Yii::app()->request->getParam('typePass');
		$contractor = Yii::app()->request->getParam('passPlaceContractors');

		$result = false;
		if ($quote_id&&$places&&$type!==null&&$contractor) {
			$quote = Quote::model()->findByPk($quote_id);
			$places = json_decode($places);
			$ids = array_map(function($place) {
				if (isset($place->sector_id)&&!isset($place->server_id))
					return array(
						"id"=>$place->sector_id,
						"count"=>$place->count
					);
				return $place->server_id;
			}, $places);

			$ids = $quote->getFunPlaceIds($ids, Ticket::STATUS_QUOTE_ON_SALE);

			if ($type == Quote::PASS_TYPE_OLD) {
				$newQuote = Quote::model()->findByPk($contractor);
				if ($newQuote) {
					$criteria = new CDbCriteria();
					$criteria->compare("order_id", $quote->order_id);
					$criteria->addInCondition("place_id", $ids);
					$builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
					$command = $builder->createUpdateCommand(Ticket::model()->tableName(), array("order_id"=>$newQuote->order_id), $criteria);
					$result = $command->execute();
				}
			} elseif ($type == Quote::PASS_TYPE_NEW) {

				$contractor = Role::model()->findByPk($contractor);

				Yii::app()->user->setState("currentCartId", "cartId_" . $contractor->id . "_" . $quote->event_id);

				$result = Quote::placeToCart($places, $contractor, $quote->event_id);

				$positions = Yii::app()->shoppingCart->getPositions();
				$sectors = array();
				foreach ($positions as $position) {
					$sectors[$position->sector_id][$position->row][] = array(
						"place"=>$position->place,
						"price"=>$position->price
					);
				}

				Quote::setContractorsBlock($this->getContractorBlock($contractor,$quote->event_id, $sectors), $contractor, $quote->event_id);

				echo json_encode(array(
					"msg"=>"redirect_url",
					"url"=>$this->createUrl("create", array("event_id"=>$quote->event_id))
				));
				Yii::app()->end();
			}
			$result = $result ? $quote->getPlacesInit($ids) : "error";
			$quote->order->updateSum();
			echo json_encode($result);
			Yii::app()->end();
		}
		echo json_encode("error");
	}

	public function getContractorBlock($contractor, $event_id, $sectors=array())
	{
		Yii::app()->user->setState("currentCartId", "cartId_".$contractor->id."_".$event_id);
		return $this->renderPartial("_contractor_block", array(
			"contractor" => $contractor,
			"num" => $contractor->id,
			"event_id" => $event_id,
			"sectors" => $sectors
		), true);
	}

	public function actionCheckOrder()
	{
		$order_id = Yii::app()->request->getParam('order_id');
		$event_id = Yii::app()->request->getParam('event_id');
		if ($order_id&&$event_id) {
			$ignoreSold = Yii::app()->request->getParam('ignoreSold');
			$model = Order::model()->with("tickets")->findByPk($order_id);
			if (($model&&$model->type==Order::TYPE_QUOTE&&$model->quote->event_id!=$event_id)||!$model) {
				echo json_encode(array("alert"=>"danger", "msg"=>"Замовлення неможливо додати, або його не існує", "places"=>array()));
				Yii::app()->end();
			}

			$hasSold = false;
			$places = array();

			foreach ($model->tickets as $ticket) {
				if ($ticket->status == Ticket::STATUS_QUOTE_SOLD)
					$hasSold = true;
				else
					$places[] = $ticket->place_id;
			}
			if ($hasSold && !$ignoreSold) {
				echo json_encode(array("alert"=>false, "msg"=>"У квоті є продані квитки, продовжити без них?", "places"=>array()));
				Yii::app()->end();
			}

			$places = Yii::app()->db->createCommand()
				->select("row, place, sector_id")
				->from(Place::model()->tableName())
				->where(array("in", "id", $places))
				->queryAll();

			$places = array_map(function($place){
				return Sector::encodePlace($place);
			}, $places);
			echo json_encode(array("alert"=>false, "msg"=>"", "places"=>$places));
		}
	}

	public function actionAjaxCloseQuote()
	{
		$id = Yii::app()->request->getParam("id");

		if ($id) {
			$quote = Quote::model()->findByPk($id);
			$undefined = Ticket::model()->findAllByAttributes(array("status" => Ticket::STATUS_QUOTE_ON_SALE,"order_id"=>$quote->order->id));
			if (empty($undefined)) {
				if($quote->closeQuote(true))
					echo $this->createUrl('index');
			} else
				echo "Додайте продані місця та зробіть повернення непроданих місць, є місця з невідомою долею";
		}
	}

	protected function performAjaxValidation($models, $contractor_id)
	{
		if (isset($_POST['ajax'])) {
			$result = array();
			if (!is_array($models))
				$models = array($models);
			foreach ($models as $model) {
				$modelName = CHtml::modelName($model);
				$post = Yii::app()->request->getParam($modelName);
				$model->setQuoteAttributes($post[$contractor_id]);
				$model->validate();
				foreach ($model->getErrors() as $attribute => $errors)
					$result[CHtml::activeId($model, "[" . $contractor_id . "]" . $attribute)] = $errors;
			}
			echo json_encode($result);
			Yii::app()->end();
		}
	}

    private function sort_p($a,$b) {
        return strcmp($a["name"], $b["name"]);
    }
}
