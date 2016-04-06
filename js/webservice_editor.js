/*[[[0]]]*/// save all visual sectors
function saveAllSectorVisual(){
    if ((window.editor.editorObj.scaleControl && window.editor.editorObj.scaleControl.val() != 1) || (window.editor.editorObj.zoom != 1)){
        window.editor.editorObj.setDefaultZoom();
    }
    var sectorLists = $('#sectors_list').find('.active .sector_item');
        var sectors = [],
            hasMacro = false,
            macroBox = {};
    if (sectorLists.length>0){
        var sectorData,
            frontHideId = [],
            backHideId = [];
        sectorLists.each(function(){
            if (!$(this).find('.front').is(':checked')){ // store id for hiding macro
                frontHideId.push($(this).attr('id'));
            }
            if (!$(this).find('.back').is(':checked')){
                backHideId.push($(this).attr('id'));
            }
            sectorData = saveSectorVisual($(this),'get_data');
            if (sectorData.hasMacro) hasMacro = true;
            sectors.push(sectorData);
        });
    }
    if (hasMacro) macroBox = window.editor.editorObj.getVisualBox(window.editor.editorObj.svgParentNode.find('.makroControl[data-joined-to], .imported, [data-type="2"]')); // imported could be presented just in macro
    var imported = [],
        isCurrent = false;
        window.editor.editorObj.getImportedElements().each(function(){
        if (this.instance.hasClass('current')){
            isCurrent = true;
        }
        imported.push(outerHTML($(this)[0]));
    });
    // if some save element are selected deselect
    if (isCurrent){
        window.editor.editorObj.deselectElements();
        imported = [];
        window.editor.editorObj.getImportedElements().each(function(){
            imported.push(outerHTML($(this)[0]));
        });
    }
    var postData = {
            scheme_id: $('#scheme_id').val(),
            "sectors": sectors,
            "imported": imported,
            "hasMacro": hasMacro,
            "frontHideId": frontHideId,
            "backHideId": backHideId

    };
    // save box for whole scheme when just are in microview
    if (!window.editor.editorObj.isPreviewPage) {
        if (!window.editor.editorObj.isMicroView){
            postData.macroBox = macroBox;
        } else{
            postData.box = window.editor.editorObj.getVisualBox();
        }
    }
    //console.log(postData);
    $.ajax({
        type: "POST",
        url: SCHEME_SERVICE,
        data: {
            data: JSON.stringify(postData)
        },
        dataType: "JSON",
        success: function(result) {
            var svg_cont =  $("#svg_cont");
            $.post(svg_cont.attr("data-bitMapUrl"),
                {
                    "svg": svg_cont.html(),
                    "scheme_id": $("#scheme_id").val()
                },function(result) {
                    showAlert('success', 'Успішно збережено!');
                });

        }
    });
}

function saveSectorVisual(sector_item,type){
    if ((window.editor.editorObj.scaleControl && window.editor.editorObj.scaleControl.val() != 1) || (window.editor.editorObj.zoom != 1)){
        window.editor.editorObj.setDefaultZoom();
    }
    var sector_item = $(sector_item);
    var sectorElements = window.editor.editorObj.getSectorData(sector_item.attr('id'));
    var isCurrent = false;
    sectorElements.each(function(){
        if (this.instance.hasClass('current')){
            isCurrent = true;
        }
    });
    // if some save element are selected deselect
    if (isCurrent){
        window.editor.editorObj.deselectElements();
        sectorElements = window.editor.editorObj.getSectorData(sector_item.attr('id'));
    }
    switch (type){
        case "save_data":
            $.ajax({
                type: "POST",
                url: SECTOR_SERVICE,
                data: {
                    sector_id: sector_item.attr('id'),
                    data : serializeVisualData(sectorElements,sector_item),
                    status : 0
                },
                dataType: "JSON"
            });
            sectorElements.each(function(){
                $(this)[0].instance.remove();
            });
            return false;
            break;
        case "get_data":
            var data = {
                sector_id: sector_item.attr('id'),
                status : 1
            };
            var scheme = JSON.parse(serializeVisualData(sectorElements,sector_item));
            if (sector_item.hasClass('fun_zone')){
                scheme != null ? data.visual = scheme.visual : data.visual = null;
                data.fun_zone = true;
            } else{
                scheme != null ? data.scheme = scheme.scheme : data.scheme = null;
            }
            if (scheme){
                data.hasMacro = scheme.hasMacro;
                data.front = scheme.front;
                data.back = scheme.back;
            }
            if (data.hasMacro && window.editor.editorObj.isMicroView) data.sectorBox = scheme.sectorBox;
            //console.log(data);
            return data;
            break;
    }
}

