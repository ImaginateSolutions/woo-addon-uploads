<?php

/**
 * WooCommerce Addon Uploads Locale
 *
 * Loads and defines text domain for the plugin
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if( ! class_exists( 'wau_locale_class' ) ){
  
  class wau_locale_class {
    
    /*public __construct(){
      
      $this->load_plugin_textdomain();
      
    }*/
    
    public function load_plugin_textdomain(){
      
      load_plugin_textdomain( 'woo-addon-uplds', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages' );
      
    }
    
  }
  
}