<?php
/*
Template Name: Portfolio Page
*/
get_header();
?>
<!-- MAIN CONTENT CONTAINER START-->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 pi-content">
            <?php 
            if(have_posts()) : while(have_posts()) : the_post();
                 the_content();
                endwhile; else:?>
                <p><?php _e('No pages were found. Sorry!'); ?></p>
            <?php endif; ?> 
        </div>              
    </div>
</div>
<?php get_footer(); ?>