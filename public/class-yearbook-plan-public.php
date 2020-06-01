<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       allteams.com
 * @since      1.0.0
 *
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/public
 * @author     AllTeams <mike@allteams.com>
 */
class Yearbook_Plan_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yearbook_Plan_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yearbook_Plan_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yearbook-plan-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap4-iso', YB_PLUGIN_URL . 'assets/bootstrap4-iso/bootstrap-4-iso.min.css', array(), '4.1.3', 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yearbook_Plan_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yearbook_Plan_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();
		wp_enqueue_script( 'bootstrap4-js', YB_PLUGIN_URL . 'assets/bootstrap4-iso/bootstrap.min.js', array( 'jquery' ), '4.1.3', false );
		wp_enqueue_script( 'jquery-file-uploader-ui-widget', YB_PLUGIN_URL . 'node_modules/blueimp-file-upload/js/vendor/jquery.ui.widget.js', array( 'jquery' ), '4.1.3', false );
		wp_enqueue_script( 'jquery-file-uploader-iframe', YB_PLUGIN_URL . 'node_modules/blueimp-file-upload/js/jquery.iframe-transport.js', array( 'jquery' ), '4.1.3', false );
		wp_enqueue_script( 'jquery-file-uploader', YB_PLUGIN_URL . 'node_modules/blueimp-file-upload/js/jquery.fileupload.js', array( 'jquery' ), '4.1.3', false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yearbook-plan-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'yb',
        array(
            'ajaxurl' => admin_url('admin-ajax.php')
        )
    );
	}

}
