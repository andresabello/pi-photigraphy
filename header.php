<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php bloginfo('name'); ?></title>
        <link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?> >
        <header class="clearfix" id="main-header">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <a  class="logo" href="<?= home_url(); ?>">
                            Pi Photography
                        </a>                    
                    </div>
                    <div class="col-8">
                    <?php
                        $defaults = array(
                            'menu'            => 'primary',
                            'theme_location'  => 'primary',
                            'container'       => 'div',
                            'container_class' => 'pi-wrap',
                            'container_id'    => 'pi-nav-wrap',
                            'menu_class'      => 'pi-menu',
                            'menu_id'         => 'pi-navigation',
                            'echo'            => true,
                            'fallback_cb'     => 'wp_page_menu',
                            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            'depth'           => 2,
                        );

                        wp_nav_menu( $defaults );
                        ?>                   
                    </div>
                </div>
            </div>
        </header>