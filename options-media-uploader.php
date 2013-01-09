<?php

/**
 * Media Uploader Using the WordPress Media Library.
 *
 * Parameters:
 * - string $_id - A token to identify this field (the name).
 * - string $_value - The value of the field, if present.
 * - string $_desc - An optional description of the field.
 *
 */

if ( ! function_exists( 'optionsframework_uploader' ) ) :

function optionsframework_uploader( $_id, $_value, $_desc = '', $_name = '') {

	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	$output = '';
	$id = '';
	$class = '';
	$int = '';
	$value = '';
	$name = '';
	
	$id = strip_tags( strtolower( $_id ) );
	
	// If a value is passed and we don't have a stored value, use the value that's passed through.
	if ( $_value != '' && $value == '' ) {
		$value = $_value;
	}
	
	if ($_name != '') {
		$name = $_name;
	}
	else {
		$name = $option_name.'['.$id.']';
	}
	
	if ( $value ) {
		$class = ' has-file';
	}
	$output .= '<input id="' . $id . '" class="upload' . $class . '" type="text" name="'.$name.'" value="' . $value . '" placeholder="' . __('No file chosen', 'optionsframework') .'" />' . "\n";
	if ( $value == '' ) {
		$output .= '<input id="upload-' . $id . '" class="upload-button button" type="button" value="' . __( 'Upload', 'optionsframework' ) . '" />' . "\n";
	} else {
		$output .= '<input id="remove-' . $id . '" class="remove-file button" type="button" value="' . __( 'Remove', 'optionsframework' ) . '" />' . "\n";
	}
	
	if ( $_desc != '' ) {
		$output .= '<span class="of-metabox-desc">' . $_desc . '</span>' . "\n";
	}
	
	$output .= '<div class="screenshot" id="' . $id . '-image">' . "\n";
	
	if ( $value != '' ) { 
		$remove = '<a class="remove-image">Remove</a>';
		$image = preg_match( '/(^.*\.jpg|jpeg|png|gif|ico*)/i', $value );
		if ( $image ) {
			$output .= '<img src="' . $value . '" alt="" />'.$remove.'';
		} else {
			$parts = explode( "/", $value );
			for( $i = 0; $i < sizeof( $parts ); ++$i ) {
				$title = $parts[$i];
			}

			// No output preview if it's not an image.			
			$output .= '';
		
			// Standard generic output if it's not an image.	
			$title = __( 'View File', 'optionsframework' );
			$output .= '<div class="no_image"><span class="file_link"><a href="' . $value . '" target="_blank" rel="external">'.$title.'</a></span>' . $remove . '</div>';
		}	
	}
	$output .= '</div>' . "\n";
	return $output;
}

endif;

/**
 * Enqueue scripts for file uploader
 */
 
if ( ! function_exists( 'optionsframework_media_scripts' ) ) :

function optionsframework_media_scripts(){
	wp_enqueue_media();
	wp_register_script( 'of-media-uploader', OPTIONS_FRAMEWORK_URL .'js/media-uploader.js', array( 'jquery' ) );
	wp_enqueue_script( 'of-media-uploader' );
	wp_localize_script( 'of-media-uploader', 'optionsframework', array(
		'upload' => __( 'Upload', 'optionsframework' ),
		'remove' => __( 'Remove', 'optionsframework' )
	) );
}

endif;
