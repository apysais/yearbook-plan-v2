<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Account Meta uses User Meta.
 * @since 0.0.1
 * */
class YB_Account_Meta {
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
	* school account, uses user meta crud.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
  public function school_account($args = []){
		$prefix = 'yb_school_account';
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

	/**
	* account name, uses user meta crud.
	* @param array $args {
	*		Array of arguments.
	*		@type int $user_id the user id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_user_meta(), u = update_user_meta(), d = delete_user_meta
	**/
  public function account_name($args = []){
		$prefix = 'account_name';
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

	/**
	* This set the author already sent notification.
	**/
	public function user_author_sent_notify($args = [])
	{

		$prefix = 'sent_notify_email_';
		if(isset($args['user_id'])) {
			$task_id = $args['task_id'];

			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix . $task_id
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
