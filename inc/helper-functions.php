<?php
/**
 * Functions
 * 
 * 
 */

namespace BDGF; 

function remove_conditional_logic_fields($form)
{

    return BDGF()->remove_conditional_fields($form);
}





function complex_labels_to_screen_reader_text($field_content, $field, $value, $entry_id, $form_id)
{

    switch ($field->type) {

        case "name":
        case "email":
        default:

            $field_content = str_replace('gform-field-label--type-sub', 'gform-field-label--type-sub screen-reader-text', $field_content);
    }


    return $field_content;
}




function button_primary( $button, $form ) {
    
    //Do nothing, since we make it primary by default
    return $button;
}

function button_secondary( $button, $form ) {

    $button = str_replace( 'button-atom--primary', 'button-atom--secondary', $button );

    return $button;
}

function button_custom( $button, $form ) {

    $button = str_replace( 'button-atom--primary', 'button-atom--custom', $button );

    return $button;
}

function button_text( $button, $form ) {

    $button = str_replace( 'button-atom--primary', 'button-atom--text', $button );

    return $button;
}