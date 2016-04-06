/**
 * Created by nodosauridae on 02.04.15.
 */
$(document).ready(function() {
    $('#hide-name').bind('keyup',function(e) {
        $("#name").val($(this).val());
        console.log($(this).val());
    });
    $('#hide-pass').bind('keyup',function(e) {
        $("#pass").val($(this).val());
        console.log($(this).val());
    });
});