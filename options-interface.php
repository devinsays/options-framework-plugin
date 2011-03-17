<?php

/**
 * Creates the options fields and inline javascript that will be used in the form.  It uses the array
 * defined in options.php to set up the fields, and the settings in get_option('of_theme_options')
 * to output the saved values.
 */

function of_admin_head() {

/**
 * Prints out the inline javascript needed for the colorpicker and choosing
 * the tabs in the panel.
 */
 
?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
	
		// Fade out the save message
		jQuery('#message').delay(1000).fadeOut(1000);
		
		// Color Picker
		$('.colorSelector').each(function(){
			var Othis = this; //cache a copy of the this variable for use inside nested function
			var initialColor = $(Othis).next('input').attr('value');
			$(this).ColorPicker({
			color: initialColor,
			onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
			},
			onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
			},
			onChange: function (hsb, hex, rgb) {
			$(Othis).children('div').css('backgroundColor', '#' + hex);
			$(Othis).next('input').attr('value','#' + hex);
		}
		});
		}); //end color picker
	});//end document ready functions
</script>
        
<script type="text/javascript">
	jQuery(document).ready(function(){
			
	jQuery('.group').hide();
	jQuery('.group:first').fadeIn();
	jQuery('.group .collapsed').each(function(){
		jQuery(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if (jQuery(this).hasClass('last')) {
					jQuery(this).removeClass('hidden');
						return false;
					}
					jQuery(this).filter('.hidden').removeClass('hidden');
					});
           		});
           					
	jQuery('.group .collapsed input:checkbox').click(unhideHidden);
				
	function unhideHidden(){
		if (jQuery(this).attr('checked')) {
			jQuery(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else {
			jQuery(this).parent().parent().parent().nextAll().each( 
			function(){
				if (jQuery(this).filter('.last').length) {
					jQuery(this).addClass('hidden');
					return false;		
					}
				jQuery(this).addClass('hidden');
				});
           					
			}
		}
				
		jQuery('.of-radio-img-img').click(function(){
			jQuery(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
			jQuery(this).addClass('of-radio-img-selected');		
		});
		
		jQuery('.of-radio-img-label').hide();
		jQuery('.of-radio-img-img').show();
		jQuery('.of-radio-img-radio').hide();
		jQuery('#of-nav li:first').addClass('current');
		jQuery('#of-nav li a').click(function(evt) {
		jQuery('#of-nav li').removeClass('current');
			jQuery(this).parent().addClass('current');
			var clicked_group = jQuery(this).attr('href');
			jQuery('.group').hide();
			jQuery(clicked_group).fadeIn();
			evt.preventDefault();
			}); 	 		
		});	
</script>

<?php

// Hook to add custom scripts
do_action( 'optionsframework_custom_scripts' );
	
}

/**
 * Generates the options fields that are used in the form.
 */

function optionsframework_fields() {

	$optionsframework_settings = get_option('optionsframework');

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	$settings = get_option($option_name);
    $options = optionsframework_options();
        
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		
		// Wrap all options
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div id="section-' . $value['id'] .'" class="section section-'.$value['type'].' '. $class .'">'."\n";
			$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";

		 }
		 
		$select_value = ''; 
		                                
		switch ( $value['type'] ) {
		
		// Basic text input
		case 'text':
			if ( isset($settings[($value['id'])]) ) {
				$val = $settings[($value['id'])];
			}
			else {
				$val = $value['std'];
			}
			$output .= '<input id="'. $value['id'] .'" class="of-input" name="'.$option_name.'['.$value['id'].']" type="'. $value['type'] .'" value="'. $val .'" />';
		break;
		
		// Textarea
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['std'])) {
				$ta_value = $value['std']; 
				
				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}
				
			}
			
			if (isset ( $settings[($value['id'])] ) ) {
				$ta_value = stripslashes( $settings[($value['id'])] );
			}
			
			$output .= '<textarea id="'. $value['id'] .'" class="of-input" name="'.$option_name.'['.$value['id'].']" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
		break;
		
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="'.$option_name.'['.$value['id'].']" id="'. $value['id'] .'">';
			
			if (isset ($settings[($value['id'])] ) ) {
				$select_value = $settings[($value['id'])];
			}
			
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if(isset ($select_value) ) {
					 if ( $select_value == $key) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $key) { $selected = ' selected="selected"'; }
				 }
				 $output .= '<option'. $selected .' value="' . $key . '">';
				 $output .= $option;
				 $output .= '</option>';
			 } 
			 $output .= '</select>';
		break;

		
		// Radio Box
		case "radio":
			if (isset ($settings[($value['id'])]) ) {
			 	$select_value = $settings[($value['id'])];
			 }
				   
			 foreach ($value['options'] as $key => $option) {
				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; } 
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="of-input of-radio" type="radio" name="'.$option_name.'['.$value['id'].']" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			}
		break;
		
		// Checkbox
		case "checkbox":
			
			$checked = '';
			$std = 'false';
						
			if ( isset( $settings[($value['id'])] ) ) {
			 	$std = $settings[($value['id'])];
			}
		   
			if ( $std == 'true') {
				$checked = 'checked="checked"';
			}
			
			$output .= '<input id="'. $value['id'] .'" class="checkbox of-input" type="checkbox" name="'.$option_name.'['.$value['id'].']" value="true" '. $checked .' />';
		break;
		
		// Multicheck
		case "multicheck":
			$std =  $value['std'];
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="'.$option_name.'['.$value['id'].']" />';	
			foreach ($value['options'] as $key => $option) {						 
				$of_key = $value['id'] . '_' . $key;
				
				if ( isset($settings[$of_key]) ) {
					$saved_std = $settings[$of_key];
					if ($saved_std == 'true') {
						$checked = 'checked="checked"';
					}
					else {
				   		$checked = '';
				}
			}
			else {
			   if ( $std == 'true') {
			   		$checked = 'checked="checked"';
				} else {
					$checked = '';
				}
			}
			$output .= '<input id="'. $of_key .'" class="checkbox of-input" type="checkbox" name="' . $option_name . '[' . $of_key .']" value="true" '. $checked .' /><label for="'. $of_key .'">'. $option .'</label><br />';						
			}
		break;
		
		// Color picker
		case "color":
			$val = $value['std'];
			if (isset ($settings[($value['id'])]) ) {
				$stored  = $settings[($value['id'])];
			}
			if ( isset($stored) ) { $val = $stored; }
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div style="background-color:'.$val.'"></div></div>';
			$output .= '<input class="of-color" name="'.$option_name.'['.$value['id'].']" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break; 
		
		// Uploader
		case "upload":
			$val = $value['std'];
			if (isset ($settings[($value['id'])]) ) {
				$val  = $settings[($value['id'])];
			}
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
		break;
		
		// Typography
		case 'typography':	
		
			// Set main option
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="'.$option_name.'['.$value['id'].']" />';
			$typography_stored = $settings[($value['id'])];
			
			if (empty($typography_stored)) {
				$typography_stored = $value['std'];
			}
			
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
			
			foreach ($faces as $i=>$face) {
				$output .= '<option value="'. $i .'" ' . selected($typography_stored['face'], $i, false) . '>'. $face .'</option>';
			}			
			$output .= '</select>';	
			
			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'_style]" id="'. $value['id'].'_style">';
			$styles = array('normal'=>'Normal',
							'italic'=>'Italic',
							'bold'=>'Bold',
							'bold italic'=>'Bold Italic');
							
			foreach ($styles as $i=>$style) {
				$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
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
			$background_stored = $settings[($value['id'])];
			
			if (empty($background_stored)) {
				$background_stored = $value['std'];
			}
			
			// Background Color
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color:'.$background_stored['color'].'"></div></div>';
			$output .= '<input class="of-color of-background of-background-color" name="'.$option_name.'['.$value['id'].'_color]" id="'. $value['id'] .'_color" type="text" value="'. $background_stored['color'] .'" />';
			
			// Background Image
			$val = $value['std'];
			$stored = $settings[($value['id'])];
			if ( $stored != "") { $val = $stored; }
			
			// New AJAX Uploader using Media Library
			$output .= optionsframework_medialibrary_uploader( $value['id'] . '_image', $background_stored['image'], null );
			if ($background_stored['image']=='') {$hide = ' hide ';} else { $hide=''; }
			$output .= '<div class="of-background-properties' . $hide . '">';
			
			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="'.$option_name.'['.$value['id'].'_repeat]" id="'. $value['id'].'_repeat">';
			$repeats = array("no-repeat"=>"No Repeat","repeat-x"=>"Repeat Horizontally","repeat-y"=>"Repeat Vertically","repeat"=>"Repeat All");
			
			foreach ($repeats as $i=>$repeat) {
				$output .= '<option value="'. $i .'" ' . selected($background_stored['repeat'], $i, false) . '>'. $repeat .'</option>';
			}
			$output .= '</select>';
			
			// Background Position
			$output .= '<select class="of-background of-background-position" name="'.$option_name.'['.$value['id'].'_position]" id="'. $value['id'].'_position">';
			$positions = array("top left"=>"Top Left","top center"=>"Top Center","top right"=>"Top Right","center left"=>"Middle Left","center center"=>"Middle Center","center right"=>"Middle Right","bottom left"=>"Bottom Left","bottom center"=>"Bottom Center","bottom right"=>"Bottom Right");
			
			foreach ($positions as $i=>$position) {
				$output .= '<option value="'. $i .'" ' . selected($background_stored['position'], $i, false) . '>'. $position .'</option>';
			}
			$output .= '</select>';
			
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="'.$option_name.'['.$value['id'].'_attachment]" id="'. $value['id'].'_attachment">';
			$attachments = array("scroll"=>"Scroll Normally","fixed"=>"Fixed in Place");
			foreach ($attachments as $i=>$attachment) {
				$output .= '<option value="'. $i .'" ' . selected($background_stored['attachment'], $i, false) . '>'. $attachment .'</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
		
		break; 
		
		// Image Selectors
		case "images":
			$i = 0;
			if (isset($settings[($value['id'])]) ) {
				$select_value = $settings[($value['id'])];
			}
				   
			foreach ($value['options'] as $key => $option) { 
				$i++;
				$checked = '';
				$selected = '';
				if ($select_value != '') {
					if ( $select_value == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
				} else {
					if ($value['std'] == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
					elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
					elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
					else { $checked = ''; }
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
			$jquery_click_hook = ereg_replace("[^A-Za-z0-9]", "", strtolower($value['name']) );
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