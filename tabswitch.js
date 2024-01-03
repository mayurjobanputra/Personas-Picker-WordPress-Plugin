jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();

    var typingInterval;

    jQuery('.persona-choice a').click(function() {
        // Remove active class from all tabs
        jQuery('.persona-choice a').removeClass('active-tab');
        
        // Add active class to the clicked tab
        jQuery(this).addClass('active-tab');

        // Hide all tab panes
        jQuery('.tab-pane').hide();

        // Clear any ongoing typewriter effect
        clearInterval(typingInterval);

        // Show the target tab pane
        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();

        // Clear any existing content inside .typeout
        $targetPanel.find('.typeout').empty();

        // Get the text to type out from the data-text attribute and decode HTML entities
        var text = jQuery('<div/>').html($targetPanel.find('.typeout').attr('data-text')).text();

        // Start the typewriter effect
        typingInterval = typeText($targetPanel.find('.typeout'), text);
    });

    function typeText($element, text) {
        var index = 0;
        return setInterval(function() {
            if (index < text.length) {
                $element.text(text.substr(0, index + 1)); // Append the entire text up to the current index
                index++;
            }
        }, 10);
    }
});
