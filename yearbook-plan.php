<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              allteams.com
 * @since             1.3.3
 * @package           Yearbook_Plan
 *
 * @wordpress-plugin
 * Plugin Name:       YearBook
 * Plugin URI:        allteams.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.3.3
 * Author:            AllTeams
 * Author URI:        allteams.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yearbook-plan
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('YB_PLUGIN_NAME_VERSION', '1.3.3' );
define('YB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('YB_CPT_PREFIX', 'yearbook-plan');
//move this to a settings page
define('YB_PUBLIC_TASK_LIST', '/');

/**
 * For autoloading classes
 * */
spl_autoload_register('yb_directory_autoload_class');
function yb_directory_autoload_class($class_name){
		if ( false !== strpos( $class_name, 'YB' ) ) {
	 $include_classes_dir = realpath( get_template_directory( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	 $admin_classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
	 $class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
	 if( file_exists($include_classes_dir . $class_file) ){
		 require_once $include_classes_dir . $class_file;
	 }
	 if( file_exists($admin_classes_dir . $class_file) ){
		 require_once $admin_classes_dir . $class_file;
	 }
 }
}
function yb_get_plugin_details(){
 // Check if get_plugins() function exists. This is required on the front end of the
 // site, since it is in a file that is normally only loaded in the admin.
 if ( ! function_exists( 'get_plugins' ) ) {
	 require_once ABSPATH . 'wp-admin/includes/plugin.php';
 }
 $ret = get_plugins();
 return $ret['yearbook-plan/yearbook-plan.php'];
}
function yb_get_text_domain(){
 $ret = yb_get_plugin_details();
 return $ret['TextDomain'];
}
function yb_get_plugin_dir(){
 return plugin_dir_path( __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yearbook-plan-activator.php
 */
function activate_yearbook_plan() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yearbook-plan-activator.php';
	Yearbook_Plan_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yearbook-plan-deactivator.php
 */
function deactivate_yearbook_plan() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yearbook-plan-deactivator.php';
	Yearbook_Plan_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_yearbook_plan' );
register_deactivation_hook( __FILE__, 'deactivate_yearbook_plan' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yearbook-plan.php';

require_once plugin_dir_path( __FILE__ ) . 'functions/helper.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/verbage.php';
// require_once plugin_dir_path( __FILE__ ) . 'setup-hooks.php';
// require_once plugin_dir_path( __FILE__ ) . 'multisite-user-hooks.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yearbook_plan() {

	$plugin = new Yearbook_Plan();
	$plugin->run();

	YB_CPT::get_instance();

	// OLD
	// YB_WPDashboard::get_instance();
	// YB_Project_MetaBox_WP::get_instance();
	// YB_Project_Rest_Task::get_instance();
	//
	// YB_Project_Pages::get_instance()->ajaxInit();
	// YB_MyPage_ListShortcode::get_instance();
	// YB_MyPage_Page::get_instance()->initHook();
	//
	// YB_Project_Notify::get_instance()->init_ajax();
	//
	// //cpt yearbook plan
	// YB_Page_CPT::get_instance();
	// YB_Page_WP::get_instance()->admin_menu();
	//
	// YB_Page_Ajax::get_instance();
	// YB_Page_AjaxBulkEdit::get_instance();
	//
	// //YB_Settings_ExportWP::get_instance();
	// YB_Settings_Export::get_instance()->run();
	//
	// if ( defined( 'WP_CLI' ) && WP_CLI ) {
	// 	$instance_export_command = new YB_Command_Export;
	// 	WP_CLI::add_command( 'yb-export', $instance_export_command );
	//
	// 	$instance_notify_command = new YB_Command_Notifications;
	// 	WP_CLI::add_command( 'yb-notify', $instance_notify_command );
	//
	// 	$instance_clean_command = new YB_Command_CleanDB;
	// 	WP_CLI::add_command( 'yb-clean-mu-db', $instance_clean_command );
	// }
	//
	// YB_Lock_Ajax::get_instance()->init();
	//
	// YB_Network_Settings::get_instance()->menu();


}
//run_yearbook_plan();
add_action('plugins_loaded', 'run_yearbook_plan');

add_action( 'admin_menu', 'register_yb_custom_menu_page' );
function register_yb_custom_menu_page() {
	//admin menu
	YB_AdminMenu::get_instance()->admin_menu();
}
