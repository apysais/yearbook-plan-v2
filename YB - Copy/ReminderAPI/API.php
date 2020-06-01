<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class YB_ReminderAPI_API {

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

  public $api_endpoint = 'https://reminder-api.allteams.nz/api';

  public function set(YB_ReminderAPI_Reminder $reminder){
     $result = $this->_get_json('/set', json_encode($reminder));
     return $result;
  }

  public function clear($tag){
     $result = $this->_get_json('/clear', json_encode(["tag" => $tag]));
     return $result;
  }

  public function get_overdue($date){
     $result = $this->_get_json('/list_overdue?date='.$date);
     return $result;
  }

  private function _get_json($query, $data=false){
     $ch = curl_init($this->api_endpoint . $query);
     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
     if ( $data ) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     }
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $result = curl_exec($ch);
     return json_decode($result, true);
  }
}
