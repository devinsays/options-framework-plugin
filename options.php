<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * 
 * If we wanted to dive deeper in the code, we could define these straight up with add_settings_field,
 * but the array makes the set up a bit easier.
 *
 * I'm including this in the plugin for now just for testing.  Eventually there will be a check in the
 * the theme so that it can be loaded from there.
 */

if ( ! function_exists( 'of_options' ) ) {

	function of_options() {
	
		//Testing 
		
		$options_select = array("one","two","three","four","five"); 
		$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		// Options Array
		
		$options = array();
		
		$options[] = array( "name" => "Basic Settings",
							"type" => "heading");
								
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
							"desc" => "Radio select with default of 'one'.",
							"id" => "example_radio",
							"std" => "one",
							"type" => "radio",
							"options" => $options_radio);
							
												
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
							
		$options[] = array( "name" => "Second Uploader",
							"desc" => "This is just for testing to make sure that two can work.",
							"id" => "example_uploader2",
							"std" => "",
							"type" => "upload");
								
		$options[] = array( "name" => "Multicheck",
							"desc" => "Multicheck description.",
							"id" => "example_multicheck",
							"std" => "two",
							"type" => "multicheck",
							"options" => $options_radio);
							
		$options[] = array( "name" => "Colorpicker",
							"desc" => "No color selected.",
							"id" => "example_colorpicker",
							"std" => "",
							"type" => "color");
							
		return $options;
	
	}
}