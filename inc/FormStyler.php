<?php
namespace BDGF;

class FormStyler {

    //The BD Element props
    public $propertiesData;

    public $form_id;
    public $show_title;
    public $show_description;
    public $submit_button_style;
    public $previous_button_style;
    public $next_button_style;
    public $save_continue_button_style;
    public $form_direction;
    public $de_select_button_style;
    public $add_list_item_button_style;
    public $remove_list_item_button_style;
    public $hide_list_button_label;
    public $is_nested_form;
    public $is_parent_form;
    public $design_preset;

    public $gf_user_class;


    public function __construct( $propertiesData, $is_nested_form = false, $is_parent_form = false ) {

        //These are for use with Gravity Perks Nested Forms
        $this->is_nested_form = $is_nested_form;
        $this->is_parent_form = $is_parent_form;

        add_filter( 'gform_disable_css', '__return_true', 20 );
        add_filter( 'gform_disable_form_theme_css', '__return_true', 20 );


        $this->propertiesData = $propertiesData;
        $this->form_id = $propertiesData['content']['controls']['form'];
        $this->show_title = $propertiesData['content']['controls']['show_title'] ?? false;
        $this->show_description = $propertiesData['content']['controls']['show_description'] ?? false;
        $this->submit_button_style = isset( $propertiesData['design']['footer']['submit_button']['style'] ) ? $propertiesData['design']['footer']['submit_button']['style'] : 'primary';
        $this->previous_button_style = isset( $propertiesData['design']['footer']['previous_button']['style'] ) ? $propertiesData['design']['footer']['previous_button']['style'] : 'secondary';
        $this->next_button_style = isset( $propertiesData['design']['footer']['next_button']['style'] ) ? $propertiesData['design']['footer']['next_button']['style'] : 'secondary';
        $this->save_continue_button_style = isset( $propertiesData['design']['footer']['save_continue_button']['style'] ) ? $propertiesData['design']['footer']['save_continue_button']['style'] : 'primary';
        $this->form_direction = isset( $propertiesData['design']['form_elements']['direction'] ) ? $propertiesData['design']['form_elements']['direction'] : 'vertical';
        //form_elements.radio_checkbox.de_select_all_button
        $this->de_select_button_style = isset( $propertiesData['design']['form_elements']['radio_checkbox']['de_select_all_button']['style'] ) ?  $propertiesData['design']['form_elements']['radio_checkbox']['de_select_all_button']['style'] : 'secondary';
        //design.form_elements.list.add_button
        $this->add_list_item_button_style = isset( $propertiesData['design']['form_elements']['list']['add_button']['style'] ) ?  $propertiesData['design']['form_elements']['list']['add_button']['style'] : 'secondary';
        $this->remove_list_item_button_style = isset( $propertiesData['design']['form_elements']['list']['remove_button']['style'] ) ?  $propertiesData['design']['form_elements']['list']['remove_button']['style'] : 'secondary';
        //{% if design.form_elements.list.hide_button_label %}
        $this->hide_list_button_label = isset( $propertiesData['design']['form_elements']['list']['hide_button_label'] ) ? $propertiesData['design']['form_elements']['list']['hide_button_label']  : false;
        $this->design_preset = $propertiesData['meta']['preset'] ?? false;


        add_filter( 'gform_form_tag_' . $this->form_id, function( $form_tag, $form ) {
       
            // Use regex to find the opening form tag and add a data attribute
            $form_tag = preg_replace('/<form\b([^>]*?)>/i', '<form$1 data-bdgf-id="%%ID%%">', $form_tag );
    
            return $form_tag;
        
        }, 10, 2 );


        
        $this->apply_breakdance_styles_to_gf_fields();


        //Filter the main class
        if ( !empty( $this->form_direction ) ) {


            add_filter( 'gform_pre_render_' . $this->form_id, [ $this, 'set_form_user_class'], 1 ); 
            



            if ( $this->form_direction == 'vertical' ) {
                
                add_filter( 'gform_pre_render_' . $this->form_id, [ $this, 'gform_pre_render_vertical' ], 10 );
                add_filter( 'gform_pre_validation_' . $this->form_id, [ $this, 'gform_pre_render_vertical' ], 10 );
                add_filter( 'gform_pre_submission_filter_' . $this->form_id, [ $this, 'gform_pre_render_vertical' ], 10 );
                add_filter( 'gform_admin_pre_render_' . $this->form_id, [ $this, 'gform_pre_render_vertical' ], 10 );

            } else {
                
                add_filter( 'gform_pre_render_' . $this->form_id, [ $this, 'gform_pre_render_horizontal' ], 10 );
                add_filter( 'gform_pre_validation_' . $this->form_id, [ $this, 'gform_pre_render_horizontal' ], 10 );
                add_filter( 'gform_pre_submission_filter_' . $this->form_id, [ $this, 'gform_pre_render_horizontal' ], 10 );
                add_filter( 'gform_admin_pre_render_' . $this->form_id, [ $this, 'gform_pre_render_horizontal' ], 10 );

            }

        }


       

            
       
        //Submit Button
        add_filter( 'gform_submit_button_' . $this->form_id, '\BDGF\button_' . $this->submit_button_style, 10, 2 ); //hook after we transform the element into an html5 button in the main code

        //Pagination Buttons
        add_filter( 'gform_previous_button_' . $this->form_id, '\BDGF\button_' . $this->previous_button_style, 10, 2 );
        add_filter( 'gform_next_button_' . $this->form_id, '\BDGF\button_' . $this->next_button_style, 10, 2 );

        //Save & Continue Button

        $save_continue_button_style = $this->save_continue_button_style;

        add_filter( 'gform_savecontinue_link_' . $this->form_id, function( $link, $url ) use ( $save_continue_button_style ) {

            $link = str_replace( 'gform_save_link', 'gform_save_link button-atom button-atom--' . $save_continue_button_style, $link  );


            if ( in_array( $save_continue_button_style, ['primary','secondary'] ) ) {

                //Set default SVG to currentColor;
                $link = str_replace( 'fill="#6B7280"', 'fill="currentColor"', $link );

            } else {
                //Remove the SVG
                // Use a regular expression to remove <svg> tags and their contents
                $link = preg_replace('/<svg[^>]*>.*?<\/svg>/s', '', $link);

            }

            return $link;

        }, 10, 2);



   



        if ( !empty( $this->de_select_button_style ) ) {
            
            $de_select_button_style = $this->de_select_button_style;

            add_filter( 'gform_field_content', function( $field_content ) use( $de_select_button_style ) {

                $field_content = str_replace( 'gfield_choice_all_toggle', 'gfield_choice_all_toggle button-atom button-atom--' . $de_select_button_style, $field_content );

                return $field_content;
            });

        }


        if ( $this->hide_list_button_label ) {

            add_filter( 'gform_field_content', function( $field_content ) {

                $field_content = str_replace( '>Add<', '><span class="screen-reader-text">Add</span><', $field_content );
                $field_content = str_replace( '>Remove<', '><span class="screen-reader-text">Remove</span><', $field_content );

                return $field_content;
            });
        }


        if ( !empty( $this->add_list_item_button_style ) ) {
            
            $add_list_item_button_style = $this->add_list_item_button_style;

            add_filter( 'gform_field_content', function( $field_content ) use( $add_list_item_button_style ) {

                $field_content = str_replace( 'add_list_item', 'add_list_item button-atom button-atom--' . $add_list_item_button_style, $field_content );

                return $field_content;
            });

        }


        if ( !empty( $this->remove_list_item_button_style ) ) {
            

            $remove_list_item_button_style = $this->remove_list_item_button_style;

            add_filter( 'gform_field_content', function( $field_content ) use( $remove_list_item_button_style ) {

                $field_content = str_replace( 'delete_list_item', 'delete_list_item button-atom button-atom--' . $remove_list_item_button_style, $field_content );

                return $field_content;
            });

        }



        if ( isset( $propertiesData['globalSettings']) ) {
            //'Front End';

        } else {
            //'Back End';
            add_filter( 'gform_pre_render_' . $this->form_id, '\BDGF\remove_conditional_logic_fields', 10, 1 );
                    

        }



        if ( $this->is_parent_form ) {
            //error_log( 'enqueue_script ' . \plugin_dir_url( dirname(__FILE__, 1 ) ). 'assets/js/nested-form-styler.js' );
            
           wp_enqueue_script( 'bdgf-nested-form-styler', \plugin_dir_url( dirname(__FILE__, 1 )). 'assets/js/nested-form-styler.js', ['jquery'], null, true );
        }

        if ( $this->is_nested_form ) {
            
            add_filter( 'gpnf_init_script_args_' . $this->form_id, function( $args, $field, $form ) {

                $args['modalHeaderColor'] = '#000';
                $args['modalColors']['primary'] = 'var(--bde-brand-primary-color)';
                $args['modalColors']['secondary'] = 'var(--bde-brand-secondary-color)';

                return $args;

            }, 10, 3 );
        }



        /**
         *  Color the indicator for the required fields legend
         * 
         */
        add_filter( 'gform_required_legend_' . $this->form_id, function( $legend ) {

            $legend = str_replace( 'gfield_required', 'gfield_required breakdance-form-field__required', $legend );

            return $legend;

        }, 10 );



        //Style Stripe Checkoug
        add_filter( 'gform_stripe_elements_style', [ $this, 'style_stripe_form' ], 10, 3 );


        //GravityForms File Uploader
        //$file_upload_markup = apply_filters( 'gform_file_upload_markup', $file_upload_markup, $file_info, $form_id, $id );
        add_filter( 'gform_file_upload_markup', [ $this, 'style_file_upload_markup' ], 10, 4 );

        //error_log( 'Finished the construct for FormStyler, form_id: ' . $this->form_id );
        //error_log( 'FormStyler: ' . print_r( $this, 1 ) );




    }


