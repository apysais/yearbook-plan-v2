<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Controller for the article page.
 * @since 0.0.1
 * */
class YB_Page_Controller extends YB_Base {
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

	public function bulkEdit()
	{
		print_r($_POST);
	}

	/**
	* Insert / Create Yearbook.
	**/
	public function insert()
	{
		$this->update();
	}

	/**
	* Update Yearbook.
	**/
	public function update()
	{

		$input_post = is_array($_POST) ? $_POST : false;
		$task = isset($_POST['task']) ? $_POST['task'] : false;
		if($input_post) {
			$yearbook_id = isset($_POST['yearbook_id']) ? $_POST['yearbook_id'] : false;
			$school_admin = isset($_POST['school_admin']) ? $_POST['school_admin'] : false;

			$yearbook_name = 'No Title - '.date("Y-m-d-Hi");
			if(trim($_POST['yearbook-page-name']) != '') {
				$yearbook_name = $_POST['yearbook-page-name'];
			}

			$status = $_POST['status'];

			if($yearbook_id) {
				$update_parent = [
					'ID' => $yearbook_id,
					'post_title' => $yearbook_name,
					'post_status' => $status,
					'meta_input' => [
						'school_admin_id' => $school_admin,
					]
				];
				wp_update_post($update_parent);
				do_action('yb_after_update_yearbook', $input_post);
			}//yearbook_id
			$existing_tasks = isset($input_post['task']) ? $input_post['task'] : false;
			$block_menu = 0;
			$index = 0;
			//loop existing data
			//apyc_dd($existing_tasks, 1);
			if($existing_tasks) {
				if(isset($existing_tasks['blocks'])) {
					foreach($existing_tasks['blocks']['block_id'] as $k => $v) {
							$block_post_id = $v;

							if ( $existing_tasks['blocks']['is_cover'][$index] == 0 || $existing_tasks['blocks']['is_cover'][$index] == ''  ) {
									$block_menu++;
							}

							$update_block_child_post = [
								'ID' => $block_post_id,
								'post_status' => $status,
								'menu_order' => $block_menu,
							];
							wp_update_post($update_block_child_post);
							$index++;
							//$term_taxonomy_ids = wp_set_post_terms($block_post_id, [$block_section_id], 'section');
					}//loop $existing_tasks['blocks']
				}//isset($existing_tasks['blocks'])
			}//$existing_tasks
			if($status == 'publish'){
				YB_Project_Notify::get_instance()->notifyGroup($input_post['task']['blocks']['block_id']);
			}
			//loop existing data
		}//$task

		$redirect_url = yb_admin_url_yearbookpage('&_method=showYearbook&id='.$yearbook_id.'&school_id='.$school_admin.'');
		yb_redirect_to($redirect_url);
	}

	public function add_new()
	{
		$data = [];
		$data['action_url'] = yb_admin_url_yearbookpage();
		$data['method'] = 'insert';
		$data['plugin_page_title'] = '';
		$fields = yb_verbage('fields');
		$data['verbage_fields'] = $fields;
		$data['is_admin'] = false;
		if(current_user_can('manage_options')) {
			$data['is_admin'] = true;
			$account_model = new YB_Account_Model;
			$accounts = $account_model->get();
			$data['accounts'] = $accounts;

			$data['obj_account_meta'] = new YB_Account_Meta;
			$data['school_admin_id'] = 0;
		}else{
			$current_user = wp_get_current_user();
			$data['current_user'] = $current_user;
			$school_admin_id = get_current_user_id();
			$data['school_admin_id'] = $school_admin_id;
		}

		$data['yearbook_id'] = 0;
		$data['show_controller'] = false;
		$data['name'] = '';
		$data['status'] = 'draft';

		YB_View::get_instance()->admin_partials('partials/page/create.php', $data);
	}

	public function YearBook()
	{
		yb_redirect_to( yb_admin_url_yearbookpage_list() );
	}

