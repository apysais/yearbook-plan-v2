<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Get the projects Task
 * @since 0.0.1
 * */
class YB_Contributor_Meta {
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

  public function is_contributors($args = []){
		$prefix = 'yb_is_contributors';
		if(isset($args['user_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_user_meta($args['user_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_user_meta($args['user_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_user_meta($args['user_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

  public function parent_school_admin($args = []){
		$prefix = 'yb_parent_school_admin';
		if(isset($args['user_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_user_meta($args['user_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_user_meta($args['user_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_user_meta($args['user_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

  public function __construct(){}
}