    /**
     * Edit file upload markup for 
     * Breakdance button
     * 
     *      <span class="gfield_fileupload_filename">user-icon.png</span>
     *      <span class="gfield_fileupload_progress gfield_fileupload_progress_complete">
     *          <span class="gfield_fileupload_progressbar">
     *              <span class="gfield_fileupload_progressbar_progress" style="width: 100%;"></span>
     *          </span>
     *          <span class="gfield_fileupload_percent">100%</span>
     *      </span>
     *      <button class="gform_delete_file gform-theme-button gform-theme-button--simple" onclick="gformDeleteUploadedFile( 1, 22, this );">
     *          <span class="dashicons dashicons-trash" aria-hidden="true"></span>
     *          <span class="screen-reader-text">Delete this file: user-icon.png</span>
     *      </button>

     */

    public function style_file_upload_markup( $file_upload_markup, $file_info, $form_id, $id ) {

        $button_style = $this->propertiesData['design']['form_elements']['uploader']['trash_button']['style'];

        //error_log( 'button_style:' . print_r( $button_style, 1 ) );

        $file_upload_markup = str_replace( 'gform_delete_file', sprintf( 'button-atom button-atom--%s gform_delete_file', $button_style), $file_upload_markup );

        return $file_upload_markup;
    }



    public function set_form_user_class( $form ) {


        if ( empty( $this->gf_user_class ) ) {   

            $this->gf_user_class = isset( $form['cssClass'] ) ? $form['cssClass'] : '';
        }

        return $form;

    }





