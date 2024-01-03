jQuery(document).ready(function() {
    jQuery('.tab-pane').hide();

    var typingInterval;

    jQuery('.persona-choice a').click(function(event) {
        event.preventDefault(); // Prevent the default anchor behavior

        // Your existing code
        jQuery('.tab-pane').hide();
        jQuery('.persona-choice').removeClass('active-tab');
        var $targetPanel = jQuery(jQuery(this).attr('href'));
        $targetPanel.show();
        jQuery(this).parent().addClass('active-tab');
        clearInterval(typingInterval);
        $targetPanel.find('.typeout').empty();
        var text = $targetPanel.find('.typeout').attr('data-text');
        typingInterval = typeText($targetPanel.find('.typeout'), text);

        // Smooth scroll with offset
        var targetOffset = $targetPanel.offset().top - 200; // 200px offset
        jQuery('html, body').animate({ scrollTop: targetOffset }, 'slow');
    });

    function typeText($element, text) {
        var index = 0;
        var content = ''; // Initialize an empty content variable
        return setInterval(function() {
            if (index < text.length) {
                var letter = text.charAt(index);
                content += letter; // Append the letter to the content variable
                $element.html(content); // Update the HTML content
                index++;
            }
        }, 10);
    }
    
    
});
