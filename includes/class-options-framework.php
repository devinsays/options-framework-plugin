<?php
/**
 * @package   Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2013 WP Theming
 */

class Options_Framework {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since 1.7.0
	 * @type string
	 */
	const VERSION = '1.7.0';

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.7.0
	 */
	public function init() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.7.0
	 */
	public function load_plugin_textdomain() {
	        $domain = self::SLUG;
	        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

	        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	        load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Deletes all options added by the Options Framework
	 */
	function delete_options() {

	    $optionsframework_settings = get_option( 'optionsframework' );

	    // Each theme saves its data in a seperate option, which all gets deleted
	    $knownoptions = $optionsframework_settings['knownoptions'];
	    if ( $knownoptions ) {
			foreach ( $knownoptions as $key ) {
				delete_option( $key );
			}
	    }
	    delete_option( 'optionsframework' );
	    delete_user_meta( $user_id, 'optionsframework_ignore_notice', 'true' );
	}

	/**
	 * Wrapper for optionsframework_options()
	 *
	 * Allows for manipulating or setting options via 'of_options' filter
	 * For example:
	 *
	 * <code>
	 * add_filter( 'of_options', function( $options ) {
	 *     $options[] = array(
	 *         'name' => 'Input Text Mini',
	 *         'desc' => 'A mini text input field.',
	 *         'id' => 'example_text_mini',
	 *         'std' => 'Default',
	 *         'class' => 'mini',
	 *         'type' => 'text'
	 *     );
	 *
	 *     return $options;
	 * });
	 * </code>
	 *
	 * Also allows for setting options via a return statement in the
	 * options.php file.  For example (in options.php):
	 *
	 * <code>
	 * return array(...);
	 * </code>
	 *
	 * @return array (by reference)
	 */
	function &_optionsframework_options() {
		static $options = null;

		if ( !$options ) {
	        // Load options from options.php file (if it exists)
	        $location = apply_filters( 'options_framework_location', array('options.php') );
	        if ( $optionsfile = locate_template( $location ) ) {
	            $maybe_options = require_once $optionsfile;
	            if ( is_array( $maybe_options ) ) {
					$options = $maybe_options;
	            } else if ( function_exists( 'optionsframework_options' ) ) {
					$options = optionsframework_options();
				}
	        }

	        // Allow setting/manipulating options via filters
	        $options = apply_filters( 'of_options', $options );
		}

		return $options;
	}

}