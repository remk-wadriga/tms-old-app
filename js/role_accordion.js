/**
 * Created by elvis on 16.04.15.
 */
function checkChild(checkBox) {
    checkBox.parent().next(".children").find("input[type=checkbox]").each(function(){
        $(this).prop("checked", checkBox.is(":checked"))
    });
}

function checkParent(parent) {
    var flag = true;
    parent.next(".children").find("input[type=checkbox]").each(function(){
        if ($(this).is(":checked")==false)
            flag = false;
    });
    parent.find("input[type=checkbox]").prop("checked", flag);

}

function showTypeCount(id) {
    $("#"+id+"_type").parents(".typeCount").toggle();
}

$(document).ready(function() {
    $(".roleRelation .typeCount.hideBlock").css({"display":"none"});
    $(".modules-list .children").css({"display":"none"});
    $(".modules-list .parent").on("click", function(e) {
        if (e.target.className != "parent" && e.target.className != "parent moduleParent")
            return;
        var _this = $(this),
            nextChild = _this.next('.children');
        if (nextChild.is(":visible")) {
            nextChild.find('.children').each(function(){
                if ($(this).is(":visible"))
                    $(this).toggle(200);
            });
            nextChild.toggle(200);
        } else {
            nextChild.toggle(200);
        }
    });

    $(".parent").each(function(){
        checkParent($(this));
    });
    $(".moduleParent").each(function(){
        checkParent($(this));
    });

    $("#Role_templatesList").find("input").on("click", function(e) {
        var _this = $(this),
            url = $(this).data('url');
        if (_this.is(":checked"))
            $.post(url,
                {
                    template_id : _this.val()
                }, function(result) {
                    var obj = JSON.parse(result);
                    for (var i=0; i<obj.length; i++)
                        $("#"+obj[i]).prop("checked", true);
                    $(".parent").each(function() {
                        checkParent($(this));
                    });
                }
            );
    });

    $("#Role_entity").on("change", function() {
        $("#Role_legal_detail").prop("disabled", !$(this).is(":checked"));
        $("#Role_company_name").prop("disabled", !$(this).is(":checked"));
        $("#Role_code_yerdpou").prop("disabled", !$(this).is(":checked"));
        $("#Role_post").prop("disabled", !$(this).is(":checked"));
        $("#Role_real_name").prop("disabled", !$(this).is(":checked"));
    });
});