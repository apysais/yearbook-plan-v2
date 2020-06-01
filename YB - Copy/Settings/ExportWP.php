<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *
 * @since 0.0.1
 * */
class YB_Settings_ExportWP {
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

	public function registerSubMenu(){
		$t = yb_get_plugin_details();
    $name = yb_verbage('yearbook_menu');
    $current_user_id = get_current_user_id();
    $user_id = array(1, 6);

    if (in_array($current_user_id, $user_id)) {
      add_submenu_page(
          $t['Name'],
          'Export',
          'Export',
          'manage_network',
          'admin.php?page=YearBook&_method=ExportYB',
          [YB_Page_Controller::get_instance(), 'controller']
      );
    }

	}

	public function __construct()
  {
    $this->registerSubMenu();
  }

}
