<?php

function yb_send_notification_after_func($data) {
	$data_api = [];
	//apyc_dd($data,1);
	if ( isset( $data['articles'] ) ) {
		$i = 0;
		foreach($data['articles'] as $k => $v) {
			$data_api = [
				'due_date' => $v['due_date'],
				'article_title' => $v['article_title'],
				'tag' => $v['article_title'] . '-' . $data['article_id'] . '-' . $data['user_id'],
				'send_to_details' => [
					'email' => $data['to'],
					'user_name' => $data['user_name'],
					'url' => $v['article_login_link']
				],
				'send_to' => new YB_ReminderAPI_Member($data['to'], $data['user_name'], $v['article_login_link'])
			];
			$res = YB_Reminder::get_instance()->send($data_api);
			// $i++;
		}
	}
}
add_action('yb_send_notification_after', 'yb_send_notification_after_func', 10, 1);

function init_yb() {
	if( is_user_logged_in() ) {
		YB_MyPage_Page::get_instance()->create();
		YB_Contributor_Profile::get_instance()->update();

		YB_Network_Settings::get_instance()->clean();

	}
	if(!session_id()) {
    session_start();
  }
}
add_action('init', 'init_yb');

function yb_template_include($template) {
	global $post;

	if( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		if(
			isset($_GET['yearbook-edit'])
			&& $_GET['yearbook-edit'] != ''
			&& is_numeric($_GET['yearbook-edit'])
		){
			$yb_id = $_GET['yearbook-edit'];
			$show_task = false;
			if(
				isset($_GET['edit-task-nonce'])
				&& YB_Page_List::get_instance()->verifyNonceUrlEditTask($_GET['edit-task-nonce'], $_GET['yearbook-edit'], $current_user->ID )
			){
				$show_task = true;
			}elseif(
				isset($_GET['task-nonce'])
				&& YB_LoginLess::get_instance()->loginCrypt($_GET['task-nonce'], 'd')
			){
				$show_task = true;
			}
			if ( yb_is_admin_current_user($current_user) ) {
			    $show_task = true;
			}
			if($show_task){
				$template = YB_Page_List::get_instance()->edit($_GET['yearbook-edit']);
			}
		}
		if( isset($post->post_content)
				&& has_shortcode($post->post_content, 'yb_list_page')
				&& !isset($_GET['yearbook-edit'])
		){
			$template = YB_Page_List::get_instance()->list();
		}
		if(
			isset($_GET['my-profile'])
		){
			$template = YB_Contributor_Profile::get_instance()->show();
		}
	}

	return $template;
}
add_action( 'template_include', 'yb_template_include' );

function yb_template_redirect() {
	if(isset($_GET['show-task'])) {
		$yb_login = new YB_LoginLess;
		$decrypt = $yb_login->decryptLogin($_GET['show-task']);
		//apyc_dd($decrypt,1);
		if($decrypt) {
			$yb_login->login($decrypt['user_id']);
			$site_url = site_url('?yearbook-edit='.$decrypt['task_id'].'&task-nonce='.$_GET['show-task']);
			wp_redirect($site_url);
			exit;
		}
	}
}
add_action( 'template_redirect', 'yb_template_redirect' );

add_filter( 'rest_user_query', 'prefix_remove_has_published_posts_from_wp_api_user_query', 10, 2 );

function yb_remove_meta_boxes() {
	remove_meta_box( 'pageparentdiv', YB_CPT_PREFIX, 'side' );
	remove_meta_box( 'commentstatusdiv', YB_CPT_PREFIX, 'normal');
	remove_meta_box( 'commentsdiv', YB_CPT_PREFIX, 'normal');
}
add_action( 'admin_menu', 'yb_remove_meta_boxes' );

function remove_menus(){
	if( is_user_logged_in() ) {
    $user = wp_get_current_user();
    if(
			in_array('editor',$user->roles)
			|| in_array('contributor',$user->roles)
		) {
			remove_menu_page( 'index.php' );                  //Dashboard
			remove_menu_page( 'jetpack' );                    //Jetpack*
			remove_menu_page( 'edit.php' );                   //Posts
		  remove_menu_page( 'upload.php' );                 //Media
		  remove_menu_page( 'edit.php?post_type=page' );    //Pages
		  remove_menu_page( 'edit-comments.php' );          //Comments
		  remove_menu_page( 'themes.php' );                 //Appearance
		  remove_menu_page( 'plugins.php' );                //Plugins
		  remove_menu_page( 'users.php' );                  //Users
		  remove_menu_page( 'tools.php' );                  //Tools
		  remove_menu_page( 'options-general.php' );        //Settings
		  remove_menu_page( 'profile.php' );        				//Profile
		}
  }

}
add_action( 'admin_menu', 'remove_menus' );

function mailtrap($phpmailer) {
	$phpmailer->isSMTP();
  $phpmailer->Host = 'smtp.mailtrap.io';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 2525;
	$phpmailer->Username = 'f2415e43655688';
  $phpmailer->Password = '1adf14ba304872';
}
if(is_staging()){
	add_action('phpmailer_init', 'mailtrap');
}

add_filter('show_admin_bar', '__return_false');

