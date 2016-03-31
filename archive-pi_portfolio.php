<?php 
$port_options = get_option('pi_portfolio_settings');
$sidebar_side = $port_options['sidebar_port_position'];
$change_view = $port_options['change_view'];
get_header(); 
?>
<div class="container">
	<div class="row">
	<?php if( $sidebar_side === "left" ): ?>
		<?php get_sidebar(); ?>
	<?php endif; ?>
	<?php if( $sidebar_side === "none" ): ?>
		<div class="col-12">
	<?php else: ?>
		<div class="col-8">
	<?php endif; ?>
			<div class="pi-content">
				<?php if ( $change_view == 'on' ) : ?>
				<div class="change-view">
					<ul>
						<li class="pi-view" data-id="3" data-current="">4 Columns</li>
						<li class="pi-view" data-id="4" data-current="">3 Columns</li>
						<li class="pi-view" data-id="6" data-current="">2 Columns</li>
						<li class="pi-view" data-id="12" data-current="">List View</li>
					</ul>
				</div>
				<?php endif; ?>
					<div class="row">
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
					
						<div class="pi-column col-12">
							<div class="pi-blog">
							<?php if ( has_post_thumbnail()) : ?>
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="img-wrap">
								<?php the_post_thumbnail('full', array( 'class' => 'img-responsive feature-image' )); ?></a>
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
					
				<?php endwhile; else: ?>
				<p><?php _e('No posts were found. Sorry!'); ?></p>
				<?php endif; ?>
				</div>
			</div>
		</div>
		<?php if( $sidebar_side === "right" ) : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?> 			
	</div>	
</div>
<?php get_footer(); ?>