jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();

    var typingInterval;

    jQuery('.persona-choice a').click(function() {
        // Hide all tab panes
        jQuery('.tab-pane').hide();

        // Remove the active-tab class from all tabs
        jQuery('.persona-choice').removeClass('active-tab');

        // Show the target tab pane
        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();

        // Add the active-tab class to the clicked tab
        jQuery(this).parent().addClass('active-tab');

        // Clear any ongoing typewriter effect
        clearInterval(typingInterval);

        // Show the content of the clicked tab
        var text = $targetPanel.find('.typeout').attr('data-text');
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
