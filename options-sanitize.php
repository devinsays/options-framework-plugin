<?php

/* Text */

function of_sanitize_text( $input ) {
	$output = sanitize_text_field( $input );
	return $output;
}
add_filter( 'of_sanitize_text', 'of_sanitize_text' );

/* Textarea */

function of_sanitize_textarea( $input ) {
	$output = esc_textarea( $input );
	return $output;
}
add_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );

/* Select */

function of_sanitize_select( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_select', 'of_sanitize_select', 10, 2);

/* Radio */

function of_sanitize_radio( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_radio', 'of_sanitize_radio', 10, 2);

/* Images */

function of_sanitize_images( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_images', 'of_sanitize_images', 10, 2);

/* Checkbox */

function of_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = "1";
	} else {
		$output = "0";
	}
	return $output;
}
add_filter( 'of_sanitize_checkbox', 'of_sanitize_checkbox' );

/* Multicheck */

function of_sanitize_multicheck( $input, $option ) {
	$output = '';
	if ( is_array( $input ) ) {
		foreach( $input as $key => $value ) {
			if ( array_key_exists( $key, $option['options'] ) ) {
				$output[$key] = "1"; 
			}
		}
	}
	return $output;
}
add_filter( 'of_sanitize_multicheck', 'of_sanitize_multicheck', 10, 2 );

/* Color Picker */

function of_sanitize_color( $input ) {
	$output = apply_filters( 'of_color', $input );
	return $output;
}
add_filter( 'of_sanitize_color', 'of_sanitize_color' );

/* Uploader */

function of_sanitize_upload( $input ) {
	$output = '';
	$filetype = wp_check_filetype($input);
	if ( $filetype["ext"] ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'of_sanitize_upload', 'of_sanitize_upload' );

/* Background */

function of_sanitize_background( $input ) {
	$output = wp_parse_args( $input, array(
		'color' => '',
		'image'  => '', // Not Quite Finished
		'repeat'  => 'repeat',
		'position' => 'top center', // Not Quite Finished
		'attachment' => 'scroll' // Not Quite Finished
	) );

	$output['color'] = apply_filters( 'of_color', $output['color'] );
	$output['image'] = apply_filters( 'of_background_image', $output['image'] );
	$output['repeat'] = apply_filters( 'of_background_repeat', $output['repeat'] );

	return $output;
}
add_filter( 'of_sanitize_background', 'of_sanitize_background' );

function of_sanitize_background_repeat( $value ) {
	$recognized = of_recognized_background_repeat();
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'of_default_background_repeat', $recognized );
}
add_filter( 'of_background_repeat', 'of_sanitize_background_repeat' );

/* Typography */

function of_sanitize_typography( $input ) {
	$output = wp_parse_args( $input, array(
		'size'  => '',
		'face'  => '',
		'style' => '',
		'color' => ''
	) );

	$output['size']  = apply_filters( 'of_font_size', $output['size'] );
	$output['face']  = apply_filters( 'of_font_face', $output['face'] );
	$output['style'] = apply_filters( 'of_font_style', $output['style'] );
	$output['color'] = apply_filters( 'of_color', $output['color'] );

	return $output;
}
add_filter( 'of_sanitize_typography', 'of_sanitize_typography' );


function of_sanitize_font_size( $value ) {
	$recognized = of_recognized_font_sizes();
	if ( in_array( (int) $value, $recognized ) ) {
		return (int) $value;
	}
	return (int) apply_filters( 'of_default_font_size', $recognized );
}
add_filter( 'of_font_face', 'of_sanitize_font_face' );


function of_sanitize_font_style( $value ) {
	$recognized = of_recognized_font_styles();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_style', current( $recognized ) );
}
add_filter( 'of_font_style', 'of_sanitize_font_style' );


function of_sanitize_font_face( $value ) {
	$recognized = of_recognized_font_faces();
	if ( array_key_exists( $value, $recognized ) ) {
		return $value;
	}
	return apply_filters( 'of_default_font_face', current( $recognized ) );
}
add_filter( 'of_font_face', 'of_sanitize_font_face' );

/**
 * Get recognized background repeat settings
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_background_repeat() {
	return array(
		'none'     => 'No Repeat',
		'repeat-x' => 'Repeat Horizontally',
		'repeat-y' => 'Repeat Vertically',
		'repeat'   => 'Repeat All',
		);
}

/**
 * Sanitize a color represented in hexidecimal notation.
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @param    string    The value that this function should return if it cannot be recognized as a color.
 * @return   string
 *
 */
 
function of_sanitize_hex( $hex, $default = '' ) {
	if ( of_validate_hex( $hex ) ) {
		return $hex;
	}
	return $default;
}
add_filter( 'of_color', 'of_sanitize_hex' );

/**
 * Get recognized font sizes.
 *
 * Returns an indexed array of all recognized font sizes.
 * Values are integers and represent a range of sizes from
 * smallest to largest.
 *
 * @return   array
 */
 
function of_recognized_font_sizes() {
	$sizes = range( 9, 71 );
	$sizes = apply_filters( 'of_recognized_font_sizes', $sizes );
	$sizes = array_map( 'absint', $sizes );
	return $sizes;
}

/**
 * Get recognized font faces.
 *
 * Returns an array of all recognized font faces.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_faces() {
	return array(
		'arial'     => 'Arial',
		'verdana'   => 'Verdana, Geneva',
		'trebuchet' => 'Trebuchet',
		'georgia'   => 'Georgia',
		'times'     => 'Times New Roman',
		'tahoma'    => 'Tahoma, Geneva',
		'palatino'  => 'Palatino',
		'helvetica' => 'Helvetica*'
		);
}

/**
 * Get recognized font styles.
 *
 * Returns an array of all recognized font styles.
 * Keys are intended to be stored in the database
 * while values are ready for display in in html.
 *
 * @return   array
 *
 */
function of_recognized_font_styles() {
	return array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
		);
}

/**
 * Is a given string a color formatted in hexidecimal notation?
 *
 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
 * @return   bool
 *
 */
 
function of_validate_hex( $hex ) {
	$hex = trim( $hex );
	/* Strip recognized prefixes. */
	if ( 0 === strpos( $hex, '#' ) ) {
		$hex = substr( $hex, 1 );
	}
	elseif ( 0 === strpos( $hex, '%23' ) ) {
		$hex = substr( $hex, 3 );
	}
	/* Regex match. */
	if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
		return false;
	}
	else {
		return true;
	}
}