/*!
 * SVG.js Pan Zoom Plugin
 * ======================
 *
 * A JavaScript library for pan and zoom SVG things.
 * Created with <3 and JavaScript by the jillix developers.
 *
 * svg.pan-zoom.js 2.2.0
 * Licensed under the MIT license.
 * */
;(function() {

    var container = null
      , markers = null
      , mousewheel = "onwheel" in document.createElement("div")
      ? "wheel"
      : document.onmousewheel !== undefined
      ? "mousewheel"
      : "DOMMouseScroll"
      ;

    function panZoom(opt_options) {

        // Selected element
        var self = this;

        function setPosition(x, y, z) {
            pz.pan.iPos = pz.pan.fPos;
            pz.transform = self.transform();
            pz.transform.x = x;
            pz.transform.y = y;
            if (typeof z === "number") {
                pz.zoom(z);
            } else {
                updateMatrix();
            }
            return pz;
        }

        function zoom(z, oX, oY) {
            if (!oX && !oY){
                if (typeof z === "number") {
                    pz.transform = self.transform();
                    pz.transform.scaleY = pz.transform.scaleX = z;
                    updateMatrix();
                    return pz;
                }

                pz.transform = self.transform();
                pz.transform.scaleY = pz.transform.scaleX = z;
                updateMatrix();

            }else{
                setPosition(oX, oY, z);
            }
            return pz;
        }

        // Pan zoom object
        var pz = {
            pan: {}
          , elm: self
          , setPosition: setPosition
          , zoom: zoom
            , updateMatrix: updateMatrix
            , transform: {
                a: 1,
                b: 0,
                c: 0,
                d: 1,
                e: 0,
                f: 0,
                matrix: "1 0 0 1 0 0",
                rotation: 0,
                scaleX: 1,
                scaleY: 1,
                skewX: 0,
                skewY: 0,
                x: 0,
                y: 0
            }
        };

        // Set options
        opt_options = Object(opt_options);
        opt_options.zoom = opt_options.zoom || [];
        opt_options.zoomSpeed = typeof opt_options.zoomSpeed === "number" ? opt_options.zoomSpeed : -1;

        // Get the svg document
        var svg = $(self.node).parent();

        // Create the rectangle
        var rect = new SVG(document.createDocumentFragment()).rect().attr({
            width: 5000
          , height: 5000,
           fill: "none"
        }).style("pointer-events", "all");

        // Insert the rectangle
        $(self.node).parent().get(0).insertBefore(rect.node, self.node);

        function updateMatrix() {
            self.attr("transform", "matrix(" + [
                pz.transform.scaleX
              , 0, 0
              , pz.transform.scaleY
              , pz.transform.x
              , pz.transform.y
            ].join(",")+ ")");
        }

        function pan(e) {
            if (!pz.pan.mousedown) {
                return;
            }
            var tr = pz.transform = self.transform();
            var diffX = pz.pan.fPos.x - pz.pan.iPos.x;
            var diffY = pz.pan.fPos.y - pz.pan.iPos.y;
            pz.setPosition(tr.x + diffX, tr.y + diffY);
            self.node.dispatchEvent(new CustomEvent("pan", { detail: { e: e, tr: tr } }));
        }

        function mousePos(e, rel) {
            var bbox = $(self.node).parent().get(0).getBoundingClientRect()
              , abs = {
                    x: e.clientX || e.touches[0].pageX
                  , y: e.clientY || e.touches[0].pageY
                }
              ;
            if (!rel) { return abs; }
            return {
                x: abs.x - bbox.left
              , y: abs.y - bbox.top
            };
        }

        function doZoom (e) {

            if (window.editor.editorObj.disableWheelZoom || window.editor.editorObj.isProductionPage) return false;

            // Get the relative mouse point
            var rP = mousePos(e, true)
              , oX = rP.x
              , oY = rP.y
              ;

            e.deltaY = e.deltaY || e.wheelDeltaY;

            // Compute the new scale
            var d = opt_options.zoomSpeed * e.deltaY / 1000
              , tr = pz.transform = self.transform()
              , scale = parseFloat(tr.scaleX + (tr.scaleX * d))
              , scaleD = parseFloat(scale / tr.scaleX)

                // Get the current x, y
              , currentX = tr.x
              , currentY = tr.y

                // Compute the final x, y
              , x = scaleD * (currentX - oX) + oX
              , y = scaleD * (currentY - oY) + oY
              ;

            // Handle zoom restrictions
            if (scale > opt_options.zoom[1]) {
                scale = opt_options.zoom[1];
                return;
            }

            if (scale < opt_options.zoom[0]) {
                scale = opt_options.zoom[0];
                return;
            }

            if (scale < window.editor.editorObj.minZoom || scale > 1){
                return;
            }

            window.editor.editorObj.zoom = scale;
            if (window.editor.editorObj.scaleControl) window.editor.editorObj.scaleControl.val(scale);

            // Zoom
            tr.scaleY = tr.scaleX = scale;
            tr.x = x;
            tr.y = y;

            self.node.dispatchEvent(new CustomEvent("zoom", { detail: { e: e, tr: tr } }));
            updateMatrix();

            // Prevent the default browser behavior
            e.preventDefault();
        }

        // The event listeners
        var EventListeners = {
            mouse_down: function (e) {
                if (window.editor.editorObj.dragMode){
                    window.editor.editorObj.svgParentNode.addClass('dragging');
                    pz.pan.mousedown = true;
                    pz.pan.iPos = mousePos(e);
                }
            }
          , mouse_up: function (e) {
                var singleClickElement = e.target.instance;
                if (singleClickElement.hasClass('makroControl')  && window.editor.editorObj.isProductionPage && !pz.pan.dragStarted){
                    window.editor.editorObj.deselectElements();
                    window.editor.editorObj.showSingleSector(singleClickElement.attr("data-joined-to"), false);
                }else{
                    if (singleClickElement.attr('data-selectable') == "true" && !pz.pan.dragStarted && window.editor.editorObj.isProductionPage && !singleClickElement.hasClass('imported')){
                        window.editor.editorObj.singleClickElement = singleClickElement;
                        if (window.editor.editorObj.selectedGroup && $(window.editor.editorObj.selectedGroup.node).children().length > 0){
                            if (window.editor.editorObj.singleClickElement.hasClass('current')){
                                if (window.editor.editorObj.currentElement.length > 1){
                                    if ($(window.editor.editorObj.selectedGroup.node).children().length > 0) {
                                        for (var i = 0; i < $(window.editor.editorObj.selectedGroup.node).children().length; i++) {
                                            if ($(window.editor.editorObj.singleClickElement.node).is($($(window.editor.editorObj.selectedGroup.node).children()[i]))) {
                                                window.editor.editorObj.currentElement.splice(i, 1);
                                                break;
                                            }
                                        }
                                    }
                                    window.editor.editorObj.changeCurrentElement(window.editor.editorObj.currentElement);
                                    if (window.editor.editorObj.currentElement.length == 0){
                                        window.editor.editorObj.changeCurrentElement(null);
                                    }
                                }else{
                                    window.editor.editorObj.changeCurrentElement(null);
                                }
                                window.editor.editorObj.singleClickElement.removeClass('current');
                                window.editor.editorObj.copyElement(window.editor.editorObj.singleClickElement, window.editor.editorObj.selectedGroup);
                                window.editor.editorObj.singleClickElement.remove();
                                delete window.editor.editorObj.singleClickElement;
                            }else{
                                window.editor.editorObj.selectedGroup.add(window.editor.editorObj.singleClickElement);
                                window.editor.editorObj.changeCurrentElement($(window.editor.editorObj.selectedGroup.node).children());
                            }
                        }else{
                            window.editor.editorObj.selectedGroup = window.editor.editorObj.createGroup();
                            window.editor.editorObj.selectedGroup.add(window.editor.editorObj.singleClickElement);
                            window.editor.editorObj.changeCurrentElement(window.editor.editorObj.singleClickElement);
                        }
                        //window.editor.selectHandler(window.editor.editorObj.currentElement);
                    }
                }
                if (window.editor.editorObj.dragMode) {
                    window.editor.editorObj.svgParentNode.removeClass('dragging');
                    pz.pan.mousedown = false;
                    pz.pan.dragStarted = false;
                    window.editor.editorObj.disableHtmlSelect(false);
                    pz.pan.fPos = mousePos(e);
                    pz.pan.startPos = pz.pan.fPos;
                    pan();
                }
            }
          , mouse_move: function (e) {
                if (window.editor.editorObj.dragMode) {
                    if (!pz.pan.mousedown)return;
                    pz.pan.dragStarted = true;
                    window.editor.editorObj.disableHtmlSelect(true);
                    pz.pan.fPos = mousePos(e);
                    pan();
                }
            }
          , mouse_leave: function (e) {
                if (window.editor.editorObj.dragMode) {
                    pz.pan.mousedown = false;
                }
            }
        };
        window.EventListeners = EventListeners;

        // Add event listeners
        rect.off(mousewheel)
            .off('mousedown')
            .off('mousemove')
            .off('mouseup');
            //.off('mouseleave');

        rect
          .on(mousewheel, doZoom)
          //.on("touchstart", EventListeners.mouse_down)
          //.on("touchmove", EventListeners.mouse_move)
          //.on("touchup", EventListeners.mouse_up)
          .on("mousedown", EventListeners.mouse_down)
          .on("mousemove", EventListeners.mouse_move)
          .on("mouseup", EventListeners.mouse_up)
          //.on("mouseleave", EventListeners.mouse_leave)
          ;

        self.off(mousewheel);
        self.on(mousewheel, doZoom);
        return pz;
    }

    // Extend the SVG.Element with the new function
    SVG.extend(SVG.Element, {
        panZoom: panZoom
    });
}).call(this);
