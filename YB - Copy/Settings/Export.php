<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * API for tasks.
 * @since 0.0.1
 * */
class YB_Settings_Export {
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

  public function __construct()
  {

  }

  public function createZip($data_arr = [])
  {

    $zip_arr = [];
    if($data_arr && !empty($data_arr)){
			$unix_time 			= strtotime("now");
      $export_name 		= isset($data_arr['zip_name']) ? $data_arr['zip_name'] : 'zip';
      $upload_dir 		= wp_upload_dir();
			//$zip_file_name 	= $export_name .'-'.$unix_time.'.zip';
			$zip_file_name 	= $export_name.'-'.$unix_time;
      $destination 		= $upload_dir['basedir'] . '/' . $zip_file_name;
			//echo $destination;
			mkdir( $destination, 0700 );
			//yb_wp_cli_logs('Creating Folder : ' . $destination);
			//apyc_dd($data_arr, 1);
      if(isset($data_arr['data']) && !empty($data_arr['data'])){
        foreach($data_arr['data'] as $data_k => $data_v){
					$zip = new ZipArchive;

          $page_folder 			= $data_v['page'];
          $article_name_txt = str_replace(' ', '', $data_v['article_name']);
          $article_content 	= $data_v['article_content'];
          $folder_image 		= $data_v['image_folder'];

					//yb_wp_cli_logs('Adding ZIP Folders : ' . $page_folder);

					$destination_zip = $destination .'/'. $page_folder.'.zip';
					yb_wp_cli_logs($destination_zip);
          if ($zip->open($destination_zip, ZipArchive::CREATE) === TRUE)
          {

            $zip->addEmptyDir($destination .'/'. $page_folder);

						//yb_wp_cli_logs('Adding ZIP Content to Folders : ' . $page_folder .'/'. $article_name_txt . '.txt');
						$zip->addFromString($page_folder .'/'. $article_name_txt . '.txt', $article_content);

            if(
							isset($data_v['images'])
							&& is_array($data_v['images'])
							&& !empty($data_v['images'])
						){
              $folder_image_dir = $page_folder . '/' . $folder_image;
							//yb_wp_cli_logs('Adding ZIP Images Folders : ' . $folder_image_dir);
              $index = 1;
              foreach($data_v['images'] as $img){
								$extension = pathinfo($img, PATHINFO_EXTENSION);
                $file_extension = $extension ? $extension : false;
                $file_name = basename($img);
                $folder_images_name = $folder_image_dir.'/'.$file_name;
                //$folder_images_name = $folder_image_dir.'/'.$article_name_txt.'-'.$index.'.'. $file_extension;
								//yb_wp_cli_logs('Adding Images ' . $folder_images_name);
                $zip->addFile($img, $folder_images_name);
                $index++;
              }
            }
						$zip->close();
          }

        }
      }

			return [
				'zip_name' 			=> $zip_file_name,
				'zip_full_path' => $destination,
			];
    }
		return false;
  }

  public function getData($id)
  {
    $get_data_arr = false;
    if($id){
      $arg_get_data = [
				'numberposts' => -1,
				'post_status' => 'any',
				'post_type' => YB_CPT_PREFIX,
				'post_parent' => $id,
				'suppress_filters' => false,
				'order' => 'ASC',
				'orderby' => 'menu_order',
      ];
      $get_data = get_posts($arg_get_data);
      if($get_data){
        $index = 0;
        foreach($get_data as $k => $child){
          if($index == 0){
            $page_number_round = 1;
          }

          $get_block_size = YB_Project_PagesMeta::get_instance()->yb_block_size([
						'action' => 'r',
						'post_id' => $child->ID,
						'single' => true
					]);

          $is_finished = YB_Project_Pages::get_instance()->is_finished([
						'post_id' => $child->ID,
						'single' => true
					]);
          if( $is_finished == 3 || $is_finished == 4){
            $get_data_arr[$child->ID] = [
              'id' => $child->ID,
              'title' => $child->post_title,
              'content' => $child->post_content,
              'page' => yb_rounder($page_number_round),
              'media' => get_attached_media( 'image', $child->ID )
            ];

						YB_Project_Pages::get_instance()->is_finished([
							'post_id' => $child->ID,
							'single' => true,
							'action' => 'u',
							'value' => 4,
						]);

          }

          $index++;
 				  $page_number_round += ($get_block_size);
        }
        return $get_data_arr;
      }
    }
    return false;
  }

