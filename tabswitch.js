jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();
    
    jQuery('.persona-choice a').click(function() {
        // Hide all tab panes
        jQuery('.tab-pane').hide();
        
        // Clear any ongoing typewriter effect in the previously active tab
        jQuery('.typeout').empty();

        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();

        // Start the typewriter effect for the new tab content
        $targetPanel.find('.typeout').each(function() {
            var text = jQuery(this).text();
            var index = 0;
            var $element = jQuery(this);
            var interval = setInterval(function() {
                if (index < text.length) {
                    $element.append(text.charAt(index));
                    index++;
                } else {
                    clearInterval(interval);
                }
            }, 10);
        });
    });
});
