<?php 
/* set all setting data for sign ups */
$dir = PTO_SIGN_UP_PLUGIN_DIR;
wp_enqueue_script( 'sign-js-editor-js-plugin', $dir.'assets/js/editor.js', array(), '1.0.0', true );
$get_color_option = get_option( 'pto_color_sign_ups_setting' );
$defult_wording_volunteers = get_option( 'defult_wording_volunteers' );
$administrators_notifcations = get_option( 'administrators_notifcations' );
$administrators_invitation = get_option( 'administrators_invitation' );
$ownsignup_invitation = get_option( 'ownsignup_invitation' );
$request_access_accept = get_option( 'request_access_accept' );
$request_access_decline = get_option( 'request_access_decline' );
$add_user_to_signup = get_option( 'add_user_to_signup' );
$remove_user_from_signup = get_option( 'remove_user_from_signup' );
$allow_own_singup_option = get_option( 'allow_own_singup_option' );
$show_request_access_btn = get_option( 'show_request_access_btn' );
$allcolors = "";
if ( !empty( $get_color_option ) ) {
	$allcolors = $get_color_option;
}
?>
<div class='wp-admin pto-custom-style'>
	<div class="wrap">
		<form method="POST" name="plugin_setting_sign_ups" id="plugin_setting_sign_ups">
			<div class="pto-sign-ups-setting-section">
				<div class="pto-sign-ups-setting-section-title">
					<h1><?php esc_html_e( 'Sign Ups Settings', PTO_SIGN_UP_TEXTDOMAIN ); ?></h1>
				</div>
				<div id="pto-signup-setting-tabs">
					<ul>
						<li><a href="#admin-tab">ADMINISTRATORS</a></li>
						<li><a href="#system-tab">SYSTEM EMAILS</a></li>
						<li><a href="#pages-tab">PAGES</a></li>
						
					</ul>
					<div id="admin-tab">
						<div class="pto-sign-ups-setting-section-title-desc">			
							<div class="pto-admin-setting-user-details">
								<div class="pto-sign-ups-setting-colors">
									<div class="pto-sign-ups-setting-colors-box">
										<div class="pto-signup-setting-title-left">
											<h2><?php esc_html_e( 'Set Sign Up Plugin Administrators', PTO_SIGN_UP_TEXTDOMAIN ); ?> 
											<i class="fa fa-info-circle" title="Allow other users to help manage all of your organization's sign ups by adding them here." aria-hidden="true"></i>
										</h2>
									</div>
									<div class="pto-signup-setting-text-right">
										<p>(access to ALL sign ups)</p>
									</div>
								</div>
							</div>
							<input type="button" name="useradd" value="+ Add Plugin Administrators" class="button button-primary" onclick="jQuery('#pto-sign-admin-add').addClass('pto-modal-open');">
							<div class="pto-admin-setting-user-data">
								<?php
								/* get all user details */
								$users = get_users();
								?>
								<table class="pto-admin-setting-user-table-project wp-list-table widefat fixed  striped table-view-list posts" width="100%">
									<thead>
										<tr>
											<th><?php esc_html_e( 'ID', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
											<th><?php esc_html_e( 'Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
											<th><?php esc_html_e( 'Username', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
											<th><?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
											<th><?php esc_html_e( 'Action', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $users as $user ) { if ( array_key_exists( "sign_up_plugin_administrators", $user->caps ) ) { ?>
											<tr class='own_user_<?php echo intval( $user->ID ); ?>'>
												<td><?php echo intval( $user->ID ); ?></td>
												<td><?php esc_html_e( $user->data->user_nicename ); ?></td>
												<td><?php esc_html_e( $user->data->display_name ); ?></td>
												<td><?php esc_html_e( $user->data->user_email ); ?></td>
												<td>
													<div class="hook-action">
														<span class="delete-user">
															<a href="javascript:void(0)" class="delete_user_signup" id="<?php echo intval( $user->ID ); ?>" attr-type="2"> Delete </a>
															<span class="pto-separator">|</span>
														</span>
														<span class="resend-invitation" user-id="<?php echo intval( $user->ID ); ?>" type="sign_up_plugin_administrators">
															<a href="javascript:void(0)"> Resend Invitation </a>
														</span>
													</div>
												</td>
											</tr>
										<?php } } ?>
									</tbody>
								</table>
							</div>
						</div>
						
						
					</div>
				</div>
				<div id="system-tab">
					<div class="pto-admin-setting-user-details">
						<div class="pto-sign-ups-setting-sign-up-sytem-emails">
							<div class="pto-sign-ups-setting-sign-up-sytem-emails-title">
								<div class="pto-sign-ups-setting-colors-box">
									<h2><?php esc_html_e( 'Sign Up System Emails', PTO_SIGN_UP_TEXTDOMAIN ); ?></h2>
								</div>
							</div>
							<div class="pto-sign-ups-setting-sign-up-sytem-emails-data">
								<div class="pto_sign_ups_after_volunteers mb-15px">
									<label><?php esc_html_e( 'Set the default wording for the receipts that go to volunteers after a successful sign up.', PTO_SIGN_UP_TEXTDOMAIN ); ?>
									<i class="fa fa-info-circle" title="Here you can set default language for successful volunteer sign ups for all future sign ups." aria-hidden="true"></i>
								</label>
								<?php 					
								wp_editor( $defult_wording_volunteers, 'defult-wording-volunteers', $settings = array(
									'textarea_name' => 'defult-wording-volunteers',
									'textarea_rows' => 10
								) );
								?>
							</div>	
							<div class="pto_sign_ups_after_volunteers mb-15px">
								<label><?php esc_html_e( 'Set the wording for the administrator notification emails from a successful sign up.', PTO_SIGN_UP_TEXTDOMAIN ); ?>
								<i class="fa fa-info-circle" title="Here you can set default language included in the emails that go to sign up adminsitrators when a new volunteer signs up to their sign up." aria-hidden="true"></i>
							</label>
							<?php 				
							
							wp_editor( $administrators_notifcations, 'administrators-notifcations', $settings = array(
								'textarea_name' => 'administrators-notifcations',
								'textarea_rows' => 10
							) );
							?>
						</div>
						<div class="pto_sign_ups_after_volunteers mb-15px">
							<label><?php esc_html_e( 'Set the email wording sent when inviting a user to be a sign up administrator.', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							<i class="fa fa-info-circle" title="Here you can set default language included in the emails that go to newly invited sign up administrators." aria-hidden="true"></i>
						</label>
						<?php 				
						wp_editor( $administrators_invitation, 'administrators-invitation', $settings = array(
							'textarea_name' => 'administrators-invitation',
							'textarea_rows' => 10
						) );
						?>
					</div>
					
					<div class="pto_sign_ups_after_volunteers mb-15px">
						<label><?php esc_html_e( 'Set the email wording for the receipts that go to volunteers after a successful sign up by an administrator.', PTO_SIGN_UP_TEXTDOMAIN ); ?>
						<i class="fa fa-info-circle" title="Here you can set default language included in the emails that go to volunteers after an administrator has added them from the back side of the application." aria-hidden="true"></i>
					</label>
					<?php 				
					wp_editor( $add_user_to_signup, 'add-user-to-signup', $settings = array(
						'textarea_name' => 'add-user-to-signup',
						'textarea_rows' => 10
					) );
					?>
				</div>

				<div class="pto_sign_ups_after_volunteers mb-15px">
					<label><?php esc_html_e( 'Set the email wording for the receipts that go to volunteers after they have been removed from a sign up by an administrator.', PTO_SIGN_UP_TEXTDOMAIN ); ?>
					<i class="fa fa-info-circle" title="Here you can set default language included in the emails that go to volunteers after an administrator has removed them from a sign up." aria-hidden="true"></i>
				</label>
				<?php 				
				wp_editor( $remove_user_from_signup, 'remove-user-from-signup', $settings = array(
					'textarea_name' => 'remove-user-from-signup',
					'textarea_rows' => 10
				) );
				?>
			</div>
		</div>
	</div>
</div>
</div>
<div id="pages-tab">
	<div class="pto-admin-setting-user-details">
		<div class="pto_sign_ups_page_create pto-admin-setting-user-details" id="page-listing">
			<?php include "pto_sign_ups_page_listing.php"; ?>	
		</div>
	</div>
</div>

<div class="pto-sign-ups-setting-save" style="margin-top:15px">
	<input type="hidden" name="action" value="pto_save_setting_action" id="pto_save_setting_action">	
	<input type="button" name="save_pto_sign_ups" value="Save" id="plugin_setting_submit" class="button button-primary">		
</div>
</div>
</div>
</form>
</div>
<?php
if ( $_POST ) {
	if ( isset( $_POST['action'] ) ) {
		$color_arr = array(
			"pto-background-color" => sanitize_text_field($_POST['pto-background-color']),
			"pto-text-color" => sanitize_text_field($_POST['pto-text-color']),
			"pto-header-background"=> sanitize_text_field($_POST['pto-header-background']),
			"pto-header-text-color"=> sanitize_text_field($_POST['pto-header-text-color']),
			"pto-task-header-background-color"=> sanitize_text_field($_POST['pto-task-header-background-color']),
			"pto-task-header-text-color"=> sanitize_text_field($_POST['pto-task-header-text-color'])
		);
		update_option( 'pto_color_sign_ups_setting', $color_arr );
		if ( isset( $_POST['defult-wording-volunteers'] ) )
			update_option( 'defult_wording_volunteers', wp_kses_post($_POST['defult-wording-volunteers']) );
		else
			update_option( 'defult_wording_volunteers', "" );
		if ( isset( $_POST['administrators-notifcations'] ) )
			update_option( 'administrators_notifcations', wp_kses_post($_POST['administrators-notifcations']) );
		else
			update_option( 'administrators_notifcations', "" );
		if ( isset( $_POST['administrators-invitation'] ) )
			update_option( 'administrators_invitation', wp_kses_post($_POST['administrators-invitation']) );
		else
			update_option( 'administrators_invitation', "" );
		if ( isset( $_POST['ownsignup-invitation'] ) )
			update_option( 'ownsignup_invitation', wp_kses_post($_POST['ownsignup-invitation']) );
		else
			update_option( 'ownsignup_invitation', "" );
		if ( isset( $_POST['request-access-accept'] ) )
			update_option( 'request_access_accept', wp_kses_post($_POST['request-access-accept']) );
		else
			update_option( 'request_access_accept', "" );
		
		if ( isset( $_POST['request-access-decline'] ) )
			update_option( 'request_access_decline', wp_kses_post($_POST['request-access-decline']) );
		else
			update_option( 'request_access_decline', "" );
		
		if ( isset( $_POST['add-user-to-signup'] ) )
			update_option( 'add_user_to_signup', wp_kses_post($_POST['add-user-to-signup']) );
		else
			update_option( 'add_user_to_signup', "" );
		if ( isset( $_POST['remove-user-from-signup'] ) )
			update_option( 'remove_user_from_signup', wp_kses_post($_POST['remove-user-from-signup']) );
		else
			update_option( 'remove_user_from_signup', "" );				
		if ( isset( $_POST['pto-display-all-sing-ups'] ) ) {	

			update_option( 'pto_display_all_sing_ups', intval($_POST['pto-display-all-sing-ups']) );
			$page_data = get_page( intval($_POST['pto-display-all-sing-ups']) ); 		
			$content = $page_data->post_content;
			if ( strpos( $content, "[pto_signup_all_listing]") !== false ) {					
			}
			else{				
				$signup_content = $content . "[pto_signup_all_listing]";				
				$my_post = array(
					'ID'           => intval($_POST['pto-display-all-sing-ups']),
					'post_content' => $signup_content,
				);			   
					// Update the post into the database
				wp_update_post( $my_post );
			}
		}else{
			update_option( 'pto_display_all_sing_ups', "" );
		}			
		if( isset( $_POST['pto-volunteers-sign-ups'] ) ) {
			update_option( 'pto_volunteers_sign_ups', intval($_POST['pto-volunteers-sign-ups']) );
			$page_data = get_page( intval($_POST['pto-volunteers-sign-ups']) ); 
			$content = $page_data->post_content;
			if ( strpos( $content, "[pto_signup_my_history]" ) !== false ) {					
			}
			else {				
				$signup_content = $content . "[pto_signup_my_history]";				
				$my_post = array(
					'ID'           => intval($_POST['pto-volunteers-sign-ups']),
					'post_content' => $signup_content,
				);			   
					// Update the post into the database
				wp_update_post( $my_post );
			}				
		}				
		else{
			update_option( 'pto_volunteers_sign_ups', "" );
		}
		
		if ( isset( $_POST['pto-checkout-sign-ups'] ) ) {
			update_option( 'pto_checkout_sign_ups', intval($_POST['pto-checkout-sign-ups']) );
			$page_data = get_page( intval($_POST['pto-checkout-sign-ups']) ); 	
			$content = $page_data->post_content;
			if ( strpos( $content, "[pto_signup_checkout]" ) !== false ) {					
			}
			else{					
				$signup_content = $content . "[pto_signup_checkout]";				
				$my_post = array(
					'ID'           => intval($_POST['pto-checkout-sign-ups']),
					'post_content' => $signup_content,
				);			   
					// Update the post into the database
				wp_update_post( $my_post );
			}					
		}			
		else {
			update_option( 'pto_checkout_sign_ups', "" );
		}				
		if ( isset( $_POST['pto-post-sign-thank-you'] ) )
			update_option( 'pto_post_sign_thank_you', intval($_POST['pto-post-sign-thank-you'] ) );
		else
			update_option( 'pto_post_sign_thank_you', "" );
		if ( isset( $_POST['number_of_archive'] ) )
			update_option( 'number_of_archive', intval($_POST['number_of_archive']) );
		else
			update_option( 'number_of_archive', "" );
		if ( isset( $_POST['signup_title'] ) )
			update_option( 'signup_title', sanitize_text_field($_POST['signup_title']) );
		else
			update_option( 'signup_title', "" );
		if ( isset( $_POST['no_date_sign_ups'] ) ) 
			update_option( 'no_date_sign_ups', sanitize_text_field($_POST['no_date_sign_ups']) );
		else
			update_option( 'no_date_sign_ups', "" );
		if ( isset( $_POST['repeating_sign_ups'] ) )
			update_option( 'repeating_sign_ups', sanitize_text_field($_POST['repeating_sign_ups']) );
		else
			update_option( 'repeating_sign_ups', "" );
		if ( isset( $_POST['sortby_sing_ups'] ) )
			update_option( 'sortby_sing_ups', sanitize_text_field($_POST['sortby_sing_ups']) );
		else
			update_option( 'sortby_sing_ups', "");
		if(isset($_POST['sort_type']))
			update_option( 'sort_type', sanitize_text_field($_POST['sort_type']) );
		else
			update_option( 'sort_type', "" );
		if ( isset( $_POST['title_text_size'] ) )
			update_option( 'title_text_size', intval($_POST['title_text_size']) );
		else
			update_option( 'title_text_size', "" );
		if ( isset( $_POST['title_text_color'] ) )
			update_option( 'title_text_color', sanitize_text_field($_POST['title_text_color']) );
		else
			update_option( 'title_text_color', "" );
		if ( isset( $_POST['allow_own_singup_option'] ) )
			update_option( 'allow_own_singup_option', sanitize_text_field($_POST['allow_own_singup_option']));
		else
			update_option( 'allow_own_singup_option', "" );
		if ( isset( $_POST['show_request_access_btn'] ) )
			update_option( 'show_request_access_btn', sanitize_text_field($_POST['show_request_access_btn']));
		else
			update_option( 'show_request_access_btn', "" );				
		?>
		<script type="text/javascript">
			window.location.reload( true );
		</script>
		<?php
	}
}
?>
<div id="pto-sign-admin-add" class="pto-modal">
	<div class="pto-modal-content">
		<div class="pto-modal-container-header">
			<span><?php esc_html_e( 'Add Sign Up Plugin Administrators', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
			<span onclick="jQuery('#pto-sign-admin-add').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
		</div>
		<div class="pto-modal-container" id="pto-sign-admin-add-data"> 
			<div class="pto-admin-setting-user-search">
				<div class="cust-field">
					<input type="search" name="" placeholder="Search admin User" id="plugin_admin_search" class="w-100">
				</div>
			</div>
			<div class="search-users-admin" id="search_users_admin"></div>
			<div class="selected-users-admin" id="selected_users_admin"></div>
		</div>
		<div class="pto-modal-footer">
			<input type="button" name="add-admin_sign_ups" value="+ Add" id="pto-plugin-admin-role-add" class="button button-primary">
			<input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#pto-sign-admin-add').removeClass('pto-modal-open');">
		</div>
	</div>
</div>
<div id="pto-sign-own-add" class="pto-modal">
	<div class="pto-modal-content">
		<div class="pto-modal-container-header">
			<span><?php esc_html_e( 'Create Your Own Sign Up', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
			<span onclick="jQuery('#pto-sign-own-add').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
		</div>
		<div class="pto-modal-container" id="pto-sign-own-add-data"> 
			<div class="pto-admin-setting-user-search">
				<div class="cust-field">
					<input type="search" name="" placeholder="Search admin User" id="plugin_own_sign_search" class="w-100">
				</div>
			</div>
			<div class="search-users-admin" id="search_users__own_sign">				
			</div>
			<div class="selected-users-admin" id="selected_users_own_sign">				
			</div>
		</div>
		<div class="pto-modal-footer">
			<input type="button" name="add_own_sign_sign_ups" value="+ Add" id="pto-plugin-own-role-add" class="button button-primary">	
			<input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#pto-sign-own-add').removeClass('pto-modal-open');">
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	function pto_sign_up_copy_shortcode_all() {
		/* Get the text field */
		var copyText = document.getElementById("shortcode_all");
		
		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /* For mobile devices */
		/* Copy the text inside the text field */
		navigator.clipboard.writeText(copyText.value);
		/* Alert the copied text */
	//alert("Copied the text: " + copyText.value);
}
function pto_sign_up_copy_shortcode_my() {
	/* Get the text field */
	var copyText = document.getElementById("shortcode_my");
	
	/* Select the text field */
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */
	/* Copy the text inside the text field */
	navigator.clipboard.writeText(copyText.value);
	/* Alert the copied text */
	//alert("Copied the text: " + copyText.value);
}
function pto_sign_up_copy_shortcode_vertical() {
	/* Get the text field */
	var copyText = document.getElementById("shortcode_vertical");
	
	/* Select the text field */
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */
	/* Copy the text inside the text field */
	navigator.clipboard.writeText(copyText.value);
	/* Alert the copied text */
	//alert("Copied the text: " + copyText.value);
}

function get_page_list() {
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"get_page_all_list",
			nonce: pto_ajax_url.nonce
		},
		success:function( resu ){
			jQuery("#page-listing").html(resu);
		}
	});
}
</script>