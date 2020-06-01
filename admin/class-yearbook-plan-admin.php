<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       allteams.com
 * @since      1.0.0
 *
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yearbook_Plan
 * @subpackage Yearbook_Plan/admin
 * @author     AllTeams <mike@allteams.com>
 */
class Yearbook_Plan_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yearbook-plan-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-custom', plugin_dir_url( __FILE__ ) . 'css/custom-yearbook-plan-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'bootstrap4-iso', YB_PLUGIN_URL . 'assets/bootstrap4-iso/bootstrap-4-iso.min.css', array(), '4.1.3', 'all' );
		wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'jquery-ui' );
		//wp_enqueue_style( 'datatables', YB_PLUGIN_URL . 'assets/DataTables/datatables.min.css', array(), '1.10.18', 'all' );
		wp_enqueue_style( 'bootstrap-select', YB_PLUGIN_URL . 'assets/bootstrap-select/css/bootstrap-select.min.css', array(), '1.13.9', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_register_script('handlebarsjs', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.12/handlebars.min.js', array(), '4.0.12');

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'popper-js', plugin_dir_url( __FILE__ ) . 'js/popper.min.js', array( 'jquery' ), '1.15.0', false );
		wp_enqueue_script( 'bootstrap4-js', YB_PLUGIN_URL . 'assets/bootstrap4-iso/bootstrap.min.js', array( 'jquery' ), '4.1.3', false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yearbook-plan-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
    // enqueue the Vue app script with localized data.
		wp_enqueue_script( 'handlebarsjs' );
		wp_localize_script( $this->plugin_name, 'rest_object',
        array(
            'api_nonce' => wp_create_nonce( 'wp_rest' ),
            'api_url'   => site_url('/wp-json/wp/v2/'),
            'yearbook_api_url'   => site_url('/wp-json/yearbook/v1/')
        )
    );

		$screen = get_current_screen();
		if($screen->id == 'toplevel_page_YearBook'){
			//wp_enqueue_script( 'datatables-js', YB_PLUGIN_URL . 'assets/DataTables/datatables.min.js', array( 'jquery' ), '1.10.18', false );
			wp_enqueue_script( 'bootstrap-select-js', YB_PLUGIN_URL . 'assets/bootstrap-select/js/bootstrap-select.min.js', array( 'jquery' ), '1.10.18', false );
			wp_enqueue_script( $this->plugin_name . 'init', plugin_dir_url( __FILE__ ) . 'js/yearbook-plan-init.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name . 'bulkedit', plugin_dir_url( __FILE__ ) . 'js/yearbook-plan-bulkedit.js', array( 'jquery' ), $this->version, true );
		}

	}

}
