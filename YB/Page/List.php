<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * List Page.
 * @since 0.0.1
 * */
 class YB_Page_List {
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
    parent::__construct( [
			'singular' => __( 'Year Book Plan', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Year Book Plans', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
  }


}
