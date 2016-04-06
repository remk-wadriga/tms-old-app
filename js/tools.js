function validateForm(form){
    if (form.length>0){
        var requiredField = form.find('select.required, input.required');
        if (requiredField.length>0){
            requiredField.each(function(){
                var requiredVal = $(this).val().trim();
                if (isEmpty(requiredVal)){
                    $(this).addClass('missedValue');
                }else{
                    if ($(this).hasClass('missedValue')) $(this).removeClass('missedValue');
                }
            });
            return $('.missedValue').length==0;
        }else{
            return true;
        }
    }
}

function isEmpty(val) {
    if (val == "" || val == "undefined" || val == null) return true;
    else return false;

}