<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Use for notify the user for the task.
 * @since 0.0.1
 * */
class YB_Project_Notify {
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

	public function _body($body)
	{

	}

	/**
	* Set the subject of the notification.
	**/
	public function _subject()
	{
		$str = 'Yolo - YearBook Task';
		return $str;
	}

	/**
	* The header of the email.
	**/
	public function _headers($args = [])
	{
		/**
		 * Define the array of defaults
		 */
		$defaults = [
			"From: Yolo Planit <message@planit.yolo.co.nz>",
			"Content-Type: text/html; charset=UTF-8"
		];

		/**
		 * Parse incoming $args into an array and merge it with $defaults
		 */
		$headers = wp_parse_args( $args, $defaults );
		return $headers;
	}

	/**
	* Sent notification viva ajax.
	**/
	public function ajax_notify()
	{
		if(isset($_POST['post_id'])) {
			$post_id = $_POST['post_id'];
			$notify = $this->notifyGroup($post_id, $_POST);
		}

		wp_die();
	}

	public function loginlessUrl($post_id, $author_id)
	{
		$string_for_encrypt = YB_LoginLess::get_instance()->createCryptSalt(['task_id' => $post_id, 'user_id' => $author_id]);
		$login_encrypt = YB_LoginLess::get_instance()->loginCrypt($string_for_encrypt);
		return site_url('/?show-task='.$login_encrypt);
	}

	public function getMailData($post_id)
	{
		$data = [];
		$get_post = get_post($post_id);
		if($get_post){
			$authors = YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
				'post_id' => $post_id,
				'action' => 'r',
				'single' => true
			]);
			if($authors && count($authors) >= 1){
				foreach($authors as $k => $auth_id){
					$login_url = $this->loginlessUrl($post_id, $auth_id);
					$user_data = get_userdata($auth_id);
					if($user_data) {
						$user_email = $user_data->user_email;
						$user_name = $user_data->display_name;
						$data[] = [
							'to' => $user_email,
							'user_name' => $user_name,
							'article_data' => $get_post,
							'article_title' => $get_post->post_title,
							'login_url' => $login_url,
							'subject' => $this->_subject(),
							'headers' => $this->_headers(),
						];
					}
				}
			}
		}
		return $data;
	}

	/**
	* not used.
	**/
	public function notifySingle($post_id)
	{
		$data = $this->getMailData($post_id);
		//apyc_dd($data);
		if($data && count($data) >= 1){
			foreach($data as $key => $val){
				ob_start();
				YB_View::get_instance()->admin_partials('partials/mail/notify.php', $val);
				$body = ob_get_contents();
				ob_end_clean();
				wp_mail($val['to'], $val['subject'], $body, $val['headers']);
			}
		}
	}

	public function notifyGroup($parent_post_id, $input_post = [])
	{
		$data 		= [];
		$articles = [];
		$posts_id = $parent_post_id;

		$article_parent_id = 0;
		if(isset($_POST['yearbook_id'])){
			$article_parent_id = $_POST['yearbook_id'];
		}

		$notify_mode = false;
		if(isset($input_post['notify_mode'])){
			$notify_mode = $input_post['notify_mode'];
		}

		if(!is_array($parent_post_id)){
			$posts_id = [$parent_post_id];
		}
		$args = [
			'include'   => $posts_id,
			'post_type' => YB_CPT_PREFIX,
			'meta_query' => [
				[
					'key' => 'submitted',
					'value' => 1,
					'compare' => '!='
				],
			]
		];
		$get_post = get_posts($args);
		// apyc_dd($args);
		// apyc_dd($get_post);
		foreach($get_post as $k_post => $v_post){
			$authors = YB_Project_PagesMeta::get_instance()->yb_multiple_authors([
				'post_id' => $v_post->ID,
				'action' => 'r',
				'single' => true
			]);
			if ( $authors ) {
				foreach($authors as $k_auth => $v_auth){
					$login_url = $this->loginlessUrl($v_post->ID, $v_auth);
					$user_data = get_userdata($v_auth);
					if($user_data) {
						$user_email = $user_data->user_email;
						$user_name = $user_data->display_name;
						$user_id = $user_data->ID;
						$due_date = YB_Project_PagesMeta::get_instance()->due_date([
	            'action' => 'r',
	            'post_id' => $v_post->ID,
	            'single' => true
	          ]);
						$articles[$v_auth][] = [
							'user_id' => $user_id,
							'to' => $user_email,
							'user_name' => $user_name,
							'article_id' => $v_post->ID,
							'article_data' => $v_post,
							'article_title' => $v_post->post_title,
							'due_date' => $due_date,
							'login_url' => $login_url,
							'subject' => $this->_subject(),
							'headers' => $this->_headers(),
						];
					}//if user_data
				}//foreach authors
			}//if authors
		}//foreach get post
		// echo count($articles);exit();
		if(count($articles) >= 1){
			foreach($articles as $k_send => $v_send){
				$data = [];
				if(is_array($v_send)){
					foreach($v_send as $key_send => $val_send){
						$is_user_already_notified = YB_Project_PagesMeta::get_instance()->author_sent_notify([
							'post_id' => $article_parent_id,
							'user_id' => $val_send['user_id'],
							'action' 	=> 'r',
							'single' 	=> true
						]);
						$data['user_id'] 		= $val_send['user_id'];
						$data['article_id'] = $val_send['article_id'];
						$data['to'] 				= $val_send['to'];
						$data['user_name'] 	= $val_send['user_name'];
						$data['subject'] 		= $val_send['subject'];
						$data['is_notify'] 	= $is_user_already_notified ? 1:0;

						$data['articles'][] = [
							'article_title' => $val_send['article_title'],
							'article_login_link' => $val_send['login_url'],
							'due_date' => $val_send['due_date'],
						];
						$data['headers'] 		= $val_send['headers'];
					}//foreach v_send
				}//is is array v_send
				//echo count($data);exit();
				if($data && count($data) >= 1){
					ob_start();
					YB_View::get_instance()->admin_partials('partials/mail/notify-group.php', $data);
					$body = ob_get_contents();
					ob_end_clean();

					$is_user_already_notified = $data['is_notify'];
					$post_id = $data['article_id'];
					$user_id = $data['user_id'];

					$sent = false;
					$sent_to_authors = [];
					$sent_to_user = false;
					if($notify_mode == 'manual'){
						$sent_to_user = true;
					}elseif(!$is_user_already_notified || $is_user_already_notified == 0){
						$sent_to_user = true;
					}
					//$sent_to_user = true;

					if($sent_to_user){

						do_action( 'yb_send_notification_before', $data );

						$sent = wp_mail($data['to'], $data['subject'], $body, $data['headers']);

						//add reminder here
						do_action( 'yb_send_notification_after', $data );

						if($sent){
							YB_Project_PagesMeta::get_instance()->author_sent_notify([
								'post_id' => $article_parent_id,
								'user_id' => $data['user_id'],
								'action' 	=> 'u',
								'value' 	=> 1
							]);
						}//if sent

					}
				}//if data
			}//foreach articles
		}//if count articles
	}


	/**
	* initialize ajax.
	**/
	public function init_ajax()
	{
		add_action( 'wp_ajax_task_notify', array($this, 'ajax_notify') );
	}

	public function __construct()
	{

	}

}
