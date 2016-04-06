function initMap() {
    var editor_cont = $('.editor_cont');
    if (editor_cont.length>0){

        if (isMobile()) $('body').addClass('mobile');

        var isPreviewPage = editor_cont.hasClass('preview'),
            isProductionPage = editor_cont.hasClass('production'),
            hasMacro = editor_cont.data('hasmacro');
        // add fixed height for editor container

        if (editor_cont.parent().hasClass("map"))
            editor_cont.find('#svg_overflow').height(editor_cont.parent().height()+5+"px").css({"position":"relative", "border":"1px solid #cccccc"}); // TODO right height
        window.editor = new Editor({
            container: "#editor_cont",
            controls: !isPreviewPage,
            generalMoving: !isProductionPage,
            isPreviewPage: isPreviewPage,
            isProductionPage: isProductionPage,
            hasMacro: hasMacro,
            keepPreviousSelected: isProductionPage,
            move: false,
            round: false,
            turning: false,
            margin: false,
            reflection: false,
            tooltip: true,
            dragMode: isProductionPage,
            selectHandler: selectHandler,
            createElementHandler: createElementHandler,
            createSectorHandler: createSectorHandler,
            microModeLoadedListener: microModeLoadedListener,
            deselectBtnHandler: deselectBtnHandler
        });
        if(isPreviewPage){
            if (!hasMacro){
                getAllSectorVisualInfo();
            }else{
                getImportedData(function(){ // get funzones data just when imported loaded for best trim results
                    var funId = editor_cont.data('funzones');
                    if (funId && funId instanceof Array && funId.length > 0){
                        for (var i=0;i<funId.length;i++){
                            getSectorVisualInfo(null, funId[i], {preventAfterLoadTrim: true});
                        }
                    }
                    schemeLoadingListener();
                });
            }
        }else{ // visual editor in
            $('#svg_cont').css({width: 5000, height: 5000}).attr({'data-width': 5000, 'data-height': 5000});
            getImportedData();
            schemeLoadingListener();
        }
    }
}
$(document).ready(function(){

    // clear localStorage data
    localStorage.clear();

    // initializing sctructure editor
    if ($('.net_container').length>0){

        // add fixed height for net container
        //$('.net_container').height($(window).height()-$('.net_container').offset().top); // TODO right height

        // create new net with properties
        var netProperties = {
            container : ".net_container",
            cols:40,
            rows:30,
            loadDump: false,
            netBackgroundColor: '#fcf8e3',
            lock: false
        };
        window.sectorNet = new Net(netProperties);
        window.sectorNet.netObj.hideNet();

        var sectList = $('#sector_list');
        if (sectList.length>0){
            if (sectList.val() != ""){
                sectList.trigger("change")
            }
        }

    }

    initMap();

    // tmp function for testing
    $('#add_sector').click(function(e){
        e.preventDefault();
        var testJson = '{"id":"3","info":{"prefix":"Тест","name":"1","row_name":"Зона","col_name":"Диван"},"scheme":{"netDimension":{"rows":30,"cols":40},"cell":{"header_col_cell":[],"header_row_cell":[],"simple_cell":[{"id":"row6col16sector3","selected":true},{"id":"row6col17sector3","selected":true},{"id":"row6col18sector3","selected":true},{"id":"row6col19sector3","selected":true},{"id":"row6col20sector3","selected":true},{"id":"row6col21sector3","selected":true},{"id":"row8col10sector3","selected":true},{"id":"row8col11sector3","selected":true},{"id":"row8col12sector3","selected":true},{"id":"row8col13sector3","selected":true},{"id":"row8col14sector3","selected":true},{"id":"row8col23sector3","selected":true},{"id":"row8col24sector3","selected":true},{"id":"row8col25sector3","selected":true},{"id":"row8col26sector3","selected":true},{"id":"row8col27sector3","selected":true},{"id":"row9col10sector3","selected":true},{"id":"row9col11sector3","selected":true},{"id":"row9col12sector3","selected":true},{"id":"row9col13sector3","selected":true},{"id":"row9col14sector3","selected":true},{"id":"row9col23sector3","selected":true},{"id":"row9col24sector3","selected":true},{"id":"row9col25sector3","selected":true},{"id":"row9col26sector3","selected":true},{"id":"row9col27sector3","selected":true},{"id":"row10col10sector3","selected":true},{"id":"row10col11sector3","selected":true},{"id":"row10col12sector3","selected":true},{"id":"row10col13sector3","selected":true},{"id":"row10col14sector3","selected":true},{"id":"row10col17sector3","selected":true},{"id":"row10col18sector3","selected":true},{"id":"row10col19sector3","selected":true},{"id":"row10col20sector3","selected":true},{"id":"row10col23sector3","selected":true},{"id":"row10col24sector3","selected":true},{"id":"row10col25sector3","selected":true},{"id":"row10col26sector3","selected":true},{"id":"row10col27sector3","selected":true},{"id":"row11col10sector3","selected":true},{"id":"row11col11sector3","selected":true},{"id":"row11col12sector3","selected":true},{"id":"row11col13sector3","selected":true},{"id":"row11col14sector3","selected":true},{"id":"row11col17sector3","selected":true},{"id":"row11col18sector3","selected":true},{"id":"row11col19sector3","selected":true},{"id":"row11col20sector3","selected":true},{"id":"row11col23sector3","selected":true},{"id":"row11col24sector3","selected":true},{"id":"row11col25sector3","selected":true},{"id":"row11col26sector3","selected":true},{"id":"row11col27sector3","selected":true},{"id":"row12col10sector3","selected":true},{"id":"row12col11sector3","selected":true},{"id":"row12col12sector3","selected":true},{"id":"row12col13sector3","selected":true},{"id":"row12col14sector3","selected":true},{"id":"row12col17sector3","selected":true},{"id":"row12col18sector3","selected":true},{"id":"row12col19sector3","selected":true},{"id":"row12col20sector3","selected":true},{"id":"row12col23sector3","selected":true},{"id":"row12col24sector3","selected":true},{"id":"row12col25sector3","selected":true},{"id":"row12col26sector3","selected":true},{"id":"row12col27sector3","selected":true}]},"netMode":false}}';
        editor.editorObj.setData(testJson,"netDump");
    });

    // dropdown function
    var dropdown_conts = $('.dropdown_cont');
    if (dropdown_conts.length>0){
        dropdown_conts.each(function(){
            var dropdown_control = $(this).children('.dropdown_control');
            var dropdown_content = $(this).children('.dropdown_content');
            if ((dropdown_control && dropdown_content).length>0){
                dropdown_control.click(function(){
                    $(this).toggleClass('act');
                    dropdown_content.slideToggle();
                });
            }
        });
    }

    // sector_list functionality
    var sector_items = $('.sector_item');
    if (sector_items.length > 0){
        var active_cont = $('.active');
        var inctive_cont = $('.inactive');
        sector_items.each(function(){
            var sector_item = $(this);
            var view_control = $(this).find('.visibility');
            var control = $(this).find('.active_control');
            var sectName = $(this).children('a');
            view_control.on('click',function(){
                $(this).toggleClass('closed');
                setSectorVisibility(sector_item,$(this));
            });
            control.on('click',function(){
                if($(this).hasClass('make_active')){
                    sector_item.hide();
                    active_cont.append(sector_item.fadeIn(700));
                    $(this).removeClass('make_active').addClass('make_inactive');
                    getSectorVisualInfo(sector_item);
                }else if($(this).hasClass('make_inactive')){
                    var _this = $(this);
                    $.post(
                        $(".dropdown_content").attr("data-checkUrl"),
                        {
                            id : sector_item.attr('id')
                        }, function(result) {
                            result = JSON.parse(result);
                            if (result == true) {
                                sector_item.hide();
                                inctive_cont.append(sector_item.fadeIn(700));
                                _this.removeClass('make_inactive').addClass('make_active');
                                saveSectorVisual(sector_item,'save_data');
                            } else {
                                showAlert("danger", "Вимкнути сектор неможливо оскільки сектор містить продані місця")
                            }
                        }
                    );
                }
            });
            if (control.hasClass('make_inactive')){
                getSectorVisualInfo(sector_item);
            }
            sectName.on('click', function(e){
                e.preventDefault();
                if (!view_control.hasClass('closed') && view_control.is(':visible')){
                    var sectorEl = $('[sector_id="'+sector_item.attr('id')+'"]');
                    if (sectorEl.length > 0){
                        $('.sector_item a.act').not(sectName).removeClass('act');
                        sectName.toggleClass('act');
                        if (sectName.hasClass('act')){
                            window.editor.editorObj.setCustomSelectedElements(sectorEl);
                        }else{
                            window.editor.editorObj.deselectElements();
                        }
                    }
                }else{
                    console.log('Sector selecting not allowed');
                }
            });
        });
        if (active_cont.find('.sector_item').length == 0 && window.loader && window.loader.is(':visible')) window.loader.hide();
    }

    var isPricePage = $('.prices').length > 0;
    if (isPricePage){
        window.price_constructor = new PriceConstructor();
    }
    var isAddQuotesPage = $('#cart_contractors').length > 0;
    if (isAddQuotesPage){
        window.quote_constructor = new QuoteConstructor();
    }
    var pageIdInp = $('#page_id');
    if (pageIdInp.length > 0){
        switch(pageIdInp.val()){
            case "edit_quote":
                window.edit_quote_constructor = new EditQuoteConstructor();
                break;
            case "view_all_quotes":
                window.allQuotes = new ViewAllQuotes();
                break;
        }
    }


});


