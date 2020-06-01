<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Get the projects Task
 * @since 0.0.1
 * */
class YB_Contributor_Model {
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
					'relation' => 'OR',
					array(
						'key'     => 'yb_is_contributors',
						'value'   => 1,
			 			'compare' => '='
					),
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

	/**
	* Get contributors by School Admin
	* list all contributors by school admin
	**/
	public function getById($account_id)
	{
		$args = array(
			'meta_query' 	=> array(
					'relation' => 'OR',
					array(
						'key'     => 'yb_is_contributors',
						'value'   => 1,
						'compare' => '='
					),
					array(
						'key'     => 'yb_parent_school_admin',
						'value'   => $account_id,
						'compare' => '='
					),
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

  public function __construct(){}
}
