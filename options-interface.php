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
			
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div id="section-' . $value['id'] .'" class="section section-'.$value['type'].' '. $class .'">'."\n";
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
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
			$output .= '<input id="'. $value['id'] .'" class="of-input" name="'.$option_name.'['.$value['id'].']" type="'. $value['type'] .'" value="'. $val .'" />';
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
			
			$output .= '<textarea id="'. $value['id'] .'" class="of-input" name="'.$option_name.'['.$value['id'].']" cols="'. $cols .'" rows="8">'.$val.'</textarea>';
		break;
		
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="'.$option_name.'['.$value['id'].']" id="'. $value['id'] .'">';
			
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if( $val != '' ) {
					 if ( $val == $key) { $selected = ' selected="selected"';} 
			     }
				 $output .= '<option'. $selected .' value="' . $key . '">';
				 $output .= $option;
				 $output .= '</option>';
			 } 
			 $output .= '</select>';
		break;

		
		// Radio Box
		case "radio":
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				if($val != '') {
					if ( $val == $key) { $checked = 'checked="checked"'; } 
				} 
				$name = $option_name .'['. $value['id'] .']';
				$id = $name .'-'. $key;
				$output .= '<input class="of-input of-radio" type="radio" name="'. $name .'" id="'. $id .'" value="'. $key .'" '. $checked .' /><label for="'. $id .'">'. $option .'</label><br />';
			}
		break;
		
		// Checkbox
		case "checkbox": 
		
			$checked = '';
		   
			if ( $val == 'true') {
				$checked = 'checked="checked"';
			}
			
			$output .= '<input id="'. $value['id'] .'" class="checkbox of-input" type="checkbox" name="'.$option_name.'['.$value['id'].']" value="true" '. $checked .' />';
		break;
		
		// Multicheck
		case "multicheck":
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="'.$option_name.'['.$value['id'].']" />';
			foreach ($value['options'] as $key => $option) {
				$checkbox_name = $option;
				$option = preg_replace('/\W/', '', strtolower($key));
				$checkbox_id = $option_name.'['.$value['id'].'_'. $option .']';
				$checked = '';
				
			    if ( isset($val[$option]) ) {
					if ( $val[$option] == 'true') {
			   			$checked = 'checked="checked"';
					}
				}
				
			$output .= '<input id="'. $checkbox_id .'" class="checkbox of-input" type="checkbox" name="'. $checkbox_id .'" value="true" '. $checked .' /><label for="'. $checkbox_id .'">'. $checkbox_name .'</label><br />';						
			}
		break;
		
		// Color picker
		case "color":
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div style="background-color:'.$val.'"></div></div>';
			$output .= '<input class="of-color" name="'.$option_name.'['.$value['id'].']" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break; 
		
		// Uploader
		case "upload":
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
		break;
		
		// Typography
		case 'typography':	
		
			// Set main option
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="'.$option_name.'['.$value['id'].']" />';
			$typography_stored = $val;
			
			// Font Size
			$output .= '<select class="of-typography of-typography-size" name="'.$option_name.'['.$value['id'].'_size]" id="'. $value['id'].'_size">';
			for ($i = 9; $i < 71; $i++) { 
				$size = $i.'px';
				if ($typography_stored['size'] == $size) { $selected = ' selected="selected"'; }
				$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $size, false) . '>'. $i .'px</option>'; 
			}
			$output .= '</select>';
		
			// Font Face
			$output .= '<select class="of-typography of-typography-face" name="'.$option_name.'['.$value['id'].'_face]" id="'. $value['id'].'_face">';
			$faces = array('arial'=>'Arial',
							'verdana'=>'Verdana, Geneva',
							'trebuchet'=>'Trebuchet',
							'georgia' =>'Georgia',
							'times'=>'Times New Roman',
							'tahoma'=>'Tahoma, Geneva',
							'palatino'=>'Palatino',
							'helvetica'=>'Helvetica*' );
			
			foreach ($faces as $key => $face) {
				$output .= '<option value="'. $key .'" ' . selected($typography_stored['face'], $key, false) . '>'. $face .'</option>';
			}			
			$output .= '</select>';	
			
			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'_style]" id="'. $value['id'].'_style">';
			
			$styles = array('normal'=>'Normal',
							'italic'=>'Italic',
							'bold'=>'Bold',
							'bold italic'=>'Bold Italic');
							
			foreach ($styles as $key => $style) {
				$output .= '<option value="'. $key .'" ' . selected($typography_stored['style'], $key, false) . '>'. $style .'</option>';		
			}
			$output .= '</select>';
			
			// Font Color		
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color:'.$typography_stored['color'].'"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="'.$option_name.'['.$value['id'].'_color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';
			
		break;
		
		// Background
		case 'background':
		
			//Set main option
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="'.$option_name.'['.$value['id'].']" />';
			
			$background = $val;
			
			// Background Color
			if (!isset($background['color'])) {
				$background['color'] = '';
			}
			
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color:'.$background['color'].'"></div></div>';
			
			$output .= '<input class="of-color of-background of-background-color" name="'.$option_name.'['.$value['id'].'_color]" id="'. $value['id'] .'_color" type="text" value="'. $background['color'] .'" />';
			
			
			// Background Image - New AJAX Uploader using Media Library
			if (!isset($background['image'])) {
				$background['image'] = '';
			}
			
			$output .= optionsframework_medialibrary_uploader( $value['id'] . '_image', $background['image'], null );
			if ($background['image'] == '') {$hide = ' hide ';} else { $hide=''; }
			$output .= '<div class="of-background-properties' . $hide . '">';
			
			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="'.$option_name.'['.$value['id'].'_repeat]" id="'. $value['id'].'_repeat">';
			$repeats = array("no-repeat"=>"No Repeat","repeat-x"=>"Repeat Horizontally","repeat-y"=>"Repeat Vertically","repeat"=>"Repeat All");
			
			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="'. $key .'" ' . selected($background['repeat'], $key, false) . '>'. $repeat .'</option>';
			}
			$output .= '</select>';
			
			// Background Position
			$output .= '<select class="of-background of-background-position" name="'.$option_name.'['.$value['id'].'_position]" id="'. $value['id'].'_position">';
			$positions = array("top left"=>"Top Left","top center"=>"Top Center","top right"=>"Top Right","center left"=>"Middle Left","center center"=>"Middle Center","center right"=>"Middle Right","bottom left"=>"Bottom Left","bottom center"=>"Bottom Center","bottom right"=>"Bottom Right");
			
			foreach ($positions as $key=>$position) {
				$output .= '<option value="'. $key .'" ' . selected($background['position'], $key, false) . '>'. $position .'</option>';
			}
			$output .= '</select>';
			
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="'.$option_name.'['.$value['id'].'_attachment]" id="'. $value['id'].'_attachment">';
			$attachments = array("scroll"=>"Scroll Normally","fixed"=>"Fixed in Place");
			
			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="'. $key .'" ' . selected($background['attachment'], $key, false) . '>'. $attachment .'</option>';
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
					if ( $val == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
				}
				
				$output .= '<span>';
				$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="'.$option_name.'['.$value['id'].']" '.$checked.' />';
				$output .= '<div class="of-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';
			}
		break;   
		
		// Info
		case "info":
			$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			if ( isset($value['name']) )  { $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n"; }
			if ( $value['desc'] )  { $output .= '<p>'. $value['desc'] .'</p>'."\n"; }
			$output .= '<div class="clear"></div></div>'."\n";
		break;                       
		
		// Heading for Navigation
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<li><a title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'">'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;                                  
		}
		
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) { 
			if ( $value['type'] != "checkbox" ) 
				{ 
				$output .= '<br/>';
				}
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; } 
			$output .= '</div><div class="explain">'. $explain_value .'</div>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
		}
	}
    $output .= '</div>';
    return array($output,$menu);
}