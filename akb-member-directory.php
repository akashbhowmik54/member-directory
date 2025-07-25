<?php
/**
 * Plugin Name: AKB Member Directory
 * Description: A custom plugin to manage members and teams with contact functionality.
 * Version: 1.0.0
 * Author: Akash Kumar Bhowmik
 * Text Domain: akb-member-directory
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'AKB_MEMBER_DIRECTORY_URL', plugin_dir_url( __FILE__ ) );
define( 'AKB_MEMBER_DIRECTORY_PATH', plugin_dir_path( __FILE__ ) );

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use MemberDirectory\Core\Plugin;
use MemberDirectory\Core\Router;
use MemberDirectory\Core\Activator;
use MemberDirectory\Core\Deactivator;

// Activation/Deactivation
register_activation_hook(__FILE__, [Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Deactivator::class, 'deactivate']);

function member_directory_init() {
    Plugin::init();
    (new Router())->register();

    // Flush rewrite rules on activation
    if (get_option('member_directory_flush_rewrite_rules_flag')) {
        flush_rewrite_rules();
        delete_option('member_directory_flush_rewrite_rules_flag');
    }
}
add_action( 'plugins_loaded', 'member_directory_init' );

// Set the flag on activation
register_activation_hook(__FILE__, function() {
    add_option('member_directory_flush_rewrite_rules_flag', true);
});
