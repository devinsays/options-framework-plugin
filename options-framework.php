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

/* Basic plugin definitions */

define('OPTIONS_FRAMEWORK_VERSION', '0.1');
define('OPTIONS_FRAMEWORK_URL', plugin_dir_url( __FILE__ ));

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a little plugin, don't mind me.";
	exit;
}

/* If the user can't edit theme options, no use running this plugin */

add_action('init', 'optionsframework_rolescheck' );

function optionsframework_rolescheck () {
	if ( current_user_can('edit_theme_options') ) {
		// If the user can edit theme options, let the fun begin!
		add_action('admin_menu', 'optionsframework_add_page');
		add_action('admin_init', 'optionsframework_init' );
		add_action( 'admin_init', 'optionsframework_mlu_init' );
	}
}

/* Register plugin activation hooks */

register_activation_hook(__FILE__,'optionsframework_activation_hook');

function optionsframework_activation_hook() {
	register_uninstall_hook( __FILE__, 'optionsframework_delete_options' );
}

/* When uninstalled, deletes options */

register_uninstall_hook( __FILE__, 'optionsframework_delete_options' );

function optionsframework_delete_options() {

	$optionsframework_settings = get_option('optionsframework');
	
	// Each theme saves its data in a seperate option, which all gets deleted
	$knownoptions = $optionsframework_settings['knownoptions'];
	if ($knownoptions) {
		foreach ($knownoption as $key) {
			delete_option($key);
		}
	}
	delete_option('optionsframework');
}

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

	// Include the required files
	require_once dirname( __FILE__ ) . '/options-interface.php';
	require_once dirname( __FILE__ ) . '/options-medialibrary-uploader.php';
	
	// Loads the options array from the theme
	if ( $optionsfile = locate_template( array('options.php') ) ) {
		require_once($optionsfile);
	}
	else if (file_exists( dirname( __FILE__ ) . '/options.php' ) ) {
		require_once dirname( __FILE__ ) . '/options.php';
	}
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Updates the unique option id in the database if it has changed
	optionsframework_option_name();
	
	// Gets the unique id, returning a default if it isn't defined
	$option_name = $optionsframework_settings['id'];
	
	// Set the option defaults in case they have changed
	optionsframework_setdefaults();
	
	// Registers the settings fields and callback
	register_setting('optionsframework', $option_name, 'optionsframework_validate' );
}

/* 
 * Adds default options to the database if they aren't already present.
 * May update this later to load only on plugin activation, or theme
 * activation since most people won't be editing the options.php
 * on a regular basis.
 *
 * http://codex.wordpress.org/Function_Reference/add_option
 *
 */

function optionsframework_setdefaults() {

	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	/* 
	 * Each theme will hopefully have a unique id, and all of its options saved
	 * as a separate option set.  We need to track all of these option sets so
	 * it can be easily deleted if someone wishes to remove the plugin and
	 * its associated data.  No need to clutter the database.  
	 *
	 */
	 
	if ( isset($optionsframework_settings['knownoptions']) ) {
		$knownoptions =  $optionsframework_settings['knownoptions'];
		if ( !in_array($option_name, $knownoptions) ) {
			array_push( $knownoptions, $option_name );
			$optionsframework_settings['knownoptions'] = $knownoptions;
			update_option('optionsframework', $optionsframework_settings);
		}
	} else {
		$newoptionname = array($option_name);
		$optionsframework_settings['knownoptions'] = $newoptionname;
		update_option('optionsframework', $optionsframework_settings);
	}
	
	// Gets the default options data from the array in options.php
	$options = optionsframework_options();
		
	// If the options haven't been added to the database yet, they are added now
	foreach ($options as $option) {
	
		if ( ($option['type'] != 'heading') && ($option['type'] != 'info') ) {
			$option_id = preg_replace("/\W/", "", strtolower($option['id']) );
			
			// wp_filter_post_kses for strings
			if (isset($option['std' ]) ) {
				if ( !is_array($option['std' ]) ) {
					$value = wp_filter_post_kses($option['std']);
				} else {
					foreach ($option['std' ] as $key => $value) {
						$values[$option_id . '_' . $key] = wp_filter_post_kses($value);
						$optionarray[$key] = wp_filter_post_kses($value);
					}
					$values[$option_id] = $optionarray;
				}
			} else {
				$value = '';
			}
		}
	}
	
	if ( isset($values) ) {
		add_option($option_name, $values);
	}
}

