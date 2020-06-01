<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Account Model.
 * @since 0.0.1
 * */
class YB_Account_Model {
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

	public function get()
	{
		$args = array(
			'meta_query' => array(
					array(
						'key'     => 'yb_school_account',
						'value'   => 1,
			 			'compare' => '='
					)
			)
		 );

		$user_query = new WP_User_Query( $args );
		if ( ! empty( $user_query->get_results() ) ) {
			return $user_query->get_results();
		}
		return false;
	}

	public function getContributors($account_id)
	{

	}

  public function __construct(){}
}
