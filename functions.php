<?php 
/**
 * 
 * Theme's functions and definitions.
 *
 **/

/* If this file is called directly, abort. */
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
*
* Define Constants
*
**/
define( 'PIMAIN', get_template_directory_uri() );
define( 'IMAGES', PIMAIN . '/assets/imgs' );
define( 'SCRIPTS', PIMAIN . '/assets/js' );
define( 'STYLES', PIMAIN . '/assets/css' );
define( 'FRAMEWORK', get_template_directory() . '/includes' );

/**
*
* Load required classes
*
**/
require_once( FRAMEWORK . '/class/class-pi-photography-init.php');


/**
 * Begins execution of the theme.
 *
 * Since everything within the theme is registered via hooks,
 * then kicking off the theme from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pi_photography() {

    $theme = new Pi_Photography();
    $theme->run();

}
run_pi_photography();


add_action( 'init', 'wptuts_buttons' );
function wptuts_buttons() {
    add_filter( "mce_external_plugins", "wptuts_add_buttons" );
    add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}
function wptuts_add_buttons( $plugin_array ) {
   $plugin_array['piPhotography'] = SCRIPTS . '/pi-tinymce.js';
   return $plugin_array;
}
function wptuts_register_buttons( $buttons ) {
   array_push($buttons, 'grid');
   return $buttons;
}
add_shortcode( 'col', 'pi_add_columns' );
function pi_add_columns( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'size' => '12',
        'position' => 'first'
    ), $atts ) );
    if( $size !== 'full'){
        if( $position == 'last' ){
            $html .= '<div class="col-'. $size .'">';
                $html .= do_shortcode($content);
            $html .= '</div></div>'; 
        }else{
            $html .= '<div class="row">';
                $html .= '<div class="col-'. $size .'">';
                    $html .= do_shortcode($content);
                $html .= '</div>'; 
        }

    }else{
        $html = '</div></div></div>';
                    $html .= do_shortcode($content);
        $html .= '<div class="container"><div class="row"><div class="col-12">';    

    }

    return $html;
}
// add_action( 'init', 'wptuts_buttons' );
// function wptuts_buttons() {
//     add_filter( "mce_external_plugins", "pi_add_editor_button" );
//     add_filter( 'mce_buttons', 'wptuts_register_buttons' );
// }
// function pi_add_editor_button( $plugin_array ) {
//     $plugin_array['wptuts'] = get_template_directory_uri() . '/wptuts-editor-buttons/wptuts-plugin.js';
//     return $plugin_array;
// }
// function wptuts_register_buttons( $buttons ) {
//     array_push( $buttons, 'dropcap', 'showrecent' ); // dropcap', 'recentposts
//     return $buttons;
// }