    <?php
        $footer_options = get_option('pi_footer_settings');
        $columns = (int)$footer_options['footer_columns'];

    ?>
    <!-- FOOTER STARTS -->
    <footer class="footer">
        <div class="upper-footer">
            <div class="container">
                <div class="row">
                    <?php if($columns == 4) : ?>

                        <div class="col-3">
                            <?php if ( is_active_sidebar( 'pi-footer-left' ) ) : ?>
                                    <?php dynamic_sidebar( 'pi-footer-left' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-3">
                            <?php if ( is_active_sidebar( 'pi-footer-center-left' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-center-left' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-3">
                            <?php if ( is_active_sidebar( 'pi-footer-right-center' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-right-center' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-3">
                            <?php if ( is_active_sidebar( 'pi-footer-right' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-right' ); ?>
                            <?php endif; ?>
                        </div>

                    <?php elseif($columns == 3) :  ?>

                        <div class="col-4">
                            <?php if ( is_active_sidebar( 'pi-footer-left' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-left' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-4">
                            <?php if ( is_active_sidebar( 'pi-footer-center-left' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-center-left' ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-4">
                            <?php if ( is_active_sidebar( 'pi-footer-right-center' ) ) : ?>
                                <?php dynamic_sidebar( 'pi-footer-right-center' ); ?>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="lower-footer">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="copyright">
                            <p class="text-center">
                                &copy; <?php echo date( 'Y' ); ?>
                                <a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
                                <?php _e( 'All rights reserved.', 'pi-photography' ); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER ENDS -->
    <?php wp_footer(); ?>
</body>
</html>