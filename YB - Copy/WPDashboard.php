<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Use to modify the WP dashboard.
 * @since 0.0.1
 * */
class YB_WPDashboard {
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

	/**
	* Remove the quick links and other UI in dashboard home.
	**/
	public function remove_dashboard_meta() {
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
	  remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	/**
	* Unset or remove not used dashboard UI.
	* callback for hook wp_dashboard_setup
	**/
	public function unset_dashboard_widgets()
	{
		global $wp_meta_boxes;
		// wp..
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
	}

	/**
	* Display dashboard the tasks.
	**/
	public function dashboard_sendtask() {
		$data = [];
		$get_pages_task = YB_Project_Rest_Task::get_instance()->getPages();
		//print_r($get_pages_task);
		$data['get_pages'] = $get_pages_task;
		YB_View::get_instance()->admin_partials('partials/dashboard/dashboard.php', $data);
	}

  public function __construct()
  {
		//add_action( 'admin_init', array($this, 'remove_dashboard_meta') );
		//removed not used dashboard UI.
		add_action( 'wp_dashboard_setup', array($this, 'unset_dashboard_widgets') );
		//add_action( 'welcome_panel', array($this, 'dashboard_sendtask') );
  }

}
