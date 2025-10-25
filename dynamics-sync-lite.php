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
require_once DSL_PLUGIN_DIR . 'includes/class-oauth-handler.php';
require_once DSL_PLUGIN_DIR . 'includes/class-dynamics-api.php';
require_once DSL_PLUGIN_DIR . 'includes/class-settings.php';
require_once DSL_PLUGIN_DIR . 'admin/admin-settings.php';
require_once DSL_PLUGIN_DIR . 'public/contact-form-handler.php';

// Initialize plugin
function dsl_init() {
    // Plugin initialization code
}
add_action('plugins_loaded', 'dsl_init');

// Activation hook to ensure HTTPS
register_activation_hook(__FILE__, function(){
    if (!is_ssl()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Dynamics Sync Lite requires HTTPS');
    }
});

wp_enqueue_script('dsl-scripts', DSL_PLUGIN_URL.'public/public-scripts.js', ['jquery']);
wp_localize_script('dsl-scripts', 'ajaxurl', admin_url('admin-ajax.php'));