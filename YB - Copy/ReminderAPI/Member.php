<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class YB_ReminderAPI_Member {
   public $email;
   public $user_name;
   public $login_url;
   protected static $instance = null;

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

  public function __construct($email, $user_name, $login_url) {
      $this->email = $email;
      $this->user_name = $user_name;
      $this->login_url = $login_url;
   }
}
