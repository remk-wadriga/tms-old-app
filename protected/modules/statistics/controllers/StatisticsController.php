<?php

class StatisticsController extends Controller
{
	public static function accessFilters()
	{
		return array(
            "basic"=>array(
                "name"=>"Базова статистика",
                "params"=>array(
                    "basic"=>array(
						"name"=>"Базова статистика",
						"params"=>array("Перегляд"),
						"type"=>Access::TYPE_CHECKBOX,
						"withEvent"=>true,
                    )
                ),
				"type"=>"tabs"
            ),
            "extended"=>array(
                "name"=>"Розширена статистика",
                "params"=>array(
                    "extended"=>array(
                        "name"=>"Розширена статистика",
                        "params"=>array("Перегляд"),
						"withEvent"=>true,
                        "type"=>Access::TYPE_CHECKBOX
                    )
                ),
                "type"=>"tabs"
            ),
            "generateKG9Xls"=>array(
                "name"=>"Генерація КГ9",
                "params"=>array(
                    "kg9"=>array(
                        "name"=>"Так/ні",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    )
                )
            ),
            "generateKG10Xls"=>array(
                "name"=>"Генерація КГ10",
                "params"=>array(
                    "kg10"=>array(
                        "name"=>"Так/ні",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    )
                )
            )
        );
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    private function getAllowedEvents()
    {

    }

	public function actionBasic()
	{
        $orderData = Yii::app()->request->getParam("Order");
        $event_id = Yii::app()->request->getParam('event_id');
		$order = new Order();
        $event = false;
        $date_from = null;
        $date_to = null;

		if(isset($orderData["event_id"])) {
			$order->event_id = $orderData["event_id"];
		} elseif($event_id)
			$order->event_id = $event_id;

        if ($order->event_id) {
            if (!Yii::app()->authManager->checkAccess(Yii::app()->user->role, Yii::app()->user->id, array(), "/".$this->getRoute(), $order->event_id))
                throw new CHttpException(403);
            if ($orderData["start_period"])
                $order->start_period = $orderData["start_period"];
            if ($orderData["end_period"])
                $order->end_period = $orderData["end_period"];
            if ($orderData["start_period"]){
                $date_from = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $orderData["start_period"]." 00:00:00");
            }
            if ($orderData["end_period"]) {
                $date_to = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", $orderData["end_period"]." 23:59:59");
            }
			$event = Event::model()->findByPk($order->event_id);
        }

		if (!$event)
			$event = new Event();
		$statistics = new Statistic($event,$date_from,$date_to);
		$chartData = $statistics->getChartData();

		$placesData = $statistics->getPlacesPreviewStatisticsData();
		$sectorsWithPriceData = $statistics->getSectorsStatistics(true);
        if (Yii::app()->user->getIsAdmin() || Yii::app()->user->id == 7067) {
            $customTable = $statistics->getRolesSpecialStatistic();
        } else
            $customTable = [];
		$this->formJs($chartData);



		$this->render('basic',[
			"event"=>$event,
			"model"=>$order,
			"placesData"=>$placesData,
			"sectorsWithPriceData" => $sectorsWithPriceData,
            "customTable" => $customTable
		]);
	}

