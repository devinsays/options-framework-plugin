<?php

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

function of_get_option($name, $default = 'false') {
	
	// Gets the unique option id, returning a default if it isn't defined
	$option_name = get_option('optionsframework[id]','optionsframework_theme_options');
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( !empty($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}