var customSelectHendler, customCreateElementHandler, customSchemeLoadingListener, customMicroModeLoadedListener, customDeselectBtnHandler;

function selectHandler(currentElements, custom){
    if (window.price_constructor != null && window.price_constructor != undefined){
        window.price_constructor.checkAmountBlockStatus(currentElements);
        window.price_constructor.countSelectedElements(currentElements);
    }else if (window.quote_constructor != null && window.quote_constructor != undefined){
        window.editor.editorObj.checkAmountBlockStatus(currentElements);
    }else if (window.edit_quote_constructor != null && window.edit_quote_constructor != undefined){
        window.editor.editorObj.checkAmountBlockStatus(currentElements);
    }else{
        if (currentElements == null && $('.sector_item a.act').length > 0){
            $('.sector_item a.act').removeClass('act');
        }
    }
    if (!window.editor.editorObj.isProductionPage && !isMobile()) window.editor.editorObj.changeDeselectBtnVisisbility();
    if (customSelectHendler != undefined && typeof customSelectHendler  === "function") customSelectHendler(currentElements);
}

function createElementHandler(createdElement){
    if (window.price_constructor != null && window.price_constructor != undefined) window.price_constructor.defineNotUsedElement(createdElement);
    if (customCreateElementHandler != undefined && typeof customCreateElementHandler  === "function") customCreateElementHandler(createdElement);
}