/* Add a subpage called "Theme Options" to the appearance menu. */

if ( !function_exists( 'optionsframework_add_page' ) ) {
function optionsframework_add_page() {

	$of_page = add_submenu_page('themes.php', 'Theme Options', 'Theme Options', 'edit_theme_options', 'options-framework','optionsframework_page');
	
	// Adds actions to hook in the required css and javascript
	add_action("admin_print_styles-$of_page",'optionsframework_load_styles');
	add_action("admin_print_scripts-$of_page", 'optionsframework_load_scripts');
	
}
}

/* Loads the CSS */

function optionsframework_load_styles() {
	wp_enqueue_style('admin-style', OPTIONS_FRAMEWORK_URL.'css/admin-style.css');
	wp_enqueue_style('color-picker', OPTIONS_FRAMEWORK_URL.'css/colorpicker.css');
}	

/* Loads the javascript */

function optionsframework_load_scripts() {

	// Inline scripts from options-interface.php
	add_action('admin_head', 'of_admin_head');
	
	// Enqueued scripts
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('color-picker', OPTIONS_FRAMEWORK_URL.'js/colorpicker.js', array('jquery'));
	wp_enqueue_script('options-custom', OPTIONS_FRAMEWORK_URL.'js/options-custom.js', array('jquery'));
}

function of_admin_head() {

	// Hook to add custom scripts
	do_action( 'optionsframework_custom_scripts' );
}

/* 
 * Builds out the options panel.
 *
 * If we were using the Settings API as it was likely intended we would use
 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
 * we'll call our own custom optionsframework_fields.  See options-interface.php
 * for specifics on how each individual field is generated.
 *
 * Nonces are provided using the settings_fields()
 *
 */

if ( !function_exists( 'optionsframework_page' ) ) {
function optionsframework_page() {

	// Get the theme name so we can display it up top
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Display message when options are reset/updated
	$message = '';
	
	if ( isset($optionsframework_settings['message']) ) {
		$message = $optionsframework_settings['message'];
	}
	
	if ( $message == 'reset' ) {
		$message = __( 'Options reset.' );
	}
	if ( $message == 'update' ) {
		$message = __( 'Options updated.' );
	}
	
	// Sets the option back to null, so the message doesn't display on refresh
	$optionsframework_settings['message'] = '';
	update_option('optionsframework',$optionsframework_settings)
	?>
    
	<div class="wrap">
    <?php screen_icon( 'themes' ); ?>
	<h2><?php _e('Theme Options'); ?></h2>
    
    <?php if ($message) { ?>
    	<div id="message" class="updated fade"><p><strong><?php echo $message; ?></strong></p></div>
    <?php } ?>
    
    <div id="of_container">
       <form action="options.php" method="post">
	  <?php settings_fields('optionsframework'); ?>

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
        <div class="of_admin_bar">
			<input type="submit" class="button-primary" name="update" value="<?php _e( 'Save Options' ); ?>" />
            </form>
            
            <input type="submit" class="reset-button button-secondary" name="reset" value="<?php _e('Restore Defaults')?>" onclick="return confirm('Click OK to reset. Any theme settings will be lost!');"/>
		</div>
<div class="clear"></div>
</div> <!-- / #container -->  
</div> <!-- / .wrap -->

<?php
}
}

/* 
 * Data sanitization!
 *
 * This runs after the submit/reset button has been clicked and
 * validates the inputs.
 *
 */

