<?php

/**
*
* Navigation for posts
*
**/
if ( ! function_exists( 'pi_paging_nav' ) ) {
	function pi_paging_nav() { 
		global $wp_query;
		$bignum = 999999999;
		if ( $wp_query->max_num_pages <= 1 )
			return;
		
		echo '<div class="row">';
			echo '<div class="col-md-12">';
				echo '<nav class="pagination">';
					echo paginate_links( array(
						'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
						'format'       => '',
						'current'      => max( 1, get_query_var('paged') ),
						'total'        => $wp_query->max_num_pages,
						'prev_text'    => '<icon class="glyphicon glyphicon-arrow-left"></icon> Previous',
						'next_text'    => 'Next <icon class="glyphicon glyphicon-arrow-right"></icon>',
						'type'         => 'list',
						'end_size'     => 3,
						'mid_size'     => 3
					) );
				echo '</nav>';
			echo '</div>';
		echo '</div>';

	}
}

if ( ! function_exists( 'pi_post_meta' ) ) {
	function pi_post_meta() {
		echo '<ul class="list-inline entry-meta">';

		if ( get_post_type() === 'post' || get_post_type() === 'page' ) {
			// If the post is sticky, mark it.
			if ( is_sticky() ) {
				echo '<li class="meta-featured-post"><i class="fa fa-thumb-tack"></i> ' . __( 'Sticky', 'pidirectory' ) . ' </li>';
			}

			// Get the post author.
			printf(
				'<li class="meta-author"><a href="%1$s" rel="author">%2$s</a></li>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);

			// Get the date.
			echo '<li class="meta-date"> ' . get_the_date() . ' </li>';

			// The categories.
			$category_list = get_the_category_list( ', ' );
			if ( $category_list ) {
				echo '<li class="meta-categories"> ' . $category_list . ' </li>';
			}

			// The tags.
			$tag_list = get_the_tag_list( '', ', ' );
			if ( $tag_list ) {
				echo '<li class="meta-tags"> ' . $tag_list . ' </li>';
			}

			// Comments link.
			if ( comments_open() ) :
				echo '<li>';
				echo '<span class="meta-reply">';
				comments_popup_link( __( 'Leave a comment', 'pidirectory' ), __( '1 comment so far', 'pidirectory' ), __( 'View all % comments', 'pidirectory' ) );
				echo '</span>';
				echo '</li>';
			endif;

			// Edit link.
			if ( is_user_logged_in() ) {
				echo '<li>';
				edit_post_link( __( 'Edit', 'pidirectory' ), '<button class=" meta-edit">', '</button>' );
				echo '</li>';
			}
		}elseif( get_post_type() === 'pi_listing' ){
			// Edit link.
			if ( is_user_logged_in() ) {
				echo '<li>';
				edit_post_link( __( 'Edit', 'pidirectory' ), '<button class="btn btn-success meta-edit">', '</button>' );
				echo '</li>';
			}			
		}
	}
}

/**
*
* Replace the standard wordpress excerpt read more link
*
**/
function pi_replace_read_more( $more ) {
	return ' <a class="read-more" href="' . get_permalink( get_the_ID() ) . '">' . __( 'Read More', 'pidirectory' ) . ' <icon class="glyphicon glyphicon-arrow-right"></icon> </a>';
}
add_filter( 'excerpt_more', 'pi_replace_read_more' );

/**
*
* Remove links from comment posts
*
**/
function pi_comment_post( $incoming_comment ) {
	$incoming_comment['comment_content'] = htmlspecialchars($incoming_comment['comment_content']);
	$incoming_comment['comment_content'] = str_replace( "'", '&apos;', $incoming_comment['comment_content'] );
	return( $incoming_comment );
}
add_filter('preprocess_comment', 'pi_comment_post', '', 1);

/**
*
* Never display links on comments
*
**/
function pi_comment_display( $comment_to_display ) {
	$comment_to_display = str_replace( '&apos;', "'", $comment_to_display );
	return $comment_to_display;
}
add_filter('comment_text', 'pi_comment_display', '', 1);
add_filter('comment_text_rss', 'pi_comment_display', '', 1);
add_filter('comment_excerpt', 'pi_comment_display', '', 1);

/**
*
* Turn HEX to RGB with 80% opacity
*
**/
function hextorgba($hex) {
	$hex = str_replace("#", "", $hex);

	if(strlen($hex) == 3) {
		$r = hexdec(substr($hex,0,1).substr($hex,0,1));
		$g = hexdec(substr($hex,1,1).substr($hex,1,1));
		$b = hexdec(substr($hex,2,1).substr($hex,2,1));
	} else {
		$r = hexdec(substr($hex,0,2));
		$g = hexdec(substr($hex,2,2));
		$b = hexdec(substr($hex,4,2));
	}
	
	$rgb = array($r, $g, $b);
	$rgba = implode(",", $rgb);
	$rgba = 'rgba(' . $rgba . ', .8)';
	return $rgba;
}

/**
*
* Get Page Name
*
**/
function get_page_by_name($pagename){
	$pages = get_pages();
	foreach ( $pages as $page ) if ( $page->post_name == $pagename ) return $page;
	return false;
}

/**
*
* Get Users IP
*
**/
function pi_get_user_ip(){
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
}

function pi_add_http( $url ) {
	if( !empty($url) ){
	    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
	        $url = "http://" . $url;
	    }		
	}
    return $url;
}

