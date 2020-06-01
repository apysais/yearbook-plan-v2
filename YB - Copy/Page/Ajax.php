<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Use to update yearbook through ajax.
 * create, delete, update and sort
 * @since 0.0.1
 * */
class YB_Page_Ajax {
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
	* Create the yearbook.
	**/
	public function create_yearbook()
	{
		global $wpdb; // this is how you get access to the database

		$data = [];
		//this will hold the id's of the yearbook section/category
		//save as post meta
		$section_category = [];
		$block_menu = 0;
		$page_number = 1;
		$index = 0;
		//parse the form inputs.
		parse_str($_POST['form'], $form);
		//apyc_dd($form, 1);
		//check if the form is already in DB
		$yearbook_id = isset($form['yearbook_id']) ? $form['yearbook_id'] : false;
		$yearbook_name = isset($form['yearbook-page-name']) ? trim($form['yearbook-page-name']) : false;

		//user owner
		if(current_user_can('manage_options')) {
			$user_id = $form['school_admin'];
		}else{
			$user_id = get_current_user_id();
		}
		//user owner

		//yearbook title
		if(
			!$yearbook_id
			&& $yearbook_id == 0
		){
			if($yearbook_name){
				$yb_name = get_page_by_title($yearbook_name, OBJECT, YB_CPT_PREFIX);
				if($yb_name) {
					$yearbook_id = $yb_name->ID;
				}
			}else{
				$yearbook_name = 'No Title - '.date("Y-m-d-Hi");
			}
		}else{
			$yearbook_name = 'No Title - '.date("Y-m-d-Hi");
		}
		//yearbook title

		//get_page_by_title
		//section category
		//$new_section_name = isset($form['new_task']['section_name']) ? $form['new_task']['section_name'] : false;
		$post_status = isset($form['post_status']) ? $form['post_status'] : 'draft';
		$new_block = isset($form['new_task']['block']) ? $form['new_task']['block'] : false;
		$existing_tasks = isset($form['task']) ? $form['task'] : false;
		$menu_order = isset($form['menu_order']) ? $form['menu_order'] : 0;

		//apyc_dd($existing_tasks['blocks']['block_id'], 1);
		//get section taxonomy | terms

		wp_defer_term_counting( true );
		wp_defer_comment_counting( true );

		//check if the yearbook id exists
		//if it exists we insert it
		//else update only
		if(!$yearbook_id) {
			//insert
			//parent first
			$insert_parent = [
				'post_title' => $yearbook_name,
				'post_type' => YB_CPT_PREFIX,
				'post_author' => $user_id,
				'post_status' => $post_status,
				'menu_order' => $menu_order,
				'meta_input' => [
					'is_parent_page' => 1,
					'school_admin_id' => $user_id,
				]
			];
			//apyc_dd($insert_parent, 1);
			//echo 'insert parent first';
			$yearbook_id = wp_insert_post($insert_parent);
		}else{
			//update
			$update_parent = [
				'ID' => $yearbook_id,
				'post_title' => $form['yearbook-page-name'],
			];
			wp_update_post($update_parent);
		}
		//parent

		//block
		//use to re-order
		//loop existing data
		//apyc_dd($existing_tasks);
		if($existing_tasks) {
			if(isset($existing_tasks['blocks'])) {
				foreach($existing_tasks['blocks']['block_id'] as $k => $v) {
						$block_post_id = $v;
						if ( $existing_tasks['blocks']['is_cover'][$index] == 0 || $existing_tasks['blocks']['is_cover'][$index] == ''  ) {
								$block_menu++;
						}
						$update_block_child_post = [
							'ID' => $v,
							'menu_order' => $block_menu,
						];
						//apyc_dd($update_block_child_post);
						wp_update_post($update_block_child_post);
						$index++;
				}//loop $existing_tasks['blocks']
			}//isset($existing_tasks['blocks'])
		}//$existing_tasks
		//loop existing data
		//apyc_dd([], 1);
		if(
			$new_block
			&& count($new_block) >= 1
		){
			//apyc_dd($new_block);
			foreach($new_block as $k_block => $v_block){
				//insert
				$block_size = $v_block['block_size_fullpage'] . $v_block['block_size_partpage'];
				if($v_block['block_size_partpage'] == 0){
					$block_size = $v_block['block_size_fullpage'];
				}
				if ( isset($v_block['custom_block_size_fullpage']) && ($v_block['block_size_fullpage'] == '-1' && $v_block['custom_block_size_fullpage'] != 0) ) {
					$block_size = $v_block['custom_block_size_fullpage'];
				}
				$insert_block_child_post = [
					'post_title' => $v_block['block_title'],
					'post_type' => YB_CPT_PREFIX,
					//'post_author' => $new_block['post_author'],
					'post_author' => $user_id,
					'post_parent' => $yearbook_id,
					'post_status' => $post_status,
					'menu_order' => $v_block['menu_order'],
					'meta_input' => [
						'block_title' => $v_block['block_title'],
						'template' => $v_block['template'],
						'due_date' => $v_block['due_date'],
						'block_size_fullpage' => $v_block['block_size_fullpage'],
						'block_size_partpage' => $v_block['block_size_partpage'],
						'block_size' => $block_size,
						'submitted' => 0,
						'is_child_block' => 1,
						'is_finished' => 0,
						'school_admin_id' => $user_id,
						'yb_uniqid' => uniqid(),
						'is_cover' => $v_block['is_cover']
					]
				];
				//apyc_dd($insert_block_child_post,1);
				$block_id = wp_insert_post($insert_block_child_post);

				YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
					'post_id' => $block_id,
					'action' => 'u',
					'value' => $v_block['post_author']
				]);
				//$term_taxonomy_ids = wp_set_post_terms($block_id, [$block_section_id], 'section');
				do_action('yb_after_insert_block', $block_id, $insert_block_child_post, $post_status);
			}

		}

		//block
		//update yearbook main post
		//add the meta section
		//update yearbook main post

		wp_defer_term_counting( false );
		wp_defer_comment_counting( false );

		if($yearbook_id){
			$data['id'] = $yearbook_id;
			$contents = YB_Project_Rest_Task::get_instance()->get_v2($data);
			return wp_send_json($contents);
		}

		wp_die();
	}

	/**
	* Update the yearbook.
	**/
	public function update_yearbook()
	{
		$post_status = isset($_POST['post_status']) ? $_POST['post_status'] : false;
		//exit();
		//echo 'xx';
		//apyc_dd($_POST, 1);
		if(isset($_POST['yearbook_id'])){
			$block_size = $_POST['block_size_fullpage'] . $_POST['block_size_partpage'];
			if($_POST['block_size_partpage'] == 0){
				$block_size = $_POST['block_size_fullpage'];
			}

			if ( isset($_POST['custom_block_size_fullpage']) ) {
				$block_size = $_POST['custom_block_size_fullpage'];
			}
			if ( isset($v_block['custom_block_size_fullpage']) && ($v_block['block_size_fullpage'] == '-1' && $v_block['custom_block_size_fullpage'] != 0) ) {
				$block_size = $v_block['custom_block_size_fullpage'];
			}
			$update_block_child_post = [
				'ID' => $_POST['yearbook_id'],
				'post_title' => $_POST['block_title'],
				'post_status' => $_POST['post_status'],
				'post_author' => $_POST['assign_to'],
				'menu_order' => $_POST['menu_order'],
				'meta_input' => [
					'block_title' => $_POST['block_title'],
					'due_date' => $_POST['due_date'],
					'block_size_fullpage' => $_POST['block_size_fullpage'],
					'block_size_partpage' => $_POST['block_size_partpage'],
					'is_cover' => $_POST['is_cover'],
					'block_size' => $block_size,
					'submitted' => isset($_POST['submitted']) ? $_POST['submitted'] : '',
					'is_child_block' => 1,
				]
			];
			wp_update_post($update_block_child_post);

			if(isset($_POST['assign_to']) && count($_POST['assign_to']) >= 1){
				YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
					'post_id' => $_POST['yearbook_id'],
					'action' => 'u',
					'value' => $_POST['assign_to']
				]);
			}

			do_action('yb_after_update_yearbook_single_blocks', $update_block_child_post, $post_status, $_POST);
		}

		wp_die();
	}

	/**
	* Sort Articles.
	**/
	public function sort_blocks()
	{
		parse_str($_POST['form'], $form);
		$block_menu = 0;
		$index = 0;
		$existing_tasks = isset($form['task']) ? $form['task'] : false;
		//block
		//use to re-order
		//loop existing data
		//apyc_dd($form);
		if($existing_tasks) {
			if(isset($existing_tasks['blocks'])) {
				foreach($existing_tasks['blocks']['block_id'] as $k => $v) {
						$block_post_id = $v;
						if ( $existing_tasks['blocks']['is_cover'][$index] == 0 || $existing_tasks['blocks']['is_cover'][$index] == ''  ) {
								$block_menu++;
						}
						$update_block_child_post = [
							'ID' => $block_post_id,
							'menu_order' => $block_menu,
						];
						//apyc_dd($update_block_child_post);
						wp_update_post($update_block_child_post);
						$index++;
						//$term_taxonomy_ids = wp_set_post_terms($block_post_id, [$block_section_id], 'section');
				}//loop $existing_tasks['blocks']
			}//isset($existing_tasks['blocks'])
		}//$existing_tasks
		//loop existing data
		wp_die();
	}

	public function change_status()
	{
		//print_r($_POST);
		$status = 0;
		$submitted = 0;

		if( isset($_POST['status']) ){
			$status = $_POST['status'];
		}

		if($status != 0){
			$submitted = 1;
		}

		YB_Project_Pages::get_instance()->is_finished([
			'post_id' => $_POST['block_id'],
			'single' => true,
			'action' => 'u',
			'value' => $status,
		]);
		YB_Project_Pages::get_instance()->updateSubmitted($_POST['block_id'], $submitted);


		wp_die();
	}

	public function __construct()
	{
		add_action( 'wp_ajax_yb_create_yearbook', array($this, 'create_yearbook') );
		add_action( 'wp_ajax_yb_update_yearbook', array($this, 'update_yearbook') );
		add_action( 'wp_ajax_yb_update_section', array($this, 'update_section') );
		add_action( 'wp_ajax_yb_add_section', array($this, 'add_section') );
		add_action( 'wp_ajax_yb_sort_blocks', array($this, 'sort_blocks') );
		add_action( 'wp_ajax_yb_sort_sections', array($this, 'sort_sections') );
		add_action( 'wp_ajax_yb_change_status', array($this, 'change_status') );
	}

}
