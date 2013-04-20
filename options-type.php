<?php 

add_filter( 'optionsframework_text', 'optionsframework_text', 10, 3 );

function optionsframework_text( $option_name, $value, $val ) {
	$output = '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
	return $output;
}

add_filter( 'optionsframework_password', 'optionsframework_password', 10, 3 );

function optionsframework_password( $option_name, $value, $val ) {
	$output = '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="password" value="' . esc_attr( $val ) . '" />';
	return $output;
}