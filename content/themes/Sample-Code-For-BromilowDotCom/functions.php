<?php
/**
 * Genesis Sample.
 *
 * This file adds functions to the Genesis Sample Theme.
 *
 * @package Genesis Sample
 * @author  StudioPress
 * @license GPL-2.0-or-later
 * @link    https://www.studiopress.com/
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

// Sets up the Theme.
require_once get_stylesheet_directory() . '/lib/theme-defaults.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( genesis_get_theme_handle(), get_stylesheet_directory() . '/languages' );

}

// Adds helper functions.
require_once get_stylesheet_directory() . '/lib/helper-functions.php';

// Adds image upload and color select to Customizer.
require_once get_stylesheet_directory() . '/lib/customize.php';

// Includes Customizer CSS.
require_once get_stylesheet_directory() . '/lib/output.php';

// Adds WooCommerce support.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php';

// Adds the required WooCommerce styles and Customizer CSS.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php';

// Adds the Genesis Connect WooCommerce notice.
require_once get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php';

add_action( 'after_setup_theme', 'genesis_child_gutenberg_support' );
/**
 * Adds Gutenberg opt-in features and styling.
 *
 * @since 2.7.0
 */
function genesis_child_gutenberg_support() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- using same in all child themes to allow action to be unhooked.
	require_once get_stylesheet_directory() . '/lib/gutenberg/init.php';
}

// Registers the responsive menus.
if ( function_exists( 'genesis_register_responsive_menus' ) ) {
	genesis_register_responsive_menus( genesis_get_config( 'responsive-menus' ) );
}

add_action( 'wp_enqueue_scripts', 'genesis_sample_enqueue_scripts_styles' );
/**
 * Enqueues scripts and styles.
 *
 * @since 1.0.0
 */
function genesis_sample_enqueue_scripts_styles() {

	$appearance = genesis_get_config( 'appearance' );

	wp_enqueue_style(
		genesis_get_theme_handle() . '-fonts',
		$appearance['fonts-url'],
		[],
		genesis_get_theme_version()
	);

	wp_enqueue_style( 'dashicons' );

	if ( genesis_is_amp() ) {
		wp_enqueue_style(
			genesis_get_theme_handle() . '-amp',
			get_stylesheet_directory_uri() . '/lib/amp/amp.css',
			[ genesis_get_theme_handle() ],
			genesis_get_theme_version()
		);
	}

	wp_enqueue_script(
		'custom',
		get_stylesheet_directory_uri() . '/js/custom.js',
		array( 'jquery' ),
		'CHILD_THEME_VERSION',
		true
	);
	wp_enqueue_script('recaptcha', '//www.google.com/recaptcha/api.js', '', '', true);
}

add_action( 'after_setup_theme', 'genesis_sample_theme_support', 9 );
/**
 * Add desired theme supports.
 *
 * See config file at `config/theme-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_theme_support() {

	$theme_supports = genesis_get_config( 'theme-supports' );

	foreach ( $theme_supports as $feature => $args ) {
		add_theme_support( $feature, $args );
	}

}

add_action( 'after_setup_theme', 'genesis_sample_post_type_support', 9 );
/**
 * Add desired post type supports.
 *
 * See config file at `config/post-type-supports.php`.
 *
 * @since 3.0.0
 */
function genesis_sample_post_type_support() {

	$post_type_supports = genesis_get_config( 'post-type-supports' );

	foreach ( $post_type_supports as $post_type => $args ) {
		add_post_type_support( $post_type, $args );
	}

}

// Adds image sizes.
add_image_size( 'sidebar-featured', 75, 75, true );
add_image_size( 'genesis-singular-images', 702, 526, true );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' === $args['theme_location'] ) {
		$args['depth'] = 1;
	}

	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}


function addShoppingBagOnMobile(){
	echo '<ul class="mobile-bag"><li class="menu-item wpmenucartli wpmenucart-display-standard-mobile" id="wpmenucartli"><a class="wpmenucart-contents empty-wpmenucart-visible" href="/cart/" title="Start shopping"><i class="wpmenucart-icon-shopping-cart-0" role="img" aria-label="Cart"></i><span class="cartcontents">0 items</span></a></li></ul>';
}

add_action('genesis_header', 'addShoppingBagOnMobile');

function addSidebar(){
	if(is_woocommerce()){
		get_sidebar('Primary Sidebar');
	}
}
add_action('woocommerce_before_shop_loop', 'addSidebar', 1);


