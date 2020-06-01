<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *
 * @since 0.0.1
 * */
class YB_Lock_Ajax {

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

	public function refreshPostLock()
	{
		if(isset($_POST['task_id'])){
			$task_id = $_POST['task_id'];
			YB_Lock_Post::get_instance()->setPostLock($task_id);
		}

		wp_die();
	}

	public function takeOverContent()
	{
		if(isset($_POST['content_id'])){
			$content_id = $_POST['content_id'];
			YB_Lock_Post::get_instance()->setPostLock($content_id);
		}
		wp_die();
	}

	public function init()
	{
		add_action( 'wp_ajax_refresh_post_lock', [$this, 'refreshPostLock'] );
		add_action( 'wp_ajax_nopriv_refresh_post_lock', [$this, 'refreshPostLock'] );
		add_action( 'wp_ajax_take_over_content', [$this, 'takeOverContent'] );
		add_action( 'wp_ajax_nopriv_take_over_content', [$this, 'takeOverContent'] );
	}

}//class YB_Lock_Ajax
