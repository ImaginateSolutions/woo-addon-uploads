<?php
/**
 * WooCommerce Addon Uploads Pro Features Class
 *
 * Contains all the pro features and other plugin information
 *
 * @author      Dhruvin Shah
 * @package     WooCommerce Addon Uploads
 */

if ( ! class_exists( 'Wau_Pro_Features' ) ) {

	/**
	 * This class contains pro features of WooCommerce addon uploads
	 */
	class Wau_Pro_Features {

		/**
		 * This function takes a callback to enqueue scripts
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
		}

		/**
		 * This functions enqueues styles
		 */
		public function enqueue_scripts_and_styles( $hook_suffix ) {
			if ( 'addon-upload-settings_page_addon_pro_page' === $hook_suffix ) {
				wp_register_style( 'pro-feature-css', plugins_url( '../assets/css/wau_features.css', __FILE__ ), array(), '1.6.0', 'all' );
				wp_enqueue_style( 'pro-feature-css' );
			}
		}

		/**
		 * This function gets json data and show that as in html template
		 */
		public static function pro_features_callback() {
			$str = wp_safe_remote_get( 'https://imaginate-solutions.com/wp-content/uploads/addon/addon_lite.json' );

			$json = array();
			if ( ! is_wp_error( $str ) && wp_remote_retrieve_response_code( $str ) === 200 ) {
				$json = json_decode( wp_remote_retrieve_body( $str ), true );
			}
			?>

			<?php if ( isset( $json['plugin_header'] ) ) { ?>
				<section class="top-section plugin_header">
					<div class="header-part">
						<div class="header-left">
							<img src=" <?php echo esc_url( $json['plugin_header']['image'] ); ?> " alt="">
						</div>
						<div class="header-right">
							<h1><?php echo esc_html( $json['plugin_header']['title'] ); ?></h1>
						</div>
					</div>
				</section>
			<?php } ?>

			<?php if ( isset( $json['promotional'] ) ) { ?>
				<section class="promotional sale-discount">
					<div class="sale-sub-part">
						<div class="cart-img">
							<img src=" <?php echo esc_url( $json['promotional']['image'] ); ?> " alt="">
						</div>
						<div class="sale-info">
							<h1><?php echo esc_html( $json['promotional']['title'] ); ?></h1>
							<p><?php echo esc_html( $json['promotional']['sub_title'] ); ?></p>
						</div>
						<a href="<?php echo esc_url( $json['promotional']['button_link'] ); ?>" class="avail-discount" target="_blank"><?php echo esc_html( $json['promotional']['button_text'] ); ?></a>
					</div>
				</section>
			<?php } ?>

			<?php if ( isset( $json['features'] ) ) { ?>
				<section class="features">
					<div class="features-list">
						<h1><?php echo esc_html( $json['features']['title'] ); ?>:</h1>
						<ul class="all-features-list">

							<?php
							$features = isset( $json['features']['features'] ) ? $json['features']['features'] : array();

							foreach ( $features as $feature ) {
								?>
								<li><?php echo esc_html( $feature ); ?></li>
							<?php } ?>
						</ul>
					</div>
				</section>
			<?php } ?>

			<?php if ( isset( $json['img_features'] ) ) { ?>
				<section class="img-features">
					<div class="img-feature-info">
						<h1><?php echo esc_html( $json['img_features']['title'] ); ?></h1>
						<div class="feature-icon">

							<?php
							$icons = isset( $json['img_features']['features'] ) ? $json['img_features']['features'] : array();
							foreach ( $icons as $icon ) {
								?>
							<div class="each-icon">
								<img src=" <?php echo esc_url( $icon['image'] ); ?> " alt="">
								<h2><?php echo esc_html( $icon['title'] ); ?></h2>
							</div>
							<?php } ?>

						</div>
					</div>
				</section>
			<?php } ?>

			<?php if ( isset( $json['moneyback'] ) ) { ?>
				<section class="moneyback">
					<div class="moneyback-info">
						<div class="moneyback-left-img">
							<img src=" <?php echo esc_url( $json['moneyback']['image'] ); ?> " alt="">
						</div>
						<div class="moneyback-right-content">
							<h1><?php echo esc_html( $json['moneyback']['title'] ); ?></h1>
							<p><?php echo esc_html( $json['moneyback']['text'] ); ?></p>
						</div>
					</div>
				</section>
			<?php } ?>

			<section class="testimonials">
				<div class="testimonials-info">
					<?php if ( isset( $json['callback'] ) ) { ?>
					<a href="<?php echo esc_url( $json['callback']['button_link'] ); ?>" class="upgrade-to-pro" target="_blank"><?php echo esc_html( $json['callback']['button_text'] ); ?></a>
					<?php } ?>

					<?php if ( isset( $json['testimonials'] ) ) { ?>
						<div class="cards">
							<?php
							$testimonials = isset( $json['testimonials'] ) ? $json['testimonials'] : array();
							foreach ( $testimonials as $testimonial ) {
								?>
								<div class='each-card'>
									<p><?php echo esc_html( $testimonial['text'] ); ?></p>
									<div class='profile'>
										<img src=' <?php echo esc_url( $testimonial['image'] ); ?> ' alt="">
										<div class='name-designation'>
											<p class="person-name"><strong><?php echo esc_html( $testimonial['name'] ); ?></strong></p>
											<p class="person-design"><?php echo esc_html( $testimonial['designation'] ); ?></p>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</section>

			<?php if ( isset( $json['other_plugins'] ) ) { ?>
				<section class="other-plugins">
					<div class="other-plugins-info">
						<h1><?php echo esc_html( $json['other_plugins']['title'] ); ?></h1>
						<div class="other-plugin-cards">

							<?php
							$other_plugins = isset( $json['other_plugins']['plugins'] ) ? $json['other_plugins']['plugins'] : array();
							foreach ( $other_plugins as $other_plugin ) {
								?>
								<div class="other-plugin-card">
									<img src=" <?php echo esc_url( $other_plugin['image'] ); ?> " alt="">
									<h1><?php echo esc_html( $other_plugin['title'] ); ?></h1>
									<p><?php echo esc_html( $other_plugin['text'] ); ?></p>
									<a href="<?php echo esc_url( $other_plugin['button_link'] ); ?>" class="get-lugin" target="_blank"><?php echo esc_html( $other_plugin['button_text'] ); ?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</section>
			<?php } ?>

			<?php
		}

	}
}

$features = new Wau_Pro_Features();
