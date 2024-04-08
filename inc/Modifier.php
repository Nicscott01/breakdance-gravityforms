<?php

namespace BDGF;


class Modifier {

    private $id;
    public $tree;
    public $children;
    public $child;
    public $modifed_children_tree = [];

    public $element = '\BDGF\GravityForm';


    public function __construct( $id )
    {
        
        $this->id = $id;
        $this->load_tree();

    }




    public function load_tree() {

        $this->tree = \Breakdance\Data\get_tree( $this->id );
        $this->children = $this->tree['root']['children'];
        return $this;

    }


    public function traverse_tree_and_modify_child() {
        
        $this->look_for_child();

        return $this->modifed_children_tree;

        
    }



    public function look_for_child( $children = null ) {

        if ( isset( $children ) ) {

            $current_children = $children;

        } else {

            $current_children = $this->children;
        }

        foreach( $current_children as $child ) {

            if ( isset( $child['children'] ) ) {
             
                $this->modifed_children_tree[] = $child;
                
                //Keep looking
                $this->look_for_child( $child );
 
 
            } elseif ( $child['data']['type'] == $this->element ) {
 
                $child['data']['type']['properties']['content']['controls']['fields'][] = [
                    'field' => 'Field extra test',
                    'type' => 'single_line_text',
                    'width' => 6
                ];

                $this->modifed_children_tree[] = $child;
                   
            }
               
            
        }
 
         

       
    }


    

}



add_action( 'wp', function() {


    if ( isset( $_GET['bd_tree_test'] ) ) {

        $modifier = new Modifier( 1342 );
        $modifier->traverse_tree_and_modify_child();

        var_dump( $modifier );
    }

});


