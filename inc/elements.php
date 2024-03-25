<?php



namespace BDGF;


use function Breakdance\Util\getDirectoryPathRelativeToPluginFolder;
use GFAPI;



class Elements {


    public function __construct() {

        add_action( 'breakdance_loaded', [ $this, 'register_save_location' ], 9 ); 
        add_action( 'breakdance_loaded', [ $this, 'register_handler' ], 9 ); 


    }







    public function register_save_location() {
            
        \Breakdance\ElementStudio\registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/elements',
            'BDGF',
            'element',
            'BDGF Elements',
            false
        );
        

        \Breakdance\ElementStudio\registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/macros',
            'BDGF',
            'macro',
            'BDGF Macros',
            false,
        );
    
        \Breakdance\ElementStudio\registerSaveLocation(
            getDirectoryPathRelativeToPluginFolder(__DIR__) . '/presets',
            'BDGF',
            'preset',
            'BDGF Presets',
            false,
        );



    }






    public function register_handler() {
        \Breakdance\AJAX\register_handler(
            'bdgf_get_forms',
            [ $this, 'get_forms' ],
            'edit',
            true,
            [],
            false
        );

    }




    public function get_forms() {


        if ( ! class_exists( 'GFAPI' ) ) {
            return;
        }


        $forms = GFAPI::get_forms();
/*
        ob_start();
        var_dump( $forms );
        $log = ob_get_clean();
error_log( $log );
*/
        $return_forms = [];
        
        
        foreach( $forms as $form ) {

            $return_forms[] = [
                'text' => $form['title'],
                'value' => strval($form['id'])
            ];

        }


        return $return_forms;


        return [ 
            [
                'text' => 'Test form 1',
                'value' => 'form_1'
            ],
            [
                'text' => 'Test form 2',
                'value' => 'form_2'
            ]
        ];
    
    
    }







}



new Elements();