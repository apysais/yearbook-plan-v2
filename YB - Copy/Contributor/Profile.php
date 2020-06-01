<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Get the projects Task
 * @since 0.0.1
 * */
class YB_Contributor_Profile {
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

	public function update()
	{
		if( is_user_logged_in() ) {

			$current_user = wp_get_current_user();
			$data = [];

			if(isset($_POST['update-profile'])){

				$user_id = $current_user->ID;
				$nonce = wp_verify_nonce( $_POST['update-profile'], 'update_profile_'.$user_id );

				if($nonce) {
					$validate = [
						'full_name@Full Name' => 'required',
						'email@Email' => 'required|email|unique_email:'.$user_id,
					];
					if(yb_validation_has_error($validate)) {
						yb_get_validation();
					}else{
						$contributor_name = $_POST['full_name'];
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
						$redirect_to = site_url('?my-profile');
						yb_redirect_to($redirect_to);
					}
				}//$nonce
			}//isset($_POST['update-profile'])
		}//is_user_logged_in()
	}

	public function show()
	{
		if( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$data = [];
			$data['current_user'] = $current_user;
			$partial_template = YB_View::get_instance()->public_part_partials('partials/dashboard/profile.php');
			return YB_View::get_instance()->display($partial_template, $data);
		}
	}

  public function __construct(){}
}
