// delete current scheme
function deleteScheme(form){
    var schemeForm = $(form);
    if (schemeForm.length>0&&confirm("Ви дійсно бажаєти видалити цей сектор?")){
        $.ajax({
            type: "DELETE",
            url: SECTOR_SERVICE,
            data: {
                data : JSON.stringify({
                    info : {
                        sector_id : $('#sector_list').val()
                    }
                })
            },
            dataType: "JSON",
            complete: function(responce) {
               if (responce.responseText.includes('Сектор'))
                   showAlert('danger', responce.responseText, 10000);
               else {
                   schemeForm.find('input[type=text]').each(function(){
                       $(this).val('');
                   });
                   var sectorList = schemeForm.find('#sector_list');
                   sectorList.find('option:selected').remove();
                   sectorList.prop('selectedIndex', 0).trigger('change');
                   window.sectorNet.netObj.removePrevious();
               }
            }
        });

    }
}

// save new scheme

function saveScheme(form){
    var schemeForm = $(form);
    if (validateForm(schemeForm)){
        $.ajax({
            type: "POST",
            url: SECTOR_SERVICE,
            data: {
                data : serializeData(schemeForm)
            },
            dataType: "JSON",
            success: function(result) {
                if (result==false) {
                    showAlert('danger','Такий запис уже існує!');
                    schemeForm.find("#sector_name").addClass('missedValue')
                } else {
                    showAlert('success', 'Успішно збережено!');
                    updateSettingsBlock(result.info);
                }
            }
        });
    }
    return false;
}

// serialize info from form inputs and net
function serializeData(form){
    if (form.length>0) {
        var schemeInfo;
        if ($(form).find('#sector_list').find('option:selected').hasClass('fun_zone')){
            schemeInfo ={
                'id' : form.find('[name=scheme_id]').val(),
                'info' : {
                    "sector_id" : $('#sector_list').val(),
                    "prefix" : $('#prefix').val(),
                    "name" : $('#sector_name').val(),
                    "amount" : $('#fan_amount').val()
                },
                'fun_zone' : true
            }
        }else{
            schemeInfo ={
                'id' : form.find('[name=scheme_id]').val(),
                'info' : {
                    "prefix" : $('#prefix').val(),
                    "sector_id" : $('#sector_list').val(),
                    "name" : $('#sector_name').val(),
                    "row_name": $('#row_name').val(),
                    "col_name": $('#col_name').val()
                },
                'scheme' : JSON.stringify(window.currentNetDump)
            }
        }
        console.log(JSON.stringify(schemeInfo));
        return  JSON.stringify(schemeInfo);
    }else{
        return JSON.stringify(null);
    }
}

function changeSector(select) {
    var sectorId = $(select).val();
    if(!isEmpty(sectorId)) {
        getSectorInfo(select);
    } else {
        $('.sector-description').addClass('hidden');
        $('.sector-buttons').addClass('hidden');
        $('.scheme-container').addClass('hidden');
    }
}

// get info for sector
function getSectorInfo(select){
    var sector_list = $(select),
        scheme_id = $("#scheme_id").val();
    $.ajax({
        type: "GET",
        url: SECTOR_SERVICE + "?scheme_id="+scheme_id,
        data: {

            data : JSON.stringify({
                info: {
                    sector_id : $(select).val()
                },
                all:true
            })
        },
        dataType: "JSON",
        success: function(result) {
            getSectorInfoResponse(result);

        }
    });
}

function updateSettingsBlock(block) {

    var settingsBlock = $(".settingsBlock");
    settingsBlock.html(block);

    $("#prefix").select2();
    $("#row_name").select2();
    $("#col_name").select2();

    $('.sector-description').removeClass('hidden');
    $('.sector-buttons').removeClass('hidden');
    $('.scheme-container').removeClass('hidden');
}

// response for getting sectors
function getSectorInfoResponse(result){
    //var result = JSON.parse(result);

    if (result){

        //var rowInfo = $('#row_name');
        //var colInfo = $('#col_name');
        //var fanAmount = $('#fan_amount');
        // set sectors scheme to net
            // clear localStorage data
        localStorage.clear();
        if ($('.net_container').length>0){
            if ($('.net_container').length>0){
                $('.net_container').html('').show(); // clear previous net and controls
                $('.edit_control_btn,.prev_net_history,.next_net_history,.cell_edit_info_cont,.cell_view_info_cont,.selectInfo').remove();
            }
            if (result.scheme) { // set sectors scheme to net
                // set net for selected sector
                //fanAmount.hide();
                //rowInfo.show().addClass('required');
                //colInfo.show().addClass('required');

                var netProperties = {
                    container: ".net_container",
                    cols: result.scheme.netDimension.cols,
                    rows: result.scheme.netDimension.rows,
                    loadDump: false,
                    netBackgroundColor: '#fcf8e3'
                };
                window.sectorNet = new Net(netProperties);
                window.sectorNet.netObj.setDumpToNet('custom', result.scheme);
                window.sectorNet.netObj.makeNetDump();
            }
            if (result.fun_zone){
                // set disabled net as it is fan zona
                //rowInfo.hide().removeClass('required');
                //colInfo.hide().removeClass('required');
                //fanAmount.show();
                var netProperties = {
                    container: ".net_container",
                    netBackgroundColor: '#fcf8e3',
                    lock: true
                };
                window.sectorNet = new Net(netProperties);
            }
        }

        updateSettingsBlock(result.info);

        //if (result.info){ // set additional info about sector to form
        //    $('#prefix').val(result.info.prefix);
        //    $('#sector_name').val(result.info.name);
        //    $('#row_name').val(result.info.row_name);
        //    $('#col_name').val(result.info.col_name);
        //}
    }
}
