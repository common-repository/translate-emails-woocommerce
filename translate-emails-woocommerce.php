<?php
/**
 * Plugin Name: ShopMagic Multilingual Support
 * Plugin URI: https://shopmagic.app/products/translate-emails-woocommerce/?utm_source=add_plugin_details&utm_medium=link&utm_campaign=plugin_homepage
 * Description: Allows creating automations with follow-up and custom emails in two or more languages.
 * Version: 1.0.10
 * Author: WP Desk
 * Author URI: https://shopmagic.app/?utm_source=user-site&utm_medium=quick-link&utm_campaign=author
 * Text Domain: translate-emails-woocommerce
 * Domain Path: /lang/
 * Requires at least: 5.4
 * Tested up to: 6.0
 * WC requires at least: 4.8
 * WC tested up to: 6.7.0
 * Requires PHP: 7.0
 *
 * Copyright 2021 WP Desk Ltd
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


$plugin_version = '1.0.10';

$plugin_name        = 'ShopMagic Multilingual Support';
$plugin_class_name  = '\WPDesk\ShopMagicMultilingual\Plugin';
$plugin_text_domain = 'translate-emails-woocommerce';
$product_id         = 'translate-emails-woocommerce';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

$requirements = [
	'php'     => '7.0',
	'wp'      => '5.4.0',
	'plugins' => [
		[
			'name'      => 'shopmagic-for-woocommerce/shopMagic.php',
			'nice_name' => 'ShopMagic for WooCommerce',
			'version'   => '2.32.0',
		],
	],

];

if ( \PHP_VERSION_ID > 50300 ) {
	require_once $plugin_dir . '/vendor/autoload.php';

	$requirements_checker = ( new \ShopMagicMultilingualVendor\WPDesk_Basic_Requirement_Checker_Factory() )
		->create_from_requirement_array(
			__FILE__,
			$plugin_name,
			$requirements,
			$plugin_text_domain
		);

	$plugin_info = new \ShopMagicMultilingualVendor\WPDesk_Plugin_Info();
	$plugin_info->set_plugin_file_name( plugin_basename( $plugin_file ) );
	$plugin_info->set_plugin_name( $plugin_name );
	$plugin_info->set_plugin_dir( $plugin_dir );
	$plugin_info->set_class_name( $plugin_class_name );
	$plugin_info->set_version( $plugin_version );
	$plugin_info->set_product_id( $product_id );
	$plugin_info->set_text_domain( $plugin_text_domain );
	$plugin_info->set_plugin_url( plugins_url( dirname( plugin_basename( $plugin_file ) ) ) );

	add_action(
		'plugins_loaded',
		static function () use ( $requirements_checker, $plugin_info, $plugin_class_name ) {
			if ( $requirements_checker->are_requirements_met() ) {
				$plugin = new $plugin_class_name( $plugin_info );
				$plugin->init();
			} else {
				$requirements_checker->render_notices();
			}
		},
		- 50
	);
}