add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );
function remove_wp_logo( $wp_admin_bar ) {
	$wp_admin_bar->remove_node( 'site-name' );
	$wp_admin_bar->remove_node( 'customize' );
	$wp_admin_bar->remove_node( 'wp-logo' );
	$wp_admin_bar->remove_node( 'updates' );
	$wp_admin_bar->remove_node( 'comments' );
	$wp_admin_bar->remove_node( 'new-content' );
}

function yb_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for contributor
				$meta = new YB_Contributor_Meta;
				$is_contributors = $meta->is_contributors([
					'user_id' => $user->ID,
					'action' 	=> 'r',
					'single' 	=> true
				]);

				$school_account = new YB_Account_Meta;
				$is_school_account = $school_account->school_account([
					'user_id' => $user->ID,
					'action' 	=> 'r',
					'single' 	=> true
				]);
        if (
					in_array('contributor', $user->roles)
					&& $is_contributors
				){
            // redirect them to another URL, in this case, the homepage
            $redirect_to =  home_url();
        }elseif(
					in_array('editor', $user->roles)
					&& $is_school_account
				){
					$redirect_to =  admin_url();
				}
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'yb_login_redirect', 10, 3 );
//add_filter('login_url', 'yb_login_redirect', 10, 3);
add_action( 'wp_ajax_yb_get_yearbook', 'yb_get_yearbook' );
function yb_get_yearbook() {
	global $wpdb; // this is how you get access to the database
	//parse_str($_POST['form'], $form);
	$data['id'] = $_POST['yearbook_id'];
	$blocks = YB_Project_Rest_Task::get_instance()->get_v2($data);
	return wp_send_json($blocks);
	wp_die(); // this is required to terminate immediately and return a proper response
}

function yb_show_parent_only($query)
{
    // bail early if we are not in the admin side
    if (!is_admin()) {
        return $query;
    }

    global $pagenow, $post_type;
    // check if we are in the edit screen for page post type
    if ($pagenow == 'edit.php' && $post_type == YB_CPT_PREFIX) {
        // filter out the pages with post_parent = 0 (ie. no post_parent, first level pages)
        //$query->set('post_parent', 0);
				$query->query_vars['post_parent'] = 0;

				/*if(!current_user_can('moderate_comments')) {
					$user_id = get_current_user_id();
					$query->query_vars['author'] = $user_id;
					$query->query_vars['meta_query'] = [
						'key' => 'school_admin_id',
						'value' => $user_id,
					];
				}*/
				//print_r($query);
    }

    return $query;

}
add_filter('parse_query', 'yb_show_parent_only', 10, 1);

add_filter( 'page_row_actions', 'remove_row_actions', 10, 2 );
function remove_row_actions( $actions, $post )
{
  if( get_post_type() === YB_CPT_PREFIX )
			$actions['edit'] = '<a href="admin.php?page=YearBook&_method=showYearbook&id='.$post->ID.'&school_id='.$post->post_author.'" aria-label="Edit &#8220;No Title &#8211; 2018-12-12-0814&#8221;">Edit</a>';
      unset( $actions['view'] );
			$actions['trash'] = '<a href="'.wp_nonce_url(yb_admin_url_yearbookpage('&_method=verify-delete&id='.$post->ID.''), 'delete_yearbook_'.$post->ID, 'delete_yearbook_nonce' ).'">Delete</a>';
      unset( $actions['inline hide-if-no-js'] );
  return $actions;
}

add_filter('get_edit_post_link', 'yb_get_edit_post_link', 99, 3);
function yb_get_edit_post_link($link, $post_id, $context) {
		global $post;
		$scr = get_current_screen();
    if ($scr->id == 'edit-yearbook-plan' && $context == 'display') {
        return 'admin.php?page=YearBook&_method=showYearbook&id='.$post->ID.'&school_id='.$post->post_author.'';
    } else {
        return $link;
    }
}

add_filter('views_edit-yearbook-plan','yb_update_quicklinks');
function yb_update_quicklinks($views) {
	if(!current_user_can('manage_options')) {
		return '';
	}
	return $views;
}

function yb_is_site_owner($user_id = null)
{
	global $wpdb;

	if(is_null($user_id)){
		$user_id = get_current_user_id();
	}
	//echo $user_id;
	$tbl = $wpdb->base_prefix . 'wu_site_owner';
	$sql_str = "SELECT COUNT(*) FROM {$tbl} WHERE user_id = {$user_id}";
	$user_count = $wpdb->get_var( $sql_str );
	if($user_count && $user_count >= 1){
		return true;
	}
	return false;
}

//WP Ultimo
function yb_add_school_account_hook() {
	global $wpdb,  $pagenow;
	//echo $pagenow;
	if ( is_multisite() && $pagenow == 'index.php' ) {
		if(yb_is_site_owner()){
			$user_id = get_current_user_id();
			$current_user = wp_get_current_user();

			yb_account_name_update($user_id, $current_user->nickname);
			yb_school_account_update($user_id, 1);
		}
	}
}
add_action( 'admin_init', 'yb_add_school_account_hook', 1 );
//WP Ultimo
add_image_size( 'newsfeed-fit', 700, 470, ['center', 'top'] );
