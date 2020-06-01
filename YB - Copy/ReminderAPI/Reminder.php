<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
class YB_ReminderAPI_Reminder {
   public $tag;
   public $due;
   public $article_title;
   public $members;
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

  public function __construct($tag, $due, $article_title, $members=[]){
      $this->tag = $tag;
      $this->due = $due;
      $this->article_title = $article_title;
      $this->members = $members;
   }
}
