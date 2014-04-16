// Number of Results (for Aggregation)
$(document).ready(function () {
        $('#NumResults').hide();
        $('.Number').click(function() {
            if ($('#NumResults').is(':hidden')) {
                 $('#NumResults').slideDown();
            } else {
                 $('#NumResults').slideUp();
            }
        });
    });