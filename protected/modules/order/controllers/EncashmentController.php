<?php

class EncashmentController extends Controller
{
	public static function accessFilters()
	{
		return array(
			"percent"=>array(
				"name"=>"Відсоток",
				"params"=>array(
					"percent"=>array(
						"name"=>"Доступ до сторінки",
						"params"=>array(),
						"type"=>Access::TYPE_CHECKBOX,
					)
				)
			)
		);

	}

	public function actionIndex()
	{
		$this->render('index');
	}

    public function accessList()
    {
        return array(
            "getCashier",
            "getPercentData",
            "getUserEventPercent",
            "savePercent",
            "deleteEventPercent"
        );
    }

	public function actionPercent()
	{
		$cs = Yii::app()->clientScript;
		$cs->registerScript('percentJS',
			'
			$(".select2").each(function(){
				if($(this).hasClass("cashier"))
					$(this).select2({"placeholder":"Виберіть касира"});
				else if($(this).hasClass("kasa"))
					$(this).select2({"placeholder":"Виберіть касу"});

			});

			$(".event-div").hide();

			$(document).on("change", "#CashierPercent_user_id", function(e){
				 var _this = $(this),
					role = $("#CashierPercent_role_id"),
					curr_role = 0;
				 if (role.val() != null)
					curr_role = role.val();
				if(_this.val() != "") {
					$.post(
						"'.$this->createUrl('getPercentData').'",
						{
							user_id: _this.val(),
							role_id: curr_role
						}, function(result) {
							var result = JSON.parse(result);
							$(".data-percent").html(result);
							$(".event-div").show();
							$.post(
								"'.$this->createUrl('getUserEventPercent').'",
								{
									user_id: _this.val(),
									role_id: curr_role
								}, function(result) {
									var result = JSON.parse(result);
									$(".data-events-percent").html(result);
								}
							);
						}
					);
				}
			});

			$(document).on("click", ".percent-submit", function(e){
				e.preventDefault();
				var data = $("#percent-form").serialize(),
					role_id = $("#CashierPercent_role_id").val();
				var _this = $(this),
					validateSuccess = true,
					textFields = $(".n-validate"),
					currElement = null;

				textFields.each(function(){
					if(validateSuccess) {
						currElement = $(this);
					}

					if(nValidate($(this),true) == false) {
						validateSuccess = false;
					}
				});


				if(role_id != "" && validateSuccess) {
					$.post(
						"'.$this->createUrl('savePercent').'",
						data
						, function(result) {
							if(result == true)
								showAlert("success","Успішно збережено");
							else
								showAlert("danger","Виникла помилка, не збережено");
						}
					);
				} else {
					currElement.focus();
				}

			});

			$(document).on("click", ".addEvent", function(e){
				e.preventDefault();
				var event_id = $("#event_id").val(),
					option = null;

				if (event_id != "") {
					option = $("#event_id [value="+event_id+"]");
					addEvent(option);
				} else {
					showAlert("danger", "Виберіть подію");
				}

			});

			$(document).on("click", ".deleteEventPercent", function(e){
				e.preventDefault();
				var parent = $(this).parent(),
					event_ids = parent.find(".event_ids").val(),
					role_ids = $("#CashierPercent_role_id").val(),
					user_ids = $("#CashierPercent_user_id").val(),
					container = parent.parent();

				if (confirm("Ви впевнені що хочете прибрати відсотки касира до цієї події?")) {
					if (event_ids != "" && role_ids != "" && user_ids != "") {
						$.post(
							"'.$this->createUrl('deleteEventPercent').'", {
							data : {event_id:event_ids, role_id:role_ids, user_id:user_ids}
							}, function(result) {
								if(result == true){
									container.remove();
								} else
									showAlert("danger","Виникла помилка, не видалено");
							}
						);
					}
				}
			});

			$(document).on("change", ".n-validate", function(e){
				e.preventDefault();
				nValidate($(this));
			});

			function nValidate(element, withMessage)
			{
				withMessage = typeof withMessage !== "undefined" ? withMessage : false;
				element = typeof element !== "undefined" ? element : false;
				if (!Array.isArray(element)) {
					var value = element.val().replace(",",".");
					value = parseFloat(value);
					if (isNaN(value)) {
						element.parent().addClass("has-error");
						if(withMessage)
							showAlert("danger","Підсвічене червоним поле заповнено невірно, доступні варінти формату (1 або 1.5 або 1.55)", 6000);

						return false;
					} else {
						element.val(value);
						if (element.parent().hasClass("has-error"))
							element.parent().removeClass("has-error");

						return true;
					}
				}
			}

			function showAlert(type, text, time)
            {
            	time = typeof time !== "undefined" ? time : 2000;
                var alert = $("."+type+"-sector-alert");
                alert.text(text);
                alert.css({"display":"block"});
                setTimeout(function(){alert.fadeOut()}, 2000);
            }

            function addEvent(option)
            {
            	var role_id = $("#CashierPercent_role_id").val(),
            		user_id = $("#CashierPercent_user_id").val(),
            		tbody = $(".events-percent-data");

            	if(role_id != "" && user_id != "") {
            		var event_id = option.val(),
            			uniq = true;
					$(".event_ids").each(function(){ if($(this).val() == event_id) uniq = false; });

            		if (uniq) {
            		var element = $(".event_ids").last();
            		if (element.length > 0) {
						var name = element.attr("name"),
							number = parseInt(name.replace(/\D+/g,"")),
							newNumber = number+1;

            		} else
            			var newNumber = 0;

					var container = `<tr>
                        <td><a class="glyphicon glyphicon-remove deleteTree" style="margin-right:20px" href="#"></a>
                        <label>`+option.text()+` / `+option.data("city")+` / `+option.data("date")+`</label><input type="hidden" class="event_ids" value="`+option.val()+`" name="EventPercent[event_id][`+newNumber+`]" id="EventPercent_event_id_`+newNumber+`"></td>
                        <td><input class="form-control input-sm m-b-none n-validate" type="text" value="0.00" name="EventPercent[fullSale][`+newNumber+`]" id="EventPercent_fullSale_`+newNumber+`"></td>
                        <td><input class="form-control input-sm m-b-none n-validate" type="text" value="0.00" name="EventPercent[cashSale][`+newNumber+`]" id="EventPercent_cashSale_`+newNumber+`"></td>
                        <td><input class="form-control input-sm m-b-none n-validate" type="text" value="0.00" name="EventPercent[printSale][`+newNumber+`]" id="EventPercent_printSale_`+newNumber+`"></td>
                    	</tr>`;

					tbody.append(container);
					} else {
						showAlert("danger","Ця подія вже має відсоток");
					}
				} else {
					showAlert("danger","Виберіть касира");
				}
            }


			', CClientScript::POS_READY
		);

		$this->render('percent');
	}

