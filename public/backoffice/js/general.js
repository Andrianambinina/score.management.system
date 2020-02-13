/**
 * Javascript général
 */

$(function() {
    $('.kl-remove-elt').on('click', function (event) {
        if (!confirm('Are you sure you want to delete ?'))
            event.preventDefault();
    });
});