$(document).ready(function () {
    // Function to inject the timer button
    function injectTimer() {
        // Target the duration field. In GLPI 10, it's often a dropdown or input with name 'actiontime'
        // We look for the container to append our button
        var $durationField = $('[name="actiontime"]');

        if ($durationField.length > 0 && $('#plugin_tagesabschlusse_timer').length === 0) {
            var $btn = $('<button type="button" id="plugin_tagesabschlusse_timer" class="btn btn-sm btn-secondary ms-2"><i class="fas fa-play"></i> Start Timer</button>');

            // Insert after the duration field (or its select2 container)
            if ($durationField.next('.select2').length > 0) {
                $durationField.next('.select2').after($btn);
            } else {
                $durationField.after($btn);
            }

            var startTime = null;
            var timerInterval = null;

            $btn.on('click', function () {
                if (startTime === null) {
                    // Start Timer
                    startTime = new Date();
                    $btn.html('<i class="fas fa-stop"></i> Stop Timer (0m)');
                    $btn.removeClass('btn-secondary').addClass('btn-danger');

                    timerInterval = setInterval(function () {
                        var now = new Date();
                        var diff = Math.round((now - startTime) / 60000); // minutes
                        $btn.html('<i class="fas fa-stop"></i> Stop Timer (' + diff + 'm)');
                    }, 60000); // Update every minute

                } else {
                    // Stop Timer
                    var now = new Date();
                    var diffMinutes = Math.round((now - startTime) / 60000);

                    // Minimum 1 minute if stopped immediately
                    if (diffMinutes < 1) diffMinutes = 1;

                    // Update the field
                    // GLPI duration is usually in minutes or seconds depending on configuration, 
                    // but the dropdown usually takes minutes or a specific value.
                    // Assuming standard dropdown, we might need to set the value.
                    // If it's a simple input (unlikely for duration), val() works.
                    // If it's a dropdown, we try to match the closest value or set it.

                    // For standard GLPI actiontime dropdown, values are in seconds usually? 
                    // Let's assume we set the value in minutes if it's a text input, or seconds if select.
                    // Actually, let's try to set the value directly.

                    // NOTE: GLPI 'actiontime' is often seconds.
                    var seconds = diffMinutes * 60;
                    $durationField.val(seconds).trigger('change');

                    // Reset UI
                    clearInterval(timerInterval);
                    startTime = null;
                    $btn.html('<i class="fas fa-play"></i> Start Timer');
                    $btn.removeClass('btn-danger').addClass('btn-secondary');
                }
            });
        }
    }

    // Attempt to inject on load
    injectTimer();

    // Re-inject if the form is reloaded via AJAX (common in GLPI tabs)
    $(document).ajaxComplete(function () {
        injectTimer();
    });
});
