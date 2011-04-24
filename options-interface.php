<?php

/**
 * Generates the options fields that are used in the form.
 */

function optionsframework_fields() {

	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	if (isset($optionsframework_settings['id'])) {
		$option_name = $optionsframework_settings['id'];
	}
	else {
		$option_name = 'optionsframework';
	};

	$settings = get_option($option_name);
    $options = optionsframework_options();
        
    $counter = 0;
	$menu = '';
	$output = '';
	
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		$select_value = '';
		
		// Wrap all options
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {

			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );

			$id = 'section-' . $value['id'];

			$class = 'section ' . $id;
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			$output .= '<h3 class="heading">' . esc_html( $value['name'] ) . '</h3>' . "\n";
			$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
		 }
		
		// Set default value to $val
		if ( isset($value['std']) ) {
			$val = $value['std'];
		}
		
		// If the option is already saved, ovveride $val
		if ( ($value['type'] != 'heading') && ($value['type'] != 'info')) {
			if ( isset($settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
			}
		}
		                                
		switch ( $value['type'] ) {
		
		// Basic text input
		case 'text':
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break;
		
		// Textarea
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['options'])){
				$ta_options = $value['options'];
				if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
				} else { $cols = '8'; }
			}
			
			$val = stripslashes( $val );
			
			$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" cols="'. esc_attr( $cols ) . '" rows="8">' . esc_textarea( $val ) . '</textarea>';
		break;
		
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
			
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if( $val != '' ) {
					 if ( $val == $key) { $selected = ' selected="selected"';} 
			     }
				 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">';
				 $output .= $option;
				 $output .= '</option>';
			 } 
			 $output .= '</select>';
		break;

		
		// Radio Box
		case "radio":
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				if ( $val != '' ) {
					if ( $val == $key) { $checked = 'checked="checked"'; } 
				}
				$id = $option_name . '-' . $value['id'] .'-'. $key;
				$name = $option_name .'['. $value['id'] .']';
				$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" ' . $checked .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label><br />';
			}
		break;
		
		// Checkbox
		case "checkbox": 
		
			$checked = '';
		   
			if ( $val ) {
				$checked = 'checked="checked"';
			}
			
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" value="true" '. $checked . ' />';
		break;
		
		// Multicheck
		case "multicheck":
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" type="hidden" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" />';
			foreach ($value['options'] as $key => $option) {
				$label = $option;
				$option = preg_replace('/\W/', '', strtolower($key));

				$id = $option_name . '-' . $value['id'] . '-'. $option;
				$name = $option_name . '[' . $value['id'] . '_' . $option .']';

				$checked = '';

			    if ( isset($val[$option]) ) {
					if ( $val[$option] == true) {
			   			$checked = 'checked="checked"';
					}
				}

				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" value="true" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
			}
		break;
		
		// Color picker
		case "color":
			$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
			$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break; 
		
		// Uploader
		case "upload":
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
		break;
		
		// Typography
		case 'typography':	
		
			// Set main option
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" type="hidden" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" />';
			$typography_stored = $val;
			
			// Font Size
			$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '_size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
			for ($i = 9; $i < 71; $i++) { 
				$size = $i . 'px';
				$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
			}
			$output .= '</select>';
		
			// Font Face
			$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '_face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
			$faces = array('arial'=>'Arial',
							'verdana'=>'Verdana, Geneva',
							'trebuchet'=>'Trebuchet',
							'georgia' =>'Georgia',
							'times'=>'Times New Roman',
							'tahoma'=>'Tahoma, Geneva',
							'palatino'=>'Palatino',
							'helvetica'=>'Helvetica*' );

			foreach ($faces as $key => $face) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . $face . '</option>';
			}			
			$output .= '</select>';	

			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'_style]" id="'. $value['id'].'_style">';

			$styles = array('normal'=>'Normal',
							'italic'=>'Italic',
							'bold'=>'Bold',
							'bold italic'=>'Bold Italic');

			foreach ($styles as $key => $style) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
			}
			$output .= '</select>';

			// Font Color		
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '_color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';

		break;
		
		// Background
		case 'background':
		
			//Set main option
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" type="hidden" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" />';
			
			$background = $val;
			
			// Background Color
			if (!isset($background['color'])) {
				$background['color'] = '';
			}
			
			$output .= '<div id="' . esc_attr( $value['id'] . '_color_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
			
			$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '_color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';
			
			
			// Background Image - New AJAX Uploader using Media Library
			if (!isset($background['image'])) {
				$background['image'] = '';
			}
			
			$output .= optionsframework_medialibrary_uploader( $value['id'] . '_image', $background['image'], null );
			$class = 'of-background-properties';
			if ( '' == $background['image'] ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';
			
			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '_repeat]' ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
			$repeats = array("no-repeat"=>"No Repeat","repeat-x"=>"Repeat Horizontally","repeat-y"=>"Repeat Vertically","repeat"=>"Repeat All");
			
			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Position
			$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '_position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
			$positions = array("top left"=>"Top Left","top center"=>"Top Center","top right"=>"Top Right","center left"=>"Middle Left","center center"=>"Middle Center","center right"=>"Middle Right","bottom left"=>"Bottom Left","bottom center"=>"Bottom Center","bottom right"=>"Bottom Right");
			
			foreach ($positions as $key=>$position) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '_attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
			$attachments = array("scroll"=>"Scroll Normally","fixed"=>"Fixed in Place");
			
			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
		
		break; 
		
		// Image Selectors
		case "images":
			$i = 0;
				   
			foreach ($value['options'] as $key => $option) { 
				$i++;
				$checked = '';
				$selected = '';
				if ($val != '') {
					if ( $val == $key ) {
						$checked = ' checked="checked"';
						$selected = 'of-radio-img-selected';
					}
				}
				
				$output .= '<span>';
				$output .= '<input type="radio" id="' . esc_attr( 'of-radio-img-' . $value['id'] . $i ) . '" class="checkbox of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" ' . $checked . ' />';
				$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<img src="' . esc_url( $option ) . '" alt="" class="of-radio-img-img ' . $selected . '" onclick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';
			}
		break;   
		
		// Info
		case "info":
			$class = 'section';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
			if ( isset($value['name']) ) {
				$output .= '<h3 class="heading">' . esc_html( $value['name'] ) . '</h3>' . "\n";
			}
			if ( $value['desc'] ) {
				$output .= '<p>'. esc_html( $value['desc'] ) . '</p>' . "\n";
			}
			$output .= '<div class="clear"></div></div>' . "\n";
		break;                       
		
		// Heading for Navigation
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li><a title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a></li>';
			$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '"><h2>' . esc_html( $value['name'] ) . '</h2>' . "\n";
			break;
		}

		if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
			if ( $value['type'] != "checkbox" ) {
				$output .= '<br/>';
			}
			$explain_value = '';
			if ( ! isset( $value['desc'] ) ) {
				$explain_value = $value['desc'];
			}
			$output .= '</div><div class="explain">' . esc_html( $explain_value ) . '</div>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
		}
	}
    $output .= '</div>';
    return array($output,$menu);
}