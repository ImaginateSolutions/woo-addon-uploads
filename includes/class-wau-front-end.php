<?php
/**
 * WooCommerce Addon Uploads Admin Settings Class
 *
 * Contains all admin settings functions and hooks
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if ( ! class_exists( 'wau_front_end_class' ) ) {

	class wau_front_end_class {

		public function __construct() {

			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			$this->load_scripts();
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'addon_uploads_section' ), 999 );

			add_filter( 'woocommerce_add_cart_item_data', array( $this, 'wau_add_cart_item_data' ), 10, 2 );
			add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'wau_get_cart_item_from_session' ), 10, 2 );
			add_filter( 'woocommerce_get_item_data', array( $this, 'wau_get_item_data' ), 10, 2 );
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'wau_add_item_meta_url' ), 10, 4 );

			add_filter( 'wau_category_checks', array( $this, 'wau_check_category_allowed' ), 10, 2 );

			add_action( 'woocommerce_cart_item_removed', array( $this, 'wau_remove_cart_action' ), 10, 2 );
		}

		function load_scripts(){
			add_action( 'woocommerce_before_single_product', array( $this, 'wau_front_end_scripts_js' ) );
			add_action( 'woocommerce_before_single_product', array( $this, 'wau_front_end_scripts_css' ) );
		}

		function wau_front_end_scripts_js() {
			if ( is_product() ) {
				//wp_enqueue_script( 'wau_upload_js', plugins_url('../assets/js/wau_upload_script.js', __FILE__), '', '', false);
				//wp_localize_script( 'wau_upload_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			}
		}

		function wau_front_end_scripts_css(){

			if ( is_product() ){
				wp_enqueue_style( 'wau_upload_css', plugins_url('../assets/css/wau_styles.css', __FILE__ ) , '', '', false );
			}
		}

		function addon_uploads_section() {

			global $product;

			$addon_settings = get_option( 'wau_addon_settings' );

			$product_ids = apply_filters( 'wau_include_product_ids', array() );

			$category_passed = apply_filters( 'wau_category_checks', true, $product );

			$enabled = false;
			if ( ( is_array( $product_ids ) && empty( $product_ids ) ) || in_array( $product->get_id(), $product_ids, true ) ) {
				$enabled = true;
			}

			if ( isset( $addon_settings['wau_enable_addon'] ) && '1' === $addon_settings['wau_enable_addon'] && $enabled && $category_passed ) {

				$upload_label = __( 'Upload an image: ', 'woo-addon-uploads' );

				$file_upload_template =
					'<div class="wau_wrapper_div">
						<label for="wau_file_addon">' . $upload_label . '</label>
						<input type="file" name="wau_file_addon" id="wau_file_addon" accept="image/*" class="wau-auto-width wau-files" />
					</div>';
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

		public function is_woocommerce_block_present() {
			$post = get_post();

			// This condition will appear for ajax calls on the checkout page.
			if ( is_null( $post ) ) {
				return true;
			}

			if ( ! has_blocks( $post->post_content ) ) {
				return false;
			}
			$blocks      = parse_blocks( $post->post_content );
			$block_names = array_map(
				function ( $block ) {
					return $block['blockName'];
				},
				$blocks
			);

			return in_array(
				'woocommerce/cart',
				$block_names,
				true
			) ||
			in_array(
				'woocommerce/checkout',
				$block_names,
				true
			);
		}

		function wau_get_item_data( $other_data, $cart_item ) {
			if ( isset( $cart_item['wau_addon_ids'] ) ) {
				foreach ( $cart_item['wau_addon_ids'] as $addon_id ) {
					$block_present = $this->is_woocommerce_block_present();
					if ( $block_present ) {
						$name    = __( 'Uploaded File', 'woo-addon-uploads' );
						$display = '&#9989;';
					} else {
						$name    = __( 'Uploaded File', 'woo-addon-uploads' );
						$display = $addon_id['media_id'];
						$display = wp_get_attachment_image( $display, 'thumbnail', 'true', '' );
					}

					$other_data[] = array(
						'name'    => $name,
						'display' => $display,
					);
				}
			}

			return $other_data;
		}

		function wau_add_item_meta_url( $item, $cart_item_key, $values, $order ) {

			if ( empty( $values['wau_addon_ids'] ) ) {
				return;
			}

			foreach ( $values['wau_addon_ids'] as $addon_key => $addon_id ) {
				$media_url = wp_get_attachment_url( esc_attr( $addon_id['media_id'] ) );

				$item->add_meta_data( __( 'Uploaded Media', 'woo-addon-uploads' ), $media_url );
			}
		}

		function wau_remove_cart_action( $cart_item_key, $cart ) {
			$removed_item = $cart->removed_cart_contents[ $cart_item_key ];

			if ( isset( $removed_item['wau_addon_ids'] ) && isset( $removed_item['wau_addon_ids'][0] ) &&
					isset( $removed_item['wau_addon_ids'][0]['media_id'] ) && $removed_item['wau_addon_ids'][0]['media_id'] !== '' ) {

				$media_id = $removed_item['wau_addon_ids'][0]['media_id'];

				$delete_status = wp_delete_attachment( $media_id, true );
			}
		}

		/**
		 * Check if part of allowed categories.
		 *
		 * @param bool       $allowed
		 * @param WC_Product $product
		 * @return bool
		 */
		public function wau_check_category_allowed( $allowed, $product ) {

			$addon_settings     = get_option( 'wau_addon_settings' );
			$allowed_categories = isset( $addon_settings['wau_settings_categories'] ) ? $addon_settings['wau_settings_categories'] : array();
			$product_cats       = $product->get_category_ids();

			if ( empty( $allowed_categories ) || in_array( 'all', $allowed_categories, true ) ) {
				return true;
			}

			$match_cats = array_intersect( $product_cats, $allowed_categories );

			if ( empty( $match_cats ) ) {
				return false;
			} else {
				return true;
			}
		}
	}
}