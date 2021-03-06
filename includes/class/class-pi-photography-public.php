<?php 
/**
 * Register all actions and filters for the theme.
 *
 * Maintain a list of all hooks that are registered throughout
 * the theme, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Pi_Photography
 * @subpackage Pi_Photography_Public/includes
 * @author     Andres Abello <abellowins@gmail.com>
 */
class Pi_Photography_Public{
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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(){
		wp_enqueue_style( 'font-awesome', STYLES . '/font-awesome.min.css', false, '4.4.0' );
		wp_enqueue_style( 'pi-normalize', STYLES . '/normalize.min.css', false, '3.0.2' );
		wp_enqueue_style( $this->theme_name, STYLES . '/custom.css', array( 'font-awesome', 'pi-normalize' ) );
		wp_enqueue_style( 'slick', STYLES . '/slick.css', false, '1.4.1' );
		wp_enqueue_style( 'pi-theme', STYLES . '/pi-theme.css', false, $this->version );
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-effects-core');
		wp_enqueue_script( 'slick-slider', SCRIPTS .'/slick.min.js', false, '1.4.1', true );
		wp_enqueue_script( $this->theme_name, SCRIPTS . '/pi-script.js', array('jquery', 'slick-slider', 'jquery-effects-core'), $this->version, true );
		wp_enqueue_script( 'pi-image', SCRIPTS .'/image.js', array( 'jquery-ui-sortable' ), $this->version, true );

		global $post;
		if($post->post_name === 'portfolio'){
			wp_enqueue_script( 'modernizr', SCRIPTS . '/modernizr.min.js', array('jquery'), '2.6.2
			' );
			wp_enqueue_script( 'shuffle', SCRIPTS . '/shufflejs.min.js', array('modernizr'), '3.1.1' );
			wp_enqueue_script( 'throttle', SCRIPTS . '/throttle.min.js', array('jquery'), '3.1.1' );
			wp_enqueue_script( 'pi_shuffle', SCRIPTS . '/pi-shuffle.js', array('shuffle', 'throttle'), $this->version );
		}
	}
}