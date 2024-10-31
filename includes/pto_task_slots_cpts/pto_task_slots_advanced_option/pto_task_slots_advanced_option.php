<?php 
$single_post_meta = get_post_meta( $post_ids, "single_tasks_advance_options", true );
$pto_sign_ups_hour_point = get_post_meta( $post_ids, "pto_sign_ups_hour_point", true );
$pto_sign_ups_hour_points = get_post_meta( $post_ids, "pto_sign_ups_hour_points", true );
$pto_sign_ups_custom_fileds = get_post_meta( $post_ids, "pto_sign_ups_custom_fileds", true );	
?>
<div class="pto-signup-task-slots-advanced-option <?php if ( isset( $_GET["rdate"] ) ) { esc_html_e(" pto-single-task-edit"); } ?>" <?php if ( isset( $_GET["rdate"] ) ) { ?> style="display:none;" <?php } ?> >
	<div class="pto-signup-task-slots-advanced-option-input">
		<div class="pto-signup-task-slots-advanced-option-radio">
			<input type="radio" name="advanced_option" class="advanced_option" value="single" <?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) echo "checked"; } else {  esc_html_e("checked"); } ?> >
			<label><?php esc_html_e( 'This is a single task/slot', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
		</div>
		
	</div>
	<div class="pto-signup-task-slots-advanced-option-input">
		<div class="pto-signup-task-slots-advanced-option-radio-single" id="advanced-option-radio-single" style="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) { echo "display: flex"; } else { echo "display: none"; } } ?>">
			<div class="pto-signup-task-slots-volunteers">
				<label><?php esc_html_e( 'How many volunteers are needed for this task/slot?', PTO_SIGN_UP_TEXTDOMAIN ); ?>&nbsp;
					<i class="fa fa-info-circle" title="Set the maximum number of volunteers that can sign up to this task/slot." aria-hidden="true"></i></label>
					<input type="number" name="how_money_volunteers" min=1 max=100 id="how_money_volunteers" value="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) { if ( array_key_exists( "how_money_volunteers", $single_post_meta['single'] ) ) { echo intval( $single_post_meta['single']['how_money_volunteers'] ); } else { echo 1; } } else { echo 1; } } else { echo 1; }  ?>">
				</div>
				<div class="pto-signup-task-slots-volunteers-sign_ups">
					<label><?php esc_html_e( 'How many times can a volunteer sign up for this task/slot?', PTO_SIGN_UP_TEXTDOMAIN ); ?>&nbsp; 
						<i class="fa fa-info-circle" title="Set the maximum number of times a specifc volunteers can sign up to this task/slot." aria-hidden="true"></i></label>
						<input type="number" id="how_money_volunteers_sign_ups" min=1 max=100 name="how_money_volunteers_sign_ups" value="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) { if ( array_key_exists( "how_money_volunteers_sign_ups", $single_post_meta['single'] ) ) { echo intval( $single_post_meta['single']['how_money_volunteers_sign_ups'] ); } else { echo 1; } } else { echo 1; } } else { echo 1; } ?>">
					</div>
					<?php  
					$pid = "";
					if ( isset( $_GET['pid'] ) ) {
						$pid = intval( $_GET['pid'] );
					}
					if ( isset( $_GET['postsignup'] ) ) {
						$pid = intval( $_GET['postsignup'] );
					}
					$get_occur_time = get_post_meta( $pid, 'pto_signup_occur_not_specific', true ); 
					
					if ( !empty( $get_occur_time ) ) {
						if ( $get_occur_time != "occurrence-not-specific" ) {						
							?>
							<div class="pto-signup-task-slots-volunteers-sign_ups_times" id="specific-start-time">
								<label><?php esc_html_e( 'Does this task/slot have a specific start time?', PTO_SIGN_UP_TEXTDOMAIN ); ?>&nbsp;
									<i class="fa fa-info-circle" title="Set a specifc time for this task/slot to be completed. This time is also used to send the reminders to your volunteers if you choose to send reminders." aria-hidden="true"></i></label>
									<input type="time" name="how_money_volunteers_sign_ups-times" id="how_money_volunteers_sign_ups" value="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) if ( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) esc_html_e( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ); } ?>">
								</div>
							<?php } } ?>
						</div>
				</div>
			</div>