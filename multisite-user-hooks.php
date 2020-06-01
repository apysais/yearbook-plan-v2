<?php
/**
 * Plugin Name:     Multisite User Meta
 * Plugin URI:      https://foxland.fi/user-meta-in-wordpress-multisite/
 * Description:     Test multisite user meta with text value.
 * Version:         1.0.0
 * Author:          Sami Keijonen
 * Author URI:      https://foxland.fi/
 * Text Domain:     multisite-user-meta
 * Domain Path:     /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package         Multisite User Meta
 * @author          Sami Keijonen <sami.keijonen@foxnet.fi>
 * @copyright       Copyright (c) Sami Keijonen
 * @license         http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds setting in user profile.
 *
 * @since  1.0.0
 * @return void
 */
function multisite_user_meta_field( $user ) {

	// Get setting.
	$setting = multisite_user_meta_get_setting( $user->ID );
	if(is_multisite() && yb_is_site_owner($user->ID)){
		$yb_account_meta = YB_Account_Meta::get_instance()->school_account(['user_id'=>$user->ID,'single'=>true]);
		?>

		<h3><?php esc_html_e( 'Multisite setting', 'multisite-user-meta' ); ?></h3>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="yb_school_account"><?php esc_html_e( 'School Admin', 'multisite-user-meta' ); ?></label></th>
					<td>
						<input type="checkbox" name="yb_school_account" id="yb_school_account" value="<?php echo $yb_account_meta ? '1':'0'; ?>" class="multisite-user-meta medium-text multisite-user-meta-setting" <?php echo $yb_account_meta ? 'checked':''; ?>/>
						<?php if($yb_account_meta){ ?>
							<p class="description"><?php esc_html_e( 'Currently School Admin, Un-Check to make it non school admin.', 'multisite-user-meta' ); ?></p>
						<?php }else{ ?>
							<p class="description"><?php esc_html_e( 'Check if its school admin.', 'multisite-user-meta' ); ?></p>
						<?php } ?>
					</td>
				</tr>
			<tbody>
		</table>

	<?php
	}
}
add_action( 'show_user_profile', 'multisite_user_meta_field' );
add_action( 'edit_user_profile', 'multisite_user_meta_field' );

/**
 * Save setting in user profile.
 *
 * @since  1.0.0
 * @return void
 */
function multisite_user_meta_save_field( $user_id ) {

	// Bail if we don't have permission to edit user.
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if(is_multisite() && yb_is_site_owner($user_id)){
		$yb_school_account = isset($_POST['yb_school_account']) ? 1 : 0;
		if($yb_school_account){
			$current_user = get_userdata($user_id);
			yb_account_name_update($user_id, $current_user->nickname);
			yb_school_account_update($user_id, 1);
		}else{
			YB_Account_Meta::get_instance()->school_account(['user_id'=>$user_id,'action'=>'d']);
			YB_Account_Meta::get_instance()->account_name(['user_id'=>$user_id,'action'=>'d']);
		}
	}
}
add_action( 'personal_options_update', 'multisite_user_meta_save_field' );
add_action( 'edit_user_profile_update', 'multisite_user_meta_save_field' );

/**
 * Get user setting.
 *
 * @since  1.0.0
 * @return void
 */
function multisite_user_meta_get_setting( $user_id = 0 ) {

	// Get current user id if it's not set from function call
	if ( empty( $user_id ) && is_user_logged_in() ) {
		$user_id = get_current_user_id();
	}

	// Get setting
	$setting = get_user_option( 'multisite_user_meta_setting', $user_id );

	return $setting;

}

//add_action('wp_loaded', 'yb_test');
