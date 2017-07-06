<?php
/**
 * Plugin Name:  CDP Shortcodes
 * Plugin URI:   http://github.com/IIP-Design/cdp-shortcodes
 * Description:  A plugin that will insert shortcodes to load CDP modules.
 * Version:      0.0.1
 * Author:       Scott Gustas 
 * Author URI:   http://www.github.com/IIP-Design
 * Text Domain:  cdp-shortcodes
 */

if ( ! defined( 'WPINC' ) ) {
    die;
}
 
require_once plugin_dir_path( __FILE__ ) . 'includes/class-cdp-shortcodes.php';
 
function run_cdp_shortcodes() {
 
    $cdps = new CDP_Shortcodes();
    $cdps->run();
  
}
 
run_cdp_shortcodes();
?>