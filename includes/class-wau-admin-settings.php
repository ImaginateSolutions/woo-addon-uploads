<?php

/**
 * WooCommerce Addon Uploads Admin Settings Class
 *
 * Contains all admin settings functions and hooks
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if( ! class_exists( 'wau_admin_settings_class' ) ){
  
  class wau_admin_settings_class {
    
    public function __construct(){
      
      add_action( 'admin_init', array(&$this, 'addon_settings_api_init' ) );
    }
    
    /**
     * Settings API init
     */
    function addon_settings_api_init(){
      
      register_setting( 'addon_settings', 'wau_addon_settings' );
      add_settings_section(
        'wau_addon_settings_section', 
        _e( '', 'woo-addon-uplds' ), 
        array(&$this, 'addon_settings_callback'), 
        'addon_settings'
      );

      add_settings_field( 
        'wau_settings_enable', 
        __( 'Enable Addon Uploads', 'woo-addon-uplds' ), 
        array(&$this, 'wau_settings_enable_renderer'), 
        'addon_settings', 
        'wau_addon_settings_section' 
      );

    }
    
    /**
     * Call back to display Settings Section information
     */
    function addon_settings_callback(){
      
      echo _e( 'Configure your Settings', 'woo-addon-uplds' );
      
    }
    
    /**
     * Display HTML for settings
     */
    function wau_settings_enable_renderer(){
      
      $options = get_option( 'wau_addon_settings' );
      $checked = '';
      if( isset( $options['wau_enable_addon'] ) ){
        $checked = checked( $options['wau_enable_addon'], 1, false );
      }
      ?>
      
      <div class="row">
        <input type='checkbox' 
               id="wau_addon_settings[wau_enable_addon]" 
               name="wau_addon_settings[wau_enable_addon]" <?php echo $checked; ?> value='1'>
        <label for="wau_addon_settings[wau_enable_addon]">
          <?php _e( 'Enable Addon Uploads on Product Page', 'woo-addon-uplds' );?>
        </label>
      </div>
      
      <?php
    }
    
    /**
     * Display Settings and Save Button
     */
    function load_addon_settings(){
      
      settings_fields( 'addon_settings' );
      do_settings_sections( 'addon_settings' );
      submit_button();
      
    }
    
  }
  
}