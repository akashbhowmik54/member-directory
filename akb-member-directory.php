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

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use MemberDirectory\Core\Plugin;

function member_directory_init() {
    Plugin::init();
}
add_action( 'plugins_loaded', 'member_directory_init' );
