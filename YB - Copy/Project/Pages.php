<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Get the projects Task
 * @since 0.0.1
 * */
class YB_Project_Pages {
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
	* Prefix use for meta key in post meta.
	**/
	public function getCptPrefix()
	{
		return YB_CPT_PREFIX;
	}

	/**
	* get is parent page key.
	* @param int $post_id the id of the yearbook.
	* @return boolean
	**/
	public function __isParentPage($post_id)
	{
		return get_post_meta($post_id, 'is_parent_page') ? true:false;
	}

	/**
	* is parent block.
	* @param int $post_id the id of the yearbook.
	* @return boolean
	**/
	public function __isParentBlock($post_id)
	{
		return get_post_meta($post_id, 'is_parent_block') ? true:false;
	}

	/**
	* is child block.
	* @param int $post_id the id of the yearbook.
	* @return boolean
	**/
	public function isChildBlock($post_id)
	{
		return get_post_meta($post_id, 'is_child_block') ? true:false;
	}

	/**
	* is submitted.
	* check if the page or article is submitted.
	* @param int $post_id the id of the yearbook.
	* @return boolean
	**/
	public function isSubmitted($post_id)
	{
		return ( get_post_meta($post_id, 'submitted', 1) == 0 ) ? false : true;
	}

	/**
	* update the submit meta.
	* @param int $post_id the id of the yearbook.
	* @return boolean
	**/
	public function updateSubmitted($post_id, $val = 0)
	{
		update_post_meta($post_id, 'submitted', $val);
	}

	/**
	* is finished meta.
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
	public function is_finished($args = []){
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


	public function production($args = []){
		$prefix = 'yb_production';
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
	* remove a page.
	**/
	public function removePage()
	{
		if(isset($_POST['post_id'])) {
			$post_id = $_POST['post_id'];
			wp_delete_post($post_id, 1);
		}

		wp_die();
	}

	/**
	* initialize ajax.
	**/
	public function ajaxInit()
	{
		if( is_admin() ) {
			add_action('wp_ajax_remove_page', array($this, 'removePage'));
		}
	}

	public function __construct()
	{

	}

}
