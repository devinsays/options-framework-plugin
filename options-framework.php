<?php
/**
 * Options Framework
 *
 * @package   Options Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2013 WP Theming
 *
 * @wordpress-plugin
 * Plugin Name: Options Framework
 * Plugin URI:  http://wptheming.com
 * Description: A framework for building theme options.
 * Version:     1.6.0
 * Author:      Devin Price
 * Author URI:  http://wptheming.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: optionsframework
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
        die;
}

// Load the Options Framework classes
require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-options-framework-admin.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-options-interface.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-options-media-uploader.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-options-sanitize.php';

// Instantiate the main plugin class
$options_framework = new Options_Framework;
$options_framework->init();

// Instantiate the options page
$options_framework_admin = new Options_Framework_Admin;
$options_framework_admin->init();


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */

 /*
register_activation_hook( __FILE__, array( 'Options_Framework', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Options_Framework', 'deactivate' ) );
*/

/* Register plugin activation hooks */

/*
register_activation_hook( __FILE__,'optionsframework_activation_hook' );

function optionsframework_activation_hook() {
	register_uninstall_hook( __FILE__, 'optionsframework_delete_options' );
}

*/

/* When uninstalled, deletes options */

/*
register_uninstall_hook( __FILE__, 'optionsframework_delete_options' );

function optionsframework_delete_options() {

	$optionsframework_settings = get_option( 'optionsframework' );

	// Each theme saves its data in a seperate option, which all gets deleted
	$knownoptions = $optionsframework_settings['knownoptions'];
	if ( $knownoptions ) {
		foreach ( $knownoptions as $key ) {
			delete_option( $key );
		}
	}
	delete_option( 'optionsframework' );
	delete_user_meta( $user_id, 'optionsframework_ignore_notice', 'true' );
}

*/