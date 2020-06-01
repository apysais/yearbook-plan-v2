<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       allteams.com
 * @since      1.0.0
 *
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/includes
 * @author     AllTeams <mike@allteams.com>
 */
class Yearbook_Plan_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'yearbook-plan',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
