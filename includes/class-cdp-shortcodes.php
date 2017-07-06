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
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cdp-shortcodes-admin.php';
        require_once plugin_dir_path( __FILE__ ) . 'class-cdp-shortcodes-loader.php';
        $this->loader = new CDP_Shortcodes_Loader();
    }

    private function define_admin_hooks() {
        
        $admin = new CDP_Shortcodes_Admin( $this->get_version() );

        // load actions
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_footer', $admin, 'cdp_shortcodes_get_shortcodes' );

        // load filters
        $this->loader->add_filter( 'mce_external_plugins', $admin, 'cdp_shortcodes_add_buttons' );
        $this->loader->add_filter( 'mce_buttons', $admin, 'cdp_shortcodes_register_buttons' );
    
    }
 
    public function run() {
        $this->loader->run();
    }
 
    public function get_version() {
        return $this->version;
    }

}

?>