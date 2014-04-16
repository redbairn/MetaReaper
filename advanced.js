// Advanced Options (for One Search Engine Only)
$(document).ready(function () {
        $('#advancedOptions').hide();
        $('.advanced').click(function() {
            if ($('#advancedOptions').is(':hidden')) {
                 $('#advancedOptions').slideDown();
            } else {
                 $('#advancedOptions').slideUp();
            }
        });
    });