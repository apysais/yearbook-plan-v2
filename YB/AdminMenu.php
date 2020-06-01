<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Show Admin Menu
 * @since 0.0.1
 * */
class YB_AdminMenu {
  /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.1
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function admin_menu() {
		$t 		= yb_get_plugin_details();
		$name = yb_verbage('yearbook_menu');

		add_menu_page(
			$name['page_title'],
			$name['menu_title'],
			'moderate_comments',
			$t['Name'],
			[ YB_Page_Controller::get_instance() , 'controller' ],
			'dashicons-welcome-learn-more',
			3
		);

		//'edit.php?post_type=yearbook-plan'
	}

	public function __construct(){}

}
