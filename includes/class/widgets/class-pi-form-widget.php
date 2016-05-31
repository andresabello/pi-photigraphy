<?php
/**
 * Adds Pi Directory Advance Search Widget to the WP_Widget Class
 *
 * @package    Pi_Framework
 * @subpackage Pi_Form_Widget/admin/widgets
 * @author     Andres Abello <abellowins@gmail.com>
 */
class Pi_Form_Widget extends WP_Widget{
    public $name;
    public $version;

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        $theme = wp_get_theme();
        $this->name = $theme->get('Name');
        $this->version = $theme->get('Version');

        parent::__construct(
            'pi_widget', // Base ID
            __( 'Contact Form', $this->name ), // Name
            array( 'description' => __( 'Simple Contact Form.', $this->name), ) // Description
        );
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];?>
        <form class="pi-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <?php
                if ( !empty( $instance['title'] ) ){
                    echo '<h3>' . $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'] . '</h3>';
                }
            ?>
            <div class="pi-group">
                <label for="pi_name">Name</label>
                <input type="text" name="pi_name" placeholder="Name" class="required">
            </div>
            <div class="pi-group">
                <label for="pi_email">Email</label>
                <input type="text" name="pi_email" placeholder="Email" class="required">
            </div>
            <div class="pi-group">
                <label for="pi_message">Comments</label>
                <textarea name="pi_message" class="widefat"></textarea>
            </div>
            <input type="submit" value="Send Request" class="btn btn-danger">
        </form>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Get In Touch', $this->name );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

}





