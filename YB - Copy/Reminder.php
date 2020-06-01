<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Page Visual.
 * @since 0.0.1
 * */
class YB_Reminder {
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

  public function __construct() {

  }

	/**
	 * send reminder.
	 * @param $args / array {
	 *	@type $tag string
	 *	@type $due_date date yyyy-mm-dd {year-month-date}
	 *	@type $article_title string
	 *		@type $send_to array {
	 *			@type $email email of the contributor
	 *			@type $name string name of the contributor
	 *			@type $url string Url of the article
 	 *  	}
 	 * }
	 */
	public function send( $args = [] ) {
		//apyc_dd($args);
		if (
			!empty( $args['send_to'] )
			&& isset( $args['send_to'] )
		) {
			$tag 						= isset($args['tag']) ? $args['tag'] : '';
			$due_date 			= isset($args['due_date']) ? $args['due_date'] : '';
			$article_title 	= isset($args['article_title']) ? $args['article_title'] : '';

			$api = new YB_ReminderAPI_API();

	    $reminder = new YB_ReminderAPI_Reminder($tag, $due_date, $article_title, [$args['send_to']]);

	    $result = $api->set($reminder);
			return $result;
		}

	}
}
