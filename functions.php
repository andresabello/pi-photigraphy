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

//add_action('admin_init', 'print_slider', 20);
//
//function print_slider(){
////    $args = array(
////        'posts_per_page'   => -1,
////        'post_type'        => 'pi_slider',
////        'post_status'      => 'publish',
////        'suppress_filters' => true
////    );
////    $posts_array = get_posts( $args );
////    foreach ($posts_array as $key => $value){
////        echo '<div class="container">';
////        var_dump(get_post_meta($value->ID, 'pi_plupload'));
////        echo '</div>';
////    }
////
////    $query_images_args = array(
////        'post_type'      => 'attachment',
////        'post_mime_type' => 'image',
////        'post_status'    => 'inherit',
////        'posts_per_page' => 5,
////    );
////
////    $query_images = new WP_Query( $query_images_args );
////
////    $images = array();
////    foreach ( $query_images->posts as $image ) {
////        $images[$image->ID] = wp_get_attachment_url( $image->ID );
////    }
////
////
////    var_dump($images);
////
////    $pi_options = get_option('pi_general_settings');
////    $dir = get_theme_root() . '/PiPhotography/assets/demo/img/';
////    $images = glob($dir . "logo.png");
////    $img_index = $images[0];
////    $img_url = PIMAIN . '/' . substr($img_index, strpos($img_index, "assets"));
////    $pi_options['pi_logo'] = $img_url;
////    update_option('pi_general_settings', $pi_options);
//    global $wp_query;
//    var_dump($wp_query);
//
//}

add_action('admin_init', 'add_category');
function add_category(){
    $args = array(
        'posts_per_page'   => -1,
        'post_status'      => 'publish',
        'post_type'        => 'pi_portfolio',
        'suppress_filters' => true
    );
    $posts_array = get_posts( $args );
    foreach ($posts_array as $post){
        $is = wp_set_post_terms( $post->ID, array('outdoors'), 'portfolio_cat');
        var_dump($is);
    }
//    $args = array(
//        'public'   => true,
//        '_builtin' => false,
//        'name'     => 'portfolio_cat'
//    );
//    $output = 'names'; // or objects
//    $operator = 'and'; // 'and' or 'or'
//    $taxonomies = get_taxonomies( $args, $output, $operator );
//    if ( $taxonomies ) {
//        foreach ( $taxonomies  as $taxonomy ) {
//            echo '<p>' . $taxonomy . '</p>';
//        }
//    }
}