function deselectBtnHandler(){
    if (customDeselectBtnHandler != undefined && typeof customDeselectBtnHandler  === "function") customDeselectBtnHandler();
}

function createSectorHandler(){
    if (typeof clickInCartElements == "function" && window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage) clickInCartElements();
}

function isMobile(){
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function isMac(){
    return navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i)?true:false;
}

function outerHTML(node){
    // if IE, Chrome take the internal method otherwise build one
    return node.outerHTML || (
        function(n){
            var div = document.createElement('div'), h;
            div.appendChild( n.cloneNode(true) );
            h = div.innerHTML;
            div = null;
            return h;
        })(node);
}

function mobileCart(id, type){
    if (id != null){
        addToCart(id, type);
        if (window.editor.editorObj.currentElement != null) window.editor.editorObj.deselectElements();
    }else{
        $('.in_mobile_cart').each(function(){
            this.instance.removeClass('in_mobile_cart');
        });
    }
}

function addToCart(id, type){
    var neededEl = $('#' + id);
    if (neededEl.length > 0){
        neededEl = neededEl[0].instance;
        switch (type){
            case 1:
                neededEl.addClass('in_mobile_cart');
                break;
            case 0:
                neededEl.removeClass('in_mobile_cart');
                break;
        }
    }
}

function backToMacro(){
    if (window.editor.editorObj != undefined && typeof window.editor.editorObj.backToMacroAction == 'function'){
        window.editor.editorObj.backToMacroAction();
        return true;
    }else{
        return false;
    }
}

function openMicroListener(){
    // everything you want when switching to micro mode
    if (isMobile() && isMac()){
        // write ios code for custom actions
    }
}

function schemeLoadingListener(){
    if (isMobile()){
        if (window.JSInterface) window.JSInterface.schemeLoadingListener();
    }
    if (customSchemeLoadingListener != undefined && typeof customSchemeLoadingListener  === "function") customSchemeLoadingListener();
}

function microModeLoadedListener(sectId){
    var checkIfLoaded = setInterval(function(){
        var sectEl = $('[sector_id='+sectId+']');
        if (sectEl.length > 0){
            clearInterval(checkIfLoaded);
            if (customMicroModeLoadedListener != undefined && typeof customMicroModeLoadedListener  === "function") customMicroModeLoadedListener(sectId, sectEl);
            schemeLoadingListener();
        }
    }, 50);
}

function getMobileSystemInfo(){
    var mobileOS,
        mobileOSver,
        ua = navigator.userAgent,
        uaindex;

    // determine OS
    if ( ua.match(/iPad/i) || ua.match(/iPhone/i) )
    {
        mobileOS = 'iOS';
        uaindex  = ua.indexOf( 'OS ' );
    }
    else if ( ua.match(/Android/i) )
    {
        mobileOS = 'Android';
        uaindex  = ua.indexOf( 'Android ' );
    }
    else
    {
        mobileOS = 'unknown';
    }

    // determine version
    if ( mobileOS === 'iOS'  &&  uaindex > -1 )
    {
        mobileOSver = ua.substr( uaindex + 3, 3 ).replace( '_', '.' );
    }
    else if ( mobileOS === 'Android'  &&  uaindex > -1 )
    {
        mobileOSver = ua.substr( uaindex + 8, 3 );
    }
    else
    {
        mobileOSver = 'unknown';
    }
    return {systemName:mobileOS, systemVersion: parseFloat(mobileOSver)};
}


function refresh_map() {
    window.editor.editorObj.clearPreviousScheme();
    initMap();
}

function appDetected(){
    $("body").addClass("app");
}