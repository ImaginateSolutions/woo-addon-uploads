<?php
/**
 * Plugin Name: File Uploads Addon for WooCommerce
 * Plugin URI: https://imaginate-solutions.com/
 * Description: WooCommerce addon to upload additional files before adding product to cart
 * Version: 1.5.0
 * Author: Imaginate Solutions
 * Author URI: https://imaginate-solutions.com
 *
 * Text Domain: woo-addon-uploads
 * Domain Path: /i18n/languages/
 *
 * Requires PHP: 5.6
 * WC requires at least: 3.0.0
 * WC tested up to: 6.7
 *
 * @package WooCommerce Addon Uploads
 * @author Dhruvin Shah
 */

if ( ! class_exists( 'woo_add_uplds' ) ) {

	/**
	 * Addon Uploads Class.
	 */
	class woo_add_uplds {

		/**
		 * WooCommerce Addon Uploads.
		 *
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name = 'WooCommerce Addon Uploads';

		/**
		 * Version 1.0.0
		 *
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version = '1.5.0';

		/**
		 * Default construtor function.
		 */
		public function __construct() {
			if (
				! $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ||
				( 'woocommerce-addon-uploads.php' === basename( __FILE__ ) && $this->is_plugin_active( 'woocommerce-addon-uploads-pro/woocommerce-addon-uploads-pro.php' ) )
				) {
				return;
			}
			$this->define_constants();
			$this->load_dependencies();
			$this->set_locale();

			$this->load_admin_settings();
			$this->front_end_actions();
		}

		/**
		 * Is plugin active.
		 *
		 * @param   string $plugin Plugin Name.
		 * @return  bool
		 * @version 1.6.0
		 * @since   1.6.0
		 */
		public function is_plugin_active( $plugin ) {
			return ( function_exists( 'is_plugin_active' ) ? is_plugin_active( $plugin ) :
			(
				in_array( $plugin, apply_filters( 'active_plugins', (array) get_option( 'active_plugins', array() ) ), true ) ||
				( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
			)
			);
		}

		/**
		 * Define constants
		 */
		private function define_constants() {
			$upload_dir = wp_upload_dir();
			$this->define( 'WAU_PLUGIN_FILE', __FILE__ );
			$this->define( 'WAU_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );
		}

		/**
		 * Define constant if not already set.
		 *
		 * @param  string $name
		 * @param  string|bool $value
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Load dependencies
		 */
		private function load_dependencies(){
			require_once 'includes/class-wau-admin.php';
			require_once 'includes/class-wau-front-end.php';
		}

		/**
		 * Set Locale
		 */
		private function set_locale() {
			load_plugin_textdomain( 'woo-addon-uploads', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/languages/' );
		}

		/**
		 * Load Admin Settings
		 */
		private function load_admin_settings() {

			$admin_class = new wau_admin_class();
		}

		/**
		 * Load Front End Actions
		 */
		private function front_end_actions() {

			$front_end_class = new wau_front_end_class();
		}

	}

}

$woo_add_uplds = new woo_add_uplds();
