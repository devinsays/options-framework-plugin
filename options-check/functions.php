<?php

/* 
 * Helper function to return the theme option.
 * If no value has been saved, returns $default.
 *
 * This code allows the theme to work without errors
 * even if the Options Framework has been disabled
 * or uninstalled.
 *
 */

function of_get_option($name, $default) {
	if ( get_option('of_theme_options') ) {
		$options = get_option('of_theme_options');
	}
	
	if ( !empty($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}


