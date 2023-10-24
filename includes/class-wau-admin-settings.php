<?php
/**
 * WooCommerce Addon Uploads Admin Settings Class
 *
 * Contains all admin settings functions and hooks
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if ( ! class_exists( 'wau_admin_settings_class' ) ) {

	/**
	 * Settings Class.
	 */
	class wau_admin_settings_class {

		/**
		 * Constructor.
		 */
		public function __construct(){
			add_action( 'admin_init', array( $this, 'addon_settings_api_init' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'wau_enqueue_scripts' ), 10 );
		}

		/**
		 * Enqueue Select2 scripts needed for settings.
		 *
		 * @return void
		 */
		public function wau_enqueue_scripts() {
			wp_enqueue_style(
				'bkap-woocommerce_admin_styles',
				plugins_url() . '/woocommerce/assets/css/admin.css',
				'',
				'2.0.0',
				false
			);

			wp_register_script(
				'select2',
				plugins_url() . '/woocommerce/assets/js/select2/select2.min.js',
				array( 'jquery', 'jquery-ui-widget', 'jquery-ui-core' ),
				'2.0.0',
				false
			);

			wp_enqueue_script( 'select2' );
		}

		/**
		 * Settings API init
		 */
		public function addon_settings_api_init() {

			register_setting( 'addon_settings', 'wau_addon_settings' );
			register_setting( 'wau_category_settings', 'wau_addon_settings' );

			add_settings_section(
				'wau_addon_settings_section',
				'',
				array( $this, 'addon_settings_callback' ),
				'addon_settings'
			);

			add_settings_field(
				'wau_settings_enable',
				__( 'Enable Addon Uploads', 'woo-addon-uploads' ),
				array( $this, 'wau_settings_enable_renderer' ),
				'addon_settings',
				'wau_addon_settings_section'
			);

			add_settings_field(
				'wau_settings_categories',
				__( 'Product Categories', 'woo-addon-uploads' ),
				array( $this, 'wau_settings_categories_renderer' ),
				'addon_settings',
				'wau_addon_settings_section'
			);
		}

		/**
		 * Call back to display Settings Section information.
		 */
		public function addon_settings_callback() {
			echo esc_html_e( 'Configure your Settings', 'woo-addon-uploads' );
		}

		/**
		 * Display HTML for settings.
		 */
		public function wau_settings_enable_renderer() {

			$options = get_option( 'wau_addon_settings' );
			$checked = '';
			if ( isset( $options['wau_enable_addon'] ) ) {
				$checked = checked( $options['wau_enable_addon'], 1, false );
			}
			?>

			<div class="row">
				<input type='checkbox' 
					id="wau_addon_settings[wau_enable_addon]" 
					name="wau_addon_settings[wau_enable_addon]"
					<?php echo esc_attr( $checked ); ?>
					value='1'>
				<label for="wau_addon_settings[wau_enable_addon]">
					<?php esc_html_e( 'Enable Addon Uploads on Product Page', 'woo-addon-uploads' ); ?>
				</label>
			</div>
			<?php
		}

		/**
		 * Display HTML for Catgories Setting.
		 */
		public function wau_settings_categories_renderer() {

			$options       = get_option( 'wau_addon_settings' );
			$selected_cats = array();
			if ( isset( $options['wau_settings_categories'] ) && ! empty( $options['wau_settings_categories'] ) ) {
				$selected_cats = $options['wau_settings_categories'];
			}

			$args = array(
				'taxonomy'   => 'product_cat',
				'number'     => 0,
				'hide_empty' => false,
				'fields'     => 'id=>name',
			);

			$product_categories = get_terms( $args );
			?>

			<div class="row">
				<select id="wau_addon_settings[wau_settings_categories][]" name="wau_addon_settings[wau_settings_categories][]" multiple="multiple" class="wau_category_select">
					<option value='all' <?php echo esc_attr( in_array( 'all', $selected_cats ) ? 'selected=selected' : '' ); ?>><?php esc_attr_e( 'All', 'woo-addon-uploads' ); ?></option>
					<?php
					foreach ( $product_categories as $cat_key => $cat_value ) {
						?>
						<option value="<?php echo esc_attr( $cat_key ); ?>" <?php echo esc_attr( in_array( $cat_key, $selected_cats ) ? 'selected=selected' : '' ); ?>>
							<?php echo esc_attr( $cat_value ); ?>
						</option>
						<?php
					}
					?>
				</select>
				</br>
				<label for="wau_addon_settings[wau_enable_addon][]">
					<?php esc_html_e( 'Select the Product Catgories for which you want to allow file upload', 'woo-addon-uploads' ); ?>
				</label>
				<script>
					jQuery(".wau_category_select").select2({
						allowClear: false,
						width: '40%',
						placeholder: "Select Categories.."
					});
				</script>
			</div>
			<?php
		}

		/**
		 * Display Settings and Save Button
		 */
		public function load_addon_settings(){

			settings_fields( 'addon_settings' );
			do_settings_sections( 'addon_settings' );
			submit_button();
			?>
				<div>
					<p>
						Looking for more settings? <a href="https://imaginate-solutions.com/downloads/woocommerce-addon-uploads/" target="blank">Upgrade to Pro</a> and get access to features such as multiple file uploads, file size restrictions, image height/width restrictions and more.
					</p>
				</div>
			<?php
		}
	}
}