	private function formJs($chart=[])
	{

		if (!empty($chart)) {
			$dateY = $chart['dateY'];
			$dateM = $chart['dateM'];
			$dateD = $chart['dateD'];
			$sold = $chart['sold'];
			$reserved = $chart['reserved'];
			Yii::app()->clientScript->registerScriptFile("http://code.highcharts.com/highcharts.js");
			Yii::app()->clientScript->registerScript('chart', "
                    $('#highChart').highcharts({
                        chart: {
                            zoomType: 'x',
                            type: 'spline'
                        },
                        title: {
                            text: false
                        },
                        subtitle: {
                            text: document.ontouchstart === undefined ?
                                'Для збільшення області натисніть та перетягніть курсор' :
                                'Pinch the chart to zoom in'
                        },
                        xAxis: {
                            type: 'datetime',
                            minRange: 60000
                        },
                        yAxis: {
                            title: {
                                text: 'К-сть білетів'
                            },
                            floor: 0
                        },
                        legend: {
                            enabled: true
                        },
                        tooltip: {
                            crosshairs: true,
                            shared: true
                        },
                        plotOptions: {
                            spline: {
                                lineWidth: 4,
                                states: {
                                    hover: {
                                        lineWidth: 5
                                    }
                                },
                                marker: {
                                    enabled: false
                                }
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        series: [
                        {
                            name: 'Продано',
                            pointInterval: 24*3600*1000,
                            pointStart: Date.UTC(". $dateY.",".($dateM-1).",".$dateD."),
                            data: [".implode(",",$sold)."]
                        },
                        {
                            name: 'Бронь',
                            pointInterval: 24*3600*1000,
                            pointStart: Date.UTC( ". $dateY.",".($dateM-1).",".$dateD."),
                            data: [".implode(",",$reserved)."]
                        }
                        ]
                    });

                ", CClientScript::POS_READY);
		}
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl."/theme/js/chart.min.js");
		Yii::app()->clientScript->registerScript('index','

            $("#Order_event_id").on("change", function(){
            	$("#select-event-form").submit();
            });

            $(".order-period").on("change", function(){
            	$("#select-event-form").submit();
            });

        ', CClientScript::POS_READY);
	}

	public function actionExtended()
	{
		$eventData = Yii::app()->request->getParam("Event");
        $event_id = Yii::app()->request->getParam("event_id");
		if ($eventData["id"])
			$event_id = $eventData["id"];
        $model = false;
        if ($event_id) {
            if (!Yii::app()->authManager->checkAccess(Yii::app()->user->role, Yii::app()->user->id, array(),"/".$this->getRoute(), $event_id))
                throw new CHttpException(403);
            $this->extendedFormJs($event_id);
//            $criteria = new CDbCriteria();
//            $criteria->compare("t.id",$event_id);
//            $criteria->with = ["places1.ticket.role"];
//			$dataProvider = new CActiveDataProvider('Event', array("criteria"=>$criteria));
			$model = Event::model()->findByPk($event_id);
        }
		if(!$model)
			$model = new Event();
		$statistics = new Statistic($model);
		$deliveryAndPayData = $statistics->getDeliveryAndPayStatistics();
        $rolesData = $statistics->getRolesStatistics();
		$KG9Data = $statistics->getKG9Statistics();
		$KG10Data = $statistics->getKG10Statistics();

		Yii::app()->clientScript->registerScript('index','
            $("#Event_id").on("change", function(){
            	$("#select-event-form").submit();
            });
        ', CClientScript::POS_READY);
		$this->render('extended',[
			"model"=>$model,
			"deliveryAndPayData" => $deliveryAndPayData,
            "rolesData" => $rolesData,
			"KG9Data" => $KG9Data,
			"KG10Data" => $KG10Data
		]);
	}

	public function extendedFormJs($event_id)
	{
		Yii::app()->clientScript->registerScript('extended','
			$(document).on("click","#valXls", function(e){
				e.preventDefault();
				type = $("input[name=\"invoiceType\"]:checked").val();
				action = "'.CController::createAbsoluteUrl('/event/constructor/getInvoice').'";
				window.open(action+"?type="+type+"&event_id='.$event_id.'","_blank");
			});

        ', CClientScript::POS_READY);
	}

	public function actionGenerateKG9Xls($id)
	{
		$criteria = new CDbCriteria();
		$criteria->compare("t.id",$id);
		$criteria->with = ["places1.ticket.role"];
		$dataProvider = new CActiveDataProvider('Event',array('criteria'=>$criteria));
		$model = current($dataProvider->getData());
		if ($model) {
			$currentDateTime =  Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
			$fileName = $model->name." - ".$currentDateTime;
			$generator = new ExelGenerator($model,$fileName);
			$generator->generateKG9Invoice();
		} else {
			throw new CHttpException("404","Event not found");
		}
	}

	public function actionGenerateKG10Xls($id)
	{
		$criteria = new CDbCriteria();
		$criteria->compare("t.id",$id);
		$criteria->with = ["places1.ticket.role"];
		$dataProvider = new CActiveDataProvider('Event',array('criteria'=>$criteria));
		$model = current($dataProvider->getData());
		if ($model) {
			$currentDateTime =  Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
			$fileName = $model->name." - ".$currentDateTime;
			$generator = new ExelGenerator($model,$fileName);
			$generator->generateKG10Invoice();
		} else {
			throw new CHttpException("404","Event not found");
		}
	}



}