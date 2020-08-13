=== WooCommerce Addon Uploads ===
Contributors: ImagiSol, dhruvin
Donate link: https://www.paypal.me/DhruvinS
Tags: woocommerce, addon, uploads, woocommerce file upload, file upload
Requires at least: 1.4.0
Tested up to: 5.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WooCommerce Addon Uploads

== Description ==

WooCommerce Addon Uploads is a WooCommerce addon for enabling end users to upload custom image files before adding Products to Cart.

This feature helps store owners to capture additional information from their customers and helps saving considerable time in preparing the Order for customer without waiting for additional information that might be required. 

Through this plugin the end user or customer has to upload image files needed for store owners.

For any suggestions and customizations please drop in a mail at info@imaginate-solutions.com

Pro version of the plugin can be found at **[WooCommerce Addon Uploads Pro](https://imaginate-solutions.com/downloads/woocommerce-addon-uploads/)**

== Installation ==

= Automatic Installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don?t need to leave your web browser. To do an automatic install of WooCommerce Addon Uploads, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type "WooCommerce Addon Uploads" and click Search Plugins. Once you've found our plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking "Install Now".

= Manual installation =

The manual installation method involves downloading our eCommerce plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Screenshots ==

1. WooCommerce Addon Upload Admin Settings
2. Front end view with Uploader
3. Thumbnail in Cart
4. Link of media once order received

== Changelog ==

= 1.4.0 (13.08.2020) =
* Fix - issue where file was getting uploaded to other products as well where the user had not uploaded any file.
* Dev - Added filter to allow only specific products where files can be uploaded.
* Dev - Added compatibility with WooCommerce 4.3

= 1.3.0 (13.06.2020) =
* Dev - Added pot files for translations
* Dev - Added compatibility with WooCommerce 4.2

= 1.2.0 =
* Issue fixed related to deprecated item meta hook

= 1.1.0 =
* Issue fixed related to Add to cart when media is missing

= 1.0.0 =
* Initial Launch Version

== Frequently Asked Questions ==

= How to allow file upload only on specific products? =

Add the below code in your theme functions.php file or Code Snippets plugin.
```
add_filter( 'wau_include_product_ids', 'wau_include_only_product', 10, 1 );

function wau_include_only_product( $pids ) {
	return array( 310, 315 ); // Add the product ID's here in the array.
}
```

== Upgrade Notice ==

Backup your store before upgrading the plugin
