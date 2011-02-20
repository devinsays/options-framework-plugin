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
			
		if($option['type'] == 'color' OR $option['type'] == 'typography' OR $option['type'] == 'border') {
			if($option['type'] == 'typography' OR $option['type'] == 'border') {
				$option_id = $option['id'];
				$temp_color = get_option($option_id);
				$option_id = $option['id'] . '_color';
				$color = $temp_color['color'];
			}
			else {
				$option_id = $option['id'];
				$color = $settings[($option['id'])];
			}
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
		
		//AJAX Upload
		jQuery('.image_upload_button').each(function(){
			
			var clickedObject = jQuery(this);
			var clickedID = jQuery(this).attr('id');	
			new AjaxUpload(clickedID, {
			  action: '<?php echo admin_url("admin-ajax.php"); ?>',
			  name: clickedID, // File upload name
			  data: { // Additional data to send
					action: 'woo_ajax_post_action',
					type: 'upload',
					data: clickedID },
			  autoSubmit: true, // Submit file after selection
			  responseType: false,
			  onChange: function(file, extension){},
			  onSubmit: function(file, extension){
					clickedObject.text('Uploading'); // change button text, when user selects file	
					this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
					interval = window.setInterval(function(){
						var text = clickedObject.text();
						if (text.length < 13){	clickedObject.text(text + '.'); }
						else { clickedObject.text('Uploading'); } 
					}, 200);
			  },
			  onComplete: function(file, response) {
			  			   
				window.clearInterval(interval);
				clickedObject.text('Upload Image');	
				this.enable(); // enable upload button
				
				// If there was an error
				if(response.search('Upload Error') > -1) {
					var buildReturn = '<span class="upload-error">' + response + '</span>';
					jQuery(".upload-error").remove();
					clickedObject.parent().after(buildReturn);				
					}
				else {
					var buildReturn = '<img class="hide woo-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
					jQuery(".upload-error").remove();
					jQuery("#image_" + clickedID).remove();	
					clickedObject.parent().after(buildReturn);
					jQuery('img#image_'+clickedID).fadeIn();
					clickedObject.next('span').fadeIn();
					clickedObject.parent().prev('input').val(response);
				}
			  }
			});
			
		});
			
		//AJAX Remove (clear option value)
		jQuery('.image_reset_button').click(function(){
			
			var clickedObject = jQuery(this);
			var clickedID = jQuery(this).attr('id');
			var theID = jQuery(this).attr('title');	
			var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
			var data = {
				action: 'woo_ajax_post_action',
				type: 'image_reset',
				data: theID
			};
				
			jQuery.post(ajax_url, data, function(response) {
				var image_to_remove = jQuery('#image_' + theID);
				var button_to_hide = jQuery('#reset_' + theID);
				image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
				button_to_hide.fadeOut();
				clickedObject.parent().prev('input').val('');
		});	
		return false; 				
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
		
		//Start Heading
		 if ( $value['type'] != "heading" )
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
			$val = $value['std'];
			$std = $settings[($value['id'])];
			if ( $std != "") { $val = $std; }
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
			$output .= '<input id="'. $value['id'] .'" type="hidden" name="of_theme_options['. $value['id'] .']" value="' . $std . '" />';	
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
		
		// Info
		case "info":
			$default = $value['std'];
			$output .= $default;
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
		
		if ( $value['type'] != "heading" ) { 
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