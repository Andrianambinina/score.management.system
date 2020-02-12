/**
 * Javascript général
 */

$(function() {
    // Plugins select2
    // $(".select2").select2();
    //
    // // Datetimepicker
    // $(".datetimepicker").datetimepicker({
    //     locale: 'fr',
    //     format: 'DD/MM/YYYY HH:mm'
    // });

    // Confirmation suppression
    $('.delete-btn, .delete-btn-custom, .remove-elt').click(function(event) {
        if( !confirm('Etes vous sûr de vouloir supprimer ?') )
            event.preventDefault();
    });

    // Supprimer la classe Error séléctionnée
    $("input").focus(function() {
        $(this).parents('.form-group').removeClass('has-error');
    });
    $("select").focus(function() {
        $(this).parents('.form-group').removeClass('has-error');
    });
    $("textarea").focus(function() {
        $(this).parents('.form-group').removeClass('has-error');
    });
});

/*
 * Mettre une erreur sur le champ spécifique
 */
function setErrorClass($this){
    $this.parents('.form-group').addClass('has-error');
}