<?php
/**
* Plugin Name: Bambora APAC Plug-in for WooCommerce.
* Plugin URI: http://www.bambora.com/
* Description:  Welcome to the Bambora APAC Plug-in for WooCommerce. Need an Account? Check us out at https:///www.bambora.com
* Version: 1.0
* Author: Bambora APAC
* Author URI: http://www.bambora.com/
* Developer: Bambora APAC
* Developer URI: http://www.bambora.com/
* Text Domain: bambora-apac
*
* Copyright: © 2017 Bambora APAC.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BAMBORA_APAC_VERSION', '1.0.0' );
define( 'BAMBORA_APAC_MIN_PHP_VER', '5.6.0' );
define( 'BAMBORA_APAC_MIN_WC_VER', '2.5.0' );
define( 'BAMBORA_APAC_MAIN_FILE', __FILE__ );

	//  Gateway Class and Register Payment Gateway with WooCommerce
	add_action( 'plugins_loaded', 'bambora_apac_init', 0 );

	// Start bambora_apac_init
	function bambora_apac_init() {

		// Checking whether WooCommerce is not installed return null
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;	
		// If WooCommerce is installed include Gateway Class
		include_once( 'bambora-apac.php' );

		// Adding Class to WooCommerce
		add_filter( 'woocommerce_payment_gateways', 'wc_add_bambora_gateway' );
		function wc_add_bambora_gateway( $methods ) {
			$methods[] = 'Bambora_Apac';
			return $methods;
		}

		if(is_admin())
			$Bambora_Apac = new Bambora_Apac;	

	}
	// End bambora_apac_init

	// Add custom action links
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bambora_apac_action_links' );

	// Start bambora_apac_action_links
	function bambora_apac_action_links( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=bambora_apac' ) . '">' . __( 'Settings', 'bambora-apac' ) . '</a>',
		);
		// Merge link with the default ones
		return array_merge( $plugin_links, $links );

	}
	// End bambora_apac_action_links