<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Validate the request either post or get
 *
 *
 * @since 3.12
 * @access (protected, public)
 * */
class YB_RequestValidation{
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

		protected $data;
		protected $validate_msg;

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
	*
	*	$arr_data = [
	*		'email' => 'required|unique'
	*		'selector' => 'required|not_value:-1'
	* ];
	*
	**/
	public function set($arr_data)
	{
		$this->data = $arr_data;
	}

	/**
	* get the data from variable $this->data
	* @see method set()
	* @return $this->data
	**/
	public function getData()
	{
		return $this->data;
	}

	/**
	* Validate the input.
	**/
	public function validate()
	{
		//get the data
		$data = $this->getData();
		$request_data = [];

		//set validate to false
		$validate = false;
		$msg_validate = '';

		$ret = [];

		//what request method is used?
		if($_SERVER['REQUEST_METHOD'] === 'POST') { //if the request is POST
			foreach($_POST as $k => $v) {
				$request_data[$k] = $v;
			}
		}elseif($_SERVER['REQUEST_METHOD'] === 'GET') { //if the request is GET
			foreach($_GET as $k => $v) {
				$request_data[$k] = $v;
			}
		}

		//loop each data from method getData
		foreach($data as $k => $v) {
			$parse_key = explode('@', $k);
			if(is_array($parse_key) && count($parse_key) >= 2) {
				$key = $parse_key[0];
				$key_label = $parse_key[1];
			}else{
				$key = $k;
				$key_label = $k;
			}
			$parse_validate = explode('|', $v);
			if(is_array($parse_validate)) {
				foreach($parse_validate as $val_k => $val_v) {
					if($val_v == 'required') {
						if(trim($request_data[$key]) == ''){
							$ret['messages'][] = $key_label.', Is Required.';
						}
					}//required
					if($val_v == 'email_exists') {
						if(is_email($request_data[$key]) && email_exists($request_data[$key])){
							$ret['messages'][] = $key_label.', Already Exists.';
						}
					}//email:exists
					if($val_v == 'email') {
						if(!is_email($request_data[$key])){
							$ret['messages'][] = $key_label.', not a valid format email.';
						}
					}//email
					if($val_v == 'user_exists') {
						if(username_exists($request_data[$key])){
							$ret['messages'][] = $key_label.', Already Exists.';
						}
					}//user:exists
					if(strpos( $val_v, 'unique_user' ) !== false) {
						$unique_user = explode(':', $val_v);
						if(is_array($unique_user) && count($unique_user) >= 2) {
							$username = $request_data[$key];
							$unique_user_id =  $unique_user[1];
							$user_info = get_user_by('id', $unique_user_id);
							if(
								$user_info
								&& username_exists($username)
								&& $user_info->user_login != $username
							){
								$ret['messages'][] = $key_label.', Already Exists.';
							}
						}
					}//unique_user
					if(strpos( $val_v, 'unique_email' ) !== false) {
						$unique_user = explode(':', $val_v);
						if(is_array($unique_user) && count($unique_user) >= 2) {
							$email = $request_data[$key];
							$user_id =  $unique_user[1];
							$user_info = get_user_by('id', $user_id);
							if(
								$user_info
								&& email_exists($email)
								&& $user_info->user_email != $email
							){
								$ret['messages'][] = $key_label.', Already Exists.';
							}
						}
					}//unique_email
				}
			}
		}
		$this->validate_msg = $ret;
	}

	/**
	* get validate message.
	**/
	public function getvalidateMsg()
	{
		return isset($this->validate_msg['messages']) ? $this->validate_msg['messages']:[];
	}

	/**
	* if the validation has error.
	* @return boolean
	**/
	public function hasError()
	{
		$msg = $this->getvalidateMsg();
		if(is_array($msg) && count($msg) == 0) {
			return false;
		}
		return true;
	}

	public function show()
	{

	}

	/**
	*
	*
	**/
	public function __construct($input) {
		$this->set($input);
		$this->validate();
	}

}
