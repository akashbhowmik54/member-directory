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

function member_directory_init() {
    Plugin::init();
}
add_action( 'plugins_loaded', 'member_directory_init' );