	public function init($article_id)
	{
		$id = $article_id;
		$zip_array = [];
		$data = $this->getData($id);
		$parent_post   = get_post( $id );
		if($parent_post){
			$zip_name = str_replace(' ', '-', strtolower($parent_post->post_title));
			if($data){
				$zip_array['zip_name'] = $zip_name;
				foreach($data as $k_data => $v_data){
					$current_page = 'Page '. $v_data['page'] . ' - ' . $v_data['title'];
					$img_folder = 'Images '.' - ' . $v_data['title'];
					if(is_array($v_data)){
						$zip_array['data'][$v_data['id']] = [
							'page' => $current_page,
							'article_name' => $v_data['title'],
							'article_content' => $v_data['content'],
							'image_folder' => $img_folder
						];
						if(is_array($v_data['media'])){
							foreach($v_data['media'] as $k_media => $v_media){
								$fullsize_path = get_attached_file( $v_media->ID );
								$zip_array['data'][$v_data['id']]['images'][] =$fullsize_path;
							}
						}
					}
				}
			}
		}
		$ret_zip_create = $this->createZip($zip_array);
		if($ret_zip_create){
			return $ret_zip_create;
		}
	}

  public function run()
  {
    if(isset($_GET['export']) && isset($_GET['id'])){
      $id = $_GET['id'];
      $zip_array = [];
      $data = $this->getData($id);
      $parent_post   = get_post( $id );
      if($parent_post){
        $zip_name = str_replace(' ', '-', strtolower($parent_post->post_title));
        if($data){
          $zip_array['zip_name'] = $zip_name;
          foreach($data as $k_data => $v_data){
            $current_page = 'Page '. $v_data['page'] . ' - ' . $v_data['title'];
            $img_folder = 'Images '.' - ' . $v_data['title'];
            if(is_array($v_data)){
              $zip_array['data'][$v_data['id']] = [
                'page' => $current_page,
                'article_name' => $v_data['title'],
                'article_content' => $v_data['content'],
                'image_folder' => $img_folder
              ];
              if(is_array($v_data['media'])){
                foreach($v_data['media'] as $k_media => $v_media){
                  $fullsize_path = get_attached_file( $v_media->ID );
                  $zip_array['data'][$v_data['id']]['images'][] =$fullsize_path;
                }
              }
            }
          }
        }
      }
      $ret_zip_create = $this->createZip($zip_array);
			if($ret_zip_create){
				$file_name = $ret_zip_create['zip_name'];
				$file_path = $ret_zip_create['zip_full_path'];
				//print_r($ret_zip_create);
				header("Content-type: application/zip");
		    header("Content-Disposition: attachment; filename = $file_name");
		    header("Pragma: no-cache");
		    header("Expires: 0");
		    readfile("$file_path");
		    exit;
			}

    }
  }

	public function notifyEmail($args = [])
	{
		$current_user_id = get_current_user_id();
		$user_info = get_userdata($current_user_id);
		$email_to = $user_info->user_email;

		if(!isset($user_info->user_email) || $user_info->user_email == ''){
			$email_to = get_option('admin_email');
		}

		if(isset($args['email_to'])){
			$email_to = $args['email_to'];
		}

		$home_url = get_home_url();
		$zip_path = '';
		if(isset($args['zip_path'])){
			$zip_path = $args['zip_path'];
		}
		
		if($email_to != ''){
			$to = $email_to;
			$subject = 'Export Is Ready';
			$body = '<p>You Can download the export zip here: <a href="'.$zip_path.'">Download ZIP</a></p>';
			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail( $to, $subject, $body, $headers );
		}

	}

}
