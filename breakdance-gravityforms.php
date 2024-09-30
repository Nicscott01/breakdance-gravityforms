<?php
/**
 *  Plugin Name: Breakdance GravityForms
 *  Description: Apply Breakdance styling to Gravity Forms
 *  Author: Nic Scott
 *  Version: 0.5
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
        require_once( __DIR__ . '/inc/FormStyler.php' );
        require_once( __DIR__ . '/inc/Modifier.php' );
     

        add_filter('gform_validation_d', function($validation_result) {

            // Check if you're in the Breakdance Builder or on a specific form/page
            if ( strpos( '/wp/', $_SERVER['REQUEST_URI'] ) === 0 ||  isset( $_GET['preview'] ) && $_GET['preview'] == 'true' ) {
                // Bypass validation for this form
                $form = $validation_result['form'];
                foreach ($form['fields'] as &$field) {
                    $field->failed_validation = false;
                    $field->validation_message = '';
                }
                $validation_result['is_valid'] = true;
            }
        
            return $validation_result;
        });




       /**
        *   This is our main way to ensure our BD classes
        *   get addd to the form when ajax is enabled.
        *
        *   We need to figure out a way to ensure that only
        *   the forms that get init'd by BD get this.
        *   
        * 
        */
       add_action( 'gform_form_args', function( $args ) {

            global $post;
       
            //error_log( 'gform_form_args: ' . print_r( $args, 1 ) );


            if ( !empty( $args['field_values'] ) && isset( $args['field_values']['is_bd_element'] ) ) {

                //set_transient( 'bdgf_post_' . $post->ID . 'form_' . $form_id . '_settings', $propertiesData, 60 * 60 );

                $props = get_transient( 'bdgf_post_' . $post->ID . '_form_' . $args['form_id'] . '_settings' );

                error_log( json_encode( $props ) );

                $FormStyler = new FormStyler( $props );
                
            } 

            return $args;

       }, 10, 3 );


       add_filter( 'gform_pre_render_d', function( $form ) {

        error_log( "form: \n\r" . print_r( $form, 1 ) );

        return $form;

       }, 10, 1 );









       /**
        *  Dependencies
        */

        add_filter( 'breakdance_reusable_dependencies_urls', function ($urls) {
    
            //    $urls['bsAccordion'] = plugin_dir_url( __FILE__ ) . 'dependencies-files/bootstrap-partials@1/bootstrap-partial.min.js';
                $urls['bdgfJquery'] = site_url() . '/wp-includes/js/jquery/jquery.min.js';
        
                //%%BREAKDANCE_REUSABLE_BDGF_DEFAULT_CSS%%
                $urls['bdgfDefaultCss'] = plugin_dir_url( __FILE__ ). 'css/normalize.css';

                //%%BREAKDANCE_REUSABLE_BDGF_PAGER_HELPER_JS%%
                $urls['bdgfPagerHelperJs'] = plugin_dir_url( __FILE__ ). 'assets/src/js/pager-helper.js';

                //GFFormDisplay::get_form_enqueue_assets( $form, $is_ajax );

            //_log( site_url() . '/wp-includes/js/jquery/jquery.min.js' );

            return $urls;
            
        });

    

        
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