function EditQuoteConstructor(){
    var editQuoteConstructor = {

        addSoldBtn: $('#addSold'),
        returnInSaleBtn: $('#returnInSale'),
        returnSoldBtn: $('#returnSold'),
        typeReturnRadio: $('[name="typeReturn"]'),
        quote_id: $('#quote_id').val(),
        amountInp: $('#amount'),
        selectAllChbx: $('#selectAll'),
        changePriceBtn: $('#change_price'),
        passPlacesBtn: $('#passPlaces'),

        IDD_NOT_SOLD_STATUS: 1,
        IDD_FAN_ZONE: 2,
        AddSoldActionId: 10,
        ReturnInSaleActionId: 11,
        ReturnSoldActionId: 12,



        // initialize action for add as sold action
        initAddSoldAction: function(){
            if (this.addSoldBtn.length > 0){
                this.addSoldBtn.on('click', function(){
                    editQuoteConstructor.editQuoteAction(editQuoteConstructor.AddSoldActionId);
                })
            }
        },

        // initialize action for returning in sale action
        initReturnInSaleAction: function(){
            if (this.returnInSaleBtn.length > 0){
                this.returnInSaleBtn.on('click', function(){
                    editQuoteConstructor.editQuoteAction(editQuoteConstructor.ReturnInSaleActionId);
                })
            }
        },

        // initialize action for returning sold elements
        initReturnSoldAction: function(){
            if (this.returnSoldBtn.length > 0){
                this.returnSoldBtn.on('click', function(){
                    editQuoteConstructor.editQuoteAction(editQuoteConstructor.ReturnSoldActionId);
                })
            }
        },

        editQuoteAction: function(action_id){
            var places = [],
                selected = $(".current"),
                allowRequest = true,
                amountVal = editQuoteConstructor.amountInp.val();
            selected.each(function(){
                var _this = $(this);
                var placeData = {};
                if (_this.data("type") == editQuoteConstructor.IDD_FAN_ZONE){
                    if (amountVal == null || amountVal.trim().length == 0 || isNaN(amountVal)){
                        editQuoteConstructor.amountInp.trigger('focus');
                        allowRequest = false;
                        return false;
                    }
                    placeData.sector_id = _this.attr("sector_id");
                    placeData.count = amountVal;
                }else{
                    placeData.server_id = _this.data("server_id");
                }
                places.push(placeData);
            });
            if (allowRequest) {
                window.editor.editorObj.deselectElements();
                if (places.length > 0) {
                    var url;
                    switch (action_id){
                        case this.AddSoldActionId:
                            url = editQuoteConstructor.addSoldBtn.data('url');
                            break;
                        case this.ReturnInSaleActionId:
                            url = editQuoteConstructor.returnInSaleBtn.data('url');
                            break;
                        case this.ReturnSoldActionId:
                            url = editQuoteConstructor.returnSoldBtn.data('url');
                            break;
                        default:
                            console.log('url not defined');
                    }
                    if (url.length > 0){
                        var postData = {
                            places: JSON.stringify(places),
                            quote_id: editQuoteConstructor.quote_id
                        };
                        if (action_id == this.ReturnSoldActionId){
                            postData.typeReturn = $('[name="typeReturn"]:checked').val();
                        }
                        $.post(
                            url,
                            postData,
                            function (result) {
                                var result = JSON.parse(result);

                                editQuoteConstructor.editElementsData(result);
                            }
                        );
                        editQuoteConstructor.amountInp.val("");
                    }
                } else {
                    showAlert("danger", "Немає вибраних місць");
                    console.log('Nothing selected');
                }
            }
        },

        // test

        editElementsData: function(result, showMessage){
            if (result.length > 0){
                showMessage = typeof showMessage !== 'undefined' ? showMessage : true;
                for (var i=0;i < result.length; i++){
                    var el = $('#'+result[i].id );
                    if (el.length>0){
                        el = el[0].instance;
                        if (result[i].selectable){
                            el.data('selectable', result[i].selectable);
                        }else{
                            el.data('selectable', 'false');
                        }
                        if (result[i].sold){
                            el.data('sold', result[i].sold);
                        }
                        if (result[i].price){
                            el.data('price', result[i].price);
                        }
                        if (result[i].status){
                            el.data('status', result[i].status);
                        }
                        if (result[i].label){
                            el.data('label', result[i].label);
                        }
                        if (result[i].fill){
                            el.attr('fill', result[i].fill);
                        }
                    }
                }
                if(showMessage)
                    showAlert("success", "Успішно збережено");
            }
        },

        // select all not sold elements with status 1
        initSelectAllAction: function(){
            if (this.selectAllChbx.length > 0){
                this.selectAllChbx.on('change', function(){
                    editQuoteConstructor.slectAllAction(editQuoteConstructor.selectAllChbx);
                });
            }
        },

        slectAllAction: function(chbx){
            if (chbx.is(':checked')){
                var elements = $('svg [data-status="'+editQuoteConstructor.IDD_NOT_SOLD_STATUS+'"][data-selectable=true]');
                window.editor.editorObj.setCustomSelectedElements(elements);
            }else{
                window.editor.editorObj.deselectElements();
            }
        },

        initChangePriceAction: function(){
            if (this.changePriceBtn.length > 0){
                this.changePriceBtn.on('click', function(){
                    editQuoteConstructor.changePrice();
                });
            }else{
                delete this.changePriceBtn;
            }
        },
        changePrice: function(){
            var places = [],
                changePriceInpVal = $('#price').val(),
                selected = $(".current");
            selected.each(function(){
                var _this = $(this);
                var placeData = {};
                if (_this.data("type") == editQuoteConstructor.IDD_FAN_ZONE){
                    placeData.sector_id = _this.attr("sector_id");
                    placeData.count = parseFloat(_this.data('count')) - parseFloat(_this.data('sold'));
                }else{
                    placeData.server_id = _this.data("server_id");
                }
                places.push(placeData);
            });
            if (places.length > 0){
                if (changePriceInpVal == ""){
                    $('#price').trigger('focus');
                    return false;
                }
                window.editor.editorObj.deselectElements();
                var url = this.changePriceBtn.data('url');
                if (url.length > 0){
                    var postData = {
                        places: JSON.stringify(places),
                        quote_id: editQuoteConstructor.quote_id,
                        price: changePriceInpVal,
                        onScheme: $('#onScheme').is(':checked')
                    };
                    $.post(
                        url,
                        postData,
                        function (result) {
                            var result = JSON.parse(result);
                            if(result == "error")
                                showAlert("danger","Для місць які відмічені проданими ціну змінити не можна!");
                            else
                                showAlert("success","Ціну успішно змінено");
                            editQuoteConstructor.editElementsData(result, false);
                        }
                    );
                }
            }
        },

        initPassPlacessAction: function(){
            if (this.passPlacesBtn.length > 0){
                this.passPlacesBtn.on('click', function(){
                    editQuoteConstructor.passPlaces($(this));
                });
            }else{
                delete this.passPlacesBtn;
            }
        },

        passPlaces: function(){
            var places = [],
                typePassVal = $('[name="typePass"]:checked').val(),
                passPlaceContractorsVal = $('#passPlaceContractors').val(),
                selected = $(".current");
            selected.each(function(){
                var _this = $(this);
                var placeData = {};
                if (_this.data("type") == editQuoteConstructor.IDD_FAN_ZONE){
                    placeData.sector_id = _this.attr("sector_id");
                    placeData.count = parseFloat(_this.data('count')) - parseFloat(_this.data('sold'));

                }else{
                    placeData.server_id = _this.data("server_id");
                }
                placeData.type = _this.data("type");
                places.push(placeData);
            });
            if (places.length > 0){
                window.editor.editorObj.deselectElements();
                var url = this.passPlacesBtn.data('url');
                if (url.length > 0){
                    var postData = {
                        places: JSON.stringify(places),
                        quote_id: editQuoteConstructor.quote_id,
                        typePass: typePassVal,
                        passPlaceContractors: passPlaceContractorsVal
                    };
                    $.post(
                        url,
                        postData,
                        function (result) {
                            var result = JSON.parse(result);
                            if (result.msg == "redirect_url")
                                window.location.replace(result.url);
                            editQuoteConstructor.editElementsData(result);
                        }
                    );
                }
            }
        },

        init: function(){
            this.initAddSoldAction();
            this.initReturnInSaleAction();
            this.initReturnSoldAction();
            this.initSelectAllAction();
            this.initChangePriceAction();
            this.initPassPlacessAction();
            return this;
        }
    }
    return editQuoteConstructor.init();
}
