function ViewAllQuotes(){
    var allQuotes = {
        quoteControls: $('.quote_control'),
        initQuotesControls: function(){ // initialize controls for block/unblock quote elements
            if (this.quoteControls.length > 0){
                this.quoteControls.each(function(){
                    var quoteControl = $(this);
                    quoteControl.on('click', function(e){
                        e.preventDefault();
                        allQuotes.toggleQuoteMode($(this));
                    });
                });
            }else{
                delete this.quoteControls;
            }
        },
        toggleQuoteMode: function(control){
            var neededQuote = control.data('quote_id'),
                neededEl = $('svg [data-quote-id="'+neededQuote+'"]');
            if (neededEl.length > 0){
                control.toggleClass('glyphicon-eye-open glyphicon-eye-close');
                neededEl.each(function(){
                    var _this = $(this)[0].instance;
                    _this.toggleClass('blocked');
                    _this.data('selectable') == true ? _this.data('selectable', 'false') : _this.data('selectable', true);
                });
            }else{
                console.log("Elements for this quote not found");
            }
        },
        init: function(){
            this.initQuotesControls();
            return this;
        }
    }
    return allQuotes.init();
}
