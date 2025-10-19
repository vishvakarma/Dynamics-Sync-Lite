<?php
/**
 * Plugin Name: Dynamics Sync Lite
 * Plugin URI: https://github.com/vishvakarma/Dynamics-Sync-Lite
 * Description: WordPress integration with Microsoft Dynamics 365 for contact management
 * Version: 1.0.0
 * Author: Vishvakarma
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DSL_VERSION', '1.0.0');
define('DSL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DSL_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files

// Initialize plugin
function dsl_init() {
    // Plugin initialization code
}
add_action('plugins_loaded', 'dsl_init');