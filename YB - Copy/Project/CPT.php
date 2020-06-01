<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Custom post type initialize.
 * @since 0.0.1
 * */
class YB_Project_CPT {
  /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

	//prefix of the custom post type.
	private $cpt_prefix = 'projects';

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
	* get the prefix of custom post type.
	* @see $cpt_prefix
	* @return string
	**/
	public function getCptPrefix()
	{
		return $this->cpt_prefix;
	}

	/**
	* Initialize the custom post.
	**/
	public function CptInit()
	{
		$text_domain = yb_get_text_domain();
		$name = yb_verbage('cpt_project');
		$cpt_prefix = $this->cpt_prefix;
		$labels = array(
			'name'               => _x( $name['name'], 'post type general name', $text_domain ),
			'singular_name'      => _x( $name['singular_name'], 'post type singular name', $text_domain ),
			'menu_name'          => _x( $name['menu_name'], 'admin menu', $text_domain ),
			'name_admin_bar'     => _x( $name['name_admin_bar'], 'add new on admin bar', $text_domain ),
			'add_new'            => _x( 'Add New', $cpt_prefix, $text_domain ),
			'add_new_item'       => __( 'Add New ' . $name['singular_name'], $text_domain ),
			'new_item'           => __( 'New ' . $name['singular_name'], $text_domain ),
			'edit_item'          => __( 'Edit ' . $name['singular_name'], $text_domain ),
			'view_item'          => __( 'View ' . $name['singular_name'], $text_domain ),
			'all_items'          => __( 'All ' . $name['menu_name'], $text_domain ),
			'search_items'       => __( 'Search ' . $name['menu_name'], $text_domain ),
			'parent_item_colon'  => __( 'Parent :' . $name['menu_name'], $text_domain ),
			'not_found'          => __( 'No '.$name['menu_name'].' found.', $text_domain ),
			'not_found_in_trash' => __( 'No '.$name['menu_name'].' found in Trash.', $text_domain )
		);

		$args = array(
			'labels'             => $labels,
	    'description'        => __( 'Description.', $text_domain ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $cpt_prefix ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'page-attributes', 'author', 'comments' )
		);

		register_post_type( $cpt_prefix, $args );
	}

  public function __construct()
  {
		add_action( 'init', array($this, 'CptInit') );
  }

}
