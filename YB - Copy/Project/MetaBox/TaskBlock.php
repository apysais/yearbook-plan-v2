<?php
//not use anymore
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * This is for the Ocean Theme Hooks
 * Not used anymore.
 * @since 0.0.1
 * */
class YB_Project_MetaBox_TaskBlock {
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
   * Renders the meta box.
   */
  public function render_metabox( $post ) {
      // Add nonce for security and authentication.
      wp_nonce_field( 'task_metabox_nonce_action', 'task_metabox_nonce' );

			$verb = yb_verbage('task_block_metabox');
			$meta = get_post_meta($post->ID);

			$data = [];
			$data['title'] = $verb['title'];
			$data['name'] = $verb['name'];
			$data['posts'] = $post;
			if(
					$post->post_status != 'auto-draft'
					&& $post->post_parent != 0
					&& get_post_meta($post->ID, 'is_parent_page')
			) {
				YB_View::get_instance()->admin_partials('partials/project/metabox/task-block.php', $data);
			}else{
				YB_View::get_instance()->admin_partials('partials/project/metabox/task-block-single.php', $data);
			}
  }

  /**
   * Handles saving the meta box.
   *
   * @param int     $post_id Post ID.
   * @param WP_Post $post    Post object.
   * @return null
   */
  public function save_metabox( $post_id, $post ) {
      // Add nonce for security and authentication.
      $nonce_name   = isset( $_POST['task_metabox_nonce'] ) ? $_POST['task_metabox_nonce'] : '';
      $nonce_action = 'task_metabox_nonce_action';

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
			$data_post = $_POST;
			$insert_post = [];
			$insert_post_block = [];

			if(
				isset($data_post['current'])
				&& !empty($data_post['current'])
			){
				$user_id = get_current_user_id();
				$current_post = [
					'ID' => $data_post['current']['id'],
					'post_type' => $post->post_type,
					'post_author' => isset($data_post['current']['post_author']) ? $data_post['current']['post_author'] : $user_id,
					'meta_input' => $data_post['current']['meta']
				];
				wp_update_post($current_post);
				update_post_meta($data_post['current']['id'], 'page_number', $data_post['current']['meta']['page_number']);
				update_post_meta($data_post['current']['id'], 'due_date', $data_post['current']['meta']['due_date']);
				update_post_meta($data_post['current']['id'], 'submitted', $data_post['current']['meta']['submitted']);
			}//if(isset($data_post['tasks'])&& !empty($data_post['tasks']))
			if(
				isset($data_post['tasks'])
				&& !empty($data_post['tasks'])
			){
				$insert_block_post = [];
				$block_menu_order = 0;
				foreach($data_post['tasks'] as $k_block => $v_block) {
					$post_title = '';
					$post_meta = [];
					if(isset($v_block['block'])) {
						$block_index = 0;
						foreach($v_block as $child_block) {
							$db_block_post = [
								'ID' => isset($child_block['id'][0]) ? $child_block['id'][0] : 0,
								'post_parent' => $post_id,
								'post_title' => isset($child_block['post_title'][0]) ? $child_block['post_title'][0]:'',
								'post_type' => $post->post_type,
								'post_author' => isset($child_block['post_author'][0]) ? $child_block['post_author'][0] : 0,
								'menu_order' => $block_menu_order,
								'post_status' => 'publish',
								'meta_input' => [
									'page_number' => isset($child_block['meta']['page_number'][0]) ? $child_block['meta']['page_number'][0] : 0,
									'due_date' => isset($child_block['meta']['due_date'][0]) ? $child_block['meta']['due_date'][0] : 0,
									'submitted' => isset($child_block['meta']['submitted'][0]) ? $child_block['meta']['submitted'][0] : 0,
									'is_block' => 1
								]
							];
							$block_index++;
							if(
								$db_block_post['ID'] == 0
							){
								wp_insert_post($db_block_post);
							}else{
								wp_update_post($db_block_post);
								foreach($db_block_post['meta_input'] as $k => $v){
									update_post_meta($db_block_post['ID'], $k, $v);
								}
							}
							$block_menu_order++;
						}//foreach($v_block['post_title'] as $child_block)
					}//if(isset($v_block['post_title']))
				}//foreach($v as $k_block => $v_block)
			}
			update_post_meta( $post->post_parent, 'tasks', $data_post['tasks']);
  }

  public function __construct()
  {

  }

}