function getCartQuanitity(){
	if(WC()->cart->get_cart_contents_count() == 0){
		return;
	}
	else if(WC()->cart->get_cart_contents_count() <= 99){
		echo '<span class="cart-quantity">'.WC()->cart->get_cart_contents_count().'</span>';
	}
	else{
		echo '<span class="cart-quantity">99+</span>';
	}
}

add_action('genesis_header', 'getCartQuanitity');

function addTitleBarToShop(){
	global $post;
	if(is_woocommerce()){
	echo '<div class="shop-hero">
	        <div class="heroCenterdiv">
	            <div class="heroH1">';
	            if(is_shop() || is_product()){
	            	echo '<h1>Shop Chocolate</h1>';
	            }
	            else if(is_product_category()){
	            	$category = single_cat_title('', false);
	            	echo '<h1>'.$category.'</h1>';
	            }
	echo        '</div>
	            <div class="heroP">
	              <div>';
				woocommerce_breadcrumb();
	echo        '</div>
	            </div>
	        </div>

	      </div>';
	}
}
add_action('genesis_before_content', 'addTitleBarToShop', 1);

add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
    if ( 'Read more' == $text ) {
        $text = __( 'View Product', 'woocommerce' );
    }

    return $text;
} );


// add a products per-page select dropdown to archive - above shop productloop
add_action( 'woocommerce_before_shop_loop', 'pro_selectbox', 25 );
function pro_selectbox() {
	$per_page = filter_input(INPUT_GET, 'perpage', FILTER_SANITIZE_NUMBER_INT);
	echo '<div class="woocommerce-perpage">';
	echo '<select><option value="?perpage=none">Number of Products</option>';
		$orderby_options = array(
		'9' => '9',
		'12' => '12',
		'24' => '24',
		'300' => 'All'
		);
	foreach( $orderby_options as $value => $label ) {
		echo "<option ".selected( $per_page, $value )." value='?perpage=$value'>$label</option>";
		}
		echo '</select>';
		echo '</div>';
}
//add_action( 'pre_get_posts', 'pro_pre_get_products_query' );
//function pro_pre_get_products_query( $query ) {
//	$per_page = filter_input(INPUT_GET, 'perpage', FILTER_SANITIZE_NUMBER_INT);
//	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'product' ) ) {
//		$query->set( 'posts_per_page', $per_page );
//	}
//}

/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 100 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  if (!isset($_GET['perpage'])){
	$cols = 9;
  }else {
  	$per_page = filter_input(INPUT_GET, 'perpage', FILTER_SANITIZE_NUMBER_INT);
  	$cols = $per_page;
  }
  return $cols;
}



function getProductSearch(){
	get_product_search_form();
}

add_action('genesis_header', 'getProductSearch', 100);

add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11); 
function dequeue_woocommerce_cart_fragments() { 
	wp_dequeue_script('wc-cart-fragments'); 
}

//Adding Alphabetical sorting option to shop and product settings pages. Making it default sorting option----------
function sip_alphabetical_shop_ordering( $sort_args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? woocommerce_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'alphabetical' == $orderby_value ) {
		$sort_args['orderby'] = 'title';
		$sort_args['order'] = 'asc';
		$sort_args['meta_key'] = '';
	}
	return $sort_args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'sip_alphabetical_shop_ordering' );

function sip_custom_wc_catalog_orderby( $sortby ) {
	$sortby['alphabetical'] = 'Sort by Name: Alphabetical';
	return $sortby;
}

function my_default_catalog_orderby( $sort_by ) {
	return 'popularity';
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'sip_custom_wc_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'sip_custom_wc_catalog_orderby' );
add_filter('woocommerce_default_catalog_orderby', 'my_default_catalog_orderby' );
//end of default alphabetical sorting option--------------------------------------------------------------------


// Remove 'You are here' texts in Genesis Framework breadcrumb
add_filter( 'genesis_breadcrumb_args', 'afn_breadcrumb_args' );
function afn_breadcrumb_args( $args ) {
	$args['labels']['prefix'] = '';
	return $args;
}

//products with lots of product variations weren't hiding unavailable attribute combinations. 
//Icreasing this threshold should help.
add_filter( 'woocommerce_ajax_variation_threshold', 'wc_ajax_variation_threshold' );
function wc_ajax_variation_threshold() {
	    return 175;
}

/*Did this so the attribute dropdown default text is now "select" when viewing variable products*/
add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'my_filter_dropdown_args', 10 );
function my_filter_dropdown_args( $args ) {
	    $args['show_option_none'] = 'Select';
		    return $args;
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'my_echo_qty_front_add_cart' );
 
