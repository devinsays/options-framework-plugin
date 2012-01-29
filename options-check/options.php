<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_option( 'stylesheet' );
	$themename = preg_replace("/\W/", "_", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Test data
	$test_array = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
	
	// Multicheck Array
	$multicheck_array = array("one" => "French Toast", "two" => "Pancake", "three" => "Omelette", "four" => "Crepe", "five" => "Waffle");

	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1","five" => "1");
	
	// Background Defaults
	
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	// Advanced jQuery selection example
	
		// Multicheck Example
		$multicheck_show_array = array("one" => "Show box 1?", "two" => "Show box 2?");
		$multicheck_show_defaults = array("one" => "0", "two" => "0");
		$multicheck_show_data = array("one" => "section-box1", "two" => "section-box2");

		// Radio Select Example
		$first_radio_show_array = array("one" => "Reveal Input One","two" => "Reveal Input Two","three" => "Reveal Input Three",);
		$first_radio_show_data = array("one" => "section-input1", "two" => "section-input2", "three" => "section-input3");

		$second_radio_show_array = array("one" => "Reveal Textarea One","two" => "Reveal Textarea Two","three" => "Reveal Textarea Three",);
		$second_radio_show_data = array("one" => "section-textarea-1", "two" => "section-textarea-2", "three" => "section-textarea-3");


	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('stylesheet_directory') . '/images/';
		
	$options = array();
		
	$options = array(
	
				array( "name" => "Basic Settings",
						"type" => "heading"
					),
							
				array( "name" => "Input Text Mini",
						"desc" => "A mini text input field.",
						"id" => "example_text_mini",
						"std" => "Default",
						"class" => "mini",
						"type" => "text"
					),
				
				array( "name" => "Input Text",
						"desc" => "A text input field.",
						"id" => "example_text",
						"std" => "Default Value",
						"type" => "text",
					),
							
				array( "name" => "Textarea",
						"desc" => "Textarea description.",
						"id" => "example_textarea",
						"std" => "Default Text",
						"type" => "textarea",
					), 
						
				array( "name" => "Input Select Small",
						"desc" => "Small Select Box.",
						"id" => "example_select",
						"std" => "three",
						"type" => "select",
						"class" => "mini", //mini, tiny, small
						"options" => $test_array,
					),
						
				array( "name" => "Input Select Wide",
						"desc" => "A wider select box.",
						"id" => "example_select_wide",
						"std" => "two",
						"type" => "select",
						"options" => $test_array,
					),
						
				array( "name" => "Select a Category",
						"desc" => "Passed an array of categories with cat_ID and cat_name",
						"id" => "example_select_categories",
						"type" => "select",
						"options" => $options_categories,
					),
						
				array( "name" => "Select a Page",
						"desc" => "Passed an pages with ID and post_title",
						"id" => "example_select_pages",
						"type" => "select",
						"options" => $options_pages,
					),
						
				array( "name" => "Input Radio (one)",
						"desc" => "Radio select with default options 'one'.",
						"id" => "example_radio",
						"std" => "one",
						"type" => "radio",
						"options" => $test_array,
					),
							
				array( "name" => "Example Info",
						"desc" => "This is just some example information you can put in the panel.",
						"type" => "info",
					),
											
				array( "name" => "Input Checkbox",
						"desc" => "Example checkbox, defaults to true.",
						"id" => "example_checkbox",
						"std" => "1",
						"type" => "checkbox",
					),
						
				array( "name" => "Advanced Settings",
						"type" => "heading",
					),
						
				array( "name" => "Check to Show a Hidden Text Input",
						"desc" => "Click here and see what happens.",
						"id" => "example_showhidden",
						"type" => "checkbox",
					),
			
				array( "name" => "Hidden Text Input",
						"desc" => "This option is hidden unless activated by a checkbox click.",
						"id" => "example_text_hidden",
						"std" => "Hello",
						"class" => "hidden",
						"type" => "text",
					),
						
				array( "name" => "Uploader Test",
						"desc" => "This creates a full size uploader that previews the image.",
						"id" => "example_uploader",
						"type" => "upload",
					),
						
				array( "name" => "Example Image Selector",
						"desc" => "Images for layout.",
						"id" => "example_images",
						"std" => "2c-l-fixed",
						"type" => "images",
						"options" => array(
							'1col-fixed' => $imagepath . '1col.png',
							'2c-l-fixed' => $imagepath . '2cl.png',
							'2c-r-fixed' => $imagepath . '2cr.png')
					),
						
				array( "name" =>  "Example Background",
						"desc" => "Change the background CSS.",
						"id" => "example_background",
						"std" => $background_defaults, 
						"type" => "background",
					),
								
				array( "name" => "Multicheck",
						"desc" => "Multicheck description.",
						"id" => "example_multicheck",
						"std" => $multicheck_defaults, // These items get checked by default
						"type" => "multicheck",
						"options" => $multicheck_array,
					),
							
				array( "name" => "Colorpicker",
						"desc" => "No color selected by default.",
						"id" => "example_colorpicker",
						"std" => "",
						"type" => "color",
					),
						
				array( "name" => "Typography",
						"desc" => "Example typography.",
						"id" => "example_typography",
						"std" => array('size' => '12px','face' => 'verdana','style' => 'bold italic','color' => '#123456'),
						"type" => "typography",
					),			
					
				array( "name" => "Advanced jQuery Selection",
						"type" => "heading",
					),

				array( "name" => "Multicheck Select Example",
						"desc" => "Multicheck description.",
						"id" => "example_multicheck_select",
						"std" => $multicheck_show_defaults, // These items get checked by default
						"type" => "multicheck",
						"options" => $multicheck_show_array,
						"data" => $multicheck_show_data,
					),

				array( "name" => "Box 1",
						"desc" => "",
						"id" => "box1",
						"std" => "",
						"type" => "textarea",
					), 
					
				array( "name" => "Box 2",
						"desc" => "",
						"id" => "box2",
						"std" => "",
						"type" => "textarea",
					), 
					
				array( "name" => "First Radio Select Example",
						"desc" => "Radio select with default options 'one'.",
						"id" => "first_example_radio_select",
						"std" => "one",
						"class" => "display",
						"type" => "radio",
						"options" => $first_radio_show_array,
						"data" => $first_radio_show_data
					),
							
				array( "name" => "Input 1",
						"desc" => "",
						"id" => "input1",
						"std" => "",
						"type" => "text",
					), 

				array( "name" => "Input 2",
						"desc" => "",
						"id" => "input2",
						"std" => "",
						"type" => "text",
					), 
					
				array( "name" => "Input 3",
						"desc" => "",
						"id" => "input3",
						"std" => "",
						"type" => "text",
					), 

				array( "name" => "Second Radio Select Example",
						"desc" => "Radio select with default options 'one'.",
						"id" => "second_example_radio_select",
						"std" => "one",
						"class" => 'display',
						"type" => "radio",
						"options" => $second_radio_show_array,
						"data" => $second_radio_show_data
					),
							
				array( "name" => "Textarea 1",
						"desc" => "",
						"id" => "textarea-1",
						"std" => "",
						"type" => "textarea",
					), 

				array( "name" => "Textarea 2",
						"desc" => "",
						"id" => "textarea-2",
						"std" => "",
						"type" => "textarea",
					), 
					
				array( "name" => "Textarea 3",
						"desc" => "",
						"id" => "textarea-3",
						"std" => "",
						"type" => "textarea",
					), 
											
				array( "name" => "Hurray!",
						"desc" => "You revealed this information section! Now go and experiment yourself!",
						"type" => "info",
						"class" => '.hide-info'
					),
		);
	return $options;
}
