<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Use for the ACcount Controller
 * @since 0.0.1
 * */
class YB_Account_Controller extends YB_Base {
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
	* Display school account.
	**/
	public function SchoolAccount()
	{
		$data = [];
		$args = array(
			'meta_query' => array(
					array(
						'key'     => 'yb_school_account',
						'value'   => 1,
			 			'compare' => '='
					)
			)
		 );
		 //print_r($args);
		$user_query = new WP_User_Query( $args );
		$data['user_query'] = $user_query;
		// User Loop
		//print_r($data['user_query']);
		YB_View::get_instance()->admin_partials('partials/account/index.php', $data);
	}

	/**
	* Edit the account.
	**/
	public function edit()
	{
		$data = [];
		$user_id = isset($_GET['id']) ? $_GET['id']:$_POST['user_id'];
		$user_info = get_userdata($user_id);
		$user_meta = new YB_Account_Meta;
		$school_account = $user_meta->school_account(['user_id'=>$user_id,'single'=>true]);
		$school_name = $user_meta->account_name(['user_id'=>$user_id,'single'=>true]);
		$data['action_url'] = yb_admin_url_school_account();
		$data['method'] = 'update';
		$data['show_random_password'] = false;
		$data['password'] = null;
		$data['account_name'] = isset($_POST['account_name']) ? $_POST['account_name']:$school_name;
		$data['username'] = isset($_POST['username']) ? $_POST['username']:$user_info->user_login;
		$data['email'] = isset($_POST['email']) ? $_POST['email']:$user_info->user_email;
		$data['user_id'] = $user_id;
		YB_View::get_instance()->admin_partials('partials/account/edit.php', $data);
	}

	/**
	* Create new view.
	**/
	public function create()
	{
		$data = [];
		$data['action_url'] = yb_admin_url_school_account();
		$data['method'] = 'insert';
		$data['show_random_password'] = true;
		$data['password'] = wp_generate_password( 8, false );
		$data['account_name'] = isset($_POST['account_name']) ? $_POST['account_name']:'';
		$data['username'] = isset($_POST['username']) ? $_POST['username']:'';
		$data['email'] = isset($_POST['email']) ? $_POST['email']:'';
		
		YB_View::get_instance()->admin_partials('partials/account/create.php', $data);
	}

	/**
	* Insert the school account.
	**/
	public function insert()
	{
		if(isset($_POST['_method'])){
			$validate = [
				'account_name@Account Name' => 'required',
				'username@User Name' => 'required|user_exists',
				'email@Email' => 'required|email|email_exists',
			];
			//has error
			if(yb_validation_has_error($validate)) {
				$this->create();
			}else{
					$username = $_POST['username'];
					$user_email = $_POST['email'];
					$password = $_POST['password'];
					$website = wp_login_url();
					$userdata = array(
					    'user_login' 		=>  $username,
							'user_email'		=> 	$user_email,
					    'user_url'   		=>  $website,
					    'user_pass'  		=>  $password,
							'user_nicename'	=> $_POST['account_name'],
							'display_name'	=> $_POST['account_name'],
					);

					$user_id = wp_insert_user( $userdata ) ;

					// On success.
					if ( ! is_wp_error( $user_id ) ) {
						$account_name = $_POST['account_name'];

						yb_account_name_update($user_id, $account_name);
						yb_school_account_update($user_id, 1);

						wp_update_user( array ('ID' => $user_id, 'role' => 'editor') ) ;
						if(isset($_POST['send_login_details'])) {
							$to = $user_email;
							$subject = 'Yearbook Account';
							$body = '<p>Here is your YearBook Account</p>';
							$body.= '<p><a href="'.$website.'">Click Here to Login</a></p>';
							$body.= '<p>Username : '.$username.'</p>';
							$body.= '<p>Password : '.$password.'</p>';
							$headers = array('Content-Type: text/html; charset=UTF-8');

							wp_mail( $to, $subject, $body, $headers );
						}
						$redirect_to = yb_admin_url_school_account();
						yb_redirect_to($redirect_to);
					}//! is_wp_error( $user_id )
			}

		}//isset($_POST['_method'])
	}

	/**
	* Update the school account.
	**/
	public function update()
	{
		if(isset($_POST['_method'])){
			$user_id = $_POST['user_id'];
			$validate = [
				'account_name@Account Name' => 'required',
				'email@Email' => 'required|email|unique_email:'.$user_id,
			];
			if(yb_validation_has_error($validate)) {
				$this->edit();
			}else{
				$user_id = $_POST['user_id'];
				$username = $_POST['username'];
				$user_email = $_POST['email'];
				$password = trim($_POST['password']);

				$userdata = array(
						'ID'						=> 	$user_id,
						'user_email'		=> 	$user_email,
				);
				if($password != '' ){
					$userdata['user_pass'] = $password;
				}
				wp_update_user( $userdata ) ;

				$account_name = $_POST['account_name'];
				yb_account_name_update($user_id, $account_name);

				$redirect_to = yb_admin_url_school_account('&_method=edit&id='.$user_id.'');
				yb_redirect_to($redirect_to);
			}
		}
	}

	/**
	* Verify delete.
	**/
	public function verify_delete()
	{
		if(
			isset($_GET['delete_school_account'])
			&& isset($_GET['id'])
		) {
			$user_id = $_GET['id'];
			$nonce = 'delete_school_account_' . $user_id;
			if(wp_verify_nonce($_GET['delete_school_account'], $nonce)) {
				$user_info = get_userdata($user_id);
				$data['count_user_posts'] = count_user_posts( $user_id, YB_CPT_PREFIX );
				$data['user_info'] = $user_info;

				$data['action_url'] = yb_admin_url_school_account();
				$data['method'] = 'delete';
				$data['user_id'] = $user_id;

				YB_View::get_instance()->admin_partials('partials/account/delete.php', $data);
			}
		}
	}

	/**
	* Delete the school account/
	**/
	public function delete()
	{

		if (
	    isset( $_POST['delete_user'] )
			&& isset($_POST['user_id'])
		) {
			$user_id = $_POST['user_id'];
			$nonce = 'delete_this_user_' . $user_id;
			if(wp_verify_nonce( $_POST['delete_user'], $nonce ) ) {
				require_once(ABSPATH.'wp-admin/includes/user.php' );
				wp_delete_user( $user_id );

				$redirect_to = yb_admin_url_school_account();
				yb_redirect_to($redirect_to);
			}
		}
	}

	/**
	 * Controller
	 *
	 * @param	$action		string | empty
	 * @parem	$arg		array
	 * 						optional, pass data for controller
	 * @return mix
	 * */
	public function controller($action = '', $arg = array()){
		$this->call_method($this, $action);
	}

	public function __construct(){}

}
