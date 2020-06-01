<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Bulk Edit
 * @since 0.0.1
 * */
class YB_Page_AjaxBulkEdit {
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

	public function bulkEditInit()
	{
		$data = [];
		$data['edit_block_id'] = isset($_POST['edit_block_id']) ? $_POST['edit_block_id'] : [];
		if(count($data['edit_block_id']) >= 1){
			$arg_posts = [
				'include' => $data['edit_block_id'],
				'post_type' => YB_CPT_PREFIX,
				'post_status' => 'any'
			];
			$articles = get_posts($arg_posts);
			$data['articles'] = $articles;
			$yearbook_page = get_post($_POST['yearbook_id']);
			$data['authors'] = [];
			if($yearbook_page){
				$arg_contributor = [
					'id' => $yearbook_page->post_author
				];
				$authors = YB_Project_Rest_Task::get_instance()->getSchoolAdminContributor($arg_contributor);
				$data['authors'] = $authors;
			}
			//apyc_dd($authors);
			//apyc_dd($articles);
			YB_View::get_instance()->admin_partials('partials/page/bulk-edit.php', $data);
		}else{
			echo '<h3>Select checkbox to Edit.</h3>';
		}
		wp_die();
	}

	public function bulkEditUpdate()
	{
		$ids = isset($_POST['edit_block_ids']) ? $_POST['edit_block_ids'] : [];
		$due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';
		$assign_to = isset($_POST['assign_to']) ? $_POST['assign_to'] : false;
		$is_cover = isset($_POST['is_cover']) ? $_POST['is_cover'] : 0;
		$status = $_POST['status'];

		if($ids && count($ids) >= 1){

			foreach($ids as $k => $v){
				$block_id = $v;


				$update_block_child_post = [
					'ID' => $block_id,
				];

				if($due_date != ''){
					$update_block_child_post['meta_input'] = ['due_date' => $due_date];
				}

				$update_block_child_post['meta_input'] = ['is_cover' => $is_cover];

				wp_update_post($update_block_child_post);

				if($assign_to && count($assign_to) >= 1){
					YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
						'post_id' => $block_id,
						'action' => 'u',
						'value' => $assign_to
					]);
				}

				//status
				$status = 0;
				$submitted = 0;

				if( isset($_POST['status']) ){
					$status = $_POST['status'];
				}

				if($status != 0){
					$submitted = 1;
				}

				YB_Project_Pages::get_instance()->is_finished([
					'post_id' => $block_id,
					'single' => true,
					'action' => 'u',
					'value' => $status,
				]);
				YB_Project_Pages::get_instance()->updateSubmitted($block_id, $submitted);

				//status
			}//foreach

		}

		wp_die();
	}

	public function __construct()
	{
		add_action( 'wp_ajax_bulk_edit_init', array($this, 'bulkEditInit') );
		add_action( 'wp_ajax_bulk_edit_udpate', array($this, 'bulkEditUpdate') );
	}

}
