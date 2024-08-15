<?php

//var_dump( $propertiesData );

use function BDGF\BDGF;

$form_id = $propertiesData['content']['controls']['form'];
$show_title = $propertiesData['content']['controls']['show_title'];
$show_description = $propertiesData['content']['controls']['show_description'];
$hide_complex_labels = isset( $propertiesData['design']['form_elements']['labels']['hide_complex_labels'] ) ? $propertiesData['design']['form_elements']['labels']['hide_complex_labels'] : false;
$submit_button_style = isset( $propertiesData['design']['form_elements']['footer']['button']['style'] ) ? $propertiesData['design']['form_elements']['footer']['button']['style'] : 'primary';
$form_direction = isset( $propertiesData['design']['form_elements']['direction'] ) ? $propertiesData['design']['form_elements']['direction'] : 'vertical';
//form_elements.radio_checkbox.de_select_all_button
$de_select_button_style = isset( $propertiesData['design']['form_elements']['radio_checkbox']['de_select_all_button']['style'] ) ?  $propertiesData['design']['form_elements']['radio_checkbox']['de_select_all_button']['style'] : 'secondary';
//design.form_elements.list.add_button
$add_list_item_button_style = isset( $propertiesData['design']['form_elements']['list']['add_button']['style'] ) ?  $propertiesData['design']['form_elements']['list']['add_button']['style'] : 'secondary';
$remove_list_item_button_style = isset( $propertiesData['design']['form_elements']['list']['remove_button']['style'] ) ?  $propertiesData['design']['form_elements']['list']['remove_button']['style'] : 'secondary';
//{% if design.form_elements.list.hide_button_label %}
$hide_list_button_label = isset( $propertiesData['design']['form_elements']['list']['hide_button_label'] ) ? $propertiesData['design']['form_elements']['list']['hide_button_label']  : false;




//Filter the main class
if ( !empty( $form_direction ) ) {

    $transient = 'form_' . $form_id . '_' . 'direction';

    if ( $form_direction == 'vertical' ) {
        
        add_filter( 'gform_pre_render', 'BDGF\gform_pre_render_vertical', 10 );
        add_filter( 'gform_pre_validation', 'BDGF\gform_pre_render_vertical', 10 );
        add_filter( 'gform_pre_submission_filter', 'BDGF\gform_pre_render_vertical', 10 );
        add_filter( 'gform_admin_pre_render', 'BDGF\gform_pre_render_vertical', 10 );

        set_transient( $transient, 'vertical', 60 );


    } else {
        add_filter( 'gform_pre_render', 'BDGF\gform_pre_render_horizontal', 10 );
        add_filter( 'gform_pre_validation', 'BDGF\gform_pre_render_horizontal', 10 );
        add_filter( 'gform_pre_submission_filter', 'BDGF\gform_pre_render_horizontal', 10 );
        add_filter( 'gform_admin_pre_render', 'BDGF\gform_pre_render_horizontal', 10 );

        set_transient( $transient, 'horiztonal', 60 );

    }

}



//Add a class to the form if we want to hide labels so we don't have a ton of extra gross css (because we want to maintain screen reader text)
if ( $hide_complex_labels ) {

    add_filter( 'gform_field_content', '\BDGF\complex_labels_to_screen_reader_text', 10, 5 );

}


if ( !empty( $submit_button_style ) ) {
    
    $button_callback = '\BDGF\button_' . $submit_button_style;
    add_filter( 'gform_submit_button', $button_callback, 11, 2 ); //hook after we transform the element into an html5 button in the main code

}


if ( !empty( $de_select_button_style ) ) {
    

    add_filter( 'gform_field_content', function( $field_content ) use( $de_select_button_style ) {

        $field_content = str_replace( 'gfield_choice_all_toggle', 'gfield_choice_all_toggle button-atom button-atom--' . $de_select_button_style, $field_content );

        return $field_content;
    });

}


if ( $hide_list_button_label ) {

    add_filter( 'gform_field_content', function( $field_content ) {

        $field_content = str_replace( '>Add<', '><span class="screen-reader-text">Add</span><', $field_content );
        $field_content = str_replace( '>Remove<', '><span class="screen-reader-text">Remove</span><', $field_content );

        return $field_content;
    });
}


if ( !empty( $add_list_item_button_style ) ) {
    

    add_filter( 'gform_field_content', function( $field_content ) use( $add_list_item_button_style ) {

        $field_content = str_replace( 'add_list_item', 'add_list_item button-atom button-atom--' . $add_list_item_button_style, $field_content );

        return $field_content;
    });

}


if ( !empty( $remove_list_item_button_style ) ) {
    

    add_filter( 'gform_field_content', function( $field_content ) use( $remove_list_item_button_style ) {

        $field_content = str_replace( 'delete_list_item', 'delete_list_item button-atom button-atom--' . $remove_list_item_button_style, $field_content );

        return $field_content;
    });

}





//gravity_form_enqueue_scripts( $form_id, false );
//var_dump( (int) $form_id );
//var_dump( $assets = GFFormDisplay::get_form_enqueue_assets( (int) $form_id, false ) );


if ( isset( $propertiesData['globalSettings']) ) {
    //'Front End';

} else {
    //'Back End';
    add_filter( 'gform_pre_render_' . $form_id, '\BDGF\remove_conditional_logic_fields', 10, 1 );

}

//gravity_form( $id_or_title, $display_title = true, $display_description = true, $display_inactive = false, $field_values = null, $ajax = false, $tabindex, $echo = true, $form_theme = null, $style_settings = null );
echo gravity_form( $form_id, $show_title, $show_description, true, null, true, null, false, null, false );


//Kill our filters in case there's another form on the page that we don't want to influence
if ( $hide_complex_labels ) {

    remove_filter( 'gform_field_content', '\BDGF\complex_labels_to_screen_reader_text', 10, 5 );

}