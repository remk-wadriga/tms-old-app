/**
 * Created by elvis on 16.11.14.
 */


function checkPaymentType(payment, contractor_id) {
    console.log(payment);
    console.log(contractor_id);
    if (payment == 1) {
        $("#commision_block_"+contractor_id).css({"display":"block"});
    } else
        $("#commision_block_"+contractor_id).css({"display":"none"});
}

function calculatePayment(percent, contractor_id) {
    var totalSum = $("#totalSum_"+contractor_id).val();
    console.log(totalSum);
    console.log(percent);
    $("#commision_block_"+contractor_id+" .commission").text(Math.round((percent/100)*totalSum));
}

$(document).ready(function() {
    $(".copySector").css({'display':'none'});
    $(".success-sector-alert").css({'display':'none'});
    $("#copySector_id").on("change", function(e){
        var sector_id = $(this).val(),
            formBlock = $(".copySector");
        if (sector_id.length>0) {
            $.post(
                'getSectorForm',
                {
                    sector_id : sector_id
                }, function(result) {
                    var obj = JSON.parse(result);
                    formBlock.find("#Sector_type_sector_id").val(obj.type_sector_id);
                    formBlock.find("#Sector_type_row_id").val(obj.type_row_id);
                    formBlock.find("#Sector_type_place_id").val(obj.type_place_id);
                    formBlock.find("#Sector_places").val(obj.places);
                    if (obj.type == 1) {
                        formBlock.find("#Sector_type_0").click();
                    } else {
                        formBlock.find("#Sector_type_1").click();
                        var places = JSON.parse(obj.places);
                        formBlock.find("#Sector_amount").val(places.fun_zone.amount);
                    }
                }
            );
           formBlock.css({'display':'block'});
        } else
            formBlock.css({'display':'none'});
    });
});