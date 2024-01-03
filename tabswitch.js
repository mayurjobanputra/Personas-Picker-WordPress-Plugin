jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();

    var typingInterval;

    jQuery('.persona-choice a').click(function() {
        // Hide all tab panes
        jQuery('.tab-pane').hide();

        // Clear any ongoing typewriter effect
        clearInterval(typingInterval);

        // Show the target tab pane
        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();

        // Clear any existing content inside .typeout
        $targetPanel.find('.typeout').empty();

        // Get the text to type out from the data-text attribute
        var text = $targetPanel.find('.typeout').attr('data-text');

        // Start the typewriter effect
        typingInterval = typeText($targetPanel.find('.typeout'), text);
    });

    function typeText($element, text) {
        var index = 0;
        return setInterval(function() {
            if (index < text.length) {
                $element.append(text.charAt(index));
                index++;
            }
        }, 10);
    }
});
