jQuery(document).ready(function($){
    $('#dsl-contact-form').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize() + '&action=dsl_update_contact';
        $.post(dslAjax.ajaxurl, data, function(resp){
            $('#dsl-message').html('<span style="color:'+(resp.success ? 'green':'red')+'">'+resp.data.message+'</span>');
        });
    });
});