    public function apply_breakdance_styles_to_gf_fields() {
 
         add_filter( 'gform_field_content_' . $this->form_id, [ $this, 'gform_field_content' ], 10, 5 );
 
         add_filter( 'gform_next_button_' . $this->form_id,  'BDGF\input_to_button', 10, 2 );
         add_filter( 'gform_previous_button_' . $this->form_id,  'BDGF\input_to_button', 10, 2 );
         add_filter( 'gform_submit_button_' . $this->form_id,  'BDGF\input_to_button', 10, 2 );
 
    }







    /**
     *  CSS class of the form element
     * 
     * 
     */


    public function gform_pre_render_direction( $form, $direction = 'vertical' ) {

        $form['cssClass'] = $this->gf_user_class;

        if ( $this->is_nested_form ) {
            $form['cssClass'] .= ' bdgf';

            if ( $this->design_preset ) {
                $form['cssClass'] .= ' bde-preset-' . $this->design_preset;
            }
        }

        $form['cssClass'] .= ' bdgf-%%ID%% breakdance-form breakdance-form--' .  $direction;

        return $form;
    }


    public function gform_pre_render_horizontal( $form ) {

        return $this->gform_pre_render_direction( $form, 'horizontal' );

    }


    public function gform_pre_render_vertical( $form ) {
        
        return $this->gform_pre_render_direction( $form, 'vertical' );

    }







