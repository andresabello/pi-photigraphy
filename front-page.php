<?php 
/**
 * front-page.php
 *
 * Homepage Template
 */
?>

<?php get_header(); ?>
    <!-- header ends -->
    <!-- content -->
    <div class="container">
		<div class="row">
			<div class="col-12" role="main">
				<?php while( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
					<?php 
						if ( is_user_logged_in() ) {
							echo '<p>';
							edit_post_link( __( 'Edit', 'pidirectory' ), '<span class="meta-edit">', '</span>' );
							echo '</p>';
						}
					?>
				<?php endwhile; ?>
			</div> <!-- end main-content -->
		</div> <!-- end row -->
	</div> <!-- end container -->
	<!-- end content -->
<?php get_footer(); ?>