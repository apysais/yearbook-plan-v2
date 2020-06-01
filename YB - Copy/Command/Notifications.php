<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * API for tasks.
 * @since 0.0.1
 * */
class YB_Command_Notifications {
  /**
	 * instance of this class
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var	null
	 * */
	protected static $instance = null;

  protected $bar;

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

  public function __construct() {

  }

  public function yb_send_notification_tasks_overdue(){
  	$children = [];
  	$args = [
  		'post_type' => YB_CPT_PREFIX,
      'post_status' => 'publish',
      'posts_per_page' => -1,
  		'meta_query' => [
  			[
  				'key' => 'submitted',
  				'value' => 1,
  				'compare' => '!='
  			],
  		]
  	];
  	$get_post = get_posts($args);
  	//apyc_dd($get_post);
  	$authors = [];
  	$articles = [];
  	foreach($get_post as $k => $v){
  		$authors = YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
  			'post_id' => $v->ID,
  			'action' => 'r',
  			'single' => true
  		]);
  		if($authors){
        $is_it_due = false;
  			foreach($authors as $k_auth => $v_auth){
  				$login_url = YB_Project_Notify::get_instance()->loginlessUrl($v->ID, $v_auth);
  				$due_date_str = YB_Project_PagesMeta::get_instance()->getDueDate($v->ID);
  				$due_date = $due_date_str ? $due_date_str : '';
  				$due_date_count = allt_date_to_number_count($due_date);
          $upcoming_due = yb_get_upcoming_days_count($due_date);

          $due_label = '';

          if($upcoming_due >= 1 && $upcoming_due <= 14){
            $is_it_due = true;
            $due_label = 'Due';
          }
          if($due_date_count >= 1 && $due_date_count <= 60){
            $is_it_due = true;
            $due_label = 'Overdue';
          }
  				/*if(
            $due_date_count >= 3
            || $due_date_count >= 14
          ){*/
  				if(
            $is_it_due
          ){
  					$user_data = get_userdata($v_auth);
  					if($user_data) {
  						$user_email = $user_data->user_email;
  						$user_name = $user_data->display_name;
  						$user_id = $user_data->ID;
  						$articles[$v_auth][] = [
  							'user_id' => $user_id,
  							'to' => $user_email,
  							'user_name' => $user_name,
  							'article_data' => '',
  							'article_title' => $v->post_title,
  							'due_date' => $due_date,
  							'due_date_count' => $due_date_count,
  							'upcoming_due' => $upcoming_due,
  							'is_it_due' => $is_it_due,
  							'due_label' => $due_label,
  							'login_url' => $login_url,
  							'subject' => YB_Project_Notify::get_instance()->_subject(),
  							'headers' => YB_Project_Notify::get_instance()->_headers(),
  						];
  					}//if user_data
  				}
  				//$login_url = '';
  			}//foreach authors
  		}//if authors
  	}
  	//echo count($articles);
  	//apyc_dd($articles);
  	if(count($articles) >= 1){
  		$data_notify = [];
  		foreach($articles as $k_send => $v_send){
  			if(is_array($v_send)){
  				foreach($v_send as $key_send => $val_send){
  					$data_notify[$k_send]['user_id'] 		= $val_send['user_id'];
  					$data_notify[$k_send]['to'] 					= $val_send['to'];
  					$data_notify[$k_send]['user_name'] 	= $val_send['user_name'];
  					$data_notify[$k_send]['subject'] 		= $val_send['subject'];
  					//$data_notify[$k_send]['is_notify'] 	= $is_user_already_notified ? 1:0;
            $due_date_format = date("d-m-Y", strtotime($val_send['due_date']));

  					$data_notify[$k_send]['articles'][] = [
  						'article_title' => $val_send['article_title'] . ' ('.$val_send['due_label'].' '.$due_date_format.')',
  						'article_login_link' => $val_send['login_url'],
  					];
  					$data_notify[$k_send]['headers'] = $val_send['headers'];
  				}
  				//echo count($data_notify);
          //apyc_dd($data_notify);
  			}

  		}
  		//apyc_dd($data_notify);
  		if($data_notify && count($data_notify) >= 1){
  			//apyc_dd($data_notify);
  			foreach($data_notify as $k_notify => $v_notify){
          WP_CLI::log('Send To User : ' . $v_notify['user_name'] . ' Email : ' . $v_notify['to']);
          WP_CLI::log('Data : ' . print_r($v_notify['articles']));

  				ob_start();
  				YB_View::get_instance()->admin_partials('partials/mail/notify-due-group.php', $v_notify);
  				$body = ob_get_contents();
  				ob_end_clean();

  				$send = wp_mail($v_notify['to'], $v_notify['subject'], $body, $v_notify['headers']);
          if($send){
            WP_CLI::success( 'Sent to : ' . $v_notify['to']);
          }else{
            WP_CLI::error( 'Send fail to : ' . $v_notify['to'] );
          }
        }
  		}
  	}

  }

  public function __invoke( $args, $assoc_args ) {
    if(isset($assoc_args['site-id']) && $assoc_args['site-id'] != ''){
      $site_id = $assoc_args['site-id'];
      switch_to_blog($site_id);
      $this->yb_send_notification_tasks_overdue();
      restore_current_blog();
    }else{
      //$all_blog = wp_get_sites();
      //print_r($all_blog);
      //foreach ($all_blog as $key => $current_blog) {
          // switch to each blog to get the posts
          //echo $current_blog['blog_id'].'<br>';
          //switch_to_blog($current_blog['blog_id']);
          // fetch all the posts
          //$this->yb_send_notification_tasks_overdue();
          //restore_current_blog();
          // display all posts
      //}
    }
  }

}
