<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 *
 * @since 0.0.1
 * */
class YB_Contributor_Controller extends YB_Base {
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

	public function Contributors()
	{
		$data = [];
		//check if the current user is admin
		//admin can add contributor to school admin
		//but only school admin can add their own contributors
		$data['contributors'] = [];
		$data['is_admin'] = true;
		if(current_user_can('manage_options')) {
			$account_model = new YB_Account_Model;
			$accounts = $account_model->get();
			$data['accounts'] = $accounts;

			$contributors_model = new YB_Contributor_Model;
			$data['contributors'] = $contributors_model->get();
		}else{
			$data['is_admin'] = false;
			$current_user = wp_get_current_user();
			$contributors_model = new YB_Contributor_Model;
			$data['contributors'] = $contributors_model->getById($current_user->ID);
		}
		
		YB_View::get_instance()->admin_partials('partials/contributor/index.php', $data);
	}

	public function edit()
	{
		$data = [];
		$user_id = isset($_GET['id']) ? $_GET['id']:$_POST['user_id'];
		$user_info = get_userdata($user_id);

		$data['is_admin'] = false;
		if(current_user_can('manage_options')) {
			$data['is_admin'] = true;
			$account_model = new YB_Account_Model;
			$accounts = $account_model->get();
			$data['accounts'] = $accounts;

			$data['obj_account_meta'] = new YB_Account_Meta;
		}else{
			$current_user = wp_get_current_user();
			$data['current_user'] = $current_user;
		}

		$user_meta = new YB_Contributor_Meta;
		$school_admin_id = $user_meta->parent_school_admin(['user_id'=>$user_id,'single'=>true]);

		$data['action_url'] = yb_contributor_url();
		$data['method'] = 'update';
		$data['show_random_password'] = false;
		$data['password'] = null;
		$data['contributor_name'] = isset($_POST['contributor_name']) ? $_POST['contributor_name']:$user_info->display_name;
		$data['username'] = isset($_POST['username']) ? $_POST['username']:$user_info->user_login;
		$data['email'] = isset($_POST['email']) ? $_POST['email']:$user_info->user_email;
		$data['user_id'] = $user_id;
		$data['school_admin_id'] = $school_admin_id;
		YB_View::get_instance()->admin_partials('partials/contributor/edit.php', $data);
	}

	public function create()
	{
		$data = [];
		$data['is_admin'] = false;
		if(current_user_can('manage_options')) {
			$data['is_admin'] = true;
			$account_model = new YB_Account_Model;
			$accounts = $account_model->get();
			$data['accounts'] = $accounts;

			$data['obj_account_meta'] = new YB_Account_Meta;
		}else{
			$current_user = wp_get_current_user();
			$data['current_user'] = $current_user;
		}
		$data['action_url'] = yb_contributor_url();
		$data['method'] = 'insert';
		$data['show_random_password'] = true;
		$data['password'] = wp_generate_password( 8, false );
		$data['contributor_name'] = isset($_POST['contributor_name']) ? $_POST['contributor_name']:'';
		$data['username'] = isset($_POST['username']) ? $_POST['username']:'';
		$data['email'] = isset($_POST['email']) ? $_POST['email']:'';
		$data['school_admin_id'] = 0;
		YB_View::get_instance()->admin_partials('partials/contributor/create.php', $data);
	}

