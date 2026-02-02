<?php
/**
 * Plugin Name: Holray Units
 * Plugin URI:  https://holray.co.uk/
 * Description: Sync your Holray units with your WordPress website along with quick and easy availability search functions.
 * Version: 1.1.0
 * Author: Holray Systems Limited
 * Author URI:  https://holray.co.uk/
 * Requires at least: 6.0
 * Tested up to: 6.9
 * Requires PHP: 7.4
 * Text Domain: holray-units
 * Domain Path: /languages
 */

use Holray\Plugin\Plugin;

if (!defined('ABSPATH')) { exit; }
define('HOLRAY_UNITS_VERSION', '1.1.S');
define('HOLRAY_UNITS_PATH', plugin_dir_path(__FILE__));
define('HOLRAY_UNITS_URL', plugin_dir_url(__FILE__));

include_once HOLRAY_UNITS_PATH . "/vendor/autoload.php";

$holray_plugin = new Plugin;