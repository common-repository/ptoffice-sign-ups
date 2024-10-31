<?php
namespace ptofficesignup\classes;
class PtoSingUpPluginCron {
    /**
    * Constructor for iniation
    * @since    1.0.0
    * @access   public
    **/
    function __construct() {
        // Run this on plugin activation
        //register_activation_hook( PTO_SIGN_UP_PLUGIN_WITH_CLASSES__FILE__,  [ $this, 'pto_create_plugin_active' ] );
        register_activation_hook( PTO_SIGN_UP_DIR, [ $this, 'pto_sign_up_isa_activation' ] );
        add_action( 'isa_add_every_three_minutes_event',  array( $this, 'pto_sign_up_isa_every_three_minutes_event_func' ) );
        add_filter( 'cron_schedules',  array( $this, 'pto_sign_up_isa_add_every_three_minutes' ) );
        register_deactivation_hook( PTO_SIGN_UP_DIR, [ $this, 'pto_sign_up_isa_deactivation' ] );
    }
    
    /**
    * Plugin activation hook
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_isa_activation() {
        if( !wp_next_scheduled( 'isa_add_every_three_minutes_event' ) ){
            wp_schedule_event( time(), 'every_three_minutes', 'isa_add_every_three_minutes_event' );
        }
    }
    /**
    * Cron event callback function
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_isa_every_three_minutes_event_func() {
         //do something        
        $to = 'janvi.kamaldhari@gmail.com';
        $subject = 'Cron job testing for signup.';
        $body = 'Congrats! your cron job is working.......!!!! Good Job Janvi....!!!.';
        $headers = array('Content-Type: text/html; charset=UTF-8');            
        wp_mail( $to, $subject, $body, $headers );
        $args = array(
            'post_type' => 'pto-signup',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ); 
        $currentdate = date( "Y-m-d" );
        global $wpdb;
        $table_name = $wpdb->prefix . "signup_orders";
        $task_query = new \WP_Query( $args );        
        if( $task_query->have_posts() ){
            while ( $task_query->have_posts() ) {
                $task_query->the_post();
                $id = get_the_ID();
                $signuptitle = get_the_title( $id );                
                $pto_sign_up_occurrence =  get_post_meta( $id , "pto_sign_up_occurrence" , true );
                if(!empty( $pto_sign_up_occurrence )){ 
                    $signup_details = "";
                    if(array_key_exists( "occurrence-specific", $pto_sign_up_occurrence )){
                        $specific_day = get_post_meta( $id , "occurrence_specific_days" , true );
                        if(!empty( $specific_day )){
                            $send_reminder = get_post_meta( $id , "send_reminder" , true );
                            if(!empty( $send_reminder )){
                                $receipts_section_number = get_post_meta( $id , 'receipts_section_number' , true );
                                $receipts_section_time = get_post_meta( $id , 'receipts_section_periad' , true );
                                if(!empty( $receipts_section_number && $receipts_section_time )){ 
                                    $signup_name = get_the_title($id);
                                    $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE signup_id = ".intval( $id )." AND status = 'on'" );
                                    $user_ids = array();
                                    $duplicate_removed = array();
                                    if(!empty( $all_user_posts )){
                                        foreach( $all_user_posts as $userkey => $post ){
                                            $user_ids[] = $post->user_id;
                                        }
                                    }
                                    $duplicate_removed = array_unique( $user_ids );                                    
                                    if(!empty( $duplicate_removed )){
                                        
                                        foreach( $duplicate_removed as $key ) {
                                            $signup_details = "<div>";								
                                            
                                            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE signup_id = ".intval( $id )." AND user_id = ".intval( $key )." AND status = 'on'" );
                                            if(!empty( $all_user_posts )){                                       
                                                foreach( $all_user_posts as $userkey => $post ){
                                                    $get_user_signup_data = unserialize( $post->order_info );                     
                                                    $user_id = $post->user_id;
                                                    if(!empty($get_user_signup_data)){
                                                        $signupid = $get_user_signup_data["signup_id"][0];
                                                        $signup_details .= "<p><strong>Signup Name:</strong>";
                                                        $signup_details .= "<a href='".get_the_permalink( $signupid )."' target='_blank' >".get_the_title( $signupid )."</a>";                                                        
                                                        $signup_details .= "</p>";
                                                        
                                                        $signup_custom_info = "";
                                                        $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                                                        $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );
                                                        
                                                        if(!empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up )){
                                                            foreach($signup_custom_fileds as $signup_custom_filed) {
                                                                $signup_type = get_post_meta( $signup_custom_filed, "pto_field_type", true );
                                                                $estype = $signup_type;
                                                                if( $signup_type == "text-area" ){
                                                                    $estype = "textarea";
                                                                }
                                                                if( $signup_type == "drop-down" ){
                                                                    $estype = "select";
                                                                }
                                                                $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed."_".$signupid;
                                                                $signup_customfieldval = "";
                                                                if( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) {
                                                                    if( $signup_type == "checkbox" ){                                                     
                                                                        $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ];
                                                                    } 
                                                                    else{
                                                                        $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];
                                                                    } 
                                                                }
                                                                if( !empty( $signup_customfieldval ) ){
                                                                    if($signup_type == "checkbox"){
                                                                        $signup_customfieldval = implode( ",", $signup_customfieldval );
                                                                    } 
                                                                }
                                                                $signup_custom_info .= "<p><strong>";
                                                                $signup_custom_info .= get_the_title( $signup_custom_filed );
                                                                $signup_custom_info .= ": </strong>";
                                                                $signup_custom_info .= $signup_customfieldval;
                                                                $signup_custom_info .= "</p>";
                                                            }
                                                        }
                                                        
                                                        if(!empty($signup_custom_info)){
                                                            $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                                            $signup_details .= $signup_custom_info;
                                                        }
                                                        
                                                        $total_task = count( $get_user_signup_data["task_id".$signupid] );                                
                                                        
                                                        for( $j=0; $j<$total_task; $j++ ) { 
                                                            
                                                            $taskid = $get_user_signup_data["task_id".$signupid][$j];                                                                     
                                                            
                                                            $task_date = $get_user_signup_data["task_date".$taskid][0];
                                                            $task_time = $get_user_signup_data["task_time".$taskid][0];
                                                            $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                                            
                                                            $signup_details .= "<p><strong>Task Name:</strong>";
                                                            $signup_details .= get_the_title( $taskid );
                                                            $signup_details .= "</p>";
                                                            
                                                            if( !empty( $task_date ) ){
                                                                $signup_details .= "<p><strong>Task Date:</strong>";
                                                                $signup_details .= $task_date;
                                                                $signup_details .= "</p>";
                                                            }
                                                            
                                                            if( !empty( $task_time ) ) {
                                                                $signup_details .= "<p><strong>Task Time:</strong>";
                                                                $signup_details .= $task_time;
                                                                $signup_details .= "</p>";
                                                            }
                                                            
                                                            $cpt_custom_fileds =  get_post_meta( $taskid, "single_task_custom_fields", true );                                
                                                            $custom_field_info = "";
                                                            if(!empty( $cpt_custom_fileds ) ) {
                                                                
                                                                foreach( $cpt_custom_fileds as $cpt_custom_filed ) {
                                                                    $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                                    if( $type == "text-area" ) {
                                                                        $type = "textarea";
                                                                    }
                                                                    if( $type == "drop-down" ) {
                                                                        $type = "select";
                                                                    }
                                                                    for( $c = 0; $c < $task_max_val; $c++ ){
                                                                        $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                                        $customfieldval = "";
                                                                        if( array_key_exists( $customfieldkey, $get_user_signup_data ) ) { 
                                                                            if($type == "checkbox"){
                                                                                $customfieldval = implode( ",", $get_user_signup_data[ $customfieldkey ] );
                                                                            } 
                                                                            else{
                                                                                $customfieldval = $get_user_signup_data[ $customfieldkey ][0];
                                                                            }
                                                                        }
                                                                        $custom_field_info .= "<p><strong>";
                                                                        $custom_field_info .= get_the_title( $cpt_custom_filed );
                                                                        $custom_field_info .= ": </strong>";
                                                                        $custom_field_info .= $customfieldval;
                                                                        $custom_field_info .= "</p>";
                                                                    }
                                                                }
                                                            }                         
                                                            if(!empty( $custom_field_info )){
                                                                $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                                                $signup_details .= $custom_field_info;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            $cur_user_obj = get_user_by( 'id', $key );
                                            $full_name = $cur_user_obj->first_name." ".$cur_user_obj->last_name;;
                                            $fname = $cur_user_obj->first_name;
                                            $lname = $cur_user_obj->last_name;
                                            if(empty( $fname )){
                                                $fname = $cur_user_obj->display_name;
                                            } 
                                            if(empty( $lname )){
                                                $lname = $cur_user_obj->display_name;
                                            }
                                            $to = $cur_user_obj->user_email; 
                                            $signup_details .= "</div>";
                                            if($receipts_section_time == "days"){
                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " day" );
                                                $beforeday = date( "Y-m-d", $beforedays );
                                            }
                                            if($receipts_section_time == "weeks"){
                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " week" );
                                                $beforeday = date( "Y-m-d", $beforedays );
                                            }
                                            if($receipts_section_time == "monthly"){
                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " month" );
                                                $beforeday = date( "Y-m-d", $beforedays );
                                            }
                                            if($beforeday == $currentdate){
                                                $mailcontent = get_post_meta( $id, "volunteer_before_setting", true );
                                                if(!empty($mailcontent)){
                                                    $arra = array("/{{First Name}}/", "/{{Last Name}}/", "/{{Full Name}}/", "/{{Signup Name}}/", "/{{Signup Details}}/");
                                                    $arra2 = array( $fname, $lname, $full_name, $signup_name, $signup_details );                                      
                                                    $mail = preg_replace( $arra, $arra2, $mailcontent );
                                                    $subject = 'Signup Reminder';
                                                    $body = $mail;               
                                                    $headers = array('Content-Type: text/html; charset=UTF-8');                    
                                                    wp_mail( $to, $subject, $body, $headers );                                     
                                                }
                                            }    
                                        }
                                    }    
                                }
                            }
                        }
                    }  
                    elseif(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                        $get_recurrence_data =  get_post_meta( $id, "pto_task_recurreence", true );                                
                        $recur_dates = "";
                        $time_set = get_post_meta( $id, "pto_sign_ups_time_set", true );			
                        $dayr = "";
                        $rtime = "";
                        $end_data = array();
                        $monthr = "";
                        $weekr = "";
                        $startd = "";
                        if( !empty( $get_recurrence_data ) ) {
                            if(array_key_exists( "daysofevery", $get_recurrence_data )){ 
                                $dayr = $get_recurrence_data["daysofevery"]; 
                            }
                            if(array_key_exists( "to_sign_up_div_repeate_time", $get_recurrence_data )){ 
                                $rtime = $get_recurrence_data["to_sign_up_div_repeate_time"]; 
                            }
                            if(array_key_exists( "end_data", $get_recurrence_data )){ 
                                $end_data = $get_recurrence_data["end_data"]; 
                            }
                            if(array_key_exists( "pto_signup_reucr_month", $get_recurrence_data )){ 
                                $monthr = $get_recurrence_data["pto_signup_reucr_month"]; 
                            }
                            if( array_key_exists( "week_days", $get_recurrence_data ) ) { 
                                $weekr = $get_recurrence_data["week_days"]; 
                            }
                            if( array_key_exists( "start_date", $get_recurrence_data ) ){
                                $startd = $get_recurrence_data['start_date'];
                            }
                            if( array_key_exists( "skipped_dates", $get_recurrence_data ) ) {
                                if(!empty($skipped_dates)){
                                    $skipped_dates_array = explode( ",", $skipped_dates );
                                }
                            }
                        }        
                        if(!empty($time_set)){ 
                            $opendate = $time_set['opendate']; 
                            $closedate = $time_set['closedate'];
                            if(array_key_exists( "on", $end_data )){
                                
                                $closedate = $end_data["on"];
                                
                            }
                            
                            $stdate = new \DateTime( $opendate );
                            
                            $etdate = new \DateTime( $closedate );
                            $etdate->modify('+1 day');	
                            
                            if(!empty( $startd )){
                                
                                $stdate = new \DateTime( $startd );
                                
                            }										
                            
                            if(!empty($opendate) && !empty($closedate) && !empty($dayr) && !empty($rtime)){
                                
                                if($rtime == "Day"){																						
                                    
                                    $dayinterval = $dayr . " day";
                                    
                                    $interval = DateInterval::createFromDateString( $dayinterval );
                                    
                                    $recur_dates = new DatePeriod( $stdate, $interval, $etdate );						
                                    
                                }
                                
                                if($rtime == "Weeks"){
                                    
                                    $recur_dates = array();
                                    
                                    $monday = $tuesday = $wednesday = $thursday = $friday = $saturday = $sunday = array();
                                    
                                    $i = 0;
                                    
                                    while( $stdate <= $etdate ) {																		
                                        
                                        $time_stamp = strtotime( $stdate->format( 'Y-m-d' ) );
                                        
                                        $week = date( 'l', $time_stamp );
                                        
                                        if($week == "Monday"){										
                                            
                                            $monday[ $i ] = $stdate->format('Y-m-d');
                                            
                                        }
                                        
                                        if($week == "Tuesday"){										
                                            
                                            $tuesday[ $i ] = $stdate->format('Y-m-d');
                                            
                                        }
                                        
                                        if($week == "Wednesday"){										
                                            
                                            $wednesday[ $i ] = $stdate->format('Y-m-d');
                                            
                                        }
                                        
                                        if($week == "Thursday"){										
                                            
                                            $thursday[ $i ] = $stdate->format('Y-m-d');
                                            
                                        }
                                        
                                        if($week == "Friday"){										
                                            
                                            $friday[ $i ] = $stdate->format('Y-m-d');
                                            
                                        }
                                        
                                        if($week == "Saturday"){										
                                            
                                            $saturday[ $i ] = $stdate->format( 'Y-m-d' );
                                            
                                        }
                                        
                                        if($week == "Sunday"){										
                                            
                                            $sunday[ $i ] = $stdate->format( 'Y-m-d' );
                                            
                                        }                                                
                                        
                                        $stdate->modify( '+1 day' );
                                        
                                        $i++;                                        
                                        
                                    }
                                    
                                    $weekr = explode( ",", $weekr );
                                    
                                    $i = 0;
                                    
                                    $mondays = array();
                                    
                                    foreach( $monday as $mon ){
                                        
                                        if($i ==  $dayr){
                                            
                                            $i = 0;
                                            
                                        }
                                        
                                        if($i == 0){
                                            
                                            $mondays[] = $mon;
                                            
                                        }
                                        
                                        $i++;									
                                        
                                    }
                                    
                                    $i = 0;
                                    
                                    $tuesdays = array();
                                    
                                    foreach( $tuesday as $tue ){
                                        
                                        if($i ==  $dayr){
                                            
                                            $i = 0;
                                            
                                        }
                                        
                                        if($i == 0){
                                            
                                            $tuesdays[] = $tue;
                                            
                                        }
                                        
                                        $i++;									
                                        
                                    }
                                    
                                    $i = 0;
                                    
                                    $wednesdays = array();
                                    
                                    foreach( $wednesday as $wed ) {
                                        
                                        if($i ==  $dayr){
                                            
                                            $i = 0;
                                            
                                        }
                                        
                                        if($i == 0){
                                            
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
                                        
                                        if ( $i == 0 ) {
                                            
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
                                        
                                        if ( $i == 0 ) {
                                            
                                            $saturdays[] = $sat;
                                            
                                        }
                                        
                                        $i++;									
                                        
                                    }
                                    
                                    $i = 0;
                                    
                                    $sundays = array();
                                    
                                    foreach( $sunday as $sun ) {
                                        
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
                                
                                if( $rtime == "Year" ) {
                                    
                                    $dayinterval = $dayr . " year";
                                    
                                    $interval = DateInterval::createFromDateString( $dayinterval );
                                    
                                    $recur_dates = new DatePeriod( $stdate, $interval, $etdate );
                                    
                                }
                                
                            }
                            
                        }
                        
                        else{                                    
                            
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
                                    
                                    while( $stdate <= $etdate ) {																		
                                        
                                        $time_stamp = strtotime( $stdate->format( 'Y-m-d' ) );
                                        
                                        $week = date( 'l', $time_stamp );
                                        
                                        if( $week == "Monday" ) {										
                                            
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
                                        
                                        if ( $i == 0 ) {
                                            
                                            $mondays[] = $mon;
                                            
                                        }
                                        
                                        $i++;
                                        
                                    }
                                    
                                    $i = 0;
                                    
                                    $tuesdays = array();
                                    
                                    foreach( $tuesday as $tue ) {
                                        
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
                                    
                                    if ( in_array( "Thursday", $weekr) ) {
                                        
                                        $recur_dates = array_merge( $recur_dates, $thursdays );									
                                        
                                    }
                                    
                                    if ( in_array( "Friday", $weekr ) ) {
                                        
                                        $recur_dates = array_merge( $recur_dates, $fridays );									
                                        
                                    }
                                    
                                    if ( in_array("Saturday", $weekr ) ) {
                                        
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
                        
                        if ( !empty( $recur_dates ) ) {
                            $endafter = "";
                            if ( array_key_exists( "after", $end_data ) ) {
                                $endafter = $end_data["after"];
                            }
                            $send_reminder = get_post_meta( $id, "send_reminder", true );
                            if ( !empty( $send_reminder ) ) {
                                $receipts_section_number = get_post_meta( $id, 'receipts_section_number', true );
                                $receipts_section_time = get_post_meta( $id, 'receipts_section_periad', true );
                                $rec_date_array = array();
                                if ( !empty( $receipts_section_number && $receipts_section_time ) ) {
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
                                            else{ 
                                                $rec_date_array[] = $sdate;                               
                                            }
                                            $i++; 
                                        }
                                        if ( !empty( $rec_date_array ) ) {                                        
                                            $signup_name = get_the_title( $id );
                                            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE signup_id = ".intval( $id )." AND status = 'on'" );
                                            $user_ids = array();
                                            $duplicate_removed = array();
                                            if ( !empty( $all_user_posts ) ) {
                                                foreach( $all_user_posts as $userkey => $post ) {
                                                    $get_user_signup_data = unserialize( $post->order_info );
                                                    $total_task = count( $get_user_signup_data["task_id".$id] );
                                                    $taskids_array = array();
                                                    for ( $j=0; $j<$total_task; $j++ ) {
                                                        $taskid = $get_user_signup_data["task_id".$id][$j];
                                                        $task_explode = explode( "_", $taskid );
                                                        $taskids_array[] = $task_explode[1];
                                                    }
                                                    if( array_key_exists( $post->user_id, $user_ids ) ) {
                                                        $user_ids[ $post->user_id ] = array_merge( $user_ids[ $post->user_id ], $taskids_array );
                                                    }
                                                    else{
                                                        $user_ids[ $post->user_id ] = $taskids_array;
                                                    }									
                                                }
                                            }
                                            
                                            if ( !empty( $user_ids ) ) {
                                                foreach ( $user_ids as $ukey => $dtarray ) {
                                                    
                                                    $duplicate_removed = array_unique( $dtarray );
                                                    if(!empty($duplicate_removed)){
                                                        
                                                        foreach ( $duplicate_removed as $key ) {
                                                            
                                                            $signup_details = "<div>";						
                                                            
                                                            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE signup_id = ".intval( $id )." AND user_id = ".intval( $ukey )." AND status = 'on'" );
                                                            if ( !empty( $all_user_posts ) ) {                                       
                                                                foreach ( $all_user_posts as $userkey => $post ) {
                                                                    $get_user_signup_data = unserialize( $post->order_info );                     
                                                                    $user_id = $post->user_id;
                                                                    if ( !empty( $get_user_signup_data ) ) {
                                                                        $signupid = $get_user_signup_data["signup_id"][0];
                                                                        $signup_details .= "<p><strong>Signup Name:</strong>";
                                                                        $signup_details .= "<a href='".get_the_permalink( $signupid )."' target='_blank' >".get_the_title( $signupid )."</a>";
                                                                        
                                                                        $signup_details .= "</p>";
                                                                        
                                                                        $signup_custom_info = "";
                                                                        $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                                                                        $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );
                                                                        
                                                                        if( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
                                                                            foreach( $signup_custom_fileds as $signup_custom_filed ) {
                                                                                $signup_type = get_post_meta( $signup_custom_filed, "pto_field_type", true );
                                                                                $estype = $signup_type;
                                                                                if ( $signup_type == "text-area" ) {
                                                                                    $estype = "textarea";
                                                                                }
                                                                                if ( $signup_type == "drop-down" ) {
                                                                                    $estype = "select";
                                                                                }
                                                                                $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed."_".$signupid;
                                                                                $signup_customfieldval = "";
                                                                                if ( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) {
                                                                                    if ( $signup_type == "checkbox" ) {                                                     
                                                                                        $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ];
                                                                                    } 
                                                                                    else {
                                                                                        $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];
                                                                                    } 
                                                                                }
                                                                                if ( !empty( $signup_customfieldval ) ) {
                                                                                    if( $signup_type == "checkbox" ) {
                                                                                        $signup_customfieldval = implode( ",", $signup_customfieldval );
                                                                                    } 
                                                                                }
                                                                                $signup_custom_info .= "<p><strong>";
                                                                                $signup_custom_info .= get_the_title( $signup_custom_filed );
                                                                                $signup_custom_info .= ": </strong>";
                                                                                $signup_custom_info .= $signup_customfieldval;
                                                                                $signup_custom_info .= "</p>";
                                                                            }
                                                                        }
                                                                        
                                                                        if ( !empty( $signup_custom_info ) ) {
                                                                            $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                                                            $signup_details .= $signup_custom_info;
                                                                        }
                                                                        
                                                                        $total_task = count( $get_user_signup_data["task_id".$signupid] );                                
                                                                        
                                                                        for ( $j=0; $j<$total_task; $j++ ) { 
                                                                            
                                                                            $taskid = $get_user_signup_data["task_id".$signupid][$j];                                                                     
                                                                            
                                                                            $tid = "";
                                                                            if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
                                                                                $taskid_explode = explode( "_", $taskid );
                                                                                $tid = $taskid_explode[0];
                                                                            }
                                                                            else{
                                                                                $tid = $taskid;
                                                                            }
                                                                            
                                                                            $task_date = $get_user_signup_data["task_date".$taskid][0];
                                                                            $task_time = $get_user_signup_data["task_time".$taskid][0];
                                                                            $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                                                            
                                                                            $signup_details .= "<p><strong>Task Name:</strong>";
                                                                            $signup_details .= get_the_title( $tid );
                                                                            $signup_details .= "</p>";
                                                                            
                                                                            if ( !empty( $task_date ) ) {
                                                                                $signup_details .= "<p><strong>Task Date:</strong>";
                                                                                $signup_details .= $task_date;
                                                                                $signup_details .= "</p>";
                                                                            }
                                                                            
                                                                            if ( !empty( $task_time ) ) {
                                                                                $signup_details .= "<p><strong>Task Time:</strong>";
                                                                                $signup_details .= $task_time;
                                                                                $signup_details .= "</p>";
                                                                            }
                                                                            
                                                                            $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );                                
                                                                            $custom_field_info = "";
                                                                            if( !empty( $cpt_custom_fileds ) ) {
                                                                                
                                                                                foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {
                                                                                    $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                                                    if ($type == "text-area") {
                                                                                        $type = "textarea";
                                                                                    }
                                                                                    if ($type == "drop-down") {
                                                                                        $type = "select";
                                                                                    }
                                                                                    for ( $c = 0; $c < $task_max_val; $c++ ) {
                                                                                        $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                                                        $customfieldval = "";
                                                                                        if ( array_key_exists( $customfieldkey, $get_user_signup_data ) ) { 
                                                                                            if ($type == "checkbox"){
                                                                                                $customfieldval = implode( ",", $get_user_signup_data[ $customfieldkey ] );
                                                                                            }  
                                                                                            else {
                                                                                                $customfieldval = $get_user_signup_data[ $customfieldkey ][0];
                                                                                            }
                                                                                        }
                                                                                        $custom_field_info .= "<p><strong>";
                                                                                        $custom_field_info .= get_the_title( $cpt_custom_filed );
                                                                                        $custom_field_info .= ": </strong>";
                                                                                        $custom_field_info .= $customfieldval;
                                                                                        $custom_field_info .= "</p>";
                                                                                    }
                                                                                }
                                                                            }                         
                                                                            if( !empty( $custom_field_info ) ) {
                                                                                $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                                                                $signup_details .= $custom_field_info;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            
                                                            $cur_user_obj = get_user_by( 'id', $ukey );				
                                                            $full_name = $cur_user_obj->first_name." ".$cur_user_obj->last_name;
                                                            $fname = $cur_user_obj->first_name;
                                                            $lname = $cur_user_obj->last_name;
                                                            if ( empty( $fname ) ) {
                                                                $fname = $cur_user_obj->display_name;
                                                            } 
                                                            if ( empty( $lname ) ) {
                                                                $lname = $cur_user_obj->display_name;
                                                            }				
                                                            $to = $cur_user_obj->user_email; 
                                                            
                                                            $specific_day = $key;
                                                            $beforeday = "";
                                                            
                                                        //if($receipts_section_time == "hours"){
                                                            
                                                            //$beforehours = strtotime(date("Y-m-d", strtotime($specific_day)) ."-". $receipts_section_number. " hour");
                                                            
                                                            //$beforehour = date("Y-m-d", $beforehours);
                                                            
                                                        //}
                                                            
                                                            if ( $receipts_section_time == "days" ) {
                                                                
                                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " day" );
                                                                
                                                                $beforeday = date( "Y-m-d", $beforedays );
                                                                
                                                            }
                                                            
                                                            if ( $receipts_section_time == "weeks" ) {
                                                                
                                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " week" );
                                                                
                                                                $beforeday = date("Y-m-d", $beforedays);
                                                                
                                                            }
                                                            
                                                            if ( $receipts_section_time == "monthly" ) {
                                                                
                                                                $beforedays = strtotime( date( "Y-m-d", strtotime( $specific_day ) ) ."-". $receipts_section_number. " month" );
                                                                
                                                                $beforeday = date( "Y-m-d", $beforedays );
                                                                
                                                            }
                                                            
                                                            if ( $beforeday == $currentdate ) {
                                                                
                                                                $mailcontent = get_post_meta( $id, "volunteer_before_setting", true );
                                                                
                                                                if ( !empty( $mailcontent ) ) {
                                                                    
                                                                    $arra = array( "/{{First Name}}/", "/{{Last Name}}/", "/{{Full Name}}/", "/{{Signup Name}}/", "/{{Signup Details}}/" );
                                                                    
                                                                    $arra2 = array( $fname, $lname, $full_name, $signup_name, $signup_details );                                      
                                                                    
                                                                    $mail = preg_replace( $arra, $arra2, $mailcontent );
                                                                    
                                                                    $subject = 'Signup Reminder';
                                                                    
                                                                    $body = $mail;                    
                                                                    
                                                                    $headers = array( 'Content-Type: text/html; charset=UTF-8' );                    
                                                                    
                                                                    wp_mail( $to, $subject, $body, $headers );                                                
                                                                    
                                                                }
                                                                
                                                            }                                       
                                                            
                                                        }
                                                        
                                                    }
                                                    
                                                }
                                            }
                                            
                                        }
                                    }
                                }
                            }        
                        }         
                    }
                } 
                
            }
            /* Archive volunteer's sign up receipts on front end after specified days */
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on'" );
            $number_of_archive = get_option( 'number_of_archive' );
            if ( !empty( $all_user_posts ) && !empty( $number_of_archive ) ) {
                foreach( $all_user_posts as $userkey => $post ) {
                    $checkout_date = $post->checkout_date; 
                    $id = $post->ID;
                    /* get date before days */
                    $afterdays = strtotime( date( "Y-m-d", strtotime( $checkout_date ) ) ."+". $number_of_archive. " day" );
                    $afterday = date( "Y-m-d", $afterdays );  
                    if( $afterday == $currentdate ){                    
                        $result = $wpdb->query( $wpdb->prepare( "UPDATE ".$table_name." SET status = 'archive' WHERE ID = ".intval( $id ) ) );
                    }                
                }
            }
        }
    /**
    * Schedule filter hook
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_isa_add_every_three_minutes( $schedules ) {
        $schedules['every_three_minutes'] = array(
            'interval'  => 60,
            'display'   => __( 'Every 3 Minutes', 'PTO_SIGN_UP_TEXTDOMAIN' )
        );
        return $schedules;
    }
    /**
    * Plugin deactivation hook
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_isa_deactivation() {
        if( wp_next_scheduled( 'isa_add_every_three_minutes_event' ) ) {
            wp_clear_scheduled_hook( 'isa_add_every_three_minutes_event' );
        }
    }
}