<?php
	$contact_organizer =  get_post_meta( $post_id, "contact_organizer", true ); 
	$tasks_slots_categories =  get_post_meta( $post->ID, "tasks_slots_categories", true ); 	
	$cat_colspan =  get_post_meta( $post->ID, "categories_colspan", true );
	$categories_colspan_show =  get_post_meta( $post->ID, "categories_colspan_show", true );
	$number_of_slots =  get_post_meta( $post->ID, "number_of_slots", true );
?>
<div class="pto-sign-up-third-tabs">
	<div class="pto-sign-up-compelling-visibility-section pto-admin-setting-user-details">
		<div class="pto-sign-up-compelling-visibility-section-title toggle-click">
			<h4><?php esc_html_e( 'Visibility and Security', PTO_SIGN_UP_TEXTDOMAIN ); ?>&nbsp;<i class="fa fa-info-circle" title='Control various display components on the frontend for your users - like a contact link, defaulting categories and minimizing task/slot lists.' aria-hidden="true"></i></h4>
		</div>
		<div class="pto-sign-up-compelling-visibility-section-details toggle-box">
			<div class="pto-sign-ups pto-sign-up-third-tabs-visibility-check">
				<input type="checkbox" name="contact-organizer" <?php if ( !empty( $contact_organizer ) ) { echo "checked"; } ?> value="volunteers-emails">
				<label><?php esc_html_e( 'Display a "Contact Organizer" link for volunteers to email for support', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				<i class="fa fa-info-circle" title="A link will appear on the sign up that will allow a potential volunteer to contact the organizer(s) of this sign up." aria-hidden="true"></i></label>
			</div>
			
		</div>
	</div>
	<div class="pto-sign-up-compelling-visibility-section pto-admin-setting-user-details">
		<div class="pto-sign-up-compelling-visibility-section-title toggle-click">
			<h4><?php esc_html_e( 'Checkout Fields', PTO_SIGN_UP_TEXTDOMAIN ); ?> 
				<i class="fa fa-info-circle" title="Adding a check out fields here will result in the fields being shown to a potential volunteer just prior to signing up. For example, you may wish to collect the t-shirt size of all volunteers. These customized field are for non-specific task information collection." aria-hidden="true"></i>
			</h4>
		</div>
		<div class="pto-sign-up-compelling-visibility-section-details toggle-box" >
			<?php 
				global $post;
				$checkout_fields_sign_up = get_post_meta( $post_id, "checkout_fields_sign_up", true );
			?>
			<div class="pto-signup-chekout-fields signup-checkout-add-new" <?php if ( !empty( $checkout_fields_sign_up ) ) { ?> style="display:block;" <?php } else { ?> style="display:none;" <?php } ?>>
				<a class="checkout-add-new button button-primary btn_add" href="javascript:void(window.open(<?php echo "'" . esc_url(site_url()) ."/wp-admin/post-new.php?post_type=pto-custom-fields&tasksloats=".intval( $post->ID )."&post_types=". esc_html($post->post_type) ."'"; ?>))"><?php esc_html_e( 'Add New', PTO_SIGN_UP_TEXTDOMAIN ); ?></a>
			</div>
			<div class="pto-signup-chekout-fields signup-checkout-add-new2" >
				<div class="mt-15px mb-15px">
					<input type="checkbox" class="checkout_fields_sign_up" id="checkout_fields_sign_up" name="checkout_fields_sign_up" <?php if ( !empty( $checkout_fields_sign_up ) ) { echo "checked"; } ?>> 
					<?php esc_html_e( 'I want to collect custom field information on volunteer checkout', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				</div>
			</div>
			<div id="pto-sign-up-compelling-visibility-section-details" class="custom-fields-show">
				<?php include "pto_custom_fileds.php"; ?>
			</div>
		</div>
	</div>
	<div class="pto-sign-up-agree-to-terms-section pto-admin-setting-user-details">
		<div class="pto-sign-up-agree-to-terms-section-title toggle-click">
			<h4><?php esc_html_e( 'Agree to Terms', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				<i class="fa fa-info-circle" title="Add an 'Agree to Terms' checkbox to your sign up checkout process which will need to be checked in order for your volunteer to sign up." aria-hidden="true"></i>
			</h4>
		</div>		
		<div class="pto-sign-up-agree-to-terms-section-details mt-15px toggle-box">
			<div class="pto-sign-up-agree-to-terms-section-checkbox">
				<?php 
					$agree_to_terms_sign_up = get_post_meta( $post_id, "agree_to_terms_sign_up", true );
				?>
				<input type="checkbox" id="agree_to_terms_sign_up" name="agree_to_terms_sign_up" <?php if ( !empty( $agree_to_terms_sign_up ) ) { echo "checked"; } ?> >
				<?php esc_html_e( 'Volunteers are required to agree to terms or other language', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</div>
			<div class="agree-to-terms-show">
				<?php
					$content = get_post_meta( $post->ID, "agree_to_terms", true );
					wp_editor( $content, 'agree_to_terms', $settings = array(
							'textarea_name' => 'agree_to_terms',
							'textarea_rows' => 10
							) );
					if ( isset( $_GET["action"] ) == "edit" ) {
						$volunteer_after_sign_up = get_post_meta( $post_id, "volunteer_after_sign_up", true );
					}
					else {
						$volunteer_after_sign_up = "on";
					}
					
				?>
			</div>
		</div>
	</div>
	<div class="pto-sign-up-administrators-section pto-admin-setting-user-details">
		<div class="pto-sign-up-administrators-section-title toggle-click">
			<h4><?php esc_html_e( 'Add Additional Administrators (optional)', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				<i class="fa fa-info-circle" title="Allow others to help you manage this sign up. Users assigned here will only have access to this specific sign up which they can access from the All Sign Ups page." aria-hidden="true"></i>	
			</h4>			
		</div>
		<div class="pto-sign-up-administrators-section-details toggle-box" >
			<div class="pto-sign-up-administrators-section-title side-title-btn">
				<a class="button button-primary btn_add" href="javascript:void(0);" onclick="jQuery('#add-administarter').addClass('pto-modal-open');jQuery('.search_user_pto_sign_up,#selected_user_sign_ups').html('');jQuery('#search-user-for-admin-signup').val('')"> Add New </a>
			</div>
			<div class="pto-sign-up-administrators-section-details" id="assign_users_pto_sign_upas">
				<table class="wp-list-table widefat fixed striped table-view-list posts">
					<thead>
						<tr>							
							<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
								<?php esc_html_e( 'Username', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							</th>
							<th scope="col" id="Name" class="manage-column column-title column-primary sortable desc">
								<?php esc_html_e( 'Name', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							</th>
							<th scope="col" id="Email" class="manage-column column-title column-primary sortable desc">
								<?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							</th>
							<th scope="col" id="notify" class="manage-column column-title column-primary sortable desc">
								<?php esc_html_e( 'Notify of sign ups?', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							</th>
							<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
								<?php esc_html_e( 'role', PTO_SIGN_UP_TEXTDOMAIN ); ?>
							</th>
						</tr>
					</thead>
					<tbody>						
						<?php 
							global $post;
							
							$post__user_meta = get_post_meta( $post->ID, "pto_assign_user_administrator", true );
							$notified_users = array();
							$notified_users = get_post_meta( $post->ID, "pto_signup_notified_users", true );													
							if ( !empty( $post__user_meta ) ) {
								foreach( $post__user_meta as $assign_user ) {
									$author_obj = get_user_by( 'id', $assign_user );
									?>
									<tr>							
										<td class="user_td"><?php esc_html_e( $author_obj->user_login ); ?>
											<span class="remove_siggle_user_cpt_sign_ups" style="color: red;cursor:pointer;" id="<?php echo intval( $author_obj->ID ); ?>" post-id="<?php echo intval( $post->ID ); ?>">Remove</span>
										</td>
										<td><?php esc_html_e( $author_obj->display_name ); ?></td>
										<td><?php esc_html_e( $author_obj->user_email ); ?></td>
										<td>
											<input type="checkbox" <?php if ( !empty( $notified_users ) ) { if( in_array( $author_obj->ID, $notified_users ) ) { echo "checked"; } } ?> class="pto-signup-notify-admin" name="notifyuser[]" value="<?php echo intval( $author_obj->ID ); ?>">
										</td>
										<td>
											<?php 											
												$all_roles = ""; 
												foreach ( $author_obj->roles as $key => $value ) {
													$role_name = $value ? wp_roles()->get_names()[ $value ] : '';											
													$all_roles .= $role_name . ", ";
												}
												esc_html_e( substr( $all_roles, 0, -2 ) );
											?>
										</td>
									</tr>
									<?php
								}
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="pto-sign-up-receipts-section pto-admin-setting-user-details">
		<div class="pto-sign-up-receipts-section-title toggle-click">
			<h4><?php esc_html_e( 'Sign Up Receipts', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				<i class="fa fa-info-circle" title="Send a receipt upon successful sign up by a volunteer. Customize the language of your receipts here." aria-hidden="true"></i>
			</h4>
		</div>
		<div class="pto-sign-up-receipts-wrap toggle-box">
			<div class="pto-sign-up-receipts-section-details">
				<div class="pto-sign-up-receipts-section-details-checkbox">
					
					<input type="checkbox" id="volunteer-after-sign-up" name="volunteer-after-sign-up" <?php if ( !empty( $volunteer_after_sign_up ) ) { echo "checked"; } ?>>
					<?php esc_html_e( 'Send a "Receipt" to your volunteer after they sign up to a task or slot', PTO_SIGN_UP_TEXTDOMAIN ); ?>
				</div>
				<div class="pto-sign-up-receipts-section-details-editor receipts-after-signup-show mt-15px">
					<?php
						$content = get_post_meta( $post_id, "volunteer_after_setting", true );
						$defult_wording_volunteers = get_option( 'defult_wording_volunteers' );
						if ( !empty( $content ) ) {
							wp_editor( $content, 'volunteer_after_setting', $settings = array(
								'textarea_name' => 'volunteer_after_setting',
	
								'textarea_rows' => 10
	
							) );
						}
						else {
							wp_editor( $defult_wording_volunteers, 'volunteer_after_setting', $settings = array(
								'textarea_name' => 'volunteer_after_setting',
	
								'textarea_rows' => 10
	
							) );
						}
					?>
				</div>
			</div>
			
		</div>
	</div>
</div>
<script type="text/javascript">
    function task_cpt_add_fields( fileds, ids ) {		
      	let post_id = jQuery("#post_ID").val();
        if(fileds == "pto-sign-up-compelling-visibility-section-details"){
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_custom_fields_checkout',
					nonce: pto_ajax_url.nonce,
					post_id:post_id,
					ids:ids
				},
				success:function( response ) {
					jQuery("#"+fileds).html(response);
					jQuery("#"+fileds).show();
				}
			});
        }
		if(fileds == "pto_sign_ups_custom_fileds_html"){
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_custom_fields_checkout',
					nonce: pto_ajax_url.nonce,
					post_id:post_id,
					ids:ids
				},
				success:function( response ) {
					jQuery("#pto-sign-up-compelling-visibility-section-details").html(response);
					jQuery("#pto-sign-up-compelling-visibility-section-details").show();
				}
            });
        }
    }
</script>