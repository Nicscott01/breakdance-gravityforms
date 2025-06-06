<?php
/**
 * Functions
 * 
 * 
 */

namespace BDGF; 
use DOMDocument;



function remove_conditional_logic_fields($form)
{

    $fields = $form['fields'];

    foreach ( $fields as &$field ) {

        $field->conditionalLogic = '';

    }

    $form['fields'] = $fields;

    return $form;

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







/**
* Filters the next, previous and submit buttons.
* Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
*
* @param string $button Contains the <input> tag to be filtered.
* @param object $form Contains all the properties of the current form.
*
* @return string The filtered button.
*/

function input_to_button( $button, $form ) {

    error_log( 'input_to_button $button:' . $button );

    libxml_use_internal_errors( true );

    $wrapper_id = 'submit-button-wrapper-' . $form['id'];

    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?><div id="'.$wrapper_id.'">' . $button . '</div>' ); // wrap in container to preserve siblings

    $wrapper = $dom->getElementById( $wrapper_id );
    $input   = $wrapper->getElementsByTagName( 'input' )->item(0);

    if ( $input ) {
        $new_button = $dom->createElement( 'button' );
        $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
        $input->removeAttribute( 'value' );

        foreach( $input->attributes as $attribute ) {
            if ( $attribute->name === 'class' ) {
                $class = $attribute->value;

                if ( strpos( $class, 'gform_previous_button' ) !== false || strpos( $class, 'gform_next_button' ) !== false ) {
                    $class .= ' button-atom button-atom--primary breakdance-form-button';
                } else {
                    $class .= ' button-atom button-atom--primary breakdance-form-button breakdance-form-button__submit';
                }

                $new_button->setAttribute( 'class', $class );
            } else {
                $new_button->setAttribute( $attribute->name, $attribute->value );
            }
        }

        $input->parentNode->replaceChild( $new_button, $input );
    }

    $new_html_button = '';
    foreach ( $wrapper->childNodes as $child ) {
        $new_html_button .= $dom->saveHTML( $child );
    }

    error_log( 'input_to_button $new_button:' . $new_html_button );

    return $new_html_button;
}



function input_to_button_d( $button, $form ) {

    error_log( 'input_to_button $button:' . $button );
    libxml_use_internal_errors( true );

    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $new_button = $dom->createElement( 'button' );

    if ( $input ) {
        $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
        $input->removeAttribute( 'value' );
    
    
    //var_dump( $input->attributes );
        foreach( $input->attributes as $attribute ) {
            if ( $attribute->name == 'class' ) {

                if ( ( strpos( $attribute->value, 'gform_previous_button' ) !== false ) || ( strpos( $attribute->value, 'gform_next_button' ) !== false ) ) {
                  
                    $new_button->setAttribute( 'class', $attribute->value . ' button-atom button-atom--primary breakdance-form-button');

                } else {

                    $new_button->setAttribute( 'class', $attribute->value . ' button-atom button-atom--primary breakdance-form-button breakdance-form-button__submit');

                }

            } else {
                $new_button->setAttribute( $attribute->name, $attribute->value );
            }
        }
        $input->parentNode->replaceChild( $new_button, $input );
    
    }

    $new_html_button = $dom->saveHtml( $new_button );

    error_log( 'input_to_button $new_button:' . $new_html_button );

    
    return $new_html_button;
}






/**
 *  Add a class to an element with DOMDocument
 *  Use sparingly
 * 
 *  @var string $tag, the html tag to find
 *  @var string $field_content, @string, the html source we're looking to replace
 *  
 *  @return string
 */

function dom_document_replacement( $tag, $field_content, $add_class = 'breakdance-form-field__input' ) {

    /**
     * Get just the input element using regex since 
     * DOMDocument changes where the label tag wraps 
     * (around the entire input elmement wrapper)
     * 
     */

    switch ( $tag ) {

        case "textarea":
        case "select":

            // Match <textarea> and <select> tags (with their inner content)
            $re = sprintf( '/<%1$s\b[^>]*>(.*?)<\/%1$s>/i', $tag );

            break;

        default:

            // Match self-closing tags like <input />
            $re = sprintf( '/<%1$s\b[^>]*\/?>/i', $tag );

    }


    preg_match_all($re, $field_content, $matches, PREG_SET_ORDER, 0);


    if ( !empty( $matches ) ) {

        foreach ( $matches as $match ) {
            $dom = new DOMDocument();
            $dom->loadHTML( $match[0], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
            $elements = $dom->getElementsByTagName( $tag );


            if ( !empty( $elements ) ) {
                foreach ( $elements as $element ) {

                    $class = $element->getAttribute('class');

                    // Check if class is empty, and add the new class accordingly
                    if ( empty( $class ) ) {
                        $element->setAttribute( 'class', $add_class ); // Set the class if none exists
                    } else {
                        $element->setAttribute( 'class', trim( $class . ' ' . $add_class ) ); // Append new class to existing ones
                    }
                }
            }

            $field_content = str_replace( $match[0], $dom->saveHTML(), $field_content );
        }

    }

    return $field_content;

}



/**
 *  Regex pattern to help match a class
 *  and no partials
 * 
 * 
 */

 function class_replace( $search, $replace, $subject ) {

    $re = sprintf( '/\b%s\b(?![-_])/', $search );

    return preg_replace(
        $re,
        $replace,
        $subject
    );
    
 }

