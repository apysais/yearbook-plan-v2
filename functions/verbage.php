<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
function yb_verbage($key) {
	$text_domain = yb_get_text_domain();

	$arr = [
		'cpt_project' => [
			'name' => __('Year Book Plan', $text_domain),
			'singular_name' => __('Year Book Plan', $text_domain),
			'menu_name' => __('Year Book Plan', $text_domain),
			'name_admin_bar' => __('Year Book Plan', $text_domain),
			'slug' => 'yearbook-plan'
		],
		'cpt_yearbook_plan' => [
			'name' => __('Year Book Plan', $text_domain),
			'singular_name' => __('Year Book Plan', $text_domain),
			'menu_name' => __('Year Book Plan', $text_domain),
			'name_admin_bar' => __('Year Book Plan', $text_domain),
			'slug' => 'yearbook-plan'
		],
		'cpt_task' => [

		],
		'task_metabox' => [
			'name' => __('Title Header here', $text_domain),
			'title' => __('List', $text_domain),
		],
		'task_block_metabox' => [
			'name' => __('Title Block Header here', $text_domain),
			'title' => __('List Block', $text_domain),
		],
		'yearbook_menu' => [
			'page_title' => __('YearBook Plan', $text_domain),
			'menu_title' => __('YearBook Plan', $text_domain),
		],
		'client_menu' => [
			'page_title' => __('School Account', $text_domain),
			'menu_title' => __('School Account', $text_domain),
		],
		'fields' => [
			'remove_page' => __('Delete Page', $text_domain),
			'publish_page' => __('Update', $text_domain),
			'sort' => __('Drag to re-order', $text_domain),
			'drag_page' => __('Drag to re-order', $text_domain),
			'drag_blocks' => __('Drag to re-order', $text_domain),
			'remove_blocks' => __('Remove', $text_domain),
			'collapse_show_blocks' => __('Show Blocks', $text_domain),
			'collapse_hide_blocks' => __('Hide Blocks', $text_domain),
			'add_blocks' => __('Add Article', $text_domain),
			'add_page' => __('Add Section', $text_domain),
			'page_number' => __('Page Number', $text_domain),
			'yearbook_name' => __('Yearbook Name', $text_domain),
			'section_name' => __('Section Name', $text_domain),
			'template_name' => __('Template', $text_domain),
			'block_title' => __('Article Title', $text_domain),
			'block_template' => __('Template', $text_domain),
			'assign_to' => __('Assign To', $text_domain),
			'due_date' => __('Due Date', $text_domain),
			'block_size' => __('Article Length', $text_domain),
			'submit' => __('Submitted', $text_domain),
			'update' => __('Update', $text_domain),
			'word_count' => __('Word Count', $text_domain),
			'photo_count' => __('Image Count', $text_domain),
			'view_blocks' => __('View', $text_domain),
			'school_owner' => __('School', $text_domain),
			'yearbook_status' => __('Status', $text_domain),
			'yearbook_status_draft' => __('Draft', $text_domain),
			'yearbook_status_publish' => __('Publish', $text_domain),
			'column_page' => __('Article Length', $text_domain),
			'column_article_name' => __('Article Name', $text_domain),
			'column_author' => __('Author', $text_domain),
			'column_word_count' => __('Word Count', $text_domain),
			'column_photo_count' => __('Photo Count', $text_domain),
			'column_due_date' => __('Due Date', $text_domain),
			'column_status' => __('Status', $text_domain),
			'block_size_fullpage' => __('Full Page', $text_domain),
			'block_size_partpage' => __('Part Page', $text_domain),
		],
		'contributors_menu' => [
			'page_title' => __('Contributors', $text_domain),
			'menu_title' => __('Contributors', $text_domain),
		],
	];

	$arr = apply_filters( 'yb_verbage', $arr );

	return isset($arr[$key]) ? $arr[$key] : '';
}
function yb_page_size() {

}
