function showAlert(type, text, time) {
    time = typeof time !== 'undefined' ? time : 6000;
    text = typeof text !== 'undefined' ? text : '';
    type = typeof type !== 'undefined' ? type : 'info';
    var element = $("<div class=\"alert alert-"+type+" alert-dismissible\" role=\"alert\" style=\"display: block;\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"закрити\"><span aria-hidden=\"true\">×</span></button>"+text+"</div>");
    element.appendTo(".alert-block");
    if (time!=0)
        setTimeout(function(){element.fadeOut()}, time);
}

if ($.fn.select2) {
    function select2_param(type){
        var css_class = "select2-custom";
        var format_select_name = format_select;
        if(type=='event'){
            css_class += " select2-event";
            format_select_name = format_select_event;
        }
        var params_ext = {
            containerCssClass: css_class,
            placeholder: "Пошук події...",
            templateResult: format,
            templateSelection: format_select_name,
            "language": {
                "noResults": function(){
                    if (type=='new_event')
                        return 'Подію не знайдено... <a href="/event/event/create" class="btn btn-xs btn-success">Додати подію</a>';
                    return false;
                }
            },
            escapeMarkup: function (m) {return m;}
        };
        return params_ext;
    }

    $(".to-select2").select2({
        containerCssClass: "select2-custom",
        formatNoMatches: 'adasdasdasd'
    });

    function format(event) {
        var el = event.element;
        if(!$(el).data('date')&&!$(el).data('city')&&!$(el).data('location')){
            return '<div class="item"><div class="title">'+event.text+'</div></div>';
        }else{
            return '<div class="item"><div class="title">'+event.text+'</div><div class="info"></div>'+$(el).data('date')+'<span>'+$(el).data('city')+'</span>, '+$(el).data('location')+'</div></div>';
        }
    }
    function format_select(event) {
        var el = event.element;
        return event.text;
    }
    function format_select_event(event) {
        var el = event.element;
        if(!$(el).data('date')&&!$(el).data('city')&&!$(el).data('location')){
            return event.text;
        }else{
            return '<b>'+event.text+'</b><span>'+$(el).data('city')+', '+$(el).data('location')+', '+$(el).data('date')+'</span>';
        }
    }
    $(".to-select2-ext").select2(select2_param('default'));
    $(".to-select2-ext.select2-event").select2(select2_param('event'));
    $(".to-select2-ext.select2-new-event").select2(select2_param('new_event'));
}

function alertTimeout(wait){
    wait = typeof wait !== 'undefined' ? wait : 6000;
    setTimeout(function(){
        $('.alert-block').children('.alert:first-child').fadeOut(function(){
            $(this).remove();
        });
    }, wait);
}

function cartResize() {
    if($('.cart-wrap').length > 0){
        $('.cart-wrap').height($(window).height()-$('.cart-wrap').offset().top-20);
        $('.cart-wrap .content').height($('.cart-wrap').height()-211);
    }
}

function spinner_load() {
    $(".loading").show();
}

function spinner_close() {
    $(".loading").hide();
}

$(document).ready(function(){
    $(document).on("change", "input[type=number]", function(){
        if ($(this).val() > parseInt($(this).attr('max'))) {
            $(this).val(parseInt($(this).attr('max')));
        }
        if ($(this).val() < parseInt($(this).attr('min'))) {
            $(this).val(parseInt($(this).attr('min')));
        }
    });



    $(".alert-block .alert").fadeIn();

    $('#event-fav-btn').click(function() {
        $('#event-fav').slideToggle();
    });
    $('#block-hover-bt').click(function() {
        $('#block-hover').slideToggle();
    });
    $('#cart-hover-bt').click(function() {
        $('#cart-hover').slideToggle();
    });

    $('.cart-hide').click(function() {
        $('.cart-block').animate({'right': 0}, 300);
        $('.cart-hide').animate({'opacity': 0}, 300);
    });
    $('.cart-hide-button').click(function() {
        $('.cart-block').animate({'right': -500}, 300);
        $('.cart-hide').animate({'opacity': 1}, 300);
    });

    $('.input-daterange').datepicker({
        language: 'uk'
    });

    $('#filter-less-button').click(function(){
        $('#filter-more').show();
        $('#filter-less').hide();
    });
    $('#filter-more-button').click(function(){
        $('#filter-less').show();
        $('#filter-more').hide();
    });


    $(window).resize(function() {
        if($('.net_container').length > 0){
            $('.net_container').height($(window).height()-$('.net_container').offset().top-45);
        }
        if($('#svg_overflow').length > 0){
            $('#svg_overflow').height($(window).height()-$('#svg_overflow').offset().top-20);
        }
        cartResize();
    });


    if($('.net_container').length > 0 && $('.scheme-container').length > 0){
        $('.scheme-container').removeClass('hidden');
        $('.net_container').show();
        $('.net_container').height($(window).height()-$('.net_container').offset().top-45);
        $('.net_container').hide();
        $('.scheme-container').addClass('hidden');
    }

    if($('#svg_overflow').length > 0){
        $('#svg_overflow').height($(window).height()-$('#svg_overflow').offset().top-20);
    }
    cartResize();
    $('.cart-wrap').bind("DOMSubtreeModified",function(){
        cartResize();
    });

    $("#sectors_list").draggable();

    $(document).on("keydown",".input-number", function(e){
        if ($.inArray(e.keyCode, [8, 9, 27, 13]) !== -1 ||
            (e.keyCode == 65 && e.ctrlKey === true) ||
            (e.keyCode == 67 && e.ctrlKey === true) ||
            (e.keyCode == 88 && e.ctrlKey === true) ||
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


});