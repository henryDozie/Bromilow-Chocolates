<?php
/*
Plugin Name: MMWD Remove Add To Cart for WooCommerce
Plugin URI:  https://mcgregormedia.co.uk
Description: Removes all Add to Cart buttons throughout a WooCommerce website without affecting anything else hooked into the Add to Cart actions.
Version:     1.4.18
Author:      McGregor Media Web Design
Author URI:  https://mcgregormedia.co.uk
Text Domain: mmwd-ratc
WC requires at least: 3.0
WC tested up to: 5.5
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/gpl-3.0.html.
*/







if ( ! defined( 'ABSPATH' ) ) {
	
	exit; // Came here directly? Vamoose.
	
}









/**
 * Loads translation files
 * 
 * @since 1.2.0					Added function
 */

function mmwd_remove_atc_load_textdomain() {
	
	load_plugin_textdomain( 'mmwd-ratc', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	
}
add_action( 'plugins_loaded', 'mmwd_remove_atc_load_textdomain' );






/**
 * Adds option on activation to check if newly activated. If true, runs WooCOmmerce check after register_activation_hook redirection
 * 
 * @since 1.2.0					Added function
 */

function mmwd_remove_atc_activate(){
	
    add_option( 'mmwd_remove_atc_activated', 'mmwd-ratc' );
	
}
register_activation_hook( __FILE__, 'mmwd_remove_atc_activate' );







/**
 * Checks whether WooCommerce is active and deactivates plugin with admin notice if not
 * 
 * @since 1.2.0					Added function
 */

function mmwd_remove_atc_load_plugin(){

    if ( is_admin() && get_option( 'mmwd_remove_atc_activated' ) == 'mmwd-ratc' ) {
		
        delete_option( 'mmwd_remove_atc_activated' ); // remove option we set on activation

        if ( !class_exists( 'WooCommerce' ) ) { // check WooCommerce is active
			
            add_action( 'admin_notices', 'mmwd_remove_atc_admin_notice' ); // if not display admin notice

            deactivate_plugins( plugin_basename( __FILE__ ) ); // deactivate plugin

            if ( isset( $_GET['activate'] ) ) {
				
                unset( $_GET['activate'] );
				
            }
			
        }
		
    }
	
}
add_action( 'admin_init', 'mmwd_remove_atc_load_plugin' );








/**
 * Display an error message if WooCommerce is not activated
 * 
 * @return string				The formatted HTML
 * 
 * @since 1.2.0					Added function
 */

function mmwd_remove_atc_admin_notice (){
	
    ?>
    <div class="notice notice-error"><p><?php _e( 'MMWD Remove Add To Cart for WooCommerce requires WooCommerce to run. Please install and activate WooCommerce.', 'mmwd-ratc' ) ?></p></div>
    <?php
	
}









/**
 * Adds settings section in WooCommerce > Settings > Products 
 * 
 * @param array $sections  		The settings sections array
 * 
 * @return array $sections  	The updated settings sections array
 *
 * @since 1.0.0					Added function
 */

function mmwd_add_remove_atc_settings_section( $sections ) {
	
	$sections['mmwd_remove_atc_section'] = __( 'Remove Add to Cart', 'mmwd-ratc' );
	
	return $sections;
	
}
add_filter( 'woocommerce_get_sections_products', 'mmwd_add_remove_atc_settings_section' );







/**
 * Displays settings section for Remove Add to Cart in WooCommerce > Settings > Products 
 * 
 * @param array $settings 					The array of settings
 * @param string $current_section  			The current setting id
 * 
 * @return array $mmwd_remove_atc			The updated array of settings
 * @return array $settings					The standard array of settings
 *
 * @since 1.2.0								Added note to reflect theme issues
 * @since 1.1.0								Added 'Remove prices' option
 * @since 1.0.0								Added function
 */

function mmwd_display_remove_atc_settings( $settings, $current_section ){
	
	// Check the current section is what we want
	if ( $current_section == 'mmwd_remove_atc_section' ) {
		
		$mmwd_remove_atc = array();
		
		// Title
		$mmwd_remove_atc[] = array(
			'id' => 'mmwd_remove_atc_title',
			'name' => __( 'Remove Add to Cart buttons', 'mmwd-ratc' ),
			'type' => 'title',
			'desc' => __( 'Remove all Add to Cart buttons without affecting anything else hooked into the Add to Cart actions. Removing prices may not work with themes that do not use the standard WooCommerce hooks.', 'mmwd-ratc' )
		);

		// Checkbox
		$mmwd_remove_atc[] = array(
			'id'       => 'mmwd_remove_atc',
			'name'     => __( 'Remove Add to Cart buttons', 'mmwd-ratc' ),
			'type'     => 'checkbox'
		);

		// Checkbox
		$mmwd_remove_atc[] = array(
			'id'       => 'mmwd_remove_price',
			'name'     => __( 'Remove prices', 'mmwd-ratc' ),
			'type'     => 'checkbox'
		);

		$mmwd_remove_atc[] = array(
			'type' => 'sectionend',
			'id' => 'mmwd_remove_atc_end'
		);
		
		return $mmwd_remove_atc;
	
	// If not, return the standard settings
	}else{ 
		
		return $settings;
		
	}
}
add_filter( 'woocommerce_get_settings_products', 'mmwd_display_remove_atc_settings', 10, 2 );







/**
 * Adds the filter to remove the Add to Cart buttons
 *
 * @since 1.0.1		Added function
 */

function mmwd_remove_atc_add_filter(){
	
	if( get_option( 'mmwd_remove_atc' ) && get_option( 'mmwd_remove_atc' ) === 'yes' ){
	
		return false;
	
	}else{
		
		return true;
		
	}
	
}
add_filter( 'woocommerce_is_purchasable', 'mmwd_remove_atc_add_filter' );




/**
 *  Removes the Add to Cart buttons from variable products
 *  
 *  @since 1.4.6
 */
 
function mmwd_remove_atc_variable_product() {
	
	if( get_option( 'mmwd_remove_atc' ) && get_option( 'mmwd_remove_atc' ) === 'yes' ){

		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
	
	}
	
}
add_action( 'woocommerce_single_product_summary', 'mmwd_remove_atc_variable_product', 1, 0 );






/**
 * Removes prices on single products
 *
 * @since 1.2.0		Removed erroneous remove_actions
 * @since 1.1.0		Added function
 */

function mmwd_remove_price_remove_actions(){

	if( get_option( 'mmwd_remove_price' ) && get_option( 'mmwd_remove_price' ) === 'yes' ){
		
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		
	}

}
add_action( 'init', 'mmwd_remove_price_remove_actions' );




/**
 *  Removes prices on variable products
 *  
 *  @param int $price 			The original price
 *  @param object $product		The product object 
 *  
 *  @return string $price		The updated price
 *  
 *  @since 1.4.7				FIXED: rearranged hook order to honour mmwd_remove_price option
 *  @since 1.4.6
 */
 
function mmwd_remove_price_variable_product( $price, $product ) {

	$price = '';
	return $price;
	
}



/**
 *  Conditionally adds filters to fire mmwd_remove_price_variable_product()
 *  
 *  @since 1.4.7
 */
function mmwd_remove_price_variable_product_init() {
	
	if ( ( get_option( 'mmwd_remove_price' ) && get_option( 'mmwd_remove_price' ) === 'yes' ) ){
		
		add_filter( 'woocommerce_variable_sale_price_html', 'mmwd_remove_price_variable_product', 10, 2 );
		add_filter( 'woocommerce_variable_price_html', 'mmwd_remove_price_variable_product', 10, 2 );
		add_filter( 'woocommerce_get_price_html', 'mmwd_remove_price_variable_product', 10, 2 );
		
	}

}
add_action( 'init', 'mmwd_remove_price_variable_product_init' );
