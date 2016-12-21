<?php

/**
 * Plugin Name:       WP Listings
 * Plugin URI:        
 * Description:       A simple listings plugin to use for example buy and sell
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       wp-listings
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('WPLISTINGS_PATH', plugin_dir_path(__FILE__));
define('WPLISTINGS_URL', plugins_url('', __FILE__));
define('WPLISTINGS_TEMPLATE_PATH', WPLISTINGS_PATH . 'templates/');

load_plugin_textdomain('wp-listings', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once WPLISTINGS_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once WPLISTINGS_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new WpListings\Vendor\Psr4ClassLoader();
$loader->addPrefix('WpListings', WPLISTINGS_PATH);
$loader->addPrefix('WpListings', WPLISTINGS_PATH . 'source/php/');
$loader->register();

// Start application
new WpListings\App();
