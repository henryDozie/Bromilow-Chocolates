<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.                                             

if (file_exists($_SERVER["DOCUMENT_ROOT"] . '/content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", (empty($_SERVER["WFWAF_LOG_PATH"])) ? $_SERVER["DOCUMENT_ROOT"] . '../../../logs/wflogs/'  : $_SERVER["WFWAF_LOG_PATH"] );
    // See https://wordpress.stackexchange.com/questions/119064/what-should-i-use-instead-of-wp-content-dir-and-wp-plugin-dir
    // Should this not use WP_PLUGIN_DIR?  plugins_url() won't work outside of a plugin.
	include_once $_SERVER["DOCUMENT_ROOT"] . '/content/plugins/wordfence/waf/bootstrap.php';
}
?>
