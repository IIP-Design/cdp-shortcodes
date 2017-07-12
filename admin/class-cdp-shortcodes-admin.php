<?php
 
class CDP_Shortcodes_Admin {
 
    protected $version;
 
    public function __construct( $version ) {
        $this->version = $version;
        $this->load_dependencies();
        $this->define_cdp_shortcodes();
        $this->load_cdp_shortcodes();
    }
 
    public function enqueue_styles() {
        //wp_enqueue_style( 'cdp-shortcodes-admin', plugin_dir_url( __FILE__ ) . 'css/cdp-shortcodes-admin.css', array(), $this->version, FALSE );
    }

    public function enqueue_scripts() {
        //wp_enqueue_script('cdp-shortcodes-admin', plugin_dir_url(__FILE__) . 'js/cdp-shortcodes-admin.js');
    }

    public function load_dependencies() {
        spl_autoload_register( array( $this, 'cdp_shortcodes_autoloader' ) );
    }

    public function define_cdp_shortcodes() {
        $this->shortcodesArray = array('Course', 'Article_Feed');
    }

    public function load_cdp_shortcodes() {
        foreach($this->shortcodesArray as $shortcode) {
            $class = 'CDP_Shortcodes_' . $shortcode;
            $this->shortcodes[$class] = new $class();
        }
    }

    public function cdp_shortcodes_autoloader( $class_name ) {
        if ( false !== strpos( $class_name, 'CDP_Shortcodes' ) ) {
            $classes_dir = plugin_dir_path( __FILE__ ) . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR;
            $class_file = 'class-' . strtolower(str_replace( '_', '-', $class_name )) . '.php';
            require_once $classes_dir . $class_file;
        }
    }
    
    public function cdp_shortcodes_get_shortcodes () {
        if( !empty($this->shortcodesArray) ) {
            
            echo '<script type="text/javascript">
            var cdp_menu = new Object();';
            foreach( $this->shortcodesArray as $item )  {
                $sc = 'cdp_' . strtolower($item);
                $item = str_replace( '_', ' ', $item );
                echo "cdp_menu['{$sc}'] = '{$item}';";
            }
            echo '</script>';
        }
    }

    function cdp_shortcodes_add_buttons( $plugin_array ) {
        $plugin_array['cdp_shortcodes'] = plugin_dir_url( __FILE__ )  . 'js/src/tinymce/cdp.tinymce.plugins.min.js';
        return $plugin_array;
    }
    function cdp_shortcodes_register_buttons( $buttons ) {
        array_push($buttons, 'cdp_shortcode');
        return $buttons;
    } 
 
}