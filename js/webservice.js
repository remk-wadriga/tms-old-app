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
                updateSettingsBlock(result.info);
            }
        });
    }
    return false;
}

// serialize info from form inputs and net
function serializeData(form){
    if (form.length>0) {
        var schemeInfo ={
            'id' : form.find('[name=scheme_id]').val(),
            'info' : {
                "sector_id": $('#sector_list').val(),
                "prefix" : $('#prefix').val(),
                "name" : $('#sector_name').val(),
                "row_name": $('#row_name').val(),
                "col_name": $('#col_name').val()
            },
            'scheme' : JSON.stringify(window.currentNetDump)
        }
        return  JSON.stringify(schemeInfo);
    }else{
        return JSON.stringify(null);
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
}

// response for getting sectors
function getSectorInfoResponse(result){
    //var result = JSON.parse(result);
    if (result){
        if (result.scheme){ // set sectors scheme to net
            // clear localStorage data
            localStorage.clear();
            if ($('.net_container').length>0){
                if ($('.net_container').length>0){
                    $('.net_container').html(''); // clear previous net and controls
                    $('.edit_control_btn,.prev_net_history,.next_net_history').remove();
                }

                // set net for selected sector
                var netProperties = {
                    container : ".net_container",
                    cols: result.scheme.netDimension.cols,
                    rows: result.scheme.netDimension.rows,
                    loadDump: false,
                    netBackgroundColor: '#fcf8e3'
                };
                window.sectorNet = new Net(netProperties);
                console.log(result.scheme);
                window.sectorNet.netObj.setDumpToNet('custom',result.scheme);
                window.sectorNet.netObj.makeNetDump();
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
