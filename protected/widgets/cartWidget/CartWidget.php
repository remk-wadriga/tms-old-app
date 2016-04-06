<?php
/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 23.11.15
 * Time: 12:24
 */

Yii::import('booster.widgets.TbEditable');

class CartWidget extends CWidget
{
    public $positions;
    public $sum;
    public $count;
    public $event_id;
    public $model_fake;
    public function init()
    {
        $this->model_fake = new FakeActiveRecord;
        $this->model_fake->myid = 1;
        $this->model_fake->count = '1';
        $this->sum = Yii::app()->shoppingCart->getCost();
        $this->count = Yii::app()->shoppingCart->getCount();
        $this->event_id = Yii::app()->request->getParam('event_id');
    }
    public function run() {
        Yii::app()->clientScript->registerScript('cartScript', '
            var i, j,
                cart_places = [],
                last_id = [],
                select_fake = false;

            window.editor.editorObj.backToMacroAction = function(_super){
                return function() {
                    select_fake = true;
                    return _super.apply(this, arguments);
                }
            }(window.editor.editorObj.backToMacroAction);

            window.editor.editorObj.showSingleSector = function(_super){
                return function() {
                    if (arguments[1]) select_fake = true;
                    return _super.apply(this, arguments);
                }
            }(window.editor.editorObj.showSingleSector);

            customSelectHendler = function(arrOfSelected){

                if (select_fake) {
                    select_fake = false;
                    return;
                }

                var macro = false,
                    places = [],
                    places_id = [];

                if (window.editor.editorObj.isMicroView && window.editor.editorObj.hasMacro)
                    macro = true;

                if (arrOfSelected == null && !window.editor.editorObj.isMicroView) {
                    last_id = [];
                    var active_sector_id = window.editor.editorObj.activeSector;
                    for (i=0; i<cart_places.length; ++i) {
                        if (cart_places[i].sector_id==active_sector_id && macro) {
                            cart_places.splice(i,1);
                            i--;
                            continue;
                        }
                        if (!macro && !cart_places[i].macro) {
                            cart_places.splice(i,1);
                            i--;
                        }
                    }
                    addToCart(cart_places);
                }

                if (arrOfSelected == null && window.editor.editorObj.isMicroView) {
                    last_id = [];
                    var active_sector_id = window.editor.editorObj.activeSector;
                    for (i=0; i<cart_places.length; ++i) {
                        if (cart_places[i].sector_id==active_sector_id && macro) {
                            cart_places.splice(i,1);
                            i--;
                            continue;
                        }
                    }
                    addToCart(cart_places);
                }

                if($(arrOfSelected).length > 0){
                    if(!$(arrOfSelected.node).length){
                        $(arrOfSelected).each(function(){
                            var _this = $(this),
                                placeData = {
                                    id: _this.attr("id"),
                                    sector_id: _this.attr("sector_id"),
                                    row_id: _this.attr("id").match(/[0-9]+(?=col)/)[0],
                                    place_id: _this.attr("id").match(/[0-9]+(?=sector)/)[0],
                                    price: _this.data("price"),
                                    server_id: _this.data("server_id"),
                                    type: _this.data("type"),
                                    macro: macro
                                };
                            var ttt = _this.attr("id");
                            places.push(placeData);
                            places_id.push(_this.attr("id"));
                        });
                    }else{
                        var _this = $(arrOfSelected.node),
                            placeData = {
                                id: _this.attr("id"),
                                sector_id: _this.attr("sector_id"),
                                row_id: _this.attr("id").match(/[0-9]+(?=col)/)[0],
                                place_id: _this.attr("id").match(/[0-9]+(?=sector)/)[0],
                                price: _this.data("price"),
                                server_id: _this.data("server_id"),
                                type: _this.data("type"),
                                macro: macro
                            };
                        places.push(placeData);
                        places_id.push(_this.attr("id"));
                    }
                    if (!($(last_id).not(places_id).length === 0) || !($(places_id).not(last_id).length === 0)) {
                        var active_sector_id = window.editor.editorObj.activeSector;
                        for (i=0; i<cart_places.length; ++i) {
                            if (cart_places[i].sector_id==active_sector_id && macro) {
                                cart_places.splice(i,1);
                                i--;
                                continue;
                            }
                            if (!macro && !cart_places[i].macro) {
                                cart_places.splice(i,1);
                                i--;
                            }
                        }
                        for (i=0; i<places.length; ++i) {
                            var mit = false;
                            for (j=0; j<cart_places.length; ++j) {
                                if (places[i].id==cart_places[j].id) {
                                    mit = true;
                                    break;
                                }
                            }
                            if (!mit) cart_places.push(places[i]);
                        }
                        last_id = places_id;
                        addToCart(cart_places);
                    }
                }
            }
            customMicroModeLoadedListener = function(sectId, sectEl){
                neededEl = $();
                for (i=0; i<cart_places.length; i++)
                    if (cart_places[i].sector_id==parseInt(window.editor.editorObj.activeSector))
                        neededEl = neededEl.add($(\'#\'+cart_places[i].id));
                if (neededEl.length>0) window.editor.editorObj.setCustomSelectedElements(neededEl);
            }
            $(document).on("click","#backToMacroBtn",function(){
                neededEl = $();
                for (i=0; i<cart_places.length; i++)
                    neededEl = neededEl.add($(\'#\'+cart_places[i].id));
                if (neededEl.length>0) window.editor.editorObj.setCustomSelectedElements(neededEl);
            });
            customDeselectBtnHandler = function(arrOfSelected) {
                if (window.editor.editorObj.isMicroView && window.editor.editorObj.hasMacro) {
                    var active_sector_id = window.editor.editorObj.activeSector;
                    for (i=0; i<cart_places.length; ++i)
                        if (cart_places[i].sector_id==active_sector_id) {
                            cart_places.splice(i,1);
                            i--;
                        }
                    addToCart(cart_places);
                } else {
                    for (i=0; i<cart_places.length; ++i)
                        if (!cart_places[i].macro) {
                            cart_places.splice(i,1);
                            i--;
                        }
                    addToCart(cart_places);
                }

            }
            function addToCart(places){
                $(".fanzone-input").each(function(){
                    for	(var i = 0; i < places.length; i++) {
                        if (parseInt(places[i].sector_id)==parseInt($(this).data("sector-id"))) {
                            places[i].count = parseInt($(this).html());
                        }
                    }
                });
                $("#cart-content").html("<div class=\"loading\"><i class=\"fa fa-spinner fa-spin\"></i></div>");
                $.post("'.Yii::app()->controller->createUrl("/order/quote/placeToCart").'",
                    {
                        places: JSON.stringify(places),
                        event_id: '.$this->event_id.',
                    }, function(result) {
                        var obj = JSON.parse(result);
                        $("#cart-content").html(obj.html);
                        $("#quote_cart_sum").val(obj.sum);
                        if (parseInt(obj.count)!=0){
                            $(".cart-widget .header .total .count").html(obj.count+" шт.");
                            $(".cart-widget .header .total .sum").html(obj.sum+" грн.");
                        } else {
                            $(".cart-widget .header .total .count").html("");
                            $(".cart-widget .header .total .sum").html("");
                        }
                    }
                );
            }
            $(".cart-widget").on("click", ".item .delete .fa", function(){
                $("#cart-content").html("<div class=\"loading\"><i class=\"fa fa-spinner fa-spin\"></i></div>");
                var event_id = $(this).parent().attr("data-event-id"),
                    sector_id = $(this).parent().attr("data-sector-id"),
                    row_id = $(this).parent().attr("data-row-id"),
                    price = $(this).parent().attr("data-price");
                $.post("'.Yii::app()->controller->createUrl("/order/quote/deleteToCart").'",
                    {
                        event_id: event_id,
                        sector_id: sector_id,
                        row_id: row_id,
                        price: price,
                    }, function(result) {
                        var obj = JSON.parse(result);
                        $("#cart-content").html(obj.html);
                        for (i=0; i<cart_places.length; ++i) {
                            if (cart_places[i].sector_id==sector_id && cart_places[i].row_id==row_id && cart_places[i].price==price) {
                                cart_places.splice(i,1);
                                i--;
                            }
                        }
                        window.editor.editorObj.deselectCustomElementsSet($("[id^=row"+row_id+"col][id$=sector"+sector_id+"][data-price="+price+"]"));
                        last_id = [];
                        $("#quote_cart_sum").val(obj.sum);
                        if (parseInt(obj.count)!=0){
                            $(".cart-widget .header .total .count").html(obj.count+" шт.");
                            $(".cart-widget .header .total .sum").html(obj.sum+" грн.");
                        } else {
                            $(".cart-widget .header .total .count").html("");
                            $(".cart-widget .header .total .sum").html("");
                        }
                    }
                );
            });
        ', CClientScript::POS_LOAD);

        $this->render("index", array(
            "items" => array(),
            "event_id" => $this->event_id,
            "model_fake" => $this->model_fake
        ));
    }
}