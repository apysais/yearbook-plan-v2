<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 *
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

	/**
	*	Get current user pages
	**/
	public function getPages( $user_id = null )
	{
		if( $user_id == null ) {
			$current_user = wp_get_current_user();
			$roles 				= ( array ) $current_user->roles;
			$user_id 			= $current_user->ID;
		}

		$post_type = YB_Project_Pages::get_instance()->getCptPrefix();
		$args = [
			'post_type' => $post_type,
			'posts_per_page' => '-1',
			'meta_query' => [
				'relation' => 'AND',
				'due_date' => [
					'key' => 'due_date',
					'compare' => 'EXISTS'
				],
				[
					'key' => 'yb_multiple_authors',
					'value' => $user_id,
					'compare' => 'LIKE'
				],
				/*'submitted' => [
					'key' => 'submitted',
					'value' => '0',
				]*/
			],
			'orderby' => [
				'due_date' => 'ASC'
			]
		];

		if ( yb_is_admin_current_user($current_user) ) {
		    unset($args['author']);
		}
		$query = new WP_Query($args);
		return $query;
	}

	public function parseList($user_id = null)
	{
		if( $user_id == null ) {
			$current_user = wp_get_current_user();
			$roles 				= ( array ) $current_user->roles;
			$user_id 			= $current_user->ID;
		}

		$data = [];
		$query = $this->getPages();
		$obj_meta = new YB_Project_PagesMeta;
		if ( $query->have_posts() ) {
			foreach($query->posts as $k => $v) {
				$user_meta = get_userdata($user_id);
				$meta = get_post_meta($v->ID);
				$data[] = [
					'id' => $v->ID,
					'title'  => $v->post_title,
					'assign_to'  => $user_id,
					'assign_to_name'  => isset($user_meta->user_nicename) ? $user_meta->user_nicename : '',
					'assign_to_display_name'  => isset($user_meta->display_name) ? $user_meta->display_name : '',
					'due_date' => $obj_meta->getDueDate($v->ID),
					'due_date_standing' => allt_due_date_standing($obj_meta->getDueDate($v->ID)),
					'due_date_human' => time2str($obj_meta->getDueDate($v->ID)),
					'block_title' => $obj_meta->getBlockTitle($v->ID),
					'template' => $obj_meta->getTemplate($v->ID),
					'block_size' => $obj_meta->getBlockSize($v->ID),
					'submitted' => $obj_meta->getSubmitted($v->ID),
					'page_number' => $obj_meta->getPageNumber($v->ID),
					'is_child_block' => $obj_meta->getIsChildBlock($v->ID),
					'is_finished' => $obj_meta->getIsFinished($v->ID),
				];
			}
			wp_reset_postdata();
		}
		return $data;
	}

	public function list()
	{
			global $post;
			$data = [];
			$pages = new YB_Project_Pages;
			$parse = $this->parseList();
			$data_lists = [];
			if ($parse) {
				foreach($parse as $k=>$v) {
					if($v['submitted'] == 1 && $v['is_finished'] == 1) {
						$data_lists['done'][] = $v;
					}elseif($v['submitted'] == 1) {
						$data_lists['submitted'][] = $v;
					}else{
						$data_lists[$v['due_date_standing']][] = $v;
					}
				}
			}
			$data['tasks'] = $data_lists;

			$data['images'] = [];
			if($post) {
				$media = get_attached_media( 'image', $post->ID );
				$data['images'] = $media;
			}

			$partial_template = YB_View::get_instance()->public_part_partials('partials/dashboard/list.php');
			return YB_View::get_instance()->display($partial_template, $data);
	}

	public function edit($task_id)
	{

			global $post;
			$data = [];
			$pages = new YB_Project_Pages;
			$obj_meta = new YB_Project_PagesMeta;

			$current_user = wp_get_current_user();
			$roles 				= ( array ) $current_user->roles;
			$user_id 			= $current_user->ID;

			$args = [
				'include'   => [$task_id],
				'post_type' => YB_CPT_PREFIX,
			];
			if ( yb_is_admin_current_user($current_user) ) {
			    unset($args['author']);
			}
			$posts_array = get_posts( $args );
			$authors = YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
				'post_id' => $task_id,
				'action' => 'r',
				'single' => true
			]);
			$post_data = [];
			if ( $posts_array && in_array($user_id, $authors) ) {
				foreach($posts_array as $k => $v) {
					$user_meta = get_userdata($user_id);
					$meta = get_post_meta($v->ID);
					$block_size = 0;
					$full_block_size = YB_Project_PagesMeta::get_instance()->block_size_fullpage([
						'single' => true,
						'post_id' => $v->ID
					]);
					$part_block_size = YB_Project_PagesMeta::get_instance()->block_size_partpage([
						'single' => true,
						'post_id' => $v->ID
					]);;
					$post_data = [
						'id' => $v->ID,
						'title'  => $v->post_title,
						'post_content'  => $v->post_content,
						'assign_to'  => $user_id,
						'assign_to_name'  => $user_meta->user_nicename,
						'assign_to_display_name'  => $user_meta->display_name,
						'due_date' => $obj_meta->getDueDate($v->ID),
						'due_date_standing' => allt_due_date_standing($obj_meta->getDueDate($v->ID)),
						'due_date_human' => time2str($obj_meta->getDueDate($v->ID)),
						'due_date_format' => date("F j, Y", strtotime($obj_meta->getDueDate($v->ID))),
						'block_title' => $obj_meta->getBlockTitle($v->ID),
						'template' => $obj_meta->getTemplate($v->ID),
						'block_size' => $full_block_size.' and '.$part_block_size,
						'submitted' => $obj_meta->getSubmitted($v->ID),
						'page_number' => $obj_meta->getPageNumber($v->ID),
						'is_child_block' => $obj_meta->getIsChildBlock($v->ID),
						'is_finished' => $obj_meta->getIsFinished($v->ID),
					];
				}
				//apyc_dd($post_data);
				wp_reset_postdata();

				$data['objPages'] = $pages;
				$data['post'] = $post_data;
				$media = get_attached_media( 'image', $task_id );
				$data['images'] = $media;
				$standing = 'success';
				if($post_data['due_date_standing'] == 'Overdue') {
					$standing = 'danger';
				}elseif($post_data['due_date_standing'] == 'Upcoming') {
					$standing = 'info';
				}
				$data['standing'] = $standing;
				$pages = new YB_Project_Pages;
				$parse = $this->parseList();
				$data_lists = [];
				if ($parse) {
					foreach($parse as $k=>$v) {
						$data_lists[$v['due_date_standing']][] = $v;
					}
				}
				$data['tasks'] = $data_lists;
				$data['task_id'] = $task_id;
				$data['content_id'] = $task_id;
				$data['current_user_id'] = $user_id;
				$partial_template = YB_View::get_instance()->public_part_partials('partials/dashboard/editor.php');

				//lock the content
				$lock_post = YB_Lock_Post::get_instance()->init($task_id, $user_id);
				$data['lock_post'] = $lock_post;
				$currently_locked_by = isset($lock_post->display_name) ? $lock_post->display_name : '';
				$data['currently_locked_by'] = $currently_locked_by;

				return YB_View::get_instance()->display($partial_template, $data);
			}else{
				echo 'Invalid request';
			}
	}

	public function createNonceUrlEditTask($task_id, $assign_to)
	{
		$url = add_query_arg(
				[
						'yearbook-edit' => $task_id,
						'edit-task-nonce'  => wp_create_nonce('edit-' . $task_id . $assign_to),
				],
				site_url()
		);
		return $url;
	}

	public function verifyNonceUrlEditTask($nonce, $task_id, $assign_to)
	{
		/*echo $nonce.'<br>';
		echo 'edit-' . $task_id.'<br>';
		echo $assign_to.'<br>';
		echo wp_verify_nonce($nonce, 'edit-' . $task_id . $assign_to) ? 'y':'n';
		exit();*/
		return wp_verify_nonce($nonce, 'edit-' . $task_id . $assign_to);
	}

  public function __construct()
  {

  }

}