    public function gform_field_content( $field_content, $field, $value, $entry_id, $form_id ) {

        //var_dump( $field->type );
        //var_dump ( $field_content );

        switch( get_class( $field ) ) {

            case "GF_Field_List" :

                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );


                $columns = $field->choices;

                $row = 1;

                foreach ( $columns as $column ) {

                    $search = sprintf( 'aria-label=\'%s, Row %s\'', $column['text'], $row );

                    $replace = sprintf( ' placeholder="%s"', $column['text'] );

                    $field_content = str_replace( $search, $search . $replace, $field_content );

                }


            case "GF_Field_Consent" :
            case "GF_Field_Checkbox":


                //ginput_container ginput_container_consent 
                if ( $field->type == 'consent' ) {

                    $field_content = class_replace( 'ginput_container_consent', 'ginput_container_consent breakdance-form-checkbox', $field_content );

                }


                $field_content = class_replace( 'gchoice', 'breakdance-form-checkbox gchoice', $field_content );
                $field_content = class_replace( 'gform-field-label--type-inline', ' gform-field-label--type-inline breakdance-form-checkbox__text ', $field_content );
                $field_content = class_replace( 'gfield_label_before_complex', 'gfield_label_before_complex breakdance-form-field__label bdgf-choice-label', $field_content );
                //$field_content = str_replace( 'gfield_choice_all_toggle', 'gfield_choice_all_toggle button-atom', $field_content );

                break;

            case "GF_Field_Radio":
            
                $field_content = str_replace( 'gchoice ', 'breakdance-form-radio gchoice ', $field_content );
                //Only replce inline classes (the label next to the radio)
                $field_content = class_replace( 'gform-field-label--type-inline', 'gform-field-label--type-inline breakdance-form-radio__text ', $field_content );
                //$field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );
                $field_content = str_replace( 'ginput_amount', 'breakdance-form-field__input ginput_amount', $field_content );
                $field_content = str_replace( "<legend class='gfield_label ", "<legend class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );
                $field_content = str_replace( "<label class='gfield_label ", "<label class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );
                $field_content = str_replace( "gchoice_other_control", "gchoice_other_control breakdance-form-field__input", $field_content );

                break;



            case "GF_Field_Select" :


                $field_content = str_replace( 'gfield--type-select', 'breakdance-form-field breakdance-form-field__select gfield--type-select', $field_content );
                $field_content = str_replace( 'gfield_select', 'breakdance-form-field__input gfield_select', $field_content );
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );


                break;

            case "GF_Field_MultiSelect" :

                //breakdance-form-field__input
                $field_content = str_replace( 'gfield_select', 'breakdance-form-field__input gfield_select', $field_content );
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                break;

            case "GF_Field_Total" :

                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );

                $field_content = class_replace( 'ginput_total ', 'ginput_total breakdance-form-field__input ', $field_content );


                break;


            case "GF_Field_Textarea" :

                //Style the label
                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = dom_document_replacement( 'textarea', $field_content );

                break;

            case "GF_Field_Text":
            case "GF_Field_Number":
            case "GF_Field_Phone":
            case "GF_Field_Name":
            case "GF_Field_Email":
            case "GF_Field_Website":
            case "BDGF\GF_Field_ModernDatePicker":
            case "GF_Field_Advanced_Date":

                //Style the label                
                $field_content = class_replace( 'gfield_label gform-field-label', 'gfield_label breakdance-form-field__label gform-field-label', $field_content );

                $field_content = dom_document_replacement( 'input', $field_content );

                break;

            //TODO: Add install gravity perks to find out class of the field
            case "form" : //Gravity perks nested form   

                $field_content = class_replace( 'gfield_label gform-field-label', 'gfield_label breakdance-form-field__label gform-field-label', $field_content );

                $field_content = class_replace( 'gpnf-add-entry', 'gpnf-add-entry button-atom button-atom--secondary', $field_content );

                break;
                

            case "GF_Field_Address":

                //Style the label
                //$field_content = str_replace( 'gform-field-label', 'gform-field-label breakdance-form-field__label', $field_content );
                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                

                $field_content = dom_document_replacement( 'input', $field_content );
                $field_content = dom_document_replacement( 'select', $field_content );

                $field_content = class_replace( 'copy_values_option_container', 'copy_values_option_container breakdance-form-checkbox', $field_content );


                break;
            
            case "GF_Field_FileUpload" :

                
                $icon = $this->propertiesData['design']['form_elements']['uploader']['icon']['svgCode'];
                //error_log( 'icon' . print_r(  $icon, 1) );

                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                $field_content = class_replace( 'button gform_button_select_files', 'button gform_button_select_files button-atom button-atom--secondary', $field_content );

                $icon_with_text = sprintf( '<span class="drop-zone-icon">%s</span>Drop files here or ', $icon );

                $field_content = str_replace( 'Drop files here or ', $icon_with_text, $field_content );

                break;

            case "GF_Field_Date":

                $field_content = dom_document_replacement( 'select', $field_content );

            case "GF_Field_SingleProduct" :
            case "GF_Field_Price" :
                
                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                $field_content = class_replace( 'ginput_amount', 'breakdance-form-field__input ginput_amount', $field_content );

                //Break after doing pricing because our default removes the `ginput_amount` class. We should probably look at a more elegant solution?
                break;

            case "flatpickr_date" :
            case "GF_Field_Post_Title" :
            case "GF_Field_Post_Content" :
            case "GF_Field_Post_Excerpt" :
            case "GF_Field_Post_Tags" :
            case "GF_Field_Post_Image" :
            case "GF_Field_SingleShipping" :
            case "GF_Field_Time" :
            case "GF_Field_Quantity" :

                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

            case "GF_Field_Time" :

                $field_content = str_replace( '<select', '<select class="breakdance-form-field breakdance-form-field__input"', $field_content );


            case "GF_Field_Calculation" :

                $field_content = class_replace( 'gfield_label_product', 'breakdance-form-field__label gfield_label_product', $field_content );
                $field_content = class_replace( 'ginput_quantity_label', 'breakdance-form-field__label ginput_quantity_label', $field_content );


            default:
            
                //var_dump( get_class( $field ) );   

                //$field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );
            
        }

