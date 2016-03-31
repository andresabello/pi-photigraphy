<?php
$pi_options = get_option('pi_general_settings');
$sidebar_side = $pi_options['sidebar_position'];
 
get_header();
?>
<!-- MAIN CONTENT CONTAINER START-->
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php echo '<h1>' . $title . '</h1>'; ?>                    
        </div>
        <?php if($sidebar_side === "left" ): ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>
        <div class="col-8 pi-content">
            <?php 
            if(have_posts()) : while(have_posts()) : the_post();
                 the_content();
                endwhile; else:?>
                <p><?php _e('No pages were found. Sorry!'); ?></p>
            <?php endif; ?> 
        </div>
        <?php if($sidebar_side === "right" ): ?>
            <?php get_sidebar(); ?>
        <?php endif; ?>               
    </div>
</div>
<?php get_footer(); ?>