function my_echo_qty_front_add_cart() {
	 echo '<div class="qty-label-text">Qty: </div>'; 
}



function my_custom_woocommerce_checkout_fields( $fields ) {
	//$logPtr = fopen('/webclient/default/bromilow/sites/v1.bromilow.com/logs/myLogs/invoice-testing-log.log', 'a');
	//fwrite($logPtr, print_r($fields, true) . '\n');
	//
	$address_2_text = 'Apartment, suite, unit, etc.';

	$fields['order']['order_comments']['placeholder'] = 'Greeting Card and Special Instructions';
	$fields['order']['order_comments']['label'] = 'Greeting Card and Special Instructions';

	$fields['billing']['billing_address_2']['label'] =  $address_2_text;
	$fields['billing']['billing_city']['label'] = 'City';//NOT WORKING FOR SOME REASON

	$fields['shipping']['shipping_address_2']['label'] = $address_2_text;
	$fields['shipping']['shipping_city']['label'] = 'City';
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'my_custom_woocommerce_checkout_fields' );

// define the woocommerce_after_cart callback 
function action_woocommerce_after_cart( $wccm_after_checkout ) { 
    echo '<div class="cart-disclaimer-container">' 
		. do_shortcode('[widget id="text-88"]') . '</div>';
}; 
         
// add the action 
add_action( 'woocommerce_after_cart', 'action_woocommerce_after_cart', 10, 1 ); 

// define the woocommerce_after_checkout_form callback 
function action_woocommerce_after_checkout_form( $wccm_after_checkout ) { 
    echo '<div class="checkout-disclaimer-container">' 
		. do_shortcode('[widget id="text-89"]') . '</div>';
}; 
         
// add the action 
add_action( 'woocommerce_after_checkout_form', 'action_woocommerce_after_checkout_form', 10, 1 );


/**
 * This widget area won't be displayed in full anywhere. Currently there are 2
 * widgets in this widget area that are displayed in different spots. This widget
 * area was created to provide Ginny with an easy way to edit the cart and checkout page
 * disclaimer text.
 */
