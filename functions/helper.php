<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
function is_staging()
{
	if(strpos( $_SERVER['HTTP_HOST'], 'staging.planit.yolo.co.nz') !== false){
		return true;
	}
	if(strpos( $_SERVER['HTTP_HOST'], 'yearbook.dew') !== false){
		return true;
	}
	return false;
}
function apyc_dd($array, $die = false){
  echo '<pre>';
    print_r($array);
  echo '</pre>';
  if($die){
    wp_die();
  }
}
function allt_date_to_number_count($ts){
	if(!ctype_digit($ts)) {
			$ts = strtotime($ts);
	}
	$diff = time() - $ts;
	if($diff == 0) {
			return 0;
	}elseif($diff > 0) {
		$day_diff = floor($diff / 86400);
		return $day_diff;
	}else{
		return $diff;
	}
}
function allt_due_date_standing($ts) {
	if(!ctype_digit($ts)) {
			$ts = strtotime($ts);
	}
	$diff = time() - $ts;
	if($diff == 0) {
			return 'Today';
	} elseif($diff > 0) {
		$day_diff = floor($diff / 86400);
		if($day_diff == 0) {
				if($diff < 60 || $diff < 120 || $diff < 3600 || $diff < 7200 || $diff < 86400) return 'Today';
		}else{
			return 'Overdue';
		}
	}else{
		return 'Upcoming';
	}
}
function time2str($ts) {
    if(!ctype_digit($ts)) {
        $ts = strtotime($ts);
    }
    $diff = time() - $ts;
    if($diff == 0) {
        return 'now';
    } elseif($diff > 0) {
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 60 || $diff < 120 || $diff < 3600 || $diff < 7200 || $diff < 86400) return 'Today';
        }
        if($day_diff == 1) { return 'Yesterday'; }
        if($day_diff < 7) { return $day_diff . ' days ago'; }
        if($day_diff < 31) { return ceil($day_diff / 7) . ' weeks ago'; }
        if($day_diff < 60) { return 'last month'; }
        return date('F Y', $ts);
    } else {
        $diff = abs($diff);
        $day_diff = floor($diff / 86400);
        if($day_diff == 0) {
            if($diff < 120) { return 'in a minute'; }
            if($diff < 3600) { return 'in ' . floor($diff / 60) . ' minutes'; }
            if($diff < 7200) { return 'in an hour'; }
            if($diff < 86400) { return 'in ' . floor($diff / 3600) . ' hours'; }
        }
        if($day_diff == 1) { return 'Tomorrow'; }
        if($day_diff < 4) { return 'This Week, On '.date('l', $ts); }
        if($day_diff < 7 + (7 - date('w'))) { return 'next week'; }
        if(ceil($day_diff / 7) < 4) { return 'in ' . ceil($day_diff / 7) . ' weeks'; }
        if(date('n', $ts) == date('n') + 1) { return 'next month'; }
        return date('F Y', $ts);
    }
}

function yb_get_upcoming_days_count($data_date) {
	$start_date = date('Y-m-d');
	$start_date = strtotime($start_date);
	$end_date = strtotime($data_date);
	return ($end_date - $start_date)/60/60/24;
}

function yb_admin_url_yearbookpage($uri = '') {
	return admin_url('admin.php?page=YearBook' . $uri);
}
function yb_admin_url_yearbookpage_list($uri = '') {
	return admin_url('edit.php?post_status=all&post_type=yearbook-plan' . $uri);
}
function yb_admin_url_school_account($uri = '') {
	return admin_url('admin.php?page=SchoolAccount' . $uri);
}
function yb_contributor_url($uri = '') {
	return admin_url('admin.php?page=Contributors' . $uri);
}
function yb_redirect_to($url) {
	?>
	<script type="text/javascript">
		window.location = '<?php echo $url; ?>';
	</script>
	<?php
	die();
}
function yb_empty_session_validate_msg() {
	$_SESSION['validate_msg'] = '';
}
function yb_validation_has_error($input) {
	yb_empty_session_validate_msg();

	$validate = new YB_RequestValidation($input);

	if($validate->hasError()) {
		$msg = $validate->getvalidateMsg();
		$_SESSION['validate_msg'] = $msg;
		return true;
	}
	return false;
}
function yb_get_validation() {
	$msg = isset($_SESSION['validate_msg']) ? $_SESSION['validate_msg']:'';
	$data['msg'] = $msg;
	yb_empty_session_validate_msg();
	YB_View::get_instance()->admin_partials('partials/validation/msg.php', $data);
}

