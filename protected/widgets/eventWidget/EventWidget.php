<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 28.09.15
 * Time: 12:25
 */


class EventWidget extends CWidget
{
    public $events;
    public $event;
    public function init()
    {
        $this->event = Yii::app()->getRequest()->getParam('event_id');
        $this->events = Event::getListEvents();
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/theme/js/url.min.js', CClientScript::POS_END);
        Yii::app()->clientScript->registerScript("widget-event", '
            function getEvents() {
				var status = $("#widget-event-status").find(":checked"),
					arr = [];
				if (status!="undefined")
					status.each(function(){
						arr.push($(this).val())
					});
				$.post("'.Yii::app()->createUrl('/order/order/getEvents').'",
				{
					status : JSON.stringify(arr),
				}, function(result){
					$("#widget-event-select").select2("destroy").html(result).select2(select2_param("event"));
				});
			}
            $("#widget-event-status").on("change", function(e){
				e.preventDefault();
				getEvents();
			});
			$("#widget-event-select").on("change", function(e){
			    var url = new Url();
			    url.query.event_id = $("#widget-event-select").val();
			    document.location.href = url;
			});
        ', CClientScript::POS_READY);

    }
    public function actionGetEvents()
    {
        $status = Yii::app()->request->getParam("status");
        if ($status) {
            $status = json_decode($status);
            $events = Event::getListEvents($status);
            foreach ($events['data'] as $k=>$event) {
                echo CHtml::tag("option", array(
                        "value"=>$k,
                    )+$events['options'][$k], $event);
            }
        }
    }
    public function run() {
        $this->render("index", array(
            "events"=>$this->events,
            "event"=>$this->event,
        ));
    }
}