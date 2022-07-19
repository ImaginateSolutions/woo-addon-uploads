<?php
/**
 * WooCommerce Addon Uploads Admin Class
 *
 * Loads and executes all admin functions and hooks
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if ( ! class_exists( 'wau_admin_class' ) ) {

	/**
	 * Addon Uploads Admin Class.
	 */
	class wau_admin_class {

		/**
		 * Default constructor function.
		 */
		public function __construct(){

			$this->load_admin_dependencies();

			// WordPress Administration Menu.
			add_action( 'admin_menu', array( &$this, 'addon_upload_settings_menu' ) );

		}

		/**
		 * Functions
		 */

		/**
		 * Load dependencies
		 */
		public function load_admin_dependencies(){

			require_once 'class-wau-admin-settings.php';

			$this->wau_admin_settings_class = new wau_admin_settings_class();
		}

		/**
		 * Addon Settings Menu in admin
		 */
		public function addon_upload_settings_menu(){

			add_menu_page(
				'Addon Upload Settings',
				'Addon Upload Settings',
				'manage_woocommerce',
				'addon_settings_page'
			);
			add_submenu_page(
				'addon_settings_page.php',
				__( 'Addon Upload Settings', 'woo-addon-uploads' ),
				__( 'Addon Upload Settings', 'woo-addon-uploads' ),
				'manage_woocommerce',
				'addon_settings_page',
				array( &$this, 'addon_settings_page' )
			);

		}

		/**
		 * Addon Settings Page
		 */
		public function addon_settings_page(){
			?>
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<a href="admin.php?page=image_settings_page" class="nav-tab nav-tab-active"> 
						<?php esc_html_e( 'Addon Upload Settings', 'woo-addon-uploads' ); ?> 
					</a>
				</h2>

				<?php settings_errors(); ?>

				<form action='options.php' method='post'>

					<h2><?php esc_html_e( 'Settings', 'woo-addon-uploads' ); ?></h2>

					<?php $this->wau_admin_settings_class->load_addon_settings(); ?>

				</form>
			<?php
		}

	}

}