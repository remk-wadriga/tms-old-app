/**
 * Created by elvis on 28.07.15.
 */

function getFunPlaces() {
    return 2
}

function updateCart(places) {
    $(".cart").parent().html(JSON.parse(places));
}

function addToCart(element) {
    $.post($("#svg_cont").attr("data-carturl"),
        {
            places: JSON.stringify(element)
        },function(result){
            updateCart(result);
        });
}

function selectHandler(currentElements, custom){
    if (customSelectHendler != undefined && typeof customSelectHendler  === "function")
        if (!customSelectHendler(currentElements))
            return;
    if (currentElements != null) {
        var result = [],
            el = $(window.editor.editorObj.singleClickElement);
        if (el && el.length > 0) {
            var node = $(window.editor.editorObj.singleClickElement.node);
            if (!custom)
                if (node.attr("data-type")==2) {

                    addToCart({
                        id: node.attr("id"),
                        event_id: $("#event_id").val(),
                        sector_id: node.attr("sector_id"),
                        type: node.attr("data-type")
                    });
                } else if (node.attr("data-type")==1) {
                    addToCart({
                        id: node.attr("data-server_id"),
                        //server_id: node.attr("data-server_id"),
                        type: node.attr("data-type")
                    })
                }

        } else if (el.length == 0){
            for(var i = 0; i< currentElements.length; i++) {
                var cur = $(currentElements[i]);
                result.push({
                    id: cur.attr('id'),
                    server_id: cur.attr('data-server_id'),
                    type: cur.attr('data-type')
                })
            }
        }


    }else{
        if (currentElements == null && $('.sector_item a.act').length > 0){
            $('.sector_item a.act').removeClass('act');
        }
    }
}

function createElementHandler(createdElement){
    if (window.price_constructor != null && window.price_constructor != undefined){
        window.price_constructor.defineNotUsedElement(createdElement);
    }
}


function isMobile(){
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function isMac(){
    return navigator.platform.match(/(Mac|iPhone|iPod|iPad)/i)?true:false;
}

function outerHTML(node){
    // if IE, Chrome take the internal method otherwise build one
    return node.outerHTML || (
            function(n){
                var div = document.createElement('div'), h;
                div.appendChild( n.cloneNode(true) );
                h = div.innerHTML;
                div = null;
                return h;
            })(node);
}


function schemeLoadingListener(){
    if (isMobile()){
        if (window.JSInterface) window.JSInterface.schemeLoadingListener();
    }
}