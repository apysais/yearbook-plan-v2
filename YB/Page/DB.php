<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * List Page.
 * @since 0.0.1
 * */
 class YB_Page_DB {
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

  public function get( $args ) {
    $data = false;

    $defaults = array (
      'post_type' => YB_CPT_PREFIX,
      'post_parent' => 0
    );

    // Parse incoming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    $the_query = new WP_Query( $args );
    if ( $the_query->have_posts() ) {
      $data = $the_query;
    }

    wp_reset_postdata();
    
    return $data;

  }

}
