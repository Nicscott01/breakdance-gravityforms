<?php
/**
 *  Plugin Name: Breakdance GravityForms
 *  Description: Apply Breakdance styling to Gravity Forms
 *  Author: Nic Scott
 *  Version: 0.2
 * 
 */


 namespace BDGF;

 use function \Breakdance\Elements\controlSection;
 use function \Breakdance\Elements\control;
 use DOMDocument;


 class BDGF {


    public static $instance;




    public function __construct()
    {

        require_once( __DIR__ . '/inc/elements.php' );
        require_once( __DIR__ . '/inc/helper-functions.php' );
        require_once( __DIR__ . '/inc/Modifier.php' );
     
        add_filter( 'gform_disable_css', '__return_true' );

        //add_filter( 'breakdance_global_settings_control_sections_append', [ $this, 'breakdance_global_settings_control_sections_append' ], 10, 1 );

        //add_filter( 'breakdance_global_settings_css_twig_template_append', [ $this, 'breakdance_global_settings_css_twig_template_append'], 10, 1 );

        //add_filter( 'breakdance_global_settings_property_paths_to_whitelist_in_flat_props_append', [ $this, 'breakdance_global_settings_property_paths_to_whitelist_in_flat_props_append'], 10, 1 );


        //add_filter( 'gform_field_css_class', [ $this, 'gform_field_css_class_label'], 10, 3 );

       // add_filter( 'gform_pre_render', [ $this, 'gform_pre_render' ], 10, 3 );


        add_filter( 'gform_field_container', [ $this, 'gform_field_container' ], 10, 6 );

        add_filter( 'gform_field_content', [ $this, 'gform_field_content' ], 10, 5 );
        add_filter( 'gform_field_input', [ $this, 'gform_field_input' ], 10, 5 );


        add_filter( 'gform_next_button',  [ $this, 'input_to_button' ], 10, 2 );
        add_filter( 'gform_previous_button',  [ $this, 'input_to_button' ], 10, 2 );
        add_filter( 'gform_submit_button',  [ $this, 'input_to_button' ], 10, 2 );


        add_filter( 'breakdance_reusable_dependencies_urls', function ($urls) {
    
            //    $urls['bsAccordion'] = plugin_dir_url( __FILE__ ) . 'dependencies-files/bootstrap-partials@1/bootstrap-partial.min.js';
                $urls['bdgfJquery'] = site_url() . '/wp-includes/js/jquery/jquery.min.js';
        
                $urls['bdgfDefaultCss'] = plugin_dir_url( __FILE__ ). '/css/normalize.css';

                //GFFormDisplay::get_form_enqueue_assets( $form, $is_ajax );

            //_log( site_url() . '/wp-includes/js/jquery/jquery.min.js' );

            return $urls;
            
        });

    

        
    }
    



    public function remove_conditional_fields( $form ) {
       
        $fields = $form['fields'];

        foreach ( $fields as &$field ) {

            $field->conditionalLogic = '';

        }

        $form['fields'] = $fields;
        //var_dump( $form );
        return $form;

    }



    public function breakdance_global_settings_control_sections_append( $appendedControlSections ) {

        return $appendedControlSections;

    }



    public function breakdance_global_settings_css_twig_template_append( $appendTwigTemplate ) {


        ob_start();

        include( __DIR__ .'/css/normalize.css' );

        $appendTwigTemplate .= ob_get_clean();


        return $appendTwigTemplate;
    }



    public function breakdance_global_settings_property_paths_to_whitelist_in_flat_props_append( $appendedWhitelistedProps ) {

        return $appendedWhitelistedProps;
    }







    public function gform_field_css_class_label( $classes, $field, $form ) {

//        var_dump( $field );

        $bd_classes = [ 'breakdance-form-field' ];




        switch( $field->type ) {

            case 'name' :

                $bd_classes[] = 'breakdance-form-field--text';

                break;

            case 'email' :

                $bd_classes[] = 'breakdance-form-field--email';

                break;

            case 'radio' : 
            case 'product' : 

                $bd_classes[] = 'breakdance-form-field--radio';
    
                break;

        }


        $classes .= ' ' . implode( ' ', $bd_classes );


        return $classes;
    }



    /**
     *  CSS class of the form element
     * 
     * 
     */

    public function gform_pre_render( $form, $ajax, $field_values ) {

        $form['cssClass'] = 'breakdance-form breakdance-form--vertical';

        return $form;
    }

    



    public function gform_field_container( $field_container, $field, $form, $css_class, $style, $field_content ) {

       // var_dump( $field_container );


        return $field_container;
    }




    public function gform_field_content( $field_content, $field, $value, $entry_id, $form_id ) {

        //var_dump( $field->type );
        //var_dump ( $field_content );

        switch( $field->type ) {

            case "consent" :
            case "checkbox":

                //ginput_container ginput_container_consent 
                if ( $field->type == 'consent' ) {

                    $field_content = str_replace( 'ginput_container_consent', 'ginput_container_consent breakdance-form-checkbox', $field_content );

                }


                $field_content = str_replace( 'gchoice', 'breakdance-form-checkbox gchoice', $field_content );
                $field_content = str_replace( 'gform-field-label--type-inline', ' gform-field-label--type-inline breakdance-form-checkbox__text ', $field_content );
                $field_content = str_replace( 'gfield_label_before_complex', 'gfield_label_before_complex breakdance-form-field__label bdgf-choice-label', $field_content );

                break;

            case "radio":
            case "product":
            


                $field_content = str_replace( 'gchoice ', 'breakdance-form-radio gchoice ', $field_content );
                $field_content = str_replace( 'gform-field-label ', 'breakdance-form-radio__text gform-field-label ', $field_content );
                //$field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );
                $field_content = str_replace( 'ginput_amount', 'breakdance-form-field__input ginput_amount', $field_content );
                $field_content = str_replace( "<legend class='gfield_label ", "<legend class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );
                $field_content = str_replace( "<label class='gfield_label ", "<label class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );

                break;

            case "select" :

                $field_content = str_replace( 'gfield--type-select', 'breakdance-form-field breakdance-form-field__select gfield--type-select', $field_content );
                $field_content = str_replace( 'gfield_select', 'breakdance-form-field__input gfield_select', $field_content );
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );


                break;

            case "total" :

                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );

                break;


            case "textarea" :

                //Style the label
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = $this->dom_document_replacement( 'textarea', $field_content );

                break;

            case "text":
            case "number":
            case "phone":
            case "name":
            case "email":

                //Style the label
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                
                $field_content = $this->dom_document_replacement( 'input', $field_content );

                break;

            case "address":

                //Style the label
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                
                $field_content = $this->dom_document_replacement( 'input', $field_content );
                $field_content = $this->dom_document_replacement( 'select', $field_content );

                break;
            
            default:
            
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                $field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );
            
        }

        //Remove weird extra div that throws off layout
        $field_content = str_replace( "<div class='gf_clear gf_clear_complex'></div>", '', $field_content );

        $field_content = str_replace( 'gfield_required gfield_required_asterisk', 'breakdance-form-field__required gfield_required gfield_required_asterisk', $field_content );

        return $field_content;
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

    public function dom_document_replacement( $tag, $field_content, $add_class = 'breakdance-form-field__input' ) {

        /**
         * Get just the input element using regex since 
         * DOMDocument changes where the label tag wraps 
         * (around the entire input elmement wrapper)
         * 
         */

        switch ( $tag ) {

            case "textarea":
            case "select":

                $re = sprintf( '/<%1$s.+<\/%1$s>/m', $tag );

                break;

            default:
            
                $re = sprintf('/<%s.+\/>/m', $tag);

        }


        preg_match_all($re, $field_content, $matches, PREG_SET_ORDER, 0);

        //var_dump( $matches );

        if ( !empty( $matches ) ) {

            foreach ( $matches as $match ) {
                $dom = new DOMDocument();
                $dom->loadHTML( $match[0], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
                $elements = $dom->getElementsByTagName( $tag );


                if ( !empty( $elements ) ) {
                    foreach ( $elements as $element ) {

                        $class = $element->getAttribute('class');
                        $element->setAttribute( 'class', $class . ' ' . $add_class );
                    }
                }

                $field_content = str_replace( $match[0], $dom->saveHTML(), $field_content );
            }

        }

        return $field_content;

    }







    public function gform_field_input( $input, $field, $value, $entry_id, $form_id ) {

        //var_dump( $field );




        return $input;
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
    public function input_to_button( $button, $form ) {
        
        $dom = new DOMDocument();
        $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
        $input = $dom->getElementsByTagName( 'input' )->item(0);
        $new_button = $dom->createElement( 'button' );

        if ( $input ) {
            $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
            $input->removeAttribute( 'value' );
        
        
        //var_dump( $input->attributes );
            foreach( $input->attributes as $attribute ) {
                $new_button->setAttribute( $attribute->name, $attribute->value );
                $new_button->setAttribute( 'class', 'button-atom button-atom--primary breakdance-form-button breakdance-form-button__submit');
            }
            $input->parentNode->replaceChild( $new_button, $input );
        
        }
        
        return $dom->saveHtml( $new_button );
    }


    
    public static function get_instance() {

        if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }



 }



 function BDGF() {
    return BDGF::get_instance();
 }

 BDGF();




