// Initializing Net object
// Written by Oleg Vykhopen

function Net(par) {

    var global = {};

    // input settings

    var par = par || {};
    par.lock = par.lock;
    par.container = par.container || 'body';
    par.cell_width = par.cell_width || '35px';
    par.cell_height = par.cell_height || '35px';
    par.cols = par.cols || (Math.floor($(par.container).width() / parseFloat(par.cell_width)-1) );
    par.rows = par.rows || (Math.floor(($(window).height() - $(par.container).offset().top) / parseFloat(par.cell_height)-1));
    par.netBGColor = par.netBackgroundColor || 'transparent';
    par.maxSeats = 5000;

    // class object and its methods

    global.netObj = {

        sector_id: $('[name=sector_list]').val(),

        createBasicNet: function () {

            // add edit mode button
            this.editButton = $('<button type="button" class="btn btn-info btn-sm m-r-sm edit_control_btn">Режим редагування</button>');
            $(par.container).before(this.editButton);
            global.netObj.edit_mode = false; // set default selecting mode
            this.initEvent('click',this.editButton);

            // add button to revert net to previous
            this.prevHistoryButton = $('<button type="button" class="btn btn-primary btn-sm m-r-sm prev_net_history">Попередній стан</button>');
            $(par.container).before(this.prevHistoryButton);
            this.initEvent('click',this.prevHistoryButton);

            // add button to revert net to next
            this.nextHistoryButton = $('<button type="button" class="btn btn-primary btn-sm m-r-lg next_net_history">Наступний стан</button>');
            $(par.container).before(this.nextHistoryButton);
            this.initEvent('click',this.nextHistoryButton);

            this.selectInfoCont = $('<span class="selectInfo">Створено: <strong class="total_rows">0</strong> рядів | <strong class="total_seats">0</strong> місць</span>');
            $(par.container).before(this.selectInfoCont);

            // add container for net
            this.netCont = $('<div>').addClass('net_object');
            this.insertTo(par.container, this.netCont);

            // create block for net settings
            var net_settings = $('<div>').addClass('net_settings').css({'width': par.cell_width, 'height': par.cell_height});
            this.createSettings(net_settings);


            if (par.lock) {
                var controls = $();
                controls.add(global.netObj.editButton).add(global.netObj.prevHistoryButton).add(global.netObj.nextHistoryButton).each(function(){
                    $(this).addClass('disabled').click(function(e){
                        e.preventDefault();
                    });
                });
                $(par.container).addClass('disabled');
            }else{
                $(par.container).removeClass('disabled');
                // create popUp for cell info edit
                this.infoEditCont = $('<div></div>').addClass('cell_edit_info_cont');
                this.insertTo($('body'), this.infoEditCont);

                // create popUp for cell info view
                this.infoViewCont = $('<div></div>').addClass('cell_view_info_cont');
                this.insertTo($('body'), this.infoViewCont);
                this.initEvent('mousemove', $('body'));

            }

            // add rows to net
            for (var i = 0; i <= par.rows; i++) {
                var rowCont = $('<div>').addClass("rowCont");
                if (i == 0) {
                    var header_row = $('<div>').addClass('header_row');
                    this.insertTo(this.netCont, header_row);
                    continue;
                }
                this.insertTo(this.netCont, rowCont);
            }

            // add columns to net
            $('.rowCont,.header_row').each(function (counter, element) {
                for (var i = 0; i <= par.cols; i++) {
                    var cellCont = $('<div>').addClass("cellCont").css({'width': par.cell_width, 'height': par.cell_height});
                    if ($(this).hasClass('header_row')) {
                        if (i == 0) {
                            global.netObj.insertTo($(this), net_settings);
                            global.netObj.initEvent("click", net_settings);
                            continue;
                        }
                        global.netObj.insertTo($(this), cellCont.html('<span>' + i + '</span>'));
                        global.netObj.initEvent("click", cellCont);
                    }
                    if (i == 0) {
                        var firstCellCont = $('<div>').addClass("firstCellCont").css({'width': par.cell_width, 'height': par.cell_height});
                        firstCellCont.attr('id', 'row' + counter + 'col' + i);
                        global.netObj.insertTo($(this), firstCellCont.html('<span>' + counter + '</span>'));
                        global.netObj.initEvent("mousedown", firstCellCont);
                    } else {
                        cellCont.attr('id', 'row' + counter + 'col' + i + 'sector' + global.netObj.sector_id);
                        global.netObj.insertTo($(this), cellCont);
                        global.netObj.initEvent("click", cellCont);
                        global.netObj.initEvent("mousedown", cellCont);
                        global.netObj.initEvent("mouseup", cellCont);
                        global.netObj.initEvent("mouseover", cellCont);
                        global.netObj.initEvent("mouseout", cellCont);
                    }
                }
            });

            // add scroll event for container
            this.initEvent('scroll',par.container);

            // create local storage and set array for future net dumps
            if (!localStorage.key('history')) {
                this.history = [];
                this.historyIndex = -1; // create counter for history traveling
                localStorage.setItem('history', JSON.stringify(this.history));

            } else {
                // get index for net dump revert
                if (!par.revert) {
                    this.historyIndex = JSON.parse(localStorage.getItem('history')).length - 1;
                } else{
                    if (par.historyIndex) this.historyIndex = par.historyIndex; // use index from previous net
                    this.checkHistoryIndex(this.historyIndex); // check if history buttons must be enabled or not
                }
                var currentDump = this.loadNetDump(this.historyIndex); // load revert dump
                this.setDumpToNet('whole',currentNetDump); // set revert dump
            }

            // uncomment if needed empty net set to history
            //if (!par.revert) this.makeNetDump(); // make dump only for real new net but not reverted

            // set net parametrs and compare with container
            this.netCont.width(parseFloat(par.cell_width) * (par.cols+1));
            this.netCont.height(parseFloat(par.cell_height) * (par.rows+1));

            // combine overflow for different net and container correlation
            var toLargeHeight = $(par.container).height() < this.netCont.height();
            var toLargeWidth = $(par.container).width() < this.netCont.width();
            if (toLargeHeight && !toLargeWidth) {
                $(par.container).css({'overflow-y': 'scroll', 'overflow-x': 'visible'});
            } else if (!toLargeHeight && toLargeWidth) {
                $(par.container).css({'overflow-y': 'visible', 'overflow-x': 'scroll'});
            } else if (toLargeHeight && toLargeWidth) {
                $(par.container).css('overflow', 'scroll');
            } else {
                $(par.container).css('overflow', 'hidden');
            }
        },

        // create popup for net settings
        createSettings: function () {
            this.settings_popUp = $('<div>').addClass('net_settings_cont');
            this.settings_popUp.html('<h2>Параметри сітки</h2><div><div class="form-group"><label for="net_rows">Кількість рядків</label><input type="text" id="net_rows"/></div><div class="form-group"><label for="net_cols">Кількість стовпців</label><input type="text" id="net_cols"/></div><a id="reformat_net" class="button btn-default">Змінити сітку</a><p class="change_info"></p>');
            $('body').append(this.settings_popUp);
            global.netObj.initEvent("click", $('body'));
            global.netObj.initEvent("click", this.settings_popUp.find('#reformat_net'));
        },

        // calculate net rows and cols
        netDimension: function () {
            var dimension = {};
            dimension.cols = this.netCont.find('.header_row .cellCont').length;
            dimension.rows = this.netCont.find('.rowCont').length;
            return dimension;
        },

        // set default values to net settings block
        emptySettindsBlock: function () {
            var parent = $('.net_settings_cont');
            var rowInp = parent.find('#net_rows');
            var colInp = parent.find('#net_cols');
            var info = parent.find('.change_info');
            rowInp.val("");
            colInp.val("");
            info.text("").hide();
            rowInp.removeClass('invalid');
            colInp.removeClass('invalid');
        },

        // validation for input fields
        fieldValidation: function (field) {
            var valid = {};
            if (isNaN(field.val()) || !(parseInt(Number(field.val())) == field.val()) || parseInt(field.val()) == 0) {
                valid.msg = 'Використовуйте лише цілочислові значення';
                valid.error = 1;
                valid.isValid = false;
                field.addClass('invalid');
                return valid;
            } else if (field.val().length == 0) {
                valid.msg = 'Поле не заповнене';
                valid.error = 2;
                valid.isValid = false;
                field.addClass('invalid');
                return valid;
            } else if (isNaN(parseInt(field.val()))) {
                valid.msg = 'Поле заповнене пробілами. Введіть необхідне число';
                valid.error = 3;
                valid.isValid = false;
                field.addClass('invalid');
                return valid;
            } else {
                field.removeClass('invalid');
                valid.isValid = true;
                return valid;
            }
        },

        //validation for net settings block
        netSettingsValidation: function (element) {
            var parent = element.parents('.net_settings_cont');
            var rowInp = parent.find('#net_rows');
            var colInp = parent.find('#net_cols');
            var info = parent.find('.change_info');
            if (global.netObj.fieldValidation(rowInp).isValid == true && global.netObj.fieldValidation(colInp).isValid == true) {
                return true;
            } else {
                var rowValid = global.netObj.fieldValidation(rowInp);
                var colValid = global.netObj.fieldValidation(colInp);
                if (rowValid.error == 1 && colValid.error == 1) {
                    info.text(rowValid.msg).show();
                }
                if (rowValid.error == 1 && colValid.error == 2) {
                    colInp.removeClass('invalid');
                    info.text(rowValid.msg).show();
                }
                if (colValid.error == 1 && rowValid.error == 2) {
                    rowInp.removeClass('invalid');
                    info.text(colValid.msg).show();
                }
                if (rowValid.isValid == true && colValid.error == 2) {
                    colInp.removeClass('invalid');
                    return true;
                }
                if (colValid.isValid == true && rowValid.error == 2) {
                    rowInp.removeClass('invalid');
                    return true;
                }
                if ((colValid.error && rowValid.error) == 3) {
                    info.text(colValid.msg).show();
                }
                if ((colValid.error && rowValid.error) == 2) {
                    $('.net_settings_cont').hide();
                    $('.net_settings').removeClass('active');
                    global.netObj.emptySettindsBlock();
                }
                return false;
            }
        },

        // init events for elements
        initEvent: function (event, element) {
            if(!par.lock) {
                switch (event) {
                    case "scroll":
                        var lastScrollLeft = 0;
                        var lastScrollTop = 0;
                        var headerTop = global.netObj.netCont.find('.header_row .cellCont span, .net_settings');
                        var headerLeft = global.netObj.netCont.find('.firstCellCont, .net_settings');
                        $(element).scroll(function (e) {
                            var elementScrollLeft = $(this).scrollLeft();
                            var elementScrollTop = $(this).scrollTop();
                            if (lastScrollLeft != elementScrollLeft) {  // detecting horizontal scroll
                                if (lastScrollLeft < elementScrollLeft) {
                                    console.log("scroll right");
                                } else {
                                    console.log("scroll left");
                                }
                                if (headerLeft.find('span').offset().left < headerLeft.find('span').offset().left + elementScrollLeft) {
                                    headerLeft.each(function () {
                                        $(this).addClass('scrollablex').css({
                                            'left': elementScrollLeft,
                                            'background-color': par.netBGColor
                                        });
                                    });
                                } else {
                                    headerLeft.each(function () {
                                        $(this).removeClass('scrollablex').css({
                                            'left': 0,
                                            'background-color': 'transparent'
                                        });
                                    });
                                }
                                lastScrollLeft = elementScrollLeft;
                            } else if (lastScrollTop != elementScrollTop) { // detecting vertical scroll
                                if (lastScrollTop < elementScrollTop) {
                                    console.log("scroll bottom"); // TODO scroll bottom on cell height
                                } else {
                                    console.log("scroll top"); // TODO scroll top on cell height
                                }
                                if (headerTop.offset().top < headerTop.offset().top + elementScrollTop) {  // make header top cells always visible
                                    headerTop.parents('.header_row').addClass('scrollablex').css({
                                        'top': elementScrollTop,
                                        'background-color': par.netBGColor
                                    });
                                } else {
                                    headerTop.parents('.header_row').removeClass('scrollablex').css({
                                        'top': 0,
                                        'background-color': 'transparent'
                                    });
                                }
                                lastScrollTop = elementScrollTop;
                            }
                        });
                        break;
                    case "click":
                        element.click(function (e) {
                            var setPopUp = global.netObj.settings_popUp;
                            if ($(this).hasClass('net_settings')) {
                                element.toggleClass('active');
                                if (setPopUp.is(':visible')) {
                                    global.netObj.emptySettindsBlock();
                                } else {
                                    setPopUp.find('#net_rows').prop('placeholder', global.netObj.netDimension().rows);
                                    setPopUp.find('#net_cols').prop('placeholder', global.netObj.netDimension().cols);
                                    setPopUp.css({
                                        'left': element.offset().left + element.width() + parseInt(element.css('border-right')) + parseInt(element.css('border-left')) + 5/*if popup has shadowbox-shadow*/,
                                        'top': element.offset().top
                                    });
                                }
                                setPopUp.toggle();
                            }
                            else if (element.attr('id') == "reformat_net") { // click event for save net settings button
                                if (global.netObj.netSettingsValidation(element)) {
                                    var newRows = parseInt(setPopUp.find('#net_rows').val()) || parseInt(setPopUp.find('#net_rows').prop('placeholder'));
                                    var newCols = parseInt(setPopUp.find('#net_cols').val()) || parseInt(setPopUp.find('#net_cols').prop('placeholder'));

                                    if (newRows * newCols > par.maxSeats){
                                        if (!confirm("Розмірність надто велика, що може привести до перевантаження редактора. Продовжити?")){
                                            return false;
                                        }
                                    }

                                    var maxRow = 0;
                                    var maxCol = 0;
                                    var allSelected = $('.cellCont.selected');
                                    if (allSelected.length>0){
                                        allSelected.each(function(){
                                            var parseId = global.netObj.parseNetElementId($(this).prop('id'));
                                            if (parseId.col > maxCol) maxCol = parseId.col;
                                            if (parseId.row > maxRow) maxRow = parseId.row;
                                        });
                                    }

                                    // confirming if new dimension is less then old
                                    if (newRows < maxRow || newCols < maxCol) {
                                        alert("Заборонено. Нова розмірність менша від попередньої, тому дані можуть бути втрачені.");
                                        return false;
                                    } else if (newRows == global.netObj.netDimension().rows && newCols == global.netObj.netDimension().cols) {
                                        $('.net_settings_cont').hide();
                                        $('.net_settings').removeClass('active');
                                        global.netObj.emptySettindsBlock();
                                        return false;
                                    }
                                    // creating new NET and deleting old
                                    var newProperties = {
                                        container: ".net_container",
                                        cols: newCols,
                                        rows: newRows,
                                        netBackgroundColor: par.netBGColor
                                    };
                                    if (global.netObj.removePrevious())new Net(newProperties);
                                }
                            }
                            else if (element.is('body')) {
                                if (setPopUp.is(':visible') && $(e.target).parents('.net_settings_cont').length == 0 && !$(e.target).hasClass('net_settings_cont') && !$(e.target).hasClass('net_settings')) {
                                    $('.net_settings_cont').hide();
                                    $('.net_settings').removeClass('active');
                                    global.netObj.emptySettindsBlock();
                                }
                            }
                            else if (element.attr('id') == "change_cell_info") { // click event for save cell info button
                                global.netObj.saveNewCellInfo(element);
                                global.netObj.currentEditCell.removeClass('tmp_edited');
                                $('.tmp_selected').each(function () {
                                    $(this).removeClass('tmp_selected');
                                });
                                global.netObj.infoEditCont.hide();
                            }
                            else if ($(this).hasClass('edit_control_btn')) { // click event for mode button
                                $(this).toggleClass('active');
                                global.netObj.edit_mode = global.netObj.edit_mode ? false : true;
                                if (global.netObj.edit_mode) {
                                    global.netObj.showEditCells(true);
                                } else {
                                    global.netObj.showEditCells(false);
                                }
                            } else if ($(this).hasClass('prev_net_history')) { // click event for revert to previous dump button
                                if (global.netObj.historyIndex > 0) {
                                    global.netObj.historyIndex--;
                                    global.netObj.checkHistoryIndex(global.netObj.historyIndex);
                                    var prevDump = global.netObj.loadNetDump(global.netObj.historyIndex); // get revert net dump
                                    window.currentNetDump = prevDump;
                                    global.netObj.setDumpToNet('custom', prevDump); // load last net dump
                                }
                            } else if ($(this).hasClass('next_net_history')) { // click event for revert to next dump button
                                if (global.netObj.historyIndex < JSON.parse(localStorage.getItem('history')).length - 1) {
                                    global.netObj.historyIndex++;
                                    global.netObj.checkHistoryIndex(global.netObj.historyIndex);
                                    var nextDump = global.netObj.loadNetDump(global.netObj.historyIndex); // get revert net dump
                                    window.currentNetDump = nextDump;
                                    global.netObj.setDumpToNet('custom', nextDump); // load last net dump
                                }
                            }
                        });

                        break;
                    case "mouseout":
                        element.mouseout(function () {
                            var elementPosition = global.netObj.findElementIndex(element);
                            if (global.netObj.multi_select == true && !element.hasClass('selecting_start')) global.netObj.changeTmpstatus(element, elementPosition); // deselect not completely selected cells
                        });
                        break;
                    case "mouseover":
                        element.css('cursor', 'pointer');
                        element.mouseover(function (e) {
                            if (global.netObj.multi_select == true) {
                                var elementPosition = global.netObj.findElementIndex(element);
                                global.netObj.cursorPosition = global.netObj.changeSelectedStatus(element, elementPosition); // temporary select cells
                                global.netObj.setSelectedAmountInfo();
                            }
                        });
                        break;
                    case "mousedown":
                        element.mousedown(function () {
                            if ($(this).hasClass('cellCont') && $(this).parents('.header_row').length == 0 && !global.netObj.edit_mode) {
                                $(this).hasClass('selected') ? global.netObj.unselect = true : global.netObj.unselect = false;
                                $(this).toggleClass('selected').addClass('selecting_start');
                                global.netObj.start_select_coords = global.netObj.findElementIndex($(this));
                                global.netObj.multi_select = true;
                            }
                            // open popUp for editing cell info
                            else if ($(this).hasClass('cellCont') && $(this).hasClass('selected') && $(this).parents('.header_row').length == 0 && global.netObj.edit_mode) {
                                global.netObj.showCellInfo('edit', element, 'whole');
                            } else if ($(this).hasClass('cellCont') && $(this).parents('.header_row').length > 0 && global.netObj.edit_mode) {
                                global.netObj.showCellInfo('edit', element, 'col');
                            } else if ($(this).hasClass('firstCellCont') && global.netObj.edit_mode) {
                                global.netObj.showCellInfo('edit', element, 'row');
                            }
                        });
                        break;
                    case "mouseup":

                        element.mouseup(function () {
                            $('.header_row .cellCont.selected,.rowCont .firstCellCont.selected').each(function () {
                                $(this).removeClass('selected');
                            });
                            if ($(this).hasClass('cellCont') && $(this).parents('.header_row').length == 0) {
                                if ($(this).hasClass('selecting_start')) $(this).removeClass('selecting_start'); // map start position for selecting
                                if (!$(this).hasClass('selecting_start')) {
                                    var start_point = $('.selecting_start');
                                    start_point.removeClass('selecting_start');
                                    global.netObj.unselect != true ? start_point.addClass('selected') : start_point.removeClass('selected');
                                }
                                global.netObj.multi_select = false;
                                global.netObj.setSelectedAmountInfo();
                                if (!global.netObj.edit_mode) global.netObj.makeNetDump(); // store dump with selected files
                            }
                            // remove all temporary selected cells
                            var temp_select_element = $('.tmp');
                            if (temp_select_element.length > 0) {
                                temp_select_element.each(function () {
                                    $(this).removeClass('tmp');
                                });
                            }
                        });
                        break;
                    case "mousemove":
                        element.mousemove(function (e) {
                            if (element.is('body') && $(e.target).hasClass('cellCont') && $(e.target).parents('.header_row').length == 0) {
                                global.netObj.real_cursor_x_position = e.pageX;
                                global.netObj.real_cursor_y_position = e.pageY;
                                global.netObj.showCellInfo('view', $(e.target)); // show popUp with info about cells
                            } else {
                                global.netObj.infoViewCont.hide();
                            }
                        });
                        break;
                }
            }
        },

        // add elemented to tmp class that are in selected area
        changeSelectedStatus: function (element, elementPosition) {
            element.parent().parent().find('.rowCont').each(function (pC, el) {
                if ((pC + 1 <= global.netObj.start_select_coords.positionX && pC + 1 >= elementPosition.positionX) || (pC + 1 >= global.netObj.start_select_coords.positionX && pC + 1 <= elementPosition.positionX)) {
                    $(el).find('.cellCont').each(function (c, el) {
                        if (((c + 1) >= elementPosition.positionY && (c + 1) <= global.netObj.start_select_coords.positionY) || ((c + 1) <= elementPosition.positionY && (c + 1) >= global.netObj.start_select_coords.positionY)) {
                            if (global.netObj.unselect != true && !$(el).hasClass('selected')) {
                                $(el).addClass('tmp');
                                $(el).addClass('selected');
                            } else if (global.netObj.unselect == true && $(el).hasClass('selected')) {
                                $(el).addClass('tmp');
                                $(el).removeClass('selected');
                            }
                            $(el).parent().find('.firstCellCont').addClass('selected');
                            $('.header_row').find('.cellCont:nth-child(' + (c + 2) + ')').addClass('selected');
                        }
                    });
                }
            });
            return elementPosition;
        },

        // parse element id from net
        parseNetElementId: function (id) {
            if (id.length != 0) {
                var parsedId = {};
                var row = id.search("row");
                var col = id.search("col");
                var sector = id.search("sector");
                parsedId.row = parseInt(id.substring(row + 3, col));
                parsedId.col = parseInt(id.substring(col + 3,sector));
                return parsedId;
            } else {
                console.log("Element id is empty");
                return false;
            }
        },

        // set total selecting results to info container
        setSelectedAmountInfo: function(){
            var selectedResults = this.getSelectingResult();
            this.selectInfoCont.find('.total_rows').text(selectedResults.rows);
            this.selectInfoCont.find('.total_seats').text(selectedResults.seats);
        },

        // count selected rows and
        getSelectingResult: function(){
            var result = {};
            var selected = $(par.container).find('.rowCont .cellCont.selected');
            if (selected.length > 0){
                result.seats = selected.length;
                var curRow = "";
                var counter = 0;
                selected.each(function(){
                    var elRow = global.netObj.parseNetElementId($(this).attr('id')).row;
                    if (curRow != elRow){
                        counter ++;
                        curRow = elRow;
                    }
                });
                result.rows = counter;
            }else{
                result.rows = 0;
                result.seats = 0;
            }
            return result;
        },

        // remove tmp class from elements that leaved selected area
        changeTmpstatus: function (element, elementPosition) {
            element.parent().parent().find('.rowCont').each(function (pC, el) {
                if ((pC + 1 <= global.netObj.cursorPosition.positionX && pC + 1 <= elementPosition.positionX) || (pC + 1 >= global.netObj.cursorPosition.positionX && pC + 1 >= elementPosition.positionX)) {
                    $(el).find('.tmp').each(function (c, el) {
                        if (((c + 1) >= elementPosition.positionY && (c + 1) >= global.netObj.cursorPosition.positionY) || ((c + 1) <= elementPosition.positionY && (c + 1) <= global.netObj.cursorPosition.positionY)) {
                            if (global.netObj.unselect != true) {
                                $(el).removeClass('selected').removeClass('tmp');
                            } else {
                                $(el).addClass('selected').removeClass('tmp');
                            }
                            $(el).parent().find('.firstCellCont').removeClass('selected');
                            $('.header_row').find('.cellCont:nth-child(' + (global.netObj.findElementIndex($(el)).positionY + 1) + ')').removeClass('selected');
                        }
                    });
                }
            });
            return true;
        },

        // find cell position in net based on rows and cols number
        findElementIndex: function (cell) {
            var elementIndex = {};
            if (cell.hasClass('cellCont') && cell.parents('.header_row').length == 0) {  // find position for simple cell
                cell.addClass('current_child');
                // find y-position
                cell.parent().find('.cellCont').each(function (counter, element) {
                    if ($(element).hasClass('current_child')) {
                        elementIndex.positionY = counter + 1;
                        $(element).removeClass('current_child');
                        return false;
                    }
                });
                // find x-position
                cell.parent().addClass('current_child').parent().find('.rowCont').each(function (counter, element) {
                    if ($(element).hasClass('current_child')) {
                        elementIndex.positionX = counter + 1;
                        $(element).removeClass('current_child');
                        return false;
                    }
                });
            }
            else if (cell.hasClass('cellCont') && cell.parents('.header_row').length > 0) { // find position for column header cell
                cell.addClass('current_child');
                cell.parent().find('.cellCont').each(function (counter, element) {
                    if ($(element).hasClass('current_child')) {
                        elementIndex.positionY = counter + 2;
                        $(element).removeClass('current_child');
                        return false;
                    }
                });
            }
            else if (cell.hasClass('.firstCellCont')) { // find position for row header cell
                cell.addClass('current_child');
                cell.parent().parent().find('.rowCont').each(function (counter, element) {
                    var childEl = $(element).find('.current_child');
                    if (childEl.length > 0) {
                        elementIndex.positionX = counter + 2;
                        childEl.removeClass('current_child');
                        return false;
                    }
                });
            }
            return elementIndex;
        },

        // show poUp with cell info or for cell info edit
        showCellInfo: function (type, cell, detail_weight) {
            var defaultRowInfo, defaultColInfo, toRowInsert, toColInsert = "";
            if (cell.attr('row_info')) var newRowInfo = cell.attr('row_info');
            if (cell.attr('col_info')) var newColInfo = cell.attr('col_info');
            if ((!newRowInfo && !newColInfo) || cell.find('span').length > 0) {
                if (detail_weight != ('col' || 'row')) {
                    defaultColInfo = $($('.header_row .cellCont')[this.findElementIndex(cell).positionY - 1]).find('span').text();
                    defaultRowInfo = cell.parent().find('.firstCellCont span').text();
                } else if (detail_weight == 'row') {
                    defaultRowInfo = cell.find('span').text();
                } else if (detail_weight == 'col') {
                    defaultColInfo = cell.find('span').text();
                }
                toRowInsert = defaultRowInfo;
                toColInsert = defaultColInfo;
            } else {
                defaultRowInfo = cell.parent().find('.firstCellCont span').text();
                defaultColInfo = $($('.header_row .cellCont')[this.findElementIndex(cell).positionY - 1]).find('span').text();
                if (newRowInfo) {
                    toRowInsert = newRowInfo;
                } else {
                    toRowInsert = defaultRowInfo;
                }
                if (newColInfo) {
                    toColInsert = newColInfo;
                } else {
                    toColInsert = defaultColInfo;
                }
            }
            switch (type) {
                case "view": // show only cell info
                    this.infoViewCont.show();
                    // view popUp position
                    if ((this.real_cursor_x_position + this.infoViewCont.outerWidth())>($(par.container).outerWidth()+$(par.container).offset().left - this.infoViewCont.outerWidth()/2)){
                        this.infoViewCont.css({'left': this.real_cursor_x_position - cell.outerWidth() - this.infoViewCont.outerWidth()});
                    } else{
                        this.infoViewCont.css({'left': this.real_cursor_x_position + cell.outerWidth()});
                    }
                    if (this.real_cursor_y_position + this.infoViewCont.outerHeight() > $(par.container).outerHeight()+$(par.container).offset().top){
                        this.infoViewCont.css({'top': this.real_cursor_y_position - this.infoViewCont.outerHeight()});
                    }else{
                        this.infoViewCont.css({'top': this.real_cursor_y_position});
                    }
                    this.infoViewCont.html('<p>Ряд: ' + toRowInsert + '</p><p>Місце: ' + toColInsert + '</p>');
                    break;
                case "edit": // show cell edit popUp
                    this.currentEditCell = cell;
                    this.resetCellInfo(cell);
                    if (cell.hasClass('firstCellCont') || cell.parents('.header_row').length > 0) var isHeaderCell = true;
                    if (!isHeaderCell) {
                        var row_parent_cell = $(this.netCont.find('.header_row .cellCont')[this.findElementIndex(cell).positionY - 1]);
                        var col_parent_cell = cell.parent().find('.firstCellCont');
                    }
                    // position for edit info popUp
                    if (cell.offset().left + cell.outerWidth() + this.infoEditCont.outerWidth()> $(par.container).outerWidth()+$(par.container).offset().left){
                        this.infoEditCont.css({'left': cell.offset().left - this.infoEditCont.outerWidth()});
                    }else{
                        this.infoEditCont.css({'left': cell.offset().left + cell.outerWidth()});
                    }
                    if (cell.offset().top + this.infoEditCont.outerHeight() > $(par.container).outerHeight()+$(par.container).offset().top){
                        this.infoEditCont.css({'top': cell.offset().top - this.infoEditCont.outerHeight()});
                    }else{
                        this.infoEditCont.css({'top': cell.offset().top});
                    }
                    if (cell.hasClass('tmp_edited')) {
                        this.infoEditCont.toggle();
                        if (!isHeaderCell) {
                            col_parent_cell.removeClass('tmp_selected'); // not highlight header cells when edit info popUp is opened
                            row_parent_cell.removeClass('tmp_selected');
                        }
                    } else {
                        this.infoEditCont.show();
                        if (!isHeaderCell) {
                            col_parent_cell.addClass('tmp_selected'); // highlight header cells when edit info popUp is opened
                            row_parent_cell.addClass('tmp_selected');
                        }
                    }
                    cell.toggleClass('tmp_edited');
                    switch (detail_weight) {
                        case 'whole': // popUp for editing cell info
                            this.infoEditCont.html('<h2>Інформація про місце</h2><div><div class="form-group"><label for="row_info">Ряд</label><input type="text" id="row_info" placeholder="' + toRowInsert + '"/></div><div class="form-group"><label for="col_info">Місце</label><input type="text" id="col_info" placeholder="' + toColInsert + '" /></div><button id="change_cell_info" type="button" class="btn btn-success btn-xs whole">Редагувати</button><p class="change_info"></p></div>');
                            break;
                        case 'col': // popUp for editing col parent info
                            this.infoEditCont.html('<h2>Інформація про стовпець</h2><div><div class="form-group"><label for="col_info">Місце</label><input type="text" id="col_info" placeholder="' + toColInsert + '" /></div><a id="change_cell_info" class="button btn-default col">Редагувати</a><p class="change_info"></p></div>');
                            break;
                        case 'row': // popUp for editing row parent info
                            this.infoEditCont.html('<h2>Інформація про рядок</h2><div><div class="form-group"><label for="row_info">Ряд</label><input type="text" id="row_info" placeholder="' + toRowInsert + '"/></div><a id="change_cell_info" class="button btn-default row">Редагувати</a><p class="change_info"></p></div>');
                            break
                    }
                    global.netObj.initEvent('click', this.infoEditCont.find('#change_cell_info'));
                    break;
            }
        },

        // reset previous editInfoCont classes
        resetCellInfo: function (cell) {
            var not_remove = cell.hasClass('tmp_edited');
            $('.tmp_edited').each(function () {
                $(this).removeClass('tmp_edited');
            });
            $('.tmp_selected').each(function () {
                $(this).removeClass('tmp_selected');
            });
            if (not_remove) cell.addClass('tmp_edited');
        },

        // save new cell info from edit popUp

        saveNewCellInfo: function (element) {
            var rowEditEl = element.parents('.cell_edit_info_cont').find('#row_info');
            var colEditEl = element.parents('.cell_edit_info_cont').find('#col_info');
            var wasInfoChanged = false;
            var rowEditElText, colEditElText = "";

            // validation for typed new info
            if (rowEditEl.length > 0) {
                var rowEditElVal = rowEditEl.val();
                if ((rowEditElVal != "") && (rowEditElVal != rowEditEl.attr('placeholder')) /*|| ((rowEditElVal != "") && !element.hasClass('whole'))*/) {
                    rowEditElText = rowEditEl.val();
                    wasInfoChanged = true;
                }
            }
            if (colEditEl.length > 0) {
                var colEditElVal = colEditEl.val();
                if ((colEditElVal != "") && (colEditElVal != colEditEl.attr('placeholder')) /*|| ((rowEditElVal != "") && !element.hasClass('whole'))*/) {
                    colEditElText = colEditElVal;
                    wasInfoChanged = true;
                }
            }
            if (element.hasClass('whole')) { // save info for whole cell
                if (wasInfoChanged) {
                    if (rowEditElText != "") global.netObj.currentEditCell.attr('row_info', rowEditElText);
                    var isDuplicated = false;
                    global.netObj.currentEditCell.parent().find('.cellCont').not('.firstCellCont').each(function(pos, el){ // prevent dublicated names fot seat in one row
                        if ($($(par.container).find('.firstCellCont')[pos]).text() == colEditElText){
                            isDuplicated = true;
                            return;
                        }
                    });
                    if (!isDuplicated){
                        if (colEditElText != "")global.netObj.currentEditCell.attr('col_info', colEditElText);
                    }else{
                        alert("В цьому ряді місце з такою назвою вже існує.");
                        return false;
                    }
                }
            } else if (element.hasClass('col')) { // save info for col header
                var isDuplicated = false;
                global.netObj.currentEditCell.parent().find('.cellCont').each(function(){ // prevent dublicated names fot net cols
                    if ($(this).text() == colEditElText){
                        isDuplicated = true;
                        return;
                    }
                });
                if (!isDuplicated) {
                    if (wasInfoChanged) {
                        this.currentEditCell.find('span').text(colEditElText);
                        global.netObj.currentEditCell.attr('col_info', colEditElText);
                        this.clearCellInfo(global.netObj.currentEditCell.parent().parent().find('.rowCont .cellCont:nth-child(' + this.findElementIndex(global.netObj.currentEditCell).positionY + ')'), 'col');
                    }
                }else{
                    alert('Стовпець з такою назвою вже існує');
                    return false;
                }
            } else if (element.hasClass('row')) { // save info for row header
                if (wasInfoChanged) {
                    var isDuplicated = false;
                    $(par.container).find('.firstCellCont').each(function(){ // prevent dublicated names fot net rows
                        if ($(this).text() == rowEditElText){
                            isDuplicated = true;
                            return;
                        }
                    });
                    if (!isDuplicated){
                        this.currentEditCell.find('span').text(rowEditElText);
                        global.netObj.currentEditCell.attr('row_info', rowEditElText);
                        this.clearCellInfo(global.netObj.currentEditCell.parent().find('.cellCont'), 'row');
                    }else{
                        alert('Ряд з такою назвою вже існує');
                        return false;
                    }
                }
            }
            if (wasInfoChanged) {
                global.netObj.currentEditCell.addClass('edited');
                global.netObj.makeNetDump(); // store dump with edited files
            }
        },

        // delete cell new info
        clearCellInfo: function (cell, type) {
            if (cell.length > 0) {
                cell.each(function () {
                    if (type == 'row') {
                        $(this).removeAttr('row_info');
                    } else if (type == 'col') {
                        $(this).removeAttr('col_info');
                    }
                    else {
                        $(this).removeAttr('row_info');
                        $(this).removeAttr('col_info');
                    }
                    if (!$(this).attr('row_info') && !$(this).attr('col_info')) {  // check if cell has still unique info
                        $(this).removeClass('edited');
                    }
                });
            }
        },

        // show or hide edited cells or row/col headers
        showEditCells: function (bool) {
            if (bool) {
                global.netObj.netCont.find('[row_info],[col_info]').each(function(){
                    if ($(this).hasClass('selected') || $(this).hasClass('firstCellCont') || $(this).parents('.header_row').length > 0){
                        $(this).addClass('edited');
                    }else{
                        $(this).removeAttr('row_info');
                        $(this).removeAttr('col_info');
                    }
                });
            } else {
                $('.tmp_edited,.edited,.tmp_selected').each(function () {
                    $(this).removeClass('tmp_selected');
                    $(this).removeClass('tmp_edited');
                    $(this).removeClass('edited');
                });
                this.infoEditCont.hide();
            }
        },

        // REVERT FUNCTIONALITY

        // net status dump
        makeNetDump: function () {  // form json with actual edited or selected cells + net dimension + current net mode
            this.deleteNetDump(this.historyIndex+1,JSON.parse(localStorage.getItem('history')).length-this.historyIndex);
            var jsonObj = {};
            jsonObj.netDimension = {};
            jsonObj.netDimension.rows = this.netDimension().rows;
            jsonObj.netDimension.cols = this.netDimension().cols;
            jsonObj.cell = {};
            var cellsForSaving = $('.selected,[row_info],[col_info]');
             // add to dump all selected or edited cells and map their info
            if (cellsForSaving.length > 0) {
                jsonObj.cell.header_col_cell = [];
                jsonObj.cell.header_row_cell = [];
                jsonObj.cell.simple_cell = [];
                cellsForSaving.each(function () {
                    var cellInfo = {};
                    cellInfo.id = $(this).attr('id');
                    if ($(this).hasClass('selected')) {
                        cellInfo.selected = true;
                    }
                    if ($(this).attr('col_info') || $(this).attr('row_info')) {
                        cellInfo.edited = {};
                        if ($(this).attr('row_info')) {
                            cellInfo.edited.row_info = $(this).attr('row_info');
                        }
                        if ($(this).attr('col_info')) {
                            cellInfo.edited.col_info = $(this).attr('col_info');
                        }
                    }
                    if ($(this).hasClass('firstCellCont')) {
                        jsonObj['cell'].header_row_cell.push(cellInfo);
                    } else if ($(this).parents('.header_row').length > 0) {
                        jsonObj['cell'].header_col_cell.push(cellInfo);
                    } else {
                        jsonObj['cell'].simple_cell.push(cellInfo);
                    }
                });
            } else {
                console.log('There are no cells selected or edited');
            }
            jsonObj.netMode = global.netObj.edit_mode; // set net mode to history
            this.historyIndex++;
            this.checkHistoryIndex(this.historyIndex);
            return this.pushDumpToStorage(jsonObj); // add json to history
        },

        // write net dump to local storage
        pushDumpToStorage: function (jsonObj) {
            console.log(JSON.stringify(jsonObj));
            window.currentNetDump = jsonObj;
            //console.log(JSON.stringify(window.currentNetDump));
            var currentHistory = JSON.parse(localStorage.getItem('history'));
            currentHistory.push(jsonObj);
            localStorage.setItem('history', JSON.stringify(currentHistory));
        },

        // load net from history
        loadNetDump: function (counter) {
            var currentSavedDumpsArray = JSON.parse(localStorage.getItem('history'));
            if (counter>=0){
                return currentSavedDumpsArray[counter]; // return some element from history
            }else{
                return currentSavedDumpsArray; // return all net history
            }
        },

        // delete net dumps from history
        deleteNetDump: function(counter,county){
            var currentHistory = JSON.parse(localStorage.getItem('history'));
            currentHistory.splice( counter, county );
            localStorage.setItem('history', JSON.stringify(currentHistory));
        },

        // set dump to net
        setDumpToNet: function (type,dump) {
            if (typeof dump == "object") {
                this.clearNet();
                for (var i in dump) {
                    if (i == "netDimension" && type=='custom'){
                        if ((dump[i].rows != this.netDimension().rows) || ((dump[i].cols != this.netDimension().cols))){
                            var newProperties = {
                                container: ".net_container",
                                cols: dump[i].cols,
                                rows: dump[i].rows,
                                revert: true,
                                historyIndex: this.historyIndex,
                                netBackgroundColor: par.netBGColor
                            };
                            if (global.netObj.removePrevious())new Net(newProperties);
                            return false;
                        }
                    }
                    if (i == "cell") {
                        for (var j in dump[i]) {
                            if (dump[i][j].length > 0) {
                                for (var k = 0; k < dump[i][j].length; k++) {
                                    if (dump[i][j][k].edited) {
                                        this.setEditOptions(dump[i][j][k]);
                                    }
                                    if (dump[i][j][k].selected) {
                                        this.setSelectOptions(dump[i][j][k]);
                                    }
                                }
                            }
                        }
                    }
                    if (i == "netMode") {
                        if (dump[i]){
                            this.showEditCells(true);
                            this.editButton.addClass('active');
                            this.edit_mode = true;
                        }else{
                            this.showEditCells(false);
                            this.editButton.removeClass('active');
                            this.edit_mode = false;
                        }
                    }
                }
                this.setSelectedAmountInfo();
            }
        },

        // clear previous net status
        clearNet: function(){
            $('.selected').each(function(){
                $(this).removeClass('selected');
            });
            $('[row_info],[col_info]').each(function(){
                $(this).removeAttr('row_info');
                $(this).removeAttr('col_info');
                $(this).removeClass('edited');
            });
        },

        // set edited class from dump history
        setEditOptions: function (editedObj) {
            var objId = editedObj.id;
            var netElement = $('#' + objId);
            var netElementChild = netElement.find('span');
            if (netElement.length>0){
                if (editedObj.edited.row_info) {
                    netElement.attr('row_info', editedObj.edited.row_info);
                    if (netElementChild.length > 0) {
                        netElementChild.text(editedObj.edited.row_info);
                    }
                }
                if (editedObj.edited.col_info) {
                    netElement.attr('col_info', editedObj.edited.col_info);
                    if (netElementChild.length > 0) {
                        netElementChild.text(editedObj.edited.col_info);
                    }
                }
            }
        },

        // set selected class from dump history
        setSelectOptions: function (editedObj) {
            var objId = editedObj.id;
            var netElement = $('#' + objId);
            if (netElement.length>0) netElement.addClass('selected');
        },

        // check history index
        checkHistoryIndex: function(index){ // correct disabling
            // check if index is higher then history length
            if (index>=JSON.parse(localStorage.getItem('history')).length-1){
                this.nextHistoryButton.addClass('disabled');
            }else{
                this.nextHistoryButton.removeClass('disabled');
            }
            //check if index is  equal 0
            if (index == 0){
                this.prevHistoryButton.addClass('disabled');
            }else{
                this.prevHistoryButton.removeClass('disabled');
            }
        },

        insertTo: function (cont, elem) {
            $(cont).append(elem);
        },

        // remove previous net's elements
        removePrevious: function () {
            this.infoEditCont.remove();
            this.infoViewCont.remove();
            this.prevHistoryButton.remove();
            this.nextHistoryButton.remove();
            this.editButton.remove();
            this.selectInfoCont.remove();
            this.settings_popUp.remove();
            global.netObj.netCont.remove();
            return true;
        },

        // hide net container
        hideNet: function(){
            $(par.container).hide();
            this.prevHistoryButton.hide();
            this.selectInfoCont.hide();
            this.nextHistoryButton.hide();
            this.editButton.hide();
        },

        // show net container
        showNet: function(){
            this.prevHistoryButton.show();
            this.nextHistoryButton.show();
            this.selectInfoCont.show();
            this.editButton.show();
            $(par.container).show();
        },

        initialize: function () {
            this.createBasicNet();
        }

    };

    global.netObj.initialize();
    return global;

    // class methods


}