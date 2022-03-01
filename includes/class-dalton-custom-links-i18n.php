<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/msmjsuarez
 * @since      1.0.0
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/includes
 * @author     MJ Suarez <msmjsuarez@gmail.com>
 */
class Dalton_Custom_Links_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dalton-custom-links',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