	public function insert()
	{
		if(isset($_POST['_method'])){
			$validate = [
				'contributor_name@Contributor Name' => 'required',
				'username@User Name' => 'required|user_exists',
				'email@Email' => 'required|email|email_exists',
			];

			//has error
			if(yb_validation_has_error($validate)) {
				$this->create();
			}else{
					if(current_user_can('manage_options')) {
						$school_admin_id = $_POST['school_admin'];
					}else{
						$current_user = wp_get_current_user();
						$school_admin_id = $current_user->ID;
					}

					$contributor_name = $_POST['contributor_name'];
					$username = $_POST['username'];
					$user_email = $_POST['email'];
					$password = $_POST['password'];
					$website = wp_login_url();
					$userdata = array(
					    'user_login' 		=>  $username,
							'user_email'		=> 	$user_email,
					    'user_url'   		=>  $website,
					    'user_pass'  		=>  $password,
							'user_nicename'	=> $_POST['contributor_name'],
							'display_name'	=> $_POST['contributor_name'],
					);

					$user_id = wp_insert_user( $userdata ) ;

					// On success.
					if ( ! is_wp_error( $user_id ) ) {
						$meta = new YB_Contributor_Meta;
						$meta->is_contributors([
							'user_id' => $user_id,
							'action' 	=> 'u',
							'value' 	=> 1
						]);
						$meta->parent_school_admin([
							'user_id' => $user_id,
							'action' 	=> 'u',
							'value' 	=> $school_admin_id
						]);

						wp_update_user( array ('ID' => $user_id, 'role' => 'contributor') ) ;
						if(isset($_POST['send_login_details'])) {
							$to = $user_email;
							$subject = 'Yearbook Contributor Account';
							$body = '<p>Here is your YearBook Contributor Account</p>';
							$body.= '<p><a href="'.$website.'">Click Here to Login</a></p>';
							$body.= '<p>Username : '.$username.'</p>';
							$body.= '<p>Password : '.$password.'</p>';
							$headers = array('Content-Type: text/html; charset=UTF-8');

							wp_mail( $to, $subject, $body, $headers );
						}
						$redirect_to = yb_contributor_url();
						yb_redirect_to($redirect_to);
					}//! is_wp_error( $user_id )
			}

		}//isset($_POST['_method'])
	}

	public function update()
	{
		if(isset($_POST['_method'])){
			$user_id = $_POST['user_id'];

			$validate = [
				'contributor_name@Contributor Name' => 'required',
				'email@Email' => 'required|email|unique_email:'.$user_id,
			];
			if(yb_validation_has_error($validate)) {
				$this->edit();
			}else{

				if(current_user_can('manage_options')) {
					$school_admin_id = $_POST['school_admin'];
				}else{
					$current_user = wp_get_current_user();
					$school_admin_id = $current_user->ID;
				}

				$user_id = $_POST['user_id'];
				$contributor_name = $_POST['contributor_name'];
				$user_email = $_POST['email'];
				$password = trim($_POST['password']);

				$userdata = array(
						'ID'						=> 	$user_id,
						'user_email'		=> 	$user_email,
						'user_nicename'	=> $contributor_name,
						'display_name'	=> $contributor_name,
				);

				if($password != '' ){
					$userdata['user_pass'] = $password;
				}
				wp_update_user( $userdata ) ;

				$meta = new YB_Contributor_Meta;
				$meta->parent_school_admin([
					'user_id' => $user_id,
					'action' 	=> 'u',
					'value' 	=> $school_admin_id
				]);

				$redirect_to = yb_contributor_url('&_method=edit&id='.$user_id.'&school_admin_id='.$school_admin_id.'');
				yb_redirect_to($redirect_to);
			}
		}
	}

	public function verify_delete()
	{
		if(
			isset($_GET['delete_user_nonce'])
			&& isset($_GET['id'])
		) {
			$user_id = $_GET['id'];
			$nonce = 'delete_user_' . $user_id;
			if(wp_verify_nonce($_GET['delete_user_nonce'], $nonce)) {
				$user_info = get_userdata($user_id);
				$data['count_user_posts'] = count_user_posts( $user_id, YB_CPT_PREFIX );
				$data['user_info'] = $user_info;

				$data['action_url'] = yb_contributor_url();
				$data['method'] = 'delete';
				$data['user_id'] = $user_id;

				YB_View::get_instance()->admin_partials('partials/contributor/delete.php', $data);
			}
		}
	}

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

				$redirect_to = yb_contributor_url();
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
