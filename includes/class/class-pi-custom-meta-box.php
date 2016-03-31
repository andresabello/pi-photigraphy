<?php
/**
 * Add Meta Box to Post Type
 *
 * Create meta boxes for custom post types
 *
 * @package    Pi_Photography
 * @subpackage Pi_Photography/includes
 * @author     Andres Abello <abellowins@gmail.com>
 */
class Pi_Custom_Meta_Box Extends Pi_Custom_Post_Type  {
	/**
	 * The ID of this Theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $theme_name    The ID of this theme.
	 */
	public $theme_name;

	/**
	 * The version of this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this theme.
	 */
	public $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $theme_name	The name of the theme.
	 * @param      string    $version    The version of this theme.
	 */
	public function __construct( $theme_name, $version, $post_type ) {

		$this->theme_name = $theme_name;
		$this->version = $version;
		$this->post_type = $post_type;

	}
	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
			$post_type = $this->post_type;
			
			add_meta_box(
				'pi_images',
				__( 'Slider Upload', 
			 $this->theme_name ),
				array( $this, 'render_pi_plupload' ),
				$post_type,
				'normal',
				'high'
			);
	        add_meta_box(
	            'pislider_shortcode',
	            __( 'Shortcode', $this->theme_name ),
	            array( $this, 'pi_slider_shortcode_callback' ),
	            $post_type,
	            'side',
	            'low'
	        );
	        add_meta_box(
				'pislider_settings',
				__( 'Settings', $this->theme_name ),
				array( $this, 'render_settings_content' ),
				$post_type,
				'advanced',
				'low'
			);
	}
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['pi_meta_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['pi_meta_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pi_meta_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		/* OK, its safe for us to save the data now. */
		// Sanitize the user input.
		$autoplay = sanitize_text_field( $_POST['autoplay_option'] );
		$dots_option = sanitize_text_field($_POST['dots_option']);
		$arrow = sanitize_text_field( $_POST['arrow_option'] );
		// $infinite = sanitize_text_field( $_POST['infinite'] );
		$slide_speed = (int) sanitize_text_field( $_POST['slide_speed'] );
		$height = (int) sanitize_text_field( $_POST['height'] );
		$opacity = sanitize_text_field( $_POST['opacity'] );
		$color = sanitize_text_field( $_POST['color'] );
		$caption = sanitize_text_field( $_POST['caption'] );
		$pause_hover = sanitize_text_field( $_POST['pause_hover'] );


		//Validation
		$slide_speed = ( is_int($slide_speed) ? $slide_speed : "300");
		$height = ( is_int($height) ? $height : "450");

		// Update the meta field.
		update_post_meta( $post_id, '_autoplay_option', $autoplay );
		update_post_meta( $post_id, '_dots_option', $dots_option );
		update_post_meta( $post_id, '_arrow_option', $arrow );
		// update_post_meta( $post_id, '_infinite', $infinite );
		update_post_meta( $post_id, '_pause_hover', $pause_hover );
		update_post_meta( $post_id, '_slide_speed', $slide_speed );
		update_post_meta( $post_id, '_height', $height );
		update_post_meta( $post_id, '_opacity', $opacity );
		update_post_meta( $post_id, '_caption', $caption );
		update_post_meta( $post_id, '_pause_hover', $pause_hover );
		update_post_meta( $post_id, '_color', $color );

	}
	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function render_pi_plupload( $post, $callback_args ) {
	    // Add an nonce field so we can check for it later.
	    wp_nonce_field( 'pi_meta_box', 'pi_meta_box_nonce' );
	    /*
	     * Use get_post_meta() to retrieve an existing value
	     * from the database and use the value for the form.
	     */
	    $field = '';
	    $field = $this->normalize_field( $callback_args['args'] );
	    $meta = get_post_meta($post->ID, $field['field_id']);
	    echo $this->html($meta, $field);
	}
	/**
	 * The following normalizes fields for displaying the right images
	 * @since 3.9.1
	 *
	 * @param  $meta = the meta field where the data is saved
	 *         $field = the name of the field
	 * @return $html
	 */
	public function normalize_field( $field ){
	    $field['field_id'] = 'pi_plupload';
	    $field['id'] = 'pi_plupload';
	    $field = wp_parse_args( $field, array(
	        'id'               => $field['field_id'],
	        'std'              => array(),
	        'force_delete'     => true,
	        'max_file_uploads' => 25,
	        'mime_type'        => '',
	        'clone'            => false,
	    ) );
	    $field['multiple'] = true;
	    $field['js_options'] = array(
	        'runtimes'              => 'html5,silverlight,flash,html4',
	        'file_data_name'        => 'async-upload',
	        //'container'               => $field['id'] . '-container',
	        'browse_button'         => $field['field_id'] . '-browse-button',
	        'drop_element'          => $field['field_id'] . '-dragdrop',
	        'multiple_queues'       => true,
	        'max_file_size'         => wp_max_upload_size() . 'b',
	        'url'                   => admin_url( 'admin-ajax.php' ),
	        'flash_swf_url'         => includes_url( 'js/plupload/plupload.flash.swf' ),
	        'silverlight_xap_url'   => includes_url( 'js/plupload/plupload.silverlight.xap' ),
	        'multipart'             => true,
	        'urlstream_upload'      => true,
	        'filters'               => array(
	            array(
	                'title'      => _x( 'Allowed Image Files', 'image upload', 'pi' ),
	                'extensions' => 'jpg,jpeg,gif,png',
	            ),
	        ),
	        'multipart_params'      => array(
	            'field_id'  => $field['field_id'],
	            'action'    => 'pi_plupload_image_upload',
	        )
	    );
	    return $field;
	}
	/**
	 * The following holds the html where the images will be.
	 * @since 3.9.1
	 *
	 * @param  $meta = the meta field where the data is saved
	 *         $field = the name of the field
	 * @return $html
	 */
	public function html( $meta, $field ){
	    if ( ! is_array( $meta ) )
	        $meta = ( array ) $meta;
	    // Filter to change the drag & drop box background string
	    $i18n_drop   = apply_filters( 'pi_plupload_image_drop_string', _x( 'Drop images here', 'image upload', 'pi' ), $field );
	    $i18n_or     = apply_filters( 'pi_plupload_image_or_string', _x( 'or', 'image upload', 'pi' ), $field );
	    $i18n_select = apply_filters( 'pi_plupload_image_select_string', _x( 'Select Files', 'image upload', 'pi' ), $field );
	    /**
	     * Uploaded images 
	     */ 
	    /** Check for max_file_uploads **/
	    $classes = array( 'pi-drag-drop', 'drag-drop', 'hide-if-no-js', 'new-files');
	    if ( ! empty( $field['max_file_uploads'] ) && count( $meta ) >= (int) $field['max_file_uploads']  )
	        $classes[] = 'hidden';
	    $html = $this->get_uploaded_images( $meta, $field );
	    // Show form upload
	    $html .= sprintf(
	        '<div id="%s-dragdrop" class="%s" data-upload_nonce="%s" data-js_options="%s">
	            <div class = "drag-drop-inside">
	                <p class="drag-drop-info">%s</p>
	                <p>%s</p>
	                <p class="drag-drop-buttons"><input id="%s-browse-button" type="button" value="%s" class="button" /></p>
	            </div>
	        </div>',
	        $field['field_id'],
	        implode( ' ', $classes ),
	        wp_create_nonce( "pi-upload-images_{$field['field_id']}" ),
	        esc_attr( json_encode( $field['js_options'] ) ),
	        $i18n_drop,
	        $i18n_or,
	        $field['field_id'],
	        $i18n_select
	    );
	    return $html;
	}
	/**
	 * The following displays images as thumbnails in order
	 * @since 3.9.1
	 *
	 * @param  $meta = the meta field where the data is saved
	 *         $field = the name of the field
	 * @return $html
	 */
	public function img_html( $image ){
	    $i18n_delete = apply_filters( 'pi_image_delete_string', _x( 'Delete', 'image upload', 'pi' ) );
	    $i18n_edit   = apply_filters( 'pi_image_edit_string', _x( 'Edit', 'image upload', 'pi' ) );
	    $li = '
	        <li id="item_%s">
	            <img src="%s" />
	            <div class="pi-image-bar">
	                <a title="%s" class="pi-edit-file" href="%s" target="_blank">%s</a> |
	                <a title="%s" class="pi-delete-file" href="#" data-attachment_id="%s">&times;</a>
	            </div>
	        </li>
	    ';
	    $src  = wp_get_attachment_image_src( $image, 'thumbnail' );
	    $src  = $src[0];
	    $link = get_edit_post_link( $image );
	    //var_dump($image->ID);
	    return sprintf($li,$image,$src,$i18n_edit, $link, $i18n_edit,$i18n_delete, $image);
	}
	/**
	 * The following gets uploaded images
	 * @since 3.9.1
	 *
	 * @param  $meta = the meta field where the data is saved
	 *         $field = the name of the field
	 * @return $html
	 */
	public function get_uploaded_images( $images, $field ){
	    $reorder_nonce = wp_create_nonce( "pi-reorder-images_{$field['field_id']}" );
	    $delete_nonce = wp_create_nonce( "pi-delete-file_{$field['field_id']}" );
	    $classes = array( 'pi-images', 'pi-uploaded' );
	    if($field['force_delete'] == false){
	        $field['force_delete'] = 0;
	    }else{
	        $field['force_delete'] = 1;
	    }
	    if ( count( $images ) <= 0  )
	        $classes[] = 'hidden';
	    $ul = '<ul class="%s" data-field_id="%s" data-delete_nonce="%s" data-reorder_nonce="%s" data-force_delete="%s" data-max_file_uploads="%s">';
	    $html = sprintf(
	        $ul,
	        implode( ' ', $classes ),
	        $field['field_id'],
	        $delete_nonce,
	        $reorder_nonce,
	        $field['force_delete'],
	        $field['max_file_uploads']
	    );
	    foreach ( $images as $image )
	    {
	        $html .= $this->img_html( $image );
	    }
	    $html .= '</ul>';
	    return $html;
	}
	/**
	 * The following handles the ajax plupload functions
	 * @since 3.9.1
	 *
	 * @return void
	 */
	public function pi_handle_upload(){
	    global $wpdb;
	    $post_id = is_numeric( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
	    $field_id = isset( $_REQUEST['field_id'] ) ? $_REQUEST['field_id'] : '';
	    check_ajax_referer( "pi-upload-images_{$field_id}" );
	    // You can use WP's wp_handle_upload() function:
	    $file       = $_FILES['async-upload'];
	    $file_attr  = wp_handle_upload( $file, array( 'test_form' => false ) );
	    //Get next menu_order
	    $meta = get_post_meta( $post_id, $field_id, false );
	    if( empty( $meta ) ){
	        $next = 0;
	    } else {
	        $meta = implode( ',' , (array) $meta );
	        $max = $wpdb->get_var( "
	            SELECT MAX(menu_order) FROM {$wpdb->posts}
	            WHERE post_type = 'attachment'
	            AND ID in ({$meta})
	        " );
	        $next = is_numeric($max) ? (int) $max + 1: 0;
	    }
	    $attachment = array(
	        'guid'              => $file_attr['url'],
	        'post_mime_type'    => $file_attr['type'],
	        'post_title'        => preg_replace( '/\.[^.]+$/', '', basename( $file['name'] ) ),
	        'post_content'      => '',
	        'post_status'       => 'inherit',
	        'menu_order'        => $next
	    );
	    // Adds file as attachment to WordPress
	    $id = wp_insert_attachment( $attachment, $file_attr['file'], $post_id );
	    if ( ! is_wp_error( $id ) )
	    {
	        wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file_attr['file'] ) );
	        // Save file ID in meta field
	        add_post_meta( $post_id, $field_id, $id, false );
	        wp_send_json_success( $this->img_html($id) );
	    }
	    exit;
	}
	/**
	 * The following handles the ajax delete functions
	 * @since 3.9.1
	 *
	 * @return void
	 */
	public function pi_ajax_delete_file(){
	    $post_id       = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	    $field_id      = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
	    $attachment_id = isset( $_POST['attachment_id'] ) ? intval( $_POST['attachment_id'] ) : 0;
	    $force_delete  = isset( $_POST['force_delete'] ) ? intval( $_POST['force_delete'] ) : 0;
	    check_ajax_referer( "pi-delete-file_{$field_id}" );
	    delete_post_meta( $post_id, $field_id, $attachment_id );
	    $ok = $force_delete ? wp_delete_attachment( $attachment_id ) : true;
	    if ( $ok )
	        wp_send_json_success();
	    else
	        wp_send_json_error( __( 'Error: Cannot delete file', 'pi' ) );
	}
	/**
	 * The following handles the ajax reorder functions
	 * @since 3.9.1
	 *
	 * @return void
	 */
	public function pi_ajax_reorder_images(){
	    $field_id = isset( $_POST['field_id'] ) ? $_POST['field_id'] : 0;
	    $order    = isset( $_POST['order'] ) ? $_POST['order'] : 0;
	    $post_id  = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0;
	    check_ajax_referer( "pi-reorder-images_{$field_id}" );
	    parse_str( $order, $items );
	    delete_post_meta( $post_id, $field_id );
	    foreach ( $items['item'] as $item ){
	        add_post_meta( $post_id, $field_id, $item, false );
	    }
	    wp_send_json_success();
	}
	/**
	 * Display shortcode
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	public function pi_slider_shortcode_callback($post, $callback_args){
	    echo '<p>Use the following shortcode wherever you want the slider to display:</p>'; 
	    echo '<p>[pi_slider id="' . $post->ID . '"]</p>';
	}
	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_settings_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pi_slider_meta_box', 'pi_slider_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$autoplay = get_post_meta( $post->ID, '_autoplay_option', true );
		$dots_option = get_post_meta( $post->ID, '_dots_option', true );
		$arrow = get_post_meta( $post->ID, '_arrow_option', true );
		// $infinite = get_post_meta( $post->ID, '_infinite', true );
		$slide_speed = get_post_meta( $post->ID, '_slide_speed', true );
		$height = get_post_meta( $post->ID, '_height', true );
		$opacity = get_post_meta( $post->ID, '_opacity', true );
		$color = get_post_meta( $post->ID, '_color', true );
		$caption = get_post_meta( $post->ID, '_caption', true );
		$pause_hover = get_post_meta( $post->ID, '_pause_hover', true );

		echo '<h3>Main Options</h3>';
		echo '<div class="main-options">';
			//1.Height
			echo $this->render_input_text($height, 'height', 'Slider Height', '450');
			//2.Opacity
			echo $this->render_input_checkbox($opacity, 'opacity', 'Slide Opacity');
			//3.Color Picker
			echo $this->render_color_picker($color, 'color', 'Caption and Background Color', '#000000');
			//4.Caption
			echo $this->render_input_checkbox($caption, 'caption', 'Caption on slide');
			//5.Arrow Option
			echo $this->render_input_checkbox($arrow, 'arrow_option', 'Enable Next/Prev arrows');
			//6.Auto Play slides
			echo $this->render_input_checkbox($autoplay, 'autoplay_option', 'Enable auto play of slides');
			//7.Dots
			echo $this->render_input_checkbox($dots_option, 'dots_option', 'Current slide indicator dots');
			//8.Slide Speed
			echo $this->render_input_text($slide_speed, 'slide_speed', 'Transition speed', '300');
			//9.Infinite Loop
			// echo $this->render_input_checkbox($infinite, 'infinite', 'Enable Infinite looping');
			//10.Pause on hover
			echo $this->render_input_checkbox($pause_hover, 'pause_hover', 'Enable pause on hover');
		echo '</div>';
	}
	/**
	 * Displays checkbox for slider options
	 * 
	 * @param $value Holds the value stored in the database
	 * @param $label Holds label for the option
	 * @param $text Holds text to display in the label
	 */
	public function render_input_checkbox($value, $label, $text){
		$content = '<fieldset><label for="'.$label.'">';
		$content .= __( $text, 'pislider' );
		$content .= '</label> ';
		$content .= '<input type="checkbox" id="'.$label.'" name="'.$label.'"';
	    $content .= ' size="25" ' . ( $value == true ? 'checked' : "" ) . '></fieldset>';
	    return $content;		
	}
	/**
	 * Displays text for slider options
	 * 
	 * @param $value Holds the value stored in the database
	 * @param $label Holds label for the option
	 * @param $text Holds text to display in the label
	 */
	public function render_input_text($value, $label, $text, $default){
		$content = '<fieldset><label for="'. $label .'">';
		$content .= __( $text, 'pislider' );
		$content .= '</label> ';
		$content .= '<input type="text" id="'. $label .'" name="'. $label .'"';
        $content .= ' value="' . ( !empty($value) ? esc_attr( $value ) : $default ) . '" size="25"></fieldset>';
        return $content;	
	}
	/**
	 * Displays select box for slider options
	 * 
	 * @param $options Holds array of values stored in the database
	 * @param $value Holds the value stored in the database
	 * @param $label Holds label for the option
	 * @param $text Holds text to display in the label
	 */
	public function render_select($options, $label, $text, $default){
		$content = '<fieldset><label for="'. $label .'">';
		$content .= __( $text, 'pislider' );
		$content .= '</label> ';
		$content .= '<select id="'. $label .'" name="'. $label .'">';
		foreach ($options as $key => $option) {
			$content .= '<option value="' . $option . '"';
			if ( $option === $default ) {
			    $content .= '" selected="selected"';
			}
			$content .= '>' . $option . '</option>';
		}
		$content .= '</select></fieldset>';
		return $content;
	}
	/**
	 * Displays textarea for slider options
	 * 
	 * @param $value Holds the value stored in the database
	 * @param $label Holds label for the option
	 * @param $text Holds text to display in the label
	 */
	public function render_input_textarea($value, $label, $text, $default){
		$content = '<fieldset><label for="'. $label .'">';
		$content .= __( $text, 'pislider' );
		$content .= '</label> ';
		$content .= '<textarea type="text" id="'. $label .'" name="'. $label .'"';
        $content .= ' cols="50">' . ( !empty($value) ? esc_attr( $value ) : $default ) . '</textarea></fieldset>';
        return $content;	
	}
    /** 
     *  General Color Picker
     */    
    public function render_color_picker( $value, $label, $text, $default ) 
    {  
		$content = '<fieldset><label for="'. $label .'">';
		$content .= __( $text, 'pislider' );
		$content .= '</label> ';
		$content .= '<input type="text" id="'. $label .'" name="'. $label .'"';
        $content .= ' value="' . ( !empty($value) ? esc_attr( $value ) : $default ) . '" size="25" class="pi-color-picker"></fieldset>';
        return $content;
    }
	/**
	 * The code that runs during theme activation.
	 */
	public function activate_pi_photography() {
	    //Register Post Type
	    $this->register_post_type();
		
	    //Flush all rewrite rules
	    parent::flush();
	}
}