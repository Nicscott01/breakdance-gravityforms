
jQuery( document ).on( 'gpnf_post_render', function( e, formId ) {

    console.log( 'gform_post_render', this );
    console.log( 'gpnf_post_render', e, formId );

    var parentId = jQuery('#gform_' + formId + ' input[name="gpnf_parent_form_id"]').val()

    var parentBdClasses = jQuery('#gform_wrapper_' + parentId ).parent().attr('class');
    
    jQuery('#gform_' + formId ).addClass( parentBdClasses );


});


