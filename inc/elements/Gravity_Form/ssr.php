<?php
/**
 * TODO: Implement a form counter, maybe via the main class, so that we can keep track if 
 * multiple forms are on the same page. The %%ID%% doesn't get searched/replaced until after the gravity form gets rendered
 * 
 */
use function BDGF\BDGF;

global $post;

$form_id = $propertiesData['content']['controls']['form'];

set_transient( 'bdgf_post_' . $post->ID . '_form_' . $form_id . '_settings', $propertiesData, 60 * 60 );

$FormStyler = new \BDGF\FormStyler($propertiesData);


//Gravity Form Arguments
$display_inactive = false;
$field_values = [
    'is_bd_element' => true,
    'post_id' => $post->ID
];
$ajax = isset( $propertiesData['globalSettings']) ? $propertiesData['content']['controls']['process_with_ajax'] ?? false : true;
$tabindex = 5;
$echo = true;
$form_theme = false;
$style_settings = false;


gravity_form( $form_id, $show_title, $show_description, $display_inactive, $field_values, $ajax, $tabindex, $echo, $form_theme, $style_settings );


$FormStyler->kill_modifications();