<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 05.06.15
 * Time: 11:38
 */
class CashierController extends Controller
{

    const CREATE_WITH_PAY = 0;
    const CREATE_WITHOUT_PAY = 1;
    const CREATE_INVITE = 2;

    public static $createParams = array(
        self::CREATE_WITH_PAY => "Тільки прийнявши оплату одразу",
        self::CREATE_WITHOUT_PAY => "Не прийнявши оплати",
        self::CREATE_INVITE => "Квитки на запрошення",
    );

    public static function accessFilters()
    {
        return array(
            "listEvent"=>array(
                "name"=>"Створити замовлення",
                "params"=>array(
                    "createOrder"=>array(
                        "name"=>"Доступ до формування замовлення на подію",
                        "withEvent"=>true,
                        "params"=>self::$createParams,
                        "type"=>Access::TYPE_CHECKBOX
                    ),
                    "createPrint"=>array(
                        "name"=>"Доступ до друку квитків на подію (не оформлюючи замовлення)",
                        "withEvent"=>true,
                        "params"=>self::$createParams,
                        "type"=>Access::TYPE_CHECKBOX
                    )
                ),
                "type"=>"tabs"
            ),
            "orders"=>array(
                "name"=>"Пошук замовлення",
                "params"=>array(
                    "allEvents"=>array(
                        "name"=>"Доступ до друку квитків на всі події, що є в системі",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    ),
                    "parentEvents"=>array(
                        "name"=>"Доступ до друку квитків на події, які доступні батьківському гравцю",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    ),
                )
            ),
            "close"=>array(
                "name"=>"Старт/завершення дня",
                "params"=>array(
                    "needClose"=>array(
                        "name"=>"Так/ні",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    )
                )
            ),
            "statistic"=>array(
                "name"=>"Статистика касира",
                "params"=>array(
                    "cashierStatistic"=>array(
                        "name"=>"Так/ні",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX
                    )
                )
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
            'getInCartIds',
            'eventListToCart',
            'deleteFromCart',
            'addFunCount',
            'saveAndPrint',
            'addToFavorites',
            'deleteFromFavorites',
            'getEventInfo',
            'toCart',
            'addFunCount',
            'fromCart',
            'clearCart',
            'eventListClearCart',
            'controls',
            'preCancelTickets',

        );
    }

    public function actionOrders()
    {
        $order = Yii::app()->request->getParam("Order");
        $model = new Order('searchOrders');
        $orders = new CArrayDataProvider(array());
        $page = Yii::app()->request->getParam('Order_page');
        if (!$page)
            $page = 1;
        if ($order) {
            $search = false;

            foreach ($order as $k=>$item) {
                if ($k!="type"&&$item!="") {
                    $search = true;
                    break;
                }

            }
            if ($search) {
                $model->attributes = $order;
                $model->type = Order::TYPE_ORDER;
//                $model->pay_method = Order::PAY_ALL;
                $model->creator = User::TYPE_ALL;
                $model->ticketStatus = array(
                    Ticket::STATUS_CANCEL,
                    Ticket::STATUS_SOLD
                );
                $orders = $model->searchOrders(array(),$page);
            }
        }

        Yii::app()->clientScript->registerScript('orderList', 'var cart = $(".cart-fixed");
			function setCartPosition() {
				cart.css({"top":($(window).height()/2)-(cart.height()/2)+"px"});
			}

			function clearForm() {
				var orderForm = $("#my-order-filter-form");
				$(":input","#my-order-filter-form")
					.not(":button, :submit, :reset, :hidden, :radio, :checkbox")
					.val("")
					.removeAttr("checked")
					.removeAttr("selected");
				$(\'input[name=\"Order[pay_method]\"][value=\"'.Order::PAY_ALL.'\"]\').prop("checked", true).trigger("change");
				$(\'input[name=\"Order[creator]\"][value=\"'.User::TYPE_ALL.'\"]\').prop("checked", true).trigger("change");
				orderForm.find("select.to-select2").each(function(){$(this).val(null).trigger("change");});
				orderForm.find("select.to-select2-ext").each(function(){$(this).val(null).trigger("change");});

			}

			$(document).on("click", "#cart-cancel-tickets", function(e){
				var selectedTickets = $(".oneTicket:checked");

				if (selectedTickets.length > 0) {
                    $.post("'.$this->createUrl("cashier/preCancelTickets").'",
                    {
                        data: "all"
                    }, function(result){
                        if(result == true) {
                            if(confirm("Ви дійсно бажаєте скасувати вибрані квитки?")){
                            $.post("'.$this->createUrl("order/cancelTickets").'",
                            {
                                data: "all"
                            }, function(result){
                                $.fn.yiiListView.update("orderList", {
                                    data:  $("#my-order-filter-form").serialize()
                                });
                                clearCart();
                                $(".cart-hide-button").click();
                            });
                            }
                        } else {
                            alert("Увага!\r\nВи не можете скасовувати квитки, які бронювали не ви!");
                        }
                    });

				}
			});

			$(document).on("click", "#printButton", function(e){
				setTimeout(refreshAll, 1000);
			});

			function refreshAll()
			{
				var selectedTickets = $(".oneTicket:checked");
				if (selectedTickets.length > 0) {
					clearCart();
				    $(".cart-hide-button").click();
				    $.fn.yiiListView.update("orderList", {
                            data:  $("#my-order-filter-form").serialize()
					});
				}
			}

			var cart_modal = $("#cart-edit-modal"),
				cart_panel = cart_modal.find("div.ticketsInfoPanel"),
				cart_loading = cart_modal.find(".loading");
			cart_panel.css({"display":"none"});
			cart_modal.on("show.bs.modal", function () {
				var _this = $(this);
				$.post("'.$this->createUrl("order/getTicketsEditInfo").'", function(result){
					cart_panel.html(JSON.parse(result));
					cart_loading.toggle();
					cart_panel.toggle();
				})
			});

			cart_modal.on("hidden.bs.modal", function(){
				var _this = $(this);
				cart_panel.toggle();
				cart_loading.toggle();

			});

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
				$.post("'.$this->createUrl("order/fromCart").'",
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
				$.post("'.$this->createUrl("order/clearCart").'",
				{
					data: "all"
				}, function(result){
					var info = JSON.parse(result);
					setCartInfo(info);
				})
			}

			setCartPosition();

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

			$(".saveFilter").on("click", function(e){
			    e.preventDefault();

                var name = $("#filterName").val(prompt("Введіть назву налаштування")),
                    form = $("#order-filter-form").serialize();

                if (name.length>0) {
                    $.post("'.$this->createUrl("order/saveOrderFilter").'",
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
                    $.post("'.$this->createUrl("order/deleteFilter").'",
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

			$(document).on("click", ".saveTicketsInfo", function(e){
			    var form = $("#cart-edit-form").serialize();
			    $.post("'.$this->createUrl("order/saveTicketsInfo").'",
			        {
			            data: form
			        }, function(result) {
                        cart_modal.modal("hide");
                        clearCart();
			        }
			    );
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
                    data:  $("#my-order-filter-form").serialize()
                })
            }
		', CClientScript::POS_READY);

        $this->render("orders", array(
            "dataProvider"=>$orders,
            "model"=>$model,
        ));
    }

    public function actionListEvent()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        $model = Event::model()->with('scheme')->findByPk($event_id);
        if (Yii::app()->request->isAjaxRequest) {
            $this->initCart("cartId_event_list");
            $positions = Yii::app()->shoppingCart->getPositions();
            if (!empty($positions))
                $positions = array_map(function($position){
                    return $position->id;
                },$positions);
            Scheme::getVisualInfo($model, false, false, $positions);
        }
        $cs = Yii::app()->clientScript;
        $js = "";
        if (Yii::app()->user->hasFlash("saved"))
            $cs->registerScript("flashMessage", '
                showAlert( "success","Замовлення #"+'.Yii::app()->user->getFlash("saved").'+" створено" , 0);
            ', CClientScript::POS_READY);
        if ($model) {
            $cs->registerScript("issetModel", '
                function clickInCartElements() {
                    $.post("'.$this->createUrl("getInCartIds").'", function(result){
                        var obj = JSON.parse(result),
                            elements = "";
                        for(var i=0; i<obj.length; i++) {
                            if (elements!="")
                                elements += ", "
                            elements += "#"+obj[i];
                        }

                        customSelectHendler = function(arrOfSelected){
                            return false;
                        };
                        window.editor.editorObj.setCustomSelectedElements($(elements));
                        customSelectHendler=undefined;
                    });
                }



                '.$js.'

            ', CClientScript::POS_BEGIN);
            $cs->registerScript("issetModelEvent", '
                $(".event-slider").css({"display":"none"});
                $(window).trigger("resize");
            ', CClientScript::POS_READY);
        }
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mousewheel.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/common.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.draggable.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/event_list.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.parser.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.export.min.js");

        $cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/reset.css");
        $cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/editor.css");
        $cs->registerScript("EventList", '
            function favorite(url, event_id) {
                $.fn.yiiListView.update("favorite-event-list", {
                        url: url,
                        data: {
                            event_id: event_id
                        }
                    });
            }

            function getEventInfo(id) {
                if (id) {
                    $.post("'.$this->createUrl("getEventInfo").'",
                        {
                            event_id: id
                        },function(result) {
                            var obj = JSON.parse(result),
                                favorites = $(".event-slider");
                            if (favorites.is(":visible"))
                                favorites.toggle();
                            $(".event-info").html(obj.info_block);
                            $(".event_info").css({"display":"block"});
                            getMap();
                    });
                }

            }

            $(".to_favorites").on("click", function(e){
                e.preventDefault();
                var event_id = $("#event_id").val();
                if (event_id)
                    favorite("'.$this->createUrl("addToFavorites").'", event_id);

            });

            $(document).on("click", ".from_favorites", function(e){
                e.preventDefault();
                favorite("'.$this->createUrl("deleteFromFavorites").'", $(this).attr("data-id"));
            });

            $(".show_event").on("click", function(e){
                e.preventDefault();
                getEventInfo($("#event_id").val());
            });

            $(document).on("click", "#favorite-event-list .item", function(e){
                e.preventDefault();
                $("#event_id").val($(this).attr("data-id"))
                $("#event-search").submit();
            });

            $(document).on("click", ".deleteFromCart", function(e){
            var _this = $(this);
                $.post("'.$this->createUrl('deleteFromCart').'",
                {
                    place_id: _this.attr("data-id"),
                    sector_id: _this.attr("data-sector_id"),
                    type: _this.attr("data-type")
                }, function(result){
                    var result = JSON.parse(result);
                    window.editor.editorObj.deselectCustomElementsSet($("#"+result.elements));
                    updateCart(result.cart);
                });
            });

            $(document).on("focusout", ".funCount", function(){
                var _this = $(this);
                $.post("'.$this->createUrl("addFunCount").'",
                {
                    data: JSON.stringify({
                        sector_id: _this.attr("data-sector_id"),
                        event_id: _this.attr("data-event_id"),
                        count: _this.val()
                    })
                }, function(result){
                    updateCart(result);

                });
            });

            $(document).on("click", ".clearCart", function(e){
                $.post("'.$this->createUrl('eventListClearCart').'", function(result){
                    window.editor.editorObj.deselectElements();
                    updateCart(result);
                });
            });

            $("#event_id").on("change", function (e){
                document.getElementById("event-search").submit();
            });

            $(document).on("click", ".saveAndPrint", function(e){
                e.preventDefault();
                $.post("'.$this->createUrl("saveAndPrint").'", function(result){
                    var obj = JSON.parse(result);
                    if (obj.msg) {
                        showAlert("danger", obj.msg);
                        updateCart(obj.cart);
                        refresh_map();
                        return
                    }
                    if (obj.order_id.length > 0) {
                        refresh_map();
                        $(document).find(".clearCart").trigger("click");
                        window.open("'.CController::createAbsoluteUrl("/order/order/printTickets").'?order_id="+obj.order_id+"&new=true","printWindow","width=screen.width,height=screen.height");
                    }
                });
            });

            $(".modal-footer").on("click", ".saveOrderButton", function(e){
                if ($(this).attr("id")=="save_print_order")
                    $("#typeSave").val("print")
                else
                    $("#typeSave").val("")
            });




        ', CClientScript::POS_READY);


        $favorites = $this->getFavorites();
        $places = $this->getPlacesInCart();

        $order = new Order();
        $order->ticketDeliveryType = Order::IN_KASA;
        $order->pay_method = Order::PAY_CASH;
//        $order->payment = Ticket::PAY_PAY;

        $events = Event::getListEvents(Event::STATUS_ACTIVE);

        $this->render("event",array(
            'events' => $events,
            'favorites'=>$favorites,
            'places'=>$places,
            'model'=>$model,
            'event_id'=>$event_id,
            'order'=>$order
        ));
    }

    private function initCart($name)
    {
        if (Yii::app()->user->getState('currentCartId') != $name)
            Yii::app()->user->setState("currentCartId", $name);
    }

    private function getFavorites()
    {
        return new CArrayDataProvider(User::getUserFavorites("Event"));
    }

    private function getPlacesInCart()
    {
        $this->initCart("cartId_event_list");
        $positions = Yii::app()->shoppingCart->getPositions();
        $places = array_map(function($place){
            if ($place->type==Place::TYPE_SEAT)
                return $place->id;
            else
                return false;
        },$positions);
        $places = Place::model()->with("sector", "sector.typeSector", "sector.typePlace", "sector.typeRow")->findAllByPk(array_filter($places));
        $funs = array_map(function($place){
            if ($place->type == Place::TYPE_FUN)
                return $place->id;
            else
                return false;
        },$positions);

        $funs = Sector::model()->with("typeSector")->findAllByPk(array_filter($funs));
        $places =array_merge($places, $funs);
        $result= array();
        foreach ($places as $place) {
            if ($place->type == Place::TYPE_FUN) {
                $position = Yii::app()->shoppingCart->itemAt($place->getId());
                $place->event_id = $position->event_id;
            }
            $event = Yii::app()->cache->get("cart_event_id_".$place->event_id);
            if ($event===false) {
                $event = Event::model()->with(array("scheme", "scheme.location", "scheme.location.city"))->findByPk($place->event_id);
                if ($event)
                    Yii::app()->cache->set("cart_event_id_".$event->id, $event, 30);
            }
            if (!isset($result[$place->event_id]) && !isset($result[$place->event_id]["event"])&& $event)
                $result[$place->event_id]["event"] = array(
                    "id"=>$event->id,
                    "name"=>$event->name,
                    "city"=>$event->scheme->location->city->name,
                    "start"=>$event->startTime
                );
            $result[$place->event_id]['places'][] = $place;
        }

        return $result;
    }

    public function actionGetInCartIds()
    {
        $this->initCart("cartId_event_list");
        $result = array();

        $places = array_map(function($place){
            if ($place instanceof Place) {
                return array(
                    "row"=>$place->row,
                    "place"=>$place->place,
                    "sector_id"=>$place->sector_id
                );
            } else {
                return array(
                    "row"=>0,
                    "place"=>0,
                    "sector_id"=>$place->id
                );
            }},Yii::app()->shoppingCart->getPositions());

        foreach ($places as $place) {
            $result[] = Sector::encodePlace($place);
        }

        echo json_encode($result);
    }

    public function actionPrint()
    {
        $this->render("print",array(

        ));
    }

    public function actionAddToFavorites()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        if ($event_id) {
            $model = Event::model()->findByPk($event_id);
            User::setUserFavorites($model);

            $this->renderPartial("favoritesBlock", array("dataProvider"=>$this->getFavorites()));

        }
    }

    public function actionDeleteFromFavorites()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        if ($event_id) {
            User::deleteUserFavorites("Event", $event_id);
            $this->renderPartial("favoritesBlock", array("dataProvider"=>$this->getFavorites()));
        }
    }

    public function actionGetEventInfo()
    {
        $event_id = Yii::app()->request->getParam('event_id');
        if ($event_id) {
            $event = Event::model()->with(array("scheme", "scheme.location", "scheme.location.city"))->findByPk($event_id);
            $result['info_block'] = $this->renderPartial("infoBlock", array("model"=>$event), true);
            $result['cart'] = $this->renderPartial("cartBlock", array("places"=>$this->getPlacesInCart()), true);
            echo json_encode($result);
        }
    }

    public function actionToCart()
    {
        $data = Yii::app()->request->getParam('data');
        if ($data) {
            $this->initCart("cartId_orderManager");
            $ids = array_map(function($ticket) {
                return $ticket['id'];
            }, $data);

            $tickets = Ticket::model()->findAllByAttributes(array("id"=>$ids, "status"=>array(Ticket::STATUS_SOLD, Ticket::STATUS_SEND_TO_EMAIL)));

            foreach ($tickets as $ticket) {
                if (Yii::app()->shoppingCart->contains($ticket->getId())) {
//					Yii::app()->shoppingCart->remove($ticket->getId());
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

    public function actionEventListToCart()
    {
        $place = Yii::app()->request->getParam("places");
        if ($place) {
            $place = json_decode($place);
            $this->initCart("cartId_event_list");
            if ($place->type == Place::TYPE_SEAT) {
                $place = Place::model()->findByPk((int)$place->id);
                if (Yii::app()->shoppingCart->contains($place->getId()))
                    Yii::app()->shoppingCart->remove($place->getId());
                else
                    Yii::app()->shoppingCart->put($place, 1, "cartId_event_list");
            } else {
                $event_id = $place->event_id;
                $count = Yii::app()->db->createCommand()
                    ->select("COUNT(*)")
                    ->from(Place::model()->tableName())
                    ->where("sector_id=:sector_id AND type=:type AND status=:status", array(
                        ":sector_id"=>$place->sector_id,
                        ":type"=>$place->type,
                        ":status"=>Place::STATUS_SALE
                    ))
                    ->queryScalar();
                if ($count>0) {

                    $place = Sector::model()->findByAttributes(array(
                        "id"=>$place->sector_id
                    ));
                    $place->detachBehavior("BeforeDeleteBehavior");
                    $place->event_id = $event_id;

                    if ($place)
                        if (Yii::app()->shoppingCart->contains($place->getId()))
                            Yii::app()->shoppingCart->remove($place->getId());
                        else {

                            Yii::app()->shoppingCart->put($place, 1, "cartId_event_list");
                        }
                }
            }
            $this->showCart();
        }
    }

    private function showCart()
    {

        echo json_encode($this->renderPartial("cartBlock", array("places"=>$this->getPlacesInCart()), true));
    }

    public function actionAddFunCount()
    {
        $data = Yii::app()->request->getParam("data");
        if ($data) {

            $this->initCart("cartId_event_list");
            $data = json_decode($data);
            $sector = Sector::model()->findByPk($data->sector_id);
            $sector->detachBehavior("BeforeDeleteBehavior");
            $sector->event_id=$data->event_id;
            if ($sector)
                Yii::app()->shoppingCart->update($sector, $data->count);
            $this->showCart();
        }
    }

    public function actionFromCart()
    {
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

    public function actionDeleteFromCart()
    {
        $place_id = Yii::app()->request->getParam("place_id");
        $sector_id = Yii::app()->request->getParam("sector_id");
        $position = false;
        $this->initCart("cartId_event_list");
        $element = "";
        if ($place_id) {
            $position = Place::model()->findByPk($place_id);
            $element = Sector::encodePlace(array(
                "row"=>$position->row,
                "place"=>$position->place,
                "sector_id"=>$position->sector_id
            ));
        }
        if ($sector_id&&!$place_id) {
            $position = Sector::model()->findByPk($sector_id);
            $element = Sector::encodePlace(array(
                "row"=>0,
                "place"=>0,
                "sector_id"=>$position->id
            ));

        }
        if ($position)
            if (Yii::app()->shoppingCart->contains($position->getId())) {
                $result['elements'] = $element;
                Yii::app()->shoppingCart->remove($position->getId());
                $result['cart'] = json_encode($this->renderPartial("cartBlock", array("places"=>$this->getPlacesInCart()), true));
                echo json_encode($result);
                Yii::app()->end();
            }
    }

    public function actionClearCart()
    {
        $this->initCart("cartId_orderManager");
        Yii::app()->shoppingCart->clear();
        $this->showCart();
        Yii::app()->end();
    }

    public function actionEventListClearCart()
    {
        $this->initCart("cartId_event_list");
        Yii::app()->shoppingCart->clear();
        $this->showCart();
        Yii::app()->end();
    }

    public function actionClose()
    {
        $cash = Yii::app()->request->getParam("KasaControl");
        $anyway = Yii::app()->request->getParam("applyAnyway");
        Yii::app()->user->setFlash("close", null);
        Yii::app()->user->setFlash("notClose", null);
        $model = new KasaControl();
        $model->unsetAttributes();
        if ($cash) {
            $this->performAjaxValidation($model);
            $model->attributes = $cash;

            $systemCash = KasaControl::calculateDayCash();
            $now = Yii::app()->dateFormatter->format("yyyy.MM.dd", time());
            $lastDate = Yii::app()->dateFormatter->format("yyyy.MM.dd", $systemCash["lastControl"]);
            if ($lastDate<$now)
                if ($anyway || $model->sum == $systemCash["sum"]) {
                    if ($model->save())
                        Yii::app()->user->setFlash("close", "Касу успішно закрито");

                    if ($anyway && $model->sum!=$systemCash["sum"])
                        $this->sendErrorMessage($model->sum, $systemCash["sum"]);
                } else
                    Yii::app()->user->setFlash("notClose", $systemCash['sum']);
        }

        Yii::app()->clientScript->registerScript("Close", '
            $(".repeatTry").on("click", function(e){
                e.preventDefault();
                $(".errorSum").css({"display":"none"});
                $(".form-wrapper").attr("style", "display:block");
            });

            $(".applySum").on("click", function(e){
                e.preventDefault();
                $("#applyAnyway").val("1");
                $("#close-form").submit();
            });
        ', CClientScript::POS_READY);

        $this->render("close", array(
            "model"=>$model,
            "anyway"=>$anyway
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param $model
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    private function sendErrorMessage($cash, $sum)
    {
        $role = Role::model()->findByPk(Role::getRoleId(Yii::app()->user->role));


        if ($role) {
            $admins = $role->getUserAdmin();
            $user = User::model()->findByPk(Yii::app()->user->id);

            $text = $this->renderPartial("error_mail", array(
                "user"=>$user,
                "cash"=>$cash,
                "sum"=>$sum,
            ), true);

            if (!empty($admins)) {
                $users = User::model()->findAllByPk($admins);
                foreach ($users as $admin)
                    User::mailsend($admin->email, Yii::app()->params['adminEmail'],"Увага!", $text);
            }

        }
    }

    public function actionControl()
    {


        $user_id = Yii::app()->request->getParam("user_id");
        $role_id = Yii::app()->request->getParam("role_id");
        if (!$role_id)
            $role_id = Role::getRoleId(Yii::app()->user->role);
        if (!$user_id)
            $user_id = Yii::app()->user->id;

        $cashiers = User::getUsersByRole($role_id);
        $role = Role::model()->findByPk($role_id);
        $admins = array();

        if ($role) {
            $ids = $role->getUserAdmin();
            $admins = User::model()->findAllByPk($ids);
        }

        $tickets = Yii::app()->db->createCommand()
            ->select("SUM(price), DATE(date_pay)")
            ->from(Ticket::model()->tableName())
            ->where("cash_user_id=:user_id AND cash_role_id=:role_id", array(
                ":user_id"=>$user_id,
                ":role_id"=>$role_id
            ))
            ->group("DATE(date_pay)")
            ->queryAll();

        $this->render("control", array(
            "role_id"=>$role_id,
            "user_id"=>$user_id,
            "cashiers"=>$cashiers,
            "admins"=>$admins
        ));
    }

    public function actionIsCartClear()
    {
        $this->initCart("cartId_event_list");
        $i=0;
        while (!Yii::app()->shoppingCart->isEmpty()) {
            if ($i==10)
                break;
            sleep(1);
            $i++;
        }
        echo json_encode(Yii::app()->shoppingCart->isEmpty());
    }

    public function actionSaveAndPrint()
    {
        $this->initCart("cartId_event_list");
        $ajax = Yii::app()->request->isAjaxRequest;
        $positions = Yii::app()->shoppingCart->getPositions();
        if (empty($positions))
            $this->redirect("listEvent");
        $model = new Order();
        $order = Yii::app()->request->getParam('Order');
        $save = Yii::app()->request->getParam('save_order');

        $userInfo = array(
            "surname"=>"",
            "name"=>"",
            "phone"=>"NULL",
            "email"=>"NULL"
        );
        if ($order) {
            $model->scenario = 'createNewOrder';
            $model->attributes = $order;
        }
        $this->performAjaxValidation($model);
        $funs = array_map(function($position){
            if ($position->type == Place::TYPE_FUN)
                return array(
                    "id"=>$position->id,
                    "quantity"=>$position->getQuantity(),
                    "price"=>$position->getPrice(),
                    "event_id"=>$position->event_id);
            else
                return false;
        }, $positions);
        $funs = array_filter($funs);
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (!empty($funs))
                foreach ($funs as $k=>$fun) {
                    $places = Place::model()->findAllByAttributes(array(
                        "sector_id"=>$fun['id'],
                        "price"=>$fun['price'],
                        "event_id"=>$fun['event_id'],
                        "status"=>Place::STATUS_SALE
                    ), array("limit"=>$fun['quantity']));
                    foreach ($places as $place)
                        Yii::app()->shoppingCart->put($place, 1, "cartId_event_list");
                    Yii::app()->shoppingCart->remove($k);
                }
            $check = Order::checkPlaceStatus(Yii::app()->shoppingCart->getPositions(), true);
            if (is_array($check)) {
                $transaction->rollBack();
                foreach ($check as $item)
                    Yii::app()->shoppingCart->remove($item);
                $result['cart'] = json_encode($this->renderPartial("cartBlock", array("places"=>$this->getPlacesInCart()), true));
                $result['msg'] = "Є зайняті місця, або створено тимчасове замовлення";
                echo json_encode($result);
                Yii::app()->end();
            }
            $model->type = Order::TYPE_ORDER;
            $model->user_id = Yii::app()->user->id;
            $model->role_id = Yii::app()->user->currentRoleId;
            $model->status = Order::STATUS_ACTIVE;
            $model->total = Yii::app()->shoppingCart->getCost();
            if ($model->save(false)) {

                $isCashPay = $model->pay_method == Order::PAY_CASH;
                switch($model->ticketDeliveryType) {
                    case Order::IN_KASA:
                        $model->ticketDeliveryType = $isCashPay ? Order::IN_KASA_PAY : Order::IN_KASA_ONLINE;
                        break;
                    case Order::NP:
                        $model->ticketDeliveryType = $isCashPay ? Order::NP_PAY : Order::NP_ONLINE;
                        break;
                    case Order::COURIER:
                        $model->ticketDeliveryType = $isCashPay ? Order::COURIER_PAY : Order::COURIER_ONLINE;
                        break;
                    default:
                        $model->ticketDeliveryType = Order::IN_KASA_PAY;
                        break;
                }

                $status = array(
                    "pay_type"=>$model->pay_method ? :Order::PAY_CASH,
                    "pay_status"=>$model->payment!=null? $model->payment:Ticket::PAY_PAY,
                    "delivery_status"=>$model->payment == Ticket::PAY_PAY&&$model->ticketDeliveryType==Order::IN_KASA ? Ticket::DELIVERY_RECIEVED : (!$save ? Ticket::DELIVERY_SENT : Ticket::DELIVERY_NOT_SENT),
                    "delivery_type"=>$model->ticketDeliveryType,
                    "status"=>Ticket::STATUS_SOLD
                );
                $positions = Yii::app()->shoppingCart->getPositions();
                Ticket::saveTickets($positions, $model, false, $status, array(), $userInfo);

                $placeIds = array_map(function($place){
                    return $place->id;
                }, $positions);
                if ($model->payment == null || $model->payment == Ticket::PAY_PAY)
                    Ticket::printTicketsGetCash(array_map(function($position){
                        return $position->id;
                    }, $positions), $model->id);
                Ticket::saveState($placeIds, true, $model->user_id);
                $transaction->commit();
                if (!$save&&!$ajax)
                    $this->redirect($this->createUrl("/order/order/printTickets", array("new"=>true, "order_id"=>$model->id)));
                elseif ($ajax) {
                    Yii::app()->shoppingCart->clear();
                    Yii::app()->user->setFlash("saved", $model->id);
                    echo json_encode(
                        array(
                            "order_id"=>$model->id
                        )
                    );
                } else {
                    Yii::app()->shoppingCart->clear();
                    Yii::app()->user->setFlash("saved", $model->id);
                    $this->redirect($this->createUrl("listEvent"));
                }
            }

        } catch (Exception $e) {
            new CHttpException($e->getCode(), $e->getMessage());
        }



    }

    public function actionStatistic()
    {
        $order = Yii::app()->request->getParam('Order');
        $cashier = Yii::app()->request->getParam('CashierPercent');

        $cashierPercent = new CashierPercent();
        $model = new Order('searchOrders');
        $event = new Event();
        $event->unsetAttributes();
        if (isset($cashier["statisticTypes"]))
            $cashierPercent->statisticTypes = $cashier["statisticTypes"];

        $is_admin = User::isAdminOfRole();
        $model->pay_method = Order::PAY_ALL;
        $model->creator = User::TYPE_USER;

        $orders = [];

        if($order) {
            $model->attributes = $order;

            $allowSeeAll = true;
            if(!$is_admin) {
                $user_id = Yii::app()->user->id;
                $role_id = Yii::app()->user->currentRoleId;
                $model->print_author = $user_id;
                $model->print_role = $role_id;
            }

            if($allowSeeAll)
                $orders = $model->searchOrders(false,false,false,true);
            else
                $orders = [];
        } else {
            $model->ticketStatus = Ticket::STATUS_SOLD;
            $model->type = [Order::TYPE_ORDER, Order::TYPE_QUOTE];
        }
        $statistic = new Statistic($orders,null,null,false);
        $cashierStatistic = $statistic->getCashierStatistic();

        Yii::app()->clientScript->registerScript("create", '
            $(".role").on("change", function(e){
				var _this = $(this);
				if (_this.val() == "")
					return;
				$.post("'.Yii::app()->createUrl("order/order/getUsers").'",
					{
						type: _this.attr("data-type"),
						role: _this.val()
					}, function(result) {
						$("."+_this.attr("data-type")+"_user").select2("destroy").html(result).select2();
					}
				);
			});

        ', CClientScript::POS_READY);
        $this->render("statistic", array(
            "model"=>$model,
            "event"=>$event,
            "cashierPercent"=>$cashierPercent,
            "cashierStatistic"=>$cashierStatistic,
            "is_admin"=>$is_admin,
        ));
    }

    public function actionPreCancelTickets()
    {
        $positions = Yii::app()->shoppingCart->getPositions();
        $user_id = Yii::app()->user->id;
        $role_id = Yii::app()->user->currentRoleId;
        foreach ($positions as $ticket) {
            if($ticket->user_id != $user_id || $ticket->role_id != $role_id) {
                echo 0;
                Yii::app()->end();
            }
        }
        echo 1;
        Yii::app()->end();
    }

}