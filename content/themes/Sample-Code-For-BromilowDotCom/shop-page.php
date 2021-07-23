<?php
/**
 * Genesis Sample
 *
 * This file adds the front page to the Genesis Sample Theme.

 */


// Add shop-page body class.
add_filter( 'body_class', 'genesis_body_class' );


// Define shop-page body class.
function genesis_body_class( $classes ) {

        $classes[] = 'shop-page';

        return $classes;

}

function render_front(){

$hero= get_field('hero');


if( $hero ){
        echo '<div class="'.$hero['class_name'].'">
                  </div>';

}


$hero= get_field('hero');


if( $hero ){
        echo '<div class="'.$hero['class_name'].'">
                <div class="wrap mobile-lr-padding">
                </div>
              </div>';

}

}
add_action('genesis_loop', 'render_front');

// Run the Genesis loop.
genesis();
