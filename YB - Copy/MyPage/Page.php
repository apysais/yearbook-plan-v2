<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 *
 * @since 0.0.1
 * */
class YB_MyPage_Page {
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

	public function create()
	{
		//apyc_dd($_POST,1);
		if (
		    isset( $_POST['page-content'] )
		    && wp_verify_nonce( $_POST['page-content'], 'add_page_content_'.$_POST['post_id'] )
		){
			//$t = json_decode($_POST['custom-img-id'][0], true);

			$post_id = $_POST['post_id'];
			$post = array(
	      'ID'           => $post_id,
	      'post_content' => $_POST['pagecontent'],
		  );
			wp_update_post($post);
			if(isset($_POST['submitted'])) {
				$finished = 0;
				if($_POST['submitted'] == 1){
					$finished = 1;
				}
				YB_Project_Pages::get_instance()->is_finished([
					'post_id' => $post_id,
					'single' => true,
					'action' => 'u',
					'value' => $finished,
				]);
				YB_Project_Pages::get_instance()->updateSubmitted($post_id, $finished);
				wp_redirect( site_url(YB_PUBLIC_TASK_LIST) );
				exit;
			}
		}
	}

	public function uploadWPImage($post_id, $file_input_name)
	{
		//add media file
		// These files need to be included as dependencies when on the front end.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		$attachment_id = false;
		$ret = [];
		// Let WordPress handle the upload.
		if ( $_FILES ) {
			$files = $_FILES[$file_input_name];
			foreach ($files['name'] as $key => $value) {
				if ($files['name'][$key]) {
					$file = [
						'name' => $files['name'][$key],
						'type' => $files['type'][$key],
						'tmp_name' => $files['tmp_name'][$key],
						'error' => $files['error'][$key],
						'size' => $files['size'][$key]
					];
					$_FILES = [
						'files' => $file
					];
					foreach ($_FILES as $file => $array) {
						$attachment_id = media_handle_upload( $file, $post_id );

						if ( is_wp_error( $attachment_id ) ) {
								// There was an error uploading the image.
								$attachment_id->get_error_message();
								$ret = 'Cannot upload : ' . $array['name'];
						} else {
								// The image was uploaded successfully!
								$ret = 1;
						}

					}
				}
			}
		}


		return $ret;
	}

	public function removeImage()
	{
		if(isset($_POST['post_image_id'])) {
			$post_image_id = $_POST['post_image_id'];
			wp_delete_attachment($post_image_id, 1);
		}

		wp_die();
	}

	public function uploadImage()
	{
		$ret = $this->uploadWPImage($_POST['post_image_id'], 'files');
		echo json_encode($ret);
		wp_die();
	}

	public function uploadImageAdmin()
	{
		$attachment = array(
	    'ID' => $_POST['attachment_id'],
	    'post_parent' => $_POST['parent_id']
		);
		wp_update_post( $attachment );
		wp_die();
	}

	public function showImage()
	{
		$task_id = $_POST['post_image_id'];
		$media = get_attached_media( 'image', $task_id );
		$data['images'] = $media;
		YB_View::get_instance()->public_partials('partials/dashboard/images.php', $data);
		wp_die();
	}

	public function initHook()
	{
		add_action('wp_ajax_remove_image', array($this, 'removeImage'));
		add_action('wp_ajax_upload_image', array($this, 'uploadImage'));
		add_action('wp_ajax_upload_image_admin', array($this, 'uploadImageAdmin'));
		add_action('wp_ajax_show_image', array($this, 'showImage'));
		add_action('wp_ajax_nopriv_upload_image', array($this, 'uploadImage'));
	}

  public function __construct()
  {

  }

}
