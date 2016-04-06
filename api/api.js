/**
 * Created by elvis on 22.06.15.
 */

function getAllSectorVisualInfo(event_id, api_url){
    window.loader.show();
    $.ajax({
        type: "GET",
        url: api_url+"/getMap",
        data: {
            event_id : event_id,
            data : JSON.stringify({
                all : true
            })
        },
        success: getAllSectorVisualInfoResponse,
        dataType: "JSON"
    });
}


// response for getting all sectors
function getAllSectorVisualInfoResponse(result){
    //var result = JSON.parse(result);
    if (result){
        window.editor.editorObj.setVisualBox(result.box);
        if (result.sectors){ // set sectors scheme to visual editor
            for (var i=0;i<result.sectors.length;i++){
                getSectorVisualInfoResponse(result.sectors[i]);
            }
        }else{
            console.log('No info about scheme sectors');
        }
        if(result.imported){
            getImportedVisualInfoResponse(result);
        }
        window.editor.editorObj.setTrimmedZoom();
        window.editor.editorObj.changeZoomControlsVisibility();
        window.editor.editorObj.checkDragControlsVisibility();
        schemeLoadingListener();
    }
}


function getSectorVisualInfo(sector_item, id ,params){
    window.loader.show();
    var sector_list = $(sector_item),
        sectId = sector_list.attr('id');
    if (id) sectId = id;
    $.ajax({
        type: "GET",
        url: $("#editor_cont").attr('data-api_url')+"/getMap",//TODO  check if correct url
        data: {
            event_id: $("#editor_cont").attr('data-id'),
            sector_id : sectId,
            status : 1
        },
        success: function(data){
            getSectorVisualInfoResponse(data, params)
        },
        dataType: "JSON"
    });
}

// response for getting sectors
function getSectorVisualInfoResponse(result, params){
    var preventAfterLoadTrim;
    params != undefined ? preventAfterLoadTrim = params.preventAfterLoadTrim : preventAfterLoadTrim = false;
    if (params != undefined && params.callback != undefined && typeof params.callback == "function") params.callback();
    if (result){
        if ((window.editor.editorObj.scaleControl && window.editor.editorObj.scaleControl.val() != 1) || (window.editor.editorObj.zoom != 1)){
            if (!window.editor.editorObj.isPreviewPage  && !preventAfterLoadTrim) window.editor.editorObj.setDefaultZoom();
        }
        if (window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage){
            if (result.sectorBox != undefined && !preventAfterLoadTrim){
                window.editor.editorObj.setVisualBox(result.sectorBox);
                window.editor.editorObj.changeShemeViewMode();
            }
        }
        if (window.editor.editorObj.isPreviewPage){ // check sector visibility set in visual editor
            var macroControl,
                id = result.fun_zone ? result.sector_id : result.id;
            if (window.editor.editorObj.isProductionPage){
                if (!result.front){
                    macroControl = $('[data-joined-to='+id+']');
                    if (macroControl.length > 0 ) macroControl.remove();
                    if (window.loader && window.loader.is(':visible')) window.loader.hide();
                    return false;
                }
            }else{
                if (!result.back){
                    macroControl = $('[data-joined-to='+id+']');
                    if (macroControl.length > 0 ) macroControl.remove();
                    if (window.loader && window.loader.is(':visible')) window.loader.hide();
                    return false
                }
            }
        }
        if (result.scheme){ // set sectors scheme to visual editor
            if(result.scheme.visualChanges == true){
                window.editor.editorObj.setData(JSON.stringify(result),"visualDump");
            }else{
                window.editor.editorObj.setData(JSON.stringify(result),"netDump");
            }
        }else if(result.fun_zone){
            window.editor.editorObj.setData(JSON.stringify(result),"fun_zone");
        }else{
            console.log('No info about sector elements');
        }
        window.editor.editorObj.createSectorHandler();
        if (window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage){
            window.editor.editorObj.setTrimmedZoom();
            if (!preventAfterLoadTrim){
                window.editor.editorObj.changeZoomControlsVisibility();
                window.editor.editorObj.checkDragControlsVisibility();
            }
        }
    }
}

