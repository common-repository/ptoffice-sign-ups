<?php
if ( isset( $_GET['sign_ups'] ) ) {
	$sign_up_id = intval( $_GET['sign_ups'] );
	$pto_sign_up_occurrence =  get_post_meta( $sign_up_id, "pto_sign_up_occurrence", true );
	$signup_custom_fileds =  get_post_meta( $sign_up_id, "single_task_custom_fields_checkout", true );
	$checkout_fields_sign_up = get_post_meta( $sign_up_id, "checkout_fields_sign_up", true );
	if( empty( $pto_sign_up_occurrence ) ){
		$pto_sign_up_occurrence = array();
	}
	global $wpdb;
	$table_name = $wpdb->prefix . "signup_orders";
	$specific_day = "";
	if ( array_key_exists( "occurrence-specific", $pto_sign_up_occurrence ) ) {
		$specific_day = get_post_meta( $sign_up_id, "occurrence_specific_days", true );
	}
	?>
	<div class="pto-custom-style wp-admin">
		<div class="back-to-page mt-15px">
			<input type='submit' value="Back" class="button button-primary " onclick="window.close()">
		</div>
		<div class="pto-sing-up-manage-volunteers wrap">	
			<h1><?php esc_html_e( 'Manage Volunteers', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			<i class="fa fa-info-circle" title="Add, edit and remove volunteers for this page. Click 'View Receipt' for more options." aria-hidden="true"></i>
		</h1>
		<div class="pto-singup-manage-volunteer">
			<div class="pto-singup-manage-volunteer-heading">
				
				<select class="pto-managev-bulk-action">
					<option value="">Bulk actions</option>
					<option value="delselected">Delete</option>
				</select>
			</div>			
			<div class="pto-singup-manage-volunteer-list">
				<a href="javascript:void(0)" class="open-manage-volunteers button button-primary btn_add">Add New</a>
				<?php 
				$all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE signup_id = ".intval( $sign_up_id )." AND status = 'on' order by ID DESC" );
				
				if ( !empty( $all_user_posts ) ) {
					$tasks = array();
					$duplicate_removed = array();
					$i = 0;
					foreach ( $all_user_posts as $userkey => $post ) {
						$get_user_signup_data = unserialize( $post->order_info );			
						$total_task = count( $get_user_signup_data["task_id".$sign_up_id] );
						for ( $j=0; $j<$total_task; $j++ ) {
							$taskid = $get_user_signup_data["task_id".$sign_up_id][ $j ];
							$taskid_explode = explode( "_", $taskid );
							$tid = $taskid_explode[0];
							$tasks[ $i ] = $tid;
							$i++;
						}						
					}
					$duplicate_removed = array_unique( $tasks );						
					?>
					<table class="wp-list-table widefat fixed striped table-view-list posts" style="display:none;" id="pto-manage-volunteers-export">
						<thead>
							<tr>								
								<th class="custom_user" ><?php esc_html_e( 'First Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_last_name" ><?php esc_html_e( 'Last Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_email" ><?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_task" ><?php esc_html_e( 'Task/Slot', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_task_date" ><?php esc_html_e( 'Task/Slot Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_task_time" ><?php esc_html_e( 'Task/Slot Time', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								
								<?php
								$thcount = 0;
								if ( !empty( $duplicate_removed ) ) {
									foreach ( $duplicate_removed as $task_slot ) {
										$tid = "";
										if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
											$taskid_explode = explode( "_", $task_slot );
											$tid = $taskid_explode[0];
										}
										else {
											$tid = $task_slot;
										}
										$cpt_custom_fileds = get_post_meta( $tid, "single_task_custom_fields", true );
										if ( !empty( $cpt_custom_fileds ) ) {
											foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {	
												$alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
												$custom_field_title = $alternet_title;
												if ( empty( $alternet_title ) ) {
													$custom_field_title = get_the_title( $cpt_custom_filed );
												}
												?>
												<th class="custom_task_fields" ><?php esc_html_e( $custom_field_title, PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
												<?php
												$thcount++;
											}
										}	
									}
								}
								
								if ( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
									foreach ( $signup_custom_fileds as $signup_custom_filed ) {
										$signup_alternet_title = get_post_meta( $signup_custom_filed, "pto_alternate_title", true );
										$signup_custom_field_title = $signup_alternet_title;
										if ( empty( $signup_alternet_title ) ) {
											$signup_custom_field_title = get_the_title( $signup_custom_filed );
										}
										?>
										<th class="custom_task_fields" ><?php esc_html_e( $signup_custom_field_title, PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
										<?php
									}
								}
								
								?>
								<th class="custom_date" ><?php esc_html_e( 'Checkout Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>									
								<th class="custom_hours" ><?php esc_html_e( 'Hours/Points', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$tdcount = 0;
							foreach ( $all_user_posts as $userkey => $post ) {
								$key = $post->user_id;
								$specific_day = $post->checkout_date;
								$get_user_signup_data = unserialize( $post->order_info );
								$task_date = "";
								$task_time = "";
								$total_task = count( $get_user_signup_data["task_id".$sign_up_id] );
								for ( $j=0; $j<$total_task; $j++ ) {
									$ids = $get_user_signup_data["task_id".$sign_up_id][ $j ];
									$task_date = $get_user_signup_data["task_date".$ids][0];
									$task_time = $get_user_signup_data["task_time".$ids][0];
									$tid = "";
									$sdate = "";
									if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
										$taskid_explode = explode( "_", $ids );
										$tid = $taskid_explode[0];
										$sdate = $taskid_explode[1];
									}
									else {
										$tid = $ids;
									}
									$saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
									$shifttime = array();
									$get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
									if ( array_key_exists( "shift", $get_filed ) ) {
										$timekey = "task_time".$ids;
										$tasktime = "";
										if ( array_key_exists( $timekey, $get_user_signup_data ) ) { 
											$tasktime = $get_user_signup_data[ $timekey ][0];
										}
										$shifttimes = explode( ",", $tasktime );
										
										$emptyRemoved = array_filter( $shifttimes );
										$tasktime = implode( ", ", $emptyRemoved );
										$shifttime = explode( ",", $tasktime );
									}
									
									$pto_sign_ups_hour_points = get_post_meta( $tid, "pto_sign_ups_hour_points", true );
									if ( $pto_sign_ups_hour_points == "" ) {
										$pto_sign_ups_hour_points = "N/A";
									}
									$title = get_the_title( $tid );
									if ( !empty( $saved_dates ) ) {
										$title = $saved_dates["post_title"];
									}									
									
									$task_max_val = $get_user_signup_data["task_max".$ids][0];
									$cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
									$user_task_hours_points = get_user_meta( $key, 'user_task_hours_points', true );
									
									for ( $m = 0; $m<$task_max_val; $m++ ) {
										$shtime = "";
										if( !empty( $shifttime ) ) { 
											$shtime = trim( $shifttime[ $m ] ); 
											$task_time = $shtime;
										}
										else { 
											$shtime = "notime"; 												
										} 
										$tdcount = 0;
										
										?>
										<tr>
										<?php
										
										$user = get_user_by( "id", $key );
										$fname = $user->first_name;
										?>
										<td>
										<?php
										
										if ( !empty( $fname ) ) {
											esc_html_e( $fname );
										}
										else{
											esc_html_e( $user->display_name );
										}											
										?>
										</td><td>
										<?php
										esc_html_e( $user->last_name );
										?>
										</td>
										<td>
										<?php
										esc_html_e( $user->user_email );
										?>
										</td>
										<td>
										<?php

											echo esc_html( $title );
										?>
										</td>
										<?php
										if ( !empty( $task_date ) ) {
											?>
												<td>
													<?php echo esc_html( $task_date ); ?>	
												</td>
											<?php
										}else{
											?>
												<td>-</td>
											<?php
										}
										if ( !empty( $task_time ) ) {
											?>
												<td><?php echo esc_html( $task_time ); ?></td>
											<?php
										} else {
											?>
												<td>-</td>
											<?php
										}
										
										if ( !empty( $duplicate_removed ) ) {
											foreach ( $duplicate_removed as $task_slot ) {
												$tid = "";
												if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
													$taskid_explode = explode( "_", $task_slot );
													$tid = $taskid_explode[0];
												}
												else {
													$tid = $task_slot;
												}
												$cpt_custom_fileds = get_post_meta( $tid, "single_task_custom_fields", true );
												if ( !empty( $cpt_custom_fileds ) ) {
													foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {	
														$type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
														if ( $type == "text-area" ) {
															$type = "textarea";
														}
														
														if ( $type == "drop-down" ) {
															$type = "select";
														}
														$customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$ids."_".$sign_up_id."_".$m;
														$customfieldval = "";
														if ( array_key_exists( $customfieldkey, $get_user_signup_data ) ) {	
															if ( $type == "checkbox" ) {		
																$customfieldval = implode( ",", $get_user_signup_data[ $customfieldkey ] );		
															} 		
															else {		
																$customfieldval = $get_user_signup_data[ $customfieldkey ][0];		
															} 
														}
														?>
														 <td>
														<?php
														if ( !empty( $customfieldval ) ) {
															$customfieldval = removeHtmlOrScriptTag($customfieldval);
															esc_html_e( $customfieldval );
														}
														else{
															?>
															-
															<?php
														}
														?>
															</td>
														<?php
														$tdcount++;
													}
												}
												
											}
										}
										if ( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
											foreach( $signup_custom_fileds as $signup_custom_filed ) {
												$signup_type = get_post_meta( $signup_custom_filed, "pto_field_type", true );
												if ( $signup_type == "text-area" ) {
													$signup_type = "textarea";
												}
												if ( $signup_type == "drop-down" ) {
													$signup_type = "select";
												}
												$signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$sign_up_id;
												$signup_customfieldval = "";
												if ( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) {	
													if ( $signup_type == "checkbox" ) {		
														$signup_customfieldval = implode( ",", $get_user_signup_data[ $signup_customfieldkey ] );		
													} 		
													else {  		
														$signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];		
													} 
												}
												?>
												<td>
												<?php
												if ( !empty( $signup_customfieldval ) ) {
													$signup_customfieldval = removeHtmlOrScriptTag($signup_customfieldval);
													 esc_html_e( $signup_customfieldval );
												}
												else {
													?>
													-
													<?php
												}
												?>
												</td>
												<?php
											}
										}
										?>
										<td>
										<?php
											esc_html_e( $specific_day );
										?>
										</td>	
										<td><?php esc_html_e($pto_sign_ups_hour_points); ?></td>
										</tr>
										<?php
									}
								}
							}
							?>
						</tbody>
					</table>
					<?php 
					
				}
				if ( !empty( $all_user_posts ) ) {
					?>

					<table class="wp-list-table widefat fixed striped table-view-list posts" id="pto-manage-volunteers">
						<thead>
							<tr>
								<td class="notexport check-column manage_custom"><input type="checkbox" class="check-all-managev" name="check_all_managev" /></td>
								<th style="display:none;"><?php esc_html_e( 'First Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_user" onclick="sortTable(2)" class="notexport"><?php esc_html_e( 'First Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>							
								<th class="custom_last_name" onclick="sortTable(3)"><?php esc_html_e( 'Last Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_email" onclick="sortTable(4)"><?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_task" onclick="sortTable(5)"><?php esc_html_e( 'Task/Slot', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								<th class="custom_date" onclick="sortTable(7)"><?php esc_html_e( 'Checkout Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
								
							</tr>
						</thead>
						<tbody>
							<?php	
							foreach( $all_user_posts as $userkey => $post ) {
								$key = $post->user_id;
								$specific_day = $post->checkout_date;
								$get_user_signup_data = unserialize( $post->order_info );
								$total_task = count( $get_user_signup_data["task_id".$sign_up_id] );
								for ( $j=0; $j<$total_task; $j++ ) {
									$ids = $get_user_signup_data["task_id".$sign_up_id][ $j ];
									$sdate = "";
									if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
										$taskid_explode = explode( "_", $ids );
										$tid = $taskid_explode[0];
										$sdate = $taskid_explode[1];
									}
									else {
										$tid = $ids;
									}
                                    $status = get_post_status($tid);
                                    
									if( $status == 'publish' ){
										$saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
    									$shifttime = array();
    									$get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
    									if ( array_key_exists( "shift", $get_filed ) ) {
    										$timekey = "task_time".$ids;
    										$tasktime = "";
    										if ( array_key_exists( $timekey, $get_user_signup_data ) ) { 
    											$tasktime = $get_user_signup_data[ $timekey ][0];
    										}
    										$shifttimes = explode( ",", $tasktime );										
    										$emptyRemoved = array_filter( $shifttimes );
    										$tasktime = implode( ", ", $emptyRemoved );
    										$shifttime = explode( ",", $tasktime );
    									}
    									$pto_sign_ups_hour_points = get_post_meta( $tid, "pto_sign_ups_hour_points", true );
    									if ( $pto_sign_ups_hour_points == "" ) {
    										$pto_sign_ups_hour_points = "N/A";
    									}
    									$title = get_the_title( $tid );
    									if ( !empty( $saved_dates ) ) {
    										$title = $saved_dates["post_title"];
    									}
    									$task_max_val = $get_user_signup_data["task_max".$ids][0];
    									$cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
    									$user_task_hours_points = get_user_meta( $key, 'user_task_hours_points', true );
    									for ( $m = 0; $m<$task_max_val; $m++ ) {
    										$shtime = "";
    										if ( !empty( $shifttime ) ) { 
    											$shtime = trim( $shifttime[ $m ] ); 
    										}
    										else { 
    											$shtime = "notime"; 
    										} 
    										?>
    										<tr><td>
    										<input type="checkbox" cnum="<?php echo intval( $m ); ?>" shiftt="<?php esc_html_e( $shtime ); ?>" orderid="<?php echo intval( $post->ID ); ?>" userid="<?php echo intval( $key ); ?>" post-id="<?php echo intval( $ids ); ?>" class="managev-checkbox" name="managev_checkbox[]" />
    										</td>
    										<?php
    
    										$user = get_user_by( "id", $key );
    
    										$fname = sanitize_text_field( $user->first_name );	
    
    										if ( !empty( $fname ) ) {																	
    
    										}
    
    										else {
    
    											$fname = sanitize_text_field( $user->display_name );
    
    										}
    										?>
    										<td style='display:none;'>
	    										<?php										
	    											echo esc_html( $user->first_name );
	    										?>	
    										</td>
    										<td>
	    										<?php
	    											echo esc_html( $fname );
	    										?>
	    										<div class="action-hook-remove_user_manage hook-action">
	    											<span>
	    												<a href="<?php echo esc_url( get_the_permalink( $sign_up_id ) ); ?>?postid=<?php echo intval( $post->ID ); ?>&uid=<?php echo intval( $key ); ?>" target="_blank" class="edit_manage_user_volunters" cnum="<?php echo intval( $m ); ?>" orderid="<?php echo intval( $post->ID ); ?>" userid="<?php echo intval( $key ); ?>" post-id="<?php echo intval( $ids ); ?>" ><?php esc_html_e( 'Edit', PTO_SIGN_UP_TEXTDOMAIN ); ?></a><span class="pto-separator">|</span>
	    											</span>																	
	    											<span>
	    												<a href="javascript:void(0)" class="view_manage_user_volunters" cnum="<?php echo intval( $m ); ?>" userid="<?php echo intval( $key ); ?>" orderid="<?php echo intval( $post->ID ); ?>" post-id="<?php esc_html_e( $ids ); ?>" >View Receipt</a>
	    											</span>
	    										</div>
    										</td>
    										<td>
	    										<?php
	    											echo esc_html( $user->last_name );
	    										?>
    										</td>
    										<td>
    										<?php
    											esc_html_e( $user->user_email );
    										?>
    										</td>
    										<td> <?php echo esc_html( $title ); ?></td>
    										<td>
    											<?php esc_html_e( $specific_day ); ?>
    										</td>
    										</tr>
    										<?php
    									}
									}
								}
							}
							?>
						</tbody>
					</table>
				<?php } ?>
			</div>			
		</div>	
	</div>  
	
	<div id="manage-volunteers-add-new-user" class="pto-modal">
		<div class="pto-modal-content">
			<div class="pto-modal-container-header">
				<span>Add New User</span>
				<span onclick="jQuery('#manage-volunteers-add-new-user').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
			</div>
			<div class="pto-modal-container">				
				<div class="cust-field">
					<label>First Name<span class="required-star">*</span></label>
					<input type="text" id="pto_signup_user_fname"  name="pto_signup_user_fname" value="" required />
				</div>
				<div class="cust-field">
					<label>Last Name<span class="required-star">*</span></label>
					<input type="text" value="" id="pto_signup_user_lname" name="pto_signup_user_lname" required />
				</div>
				<div class="cust-field">
					<label>Email Address<span class="required-star">*</span></label>
					<input type="email" value="" id="pto_signup_user_email" name="pto_signup_user_email" required />
				</div>
			</div>
			<div class="pto-modal-footer">				
				<button class="add-new-volunteer-user button button-primary btn_add">Add User</button>
				<button class="add_new outline_btn delete-btn" onclick="jQuery('#manage-volunteers-add-new-user').removeClass('pto-modal-open');">Cancel</button>
			</div>
		</div>
	</div>
	<div id="manage-volunteers" class="pto-modal">
		<div class="pto-modal-content">
			<div class="pto-modal-container-header">
				<span>Add Volunteers</span>
				<span onclick="jQuery('#manage-volunteers').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
			</div>
			<div class="pto-modal-container">				
				<input type="hidden" name="post_id" id="post_id" value="<?php echo intval( $sign_up_id ); ?>">
				<div class="cust-field">
					<label><?php esc_html_e( 'User Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
					<input type="search" name="searchuserformanage" id="search-user-for-manage">
				</div>
				<div class="search_user_for_manage">
				</div>
				<div class="cust-field" style="display:none;">
					<label><?php esc_html_e( 'Users', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
					<select class="select_users">
						<option value="">Choose User</option>
						<?php 
						$getallusers = get_users();
						foreach ( $getallusers as $user ) {
							?>
							<option value="<?php echo intval( $user->ID ); ?>"><?php esc_html_e( $user->display_name ) ?></option>
							<?php 
						}
						?>
					</select>
				</div>				
				<div class="search-task-pto">
				</div>
				<form class="pto-signup-managev-form" method="post" id="pto-signup-managev-form">
					<input type="hidden" name="signup_id[]" value="<?php echo intval( $sign_up_id ); ?>">
					<div class="selected-task-pto">	  		
					</div>				
				</form>
			</div>
			<div class="pto-modal-footer">
				
				<button class="add-new-user button button-primary btn_add">Add New User</button>
				<a href="<?php echo esc_url( get_the_permalink( $sign_up_id ) ); ?>" target="_blank" class="add-new-volunteer button button-primary btn_add">Add</a>
				<input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#manage-volunteers').removeClass('pto-modal-open');">
			</div>
		</div>
	</div>
	<div id="view-receipt-manage-volunteers" class="pto-modal">
		<div class="pto-modal-content pto-managev-popup-content">
			<div class="pto-modal-container-header">
				<span>Signed Up: date</span>
				<span onclick="jQuery('#view-receipt-manage-volunteers').removeClass('pto-modal-open');" class="w3-button w3-display-topright">Close</span>
			</div>
			<div class="pto-modal-container">
				<div class="pto-managev-content">				
					<div class="pto-managev-left">left data</div>
					<div class="pto-managev-right">right data</div>
				</div>
			</div>
			<div class="pto-modal-footer">
				<a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo esc_html("id"); ?>">Remove From Sign Up</a>
			</div>
		</div>
	</div>
</div>
<script>
	document.title = "Manage Volunteers";
	function sortTable(n) {
		var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
		table = document.getElementById("pto-manage-volunteers");
		switching = true;
	//Set the sorting direction to ascending:
	dir = "asc"; 
	/*Make a loop that will continue until
	no switching has been done:*/
	while (switching) {
		//start by saying: no switching is done:
		switching = false;
		rows = table.rows;
		/*Loop through all table rows (except the
		first, which contains table headers):*/
		for (i = 1; i < (rows.length - 1); i++) {
		//start by saying there should be no switching:
		shouldSwitch = false;
		/*Get the two elements you want to compare,
		one from current row and one from the next:*/
		x = rows[i].getElementsByTagName("TD")[n];
		y = rows[i + 1].getElementsByTagName("TD")[n];
		/*check if the two rows should switch place,
		based on the direction, asc or desc:*/
		if (dir == "asc") {
			if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
			//if so, mark as a switch and break the loop:
			shouldSwitch= true;
			break;
		}
	} else if (dir == "desc") {
		if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
			//if so, mark as a switch and break the loop:
			shouldSwitch = true;
			break;
		}
	}
}
if (shouldSwitch) {
		/*If a switch has been marked, make the switch
		and mark that a switch has been done:*/
		rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
		switching = true;
		//Each time a switch is done, increase this count by 1:
		switchcount ++;      
	} else {
		/*If no switching has been done AND the direction is "asc",
		set the direction to "desc" and run the while loop again.*/
		if (switchcount == 0 && dir == "asc") {
			dir = "desc";
			switching = true;
		}
	}
}
}
</script>
<?php
}
function removeHtmlOrScriptTag($htmlString) {
    $htmlString = strip_tags($htmlString);
    // Create a regular expression pattern to match HTML or script tags
    $pattern = '#<script(.*?)>(.*?)</script>#is';

    // Use preg_replace to remove the matched HTML or script tag from the string
    $result = preg_replace($pattern, '', $htmlString);
    // echo $result;
    return $result;
}
?>