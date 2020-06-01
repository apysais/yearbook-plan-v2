<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *
 * @since 0.0.1
 * */
class YB_Lock_Post {

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

	public function init($post_id, $user_id = null)
  {
    $current_user_id = $user_id;

    if(is_null($user_id)){
      $current_user     = wp_get_current_user();
      $current_user_id 	= $current_user->ID;
    }

    $user = null;
    if ( $current_user_id = $this->checkPostLock( $post_id ) ) {
      $user = get_userdata( $current_user_id );
      $lock = true;
    }else{
      $this->setPostLock( $post_id );
      $lock = false;
    }
    if($lock){
      return $user;
    }else{
      return false;
    }
  }

  public function setPostLock($post_id)
  {
    if ( ! $post = get_post( $post_id ) ) {
        return false;
    }

    if ( 0 == ( $user_id = get_current_user_id() ) ) {
        return false;
    }

    $now  = time();
    $lock = "$now:$user_id";

    update_post_meta( $post->ID, '_edit_lock', $lock );

    return array( $now, $user_id );
  }

  public function checkPostLock($post_id)
  {
    if ( ! $post = get_post( $post_id ) ) {
        return false;
    }
    $lock = get_post_meta( $post->ID, '_edit_lock', true );
    if ( ! $lock  ) {
        return false;
    }

    $lock = explode( ':', $lock );
    $time = $lock[0];
    $user = isset( $lock[1] ) ? $lock[1] : get_post_meta( $post->ID, '_edit_last', true );
    if ( ! get_userdata( $user ) ) {
        return false;
    }

    /** This filter is documented in wp-admin/includes/ajax-actions.php */
    $time_window = apply_filters( 'yb_wp_check_post_lock_window', 150 );
    if ( $time && $time > time() - $time_window && $user != get_current_user_id() ) {
      return $user;
    }

    return false;
  }

  public function deletePostLock($post_id)
  {
    delete_post_meta( $post_id, '_edit_lock');
  }



}//class YB_Page_CPT
