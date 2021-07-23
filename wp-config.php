<?php
// =========================
// Enable Cache by WP Rocket
// ========================
/**
 *	This is commented out by default until WP Rocket is ready to be activated
 *		-- DBG
 */
 define( 'WP_CACHE', true );

// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	define( 'WP_LOCAL_DEV', true );
	include( dirname( __FILE__ ) . '/local-config.php' );
} else {
	define( 'WP_LOCAL_DEV', false );
	define( 'DB_NAME', $_SERVER['DB_NAME'] );
	define( 'DB_USER', $_SERVER['DB_USER'] );
	define( 'DB_PASSWORD', $_SERVER['DB_PASSWORD'] );
	define( 'DB_HOST', $_SERVER['DB_HOST'] ); // Probably 'localhost'
}

// ========================
// Completely override "home" and "siteurl" values in wp_options table, 
// taking values from Apache that should always be accurate.  However, 
// be aware that under some circumstances one must set in the virtual
// host "UseCanonicalName On" (and perhaps change these to SERVER_NAME?).
// ========================
$method = (empty($_SERVER['HTTPS'])) ? 'http://' : 'https://';
define('WP_HOME', $method . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', $method . $_SERVER['HTTP_HOST'] . '/wp');
//define('WP_SITEURL', '/wp');                                                                                                                                                                              

// ========================
// Custom Content Directory
// ========================
define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/content' );

define( 'WP_CONTENT_URL', $method . $_SERVER['HTTP_HOST'] . '/content' );

if ( !defined( 'REVISR_GIT_DIR' ) )
  define( 'REVISR_GIT_DIR', $_SERVER['DOCUMENT_ROOT'] . '' );





// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
//
// Or...try to get these from the server configuration (which is
// likely set from https://api.wordpress.org/secret-key/1.1/salt
// with changes.
// ==============================================================
define( 'AUTH_KEY',         (empty($_SERVER['AUTH_KEY'])) ?		'put your unique phrase here' :	$_SERVER['AUTH_KEY'] );
define( 'SECURE_AUTH_KEY',  (empty($_SERVER['SECURE_AUTH_KEY'])) ?	'put your unique phrase here' :	$_SERVER['SECURE_AUTH_KEY'] );
define( 'LOGGED_IN_KEY',    (empty($_SERVER['LOGGED_IN_KEY'])) ?	'put your unique phrase here' :	$_SERVER['LOGGED_IN_KEY'] );
define( 'NONCE_KEY',        (empty($_SERVER['NONCE_KEY'])) ?		'put your unique phrase here' :	$_SERVER['NONCE_KEY'] );
define( 'AUTH_SALT',        (empty($_SERVER['AUTH_SALT'])) ?		'put your unique phrase here' :	$_SERVER['AUTH_SALT'] );
define( 'SECURE_AUTH_SALT', (empty($_SERVER['SECURE_AUTH_SALT'])) ?	'put your unique phrase here' :	$_SERVER['SECURE_AUTH_SALT'] );
define( 'LOGGED_IN_SALT',   (empty($_SERVER['LOGGED_IN_SALT'])) ?	'put your unique phrase here' :	$_SERVER['LOGGED_IN_SALT'] );
define( 'NONCE_SALT',       (empty($_SERVER['NONCE_SALT'])) ?		'put your unique phrase here' :	$_SERVER['NONCE_SALT'] );

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix = (empty($_SERVER['TABLE_PREFIX'])) ? 'wp_' : $_SERVER['TABLE_PREFIX'];

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );


@ini_set( 'max_input_vars' , 5000 );

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
// define( 'SAVEQUERIES', true );
// define( 'WP_DEBUG', true );

// ======================================
// Load a Memcached config if we have one
// ======================================
if ( file_exists( dirname( __FILE__ ) . '/memcached.php' ) )
	$memcached_servers = include( dirname( __FILE__ ) . '/memcached.php' );

// ===========================================================================================
// This can be used to programatically set the stage when deploying (e.g. production, staging)
// ===========================================================================================
define( 'WP_STAGE', $_SERVER['WP_STAGE'] );
define( 'STAGING_DOMAIN', $_SERVER['WP_STAGING_DOMAIN'] ); // Does magic in WP Stack to handle staging domain rewriting

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
  define( 'ABSPATH', $_SERVER['DOCUMENT_ROOT'] . '/wp' );
require_once( ABSPATH . 'wp-settings.php' );


