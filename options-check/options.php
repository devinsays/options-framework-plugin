<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * 
 * When you create the "id" field, make sure it is all lowercase and doesn't contain spaces because this is
 * how it is saved in the database.
 *
 * It is also important to have a unique id.  This will ensure that it doesn't clash with other themes
 * that may have options defined and saved.  I would suggest using the theme name.
 *
 * For this example, I've prepended "optioncheck" to each id.
 *  
 */

if ( ! function_exists( 'of_options' ) ) {

	function of_options() {
	
		//Testing 
		
		$options_select = array("one","two","three","four","five"); 
		$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//If you use images, define a path for them
		$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
		//Options Array
		
		$options = array();
		
		$options[] = array( "name" => "Basic Settings",
							"type" => "heading");
								
		$options[] = array( "name" => "Input Text",
							"desc" => "A text input field.",
							"id" => "optioncheck_text",
							"std" => "Default Value",
							"type" => "text");
							
		$options[] = array( "name" => "Textarea",
							"desc" => "Textarea description.",
							"id" => "optioncheck_textarea",
							"std" => "Default Text",
							"type" => "textarea"); 
							
		$options[] = array( "name" => "Input Select Small",
							"desc" => "Small Select Box.",
							"id" => "optioncheck_select",
							"std" => "three",
							"type" => "select",
							"class" => "mini", //mini, tiny, small
							"options" => $options_select);  
							
		$options[] = array( "name" => "Input Select Wide",
							"desc" => "A wider select box.",
							"id" => "optioncheck_select_wide",
							"std" => "two",
							"type" => "select2",
							"options" => $options_select);
							
		$options[] = array( "name" => "Input Radio (one)",
							"desc" => "Radio select with default of 'one'.",
							"id" => "optioncheck_radio",
							"std" => "one",
							"type" => "radio",
							"options" => $options_radio);
							
		$options[] = array( "name" => "Example Info",
							"desc" => "This is just some example information you can put in the panel.",
							"type" => "info");
												
		$options[] = array( "name" => "Input Checkbox",
							"desc" => "Example checkbox, defaults to true.",
							"id" => "optioncheck_checkbox",
							"std" => "false",
							"type" => "checkbox");
							
		$options[] = array( "name" => "Advanced Settings",
							"type" => "heading");
							
		$options[] = array( "name" => "Uploader Test",
							"desc" => "This creates a full size uploader that previews the image.",
							"id" => "optioncheck_uploader",
							"std" => "",
							"type" => "upload");
							
		$options[] = array( "name" => "Example Image Selector",
							"desc" => "Images for layout.",
							"id" => "optioncheck_images",
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
							"id" => "optioncheck_multicheck",
							"std" => "two",
							"type" => "multicheck",
							"options" => $options_radio);
							
		$options[] = array( "name" => "Colorpicker",
							"desc" => "No color selected by default.",
							"id" => "optioncheck_colorpicker",
							"std" => "",
							"type" => "color");
							
		return $options;
	
	}
}