function disclaimer_widgets_init() {

	register_sidebar( array(
		'name'          => 'Important Text for Customers',
		'id'            => 'disclaimers00',
		'description'	=> 'This widget area is for editing key pieces of text such as disclaimers and order email text.',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'disclaimer_widgets_init' );

// REMOVE WIDGET TITLE IF IT BEGINS WITH EXCLAMATION POINT
add_filter( 'widget_title', 'remove_widget_title' );
function remove_widget_title( $widget_title ) {
    if ( substr ( $widget_title, 0, 1 ) === '!' )
        return;
    else
        return ( $widget_title );
}

function action_woocommerce_email_order_details( $order, $sent_to_admin, $plain_text, $email ) { 
    //$log = fopen('/webclient/default/bromilow/sites/v3.bromilow.com/logs/testLog.txt', 'a+');
	//fwrite($log, 'email: ' . print_r($email, true) );
	if ($email->id === 'customer_completed_order' ) {
		echo do_shortcode('[widget id="text-90"]');
			
	} else if ($email->id === 'customer_processing_order' ) {
		echo do_shortcode('[widget id="text-91"]');	
	}
}; 
         
// add the action 
add_action( 'woocommerce_email_order_details', 'action_woocommerce_email_order_details', 10, 4 );



//The following declaration targets field 7 (paragraph text) in form 1 (contact form). We aren't 
//going to allow messages with "http://", "https://", an "href" from an a tag. 
//we are also blocking .ru and .com since these are common TLDs seen with spam links
add_filter( 'gform_field_validation_1_7', 'no_urls_allowed', 10, 4 );

function no_urls_allowed ($result, $value, $form, $field) {
	if ( $result['is_valid'] && 
		(  stripos($value, 'http://') !== false 
		|| stripos($value, 'https://') !== false 
		|| stripos($value, 'href=') !== false
		|| stripos($value, '.ru') !== false  
		|| stripos($value, '.com')!== false ))  {
		$result['is_valid'] = false;
		$result['message'] = 'No URLs allowed in your message.';
	}
	return $result;
}

// we don't want certain products to be included in shipping calculations. We set
// them as virtual to achive this.
function filter_woocommerce_product_needs_shipping( $this_is_virtual, $instance ) {

	$free_shipping_product_ids = array(
		'gift_cert' => 1246,
	);

	if (in_array( $instance->get_parent_id(), $free_shipping_product_ids ) ) {
		return 0;
	}
    return $this_is_virtual; 
}; 
         
// add the filter 
add_filter( 'woocommerce_product_needs_shipping', 'filter_woocommerce_product_needs_shipping', 10, 2 );

//we'll always ask for shipping address since gift certificates will be considered virtual 
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_true', 50 );



// On product category pages, change button text for out of stock items. Works for
//simple products out of stock and variable products where all variations are out
//of stock
add_filter( 'woocommerce_product_add_to_cart_text', 'out_of_stock_text', 5, 2 );
function out_of_stock_text( $button_text, $product ) {
	if( ! $product->is_in_stock() /*&& ! $product->is_type('variable')*/ ) {
        $button_text = __("Out of stock", "woocommerce");
    }
    return $button_text;
}


/**
 * Disable out of stock variations
 * https://github.com/woocommerce/woocommerce/blob/826af31e1e3b6e8e5fc3c1004cc517c5c5ec25b1/includes/class-wc-product-variation.php
 * @return Boolean
 */
function wcbv_variation_is_active( $active, $variation ) {
 if( ! $variation->is_in_stock() ) {
 return false;
 }
 return $active;
}
add_filter( 'woocommerce_variation_is_active', 'wcbv_variation_is_active', 10, 2 );



function custom_varition_stock_status_column( $columns ) {
	$columns['some-variations-out-of-stock'] = 'Some variations out of stock?';
	return $columns;
}
add_action( 'manage_edit-product_columns', 'custom_varition_stock_status_column', 10, 2 );


function populate_custom_variation_stock_status_column( $column, $postid ) {
    $some_variations_out_of_stock = FALSE;
	$all_out_of_stock_variation_ids = '';
	if ( $column == 'some-variations-out-of-stock') {
        $product = wc_get_product($postid);
		if ($product->is_type('variable') ) {
			$variations = $product->get_available_variations();
			foreach ($variations as $variation) {
				if ($variation['is_in_stock'] != 1 ) {
					$some_variations_out_of_stock = TRUE;
					$all_out_of_stock_variation_ids = $all_out_of_stock_variation_ids . '<br>' . $variation['variation_id']; 
				}
			}
			if ($some_variations_out_of_stock) {
				echo 'Yes. The variations with the following variation IDs are out of stock:<br>' . $all_out_of_stock_variation_ids;
				return;
			}
		}
	echo 'No';
    }
}
add_action( 'manage_product_posts_custom_column', 'populate_custom_variation_stock_status_column', 10, 2 );


//new "Payment Complete?" column for order overview admin page. Indicates
//whether or not heartland payment was captured.
function wc_new_order_column( $columns ) {
    $columns['payment-complete'] = 'Payment Complete?';
    return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'wc_new_order_column' );

//Populates the new "Payment Complete?" column by looking to see if payment 
//was captured within the order notes.
function wc_orders_populate_payment_complete_column( $column ) {
	if ('payment-complete' === $column){
		global $post;
		$order_id = $post->ID;
		
		if (has_captured_payment ($order_id)) {
			echo 'Yes';
		}	
		else {
			echo 'No';
		}
	}
}
add_action( 'manage_shop_order_posts_custom_column', 'wc_orders_populate_payment_complete_column' );


//custom function to get order notes. Found here
//https://stackoverflow.com/questions/43463217/get-order-notes-from-a-woocommerce-order-wc-order-object
function get_private_order_notes( $order_id){
    global $wpdb;

    $table_perfixed = $wpdb->prefix . 'comments';
    $results = $wpdb->get_results("
        SELECT *
        FROM $table_perfixed
        WHERE  `comment_post_ID` = $order_id
        AND  `comment_type` LIKE  'order_note'
    ");

    foreach($results as $note){
        $order_note[]  = array(
            'note_id'      => $note->comment_ID,
            'note_date'    => $note->comment_date,
            'note_author'  => $note->comment_author,
            'note_content' => $note->comment_content,
        );
    }
    return $order_note;
}

//returns true or false indicating whether or not payment has been captured
//for an order
function has_captured_payment($order_id) {
	$payment_cap_string = 'SecureSubmit payment captured';	
	$order_notes = get_private_order_notes($order_id);

	foreach ($order_notes as $note) {
		foreach ($note as $note_details) {
			if (stripos( $note_details, $payment_cap_string ) !== FALSE ) {
				return TRUE;			
			}
		}
	}
	return FALSE;
}
function order_notes_have_string($order_id, $string) {
	$search_string = $string;	
	$order_notes = get_private_order_notes($order_id);

	foreach ($order_notes as $note) {
		foreach ($note as $note_details) {
			if (stripos( $note_details, $search_string ) !== FALSE ) {
				return TRUE;			
			}
		}
	}
	return FALSE;
}
//When an admin sets an orders status as completed, it will revert it back to processing if the payment was not captured.
function revert_completed_status_if_no_payment($order_id) {
    $order = new WC_Order($order_id);
	$failed_status_change_note = 'Order was marked as completed before payment was captured. Reverting to processing status';

	if ( ! has_captured_payment($order_id) ){
		$order->add_order_note($failed_status_change_note);
		$order->update_status( 'processing' );
	}
}
add_action( 'woocommerce_order_status_completed', 'revert_completed_status_if_no_payment', 0, 1);

//Don't send order complete email if payment was not captured.
function conditionally_send_wc_complete_email( $whether_enabled, $object ) { 
	if ( is_null($object)  ) {
		return $whether_enabled;
	}
	
	if ( !has_captured_payment( $object->get_order_number()) ) {
		return FALSE;
	}
	return $whether_enabled;
}
add_filter( 'woocommerce_email_enabled_customer_completed_order', 'conditionally_send_wc_complete_email', 10, 2 );


// This function is meant to mark an order as completed once 
// payment has been captured. It uses the order notes to see
// if payment was captured or not. 
function complete_order_on_payment_capture( $process_shop_order, $int0 ) { 
    $order = new WC_Order($process_shop_order);
	$custom_order_complete_notice = 'TAGONLINE: Payment capture detected. Marking order as complete';

	if ( has_captured_payment($process_shop_order) 
	  && strcasecmp($order->get_status(), 'completed') !== 0
	  && !order_notes_have_string($process_shop_order, $custom_order_complete_notice)  ){
		$order->add_order_note($custom_order_complete_notice);
		$order->update_status ('completed');
	}
}; 
         
// The priority is 100 to make sure this is done after everything 
// woocommerce itself may do. If you need to update this priority check
// 'grep -ri woocommerce_process_shop_order_meta plugins/woocommerce' first
add_action( 'woocommerce_process_shop_order_meta', 'complete_order_on_payment_capture', 100, 2 ); 


// Code to clear default shipping option. Test using incognito/private browser window.
add_filter( 'woocommerce_shipping_chosen_method', '__return_false', 99);



/**
 * Exclude products from categories within store. The categories
 * to ignore are determined by the 'hide' custom field for all product
 * categories
 */
function bromilow_pre_get_posts_query( $q ) {
	
	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false ;
	$cat_args = array(
    	'orderby'    => $orderby,
	    'order'      => $order,
	    'hide_empty' => $hide_empty,
		'meta_key' => 'hide',
		'meta_value' => '"Yes"',
		'meta_compare' => 'LIKE'
	);
 
	$hidden_product_categories = get_terms( 'product_cat', $cat_args );
	$slugs_of_product_categories_to_exclude = array();

	foreach($hidden_product_categories as $cat){
		array_push($slugs_of_product_categories_to_exclude, $cat->slug);
	}	
	
    $tax_query = (array) $q->get( 'tax_query' );
    $tax_query[] = array(
           'taxonomy' => 'product_cat',
           'field' => 'slug',
           'terms' => $slugs_of_product_categories_to_exclude, 
           'operator' => 'NOT IN'
    );

    $q->set( 'tax_query', $tax_query );
}
add_action( 'woocommerce_product_query', 'bromilow_pre_get_posts_query' );  


/**
 * Got the below code from https://gist.github.com/doubleedesign/6169b5a3d678298aa062fe5f343d57c4
 * Add reCaptcha to checkout form
 * Note: Can't place within the payment part of the form, WooCommerce 
 * just won't show it, choose an appropriate action to add it to accordingly
 * @param $checkout
 */
function bromilow_show_me_the_checkout_captcha($checkout) {
	echo '<div class="g-recaptcha" data-sitekey="6Le5ya0UAAAAAJDc3WzLFt_uGJwQLlqTiYMlhee0"></div>';
}
add_action('woocommerce_checkout_order_review', 'bromilow_show_me_the_checkout_captcha', 18);
/**
 * Validate reCaptcha
 */
function bromilow_process_recaptcha() {

	$postdata = $_POST['g-recaptcha-response'];
	$verified_recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Le5ya0UAAAAALaXUdiv6yuhtHgDkTZ7DXIR5JPu&response='.$postdata);
	$response = json_decode($verified_recaptcha);

	if(!$response->success) {
		wc_add_notice('Please verify that you are not a robot' ,'error');
	}
}
add_action('woocommerce_checkout_process', 'bromilow_process_recaptcha');

