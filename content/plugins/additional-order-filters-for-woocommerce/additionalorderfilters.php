<?php
if ( ! defined( 'ABSPATH' ) ) exit();
/*
	Plugin Name: Additional Order Filters for WooCommerce
	Description: Adds additional order filters for WooCommerce
	Version: 1.10
	Author: Anton Bond
	Author URI: facebook.com/antonbondarevych
	License: GPL2
	 
	Additional Order Filters for WooCommerce is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	any later version.
	 
	Additional Order Filters for WooCommerce is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
	 
	You should have received a copy of the GNU General Public License
	along with Additional Order Filters for WooCommerce. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

class AOF_Woo_Additional_Order_Filters {

	private static $filter_search  = array( '*', ', ', ',' );
	private static $filter_replace = array( '%', '|', '|' );

	function __construct() {
		add_action( 'admin_menu', array( $this,'woaf_add_plugin_settings_page' ) );
	}

	function woaf_add_plugin_settings_page() {
		add_options_page( 'Additional Order Filters', 'Additional Order Filters', 'manage_options', 'additional-order-filters', array( $this, 'woaf_show_plugin_settings' ) );

		add_action( 'admin_notices', array( $this, 'waof_woocommerce_plugin_check' ) );
		add_action( 'admin_notices', array( $this, 'waof_woocommerce_settings_check' ) );
		add_action( 'views_edit-shop_order', array( $this, 'woaf_show_button' ), 2000, 2000 );
		add_action( 'restrict_manage_posts', array( $this, 'woaf_show_filters' ), 2000, 2000 );
		add_action( 'posts_where', array( $this, 'woaf_where_plugin_functions' ) );
		add_action( 'admin_head', array( $this, 'woaf_additional_plugin_admin_head' ) );
		add_filter( 'pre_get_posts', array( $this, 'woaf_filter_date_range' ) );
		add_filter( 'plugin_action_links_'.plugin_basename( __FILE__ ).'', array( $this, 'waof_plugin_add_settings_link' ) );
		add_action( 'admin_init', array( $this, 'woaf_load_textdomain' ) );
	}

	
	function woaf_load_textdomain() {
		load_plugin_textdomain( 'woaf-plugin', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}

	function waof_woocommerce_plugin_check() {
		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$error .= '<div class="error notice">';
				$error .= '<p>Additional Order Filters for WooCommerce Plugin requires WooCommerce to be installed and active.</p>';
			$error .= '</div>';
			echo $error;
		}
	}

	function waof_woocommerce_settings_check() {
		global $typenow;

		$enabled_filters = $this->woaf_enabled_additional_filters();

		if( 'shop_order' == $typenow && empty($enabled_filters) ) {
			$notice .= '<div class="notice notice-warning">';
				$notice .= '<p>Enable additional order filters on <a href="options-general.php?page=additional-order-filters">settings page</a> to use them</p>';
			$notice .= '</div>';
			echo $notice;
		}
	}

	function waof_plugin_add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=additional-order-filters">' . __( 'Settings', 'woaf-plugin' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

	function woaf_saving_settings() {
		if ( isset($_POST['ant_waof_save_settings']) ) {
			if ( function_exists('check_admin_referer') ) {
				check_admin_referer('ant_waof_save_settings');
			}
			if ( !empty($_POST['filters']) ) {
				$enabled_filters = $_POST['filters'];

				$filters = array();
				foreach ($enabled_filters as $filter) {
					$filters[] = sanitize_text_field( $filter );
				}
			}

			if ( !empty($_POST['woaf_per_column']) ) {
				if ( is_numeric($_POST['woaf_per_column']) ) {
					sanitize_text_field( update_option( 'woaf_per_column', $_POST['woaf_per_column'] ) );
				}
			}

			update_option( 'ant_additional_order_enabled_filters', serialize( $filters ) );

			return true;
		}
	}

	function woaf_show_plugin_settings() {

		$save_settings = $this->woaf_saving_settings();

		$output  = '';
		$output .= '<div class="wrap">';
		$output .= '<h1>Additional Order Filters Settings</h1>';
		$output .= '<p>'.__( 'Active filters:', 'woaf-plugin' ).'</p>';

		$output .= '<form action="'.$_SERVER['PHP_SELF'].'?page=additional-order-filters&update=true" method="POST" id="ant_waof_save_settings">';
		if ( function_exists('wp_nonce_field') ) {
			$output .= wp_nonce_field('ant_waof_save_settings');
		}

		$filters         = $this->woaf_get_filters();
		$enabled_filters = $this->woaf_enabled_additional_filters();
		$per_column      = ( get_option( 'woaf_per_column' ) ) ? get_option( 'woaf_per_column' ) : '4' ;

		if ( !empty($filters) ) {
			$output .= "<ul class='waof_enebled_filters'>";
			foreach ($filters as $filter) {
				$output .= "<li>";
				if ( !empty($enabled_filters) && in_array( $filter['id'], $enabled_filters ) ) {
					$output .= "<input type='checkbox' id='".$filter['id']."' name='filters[]' checked value='".$filter['id']."'>";
				} else {
					$output .= "<input type='checkbox' id='".$filter['id']."' name='filters[]' value='".$filter['id']."'>";
				}
				$output .= "<label for='".$filter['id']."'>".$filter['name']."</label>";
				if ( isset($filter['desc']) ) {
					$output .= "<p class='description'>".$filter['desc']."</p>";
				}
				$output .= "</li>";
			}
			$output .= "</ul>";
		}

		$output .= '<div class="select_buttons"><input id="select_all_filters" class="button" value="'.__( 'Select all', 'woaf-plugin' ).'" type="button"><input id="deselect_all_filters" class="button" value="'.__( 'Deselect all', 'woaf-plugin' ).'" type="button"></div>';
		$output .= '<div class="option_block">';
		$output .= '<label for="woaf_per_column">'.__( 'Number of filters in the column:', 'woaf-plugin' ).'</label>';
		$output .= '<input type="number" name="woaf_per_column" id="woaf_per_column" min="2" max="7" required="" value="'.$per_column.'">';
		$output .= '</div>';
		if ( $save_settings ) {
			$output .= '<p class="set_saved">Settings saved</p>';
		}
		$output .= '<input name="ant_waof_save_settings" id="submit" class="button button-primary" value="Save Changes" type="submit">';
		$output .= '</form>';
		$output .= '</div>'; // .wrap

		echo $output;
	}

	function woaf_enabled_additional_filters() {
		$enabled_filters = get_option( 'ant_additional_order_enabled_filters' );
		if ( !empty( $enabled_filters ) ) {
			$enabled_filters = unserialize( $enabled_filters );
		}
		return $enabled_filters;
	}

	function woaf_show_button($views) {
		$enabled_filters = $this->woaf_enabled_additional_filters();

		if ( !empty( $enabled_filters ) )
			echo '<a href="" onclick="event.preventDefault()" id="ant_add_filter" class="button action">Additional Filters</a>';

		return $views;
	}

	function woaf_get_filters() {
		$filters = array();
		//$filters[0]['name'] = 'Order Statuses';
		$filters[0]['name'] = __( 'Order Statuses', 'woaf-plugin' );
		$filters[0]['id']   = 'order_statuses';

		$filters[1]['name'] = __( 'Payment Method', 'woaf-plugin' );
		$filters[1]['id']   = 'payment_method';

		$filters[2]['name'] =  __( 'Customer Group', 'woaf-plugin' );
		$filters[2]['id']   = 'customer_group';

		$filters[3]['name'] = __( 'Shipping Method', 'woaf-plugin' );
		$filters[3]['id']   = 'shipping_method';

		$filters[4]['name'] = __( 'Customer Email', 'woaf-plugin' );
		$filters[4]['id']   = 'customer_email';

		$filters[5]['name'] = __( 'Customer First Name', 'woaf-plugin' );
		$filters[5]['id']   = 'customer_first_name';

		$filters[6]['name'] = __( 'Customer Last Name', 'woaf-plugin' );
		$filters[6]['id']   = 'customer_last_name';

		$filters[7]['name'] = __( 'Customer Billing Address', 'woaf-plugin' );
		$filters[7]['id']   = 'customer_billing_address';

		$filters[8]['name'] = __( 'Customer Billing Country', 'woaf-plugin' );
		$filters[8]['id']   = 'billing_country';

		$filters[9]['name'] = __( 'Customer Phone', 'woaf-plugin' );
		$filters[9]['id']   = 'customer_phone';

		$filters[10]['name'] = __( 'Track Number', 'woaf-plugin' );
		$filters[10]['desc'] = __( 'This filter requires <a href="https://woocommerce.com/products/shipment-tracking/" target="_blank">Shipment Tracking</a> plugin.', 'woaf-plugin' );
		$filters[10]['id']   = 'track_number';

		$filters[11]['name'] = __( 'Search by SKU Number', 'woaf-plugin' );
		$filters[11]['id']   = 'search_by_sku';

		$filters[12]['name'] = __( 'Orders by Date Range', 'woaf-plugin' );
		$filters[12]['id']   = 'orders_by_date_range';

		$filters[13]['name'] = __( 'Order Total', 'woaf-plugin' );
		$filters[13]['id']   = 'filter_order_total';

		return $filters;
	}

	function woaf_show_filters() {
		$post_type = sanitize_text_field( $_GET['post_type'] );
		if (!isset($_GET['post_type']) || $post_type !='shop_order') {
			return false;
		}

		global $wpdb;
		$output = '';
		$filters = $this->woaf_get_filters();
		$enabled_filters = $this->woaf_enabled_additional_filters();

		if ( !empty($filters) && !empty($enabled_filters) ) {
			$output .= '<div class="ant_special_order_filter_wrapper">';
			$opened = ( isset( $_COOKIE["ant_special_order_filter"] ) && $_COOKIE["ant_special_order_filter"] == 'opened' ) ? 'style="display:block"' : '';
			$output .= "<div class='ant_special_order_filter' $opened>";
			$per_column = get_option( 'woaf_per_column' );
			$per_column = ($per_column) ? $per_column : '4';
			foreach (array_chunk($filters, $per_column, true) as $filter) {
				$output .= '<div class="inline_block">';
				foreach ($filter as $filter) {
					if ( !empty($enabled_filters) && in_array( $filter['id'], $enabled_filters ) ) {
						if ( $filter['id'] == 'order_statuses' ) :
							$output .= '<div class="order_block_wrapper">';
							$output .= '<label for="order_statuses">'.$filter["name"].'</label>';
							$output .= '<select id="order_statuses" class="order_statuses_select" name="post_status[]" multiple="multiple">';
								$orders_statuses = wc_get_order_statuses();

								$selected = ( isset($_GET['post_status']) ) ? (array)$_GET['post_status'] : array();

								foreach ($orders_statuses as $k => $v) {
									$select = ( isset($selected) && in_array($k, $selected) ) ? " selected" : "";
									$output .= '<option value="'.$k.'" name="post_status" '.$select.'>'.$v.'</option>';
								}
							$output .= "</select>";
							$output .= "</div>";
						endif;
						if ( $filter['id'] == 'payment_method' ) :
							$selected = ( isset($_GET['payment_customer_filter']) ) ? sanitize_text_field($_GET['payment_customer_filter']) : '';
							$gateways = WC()->payment_gateways->payment_gateways();
							$output .= '<div class="order_block_wrapper">';
							$output .= '<label for="payment_customer_filter">'.$filter["name"].'</label>';
							$output .= '<select name="payment_customer_filter" id="payment_customer_filter">';
								$output .= '<option value=""></option>';
									foreach ($gateways as $gateway) {
										$title     = $gateway->title;
										$method_id = $gateway->id;
										if ( $selected == $method_id ) {
											$output .= '<option value="'.$method_id.'" selected>'.$title.'</option>';
										} else {
											$output .= '<option value="'.$method_id.'">'.$title.'</option>';
										}
									}
							$output .= '</select>';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_group' ) :
							$selected = ( isset($_GET['nonregistered_users_filter']) ) ? sanitize_text_field($_GET['nonregistered_users_filter']) : '';
							if ( $selected == 'nonregistered_users' ) $selected = 'selected';
							$selected_reg = ( $selected == 'registered_users' ) ? 'selected' : '';
							$output .= '<div class="order_block_wrapper">';
							$output .= '<label for="nonregistered_users_filter">'.$filter["name"].'</label>';
							$output .= '<select name="nonregistered_users_filter" id="nonregistered_users_filter">';
							$output .= '<option value=""></option>';
							$output .= "<option value=\"nonregistered_users\" $selected >Nonregistered Users</option>";
							$output .= "<option value=\"registered_users\" $selected_reg>Registered Users</option>";
							$output .= '</select>';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'shipping_method' ) :
							$output .= '<div class="order_block_wrapper">';
								$shipping_method_filter = (isset( $_GET['shipping_method_filter'] )) ? sanitize_text_field($_GET['shipping_method_filter']) : '';
							$output .= '<label for="shipping_method_filter">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$shipping_method_filter.'" name="shipping_method_filter" id="shipping_method_filter">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_email' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_email_search = (isset( $_GET['user_email_search'] )) ? sanitize_text_field($_GET['user_email_search']) : '';
							$output .= '<label for="user_email_search">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$user_email_search.'" name="user_email_search" id="user_email_search">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_first_name' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_billing_first_name = (isset( $_GET['user_billing_first_name'] )) ? sanitize_text_field($_GET['user_billing_first_name']) : '';
							$output .= '<label for="user_billing_first_name">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$user_billing_first_name.'" name="user_billing_first_name" id="user_billing_first_name">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_last_name' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_billing_last_name = (isset( $_GET['user_billing_last_name'] )) ? sanitize_text_field($_GET['user_billing_last_name']) : '';
							$output .= '<label for="user_billing_last_name">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$user_billing_last_name.'" name="user_billing_last_name" id="user_billing_last_name">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_billing_address' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_billing_address = (isset( $_GET['user_billing_address'] )) ? sanitize_text_field($_GET['user_billing_address']) : '';
							$output .= '<label for="user_billing_address">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$user_billing_address.'" name="user_billing_address" id="user_billing_address">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'billing_country' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_billing_country = (isset( $_GET['user_billing_country'] )) ? sanitize_text_field($_GET['user_billing_country']) : '';
								$output .= '<label for="order_statuses">'.$filter["name"].'</label>';
								$output .= '<select id="user_billing_country" class="order_statuses_select" name="user_billing_country[]" multiple="multiple">';
									$woo_countries = new WC_Countries();
									$countries     = $woo_countries->__get('countries');

									$selected = ( isset($_GET['user_billing_country']) ) ? (array)$_GET['user_billing_country'] : array();

									foreach ($countries as $k => $v) {
										$select = ( isset($selected) && in_array($k, $selected) ) ? " selected" : "";
										$output .= '<option value="'.$k.'" name="user_billing_country" '.$select.'>'.$v.'</option>';
									}
								$output .= "</select>";
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'customer_phone' ) :
							$output .= '<div class="order_block_wrapper">';
								$user_phone = (isset( $_GET['user_phone'] )) ? sanitize_text_field($_GET['user_phone']) : '';
							$output .= '<label for="user_phone">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$user_phone.'" name="user_phone" id="user_phone">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'track_number' ) :
							$output .= '<div class="order_block_wrapper">';
								$shpping_track_number = (isset( $_GET['shpping_track_number'] )) ? sanitize_text_field($_GET['shpping_track_number']) : '';
							$output .= '<label for="shpping_track_number">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$shpping_track_number.'" name="shpping_track_number" id="shpping_track_number">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'search_by_sku' ) :
							$output .= '<div class="order_block_wrapper">';
								$ant_filter_search_sku = (isset( $_GET['ant_filter_search_sku'] )) ? sanitize_text_field($_GET['ant_filter_search_sku']) : '';
							$output .= '<label for="ant_filter_search_sku">'.$filter["name"].'</label>';
							$output .= '<input type="text" value="'.$ant_filter_search_sku.'" name="ant_filter_search_sku" id="ant_filter_search_sku">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'orders_by_date_range' ) :
							$output .= '<div class="order_block_wrapper date_range">';
							$output .= '<label for="ant_filter_start_date">'.$filter["name"].'</label>';
							$from = ( isset($_GET['ant_filter_start_date']) ) ? sanitize_text_field( $_GET['ant_filter_start_date'] ) : '';
							$to   = ( isset($_GET['ant_filter_end_date']) ) ? sanitize_text_field( $_GET['ant_filter_end_date'] ) : '';
							$output .= '<input type="text" id="ant_filter_start_date" name="ant_filter_start_date" value="'.$from.'" placeholder="'.__( 'Start date', 'woaf-plugin' ).'">';
							$output .= '<input type="text" id="ant_filter_end_date" value="'.$to.'" name="ant_filter_end_date" placeholder="'.__( 'End date', 'woaf-plugin' ).'">';
							$output .= '</div>';
						endif;
						if ( $filter['id'] == 'filter_order_total' ) :
							$output .= '<div class="order_block_wrapper order_total">';
							$order_total_start = (isset( $_GET['order_total_start'] )) ? sanitize_text_field($_GET['order_total_start']) : '';
							$order_total_end = (isset( $_GET['order_total_end'] )) ? sanitize_text_field($_GET['order_total_end']) : '';
							$output .= '<label for="order_total_start">'.$filter["name"].'';
								$output .= '<div class="inline">';
									$output .= '<label for="order_total_start">'.__( 'from:', 'woaf-plugin' ).'</label>';
									$output .= '<input type="number" min="0" value="'.$order_total_start.'" id="order_total_start" name="order_total_start">';
								$output .= '</div>';
								$output .= '<div class="inline">';
									$output .= '<label for="order_total_end">'.__( 'to:', 'woaf-plugin' ).'</label>';
									$output .= '<input type="number" min="1" value="'.$order_total_end.'" id="order_total_end" name="order_total_end">';
								$output .= '</div>';
							$output .= '</label>';
							$output .= '</div>';
						endif;
					}
				}
				$output .= "</div>";
			}
			$output .= '<div class="filter_buttons">';
			$output .= '<input name="filter_action" class="button" value="'.__( 'Apply Filters', 'woaf-plugin' ).'" type="submit">';
			$output .= '<input name="filter_clear" class="button" value="'.__( 'Clear', 'woaf-plugin' ).'" id="filter_clear" type="button">';
			$output .= '</div>';
			$output .= '<div class="cledarfix"></div>';

			$output .= "</div>"; // .ant_special_order_filter
			$output .= "</div>"; // .ant_special_order_filter_wrapper
		}

		echo $output;
	}

	function woaf_where_plugin_functions( $where ) {
		global $typenow, $wpdb;

		if( 'shop_order' == $typenow ) {
			if ( isset( $_GET['nonregistered_users_filter'] ) && !empty( $_GET['nonregistered_users_filter'] ) ) { // search by user email
				$filter = trim( sanitize_text_field($_GET['nonregistered_users_filter']) );
				$filter = str_replace("*", "%", $filter);
				$filter = $wpdb->_escape($filter);
				if ( !empty( $filter ) && $filter == 'nonregistered_users' ) :
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_customer_user' AND meta_value = '0' OR meta_key = '_customer_user' AND meta_value = '' ) ";
				endif;
				if ( !empty( $filter ) && $filter == 'registered_users' ) :
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_customer_user' AND meta_value > '0' )";
				endif;
			}
			if ( isset( $_GET['post_status'] ) && !empty( $_GET['post_status'] ) && is_array($_GET['post_status']) ) { // search by order statuses
				$filter = '';
				$count = count( $_GET['post_status'] );
				foreach ($_GET['post_status'] as $k => $status) {
					$last = ( $k + 1 == $count ) ? "" : ", ";
					$filter .= "'". trim( sanitize_text_field($status) ) . "'$last";
				}
				if ( !empty( $filter )  ) {
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->posts .".ID FROM ".$wpdb->posts ." WHERE `post_status` IN (".$filter."))";
				}
			}
			if ( isset( $_GET['user_email_search'] ) && !empty( $_GET['user_email_search'] ) ) { // search by user email
				$filter = trim( sanitize_text_field($_GET['user_email_search']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter = $wpdb->_escape($filter);
				$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_email' AND meta_value REGEXP '" . $filter . "' )";
			}
			if ( isset( $_GET['user_billing_first_name'] ) && !empty( $_GET['user_billing_first_name'] ) ) { // search by billing first name
				$filter = trim( sanitize_text_field($_GET['user_billing_first_name']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter = $wpdb->_escape($filter);
				$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_first_name' AND meta_value REGEXP '" . $filter . "' )";
			}
			if ( isset( $_GET['user_billing_last_name'] ) && !empty( $_GET['user_billing_last_name'] ) ) { // search by billing last name
				$filter  = trim( sanitize_text_field($_GET['user_billing_last_name']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter  = $wpdb->_escape($filter);
				$where  .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_last_name' AND meta_value REGEXP '" . $filter . "' )";
			}
			if ( isset( $_GET['user_billing_address'] ) && !empty( $_GET['user_billing_address'] ) ) { // search by billing address
				$filter = trim( sanitize_text_field($_GET['user_billing_address']) );
				$filter = str_replace("*", "%", $filter);
				$filter = $wpdb->_escape($filter);
				$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_address_1' AND meta_value LIKE '%" . $filter . "%' )";
			}
			if ( isset( $_GET['user_billing_country'] ) && !empty( $_GET['user_billing_country'] ) ) { // search by billing country
				$filter = '';
				$count = count( $_GET['user_billing_country'] );
				foreach ($_GET['user_billing_country'] as $k => $country) {
					$suffix = ( $k + 1 == $count ) ? "" : " OR meta_value = ";
					$country_code  = "'". trim( sanitize_text_field($country) ) ."'";
					$filter       .= $country_code.$suffix;
				}
				$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_country' AND meta_value = ".$filter." )";
			}
			if ( isset( $_GET['user_phone'] ) && !empty( $_GET['user_phone'] ) ) { // search by billing phone or shipping phone
				$filter  = trim( sanitize_text_field($_GET['user_phone']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter  = $wpdb->_escape($filter);
				$where  .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_billing_phone' AND meta_value REGEXP '" . $filter . "' OR meta_key = '_shipping_phone' AND meta_value REGEXP '" . $filter . "' )";
			}
			if ( isset( $_GET['order_total_start'] ) && !empty( $_GET['order_total_start'] ) || isset( $_GET['order_total_end'] ) && !empty( $_GET['order_total_end'] ) ) { // search by total
				$start = $_GET['order_total_start'];
				$end   = $_GET['order_total_end'];

				if ( is_numeric( $start ) || is_numeric( $end ) ) {
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_order_total'";
					if ( is_numeric( $start ) ){
						$where .= " AND meta_value >= " . sprintf("%.2f", $start);
					}
					if ( is_numeric( $end ) ){
						$where .= " AND meta_value <= " . sprintf("%.2f", $end) ;
					}
					$where .= " ) ";
				}
			}
			if ( isset( $_GET['shipping_method_filter'] ) && !empty( $_GET['shipping_method_filter'] ) ) { // search by shipping
				$filter  = trim( sanitize_text_field($_GET['shipping_method_filter']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter  = $wpdb->_escape($filter);
				$where  .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->prefix."woocommerce_order_items.order_id FROM ".$wpdb->prefix."woocommerce_order_items WHERE order_item_type = 'shipping' AND  order_item_name REGEXP '" . $filter . "' )";
			}
			if ( isset( $_GET['payment_customer_filter'] ) && !empty( $_GET['payment_customer_filter'] ) ) { // search by payment method
				$filter = trim( sanitize_text_field($_GET['payment_customer_filter']) );
				$filter = str_replace("*", "%", $filter);
				$filter = $wpdb->_escape($filter);
				$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_payment_method' AND meta_value LIKE '%" . $filter . "%' )";
			}
			if ( isset( $_GET['shpping_track_number'] ) && !empty( $_GET['shpping_track_number'] ) ) { // search by track number
				$filter  = trim( sanitize_text_field($_GET['shpping_track_number']) );
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter  = $wpdb->_escape($filter);
				if ( is_plugin_active( 'woocommerce-shipment-tracking/shipment-tracking.php' ) ) {
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = '_wc_shipment_tracking_items' AND meta_value REGEXP '\"tracking_number\";s:" . $filter . "%' )";
				} elseif ( is_plugin_active( 'woo-shipment-tracking-order-tracking/woocommerce-shipment-tracking.php' ) ) {
					$where .= " AND $wpdb->posts.ID IN (SELECT ".$wpdb->postmeta.".post_id FROM ".$wpdb->postmeta." WHERE meta_key = 'wf_wc_shipment_source' AND meta_value REGEXP '\"shipment_id_cs\";s:" . $filter . "%' )";
				}
			}
			if ( isset( $_GET['ant_filter_search_sku'] ) && !empty( $_GET['ant_filter_search_sku'] ) ) { // search by SKU
				$filter  = trim($_GET['ant_filter_search_sku']);
				$filter  = str_replace(self::$filter_search, self::$filter_replace, $filter);
				$filter  = $wpdb->_escape($filter);

				$where .= " AND ($wpdb->posts.ID IN(
				SELECT $wpdb->posts.ID FROM $wpdb->posts
				INNER JOIN " . $wpdb->prefix . "woocommerce_order_items ON $wpdb->posts.ID = " . $wpdb->prefix . "woocommerce_order_items.order_id
				INNER JOIN " . $wpdb->prefix . "woocommerce_order_itemmeta ON " . $wpdb->prefix . "woocommerce_order_items.order_item_id = " . $wpdb->prefix . "woocommerce_order_itemmeta.order_item_id
				INNER JOIN $wpdb->postmeta ON " . $wpdb->prefix . "woocommerce_order_itemmeta.meta_value = $wpdb->postmeta.post_id
				WHERE $wpdb->posts.post_type = 'shop_order'
				AND " . $wpdb->prefix . "woocommerce_order_items.order_item_type = 'line_item'
				AND " . $wpdb->prefix . "woocommerce_order_itemmeta.meta_key = '_product_id'
				AND $wpdb->postmeta.meta_key = '_sku'
				AND $wpdb->postmeta.meta_value REGEXP '" . $filter . "') )";
			}
		}
		return $where;
	}

	function woaf_filter_date_range( $wp_query ) {
		global $pagenow;

		if (
			is_admin()
			&& $wp_query->is_main_query()
			&& isset($_GET['post_type']) && sanitize_text_field($_GET['post_type']) =='shop_order' 
			&& ! empty( $_GET['ant_filter_start_date'] )
			&& ! empty( $_GET['ant_filter_end_date'] )
		) {
			$from = explode( '/', sanitize_text_field( $_GET['ant_filter_start_date'] ) );
			$to   = explode( '/', sanitize_text_field( $_GET['ant_filter_end_date'] ) );

			$from = array_map( 'intval', $from );
			$to   = array_map( 'intval', $to );

			if (
				3 === count( $to )
				&& 3 === count( $from )
			) {
				list( $year_from, $month_from, $day_from ) = $from;
				list( $year_to, $month_to, $day_to )       = $to;
			} else {
				return $wp_query;
			}
			$wp_query->set(
				'date_query',
				array(
					'after' => array(
						'year'  => $year_from,
						'month' => $month_from,
						'day'   => $day_from,
					),
					'before' => array(
						'year'  => $year_to,
						'month' => $month_to,
						'day'   => $day_to,
					),
					'inclusive' => apply_filters( 'woo_orders_filterby_date_range_query_is_inclusive', true ),
					'column'    => apply_filters( 'woo_orders_filterby_date_query_column', 'post_date' ),
				)
			);
		}
		return $wp_query;
	}

	function woaf_additional_plugin_admin_head() {
		global $typenow;

		if ( isset($_GET['page']) && $_GET['page'] == 'additional-order-filters' ) : ?>
			<style>
				p.description {
					font-style: italic; margin: 0;
				}
				.waof_enebled_filters li {
					margin-bottom: 15px;
				}
				.waof_enebled_filters label {
					position: relative;
					top: -3px;
				}
				.set_saved {
					color: green;
				}
				.select_buttons {
					margin: 20px 0;
				}
				.option_block {
					display: block;
					margin-bottom: 20px;
				}
				.option_block label {
					display: block;
					margin-bottom: 5px;
				}
				.option_block input[type="number"] {
					max-width: 60px;
				}
				#deselect_all_filters {
					margin-left: 10px;
				}
			</style>
			<script>
				jQuery(document).ready(function($) {
					$('#select_all_filters, #deselect_all_filters').on('click', function(){
						var action = (this.id == 'select_all_filters' ? true : false);
						$.each( $('ul.waof_enebled_filters input[type="checkbox"]'), function( k, v ) {
							$(v).prop( "checked", action );
						});
					});
				});
			</script>
		<?php
		endif;
		if( 'shop_order' == $typenow ) : ?>

		<style>
			.cledarfix {
				clear: both;
			}
			.ant_special_order_filter_wrapper {
				display: block;
				float: left;
				clear: both;
				margin: 10px 0;
			}
			.ant_special_order_filter {
				display: none;
				margin-top: 20px;
			}
			.ant_special_order_filter > ul {
				display: inline-block;
				vertical-align: top;
				margin-right: 20px;
			}
			.post-type-shop_order .tablenav .actions {
				float: none;
			}
			.inline_block {
				display: inline-block;
				vertical-align: top;
				margin-right: 25px;
				width: 240px;
			}
			.ant_special_order_filter .order_block_wrapper select {
				max-width: 100%;
			}
			.ant_special_order_filter .date_range input[type="text"] {
			    min-width: 115px;
			}
			.ant_special_order_filter .date_range input#ant_filter_end_date {
				float: right !important;
				margin-right: 0;
			}
			.order_block_wrapper {
				margin-bottom: 15px;
			}
			.order_block_wrapper > * {
				display: block;
				float: none !important;
				width: 100%;
				min-width: 180px;
			}
			.nonregistered_users_filter select {
				display: block;
			}
			.nonregistered_users_filter {
				margin-bottom: 10px;
			}
			.user_email_search {
				padding-top: 20px;
				clear: both;
			}
			.user_email_search label {
				margin: 0;
				padding: 0;
				display: block;
			}
			.user_email_search input {
				margin-top: 5px;
				display: block;
				width: 100%;
			}
			.order_total .inline {
				margin-top: 10px;
			}
			.order_total .inline > label,
			.order_total .inline > input[type="number"] {
				display: inline-block;
				vertical-align: top;
				width: 60px;
				min-width: 60px;
				line-height: 25px;
			}
			.order_total .inline > input[type="number"] {
				width: 120px;
			}
			#ant_add_filter {
				display: inline-block;
				margin-left: 20px;
				margin-top: 7px;
			}
			.rtl #ant_add_filter {
				margin-right: 20px;
				margin-top: 7px;
			}
			.date_range input[type="text"] {
				min-width: 100px;
				width: 100px;
				display: inline-block;
			}
			.filter_buttons {
				display: inline-block;
				margin-top: 18px;
			}
			.post-type-shop_order .alignleft.actions .ant_special_order_filter_wrapper .select2.select2-container {
				display: block;
			}
			.ant_special_order_filter_wrapper #filter_clear {
				margin-left: 10px;
			}
		</style>
		<script>
			jQuery(document).ready(function($) {
				$( '#ant_filter_start_date' ).datepicker({
					dateFormat: 'yy/mm/dd',
					maxDate: '0',
					isRTL: <?php echo ( is_rtl() ? 'true' : 'false' ) ?>,
					onSelect: function (date) {
						var date2 = $('#ant_filter_start_date').datepicker('getDate');
						date2.setDate(date2.getDate());
						$('#ant_filter_end_date').datepicker('option', 'minDate', date2);
					}
				});
				$( '#ant_filter_end_date' ).datepicker({
					dateFormat:'yy/mm/dd',
					maxDate: '0',
					isRTL: <?php echo ( is_rtl() ? 'true' : 'false' ) ?>
				});

				$('#filter_clear').on('click', function(){
					$.each( $('.ant_special_order_filter input, .ant_special_order_filter select'), function( k, v ) {
						var type = $(v).attr('type');
						if ( type == 'text' || type == 'email' || type == 'number' ) {
							$(v).val('');
						}
						if ( type == null || $(v).prop('tagName') == 'SELECT' ) {
							$(v).val('');
						}
					});

					$(".order_statuses_select").select2();

				});
				$('#ant_add_filter').on('click', function(){
					$('.ant_special_order_filter').slideToggle( "400", function() {
						if ( $('.ant_special_order_filter').is(':visible') ) {
							document.cookie = "ant_special_order_filter=opened";
						} else {
							document.cookie = "ant_special_order_filter=closed";
						}
					});
				});
				$('.order_statuses_select').select2();
			});
		</script>
	<?php
		endif;
	}
}

new AOF_Woo_Additional_Order_Filters();