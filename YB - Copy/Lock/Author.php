<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *
 * @since 0.0.1
 * */
class YB_Lock_Author {

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

  public function authorComplete($post_id)
  {
    $is_submitted  = YB_Project_Pages::get_instance()->isSubmitted($post_id);
    $is_finished = YB_Project_Pages::get_instance()->is_finished([
      'post_id' => $post_id,
      'single' => true
    ]);
    if($is_submitted || ($is_finished == 1 || $is_finished == 2 || $is_finished == 4) ){
      return true;
    }
    return false;
  }

  public function show($data)
  {
    //partials to show the lock notice
		YB_View::get_instance()->public_partials('partials/notice/author-complete.php', $data);
  }

}//class YB_Lock_Author