	public function showYearbook($id)
	{
		$data = [];
		$id = 0;
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$arg_parent = [
				'include' => $id,
				'post_type' => YB_CPT_PREFIX,
				'post_status' => 'any',
			];
			$parent_post   = get_posts( $arg_parent );
			$name = $parent_post[0]->post_title;
			$status = $parent_post[0]->post_status;

			$data['status'] = $status;
			$data['name'] = $name;
			$data['action_url'] = yb_admin_url_yearbookpage();
			$data['method'] = 'update';
			$data['plugin_page_title'] = '';
			$fields = yb_verbage('fields');
			$data['verbage_fields'] = $fields;
			$data['is_admin'] = false;

			$current_user = wp_get_current_user();

			if( current_user_can('manage_options') ) {
				$data['is_admin'] = true;
				$account_model = new YB_Account_Model;
				$accounts = $account_model->get();
				$data['accounts'] = $accounts;

				$data['obj_account_meta'] = new YB_Account_Meta;
				$school_admin_id = $parent_post[0]->post_author;
				$data['school_admin_id'] = $school_admin_id;
				$data['is_admin'] = true;
			}else{
				//$current_user = wp_get_current_user();
				$data['current_user'] = $current_user;
				$school_admin_id = get_current_user_id();
				$data['school_admin_id'] = $school_admin_id;
			}
			$data['school_name'] = get_userdata($school_admin_id);
			$data['yearbook_id'] = $id;
			$data['show_controller'] = true;

			if ( get_post_meta($id, 'school_admin_id', true) ) {
				$data['school_admin_id'] = get_post_meta($id, 'school_admin_id', true);
			}

			YB_View::get_instance()->admin_partials('partials/page/create.php', $data);
		}else{
			yb_redirect_to( yb_admin_url_yearbookpage() );
		}
	}

	public function verify_delete()
	{
		if(
			isset($_GET['delete_yearbook_nonce'])
			&& isset($_GET['id'])
		) {
			$yearbook_id = $_GET['id'];
			$nonce = 'delete_yearbook_' . $yearbook_id;
			if(wp_verify_nonce($_GET['delete_yearbook_nonce'], $nonce)) {
				$data['action_url'] = yb_admin_url_yearbookpage();
				$data['method'] = 'delete';
				$data['yearbook_id'] = $yearbook_id;
				$yearbook_post   = get_post( $yearbook_id );
				$data['yearbook_post'] = $yearbook_post;
				YB_View::get_instance()->admin_partials('partials/page/delete.php', $data);
			}
		}
	}

	public function delete()
	{
		if (
	    isset( $_POST['delete_yearbook'] )
			&& isset($_POST['yearbook_id'])
		) {
			$yearbook_id = $_POST['yearbook_id'];
			$nonce = 'delete_this_yearbook_' . $yearbook_id;

			if(wp_verify_nonce( $_POST['delete_yearbook'], $nonce ) ) {

				$args = array(
				    'post_parent' => $yearbook_id,
				    'post_type' => YB_CPT_PREFIX
				);

				$posts = get_posts( $args );

				if (is_array($posts) && count($posts) > 0) {
				    // Delete all the Children of the Parent Page
				    foreach($posts as $post){
								$blocks_args = array(
								    'post_parent' => $post->ID,
								    'post_type' => YB_CPT_PREFIX
								);
								$block_posts = get_posts( $blocks_args );
								if (is_array($block_posts) && count($block_posts) > 0) {
									foreach($block_posts as $block_post){
										wp_delete_post($block_post->ID, true);
										wp_delete_attachment($block_post->ID);
									}
								}
								wp_delete_post($post->ID, true);
								wp_delete_attachment($post->ID);
				    }
				}
				wp_delete_post($yearbook_id, true);

				$redirect_to = yb_admin_url_yearbookpage_list();
				yb_redirect_to($redirect_to);
			}
		}
	}

	public function verify_delete_block()
	{
		$data = [];
		$school_id = $_GET['school_id'];
		$yb_id = $_GET['yb'];
		$block_id = $_GET['block_id'];
		if(
			isset($_GET['delete-block-nonce'])
			&& wp_verify_nonce($_GET['delete-block-nonce'], 'delete-block-'.$block_id.$school_id.$yb_id)
		){
			$posts = get_post($block_id);
			$data['posts'] = $posts;
			$data['action_url'] = yb_admin_url_yearbookpage();
			$data['method'] = 'trash-block';
			$data['yb_id'] = $yb_id;
			$data['school_id'] = $school_id;
			YB_View::get_instance()->admin_partials('partials/page/delete-block.php', $data);
		}
	}

	public function trash_block()
	{

		$block_id = $_POST['block_id'];
		$yb_id = $_POST['yb_id'];
		$school_id = $_POST['school_id'];
		if(isset($_POST['delete_block'])
			&& wp_verify_nonce($_POST['delete_block'], 'delete_this_block_'.$block_id)
		){
			do_action('yb_before_delete_block', $block_id);
			wp_delete_post($block_id, 1);
		}
		$redirect_to = yb_admin_url_yearbookpage('&_method=showYearbook&id='.$yb_id.'&school_id='.$school_id);
		yb_redirect_to($redirect_to);
	}

	public function ExportYB()
	{
		$data = [];
		$arg_get_data = [
			'post_parent' => 0,
			'numberposts' => '-1',
			'post_type'  => YB_CPT_PREFIX,
		];
		$get_data = get_posts($arg_get_data);
		$data['articles'] = $get_data;
		YB_View::get_instance()->admin_partials('partials/settings/export/index.php', $data);
	}

	public function exportYearBook()
	{
		if(isset($_GET['site-id']) && isset($_GET['article-id'])){
			$site_id = $_GET['site-id'];
			$article_id = $_GET['article-id'];
			$school_id = $_GET['school-id'];

			exec("wp yb-export --site-id='".$site_id."' --article-id='".$article_id."'", $result);
			//apyc_dd($result);
			$data['result'] = $result;
			$data['go_back'] = '<a href="'.admin_url('admin.php?page=YearBook&_method=showYearbook&id='.$article_id.'&school_id='.$school_id).'">Go Back</a>';
			YB_View::get_instance()->admin_partials('partials/settings/export/export.php', $data);
		}

	}

	public function cleandb( $id = '') {
		$parent_id = 0;
		$data_post = [];
		if ( isset( $_GET['id'] ) && $_GET['id'] > 0 ) {
			$parent_id = $_GET['id'];
			$school_id = $_GET['school_id'];
			$nonce = 'cleandb-page_id-' . $parent_id . '-school-id-' . $school_id;
			if ( isset( $_GET['cleandb-nonce'] ) && wp_verify_nonce( $_GET['cleandb-nonce'] , $nonce ) ) {
				$arg_clean = [
					'parent_id' => $parent_id,
					'school_id' => $school_id,
				];
				YB_Page_CleanDB::get_instance()->clean($arg_clean);
				//apyc_dd($query);
			}
			$redirect_to = yb_admin_url_yearbookpage('&_method=showYearbook&id='.$parent_id.'&school_id='.$school_id);
			yb_redirect_to($redirect_to);
		}

	}

	/**
	 * Controller
	 *
	 * @param	$action		string | empty
	 * @parem	$arg		array
	 * 						optional, pass data for controller
	 * @return mix
	 * */
	public function controller($action = '', $arg = array()){
		$this->call_method($this, $action);
	}

	public function __construct(){}

}
