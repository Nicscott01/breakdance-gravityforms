<?php
/**
 * TODO: Implement a form counter, maybe via the main class, so that we can keep track if 
 * multiple forms are on the same page. The %%ID%% doesn't get searched/replaced until after the gravity form gets rendered
 * 
 */
use function BDGF\BDGF;

global $post;


//Load the design presets if they're being used.
if ( $propertiesData['design'] === null && isset( $propertiesData['meta']['preset']) ) {

    $design_preset = Breakdance\DesignPresets\getPreset( $propertiesData['meta']['preset'] );

    $propertiesData['design'] = $design_preset['design'];
}


$form_id = $propertiesData['content']['controls']['form'];


$form_obj = \GFAPI::get_form( $form_id );


//Check the form obj for any nested forms

$nested_form_ids = [];

if ( !empty( $form_obj ) ) {

    $nested_form_props = isset( $props ) ? $props : null;

    foreach( $form_obj['fields'] as $field ) {

        if ( $field->type == 'form' ) {

            //Set the $propertiesData form ID to the nested form
            $nested_form_ids[] = $field->gpnfForm;
        }

    }
}


//We set the transient so that this form can be styled when it's loaded via ajax. This transient is used in the filter gform_form_args
set_transient( 'bdgf_post_' . $post->ID . '_form_' . $form_id . '_settings', $propertiesData, 60 * 60 );


//Gravity Form Arguments
$display_inactive = false;
$field_values = [
    'is_bd_element' => true,
    'post_id' => $post->ID,
    'design_preset' => $propertiesData['meta']['preset'] ?? false,
    'parent_form_id' => $form_id,
    'nested_form_ids' => $nested_form_ids
];
$ajax = isset( $propertiesData['globalSettings']) ? $propertiesData['content']['controls']['process_with_ajax'] ?? false : true;
$tabindex = 5;
$echo = true;
$form_theme = false;
$style_settings = false;
$show_title = $propertiesData['content']['controls']['show_title'] ?? false;
$show_description = $propertiesData['content']['controls']['show_description'] ?? false;


gravity_form( $form_id, $show_title, $show_description, $display_inactive, $field_values, $ajax, $tabindex, $echo, $form_theme, $style_settings );








/**
 *  Use the field content filter to find a "form" field (Nested Form),
 *  then trigger the form filter for that form ID to add our BD classes
 *  so that form gets styled like this one.
 * 
 */

add_filter( 'gform_field_content_d', function( $html, $field, $value, $entry_id, $form_id ) {

    if ( $field->type == 'form' ) {

        $nested_form = $field->gpnfForm;

        add_filter( 'gform_pre_render', function( $form ) use ( $nested_form ) {

            if ( $form['id'] == $nested_form ) {

                //error_log( 'gform_pre_render should be nested: ' . print_r( $form, 1 ) );

                $form['cssClass'] = $form['cssClass'] . ' test';

            }

            return $form;

        }, 15, 1 );
    }

    return $html;

}, 10, 5 );

