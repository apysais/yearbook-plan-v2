<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * API for tasks.
 * @since 0.0.1
 * */
class YB_Project_Rest_Task {

	/**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	//the last page var
	public $last_page = null;

	//loop page start at zero.
	public $loop_page = 0;

	//page number start at 1
	public $page_number = 1;

	//total block size
	public $total_block_size;

	//loop block size page
	public $loop_block_size_page;

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
	* Get the pages / articles.
	**/
	public function getPages() {
		$args = array(
			'sort_order' => 'asc',
			'sort_column' => 'menu_order',
			'hierarchical' => 1,
			'child_of' => 0,
			'parent' => -1,
			'post_type' => YB_CPT_PREFIX,
			'post_status' => 'publish'
		);
		$pages = get_pages($args);
		if($pages) {
			$posts_data = [];
			foreach($pages as $k => $v) {
				if($v->post_parent == 0){
					$parent_data = [
						'section' => get_post_meta($v->ID, 'section', 1),
					];
					$posts_data[] = [
						'id' => $v->ID,
						'post_author' => $v->post_author,
						'post_date' => $v->post_date,
						'post_title' => $v->post_title,
						'post_content' => $v->post_content,
						'post_status' => $v->post_status,
						'post_modified' => $v->post_modified,
						'post_parent' => $v->post_parent,
						'menu_order' => $v->menu_order,
						'comment_count' => $v->comment_count,
						'user_info' =>  get_userdata($v->post_author),
						'meta' => get_post_meta($v->ID),
						'childrens' => $this->get_grandchildrens($v->ID, $parent_data)
				 ];
				}
		 }
		}
		return $posts_data;
	}

	/**
	* Use to get the data, yearbook project and articles.
	* @param array $data{
	*		@type int $id the yearbook id.
	*	}
	**/
	public function get_v2($data)	{
		$post_id = $data['id'];
		$post_data = [];
		$arg_parent = [
			'include' => $post_id,
			'post_type' => YB_CPT_PREFIX,
			'post_status' => 'any',
		];
		$parent_post   = get_posts( $arg_parent );
		if($parent_post) {
			$get_parent_post = $parent_post[0];
			$school_id = $get_parent_post->post_author;
			$this->loop_block_size_page = 0;
			$post_data = [
				'parent' => [
					'title' => $get_parent_post->post_title,
					'id' => $get_parent_post->ID,
					'user_id' => $school_id,
				],
				'blocks' => $this->_blocks($post_id, $school_id),
			];
		}
		return $post_data;
	}

