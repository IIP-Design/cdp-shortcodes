<?php 

class CDP_Shortcodes_Admin {
 
    protected $version;
 
    public function __construct( $version ) {
        $this->version = $version;
        $this->shortcode = 'cdp_shortcode';
        $this->modulesSrc = 'http://iip-design-stage-modules.s3-website-us-east-1.amazonaws.com/modules/';
        $this->load_cdp_shortcode();
    }

    public function init_my_shortcode_button() {

      // Set up the button data that will be passed to the javascript files
      $js_button_data = array(
        'qt_button_text' => __( 'CDP Shortcodes', 'cdp-shortcodes' ),
        'button_tooltip' => __( 'CDP Shortcodes', 'cdp-shortcodes' ),
        // Temp icon. Replace with 20x20 CDP icon
        'icon'           => 'dashicons-networking',

        // Use your own textdomain
        'l10ncancel'     => __( 'Cancel', 'cdp-shortcodes' ),
        'l10ninsert'     => __( 'Insert Shortcode', 'cdp-shortcodes' )
      );

      // Optional additional parameters
      $additional_args = array(
        // Can be a callback or metabox config array
        'cmb_metabox_config'   => array($this, 'shortcode_button_cmb_config'),
        // Set the conditions of the shortcode buttons
        'conditional_callback' => array($this, 'shortcode_button_only_admin')
      );

      $button = new Shortcode_Button( $this->shortcode, $js_button_data, $additional_args );

    }

    public function change_assets_url(){
      $file = dirname(__FILE__) . '/../vendor/jtsternberg/Shortcode_Button/shortcode-button.php';
      $plugin_url = plugin_dir_url($file);
      return $plugin_url;
    }

    /**
     * Return CMB2 config array
     *
     * @param  array  $button_data Array of button data
     *
     * @return array               CMB2 config array
     */
    public function shortcode_button_cmb_config( $button_data ) {

      return array(
        'id'     => 'cdp_shortcodes_'. $button_data['slug'],
        'fields' => array(
          array(
            'name'             => 'Choose CDP Shortcode',
            'desc'             => 'Select a CDP shortcode to insert.',
            'id'               => 'type',
            'type'             => 'select',
            'show_option_none' => false,
            'default'          => 'article-feed',
            'options'          => array(
              'article-feed' => __( 'Article Feed', 'cdp-shortcodes' ),
              //'course'   => __( 'Course', 'cdp-shortcodes' ),
              //'video'     => __( 'Video', 'cdp-shortcodes' ),
            ),
          ),
          array(
            'name'    => 'Sites',
            'desc'    => 'Choose which sites you would like to display',
            'id'      => 'sites',
            'type'    => 'multicheck',
            'options' => array(
              'share.america.gov' => 'ShareAmerica',                
              'yali.state.gov' => 'YALI',
              'ylai.state.gov' => 'YLAI'
            ),
          ),
        ),
        // keep this w/ a key of 'options-page' and use the button slug as the value
        'show_on' => array( 'key' => 'options-page', 'value' => $button_data['slug'] ),
      );

    }

    /**
     * Callback dictates that shortcode button will only display if we're on a 'page' edit screen
     *
     * @return bool Expects a boolean value
     */
    public function shortcode_button_only_admin() {
      if ( ! is_admin() ) {
        return false;
      }

      // Ok, guess we're on an admin edit screen
      return true;
    }

    public function define_cdp_shortcodes() {
        $this->shortcodesArray = array('Course', 'Article_Feed');
        //Todo: This URL should be stored elsewhere.
    }

    public function load_cdp_shortcode() {
      add_shortcode($this->shortcode, array($this, 'cdp_shortcodes_output'));
    }

    public function cdp_shortcodes_output($atts, $content = "", $shortcode) {

        $uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );

        $shortcode = "<div id='$uuid' ";

        foreach($atts as $key => $val){
          if ( $key === 'type' ) {
            $type = $val;
            $shortcode .= "rel=$type ";
          } else {
            $shortcode .= "data-$key=$val ";
          }
        }

        $shortcode .= "></div>";

        $moduleURL = $this->modulesSrc . 'cdp-module-' . $type . '/' . $type . '.min.js';
        $moduleCSS = $this->modulesSrc . 'cdp-module-' . $type . '/' . $type . '.min.css';

        $shortcode .= "<script type='text/javascript' src=$moduleURL></script>";
        $shortcode .= "<link rel='stylesheet' id=$type href=$moduleCSS type='text/css' media='all'></style>";

        return $shortcode;
    }
}

?>