<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Get the projects Task
 * @since 0.0.1
 * */
class YB_Project_PagesMeta {
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
	* get the due date
	* @param int $post_id the id of the yearbook.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getDueDate($post_id, $single = true)
	{
		return get_post_meta($post_id, 'due_date', $single);
	}

	/**
	* get the article title.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getBlockTitle($post_id, $single = true)
	{
		return get_post_meta($post_id, 'block_title', $single);
	}

	/**
	* get the article template.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getTemplate($post_id, $single = true)
	{
		return get_post_meta($post_id, 'template', $single);
	}

	/**
	* get the article size.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getBlockSize($post_id, $single = true)
	{
		return get_post_meta($post_id, 'block_size', $single);
	}

	/**
	* get the article submit status.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getSubmitted($post_id, $single = true)
	{
		return get_post_meta($post_id, 'submitted', $single);
	}

	/**
	* get the article page number.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getPageNumber($post_id, $single = true)
	{
		return get_post_meta($post_id, 'page_number', $single);
	}

	/**
	* get the article is child.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getIsChildBlock($post_id, $single = true)
	{
		return get_post_meta($post_id, 'is_child_block', $single);
	}

	/**
	* get the article finished status.
	* @param int $post_id the article id.
	* @param bool $single if true it will display as string otherwise array.
	**/
	public function getIsFinished($post_id, $single = true)
	{
		return get_post_meta($post_id, 'is_finished', $single);
	}

	/**
	* not used anymore to be deleted.
	**/
	public function yb_section_ids($args = []){
		$prefix = 'yb_section_ids';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article page size.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_block_size($args = []){
		$prefix = 'block_size';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article current page.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_current_page($args = []){
		$prefix = 'yb_current_page';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article last page.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_last_page($args = []){
		$prefix = 'yb_last_page';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article size page.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_loop_block_size_page($args = []){
		$prefix = 'yb_loop_block_size_page';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article term id.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_school_id_term($args = []){
		$prefix = 'yb_school_id_term';
		if(isset($args['term_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_term_meta($args['term_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_term_meta($args['term_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_term_meta($args['term_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* get the article uniq id.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_uniqid($args = []){
		$prefix = 'yb_uniqid';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* mutliple coordinator/author.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function yb_multiple_authors($args = [])
	{
		$prefix = 'yb_multiple_authors';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* Block size full page.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function block_size_fullpage($args = [])
	{
		$prefix = 'block_size_fullpage';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* Block size part page.
	* @param array $args {
	*		Array of arguments.
	*		@type int $post_id the article id, required.
	*		@type bool $single this will return string if true else array if false. default is false.
	*		@type string $action CRUD action, default is read.
	*			accepted values: r (read), u (update), d (delete)
	*		@type string $prefix the prefix meta key.
	* }
	* @return  $action, r = get_post_meta(), u = update_post_meta(), d = delete_post_meta
	**/
	public function block_size_partpage($args = [])
	{
		$prefix = 'block_size_partpage';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* set user is sent notification.
	**/
	public function author_sent_notify($args = [])
	{
		$prefix = 'sent_notify_email_author_id_';
		if(isset($args['post_id'])) {
			$user_id = $args['user_id'];
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix . $user_id
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* set user is sent notification.
	**/
	public function due_date($args = [])
	{
		$prefix = 'due_date';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* set user status.
	* 0 ongoing
	* 1 Author complete
	* 2 Proof Read
	* 3 Ready for production
	* 4 in-production
	**/
	public function status($args = [])
	{
		$prefix = 'is_finished';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	/**
	* set submitted.
	**/
	public function submitted($args = [])
	{
		$prefix = 'submitted';
		if(isset($args['post_id'])) {
			$defaults = array(
				'single' => false,
				'action' => 'r',
				'value' => '',
				'prefix' => $prefix
			);
			$args = wp_parse_args( $args, $defaults );
			switch($args['action']){
				case 'd':
					delete_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'u':
					update_post_meta($args['post_id'], $args['prefix'], $args['value']);
				break;
				case 'r':
					return get_post_meta($args['post_id'], $args['prefix'], $args['single']);
				break;
			}
		}
	}

	public function __construct()
	{

	}

}
