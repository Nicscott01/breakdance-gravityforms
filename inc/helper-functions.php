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
* Styles native Gravity Forms 3 buttons and converts legacy input buttons.
* Preserve submission attributes, button content, and adjacent payment markup.
*
* @param string $button Contains the <input> tag to be filtered.
* @param object $form Contains all the properties of the current form.
*
* @return string The filtered button.
*/

function input_to_button( $button, $form ) {

    $previous_errors = libxml_use_internal_errors( true );

    $wrapper_id = 'submit-button-wrapper-' . $form['id'];

    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?><div id="'.$wrapper_id.'">' . $button . '</div>' ); // wrap in container to preserve siblings
    libxml_clear_errors();
    libxml_use_internal_errors( $previous_errors );

    $wrapper = $dom->getElementById( $wrapper_id );
    $xpath = new \DOMXPath( $dom );
    $controls = $xpath->query( './/button | .//input[@type="submit" or @type="button" or @type="image"]', $wrapper );
    $control = null;

    // Select GF's control, never a provider button or a hidden input beside it.
    foreach ( $controls as $candidate ) {
        if ( preg_match( '/^gform_(?:submit|next|previous)_button_' . (int) $form['id'] . '(?:_\d+)?$/', $candidate->getAttribute( 'id' ) ) ) {
            $control = $candidate;
            break;
        }
    }

    if ( !$control ) {
        return $button;
    }

    if ( $control->tagName === 'input' ) {
        $new_button = $dom->createElement( 'button' );
        foreach ( $control->attributes as $attribute ) {
            $new_button->setAttribute( $attribute->name, $attribute->value );
        }

        if ( $control->getAttribute( 'type' ) === 'image' ) {
            $image = $dom->createElement( 'img' );
            $image->setAttribute( 'src', $control->getAttribute( 'src' ) );
            $image->setAttribute( 'alt', $control->getAttribute( 'alt' ) );
            $new_button->appendChild( $image );
            $new_button->setAttribute( 'type', 'submit' );
            $new_button->removeAttribute( 'src' );
            $new_button->removeAttribute( 'alt' );
        } else {
            $new_button->appendChild( $dom->createTextNode( $control->getAttribute( 'value' ) ) );
        }

        $control->parentNode->replaceChild( $new_button, $control );
        $control = $new_button;
    }

    $classes = preg_split( '/\s+/', trim( $control->getAttribute( 'class' ) ), -1, PREG_SPLIT_NO_EMPTY );
    $classes[] = 'button-atom';
    if ( !preg_grep( '/^button-atom--/', $classes ) ) {
        $classes[] = 'button-atom--primary';
    }
    $classes[] = 'breakdance-form-button';
    if ( strpos( $control->getAttribute( 'id' ), 'gform_submit_button_' ) === 0 ) {
        $classes[] = 'breakdance-form-button__submit';
    }
    $control->setAttribute( 'class', implode( ' ', array_unique( $classes ) ) );

    $new_html_button = '';
    foreach ( $wrapper->childNodes as $child ) {
        $new_html_button .= $dom->saveHTML( $child );
    }

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
            libxml_use_internal_errors( true );
            $dom = new DOMDocument();
            // prepend an XML encoding declaration so DOMDocument parses as UTF-8
            $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $match[0], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
            $dom->encoding = 'UTF-8';
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
            libxml_clear_errors();
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
