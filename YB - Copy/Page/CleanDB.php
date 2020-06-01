<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Bulk Edit
 * @since 0.0.1
 * */
class YB_Page_CleanDB {
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

	public function __construct()	{

	}

  public function clean( $args = [] ) {
    $parent_id = 0;
    $school_id = 0;

    if ( isset( $args['post_id'] ) ) {
      $post_id = $args['post_id'];
    }
    if ( isset( $args['school_id'] ) ) {
      $school_id = $args['school_id'];
    }
    if ( isset( $args['parent_id'] ) ) {
      $parent_id = $args['parent_id'];
    }

    if ( $parent_id != 0 ) {
      $args = [
        'post_type' => 'yearbook-plan',
        'post_parent' => $parent_id,
        'posts_per_page' => -1,
				'post_status' => ['publish', 'pending', 'draft' ]
      ];

      $query = new WP_Query( $args );
      if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
          $query->the_post();
          $post_id = get_the_ID();

          $medias = get_attached_media( 'image', $post_id );
          if ( $medias ) {
            foreach ( $medias as $media ) {
              wp_delete_attachment($media->ID, 1);
            }
          }

          $update_post = array(
            'ID'           => $post_id,
            'post_content'   => '',
          );
          wp_update_post($update_post);

          $due_date = YB_Project_PagesMeta::get_instance()->due_date([
            'action' => 'u',
            'post_id' => $post_id,
            'value' => ''
          ]);
          $status = YB_Project_PagesMeta::get_instance()->status([
            'action' => 'u',
            'post_id' => $post_id,
            'value' => 0
          ]);
          $submitted = YB_Project_PagesMeta::get_instance()->submitted([
            'action' => 'u',
            'post_id' => $post_id,
            'value' => 0
          ]);
        }
      }

      $update_parent_post = [
        'ID' => $parent_id,
        'post_status' => 'draft'
      ];
      wp_update_post($update_parent_post);

    }//if

  }//clean method

}
