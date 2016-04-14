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

		wp_enqueue_media();
        wp_enqueue_script( 'pi-upload', SCRIPTS . '/pi-upload.js', array( 'jquery'), $this->version, 'all' );
        wp_enqueue_script( 'pi-script', SCRIPTS . '/admin/pi-script.js', array( 'jquery'), $this->version, 'all' );
        // Make sure to add the wp-color-picker dependecy to js file
        wp_enqueue_script( 'pi_custom_js', SCRIPTS .'/pi-picker.js', array( 'jquery', 'wp-color-picker' ), '', true  );

        wp_enqueue_script( 'pi-image', SCRIPTS .'/image.js', array( 'jquery-ui-sortable' ), '1.0.0', true );

		$data = array(
			'nonce' => wp_create_nonce(  'pi_import_ajax' ),
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'failMessage' => __( 'Request Failed With Code ', 'pi_import_ajax')
		);

		wp_localize_script( 'pi-script', 'pi_import_ajax', $data );
        
        if( $currentScreen->id === "pi_slider" ) {
            wp_enqueue_script( 'pi-plupload', SCRIPTS . '/pi-plupload.js', array( 'jquery','wp-ajax-response', 'plupload-all' ), '1.0.0', true );
            /** localize script to handle ajax using wordpress and not an outside source. piajax is your ajax varible **/
            wp_localize_script( 'pi-plupload', 'piajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'imgs' => IMAGES ));
            wp_localize_script( 'pi-plupload', 'piFile', array( 'maxFileUploadsSingle' => __( 'You may only upload maximum %d file', $this->theme_name ), 'maxFileUploadsPlural' => __( 'You may only upload maximum %d files', $this->theme_name ),));
        }

	}

	public function pi_ajax_create_files() {
		if ( !isset( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], 'pi_import_ajax') ) {
			status_header( '401' );
			die();
		}
		$current_file = $_POST['currentFile'];

		$files = 20;
		if( $current_file >= $files ){
			$this->pi_create_slider($current_file);
			$this->add_theme_options();
			$this->add_demo_pages();
			wp_send_json_success( 'done' );
		}else{
			//Create the posts...Here we should fetch the stored api and then request the
			$next_file = $this->pi_create_item( $current_file );
			wp_send_json_success( $next_file );
		}

	}
	/**
	 *Creates Portfolio Item
	 */
	public function pi_create_item($value) {
		$title = $this->get_image_name($value);
		$faker = $this->generate_faker();
		$pi_post = array(
			'post_title'    => wp_strip_all_tags( $title ),
			'post_content'  => $faker['content'],
			'post_status'   => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type'     => 'pi_portfolio',
		);
		// Insert the post into the database
		$post_id = wp_insert_post( $pi_post );
		if( $post_id ){
			update_post_meta( $post_id, 'name', 'Sample Name' );
			update_post_meta( $post_id, 'url', 'http://piboutique.com' );
			$image_url = $this->get_image_from_demo($value);
			$this->fetch_media($image_url, $post_id);
		}

		return $value + 1;
	}
	/**
	 *Creates Portfolio Item
	 */
	public function pi_create_slider($value) {
		$title = $this->get_image_name($value);
		$faker = $this->generate_faker();
		$pi_post = array(
			'post_title'    => wp_strip_all_tags( $title ),
			'post_content'  => $faker['content'],
			'post_status'   => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type'     => 'pi_slider',
		);
		// Insert the post into the database
		$post_id = wp_insert_post( $pi_post );
		if( $post_id ){
			update_post_meta( $post_id, '_autoplay_option', false );
			update_post_meta( $post_id, '_dots_option',  false);
			update_post_meta( $post_id, '_arrow_option', false );
			update_post_meta( $post_id, '_slide_speed', 500 );
			update_post_meta( $post_id, '_height', 450);
			update_post_meta( $post_id, '_opacity', true);
			update_post_meta( $post_id, '_color',  '#212121');
			update_post_meta( $post_id, '_caption', false );
			update_post_meta( $post_id, '_pause_hover', true );
			$images = $this->get_images(5);
			foreach ($images as $key => $image){
				add_post_meta($post_id, 'pi_plupload', $key, false);
			}
		}
	}
	public function add_theme_options() {
		$pi_options = array();
		$option_name = 'pi_general_settings' ;
		$img_url = $this->get_image_logo_from_demo();
		//Logo
		$pi_options['pi_logo'] = $img_url;
		$pi_options['pi_font_color'] = '#262626';
		$pi_options['pi_font_family'] = 'Lato';
		$pi_options['pi_main_color_picker'] = '#fdfdfd';
		$pi_options['pi_second_color_picker'] = '#dc724d';
		$pi_options['sidebar_general_position'] = 'right';

		if ( get_option( $option_name ) !== false ) {
			// The option already exists, so we just update it.
			update_option( $option_name, $pi_options );

		} else {
			// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
			$deprecated = null;
			$autoload = 'no';
			add_option( $option_name, $pi_options, $deprecated, $autoload );
		}

	}
	public function add_demo_pages(){
		$pages = array('home', 'about', 'blog', 'portfolio', 'contact');
		$faker = $this->generate_faker();
		foreach ($pages as $page){
			$data = array(
				'post_title'    => ucwords( $page ),
				'post_content'  => $faker['content'],
				'post_status'   => 'publish',
				'post_author'   => get_current_user_id(),
				'post_type'		=> 'page'
			);
			$post_id = wp_insert_post( $data );

			if( $post_id ){
				$this->pi_add_page_meta($post_id, $page);
			}
		}
	}
	public function pi_add_page_meta($post_id, $page){
		if($page === 'portfolio'){
			update_post_meta( $post_id, '_wp_page_template', 'page-portfolio.php');
			update_post_meta( $post_id, 'page_template', 'page-portfolio.php');
		}
		if($page === 'contact'){
			update_post_meta( $post_id, '_wp_page_template', 'page-contact.php');
			update_post_meta( $post_id, 'page_template', 'page-contact.php');
		}
		if($page === 'home'){
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $post_id );
		}
		if ($page === 'blog'){
			$images = $this->get_images(1);
			foreach ($images as $key => $image){
				$new = $key;
			}
			set_post_thumbnail($post_id, $new);
			update_option( 'page_for_posts', $post_id );
		}
	}
	public function generate_faker(){
		$faker = array();
		$lipsum = new Pi_Photography_Lorem_Ipsum();

		$faker['content'] = $lipsum->paragraphs(3, 'p');
		return $faker;
	}
	public function get_images($num = 1){
		$query_images_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => $num,
		);

		$query_images = new WP_Query( $query_images_args );

		$images = array();
		foreach ( $query_images->posts as $image ) {
			$images[$image->ID] = wp_get_attachment_url( $image->ID );
		}
		return $images;
	}
	public function get_image_from_demo($index)
	{
		$dir = get_theme_root() . '/PiPhotography/assets/demo/img/';
		$images = glob($dir . "*.jpg");
		$img_index = isset($images[$index]) ? $images[$index] : $images[0];
		$img_url = PIMAIN . '/' . substr($img_index, strpos($img_index, "assets"));

		return $img_url;
	}
	public function get_image_logo_from_demo()
	{
		$dir = get_theme_root() . '/PiPhotography/assets/demo/img/';
		$images = glob($dir . "logo.png");
		$img_index = $images[0];
		$img_url = PIMAIN . '/' . substr($img_index, strpos($img_index, "assets"));

		return $img_url;
	}
	public function get_image_name($index)
	{
		$dir = get_theme_root() . '/PiPhotography/assets/demo/img/';
		$images = glob($dir . "*.jpg");
		$img_index = isset($images[$index]) ? $images[$index] : $images[0];
		$img_url = PIMAIN . '/' . substr($img_index, strpos($img_index, "assets"));
		$array = explode("/", $img_url);
		$title = end($array);

		return $title;
	}
	/* Import media from url
	 *
	 * @param string $file_url URL of the existing file from the original site
	 * @param int $post_id The post ID of the post to which the imported media is to be attached
	 *
	 * @return boolean True on success, false on failure
	 */
	public function fetch_media($file_url, $post_id){
		require_once(ABSPATH . 'wp-load.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');
		global $wpdb;

		if (!$post_id) {
			return false;
		}

		$img_dir = wp_upload_dir()['path'];
		$array = explode("/", $file_url);
		$new_filename = end($array);
		if (@fclose(@fopen($file_url, "r"))) {
			copy($file_url, $img_dir .'/'. $new_filename);

			$siteurl = get_option('siteurl');
			$file_info = getimagesize($img_dir .'/'. $new_filename);

			$img_data = array(
				'post_author' => 1,
				'post_date' => current_time('mysql'),
				'post_date_gmt' => current_time('mysql'),
				'post_title' => $new_filename,
				'post_status' => 'inherit',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
				'post_name' => sanitize_title_with_dashes(str_replace("_", "-", $new_filename)), 'post_modified' => current_time('mysql'),
				'post_modified_gmt' => current_time('mysql'),
				'post_parent' => $post_id,
				'post_type' => 'attachment',
				'guid' => $siteurl . '/' . $img_dir . '/' . $new_filename,
				'post_mime_type' => $file_info['mime'],
				'post_excerpt' => '',
				'post_content' => ''
			);

			$save_path = $img_dir .'/'. $new_filename;

			//insert the database record
			$attach_id = wp_insert_attachment($img_data, $save_path, $post_id);

			//generate metadata and thumbnails
			if ($attach_data = wp_generate_attachment_metadata($attach_id, $save_path)) {
				wp_update_attachment_metadata($attach_id, $attach_data);
			}

			//optional make it the featured image of the post it's attached to
			$wpdb->insert($wpdb->prefix . 'postmeta', array('post_id' => $post_id, 'meta_key' => '_thumbnail_id', 'meta_value' => $attach_id));
		}
		return true;
	}
}