<?php get_header(); ?>
<div class="container">
	<div class="row">
		<div class="box">
			<div class="col-md-8">
				<div class="ac-content">
					<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
						<h1 class="main-title"><?php the_title(); ?></h1>
						<?php if ( has_post_thumbnail()) : ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('full', array( 'class'   =>"img-responsive feature-image")); ?></a>
						<?php endif; ?>	
						<div class="byline">
							<p>by <?php the_author_posts_link(); ?> on <span class="date"> <?php the_time('l F d, Y'); ?></span><br/>Posted in: <?php the_category(', '); ?> | <?php the_tags('Tagged with: ',' • ','<br />'); ?></p>
						</div>
						<?php edit_post_link( 'Edit Post' ); ?>
						<?php the_content('Read More...'); ?>
						<div class="navi">
							<div class="right">
								<?php previous_post_link(''); ?>
								<?php next_post_link(''); ?></div>
						</div>
						<?php endwhile; else: ?>
						<p><?php _e('No posts were found. Sorry!'); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php get_sidebar(); ?>		
		</div>
	</div>
</div>
<?php get_footer(); ?>