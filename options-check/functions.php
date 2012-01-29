<?php

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});
	
	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}
	
});
</script>
 
<?php
}

/* 
 * This is an example showing how to do some advanced jQuery selection.
 * This one shows/hides different inputs based on the data-attr value.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_advanced_jquery');

function optionsframework_advanced_jquery() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {
	
	var check = jQuery('input[type=checkbox]');
	var radio = jQuery('input[type=radio]');
	var dataElements = jQuery('[data-attr]');
	var dataAttr = dataElements.data('attr');
	var $dataAttr = '#'+dataAttr;

	//Cycles through the page finding the radio and checkbox elements, 
	//retrieves their data-attr value, then hides the elements with the id of 
	//that value.
	dataElements.each(function(){
		var $this = jQuery(this);
		var dataAttr = '#'+$this.data('attr');
		var $dataAttr = jQuery(dataAttr);

		$dataAttr.stop().hide();	

		if ($this.is(':checked')){
			$dataAttr.stop().show();

			if($this.is(radio)){
				$this.addClass('selected');
				
			}
		}

	});

	//For checkboxes, toggles the display of the element it's selected to hide. 
	//For Radio buttons, finds the closest element with class .selected, grabs 
	//its data-attr, hides that element, then shows the element it's selected.	
	dataElements.click(function(){
		var $this = jQuery(this);
		var dataAttr = '#'+$this.data('attr');
		var $dataAttr = jQuery(dataAttr);

		if ($this.is(check)){
			$dataAttr.toggle();
		}
		else if($this.is(radio)){
				var selected = $this.siblings('.selected');	
				var itemToHide = jQuery('#'+selected.data('attr'));
				itemToHide.stop().hide();
				$dataAttr.stop().show();
				selected.removeClass('selected');
				$this.addClass('selected');
		}
	});

});
</script>
 
<?php
}
/* 
 * This is an example of how to override a default filter
 * for 'text' sanitization and use a different one.
 */

/*

add_action('admin_init','optionscheck_change_santiziation', 100);

function optionscheck_change_santiziation() {
	remove_filter( 'of_sanitize_text', 'sanitize_text_field' );
	add_filter( 'of_sanitize_text', 'of_sanitize_text_field' );
}

function of_sanitize_text_field($input) {
	global $allowedtags;
	$output = wp_kses( $input, $allowedtags);
	return $output;
}

*/

/* 
 * This is an example of how to override the default location and name of options.php
 * In this example it has been renamed options-renamed.php and moved into the folder extensions
 */

/*

add_filter('options_framework_location','options_framework_location_override');

function options_framework_location_override() {
	return array('/extensions/options-renamed.php');
}

*/

/* 
 * Turns off the default options panel from Twenty Eleven
 */
 
add_action('after_setup_theme','remove_twentyeleven_options', 100);

function remove_twentyeleven_options() {
	remove_action( 'admin_menu', 'twentyeleven_theme_options_add_page' );
}
