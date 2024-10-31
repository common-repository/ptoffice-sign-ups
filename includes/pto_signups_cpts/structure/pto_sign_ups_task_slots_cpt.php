<?php
if ( !empty( $trash ) ) {
	$status = $trash;
}
else {
	$status = "publish";
}		
$get_task_slots = get_post_meta( $post_id, "pto_signups_task_slots", true );	
$pto_sign_up_occurrence = get_post_meta( $post_id, "pto_sign_up_occurrence", true );
if( empty( $pto_sign_up_occurrence ) ){
	$pto_sign_up_occurrence = array();
}
$publish_count = 0;
$draft_count = 0;
$trash_count = 0;	
if ( !empty( $get_task_slots ) ) {
	?>
	<div class="pto-signup-task-slot-bulk-main">
		<div class="pto-sign-up-compelling-task-section_title pto-task-slots-bulk-action">
			<div class="pto-task-slots-publish-trash">
				<?php
				foreach ( $get_task_slots as $get_task_slot ) {
					$current_status = get_post_status ( $get_task_slot );
					if ( $current_status == "publish" ) {
						$publish_count++;
					}
					if ( $current_status == "draft" ) {
						$draft_count++;
					}
					if ( $current_status == "trash" ) {
						$trash_count++;
					}
				}		
				if ( $publish_count != 0 ) {
					?>
					<a href="#0" data="publish" class="task-slot-publish task-slot-pt-filter <?php if ( $status == "publish" ) { ?> active-filter <?php } ?>">Publish(<?php echo intval( $publish_count ); ?>)</a>
				<?php } if ( $draft_count != 0 ) { ?>
					<a href="#0" data="draft" class="task-slot-draft task-slot-pt-filter <?php if ( $status == "draft" ) { ?> active-filter <?php } ?>" >Draft(<?php echo intval( $draft_count ); ?>)</a>
				<?php } if ( $trash_count != 0 ) { ?>
					<a href="#0" data="trash" class="task-slot-trash task-slot-pt-filter <?php if ( $status == "trash" ) { ?> active-filter <?php } ?>" >Trash(<?php echo intval( $trash_count ); ?>)</a>
				<?php } ?>
			</div>
		</div>
		<div class="pto-signup-task-filter-block-header">
			<div class="pto-signup-task-cpt-filter">
				<div class="pto-signup-task-cpt-filter_action">
					<select id="pto-task-select">
						<option value="">Bulk actions</option>
						<option value="publish">Published</option>
						<option value="trash">Trash</option>
					</select>
					<input type="button" class="button action" id="pto_task_button_filter_apply" ctab="<?php esc_html_e( $status ); ?>"  value="Apply">
				</div>
				<div class="pto-signup-task-cpt-filter_action_month">
					<?php 
					global $wpdb;
					if ( !empty( $month ) ) {
					}
					else {
						$month = "All Dates";
					}
					
					$taskids = implode( ",", $get_task_slots );										
					$result = $wpdb->get_results( "select date_format(post_date, '%M %Y') as yearmonth
						from ".$wpdb->prefix."posts
						where post_type='tasks-signup' and post_status='".esc_sql( $status )."' and ID IN(".intval( $taskids ).")
						group by yearmonth
						order by post_date DESC" );				
					if ( !empty( $result ) ) {
						?>
						<select id="pto-task-select-month">
							<option value="">All Dates</option>
							<?php
							foreach ( $result as $res ) {
								?>
								<option value="<?php esc_html_e( $res->yearmonth ); ?>" <?php if ( $month == $res->yearmonth ) { echo "selected"; } ?>><?php esc_html_e( $res->yearmonth ); ?></option>					
								<?php
							}
							?>
						</select>
						<input type="button" class="button action" ctab="<?php esc_html_e( $status ); ?>" id="pto_task_button_filter_month_apply" type-cpt="pto-task" select="pto-task-select-month" value="Filter">
						<?php
					}									
					?>		
				</div>
			</div>	
		</div>
	</div>
<?php } ?>
<table class="wp-list-table pto-class-shift-data-cpt widefat fixed striped table-view-list posts" id="task-slots-cpt-list-for-single">
	<thead>
		<tr>
			<td width="3%">
				<input type="checkbox" name="checkall_pto_task" id="checkall-pto-task" class="checkbox_check_all">
			</td>
			<td class="manage-column column-title column-primary sorted desc" onclick="sortTable(1)">
				<div class="pto_cpt_get_details_header_title_details">
					<span><?php esc_html_e( 'Task/Slot Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
				</div>
			</td>
			<?php
			if ( !empty( $pto_sign_up_occurrence ) ) {
				if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
					?>
					<td class="manage-column column-title column-primary sorted desc" >
						
						<div class="pto_cpt_get_details_header_title_details">
							
							<span><?php esc_html_e( 'Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
							
						</div>
						
					</td>
					<?php
				}
			}				
			?>
			<td class="manage-column column-title column-primary sorted desc" onclick="sortTable(2)">
				<div class="pto_cpt_get_details_header_title_details">
					<span><?php esc_html_e( 'Description', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
				</div>
			</td>
		
			<td class="manage-column column-title column-primary sorted desc" onclick="sortTable(4)">
				<div class="pto_cpt_get_details_header_title_details_meta pto_cpt_get_details_header_title_details">
					<span><?php esc_html_e( 'Type', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
				</div>
			</td>
			<td class="manage-column column-title column-primary sorted desc" onclick="sortTable(5)">
				<div class="pto_cpt_get_details_header_title_details_meta pto_cpt_get_details_header_title_details">
					<span><?php esc_html_e( 'Filled', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
				</div>
			</td>
		</tr>
	</thead>
	<tbody class="pto-signup-task-slot-list-tbody">
		<?php
		if ( !empty( $get_task_slots ) ) {

			if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
				$recur_dates = array();
				$get_recurrence_data =  get_post_meta( $post_id, "pto_task_recurreence", true );				
				$time_set = get_post_meta( $post_id, "pto_sign_ups_time_set", true );					
				$dayr = "";
				$rtime = "";
				$end_data = array();
				$monthr = "";
				$weekr = "";
				$startd = "";
				$skipped_dates = "";
				$skipped_dates_array = array();                 
				
				if ( !empty( $get_recurrence_data ) ) {
					if ( array_key_exists( "daysofevery", $get_recurrence_data ) ) { 
						$dayr = $get_recurrence_data["daysofevery"]; 
					}
					if ( array_key_exists( "to_sign_up_div_repeate_time", $get_recurrence_data ) ) { 
						$rtime = $get_recurrence_data["to_sign_up_div_repeate_time"]; 
					}
					if ( array_key_exists( "end_data", $get_recurrence_data ) ) {  
						
						$end_data = $get_recurrence_data["end_data"]; 
							// print_r($end_data);
					}
					if ( array_key_exists( "pto_signup_reucr_month", $get_recurrence_data ) ) { 
						$monthr = $get_recurrence_data["pto_signup_reucr_month"]; 
					}
					if ( array_key_exists( "week_days", $get_recurrence_data ) ) { 
						$weekr = $get_recurrence_data["week_days"]; 
					}
					if ( array_key_exists( "start_date", $get_recurrence_data ) ) {
						$startd = $get_recurrence_data['start_date'];
					}
					if ( array_key_exists( "skipped_dates", $get_recurrence_data ) ) {
						$skipped_dates = $get_recurrence_data['skipped_dates'];
					}
				}
				if ( !empty( $skipped_dates ) ) {
					$skipped_dates_array = explode( ",", $skipped_dates );
				}			
				if ( !empty( $time_set ) ) {  
					$opendate = $time_set['opendate']; 
					$closedate = $time_set['closedate'];
					if ( array_key_exists( "on", $end_data ) ) {
						$closedate = $end_data["on"];
					}
					$stdate = new \DateTime( $opendate );
					$etdate = new \DateTime( $closedate );	
					$etdate->modify( '+1 day' );
					if ( !empty( $startd ) ) {
						$stdate = new \DateTime( $startd );
					}										
					if ( !empty( $opendate ) && !empty( $closedate ) && !empty( $dayr ) && !empty( $rtime ) ) {
						if ( $rtime == "Day" ) {																						
							$dayinterval = $dayr . " day";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );						
						}
						if ( $rtime == "Weeks" ) {								
							$recur_dates = array();
							$monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = array();
							$i = 0;
							while( $stdate <= $etdate ) {																		
								$time_stamp = strtotime( $stdate->format( 'Y-m-d' ) );
								$week = date( 'l', $time_stamp );
								if ( $week == "Monday" ) {										
									$monday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Tuesday" ) {										
									$tuesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Wednesday" ) {										
									$wednesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Thursday" ) {										
									$thursday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Friday" ) {										
									$friday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Saturday" ) {										
									$saturday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Sunday" ) {										
									$sunday[ $i ] = $stdate->format( 'Y-m-d' );
								}									
								$stdate->modify( '+1 day' );
								$i++;
								
							}
							$weekr = explode( ",", $weekr );
							$i = 0;
							$mondays = array();
							foreach ( $monday as $mon ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$mondays[] = $mon;
								}
								$i++;									
							}
							$i = 0;
							$tuesdays = array();
							foreach ( $tuesday as $tue ) {
								if ( $i ==  $dayr ) { 
									$i = 0;
								}
								if ( $i == 0 ) {
									$tuesdays[] = $tue;
								}
								$i++;									
							}
							$i = 0;
							$wednesdays = array();
							foreach ( $wednesday as $wed ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {   
									$wednesdays[] = $wed;
								}
								$i++;									
							}
							$i = 0;
							$thursdays = array();
							foreach ( $thursday as $thu ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$thursdays[] = $thu;
								}
								$i++;									
							}
							$i = 0;
							$fridays = array();
							foreach ( $friday as $fri ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$fridays[] = $fri;
								}
								$i++;									
							}
							$i = 0;
							$saturdays = array();
							foreach ( $saturday as $sat ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$saturdays[] = $sat;
								}
								$i++;									
							}
							$i = 0;
							$sundays = array();
							foreach ( $sunday as $sun ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$sundays[] = $sun;
								}
								$i++;									
							}
							if ( in_array( "Monday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $mondays );
							}
							if ( in_array( "Tuesday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $tuesdays );									
							}
							if ( in_array( "Wednesday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $wednesdays );									
							}
							if ( in_array( "Thursday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $thursdays );									
							}
							if ( in_array( "Friday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $fridays );									
							}
							if ( in_array( "Saturday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $saturdays );									
							}
							if ( in_array( "Sunday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $sundays );									
							}							
						}
						if ( $rtime == "Month" ) {
							$dayinterval = $dayr . " month";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
						if ( $rtime == "Year" ) {
							$dayinterval = $dayr . " year";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
					}
				}
				else {						
					$cur_year = date( "Y" );
					$cur_year = $cur_year + 5;
					$today_date = date( 'Y-m-d' );
					$end_date = $cur_year."-12-31";
					if ( array_key_exists( "on", $end_data ) ) {
						$end_date = $end_data["on"];
					}
					
					$stdate = new \DateTime( $today_date );
					$etdate = new \DateTime( $end_date );
					$etdate->modify( '+1 day' );
					if ( !empty( $startd ) ) {
						$stdate = new \DateTime( $startd );
					}
					if ( !empty( $dayr ) && !empty( $rtime ) ) {
						if ( $rtime == "Day" ) {														
							$dayinterval = $dayr . " day";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );						
						}
						if ( $rtime == "Weeks" ) {								
							$recur_dates = array();
							$monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = array();
							$i = 0;
							while( $stdate <= $etdate ) {																		
								$time_stamp = strtotime( $stdate->format( 'Y-m-d' ) );
								$week = date( 'l', $time_stamp );
								if ( $week == "Monday" ) {										
									$monday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Tuesday" ) {										
									$tuesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Wednesday" ) {										
									$wednesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Thursday" ) {										
									$thursday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Friday" ) {										
									$friday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Saturday" ) {										
									$saturday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if ( $week == "Sunday" ) {										
									$sunday[ $i ] = $stdate->format( 'Y-m-d' );
								}							
								$stdate->modify( '+1 day' );
								$i++;								
							}
							$weekr = explode( ",", $weekr );
							$i = 0;
							$mondays = array();
							foreach ( $monday as $mon ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$mondays[] = $mon;
								}
								$i++;
							}
							$i = 0;
							$tuesdays = array();
							foreach ( $tuesday as $tue ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$tuesdays[] = $tue;
								}
								$i++;									
							}
							$i = 0;
							$wednesdays = array();
							foreach ( $wednesday as $wed ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$wednesdays[] = $wed;
								}
								$i++;									
							}
							$i = 0;
							$thursdays = array();
							foreach ( $thursday as $thu ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$thursdays[] = $thu;
								}
								$i++;									
							}
							$i = 0;
							$fridays = array();
							foreach ( $friday as $fri ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$fridays[] = $fri;
								}
								$i++;									
							}
							$i = 0;
							$saturdays = array();
							foreach ( $saturday as $sat ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$saturdays[] = $sat;
								}
								$i++;									
							}
							$i = 0;
							$sundays = array();
							foreach ( $sunday as $sun ) {
								if ( $i ==  $dayr ) {
									$i = 0;
								}
								if ( $i == 0 ) {
									$sundays[] = $sun;
								}
								$i++;									
							}
							if ( in_array( "Monday", $weekr ) ) {
								
								$recur_dates = array_merge( $recur_dates, $mondays );
							}
							if ( in_array( "Tuesday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $tuesdays );									
							}
							if ( in_array( "Wednesday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $wednesdays );									
							}
							if ( in_array( "Thursday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $thursdays );									
							}
							if ( in_array( "Friday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $fridays );									
							}
							if ( in_array( "Saturday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $saturdays );									
							}
							if ( in_array( "Sunday", $weekr ) ) {
								$recur_dates = array_merge( $recur_dates, $sundays );									
							}
						}
						if ( $rtime == "Month" ) {								
							$dayinterval = $dayr . " month";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
						if ( $rtime == "Year" ) {
							$dayinterval = $dayr . " year";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
					}
				}									
				
				foreach( $get_task_slots as $get_task_slot ) {
					$dc = 1;	
					
					foreach( $recur_dates as $rdate ) {	
						$endafter = "";
						if ( array_key_exists( "after", $end_data ) ) {
							$endafter = $end_data["after"];
						}
						if ( !empty( $endafter ) ) {
							if ( $dc > $endafter ) {
								break;
							}
						}
						$dc++;			
						$post_details = get_post( $get_task_slot );			
						$desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
						$get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
						
						$tasktype = "";
						if ( !empty( $get_filed ) ) { 
							if ( array_key_exists( "single", $get_filed ) ) {
								$tasktype = "task/slot";
							}
							else {
								$tasktype = "shift";
							}
						}
						
						
						$monthfilter = get_the_time( 'F Y', $get_task_slot );
						$current_status = get_post_status ( $get_task_slot );
						$sdate = "";
						if ( $rtime == "Weeks" ) {
							$sdate = $rdate;
						}
						else {
							$sdate = $rdate->format( "Y-m-d" );
						}
						if ( $status == $current_status ) {
							if ( $month != "All Dates" ) {
								if ( $monthfilter != $month ) {
									continue;
								}
							}
							if ( in_array( $sdate, $skipped_dates_array ) ) {									
							}
							else {
								$saved_dates = get_post_meta( $get_task_slot, "pto_signup_task_edit_single".$sdate, true );									
								?>
								<tr class="">
									<td>
										<div class="cpt-single-record-details">
											<div class="pto_cpt_get_details_checkbox-cpt">
												<input type="checkbox" name="checkall" class="checkall-pto-task" task-id="<?php echo intval( $get_task_slot ); ?>">
											</div>
										</div>
									</td>
									<td>
										<div class="task_name">
											<?php 
											if ( !empty( $saved_dates ) ) {
												esc_html_e( $saved_dates["post_title"] );
											}
											else {
												esc_html_e( $post_details->post_title ); 
											}
											
											$cpt_custom_filed = $get_task_slot;  
											?>	
											<?php
											if ( $status == "publish" ) {

												?>
												<div class="hook-action">
													<span><a href="javascript:void(window.open('<?php echo esc_url( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit&postsignup=".intval( $post_id ); ?>'))"> Edit All </a> <span class="pto-separator">|</span> </span>
													<span><a href="javascript:void(window.open('<?php echo esc_url( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit&postsignup=".intval( $post_id )."&rdate=".sanitize_text_field( $sdate ); ?>'))"> Edit Single </a> <span class="pto-separator">|</span> </span>
													<span><a href="javascript:void(0)" id="single-task-cpt-delete" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Trash </a> <span class="pto-separator">|</span> </span>
													<?php
													$url = wp_nonce_url( add_query_arg( array(
														'action' => 'pto_sign_up_duplicate_post_as_draft',
														'post' => $cpt_custom_filed,
													) , 'admin.php' ) , basename(__FILE__) , 'duplicate_nonce' );
													$duplicate_url = '<a href="javascript:void(window.open(\''.$url.'\'))" title="Duplicate this item" rel="permalink">Duplicate</a>';                       
													?>
													<span><?php print_r( $duplicate_url ); ?></span>
												</div>
												<?php
											}
											if ( $status == "trash" ) {
												?>
												<div class="hook-action">
													<span><a href="javascript:void(0)" id="single-task-cpt-restore" data="trash" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Restore </a> <span class="pto-separator">|</span> </span>
													<span><a href="javascript:void(0)" id="single-task-cpt-delete-permanent" data="trash" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Delete Permanently </a> </span>
												</div>
												<?php
											}
											if ( $status == "draft" ) {
												?>
												<div class="hook-action">
													<span><a href="javascript:void(window.open('<?php esc_html_e( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit&postsignup=".intval( $post_id ); ?>'))"> Edit </a> <span class="pto-separator">|</span> </span>
													<span><a href="javascript:void(0)" id="single-task-cpt-delete" data="draft" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Trash </a> </span>
												</div>
												<?php
											}
											?>
										</div>
									</td>
									<td>
										<div class="rec-date"><?php esc_html_e( $sdate );  ?></div>
									</td>
									<td>
										<?php 
										
										if ( !empty( $saved_dates ) ) {
											$desc = $saved_dates["tasks_comp_desc"];
											?>
											<div class="desc"><?php print_r( mb_strimwidth( $desc, 0, 156, '...' ) );  ?></div>
										<?php } else{ ?>
											<div class="desc"><?php print_r( mb_strimwidth( $desc, 0, 156, '...' ) );  ?></div>
										<?php } ?>
									</td>
									<td>
										<div class="category"><?php 
										if ( !empty( $saved_dates ) ) {
											if ( array_key_exists( "post_cat", $saved_dates ) ) {
												$term_id = $saved_dates["post_cat"];
												if ( !empty( $term_id ) ) {
													$cat_name = get_term( $term_id )->name;
													esc_html_e( $cat_name );
												}
											}
										}
										else {
											if ( !empty( $cat_name ) ) esc_html_e( substr( $cat_name, 0, -1 ) );
										}
										
									?></div>
								</td>
								<td>
									<div class="type"><?php esc_html_e( $tasktype ); ?></div>
								</td>
								<td>
									<div class="filled">
										<?php						
										if ( !empty( $get_filed ) )	{
											$avdate = $get_task_slot."_".$sdate;
											$get_availability = get_post_meta( $get_task_slot, "signup_task_availability".$avdate, true );
											
											if ( array_key_exists( "single", $get_filed ) ) {
												$total_volantears = $get_filed['single']["how_money_volunteers"];
												$total_volantears_sign_ups = $get_filed['single']["how_money_volunteers_sign_ups"];
												if ( $total_volantears == "" ) {
													$total_volantears = 0;
												}
												if ( $total_volantears_sign_ups == "" ) {
													$total_volantears_sign_ups = 0;
												}								
												$total = $total_volantears;
												if ( !empty( $get_availability ) ) {
													echo "<b>".intval( $get_availability )."/".intval( $total )."</b>";
												} else {
													echo "<b>0/".intval( $total )."</b>";
												}
											} else if ( array_key_exists( "shift", $get_filed ) ) {
												$total_volantears = $get_filed['shift']["volunteers_shift"];
												$total_volantears_sign_ups = $get_filed['shift']["volunteers_shift_times"];
												$shift_meta = $get_filed["shift"];
												$count = 0;
												if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
													$shift_start = $shift_meta['first-shift'];
													$shift_end = $shift_meta['last-end-shift'];
													$shift_min = $shift_meta['how-long-shift'];
													$break_time = $shift_meta['between-shift-minutes'];
													$array_of_time = array ();
													$start_time    = strtotime ( $shift_start ); 
													$end_time      = strtotime ( $shift_end );
													$add_mins  = $shift_min * 60;
													$break_min = $break_time * 60; 
													$i = 0;									
													while ( $start_time <= $end_time ) { 
														$array_of_time[ $i ] = date ( "h:i A", $start_time );
														$start_time += ( $add_mins + $break_min );
														$count++;
														$i++;
													}
												}
												
												if ( $total_volantears == "" ) {
													$total_volantears = 0;
												}
												if ( $total_volantears_sign_ups == "" ) {
													$total_volantears_sign_ups = 0;
												}
												$end_val = strtotime( end( $array_of_time ) );
												if ( $end_val == $end_time ) {
													if ( $count != 0 ) {
														$count = $count - 1;
													}
												}								
												$total = $count * $total_volantears;
												if ( !empty( $get_availability ) ) {
													echo "<b>".intval( $get_availability )."/".intval( $total )."</b>";
												} else {
													echo "<b>0/".intval( $total )."</b>";
												}
											}
										} else {
											echo "<b>0/0</b>";
										}
										?>
									</div>
								</td>
							</tr>
						<?php }
					}
				}
				
			}
			
		}
		else {
			foreach ( $get_task_slots as $get_task_slot ) {				
				
				$post_details = get_post( $get_task_slot );			
				$desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
				$get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
				
				$tasktype = "";
				if ( !empty( $get_filed ) ) { 
					if ( array_key_exists( "single", $get_filed ) ) {
						$tasktype = "task/slot";
					}
					else {
						$tasktype = "shift";
					}
				}
				
				$monthfilter = get_the_time( 'F Y', $get_task_slot );
				$current_status = get_post_status ( $get_task_slot );
				if ( $status == $current_status ) {
					if ( $month != "All Dates" ) {
						if ( $monthfilter != $month ) {
							continue;
						}
					}
					?>
					<tr class="">
						<td>
							<div class="cpt-single-record-details">
								<div class="pto_cpt_get_details_checkbox-cpt">
									<input type="checkbox" name="checkall" class="checkall-pto-task" task-id="<?php echo intval( $get_task_slot ); ?>">
								</div>
							</div>
						</td>
						<td>
							<div class="task_name">
								<?php 
								esc_html_e( $post_details->post_title ); 
								$cpt_custom_filed = $get_task_slot;  
								?>	
								<?php
								if ( $status == "publish" ) {
									?>
									<div class="hook-action">
										<span><a href="javascript:void(window.open('<?php echo esc_url( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit&postsignup=".intval( $post_id ); ?>'))"> Edit </a> <span class="pto-separator">|</span> </span>
										<span><a href="javascript:void(0)" id="single-task-cpt-delete" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Trash </a> <span class="pto-separator">|</span> </span>
										<?php
										$url = wp_nonce_url( add_query_arg( array(
											'action' => 'pto_sign_up_duplicate_post_as_draft',
											'post' => $cpt_custom_filed,
										) , 'admin.php' ) , basename(__FILE__) , 'duplicate_nonce' );
										$duplicate_url = '<a href="javascript:void(window.open(\''.$url.'\'))" title="Duplicate this item" rel="permalink">Duplicate</a>';                       
										?>
										<span><?php print_r( $duplicate_url ); ?></span>
									</div>
									<?php
								}
								if ( $status == "trash" ) {
									?>
									<div class="hook-action">
										<span><a href="javascript:void(0)" id="single-task-cpt-restore" data="trash" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Restore </a> <span class="pto-separator">|</span> </span>
										<span><a href="javascript:void(0)" id="single-task-cpt-delete-permanent" data="trash" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Delete Permanently </a> </span>
									</div>
									<?php
								}
								if ( $status == "draft" ) {
									?>
									<div class="hook-action">
										<span><a href="javascript:void(window.open('<?php esc_html_e( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit&postsignup=".intval( $post_id ); ?>'))"> Edit </a> <span class="pto-separator">|</span> </span>
										<span><a href="javascript:void(0)" id="single-task-cpt-delete" data="draft" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Trash </a> </span>
									</div>
									<?php
								}
								?>
							</div>
						</td>
						<td>
							<div class="desc"><?php print_r( mb_strimwidth( $desc, 0, 156, '...' ) );  ?></div>
						</td>
						
						<td>
							<div class="type"><?php esc_html_e( $tasktype ); ?></div>
						</td>
						<td>
							<div class="filled">
								<?php						
								if ( !empty( $get_filed ) ) {
									
									$get_availability = get_post_meta( $get_task_slot, "signup_task_availability", true );
									
									if ( array_key_exists( "single", $get_filed ) ) {
										$total_volantears = $get_filed['single']["how_money_volunteers"];
										$total_volantears_sign_ups = $get_filed['single']["how_money_volunteers_sign_ups"];
										if ( $total_volantears == "" ) {
											$total_volantears = 0;
										}
										if ( $total_volantears_sign_ups == "" ) {
											$total_volantears_sign_ups = 0;
										}								
										$total = $total_volantears;
										if ( !empty( $get_availability ) ) {
											echo "<b>".intval( $get_availability )."/".intval( $total )."</b>";
										} else {
											echo "<b>0/".intval( $total )."</b>";
										}													
									} else if ( array_key_exists( "shift", $get_filed ) ) {
										$total_volantears = $get_filed['shift']["volunteers_shift"];
										$total_volantears_sign_ups = $get_filed['shift']["volunteers_shift_times"];
										$shift_meta = $get_filed["shift"];
										$count = 0;
										if ( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
											$shift_start = $shift_meta['first-shift'];
											$shift_end = $shift_meta['last-end-shift'];
											$shift_min = $shift_meta['how-long-shift'];
											$break_time = $shift_meta['between-shift-minutes'];
											$array_of_time = array ();
											$start_time = strtotime ( $shift_start ); 
											$end_time = strtotime ( $shift_end );
											$add_mins  = $shift_min * 60;
											$break_min = $break_time * 60; 
											$i = 0;									
											while ( $start_time <= $end_time ) { 
												$array_of_time[ $i ] = date ( "h:i A", $start_time );
												$start_time += ( $add_mins + $break_min );
												$count++;
												$i++;
											}
										}
										
										if ( $total_volantears == "" ) {
											$total_volantears = 0;
										}
										if ( $total_volantears_sign_ups == "" ) {
											$total_volantears_sign_ups = 0;
										}
										$end_val = strtotime( end( $array_of_time ) );
										if ( $end_val == $end_time ) {
											if ( $count != 0 ) {
												$count = $count - 1;
											}
										}								
										$total = $count * $total_volantears;
										if ( !empty( $get_availability ) ) {
											echo "<b>".intval( $get_availability )."/".intval( $total )."</b>";
										}else{
											echo "<b>0/".intval( $total )."</b>";
										}
									}
								} else {
									echo "<b>0/0</b>";
								}
								?>
							</div>
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
<script>
	jQuery(function() {
		if(jQuery("#task-slots-cpt-list-for-single").length != 0){
			jQuery( '#task-slots-cpt-list-for-single' ).sortable({
				update: function( event, ui ) {
					let ids = "";
					let post_id = jQuery("#post_ID").val();
					jQuery("#task-slots-cpt-list-for-single li").each(function() {
						ids += jQuery(this).attr("ids") + ",";
					}); 
					ids = ids.substring(0, ids.length - 1);
					jQuery.ajax({
						method:"POST",
						url:pto_ajax_url.ajax_url,
						data:{
							action:'pto_signup_task_slots_single_dragable',
							nonce: pto_ajax_url.nonce,
							ids:ids,
							post_id:post_id
						},
						success:function( response ) {
						}
					});  
				}
			}).disableSelection();
		}    	
	});
	function sortTable(n) {
		var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
		table = document.getElementById("task-slots-cpt-list-for-single");
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