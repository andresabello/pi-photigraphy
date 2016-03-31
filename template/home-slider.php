<?php
    $homepage_options = get_option('ac_homepage_settings');
    $call_image_url = $homepage_options['cta_button_image_one'];
    $call_image_id = ac_get_image_id($call_image_url);
    $call_alt = get_post_meta($call_image_id, '_wp_attachment_image_alt', true);
    $chat_image_url = $homepage_options['cta_button_image_two'];
    $chat_image_id = ac_get_image_id($chat_image_url);
    $chat_alt = get_post_meta($chat_image_id, '_wp_attachment_image_alt', true);
    $slide1_image_url = $homepage_options['ac_main_image'];
    $slide2_image_url = $homepage_options['ac_main_image_two'];
    $slide3_image_url = $homepage_options['ac_main_image_three']; 
    $ac_images = array($slide1_image_url, $slide2_image_url, $slide3_image_url);
?>
<!-- MAIN CONTENT CONTAINER START-->
<div class="container home-slider">
    <div class="row">
        <div class="col-md-8">
            <!-- Slider -->
            <div class="bs-example" data-example-id="simple-carousel">
                <div id="main-carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <?php
                        foreach ($ac_images as $key => $image_url){
                            if (!empty($image_url) ){
                                echo '<li data-target="#main-carousel" data-slide-to="'. $key .'" class=" '. ( $key === 0 ? "active" : "" ) .' "></li>';
                            }
                        }
                        ?>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <?php
                        foreach ( $ac_images as $key => $image_url){
                            if( !empty($image_url) ){
                                $image_id = ac_get_image_id($image_url);
                                $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                echo '<div class="item '. ( $key === 0 ? "active" : "" ) .' "><img src="' . $image_url .'"  alt="' . ( !empty($alt) ? $alt : get_bloginfo('name') ) . '"></div>';                                             
                            }
                        }
                        ?>
                    </div>
                    <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <!-- Slider End -->
            <div class="row">
                <div class="col-md-6">
                    <?php 
                    echo '<div class="call-wrap"><a href="tel:'. preg_replace("/[^0-9,.]/", "", $phone_number) .'"><img class="call-image contact-image" src="'. $call_image_url .'" alt="'. ( !empty($call_alt) ? $call_alt : get_bloginfo( 'name') ) .'"></a></div>';
                    ?>
                </div>
                <div class="col-md-6">
                    <?php 
                    echo '<div class="chat-wrap"><a href="#" onclick="Comm100API.open_chat_window(event, 1225);"><img class="chat-image contact-image" src="'. $chat_image_url .'" alt="'  . ( !empty($chat_alt) ? $call_alt : get_bloginfo( 'name') ) . '"></a></div>';
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if ( !function_exists('dynamic_sidebar')
            || !dynamic_sidebar("Form Container") ) : ?>  
            <?php endif; ?>   
        </div>
    </div>    
</div>
<!-- CLOSE CONTAINER STARTED IN HEADER.PHP-->