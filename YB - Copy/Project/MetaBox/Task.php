<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
* Use for the image metabox in the yearbookplan admin CPT.
**/
class YB_Project_MetaBox_Task {
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
	* Render metabox.
	**/
	public function render_metabox_images( $post )
	{
		$media = get_attached_media( 'image', $post->ID );
		$data['images'] = $media;
		YB_View::get_instance()->admin_partials('partials/project/metabox/task-images.php', $data);
	}

	public function render_metabox_settings($post)
	{
		wp_nonce_field( 'task_metabox_nonce_yearbook_settings', 'task_metabox_nonce' );
		$data = [];
		$media = get_attached_media( 'image', $post->ID );
		$data['word_count'] = str_word_count(wp_strip_all_tags($post->post_content));
		$data['photo_count'] = count($media);
		$data['is_finished'] = YB_Project_Pages::get_instance()->is_finished([
			'post_id' => $post->ID,
			'action' => 'r',
			'single'=>true
		]);
		$data['post_yearbook_id'] = $post->post_parent;
		//print_r($data);
		YB_View::get_instance()->admin_partials('partials/project/metabox/task-settings.php', $data);
	}

  public function __construct()
  {
  }

}
