<?php 
global $wpdb;
$table_name = $wpdb->prefix . "signup_orders";
?>
<div class='wp-admin pto-custom-style'>
    <div class='wrap'>
        <h1>Sign Ups Reports</h1>
        <div class="pto-filter-text">Choose your filters and click RUN REPORT</div>
        <div class="pto-filter-form">
            <form metho="post" id="pto-signup-report-filter-form">
                <?php            
                $args = array(
                    'post_type'=> 'pto-signup',
                    'orderby'    => 'ID',
                    'post_status' => 'publish',
                    'order'    => 'DESC',
                'posts_per_page' => -1 // this will retrive all the post that is published                                          
            );
                $result = new \WP_Query( $args );
                if ( $result->have_posts() ) {                
                    ?>
                    <select name="pto_all_signups" id="pto-all-signups" class="pto-all-signups">
                        <option value="">All Sign ups</option>
                        <?php
                        while ( $result->have_posts() ) {
                            $result->the_post();
                            $post_id =  get_the_ID();
                            ?>
                            <option value="<?php echo intval( $post_id ); ?>"><?php esc_html_e( get_the_title( $post_id ) ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                }           
                $args = array(
                    'post_type'=> 'tasks-signup',
                    'orderby'    => 'ID',
                    'post_status' => 'publish',
                    'order'    => 'DESC',
                'posts_per_page' => -1 // this will retrive all the post that is published                                          
            );
                $result = new \WP_Query( $args );
                if ( $result->have_posts() ) {                
                    ?>
                    <select name="pto_all_signup_tasks" id="pto-all-signup-tasks" class="pto-all-signup-tasks">
                        <option value="">All Tasks/Slots</option>
                        <?php
                        while ( $result->have_posts() ) {
                            $result->the_post();
                            $post_id =  get_the_ID();
                            ?>
                            <option value="<?php echo intval( $post_id ); ?>"><?php esc_html_e( get_the_title( $post_id ) ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                }   
                
                $blogusers = get_users();            
                if ( !empty( $blogusers ) ) {                 
                    ?>
                    <select name="pto_all_signup_users" id="pto-all-signup-users" class="pto-all-signup-users" >
                        <option value="">All Volunteers</option>
                        <?php
                        foreach ( $blogusers as $user ) {                            
                            $user_id =  $user->ID;
                            ?>
                            <option value="<?php echo intval( $user_id ); ?>"><?php esc_html_e( $user->display_name ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                }   
                ?>
                <input type="text" name="from_date" id="fromdate" placeholder="From Date">
                <input type="text" name="to_date" id="todate" placeholder="To Date">
                <input type="button" value="Submit" id="pto-signup-report-submit" class="pto-signup-report-submit button button-primary" />     
            </form>
        </div>
        <div class="pto-signup-wrap-tables">
            <div class="pto-signup-filter-table-section">
                <?php 
                $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on'" ); 
                if ( !empty( $all_user_posts ) ) {
                    $tasks = array();
                    $signups = array();
                    $duplicate_removed = array();
                    $duplicate_removed_signups = array();
                    $i = 0;
                    $s = 0;
                    foreach ( $all_user_posts as $userkey => $post ) {
                        $get_user_signup_data = unserialize( $post->order_info );
                        $sign_up_id = $post->signup_id;
                        
                        $signups[ $s ] = $sign_up_id;
                        $total_task = count( $get_user_signup_data["task_id".$sign_up_id] );
                        for ( $j=0; $j<$total_task; $j++ ) {
                            $taskid = $get_user_signup_data["task_id".$sign_up_id][ $j ];
                            $taskid_explode = explode( "_", $taskid );
                            $tid = $taskid_explode[0];
                            $tasks[ $i ] = $tid;
                            $i++;
                        }
                        $s++;						
                    }
                    $duplicate_removed = array_unique( $tasks );
                    $duplicate_removed_signups = array_unique( $signups );
                    ?>
                    <table class="wp-list-table widefat fixed striped table-view-list signup-reports-list-tbl" style="display:none;" id="pto-singup-filter-listing-table-export">
                        <thead>
                            <tr>
                                <th scope="col" id="fname" class="manage-column column-fname" ><?php esc_html_e( 'First Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="lname" class="manage-column column-lname" ><?php esc_html_e( 'Last Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="email" class="manage-column column-email" ><?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="signup" class="manage-column column-signup" ><?php esc_html_e( 'Sign Up', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="task" class="manage-column column-task-slot" ><?php esc_html_e( 'Task/Slot', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="task-date" class="manage-column column-task-slot-date" ><?php esc_html_e( 'Task/Slot Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <th scope="col" id="task-time" class="manage-column column-task-slot-time" ><?php esc_html_e( 'Task/Slot Time', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                                <?php
                                $thcount = 0;
                                if ( !empty( $duplicate_removed ) ) {
                                    foreach ( $duplicate_removed as $task_slot ) {
                                        $tid = "";                        
                                        $taskid_explode = explode( "_", $task_slot );
                                        $tid = $taskid_explode[0];                        
                                        $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                                        if ( !empty( $cpt_custom_fileds ) ) {
                                            foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {	
                                                $alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
                                                $custom_field_title = $alternet_title;
                                                if ( empty( $alternet_title ) ) {
                                                    $custom_field_title = get_the_title( $cpt_custom_filed );
                                                }
                                                ?>
                                                <th class="custom_task_fields" ><?php esc_html_e( $custom_field_title ); $thcount++; ?></th>
                                                <?php
                                            }
                                        }	
                                    }
                                }
                                if ( !empty( $duplicate_removed_signups ) ) {
                                    foreach ( $duplicate_removed_signups as $signup_ids ) {
                                        $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                        $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                        if ( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
                                            foreach ( $signup_custom_fileds as $signup_custom_filed ) {
                                                $signup_alternet_title = get_post_meta( $signup_custom_filed, "pto_alternate_title", true );
                                                $signup_custom_field_title = $signup_alternet_title;
                                                if ( empty( $signup_alternet_title ) ) {
                                                    $signup_custom_field_title = get_the_title( $signup_custom_filed );
                                                }
                                                ?>
                                                <th class="custom_task_fields" ><?php esc_html_e( $signup_custom_field_title ); ?></th>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                <th scope="col" id="date" class="manage-column column-date" ><?php esc_html_e( 'Checkout Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php             
                            if ( !empty( $all_user_posts ) ) {
                                $tdcount = 0;
                                foreach( $all_user_posts as $key => $post ):
                                    $checkout_date = $post->checkout_date;                    
                                    $user_id = $post->user_id;
                                    $user_info = get_userdata( $user_id );                    
                                    $first_name = $user_info->first_name;
                                    if ( empty( $first_name ) ) {
                                        $first_name = $user_info->display_name;
                                    }
                                    $last_name = $user_info->last_name;
                                    $user_email = $user_info->user_email;
                                    $display_name = $user_info->display_name;
                                    $get_user_signup_data = unserialize( $post->order_info );
                                    $shifttime = "";                    
                                    if ( !empty( $get_user_signup_data ) ) {
                                        $total_signup = count( $get_user_signup_data["signup_id"] );
                                        for ( $i=0; $i<$total_signup; $i++ ) {
                                            $signupid = $get_user_signup_data["signup_id"][ $i ];  
                                            $pto_sign_up_occurrence =  get_post_meta( $signupid, "pto_sign_up_occurrence", true );     
                                            if( empty( $pto_sign_up_occurrence ) ){
                                                $pto_sign_up_occurrence = array();
                                            }     
                                            $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                                            $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );
                                            $total_task = count( $get_user_signup_data["task_id".$signupid] );
                                            for ( $j=0; $j<$total_task; $j++ ) { 
                                                $taskid = $get_user_signup_data["task_id".$signupid][ $j ];
                                                
                                                $task_date = $get_user_signup_data["task_date".$taskid][0];
                                                $task_time = $get_user_signup_data["task_time".$taskid][0];
                                                $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                                $tid = "";
                                                $sdate  = "";
                                                if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
                                                    $taskid_explode = explode( "_", $taskid );
                                                    $tid = $taskid_explode[0];
                                                    $sdate = $taskid_explode[1];
                                                }
                                                else{
                                                    $tid = $taskid;
                                                }
                                                $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
                                                if( empty( $saved_dates ) ){
                                                    $saved_dates = array();
                                                }
                                                $tasktitle = "";
                                                if( !empty( $saved_dates ) ) {
                                                    $tasktitle = $saved_dates["post_title"];
                                                }
                                                else {
                                                    $tasktitle = get_the_title( $tid );
                                                }
                                                $hourspoints = "N/A";
                                                if ( array_key_exists( "task_hours_points".$taskid, $get_user_signup_data ) ) {
                                                    $hourspoints = $get_user_signup_data["task_hours_points".$taskid][0];
                                                }
                                                $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
                                                if( empty( $get_filed ) ){
                                                    $get_filed = array();
                                                }
                                                if ( array_key_exists( "shift", $get_filed ) ) {
                                                    $timekey = "task_time".$taskid;
                                                    $tasktime = "";
                                                    if ( array_key_exists( $timekey, $get_user_signup_data ) ) { 
                                                        $tasktime = $get_user_signup_data[ $timekey ][0];
                                                    }
                                                    $shifttimes = explode( ",", $tasktime );
                                                    
                                                    $emptyRemoved = array_filter( $shifttimes );
                                                    $tasktime = implode( ", ", $emptyRemoved );
                                                    $shifttime = explode( ",", $tasktime );
                                                }
                                                $tdcount = 0;
                                                for ( $m = 0; $m < $task_max_val; $m++ ) {
                                                    $tdcount = 0;
                                                    $shtime = "";
                                                   
                                                    if ( !empty( $shifttime ) ) { 
                                                        $shtime = trim( $shifttime[ $m ] ); 
                                                        $task_time = $shtime;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="column-fname"><?php  esc_html_e( $first_name );  ?></td>
                                                        <td class="column-lname"><?php esc_html_e( $last_name ); ?></td>
                                                        <td class="column-email"><?php esc_html_e( $user_email ); ?></td>
                                                        <td class="column-signup"><?php esc_html_e( get_the_title( $signupid ) ); ?></td>
                                                        <td class="column-task-slot"><?php esc_html_e( $tasktitle ); ?></td>
                                                        <td class="column-task-slot-date"><?php esc_html_e( $task_date ); ?></td>
                                                        <td class="column-task-slot-time"><?php esc_html_e( $task_time ); ?></td>
                                                        <?php 
                                                        
                                                        if ( !empty( $duplicate_removed ) ) {
                                                            foreach ( $duplicate_removed as $task_slot ) {
                                                                $tid = "";                                            
                                                                $taskid_explode = explode( "_", $task_slot );
                                                                $tid = $taskid_explode[0];
                                                                
                                                                $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                                                                if ( !empty( $cpt_custom_fileds ) ) {
                                                                    foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {	
                                                                        $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                                        if ( $type == "text-area" ) {
                                                                            $type = "textarea";
                                                                        }
                                                                        if ( $type == "drop-down" ) {
                                                                            $type = "select";
                                                                        }
                                                                        $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$task_slot."_".$signupid."_".$m;
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
                                                                            $modifiedHtmlString = removeHtmlOrScriptTag($customfieldval);
                                                                            esc_html_e( $modifiedHtmlString );
                                                                        }
                                                                        else{
                                                                            esc_html_e("-");
                                                                        }
                                                                        ?>
                                                                        </td>
                                                                        <?php
                                                                        
                                                                        $tdcount++;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if ( !empty( $duplicate_removed_signups ) ) {
                                                            foreach ( $duplicate_removed_signups as $signup_ids ) {
                                                                $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                                                $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                                                if ( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
                                                                    foreach ( $signup_custom_fileds as $signup_custom_filed ) {
                                                                        $signup_type = get_post_meta( $signup_custom_filed, "pto_field_type", true );
                                                                        if ( $signup_type == "text-area" ) {
                                                                            $signup_type = "textarea";
                                                                        }
                                                                        if ( $signup_type == "drop-down" ) {
                                                                            $signup_type = "select";
                                                                        }
                                                                        $signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$signupid;
                                                                        $signup_customfieldval = "";
                                                                        if ( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) {	
                                                                            if ( $signup_type == "checkbox" ) {		
                                                                                $signup_customfieldval = implode( ",", $get_user_signup_data[ $signup_customfieldkey ] );		
                                                                            } 		
                                                                            else{		
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
                                                                        else{
                                                                            esc_html_e("-");
                                                                        }                                                                        
                                                                        ?>
                                                                        </td>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        ?>
                                                        <td class="column-date"><?php esc_html_e( $checkout_date ); ?></td>
                                                        
                                                    </tr>
                                                    <?php 
                                                } 
                                            }
                                        }
                                    }
                                endforeach;
                            }
                            ?> 
                        </tbody>
                    </table>
                <?php } ?>
                <table class="wp-list-table widefat fixed striped table-view-list signup-reports-list-tbl" id="pto-singup-filter-listing-table">
                    <thead>
                        <tr>
                            <th scope="col" id="fname" class="manage-column column-fname" onclick="sortTable(0)"><?php esc_html_e( 'First Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            <th scope="col" id="lname" class="manage-column column-lname" onclick="sortTable(1)"><?php esc_html_e( 'Last Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            <th scope="col" id="email" class="manage-column column-email" onclick="sortTable(2)"><?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            <th scope="col" id="signup" class="manage-column column-signup" onclick="sortTable(3)"><?php esc_html_e( 'Sign Up', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            <th scope="col" id="task" class="manage-column column-task-slot" onclick="sortTable(4)"><?php esc_html_e( 'Task/Slot', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            <th scope="col" id="date" class="manage-column column-date" onclick="sortTable(5)"><?php esc_html_e( 'Checkout Date', PTO_SIGN_UP_TEXTDOMAIN ); ?></th>
                            
                        </tr>
                    </thead>
                    <tbody id="pto-singup-filter-listing">
                        <?php 
                        $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on'" );
                        if ( !empty( $all_user_posts ) ) {
                            foreach ( $all_user_posts as $key => $post ):
                                $checkout_date = $post->checkout_date;                    
                                $user_id = $post->user_id;
                                $user_info = get_userdata( $user_id );                    
                                $first_name = $user_info->first_name;
                                if ( empty( $first_name ) ) {
                                    $first_name = $user_info->display_name;
                                }
                                $last_name = $user_info->last_name;
                                $user_email = $user_info->user_email;
                                $display_name = $user_info->display_name;
                                $get_user_signup_data = unserialize( $post->order_info );                   
                                if ( !empty( $get_user_signup_data ) ) {
                                    $total_signup = count( $get_user_signup_data["signup_id"] );
                                    for ( $i=0; $i<$total_signup; $i++ ) {
                                        $signupid = $get_user_signup_data["signup_id"][$i];            
                                        $pto_sign_up_occurrence =  get_post_meta( $signupid, "pto_sign_up_occurrence", true ); 
                                        if( empty( $pto_sign_up_occurrence ) )
                                            $pto_sign_up_occurrence = array();
                                        $total_task = count( $get_user_signup_data["task_id".$signupid] );
                                        for ( $j=0; $j<$total_task; $j++ ) { 
                                            $taskid = $get_user_signup_data["task_id".$signupid][ $j ];
                                            $sdate = "";
                                            if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
                                                $taskid_explode = explode( "_", $taskid );
                                                $tid = $taskid_explode[0];
                                                $sdate = $taskid_explode[1];
                                            }
                                            else {
                                                $tid = $taskid;
                                            }
                                            $hourspoints = "N/A";
                                            $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
                                            $tasktitle = "";
                                            if ( !empty( $saved_dates ) ) {
                                                $tasktitle = $saved_dates["post_title"];
                                            }
                                            else {
                                                $tasktitle = get_the_title( $tid );
                                            }
                                            if ( array_key_exists( "task_hours_points".$taskid, $get_user_signup_data ) ) {
                                                $hourspoints = $get_user_signup_data["task_hours_points".$taskid][0];
                                            }
                                            ?>
                                            <tr>
                                                <td class="column-fname"><?php  esc_html_e( $first_name );  ?></td>
                                                <td class="column-lname"><?php  esc_html_e( $last_name ); ?></td>
                                                <td class="column-email"><?php  esc_html_e( $user_email ); ?></td>
                                                <td class="column-signup"><?php  esc_html_e( get_the_title( $signupid ) ); ?></td>
                                                <td class="column-task-slot"><?php  esc_html_e( $tasktitle ); ?></td>
                                                <td class="column-date"><?php esc_html_e( $checkout_date ); ?></td>
                                                
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                            endforeach;
                        }
                        ?>            
                    </tbody>
                </table>
            </div>
            <div class="pto-singup-top-volunteers-section">
                <h3 class="pto-top-volunteers">TOP VOLUNTEERS</h3>
                <div class="pto-singup-volunteers-sorting">
                    <span>Sort by</span>
                    <select name="pto_singup_volunteers_sort" class="volunteers-sorting">
                       
                        <option value="Tasks">Tasks</option>
                        <option value="Signup">Signup</option>
                    </select>
                </div>
                <div class="pto-singup-volunteers-listing">
                    <?php            
                    $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' GROUP BY user_id" );
                    if ( !empty( $all_user_posts ) ) { 
                        $inc = 1;                
                        $volunteers = array();
                        foreach ( $all_user_posts as $key => $post ):                    
                            $user_id = $post->user_id;
                            $user_info = get_userdata( $user_id );
                            $first_name = $user_info->first_name;
                            $last_name = $user_info->last_name;
                            $display_name = $user_info->display_name; 
                            $user_name = "";
                            if ( !empty( $first_name ) && !empty( $last_name ) ) {
                                $user_name = $first_name ." ".$last_name;
                            }
                            else {
                                $user_name = $display_name;
                            }                            
                            $this_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND user_id =" .intval( $user_id ) );
                            if ( !empty( $this_user_posts ) ) {
                                $singups = array();
                                $tasks = array();                                        
                                $singupkey = 0;
                                $taskkey = 0;                                        
                                $hourpoint = 0;
                                foreach ( $this_user_posts as $keys => $posts ):                                            
                                    $get_user_order_info = unserialize( $posts->order_info );                                            
                                    if ( !empty( $get_user_order_info ) ) {
                                        $total_signup = count( $get_user_order_info["signup_id"] );
                                        for ( $i=0; $i<$total_signup; $i++ ) {
                                            $signupid = $get_user_order_info["signup_id"][ $i ];
                                            $singups[ $singupkey ] = $signupid;
                                            $singupkey++;
                                            $total_task = count( $get_user_order_info["task_id".$signupid] );
                                            for ( $j=0; $j<$total_task; $j++ ) { 
                                                $taskid = $get_user_order_info["task_id".$signupid][ $j ];
                                                $tasks[ $taskkey ] = $taskid;
                                                $taskkey++;
                                                
                                                if ( array_key_exists( "task_hours_points".$taskid, $get_user_order_info ) ) {
                                                    if ( !empty( $get_user_order_info["task_hours_points".$taskid ][0] ) ) {
                                                        $hourpoint += $get_user_order_info["task_hours_points".$taskid][0]; 
                                                    }                                                                                                                        
                                                }
                                            }
                                        }                                                
                                    }
                                    
                                endforeach;
                                $volunteers[ $user_id ]['user_name'] = $user_name;
                                $volunteers[ $user_id ]['hours_points'] = $hourpoint;
                                $volunteers[ $user_id ]['signups'] = count( array_unique( $singups ) );
                                $volunteers[ $user_id ]['tasks'] = count( array_unique( $tasks ) );
                            }                                     
                            $inc++;    
                            
                        endforeach;                         
                        uasort( $volunteers, function( $a, $b ) { return $b['hours_points'] <=> $a['hours_points']; } );   
                    }    
                    ?>
                    <div class="pto-signup-top-volunteers-list">                
                        <?php
                        if ( !empty( $volunteers ) ) { 
                            ?>
                            <table class="pto-singup-userwise-sorting" id="pto-singup-userwise-sorting">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Tasks</th>
                                        <th>Sign ups</th>
                                    </tr>
                                </thead>
                                <tbody class="pto-signup-user-tbody">
                                    <?php
                                    $inc = 1;                        
                                    foreach( $volunteers as $key => $vol ): 
                                        
                                        ?>
                                        <tr class="pto-singup-volunteer-block">
                                            <td class="pto-signup-volunteer-number">
                                                <?php echo intval( $inc ); ?>
                                            </td>
                                            <td class="pto-signup-volunteer-name">
                                                <?php esc_html_e( $vol["user_name"]); ?>
                                            </td>
                                                                        
                                            <td class="pto-signup-volunteer-tasks">
                                                <?php esc_html_e( $vol["tasks"] );?> Tasks/Slots
                                            </td>
                                            <td class="pto-signup-volunteer-signups">
                                                 <?php esc_html_e($vol["signups"]);?> Sign Ups
                                            </td> 
                                        </tr>
                                        
                                        <?php
                                        $inc++;                           
                                    endforeach;  
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function sortTable(n) {
               var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
               table = document.getElementById("pto-singup-filter-listing-table");
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
</div>
</div>
<?php 
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