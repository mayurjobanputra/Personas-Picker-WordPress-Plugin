(function($){
    $.fn.typeOut = function(options){
        return this.each(function() {
            var el = $(this),
                tags = /(<\/?\w+(?:(?:\s+\w+(?:\s*=\s*(?:".*?"|'.*?'|[^'">\s]+))?)+\s*|\s*)\/?>)/gim,
                entities = /(&#?[a-z0-9]+;)/gim,
                settings = $.extend({
                    delay : 90,
                    preserve : false,
                    marker : '_',
                    stop: false // Added stop option
                }, options),
                html = (function(){
                    var temp = el.html().trim().split(tags),
                        html = [],
                        x = 0;
                    temp.map(function(v,i){
                        if(v.indexOf('<') < 0){
                             temp[i] = v.split(entities);
                             temp[i].map(function(v,i){
                                 if(v.indexOf('&') < 0){
                                     v = v.split('');
                                     v.map(function(v){
                                         if(v != '')
                                             html.push(v);
                                     });
                                 } else {
                                     html.push(v);
                                 }
                             });
                        } else {
                            if(temp[i] != '')
                                html.push(temp[i]);
                        }
                    });
                    return html;
                })(),
                step = function(num){
                    if (settings.stop) return; // Added stop condition
                    el.html(el.html().slice(0,-1)+html[num]+settings.marker);
                    num = num + 1;
                    if(num < html.length){
                        setTimeout(function(){
                             step(num);
                        }, settings.delay);
                    } else {
                        el.html(el.html().slice(0,-1));
                        el.html(''); // Clear el after typeOut is complete
                    }
                };
                
            settings.marker = (settings.marker != '') ? settings.marker : ' ';
            html = (settings.preserve) ? html : el.text().split('');
            el.html('');
            
            if(html.length >= 1)
                step(0);
            
            // Added stop method
            el.data('typeOutStop', function() {
                settings.stop = true;
                el.html(''); // Clear existing characters
                html = []; // Clear cache of characters being typed
            });
        });
    };
})(jQuery);
