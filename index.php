<?php 
$pi_options = get_option('pi_general_settings');
$sidebar_side = $pi_options['sidebar_general_position'];
get_header(); 
?>
<div class="container">
	<div class="row">
		<?php if( $sidebar_side === "left" ): ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>
		<div class="col-8">
			<div class="pi-content">
				<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
				<div class="pi-blog">
					<div class="row">
						<div class="col-12">
							<?php if ( has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="img-wrap">
								<?php the_post_thumbnail('full', array( 'class'   =>"img-responsive feature-image")); ?></a>
							<?php endif; ?>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<div class="byline">
								<p>In: <?php the_category(', '); ?> | <?php the_tags('Tagged with: ',' • ','<br />'); ?></p>
							</div>
							<?php
							$content = get_the_content();                    
							echo substr($content, 0, 350) . '<span class="pi-more"><a href="' . get_the_permalink() . '">... Read More →</a></span>';
							?>
						</div>
					</div>
				</div>
			<?php endwhile; else: ?>
			<p><?php _e('No posts were found. Sorry!'); ?></p>
			<?php endif; ?>
			</div>
		</div>
		<?php if( $sidebar_side === "right" ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?> 			
	</div>	
</div>
<?php get_footer(); ?>