<?php
/**
 * The theme option name is set as 'options-theme-customizer' here.
 * In your own project, you should use a different option name.
 * I'd recommend using the name of your theme.
 *
 * This option name will be used later when we set up the options
 * for the front end theme customizer.
 */

function optionsframework_option_name() {

	$optionsframework_settings = get_option('optionsframework');
	
	// Edit 'options-theme-customizer' and set your own theme name instead
	$optionsframework_settings['id'] = 'options_theme_customizer';
	update_option('optionsframework', $optionsframework_settings);
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 */

function optionsframework_options() {

	// Test data
	$test_array = array(
		"First" => "First Option",
		"Second" => "Second Option",
		"Third" => "Third Option" );

	$options = array();

	$options[] = array( "name" => "Example Settings",
		"type" => "heading" );

	$options['example_text'] = array(
		"name" => "Text",
		"id" => "example_text",
		"std" => "Default Value",
		"type" => "text" );

	$options['example_select'] = array(
		"name" => "Select Box",
		"id" => "example_select",
		"std" => "First",
		"type" => "select",
		"options" => $test_array );

	$options['example_radio'] = array(
		"name" => "Radio Buttons",
		"id" => "example_radio",
		"std" => "Third",
		"type" => "radio",
		"options" => $test_array );

	$options['example_checkbox'] = array(
		"name" => "Input Checkbox",
		"desc" => "This is a work in progress.  There is are some issues with how the front end customizer saves checkbox options, and how the Options Framework does.  Bear with me a bit while I work on a solution.",
		"id" => "example_checkbox",
		"std" => "1",
		"type" => "checkbox" );

	$options['example_uploader'] = array(
		"name" => "Uploader Test",
		"desc" => "This creates a full size uploader that previews the image.",
		"id" => "example_uploader",
		"type" => "upload" );
		
	$options['example_colorpicker'] = array(
		"name" => "Colorpicker",
		"id" => "example_colorpicker",
		"std" => "#666666",
		"type" => "color" );

	return $options;
}

/**
 * Front End Customizer
 *
 * WordPress 3.4 Required
 */

add_action( 'customize_register', 'options_theme_customizer_register' );

function options_theme_customizer_register($wp_customize) {

	/**
	 * This is optional, but if you want to reuse some of the defaults
	 * or values you already have built in the options panel, you
	 * can load them into $options for easy reference
	 */
	 
	$options = optionsframework_options();
	
	/* Basic */

	$wp_customize->add_section( 'options_theme_customizer_basic', array(
		'title' => __( 'Basic', 'options_theme_customizer' ),
		'priority' => 100
	) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_text]', array(
		'default' => $options['example_text']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_example_text', array(
		'label' => $options['example_text']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => 'options_theme_customizer[example_text]',
		'type' => $options['example_text']['type']
	) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_select]', array(
		'default' => $options['example_select']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_example_select', array(
		'label' => $options['example_select']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => 'options_theme_customizer[example_select]',
		'type' => $options['example_select']['type'],
		'choices' => $options['example_select']['options']
	) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_radio]', array(
		'default' => $options['example_radio']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_example_radio', array(
		'label' => $options['example_radio']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => 'options_theme_customizer[example_radio]',
		'type' => $options['example_radio']['type'],
		'choices' => $options['example_radio']['options']
	) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_checkbox]', array(
		'default' => $options['example_checkbox']['std'],
		'type' => 'option'
	) );

	$wp_customize->add_control( 'options_theme_customizer_example_checkbox', array(
		'label' => $options['example_checkbox']['name'],
		'section' => 'options_theme_customizer_basic',
		'settings' => 'options_theme_customizer[example_checkbox]',
		'type' => $options['example_checkbox']['type']
	) );
	
	/* Extended */

	$wp_customize->add_section( 'options_theme_customizer_extended', array(
		'title' => __( 'Extended', 'options_theme_customizer' ),
		'priority' => 110
	) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_uploader]', array(
		'type' => 'option'
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'example_uploader', array(
		'label' => $options['example_uploader']['name'],
		'section' => 'options_theme_customizer_extended',
		'settings' => 'options_theme_customizer[example_uploader]'
	) ) );
	
	$wp_customize->add_setting( 'options_theme_customizer[example_colorpicker]', array(
		'default' => $options['example_colorpicker']['std'],
		'type' => 'option'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'   => $options['example_colorpicker']['name'],
		'section' => 'options_theme_customizer_extended',
		'settings'   => 'options_theme_customizer[example_colorpicker]'
	) ) );
}