<?php
/**
 * Plugin Name: NeuroBlock
 * Copyright (C) 2025 Papyrus - Starlight Pro Agency
 * Plugin URI: https://starlightproagency.com/neuroblock
 * Description: AI-powered generator to create custom Gutenberg blocks and WordPress pages with your own API
 * Version: 1.0.0
 * Author: Papyrus - Starlight Pro Agency
 * Author URI: https://starlightproagency.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: neuroblock
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('NEUROBLOCK_VERSION', '1.0.0');
define('NEUROBLOCK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NEUROBLOCK_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NEUROBLOCK_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once NEUROBLOCK_PLUGIN_DIR . 'includes/class-neuroblock-admin.php';
require_once NEUROBLOCK_PLUGIN_DIR . 'includes/class-neuroblock-api.php';
require_once NEUROBLOCK_PLUGIN_DIR . 'includes/class-neuroblock-blocks.php';
require_once NEUROBLOCK_PLUGIN_DIR . 'includes/class-neuroblock-security.php';

/**
 * Initialize the plugin
 */
function neuroblock_init() {
    // Load text domain for translations
    load_plugin_textdomain('neuroblock', false, dirname(NEUROBLOCK_PLUGIN_BASENAME) . '/languages');
    
    // Initialize admin interface
    if (is_admin()) {
        $admin = new NeuroBlock_Admin();
        $admin->init();
    }
    
    // Initialize blocks
    $blocks = new NeuroBlock_Blocks();
    $blocks->init();
}
add_action('plugins_loaded', 'neuroblock_init');

/**
 * Activation hook - Set up default plugin options
 */
function neuroblock_activate() {
    // Create default options
    add_option('neuroblock_api_provider', 'openai');
    add_option('neuroblock_api_key', '');
    add_option('neuroblock_model', 'gpt-4');
    add_option('neuroblock_max_tokens', 2000);
    add_option('neuroblock_temperature', 0.7);
    
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'neuroblock_activate');

/**
 * Deactivation hook
 */
function neuroblock_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'neuroblock_deactivate');

/**
 * Uninstall hook - Clean up plugin data
 */
function neuroblock_uninstall() {
    // Delete options
    delete_option('neuroblock_api_provider');
    delete_option('neuroblock_api_key');
    delete_option('neuroblock_model');
    delete_option('neuroblock_max_tokens');
    delete_option('neuroblock_temperature');
}
register_uninstall_hook(__FILE__, 'neuroblock_uninstall');