if ( !function_exists( 'optionsframework_validate' ) ) {
function optionsframework_validate($input) {

	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	// If the reset button was clicked
	if (!empty($_REQUEST['reset'])) {
		delete_option($option_name);
		$optionsframework_settings['message'] = 'reset';
		update_option('optionsframework', $optionsframework_settings);
		header('Location: themes.php?page=options-framework');
		exit;
	}
	
	else
	
	{
	
	if (!empty($_REQUEST['update'])) {
	
		$clean = array();
	
		$optionsframework_settings['message'] = 'update';
		
		update_option('optionsframework', $optionsframework_settings);

		// Get the options array we have defined in options.php
		$options = optionsframework_options();
		
		foreach ($options as $option) {
			
			// Verify that the option has an id
			if ( isset ($option['id']) ) {
			
				// Checkbox data isn't sent if it's unchecked, so we'll default it to false
				if ( ($option['type'] == 'checkbox') && !isset($input[($option['id'])]) ) {
					$input[($option['id'])] = 'false';
				}
				
				// Verify that there's a value in the $input
				if (isset ($input[($option['id'])]) ) {
			
					switch ( $option['type'] ) {
					
					// If it's a checkbox, make sure it's either true or false
					case ($option['type'] == 'checkbox'):
						if ( ($input[($option['id'])]) == 'true' )
							$clean[($option['id'])] = 'true';
						else {
							$clean[($option['id'])] = 'false';
						}
					break;
					
					// If it's a multicheck
					case ($option['type'] == 'multicheck'):
						$i = 0;
						foreach ($option['options'] as $key ) {
							// Make sure the key is lowercase and without spaces
							$key = ereg_replace("[^A-Za-z0-9]", "", strtolower($key));
							// Check that the option isn't null
							if (!empty($input[($option['id']. '_' . $key)])) {
								// If it's not null, make sure it's true, add it to an array
								$checkboxarray[$key] = 'true';
							}
							else {
								$checkboxarray[$key] = 'false';
							}
						}
						// Take all the items that were checked, and set them as the main option
						if (!empty($checkboxarray)) {
							$clean[($option['id'])] = $checkboxarray;
						}
					break;
					
					// If it's a typography option
					case ($option['type'] == 'typography') :
						$typography_id = $option['id'];
						$clean[$typography_id] = array(
							'size' => $input[$typography_id .'_size'],
							'face' => $input[$typography_id .'_face'],
							'style' => $input[$typography_id .'_style'],
							'color' => $input[$typography_id .'_color']);
					break;
					
					// If it's a background option
					case ($option['type'] == 'background') :
						$background_id = $option['id'];
						if ( empty($input[$background_id .'_color']) ) {
							$clean[$background_id .'_color'] = '';
						}
						if ( empty($input[$background_id .'_image']) ) {
							$clean[$background_id .'_image'] = '';
						}
						$clean[$background_id] = array(
							'color' => $input[$background_id .'_color'],
							'image' => $input[$background_id .'_image'],
							'repeat' => $input[$background_id .'_repeat'],
							'position' => $input[$background_id .'_position'],
							'attachment' => $input[$background_id .'_attachment']);
					break;
					
					// If it's a select make sure it's in the array we supplied
					case ($option['type'] == 'select') :
						if ( array_key_exists( $input[($option['id'])], $option['options'] ) ) {
							$clean[($option['id'])] = $input[($option['id'])];
						}
					break;
					
					// For the remaining options, strip any tags that aren't allowed in posts
					default:
						// Cleans html characters
						$input[($option['id'])] = sanitize_text_field($input[($option['id'])]);
						// http://codex.wordpress.org/Function_Reference/wp_filter_post_kses
						$clean[($option['id'])] = wp_filter_post_kses( $input[($option['id'])] );
					}
					
				} // end switch
				
			} // end isset $input
			
		} // end isset $id
		
	} // end foreach
	
	if ( isset($clean) ) {
		return $clean; // Return validated input
	}
	
	} // end $_REQUEST['update']
	
}
}


/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 */
	
if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = 'false') {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
	
	if ( !empty($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}