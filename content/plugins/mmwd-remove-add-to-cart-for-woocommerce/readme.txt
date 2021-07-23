=== MMWD Remove Add To Cart for WooCommerce ===
Contributors: mcgregormedia
Donate link: https://paypal.me/mcgregormedia
Requires at least: 4.7
Tested up to: 5.8
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Removes all Add to Cart buttons throughout a WooCommerce website without affecting anything else hooked into the Add to Cart actions.

== Description ==
This plugin removes all Add to Cart buttons throughout a WooCommerce website by using the woocommerce_is_purchasable filter rather than the Add to Cart actions, thus not affecting anything else hooked into these actions. This plugin also offers the option to remove prices in product archive and single product pages. *Please note removing prices may not work with themes that do not use the standard WooCommerce hooks.*

= How to use this plugin =

Navigate to Admin > WooCommerce > Settings > Products and click the Remove Add to Cart settings tab. Use the checkboxes to enable the Remove Add to Cart and/or Remove Prices options. Removing prices may not work with themes that do not use the standard WooCommerce hooks.

= WooCommerce compatibility =

This plugin is compatible with WooCommerce 3.x, 4.x and 5.x versions.

= GDPR information =

This plugin does not gather or store any personal data.

== Installation ==
Install as usual by going to Plugins > Add New and searching for MMWD Remove Add To Cart for WooCommerce or download the plugin file and upload to your-site.com/wp-content/plugins.

== Changelog ==
1.4.18 30-06-2021
UPDATED: Tested up to WordPress 5.8
UPDATED: compatibility with WooCommerce 5.5

1.4.17 08-03-2021
UPDATED: Tested up to WordPress 5.7
UPDATED: compatibility with WooCommerce 5.1

1.4.16 24-11-2020
UPDATED: Tested up to WordPress 5.6

1.4.15 11-11-2020
UPDATED: compatibility with WooCommerce 4.7

1.4.14 15-10-2020
UPDATED: compatibility with WooCommerce 4.6.x

1.4.13 08-08-2020
UPDATED: Tested up to WordPress 5.5
UPDATED: Tested up to WooCommerce 4.4

1.4.12 13-07-2020
UPDATED: Tested up to WooCommerce 4.3

1.4.11 03-06-2020
UPDATED: Tested up to WooCommerce 4.2

1.4.10 26-02-2020
UPDATED: Tested up to WooCommerce 4.0
UPDATED: Tested up to WordPress 5.4

1.4.9 24-01-2020
UPDATED: WC tested up to tags for WooCommerce 3.9.x

1.4.8 24-10-2019
UPDATED: Tested up to WordPress 5.3

1.4.7 09-10-2019
FIXED: rearranged hook order to honour mmwd_remove_price option

1.4.6 13-09-2019
ADDED: compatibility with variable products

1.4.5 13-08-2019
UPDATED: WC tested up to tags for WooCommerce 3.7.x

1.4.4 01-05-2019
ADDED: Requires PHP tag
UPDATED: WC tested up to tags for WooCommerce 3.6.x
UPDATED: Tested up to WordPress 5.2

1.4.3 15-02-2019
UPDATED: Tested up to WordPress 5.1

1.4.2 19-12-2018
UPDATED: WC tested up to tags for WooCommerce 3.5.x

1.4.1 16-05-2018
UPDATED: WC tested up to tags for WooCommerce 3.4.x
UPDATED: Tested up to WordPress 5.0

1.4.0 27-04-2018
ADDED: GDPR notice in ReadMe

1.3.1 08-02-2018
UPDATED: WC requires at least and WC tested up to tags for WooCommerce 3.3.x


1.3.0 12-10-2017
ADDED: WC requires at least and WC tested up to tags for WooCommerce 3.2+
UPDATED: .pot file
FIXED: typo in readme.txt

1.2.0 16-08-2017
ADDED: check to see if WooCommerce is activated on plugin activation
ADDED: note to reflect theme issues
ADDED: .pot file for translation
TWEAK: edited description to make the usage instructions clearer
REMOVED: erroneous remove_actions

1.1.0 21-03-2017
ADDED: option to remove prices too, following user feedback

1.0.1 20-03-2017
FIXED: some users received a php warning when the plugin was in use

1.0.0 15-12-2016
Initial release