<?php
$pto_sign_up_occurrence =  get_post_meta( $post->ID, "pto_sign_up_occurrence", true );			
?>
<div class="pto-sign-up-compelling-occurrence">
	<div class="pto-sign-up-compelling-occurrence_section pto-admin-setting-user-details">
		<div class="pto-sign-up-compelling-occurrence-title toggle-click">
			<h4><?php esc_html_e( 'Task/Slot Occurrence', PTO_SIGN_UP_TEXTDOMAIN ); ?> 
			<i class="fa fa-info-circle" title="Choose when this sign up occurs. Recurring sign ups have the same tasks for every day chosen. Great for 'Bring snacks every Monday for 3 weeks' type of sign ups!" aria-hidden="true"></i>
		</h4>
	</div>
	<div class="pto-sign-up-compelling-occurrence-details toggle-box">
		<div class="pto-sign-up-compelling-occurrence-input">
			<input type="radio" name="pto-radios-occurrence" value="occurrence-not-specific" class="occurrence-options" <?php if ( empty( $pto_sign_up_occurrence ) ) { echo "checked"; } elseif ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) ) { echo "checked"; } ?>>
			<label><?php esc_html_e( 'This sign up does not occur on any specific day', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>	
		</div>
		<div class="pto-sign-up-compelling-occurrence-input">
			<input type="radio" name="pto-radios-occurrence" value="occurrence-specific" class="occurrence-options" <?php if ( !empty( $pto_sign_up_occurrence ) ) if ( array_key_exists ( "occurrence-specific", $pto_sign_up_occurrence ) ) { echo "checked"; } ?>>
			<label><?php esc_html_e( 'This sign up occurs on a specific day', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
			<div class="pto-signup-specific-days" style="<?php if ( !empty( $pto_sign_up_occurrence ) ) { if ( array_key_exists( "occurrence-specific", $pto_sign_up_occurrence ) ) { echo "display: block"; } else { echo "display: none"; } } else { echo "display: none"; } ?>">
				<input type="date" id="occurrence-specific-days" name="occurrence-specific-days" value="<?php if ( !empty( $pto_sign_up_occurrence ) ) if ( array_key_exists ( "occurrence-specific", $pto_sign_up_occurrence ) ) { esc_html_e( get_post_meta( $post->ID, "occurrence_specific_days", true ) ); } ?>">
			</div>
		</div>
		
	</div>
</div>
<div class="pto-sign-up-compelling-occurrence_section-task-section pto-admin-setting-user-details">
	<div class="pto-sign-up-compelling-task-section_title toggle-click">
		<div class="pto-sign-up-compelling-task-section_title-task">				
			<h4><?php esc_html_e( 'Tasks/Slots', PTO_SIGN_UP_TEXTDOMAIN ); ?> 
			<i class="fa fa-info-circle" title="Create the tasks or slots you're trying to fill. These tasks/slots will show for each of the days you set above in the 'Occurrence' section" aria-hidden="true"></i>
		</h4>				
	</div>			
</div>
<div class="pto-sign-up-compelling-task-section_list toggle-box" >
	<div class="pto-sign-up-compelling-task-section_title">				
		<div class="pto-sign-up-compelling-task-section_title-task-end">
			<?php
			global $post;
			$post->ID;
			
			?>
			<a class="button button-primary" href="<?php echo esc_url( site_url() ). '/wp-admin/admin.php?page=managevolunteer&sign_ups='.intval( $post->ID ).'';?>" target="_blank"><?php esc_html_e( 'MANAGE VOLUNTEERS', PTO_SIGN_UP_TEXTDOMAIN ); ?></a>
		</div>
		<a class="button button-primary btn_add" href="javascript:void(window.open('<?php echo esc_url( site_url() ).'/wp-admin/post-new.php?post_type=tasks-signup&pid='.intval( $post->ID ) ?>'))"><?php esc_html_e( 'Add New', PTO_SIGN_UP_TEXTDOMAIN ); ?></a>
	</div>			
	<div class="pto-sign-up-compelling-task-section_list" id="pto_sign_up_compelling_task_section_list">
		<?php
		global $post;
		$post_id = $post->ID;
		include "pto_sign_ups_task_slots_cpt.php";
		wp_reset_postdata();
		?>
	</div>
</div>
</div>
</div>
<script type="text/javascript">
	function pto_task_cpt_call( pto_section_ids, task_cpt_ids ) {
		let post_id = jQuery("#post_ID").val();
		if( pto_section_ids == "pto_sign_up_compelling_task_section_list" ) {
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_task_slots_single',
					nonce: pto_ajax_url.nonce,
					post_id:post_id,
					task_cpt_ids:task_cpt_ids
				},
				success:function( response ) {
					jQuery("#"+pto_section_ids).html(response);
					jQuery("#"+pto_section_ids).show();
				}
			});
		}
	}
</script>