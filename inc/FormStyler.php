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
            error_log( 'enqueue_script ' . \plugin_dir_url( dirname(__FILE__, 1 ) ). 'assets/js/nested-form-styler.js' );
            
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



        error_log( 'Finished the construct for FormStyler, form_id: ' . $this->form_id );
        error_log( 'FormStyler: ' . print_r( $this, 1 ) );

    }




    public function set_form_user_class( $form ) {


        if ( empty( $this->gf_user_class ) ) {   

            $this->gf_user_class = $form['cssClass'];
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

        switch( $field->type ) {

            case "list" :

                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );


                $columns = $field->choices;

                $row = 1;

                foreach ( $columns as $column ) {

                    $search = sprintf( 'aria-label=\'%s, Row %s\'', $column['text'], $row );

                    $replace = sprintf( ' placeholder="%s"', $column['text'] );

                    $field_content = str_replace( $search, $search . $replace, $field_content );

                }


            case "consent" :
            case "checkbox":

                //ginput_container ginput_container_consent 
                if ( $field->type == 'consent' ) {

                    $field_content = class_replace( 'ginput_container_consent', 'ginput_container_consent breakdance-form-checkbox', $field_content );

                }

                $field_content = class_replace( 'gchoice', 'breakdance-form-checkbox gchoice', $field_content );
                $field_content = class_replace( 'gform-field-label--type-inline', ' gform-field-label--type-inline breakdance-form-checkbox__text ', $field_content );
                $field_content = class_replace( 'gfield_label_before_complex', 'gfield_label_before_complex breakdance-form-field__label bdgf-choice-label', $field_content );
                //$field_content = str_replace( 'gfield_choice_all_toggle', 'gfield_choice_all_toggle button-atom', $field_content );

                break;

            case "radio":
            case "product":
            


                $field_content = str_replace( 'gchoice ', 'breakdance-form-radio gchoice ', $field_content );
                $field_content = str_replace( 'gform-field-label ', 'breakdance-form-radio__text gform-field-label ', $field_content );
                //$field_content = str_replace( '<input ', '<input class="breakdance-form-field__input" ', $field_content );
                $field_content = str_replace( 'ginput_amount', 'breakdance-form-field__input ginput_amount', $field_content );
                $field_content = str_replace( "<legend class='gfield_label ", "<legend class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );
                $field_content = str_replace( "<label class='gfield_label ", "<label class='breakdance-form-field__label gfield_label bdgf-choice-label ", $field_content );
                $field_content = str_replace( "gchoice_other_control", "gchoice_other_control breakdance-form-field__input", $field_content );

                break;

            case "select" :

                $field_content = str_replace( 'gfield--type-select', 'breakdance-form-field breakdance-form-field__select gfield--type-select', $field_content );
                $field_content = str_replace( 'gfield_select', 'breakdance-form-field__input gfield_select', $field_content );
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );


                break;

            case "multiselect" :

                //breakdance-form-field__input
                $field_content = str_replace( 'gfield_select', 'breakdance-form-field__input gfield_select', $field_content );
                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                break;

            case "total" :

                $field_content = str_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label bdgf-choice-label', $field_content );

                break;


            case "textarea" :

                //Style the label
                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );

                $field_content = dom_document_replacement( 'textarea', $field_content );

                break;

            case "text":
            case "number":
            case "phone":
            case "name":
            case "email":
            case "modern_date_picker":

                //Style the label                
                $field_content = class_replace( 'gfield_label gform-field-label', 'gfield_label breakdance-form-field__label gform-field-label', $field_content );

                $field_content = dom_document_replacement( 'input', $field_content );

                break;

            case "form" : //Gravity perks nested form

                $field_content = class_replace( 'gfield_label gform-field-label', 'gfield_label breakdance-form-field__label gform-field-label', $field_content );

                $field_content = class_replace( 'gpnf-add-entry', 'gpnf-add-entry button-atom button-atom--secondary', $field_content );

                break;
                

            case "address":

                //Style the label
                //$field_content = str_replace( 'gform-field-label', 'gform-field-label breakdance-form-field__label', $field_content );
                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                

                $field_content = dom_document_replacement( 'input', $field_content );
                $field_content = dom_document_replacement( 'select', $field_content );

                $field_content = class_replace( 'copy_values_option_container', 'copy_values_option_container breakdance-form-checkbox', $field_content );


                break;
            
            case "fileupload" :

                $field_content = class_replace( 'gform-field-label', 'breakdance-form-field__label gform-field-label', $field_content );
                $field_content = class_replace( 'button gform_button_select_files', 'button gform_button_select_files button-atom button-atom--secondary', $field_content );

                break;

            case "date":

                $field_content = dom_document_replacement( 'select', $field_content );

            default:
            
               /* $field_content = preg_replace(
                    '/\b(gform-field-label)\b/',
                    'breakdance-form-field__label gform-field-label',
                    $field_content
                );*/

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








    public function kill_modifications() {


        //Kill our filters in case there's another form on the page that we don't want to influence

    }








}