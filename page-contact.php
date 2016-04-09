<?php
/*
Template Name: Contact Page
*/ 
get_header();
/*page variables*/
$general_options = get_option('pi_general_settings');

?>
<div class="container">
    <div class="row">
        <div class="box">
            <div class="col-md-12 ac-content">
                <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?php
                            $id = get_the_ID();
                            var_dump(get_post_meta($id));
                            echo 'hello';
                            the_content();
                            ?>
                        </div>
                        <div class="col-md-8">
                            <?php echo do_shortcode('[piform title="Contact Form"]') ;?>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                <p><?php _e('No pages were found. Sorry!'); ?></p>
                <?php endif; ?>
            </div>           
        </div>
    </div>     
</div>
<?php get_footer(); ?>