<?php 
get_header();
/*page variables*/

?>
<div class="container">
    <div class="row">
        <div class="box">
            <div class="col-md-12 ac-content">
                <div class="row">
                    <div class="col-md-4">
                        <?php 
                        echo 'Sorry for the inconvenience, it seems the page that you are trying to find does not exists. You can contact us or go <a href="' . home_url() . '">back to the home page here</a> ';
                        ?>
                    </div>
                    <div class="col-md-8">
                        
                    </div>
                </div>
            </div>           
        </div>
    </div>     
</div>
<?php get_footer(); ?>