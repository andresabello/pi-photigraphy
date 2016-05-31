<?php
/**
 * Register all forms
 *
 * Maintain a list of all hooks that are registered throughout
 * the theme, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Pi_Photography
 * @subpackage Pi_Theme_Forms/includes
 * @author     Andres Abello <abellowins@gmail.com>
 */
class Pi_Theme_Forms {

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

	public function register_pi_forms_menu_page(){
		add_submenu_page(
			'pi_page_options',
	    	'Pi Form Reports',
	    	'Forms',
	    	'manage_options',
	    	'pi_forms_menu',
	    	array($this, 'pi_forms_menu_page')
	    );
	}
	public function pi_forms_menu_page(){
		/*Get posts with post type ac_forms*/
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => 'pi_form',
			'post_status'      => 'publish',
			'suppress_filters' => true 
		);
		$posts = get_posts($args);
		/*Start count at 1*/
		$count = 1;
		echo '<div class="pi-admin-show wrap">';
			echo '<h1 class="widefat">Form Reports: </h1><hr style="margin-bottom: 40px;">';
			echo '<p>View Form Summary Reports</p>';
			/*Present the data*/
			foreach ($posts as $post) {
				$post_meta = get_post_meta( $post->ID );
				echo '<h3>Submission ' . $count . ':</h3>'; 
					echo '<ul class="pi-data-show">';
						foreach ($post_meta as $field => $value) {
							echo '<li>'. $field .': '. $value[0] .'</li>';
						}
					echo '</ul>';
				echo '<hr>';
				/*Increment the count only within this loop*/
				$count ++;
			}
		echo '</div>';
	}

	/*Submit Form Helper methods*/
	public function pi_set_content_type(){
    	return "text/html";
	}
	public function find_users_ip(){
		// /*Get ip information from user*/
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			//check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			//to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	public function update_pi_form_fost( $posted, $ip, $current_time ){
			extract($posted);
			$pi_post = array(
				'post_title'    => $pi_name . 'contact form',
				'post_content'  => $pi_message,
				'post_status'   => 'publish',
				'post_type'		=> 'pi_form'
			);
			$url = home_url();
			/*Insert the post while getting the id*/
			$post_id =  wp_insert_post( $pi_post );
			add_post_meta( $post_id, 'pi_name', $pi_name, true);
			add_post_meta( $post_id, 'pi_email', $pi_email, true);
			add_post_meta( $post_id, 'pi_questions', $pi_message, true);
			add_post_meta( $post_id, 'ip', $ip, true);
			add_post_meta( $post_id, 'current_time', $current_time, true);
	}
	public function pi_form_ajaxhandler() {
		if ( !isset( $_POST[ 'nonce' ] ) || !wp_verify_nonce( $_POST[ 'nonce' ], 'pi_msg_ajax') ) {
			status_header( '401' );
			die('Busted!');
		}

		$total = $_POST['total'];

		$posted = array();
		parse_str( $_POST[ 'formData' ] , $posted);
		$errors = $this->validate($posted, $total);
		if( $errors ){
			wp_send_json_error( $errors );
		}else{
			$ip = $this->find_users_ip();
			$current_time = time();
			$email = get_bloginfo('admin_email');
			$name = get_bloginfo('name');
			$subject = $name . ' Contact';
			$message = $this->pi_get_message( $posted );
			$headers[] = 'From: ' . $name. ' /<'. $email .'>';
			/*Send Email*/
	    	$mail = wp_mail( $email, $subject, $message, $headers );

	    	/*Create Email backup*/
			if($mail){
				$this->update_pi_form_fost( $posted, $ip, $current_time );

				/*Send success message to browser*/
				wp_send_json_success('Thank you for Contacting us. An <strong>Addiction Specialist</strong> will return your request within the next 24 hours. You can go back <a href="'. home_url() .'" >to our home page here.</a>');
			}else{
				wp_send_json_error('Email not sent!');
			}
		}
	}
}