<?php
 
class CDP_Shortcodes_Admin {
 
    protected $version;
 
    public function __construct( $version ) {
        $this->version = $version;
        $this->define_cdp_shortcodes();
        $this->load_cdp_shortcodes();
    }
 
    public function enqueue_styles() {
        //wp_enqueue_style( 'cdp-shortcodes-admin', plugin_dir_url( __FILE__ ) . 'css/cdp-shortcodes-admin.css', array(), $this->version, FALSE );
    }

    public function enqueue_scripts() {
        //wp_enqueue_script('cdp-shortcodes-admin', plugin_dir_url(__FILE__) . 'js/cdp-shortcodes-admin.js');
    }

    public function define_cdp_shortcodes() {
        $this->shortcodesArray = array('Course', 'Article_Feed');
        //Todo: This URL should be stored elsewhere.
        $this->modulesSrc = 'http://iip-design-stage-modules.s3-website-us-east-1.amazonaws.com/modules/';
    }

    public function load_cdp_shortcodes() {
        foreach($this->shortcodesArray as $shortcode) {
            $shortcode = 'cdp-' . strtolower(str_replace( '_', '-', $shortcode));
            add_shortcode($shortcode, array($this, 'cdp_shortcodes_output'));
        }
    }
    
    public function load_cdp_styles_scripts() {
        //Todo: This should be moved outside admin.
        global $post;
        foreach($this->shortcodesArray as $shortcode) {
            $shortcode = 'cdp-' . strtolower(str_replace( '_', '-', $shortcode));
            $src = $this->modulesSrc . $shortcode;
            wp_register_style($shortcode, $src . '.min.css');
            wp_register_script($shortcode, $src . '.min.js');
            if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $shortcode) ) {
                wp_enqueue_script($shortcode);
                wp_enqueue_style($shortcode);
            }
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

    public function cdp_shortcodes_output($atts, $content = "", $shortcode) {

        $uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );

        $shortcode = "<div id='$uuid' rel='$shortcode' ";
        foreach($atts as $key => $val){
            $shortcode .= "data-$key=$val ";
        }
        $shortcode .= "></div>";

        return $shortcode;
    }

    public function cdp_shortcodes_add_buttons( $plugin_array ) {
        $plugin_array['cdp_shortcodes'] = plugin_dir_url( __FILE__ )  . 'js/src/tinymce/cdp.tinymce.plugins.min.js';
        return $plugin_array;
    }
    
    public function cdp_shortcodes_register_buttons( $buttons ) {
        array_push($buttons, 'cdp_shortcode');
        return $buttons;
    } 
 
}