	public function actionCollection()
	{
		$this->render('collection');
	}

	public function actionGetCashier()
	{
		$role_id = Yii::app()->request->getParam("role_id");
		if ($role_id){
			$role = Role::model()->findByPk($role_id);
			$users = $role->userRoles;
			foreach ($users as $user) {
				if(!$user->name && !$user->surname)
					$user->name = $user->email;

				echo CHtml::tag('option',
					array('value' => $user->id), CHtml::encode($user->name." ".$user->surname), true);
			}
		}
		Yii::app()->end();
	}

	public function actionGetPercentData()
	{
		$role_id = Yii::app()->request->getParam("role_id");
		$user_id = Yii::app()->request->getParam("user_id");
		if ($user_id & $role_id)
			$data = CashierPercent::getPercentageByUser($user_id,$role_id);
		elseif ($role_id)
			$data = CashierPercent::getPercentageByRole($role_id);
		else
			$data = [];

		$result = ["order_cash_print_percent"=>0,"cash_print_percent"=>0,"print_percent"=>0];
		if(!empty($data)) {
			$result = ["order_cash_print_percent"=>$data->order_cash_print_percent,"cash_print_percent"=>$data->cash_print_percent,
				"print_percent"=>$data->print_percent];
		}
		echo json_encode($this->renderPartial("_dataPercent", array("data" => $result), true, true));

		Yii::app()->end();

	}

	public function actionGetUserEventPercent()
	{
		$role_id = Yii::app()->request->getParam("role_id");
		$user_id = Yii::app()->request->getParam("user_id");
		if ($user_id & $role_id)
			$data = CashierPercent::getUserEventsPercentage($role_id, $user_id);
		else
			$data = [];

		echo json_encode($this->renderPartial("_eventsPercent", array("data" => $data), true, true));

		Yii::app()->end();
	}

	public function actionSavePercent()
	{
		$cashierData = Yii::app()->request->getParam("CashierPercent");
		$cashierEvents = Yii::app()->request->getParam("EventPercent");
		if (!empty($cashierData)) {
			if($cashierData["role_id"]) {
				$user_id = $cashierData["user_id"] ? $cashierData["user_id"] : 0;
				$cashier = CashierPercent::model()->findByAttributes(["role_id"=>$cashierData["role_id"], "user_id"=>$user_id, "event_id"=>CashierPercent::NO_EVENT]);
				if (!$cashier)
					$cashier = new CashierPercent();
				$cashier->attributes = $cashierData;
				if (!empty($cashierEvents))
					$cashier->eventsPercent = $cashierEvents;
				echo $cashier->save();
			}
		}
		Yii::app()->end();
	}

	public function actionDeleteEventPercent()
	{
		$data = Yii::app()->request->getParam("data");
//		CVarDumper::dump($data);
//		die;
		$eventPercent = CashierPercent::model()->findByAttributes(["role_id"=>$data["role_id"], "user_id"=>$data["user_id"], "event_id"=>$data["event_id"]]);
		if ($eventPercent) {
			echo $eventPercent->delete();
		} else
			echo true;

		Yii::app()->end();
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}