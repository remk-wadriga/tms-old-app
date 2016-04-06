// Initializing Editor object
// Written by Oleg Vykhopen 2014

function Editor(par) {

    var global = {};

    // input settings
    global.container = $(par.container);
    global.controls = par.controls;
    global.generalMoving = par.generalMoving;
    global.seatWidth = par.seatWidth || 25;
    global.seatHeight = par.seatHeight || 25;
    global.seatMarginRight = par.seatMarginRight || 8;
    global.seatMarginBottom = par.seatMarginBottom || 16;
    global.round = par.round;
    global.move = par.move;
    global.isPreviewPage = par.isPreviewPage;
    global.isProductionPage = par.isProductionPage;
    global.turning = par.turning;
    global.margin = par.margin;
    global.keepPreviousSelected = par.keepPreviousSelected;
    global.reflection = par.reflection;
    global.selectHandler = par.selectHandler || function () {};
    global.createElementHandler = par.createElementHandler || function () {};
    global.createSectorHandler = par.createSectorHandler || function () {};
    global.microModeLoadedListener = par.microModeLoadedListener || function () {};
    global.deselectBtnHandler = par.deselectBtnHandler || function () {};
    global.dragMode = par.dragMode;
    global.isMobile = isMobile();
    global.isMac = isMac();
    global.tooltip = par.tooltip;
    // class object and its methods

    global.editorObj = {

        zoom: 1,
        zoomStep: 0.05,
        minZoom: 0.1,
        amountInfoBlock: $('#amount_info_block'),
        svgChildBlock: global.container.children('#svg_overflow'),
        svgParentNode: $('#svg_cont'),
        sectorsList: $('#sectors_list'),
        sectorsListItems: $('#sectors_list').find('.sector_item '),
        IDD_FAN_ZONE: 2,
        allowPanDrag: global.isPreviewPage && global.isProductionPage,
        dragMode: global.dragMode,
        hasMacro: par.hasMacro,
        isMicroView: true,
        isPreviewPage: global.isPreviewPage,
        isProductionPage: global.isProductionPage,
        createSectorHandler: global.createSectorHandler,
        mobileInfo: getMobileSystemInfo(),

        createEditor: function () {

            if (!global.isMobile) {
                this.controlsCont = $('<div id="controls_cont"></div>');
                this.svgChildBlock.prepend(this.controlsCont);

                // create scale controls
                this.scaleOutBtn = $('<div class="scaleOut"></div>');
                this.scaleInBtn = $('<div class="scaleIn"></div>');
                this.controlsCont.append(this.scaleInBtn, this.scaleOutBtn);
                this.scaleOutBtn.on('click', function () {
                    global.editorObj.scaleControl.val(parseFloat(global.editorObj.scaleControl.val()) - global.editorObj.zoomStep).trigger('change');
                });
                this.scaleInBtn.on('click', function () {
                    global.editorObj.scaleControl.val(parseFloat(global.editorObj.scaleControl.val()) + global.editorObj.zoomStep).trigger('change');
                });
                this.scaleControl = $('<input id="scaleControl" type="range" max="1" min="0.1" step="0.05" orient="vertical" value="1" />');
                this.controlsCont.append(this.scaleControl);
                this.scaleControl.change(function () {
                    global.editorObj.zoomElements(parseFloat($(this).val()), true);
                });
                this.scaleControl.mousewheel(function (event) { // add zoom when use mousewheel on scale control
                    global.editorObj.mouseWheelZoom(event);
                });

                // cerate scheme mode controls
                this.dragControlHand = $('<div class="dragControl hand" type="button"></div>');
                this.dragControlSelect = $('<div class="dragControl select" type="button"></div>');
                if (global.dragMode) {
                    this.dragControlHand.addClass('active');
                } else {
                    this.dragControlSelect.addClass('active');
                }
                this.controlsCont.append(this.dragControlHand, this.dragControlSelect);
                this.dragControlHand.on('click', function () {
                    global.editorObj.changeMode(1);
                });
                this.dragControlSelect.on('click', function () {
                    global.editorObj.changeMode(0);
                });
                if (global.isProductionPage) {
                    this.dragControlHand.hide();
                    this.dragControlSelect.hide();
                }
                // hot key for changing modes
                $(window).keydown(function (e) {
                    var step = 1,
                        allowMove = !global.editorObj.dragMode && global.editorObj.currentElement != null;
                    switch (e.keyCode){
                        case 72:
                            if (!global.editorObj.dragMode && global.editorObj.dragControlSelect.hasClass('active')){
                                global.dragMode = true;
                                global.editorObj.dragMode = true;
                                global.editorObj.svgParentNode.addClass('dragged');
                            }else if(global.editorObj.dragMode && global.editorObj.dragControlHand.hasClass('active') && !global.isProductionPage){
                                global.dragMode = false;
                                global.editorObj.dragMode = false;
                                global.editorObj.svgParentNode.removeClass('dragged');
                            }
                            break;
                        case 37:
                            if (allowMove){
                                e.preventDefault();
                                if (global.editorObj.shiftPressed) step = global.seatWidth + global.seatMarginRight;
                                global.editorObj.moveElement("left", step);
                            }
                            break;
                        case 38:
                            if (allowMove) {
                                e.preventDefault();
                                if (global.editorObj.shiftPressed) step = global.seatHeight + global.seatMarginBottom;
                                global.editorObj.moveElement("top", step);
                            }
                            break;
                        case 39:
                            if (allowMove) {
                                e.preventDefault();
                                if (global.editorObj.shiftPressed) step = global.seatWidth + global.seatMarginRight;
                                global.editorObj.moveElement("right", step);
                            }
                            break;
                        case 40:
                            if (allowMove) {
                                e.preventDefault();
                                if (global.editorObj.shiftPressed) step = global.seatHeight + global.seatMarginBottom;
                                global.editorObj.moveElement("bottom", step);
                            }
                            break;
                        default:
                    }
                });
                $(window).keyup(function (e) {
                    switch (e.keyCode){
                        case 72:
                            if (global.editorObj.dragMode && global.editorObj.dragControlSelect.hasClass('active')){
                                global.dragMode = false;
                                global.editorObj.dragMode = false;
                                global.editorObj.svgParentNode.removeClass('dragged');
                            }else if(!global.editorObj.dragMode && global.editorObj.dragControlHand.hasClass('active') && !global.isProductionPage){
                                global.dragMode = true;
                                global.editorObj.dragMode = true;
                                global.editorObj.svgParentNode.addClass('dragged');
                            }
                            break;
                    }
                });

                if (!global.isProductionPage){
                    this.deselectBtn = $('<div id="deselectBtn"></div>');
                    this.svgChildBlock.prepend(this.deselectBtn);
                    this.deselectBtn.on('click', function(){
                        global.editorObj.deselectElements();
                        global.deselectBtnHandler();
                    });
                    this.changeDeselectBtnVisisbility();
                }


            }

            if (this.hasMacro){
                this.backToMacroBtn = $('<div id="backToMacroBtn"></div>');
                this.svgChildBlock.prepend(this.backToMacroBtn);
                this.backToMacroBtn.on('click', function(){
                    global.editorObj.backToMacroAction();
                })
            }

            if (global.controls) {

                // add tools container and tool's controls
                var toolsCont = $('<div></div>').addClass('toolsCont');
                global.container.prepend(toolsCont);

                if (global.round) {
                    var roundControl = $('<div id="round"><a href="#"  id="round_btn" class="edit_control_btn button btn-default tool">Скруглення</a><input id="round_value" type="text" name="round_value" placeholder="px" value="5" /></div>');
                    toolsCont.append(roundControl);
                    $('#round_btn').click(function () {
                        global.editorObj.addBorderRadius();
                    });
                }

                if (global.move) {
                    var moveControl = $('<div id="move"><span id="move_left"></span><span id="move_top"></span><span id="move_right"></span><span id="move_bottom"></span><input type="text" id="move_value" placeholder="px" value="20"/></div>');
                    toolsCont.append(moveControl);
                    $('#move_left').click(function () {
                        global.editorObj.moveElement('left');
                    });
                    $('#move_top').click(function () {
                        global.editorObj.moveElement('top');
                    });
                    $('#move_bottom').click(function () {
                        global.editorObj.moveElement('bottom');
                    });
                    $('#move_right').click(function () {
                        global.editorObj.moveElement('right');
                    });
                }

                if (global.turning) {
                    var turnParentControl = $('<div id="turn_group"><span id="turn_group_left"></span><input id="turn_group_value" type="text" placeholder="degrees" value="10"/><span id="turn_group_right"></span></div>');
                    toolsCont.append(turnParentControl);
                    $('#turn_group_left').click(function () {
                        global.editorObj.turnElement('left', 'group')
                    });
                    $('#turn_group_right').click(function () {
                        global.editorObj.turnElement('right', 'group')
                    });
                }

                if (global.margin) {
                    var marginControl = $('<div id="margin"><input id="margin_slider" type="range" max="100" min="-100" step="1" value="0" /><select class="form-control" id="margin_type"><option value="0">по осі X</option><option value="1">по осі Y</option><option value="2">по осі X та Y</option></select><input id="margin_value" type="text" value="0" /></div>');
                    toolsCont.append(marginControl);
                    var marginSlider = $('#margin_slider');
                    marginSlider.change(function () {
                        marginValue.val($(this).val());
                        global.editorObj.addMargin(marginValue.val(), marginType.val());
                    });
                    var marginValue = $('#margin_value');
                    marginValue.change(function () {
                        marginSlider.val($(this).val());
                        global.editorObj.addMargin($(this).val(), marginType.val());
                    });
                    var marginType = $('#margin_type');
                    marginType.change(function () {
                        global.editorObj.addMargin(marginValue.val(), $(this).val());
                    });
                }

                if (global.reflection) {
                    var mirrowControl = $('<div id="mirrow_cont"><div id="reflect_x"></div><div id="reflect_y"></div></div>');
                    toolsCont.append(mirrowControl);
                    $('#reflect_x').click(function () {
                        global.editorObj.addReflection('x');
                    });
                    $('#reflect_y').click(function () {
                        global.editorObj.addReflection('y');
                    });
                }

                this.importControl = $('<button id="import" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Import SVG</button>');
                toolsCont.append(this.importControl);
                this.importControl.click(function () {
                    global.editorObj.importSVG();
                });

                this.exportControl = $('<button id="export" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Export SVG</button>');
                toolsCont.append(this.exportControl);
                this.exportControl.click(function () {
                    global.editorObj.exportSVG();
                });

                /*this.addSceneBtn = $('<button id="add_scene" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Add scene</button>');
                toolsCont.append(this.addSceneBtn);
                this.addSceneBtn.click(function () {
                    global.editorObj.addScene();
                });

                this.removeScene = $('<button id="remove_scene" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Remove scene</button>');
                toolsCont.append(this.removeScene);
                this.removeScene.click(function () {
                    global.editorObj.removeScene();
                });*/

                this.viewModeControl = $('<select id="view_mode" name="view_mode" class="form-control input-sm">' +
                '<option value="0">Мікросхема</option>' +
                '<option value="1">Макросхема</option>' +
                '</select>');
                toolsCont.append(this.viewModeControl);
                this.viewModeControl.on('change', function(){
                    global.editorObj.changeShemeViewMode();
                });

                    // special cont for using in macro mode
                this.makroToolsCont = $('<span id="makroToolsCont"></span>');
                toolsCont.append(this.makroToolsCont.hide());

                this.addMacroElementBtn = $('<button id="add_makro_element" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Добавити елемнт</button>');
                this.addMacroElementBtn.on('click', function(){
                    global.editorObj.addMakroElement();
                });

                this.removeMacroElementBtn = $('<button id="remove_makro_element" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Видалити елемнт</button>');
                this.removeMacroElementBtn.on('click', function(){
                    global.editorObj.removeMakroElement();
                });

                this.joinToSectorDropdown = $('<select id="join_to_sector_dropdown" name="join_to_sector" class="form-control input-sm"></select>');
                this.sectorsListItems.each(function(){
                    global.editorObj.joinToSectorDropdown.append('<option value="'+$(this).attr('id')+'">'+$(this).find('a').text()+'</option>');
                });

                this.joinToSectorBtn = $('<button id="join_to_sector_btn" class="edit_control_btn tool btn btn-primary btn-xs m-r-sm">Прив\'язати</button>');
                this.joinToSectorBtn.on('click', function(){
                    global.editorObj.joinToSector();
                });

                this.viewSectorsDropdown = $('<select id="view_sector_dropdown" name="view_sector_dropdown" class="form-control input-sm"><option value="-1">Перегляд всіх у макро</option></select>');
                this.sectorsListItems.each(function(){
                    global.editorObj.viewSectorsDropdown.append('<option value="'+$(this).attr('id')+'">'+$(this).find('a').text()+'</option>');
                });
                this.viewSectorsDropdown.on('change', function(){
                    global.editorObj.showSingleSector($(this).val(), false);
                });

                this.makroToolsCont.append(this.addMacroElementBtn, this.removeMacroElementBtn, this.joinToSectorDropdown, this.joinToSectorBtn, this.viewSectorsDropdown);

            }

            global.editorObj.isCrlPressed();
            global.editorObj.isShiftPressed();

            // add general svg object
            if (SVG.supported) {
                this.mainSVG = SVG('svg_cont');
                var draw = this.mainSVG;
                this.nodes = draw.group();
                if (global.isMobile) {
                    SVG.off(window, 'touchstart');
                    SVG.off(window, 'touchend');
                    SVG.on(window, 'touchstart', this.startSelectingMObile);
                    SVG.on(window, 'touchend', this.endSelectingMobile);
                } else {
                    SVG.off(window, 'mousedown');
                    SVG.off(window, 'mouseup');
                    SVG.on(window, 'mousedown', this.startSelecting);
                    SVG.on(window, 'mouseup', this.endSelecting);
                }

                    this.panZoom = this.nodes.panZoom();
                if (!global.isMobile) {
                    SVG.off(window, 'mousemove');
                    SVG.on(window, 'mousemove', this.isSelecting);
                    this.svgChildBlock.mousewheel(function (event) { // add zoom when use mousewheel on svg
                        if (!global.editorObj.isProductionPage) event.preventDefault();
                    });
                    var scrollTop = 0,
                        scrollLeft = 0;
                    this.svgChildBlock.on('scroll', function(e){
                        var newScrollTop = $(this).scrollTop(),
                            newScrollLeft = $(this).scrollLeft(),
                            topDiff = newScrollTop - scrollTop,
                            leftDiff = newScrollLeft - scrollLeft;
                        global.editorObj.changePositionWhenScroll([
                            global.editorObj.controlsCont,
                            global.editorObj.backToMacroBtn,
                            global.editorObj.deselectBtn], topDiff, leftDiff);
                        scrollTop = newScrollTop;
                        scrollLeft = newScrollLeft;
                    });
                }
                if (global.tooltip) this.createTooltip();
            } else {
                console.log('SVG not supported');
            }

            // additional global features and events
            if (global.generalMoving) {
                if (global.editorObj.svgChildBlock.length > 0) {
                    global.editorObj.svgChildBlock.scroll(function () {
                        global.editorObj.isScrolling = true;
                    });
                } else {
                    console.log('Overflow container missed');
                }
            }

        },

        changeDeselectBtnVisisbility: function(){
            if (this.deselectBtn && this.deselectBtn.length > 0){
                if (this.currentElement != undefined && (this.currentElement.length > 0 || (this.currentElement.node != null && this.currentElement.node != undefined))){
                    this.deselectBtn.show();
                }else{
                    this.deselectBtn.hide();
                }
            }
        },

        changeShemeViewMode: function(){
            this.isMicroView = !this.isMicroView;
            if (this.makroToolsCont) this.makroToolsCont.toggle();
            $('.makroControl').toggle();
            this.svgParentNode.toggleClass('makroView');
            this.sectorsList.toggle();
            if (this.backToMacroBtn && global.isPreviewPage) this.isMicroView == true ? this.backToMacroBtn.show() : this.backToMacroBtn.hide();
            global.editorObj.deselectElements();
            this.setDefaultZoom();this.nodes.move(0, 0); // set to default position for better trimming
            if (this.isMicroView) this.showSingleSector(-1, false);
        },

        addMakroElement: function(){
            var macroControl = $("<rect class='imported makroControl' data-selectable='true' fill='#dddddd' width='450px' height='150px' onclick='window.editor.editorObj.showSingleSector($(this).attr(\"data-joined-to\"), true)'></rect>");
            this.setImport(outerHTML(macroControl.get(0)), false);
        },

        removeMakroElement: function(){
            var curEl = $('.current');
            if (curEl. length > 0){
                curEl.each(function(){
                    if (this.instance.hasClass('makroControl')){
                        this.instance.remove();
                        $(this).remove();

                    }
                });
            }
        },

        backToMacroAction: function(){
            this.changeShemeViewMode();
            if (this.svgParentNode.data('macroBox')){ // set macrobox when switch to makro mode
                this.setVisualBox(global.editorObj.svgParentNode.data('macroBox'));
                this.setTrimmedZoom();
            }else{console.log('Macrobox not found')}
            var singleSectEl = this.svgParentNode.find('.native[data-type=1]'); // clear previously loaded old data
            if (singleSectEl.length > 0){
                singleSectEl.each(function(){
                    this.instance.remove();
                    $(this).remove();
                });
            }
            this.activeSector = null;
        },

        joinToSector: function(){
            var sectorToJoin = this.joinToSectorDropdown.val(),
                sectorToJoinName = global.editorObj.joinToSectorDropdown.find(':selected').text();
            if (sectorToJoin == ""){
                this.joinToSectorDropdown.trigger('focus');
            }else{
                var curEl = $('.current');
                if (curEl. length > 0){
                    curEl.each(function(){
                        if (!this.instance.hasClass("makroControl")){
                            this.instance.addClass("makroControl");
                            this.instance.attr({onclick: "window.editor.editorObj.showSingleSector("+sectorToJoin+", true)"});
                        }
                        this.instance.data({'joined-to': sectorToJoin, 'label': sectorToJoinName});
                        global.editorObj.setHoverHandlers(this.instance);
                        showAlert('success', "Елемент успішно прив'язано до "+sectorToJoinName+"!");
                    });
                }
            }
        },

        showSingleSector: function(sectId, isUsedFromControl){
            if (isUsedFromControl && !global.editorObj.isPreviewPage) return;
            if (isUsedFromControl && global.editorObj.isProductionPage) return;
            if (sectId == -1){
                $('.makroControl').css('cssText','');
                if (this.makroStyleEl) this.makroStyleEl.remove();
                return;
            }
            $('.makroControl').css('cssText','display: none !important');
            if (this.makroStyleEl) this.makroStyleEl.remove();
            this.makroStyleEl = $('<style type="text/css">.makroView .native[sector_id="'+sectId+'"], .makroView .rect[sector_id="'+sectId+'"]{display: block !important;}#svg_cont.makroView .imported:not([sector_id="'+sectId+'"]), .makroView .rect[data-type="2"]:not([sector_id="'+sectId+'"]), .makroView .native[data-type="2"]:not([sector_id="'+sectId+'"]){display: none !important}</style>')
            this.svgParentNode.prepend(this.makroStyleEl);
            if (global.isMobile && window.JSInterface && isUsedFromControl) window.JSInterface.showMacroBackButton("true");
            if (openMicroListener != undefined && typeof openMicroListener == "function") openMicroListener();
            var itemInList = this.sectorsList.find('#'+sectId),
                itemPar = itemInList.parent();
            this.activeSector = sectId;
            if (itemInList.length > 0){
                if (itemInList.length > 0 && itemPar.hasClass('inactive')) {
                    itemInList.find('.active_control').trigger('click');
                }
            }else{
                var hF = $('<style>.editor_cont[data-hasmacro=true].preview .makroView [data-type="2"] {display: none !important;}</style>');
                this.svgParentNode.prepend(hF);
                getSectorVisualInfo(null, sectId , {callback: function(){
                        hF.remove();
                        global.microModeLoadedListener(sectId);
                    }
                });
            }

        },

        hasSectorMacro: function(sectorId){
            return $('.makroControl[data-joined-to='+sectorId+']').length > 0;
        },

        changeMode: function(type){
            switch(type){
                case 1:
                    global.dragMode = true;
                    global.editorObj.dragMode = true;
                    global.editorObj.svgParentNode.addClass('dragged');
                    global.editorObj.dragControlHand.addClass('active');
                    global.editorObj.dragControlSelect.removeClass('active');
                    break;
                case 0:
                    global.dragMode = false;
                    global.editorObj.dragMode = false;
                    global.editorObj.svgParentNode.removeClass('dragged');
                    global.editorObj.dragControlHand.removeClass('active');
                    global.editorObj.dragControlSelect.addClass('active');
                    break;
            }

        },

        changePositionWhenScroll: function(elementsArr, topDiff, leftDiff){
            for (var i=0; i < elementsArr.length; i++){
                if (elementsArr[i] != undefined && elementsArr[i].length > 0){
                    elementsArr[i].css({
                        top: parseFloat(elementsArr[i].css('top')) + topDiff,
                        left: parseFloat(elementsArr[i].css('left')) + leftDiff
                    });
                }
            }

        },

        // general zoom on mousewheel for svg elements
        mouseWheelZoom: function (event) {
            event.preventDefault();
            var scaleVal, scaleStep,
                isZoomNeeded = (this.zoom >= this.minZoom && this.zoom < 1) || (this.zoom == 1 && event.deltaY == -1);
            // scale elements (change controls if needed)
            if (global.editorObj.scaleControl) {
                scaleStep = parseFloat(global.editorObj.scaleControl.attr('step'));
                scaleVal = parseFloat(global.editorObj.scaleControl.val());
                if (event.deltaY == 1) {
                    global.editorObj.scaleControl.val(scaleVal + scaleStep);
                    global.editorObj.scaleControl.trigger('change');
                } else if (event.deltaY == -1) {
                    global.editorObj.scaleControl.val(scaleVal - scaleStep);
                    global.editorObj.scaleControl.trigger('change');
                }
            } else {
                scaleVal = this.zoom;
                scaleStep = this.zoomStep;
                var newZoom;
                if (event.deltaY == 1) {
                    newZoom = scaleVal + scaleStep;
                } else if (event.deltaY == -1) {
                    newZoom = scaleVal - scaleStep;
                }
                if (newZoom > 0 && newZoom <= 1) {
                    this.zoom = newZoom;
                    this.zoomElements(this.zoom);
                }
            }

        },

        getTransformParams: function (obj) {
            if (obj.css('transform') != undefined){
                var matrix = obj.css('transform').replace(/[^0-9\-.,]/g, '').split(',');
                if (matrix.length > 1) {
                    return {
                        x: parseFloat(matrix[12]) || parseFloat(matrix[4]),
                        y: parseFloat(matrix[13]) || parseFloat(matrix[5])
                    };
                } else {
                    return {x: 0, y: 0};
                }
            }else {
                return {x: 0, y: 0};
            }
        },

        getScrollbarWidth: function () {
            var outer = document.createElement("div");
            outer.style.visibility = "hidden";
            outer.style.width = "100px";
            outer.style.msOverflowStyle = "scrollbar"; // needed for WinJS apps
            document.body.appendChild(outer);
            var widthNoScroll = outer.offsetWidth;
            // force scrollbars
            outer.style.overflow = "scroll";
            // add innerdiv
            var inner = document.createElement("div");
            inner.style.width = "100%";
            outer.appendChild(inner);
            var widthWithScroll = inner.offsetWidth;
            // remove divs
            outer.parentNode.removeChild(outer);
            return widthNoScroll - widthWithScroll;
        },

        zoomElements: function (scaleVal, zoomInCenter) {
            /*if (!global.isMobile){
                this.nodes.scale(scaleVal, scaleVal);
                this.svgParentNode.css({width: this.svgParentNode.attr('data-width')*scaleVal, height: this.svgParentNode.attr('data-height')*scaleVal});
                this.zoom = scaleVal;
            }else{*/
                if (zoomInCenter){
                    var mosX = this.svgChildBlock.width()/2,
                        mosY = this.svgChildBlock.height()/2,
                        scaleD = parseFloat(scaleVal / this.panZoom.transform.scaleX),
                        currentX = this.panZoom.transform.x,
                        currentY = this.panZoom.transform.y,
                        x = scaleD * (currentX - mosX) + mosX,
                        y  = scaleD * (currentY - mosY) + mosY;
                    this.panZoom.zoom(scaleVal, x, y);
                }else{
                    this.panZoom.zoom(scaleVal);
                }
                if (global.isMobile) this.svgParentNode.css({width: this.svgParentNode.attr('data-width')*scaleVal, height: this.svgParentNode.attr('data-height')*scaleVal});
            //}
        },

        setDefaultZoom: function () {
            if (this.scaleControl) {
                this.scaleControl.val(1).trigger('change');
            } else {
                if (this.zoom != 1) {
                    this.zoom = 1;
                    this.zoomElements(1);
                }
            }
        },

        //initializing selecting tool
        startSelecting: function (event) {
            if (global.isProductionPage) return true;
            if (event == undefined) return true;
            //if (global.editorObj.dragSarted) return true; // if element starts dragg don't select new
            if (global.dragMode && !global.isProductionPage) return true; // if element starts dragg don't select new
            global.editorObj.isScrolling = false;
            var clickEl = event.target;
            if ((clickEl.tagName == "svg" || $(clickEl).parents('svg').length > 0) && !global.dragMode && !global.editorObj.dragSarted) {
                // form select area if using multiple select x: event.pageX - global.editorObj.mainSVG.leftOffset
                global.editorObj.selectEnable = true;
                global.editorObj.disableHtmlSelect(true);
                global.editorObj.startPosition = {};
                global.editorObj.mainSVG.topOffset = $(global.editorObj.mainSVG.node).offset().top;
                global.editorObj.mainSVG.leftOffset = $(global.editorObj.mainSVG.node).offset().left;
                global.editorObj.startPosition.y = event.pageY - global.editorObj.mainSVG.topOffset;
                global.editorObj.startPosition.x = event.pageX - global.editorObj.mainSVG.leftOffset;
                if (global.editorObj.selectArea) {
                    global.editorObj.selectArea.remove();
                    delete global.editorObj.selectArea;
                }
                global.editorObj.selectArea = global.editorObj.mainSVG.rect({
                    width: 0,
                    height: 0
                }).fill('#0081FF').x(global.editorObj.startPosition.x).y(global.editorObj.startPosition.y).opacity(0.2);
                // clear previous selected group if not multiple select
                if (global.editorObj.selectedGroup && !global.editorObj.ctrlPressed) global.editorObj.removeGroup(global.editorObj.selectedGroup);
                global.editorObj.getElements();
            }
            if (clickEl.instance != null) {
                // single select
                if (!clickEl.instance.data('selectable')) return true;
                if (global.isPreviewPage && clickEl.instance.hasClass('imported')) return true; // don't allow select decorative in preview pages
                global.editorObj.singleClickElement = clickEl.instance;
                if (!global.editorObj.isGroupSelected && (!global.editorObj.ctrlPressed || global.keepPreviousSelected )) {
                    // use single select if multiple select wasn't fired
                    if (!global.editorObj.singleClickElement.hasClass('clicked')) {
                        if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked'); // clear previous single selected element
                        global.editorObj.removeGroup(global.editorObj.selectedGroup);
                        global.editorObj.selectedGroup = global.editorObj.createGroup();
                        global.editorObj.selectedGroup.add(global.editorObj.singleClickElement);
                        global.editorObj.addDraggableAction(global.editorObj.selectedGroup);
                        global.editorObj.singleClickElement.addClass('clicked');
                    }
                    if (!global.editorObj.singleClickElement.hasClass('current')) {
                        global.editorObj.changeCurrentElement(global.editorObj.singleClickElement);
                    } else {
                        global.editorObj.changeCurrentElement(null);
                    }
                }
                else if (global.editorObj.isGroupSelected && !global.editorObj.singleClickElement.hasClass('current') && !global.editorObj.ctrlPressed) {
                    // clear multiple select if single select is fired
                    global.editorObj.removeGroup(global.editorObj.selectedGroup);
                    global.editorObj.changeCurrentElement(global.editorObj.singleClickElement);
                    global.editorObj.isGroupSelected = false;
                    global.editorObj.selectedGroup = global.editorObj.createGroup();
                    global.editorObj.selectedGroup.add(global.editorObj.singleClickElement);
                    global.editorObj.addDraggableAction(global.editorObj.selectedGroup);
                    global.editorObj.singleClickElement.addClass('clicked');
                }
                else if (global.editorObj.isGroupSelected && global.editorObj.singleClickElement.hasClass('current')) {
                    setTimeout(function(){
                        if (!global.editorObj.isDraging){
                            if (global.editorObj.currentElement && global.editorObj.currentElement.length > 0) {
                                for (var i = 0; i < global.editorObj.currentElement.length; i++) {
                                    if ($(global.editorObj.singleClickElement.node).is($(global.editorObj.currentElement[i]))) {
                                        global.editorObj.currentElement.splice(i, 1);
                                        break;
                                    }
                                }
                            }
                            global.editorObj.singleClickElement.removeClass('current');
                            global.editorObj.singleClickElement.removeClass('clicked');
                            global.editorObj.singleClickElement.removeClass('tmp');
                            global.editorObj.copyElement(global.editorObj.singleClickElement, global.editorObj.selectedGroup);
                            global.editorObj.singleClickElement.remove();
                            delete global.editorObj.singleClickElement;
                            if (global.editorObj.currentElement.length == 0){
                                global.editorObj.changeCurrentElement(null);
                            }else{
                                global.editorObj.changeCurrentElement(global.editorObj.currentElement);
                            }
                        }
                    }, 200);
                }
                if (global.editorObj.ctrlPressed) {
                    clickEl.instance.addClass('tmp');
                    if (global.keepPreviousSelected) {
                        var selectedElements;
                        if (!global.editorObj.ctrlPressed) {
                            selectedElements = $('.tmp');
                        } else {
                            selectedElements = $('.current,.tmp');
                        }
                        if (selectedElements.length > 0) {
                            // multiple array was selected
                            global.editorObj.changeCurrentElement(selectedElements);
                            global.editorObj.selectedGroup = global.editorObj.createGroup();
                            selectedElements.each(function () {
                                this.instance.removeClass('tmp');
                                global.editorObj.selectedGroup.add(this.instance);
                            });
                            global.editorObj.addDraggableAction(global.editorObj.selectedGroup);
                            global.editorObj.isGroupSelected = true;
                        } else if ($('.clicked').length == 0) {
                            // clear everything if nothing was selected
                            global.editorObj.isGroupSelected = false;
                            global.editorObj.changeCurrentElement(null);
                            if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked');
                        }
                        //global.selectHandler(global.editorObj.currentElement);
                    }
                }
            }
        },

        // selecting elements on editor
        isSelecting: function (event) {
            if (window.editor.editorObj.dragMode && global.editorObj.panZoom.pan.mousedown) window.EventListeners.mouse_move(event);
            if (global.editorObj.selectEnable && global.editorObj.selectArea) {
                var scrolledTop = global.editorObj.svgChildBlock.scrollTop(),
                    scrolledLeft = global.editorObj.svgChildBlock.scrollLeft(),
                    currentY = event.pageY - global.editorObj.mainSVG.topOffset,
                    currentX = event.pageX - global.editorObj.mainSVG.leftOffset;
                // form select area rectangle
                global.editorObj.selectArea.height(Math.abs(currentY - global.editorObj.startPosition.y));
                global.editorObj.selectArea.width(Math.abs(currentX - global.editorObj.startPosition.x));
                if (scrolledTop <= 0) {
                    if (currentY < global.editorObj.startPosition.y) global.editorObj.selectArea.y(currentY);
                } else {
                    global.editorObj.startPosition.y >= currentY ? global.editorObj.selectArea.y(currentY) : global.editorObj.selectArea.y(global.editorObj.startPosition.y);
                    if (global.editorObj.isScrolling) {
                        if (global.editorObj.startPosition.y < global.editorObj.svgChildBlock.height()) {
                            global.editorObj.selectArea.height(global.editorObj.selectArea.height() + scrolledTop);
                        } else {
                            global.editorObj.selectArea.height(global.editorObj.selectArea.height());
                        }
                    }
                }
                if (scrolledLeft <= 0) {
                    if (currentX < global.editorObj.startPosition.x) global.editorObj.selectArea.x(currentX);
                } else {
                    global.editorObj.startPosition.x >= currentX ? global.editorObj.selectArea.x(currentX) : global.editorObj.selectArea.x(global.editorObj.startPosition.x);
                    if (global.editorObj.isScrolling) {
                        global.editorObj.selectArea.width(global.editorObj.selectArea.width() + scrolledLeft);
                    }
                }
            }
        },

        // deleting selecting tool
        endSelecting: function (event) {
            if (!$(event.target).hasClass('editor_cont') && $(event.target).parents('editor_cont').length == 0){
                global.editorObj.panZoom.pan.mousedown = false;
                window.editor.editorObj.disableHtmlSelect(false);
            }
            if (global.isProductionPage) return true;
            if (event == undefined) return true;
            var clickEl = event.target;
            if (global.editorObj.dragSarted) return true;
            if (global.editorObj.selectArea && global.editorObj.selectArea.height() == 0 && $(clickEl).is($(global.editorObj.selectArea.node))) {
                //global.editorObj.deselectElements();
            }
            if (global.editorObj.selectEnable) {
                global.editorObj.selectEnable = false;
                if (global.editorObj.selectArea && global.editorObj.selectArea.width() != 0) {
                    // check if elements are in select area
                    if (global.editorObj.allSingleElements && global.editorObj.allSingleElements.length > 0 && global.editorObj.selectArea.width() != 0) {
                        global.editorObj.allSingleElements.each(function () {
                            if (this.instance.visible() && !global.editorObj.isEmptyBbox(this.instance.bbox())){
                                if (global.editorObj.isInside(global.editorObj.selectArea, this.instance)) {
                                    this.instance.addClass('tmp');
                                } else {
                                    this.instance.removeClass('tmp');
                                }
                            }
                        });
                    }
                    if (global.editorObj.selectArea) {
                        global.editorObj.selectArea.remove();
                        delete global.editorObj.selectArea;
                    }
                }
                global.editorObj.disableHtmlSelect(false);
                if (global.editorObj.ctrlPressed) global.editorObj.removeGroup(global.editorObj.selectedGroup);
                var selectedElements;
                if (!global.editorObj.ctrlPressed) {
                    selectedElements = $('.tmp');
                } else {
                    selectedElements = $('.current,.tmp');
                }
                if (selectedElements.length > 0) {
                    // multiple array was selected
                    global.editorObj.changeCurrentElement(selectedElements);
                    global.editorObj.selectedGroup = global.editorObj.createGroup();
                    selectedElements.each(function () {
                        this.instance.removeClass('tmp');
                        global.editorObj.selectedGroup.add(this.instance);
                    });
                    global.editorObj.addDraggableAction(global.editorObj.selectedGroup);
                    global.editorObj.isGroupSelected = true;
                } else if ($('.clicked').length == 0) {
                    // clear everything if nothing was selected
                    /*global.editorObj.isGroupSelected = false;
                    global.editorObj.changeCurrentElement(null);
                    if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked');*/
                }
            }
             //if (!global.isProductionPage) global.selectHandler(global.editorObj.currentElement);
        },

        // add draggable action for each element
        addDraggableAction: function (element, params) {
            if (global.generalMoving) {
                var dragEl,
                    translate = {
                        x: element.transform().x,
                        y: element.transform().y
                    };
                element.beforestart = function (event) {

                }
                element.dragstart = function (event) {
                    if (global.isPreviewPage){
                        element.translate(translate.x, translate.y);
                    }else{
                        global.editorObj.disableHtmlSelect(true);
                    }
                    global.editorObj.dragSarted = true;
                    if (global.editorObj.tooltipCont.is(':visible')) global.editorObj.tooltipCont.hide().text("");
                }
                element.dragend = function (event) {
                    if (global.isPreviewPage){
                        element.translate(translate.x, translate.y);
                    }else{
                        global.editorObj.disableHtmlSelect(false);
                    }
                    global.editorObj.dragSarted = false;
                    global.editorObj.isDraging = false;
                }
                element.dragmove = function (event) {
                    if (global.isPreviewPage) element.translate(translate.x, translate.y);
                    global.editorObj.isDraging = true;
                }
                if (element.hasClass('transformed')) {
                    var dragGroup = element.parent.group();
                    dragGroup.add(element);
                    dragEl = dragGroup;
                } else {
                    dragEl = element;
                }
                if (params) {
                    dragEl.draggable(function (x, y) {
                        return {
                            x: x < params.maxX || (global.container.width()) && x > (params.minX || 0),
                            y: y < (params.maxY || global.container.height()) && y > (params.minY || 0)
                        }
                    });
                } else {
                    dragEl.draggable();
                }
            }
            return element;
        },

        // remove draggable action
        removeDraggableAction: function (element) {
            element.fixed();
        },

        startSelectingMObile: function (event) {
            var clickEl = event.target;
            if (clickEl.tagName != "svg" && clickEl.instance != null) {
                if (!clickEl.instance.data('selectable')) return;
                if (global.isPreviewPage && clickEl.instance.hasClass('imported')) return; // don't allow select decorative in preview pages
                global.editorObj.singleClickElement = clickEl.instance;
                /*if (!global.editorObj.singleClickElement.hasClass('clicked')) {
                    if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked');
                    global.editorObj.removeGroup(global.editorObj.selectedGroup);
                    global.editorObj.selectedGroup = global.editorObj.createGroup();
                    global.editorObj.selectedGroup.add(global.editorObj.singleClickElement);
                    global.editorObj.addDraggableAction(global.editorObj.selectedGroup);
                    global.editorObj.singleClickElement.addClass('clicked');
                }*/
                if (!global.editorObj.singleClickElement.hasClass('current')) {
                    global.editorObj.changeCurrentElement(global.editorObj.singleClickElement);
                    if (window.JSInterface) window.JSInterface.clickHandler(global.editorObj.singleClickElement.attr('id'), global.editorObj.singleClickElement.attr('data-server_id'), global.editorObj.singleClickElement.attr('data-type'));
                } else {
                    global.editorObj.changeCurrentElement(null);
                    if (window.JSInterface) window.JSInterface.clickHandler("null", "null", "null");
                }
            } else if (clickEl.tagName == "svg") {
                if (global.editorObj.selectedGroup) global.editorObj.removeGroup(global.editorObj.selectedGroup);
                global.editorObj.changeCurrentElement(null);
                if (window.JSInterface) window.JSInterface.clickHandler("null", "null", "null");
                if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked');
            }
        },

        endSelectingMobile: function (event) {
            //global.selectHandler(global.editorObj.currentElement);
        },

        // get all single elements in svg
        getElements: function () {
            // don't allow select decorative in preview pages
            if (!global.isPreviewPage) {
                this.allSingleElements = $('.rect, .native, .imported').not(global.editorObj.selectArea).not('[data-selectable="false"]');
            } else {
                this.allSingleElements = $('.rect, .native').not(global.editorObj.selectArea).not('.imported').not('[data-selectable="false"]');
            }
            return this.allSingleElements;
        },

        //check if element is in some box
        isInside: function (box, element) {
            var elementBbox = element.bbox(),
                elCoords = {
                    x1: elementBbox.x,
                    x2: elementBbox.x2,
                    y1: elementBbox.y,
                    y2: elementBbox.y2,
                    cx: elementBbox.cx,
                    cy: elementBbox.cy
                };
            var parTrans = {
                x: this.nodes.transform().x,
                y: this.nodes.transform().y,
                scale: this.nodes.transform().scaleX
            };
            if (parTrans){
                elCoords = {
                    x1: elementBbox.x*parTrans.scale + parTrans.x,
                    x2: elementBbox.x2*parTrans.scale + parTrans.x,
                    y1: elementBbox.y*parTrans.scale + parTrans.y,
                    y2: elementBbox.y2*parTrans.scale + parTrans.y,
                    cx: elementBbox.cx*parTrans.scale + parTrans.x,
                    cy: elementBbox.cy*parTrans.scale + parTrans.y
                };
            }
            return (box.inside(elCoords.cx, elCoords.cy) || box.inside(elCoords.x1, elCoords.y1) || box.inside(elCoords.x2, elCoords.y1) || box.inside(elCoords.x1, elCoords.y2) || box.inside(elCoords.x2, elCoords.y2));
        },

        // refactor coordinates when transform is used
        transformCoords: function (x, y, rotation, zoom) {
            var transformedCoords = {};
            transformedCoords.x = (x * Math.cos(rotation) + y * Math.sin(rotation));
            transformedCoords.y = Math.abs(y * Math.cos(rotation) + x * Math.sin(-rotation));
            return transformedCoords;
        },


        //create group of elements
        createGroup: function (container) {
            var group = this.nodes.group();
            return group;
        },

        //add element to group
        addToGroup: function (group, element) {
            group.add(element);
            return group;
        },

        //remove element from group
        removeFromGroup: function (group, element) {
            group.remove(element);
            if (group.children().length == 0) {
                group.remove();
                return false;
            }
            return group;
        },

        //remove group without removing elements
        removeGroup: function (group) {
            if (group) {
                var groupEl = group.node.childNodes;
                if (groupEl && groupEl.length > 0) {
                    // move elemts from group to parent (duplicates)
                    var new_elements = [];
                    $(groupEl).each(function (i, children) {
                        var oldEl = children.instance;
                        new_elements.push(global.editorObj.copyElement(oldEl, group));
                        oldEl.remove();
                    });
                    this.currentElement = $('.current'); // TODO ise new_elements
                }
                global.editorObj.selectedGroup.remove();
            }
        },

        // dublicate element before deleting
        copyElement: function (oldEl, group, group_params) {
            var new_element;
            new_element = outerHTML(oldEl.node);
            this.setImport(new_element, false);
            var elScale = this.just_created.transform().scaleX;
            var parX = group != undefined ? group.x() : 0;
            var parY = group != undefined ? group.y() : 0;
            if (elScale != 1) {
                parX /= elScale;
                parY /= elScale;
                this.just_created.scale(1);
            }
            this.just_created.x(oldEl.x() + parX).y(oldEl.y() + parY);
            this.just_created.scale(elScale);
            if (!oldEl.visible()) {
                this.just_created.hide();
            }
            return this.just_created;
        },

        //change previous current active element
        changeCurrentElement: function (new_element) {
            if (global.editorObj.currentElement) {
                if (global.editorObj.currentElement.length == null || isNaN(global.editorObj.currentElement.length) || typeof global.editorObj.currentElement.length == "function") {
                    global.editorObj.currentElement.removeClass('current');
                } else {
                    global.editorObj.currentElement.each(function () {
                        this.instance.removeClass('current');
                    });
                }
            }
            if (new_element != null) {
                if (new_element.length == null || isNaN(new_element.length) || typeof new_element.length == "function") {
                    new_element.addClass("current");
                } else {
                    new_element.each(function () {
                        this.instance.addClass("current");
                    });
                }
            }
            global.editorObj.currentElement = new_element;

            global.selectHandler(global.editorObj.currentElement);

            var userAgent = window.navigator.userAgent;
            if (userAgent.match(/iPad/i) || userAgent.match(/iPhone/i)) {
                window.location.href = "callback://placeSelectionChanged";
            }
        },

        // set custom active elements
        setCustomSelectedElements: function (selectedElements) {
            selectedElements = selectedElements.not('[data-selectable="false"]');
            this.changeCurrentElement(selectedElements);
            this.selectedGroup = this.createGroup();
            selectedElements.each(function () {
                this.instance.removeClass('tmp');
                window.editor.editorObj.selectedGroup.add(this.instance);
            });
            this.addDraggableAction(this.selectedGroup);
            this.isGroupSelected = true;
        },

        // deselect custom match of elementa
        deselectCustomElementsSet: function(elementsSet){
            if (elementsSet && elementsSet.length > 0){
                elementsSet.each(function(pos, el){
                    el = el.instance;
                    if (el && global.editorObj.currentElement.length > 0) {
                        for (var i = 0; i < global.editorObj.currentElement.length; i++) {
                            if ($(el.node).is($(global.editorObj.currentElement[i]))) {
                                global.editorObj.currentElement.splice(i, 1);
                                break;
                            }
                        }
                    }
                    el.removeClass('current');
                    el.removeClass('clicked');
                    el.removeClass('tmp');
                    global.editorObj.copyElement(el, global.editorObj.selectedGroup);
                    el.remove();
                    delete el;
                    if (global.editorObj.currentElement.length == 0){
                        global.editorObj.changeCurrentElement(null);
                    }
                });
                global.selectHandler(global.editorObj.currentElement, true);
            }
        },


        // deselect all elements in editor
        deselectElements: function () {
            global.editorObj.isGroupSelected = false;
            global.editorObj.changeCurrentElement(null);
            if ($('.clicked').length > 0) $('.clicked')[0].instance.removeClass('clicked');
            window.editor.editorObj.removeGroup(window.editor.editorObj.selectedGroup);
            if (global.editorObj.selectArea) {
                global.editorObj.selectArea.remove();
                delete global.editorObj.selectArea;
            }
            //global.selectHandler(null);
        },

        // change margin of elements
        addMargin: function (value, type) {
            if (type && value && this.selectedGroup) {
                var margin = parseFloat(value);
                var groupXCenter = global.editorObj.selectedGroup.bbox().cx;
                var groupYCenter = global.editorObj.selectedGroup.bbox().cy;
                this.selectedGroup.each(function () {
                    var elementXCenter = parseFloat(this.bbox().cx) + parseFloat(global.editorObj.selectedGroup.transform().x);
                    var elementYCenter = parseFloat(this.bbox().cy) + parseFloat(global.editorObj.selectedGroup.transform().y);
                    switch (parseFloat(type)) {
                        case 0:
                            if (elementXCenter == groupXCenter) {
                                return false;
                            } else {
                                if (elementXCenter > groupXCenter) {
                                    if (margin > 0) {
                                        this.dx((margin + (elementXCenter - groupXCenter) / 2));
                                    } else if (margin < 0) {
                                        this.dx((margin - (elementXCenter - groupXCenter) / 2));
                                    }
                                } else if (elementXCenter < groupXCenter) {
                                    if (margin > 0) {
                                        this.dx(-(margin + (groupXCenter - elementXCenter) / 2));
                                    } else if (margin < 0) {
                                        this.dx(-(margin - (groupXCenter - elementXCenter) / 2));
                                    }
                                }
                            }
                            break;
                        case 1:
                            if (elementYCenter == groupYCenter) {
                                return false;
                            } else {
                                if (elementYCenter > groupYCenter) {
                                    if (margin > 0) {
                                        this.dy((margin + (elementYCenter - groupYCenter) / 2));
                                    } else if (margin < 0) {
                                        this.dy((margin - (elementYCenter - groupYCenter) / 2));
                                    }
                                } else if (elementYCenter < groupYCenter) {
                                    if (margin > 0) {
                                        this.dy(-(margin + (groupYCenter - elementYCenter) / 2));
                                    } else if (margin < 0) {
                                        this.dy(-(margin - (groupYCenter - elementYCenter) / 2));
                                    }
                                }
                            }
                            break;
                        case 2:
                            // TODO both x and y margin
                            break;
                    }
                });
            } else if (!this.selectedGroup) {
                console.log('No elements selected');
            }
        },

        addReflection: function (type) {
            if (this.selectedGroup) {
                this.selectedGroup.each(function () {
                    switch (type) {
                        case "x":
                            var groupXCenter = parseFloat(global.editorObj.selectedGroup.bbox().cx);
                            var elementXCenter = parseFloat(this.bbox().cx) + parseFloat(global.editorObj.selectedGroup.transform().x);
                            if (elementXCenter > groupXCenter) {
                                this.data('reflection', {type: "x", "value": -(elementXCenter - groupXCenter) * 2});
                            } else if (elementXCenter < groupXCenter) {
                                this.data('reflection', {type: "x", "value": (groupXCenter - elementXCenter) * 2});
                            }
                            break;
                        case "y":
                            var groupYCenter = global.editorObj.selectedGroup.bbox().cy;
                            var elementYCenter = parseFloat(this.bbox().cy) + parseFloat(global.editorObj.selectedGroup.transform().y);
                            if (elementYCenter > groupYCenter) {
                                this.data('reflection', {type: "y", "value": -(elementYCenter - groupYCenter) * 2});
                            } else if (elementYCenter < groupYCenter) {
                                this.data('reflection', {type: "y", "value": (groupYCenter - elementYCenter) * 2});
                            }
                            break;
                    }
                });
                this.selectedGroup.each(function () {
                    if (this.data('reflection')) {
                        var type = this.data('reflection').type;
                        var value = this.data('reflection').value;
                        if (type == "x") {
                            this.dx(value);
                        } else if (type == "y") {
                            this.dy(value);
                        }
                        this.data('reflection', null);
                    }
                });
            } else {
                console.log('Nothing for reflection');
            }
        },

        // move current element with control on some value
        moveElement: function (direction, moveValue) {
            if (this.currentElement != null) {
                if($('#move_value').length > 0 && moveValue == undefined) moveValue = Number($('#move_value').val());
                if (!isNaN(moveValue) && moveValue != 0 && !this.isPreviewPage) {
                    switch (direction) {
                        case 'left':
                            this.selectedGroup.dx(-moveValue);
                            break;
                        case 'top':
                            this.selectedGroup.dy(-moveValue);
                            break;
                        case 'right':
                            this.selectedGroup.dx(moveValue);
                            break;
                        case 'bottom':
                            this.selectedGroup.dy(moveValue);
                            break;
                    }
                }
            } else {
                console.log('Nothing for generalMoving');
            }
        },

        // turn current element with control on some degrees
        turnElement: function (direction, type) {
            if (this.selectedGroup) {
                var moveValue;
                switch (type) {
                    case "group":
                        moveValue = Number($('#turn_group_value').val());
                        break;
                }
                if (!isNaN(moveValue) && moveValue != 0 && moveValue <= 360) {
                    switch (type) {
                        case "group":
                            switch (direction) {
                                case 'left':
                                    this.selectedGroup.rotate(parseInt(this.selectedGroup.transform().rotation) - moveValue, this.selectedGroup.cx(), this.selectedGroup.cy());
                                    break;
                                case 'right':
                                    this.selectedGroup.rotate(parseInt(this.selectedGroup.transform().rotation) + moveValue, this.selectedGroup.cx(), this.selectedGroup.cy());
                                    break;
                            }
                            this.selectedGroup.each(function () {
                                this.rotate(0);
                                this.data('rotation_center', {
                                    'x': global.editorObj.selectedGroup.cx(),
                                    'y': global.editorObj.selectedGroup.cy(),
                                    'value': parseInt(global.editorObj.selectedGroup.transform().rotation) - moveValue
                                });
                            });
                            break;
                    }
                } else {
                    console.log('wrong value for turning' + moveValue);
                }
            } else {
                console.log('Nothing for turning');
            }
        },

        createRectangle: function (params, container) {
            var new_rectangle;
            if (container) {
                new_rectangle = container.rect(params.width, params.height).addClass('rect native').fill(params.fill || "#DDDDDD").stroke(params.stroke || 'none').attr('transform', params.transform || null).attr('rx', params.rx || 20);
            } else {
                new_rectangle = this.nodes.rect(params.width, params.height).addClass('rect native').fill(params.fill || "#DDDDDD").stroke(params.stroke || 'none').attr('transform', params.transform || null).attr('rx', params.rx || 20);
            }
            return new_rectangle;
        },

        createLine: function (params, container) {
            var new_line;
            if (container) {
                new_line = container.line(params.x1, params.y1, params.x2, params.y2).addClass('line').fill("none").attr({
                    'stroke': params.stroke,
                    'stroke-width': params.stroke_width != 0 ? params.stroke_width : 1
                });
            } else {
                new_line = this.mainSVG.line(params.x1, params.y1, params.x2, params.y2).addClass('line').fill("none").attr({
                    'stroke': params.stroke,
                    'stroke-width': params.stroke_width != 0 ? params.stroke_width : 1
                });
            }
            return new_line;
        },

        createText: function (params, container) {
            var new_text;
            if (container) {
                new_text = container.text(function (add) {
                    add.plain(params.text)
                }).addClass('text').attr({
                    'transform': params.transform,
                    'stroke': params.stroke || 'none',
                    'fill': params.fill,
                    'font-family': params.font_family,
                    'font-size': params.font_size
                });
            } else {
                new_text = this.mainSVG.text(function (add) {
                    add.plain(params.text)
                }).addClass('text').attr({
                    'transform': params.transform,
                    'stroke': params.stroke || 'none',
                    'fill': params.fill,
                    'font-family': params.font_family,
                    'font-size': params.font_size
                });
            }
            return new_text;
        },

        //give border radius
        addBorderRadius: function () {
            if (this.selectedGroup) {
                var roundValue = Number($('#round_value').val());
                if (!isNaN(roundValue) && roundValue >= 0) {
                    this.selectedGroup.each(function () {
                        this.radius(roundValue);
                    });
                } else {
                    console.log('wrong value for rounding' + moveValue);
                }
            } else {
                console.log('Nothing for rounding');
            }
        },

        // import foreign svg elements
        importSVG: function () {
            var doc = prompt('Paste raw SVG here:')
            if (doc != null && doc != '') {
                this.setDefaultZoom();
                this.setImport(doc, true);
            }
        },

        // setImported svg elements
        setImport: function (doc, reimport) {
            var just_created,
                addedArr = [],
                addedIds = [],
                importingJustSector = true,
                decorativeCleared = false;
            if (reimport){
                this.nodes.move(0, 0);
                this.setDefaultZoom();
                this.deselectElements();
            }
            this.nodes.svg(doc, function (level) {
                if (reimport) {
                    //console.log(this);
                    this.attr({'id':null,'name': this.attr('id')});
                    var oldElemnt = SVG.get(this.attr('name'));
                    var sectorId;
                    var isNative = false;
                    var elementId = this.attr('id') || this.attr('name');
                    if (elementId && elementId.length > 0) {
                        var neededSearch = elementId.search('sector');
                        sectorId = elementId.substring(neededSearch + 6);
                        if (neededSearch >= 0) isNative = true;
                    }
                    var sectorItem = $('#' + sectorId),
                        allowImportNative = sectorItem.length > 0 && sectorItem.parent().hasClass('active');
                    if (oldElemnt != undefined){
                        var attrb = {
                                'id': oldElemnt.attr('id'),
                                'data_label': oldElemnt.attr('data-label'),
                                'data_selectable': oldElemnt.attr('data-selectable'),
                                'data_joined_to': oldElemnt.attr('data-joined-to'),
                                'class': oldElemnt.attr('class'),
                                'data_type': oldElemnt.attr('data-type'),
                                'data_status': oldElemnt.attr('data-status'),
                                'data_price': oldElemnt.attr('data-price'),
                                'data_server_id': oldElemnt.attr('data-server_id'),
                                'sector_id': oldElemnt.attr('sector_id'),
                                'data_quote_id': oldElemnt.attr('data-quote_id'),
                                'data_count': oldElemnt.attr('data-count'),
                                'data_amount': oldElemnt.attr('data-amount')
                            };
                            this.attr({
                                'data-label': attrb.data_label,
                                'data-selectable': attrb.data_selectable,
                                'data-joined-to': attrb.data_joined_to,
                                'class': attrb.class,
                                'data-type': attrb.data_type,
                                'data-status': attrb.data_status,
                                'data-price': attrb.data_price,
                                'data-server_id': attrb.data_server_id,
                                'sector_id': attrb.sector_id,
                                'data-quote_id': attrb.data_quote_id,
                                'data-count': attrb.data_count,
                                'data-amount': attrb.data_amount
                        });
                        if ((this.hasClass('makroControl') && global.editorObj.isMicroView) || (this.hasClass('native') && this.attr('data-type') != 2 && !global.editorObj.isMicroView)){
                            global.editorObj.removeTotaly(this);
                            return;
                        }
                        if (isNative){
                            if (allowImportNative){
                                global.editorObj.removeTotaly(oldElemnt);
                            }else{
                                global.editorObj.removeTotaly(this);
                            }
                        }else{
                            global.editorObj.removeTotaly(oldElemnt);
                        }
                    }else{
                        if (!isNative && !this.hasClass('makroControl')){
                            if (importingJustSector && !decorativeCleared){
                                importingJustSector = false;
                                decorativeCleared = true;
                                var exc = $('.makroControl');
                                if (addedArr.length > 0){
                                    for (var i = 0; i < addedArr.length; i++){
                                        exc = exc.add(addedArr[i].node);
                                    }
                                }
                                console.log(exc);
                                global.editorObj.deleteImported(exc);
                            }
                        }
                        if (isNative && sectorId && sectorId.length > 0) {
                            if (allowImportNative) {
                                this.addClass('native');
                            }
                        } else {
                            this.addClass('imported');
                        }
                        this.data('selectable', true);
                    }
                    if ((this.hasClass('makroControl') && global.editorObj.isMicroView) || (this.hasClass('native') && this.attr('data-type') != 2 && !global.editorObj.isMicroView)) {
                        global.editorObj.removeTotaly(this);
                        return;
                    }
                } else {
                    //console.log('just created');
                    if (this.hasClass('imported')) {
                        this.data('selectable', true);
                    }
                    if (global.editorObj.visualBox && !$.isEmptyObject(global.editorObj.visualBox)) {
                        this.x(this.x() - global.editorObj.visualBox.minX);
                        this.y(this.y() - global.editorObj.visualBox.minY);
                    }
                }
                just_created = this;
                if (just_created != undefined){
                    addedIds.push(this.attr('name') || this.attr('id'));
                    addedArr.push(this);
                    global.createElementHandler(this);
                }
            });
            this.just_created = just_created;
            if (addedArr.length > 0) {
                for (var i = 0; i < addedArr.length; i++) {
                    (function(i){
                        var el = addedArr[i];
                        if (el.attr("onclick") && global.isMobile){
                            el.on("touchstart", function(){
                                if (!$("body").hasClass("app")){
                                    el.addClass("touchstart");
                                }
                            });
                            el.on("touchend", function(){
                                if (el.hasClass("touchstart")){
                                    el.removeClass("touchstart");
                                    global.editorObj.showSingleSector(el.attr('data-joined-to'), false);
                                }
                            });
                        }
                    })(i);
                    if (addedArr[i].attr('id') == undefined || addedArr[i].attr('id') == null) addedArr[i].attr({'id':addedArr[i].attr('name'),'name':null});
                    this.setHoverHandlers(addedArr[i]);
                    if (global.isProductionPage && !global.isMobile){
                        addedArr[i].off("mousedown")
                            .off("mousemove")
                            .off("mouseup");
                        addedArr[i].on("mousedown", window.EventListeners.mouse_down)
                            .on("mousemove", window.EventListeners.mouse_move)
                            .on("mouseup", window.EventListeners.mouse_up);
                    }
                }
            }
            if (window.loader && window.loader.is(':visible')) window.loader.hide();
        },

        removeTotaly: function(inst){
            inst.remove();
            $(inst.node).remove();
        },

        exportSVG: function () {
            this.setDefaultZoom();
            this.nodes.move(0, 0);
            this.setDefaultZoom();
            var exported = this.mainSVG.exportSvg({
                exclude: function () {
                    return this.data('exclude') || global.editorObj.isEmptyBbox(this.bbox());
                },
                whitespace: '\t'
            });
            var w = window.open();
            var wrapper = $('<pre>');
            $(w.document.body).append(wrapper);
            wrapper.text(exported);
        },

        isEmptyBbox: function(bbox){
            return bbox.x == 0 && bbox.y == 0 && bbox.x2 == 0 && bbox.y2 == 0;
        },

        addScene: function () {
            var sceneByIdLength = $('#scene').length,
                sceneByNameLength = $('[name="scene"]').length;
            if (( sceneByIdLength > 0 || sceneByNameLength > 0 || this.scene) && confirm("Scene already exist. Do you want overwrite it ?")) {
                this.removeScene();
                this.scene = this.createRectangle({width: 200, height: 100}).attr({
                    'id': 'scene',
                    'data-selectable': 'true'
                }).fill('yellow').addClass('imported');
            } else if (sceneByIdLength == 0 && sceneByNameLength == 0 && !this.scene) {
                this.scene = this.createRectangle({width: 200, height: 100}).attr({
                    'id': 'scene',
                    'data-selectable': 'true'
                }).fill('yellow').addClass('imported');
            }
        },

        removeScene: function () {
            var sceneById = $('#scene'),
                sceneByName = $('[name="scene"]');
            if (sceneById.length > 0) {
                sceneById[0].instance.remove();
                sceneById.remove();
                delete this.scene;
            } else if (sceneByName.length > 0) {
                sceneByName[0].instance.remove();
                sceneByName.remove();
                delete this.scene;
            } else {
                alert("Scene is not added");
            }
        },

        // check if ctrl key is pressed
        isCrlPressed: function () {
            if (global.keepPreviousSelected) global.editorObj.ctrlPressed = true;
            $(window).keydown(function (e) {
                if (e.keyCode == 46)  global.editorObj.deleteImported($('.makroControl'));
                if ((e.ctrlKey && !this.isMac()) || (e.keyCode == 91 || e.keyCode == 93) && this.isMac()) {
                    global.editorObj.ctrlPressed = true;
                }
            });
            $(window).keyup(function (e) {
                if ((e.keyCode == 17 && !this.isMac()) || (e.keyCode == 91 || e.keyCode == 93) && this.isMac()) {
                    if (!global.keepPreviousSelected) global.editorObj.ctrlPressed = false;
                }
            });
        },


        // check if shift key is pressed
        isShiftPressed: function () {
            $(window).keydown(function (e) {
                if ((e.keyCode == 16 && !this.isMac()) || (e.keyCode == 91 || e.keyCode == 93) && this.isMac()) { // TODO check keycode for mac
                    global.editorObj.shiftPressed = true;
                }
            });
            $(window).keyup(function (e) {
                if ((e.keyCode == 16 && !this.isMac()) || (e.keyCode == 91 || e.keyCode == 93) && this.isMac()) { // TODO check keycode for mac
                    global.editorObj.shiftPressed = false;
                }
            });
        },


        // parse json data to object
        parseJson: function (json) {
            if (json.length != 0) return JSON.parse(json);
            return false;
        },

        // parse element id from net
        parseNetElementId: function (id) {
            if (id.length != 0) {
                var parsedId = {};
                var row = id.search("row");
                var col = id.search("col");
                var sector = id.search("sector");
                parsedId.row = parseInt(id.substring(row + 3, col));
                parsedId.col = parseInt(id.substring(col + 3, sector));
                return parsedId;
            } else {
                console.log("Element id is empty");
                return false;
            }
        },

        // load external data to editor
        setData: function (json, type) {
            var jsonObj = this.parseJson(json);
            if (!$.isEmptyObject(jsonObj)) {
                switch (type) {
                    case "netDump": // json with data from net
                        if (!$.isEmptyObject(jsonObj.scheme.cell.simple_cell)) {
                            this.buildDefaultSeat(jsonObj.scheme.cell.simple_cell, jsonObj);
                        }
                        break;
                    case "visualDump":
                        if (jsonObj.scheme.visual) {
                            this.setImport(jsonObj.scheme.visual, false);
                        }
                        if (!$.isEmptyObject(jsonObj.scheme.cell.simple_cell)) {
                            this.buildDefaultSeat(jsonObj.scheme.cell.simple_cell, jsonObj);
                        }
                        break;
                    case "fun_zone":
                        if (!$.isEmptyObject(jsonObj.visual)) {
                            if (jsonObj.visual) {
                                this.setImport(jsonObj.visual, false);
                            }
                        } else {
                            var fun_zone = this.createRectangle({
                                fill: jsonObj.fill,
                                width: jsonObj.visual.width || 400,
                                height: jsonObj.visual.height || 200
                            }).attr({'sector_id': jsonObj.sector_id, 'id': jsonObj.id, 'rx': 0}).data('type', jsonObj.type != undefined ? jsonObj.type : 2);
                            if (!global.isPreviewPage) fun_zone.attr('data-selectable', 'true');
                            global.createElementHandler(fun_zone);
                            if (window.loader && window.loader.is(':visible')) window.loader.hide();
                        }
                        break;
                }
            } else {
                console.log("Loaded data is empty");
            }

        },

        // build default seat from structure editor
        buildDefaultSeat: function (elementArray, jsonObj) {
            var elementsArray = [];
            var minRow = Number.MAX_VALUE;
            var maxRow = Number.MIN_VALUE;
            var minCol = Number.MAX_VALUE;
            var maxCol = Number.MIN_VALUE;
            for (var i = 0; i < elementArray.length; i++) {
                // find area for trimming from empty cells
                this.parseNetElementId(elementArray[i].id);
                elementsArray[i] = elementArray[i].id;
                var elRow = this.parseNetElementId(elementArray[i].id).row;
                var elCol = this.parseNetElementId(elementArray[i].id).col;
                minRow = (elRow < minRow) ? elRow : minRow;
                maxRow = (elRow > maxRow) ? elRow : maxRow;
                minCol = (elCol < minCol) ? elCol : minCol;
                maxCol = (elCol > maxCol) ? elCol : maxCol;
            }
            var elementsArrayLength = elementsArray.length;
            if (elementsArrayLength > 0) {
                for (var i = 0; i < elementsArrayLength; i++) {
                    // create seats with right width and margins
                    var seat = this.createRectangle({
                        width: global.seatWidth,
                        height: global.seatHeight,
                        fill: elementArray[i].fill
                    }).attr({'id': elementArray[i].id, 'sector_id': jsonObj.id}).data('type', elementArray[i].type);
                    var seatRow = this.parseNetElementId(elementsArray[i]).row;
                    var seatCol = this.parseNetElementId(elementsArray[i]).col;
                    seat.x((global.seatWidth + global.seatMarginRight) * (Math.abs(seatCol - minCol)));
                    seat.y((global.seatHeight + global.seatMarginBottom) * (Math.abs(seatRow - minRow)));
                    if (global.editorObj.visualBox && !$.isEmptyObject(global.editorObj.visualBox)) {
                        seat.x(seat.x() - global.editorObj.visualBox.minX);
                        seat.y(seat.y() - global.editorObj.visualBox.minY);
                    }
                    if (!$.isEmptyObject(elementArray[i].price_info)) this.setPriceInfo(seat, elementArray[i].price_info);
                    if (elementArray[i].label) seat.data('label', elementArray[i].label);
                    if (elementArray[i].quote_id) seat.data('quote-id', elementArray[i].quote_id);
                    if (elementArray[i].selectable) {
                        seat.data('selectable', elementArray[i].selectable);
                    } else {
                        seat.data('selectable', "false");
                    }
                    this.setHoverHandlers(seat);
                    global.createElementHandler(seat);
                }
            } else {
                console.log("There are no elements selected");
            }
            if (window.loader && window.loader.is(':visible')) window.loader.hide();
        },

        // set price info
        setPriceInfo: function (seat, infoObj) {
            seat.data({
                'price': infoObj.price,
                'server_id': infoObj.server_id,
                'status': infoObj.status
            }).attr('fill', infoObj.fill);
            if (!$.isEmptyObject(infoObj.partner)) { // array pf partners
                seat.data('partner_id', infoObj.partner);
                //seat.data('color', infoObj.color); TODO
            }
            if (seat.data('type') == 2) {
                seat.data({
                    'amount': infoObj.amount,
                    'count': infoObj.count,
                    'sold': infoObj.sold_count
                });
            }
        },

        // set hover handlers
        setHoverHandlers: function (el) {
            if (!global.isMobile) {
                el.on('mouseover', function () {
                    if (!global.editorObj.dragSarted && (!global.dragMode || global.isProductionPage)) {
                        var elLabel = el.data('label');
                        if (elLabel && elLabel.length > 0) {
                            global.editorObj.tooltipCont.text(elLabel).css({
                                left: $(el.node).offset().left,
                                top: $(el.node).offset().top - 30
                            }).show();
                        }
                    }
                });
                el.on('mouseout', function () {
                    if (global.editorObj.tooltipCont.is(':visible')) global.editorObj.tooltipCont.hide().text("");
                });
            }
        },

        // initialize cont for elements labels
        createTooltip: function () {
            this.tooltipCont = $('<div class="tooltipCont">');
            $('body').append(this.tooltipCont.hide());
        },

        // parse element id
        parseNetElementId: function (id) {
            if (id.length != 0) {
                var parsedId = {};
                var row = id.search("row");
                var col = id.search("col");
                var sector = id.search("sector");
                parsedId.row = parseInt(id.substring(row + 3, col));
                parsedId.col = parseInt(id.substring(col + 3, sector));
                return parsedId;
            } else {
                console.log("Element id is empty");
                return false;
            }
        },

        // get specific sector
        getSectorData: function (sector_id) {
            var visualDump = $('[sector_id=' + sector_id + ']');
            return visualDump;
        },

        // get all imported elements
        getImportedElements: function () {
            return global.container.find('.imported').add('#scene');
        },

        // delete imported elements
        deleteImported: function (exception) {
            var exc;
            if (exception && exception.length > 0)  exc = exception;
            if (global.container.find('.imported').length > 0) {
                console.log(global.container.find('.imported').not(exc));
                global.container.find('.imported').not(exc).each(function () {
                    global.editorObj.removeTotaly(this.instance);
                });
            }
        },

        // disable selecting html elements
        disableHtmlSelect: function (boolean) {
            if (boolean) {
                $('body').addClass('blocked');
            } else {
                $('body').removeClass('blocked');
            }
        },

        // change visibility of amount info block
        checkAmountBlockStatus: function (selectedElements) {
            if (selectedElements == null) {
                this.changeVisibility(this.amountInfoBlock, false);
            } else {
                if (typeof selectedElements == 'object') {
                    var isFun = this.isJustFunZones(selectedElements);
                    if (isFun) {
                        this.setInfoToAmountBlock(selectedElements);
                        this.changeVisibility(this.amountInfoBlock, true);
                    } else {
                        this.changeVisibility(this.amountInfoBlock, false);
                    }
                }
            }
        },

        // update amount info block
        setInfoToAmountBlock: function (element) {
            var soldAmount = $('#sold_amount'),
                availableAmount = $('#available_amount'),
                totalAmount = $('#total_amount');
            if (element.length != undefined & element.length > 0) { // multiple selected elements
                var soldA = 0,
                    totalA = 0,
                    availableA = 0;
                element.each(function () {
                    if (this.instance.data('type') == global.editorObj.IDD_FAN_ZONE) {
                        soldA += parseInt(this.instance.data('sold'));
                        totalA += parseInt(this.instance.data('count'));
                        availableA += parseInt(this.instance.data('count')) - parseInt(this.instance.data('sold'));
                    }
                });
                soldAmount.text(soldA);
                totalAmount.text(totalA);
                availableAmount.text(availableA);
            } else { // single selected element
                soldAmount.text(element.data('sold') || 0);
                totalAmount.text(element.data('count') || 0);
                availableAmount.text(parseInt(element.data('count')) - parseInt(element.data('sold')) || 0);
            }
        },

        // check if single selected element is fun zone
        isFunzone: function (element) {
            var elementType = element.data('type');
            var isFunZone = false;
            if (elementType == this.IDD_FAN_ZONE) {
                isFunZone = true;
            }
            return isFunZone;
        },

        // check if all selected elements are fun zones
        isJustFunZones: function (selectedElements) {
            var isFun = false;
            if (selectedElements.length != undefined && selectedElements.length > 0) { // multiple selected elements
                selectedElements.each(function () {
                    if ($(this).data('type') == global.editorObj.IDD_FAN_ZONE) {
                        isFun = true;
                        return;
                    }
                });
            } else { // single selected element
                isFun = this.isFunzone(selectedElements);
            }
            return isFun;
        },

        // change element visibility
        changeVisibility: function (element, visible) {
            if (visible) {
                if (element.css('visibility') == "hidden") element.css({
                    opacity: 0.0,
                    visibility: "visible"
                }).animate({opacity: 1.0});
            } else {
                if (element.css('visibility') == "visible") element.css({
                    opacity: 1.0,
                    visibility: "hidden"
                }).animate({opacity: 0.0});
            }
        },

        // get size of all elements box
        getVisualBox: function (getFrom) {
            var minX = 100000,
                minY = 100000,
                maxX = 0,
                maxY = 0,
                allEl;
            if (getFrom) { allEl = getFrom; } else { allEl = $('svg .imported, svg .native').not('.makroControl'); }
            allEl.each(function () {
                var elInst = this.instance;
                var elBox = elInst.bbox();
                if (elBox.x < minX) minX = elBox.x;
                if (elBox.y < minY) minY = elBox.y;
                if (elBox.x2 > maxX) maxX = elBox.x2;
                if (elBox.y2 > maxY) maxY = elBox.y2;
                /*if (minX < 0){
                    maxX += Math.abs(minX);
                    minX += Math.abs(minX);
                }
                if (maxX < 0){
                    minX += Math.abs(maxX);
                    maxX += Math.abs(maxX);
                }
                if (minY < 0){
                    maxY += Math.abs(minY);
                    minY += Math.abs(minY);
                }
                if (maxY < 0){
                    minY += Math.abs(maxY);
                    maxY += Math.abs(maxY);
                }*/
            });
            return {minX: Math.round(minX), minY: Math.round(minY), maxX: Math.round(maxX), maxY: Math.round(maxY)};
        },

        // set size of svg box depending on saved box from visual editor
        setVisualBox: function (box) {
            var cont = $(this.mainSVG.node).parent(),
                w, h;
            if (box) {
                if (!$.isEmptyObject(box) && global.isPreviewPage) {
                    w = box.maxX - box.minX + 160;
                    h = box.maxY - box.minY + 160;
                    cont.width(w);
                    cont.attr({'data-width': w});
                    this.visualBox = box;
                    this.visualBox.minX -= 80;
                    this.visualBox.minY -= 80;
                    if (w < this.svgChildBlock.width()){
                        this.disableWheelZoom = h < this.svgChildBlock.height();
                        this.neededZoom = 1;
                    }else{
                        /*var wZ = this.svgChildBlock.outerWidth() / cont.width(),
                            hZ = this.svgChildBlock.outerHeight() / cont.height();
                        this.neededZoom = wZ < hZ ? wZ : hZ;*/
                        this.neededZoom = this.svgChildBlock.outerWidth() / cont.width();
                        this.disableWheelZoom = false;
                    }
                    if (!global.isMobile){
                        h = this.neededZoom*h;
                    }
                    cont.height(h);
                    cont.attr({'data-height': h});
                    //if (w*this.neededZoom < this.svgChildBlock.width()) this.nodes.x(this.nodes.x() + (this.svgChildBlock.width() - w*this.neededZoom)/2);
                }
            } else {
                cont.css({width: 5000, height: 5000});
                cont.attr({'data-width': 5000, 'data-height': 5000});
            }
            this.checkDragControlsVisibility();
        },

        checkDragControlsVisibility: function () {
            if (!global.isMobile) {
                if (!global.isProductionPage){
                    this.dragControlSelect.show();
                    this.dragControlHand.show();
                }else{
                    this.dragControlSelect.hide();
                    this.dragControlHand.hide();
                }
            }
        },

        changeZoomControlsVisibility: function () {
            if (!global.isMobile) window.editor.editorObj.controlsCont.show();
        },

        setTrimmedZoom: function (customZoom) {
            if (global.isMobile && this.mobileInfo.systemName == 'Android' && this.mobileInfo.systemVersion <= 4.2){
                window.editor.editorObj.neededZoom = 0.4;
                $('head').prepend('<meta name="viewport" content="width=device-width, minimum-scale=1.0 initial-scale=0, minimum-scale=0, maximum-scale=5.0"/>');
            }
            if (customZoom != null) window.editor.editorObj.neededZoom = customZoom;
            if (window.editor.editorObj.neededZoom && window.editor.editorObj.neededZoom < 1) {
                var trimmedValue = window.editor.editorObj.neededZoom.toFixed(2);
                window.editor.editorObj.zoom = trimmedValue;
                window.editor.editorObj.zoomElements(trimmedValue);
                if (window.editor.editorObj.scaleControl) {
                    window.editor.editorObj.scaleControl.val(trimmedValue);
                    if (global.isProductionPage){
                        //window.editor.editorObj.minZoom = trimmedValue;
                        //window.editor.editorObj.scaleControl.attr('min', trimmedValue);
                    }
                }
            } else {
                if (window.editor.editorObj.scaleControl && global.isProductionPage){
                    //window.editor.editorObj.minZoom = 0.7;
                    //window.editor.editorObj.scaleControl.attr('min', 0.7);
                }
            }
        },

        clearPreviousScheme: function(){
            this.svgParentNode.html('');
            $('#controls_cont').remove();
            $('#loader').remove();
        },

        createLoader: function(){
            window.loader = $('<div id="loader">');
            this.svgChildBlock.prepend(window.loader);
            window.loader.show();
        },

        initialize: function () {
            this.clearPreviousScheme();
            this.createLoader();
            this.createEditor();
        }

    };

    global.editorObj.initialize();
    return global;

}