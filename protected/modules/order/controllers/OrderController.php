<?php

class OrderController extends Controller
{

    const MANAGER_VIEW = 0;
    const MANAGER_EDIT = 1;
    const MANAGER_PRINT = 2;
    const MANAGER_CANCEL = 3;
    const MANAGER_CODE_FILE = 4;
    public static $func = array(
        self::MANAGER_VIEW => "Перегляд",
        self::MANAGER_EDIT => "Редагування",
        self::MANAGER_PRINT => "Друк",
        self::MANAGER_CANCEL => "Скасування",
        self::MANAGER_CODE_FILE => "Перегляд штрих-коду та вивантаження в файл"
    );
	public $windowIncrement = 1;

	public static function accessFilters()
    {
        return array(
            "index"=>array(
                "name"=>"Менеджер замовлень",
                "params"=>array(
                    "user" => array(
                            "name"=>"Квитки користувача",
                            "params"=>self::$func,
                            "type"=>Access::TYPE_CHECKBOX,
							"withEvent"=>true
                        ),
                    "role" => array(
                            "name"=>"Квитки гравця",
                            "params"=>self::$func,
                            "type"=>Access::TYPE_CHECKBOX,
							"withEvent"=>true
                        ),
                    "event" => array(
                            "name"=>"Усі квитки події",
                            "params"=>self::$func,
                            "type"=>Access::TYPE_CHECKBOX,
							"withEvent"=>true
                        ),
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

    public function accessList()
    {
        return array(
            "getSectors",
            "getUsers",
            "getEvents",
            "getCities",
            "toCart",
            "fromCart",
            "printTickets",
            "generatePrintPage",
            "cancelTickets",
            "clearCart",
            "generateTicketXls",
            "generateTicketCsv",
            "getTicketHistory",
            "getTicketDetail",
            "getOrderDetail",
            "getTicketsEditInfo",
            "saveTicketsInfo",
            "saveOrderFilter",
            "deleteFilter",
            "getInvoice",
			"isPaidSelectedTickets",
			"saveOrderDetail"
        );
    }

    public $ordersTotal = array();

	public function actionIndex()
	{

		$filter_id = Yii::app()->request->getParam('filter_id');
		$search = false;
		$status = false;
		$order = false;
		$page = false;
		if ($filter_id) {
			$filter = OrderFilter::model()->findByAttributes(array(
				"id"=>$filter_id,
                "user_id"=>Yii::app()->user->id
			));
            if ($filter) {
                $settings = json_decode($filter->settings);

                $search = $settings->search;
                $order = (array)$settings->Order;
                $page = $settings->Order_page;
                $status = $settings->Event;
            }
		}

		$search = $search ? :Yii::app()->request->getParam('search');

        $status = $status ? :Yii::app()->request->getParam('Event');

		$order = $order? :Yii::app()->request->getParam('Order');

		$page = $page? :Yii::app()->request->getParam('Order_page');

		$maxPage = Yii::app()->request->getParam('maxPageSize');


		if (!$page)
			$page = 1;

		if (!$maxPage)
			$maxPage = 10;

		$model = new Order('searchOrders');
        $event = new Event();
        $event->unsetAttributes();
        if ($status)
            $event->attributes = $status;

		$model->pay_method = Order::PAY_ALL;
		$model->creator = User::TYPE_ALL;

		$orders = new CArrayDataProvider(array());

		Yii::app()->clientScript->registerScript('orderList', '
			var cart = $(".cart-fixed");
			function setCartPosition() {
				cart.css({"top":($(window).height()/2)-(cart.height()/2)+"px"});
			}

			function clearForm() {
				var orderForm = $("#order-filter-form");
				$(":input","#order-filter-form")
					.not(":button, :submit, :reset, :hidden, :radio, :checkbox")
					.val("")
					.removeAttr("checked")
					.removeAttr("selected");
				$(\'input[name=\"Order[pay_method]\"][value=\"'.Order::PAY_ALL.'\"]\').prop("checked", true).trigger("change");
				$(\'input[name=\"Order[creator]\"][value=\"'.User::TYPE_ALL.'\"]\').prop("checked", true).trigger("change");
				orderForm.find("select.to-select2").each(function(){$(this).val(null).trigger("change");});
				orderForm.find("select.to-select2-ext").each(function(){$(this).val(null).trigger("change");});
				orderForm.find(":checkbox").each(function(){
				    $(this).prop("checked",false).trigger("change");
				});

			}

			var cart_modal = $("#cart-edit-modal"),
				cart_panel = cart_modal.find("div.ticketsInfoPanel"),
				cart_loading = cart_modal.find(".loading");
			cart_panel.css({"display":"none"});
			cart_modal.on("show.bs.modal", function () {
				var _this = $(this);
				$.post("'.$this->createUrl("getTicketsEditInfo").'", function(result){
					cart_panel.html(JSON.parse(result));
					spinner_load();
					cart_panel.toggle();
					spinner_close();
				})
			});

			cart_modal.on("hidden.bs.modal", function(){
				var _this = $(this);
				cart_panel.toggle();
				spinner_close();

			});

			$(document).on("click", "#printButton", function(e){

				$.post("'.$this->createUrl("isPaidSelectedTickets").'",
					 function(result){
						if(result == true)
							window.open("'.CController::createAbsoluteUrl("/order/order/printTickets").'","printWindow","width=screen.width,height=screen.height");
						else {
							if(confirm("Увага!\r\nВи друкуєте неоплачені квитки!\rБажаєте щоб факт отримання оплати був зарахований за Вами?"))
								window.open("'.CController::createAbsoluteUrl("/order/order/printTickets").'?fromOrder=true","printWindow","width=screen.width,height=screen.height");
							else
								window.open("'.CController::createAbsoluteUrl("/order/order/printTickets").'?notPayByMe=true&fromOrder=true","printWindow","width=screen.width,height=screen.height");
						}
				});
				setTimeout(refreshAll, 1000);

			});

			$(document).on("click", "#cart-cancel-tickets", function(e){
				var selectedTickets = $(".oneTicket:checked");

				if (selectedTickets.length > 0) {
					if(confirm("Ви дійсно бажаєте скасувати вибрані квитки?")){
						spinner_load();
						$.post("'.$this->createUrl("order/cancelTickets").'",
						{
							data: "all"
						}, function(result){
							$.fn.yiiListView.update("orderList", {
								data:  $("#order-filter-form").serialize()
							});
							clearCart();
							$(".cart-hide-button").click();
							spinner_close()
						});
					}
				}
			});

			$(document).on("change", "#maxPage", function(e){
				spinner_load();
				var pageSize = $("#maxPageSize"),
					newSize = $(this).val();
				$("#Order_page").val(1);
				newSize = parseInt(newSize);
				pageSize.val(newSize);
				$.fn.yiiListView.update("orderList", {
                            data:  $("#order-filter-form").serialize()
				});
				spinner_close();
			});

			$(document).on("keyup", ".select2-search__field", function(e){
				var message = $(this).parent().next().find(".select2-results__message"),
					select = $("#maxPage"),
					selectLab = select.next();
				if(selectLab.hasClass("select2-container--open")) {
					message.text("");
					message.append("<a href=\"#\" id=\"addNewPaginationOption\" data-count=\""+$(this).val()+"\" class=\"btn btn-info\">Додати \""+$(this).val()+"\" до кількості вибору замовлень на сторінці</a>");
				}
			});

			$(document).on("click", "#addNewPaginationOption", function(e){
				var select = $("#maxPage"),
					newCount = $(this).data("count");
				newCount = parseInt(newCount);

				if(newCount != "NaN" && newCount != 0) {
					select.append("<option value=\""+newCount+"\">"+newCount+" замовлень</option>");
					select.next().click();
					select.val(newCount);
					select.change();
				}
			});

			function refreshAll()
			{
				var selectedTickets = $(".oneTicket:checked");
				if (selectedTickets.length > 0) {
					clearCart();
				    $(".cart-hide-button").click();
				    $.fn.yiiListView.update("orderList", {
                            data:  $("#order-filter-form").serialize()
					});
				}
			}


			$(document).on("click", ".dropFilters", function(e){
				e.preventDefault()
				clearForm();
			});

			function openCloseCart() {
				cart.animate({"right":cart.attr("data-visible") == "visible" ? "-285px" : "7px"}).attr("data-visible", cart.attr("data-visible") == "visible" ? "none" : "visible");
			}

			function setCartInfo(info) {
				$(".cartCount").html(info.count);
				$(".cartSum").html(info.cost);
				$(".cartToPay").html(info.cost-info.discount);
			}

			function deleteFromCart(ids) {
				$.post("'.$this->createUrl("fromCart").'",
				{
					data: ids
				}, function(result){
					var info = JSON.parse(result);
					setCartInfo(info);
				})
			}
			$(document).on("click", ".clearCart", function(e) {
				e.preventDefault();
				clearCart();
			});
			function clearCart() {
			    $(document).find("#orderList input:checkbox").prop("checked", false);
				$.post("'.$this->createUrl("clearCart").'",
				{
					data: "all"
				}, function(result){
					var info = JSON.parse(result);
					setCartInfo(info);
				})
			}

			setCartPosition();
			openCloseCart();

			cart.find(".left-arrow").on("click", function(){
				openCloseCart();
				setCartPosition();
			})


			$(window).on("resize", function(){setCartPosition()});

			$(document).on("change", ".selectChild", function(){
				var _this = $(this),
					dataId = _this.attr("data-id"),
					checked = _this.is(":checked"),
					tickets = [],
					childs = $(".child_"+dataId);
				if(childs.length > 0) {
					$(".fa-shopping-cart").click()
					childs.prop("checked", checked);
					childs.each(function(){
							var _this = $(this);
							tickets.push({
								id:_this.attr("data-id"),
								status: _this.attr("data-status")
							});
						});
					if (checked) {

						$.post("'.$this->createUrl("toCart").'",
						{
							data: tickets
						}, function(result){
							var info = JSON.parse(result);
							setCartInfo(info);
						})
					} else
						deleteFromCart(tickets);
				}
			});

			$(document).on("change", ".oneTicket", function(){
				var _this = $(this),
					ticket = [{id:_this.attr("data-id"),
							status: _this.is(":checked") ? 1 : 0}];
				if (_this.is(":checked")) {
					$(".fa-shopping-cart").click()
					$.post("'.$this->createUrl("toCart").'",
						{
							data: ticket
						}, function(result){
							var info = JSON.parse(result);
							setCartInfo(info);
					})
				} else {
					deleteFromCart(ticket);
				}

			});
			function getEvents() {
				var status = $("#Event_status").find(":checked"),
					arr = [],
					city = $("#Order_city_id").val();
				if (status!="undefined")
					status.each(function(){
						arr.push($(this).val())
					});

				$.post("'.$this->createUrl('getEvents').'",
				{
					status : JSON.stringify(arr),
					city: JSON.stringify(city)
				}, function(result){
					$("#Order_event_id").select2("destroy").html(result).select2(select2_param("default"));
					$("#Order_sector").select2("destroy").html("").select2();
				});
			}

            $("#Order_event_id").on("change", function(){
                $.post("'.$this->createUrl("getSectors").'",
                    {
                        events: $(this).val()
                    }, function(result) {
                        $("#Order_sector").select2("destroy").html(result).select2();
                    }
                )
            });

            $("#filter_id").on("change", function(e) {
                e.preventDefault();
				var _this = $(this),
					applyFilter = $(".applyFilter");
				if (_this.val()>0)
					applyFilter.prop("disabled",false);
				else
					applyFilter.prop("disabled", true);
            })
			$(".saveFilter").on("click", function(e){
			    e.preventDefault();

                var name = $("#filterName").val(prompt("Введіть назву налаштування")),
                    form = $("#order-filter-form").serialize();

                if (name.length>0) {
                    $.post("'.$this->createUrl("saveOrderFilter").'",
                        form
                        , function(result) {

                            $("#filter_id").select2({data:result});
                        }, "json"
                    );
                }
			});

			$(".deleteFilter").on("click", function(e){
			    e.preventDefault();
			    var _filter =  $("#filter_id")
			    if (_filter.val()>0)
                    $.post("'.$this->createUrl("deleteFilter").'",
                        {
                            filter_id: _filter.val()
                        }, function(result){
                            if (JSON.parse(result)>0) {
                                _filter.find("option:selected").remove();
                                _filter.select2()
                            }

                        }
                    );
			});

			$("#Order_city_id").on("change", function(e){
				getEvents();
			});

			$(".role").on("change", function(e){
				var _this = $(this);
				if (_this.val() == "")
					return;
				$.post("'.$this->createUrl("getUsers").'",
					{
						type: _this.attr("data-type"),
						role: _this.val()
					}, function(result) {
						$("."+_this.attr("data-type")+"_user").select2("destroy").html(result).select2();
					}
				);
			});

			$("#Order_creator").on("change", function(e){
				var _val = $(this).find(":checked").val(),
					creatorRole = $("#Order_creator_role"),
					creatorId = $("#Order_creator_id");
				if (_val == '.User::TYPE_USER.') {
					creatorRole.prop("disabled", false);
					creatorId.prop("disabled", false);
				} else {
					creatorRole.prop("disabled", true);
					creatorId.prop("disabled", true);
				}
			});

			$(document).on("click", ".saveTicketsInfo", function(e){
			    var form = $("#cart-edit-form").serialize();
			    $.post("'.$this->createUrl("saveTicketsInfo").'",
			        {
			            data: form
			        }, function(result) {
                        cart_modal.modal("hide");
                        clearCart();
                        $.fn.yiiListView.update("orderList", {
                            data:  $("#order-filter-form").serialize()
                        });
			        }
			    );
			});

			$("#Order_pay_method").on("change", function(e){
				var _val = $(this).find(":checked").val(),
					cashRole = $("#Order_cash_role"),
					cashUserId = $("#Order_cash_user_id");
				if (_val == '.Order::PAY_CASH.') {
					cashRole.prop("disabled", false);
					cashUserId.prop("disabled", false);
				} else {
					cashRole.prop("disabled", true);
					cashUserId.prop("disabled", true);
				}
			});

			$("#Event_status").on("change", function(e){
				e.preventDefault();
				getEvents();
			});
	 $(document).find(\'.page\').click(function(){
        submitForm(this);
        return false;
    });
    $(document).find(\'.first\').click(function(){
        submitForm(this);
        return false;
    });
    $(document).find(\'.previous\').click(function(){
        submitForm(this);
        return false;
    });
    $(document).find(\'.next\').click(function(){
        submitForm(this);
        return false;
    });
    $(document).find(\'.last\').click(function(){
        submitForm(this);
        return false;
    });
    function submitForm(obj)
    {
        var href = $(obj).find(\'a\').attr(\'href\');
        var pos = href.indexOf(\'Order_page=\');
        var page = 1;

        if(pos > 0)
            page = href.substring(pos + 11);


        $("#Order_page").val(page);

        $.fn.yiiListView.update("orderList", {
        	data:  $("#order-filter-form").serialize()
        });
    }

    var formData = $("#order-filter-form").serialize(),
    	generateLinks = $(".generateDoc");
	generateLinks.each(function(e){
		var link = $(this).attr("href");
		$(this).attr("href",link + "?" + formData);
	});


		', CClientScript::POS_READY);
		$sectors = array();

		$ticketsInfo = array(
			"sum"=>0,
			"count"=>0,
			"ordersCount"=>0
		);

		if ($search) {
            ini_set("memory_limit", "256M");
			$model->attributes = $order;

			$orders = $model->searchOrders(array(), $page, $maxPage);
			if (Yii::app()->request->isAjaxRequest) {
				$this->widget('application.widgets.orderListWidget.OrderList', array(
					'dataProvider'=>$orders,
					'pagination'=>$model->pagination
				));
				Yii::app()->end();
			}
			if (isset($order['event_id'])) {
				$event_ids =  $order['event_id'];
				$sectors = Event::getListSectors($event_ids);
			}

			$ticketsInfo = $model->ticketsInfo;
			if (empty($ticketsInfo))
				$ticketsInfo = $model->getTicketsInfo();
		}
        if (!empty($model->ticketIds)) {
            $model->orderIds = [];
            foreach ($orders->getData(true) as $order)
                $model->orderIds[] = $order->id;
            $totals = Yii::app()->db->createCommand()
                ->select("order_id, count(*) as count")
                ->from(Ticket::model()->tableName())
                ->where(array("in", "order_id", $model->orderIds))
                ->group("order_id")
                ->queryAll();
            foreach ($totals as $total)
                if (!isset($this->ordersTotal[$total['order_id']]))
                    $this->ordersTotal[$total['order_id']] = $total['count'];
        }
		$this->initCart();
		$this->render('index', array(
			"model"=>$model,
			"dataProvider"=>$orders,
			"sectors"=>$sectors,
			"ticketsInfo"=>$ticketsInfo,
			"page"=>$page,
			"filter_id"=>$filter_id,
            "event"=>$event,
			"maxPage"=>$maxPage
		));
	}

	private function initCart()
	{
		if (Yii::app()->user->getState('currentCartId') != "cartId_orderManager")
			Yii::app()->user->setState("currentCartId", "cartId_orderManager");
	}

	public function actionToCart()
	{
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$this->initCart();
			$ids = array_map(function($ticket) {
				return $ticket['id'];
			}, $data);

			$tickets = Ticket::model()->findAllByAttributes(array("id"=>$ids));

			foreach ($tickets as $ticket) {
				if (Yii::app()->shoppingCart->contains($ticket->getId())) {
//					Yii::app()->shoppingCart->remove($ticket->id);
					continue;
				}
				Yii::app()->shoppingCart->put($ticket, 1, "cartId_orderManager");
			}
			$result['count'] = Yii::app()->shoppingCart->getCount();
			$result['cost'] = Yii::app()->shoppingCart->getCost();
			$result['discount'] = 0;
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	public function actionFromCart() {
		$data = Yii::app()->request->getParam('data');
		if ($data) {
			$ids = array_map(function($ticket) {
				return $ticket['id'];
			}, $data);
			$tickets = Ticket::model()->findAllByAttributes(array("id"=>$ids));
			foreach ($tickets as $ticket)
				Yii::app()->shoppingCart->remove($ticket->getId());

			$result['count'] = Yii::app()->shoppingCart->getCount();
			$result['cost'] = Yii::app()->shoppingCart->getCost();
			$result['discount'] = 0;
			echo json_encode($result);
			Yii::app()->end();
		}
	}

	public function actionPrintTickets()
	{
		$notPayByMe = Yii::app()->request->getParam("notPayByMe");
		$fromOrder = Yii::app()->request->getParam("fromOrder");

		$positions = Yii::app()->shoppingCart->getPositions();

		$order_id = Yii::app()->request->getParam("order_id");
        if (empty($positions) && !$order_id) {
			Yii::app()->clientScript->registerScript("closeWindow", "
				window.close();
			", CClientScript::POS_BEGIN);
			$this->renderPartial("print", array("dataProvider"=>new CArrayDataProvider(array())), false, true);
			Yii::app()->end();
		}
        $new = Yii::app()->request->getParam("new");
		$enabledInSame = true;

		$changedEventsCSSArray = array();
		$criteria = new CDbCriteria();
		if (!empty($positions)) {
			$ids = array_map(function($ticket){
				return $ticket->id;
			}, $positions);
			Yii::app()->shoppingCart->clear();

			$id = $new ? "place_id" : "id";

			$criteria->addInCondition($id, $ids);
		}


        if ($new&&$order_id)
            $criteria->compare("order_id", $order_id);

		$tickets = Ticket::model()->findAll($criteria);
		$printed = "false";
		Yii::app()->clientScript->registerMetaTag("text/html; charset=utf-8", null, "Content-Type");
		$type = null;
		$diffTypes = false;
		foreach($tickets as $key => $ticket){
			if ($type !== null && $type !== $ticket->type_blank)
				$diffTypes = true;
			else
				$type = $ticket->type_blank;
			$place = Place::model()->with("event.tickets")->findByPk($ticket->place_id);

			$ticket->author_print_id = Yii::app()->user->id;
			$ticket->print_role_id = Yii::app()->user->currentRoleId;
			if($ticket->pay_status != Ticket::PAY_PAY && $ticket->pay_status != Ticket::PAY_INVITE && !$ticket->cash_user_id && !$notPayByMe && !$fromOrder) {
				$ticket->pay_status = Ticket::PAY_PAY;
				$ticket->cash_user_id = Yii::app()->user->id;
				$ticket->cash_role_id = Yii::app()->user->currentRoleId;
				$ticket->delivery_type = Order::IN_KASA_PAY;
				$ticket->delivery_status = Ticket::DELIVERY_RECIEVED;
				$ticket->pay_type = Order::IN_KASA_PAY;
				$ticket->date_pay = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
			} elseif($ticket->pay_status != Ticket::PAY_PAY && $ticket->pay_status != Ticket::PAY_INVITE && !$ticket->cash_user_id && !$notPayByMe && $fromOrder) {
				$ticket->pay_status = Ticket::PAY_PAY;
				$ticket->cash_user_id = Yii::app()->user->id;
				$ticket->cash_role_id = Yii::app()->user->currentRoleId;
				$ticket->date_pay = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
			}

			if($ticket->delivery_status != Ticket::DELIVERY_RECIEVED && $ticket->delivery_type == Order::IN_KASA_PAY)
				$ticket->delivery_status = Ticket::DELIVERY_RECIEVED;

			if($place->event->tickets){
				if(array_key_exists($place->event->id,$changedEventsCSSArray))
					array_push($changedEventsCSSArray[$place->event->id], $ticket);
				else
					$changedEventsCSSArray[$place->event->id] = array($ticket);
				unset($tickets[$key]);
			}
		}

		if($diffTypes) {
			Yii::app()->clientScript->registerScript("closeWindow", "
				alert('Виберіть квитки одного типу (А4 чи Бланк)');
				window.close();
			", CClientScript::POS_BEGIN);
			$this->renderPartial("print", array("dataProvider"=>new CArrayDataProvider(array()),"type"=>$type), false, true);
			Yii::app()->end();
		}

		if(!empty($changedEventsCSSArray))
			$messageConfirm = "(друк відбудеться в декількох вікнах)";
		else
			$messageConfirm = "";



		Yii::app()->clientScript->registerScript('loadBarcodes', '
               $(".barcodeView").each(function(){
                var currContainer = $(this);
                   if(currContainer.is(":empty"))
                        currContainer.html("").show().barcode(value, type, settings);
               });
		    ', CClientScript::POS_READY);

		if (!empty($tickets)) {
			$dataProvider = Order::getTicketCriteria($tickets);
			foreach ($tickets as $ticket) {
//				}
				if($ticket->date_print){
					$printed = "true";
					$ticket->date_print = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
					$ticket->save(false);
				} else {
					$ticket->date_print = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
					$ticket->save(false);
				}
                Ticket::saveState($ticket->id);
			}
			$enabledInSame = false;
		};

		if(empty($tickets) && !empty($changedEventsCSSArray) && $enabledInSame) {
			foreach ($changedEventsCSSArray as $key => $ticket) {
				if(!$enabledInSame){
					continue;
				}
				$id = array_map(function ($t) {
					return $t['id'];
				}, $ticket);

				if(!is_array($id))
					$id = array("id"=>$id);
				foreach ($ticket as $t) {
					$t->author_print_id = Yii::app()->user->id;
					$t->print_role_id = Yii::app()->user->currentRoleId;
					if($t->pay_status != Ticket::PAY_PAY && $t->pay_status != Ticket::PAY_INVITE && !$t->cash_user_id && !$notPayByMe) {
						$t->pay_status = Ticket::PAY_PAY;
						$t->cash_user_id = Yii::app()->user->id;
						$t->cash_role_id = Yii::app()->user->currentRoleId;
						$t->date_pay = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
					}
					if($t->date_print){
						$printed = "true";
						$t->date_print = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
						$t->save(false);
					} else {
						$t->date_print = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
						$t->save(false);
					}
                    Ticket::saveState($t->id);
				}
				$dataProvider = Order::getTicketCriteria($id);
				$enabledInSame = false;
				unset($changedEventsCSSArray[$key]);
			}
		}

		Yii::app()->clientScript->registerScript('print', '
			if(' . $printed . ') {
				if(confirm("Цe замовлення вже було роздруковано. Роздрукувати примусово?'.$messageConfirm.'")) {
					window.print();
				}
			} else {
				window.print();
			}
			var keys = {};

			$(window).keydown(function (e) {
				e.preventDefault();
				keys[e.which] = true;

			});

			window.oncontextmenu = function ()
			{
				return false;
			}

			$(window).keyup(function (e) {
				if(keys[80] && keys[17]){
					keys = {};
					if(confirm("Увага! За подальші дії Ви несете матеріальну відповідальність! Продовжити?")){
						window.print();
					}
				}
				delete keys[e.which];
			});

		', CClientScript::POS_READY);

		if(!empty($changedEventsCSSArray)) {
			$i = 2;
			foreach ($changedEventsCSSArray as $key => $tickets) {

				$id = [];
				foreach ($tickets as $ticket) {
					$id[] = $ticket->id;
					$ticket->date_print = Yii::app()->dateFormatter->format("yyyy-MM-dd HH:mm:ss", time());
					$ticket->save(false);
					Ticket::saveState($ticket->id);
				}

			if (is_array($id))
				$id = implode('&', $id);


			Yii::app()->clientScript->registerScript('print' . $i, '
					window.open("' . CController::createAbsoluteUrl('/order/order/generatePrintPage', array("id" => $id)) . '","printWindow' . $i . '","width=screen.width,height=screen.height");

					', CClientScript::POS_BEGIN);
			$i++;
			}

		}


		$this->renderPartial("print", array(
			"dataProvider" => $dataProvider,
			"type"=>$type
		), false, true);

	}

	public function actionGeneratePrintPage($id)
	{
		if(strpos($id,'&'))
			$id = explode("&",$id);
		else
			$id = array("id"=>$id);
		$tickets = Ticket::model()->findAllByAttributes(array("id"=>$id));

		$type = false;
		$printed = "false";
		foreach ($tickets as $ticket) {
			if(!$type)
				$type = $ticket->type_blank;
			if($ticket->date_print)
				$printed = "true";

		}

		$this->windowIncrement++;

		Yii::app()->clientScript->registerScript('loadBarcodes', '
				   $(".barcodeView").each(function(){
					var currContainer = $(this);
					   if(currContainer.is(":empty"))
							currContainer.html("").show().barcode(value, type, settings);
				   });
					var keys = {};
				   if('.$printed.') {
						if(confirm("Цe замовлення вже було роздруковано. Роздрукувати примусово? (друк відбудеться в декількох вікнах - повідомлення вікна № '.$this->windowIncrement.')")) {
							window.print();
						}
					} else
						window.print();
					$(window).oncontextmenu = function ()
					{
						return false;
					}
					$(window).keydown(function (e) {
						e.preventDefault();
						keys[e.which] = true;

					});
					$(window).keyup(function (e) {
						if(keys[80] && keys[17]){
							keys = {};
							if(confirm("Увага! За подальші дії Ви несете матеріальну відповідальність! Продовжити?")){
								window.print();
							}
						}
						delete keys[e.which];
					});
		', CClientScript::POS_READY);
		$dataProvider = Order::getTicketCriteria($id);
		if (!$dataProvider->itemCount)
			$this->redirect("index");
		$this->renderPartial("print", array(
			"dataProvider"=>$dataProvider,
			"type"=>$type
		), false, true);
	}

	public function actionCancelTickets()
	{
		$positions = Yii::app()->shoppingCart->getPositions();
		$tIds = array_map(function($ticket){
			return $ticket->id;
		}, $positions);
		$ids = Yii::app()->db->createCommand()
			->select("place_id")
			->from(Ticket::model()->tableName())
			->where(array("in", "id", $tIds))
			->queryColumn();
		Place::setPlaceStatus($ids, Ticket::STATUS_CANCEL, false, Place::STATUS_SALE);
		Place::refreshCode($ids);
		Ticket::saveState($tIds);

//		Yii::app()->db->createCommand()
//			->update(Ticket::model()->tableName(), array("status"=>Ticket::STATUS_CANCEL), array("in", "id", $ids));

		Yii::app()->shoppingCart->clear();
		$this->redirect('index');
	}

	public function actionClearCart()
	{
		$this->initCart();

		Yii::app()->shoppingCart->clear();
		$result['count'] = Yii::app()->shoppingCart->getCount();
		$result['cost'] = Yii::app()->shoppingCart->getCost();
		$result['discount'] = 0;
		echo json_encode($result);
		Yii::app()->end();
	}

	public function actionGetOrderDetail()
	{
		$order_id = Yii::app()->request->getParam("order_id");
		$edit = Yii::app()->request->getParam("edit");
		$isScript = Yii::app()->request->getParam("isScript");
        if ($order_id) {
			$process = true;
			if ($isScript)
				$process = false;
            $model = Order::model()->findByPk($order_id);
            if (!$model)
                Yii::app()->end();
			if ($edit) {
                $delivery = new Delivery();
				$result = $this->renderPartial("_edit_order", array(
					"model"=>$model,
                    "delivery"=>$delivery
				), true, $process);
			} else
				$result = $this->renderPartial("_order_info", array(
					"model"=>$model
				), true, $process);
            echo json_encode($result);
        }
	}

    public function actionSaveOrderDetail()
    {
        $order = Yii::app()->request->getParam("Order");
        $delivery = Yii::app()->request->getParam("Delivery");

        if ($order) {

            $model = Order::model()->findByPk($order['id']);
            $this->performAjaxValidation($model);
            $model->attributes = $order;

            if ($model->save(false)) {
                if ($delivery) {
                    if ($model->delivery)
                        $model_del = $model->delivery;
                    else {
                        $model_del = new Delivery();
                        $model_del->order_id = $model->id;
                    }
                    $model_del->attributes = $delivery;
                    $model_del->save(false);
                }
                echo json_encode("ok");
			}
        }
    }
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

	public function actionGetTicketDetail()
	{
		$id = Yii::app()->request->getParam('id');
		if ($id) {
            $ticket = Yii::app()->cache->get("ticket_detail_ticket_".$id);
            if (!$ticket) {
                $ticket = Ticket::model()->with('order', 'place', 'place.sector', 'place.sector.typeSector', 'place.sector.typeRow', 'place.sector.typePlace', 'place.event', 'platform')->findByPk($id);
                Yii::app()->cache->set("ticket_detail_ticket_".$id, $ticket, 30);
            }
			$result = array(
				array(
					"type"=>"Подія",
					"value"=>$ticket->place->event->name
				),
				array(
					"type"=>$ticket->place->sector->typeSector ? $ticket->place->sector->typeSector->name : "Сектор",
					"value"=>$ticket->place->sector->name
				),
				array(
					"type"=>$ticket->place->type == Place::TYPE_SEAT ? $ticket->place->sector->typeRow->name : "Ряд",
					"value"=>$ticket->place->editedRow
				),
				array(
					"type"=>$ticket->place->type == Place::TYPE_SEAT ? $ticket->place->sector->typePlace->name : "Місце",
					"value"=>$ticket->place->editedPlace
				),
				array(
					"type"=>"Ціна",
					"value"=>number_format($ticket->price-$ticket->discount, 0, '.', ' ')
				),
                array(
                    "type"=>"Активність",
                    "value"=>$ticket->getStatus()
                ),
                array(
                    "type"=>"Формат",
                    "value"=>Ticket::getBlankType($ticket->type_blank)
                ),
                array(
                    "type"=>"Власник",
                    "value"=>!strpos($ticket->owner_surname, "NULL")?$ticket->owner_surname:"-"
                ),
                array(
                    "type"=>"",
                    "value"=>""
                ),
                array(
                    "type"=>"Дата створення",
                    "value"=>Yii::app()->dateFormatter->format("dd-MM-yyyy HH:mm", $ticket->date_add)
                ),
                array(
                    "type"=>"Створив",
                    "value"=>$ticket->user->fullName
                ),
                array(
                    "type"=>"Гравець, що створив",
                    "value"=>$ticket->role->name
                ),
                array(
                    "type"=>"Платформа створення",
                    "value"=>$ticket->platform_id ? "Платформа ".$ticket->platform->name : "-"
                ),
                array(
                    "type"=>"",
                    "value"=>""
                ),
                array(
                    "type"=>"Гравець автора друку",
                    "value"=>$ticket->date_print&&$ticket->print_role_id ? Role::getRoleName($ticket->print_role_id) : "-"
                ),
				array(
					"type"=>"Автор друку",
					"value"=>$ticket->date_print&&$ticket->author_print_id ? $ticket->printUser->fullName : "-"
				),
				array(
					"type"=>"Статус друку",
					"value"=>Ticket::$statusPrint[$ticket->printStatus]
				),
				array(
					"type"=>"Дата друку",
					"value"=>Yii::app()->dateFormatter->format("dd-MM-yyyy HH:mm", $ticket->date_print)
				),
				array(
					"type"=>"",
					"value"=>""
				),
				array(
					"type"=>"Спосіб оплати",
					"value"=>$ticket->payType
				),
				array(
					"type"=>"Статус оплати",
					"value"=>$ticket->payStatus
				),
				array(
					"type"=>"Дата оплати",
					"value"=>$ticket->date_pay ? Yii::app()->dateFormatter->format("dd-MM-yyyy HH:mm", $ticket->date_pay) : "-"
				),
				array(
					"type"=>"Прийняв оплату",
					"value"=>in_array($ticket->pay_type, Order::$ePay) ? "Платон" : ($ticket->date_pay&&$ticket->cash_user_id ? $ticket->cashUser->fullName:"-")
				),
				array(
					"type"=>"",
					"value"=>""
                ),
				array(
					"type"=>"Спосіб доставки",
					"value"=>Ticket::getDeliveryType($ticket->delivery_type)
				),
				array(
					"type"=>"Статус доставки",
					"value"=>Ticket::getStatusDelivery($ticket->delivery_status)
				),
			);
			$dataProvider = new CArrayDataProvider($result, array(
				"keyField"=>"type",
				"pagination"=>false
			));
			$this->render("application.widgets.orderListWidget.views._ticketDetail", array(
				"dataProvider"=> $dataProvider
			));
		}
	}

	public function actionGetUsers()
	{
		$role = Yii::app()->request->getParam('role');
		$type = Yii::app()->request->getParam('type');
		if ($role&&$type) {
			$row = "user_id";
			$where = "role_id";
			if ($type == "cash") {
				$row = "cash_user_id";
				$where = "cash_role_id";
			} elseif ($type == "print") {
				$row = "author_print_id";
				$where = "print_role_id";
			}
			$ids = Yii::app()->db->createCommand()
				->selectDistinct($row)
				->from(Ticket::model()->tableName())
				->where($where."=:where", array(":where"=>$role))
				->queryColumn();
			$ids = array_values($ids);
			$users = User::model()->findAllByPk($ids, "type=:type", array(":type"=>User::TYPE_USER));
            echo CHtml::tag("option", array(
                "value"=>null
            ),"Виберіть користувача");
			foreach ($users as $user) {
				echo CHtml::tag("option", array(
						"value"=>$user->id,
					), "#".$user->id." - ".$user->fullName);
			}
		}
	}

	public function actionGetEvents()
	{
		$status = Yii::app()->request->getParam("status");
		$city = Yii::app()->request->getParam("city");
		if ($status) {
			$status = json_decode($status);
            if($city)
			    $city = json_decode($city);
			$events = Event::getListEvents($status, $city);
			foreach ($events['data'] as $k=>$event) {

				echo CHtml::tag("option", array(
					"value"=>$k,

				)+$events['options'][$k], $event);
			}
		}
	}

	public function actionGetCities() {
		$event = Yii::app()->request->getParam("event");
		if ($event) {

		}
	}
	public function actionGetInvoice($id)
	{
		ini_set("memory_limit", "256M");
		$quote = Quote::model()->findByPk($id);
		$order_id = $quote->order_id;
		$tickets = Ticket::model()->findAllByAttributes(array('order_id'=>$order_id));
		if(!empty($tickets)){
			$generator = new ExelGenerator($quote);
			$generator->generateQuoteInvoice();
		}
	}

	public function actionGetTicketsEditInfo()
	{
		Yii::app()->clientScript->registerCoreScript("jquery.ui");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl."/theme/js/select2/select2.full.min.js");
		Yii::app()->clientScript->registerScript("select2init", "
			$('#cart-edit-modal .to-select2').select2();
		", CClientScript::POS_READY);
		$this->initCart();
		$positions = Yii::app()->shoppingCart->getPositions();

		$ids = array_map(function($ticket){return $ticket->id;}, $positions);

		$tickets = Ticket::model()->with()->findAllByPk($ids);
		$result = array();
		foreach ($tickets as $ticket) {
            $username = "id".$ticket->user->id." - ".$ticket->user->fullName;
            $platformName = $ticket->platform ?  $ticket->platform->name." - ".$ticket->platform->role->name : "";
            $cashUsername = in_array($ticket->pay_type, Order::$ePay) ? "Платон" : ($ticket->cashUser ? "id".$ticket->cashUser->id." - ".$ticket->cashUser->fullName : "");

            $printUsername = $ticket->printUser ? "id".$ticket->printUser->id." - ".$ticket->printUser->fullName : "";


            $pay_method = in_array($ticket->pay_type, Order::$physicalPay) ? Order::PAY_CASH : (in_array($ticket->pay_type, Order::$ePay) ? Order::PAY_CARD : "");
            $status = $ticket->status == Ticket::STATUS_SOLD || $ticket->status == Ticket::STATUS_SEND_TO_EMAIL ? Ticket::STATUS_SOLD : Ticket::STATUS_CANCEL;
            $date_cancel = round((strtotime($ticket->cancel_day)-time())/(60*60*24));
            $date_cancel = $date_cancel<0 ? "" : $date_cancel;
            switch($ticket->delivery_type) {
                case Order::IN_KASA_ONLINE:
                    $del_type = Order::IN_KASA_PAY;
                    break;
                case Order::NP_ONLINE:
                    $del_type = Order::NP_PAY;
                    break;
                case Order::COURIER_ONLINE:
                    $del_type = Order::COURIER_PAY;
                    break;
                default:
                    $del_type = $ticket->delivery_type;
            }
			if (empty($result)) {
				$result = array(
					"del_status"=>$ticket->delivery_status,
					"del_type"=>$del_type,
					"pay_method"=>$pay_method,
					"pay_status"=>$ticket->pay_status,
					"print_status"=>$ticket->getPrintStatus(),
					"status"=>$status,
					"format"=>$ticket->type_blank,
					"tags"=>$ticket->tag,
					"creators"=>array($username),
					"platforms"=>array($platformName),
					"cashiers"=>array($cashUsername),
					"printers"=>array($printUsername),
                    "date_cancel"=>$date_cancel
				);
			} else {
                if (!in_array($username, $result['creators']))
                    $result['creators'][] = $username;
                if (!in_array($platformName, $result['platforms']))
                    $result['platforms'][] = $platformName;
                if (!in_array($cashUsername, $result['cashiers']))
                    $result['cashiers'][] = $cashUsername;
                if (!in_array($printUsername, $result['printers']))
                    $result['printers'][] = $printUsername;

                if ($result['format']!=="" && $result['format']!=$ticket->type_blank)
                    $result['format'] = "";

                if ($result['pay_method']!=="" && $result['pay_method']!=$pay_method)
                    $result['pay_method'] = "";

                if ($result['del_status']!=="" && $result['del_status']!=$ticket->delivery_status)
                    $result['del_status'] = "";

                if ($result['status']!=="" && $result['status']!=$status)
                    $result['status'] = "";

                if ($result["pay_status"]!=="" && $result['pay_status']!=$ticket->pay_status)
                    $result["pay_status"] = "";

                if ($result["print_status"]!=="" && $result['print_status']!=$ticket->getPrintStatus())
                    $result["print_status"] = "";
                if ($result["date_cancel"]!=="" && $result["date_cancel"]!=$date_cancel)
                    $result["date_cancel"]= "";
			}
		}
		$info = $this->renderPartial("_edit_tickets", array("result"=>$result, "tickets"=>$tickets), true, true);
		echo json_encode($info);
	}


    public function actionSaveTicketsInfo()
    {
        $data = Yii::app()->request->getParam("data");
        if ($data) {
            parse_str($data, $data);
            echo json_encode(Ticket::saveTicketsInfo($data));
            Yii::app()->end();
        }
    }

    public function actionSaveOrderFilter()
    {
        $name = Yii::app()->request->getParam("filterName");

        if ($name) {

            if (!($filter = OrderFilter::model()->findByAttributes(array("user_id"=>Yii::app()->user->id, "name"=>$name)))) {
                $filter = new OrderFilter();
                $filter->name = $name;
            }
            unset($_POST['filterName']);
            $filter->settings = json_encode($_POST);
            $filter->save();
            echo json_encode(OrderFilter::getOrderList(false));
        }
    }

    public function actionDeleteFilter()
    {
        $id = Yii::app()->request->getParam("filter_id");
        if ($id) {
            $filter = OrderFilter::model()->deleteByPk($id, "user_id=:user_id", array(
                ":user_id"=>Yii::app()->user->id
            ));
            echo json_encode($filter);
        }
    }

	public function actionGetTicketHistory()
	{
		$model_id = Yii::app()->request->getParam("model_id");
		if ($model_id) {
			$dataProvider = History::getHistory($model_id);
			$this->render("ticketHistory", array("dataProvider"=>$dataProvider));
		}
	}

    public function actionGetSectors()
    {
        $events = Yii::app()->request->getParam("events");
        if ($events) {
            $sectors = Event::getListSectors($events);
            foreach ($sectors as $k=>$sector) {

                echo CHtml::tag("option", array(
                        "value"=>$k,
                    ), $sector);
            }

        }
    }

	public function actionGenerateTicketXls(){
		ini_set("memory_limit", "512M");
		ini_set("max_execution_time", "300");
		$generator = new ExelGenerator(null, uniqid("invoice_"));
		$generator->generateTicketsInvoice();

	}

	public function actionGenerateTicketCsv()
	{
		$tickets = ExelGenerator::filterAcceptedData();

        if(!empty($tickets)){
            Ticket::generateCsv($tickets);
        } else
            throw new CHttpException("400","No tickets given");
	}



	public function actionIsPaidSelectedTickets()
	{
		$positions = Yii::app()->shoppingCart->getPositions();
		if (empty($positions)) {
			echo 1;
			Yii::app()->end();
		}
		foreach ($positions as $ticket) {
			if($ticket->pay_status == Ticket::PAY_NOT_PAY) {
				echo 0;
				Yii::app()->end();
			}
		}
		echo 1;
		Yii::app()->end();
	}
}