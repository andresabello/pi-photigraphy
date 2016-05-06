<?phpclass Pi_Photography_Theme_Options{    /**     * Holds the values to be used in the fields callbacks     */ 	private $general_settings_key       = 'pi_general_settings';    private $homepage_settings_key      = 'pi_homepage_settings';    private $page_settings_key          = 'pi_page_settings';    private $form_settings_key          = 'pi_form_settings';    private $footer_settings_key        = 'pi_footer_settings';    private $css_settings_key           = 'pi_css_settings';    private $portfolio_settings_key     = 'pi_portfolio_settings';    private $plugin_options_key         = 'pi_page_options';    private $plugin_settings_tabs       = array();    /**     * The ID of this Theme.     *     * @since    1.0.0     * @access   private     * @var      string    $theme_name    The ID of this theme.     */    private $theme_name;    /**     * The version of this theme.     *     * @since    1.0.0     * @access   private     * @var      string    $version    The current version of this theme.     */    private $version;    /**     * Initialize the class and set its properties.     *     * @since    1.0.0     * @param      string    $theme_name    The name of the theme.     * @param      string    $version    The version of this theme.     */    public function __construct( $theme_name, $version ) {        $this->theme_name = $theme_name;        $this->version = $version;    }	function load_settings() {	    $this->general_settings = (array) get_option( $this->general_settings_key );	    $this->homepage_settings = (array) get_option( $this->homepage_settings_key );        $this->page_settings = (array) get_option( $this->page_settings_key );        $this->form_settings = (array) get_option( $this->form_settings_key );        $this->footer_settings = (array) get_option( $this->footer_settings_key );        $this->css_settings = (array) get_option( $this->css_settings_key );        $this->portfolio_settings = (array) get_option( $this->portfolio_settings_key );	    // Merge with defaults	    $this->general_settings = array_merge( array(	        'general_option' => 'General Options'	    ), $this->general_settings );	    $this->homepage_settings = array_merge( array(	        'homepage_option' => 'Home Page Options'	    ), $this->homepage_settings );        $this->page_settings = array_merge( array(            'page_option' => 'Blog Options'        ), $this->page_settings );        $this->form_settings = array_merge( array(            'form_option' => 'Form Options'        ), $this->form_settings );        $this->footer_settings = array_merge( array(            'footer_option' => 'Footer Options'        ), $this->footer_settings );        $this->portfolio_settings = array_merge( array(            'portfolio_option' => 'Portfolio Options'        ), $this->portfolio_settings );        $this->css_settings = array_merge( array(            'css_option' => 'CSS Options'        ), $this->css_settings );	}    /**     * Add options page     */    public function add_plugin_page(){        add_menu_page(            'Settings Admin',             'Pi Theme Options',             'manage_options',              $this->plugin_options_key,             array( $this, 'create_admin_page' )        );    }    /**     * Options page callback     */    public function create_admin_page(){    	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;        // Set class property        $this->options = get_option( 'pi_option_name' );//        echo (empty($array) ? $this->pi_admin_notice() : ' ');///        var_dump(array_merge($this->general_settings, $this->homepage_settings, $this->page_settings, $this->form_settings, $this->footer_settings, $this->css_settings//        $this->portfolio_settings));        ?>        <div class="wrap">			<?php $this->plugin_options_tabs(); ?>            <br>            <a href="http://piboutique.com/PiPhotography/documentation/">Documentation &rarr;</a>            <br>            <?php            $array = array_merge($this->general_settings, $this->homepage_settings, $this->page_settings, $this->form_settings, $this->footer_settings, $this->css_settings);            if(!empty($array)){                echo $this->pi_admin_notice(true);            }?>            <form method="post" action="options.php">            <?php                // This prints out all hidden setting fields            	wp_nonce_field( 'update-options' );                settings_fields( $tab);                   do_settings_sections( $tab );                submit_button();             ?>            </form>        </div>        <?php    }	/*	 * Renders our tabs in the plugin options page,	 * walks through the object's tabs array and prints	 * them one by one. Provides the heading for the	 * plugin_options_page method.	 */	function plugin_options_tabs() {		$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->general_settings_key;		screen_icon();		echo '<h2 class="nav-tab-wrapper">';		foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {			$active = $current_tab == $tab_key ? 'nav-tab-active' : '';			echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';			}		echo '</h2>';	}    /**     * Register and add general settings     */    public function register_general_settings(){		$this->plugin_settings_tabs[$this->general_settings_key] = 'General';		register_setting( 			$this->general_settings_key, 			$this->general_settings_key,            array( $this, 'sanitize' )		);		add_settings_section( 			'section_general', 			'General Settings', 			array( $this, 'print_section_info' ), 			$this->general_settings_key 		);        add_settings_field(             'pi_logo',             'Logo Image',             array( $this, 'main_image_callback_general' ),             $this->general_settings_key,             'section_general',            'pi_logo'         );        add_settings_field(             'pi_font_color',             'Font Color',             array( $this, 'pi_color_picker' ),             $this->general_settings_key,            'section_general',            'pi_font_color'         );        add_settings_field(             'pi_font_family',             'Font Family',             array( $this, 'font_family_callback' ),             $this->general_settings_key,            'section_general',            'pi_font_family'        );        add_settings_field(            'pi_main_color_picker',            'Main Color',             array( $this, 'pi_color_picker' ),             $this->general_settings_key,            'section_general',            'pi_main_color_picker'        );        add_settings_field(             'pi_second_color_picker',             'Secondary Color',             array( $this, 'pi_color_picker' ),             $this->general_settings_key,            'section_general',            'pi_second_color_picker'         );        add_settings_field(             'pi_number',             'Phone Number',             array( $this, 'general_text_callback' ),             $this->general_settings_key,            'section_general',            'pi_number'         );        add_settings_field(             'sidebar_general_position',             'Choose Sidebar Position',             array( $this, 'general_sidebar_position_callback' ),             $this->general_settings_key,             'section_general',            'sidebar_general_position'         );    }    /**     * Register and add homepage settings     */    public function register_homepage_settings(){   		$this->plugin_settings_tabs[$this->homepage_settings_key] = 'Homepage';		register_setting( 			$this->homepage_settings_key, 			$this->homepage_settings_key,            array( $this, 'sanitize' ) // Sanitize 		);        //Main         add_settings_section(            'section_homepage', // ID            'Home Page Settings', // Title            array( $this, 'print_section_info' ), // Callback			$this->homepage_settings_key         );        //Main Image        add_settings_field(             'pi_main_image',             'Main Image',             array( $this, 'main_image_callback' ),             $this->homepage_settings_key,            'section_homepage',            'pi_main_image'        );        add_settings_field(            'form_position',            'Choose Home Form Position',            array( $this, 'homeform_position_callback' ),            $this->homepage_settings_key,            'section_homepage',            'form_position'        );    }    /**     * Register and add page settings     */    public function register_page_settings(){        $this->plugin_settings_tabs[$this->page_settings_key] = 'Blog';        register_setting(             $this->page_settings_key,             $this->page_settings_key,            array( $this, 'sanitize' )        );        add_settings_section(             'section_page',             'Blog Settings',             array( $this, 'print_section_info' ),             $this->page_settings_key         );        add_settings_field(             'title_background',             'Title Background',             array( $this, 'page_image_callback' ),             $this->page_settings_key,             'section_page',            'title_background'         );        add_settings_field(             'sidebar_position',             'Choose Sidebar Position',             array( $this, 'sidebar_position_callback' ),             $this->page_settings_key,             'section_page',            'sidebar_position'         );            }    /**     * Register and add page settings     */    public function register_form_settings(){        $this->plugin_settings_tabs[$this->form_settings_key] = 'Form';        register_setting(             $this->form_settings_key,             $this->form_settings_key,            array( $this, 'sanitize' )        );        add_settings_section(             'section_form',             'Form Settings',             array( $this, 'print_section_info' ),             $this->form_settings_key         );        add_settings_field(            'form_title',            'Form Title',            array( $this, 'form_text_callback' ),            $this->form_settings_key,            'section_form',            'form_title'        );        add_settings_field(             'home_form_background_color',             'Home Form Background Color)',            array( $this, 'form_color_picker_callback' ),             $this->form_settings_key,             'section_form',            'home_form_background_color'         );        add_settings_field(             'home_form_font_color',             'Home Form Font Color',             array( $this, 'form_color_picker_callback' ),             $this->form_settings_key,             'section_form',            'home_form_font_color'         );        add_settings_field(             'regular_form_background_color',             'Regular Form Background Color',            array( $this, 'form_color_picker_callback' ),             $this->form_settings_key,             'section_form',            'regular_form_background_color'         );        add_settings_field(             'regular_form_font_color',             'Regular Form Font Color',             array( $this, 'form_color_picker_callback' ),             $this->form_settings_key,             'section_form',            'regular_form_font_color'         );           }    /**     * Register and add footer settings     */    public function register_footer_settings(){        $this->plugin_settings_tabs[$this->footer_settings_key] = 'Footer';        register_setting(             $this->footer_settings_key,             $this->footer_settings_key,            array( $this, 'sanitize' )        );        add_settings_section(             'section_footer',             'Footer Settings',             array( $this, 'print_section_info' ),             $this->footer_settings_key         );        add_settings_field(             'footer_columns',             'Footer Columns',             array( $this, 'footer_columns_callback' ),             $this->footer_settings_key,             'section_footer'        );        add_settings_field(             'footer_logo_image',             'Footer Logo Image',             array( $this, 'footer_image_callback' ),             $this->footer_settings_key,             'section_footer',            'footer_logo_image'         );        add_settings_field(             'footer_background',             'Footer Background',             array( $this, 'footer_color_picker_callback' ),             $this->footer_settings_key,             'section_footer',            'footer_background'         );        add_settings_field(             'lower_footer_background',             'Lower Footer Background',             array( $this, 'footer_color_picker_callback' ),             $this->footer_settings_key,             'section_footer',            'lower_footer_background'         );        add_settings_field(             'footer_color',             'Footer Font Color',             array( $this, 'footer_color_picker_callback' ),             $this->footer_settings_key,             'section_footer',            'footer_color'         );           }    /**     * Register and add Portfolio settings     */    public function register_portfolio_settings(){        $this->plugin_settings_tabs[$this->portfolio_settings_key] = 'Portfolio';        register_setting(             $this->portfolio_settings_key,             $this->portfolio_settings_key,            array( $this, 'sanitize' )        );        add_settings_section(             'section_portfolio',             'Portfolio Options',             array( $this, 'print_section_info' ),             $this->portfolio_settings_key         );        add_settings_field(             'change_view',             'Allow user to change portfolio view',             array( $this, 'portfolio_checkbox_callback' ),             $this->portfolio_settings_key,             'section_portfolio',            'change_view'        );         add_settings_field(             'sidebar_port_position',             'Choose Sidebar Position',             array( $this, 'port_sidebar_position_callback' ),            $this->portfolio_settings_key,             'section_portfolio',            'sidebar_port_position'         );        add_settings_field(             'pi_placeholder',             'Placeholder Image',             array( $this, 'placeholder_image_callback_general' ),             $this->portfolio_settings_key,             'section_portfolio',            'pi_placeholder'         );        add_settings_field(            'pi_col',            'Columns',            array( $this, 'portfolio_columns_callback' ),            $this->portfolio_settings_key,            'section_portfolio',            'pi_col'        );    }    /**     * Register and add CSS settings     */    public function register_css_settings(){        $this->plugin_settings_tabs[$this->css_settings_key] = 'CSS';        register_setting(             $this->css_settings_key,             $this->css_settings_key,            array( $this, 'sanitize' )        );        add_settings_section(             'section_css',             'Enter Custom CSS',             array( $this, 'print_section_info' ),             $this->css_settings_key         );        add_settings_field(             'css_value',             'CSS Styles',             array( $this, 'css_callback' ),             $this->css_settings_key,             'section_css',            'css_value'        );       }    /**     * Sanitize each setting field as needed     *     * @param array $input Contains all settings fields as array keys     */    public function sanitize( $input ){        //Header Options        $new_input = array();        //General options                if( isset( $input['pi_state_abb'] ) )            $new_input['pi_state_abb'] = sanitize_text_field( $input['pi_state_abb'] );        if( isset( $input['pi_logo'] ) )            $new_input['pi_logo'] = sanitize_text_field( $input['pi_logo'] );        if( isset( $input['pi_font_color'] ) )            $new_input['pi_font_color'] = sanitize_text_field( $input['pi_font_color'] );        if( isset( $input['pi_font_family'] ) )            $new_input['pi_font_family'] = sanitize_text_field( $input['pi_font_family'] );        if( isset( $input['pi_main_color_picker'] ) )            $new_input['pi_main_color_picker'] = sanitize_text_field( $input['pi_main_color_picker'] );        if( isset( $input['pi_second_color_picker'] ) )            $new_input['pi_second_color_picker'] = sanitize_text_field( $input['pi_second_color_picker'] );        if( isset( $input['pi_number'] ) )            $new_input['pi_number'] = sanitize_text_field( $input['pi_number'] );        if( isset( $input['pi_menu_picker'] ) )            $new_input['pi_menu_picker'] = sanitize_text_field( $input['pi_menu_picker'] );        if( isset( $input['sidebar_general_position'] ) )            $new_input['sidebar_general_position'] = sanitize_text_field( $input['sidebar_general_position'] );        if( isset( $input['pi_main_image_bg'] ) )            $new_input['pi_main_image_bg'] = sanitize_text_field( $input['pi_main_image_bg'] );        //Homepage options        if( isset( $input['pi_main_image'] ) )            $new_input['pi_main_image'] = sanitize_text_field( $input['pi_main_image'] );        if( isset( $input['form_position'] ) )            $new_input['form_position'] = sanitize_text_field( $input['form_position'] );                //Blog Options        if( isset( $input['title_background'] ) )            $new_input['title_background'] = sanitize_text_field( $input['title_background'] );        if( isset( $input['contact_text'] ) )            $new_input['contact_text'] = htmlspecialchars( $input['contact_text'] );         if( isset( $input['sidebar_position'] ) )            $new_input['sidebar_position'] = sanitize_text_field( $input['sidebar_position'] );                       //Form Options        if( isset( $input['home_form_background'] ) )            $new_input['home_form_background'] = sanitize_text_field( $input['home_form_background'] );        if( isset( $input['home_form_background_color'] ) )            $new_input['home_form_background_color'] = sanitize_text_field( $input['home_form_background_color'] );        if( isset( $input['home_form_font_color'] ) )            $new_input['home_form_font_color'] = sanitize_text_field( $input['home_form_font_color'] );          if( isset( $input['regular_form_background'] ) )            $new_input['regular_form_background'] = sanitize_text_field( $input['regular_form_background'] );        if( isset( $input['regular_form_background_color'] ) )            $new_input['regular_form_background_color'] = sanitize_text_field( $input['regular_form_background_color'] );        if( isset( $input['regular_form_font_color'] ) )            $new_input['regular_form_font_color'] = sanitize_text_field( $input['regular_form_font_color'] );        if( isset( $input['form_title'] ) )            $new_input['form_title'] = sanitize_text_field( $input['form_title'] );        //Footer Options        if( isset( $input['footer_columns'] ) )            $new_input['footer_columns'] = sanitize_text_field( $input['footer_columns'] );          if( isset( $input['footer_logo_image'] ) )            $new_input['footer_logo_image'] = sanitize_text_field( $input['footer_logo_image'] );        if( isset( $input['footer_address'] ) )            $new_input['footer_address'] = htmlspecialchars( $input['footer_address'] );        if( isset( $input['footer_background'] ) )            $new_input['footer_background'] = sanitize_text_field( $input['footer_background'] );         if( isset( $input['lower_footer_background'] ) )            $new_input['lower_footer_background'] = sanitize_text_field( $input['lower_footer_background'] );            if( isset( $input['footer_color'] ) )            $new_input['footer_color'] = sanitize_text_field( $input['footer_color'] );        //CSS Options        if( isset( $input['css_value'] ) )            $new_input['css_value'] = $input['css_value'];          //Portfolio Options        if( isset( $input['change_view'] ) )            $new_input['change_view'] = sanitize_text_field($input['change_view']);         if( isset( $input['sidebar_port_position'] ) )            $new_input['sidebar_port_position'] = htmlspecialchars($input['sidebar_port_position']);         if( isset( $input['pi_placeholder'] ) )            $new_input['pi_placeholder'] = sanitize_text_field( $input['pi_placeholder'] );        if( isset( $input['pi_col'] ) )            $new_input['pi_col'] = sanitize_text_field( $input['pi_col'] );        return $new_input;    }    /**      * Print the Section text     */    public function print_section_info(){    	print '<p>Enter & Upload your settings below:</p>';    }    /**      * Get the settings option array and print one of its values     */    public function logo_callback(){           printf(            '<span class="upload">                <input type="text" id="pi_logo" class="regular-text text-upload" name="pi_option_name[pi_logo]" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br>            </span>',            isset( $this->options['pi_logo'] ) ? esc_url( $this->options['pi_logo']) : '',            isset( $this->options['pi_logo'] ) ? esc_url( $this->options['pi_logo']) : ''        );    }    /**      * Get the settings option array and print one of its values     */    public function font_family_callback($value){        $fonts = array('Open Sans', 'Droid Sans', 'Lato', 'Bitter', 'Helvetica', 'Georgia', 'Trebuchet MS');        $value = isset( $this->general_settings[$value] ) ? esc_attr( $this->general_settings[$value]) : '';        echo '<select id="pi_font_family" name="'.$this->general_settings_key.'[pi_font_family]" value="true">';        foreach ($fonts as $key => $font) {            echo '<option value="' . $font . '"';            if ( $font === $value) {                echo '" selected="selected"';            }            echo '>' . $font . '</option>';        }    }    /**      *  General Color Picker     */        public function pi_color_picker( $value ) {            printf(            '<input type="text" name="'.$this->general_settings_key.'['. $value .']" value="%s" class="pi-color-picker" >',            isset( $this->general_settings[$value] ) ? $this->general_settings[$value] : ''        );       }    /**      * Get the settings option array and print one of its values     */    public function general_text_callback($value){        printf(            '<input type="text" id="' . $value . '" name="'. $this->general_settings_key .'['. $value .']" value="%s"/>',            isset( $this->general_settings[$value] ) ? esc_attr( $this->general_settings[$value] ) : ''        );    }    /**      * Choose wether sidebar will be on the right or left. For page     */    public function general_sidebar_position_callback($value){        $value = isset( $this->general_settings[$value] ) ? esc_attr( $this->general_settings[$value]) : '';        $sides = array('none', 'right', 'left');        echo '<select id="sidebar_general_position" name="'.$this->general_settings_key.'[sidebar_general_position]" value="true">';            foreach ($sides as $key => $side) {                echo '<option value="' . $side . '"';                if ( $side ===  $value ) {                    echo '" selected="selected"';                }                echo '>' . $side . '</option>';             }         echo '</select>';    }    /**      * Get the settings option array and print one of its values     */    public function main_image_callback_general($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->general_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->general_settings[$value] ) ? esc_url( $this->general_settings[$value]) : '',            isset( $this->general_settings[$value] ) ? esc_url( $this->general_settings[$value]) : ''        );    }    /**      * Get the settings option array and print one of its values     */    public function text_callback($value){        printf(            '<input type="text" id="'. $value .'" name="'. $this->homepage_settings_key .'['. $value .']" value="%s"/>',            isset( $this->homepage_settings[$value] ) ? esc_attr( $this->homepage_settings[$value] ) : ''        );    }    /**      * Get the settings option array and print one of its values     */    public function textarea_callback( $value ){        printf(            '<textarea type="text" id="'. $value .'" name="'. $this->homepage_settings_key  .'['. $value .']" class="pi-textarea">%s</textarea>',            isset( $this->homepage_settings[$value] ) ? esc_attr( $this->homepage_settings[$value]) : ''        );    }    /**      * Choose wether form will be on top or bottom. For homepage     */    public function homeform_position_callback($value){        $value = isset( $this->homepage_settings[$value] ) ? esc_attr( $this->homepage_settings[$value]) : '';        $positions = array('top', 'bottom');        echo '<select class="form_position" name="'.$this->homepage_settings_key.'[form_position]" value="true">';            foreach ($positions as $key => $position) {                echo '<option value="' . $position . '"';                if ( $position === $value) {                    echo '" selected="selected"';                }                echo '>' . $position . '</option>';             }         echo '</select>';    }    /**      *  Feature Color Picker     */        public function home_color_picker( $value ) {            printf(            '<input type="text" name="'. $this->homepage_settings_key  .'['. $value .']" value="%s" class="pi-color-picker" >',            isset( $this->homepage_settings[$value] ) ? $this->homepage_settings[$value] : ''        );       }    /**      * Get the settings option array and print one of its values     */    public function main_image_callback($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->homepage_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->homepage_settings[$value] ) ? esc_url( $this->homepage_settings[$value]) : '',            isset( $this->homepage_settings[$value] ) ? esc_url( $this->homepage_settings[$value]) : ''        );    }    /**      * Display Image option for page     */    public function page_image_callback($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->page_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->page_settings[$value] ) ? esc_url( $this->page_settings[$value]) : '',            isset( $this->page_settings[$value] ) ? esc_url( $this->page_settings[$value]) : ''        );    }    /**      * Text callback option for Page     */    public function page_textarea_callback( $value ){        printf(            '<textarea type="text" id="'. $value .'" name="'. $this->page_settings_key  .'['. $value .']" class="pi-textarea">%s</textarea>',            isset( $this->page_settings[$value] ) ? esc_attr( $this->page_settings[$value]) : ''        );    }    /**      * Choose whether sidebar will be on the right or left. For page     */    public function sidebar_position_callback($value){        $sides = array('none', 'right', 'left');        $value = isset( $this->page_settings[$value] ) ? esc_attr( $this->page_settings[$value]) : '';        echo '<select id="sidebar_position" name="'.$this->page_settings_key.'[sidebar_position]" value="true">';            foreach ($sides as $key => $side) {                echo '<option value="' . $side . '"';                if ( $side === $value) {                    echo '" selected="selected"';                }                echo '>' . $side . '</option>';             }         echo '</select>';    }    /**      * Display Image option for form     */    public function form_image_callback($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->form_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->form_settings[$value] ) ? esc_url( $this->form_settings[$value]) : '',            isset( $this->form_settings[$value] ) ? esc_url( $this->form_settings[$value]) : ''        );    }    /**      *  Form Color Picker     */        public function form_color_picker_callback( $value ) {            printf(            '<input type="text" name="'.$this->form_settings_key.'['. $value .']" value="%s" class="pi-color-picker" >',            isset( $this->form_settings[$value] ) ? $this->form_settings[$value] : ''        );       }    /**     *  Form Color Picker     */    public function form_text_callback( $value ) {        printf(            '<input type="text" id="'. $value .'" name="'. $this->form_settings_key .'['. $value .']" size="50" value="%s"/>',            isset( $this->form_settings[$value] ) ? esc_attr( $this->form_settings[$value] ) : ''        );    }    /**      * Display Image option for footer     */    public function footer_image_callback($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->footer_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->footer_settings[$value] ) ? esc_url( $this->footer_settings[$value]) : '',            isset( $this->footer_settings[$value] ) ? esc_url( $this->footer_settings[$value]) : ''        );    }    /**      * Footer textarea render     */    public function footer_textarea_callback( $value ){        printf(            '<textarea type="text" id="'. $value .'" name="'. $this->footer_settings_key  .'['. $value .']" class="pi-textarea">%s</textarea>',            isset( $this->footer_settings[$value] ) ? esc_attr( $this->footer_settings[$value]) : ''        );    }    /**      * Get the settings option array and print one of its values     */    public function footer_text_callback($value){        printf(            '<input type="text" id="'. $value .'" name="'. $this->footer_settings_key .'['. $value .']" value="%s"/>',            isset( $this->footer_settings[$value] ) ? esc_attr( $this->footer_settings[$value] ) : ''        );    }    /**      * Choose number of columns for footer     */    public function footer_columns_callback(){           $columns = array('3', '4');        echo '<select id="footer_columns" name="'.$this->footer_settings_key.'[footer_columns]" value="true">';            foreach ($columns as $key => $column) {                echo '<option value="' . $column . '"';                if ( $column === $this->footer_settings_key . '[footer_columns]') {                    echo '" selected="selected"';                }                echo '>' . $column . '</option>';             }         echo '</select>';    }    /**      *  Footer Color Picker     */        public function footer_color_picker_callback( $value ) {            printf(            '<input type="text" name="'.$this->footer_settings_key.'['. $value .']" value="%s" class="pi-color-picker" >',            isset( $this->footer_settings[$value] ) ? $this->footer_settings[$value] : ''        );       }    /**      * CSS Code area render     */    public function css_callback( $value ){        printf(            '<textarea type="text" id="'. $value .'" name="'. $this->css_settings_key  .'['. $value .']" class="pi-textarea">%s</textarea>',            isset( $this->css_settings[$value] ) ? esc_attr( $this->css_settings[$value]) : ''        );    }    /**      * Import Code area render button     */    public function portfolio_checkbox_callback( $value ){        $abs = isset( $this->portfolio_settings[$value] ) ? esc_attr( $this->portfolio_settings[$value]) : '';        echo '<input type="checkbox" id="'.$value.'" name="'.$this->portfolio_settings_key . '[' . $value . ']" ' . ( $abs == true ? 'checked' : "" ) . '>';        }    /**     * Choose number of columns for Portfolio     */    public function portfolio_columns_callback(){        $columns = array('3', '4', '6', 'Block');        echo '<select id="footer_columns" name="'.$this->portfolio_settings_key.'[pi_col]" value="true">';        foreach ($columns as $key => $column) {            echo '<option value="' . $column . '"';            if ( $column === $this->portfolio_settings_key . '[pi_col]' ) {                echo '" selected="selected"';            }            echo '>' . $column . '</option>';        }        echo '</select>';    }    /**      * Get the settings option array and print one of its values from the Portfolio options     */    public function placeholder_image_callback_general($value){           printf(            '<span class="upload">                <input type="text" id="'. $value .'" class="regular-text text-upload" name="'. $this->portfolio_settings_key .'[' . $value . ']" value="%s"/>                <img src="%s" class="preview-upload"/>                <input type="button" class="button button-upload" value="Upload an image"/><br/>            </span>',            isset( $this->portfolio_settings[$value] ) ? esc_url( $this->portfolio_settings[$value]) : '',            isset( $this->portfolio_settings[$value] ) ? esc_url( $this->portfolio_settings[$value]) : ''        );    }    /**      * Choose wether sidebar will be on the right or left. For page     */    public function port_sidebar_position_callback($value){        $value = isset( $this->portfolio_settings[$value] ) ? esc_attr( $this->portfolio_settings[$value]) : '';        $sides = array('none', 'right', 'left');        echo '<select id="sidebar_port_position" name="'.$this->portfolio_settings_key.'[sidebar_port_position]" value="true">';            foreach ($sides as $key => $side) {                echo '<option value="' . $side . '"';                if ( $side === $value) {                    echo '" selected="selected"';                }                echo '>' . $side . '</option>';             }         echo '</select>';    }    public function pi_admin_notice($boolean = false) {        if ($boolean == true) :            $output = '';            $items = 20;            $count_posts = wp_count_posts('pi_portfolio');            $current_list = $count_posts->publish;            $dir = get_theme_root() . '/PiPhotography/assets/demo/img/';            $images = glob($dir . "*.jpg");            $img_index = $images[0];            $img_dir = wp_upload_dir()['path'];            $img_url = PIMAIN . '/' . substr($img_index, strpos($img_index, "assets"));            //echo $img_url;            $array = explode("/", $img_url);            $new_filename = end($array);            //echo $img_dir . '/' . $new_filename;            $img_dir = wp_upload_dir()['path'];            if (@fclose(@fopen($img_url, "r")))                copy($img_url, $img_dir .'/'. $new_filename);            ob_start();            ?>            <?php if( $items > $current_list ) : ?>                <div class="pi-notice">                    <ul>                        <li> <strong>Make Things Easy!</strong> </li>                        <li>Simply click here and the Theme will be Populated with the <strong>Demo Version</strong></li>                    </ul>                    <p class="green"><span id="status-number"><?php echo $current_list; ?></span>  of <strong> <?php echo $items; ?></strong> Portfolio Items Created </p>                    <p id="progress-bar"><span class="percent"></span></p>                    <button id="demo-theme">Click for Demo</button>                    <input type="hidden" id="total-files" value="<?php echo $items; ?>">                </div>            <?php endif;            $output .= ob_get_contents();            ob_end_clean();        endif;        return $output;    }}    