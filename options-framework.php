<?php
/*
Plugin Name: Options Framework
Plugin URI: http://www.wptheming.com
Description: A framework for building theme options.
Version: 0.1
Author: Devin Price
Author URI: http://www.wptheming.com
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('OPTIONS_FRAMEWORK_VERSION', '0.1');
define('OPTIONS_FRAMEWORK_URL', plugin_dir_url( __FILE__ ));

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a little plugin, don't mind me.";
	exit;
}

/* Let the fun begin! */

add_action('admin_init', 'optionsframework_init' );
add_action('admin_menu', 'optionsframework_add_page');

/* 
 * Creates the settings in the database by looping through the array
 * we supplied in options.php.  This is a neat way to do it since
 * we won't have to save settings for headers, descriptions, or arguments-
 * and it makes it a little easier to change and set up in my opinion.
 *
 * Read more about the Settings API in the WordPress codex:
 * http://codex.wordpress.org/Settings_API
 *
 */

function optionsframework_init() {

	/* Include the required files */
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-medialibrary-uploader.php';
	
	/* Loads the options array from the theme */
	
	if ( $optionsfile = locate_template( array('options.php') ) ) {
		require_once($optionsfile);
	}
	else if (file_exists( dirname( __FILE__ ) . '/options.php' ) ) {
		require_once dirname( __FILE__ ) . '/options.php';
	}

	register_setting('theme_options', 'theme_options', 'theme_options_validate' );
	add_settings_section('theme_settings', 'Theme Settings', '', __FILE__);
	
	// Here's where we get that options data from the array
	$of_options = of_options();
	
	// No callback, optionsframework_fields will take care of it
	foreach ($of_options as $option) {
		// Each item in the multicheck gets saved on its own setting
		if ($option['type'] == 'multicheck') {
			foreach ($option['options'] as $key) {
				$checkbox_id = ereg_replace("[^A-Za-z0-9]", "", strtolower($option['id']. '_' . $key));
				$checkbox_name = ereg_replace("[^A-Za-z0-9]", "", strtolower($option['name']. '_' . $key));
				add_settings_field($checkbox_id, $checkbox_name, __FILE__, 'theme_settings');
			}
		}
		else {
			$opt_id = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['id']) );
			$opt_name = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['name']) );
			add_settings_field($opt_id, $opt_name, '', __FILE__, 'theme_settings');
		}
	}
}

/* Let's add a subpage called "Theme Options" to the appearance menu. */
 
function optionsframework_add_page() {

	$of_page = add_submenu_page('themes.php', $themename, 'Theme Options', 'edit_theme_options', 'options-framework','optionsframework_page');
	
	// Loads the required css and javascripts
	add_action("admin_print_styles-$of_page",'of_load_styles');
	add_action("admin_print_scripts-$of_page", 'of_load_scripts');
	
}

/* Load the required CSS */

function of_load_styles() {
	wp_enqueue_style('admin-style', OPTIONS_FRAMEWORK_URL.'css/admin-style.css');
	wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_URL.'css/colorpicker.css');
}	

/* 
 * Loads the javascripts required to make all those sweet effects.
 * You'll notice the inline script called by of_admin_head is actually
 * hanging out with the rest of the party in options-interface.php.
 *
 */

function of_load_scripts() {

	// Loads inline scripts in options-interface.php
	add_action('admin_head', 'of_admin_head');
	
	// Loads enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-input-mask', OPTIONS_FRAMEWORK_URL.'js/jquery.maskedinput-1.2.2.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_URL.'js/colorpicker.js', array('jquery'));
	
}

/* 
 * Let's build out the options panel.
 *
 * If we were using the Settings API as it was likely intended
 * we would call do_settings_sections here.  But as we don't want the
 * settings wrapped in a table, we'll call our own custom
 * optionsframework_fields.  Saunter over to options-interface.php
 * if you want to see how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

function optionsframework_page() {

	// Get the theme name so we can display it up top
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false;
	?>
    
	<div class="wrap">
    <?php screen_icon( 'themes' ); ?>
	<h2>Theme Options</h2>
    
    <?php 
	?>
    
    <?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div id="message" class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; ?>
    
    <div id="of_container">
       <form action="options.php" method="post">
	  <?php settings_fields('theme_options'); ?>

        <div id="header">
          <div class="logo">
            <h2><?php echo $themename; ?></h2>
          </div>
          <div class="clear"></div>
        </div>
        <div id="main">
        <?php $return = optionsframework_fields(); ?>
          <div id="of-nav">
            <ul>
              <?php echo $return[1]; ?>
            </ul>
          </div>
          <div id="content">
            <?php echo $return[0]; /* Settings */ ?>
          </div>
          <div class="clear"></div>
        </div>
        <div class="save_bar_top">
    	<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
       </div>
  </form>
<div class="clear"></div>
</div>  
</div>

<?php
}

/* 
 * Data sanitization!
 *
 * This runs after the submit button has been clicked and checks
 * the fields for stuff that's not supposed to be there.
 *
 */
 
function theme_options_validate($input) {

	// At the moment no sanitization is happening.  Just wait...
	return $input; // return validated input
}