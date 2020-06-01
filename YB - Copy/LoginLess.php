<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Use for login less.
 * @access (protected, public)
 * */
class YB_LoginLess{
	/**
	 * instance of this class
	 *
	 * @since 3.12
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

    /**
     * use for magic setters and getter
     * we can use this when we instantiate the class
     * it holds the variable from __set
     *
     * @see function __get, function __set
     * @access protected
     * @var array
     * */
    protected $vars = array();

		//part of encryption, the task id
		protected $enc_prefix_task_id = 'task-id=';

		//part of encryption assign id
		protected $enc_prefix_user_id = 'assign-to-id=';

    public function __construct() {}

    /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
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
	* Create the encrption salt.
	* This is use for adding salt encryption, making sure the task id and the user id is owner.
	* @param array $arg {
	* 	@param integer $task_id is the id of the task.
	* 	@param integer $user_id is the id of the author or user.
	* }
	* @return concanated string of task id and user id.
	**/
	public function createCryptSalt($arg = [])
	{
		$task_id = '';
		if(isset($arg['task_id'])) {
			$task_id = $this->enc_prefix_task_id . $arg['task_id'];
		}
		$user_id = '';
		if(isset($arg['user_id'])) {
			$user_id = $this->enc_prefix_user_id . $arg['user_id'];
		}
		return $task_id.','.$user_id;
	}

	/**
	* decrypt the login less string parse the key and value.
	**/
	public function decryptLogin($string = '')
	{
		$dec = $this->loginCrypt($string, 'd');
		$explode = explode(',', $dec);
		$task_id = 0;
		$user_id = 0;
		if(count($explode) > 0 && is_array($explode)) {
			$ex_get_task_id = explode('=', $explode[0]);
			$task_id = isset($ex_get_task_id[1]) ? $ex_get_task_id[1]:0;

			$ex_get_user_id = explode('=', $explode[1]);
			$user_id = isset($ex_get_user_id[1]) ? $ex_get_user_id[1]:0;

			return [
				'task_id' => $task_id,
				'user_id' => $user_id,
			];
		}
		return false;
	}

	/**
	* Login using the string salt.
	* @param string $string the salt part of encryption.
	* @param sring @action e is for encrypt and d is for decrypt, default to e.
	**/
	public function loginCrypt( $string, $action = 'e' ) {
	    // you may change these values to your own
	    $secret_key = NONCE_KEY;
	    $secret_iv 	= NONCE_SALT;

	    $output = false;
	    $encrypt_method = "AES-256-CBC";
	    $key = hash( 'sha256', $secret_key );
	    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	    if( $action == 'e' ) {
	        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	    }
	    else if( $action == 'd' ){
	        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	    }
			
	    return $output;
	}

	/**
	* Login user.
	**/
	public function login($user_id)
	{
		$user = get_user_by( 'id', $user_id );
		if( $user ) {
		    wp_set_current_user( $user_id, $user->user_login );
		    wp_set_auth_cookie( $user_id );
		    do_action( 'wp_login', $user->user_login );
		}else{
			return false;
		}
	}

}
