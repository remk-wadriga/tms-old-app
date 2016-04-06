function QuoteConstructor(){
    var quoteConstructor = {
        addToCartBtn: $("#addToCart"),
        contractors_block: $('#cart_contractors'),
        addContractorBtn: $('#addContractorBtn'),
        checkOrderBtn: $("#addOrderToCart"),
        contractor: $("#contractor_id"),
        eventId: $("#event_id").val(),
        accordion: $("#accordion"),
        saveTabs: $("#saveTabs"),
        amountInp: $('#amount'),
        funInp: $('#fun_price'),
        amountInfoBlock: $('#amount_info_block'),
        IDD_FAN_ZONE: 2,

        setAddContractorAction: function(){
            this.addContractorBtn.on("click", function(){
                var _this = $(this),
                    contractor_id = quoteConstructor.contractor.val(),
                    contractor_name = quoteConstructor.contractor.find("option:selected").text();
                if (contractor_id != "")
                    $.post(
                        _this.data("url"),
                        {
                            contractor_id: contractor_id,
                            event_id : quoteConstructor.eventId
                        }, function(result) {
                            var obj = JSON.parse(result);
                            if (obj != "error") {
                                //додаєм у правий баян блок контрагента
                                quoteConstructor.accordion.append(obj.result);
                                //список контрагентів при кліку на кнопку "В кошик"
                                var contrEl = $("<li data-value=\'"+contractor_id+"\' id=\'cartContractor"+contractor_id+"\'>"+contractor_name+"</li>");
                                quoteConstructor.contractors_block.find("ul").append(contrEl);
                                contrEl.on('click', function(){
                                    quoteConstructor.addPlaces($(this));
                                });
                                //У модальне вікно дадаєм таби
                                quoteConstructor.saveTabs.find("ul").append( "<li role=\'presentation\' class=\'active\' id=\'control_"+contractor_name+"\'><a href=\'#"+contractor_name+"\' aria-controls=\'"+contractor_name+"\' role=\'tab\' data-toggle=\'tab\'>"+contractor_name +"</a></li>")
                                quoteConstructor.saveTabs.find(".tab-content").append(" <div role=\'tabpanel\' class=\'tab-pane active\' id=\'"+contractor_name+"\'>"+obj.form+"</div>")
                                //ітс е меджік, без цього курва не робе
                                quoteConstructor.saveTabs.tabs("refresh");
                                quoteConstructor.saveTabs.find("ul li").each(function(){
                                    $(this).find("a").click();
                                    return false;
                                });
                                //видаляєм з дропдауна контрагента
                                quoteConstructor.contractor.find("option[value="+contractor_id+"]").remove();
                            }
                        }
                    );
            });
        },
        addPlaces: function(contractor){
            var places = [],
                allowRequest = true;
            $(".current").each(function(){
                var _this = $(this);
                quoteConstructor.contractors_block.hide();
                var placeData = {
                    id: _this.attr("id"),
                    sector_id: _this.attr("sector_id"),
                    price: _this.data("price"),
                    server_id: _this.data("server_id"),
                    type: _this.data("type")
                };
                if (_this.data("type") == quoteConstructor.IDD_FAN_ZONE){
                    var amountVal = quoteConstructor.amountInp.val(),
                        priceVal = quoteConstructor.funInp.val();
                    if (amountVal == null || amountVal.trim().length == 0 || isNaN(amountVal)){
                        quoteConstructor.amountInp.trigger('focus');
                        allowRequest = false;
                        return false;
                    }
                    if (priceVal.trim().length != 0 && !isNaN(priceVal)) placeData.price = priceVal;
                    placeData.amount = amountVal;
                }
                places.push(placeData);
            });
            if (allowRequest){
                $(".current").each(function() {
                    if (this.instance.data("type") != quoteConstructor.IDD_FAN_ZONE) {
                        this.instance.data("selectable", "false");
                        this.instance.attr("fill", "#05ca25");
                    }
                    this.instance.removeClass("current").removeClass('clicked');
                });
                window.editor.editorObj.deselectElements();
                var contractor_id = contractor.data("value");
                $.post(
                    quoteConstructor.addToCartBtn.data('url'),
                    {
                        places: JSON.stringify(places),
                        contractor: contractor_id,
                        event_id: quoteConstructor.eventId
                    }, function(result) {
                        var result = JSON.parse(result);
                        //console.log(result);
                        //справа оновлюєм блочок контрагента
                        $("#collapse"+contractor_id).html(result.collapseBlock);
                        //там само оновлюємо кількість і суму
                        $("#totalSum_"+contractor_id).val(result.sum);
                        $("#heading"+contractor_id+" .sum").html(result.sum);
                        $("#heading"+contractor_id+" .count").html(result.count);
                        if (result.places){
                            var plLength = result.places.length;
                            if (plLength > 0){
                                for (var i=0; i < plLength;i++){
                                    if (result.places[i].fun_zone){
                                        var neededEl = $('#'+ result.places[i].id);
                                        if (neededEl.length > 0){
                                            neededEl = neededEl[0].instance;
                                            var priceInfo = result.places[i].price_info;

                                            if (priceInfo && !$.isEmptyObject(priceInfo)){
                                                neededEl.data({
                                                    'amount': priceInfo.amount,
                                                    'count': priceInfo.count,
                                                    'sold': priceInfo.sold_count
                                                });
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                );
                quoteConstructor.amountInp.val("");
                quoteConstructor.funInp.val("");
            }
        },
        initializeSetPlacesAction: function(){

            this.contractors_block.find("ul li").each(function(){
                var _this = $(this);
                _this.on('click', function(){
                    quoteConstructor.addPlaces($(this));
                });
            });
        },
        setAddToCartAction: function(){
            this.addToCartBtn.on("click", function(e){
                if (quoteConstructor.contractors_block.find("li").length > 0)
                    quoteConstructor.contractors_block.toggle();
            });
        },
        setCheckOrderAction: function() {
            this.checkOrderBtn.on("click", function(e){
                quoteConstructor.checkOrder();
            });
        },
        checkOrder: function(ignoreSold) {
            $.post(
                this.checkOrderBtn.data("url"),
                {
                    order_id : $("#orderToCart").val(),
                    event_id : $("#event_id").val(),
                    ignoreSold: ignoreSold
                }, function(result) {
                    var obj = JSON.parse(result);
                    if (obj.msg != '' && obj.alert == false) {
                        if (confirm(obj.msg)) {
                            quoteConstructor.checkOrder(true);
                        }
                    } else if (obj.alert != false) {
                        showAlert(obj.alert, obj.msg);
                    } else if (obj.places!=[]) {
//тут я повертаю масив id на які треба зробити клас current, щоб при кліку "в кошик" ми змогли їх відправити контрагенту
                    }
                }
            );
        },
        initTabs: function(){
            quoteConstructor.saveTabs.tabs();
        },

        // delete all info from chart container
        deleteFromCart: function(contractor){
            $.post(
                quoteConstructor.addContractorBtn.data('url'),
            {
                contractor_id: contractor,
                event_id : quoteConstructor.eventId,
                delete : true
            }, function(result) {
                var result = JSON.parse(result);
                //респонс такого формату {deleted:true, enableSelect: [{id:row2col2sector22, fill:"#09823" selectable:bool}]}
                if (result.deleted) {
                    var ids = result.enableSelect;
                    for (i=0; i<ids.length;i++) {
                        $("#"+ids[i].id).attr({
                            "fill": ids[i].fill,
                            "data-selectable": ids[i].selectable
                        });
                    }
                    //видаляєм зпід кнопки "в кошик" нашого контрагента
                    quoteConstructor.contractors_block.find("ul li").each(function(){
                        if ($(this).data("value") == contractor)
                            $(this).remove();
                    });
                    var contractor_name = $("#heading"+contractor+" a:first span:first").text();
                    $("#heading"+contractor).parent().remove();
                    $("#control_"+contractor_name.trim()).remove();
                    $("#"+contractor_name.trim()).remove();
                    //на цьому моменті мав би ховатись блок контрагентів
                    quoteConstructor.contractors_block.css({"display":"none"});
                    //вписуєм назад у дропдаун контрагента
                    if (quoteConstructor.contractor.find("option[value=\'"+contractor+"\']").length == 0)
                        quoteConstructor.contractor.append("<option value=\'"+contractor+"\'>"+contractor_name+"</option>")
                }
            }
            )
        },
        // this handler fires when save quote form passed validation
        afterValidate: function(form, data, hasError,url,contractor_id){
            var list = quoteConstructor.saveTabs.find('ul li');
            console.log(list);
            if (list && list.length >1) {
                if (!hasError) {
                    $.post(
                        url,
                        $('#saveQuoteForm'+contractor_id).serialize(),
                        function(result) {
                            console.log(list);
                            list.each(function(){
                                $(this).find('a').click();
                                return false;
                            });
                            quoteConstructor.deleteFromCart(contractor_id);
                        }
                    );
                }
            }
            else
                return true;
        },

        init: function(){
            this.initTabs();
            this.setAddContractorAction();
            this.initializeSetPlacesAction();
            this.setAddToCartAction();
            this.setCheckOrderAction();
            return this;
        }
    }
    return quoteConstructor.init();
}
