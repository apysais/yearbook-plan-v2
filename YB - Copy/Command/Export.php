<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * API for tasks.
 * @since 0.0.1
 * */
class YB_Command_Export {
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

  public function __invoke( $args, $assoc_args ) {
    //print_r($args);
    //print_r($assoc_args);
		$export_obj = new YB_Settings_Export;

    $article_id = $assoc_args['article-id'];
    $site_id 		= $assoc_args['site-id'];

		if(is_multisite()){
			switch_to_blog($site_id);
		}

		$id = $article_id;
		$zip_array = [];
		// echo $site_id.'<br>';
		// echo $id.'<br>';
		$data 					= $export_obj->getData($id);
		$parent_post   	= get_post( $id );
		if($parent_post){
			$zip_name = str_replace(' ', '-', strtolower($parent_post->post_title));
			if($data){
				$zip_array['zip_name'] = $zip_name;
				foreach($data as $k_data => $v_data){
					$current_page = 'Page '. $v_data['page'] . ' - ' . $v_data['title'];
					$img_folder 	= 'Images '.' - ' . $v_data['title'];
					if(is_array($v_data)){
						$zip_array['data'][$v_data['id']] = [
							'page' 						=> $current_page,
							'article_name' 		=> $v_data['title'],
							'article_content' => wp_strip_all_tags($v_data['content']),
							'image_folder' 		=> $img_folder
						];
						if(is_array($v_data['media']) && isset($v_data['media'])){
							foreach($v_data['media'] as $k_media => $v_media){
								$fullsize_path = get_attached_file( $v_media->ID );
								$zip_array['data'][$v_data['id']]['images'][] = $fullsize_path;
							}
						}
					}
				}
			}
		}
		//apyc_dd($zip_array);
		$ret_zip_create = $export_obj->createZip($zip_array);
		if($ret_zip_create){
			$zip_name = $ret_zip_create['zip_name'] . '.zip';
			$upload_dir 		= wp_upload_dir();
      $destination 		= $upload_dir['basedir'] . '/' . $zip_name;

			WP_CLI::log('Added ZIP File Name  : ' . $ret_zip_create['zip_name']);
			WP_CLI::log('Added ZIP File Path  : ' . $ret_zip_create['zip_full_path']);
			WP_CLI::log('You Can download the export zip here: <a href="'.$upload_dir['baseurl'] . '/' . $zip_name.'">Download ZIP</a>');
			WP_CLI::success( 'Success' );
			//WP_CLI::log($ret_zip_create['zip_name']);
			//WP_CLI::log($ret_zip_create['zip_full_path']);
			exec('zip -r -j '.$destination.' '.$ret_zip_create['zip_full_path'].'');
			$export_obj->notifyEmail([
				'zip_path' => $upload_dir['baseurl'] . '/' . $zip_name
			]);
		}else{
			WP_CLI::error( 'Export to ZIP fail.' );
		}

		if(is_multisite()){
			restore_current_blog();
		}
  }

}
