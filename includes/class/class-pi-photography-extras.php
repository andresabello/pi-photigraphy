<?php
/**
 * All extra functions to support theme functionality
 *
 * @package    Pi_Framework
 * @subpackage Pi_Framework_Extras/includes
 * @author     Andres Abello <abellowins@gmail.com>
 */ 
class Pi_Photography_Extras {
	/**
	 * The ID of this Theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $theme_name    The ID of this theme.
	 */
	private $theme_name;

	/**
	 * The version of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this theme.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $theme_name	The name of the theme.
	 * @param      string    $version    The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version = $version;

	}
	/**
	 * Create shortcode to display PI Slider 
	 *
	 * @param array $atts has shortcode attributes
	 */
	public function pi_slider_shortcode( $atts ) {
	    /*Handle shortode attributes*/
	    $a = shortcode_atts( array(
	        'id' => 1,
	    ), $atts );

	    /*Get all pi_slider posts*/
	     $args = array(
	        'posts_per_page'   => 1,
	        'offset'           => 0,
	        'orderby'          => 'post_date',
	        'order'            => 'DESC',
	        'post_type'        => 'pi_slider',
	        'post_status'      => 'publish',
	        'post__in'         => array($a['id'])
	    );  
	    /*Pi Slider Query*/
	    $pi_slider_query = new WP_Query( $args );
	    $post = get_posts( $args );
	    $autoplay = get_post_meta($post[0]->ID, '_autoplay_option', true);
	    $dots = get_post_meta($post[0]->ID, '_dots_option', true);
	    $arrows = get_post_meta($post[0]->ID, '_arrow_option', true);
	    // $infinite = get_post_meta($post[0]->ID, '_infinite', true);
	    $slide_speed = get_post_meta($post[0]->ID, '_slide_speed', true);
	    $height = get_post_meta( $post[0]->ID, '_height', true );
	    $opacity = get_post_meta( $post[0]->ID, '_opacity', true );
	    $color = get_post_meta( $post[0]->ID, '_color', true );
	    $caption = get_post_meta( $post[0]->ID, '_caption', true );
	    $pause_hover = get_post_meta($post[0]->ID, '_pause_hover', true);
	    if( $opacity == true){
	        $html = '<style type="text/css">
	            .slick-slide:after{ 
	                background: ' . $color . ';
	                opacity: .65;
	            }
	        </style>';
	    }else{
	        $html .= '<style type="text/css">
	            .slick-slide:after{ 
	                background: transparent;
	            }
	        </style>';        
	    }

	    $html .= '<div class="pi-slider">';
	    $html .= '<input class="slider-settings" type="hidden" data-id="'.$post[0]->ID.'" data-autoplay="'.( $autoplay == on ? "true" : "false" ).'" data-pausehover="'.( $pause_hover == on ? "true" : "false" ) .'" data-slidespeed="'.$slide_speed.'" data-arrows="'.( $arrows == on ? "true" : "false" ).'" data-dots="'.( $dots == on ? "true" : "false" ).'" data-height="'. $height .'"  data-opacity="'.( $opacity == on ? "true" : "false" ).'"  data-color="'. $color .'"  data-caption="'. $caption .'">';

	    if ( $pi_slider_query->have_posts() ) {
	        $html .= '<div class="pi-slider-id" id="pi-slider-' . $post[0]->ID .'" data-id="' . $post[0]->ID . '">';
	        while ( $pi_slider_query->have_posts() ) {
	            $pi_slider_query->the_post();
	            $post_id = get_the_ID();
	            $post_meta = get_post_meta($post_id, 'pi_plupload');
	            $count = 1;
	            foreach ($post_meta as $image_id) {
	                $img = wp_get_attachment_image_src($image_id, 'full');
	                $img_post = get_post($image_id);
	                $img_caption = isset($img_post->post_excerpt) ? $img_post->post_excerpt : false;
	                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
	                $alt = ( !empty($alt) ? $alt : get_bloginfo() ) ;
	                $title = get_the_title( $image_id );
	                $html .= '<div class="pi-single"><div class="pi-overlay">'; 
	                $html .= '<img src="'. $img[0] .'" alt="image'. $count .'" rel="' . $img_caption . '" height="'. $height .'">';
	                $html .= ( isset( $img_caption ) && !empty( $img_caption ) && $caption === on ? ' <figcaption class="pi-hover"><p style="padding: 40px;">'. $img_caption .'</p></figcaption>' : ''); 
	                $html .= '</div></div>';
	                $count ++;
	            }
	        }
	        $html .= '</div></div>';
	    } else {
	    	$html .= '<p> Sorry, no posts were found. <a href="'.  home_url() .'">You can go back home here. </a></p>';
	    }
	    /* Restore original Post Data */
	    wp_reset_postdata();
	    return $html;
	}
}