// get info about imported elements for visual elements
function getImportedData(callback){
    $.ajax({
        type: "GET",
        url: $("#editor_cont").attr('data-api_url')+"/getMap", //TODO  check if correct url
        data: {
            event_id : $("#editor_cont").attr('data-id'),
            data : JSON.stringify({
                getImported: true
            })
        },
        success: function(data){
            getImportedVisualInfoResponse(data, callback);
        },
        dataType: "JSON"
    });
}

// handle response with imported svg elements for scheme
function getImportedVisualInfoResponse(result, callback){
    if (result){
        if (window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage){
            window.editor.editorObj.changeShemeViewMode();
            window.editor.editorObj.setVisualBox(result.macroBox);
            window.editor.editorObj.svgParentNode.data('macroBox', result.macroBox);
        }
        var imported, i;
        if (result.imported) imported = result.imported;
        if (imported && imported.length > 0){
            if (typeof  imported == "string"){
                window.editor.editorObj.setImport(imported, false);
            }else{
                for (i=0;i<imported.length;i++){
                    window.editor.editorObj.setImport(imported[i], false);
                }
            }
        }
        if (result.frontHideId != undefined && result.frontHideId.length > 0 && window.editor.editorObj.isProductionPage){
            for (i = 0; i < result.frontHideId.length;i++){
                var macroFr = $('[data-joined-to='+result.frontHideId[i]+']');
                if (macroFr.length > 0) macroFr.remove();
            }
        }else if (result.backHideId != undefined && result.backHideId.length > 0 && window.editor.editorObj.isPreviewPage && !window.editor.editorObj.isProductionPage){
            for (i = 0; i < result.backHideId.length;i++){
                var macroBack = $('[data-joined-to='+result.backHideId[i]+']');
                if (macroBack.length > 0) macroBack.remove();
            }
        }
        if (window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage){
            window.editor.editorObj.setTrimmedZoom();
            window.editor.editorObj.changeZoomControlsVisibility();
            window.editor.editorObj.checkDragControlsVisibility();
        }
    }
    if (callback) callback();
}

$(document).ready(function(e) {
    var map = $("#editor_cont"),
        hasMacro = $('#editor_cont').data('hasmacro');
        //hasMacro = true;

    if (map.length != 0) {

        window.editor = new Editor({
            container: "#editor_cont",
            controls: false,
            generalMoving: false,
            isPreviewPage: true,
            isProductionPage: true,
            dragMode: true,
            hasMacro: hasMacro,
            keepPreviousSelected: true,
            move: false,
            round: false,
            turning: false,
            margin: false,
            reflection: false,
            tooltip: true,
            selectHandler: selectHandler,
            createElementHandler: createElementHandler,
            createSectorHandler: createSectorHandler,
            microModeLoadedListener: microModeLoadedListener,
            deselectBtnHandler: deselectBtnHandler
        });
        if (!hasMacro){
            getAllSectorVisualInfo(map.attr('data-id'), map.attr('data-api_url'));
        }else{
            getImportedData(function(){ // get funzones data just when imported loaded for best trim results
                var funId = $('#editor_cont').data('funzones');
                if (funId && funId instanceof Array && funId.length > 0){
                    for (var i=0;i<funId.length;i++){
                        getSectorVisualInfo(null, funId[i], {preventAfterLoadTrim: true});
                    }
                }
                schemeLoadingListener();
            });
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
    if (window.price_constructor != null && window.price_constructor != undefined){
        window.price_constructor.defineNotUsedElement(createdElement);
    }
    if (customCreateElementHandler != undefined && typeof customCreateElementHandler  === "function") customCreateElementHandler(createdElement);
}

function createSectorHandler(){}

function openMicroListener(){
    // everything you want when switching to micro mode
    if (isMobile() && isMac()){
        // write ios code for custom actions
    }
}

function deselectBtnHandler(){
    if (customDeselectBtnHandler != undefined && typeof customDeselectBtnHandler  === "function") customDeselectBtnHandler();
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

function backToMacro(){
    if (window.editor.editorObj != undefined && typeof window.editor.editorObj.backToMacroAction == 'function'){
        window.editor.editorObj.backToMacroAction();
        return true;
    }else{
        return false;
    }
}

function appDetected(){
    $("body").addClass("app");
}