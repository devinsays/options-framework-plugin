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
jQuery(document).ready(function() {
	
	// Fade out the save message
	jQuery('#message').delay(1000).fadeOut(1000);
			
	// Color Picker
	<?php
	$settings = get_option('of_theme_options');
	$options = of_options();
			
	foreach($options as $option){ 
			
		if($option['type'] == 'color' OR $option['type'] == 'typography') {
			if($option['type'] == 'typography') {
				$option_id = $option['id'] . '_color';
			}
			else {
				$option_id = $option['id'];
			}
			$color = $settings[($option['id'])];
			?>
			 jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '<?php echo $color; ?>');    
			 jQuery('#<?php echo $option_id; ?>_picker').ColorPicker({
				color: '<?php echo $color; ?>',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					//jQuery(this).css('border','1px solid red');
					jQuery('#<?php echo $option_id; ?>_picker').children('div').css('backgroundColor', '#' + hex);
					jQuery('#<?php echo $option_id; ?>_picker').next('input').attr('value','#' + hex);					
				}
			});
		<?php } } ?> 
});
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
}

/**
 * Generates the options fields that are used in the form.
 */

function optionsframework_fields() {

	$settings = get_option('of_theme_options');
    $options = of_options();
        
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		
		// Wrap all options
		 if ( ($value['type'] != "heading") && ($value['type'] != "info") )
		 {
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
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
			$output .= '<input id="'. $value['id'] .'" class="of-input" name="of_theme_options['. $value['id'] .']" type="'. $value['type'] .'" value="'. $val .'" />';
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
				$std = $settings[($value['id'])];
				if( $std != "") { $ta_value = stripslashes( $std ); }
				$output .= '<textarea id="'. $value['id'] .'" class="of-input" name="of_theme_options['. $value['id'] .']" cols="'. $cols .'" rows="8">'.$ta_value.'</textarea>';
		break;
		
		// Small Select Box
		case ($value['type'] == 'select' || $value['type'] == 'select2'):
			$output .= '<select class="of-input" name="of_theme_options['. $value['id'] .']" id="'. $value['id'] .'">';
			$select_value = $settings[($value['id'])];
			
			foreach ($value['options'] as $option) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				 $output .= '<option'. $selected .'>';
				 $output .= $option;
				 $output .= '</option>';
			 } 
			 $output .= '</select>';
		break;
		
		// Radio Box
		case "radio":
			 $select_value = $settings[($value['id'])];
				   
			 foreach ($value['options'] as $key => $option) 
			 {
				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; } 
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="of-input of-radio" type="radio" name="of_theme_options['. $value['id'] .']" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			}
		break;
		
		// Checkbox
		case "checkbox": 
		   $std = $value['std'];  
		   $saved_std = $settings[($value['id'])];
		   $checked = '';
			
			if (!empty($saved_std)) {
				if($saved_std == 'true') {
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
			$output .= '<input id="'. $value['id'] .'" class="checkbox of-input" type="checkbox" name="of_theme_options['. $value['id'] .']" value="true" '. $checked .' />';
		break;
		
		// Multicheck
		case "multicheck":
			$std =  $value['std'];
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="of_theme_options['. $value['id'] .']" />';	
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
			$output .= '<input id="'. $of_key .'" class="checkbox of-input" type="checkbox" name="of_theme_options['. $of_key .']" value="true" '. $checked .' /><label for="'. $of_key .'">'. $option .'</label><br />';						
			}
		break;
		
		// Color picker
		case "color":
			$val = $value['std'];
			$stored  = $settings[($value['id'])];
			if ( isset($stored) ) { $val = $stored; }
			$output .= '<div id="' . $value['id'] . '_picker" class="colorSelector"><div></div></div>';
			$output .= '<input class="of-color" name="of_theme_options['. $value['id'] .']" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break; 
		
		// Uploader
		case "upload":
			$val = $value['std'];
			$stored  = $settings[($value['id'])];
			if ( $stored != "") { $val = $stored; }
			$output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
		break;
		
		// Typography
		case 'typography':	
		
			//Set main option
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="of_theme_options['. $value['id'] .']" />';
			$typography_stored = $settings[($value['id'])];
			
			if (empty($typography_stored)) {
				$typography_stored = $value['std'];
			}
			
			/* Font Size */ 
			$output .= '<select class="of-typography of-typography-size" name="of_theme_options['. $value['id'].'_size]" id="'. $value['id'].'_size">';
			for ($i = 9; $i < 71; $i++) { 
				$size = $i.'px';
				if ($typography_stored['size'] == $size) { $selected = ' selected="selected"'; }
				$output .= '<option value="'. $i .'px" ' . selected($typography_stored['size'], $size, false) . '>'. $i .'px</option>'; 
			}
			$output .= '</select>';
		
			/* Font Face */
			$output .= '<select class="of-typography of-typography-face" name="of_theme_options['. $value['id'].'_face]" id="'. $value['id'].'_face">';
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
			
			/* Font Weight */
			$output .= '<select class="of-typography of-typography-style" name="of_theme_options['. $value['id'].'_style]" id="'. $value['id'].'_style">';
			$styles = array('normal'=>'Normal',
							'italic'=>'Italic',
							'bold'=>'Bold',
							'bold italic'=>'Bold Italic');
							
			foreach ($styles as $i=>$style) {
				$output .= '<option value="'. $i .'" ' . selected($typography_stored['style'], $i, false) . '>'. $style .'</option>';		
			}
			$output .= '</select>';
			
			/* Font Color */			
			$output .= '<div id="' . $value['id'] . '_color_picker" class="colorSelector"><div style="background-color:'.$typography_stored['color'].'"></div></div>';
			$output .= '<input class="of-color of-typography of-typography-color" name="of_theme_options['. $value['id'].'_color]" id="'. $value['id'] .'_color" type="text" value="'. $typography_stored['color'] .'" />';

		break;  
		
		// Image Selectors
		case "images":
			$i = 0;
			$select_value = $settings[($value['id'])];
				   
			foreach ($value['options'] as $key => $option) { 
			 $i++;

				 $checked = '';
				 $selected = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; } 
				    } else {
						if ($value['std'] == $key) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'of-radio-img-selected'; }
						else { $checked = ''; }
					}	
				
				$output .= '<span>';
				$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio" value="'.$key.'" name="of_theme_options['. $value['id'] .']" '.$checked.' />';
				$output .= '<div class="of-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" onClick="document.getElementById(\'of-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';
				
			}
		break;   
		
		// Info
		case "info":
			$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			if ( $value['name'] )  { $output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n"; }
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