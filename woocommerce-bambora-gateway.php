<?php
/*
* Plugin Name: Bambora APAC Plug-in for WooCommerce. 
* Plugin URI: http://www.bambora.com/
* Description: Welcome to the Bambora APAC Plug-in for WooCommerce. Need an Account? Check us out at https:///www.bambora.com
* Author: Bambora APAC
* Author URI: http://www.bambora.com/
* Version: 1.0.0
* Text Domain: woocommerce-gateway-bambora
*
* Copyright (c) 2017 Bambora
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WC_BAMBORA_VERSION', '1.0.0' );
define( 'WC_BAMBORA_MIN_PHP_VER', '5.6.0' );
define( 'WC_BAMBORA_MIN_WC_VER', '2.5.0' );
define( 'WC_BAMBORA_MAIN_FILE', __FILE__ );

	//  Gateway Class and Register Payment Gateway with WooCommerce
	add_action( 'plugins_loaded', 'wc_bambora_init', 0 );

	// Start wc_bambora_init
	function wc_bambora_init() {

		// Checking whether WooCommerce is not installed return null
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;	
		// If WooCommerce is installed include Gateway Class
		include_once( 'woocommerce-bambora.php' );

		// Adding Class to WooCommerce
		add_filter( 'woocommerce_payment_gateways', 'wc_add_bambora_gateway' );
		function wc_add_bambora_gateway( $methods ) {
			$methods[] = 'WC_Bambora';
			return $methods;
		}

		$WC_Bambora = new WC_Bambora;	

	}
	// End wc_bambora_init

	// Add custom action links
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_bambora_action_links' );

	// Start wc_bambora_action_links
	function wc_bambora_action_links( $links ) {

		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_bambora' ) . '">' . __( 'Settings', 'wc-bambora' ) . '</a>',
		);
		// Merge link with the default ones
		return array_merge( $plugin_links, $links );

	}
	// End wc_bambora_action_links