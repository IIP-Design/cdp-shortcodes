<?php

class CDP_Shortcodes {
    
    protected $loader;
 
    protected $plugin_slug;
 
    protected $version;
 
    public function __construct() {
        $this->plugin_slug = 'cdp-shortcodes';
        $this->version = '0.0.1';
 
        $this->load_dependencies();
        $this->define_admin_hooks();
 
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/webdevstudios/cmb2/init.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/jtsternberg/Shortcode_Button/shortcode-button.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cdp-shortcodes-admin.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-cdp-shortcodes-loader.php';
        $this->loader = new CDP_Shortcodes_Loader();
    }

    private function define_admin_hooks() {
        
        $admin = new CDP_Shortcodes_Admin( $this->get_version() );

        // load actions
        //$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
        //$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
        //$this->loader->add_action( 'wp_enqueue_scripts', $admin, 'load_cdp_styles_scripts');
        //$this->loader->add_action( 'admin_footer', $admin, 'cdp_shortcodes_get_shortcodes' );
        $this->loader->add_action( 'shortcode_button_load', $admin, 'init_my_shortcode_button', ( SHORTCODE_BUTTONS_LOADED + 1 ) );

        // load filters
        //$this->loader->add_filter( 'mce_external_plugins', $admin, 'cdp_shortcodes_add_buttons' );
        //$this->loader->add_filter( 'mce_buttons', $admin, 'cdp_shortcodes_register_buttons' );
        $this->loader->add_filter( 'shortcode_button_assets_url', $admin, 'change_assets_url' );
    
    }
 
    public function run() {
        $this->loader->run();
    }
 
    public function get_version() {
        return $this->version;
    }

}

?>