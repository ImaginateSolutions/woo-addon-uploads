<?php

/**
 * WooCommerce Addon Uploads Admin Settings Class
 *
 * Contains all admin settings functions and hooks
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if( ! class_exists( 'wau_front_end_class' ) ){
  
  class wau_front_end_class {
    
    public function __construct(){
      
      require_once( ABSPATH . 'wp-admin/includes/image.php' );
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
      require_once( ABSPATH . 'wp-admin/includes/media.php' );
      
      $this->load_scripts();
      add_action( 'woocommerce_before_add_to_cart_button' , array(&$this, 'addon_uploads_section') );
      
      add_filter( 'woocommerce_add_cart_item_data', array(&$this, 'wau_add_cart_item_data'), 10, 2 );
      add_filter( 'woocommerce_get_cart_item_from_session', array(&$this, 'wau_get_cart_item_from_session'), 10, 2 );
      add_filter( 'woocommerce_get_item_data', array(&$this, 'wau_get_item_data'), 10, 2 );
      add_action( 'woocommerce_new_order_item', array(&$this, 'wau_add_item_meta_url'), 10, 3 );
      
      add_action( 'woocommerce_cart_item_removed', array(&$this, 'wau_remove_cart_action'), 10, 2 );
      
    }
    
    function load_scripts(){
      
      add_action( 'woocommerce_before_single_product', array(&$this, 'wau_front_end_scripts_js') );
      add_action( 'woocommerce_before_single_product', array(&$this, 'wau_front_end_scripts_css') );
      
    }
    
    function wau_front_end_scripts_js(){
      
      if( is_product() ){
        //wp_enqueue_script( 'wau_upload_js', plugins_url('../assets/js/wau_upload_script.js', __FILE__), '', '', false);
        //wp_localize_script( 'wau_upload_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
      }
      
    }
    
    function wau_front_end_scripts_css(){
      
      if( is_product() ){
        wp_enqueue_style( 'wau_upload_css', plugins_url('../assets/css/wau_styles.css', __FILE__ ) , '', '', false);
      }
      
    }
    
    function addon_uploads_section(){
      
      $addon_settings = get_option( 'wau_addon_settings' );
      
      if( isset($addon_settings['wau_enable_addon']) && $addon_settings['wau_enable_addon'] === '1' ){
        $file_upload_template = 
          '<div>' . _e( 'Upload an image:', 'woo-addon-uplds' ) . '<input type="file" name="wau_file_addon" id="wau_file_addon" accept="image/*" class="wau-auto-width wau-files" /></div>';
        echo $file_upload_template;
      }
      
    }
    
    function wau_add_cart_item_data( $cart_item_meta, $product_id ){
      
      $addon_id = array();
      
      if( isset( $_FILES ) && isset( $_FILES['wau_file_addon'] ) && $_FILES['wau_file_addon']['name'] !== '' ){
        $attach_id = media_handle_upload( 'wau_file_addon', 0 );
        
        $addon_id['media_id'] = $attach_id;
        $addon_id['media_url'] = wp_get_attachment_url( esc_attr( $attach_id ) );
        $cart_item_meta['wau_addon_ids'][] = $addon_id;
      }
      
      return $cart_item_meta;
      
    }
    
    function wau_get_cart_item_from_session( $cart_item, $values ){
      
      if( isset( $values['wau_addon_ids'] ) ){
        $cart_item['wau_addon_ids'] = $values['wau_addon_ids'];
      }
      
      return $cart_item;
    }
    
    function wau_get_item_data( $other_data, $cart_item ){
      
      if( isset( $cart_item['wau_addon_ids'] ) ){
        
        foreach( $cart_item['wau_addon_ids'] as $addon_id ){
          //$name = _e( 'Uploaded File', 'woo-addon-uplds' );
          $display = $addon_id['media_id'];
          $other_data[] = array(
            'name'    => 'Uploaded File',
            'display' => wp_get_attachment_image( $display, 'thumbnail', 'true', '' )
          );
        }
        
      }
      
      return $other_data;
      
    }
    
    function wau_add_item_meta_url( $item_id, $values, $order_id ){

      global $woocommerce;
      
      foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ){
        
        if ( isset( $values['wau_addon_ids'] ) ){
          
          foreach( $values['wau_addon_ids'] as $addon_id ){
            
            $media_url = wp_get_attachment_url( esc_attr($addon_id['media_id']) );
            wc_add_order_item_meta( $item_id, 'Uploaded Media', $media_url, false );
            
          }
          
        }
        
      }
      
    }
    
    
    function wau_remove_cart_action( $cart_item_key, $cart ){
      
      $removed_item = $cart->removed_cart_contents[$cart_item_key];
      
      if( isset( $removed_item['wau_addon_ids'] ) && isset( $removed_item['wau_addon_ids'][0] ) && 
          isset( $removed_item['wau_addon_ids'][0]['media_id'] ) && $removed_item['wau_addon_ids'][0]['media_id'] !== '' ){
        
        $media_id = $removed_item['wau_addon_ids'][0]['media_id'];
        
        $delete_status = wp_delete_attachment( $media_id, true );
        
      }
      
    }
    
  }
   
}