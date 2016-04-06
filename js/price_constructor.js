function PriceConstructor(){
    var priceConstructor = {

        // constants
        IDD_DEFAULT_SEAT: 1,
        IDD_FAN_ZONE: 2,
        IDD_ALL: 0,

        panelCont: $('.filter').first(),

        amountInfoBlock: $('#amount_info_block'),
        selectedInfoBlock: $('#selected_info_cont'),
        selectedRowBlock: $('#select_row_info'),
        selectedSeatAmount: $('#select_seat_amount'),
        selectedPriceAmount: $('#select_price_amount'),

        // select specific price
        selectByPrice: function(priceControl){
            var neededPrice = priceControl.data('price');
            var neededType = priceControl.data('type');
            if (neededPrice != null && neededType != null){
                var priceElements;
                if (neededType == this.IDD_ALL){
                    priceElements = $('svg [data-price="'+neededPrice+'"]');
                }else{
                    priceElements = $('svg [data-type="'+neededType+'"][data-price="'+neededPrice+'"]');
                }
                if (priceElements.length > 0){
                    window.editor.editorObj.setCustomSelectedElements(priceElements);
                    this.checkAmountBlockStatus(window.editor.editorObj.currentElement);
                    this.countSelectedElements(window.editor.editorObj.currentElement);
                }else{
                    console.log("Не знайдено місць/фан-зон з ціною " + neededPrice);
                }
            }else if (neededPrice == null){
                var allElements = $('.rect');
                var priceElements = [];
                if (allElements.length > 0){
                    allElements.each(function(){
                        if ($(this).data('type') != null && $(this).data('price') == null){
                            priceElements.push(this);
                        }
                    });
                }
                if (priceElements.length > 0){
                    console.log(priceElements);
                    window.editor.editorObj.setCustomSelectedElements($(priceElements));
                    this.checkAmountBlockStatus(window.editor.editorObj.currentElement);
                    this.countSelectedElements(window.editor.editorObj.currentElement);
                }
            }
        },

        // select specific sector
        selectBySector: function(sectorControl){
            var neededSector = sectorControl.data('sector_id');
            if (neededSector != null){
                var sectorElements = $();
                if (neededSector == "all"){
                    $('[data-sector_id]').not(sectorControl).each(function(){
                        var tmpEl = $('[sector_id="'+$(this).data('sector_id')+'"]');
                        if (tmpEl.length > 0){
                            sectorElements = sectorElements.add(tmpEl);
                        }
                    });
                }else{
                    sectorElements = $('[sector_id="'+neededSector+'"]');
                }
                if (sectorElements.length > 0){
                    window.editor.editorObj.setCustomSelectedElements(sectorElements);
                    this.checkAmountBlockStatus(window.editor.editorObj.currentElement);
                    this.countSelectedElements(window.editor.editorObj.currentElement);
                }else{
                    console.log("Не знайдено місць для сектора " + neededSector);
                }
            }
        },

        // find and initialize price controls in side panel
        definePriceControls: function(){
            this.priceInfoBlocks = this.panelCont.find('.price-block');
            if (this.priceInfoBlocks.length > 0){
                this.priceInfoBlocks.each(function(){
                    var priceInfoBlock = $(this);
                    var priceControl = priceInfoBlock.find('a');
                    if (priceControl.length > 0){
                        priceControl.on('click',function(e){
                            e.preventDefault();
                            priceConstructor.selectByPrice($(this));
                        });
                    }
                });
            }
        },

        // find and initialize sector controls in side panel
        defineSectorsControls: function(){
            this.sectorInfoBlocks = this.panelCont.find('.sector-block');
            if (this.sectorInfoBlocks.length > 0){
                this.sectorInfoBlocks.each(function(){
                    var sectorInfoBlock = $(this);
                    var sectorControl = sectorInfoBlock.find('a');
                    if (sectorControl.length > 0){
                        sectorControl.on('click',function(e){
                            e.preventDefault();
                            priceConstructor.selectBySector($(this));
                        });
                    }
                });
            }
        },

        // register setting price to selected elements
        setPriceAction: function(){
            var price = $('#price'),
                event_id = $('#event_id'),
                typeSet = $('#typeSet');
            this.setPriceBtn = $('#setPrice');
            if (this.setPriceBtn.length >0){
                this.setPriceBtn.on('click', function(e) {
                    e.preventDefault();
                    var setPrice = true;
                    var selectedPlaces = $('svg .current').not('#scene');
                    if (price.length > 0 && selectedPlaces.length > 0) {

                        var places = [];
                        selectedPlaces.each(function(){
                            var _this = $(this);
                            var elType = _this.data('type');
                            var ElSectorId = _this.attr('sector_id');
                            if (elType == priceConstructor.IDD_FAN_ZONE){
                                var ElCount = _this.data('count');
                                if (ElCount == null || ElCount == ""){
                                    alert("Встановіть кількість місць для продажу для сектора " + ElSectorId);
                                    setPrice = false;
                                    return;
                                }
                            }
                            places.push({
                                id : _this.attr('id'),
                                sector_id : ElSectorId,
                                server_id : _this.data('server_id'),
                                type : elType
                            });
                        });

                        if (setPrice){
                            var postUrl = priceConstructor.setPriceBtn.data('url');
                            if (postUrl != null && postUrl != "" && postUrl.length > 0){
                                $.post(postUrl,
                                    {
                                        data : JSON.stringify({
                                            event_id: event_id.val(),
                                            price: price.val(),
                                            typeSet: typeSet.val(),
                                            places: places
                                        })
                                    }, function(result) {
                                        //console.log(result);
                                        var response = JSON.parse(result);
                                        var success = response.success;
                                        if (success){
                                            var responseLength = response.prices.length;
                                            if (responseLength > 0){
                                                showAlert('success', 'Ціну успішно встановлено!');
                                                price.val('');
                                                //selectedPlaces.attr('class','native');
                                                window.editor.editorObj.deselectElements();
                                                for (var i=0; i < responseLength;i++){
                                                    priceConstructor.updateElementsData(response.prices[i]);
                                                }
                                            }else{
                                                console.log("Price is not changed");
                                            }
                                            priceConstructor.countSelectedElements(selectedPlaces);
                                            if (!$.isEmptyObject(response.price_block)){
                                                var priceBlock = $('.prices');
                                                if (priceBlock.length > 0){
                                                    priceBlock.html(response.price_block);
                                                    priceConstructor.definePriceControls();
                                                }
                                            }
                                            if (!$.isEmptyObject(response.totalInfo))
                                                priceConstructor.updateCountBlock(response.totalInfo);
                                        }else{
                                            var errorMsg = response.msg;
                                            if (errorMsg != null && errorMsg.length > 0){
                                                showAlert('danger', errorMsg);
                                            }
                                        }
                                    }
                                );
                            }else{
                                console.log("Set price url missed");
                            }
                        }
                    }else{
                        console.log("Setting price failed");
                    }
                });
            }
        },
        updateCountBlock: function(totalInfo) {
            $(".countAll").html(totalInfo.countAll);
            $(".countWithPrice").html(totalInfo.countWithPrice);
            $(".sumAll").html(totalInfo.sumAll);
        },

        // register deleting price from selected elements
        deletePriceAction: function(){
            var price = $('#price'),
                event_id = $('#event_id');
            this.delPriceBtn = $('#delPrice');
            if (this.delPriceBtn.length >0){
                this.delPriceBtn.on('click', function(e) {
                    e.preventDefault();
                    var selectedPlaces = $('svg .current').not('#scene');
                    if (selectedPlaces.length > 0) {

                        var places = [];
                        selectedPlaces.each(function(){
                            var _this = $(this);
                            var elType = _this.data('type');
                            var ElSectorId = _this.attr('sector_id');
                            places.push({
                                id : _this.attr('id'),
                                sector_id : ElSectorId,
                                server_id : _this.data('server_id'),
                                type : elType
                            });
                        });

                        var postUrl = priceConstructor.delPriceBtn.data('url');
                        if (postUrl != null && postUrl != "" && postUrl.length > 0){
                            $.post(postUrl,
                                {
                                    data : JSON.stringify({
                                        event_id: event_id.val(),
                                        places: places
                                    })
                                }, function(result) {
                                    //console.log(result);
                                    var response = JSON.parse(result);
                                    var success = response.success;
                                    if (success){
                                        var responseLength = response.ids.length;
                                        if (responseLength > 0){
                                            showAlert('success', 'Ціну успішно видалено!');
                                            window.editor.editorObj.deselectElements();
                                            for (var i=0; i < responseLength;i++){
                                                var neededEl = $('#'+response.ids[i].id);
                                                if (neededEl.length > 0){
                                                    priceConstructor.deletePriceInfo(neededEl);
                                                    neededEl[0].instance.data('label', response.ids[i].label);
                                                    priceConstructor.defineNotUsedElement(neededEl);
                                                }
                                            }
                                        }else{
                                            console.log("Price is not changed");
                                        }
                                        priceConstructor.countSelectedElements(selectedPlaces);
                                        if (!$.isEmptyObject(response.price_block)){
                                            var priceBlock = $('.prices');
                                            if (priceBlock.length > 0){
                                                priceBlock.html(response.price_block);
                                                priceConstructor.definePriceControls();
                                            }
                                        }
                                        if (!$.isEmptyObject(response.totalInfo))
                                            priceConstructor.updateCountBlock(response.totalInfo);
                                    }else{
                                        var errorMsg = response.msg;
                                        if (errorMsg != null && errorMsg.length > 0){
                                            showAlert('danger', errorMsg);
                                        }
                                    }
                                }
                            );
                        }else{
                            console.log("Set price url missed");
                        }
                    }else{
                        console.log("Deleting price failed. No items to delete");
                    }
                });
            }
        },

        // set count for selected fans
        setFunCountAction: function(){
            var actionType = $('#actionType'),
                amount = $('#amount'),
                event_id = $('#event_id');

            this.setAmountBtn = $('#setFunCount');
            if (this.setAmountBtn.length > 0){
                this.setAmountBtn.on('click', function(e){
                    e.preventDefault();
                    var selectedFuns = $('svg .current[data-type="'+priceConstructor.IDD_FAN_ZONE+'"]');
                    if (amount.length > 0 && selectedFuns.length > 0) {
                        var places = [];
                        selectedFuns.each(function(){
                            places.push({
                                sector_id : $(this).attr('sector_id')
                            });
                        });

                        var postUrl = priceConstructor.setAmountBtn.data('url');
                        if (postUrl != null && postUrl != "" && postUrl.length > 0){
                            $.post(postUrl,
                                {
                                    data : JSON.stringify({
                                        event_id: event_id.val(),
                                        fun_zones: places,
                                        count: amount.val(),
                                        actionType: actionType.val()
                                    })
                                }, function(result) {
                                    //console.log(result);
                                    var response = JSON.parse(result);
                                    var success = response.success;

                                    if (success) {

                                        var responseLength = response.places.length;
                                        if (responseLength > 0) {
                                            showAlert('success', 'Кількість місць для продажу успішно встановлено!');
                                            for (var i = 0; i < responseLength; i++) {
                                                priceConstructor.updateElementsData(response.places[i]);
                                            }
                                        } else {
                                            console.log("Amount is not changed");
                                        }
                                        priceConstructor.checkAmountBlockStatus(selectedFuns);
                                        priceConstructor.countSelectedElements(selectedFuns);
                                        if (!$.isEmptyObject(response.price_block)) {
                                            var priceBlock = $('.prices');
                                            if (priceBlock.length > 0) {
                                                priceBlock.html(response.price_block);
                                                priceConstructor.definePriceControls();
                                            }
                                        }
                                        if (!$.isEmptyObject(response.sector_block)){
                                            var sectorBlock = $('.sectors');
                                            if (sectorBlock.length > 0) {
                                                sectorBlock.html(response.sector_block);
                                                priceConstructor.defineSectorsControls();
                                            }
                                        }
                                    }else{
                                        var errorMsg = response.msg;
                                        if (errorMsg != null && errorMsg.length > 0){
                                            showAlert('danger', errorMsg);
                                        }
                                    }
                                }
                            );
                        }else{
                            console.log("Set amount url missed");
                        }
                    }else{
                        console.log('Set fun amount failed');
                    }
                });
            }
        },

        // update elements data
        updateElementsData: function(updateObj){
            var updateEl =  $('#'+updateObj.id);
            if (updateEl.length > 0){
                window.editor.editorObj.setPriceInfo(updateEl[0].instance, updateObj.price_info);
                if (updateObj.label){
                    updateEl[0].instance.data('label', updateObj.label);
                }
            }else{
                console.log("Element not found");
            }

        },

        // delete price from element
        deletePriceInfo: function(neededEl){
            neededEl[0].instance.data('price', null);
        },

        // change visibility of amount info block
        checkAmountBlockStatus: function(selectedElements){
            if (selectedElements == null){
                this.changeVisibility(this.amountInfoBlock, false);
            }else{
                if (typeof selectedElements == 'object'){
                    var isFun = this.isJustFunZones(selectedElements);
                    if (isFun){
                        this.setInfoToAmountBlock(selectedElements);
                        this.changeVisibility(this.amountInfoBlock, true);
                    }else{
                        this.changeVisibility(this.amountInfoBlock, false);
                    }
                }
            }
        },

        // change element visibility
        changeVisibility: function(element, visible){
            if (visible){
                if (element.css('visibility') == "hidden") element.css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0});
            }else{
                if (element.css('visibility') == "visible") element.css({opacity: 1.0, visibility: "hidden"}).animate({opacity: 0.0});
            }
        },

        // check if single selected element is fun zone
        isFunzone: function(element){
            var elementType = element.data('type');
            var isFunZone = false;
            if (elementType == this.IDD_FAN_ZONE){
                isFunZone = true;
            }
            return isFunZone;
        },

        // check if all selected elements are fun zones
        isJustFunZones: function(selectedElements){
            var isFun = true;
            if (selectedElements.length != undefined && selectedElements.length > 0){ // multiple selected elements
                selectedElements.each(function(){
                    if ($(this).data('type') != priceConstructor.IDD_FAN_ZONE){
                        isFun = false;
                        return;
                    }
                });
            }else{ // single selected element
                isFun = this.isFunzone(selectedElements);
            }
            return isFun;
        },

        // update amount info block
        setInfoToAmountBlock: function(element){
            var soldAmount = $('#sold_amount'),
                availableAmount = $('#available_amount'),
                totalAmount = $('#total_amount');
            if (element.length != undefined & element.length > 0){ // multiple selected elements
                var soldA = 0,
                    totalA = 0,
                    availableA = 0;
                element.each(function(){
                    soldA += parseInt(this.instance.data('sold'));
                    totalA += parseInt(this.instance.data('count'));
                    availableA += parseInt(this.instance.data('count')) - parseInt(this.instance.data('sold'));
                });
                soldAmount.text(soldA);
                totalAmount.text(totalA);
                availableAmount.text(availableA);
            }else{ // single selected element
                soldAmount.text(element.data('sold') || 0);
                totalAmount.text(element.data('count') || 0);
                availableAmount.text(parseInt(element.data('count')) - parseInt(element.data('sold')) || 0);
            }
        },

        // set selected results to selected info block
        setInfoToSelectBlock: function(element){
            /*selectedSeatAmount: $('#select_seat_amount'),
            selectedPriceAmount: $('#select_price_amount'),*/
            var rowAmount = this.selectedRowBlock.find('strong');
            if (element.length != undefined & element.length > 0){ // multiple selected elements
                var price = 0;
                var seat = 0;
                var counter = 0;
                var rowHistory = {};
                var sectHistory = [];
                element.each(function(){

                    if (priceConstructor.isFunzone($(this))){
                        var totalPrice = parseInt(this.instance.data('price')) * parseInt(this.instance.data('count'));
                        if (isNaN(totalPrice)) totalPrice = 0;
                        price += totalPrice;
                        seat += parseInt(this.instance.data('count'));
                    }else{
                        var elRow = window.editor.editorObj.parseNetElementId($(this).attr('id')).row;
                        var elSect = $(this).attr('sector_id');

                        // function for counting rows amount for selected elements
                        if (sectHistory.length == 0){
                            sectHistory.push(elSect);
                            rowHistory[''+elSect+''] = [];
                            rowHistory[''+elSect+''].push(elRow);
                            counter++;
                        }else{
                            var isSectInHistory = false;
                            for (var j=0;j < sectHistory.length; j++){
                                if (elSect == sectHistory[j]){
                                    isSectInHistory = true;
                                    break;
                                }
                            }
                            if (!isSectInHistory){
                                sectHistory.push(elSect);
                                rowHistory[''+elSect+''] = [];
                                rowHistory[''+elSect+''].push(elRow);
                                counter++;
                            }else{
                                var isSector = false;
                                for (var i in rowHistory){
                                    if (i == elSect){
                                        var isRow = false;
                                        for (var g=0; g < rowHistory[i].length; g++){
                                            if (elRow == rowHistory[i][g]){
                                                isRow = true;
                                                break;
                                            }
                                        }
                                        if (!isRow){
                                            rowHistory[i].push(elRow);
                                            counter++;
                                        }
                                        isSector = true;
                                        break;
                                    }
                                }
                                if (!isSector){
                                    rowHistory[''+elSect+''].push(elRow);
                                    counter++;
                                }
                            }
                        }
                        var elPrice = parseInt(this.instance.data('price'));
                        price += !isNaN(elPrice) ? elPrice : 0;
                        seat++;
                    }
                });
                this.selectedPriceAmount.text(!isNaN(price) ? price : 0);
                this.selectedSeatAmount.text(seat);
                rowAmount.text(counter);
            }else{ // single selected element
                if (priceConstructor.isFunzone(element)){
                    var totalPrice = parseInt(element.data('price')) * parseInt(element.data('count'));
                    if (isNaN(totalPrice)) totalPrice = 0;
                    this.selectedPriceAmount.text(totalPrice);
                    this.selectedSeatAmount.text(element.data('count') || 0);
                }else{
                    this.selectedPriceAmount.text(element.data('price') || 0);
                    this.selectedSeatAmount.text(1);
                    rowAmount.text(1);
                }
            }
        },

        // mark not used elements
        defineNotUsedElement: function(createdElement){
            if (!$.isEmptyObject(createdElement)){
                if (createdElement.data('price') == null && !createdElement.hasClass('imported')){
                    createdElement.attr('fill','#ddd');
                }
            }
        },

        // calculate selected elemnts and their price
        countSelectedElements: function(selectedElements){
            if (selectedElements == null){
                this.changeVisibility(this.selectedInfoBlock, false);
            }else{
                if (typeof selectedElements == 'object'){
                    var isFun = this.isJustFunZones(selectedElements);
                    if (isFun){
                        this.selectedRowBlock.hide()
                    }else{
                        this.selectedRowBlock.show()
                    }
                    this.setInfoToSelectBlock(selectedElements);
                    this.changeVisibility(this.selectedInfoBlock, true);
                }
            }
        },

        init: function(){
            this.setPriceAction();
            this.deletePriceAction();
            this.setFunCountAction();
            this.definePriceControls();
            this.defineSectorsControls();
            return this;
        }
    }
    return priceConstructor.init();
}