// serialize info from visual
function serializeVisualData(visualElements,sector_item){
    if (visualElements.length>0) {
        var schemeInfo,
            front = sector_item.find('.front').is(':checked'),
            back = sector_item.find('.back').is(':checked');
        if(sector_item.hasClass('fun_zone')){
            schemeInfo ={
                "front": front,
                "back": back,
                "visual": formVisualObject(visualElements)
            }
        }else{
            schemeInfo ={
                "front": front,
                "back": back,
                'scheme' : {
                    "visualChanges":true,
                    'cell':{
                        'simple_cell':formJsonAboutVisualElements(visualElements)
                    }
                }
            }
        }
        schemeInfo.hasMacro = window.editor.editorObj.hasSectorMacro(sector_item.attr('id'));
        if (schemeInfo.hasMacro && !window.editor.isPreviewPage && window.editor.editorObj.isMicroView){ // save visual box for sectors just when saving in editor
            schemeInfo.sectorBox = window.editor.editorObj.getVisualBox(visualElements);
        }
        return  JSON.stringify(schemeInfo);
    }else{
        return JSON.stringify(null);
    }
}

// form object with visual settings
function formJsonAboutVisualElements(visualElements){
    var elementsArray = [];
    if (visualElements.length>0){
        visualElements.each(function(){
            var element = {
                'id': $(this).attr('id'),
                'visual': formVisualObject($(this))
            };
            elementsArray.push(element);
        });
    }
    return elementsArray;
}

function formVisualObject(element){
    var visualObject = {
        'context': outerHTML(element[0])
    };
    return visualObject;
}

function getSectorVisualInfo(sector_item, id ,params){
    window.loader.show();
    var sector_list = $(sector_item),
        sectId = sector_list.attr('id');
    if (id) sectId = id;
    $.ajax({
        type: "GET",
        url: SECTOR_SERVICE,
        data: {
            sector_id : sectId,
            event_id : $("#event_id").val(),
            //data : JSON.stringify({all:true}),
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
    //console.log(result);
    params != undefined ? preventAfterLoadTrim = params.preventAfterLoadTrim : preventAfterLoadTrim = false;
    if (params != undefined && params.callback != undefined) params.callback();
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
        if (params != undefined && params.callback != undefined && typeof params.callback == "function") params.callback();
        if (window.editor.editorObj.hasMacro && window.editor.editorObj.isPreviewPage){
            window.editor.editorObj.setTrimmedZoom();
            if (!preventAfterLoadTrim){
                window.editor.editorObj.changeZoomControlsVisibility();
                window.editor.editorObj.checkDragControlsVisibility();
            }
        }
        if(result.id){
            var sector_item = $('#'+result.id);
            if (sector_item.length>0) setSectorVisibility(sector_item,sector_item.find('.visibility').removeClass('closed'));
        }else{
            console.log('No sector id');
        }
    }
}

// get info about all sectors for visual editor
function getAllSectorVisualInfo(){
    $.ajax({
        type: "GET",
        url: SECTOR_SERVICE,
        data: {
            quote_id : $('#quote_id').val(),
            event_id : $('#event_id').val(),
            scheme_id : $('#scheme_id').val(),
            token : $('#token').val(),
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
    //console.log(result);
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

// get info about imported elements for visual elements
function getImportedData(callback){
    $.ajax({
        type: "GET",
        url: SECTOR_SERVICE,
        data: {
            scheme_id : $('#scheme_id').val(),
            event_id : $('#event_id').val(),
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
            for (i=0;i<imported.length;i++){
                window.editor.editorObj.setImport(imported[i], false);
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

// show/hide sector function
function setSectorVisibility(sector_item,view_control){
    var sectorElements = window.editor.editorObj.getSectorData(sector_item.attr('id'));
    if (view_control.hasClass('closed')){
        // show sector
        sectorElements.each(function(){
            $(this).hide();
        });
    }else{
        // hide sector
        sectorElements.each(function() {
            $(this).show();
        });
    }
}