function yb_school_account_update($user_id, $val) {
	$arg = [
		'user_id' => $user_id,
		'action'  => 'u',
		'value'   => $val
	];
	$meta = new YB_Account_Meta;
	$meta->school_account($arg);
}

function yb_account_name_update($user_id, $val) {
	$arg = [
		'user_id' => $user_id,
		'action'  => 'u',
		'value'   => $val
	];
	$meta = new YB_Account_Meta;
	$meta->account_name($arg);
}
function yb_array_value_exists_wildcard ( $array, $search, $return = '' ) {
    $search = str_replace( '\*', '.*?', preg_quote( $search, '/' ) );
    $result = preg_grep( '/^' . $search . '$/i', array_values( $array ) );
    if ( $return == 'key-value' )
        return array_intersect( $array, $result );
    return $result;
}
function yb_array_key_exists_wildcard ( $array, $search, $return = '' ) {
    $search = str_replace( '\*', '.*?', preg_quote( $search, '/' ) );
    $result = preg_grep( '/^' . $search . '$/i', array_keys( $array ) );
    if ( $return == 'key-value' )
        return array_intersect_key( $array, array_flip( $result ) );
    return $result;
}
function yb_is_admin_current_user($current_user){

	if($current_user && isset($current_user->roles)){
		$roles = ( array ) $current_user->roles;
	}else{
		$current_user = wp_get_current_user();
		$roles = ( array ) $current_user->roles;
	}
	if ( in_array( 'administrator', $roles ) ) {
			return true;
	}
	return false;
}
function yb_rounder($num){
	$round_number = $num;
	//list($whole, $decimal) = explode('.', $num);
	$explode_number = explode('.', $num);

	if(isset($explode_number[0])){
		$whole = $explode_number[0];
	}
	$decimal = 0;
	if(isset($explode_number[1])){
		$decimal = $explode_number[1];
		if($decimal == 99){
			$round_number = ceil($num);
		}elseif($decimal == 49){
			$round_number = (float)($whole + .50);
		}else{
			$round_number = round($num, 2);
		}
	}

	return $round_number;
}

function yb_is_whole_number($value) {
    return (is_numeric($value) && (round($value, 3) == round($value)));
}

function yb_get_decimal_part($number)
{
  $negative = 1;
  if ($number < 0)
  {
    $negative = -1;
    $number *= -1;
  }

  return ($number - floor($number)) * $negative;
}

function yb_custom_logs($message) {
    if(is_array($message)) {
        $message = json_encode($message);
    }
		$custom_file = yb_get_plugin_dir() . "custom_logs.log";
    $file = fopen($custom_file,"a");
    fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message);
    fclose($file);
}

function yb_wp_cli_logs($msg = '', $type = 'log')
{
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		switch($type){
			case 'log':
					WP_CLI::log($msg);
			break;
		}
	}
}

function yb_show_export_button($article_id = 0, $school_id, $is_publish = false)
{
	$site_id = get_current_blog_id();

	if($is_publish && is_super_admin() && $article_id > 0){
		echo '<a href="'.admin_url('admin.php?page=YearBook&_method=exportYearBook&site-id='.$site_id.'&article-id='.$article_id.'&school-id='.$school_id).'" class="btn export-btn btn-primary btn-sm">Export</a>';
	}

}

function yb_reminder($tag, $due , $article_title, $send_to = []) {
	$res = YB_Reminder::get_instance()->send($tag, $due , $article_title, $send_to);
}
