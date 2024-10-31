<?php
/**
 * The template for displaying all single posts
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */
get_header();
include "pto_frontend/pto-color-settings.php";

?>
<div id="primary" class="content-area pto-signup-plugin">
	<main id="main" class="site-main wp-admin pto-custom-style">
		<?php	
				// Start the Loop.
		while ( have_posts() ) :
			the_post();
			$post_id = get_the_id();
			$c_user_id = get_current_user_id();
			$user = get_user_by( 'id', $c_user_id );
			$taskcounts = 0;


			$categories_colspan_show =  get_post_meta( $post_id, "categories_colspan_show", true );
			$number_of_slots =  get_post_meta( $post_id, "number_of_slots", true);
			$pto_sign_up_signupdescreption = get_post_meta( $post_id, "pto_sign_up_signupdescreption", true );
			$pto_sign_up_address = get_post_meta( $post_id, "pto_sign_up_address", true );
			$pto_sign_up_occurrence = get_post_meta( $post_id, "pto_sign_up_occurrence", true );				
			if( empty( $pto_sign_up_occurrence ) )
				$pto_sign_up_occurrence = array();

			$specific_day = $recur_dates = "";
			$skipped_dates = "";
			$skipped_dates_array = array(); 				

			if( array_key_exists( "occurrence-specific", $pto_sign_up_occurrence ) ) {

				$specific_day = get_post_meta( $post_id, "occurrence_specific_days", true );
			}				
			if( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {

				$get_recurrence_data =  get_post_meta( $post_id, "pto_task_recurreence", true );
				$time_set = get_post_meta( $post_id, "pto_sign_ups_time_set", true );			
				$dayr = "";
				$rtime = "";
				$end_data = array();
				$monthr = "";
				$weekr = "";
				$startd = "";
				if( !empty( $get_recurrence_data ) ) {
					if( array_key_exists( "daysofevery", $get_recurrence_data ) ) { 
						$dayr = $get_recurrence_data["daysofevery"]; 
					}
					if( array_key_exists( "to_sign_up_div_repeate_time", $get_recurrence_data ) ) { 
						$rtime = $get_recurrence_data["to_sign_up_div_repeate_time"]; 
					}
					if( array_key_exists( "end_data", $get_recurrence_data ) ) { 
						$end_data = $get_recurrence_data["end_data"]; 
					}
					if( array_key_exists( "pto_signup_reucr_month", $get_recurrence_data ) ) { 
						$monthr = $get_recurrence_data["pto_signup_reucr_month"]; 
					}
					if( array_key_exists( "week_days", $get_recurrence_data ) ) { 
						$weekr = $get_recurrence_data["week_days"]; 
					}
					if( array_key_exists( "start_date", $get_recurrence_data ) ) {
						$startd = $get_recurrence_data['start_date'];
					}
					if( array_key_exists( "skipped_dates", $get_recurrence_data ) ) {
						$skipped_dates = $get_recurrence_data['skipped_dates'];
						if( !empty( $skipped_dates ) ) {
							$skipped_dates_array = explode( ",", $skipped_dates );
						}
					}
				}
				if( !empty( $time_set ) ) { 

					$opendate = $time_set['opendate']; 
					$closedate = $time_set['closedate'];
					if( array_key_exists( "on", $end_data ) ) {
						$closedate = $end_data["on"];
					}
					$stdate = new \DateTime( $opendate );
					$etdate = new \DateTime( $closedate );
					$etdate->modify( '+1 day' );	
					if( !empty( $startd ) ) {
						$stdate = new \DateTime( $startd );
					}										
					if( !empty( $opendate ) && !empty( $closedate ) && !empty( $dayr ) && !empty( $rtime ) ) {
						if( $rtime == "Day" ) {																						
							$dayinterval = $dayr . " day";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );	
						}
						if( $rtime == "Weeks" ) {
							$recur_dates = array();
							$monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = array();
							$i = 0;
							while( $stdate <= $etdate ) {																		
								$time_stamp = strtotime( $stdate->format( 'Y-m-d' ) );
								$week = date( 'l', $time_stamp );
								if( $week == "Monday" ) {										
									$monday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Tuesday" ) {										
									$tuesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Wednesday" ) {										
									$wednesday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Thursday" ) {										
									$thursday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Friday" ) {										
									$friday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Saturday" ) {										
									$saturday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								if( $week == "Sunday" ){										
									$sunday[ $i ] = $stdate->format( 'Y-m-d' );
								}
								$stdate->modify( '+1 day' );
								$i++;
							}
							$weekr = explode( ",", $weekr );
							$i = 0;
							$mondays = array();
							foreach( $monday as $mon ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$mondays[] = $mon;
								}
								$i++;									
							}
							$i = 0;
							$tuesdays = array();
							foreach( $tuesday as $tue ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$tuesdays[] = $tue;
								}
								$i++;									
							}
							$i = 0;
							$wednesdays = array();
							foreach( $wednesday as $wed ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$wednesdays[] = $wed;
								}
								$i++;									
							}
							$i = 0;
							$thursdays = array();
							foreach( $thursday as $thu ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$thursdays[] = $thu;
								}
								$i++;									
							}
							$i = 0;
							$fridays = array();
							foreach( $friday as $fri ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$fridays[] = $fri;
								}
								$i++;									
							}
							$i = 0;
							$saturdays = array();
							foreach( $saturday as $sat ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$saturdays[] = $sat;
								}
								$i++;									
							}
							$i = 0;
							$sundays = array();
							foreach( $sunday as $sun ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
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
						if ( $rtime == "Month"  ){
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
							$interval = DateInterval::createFromDateString($dayinterval);
							$recur_dates = new DatePeriod($stdate, $interval, $etdate);						
						}
						if ( $rtime == "Weeks" ) {
							$recur_dates = array();
							$monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = array();
							$i = 0;
							while ( $stdate <= $etdate ) {																		
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
							foreach( $monday as $mon ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$mondays[] = $mon;
								}
								$i++;
							}
							$i = 0;
							$tuesdays = array();
							foreach( $tuesday as $tue ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$tuesdays[] = $tue;
								}
								$i++;									
							}
							$i = 0;
							$wednesdays = array();
							foreach( $wednesday as $wed ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$wednesdays[] = $wed;
								}
								$i++;									
							}
							$i = 0;
							$thursdays = array();
							foreach( $thursday as $thu ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ){
									$thursdays[] = $thu;
								}
								$i++;									
							}
							$i = 0;
							$fridays = array();
							foreach( $friday as $fri ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$fridays[] = $fri;
								}
								$i++;									
							}
							$i = 0;
							$saturdays = array();
							foreach( $saturday as $sat ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
									$saturdays[] = $sat;
								}
								$i++;									
							}
							$i = 0;
							$sundays = array();
							foreach( $sunday as $sun ) {
								if( $i ==  $dayr ) {
									$i = 0;
								}
								if( $i == 0 ) {
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
								$recur_dates = array_merge($recur_dates, $wednesdays);									
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
						if( $rtime == "Month" ) {								
							$dayinterval = $dayr . " month";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
						if( $rtime == "Year" ) {
							$dayinterval = $dayr . " year";
							$interval = DateInterval::createFromDateString( $dayinterval );
							$recur_dates = new DatePeriod( $stdate, $interval, $etdate );
						}
					}
				}									
			}

			$author_id = get_post_field( 'post_author', $post_id );
			$user_info = get_userdata( $author_id );            
			$usermail = $user_info->user_email;
			$get_task_slots = get_post_meta( $post_id, "pto_signups_task_slots", true );
			$check_cat = "";
			$chk_time = "";

			if( !empty( $get_task_slots ) ) {
				foreach( $get_task_slots as $get_task_slot ) {
					$single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
								
					if( !empty( $single_post_meta ) ) { 
						if( array_key_exists( "single", $single_post_meta ) ) {
							if( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) {
								$chk_time .= $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
							}
						}
					}
				}
			}
			
			if ( has_post_thumbnail() ) { 
						$imgurl = get_the_post_thumbnail_url( get_the_ID(), "pto-signup-image" );
						?>
						<div class="pto-signup-background">							
							<img src="<?php echo esc_url( $imgurl ); ?>" />
						</div>
			<?php }

			?>
			<div class="main-signup-lists">
				<div class="container">													
					<?php 
					$chkuid = 0;
					if( isset($_REQUEST["uid"] ) ) {										
						$chkuser = get_userdata( $c_user_id );
						$user_roles = $chkuser->roles;
						$author_id = get_post_field( 'post_author', $post_id );
						$get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true );
						if( empty( $get_user_req_post ) ) { 
							$get_user_req_post = array();										
						}
						if( $c_user_id == $author_id || in_array( $c_user_id , $get_user_req_post ) || in_array( "administrator", $user_roles ) || in_array( "sign_up_plugin_administrators", $user_roles ) ) {
							$c_user_id = intval($_REQUEST["uid"]);
						}
						else{
							$chkuid = 1;
						}
					} 
					 ?>
					<div class="pto-signup-details">
						<div class="pto-signup-details-left">
							<div class="pto-signup-title">
								<h2 class="signup-title" postid="<?php echo intval( $post_id ); ?>"><?php  esc_html_e( get_the_title( $post_id ) ); ?></h2>
							</div>
							<p class="pto-signup-desc">
								<?php print_r( $pto_sign_up_signupdescreption ); ?>
							</p>
						</div>
						<div class="pto-signup-details-right">
							<div class="pto-signup-date">
								<?php if ( !empty( $specific_day ) ) {  esc_html_e( date( "l, F jS", strtotime( $specific_day ) ) ); } ?>
							</div>
							<div class="pto-signup-location">
								<?php if ( !empty( $pto_sign_up_address['address1'] ) ) {  esc_html_e( $pto_sign_up_address['address1'] ) . ","; } ?>
								<?php if ( !empty($pto_sign_up_address['address2'] ) ) {  esc_html_e( $pto_sign_up_address['address2'] ) . ", "; } ?>
								<?php if ( !empty( $pto_sign_up_address['state'] ) ) {  esc_html_e( $pto_sign_up_address['state'] ) . ", "; } ?>
								<?php if ( !empty( $pto_sign_up_address['city'] ) ) {  esc_html_e( $pto_sign_up_address['city'] ) . ", "; } ?>
								<?php if ( !empty( $pto_sign_up_address['postalcode'] ) ) {  esc_html_e( $pto_sign_up_address['postalcode'] ); } ?>						
							</div>
						</div>
					</div>
					<form class="pto-signup-frontend" method="post" id="pto-signup-frontend" >
						<?php 
						if ( isset( $_REQUEST["postid"] ) ) {
							$editid = intval($_REQUEST["postid"]);
							global $wpdb;
							$table_name = $wpdb->prefix . "signup_orders";
							$get_signup_data = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE ID = ".intval( $editid ) );
							$taskids_array = array();
							$tasktime_array = array();
							$taskmax_array = array();
							$selected_date_array = array();
							foreach ( $get_signup_data as $key => $post ):
								$get_user_signup_data = unserialize( $post->order_info );											
								if ( !empty( $get_user_signup_data ) ) {												 
									$signupid = $get_user_signup_data["signup_id"][0];            
									$total_task = count( $get_user_signup_data["task_id".$signupid] );
									for ( $j=0; $j<$total_task; $j++ ) { 
										$taskid = $get_user_signup_data["task_id".$signupid][$j];
										if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
											$taskid_explode = explode( "_", $taskid );
											$tid = $taskid_explode[0];
											$selected_date_array[$j] = $taskid_explode[1]; 
										}
										$task_hour_points = $get_user_signup_data["task_hours_points".$taskid][0];
										$task_max_value = $get_user_signup_data["task_max".$taskid][0];
										$task_time = $get_user_signup_data["task_time".$taskid][0];
										$taskids_array[$j] = $taskid;
										$tasktime_array[$taskid] = explode( ",", $task_time );
										$taskmax_array[$taskid] = $task_max_value;
									}												
								}
							endforeach;
							?>
							<div class="signup-step1">
								<?php 
								if( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) { ?>
									<div class="multiple-days-signup"> 
										These tasks/slots are offered on multiple days. Please choose which days you'd like to sign up for:
										<div class="cust-dropdown">
											<span class="dropdown-click-link choose-dates">Choose dates:</span>
											<?php 
											if( !empty( $recur_dates ) ) { 
												$endafter = "";
												if( array_key_exists( "after", $end_data ) ) {
													$endafter = $end_data["after"];
												}
												?>
												<div class="multiple-dates cust-dropdown-contant">
													<ul class="checkbox-list">
														<li>
															<input type="checkbox" name="all_dates" value="All Dates" id="all-dates" />
															<label>All Dates</label>
														</li>
														<?php 
														$i = 1; 
														foreach ( $recur_dates as $dt ) { 
															if ( !empty( $endafter ) ) {
																if( $i > $endafter ) {
																	break;
																}
															}
															$sdate = "";
															if ( $rtime == "Weeks" ) {
																$sdate = $dt;
															}
															else{
																$sdate = $dt->format( "Y-m-d" );
															}
															if ( in_array( $sdate, $skipped_dates_array ) ) { }
																else {
																	?>
																	<li>
																		<input type="checkbox" <?php if ( in_array( $sdate, $selected_date_array ) ) { echo sanitize_text_field("checked"); } ?> name="multiple_dates_signup[]" class="multiple-dates-signup" value="<?php if ( $rtime == "Weeks" ) {  esc_html_e( $dt ); } else {  esc_html_e( $dt->format( "Y-m-d" ) ); } ?>" />
																		<label><?php if( $rtime == "Weeks" ) {  esc_html_e( date( "l, F jS Y", strtotime( $dt ) ) ); } else { 
																		 esc_html_e( $dt->format( "l, F jS Y" ) ); } ?></label>	
																	</li>
																<?php }
																$i++; 
															} ?>
															<li>
																<input type="button" value="Done" uid="<?php echo intval( $c_user_id ); ?>" orderid="<?php echo intval( $editid ); ?>" name="btn_date_done" class="btn-date-done pto-signup-btn-text-color pto-signup-btn-background-color" id="btn-date-done" />
															</li>
														</ul>
													</div>														
													<?php 
												} 
												else { 
													?>
													<div class="multiple-dates">No date available.</div>	
													<?php 
												} 
												?>
											</div>										
										</div>
										<script>
											jQuery(document).ready(function($) {
												jQuery("#btn-date-done").trigger("click");
											});
										</script>
										<?php 
									} 
									?>								
									<div class="pto-signup-task-list" <?php if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) { ?> style="display:none;" <?php } ?> >
										<div class="table-responsive">													
											<table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
												<thead>
													<tr>    
														<th onclick="sortTable(0)">Task Name</th>
														<th onclick="sortTable(2)" <?php if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) || empty( $chk_time ) ){ ?> style="display:none;" <?php } ?>>Time</th>
														<?php if ( !empty( $check_cat ) ) { ?> 
															<th onclick="sortTable(3)">Category</th>
														<?php } ?>
														<th>Availability</th>
														<th>Sign Up</th>						
													</tr>
												</thead>
												<tbody class="pto-signup-tasks">
													<?php

													if( !empty( $get_task_slots ) ) {
														foreach( $get_task_slots as $get_task_slot ) {
															$filled = 0;
															$post_details = get_post( $get_task_slot );
															$single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );																		
															$desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
															$get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );														
															
															$hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
															$hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );																		
															
															$current_status = get_post_status ( $get_task_slot );
															if ( $current_status == "publish" ) {
																?>
																<tr>  
																	<td>
																		<?php 
																		esc_html_e( $post_details->post_title ); 
																		if ( !empty( $desc ) ) { ?>
																			<a href="#0" class="pto-task-desc" >details</a>
																			<div class="pto-task-content pto-modal" style="display:none;">
																				<div class="pto-modal-content">
																					<div class="pto-modal-container-header">
																						<span><?php esc_html_e( 'Task Description', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
																						<span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
																					</div>
																					<div class="pto-modal-container">
																						<div class="pto-show-task-desc"><?php print_r( $desc ); ?></div>
																					</div>
																					<div class="pto-modal-footer">
																						<input type="button" name="ok" value="Ok" onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="task-recurrence_add_new outline_btn button button-primary">
																					</div>
																				</div>
																			</div>
																			<?php
																		}
																		?>
																	</td>																																
																	<td <?php if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) || empty( $chk_time ) ) { ?> style="display:none;" <?php } ?>>
																		<?php 
																		if ( !empty( $single_post_meta ) ) { 
																			if ( array_key_exists( "single", $single_post_meta ) ) {
																				if ( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) {
																					if ( !empty( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ) ) {
																						esc_html_e( date( "H:i a", strtotime( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ) ) );
																					}															
																				}
																			}																						
																		} 
																		?>												 
																	</td>
																	<?php if ( !empty( $check_cat ) ) { ?>  
																		<td><?php if( !empty( $cat_name ) ) esc_html_e( substr( $cat_name, 0, -1 ) ); ?></td>
																	<?php } ?>
																	<td>
																		<input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php if ( !empty( $specific_day ) ) { esc_html_e( $specific_day ); } ?>"  />
																		<input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) if ( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) esc_html_e( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ); } ?>"  />
																		<input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval( $hourspoint ); ?>" />
																		<?php														
																		if( !empty( $get_filed ) ) {
																			$get_availability = get_post_meta( $get_task_slot, "signup_task_availability", true );
																			$diff = 0;
																			if( array_key_exists( "single", $get_filed ) ) {
																				$total_volantears = $get_filed['single']["how_money_volunteers"];
																				$total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
																				if ( $total_volantears == "" ) {
																					$total_volantears = 0;
																				}
																				if ($total_volantears_sign_ups == "") {
																					$total_volantears_sign_ups = 0;
																				}
																				$total = $total_volantears;
																				if ( !empty( $get_availability ) ) {
																					?>
																					<b>
																					<?php echo intval( $get_availability ); ?>/<?php echo intval( $total ); ?>
																					</b>
																					<?php
																					if( $get_availability == $total ) {
																						$filled = 1;
																						?>
																						<span> filled</span>
																						<?php
																						
																					} 
																					else {
																						$diff = $total - $get_availability;
																					}
																				}
																				else {
																					?>
																						<b>0/<?php echo intval( $total ); ?>
																						</b>
																					<?php
																				}
																			}
																			
																		}else{
																			?><b>0/0</b><?php
																		}

																		?>
																	</td>
																	<td>
																		<?php 
																		if ( in_array( $get_task_slot, $taskids_array ) ) {
																			?>
																			<div class="sign-up-task-shift-block">
																				<?php
																				$task_max_value = $taskmax_array[ $get_task_slot ];
																				$shift_meta_time = array();
																				$all_shifts = "";
																				$current_user_shift = array();
																				$get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time', true ); 
																				if ( !empty( $get_shift_time ) ) {
																					if ( array_key_exists( $c_user_id, $get_shift_time ) ) {
																						$current_user_shift = explode( ",", $get_shift_time[ $c_user_id ] );
																					}
																					foreach ( $get_shift_time as $uid ) {																										
																						$all_shifts .= $uid;
																					}
																					$shift_meta_time = explode( ",", $all_shifts );																									
																				}
																				$usermax = 0;
																				$ediff = 0;
																				if ( $c_user_id != 0 ) {																									
																					$get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );																																									
																					
																					if( !empty( $get_max_user_task_signup ) ) {
																						$max_key = $post_id."_".$get_task_slot;																
																						if( array_key_exists( $max_key, $get_max_user_task_signup ) ) {
																							$usermax = $get_max_user_task_signup[ $max_key ];																											
																							$ediff = $total_volantears_sign_ups - $usermax;
																							
																							if( $diff < $ediff ) {
																								$ediff = $diff;
																							}																																													
																						}
																					}																
																				}
																				$max = 1;																								
																				if ( $filled != 1 ) {
																					$max = $task_max_value + $ediff;
																				}	
																				if ( $filled == 1 ) {
																					$max = $task_max_value;
																				}

																				// $max = $diff;																
																				// $max = intval($total) - intval($get_availability);						
																																				
																				?>
																				<input type="checkbox" class="sign-up-task"  checked  <?php if ( $max != 1 || array_key_exists( "shift", $get_filed ) ) { ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval( $post_details->ID ); ?>" />
																				<input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value="<?php echo intval( $post_details->ID ); ?>" />
																				<?php 
																				if( $max != 1 && array_key_exists( "single", $get_filed ) ) { 																									
																					?>
																					<select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
																						<?php 																	
																						for ( $i=0; $i<=$max; $i++ ) { ?>
																							<option <?php if ( $task_max_value == $i ) { esc_html_e("selected"); } ?> value="<?php echo intval( $i ); ?>"><?php echo intval( $i ); ?></option>
																							<?php
																						}
																						?>
																					</select>
																					<input type="number" name="pto_signup_task_max1[]" min="1" max="<?php echo intval( $max ); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
																				<?php  } else { ?>														
																					<select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
																						<option value="<?php if ( array_key_exists( "shift", $single_post_meta ) ) { echo intval( $task_max_value ); } else { echo intval("1"); } ?>">1</option>
																					</select>
																					<input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval( $max ); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
																				<?php } 
																				$tasktimes = array();
																				if ( array_key_exists( "shift", $single_post_meta ) ) {
																					$shift_meta = $single_post_meta["shift"];
																					if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
																						$shift_count = count( $array_of_time );								
																						$tasktimes = $tasktime_array[ $get_task_slot ];
																						?>
																						<div class="shift-checkbox-list" >
																							<span class="span-choose-shift">Choose a shift:</span>
																							<ul class="checkbox-list">	
																								<li>
																									<input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
																									<label class="choose-shift">Choose a shift:</label>
																								</li>
																								<?php																		
																								$i = 0;																		
																								while ( $i < $shift_count ) { 
																									$shift_endtime = date ( "h:i A", ( strtotime( $array_of_time[ $i ] ) + $add_mins ) );																																					
																									if ( !empty( $shift_meta_time ) ) {
																										if ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears == 1 ) {
																											if ( !empty( $current_user_shift ) && in_array( $array_of_time[ $i ], $current_user_shift ) && !empty( $tasktime_array ) && in_array( $array_of_time[ $i ], $tasktimes ) ) {
																												?>
																												<li>
																													<input type="checkbox" class="task-shift" checked name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																													<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																												</li>
																												<?php
																											}
																											else {
																												?>
																												<li>
																													<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																													<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																												</li>
																												<?php
																											}
																										}	
																										elseif ( strtotime( $array_of_time[ $i ] ) == $end_time ) {
																										}																			
																										elseif ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears > 1 ) {
																											if ( !empty( $current_user_shift ) && in_array( $array_of_time[ $i ], $current_user_shift ) && !empty( $tasktime_array ) && in_array( $array_of_time[ $i ], $tasktimes ) ) {
																												?>
																												<li>
																													<input type="checkbox" class="task-shift" checked name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																													<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																												</li>
																												<?php
																											}
																											else{
																												$count_values = array_count_values( $shift_meta_time );
																												$this_shift_count = $count_values[ $array_of_time[ $i ] ];
																												if ( $this_shift_count == $total_volantears ) {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																												elseif ( !empty( $current_user_shift ) && in_array( $array_of_time[ $i ], $current_user_shift ) ) {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																												else {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																											}
																										}																				
																										else{ 
																											?>
																											<li>
																												<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																												<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																											</li>
																											<?php
																										}																				
																									}
																									elseif ( strtotime( $array_of_time[ $i ]) == $end_time ) {
																									}
																									else {
																										?>
																										<li>
																											<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																											<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																										</li>
																										<?php
																									}																		
																									$i++; 
																								}	
																								?>
																							</ul>
																						</div>															
																						<?php	
																					}
																				} 
																				else { 
																				} ?>
																				<input type="hidden" value="<?php esc_html_e( implode( ",", $tasktimes ) ); ?>" name="task_shift_hidden[]" class="task-shift-hidden" />										
																			</div>
																		<?php } else {   ?>

																			<div class="sign-up-task-shift-block">
																				<?php
																				$shift_meta_time = array();
																				$all_shifts = "";
																				$current_user_shift = array();
																				$get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time', true ); 
																				if( !empty( $get_shift_time ) ) {																									
																					if( array_key_exists( $c_user_id, $get_shift_time ) ) {
																						$current_user_shift = explode( ",", $get_shift_time[ $c_user_id ] );
																					}
																					foreach ( $get_shift_time as $uid ) {
																						$all_shifts .= $uid;
																					}
																					$shift_meta_time = explode( ",", $all_shifts );
																				}														
																				$usermax = 0;
																				if ( $c_user_id != 0 ) {
																					$get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );																
																					if ( !empty( $get_max_user_task_signup ) ) {
																						$max_key = $post_id."_".$get_task_slot;																
																						if ( array_key_exists( $max_key, $get_max_user_task_signup ) ) {
																							$usermax = $get_max_user_task_signup[ $max_key ];	
																							if ( $diff == 1 ) {
																							}
																							else {
																								$diff = $total_volantears_sign_ups - $usermax;
																							}
																							if ( $usermax == $total_volantears_sign_ups ) {
																								$diff = 0;
																							}																			
																						}
																					}																
																				}
																				$max = 1;
																				// $max = intval($total) - intval($get_availability);

																				if ( $diff != 0 && $diff < $total_volantears_sign_ups ) {
																					$max = $diff;
																				}
																				else {
																					$max = $total_volantears_sign_ups;
																				}
																				if ( $total_volantears_sign_ups > $total ) {
																					$max = $total;
																				}
																				if( !empty( $get_availability ) && $diff == 0 ) {
																					$max = 0;
																				}	
																				$t = intval($total) - intval($get_availability);
																				if( $t <=  $max ){
																					$max = $t;
																				}


																				?>
																				<input type="checkbox" class="sign-up-task" <?php if( $filled == 1 || $max != 1 || ( $max == 1 && array_key_exists( "shift", $get_filed ) ) ) { ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval( $post_details->ID ); ?>" />
																				<input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value="<?php //echo intval( $post_details->ID ); ?>"  />
																				<?php if ( $total_volantears_sign_ups != 1 && array_key_exists( "single", $get_filed ) && $total_volantears_sign_ups != $usermax ) { 
																					if ( $filled == 1 || $max == 1 ) {
																						?>														
																						<select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
																							
																							<option value="1">1</option>
																						</select>
																						<input type="number" name="pto_signup_task_max1[]" min=1 max=<?php echo intval( $max ); ?> value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
																						<?php
																					}
																					else {
																						?>
																						<select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
																							<?php 																	
																							for ( $i=0; $i<=$max; $i++ ) { ?>
																								<option value="<?php echo intval( $i ); ?>"><?php echo intval( $i ); ?></option>
																								<?php
																							}
																							?>
																						</select>
																						<input type="number" name="pto_signup_task_max1[]" min=1 max=<?php echo intval( $max ); ?> value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
																					<?php } } else { ?>														
																						<select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
																							<option value="1">1</option>
																						</select>
																						<input type="number" name="pto_signup_task_max1[]" value="" max=<?php echo intval( $max ); ?>  style="visibility:hidden;" class="pto-singup-task-max-number" />
																					<?php } 
																					if ( array_key_exists( "shift", $single_post_meta ) ) {
																						$shift_meta = $single_post_meta["shift"];
																						if ( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
																							$shift_count = count( $array_of_time );	
																							?>
																							<div class="shift-checkbox-list" <?php if ( $filled == 1 ) { ?> style="visibility:hidden;" <?php } ?>>
																								<span class="span-choose-shift">Choose a shift:</span>
																								<ul class="checkbox-list">	
																									<li>
																										<input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
																										<label class="choose-shift">Choose a shift:</label>
																									</li>
																									<?php																		
																									$i = 0;																		
																									while ( $i < $shift_count ) { 
																										$shift_endtime = date ( "h:i A", ( strtotime( $array_of_time[ $i ] ) + $add_mins ) );																																					
																										if ( !empty( $shift_meta_time ) ) {
																											if ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears == 1 ) {
																												?>
																												<li>
																													<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																													<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																												</li>
																												<?php
																											}	
																											elseif ( strtotime( $array_of_time[ $i ] ) == $end_time ) {
																											}																			
																											elseif ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears > 1 ) {
																												$count_values = array_count_values( $shift_meta_time );
																												$this_shift_count = $count_values[ $array_of_time[ $i ] ];
																												if ( $this_shift_count == $total_volantears ) {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																												elseif ( !empty( $current_user_shift ) && in_array( $array_of_time[ $i ], $current_user_shift ) ) {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																												else {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}
																											}																				
																											else { 
																												?>
																												<li>
																													<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																													<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																												</li>
																												<?php
																											}																				
																										}
																										elseif ( strtotime( $array_of_time[ $i ] ) == $end_time ) {
																										}
																										else {
																											?>
																											<li>
																												<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																												<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																											</li>

																											<?php
																										}																			
																										$i++; 	
																									}	
																									?>
																								</ul>
																							</div>															
																							<?php	
																						}
																					} else { 
																					} ?>
																					<input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />										
																				</div>
																			<?php } ?>																						
																		</td>
																	</tr>
																	<?php 
																} 
															} 
														} else {
															?>
															<tr class='sign_up_task'><td colspan='3'>No task/slot available.</td></tr>
															<?php
														} ?>
													</tbody>

												</table>

											</div>						
										</div>

										<input type="hidden" class="pto-sign-up" id="pto-sign-up" name="pto_sign_up" value="<?php echo intval( $post_id ); ?>" />
										<input type="hidden" class="pto-sign-up-edit" id="pto-sign-up-edit" name="pto_sign_up_edit" value="<?php esc_html_e( $editid ); ?>" />
										<?php
										$pto_checkout_sign_ups = get_option('pto_checkout_sign_ups'); 
										$url = "";
										if ( !empty( $pto_checkout_sign_ups ) ) {
											$url = get_the_permalink( $pto_checkout_sign_ups );
										}
										else {
											$url = site_url()."/pto-signup-checkout";
										}
										if( isset( $_REQUEST["uid"] ) ) {
											$url .= "?uid=". intval($_REQUEST["uid"]);
										}									
										?>

										<button class="signup-tasks-nextBtn button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn" data-url="<?php echo esc_url( $url ); ?>" type="button" >Next</button>
									</div>
									<?php
								} 
								else {									
									?>
									<div class="signup-step1">
										<div class="pto-signup-owner">
											<?php 
											$contact_organizer =  get_post_meta( $post_id, "contact_organizer", true );
											if ( !empty( $contact_organizer ) ) {
												?>
												<a class="front-primary-btn pto-signup-btn-background-color pto-signup-btn-text-color" href="mailto:<?php esc_html_e( $usermail ); ?>">Contact Sign Up owner</a>
											<?php } 
											$volunteer_names = get_post_meta( $post_id, 'volunteer_names', true );
											if ( !empty( $volunteer_names ) ) {
												?>
												<a href="#0" class="view-volunteers pto-signup-btn-text-color front-primary-btn pto-signup-btn-background-color" data-id="<?php echo intval( $post_id ); ?>">view volunteers</a>
											<?php } ?>
											<select class="pto-signup-task-sorting" name="task_sorting">
												<option value="Sort by">Sort by</option>
												<option value="task">Task/Slot</option>
												<?php 
												if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) ) { 
												}
												else { 
													if( !empty( $chk_time ) ) {
														?>
														<option value="time">Time</option>
													<?php }
												} 
												if( !empty( $check_cat ) ) {
													?>
													<option value="category">Category</option>
												<?php } ?>
											</select>


											<?php 
												$taskcount = 0;
										        if(array_key_exists( "pto_signup_tasks_cart", $_SESSION )){
										            $get_user_cart = filter_var_array($_SESSION['pto_signup_tasks_cart']);
										            if(!empty($get_user_cart)){
										                foreach($get_user_cart as $key => $val){
										                    $count_tasks = count($val["sign_up_task"]);
										                    $taskcount += $count_tasks;
										                }
										            }
										        }
										        $pto_checkout_sign_ups = get_option('pto_checkout_sign_ups'); 
										        $url = "";
										        
										        if(!empty($pto_checkout_sign_ups)){
										            $url = get_the_permalink($pto_checkout_sign_ups);
										        }
										        else{
										            $url = site_url()."/pto-signup-checkout";
										        }
										    	?>
				                                <div  class='pto-cart-signup' >
				                                    <a  href='<?php echo esc_url( $url ); ?>' class='pto-cart-tasks-count'><i class="fa fa-hand-pointer-o"></i><?php if( $taskcount != 0 ){  ?><span><?php echo intval($taskcount); ?></span> <?php } ?></a>
				                                </div>
					                            <?php 
											?>

										</div>
										<div class="pto-singup-view-volunteer-list"></div>
										<?php 
										if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) { ?>
											<div class="multiple-days-signup"> 
												These tasks/slots are offered on multiple days. Please choose which days you'd like to sign up for:
												<div class="cust-dropdown">
													<span class="dropdown-click-link choose-dates">Choose dates:</span>
													<?php 
													if ( !empty( $recur_dates ) ) { 
														$endafter = "";
														if ( array_key_exists( "after", $end_data ) ) {
															$endafter = $end_data["after"];
														}
														?>
														<div class="multiple-dates cust-dropdown-contant">
															<ul class="checkbox-list">
																<li>
																	<input type="checkbox" name="all_dates" value="All Dates" id="all-dates" />
																	<label>All Dates</label>
																</li>
																<?php 
																$i = 1; 
																foreach ( $recur_dates as $dt ) { 
																	if ( !empty( $endafter ) ) {
																		if( $i > $endafter ) {
																			break;
																		}
																	}
																	$sdate = "";
																	if ( $rtime == "Weeks" ) {
																		$sdate = $dt;
																	}
																	else {
																		$sdate = $dt->format( "Y-m-d" );
																	}
																	if ( in_array( $sdate, $skipped_dates_array ) ) { }
																		else {
																			?>
																			<li>
																				<input type="checkbox" name="multiple_dates_signup[]" class="multiple-dates-signup" value="<?php if ( $rtime == "Weeks" ) { esc_html_e( $dt ); } else { esc_html_e( $dt->format( "Y-m-d" ) ); } ?>" />
																				<label><?php if ( $rtime == "Weeks" ) { esc_html_e( date( "l, F jS Y", strtotime( $dt ) ) ); } else { esc_html_e( $dt->format( "l, F jS Y" ) ); } ?></label>	
																			</li>
																		<?php }
																		$i++; 
																	} ?>
																	<li>
																		<input type="button" value="Done" uid="<?php echo intval( $c_user_id ); ?>" name="btn_date_done" class="btn-date-done pto-signup-btn-text-color pto-signup-btn-background-color" id="btn-date-done" />
																	</li>
																</ul>
															</div>														
															<?php 
														} 
														else { 
															?>
															<div class="multiple-dates">No date available.</div>	
															<?php 
														} 
														?>
													</div>										
												</div>
												<?php 
											} 
											?>								
											<div class="pto-signup-task-list" <?php if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) { ?> style="display:none;" <?php } ?> >
												<div class="table-responsive">
													<?php if ( $chkuid == 0 ) {  ?>
														<table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
															<thead>
																<tr>    
																	<th onclick="sortTable(0)">Task Name</th> 																							
																	<th onclick="sortTable(2)" <?php if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) || empty( $chk_time ) ) { ?> style="display:none;" <?php } ?>>Time</th>
																	<?php if ( !empty( $check_cat ) ) { ?> 
																		<th onclick="sortTable(3)">Category</th>
																	<?php } ?>
																	<th>Availability</th>
																	<th>Sign Up</th>						
																</tr>
															</thead>
															<tbody class="pto-signup-tasks">
																<?php
																if( !empty( $get_task_slots ) ) {
																	foreach( $get_task_slots as $get_task_slot ) {
																		$filled = 0;
																		$post_details = get_post( $get_task_slot );														
																		$single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );														
																		$desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
																		$get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );															
																	
																		$hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
																		$hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );														
																		
																		$current_status = get_post_status ( $get_task_slot );
																		if ( $current_status == "publish" ) {
																			$taskcounts++;
																			?>
																			<tr <?php if( !empty( $categories_colspan_show ) && !empty( $number_of_slots ) && $taskcounts > $number_of_slots ) { ?> class="extra-tr" <?php } ?> >  
																				<td>
																					<?php 
																					esc_html_e( $post_details->post_title ); 
																					if( !empty( $desc ) ) { ?>
																						<a href="#0" class="pto-task-desc" >details</a>
																						<div class="pto-task-content pto-modal" style="display:none;">
																							<div class="pto-modal-content">
																								<div class="pto-modal-container-header">
																									<span><?php esc_html_e( 'Task Description', PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
																									<span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
																								</div>
																								<div class="pto-modal-container">
																									<div class="pto-show-task-desc"><?php print_r( $desc ); ?></div>
																								</div>
																								<div class="pto-modal-footer">
																									<input type="button" name="ok" value="Ok" onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="task-recurrence_add_new outline_btn button button-primary">
																								</div>
																							</div>
																						</div>													
																						<?php
																					}
																					?>
																				</td>																								
																				<td <?php if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) || empty( $chk_time ) ) { ?> style="display:none;" <?php } ?> >
																					<?php 
																					if ( !empty( $single_post_meta ) ) { 
																						if ( array_key_exists( "single", $single_post_meta ) ) {
																							if ( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) {
																								if( !empty( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ) ) {
																								    //echo  $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
																									esc_html_e( date( "h:i a", strtotime( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ) ) );
																								}															
																							}
																						}
																					} 
																					?>												 
																				</td>
																				<?php if ( !empty( $check_cat ) ) { ?>  
																					<td><?php if ( !empty( $cat_name ) ) esc_html_e( substr( $cat_name, 0, -1 ) ); ?></td>
																				<?php } ?>
																				<td>
																					<input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php if ( !empty( $specific_day ) ) { esc_html_e( $specific_day ); } ?>"  />
																					<input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if ( !empty( $single_post_meta ) ) { if ( array_key_exists( "single", $single_post_meta ) ) if( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) esc_html_e( $single_post_meta['single']['how_money_volunteers_sign_ups-times'] ); } ?>"  />
																					<input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval( $hourspoint ); ?>" />
																					<?php														
																					$diff = "";
																					$total_volantears_sign_ups = "";
																					$total = "";
																					if ( !empty( $get_filed ) ) {
																						$get_availability = get_post_meta( $get_task_slot, "signup_task_availability", true );
																						$diff = 0;
																						if ( array_key_exists( "single", $get_filed ) ) {
																							$total_volantears = $get_filed['single']["how_money_volunteers"];
																							$total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
																							if ( $total_volantears == "" ) {
																								$total_volantears = 0;
																							}
																							if ( $total_volantears_sign_ups == "" )	{
																								$total_volantears_sign_ups = 0;
																							}
																							$total = $total_volantears;
																							if ( !empty( $get_availability ) ) {
																								?>
																								<b><?php echo intval( $get_availability ); ?>/<?php echo intval( $total ); ?>
																								</b>
																								<?php
																								if ( $get_availability == $total ) {
																									$filled = 1;
																									?>
																									<span>filled</span>
																									<?php
																								} else {
																									$diff = $total - $get_availability;
																								}																	 
																							} else {
																								?>
																								<b>0/<?php echo intval( $total ); ?></b>
																								<?php
																							}
																						} else if ( array_key_exists( "shift", $get_filed ) ) {
																							$total_volantears = $get_filed['shift']["volunteers_shift"];
																							$total_volantears_sign_ups = $get_filed['shift']["volunteers_shift_times"];
																							$shift_meta = $get_filed["shift"];
																							$count = 0;
																							if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && 
																								array_key_exists( "between-shift-minutes", $shift_meta ) ) {
																								$shift_start = $shift_meta['first-shift'];
																								$shift_end = $shift_meta['last-end-shift'];
																								$shift_min = $shift_meta['how-long-shift'];
																								$break_time = $shift_meta['between-shift-minutes'];
																								$array_of_time = array();
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
																								?>
																								<b>
																									<?php echo intval( $get_availability ); ?> /
																									<?php echo intval( $total ); ?>
																								</b>
																								<?php
																								
																								if ( $get_availability == $total ) {
																									$filled = 1;
																									?>
																									<span>
																										<?php
																											esc_html_e( "filled" );
																										?>
																									</span>
																									<?php
																								} else {
																									$diff = $total - $get_availability;
																								}
																							} else {
																								?>
																								<b>0/<?php echo intval( $total ); ?>						
																								</b>
																								<?php
																							}
																						}
																					} else {
																						?>
																						<b>0/0</b>
																						<?php
																					}
																					?>
																				</td>
																				<td>
																					<div class="sign-up-task-shift-block">
																						<?php
																						$shift_meta_time = array();
																						$all_shifts = "";
																						$current_user_shift = array();
																						$get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time', true ); 															
																						if ( !empty( $get_shift_time ) ) {																
																							if ( array_key_exists( $c_user_id, $get_shift_time ) ) {
																								$current_user_shift = explode( ",", $get_shift_time[ $c_user_id ] );
																							}
																							foreach ( $get_shift_time as $uid ) {																
																								$all_shifts .= $uid;
																							}
																							$shift_meta_time = explode( ",", $all_shifts );																
																						}														
																						$usermax = 0;
																						if( $c_user_id != 0 ) {
																							$get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );																
																							if ( !empty( $get_max_user_task_signup ) ) {
																								$max_key = $post_id."_".$get_task_slot;																
																								if ( array_key_exists( $max_key, $get_max_user_task_signup ) ) {
																									$usermax = $get_max_user_task_signup[$max_key];	
																									if ( $diff == 1 ) {
																									} else {
																										$diff = $total_volantears_sign_ups - $usermax;
																									}
																									if ( $usermax == $total_volantears_sign_ups ) {
																										$diff = 0;
																									}																			
																								}
																							}																
																						} 															
																						$max = 1;
																						// $max = intval($total) - intval($get_availability);

																						if ( $diff != 0 && $diff < $total_volantears_sign_ups ) {
																							$max = $diff;
																						}
																						else {
																							$max = $total_volantears_sign_ups;
																						}
																						if ( $total_volantears_sign_ups > $total ) {
																							$max = $total;
																						}
																						if( !empty( $get_availability ) && $diff == 0 ) {
																							$max = 0;
																						}	
																						$t = intval($total) - intval($get_availability);
																						
																						if( $t <=  $max ){
																							$max = $t;
																						}

																						?>
																						<input type="checkbox" class="sign-up-task" <?php if ( $filled == 1 || $max != 1 || ( $max == 1 && array_key_exists( "shift", $get_filed ) ) ) { ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval( $post_details->ID ); ?>" />
																						<?php //if(  $max != 0 ){
																							?>
																							<input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value="<?php //echo intval( $post_details->ID ); ?>"  />
																							<?php
																						//}
																						?>
																						

																						<?php if ( $total_volantears_sign_ups != 1 && array_key_exists( "single", $get_filed ) && $total_volantears_sign_ups != $usermax ) { 
																							if( $filled == 1 || $max == 1 ) {
																								?>														
																								<select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
																									<option value="1">1</option>
																								</select>
																								<input type="number" name="pto_signup_task_max1[]" min=1 max=<?php echo intval( $max ); ?> value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
																								<?php
																							} else {
																								?>
																								<select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
																									<?php 																	
																									for ( $i=0; $i<=$max; $i++ ) { ?>
																										<option value="<?php echo intval( $i ); ?>"><?php echo intval( $i ); ?></option>
																										<?php
																									}
																									?>
																								</select>
																								<input type="number" name="pto_signup_task_max1[]" min=1 max=<?php echo intval( $max ); ?> value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
																							<?php } } else { ?>														
																								<select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
																									<option value="1">1</option>
																								</select>
																								<input type="number" name="pto_signup_task_max1[]" value="" max=<?php echo intval( $max ); ?>  style="visibility:hidden;" class="pto-singup-task-max-number" />
																							<?php } 
																							if ( array_key_exists( "shift", $single_post_meta ) ) {
																								$shift_meta = $single_post_meta["shift"];
																								if ( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
																									$shift_count = count( $array_of_time );	
																									?>
																									<div class="shift-checkbox-list" <?php if( $filled == 1 ) { ?> style="visibility:hidden;" <?php } ?>>
																										<span class="span-choose-shift">Choose a shift:</span>
																										<ul class="checkbox-list">	
																											<li>
																												<input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
																												<label class="choose-shift">Choose a shift:</label>
																											</li>
																											<?php																		
																											$i = 0;																		
																											while ( $i < $shift_count ) { 
																												$shift_endtime = date ( "h:i A", ( strtotime( $array_of_time[ $i ] ) + $add_mins ) );																																					
																												if ( !empty( $shift_meta_time ) ) {
																													if ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears == 1 ) {
																														?>
																														<li>
																															<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																															<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																														</li>
																														<?php
																													}	
																													elseif ( strtotime( $array_of_time[ $i ] ) == $end_time ) {
																													}																			
																													elseif ( in_array( $array_of_time[ $i ], $shift_meta_time ) && $total_volantears > 1 ) {
																														$count_values = array_count_values( $shift_meta_time );
																														$this_shift_count = $count_values[ $array_of_time[ $i ] ];
																														if ( $this_shift_count == $total_volantears ) {
																															?>
																															<li>
																																<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																																<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																															</li>
																															<?php
																														}
																														elseif ( !empty( $current_user_shift ) && in_array( $array_of_time[ $i ], $current_user_shift ) ) {
																															?>
																															<li>
																																<input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																																<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																															</li>
																															<?php
																														}
																														else{
																															?>
																															<li>
																																<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																																<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																															</li>
																															<?php
																														}
																													}																				
																													else { 
																														?>
																														<li>
																															<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																															<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																														</li>
																														<?php
																													}																				
																												}
																												elseif ( strtotime( $array_of_time[ $i ] ) == $end_time ) {
																												}
																												else {
																													?>
																													<li>
																														<input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
																														<label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>																					
																													</li>
																													<?php
																												}																			
																												$i++; 	
																											}	
																											?>
																										</ul>
																									</div>															
																									<?php	
																								}
																							} else { 															
																							} ?>
																							<input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />										
																						</div>	
																					</td>
																				</tr>
																			<?php } } } else {
																				?>
																				<div class='sign_up_task'>No task/slot available.</div>
																				<?php
																				
																			} ?>
																		</tbody>
																	</table>
																<?php } else {
																	?>
																		You don't have access to this;
																	<?php
																} 
																if ( !empty( $categories_colspan_show ) && !empty( $number_of_slots ) && $taskcounts > $number_of_slots ) {
																	?>
																	<button type="button" class="signup-show-more button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn">Show More</button>
																	<?php
																}
																?>
															</div>				
														</div>
														<input type="hidden" class="pto-sign-up" id="pto-sign-up" name="pto_sign_up" value="<?php echo intval( $post_id ); ?>" />
														<?php 		
														$pto_checkout_sign_ups = get_option('pto_checkout_sign_ups'); 
														$url = "";
														if ( !empty( $pto_checkout_sign_ups ) ) {
															$url = get_the_permalink( $pto_checkout_sign_ups );
														}
														else {
															$url = site_url()."/pto-signup-checkout";
														}
														if ( isset( $_REQUEST["uid"] ) ) {
															$url .= "?uid=". intval($_REQUEST["uid"]);
														}									

														?>

														<button class="signup-tasks-nextBtn button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn" data-url="<?php echo esc_url( $url ); ?>" type="button" >Next</button>
													</div>
												<?php } ?>									
											</form>							
										</div>
									</div>				
									<?php  
									if ( !empty( $check_cat ) ) {
										$tasks_slots_categories = get_post_meta( $post_id, "tasks_slots_categories", true ); 
										if ( !empty( $tasks_slots_categories ) ) {
											?>
											<script>
												jQuery(document).ready(function() {								
													jQuery('.pto-signup-task-sorting').val('category').trigger('change');
												});
											</script>
											<?php
										}
									}
					endwhile; // End the loop.
					?>
				</main><!-- #main -->
			</div><!-- #primary -->	
			<script type="text/javascript">
			document.body.classList.add('pto-signup-body');
				jQuery(".sortable-data ul").each(function(){
					let ids = jQuery(this).attr("id");
					jQuery( function() {
						jQuery( "#"+ ids).sortable({
							connectWith: ".connectedSortable",
						}).disableSelection();
					});
				});
				function sortTable(n) {
					var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
					table = document.getElementById("single-signup-task-list");
					switching = true;
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
get_footer();