        //Remove weird extra div that throws off layout
        $field_content = str_replace( "<div class='gf_clear gf_clear_complex'></div>", '', $field_content );


        //Required label coloring
        $required_label_search = [
            'gfield_required gfield_required_asterisk',
            'gfield_required gfield_required_text',
            'gfield_required gfield_required_custom'
        ];

        $required_label_replace = [
            'gfield_required breakdance-form-field__required gfield_required_asterisk ',
            'gfield_required breakdance-form-field__required gfield_required_text',
            'gfield_required breakdance-form-field__required gfield_required_custom'
        ];

        $field_content = str_replace( $required_label_search, $required_label_replace, $field_content );

        return $field_content;
    }




    public function style_stripe_form( $card_styles, $form_id, $is_payment_element_enabled ) {

        //error_log( 'card_styles: ' . print_r( $card_styles, 1 ));
        //error_log( 'is_payment_element_enabled: '. print_r( $is_payment_element_enabled, 1 ) );

        //Start with the global styles
        $global_styles = \Breakdance\Data\get_global_settings_array();

        $primary_color = null;
        $text_color = null;
        $headings_color = null;
        $form_input_border_radius = null;
        $body_typography = null;
        $body_font_size_global = null;
        $label_font_size_form = null;
        $font_weight_label = null;
        $field_margin_bottom = null;
        $label_margin_bottom = null;

        if ( isset( $global_styles['settings']['colors'] ) ) {

            $primary_color = isset($global_styles['settings']['colors']['brand']) ? $global_styles['settings']['colors']['brand'] : null;
            $text_color    = isset($global_styles['settings']['colors']['text']) ? $global_styles['settings']['colors']['text'] : null;
            $headings_color = isset($global_styles['settings']['colors']['headings']) ? $global_styles['settings']['colors']['headings'] : null;
        
        } 


        if ( isset( $global_styles['settings'] ) ) {

            //Defaults
            //--bde-form-input-border-radius
            $form_input_border_radius =  $global_styles['settings']['forms']['fields']['borders']['radius']['breakpoint_base']['all']['style'] ?? '3px';

            //Typography
            $body_typography = \Breakdance\Fonts\process_font( $global_styles['settings']['typography']['body_font'] ) ?? '';
            
            //Font Size
            $body_font_size_global = isset($global_styles['settings']['typography']['base_size']['breakpoint_base']['style']) ? $global_styles['settings']['typography']['base_size']['breakpoint_base']['style'] : null;
            
        }



        if ( isset( $this->propertiesData['design']['form_elements']['labels']['primary_typography'] ) ) {
        
            $label_font_size_form = $this->propertiesData['design']['form_elements']['labels']['primary_typography']['typography']['custom']['customTypography']['fontSize']['breakpoint_base']['style'];

            //Font Weight
            //var(--bde-form-label-font-weight)
            $font_weight_label = $this->propertiesData['design']['form_elements']['labels']['primary_typography']['typography']['custom']['customTypography']['fontWeight']['breakpoint_base'] ?? '500';

        }

    
        if ( isset( $this->propertiesData['design']['form_elements']['field_spacing'] ) ) {

            //Field margin bottom
            $field_margin_bottom = $this->propertiesData['design']['form_elements']['field_spacing']['margin_bottom']['breakpoint_base']['style'];

        }


        if ( isset( $this->propertiesData['design']['form_elements']['labels'] ) ) {
            //Label margin bottom
            $label_margin_bottom = $this->propertiesData['design']['form_elements']['labels']['primary_spacing']['margin_bottom']['breakpoint_base']['style'];

        }

        //error_log( 'field_margin_bottom: ' . print_r( $field_margin_bottom, 1 ) );
        //error_log( 'global_styles' . print_r( $global_styles, 1 ));


        $card_styles['theme'] = 'minimal';

        if ( $is_payment_element_enabled ) {

            // reference https://docs.stripe.com/elements/appearance-api
            if ( !empty( $primary_color ) ) {
                $card_styles['variables']['colorPrimary'] = $primary_color;
            }
            if ( !empty( $text_color ) ) {
                $card_styles['variables']['colorTextSecondary'] = $text_color;
            }
            if ( !empty( $headings_color ) ) {
                $card_styles['variables']['colorText'] = $headings_color;
            }
            if ( !empty( $body_typography ) ) {
                $card_styles['variables']['fontFamily'] = $body_typography;
            }

            if ( !empty( $body_font_size_global ) ) {
                $card_styles['variables']['fontSizeBase'] = $body_font_size_global;
            }
            $card_styles['variables']['tabSpacing'] = '3rem';
            if ( !empty( $form_input_border_radius ) ) {
                $card_styles['variables']['borderRadius'] = $form_input_border_radius;
            }
            if ( !empty( $field_margin_bottom ) ) {
                $card_styles['variables']['spacingGridRow'] = $field_margin_bottom;
            }
             
            $rules = [];

            if ( !empty( $label_font_size_form ) ) {
                $rules['fontSize'] = $label_font_size_form;
            }
            if ( !empty( $font_weight_label ) ) {
                $rules['fontWeight'] = $font_weight_label;
            }
            if ( !empty( $label_margin_bottom ) ) {
                $rules['marginBottom'] = $label_margin_bottom;
            }

            if ( !empty( $body_typography ) ) {
                $rules['fontFamily'] = $body_typography;
            }

            if ( !empty( $rules ) ) {
                $card_styles['rules'] = [
                    '.Label' => $rules
                ];
            }
     
        } else {
     
            // reference https://docs.stripe.com/js/appendix/style
            $card_styles['base'] = array(
                'color' => $primary_color,
                'fontFamily' => $body_typography,
                'fontSize' => '22px',
                'borderColor' => '#000000',
                ':focus' => array(
                    'color' => '#272829'
                )
            );
     
            $card_styles = [];
        }
         
        return $card_styles;

    }





    public function kill_modifications() {


        //Kill our filters in case there's another form on the page that we don't want to influence

    }








}