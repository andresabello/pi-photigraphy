<?php
/**
 * The core theme class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this theme as well as the current
 * version of the theme.
 *
 * @since      1.0.0
 * @package    Pi_Photography
 * @subpackage Pi_Photography/includes
 * @author     Andres Abello <abellowins@gmail.com>
 */ 
class Pi_Photography {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pi_Photography_loader    $loader    
	 * Maintains and registers all hooks for the theme.
	 */
	protected $loader;

	/**
	 * The unique identifier of this theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $theme_name    The string used to uniquely identify this theme.
	 */
	protected $theme_name;

	/**
	 * The unique identifier of this theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $theme_default    The string used to uniquely identify this theme.
	 */
	protected $theme_default;

	/**
	 * The current version of the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the theme.
	 */
	protected $version;
	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the theme name and the theme version that can be used throughout the theme.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->theme_name = 'pi-photography';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		
		$this->theme_defaults();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	/**
	 * Load the required dependencies for this theme.
	 *
	 * Include the following files that make up the theme:
	 *
	 * - Pi_Photography_Loader. Orchestrates the hooks of the theme.
	 * - Pi_Photography_i18n. Defines internationalization functionality.
	 * - Pi_Photography_Admin. Defines all hooks for the admin area.
	 * - Pi_Photography_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		//Orchestrae the actions and filters of the core theme.
		require_once FRAMEWORK . '/class/class-pi-photography-loader.php';
		//Internationalization functionality of the theme.
		require_once FRAMEWORK . '/class/class-pi-photography-i18n.php';
		//Admin Side and Front End Functionality
		require_once FRAMEWORK . '/class/class-pi-photography-default.php';
		//Admin Side Functionality
		require_once FRAMEWORK . '/class/admin/class-pi-photography-admin.php';
		//Public Side Functionality
		require_once FRAMEWORK . '/class/class-pi-photography-public.php';	
		//Custom Post Type
		require_once FRAMEWORK . '/class/class-pi-custom-post-type.php';
		//Meta Boxes 
		require_once FRAMEWORK . '/class/class-pi-custom-meta-box.php';
		//Security, Validation, Sanitization of data
		require_once FRAMEWORK . '/class/class-pi-photography-security.php';
		//Load Forms
		require_once FRAMEWORK . '/class/class-pi-forms.php';
		//Load theme extra functions
		require_once FRAMEWORK . '/class/class-pi-photography-extras.php';
		//Load theme options
		require_once FRAMEWORK . '/class/class-pi-photography-options.php';
		//Load theme helper functions
		require_once FRAMEWORK . '/pi-photography-helper.php';		
		//Start Loader
		$this->loader = new Pi_Photography_Loader();

	}
	/**
	 * Define the locale for this Theme for internationalization.
	 *
	 * Uses the Pi_Photography_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$theme_i18n = new Pi_Photography_i18n();
		$theme_i18n->set_domain( $this->get_theme_name() );
		$this->loader->add_action( 'theme_setup', $theme_i18n, 'load_pi_photography' );

	}
	/**
	 * Load the required dependencies for this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function theme_defaults() {
		$sliders = array(
		    'post_type_name' => 'pi_slider',
		    'singular' => 'Slider',
		    'plural' => 'Sliders',
		    'slug' => 'sliders'
		);
		$options = array(
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'supports' => array( 'title' )
		);
		$portfolio = array(
		    'post_type_name' => 'pi_portfolio',
		    'singular' => 'Portfolio Item',
		    'plural' => 'Portfolio Items',
		    'slug' => 'item'
		);
		$por_options = array(
			'has_archive' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' )
		);

		$defaults = new Pi_Directory_Default( $this->get_theme_name(), $this->get_version() );
		//Start Custom post types
		$cpt_sliders = new Pi_Custom_Post_Type( $sliders, $options, $this->get_theme_name());
		$cpt_sliders->run();
		
		$cpt_portfolios = new Pi_Custom_Post_Type( $portfolio, $por_options, $this->get_theme_name());
		$cpt_portfolios->run();
		$cpt_portfolios->register_taxonomy(array(
		    'taxonomy_name' => 'category',
		    'singular' => 'Category',
		    'plural' => 'Categories',
		    'slug' => 'category'
		));

		$extras = new Pi_Photography_Extras( $this->get_theme_name(), $this->get_version() );
		
		$this->loader->add_action( 'after_setup_theme', $defaults, 'pi_setup' );
		$this->loader->add_action( 'widgets_init', $defaults, 'pi_register_sidebars' );

		//Add Slider Shortcode
		add_shortcode( 'pi_slider', array( $extras, 'pi_slider_shortcode') );
	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$theme_admin = new Pi_Photography_Admin( $this->get_theme_name(), $this->get_version() );
		$slider_meta = new Pi_Custom_Meta_Box( $this->get_theme_name(), $this->get_version(), 'pi_slider' );
		$theme_options = new Pi_Photography_Theme_Options( $this->get_theme_name(), $this->get_version() );
		// Register admin styles
		$this->loader->add_action( 'admin_enqueue_scripts', $theme_admin, 'enqueue_styles' );
		// Register admin scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $theme_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'add_meta_boxes', $slider_meta, 'add_meta_box' ) ;
		$this->loader->add_action( 'save_post', $slider_meta, 'save' );
		$this->loader->add_action( 'wp_ajax_pi_plupload_image_upload', $slider_meta, 'pi_handle_upload');
		$this->loader->add_action( 'wp_ajax_pi_delete_file', $slider_meta, 'pi_ajax_delete_file');
		$this->loader->add_action( 'wp_ajax_pi_reorder_images', $slider_meta, 'pi_ajax_reorder_images');
		//theme options
    	$this->loader->add_action( 'init', $theme_options, 'load_settings' );
        // Register general options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_general_settings' ); 
        // Register homepage options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_homepage_settings' );
        // Register page options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_page_settings' );
        // Register form options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_form_settings' ); 
        // Register footer options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_footer_settings' ); 
        // Register import options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_portfolio_settings' ); 
        // Register css options
        $this->loader->add_action( 'admin_init', $theme_options, 'register_css_settings' );         
        // Add the page to the admin menu
        $this->loader->add_action( 'admin_menu', $theme_options, 'add_plugin_page' );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$theme_public = new Pi_Photography_Public( $this->get_theme_name(), $this->get_version() );
		//Add styles
		$this->loader->add_action( 'wp_enqueue_scripts', $theme_public, 'enqueue_styles' );
		//Add scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $theme_public, 'enqueue_scripts' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the theme.
	 */
	public function get_theme_name() {
		return $this->theme_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the theme.
	 *
	 * @since     1.0.0
	 * @return    Pi_Photography_Loader    Orchestrates the hooks of the theme.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the theme.
	 */
	public function get_version() {
		return $this->version;
	}
}