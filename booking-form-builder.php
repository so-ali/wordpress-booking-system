<?php
/*
 * Plugin Name:       Booking Form Builder
 * Plugin URI:        https://github.com/so-ali/booking-form-builder/
 * Description:       Booking system.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Ali Soleymani
 * Author URI:        https://github.com/so-ali
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bfb
 * Domain Path:       /languages
 */

define('BFB_DIR', plugin_dir_path(__FILE__));
define('BFB_INCLUDES_DIR', realpath(plugin_dir_path(__FILE__) . '/includes'));
define('BFB_RESOURCES_DIR', realpath(plugin_dir_path(__FILE__) . '/resources'));
define('BFB_RESOURCES_URL', plugin_dir_url(__FILE__) . 'resources');
define('BFB_VERSION', '1.0.0');

/**
 * Require plugin bootstrap.
 */
require_once BFB_INCLUDES_DIR . '/Bootstrap.php';