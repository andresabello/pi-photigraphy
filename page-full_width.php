<?php 
/**
 * page-full-width.php
 *
 * Template Name: Full Width Page
 */
?>

<?php get_header(); ?>
    <!-- header ends -->
    <!-- content -->
    <div class="container">
		<div class="row">
			<div class="col-12" role="main">
				<?php while( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<!-- Article header -->
						<header class="entry-header">
							<h1><?php the_title(); ?></h1>
						</header> <!-- end entry-header -->

						<!-- Article content -->
						<div class="entry-content">
							<?php
							$id = get_the_ID();
							var_dump(get_post_meta($id));
							the_content(); ?>

							<?php wp_link_pages(); ?>
						</div> <!-- end entry-content -->

						<!-- Article footer -->
						<footer class="entry-footer">
							<?php 
								if ( is_user_logged_in() ) {
									echo '<p>';
									edit_post_link( __( 'Edit', 'pidirectory' ), '<span class="meta-edit">', '</span>' );
									echo '</p>';
								}
							?>
						</footer> <!-- end entry-footer -->
					</article>
				<?php endwhile; ?>
			</div> <!-- end main-content -->
		</div> <!-- end row -->
	</div> <!-- end container -->
	<!-- end content -->
<?php get_footer(); ?>