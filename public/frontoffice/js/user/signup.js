$(function () {
    var loaded = false;
    $('form#id-signup-modal').bootstrapValidator();

    $('form#id-signup-modal').submit(function(e) {
        e.preventDefault();
        var data = $('form#id-signup-modal').serialize();
        if (loaded) return;
        var has_error = $('form#id-signup-modal').bootstrapValidator().has('.has-error').length;
        if (has_error == 0) {
            loaded = true;
            var _loading = "<i class='fa-li fa fa-spinner fa-spin'></i>";
            $(".kl-loading").html(_loading);

            $.ajax({
                type: 'POST',
                url: $('form#id-signup-modal').attr('action'),
                data: data,
                dataType: 'json',
                success: function(result) {
                    loaded = false;
                    $('.kl-loading').html('');
                    $('#id-signup-modal').modal('hide');
                },
                error: function(err) {
                    loaded = false;
                    $('.kl-loading').html('');
                }
            });
        }
    });
});