	/**
	* Get the articles of the yearbook.
	* @param int $parent_id the parent id of the article or block.
	* @param int $school_id the owner of the yearbook.
	**/
	public function _blocks($parent_id, $school_id){
			$obj_meta = new YB_Project_PagesMeta;

	    $children = [];
	    // grab the posts children
			$posts = get_posts( array(
				 'numberposts' => -1,
				 'post_status' => 'any',
				 'post_type' => YB_CPT_PREFIX,
				 'post_parent' => $parent_id,
				 'suppress_filters' => false,
				 'order' => 'ASC',
				 'orderby' => 'menu_order',
			 	)
			);
			//print_r($posts);
			$loop_page_num = 0;
			$page_number = 1;
			if(!is_null($this->last_page)){
				$page_number = ($this->last_page += 1);
			}
			$previous_block_size = 0;
			if($posts) {
		    // now grab the grand children
				$i = 0;
				$page_number_round = 0;
				$objPageVisual = new YB_PageVisual();

				$current_user = wp_get_current_user();

				$is_admin = false;
				$roles = ( array ) $current_user->roles;
				if(in_array('administrator', $roles) || is_super_admin($current_user->ID)){
					$is_admin = true;
				}

		    foreach( $posts as $k => $child ){
					$indx = $i;
					$left_side_css = '';
					$right_side_css = '';

					//compute block size
					$get_block_size = $obj_meta->yb_block_size([
						'action' => 'r',
						'post_id' => $child->ID,
						'single' => true
					]);
					//$this->loop_block_size_page += $get_block_size;
					//$loop_page_num += $get_block_size;
					//compute block size

					//page visual.
					$wp = 1;
				  $pg_args = [
				    'page'  => isset($page) ? $page : 1,
				    'cs'    => $get_block_size,
				    'lr'    => isset($lr) ? $lr:'left',
				    'lpp'   => isset($lpp) ? $lpp:0,
				    'tcsp'  => isset($tcsp) ? $tcsp:0,
				    'csp'   => isset($csp) ? $csp:0,
				    'mcs'   => isset($mcs) ? $mcs:0,
				    'clp'   => isset($clp) ? $clp:0,
				    'index' => $indx
				  ];
				  $ret_page_visual = $objPageVisual->init($pg_args);
				  $page = $ret_page_visual['page'];
				  $lpp 	= $ret_page_visual['lpp'];
				  $tcsp = $ret_page_visual['tcsp'];
				  $csp 	= $ret_page_visual['csp'];
				  $mcs 	= $ret_page_visual['mcs'];
				  $clp 	= $ret_page_visual['clp'];
				  $cs 	= $ret_page_visual['cs'];

					$lr = $objPageVisual->leftOrRight($page);

					$page_visual = $objPageVisual->htmlPageVisual([
					  'wp' => $wp,
					  'page' => $page,
					  'cs' => $cs,
					  'lr' => $lr,
					  'lpp' => $lpp,
					  'tcsp' => $tcsp,
					  'csp' => $csp,
					  'mcs' => $mcs,
					  'clp' => $clp,
					]);

					//page visual.

					$due_date_standing = strtolower(allt_due_date_standing($obj_meta->getDueDate($child->ID)));
					$media = get_attached_media( 'image', $child->ID );
					$is_finished = YB_Project_Pages::get_instance()->is_finished([
						'post_id' => $child->ID,
						'single' => true
					]);
					$label_task = 'secondary';
					if($is_finished == 1){
						$label_task = 'success';
					}elseif($due_date_standing == 'overdue') {
						$label_task = 'warning';
					}elseif($due_date_standing == 'today') {
						$label_task = 'primary';
					}elseif($due_date_standing == 'upcoming') {
						$label_task = 'info';
					}
					$string_for_encrypt = YB_LoginLess::get_instance()->createCryptSalt(['task_id' => $child->ID, 'user_id' => $child->post_author]);
					$login_encrypt 			= YB_LoginLess::get_instance()->loginCrypt($string_for_encrypt);

					//$compute_page_number = ($page_number + $loop_page_num);

					if($i == 0){
						$page_number_round = 1;
					}

					$is_submitted  = YB_Project_Pages::get_instance()->isSubmitted($child->ID);
					$status = 'On Going';
					if($is_submitted){
						$status = 'Author Complete';
					}
					if($is_finished == 2){
						$status = 'Proof Read';
					}
					if($is_finished == 3){
						$status = 'Ready for Production';
					}
					if($is_finished == 4){
						$status = 'In Production';
					}

					$authors = YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
						'post_id' => $child->ID,
						'action' => 'r',
						'single' => true,
					]);
					$contributors_name = '';
					$author_int = [0];
					if($authors && count($authors) > 0){
						$author_int = array_map('intval', $authors);

						$user_query = new WP_User_Query([
								'include' => $author_int
						]);

						foreach ( $user_query->get_results() as $user ) {
							$contributors_name .= $user->display_name.', ';
						}
					}

					$can_be_edited = true;
					if(!$is_admin && $is_finished == 4){
						$can_be_edited = false;
					}
					$is_cover = get_post_meta($child->ID, 'is_cover', true);
					$article_page_number = 0;
					if ( !$is_cover ) {
						$article_page_number = yb_rounder($page_number_round);
					}
					//quick fix
					//$article_page_number = ceil($article_page_number);
					$children['page'][$page_number][] = [
					 'id' => $child->ID,
					 'post_author' => $child->post_author,
					 'post_date' => $child->post_date,
					 'post_title' => $child->post_title,
					 'post_status' => $child->post_status,
					 'post_modified' => $child->post_modified,
					 'post_parent' => $child->post_parent,
					 'menu_order' => $child->menu_order,
					 'comment_count' => $child->comment_count,
					 'page_number' => $article_page_number,
					 '_page_number' => $page_number,
					 'word_count' => str_word_count(wp_strip_all_tags($child->post_content)),
					 'photo_count' => count($media),
					 'due_date_standing' => $due_date_standing,
					 'due_date_format' => date("F j, Y", strtotime($obj_meta->getDueDate($child->ID))),
					 'due_date_standing_label' => $label_task,
 					 'due_date_human' => time2str($obj_meta->getDueDate($child->ID)),
 					 'is_finished' => $is_finished,
					 'is_submitted' => $is_submitted ? 1 : 0,
					 'status' => $status,
					 'user_info' =>  get_userdata($child->post_author),
					 'meta' => get_post_meta($child->ID),
					 'authors_name' => rtrim($contributors_name,', '),
					 'authors' => $author_int,
					 'edit_post' => admin_url('post.php?post='.$child->ID.'&action=edit'),
					 'delete_url' => html_entity_decode(wp_nonce_url(yb_admin_url_yearbookpage("&_method=verify-delete-block&yb=".$parent_id."&school_id=".$school_id."&block_id=".$child->ID.""), "delete-block-".$child->ID.$school_id.$parent_id, "delete-block-nonce")),
					 'login_less_url' => site_url('/?show-task='.$login_encrypt),
					 'page_visual' => $page_visual,
					 'can_be_edited' => $can_be_edited
				 ];
				 $i++;
				 if ( !$is_cover ) {
					 $page_number_round = yb_rounder($page_number_round) + ($get_block_size);
					 $page 	= $page_number_round;
				   $lpp 	= $clp;
			 	 }
			 }//foreach loop
	    }

	    return $children;
	}


	/**
	* Construct.
	* Rest API controller.
	**/
  public function __construct()
  {
		add_action( 'rest_api_init', function () {

			register_rest_route( 'yearbook/v1', '/get-yearbookplan/(?P<id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'get'),
		    'args' => array(
		      'id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-sections/(?P<school_id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'sectionAutoComplete'),
		    'args' => array(
		      'school_id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-yearbookplan-v2/(?P<id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'get_v2'),
		    'args' => array(
		      'id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-yearbookplan-single/(?P<id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'getSingle'),
		    'args' => array(
		      'id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

			register_rest_route( 'yearbook/v1', '/show-parent-page/(?P<id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'showParentPage'),
		    'args' => array(
		      'id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-pages', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'getPages'),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-yearbookplan-parent', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'getYearbookParent'),
		  ) );

			register_rest_route( 'yearbook/v1', '/get-school-admin-contributor/(?P<id>\d+)', array(
		    'methods' => 'GET',
		    'callback' => array($this, 'getSchoolAdminContributor'),
				'args' => array(
		      'id' => array(
		        'validate_callback' => function($param, $request, $key) {
		          return is_numeric( $param );
		        }
		      ),
		    ),
		  ) );

		} );
  }

}
