<?php

/**
* This file should only load if options.php isn't present in the theme. I've included sample options
* (commented out) in case you simply wanted to copy this file into your theme and get to work.
*
* When you create the "id" field, make sure it is all lowercase and doesn't contain spaces because this is
* how it is saved in the database.
*
*/

/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	// Delete the 3 lines directly below this, and uncomment the next one if
	// you are moving this into your theme folder
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = 'optionsframework';
	update_option('optionsframework', $optionsframework_settings);
	
	// $optionsframework_settings = get_option('optionsframework');
	// $optionsframework_settings['id'] = $themename;
	// update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {

		$options[] = array( "name" => "Theme Options",
							"type" => "heading");
		
		$options[] = array( "name" => "No Options Are Loaded",
							"desc" => "If you are seeing this, it means your theme isn't set up to use the Options Framework yet.",
							"type" => "info");
							
		$options[] = array(  "desc" => "If this is a mistake, make sure that the file options.php is in your theme folder, and that you have the correct theme activated.",
							"type" => "info");
							
		$options[] = array( "name" => "How to Set Up Options",
							"desc" => "If you are trying to set up new options for a theme, visit the <a href=\"http://wptheming.com/options-framework-plugin\" />Options Framework plugin page</a>.  Or, just copy options.php from this plugin's directory into your theme and read the comments.",
							"type" => "info");
							
	/*
	
	// Test data
	$options_select = array("one","two","three","four","five"); 
	$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
    $categories = get_categories();
    $options_categories = array();
	foreach($categories as $category) {
		$options_categories[$category->slug] = $category->name;	
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
	$options = array();
		
	$options[] = array( "name" => "Basic Settings",
						"type" => "heading");
							
	$options[] = array( "name" => "Input Text Mini",
						"desc" => "A mini text input field.",
						"id" => "example_text_mini",
						"std" => "Default",
						"class" => "mini",
						"type" => "text");
								
	$options[] = array( "name" => "Input Text",
						"desc" => "A text input field.",
						"id" => "example_text",
						"std" => "Default Value",
						"type" => "text");
							
	$options[] = array( "name" => "Textarea",
						"desc" => "Textarea description.",
						"id" => "example_textarea",
						"std" => "Default Text",
						"type" => "textarea"); 
						
	$options[] = array( "name" => "Input Select Small",
						"desc" => "Small Select Box.",
						"id" => "example_select",
						"std" => "three",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $options_select);  
						
	$options[] = array( "name" => "Input Select Wide",
						"desc" => "A wider select box.",
						"id" => "example_select_wide",
						"std" => "two",
						"type" => "select2",
						"options" => $options_select);
						
	$options[] = array( "name" => "Input Radio (one)",
						"desc" => "Radio select with default options 'one'.",
						"id" => "example_radio",
						"std" => "one",
						"type" => "radio",
						"options" => $options_radio);
							
	$options[] = array( "name" => "Example Info",
						"desc" => "This is just some example information you can put in the panel.",
						"type" => "info");
											
	$options[] = array( "name" => "Input Checkbox",
						"desc" => "Example checkbox, defaults to true.",
						"id" => "example_checkbox",
						"std" => "false",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Advanced Settings",
						"type" => "heading");
						
	$options[] = array( "name" => "Uploader Test",
						"desc" => "This creates a full size uploader that previews the image.",
						"id" => "example_uploader",
						"std" => "",
						"type" => "upload");
						
	$options[] = array( "name" => "Example Image Selector",
						"desc" => "Images for layout.",
						"id" => "example_images",
						"std" => "2c-l-fixed",
						"type" => "images",
						"options" => array(
							'1col-fixed' => $imagepath . '1col.png',
							'2c-r-fixed' => $imagepath . '2cr.png',
							'2c-l-fixed' => $imagepath . '2cl.png',
							'3c-fixed' => $imagepath . '3cm.png',
							'3c-r-fixed' => $imagepath . '3cr.png')
						);
								
	$options[] = array( "name" => "Multicheck",
						"desc" => "Multicheck description.",
						"id" => "example_multicheck",
						"std" => "two",
						"type" => "multicheck",
						"options" => $options_radio);
						
	$options[] = array( "name" => "Select with values",
						"desc" => "Choose the category name; save the category slug.",
						"id" => "example_category_select",
						"std" => "uncategorized",
						"type" => "select_with_values",
						"options" => $options_categories);
							
	$options[] = array( "name" => "Colorpicker",
						"desc" => "No color selected by default.",
						"id" => "example_colorpicker",
						"std" => "",
						"type" => "color");
						
	$options[] = array( "name" => "Typography",
						"desc" => "Example typography.",
						"id" => "example_typography",
						"std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
						"type" => "typography");
	*/		
	return $options;
}