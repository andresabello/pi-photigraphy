<?php 

/**
 * The admin-specific functionality of the theme.
 *
 * Defines the theme name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pi_Photography
 * @subpackage Pi_Photography/admin
 * @author     Andres Abello <abellowins@gmail.com>
 */
class Pi_Photography_Admin {
	/**
	 * The ID of this theme.
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
	 * @param      string    $theme_name       The name of this theme.
	 * @param      string    $version    The version of this theme.
	 */
	public function __construct( $theme_name, $version ) {

		$this->theme_name = $theme_name;
		$this->version = $version;

	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
        $currentScreen = get_current_screen();
        // Include Styles for admin options
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_style( 'wp-color-picker' );
        wp_register_style( 'font-awesome', STYLES . '/font-awesome.min.css', false, '4.4.0' );
        wp_register_style( 'pi-admin-css', STYLES . '/admin/css/admin-styles.css', array( 'font-awesome') );
        wp_enqueue_style( 'pi-admin-css' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$currentScreen = get_current_screen();
        //Scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-sortable');

        wp_enqueue_script( 'thickbox' );
        wp_enqueue_script( 'media-upload' );
        // Custom script to start thickbox
        wp_enqueue_script( 'pi-upload', SCRIPTS . '/pi-upload.js', array( 'thickbox', 'media-upload' ) );
        // Make sure to add the wp-color-picker dependecy to js file
        wp_enqueue_script( 'pi_custom_js', SCRIPTS .'/pi-picker.js', array( 'jquery', 'wp-color-picker' ), '', true  );

        wp_enqueue_script( 'pi-image', SCRIPTS .'/image.js', array( 'jquery-ui-sortable' ), '1.0.0', true );            

        
        if( $currentScreen->id === "pi_slider" ) {
            wp_enqueue_script( 'pi-plupload', SCRIPTS . '/pi-plupload.js', array( 'jquery','wp-ajax-response', 'plupload-all' ), '1.0.0', true );
            /** localize script to handle ajax using wordpress and not an outside source. piajax is your ajax varible **/
            wp_localize_script( 'pi-plupload', 'piajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'imgs' => IMAGES ));
            wp_localize_script( 'pi-plupload', 'piFile', array( 'maxFileUploadsSingle' => __( 'You may only upload maximum %d file', $this->theme_name ), 'maxFileUploadsPlural' => __( 'You may only upload maximum %d files', $this->theme_name ),));
        }

	}
}