function pi_show_extra_register_fields(){
?>
	<p>
		<label for="password">Password<br/>
		<input id="password" class="input" type="password" tabindex="30" size="25" value="" name="password" />
		</label>
	</p>
	<p>
		<label for="repeat_password">Repeat password<br/>
		<input id="repeat_password" class="input" type="password" tabindex="40" size="25" value="" name="repeat_password" />
		</label>
	</p>
<?php
}
add_action( 'register_form', 'pi_show_extra_register_fields' );
// Check the form for errors

function pi_check_extra_register_fields($login, $email, $errors) {
	if ( $_POST['password'] !== $_POST['repeat_password'] ) {
		$errors->add( 'passwords_not_matched', "<strong>ERROR</strong>: Passwords must match" );
	}
	if ( strlen( $_POST['password'] ) < 6 ) {
		$errors->add( 'password_too_short', "<strong>ERROR</strong>: Passwords must be at least six characters long" );
	}
}
add_action( 'register_post', 'pi_check_extra_register_fields', 10, 3 );

function pi_register_extra_fields( $user_id ){
	$userdata = array();

	$userdata['ID'] = $user_id;
	if ( $_POST['password'] !== '' ) {
		$userdata['user_pass'] = $_POST['password'];
	}
	$new_user_id = wp_update_user( $userdata );
}
add_action( 'user_register', 'pi_register_extra_fields', 100 );


function pi_edit_password_email_text ( $text ) {
	if ( $text == 'A password will be e-mailed to you.' ) {
		$text = 'If you leave password fields empty one will be generated for you. Password must be at least 6 characters long.';
	}
	return $text;
}
add_filter( 'gettext', 'pi_edit_password_email_text' );


function pi_format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
    else
        return $phone;
}


// Breadcrumbs for website
function pi_breadcrumbs() {
	echo '<a href="';
	echo get_option('home');
	echo '">';
	bloginfo('name');
	echo "</a>";
		if (is_category() || is_single()) {
			echo "&nbsp;&nbsp;/&nbsp;&nbsp;";
			the_category('&nbsp;&nbsp; &bull; &nbsp;&nbsp;');
				if (is_single()) {
					echo " &nbsp;&nbsp;/&nbsp;&nbsp; ";
					the_title();
				}
        } elseif (is_page()) {
            echo "&nbsp;&nbsp;/&nbsp;&nbsp;";
            echo the_title();
		} elseif (is_search()) {
            echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
			echo '"<em>';
			echo the_search_query();
			echo '</em>"';
        }
}
function display_breadcrumbs() {?>
	<div class="breadcrumbs"><?php pi_breadcrumbs(); ?></div><?php
}
add_action('ac_hook_after_header','display_breadcrumbs');

function pi_get_portfolio_items($num = -1){
	/*Get all pi_slider posts*/
	$args = array(
		'posts_per_page'   => $num,
		'offset'           => 0,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'pi_portfolio',
		'post_status'      => 'publish'
	);
	/*Portfolio Query*/
	$items = get_posts( $args );
	$grid_class = 'col-4';

	switch ($grid_class){
		case 'col-3':
			$col_width = '262.5px';
			break;
		case 'col-4':
			$col_width = '360px';
			break;
		case 'col-6':
			$col_width = '555px';
			break;
	}

	$cat_args = array(
		'taxonomy' => 'category',
		'orderby'  => 'name',
		'order'    => 'DESC',
		'exclude'  => 1
	);
	$all_cats = get_categories($cat_args);
	ob_start();
	?>
	<div class="pi-portfolio-wrapper row">
		<div class="row">
			<div class="col-8">
				<select name="categories" class="portfolio-sorting list-inline text-center">
					<option data-group="all" class="active">All</option>
					<?php
					foreach ($all_cats as $cat){
						echo '<option data-group="'. $cat->cat_name .'">' . ucwords($cat->cat_name) . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="portfolio-items list-unstyled" id="grid">
			<?php
			foreach($items as $item) {
				$categories = get_the_category($item->ID);
				$cat_num = count($categories);
				$cat = '';
				$i = 1;
				foreach ($categories as $key => $category){
					$cat .= $category->cat_name;
					$cat .= ( $i < $cat_num) ? ',' : '';
					$i++;
				}
				?>
				<div class="portfolio-item <?php echo $grid_class; ?>" data-groups="<?php echo $cat; ?>">
					<?php echo get_the_post_thumbnail ($item->ID, 'large', array('class' => 'img-responsive')); ?>
					<figure class="portfolio-item__details" style="width: <?php echo $col_width; ?>;">
						<figcaption class="portfolio-item__title"><a href="<?php echo esc_url(get_permalink($item->ID))?>"><?php echo $item->post_title; ?></a></figcaption>
						<p class="portfolio-item__tags"><?php echo $cat; ?></p>
					</figure>
				</div>
				<?php

			}
			?>
		</div>
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	/* Restore original Post Data */
	wp_reset_postdata();
	return $html;
}











