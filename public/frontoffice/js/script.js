$(function () {
    /* Birthday date */
    $('.kl-date-picker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy',
        endDate: "today",
        useCurrent: true
    });

    /* Country select2 */
    $('.kl-country-select2').select2({
        placeholder: 'Country',
        width: '100%',
        dropdownParent: $('#id-form .kl-country-select2').closest('.form-group'),
        ajax: {
            url: url_list_country,
            dataType: 'json',
            delay: 250,
            minimumInputLength: 1,
            data: function (params) {
                var query = {
                    term: params.term,
                }

                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    /* City select2 */
    $('.kl-city-select2').select2({
        placeholder: 'City',
        width: '100%',
        dropdownParent: $('#id-form .kl-city-select2').closest('.form-group'),
        ajax: {
            url: url_list_city,
            dataType: 'json',
            delay: 250,
            minimumInputLength: 1,
            data: function (params) {
                var query = {
                    term: params.term,
                    country_id: $('select[name=country]').val()
                }

                return query;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });

    /* Add ajax */
    var loaded = false;
    $('form#id-form').bootstrapValidator();

    $('form#id-form').submit(function(e) {
        e.preventDefault();
        var data = $('form#id-form').serialize();

        if (loaded) return;
        var has_error = $('form#id-form').bootstrapValidator().has('.has-error').length
        if (has_error == 0) {
            loaded = true;

            $.ajax({
                type: 'POST',
                url: $('form#id-form').attr('action'),
                data: data,
                dataType: 'json',
                success: function(result) {
                    loaded = false;
                    if (result.success) {
                        window.location.href = result.url;
                    }
                },
                error: function(err) {
                    loaded = false;
                }
            });
        }
    });

    /* Edit ajax */
    $(document).on('click', '.kl-edit', function(e) {
        e.preventDefault();

        var id = $(this).data('id'),
            name = $(this).data('name'),
            email = $(this).data('email'),
            birthday = $(this).data('birthday'),
            country_id = $(this).data('country-id'),
            country_name = $(this).data('country-name'),
            city_id = $(this).data('city-id'),
            city_name = $(this).data('city-name'),
            country_select2 = $('.kl-country-select2'),
            city_select2 = $('.kl-city-select2');

        $('input[name=ajax_id]').val(id);
        $('input[name=name]').val(name);
        $('input[name=email]').val(email);
        $('input[name=birthday]').val(birthday);

        country_select2.append('<option value="' + country_id + '" selected="selected">' + country_name + '</option>');
        country_select2.trigger('change');
        city_select2.append('<option value="' + city_id + '" selected="selected">' + city_name + '</option>');
        city_select2.trigger('change');

        $('#id-modal').modal('show');
    });

    /* Delete ajax */
    $(document).on('click', '.kl-remove-ajax', function(e) {
        e.preventDefault();
        var ajax_id = $(this).attr('data-id');
        var url = $(this).data('url');

        if (confirm('Are you sure ?')) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {ajax_id: ajax_id},
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        $('#id-item_' + ajax_id).fadeOut('slow', function () {
                            $(this).remove();
                            $('.kl-remove-ajax[data-id=' + ajax_id + ']').remove();
                        });
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    });
});