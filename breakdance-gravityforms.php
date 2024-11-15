<?php
/**
 *  Plugin Name: Breakdance GravityForms
 *  Description: Apply Breakdance styling to Gravity Forms
 *  Author: Nic Scott
 *  Version: 0.5.1.dev
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
        require_once( __DIR__ . '/inc/CustomFieldDatePicker.php' );
     

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
       
            //error_log( '$_POST:' . print_r( $_POST, 1 ) );
            error_log( '$_REQUEST:' . print_r( $_REQUEST, 1 ) );
            //error_log( '$post: ' . print_r( $post, 1 ) );
            error_log( 'pre get transient gform_form_args: ' . print_r( $args, 1 ) );


            //This checks to see if GP Nested Forms are trying to refresh. They change the $field_values,
            //but since it's AJAX we can look at the $_REQUEST.
            if ( isset( $_REQUEST['gpnf_context']['field_values'] ) ) {
                
                $field_values = $_REQUEST['gpnf_context']['field_values'] ?? false;
                $is_bd_element = $_REQUEST['gpnf_context']['field_values']['is_bd_element'] ?? false;

                $post_id = $_REQUEST['gpnf_context']['field_values']['post_id'];
                $form_id = $_REQUEST['gpnf_context']['field_values']['parent_form_id'];
                $nested_form_ids = $_REQUEST['gpnf_context']['field_values']['nested_form_ids'];



            } else {

                $field_values = $args['field_values'] ?? false;
                $is_bd_element = $args['field_values']['is_bd_element'] ?? false;

                //Since the global $post isn't always there, we should have it stored here:
                $post_id = $args['field_values']['post_id'];
                $form_id = $args['field_values']['parent_form_id'];
                $nested_form_ids = $args['field_values']['nested_form_ids'];

            }


            if ( $field_values && $is_bd_element ) {

            
                error_log( 'Nested form ids within the gform_form_args: ' . json_encode( $nested_form_ids ) );

                //Grab the transient we set in the ssr.php file
                $props = get_transient( 'bdgf_post_' . $post_id . '_form_' . $form_id . '_settings' );

                if ( empty( $props ) ) {

                    error_log( 'BDGF: Could not retrieve $propertiesData from transient bdgf_post_' . $post_id . '_form_' . $form_id . '_settings' );
                    return $args;

                }



                //Check the form obj for any nested forms
                if ( !empty( $nested_form_ids ) ) {

                    //This is the parent, so make it true
                    new FormStyler( $props, false, true );

                    $nested_form_props = $props;
                
                    foreach( $nested_form_ids as $nested_form_id ) {

                        //Set the $propertiesData form ID to the nested form
                        $nested_form_props['content']['controls']['form'] = $nested_form_id;

                        //Style the form
                        new FormStyler( $nested_form_props, true, false );

                        error_log( 'BDGF: Triggered the form styling for nested form: ' . $nested_form_id );              
                        
                
                    }
                } else {

                    //Style the form
                    new FormStyler( $props );

                }

            
            } 

            return $args;

       }, 10, 3 );


       //do_action( 'gpnf_load_nested_form_hooks', $form_id, $parent_form_id );
       add_action( 'gpnf_load_nested_form_hooks_d', function( $form_id, $parent_form_id ){


            error_log(  'gpnf_load_nested_form_hooks, ' . $form_id . ' parent: ' . $parent_form_id );


       }, 10, 2 );


       /**
        *   TODO: This is running all the time, and we blindly grab the BD variables 
        *
        */

       add_filter( 'gpnf_init_script_args', function( $args, $field, $form ) {

            $args['modalHeaderColor'] = 'var(--bde-brand-primary-color)';
            $args['modalColors']['primary'] = 'var(--bde-brand-primary-color)';
            $args['modalColors']['secondary'] = 'var(--grey-500)';


            return $args;

        }, 10, 3 );












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
