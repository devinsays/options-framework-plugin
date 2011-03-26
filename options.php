<?php

/**
* This file should only load if options.php isn't present in the theme.
*
*/

/* Defaults the settings to 'optionsframework' */

function optionsframework_option_name() {
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = 'optionsframework';
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Displays a message that options aren't available in the current theme
 *  
 */

function optionsframework_options() {

		$options[] = array( "name" => "Theme Options",
							"type" => "heading");
		
		$options[] = array( "name" => "No Options Are Loaded",
							"desc" => "Your theme doesn't appear to support the Options Framework yet.",
							"type" => "info");
							
		$options[] = array(  "desc" => "If this is a mistake, make sure that the file options.php is in your theme folder and that you have the correct theme activated.",
							"type" => "info");
							
		$options[] = array( "name" => "How to Set Up Options",
							"desc" => "If you are trying to set up new options for a theme, visit the <a href=\"http://wptheming.com/options-framework-plugin\" />Options Framework plugin page</a>.",
							"type" => "info");
								
	return $options;
}