<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Bulk Edit
 * @since 0.0.1
 * */
class YB_Network_Settings {
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
    if ( is_multisite() ) {
      add_action('network_admin_menu', [$this, 'menu']);
    }
	}

  public function menu() {
    add_menu_page(
        "YearBook Settings",
        "YearBook Settings",
        'manage_options',
        'yearbook-settings',
        [$this, 'settings']
    );
  }

  public function settings() {
    $data = [];
    YB_View::get_instance()->admin_partials('partials/settings/network/index.php', $data);
  }

	/**
	 * this is for MU
	 */
	public function cleanMU() {
		$all_blog = get_sites();
		foreach ($all_blog as $key => $current_blog) {
				// // switch to each blog to get the posts
				switch_to_blog($current_blog->blog_id);
				// fetch all the posts
				$args = [
					'post_type' => 'yearbook-plan',
					'post_parent' => 0,
					'posts_per_page' => -1,
					'post_status' => ['publish', 'pending', 'draft' ]
				];
				$query = new WP_Query( $args );
				if( $query->have_posts() ) :
					while( $query->have_posts() ) : $query->the_post();
						$post_id = get_the_ID();
						YB_Page_CleanDB::get_instance()->clean([
							'parent_id' => $post_id
						]);
					endwhile;
				endif;
				wp_reset_postdata();

				restore_current_blog();
				// display all posts
		}
	}

  public function clean() {

    $action = 'cleandb';
    if (
      isset( $_GET['action'] )
      && $_GET['action'] == $action
      && isset($_GET['cleandb-nonce'])
    ) {
      if ( wp_verify_nonce( $_GET['cleandb-nonce'] , 'cleandb' ) ) {
				$this->cleanMU();
      }
    }
  }

}
