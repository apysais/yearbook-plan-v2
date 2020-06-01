<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Use on the view single article page admin.
 * add a metebox on the right side.
 * @since 0.0.1
 * */
class YB_Project_MetaBox_WP {
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

	/**
   * Meta box initialization.
   */
  public function init_metabox() {
      add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
      add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
  }
	/**
   * Adds the meta box.
   */
  public function add_metabox() {
			global $post;
			//print_r($post);

			$project_cpt = new YB_Project_CPT;
			$text_domain = yb_get_text_domain();

			$verb = yb_verbage('task_metabox');
			$task = new YB_Project_MetaBox_Task;
			add_meta_box(
				'task-meta-images',
				'Images',
				array( $task, 'render_metabox_images' ),
				YB_CPT_PREFIX,
				'advanced',
				'high'
			);
			add_meta_box(
				'task-meta-settings',
				'Options/Stat',
				array( $task, 'render_metabox_settings' ),
				YB_CPT_PREFIX,
				'side',
				'high'
			);
  }

	/**
	* save the metabox.
	**/
	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		//$nonce_name   = isset( $_POST['task_metabox_nonce'] ) ? $_POST['task_metabox_nonce'] : '';
		//$nonce_action = 'task_metabox_nonce_action';
		$nonce_name   = isset( $_POST['task_metabox_nonce'] ) ? $_POST['task_metabox_nonce'] : '';
		$nonce_action = 'task_metabox_nonce_yearbook_settings';
		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
				return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
				return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
				return;
		}

		if(isset($_POST['done'])) {
			YB_Project_Pages::get_instance()->is_finished([
				'action' => 'u',
				'post_id' => $post_id,
				'value' => $_POST['done']
			]);
		}
	}

  public function __construct()
  {
		if ( is_admin() ) {
        add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
        add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}
  }

}
