jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();
    
    jQuery('.persona-choice a').click(function() {
        jQuery('.tab-pane').hide();
        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();

        // Typewriter effect
        $targetPanel.find('.typeout').each(function() {
            var text = jQuery(this).text();
            jQuery(this).empty();
            var index = 0;
            var interval = setInterval(function() {
                if (index < text.length) {
                    jQuery(this).append(text.charAt(index));
                    index++;
                } else {
                    clearInterval(interval);
                }
            }.bind(this), 10);
        });
    });
});
