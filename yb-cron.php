<?php
//require ( $_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-load.php' );
//require ( $_SERVER['DOCUMENT_ROOT'] . './wp-load.php' );
require ('wp-load.php');
yb_get_tasks_overdue();
function yb_get_tasks_overdue(){
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

        if($upcoming_due <= 14){
          $is_it_due = true;
          $due_label = 'Due';
        }
        if($due_date_count >= 1){
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
							'article_id' => $v->ID,
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
	//apyc_dd($articles);
	if(count($articles) >= 1){
		foreach($articles as $k_send => $v_send){
			$data_notify = [];
			if(is_array($v_send)){
				foreach($v_send as $key_send => $val_send){
					$data_notify['user_id'] 		= $val_send['user_id'];
					$data_notify['article_id'] 	= $val_send['article_id'];
					$data_notify['to'] 					= $val_send['to'];
					$data_notify['user_name'] 	= $val_send['user_name'];
					$data_notify['subject'] 		= $val_send['subject'];
					$data_notify['is_notify'] 	= $is_user_already_notified ? 1:0;
          $due_date_format = date("d-m-Y", strtotime($val_send['due_date']));

					$data_notify['articles'][] = [
						'article_title' => $val_send['article_title'] . ' ('.$val_send['due_label'].' '.$due_date_format.')',
						'article_login_link' => $val_send['login_url'],
					];
					$data_notify['headers'] 		= $val_send['headers'];
				}
        //apyc_dd($data_notify);
			}
			if($data_notify && count($data_notify) >= 1){
				//apyc_dd($data_notify);
        ob_start();
        YB_View::get_instance()->admin_partials('partials/mail/notify-due-group.php', $data_notify);
        $body = ob_get_contents();
        ob_end_clean();

        wp_mail($data_notify['to'], $data_notify['subject'], $body, $data_notify['headers']);
			}
		}
	}

}

function yb_get_tasks_mu() {
    // loop through all blogs
    $all_blog = wp_get_sites();
    //print_r($all_blog);
    foreach ($all_blog as $key => $current_blog) {
        // switch to each blog to get the posts
        //echo $current_blog['blog_id'].'<br>';
        switch_to_blog($current_blog['blog_id']);
        // fetch all the posts
        yb_get_tasks_overdue();
        restore_current_blog();
        // display all posts
    }
}

yb_get_tasks_mu();
?>
