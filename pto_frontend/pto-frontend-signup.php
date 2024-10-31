<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoFrontendSignup {
    /**
    * Constructor for iniation
    * @since    1.0.0
    * @access   public
    **/
    public function __construct() {
        $this->init();
    }    
    /**
    * Initiating necessary functions 
    * @since    1.0.0
    * @access   public
    **/
    public function init() {
        /* save user's sign ups with tasks */ 
        add_action( 'wp_ajax_nopriv_pto_save_signup_tasks', array( $this , 'pto_sign_up_save_signup_tasks' ) );
        add_action( 'wp_ajax_pto_save_signup_tasks', array( $this , 'pto_sign_up_save_signup_tasks' ) ); 
        /* save user's sign ups with user registration */ 
        add_action( 'wp_ajax_nopriv_pto_signup_checkout', array( $this , 'pto_sign_up_checkout' ) );
        add_action( 'wp_ajax_pto_signup_checkout', array( $this , 'pto_sign_up_checkout' ) ); 
        /* edit my signup */ 
        add_action( 'wp_ajax_nopriv_pto_signup_checkout_update', array( $this , 'pto_sign_up_checkout_update' ) );
        add_action( 'wp_ajax_pto_signup_checkout_update', array( $this , 'pto_sign_up_checkout_update' ) ); 
        /* Singup task sorting */ 
        add_action( 'wp_ajax_nopriv_pto_signup_tasks_sorting', array( $this , 'pto_sign_up_tasks_sorting' ) );
        add_action( 'wp_ajax_pto_signup_tasks_sorting', array( $this , 'pto_sign_up_tasks_sorting' ) );
        
        /* Singup logout from checkout */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_logout_from_checkout', array( $this , 'pto_sign_up_logout_from_checkout' ) );
        add_action( 'wp_ajax_pto_sing_up_logout_from_checkout', array( $this , 'pto_sign_up_logout_from_checkout' ) );
        /* remove signup from checkout */ 
        add_action( 'wp_ajax_nopriv_pto_remove_signup_checkout', array( $this , 'pto_sign_up_remove_signup_checkout' ) );
        add_action( 'wp_ajax_pto_remove_signup_checkout', array( $this , 'pto_sign_up_remove_signup_checkout' ) );
        /* view singup volunteers */ 
        add_action( 'wp_ajax_nopriv_pto_view_signup_tasks_volunteers', array( $this , 'pto_sign_up_view_signup_tasks_volunteers' ) );
        add_action( 'wp_ajax_pto_view_signup_tasks_volunteers', array( $this , 'pto_sign_up_view_signup_tasks_volunteers' ) );
        /* view singup volunteers */ 
      
        
        // add_filter( 'wp_nav_menu_items', array( $this ,'add_extra_item_to_nav_menu'), 10, 2 );
        // add_action( 'wp_footer' , array( $this , "add_extra_item_to_nav_menu" ) );
        
        /* show my signup archive/unarchive */ 
        add_action( 'wp_ajax_nopriv_pto_signup_show_archive_unarchive', array( $this , 'pto_sign_up_show_archive_unarchive' ) );
        add_action( 'wp_ajax_pto_signup_show_archive_unarchive', array( $this , 'pto_sign_up_show_archive_unarchive' ) );
        /* move signup to archive */ 
        add_action( 'wp_ajax_nopriv_pto_signup_to_archive', array( $this , 'pto_sign_up_to_archive' ) );
        add_action( 'wp_ajax_pto_signup_to_archive', array( $this , 'pto_sign_up_to_archive' ) );
        /* move signup to unarchive */ 
        add_action( 'wp_ajax_nopriv_pto_signup_to_unarchive', array( $this , 'pto_sign_up_to_unarchive' ) );
        add_action( 'wp_ajax_pto_signup_to_unarchive', array( $this , 'pto_sign_up_to_unarchive' ) );
        /* get recurrence task list */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_get_recurrence_task_list', array( $this , 'pto_sign_up_get_recurrence_task_list' ) );
        add_action( 'wp_ajax_pto_sing_up_get_recurrence_task_list', array( $this , 'pto_sign_up_get_recurrence_task_list' ) );
        /* remove signup from my signup */ 
        add_action( 'wp_ajax_nopriv_pto_signup_remove_my_signup', array( $this , 'pto_sign_up_remove_my_signup' ) );
        add_action( 'wp_ajax_pto_signup_remove_my_signup', array( $this , 'pto_sign_up_remove_my_signup' ) );
        /* resend receipt from my signup */ 
        add_action( 'wp_ajax_nopriv_pto_my_singup_resend_receipt', array( $this , 'pto_sign_up_my_singup_resend_receipt' ) );
        add_action( 'wp_ajax_pto_my_singup_resend_receipt', array( $this , 'pto_sign_up_my_singup_resend_receipt' ) );
    }
    
    
    function add_extra_item_to_nav_menu( ) {
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
            <div  class='pto-cart-signup' style='display:none'>
                <li id='menu-item' class='menu-item menu-item-type-post_type menu-item-object-page menu-item nav-item pto-header-cart'><a  href='<?php echo esc_url( $url ); ?>' class='pto-cart-tasks-count'><?php echo intval($taskcount); ?></a></a></li></div>
            <?php
       
        // return $items;
    }
    /**
    * Get recurrence task list
    * @since    1.0.0
    * @access   public
    **/
    
    public function pto_sign_up_get_recurrence_task_list() {
        ob_start();
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if( isset( $_POST['selected_dates'] ) && isset( $_POST['signupid'] ) ) {
            $selected_dates = explode( ",", $_POST['selected_dates'] );
            $emptyremoved = array_filter( $selected_dates );
            $dcount = count( $emptyremoved );
            $signupid = sanitize_text_field( $_POST['signupid'] );
            $c_user_id = get_current_user_id();
            if( isset( $_POST['uid'] ) ) {
                $uid = intval( $_POST['uid'] );
            }
            
            $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
            $categories_colspan_show = get_post_meta( $signupid, "categories_colspan_show", true );
            $number_of_slots = get_post_meta( $signupid, "number_of_slots", true );
            $chkuid = 0;
            if( $c_user_id != $uid ) {
                $chkuser = get_userdata( $c_user_id );
                $user_roles = $chkuser->roles;
                $author_id = get_post_field( 'post_author', $signupid );
                $get_user_req_post = get_post_meta( $signupid, 'pto_assign_user_administrator', true );
                if ( empty( $get_user_req_post ) ) { 
                    $get_user_req_post = array();                                       
                }
                if ( $c_user_id == $author_id || in_array( $c_user_id , $get_user_req_post ) || in_array( "administrator", $user_roles ) || in_array( "sign_up_plugin_administrators", $user_roles ) ) {
                    $c_user_id = $uid;
                }
                else {
                    $chkuid = 1;
                }
            }   
            $get_task_slots = get_post_meta( $signupid, "pto_signups_task_slots", true );
            $check_cat = "";
            $chk_time = "";
            if ( !empty( $get_task_slots ) ) {
                foreach( $get_task_slots as $get_task_slot ) {
                    $category_detail = get_the_terms( $get_task_slot, 'TaskCategories' );
                    $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                    if ( !empty( $category_detail ) ) {                                     
                        foreach ( $category_detail as $category_details ) {
                            $check_cat .= " ".$category_details->name . "," ;
                        }
                    }                           
                    if ( !empty( $single_post_meta ) ) { 
                        if( array_key_exists( "single", $single_post_meta ) ) {
                            if( array_key_exists( "how_money_volunteers_sign_ups-times", $single_post_meta['single'] ) ) {
                                $chk_time .= $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
                            }
                        }
                    }
                }
            }
            if ( isset( $_POST['orderid'] ) ) {
                $editid = intval( $_POST['orderid'] );
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
                        $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                        
                        for ( $j=0; $j<$total_task; $j++ ) { 
                            $taskid = $get_user_signup_data["task_id".$signupid][$j];
                            $tid = "";
                            if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
                                $taskid_explode = explode( "_", $taskid );
                                $tid = $taskid_explode[0];
                                $selected_date_array[ $j ] = $taskid_explode[1]; 
                            }
                            else {
                                $tid = $taskid;
                            }
                            $task_hour_points = $get_user_signup_data["task_hours_points".$taskid][0];
                            $task_max_value = $get_user_signup_data["task_max".$taskid][0];
                            $task_time = $get_user_signup_data["task_time".$taskid][0];
                            $taskids_array[ $j ] = $taskid;
                            $tasktime_array[ $taskid ] = explode( ",", $task_time );
                            $taskmax_array[ $taskid ] = $task_max_value;
                        }                                               
                    }
                endforeach;
                ?>
                <div class="table-responsive">
                <?php if ( $chkuid == 0 ) {  ?>
                    <table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
                        <thead>
                            <tr>    
                                <th onclick="sortTable(0)">Task Name</th>                                               
                                <th onclick="sortTable(1)" >Date</th>
                                <th onclick="sortTable(2)" <?php if ( !empty( $pto_sign_up_occurrence ) ) { if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) || empty( $chk_time ) ) { ?> style="display:none;" <?php } } ?>>Time</th>
                                <?php if ( !empty ( $check_cat ) ) { ?> 
                                <th onclick="sortTable(3)">Category</th>
                                <?php } ?>
                                <th>Availability</th>
                                <th>Sign Up</th>                        
                            </tr>
                        </thead>
                        <tbody class="pto-signup-tasks">
                            <?php                                
                                if(!empty($get_task_slots))
                                {
                                    foreach($get_task_slots as $get_task_slot)
                                    {
                                        for($dt=0;$dt<$dcount;$dt++){                                        
                                            $filled = 0;
                                            $post_details = get_post( $get_task_slot );
                                            //print_r($post_details);
                                            $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                            //print_r($single_post_meta);
                                            $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
                                            $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                     
                                            $category_detail = get_the_terms( $get_task_slot, 'TaskCategories' );
                                            $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
                                            $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                            $sdate = $emptyremoved[$dt];
                                            $saved_dates = get_post_meta($get_task_slot, "pto_signup_task_edit_single".$sdate, true);
                                            if(!empty($saved_dates)){
                                                $desc = $saved_dates["tasks_comp_desc"];
                                            }
                                            $avdate = $get_task_slot."_".$emptyremoved[$dt];
                                            $cat_name = "";
                                            if(!empty($category_detail)){                                       
                                                foreach($category_detail as $category_details){
                                                    $cat_name .= " ".$category_details->name . "," ;
                                                }
                                            }
                                            $current_status = get_post_status ( $get_task_slot );
                                            if($current_status == "publish"){
                                                $taskcounts++;
                                                ?>
                                                <tr>  
                                                    <td>
                                                        <?php 
                                                        if(!empty($saved_dates)){
                                                            esc_html_e($saved_dates["post_title"]);
                                                        }
                                                        else{
                                                            esc_html_e($post_details->post_title); 
                                                        } 
                                                        if(!empty($desc)){ ?>
                                                        <a href="#0" class="pto-task-desc" >details</a>
                                                        <div class="pto-task-content pto-modal" style="display:none;">
                                                            <div class="pto-modal-content">
                                                                <div class="pto-modal-container-header">
                                                                    <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                                                    <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                                </div>
                                                                <div class="pto-modal-container">
                                                                    <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                                                    <td><?php esc_html_e(date("F jS Y", strtotime($emptyremoved[$dt]))); ?></td>
                                                    <td <?php if(!empty($pto_sign_up_occurrence) ){ if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time) ){ ?> style="display:none;" <?php } } ?>>
                                                        <?php 
                                                            if(!empty($single_post_meta)){ 
                                                                if(array_key_exists("single",$single_post_meta)){
                                                                    if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                                    {
                                                                        if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                                            esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                                                        }                                                           
                                                                    }
                                                                }
                                                                
                                                            } 
                                                        ?>                                               
                                                    </td>
                                                    <?php if(!empty($check_cat)){ ?>  
                                                    <td>
                                                        <?php 
                                                            if(!empty($saved_dates)){
                                                                $term_id = $saved_dates["post_cat"];
                                                                if(!empty($term_id)){
                                                                    $cat_name = get_term( $term_id )->name;
                                                                    esc_html_e($cat_name);
                                                                }                                                                
                                                            }
                                                            else{
                                                                if(!empty($cat_name)) esc_html_e(substr($cat_name, 0, -1));
                                                            } 
                                                        ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td>
                                                        <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php esc_html_e($emptyremoved[$dt]); ?>"  />
                                                        <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                            esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                                        <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                                        <?php      
                                                            $filed1 = $filed2 = 0;
                                                            if(!empty($get_filed)){                                                                
                                                                $get_availability = get_post_meta( $get_task_slot, "signup_task_availability".$avdate, true );
                                                                $diff = 0;
                                                                if(array_key_exists("single",$get_filed)){
                                                                    $total_volantears = $get_filed['single']["how_money_volunteers"];
                                                                    $total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    //$total = $total_volantears * $total_volantears_sign_ups;
                                                                    $total = $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                        $filed1 = intval($get_availability);
                                                                        $filed2 = intval($total);
                                                                        ?>
                                                                        <b>
                                                                            <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                     
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                            <span> filled</span>
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        } 
                                                                    }else{
                                                                        ?>
                                                                        <b>
                                                                            0 / 
                                                                            <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                        
                                                                    }
                                                                }else if(array_key_exists("shift",$get_filed)){
                                                                    //print_r($get_filed);
                                                                    $total_volantears= $get_filed['shift']["volunteers_shift"];
                                                                    $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                                                    $shift_meta = $get_filed["shift"];
                                                                    $count = 0;
                                                                    if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                        $shift_start = $shift_meta['first-shift'];
                                                                        $shift_end = $shift_meta['last-end-shift'];
                                                                        $shift_min = $shift_meta['how-long-shift'];
                                                                        $break_time = $shift_meta['between-shift-minutes'];
                                                                        $array_of_time = array();
                                                                        $start_time    = strtotime ($shift_start); 
                                                                        $end_time      = strtotime ($shift_end);
                                                                        $add_mins  = $shift_min * 60;
                                                                        $break_min = $break_time * 60; 
                                                                        $i = 0;                                 
                                                                        while ($start_time <= $end_time) {                                                                                                                                              
                                                                            $array_of_time[$i] = date ("h:i A", $start_time);
                                                                            $start_time += ($add_mins + $break_min);
                                                                            $count++;
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    $end_val = strtotime(end($array_of_time));
                                                                    if($end_val == $end_time){
                                                                        if($count != 0){
                                                                            $count = $count - 1;
                                                                        }
                                                                    }                                                       
                                                                    
                                                                    $total = $count * $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                        ?>
                                                                        <b>
                                                                            <?php echo intval($get_availability); ?> / 
                                                                            <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                            <span> filled</span>
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        }
                                                                    }else{
                                                                        ?>
                                                                        <b>
                                                                            0 / 
                                                                            <?php echo intval($total); ?> 
                                                                        </b>
                                                                        <?php
                                                                    }
                                                                }
                                                            }else{
                                                                ?>
                                                                 <b>0/0</b>
                                                                <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if(in_array($avdate, $taskids_array)){ ?>
                                                            <div class="sign-up-task-shift-block">
                                                                <?php  
                                                                    $task_max_value = $taskmax_array[$avdate];
                                                                    $shift_meta_time = array();
                                                                    $all_shifts = "";
                                                                    $current_user_shift = array();
                                                                    $get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time'.$avdate, true ); 
                                                                    if(!empty($get_shift_time)){
                                                                        
                                                                        if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                            $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                        }
                                                                        foreach($get_shift_time as $uid){                                                                                                       
                                                                            $all_shifts .= $uid;
                                                                        }
                                                                        $shift_meta_time = explode(",", $all_shifts);                                                                                                   
                                                                    }
                                                                    $usermax = 0;
                                                                    $ediff = 0;
                                                                    //echo "total: ".$total_volantears_sign_ups;
                                                                    if($c_user_id != 0){                                                                                                    
                                                                        $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                                                                                                                                  
                                                                        if(!empty($get_max_user_task_signup)){
                                                                            $max_key = $signupid."_".$avdate;                                                               
                                                                            if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                                $usermax = $get_max_user_task_signup[$max_key];                                                              
                                                                                $ediff = $total_volantears_sign_ups - $usermax;
                                                                                if($diff < $ediff){
                                                                                    $ediff = $diff;
                                                                                }                                                                                                                                                                                   
                                                                            }
                                                                        }                                                               
                                                                    }
                                                                    
                                                                    $max = 1;                                                                                               
                                                                    // echo $max;
                                                                    if( empty( $task_max_value ) ){
                                                                        $task_max_value =0;
                                                                    }
                                                                    // echo $task_max_value;
                                                                    // echo $ediff;
                                                                    if($filled != 1){
                                                                        // if( $task_max_value == 0 || empty( $task_max_value ) ){
                                                                        //     $max = $total + $ediff;    
                                                                        // }else{
                                                                        //     $max = $task_max_value + $ediff;    
                                                                        // }
                                                                        $max = $task_max_value + $ediff;
                                                                    }   
                                                                    if($filled == 1){
                                                                        $max = $task_max_value;
                                                                    }
                                                                    // $max = intval($total) - intval($max);
                                                                    // echo $max;
                                                                    // echo $max;
                                                                ?>
                                                                <input type="checkbox" class="sign-up-task" <?php if( $filed1 == $filed2 ){ esc_html_e("checked"); } ?> <?php if($max != 1 || array_key_exists("shift", $get_filed)){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                                <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                                <?php 
                                                                    if($max != 1 && array_key_exists("single", $get_filed) ){                                                                                                   
                                                                ?>
                                                                    <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                                        <?php                                                                   
                                                                            for($i=0; $i<=$max; $i++){ ?>
                                                                            <option <?php if($task_max_value == $i){ esc_html_e("selected"); } ?> value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <input type="number" name="pto_signup_task_max1[]" min="1" max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                <?php  }else{ ?>                                                        
                                                                <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                    <option value="<?php if( array_key_exists( "shift", $single_post_meta ) ){ echo intval($task_max_value); }else{ esc_html_e("1"); } ?>">1</option>
                                                                </select>
                                                                <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                                                <?php } 
                                                                $tasktimes = array();
                                                                if( array_key_exists( "shift", $single_post_meta ) ){
                                                                    $shift_meta = $single_post_meta["shift"];
                                                                    if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                        $shift_count = count($array_of_time);   
                                                                        //print_r($current_user_shift); 
                                                                        //echo "==========";                                                                                                                                                                                                            
                                                                        //print_r($tasktime_array[$get_task_slot]);
                                                                        $tasktimes = $tasktime_array[$avdate];
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
                                                                                    while ($i < $shift_count) { 
                                                                                        $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                                        if(!empty($shift_meta_time)){
                                                                                            if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                                if(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift) && !empty($tasktime_array) && in_array($array_of_time[$i], $tasktimes)){
                                                                                                    ?>
                                                                                                    <li>
                                                                                                        <input type="checkbox" class="task-shift" checked name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                    </li>
                                                                                                    <?php
                                                                                                }
                                                                                                else{
                                                                                                    ?>
                                                                                                    <li>
                                                                                                        <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                    </li>
                                                                                                    <?php
                                                                                                }
                                                                                            }   
                                                                                            elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                            }                                                                           
                                                                                            elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                                if(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift) && !empty($tasktime_array) && in_array($array_of_time[$i], $tasktimes)){
                                                                                                    ?>
                                                                                                    <li>
                                                                                                        <input type="checkbox" class="task-shift" checked name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                    </li>
                                                                                                    <?php
                                                                                                }
                                                                                                else{
                                                                                                    $count_values = array_count_values($shift_meta_time);
                                                                                                    $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                                    if($this_shift_count == $total_volantears){
                                                                                                        ?>
                                                                                                        <li>
                                                                                                            <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                            <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                        </li>
                                                                                                        <?php
                                                                                                    }
                                                                                                    elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                        }
                                                                                        else{
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
                                                                    }else{ 
                                                                    
                                                                    } ?>
                                                                    <input type="hidden" value="<?php esc_html_e(implode(",", $tasktimes)); ?>" name="task_shift_hidden[]" class="task-shift-hidden" /> 
                                                             </div>
                                                        <?php
                                                        }else{ ?>
                                                        <div class="sign-up-task-shift-block">
                                                            <?php
                                                                $shift_meta_time = array();
                                                                $all_shifts = "";
                                                                $current_user_shift = array();
                                                                $get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time'.$avdate, true ); 
                                                                if(!empty($get_shift_time)){
                                                                    //print_r($get_shift_time);
                                                                    if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                        $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                        //print_r($current_user_shift);
                                                                    }
                                                                    foreach($get_shift_time as $uid){
                                                                        //print_r($uid);
                                                                        $all_shifts .= $uid;
                                                                    }
                                                                    $shift_meta_time = explode(",", $all_shifts);
                                                                    //print_r($shift_meta_time);
                                                                }                                                       
                                                                $usermax = 0;                                                                
                                                                if($c_user_id != 0 ){
                                                                    $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                                                    //print_r($get_max_user_task_signup);
                                                                    if(!empty($get_max_user_task_signup)){
                                                                        $max_key = $signupid."_".$avdate;                                                               
                                                                        if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                            $usermax = $get_max_user_task_signup[$max_key]; 
                                                                            if($diff == 1 || $usermax == 0){
                                                                            }else{
                                                                                $diff = $total_volantears_sign_ups - $usermax;
                                                                            }
                                                                            if($usermax == $total_volantears_sign_ups){
                                                                                $diff = 0;
                                                                            }                                                                           
                                                                        }
                                                                    }                                                               
                                                                }                                                           
                                                                //echo "diff: ".$diff;                                                            
                                                                $max = 1;
                                                                if($diff != 0 && $diff < $total_volantears_sign_ups){
                                                                    $max = $diff;
                                                                }
                                                                else{
                                                                    $max = $total_volantears_sign_ups;
                                                                }
                                                                if($total_volantears_sign_ups > $total){
                                                                    $max = $total;
                                                                }
                                                                if(!empty($get_availability) && $diff == 0){
                                                                    $max = 0;
                                                                }
                                                                //echo "max: ".$max;                                                            
                                                            ?>
                                                            <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                            <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                                            <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                                                    if(($filled == 1 || $max == 1)){
                                                                        ?>                                                      
                                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                                <option value="1">1</option>
                                                                            </select>
                                                                            <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                        <?php
                                                                    }else{
                                                                ?>
                                                                <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                                    <?php                                                                   
                                                                        for($i=0; $i<=$max; $i++){ ?>
                                                                        <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                            <?php } }else{ ?>                                                       
                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                <option value="1">1</option>
                                                            </select>
                                                            <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                                            <?php } 
                                                            
                                                            if( array_key_exists( "shift", $single_post_meta ) ){
                                                                $shift_meta = $single_post_meta["shift"];
                                                                if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                    $shift_count = count($array_of_time);   
                                                                    //print_r($shift_meta_time);                                                                                                                    
                                                                    ?>
                                                                    <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                                        <span class="span-choose-shift">Choose a shift:</span>
                                                                        <ul class="checkbox-list">  
                                                                            <li>
                                                                                <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                                                <label class="choose-shift">Choose a shift:</label>
                                                                            </li>
                                                                            <?php                                                                       
                                                                                $i = 0;                                                                     
                                                                                while ($i < $shift_count) { 
                                                                                    $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                                    if(!empty($shift_meta_time)){
                                                                                        if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }   
                                                                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                        }                                                                           
                                                                                        elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                            $count_values = array_count_values($shift_meta_time);
                                                                                            $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                            if($this_shift_count == $total_volantears){
                                                                                                ?>
                                                                                                <li>
                                                                                                    <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                    <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                </li>
                                                                                                <?php
                                                                                            }
                                                                                            elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                        else{ 
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }                                                                               
                                                                                    }
                                                                                    elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                    }
                                                                                    else{
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
                                                                }else{ 
                                                                
                                                                } ?>
                                                                <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
                                                        </div>
                                                        <?php } ?>  
                                                    </td>
                                                </tr>
                                            <?php 
                                            }
                                        } 
                                    } 
                                }else{
                                    ?>
                                    <div class='sign_up_task'>No task/slot available.</div>
                                    <?php
                                    
                                } ?>
                            </tbody>
                        </table>
                        <?php 
                    } else{
                        ?>
                            You don't have access to this
                        <?php
                    } 
                ?>
                </div>
                <?php
            }else{
            ?>
            <div class="table-responsive">
                <?php if($chkuid == 0){  ?>
                    <table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
                        <thead>
                            <tr>    
                                <th onclick="sortTable(0)">Task Name</th>                                               
                                <th onclick="sortTable(1)" >Date</th>
                                <th onclick="sortTable(2)" <?php if(!empty($pto_sign_up_occurrence) ){ if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time) ){ ?> style="display:none;" <?php } } ?>>Time</th>
                                <?php if(!empty($check_cat)){ ?> 
                                <th onclick="sortTable(3)">Category</th>
                                <?php } ?>
                                <th>Availability</th>
                                <th>Sign Up</th>                        
                            </tr>
                        </thead>
                        <tbody class="pto-signup-tasks">
                            <?php
                                
                                if(!empty($get_task_slots))
                                {
                                    foreach($get_task_slots as $get_task_slot)
                                    {
                                        for($dt=0;$dt<$dcount;$dt++){                                        
                                            $filled = 0;
                                            $post_details = get_post( $get_task_slot );
                                            //print_r($post_details);
                                            $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                            //print_r($single_post_meta);
                                            $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
                                            $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                     
                                            $category_detail = get_the_terms( $get_task_slot, 'TaskCategories' );
                                            $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
                                            $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                            $sdate = $emptyremoved[$dt];
                                            $saved_dates = get_post_meta($get_task_slot, "pto_signup_task_edit_single".$sdate, true);
                                            if(!empty($saved_dates)){
                                                $desc = $saved_dates["tasks_comp_desc"];
                                            }
                                            $cat_name = "";
                                            if(!empty($category_detail)){                                       
                                                foreach($category_detail as $category_details){
                                                    $cat_name .= " ".$category_details->name . "," ;
                                                }
                                            }
                                            $current_status = get_post_status ( $get_task_slot );
                                            if($current_status == "publish"){
                                                $taskcounts++;
                                                ?>
                                                <tr <?php if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){ ?> class="extra-tr" <?php } ?>>  
                                                    <td>
                                                        <?php 
                                                        if(!empty($saved_dates)){
                                                            esc_html_e($saved_dates["post_title"]);
                                                        }
                                                        else{
                                                            esc_html_e($post_details->post_title); 
                                                        }                                                         
                                                        if(!empty($desc)){ ?>
                                                        <a href="#0" class="pto-task-desc" >details</a>
                                                        <div class="pto-task-content pto-modal" style="display:none;">
                                                            <div class="pto-modal-content">
                                                                <div class="pto-modal-container-header">
                                                                    <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                                                    <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                                </div>
                                                                <div class="pto-modal-container">
                                                                    <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                                                    <td><?php esc_html_e(date("F jS Y", strtotime($emptyremoved[$dt]))); ?></td>
                                                    <td <?php if(!empty($pto_sign_up_occurrence) ){ if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time) ){ ?> style="display:none;" <?php } } ?>>
                                                        <?php 
                                                            if(!empty($single_post_meta)){ 
                                                                if(array_key_exists("single",$single_post_meta)){
                                                                    if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                                    {
                                                                        if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                                            esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                                                        }                                                           
                                                                    }
                                                                }
                                                                
                                                            } 
                                                        ?>                                               
                                                    </td>
                                                    <?php if(!empty($check_cat)){ ?>  
                                                    <td>
                                                        <?php 
                                                        if(!empty($saved_dates)){
                                                            $term_id = $saved_dates["post_cat"];
                                                            if(!empty($term_id)){
                                                                $cat_name = get_term( $term_id )->name;
                                                                esc_html_e($cat_name);
                                                            }
                                                            
                                                        }
                                                        else{
                                                            if(!empty($cat_name)) esc_html_e(substr($cat_name, 0, -1));
                                                        } 
                                                        ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td>
                                                        <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php esc_html_e($emptyremoved[$dt]); ?>"  />
                                                        <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                            esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                                        <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                                        <?php                                                       
                                                            if(!empty($get_filed)){
                                                                $avdate = $get_task_slot."_".$emptyremoved[$dt];
                                                                $get_availability = get_post_meta( $get_task_slot, "signup_task_availability".$avdate, true );
                                                                $diff = 0;
                                                                if(array_key_exists("single",$get_filed)){
                                                                    $total_volantears = $get_filed['single']["how_money_volunteers"];
                                                                    $total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    //$total = $total_volantears * $total_volantears_sign_ups;
                                                                    $total = $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                        ?>
                                                                        <b>
                                                                            <?php echo intval($get_availability); ?> / 
                                                                            <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                                <span> filled</span>
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        }
                                                                            
                                                                    }else{
                                                                        ?>
                                                                            <b>
                                                                                0 / <?php echo intval($total); ?>
                                                                            </b>
                                                                        <?php
                                                                    }
                                                                }else if(array_key_exists("shift",$get_filed)){
                                                                    //print_r($get_filed);
                                                                    $total_volantears= $get_filed['shift']["volunteers_shift"];
                                                                    $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                                                    $shift_meta = $get_filed["shift"];
                                                                    $count = 0;
                                                                    if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                        $shift_start = $shift_meta['first-shift'];
                                                                        $shift_end = $shift_meta['last-end-shift'];
                                                                        $shift_min = $shift_meta['how-long-shift'];
                                                                        $break_time = $shift_meta['between-shift-minutes'];
                                                                        $array_of_time = array();
                                                                        $start_time    = strtotime ($shift_start); 
                                                                        $end_time      = strtotime ($shift_end);
                                                                        $add_mins  = $shift_min * 60;
                                                                        $break_min = $break_time * 60; 
                                                                        $i = 0;                                 
                                                                        while ($start_time <= $end_time) {                                                                                                                                              
                                                                            $array_of_time[$i] = date ("h:i A", $start_time);
                                                                            $start_time += ($add_mins + $break_min);
                                                                            $count++;
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    $end_val = strtotime(end($array_of_time));
                                                                    if($end_val == $end_time){
                                                                        if($count != 0){
                                                                            $count = $count - 1;
                                                                        }
                                                                    }                                                       
                                                                    
                                                                    $total = $count * $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                        ?>
                                                                        <b>
                                                                            <?php echo intval($get_availability); ?> / 
                                                                            <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                                <span> filled</span> 
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        }
                                                                    }else{
                                                                        ?>
                                                                        <b>
                                                                            0 / <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php
                                                                    }
                                                                }
                                                            }else{
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
                                                                $get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time'.$avdate, true ); 
                                                                if(!empty($get_shift_time)){
                                                                    //print_r($get_shift_time);
                                                                    if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                        $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                        //print_r($current_user_shift);
                                                                    }
                                                                    foreach($get_shift_time as $uid){
                                                                        //print_r($uid);
                                                                        $all_shifts .= $uid;
                                                                    }
                                                                    $shift_meta_time = explode(",", $all_shifts);
                                                                    //print_r($shift_meta_time);
                                                                }                                                       
                                                                $usermax = 0;
                                                                if($c_user_id != 0){
                                                                    $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                                                    //print_r($get_max_user_task_signup);
                                                                    if(!empty($get_max_user_task_signup)){
                                                                        $max_key = $signupid."_".$avdate;                                                               
                                                                        if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                            $usermax = $get_max_user_task_signup[$max_key]; 
                                                                            if($diff == 1){
                                                                            }else{
                                                                                $diff = $total_volantears_sign_ups - $usermax;
                                                                            }
                                                                            if($usermax == $total_volantears_sign_ups){
                                                                                $diff = 0;
                                                                            }                                                                           
                                                                        }
                                                                    }                                                               
                                                                }                                                           
                                                                                                                            
                                                                $max = 1;
                                                                if($diff != 0 && $diff < $total_volantears_sign_ups){
                                                                    $max = $diff;
                                                                }
                                                                else{
                                                                    $max = $total_volantears_sign_ups;
                                                                }
                                                                if($total_volantears_sign_ups > $total){
                                                                    $max = $total;
                                                                }
                                                                if(!empty($get_availability) && $diff == 0){
                                                                    $max = 0;
                                                                }
                                                                //echo "max: ".$max;                                                            
                                                            ?>
                                                            <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                            <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                                            <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                                                    if(($filled == 1 || $max == 1)){
                                                                        ?>                                                      
                                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                                <option value="1">1</option>
                                                                            </select>
                                                                            <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                        <?php
                                                                    }else{
                                                                ?>
                                                                <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                                    <?php                                                                   
                                                                        for($i=0; $i<=$max; $i++){ ?>
                                                                        <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                            <?php } }else{ ?>                                                       
                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                <option value="1">1</option>
                                                            </select>
                                                            
                                                            <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />                                                            
                                                            <?php } 
                                                            if( array_key_exists( "shift", $single_post_meta ) ){
                                                                $shift_meta = $single_post_meta["shift"];
                                                                if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                    $shift_count = count($array_of_time);   
                                                                    //print_r($shift_meta_time);                                                                                                                    
                                                                    ?>
                                                                    <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                                        <span class="span-choose-shift">Choose a shift:</span>
                                                                        <ul class="checkbox-list">  
                                                                            <li>
                                                                                <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                                                <label class="choose-shift">Choose a shift:</label>
                                                                            </li>
                                                                            <?php                                                                       
                                                                                $i = 0;                                                                     
                                                                                while ($i < $shift_count) { 
                                                                                    $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                                    if(!empty($shift_meta_time)){
                                                                                        if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }   
                                                                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                        }                                                                           
                                                                                        elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                            $count_values = array_count_values($shift_meta_time);
                                                                                            $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                            if($this_shift_count == $total_volantears){
                                                                                                ?>
                                                                                                <li>
                                                                                                    <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                    <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                </li>
                                                                                                <?php
                                                                                            }
                                                                                            elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                        else{ 
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }                                                                               
                                                                                    }
                                                                                    elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                    }
                                                                                    else{
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
                                                                }else{ 
                                                                
                                                                } ?>
                                                                <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
                                                        </div>  
                                                    </td>
                                                </tr>
                                            <?php 
                                            }
                                        } 
                                    } 
                                }else{
                                    ?>
                                    <div class='sign_up_task'>No task/slot available.</div>
                                    <?php
                                } ?>
                            </tbody>
                        </table>
                        <?php 
                    } else{
                        ?>
                            You don't have access to this
                        <?php
                    }
                    
                    if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){
                        ?>
                        <button type="button" class="signup-show-more button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn">Show More</button>
                        <?php
                    }
                ?>
                </div>
            <?php }
        }
        $scat = 0;
        if(!empty($check_cat)){
            $tasks_slots_categories = get_post_meta($signupid, "tasks_slots_categories", true); 
            if(!empty($tasks_slots_categories)){
                $scat = 1;
            }
        }
        $html = ob_get_clean();
        $result = array(
            'scat'  => $scat,        
            'data' => array(
                'html' => $html
            ),
        );
        // print_r(json_encode($result));
        echo json_encode($result);
        die();
    }
    /**
    * My sign up resend receipt 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_my_singup_resend_receipt() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        $id = "";
        $user_id = 0;
        $table_name = $wpdb->prefix . "signup_orders"; 
        //$signup_details = "<div>";
        if(isset($_POST['id'])){
            $id = intval( $_POST['id'] );
            if(!empty($id)){                         
                $all_data =  $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE ID = ".intval($id) );
                if(!empty($all_data)){               
                    foreach($all_data as $key => $post):
                        $get_user_signup_data = unserialize($post->order_info);                     
                        $user_id = $post->user_id;
                        if(!empty($get_user_signup_data)){
                            $total_signup = count($get_user_signup_data["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){ 
                                $signup_details = "<div>";
                                $signupid = $get_user_signup_data["signup_id"][$i];
                                $signup_name = get_the_title($signupid);
                                $signup_details .= "<p><strong>Signup Name:</strong>";
                                $signup_details .= "<a href='".get_the_permalink($signupid)."' target='_blank' >".get_the_title($signupid)."</a>";
                                //$signup_details .= get_the_title($signupid);
                                $signup_details .= "</p>";
                                $signup_custom_info = "";
                                $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                                $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );
                                if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                    foreach($signup_custom_fileds as $signup_custom_filed)
                                    {
                                        $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                        $estype = $signup_type;
                                        if($signup_type == "text-area"){
                                            $estype = "textarea";
                                        }
                                        if($signup_type == "drop-down"){
                                            $estype = "select";
                                        }
                                        $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed."_".$signupid;
                                        $signup_customfieldval = "";
                                        if(array_key_exists($signup_customfieldkey, $get_user_signup_data)){
                                            if($signup_type == "checkbox"){                                                     
                                                $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey];
                                            } 
                                            else{
                                                $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey][0];
                                            } 
                                        }
                                        if(!empty($signup_customfieldval)){
                                            if($signup_type == "checkbox"){
                                                $signup_customfieldval = implode(",", $signup_customfieldval);
                                            } 
                                        }
                                        $signup_custom_info .= "<p><strong>";
                                        $signup_custom_info .= get_the_title($signup_custom_filed);
                                        $signup_custom_info .= ": </strong>";
                                        $signup_custom_info .= $signup_customfieldval;
                                        $signup_custom_info .= "</p>";
                                    }
                                }
                                if(!empty($signup_custom_info)){
                                    $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                    $signup_details .= $signup_custom_info;
                                }
                                $total_task = count($get_user_signup_data["task_id".$signupid]);                                
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                for($j=0; $j<$total_task; $j++){ 
                                    $taskid = $get_user_signup_data["task_id".$signupid][$j];                                                                     
                                    $tid = "";
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                    }
                                    else{
                                        $tid = $taskid;
                                    }
                                    $task_date = $get_user_signup_data["task_date".$taskid][0];
                                    $task_time = $get_user_signup_data["task_time".$taskid][0];
                                    $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                    $signup_details .= "<p><strong>Task Name:</strong>";
                                    $signup_details .= get_the_title($tid);
                                    $signup_details .= "</p>";
                                    if(!empty($task_date)){
                                        $signup_details .= "<p><strong>Task Date:</strong>";
                                        $signup_details .= $task_date;
                                        $signup_details .= "</p>";
                                    }
                                    if(!empty($task_time)){
                                        $signup_details .= "<p><strong>Task Time:</strong>";
                                        $signup_details .= $task_time;
                                        $signup_details .= "</p>";
                                    }
                                    $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );                                
                                    $custom_field_info = "";
                                    if(!empty($cpt_custom_fileds)){
                                        foreach($cpt_custom_fileds as $cpt_custom_filed){
                                            $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                            if($type == "text-area"){
                                                $type = "textarea";
                                            }
                                            if($type == "drop-down"){
                                                $type = "select";
                                            }
                                            for($c = 0; $c < $task_max_val; $c++){
                                                $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                $customfieldval = "";
                                                if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                    if($type == "checkbox"){
                                                        $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);
                                                    } 
                                                    else{
                                                        $customfieldval = $get_user_signup_data[$customfieldkey][0];
                                                    }
                                                }
                                                $custom_field_info .= "<p><strong>";
                                                $custom_field_info .= get_the_title($cpt_custom_filed);
                                                $custom_field_info .= ": </strong>";
                                                $custom_field_info .= $customfieldval;
                                                $custom_field_info .= "</p>";
                                            }
                                        }
                                    }                         
                                    if(!empty($custom_field_info)){
                                        $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                        $signup_details .= $custom_field_info;
                                    }
                                }
                                /* get last signup details */
                                //$lastid = $wpdb->insert_id;    

                                $cur_user_obj = get_user_by('id', $user_id);
                                $cuname = $cur_user_obj->first_name . " " . $cur_user_obj->last_name;
                                $first_name = $cur_user_obj->first_name;
                                $last_name = $cur_user_obj->last_name;
                                if(empty($first_name)){
                                    $first_name = $user_info->display_name;
                                } 
                                if(empty($last_name)){
                                    $last_name = $user_info->display_name;
                                }
                                $signup_details .= "</div>";
                                // send "Receipt" to volunteer after they sign up 
                                $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                                $signuptitle = get_the_title($signupid);
                                $to = $cur_user_obj->user_email;
                                $suser = $to;
                                if(!empty($volunteer_after_sign_up)){
                                    $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
                                    if(!empty($mailcontent)){
                                        $arra = array("/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                                        $arra2 = array($cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
                                        $mail = preg_replace($arra, $arra2, $mailcontent);                                
                                        $subject = 'Resent receipt for successfully done signup.';
                                        $body = $mail;                    
                                        $headers = array('Content-Type: text/html; charset=UTF-8');                    
                                        wp_mail( $to, $subject, $body, $headers );
                                    }
                                }                        
                                // send notification to admins                                           
                                
                                $author_id = get_post_field( 'post_author', $signupid );
                                $user_info = get_userdata($author_id);            
                                $to = $user_info->user_email;
                                $admin_name = $user_info->display_name;    
                                $administrators_notifcations = get_option('administrators_notifcations');     

                                $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                                $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
                                $mail = preg_replace($arra, $arra2, $administrators_notifcations);  
                                
                                $body = $mail;
                                $subject = 'You have got the new signup details resent receipt.';
                                                  
                                $headers = array('Content-Type: text/html; charset=UTF-8');    
                                
                                wp_mail( $to, $subject, $body, $headers );  

                                $defult_wording_volunteers = get_option( 'defult_wording_volunteers' );                
                                $mail = preg_replace($arra, $arra2, $defult_wording_volunteers);
                                $body = $mail;    
                                wp_mail( $suser, $subject, $body, $headers );  

                                $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
                                if(!empty($notified_users)){  
                                    foreach($notified_users as $assign_user)
                                    {
                                        $author_obj = get_user_by('id', $assign_user);
                                        $to = $author_obj->user_email;
                                        $admin_name = $author_obj->display_name;
                                        $arra2 = array($admin_name, $cuname, $signup_details, $first_name, $last_name);                                       
                                        $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
                                        $body = $mail;                            
                                        $headers = array('Content-Type: text/html; charset=UTF-8');                            
                                        wp_mail( $to, $subject, $body, $headers );
                                    }
                                } 
                            }
                        }                        
                    endforeach;
                }
            }            
        }
        die();
    }
    /**
    * Show archive/unarchive on my sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_show_archive_unarchive() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;        
        $text = "";
        $table_name = $wpdb->prefix . "signup_orders";        
        if(isset($_POST['text'])){
            
            $text = sanitize_text_field( $_POST['text'] );
            $current_user_id = get_current_user_id();
            $all_user_posts_count = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) );
            
            if(!empty($text)){
                if($text == "Show Archived"){
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id)." AND status = 'archive' order by ID DESC");
                }
                else{
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id)." AND status = 'on' order by ID DESC");
                }
            }   
            ?>
            <div class="pto-my-signup-personal-signup">
                <div class="pto-my-signup-total-count">
                    Personal Sign Up Total: <?php echo count($all_user_posts_count); ?>
                </div>              
            </div>
            <?php   
            if(!empty($all_user_posts)){ 
                foreach($all_user_posts as $key => $post): 
                    $signupid = $post->signup_id;
                    $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                ?>
                    <div class="pto-signup-datewise-block">
                        <div class="pto-signed-up-date">
                        <div class="pto-mysignup-date">
                            <?php echo "Signed Up: ".esc_html_e($post->checkout_date); ?>
                        </div>
                        <div class="pto-mysignup-receipt-btn">
                            <?php if($text == "Show Archived"){ } 
                            else{ 
                                if(!empty($volunteer_after_sign_up)){
                            ?>
                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval($post->ID); ?>" class="pto-signup-resend-receipt front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary">Re-send receipt</button>
                            <?php } } ?>
                        </div>
                        </div>
                        <?php
                            $get_user_signup_data = unserialize($post->order_info);                            
                            if(!empty($get_user_signup_data)){
                                $total_signup = count($get_user_signup_data["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){ 
                                $signupid = $get_user_signup_data["signup_id"][$i];            
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                $total_task = count($get_user_signup_data["task_id".$signupid]);
                                $current_status = get_post_status ( $signupid );
                                ?>
                                <div class="pto-mysignup-block">                                
                                <?php   
                                
                                for($j=0; $j<$total_task; $j++){ 
                                    $tid = "";
                                    $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                    $sdate = "";
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                        $sdate = $taskid_explode[1];
                                        $selected_date_array[$j] = $taskid_explode[1]; 
                                    }
                                    else{
                                        $tid = $taskid;
                                    }
                                    $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                    $desc = get_post_meta($tid,"tasks_comp_desc",true);
                                    $saved_dates = get_post_meta($tid, "pto_signup_task_edit_single".$sdate, true);
                                    if(!empty($saved_dates)){
                                        $desc = $saved_dates["tasks_comp_desc"];
                                    }
                                    $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                                    $datekey = "task_date".$taskid;
                                    $taskdate = "";
                                    $timekey = "task_time".$taskid;
                                    $tasktime = "";
                                    if(array_key_exists($datekey, $get_user_signup_data)){ 
                                        $taskdate = $get_user_signup_data[$datekey][0];
                                    }
                                    if(array_key_exists($timekey, $get_user_signup_data)){ 
                                        $tasktime = $get_user_signup_data[$timekey][0];
                                    }
                                    $add_mins = 0;
                                    $single_post_meta = get_post_meta( $tid, "single_tasks_advance_options", true );
                                    if(array_key_exists("shift",$single_post_meta)){
                                        $shift_meta = $single_post_meta["shift"];
                                        $shift_min = $shift_meta['how-long-shift'];
                                        $add_mins  = $shift_min * 60;
                                        $shifttimes = explode(",", $tasktime);
                                        //print_r($shifttimes);
                                    }
                                    ?>
                                    <div class="pto-singup-task-blocks-list">
                                        <div class="pto-singup-task-block">
                                            <?php if($j == 0){ ?>
                                                <h3><?php esc_html_e(get_the_title($signupid)); ?></h3>
                                            <?php } ?>                           
                                            <h4><?php 
                                                if(!empty($saved_dates)){
                                                    esc_html_e($saved_dates["post_title"]);
                                                }
                                                else{
                                                    esc_html_e(get_the_title($tid));  
                                                }
                                             ?></h4>
                                            <p class="task-desc"><?php print_r($desc); ?></p>                                    
                                        </div>  
                                    <?php 
                                    if(!empty($cpt_custom_fileds)){ ?> 
                                        <div class="pto-singup-task-custom-fields">                                   
                                        <?php 
                                            foreach($cpt_custom_fileds as $cpt_custom_filed){     
                                                $alternet_title = get_post_meta($cpt_custom_filed,"pto_alternate_title",true);
                                                //$instruction = get_post_meta($cpt_custom_filed,"instruction",true);
                                                $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                //$require = get_post_meta($cpt_custom_filed,"pto_field_required",true);
                                                $custom_field_title = "";
                                                if($type == "text-area"){
                                                    $type = "textarea";
                                                }
                                                if($type == "drop-down"){
                                                    $type = "select";
                                                }
                                                if(!empty($alternet_title)){
                                                    $custom_field_title = $alternet_title;
                                                }
                                                else{
                                                    $custom_field_title = get_the_title($cpt_custom_filed);
                                                }
                                                for($c = 0; $c < $task_max_val; $c++){
                                                    $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                    //echo $customfieldkey;
                                                    if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                        $customfieldval = "";
                                                        if($type == "checkbox"){
                                                            $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);
                                                        } 
                                                        else{
                                                            $customfieldval = $get_user_signup_data[$customfieldkey][0];
                                                        } 
                                                        if(!empty($customfieldval)){                                                                    
                                                        ?>
                                                        <p class="pto-custom-field-item"><strong><?php echoesc_html_e($custom_field_title); ?> : </strong> <?php esc_html_e($customfieldval);  ?></p>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            } 
                                            ?>
                                            </div>
                                            <?php
                                        } 
                                        if(!empty($taskdate) || !empty($tasktime)){
                                        ?>
                                        <div class="task-date-time">
                                            <p><?php if(!empty($taskdate)){ ?> <span>*</span>Task Due Date: <?php esc_html_e($taskdate); ?> <?php } if(!empty($tasktime)){ ?> Time: <?php esc_html_e($tasktime); } ?> </p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                                <?php
                            }
                        } 
                        if($text == "Show Archived"){ ?>
                            <div class="pto-mysignup-actions">
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-unarchive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">UnArchive</a> 
                            </div>
                        <?php
                        }else{                         
                        ?>
                            <div class="pto-mysignup-actions">
                                <?php
                                    if($current_status == "publish"){
                                ?>
                                <a href="<?php echo esc_url(get_the_permalink($signupid)); ?>?postid=<?php echo intval($post->ID); ?>" class="pto-signup-edit-mysignup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Edit</a>
                                <?php } ?>
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-archive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Archive</a> 
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                endforeach;
            }
            else{
                ?>
                <div class='no_data'>No data found</div>
                <?php
            }
        }        
        die();
    }
    /**
    * Move sign up to archive
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_to_archive() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        $id = "";
        $text = "";
        $table_name = $wpdb->prefix . "signup_orders";        
        if(isset($_POST['id']) || isset($_POST['text'])){
            $id =  intval( $_POST['id'] );
            $text = sanitize_text_field( $_POST['text'] );
            $current_user_id = get_current_user_id();
            $all_user_posts_count = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) );
            if(!empty($id)){
                //$sql =  $wpdb->prepare( "UPDATE ".$table_name." SET status = 'archive' WHERE ID = ".intval($id) );            
                $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET status = 'archive' WHERE ID = ".intval($id)));
                if($result){
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'on' order by ID DESC");   
                }
            }
            if(!empty($text)){
                if($text == "Show Archived"){
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'on' order by ID DESC");
                }
                else{
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'archive' order by ID DESC");
                }
            }   
            ?>
            <div class="pto-my-signup-personal-signup">
                <div class="pto-my-signup-total-count">
                    Personal Sign Up Total: <?php echo count($all_user_posts_count); ?>
                </div>              
            </div>
            <?php   
            if(!empty($all_user_posts)){ 
                foreach($all_user_posts as $key => $post): 
                    $signupid = $post->signup_id;
                    $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                ?>
                    <div class="pto-signup-datewise-block">
                        <div class="pto-signed-up-date">
                        <div class="pto-mysignup-date">
                            <?php echo "Signed Up: ".esc_html_e($post->checkout_date); ?>
                        </div>
                        <div class="pto-mysignup-receipt-btn">
                            <?php if($text == "Show Archived"){ } 
                                else{ 
                                    if(!empty($volunteer_after_sign_up)){
                            ?>
                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval($post->ID); ?>" class="pto-signup-resend-receipt front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary">Re-send receipt</button>
                            <?php } } ?>
                        </div>
                        </div>
                        <?php
                            $get_user_signup_data = unserialize($post->order_info);                            
                            if(!empty($get_user_signup_data)){
                                $total_signup = count($get_user_signup_data["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){ 
                                $signupid = $get_user_signup_data["signup_id"][$i];            
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                $total_task = count($get_user_signup_data["task_id".$signupid]);
                                $current_status = get_post_status ( $signupid );
                                ?>
                                <div class="pto-mysignup-block">                                
                                <?php   
                                for($j=0; $j<$total_task; $j++){ 
                                    $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                    $tid = "";
                                    $sdate = "";
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                        $sdate = $taskid_explode[1];
                                        $selected_date_array[$j] = $taskid_explode[1]; 
                                    }
                                    else{
                                        $tid = $taskid;
                                    }
                                    $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                    $desc = get_post_meta($tid,"tasks_comp_desc",true);
                                    $saved_dates = get_post_meta($tid, "pto_signup_task_edit_single".$sdate, true);
                                    if(!empty($saved_dates)){
                                        $desc = $saved_dates["tasks_comp_desc"];
                                    }
                                    $cpt_custom_fileds =  get_post_meta( $taskid, "single_task_custom_fields", true );
                                    $datekey = "task_date".$taskid;
                                    $taskdate = "";
                                    $timekey = "task_time".$taskid;
                                    $tasktime = "";
                                    if(array_key_exists($datekey, $get_user_signup_data)){ 
                                        $taskdate = $get_user_signup_data[$datekey][0];
                                    }
                                    if(array_key_exists($timekey, $get_user_signup_data)){ 
                                        $tasktime = $get_user_signup_data[$timekey][0];
                                    }
                                    ?>
                                    <div class="pto-singup-task-blocks-list">
                                    <div class="pto-singup-task-block">
                                        <?php if($j == 0){ ?>
                                            <h3><?php esc_html_e(get_the_title($signupid)); ?></h3>
                                        <?php } ?>                           
                                        <h4><?php 
                                            if(!empty($saved_dates)){
                                                esc_html_e($saved_dates["post_title"]);
                                            }
                                            else{
                                                esc_html_e(get_the_title($tid));  
                                            }
                                         ?></h4>
                                        <p class="task-desc"><?php print_r($desc); ?></p>                                    
                                    </div>                                    
                                    
                                    <?php 
                                    if(!empty($cpt_custom_fileds)){ ?> 
                                        <div class="pto-singup-task-custom-fields">                                   
                                        <?php 
                                            
                                            foreach($cpt_custom_fileds as $cpt_custom_filed){     
                                                $alternet_title = get_post_meta($cpt_custom_filed,"pto_alternate_title",true);
                                                //$instruction = get_post_meta($cpt_custom_filed,"instruction",true);
                                                $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                //$require = get_post_meta($cpt_custom_filed,"pto_field_required",true);
                                                $custom_field_title = "";
                                                if($type == "text-area"){
                                                    $type = "textarea";
                                                }
                                                if($type == "drop-down"){
                                                    $type = "select";
                                                }
                                                if(!empty($alternet_title)){
                                                    $custom_field_title = $alternet_title;
                                                }
                                                else{
                                                    $custom_field_title = get_the_title($cpt_custom_filed);
                                                }
                                                for($c = 0; $c < $task_max_val; $c++){
                                                    $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                    //echo $customfieldkey;
                                                    if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                        $customfieldval = "";
                                                        if($type == "checkbox"){
                                                            $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);
                                                        } 
                                                        else{
                                                            $customfieldval = $get_user_signup_data[$customfieldkey][0];
                                                        }                          
                                                        
                                                        if(!empty($customfieldval)){                                                                    
                                                        ?>
                                                            <p class="pto-custom-field-item"><strong><?php esc_html_e($custom_field_title) ?> : </strong> <?php esc_html_e($customfieldval);  ?></p>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            } 
                                            ?>
                                            </div>
                                            <?php
                                        } 
                                        if(!empty($taskdate) || !empty($tasktime)){
                                        ?>
                                        <div class="task-date-time">
                                            <p><?php if(!empty($taskdate)){ ?> <span>*</span>Task Due Date: <?php esc_html_e($taskdate); ?> <?php } if(!empty($tasktime)){ ?> Time: <?php esc_html_e($tasktime); } ?> </p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                                <?php
                            }
                        } 
                        if($text == "Show Archived"){ ?>
                            <div class="pto-mysignup-actions">
                                <?php
                                    if($current_status == "publish"){
                                ?>
                                <a href="<?php echo esc_url(get_the_permalink($signupid)); ?>?postid=<?php echo intval($post->ID); ?>" class="pto-signup-edit-mysignup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Edit</a>
                                <?php } ?>
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-archive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Archive</a> 
                            </div>                            
                        <?php
                        }else{                         
                        ?>
                            <div class="pto-mysignup-actions">
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-unarchive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">UnArchive</a> 
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                endforeach;
            }
            else{
                ?>
                    <div class='no_data'>No data found</div>
                <?php
            }
        }        
        die();
    }
    /**
    * Move sign up to unarchive
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_to_unarchive() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        $id = "";
        $text = "";
        $table_name = $wpdb->prefix . "signup_orders";        
        if(isset($_POST['id']) || isset($_POST['text'])){
            $id =  intval( $_POST['id'] );
            $text = sanitize_text_field( $_POST['text'] );
            $current_user_id = get_current_user_id();
            $all_user_posts_count = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) );
            if(!empty($id)){
                //$sql =  $wpdb->prepare( "UPDATE ".$table_name." SET status = 'on' WHERE ID = ".$id );            
                $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET status = 'on' WHERE ID = ".intval($id) ));
                if($result){
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'archive' order by ID DESC");   
                }
            }
            if(!empty($text)){
                if($text == "Show Archived"){
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'on' order by ID DESC");
                }
                else{
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'archive' order by ID DESC");
                }
            }   
            ?>
            <div class="pto-my-signup-personal-signup">
                <div class="pto-my-signup-total-count">
                    Personal Sign Up Total: <?php echo count($all_user_posts_count); ?>
                </div>              
            </div>
            <?php   
            if(!empty($all_user_posts)){ 
                foreach($all_user_posts as $key => $post): 
                    $signupid = $post->signup_id;
                    $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                ?>
                    <div class="pto-signup-datewise-block">
                        <div class="pto-signed-up-date">
                        <div class="pto-mysignup-date">
                            Signed Up: 
                            <?php esc_html_e($post->checkout_date); ?>
                        </div>
                        <div class="pto-mysignup-receipt-btn">
                            <?php if($text == "Show Archived"){ } 
                            else{ 
                                if(!empty($volunteer_after_sign_up)){
                            ?>
                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval($post->ID); ?>" class="pto-signup-resend-receipt front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary">Re-send receipt</button>
                            <?php } } ?>
                        </div>
                        </div>
                        <?php
                            $get_user_signup_data = unserialize($post->order_info);                            
                            if(!empty($get_user_signup_data)){
                                $total_signup = count($get_user_signup_data["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){ 
                                $signupid = $get_user_signup_data["signup_id"][$i];            
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                $total_task = count($get_user_signup_data["task_id".$signupid]);
                                $current_status = get_post_status ( $signupid );
                                ?>
                                <div class="pto-mysignup-block">                                
                                <?php   
                                for($j=0; $j<$total_task; $j++){ 
                                    $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                    $tid = "";
                                    $sdate = "";
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                        $sdate = $taskid_explode[1];
                                        $selected_date_array[$j] = $taskid_explode[1]; 
                                    }
                                    else{
                                        $tid = $taskid;
                                    }
                                    $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                    $desc = get_post_meta($tid,"tasks_comp_desc",true);
                                    $saved_dates = get_post_meta($tid, "pto_signup_task_edit_single".$sdate, true);
                                    if(!empty($saved_dates)){
                                        $desc = $saved_dates["tasks_comp_desc"];
                                    }
                                    $cpt_custom_fileds =  get_post_meta( $taskid, "single_task_custom_fields", true );
                                    $datekey = "task_date".$taskid;
                                    $taskdate = "";
                                    $timekey = "task_time".$taskid;
                                    $tasktime = "";
                                    if(array_key_exists($datekey, $get_user_signup_data)){ 
                                        $taskdate = $get_user_signup_data[$datekey][0];
                                    }
                                    if(array_key_exists($timekey, $get_user_signup_data)){ 
                                        $tasktime = $get_user_signup_data[$timekey][0];
                                    }
                                    ?>
                                    <div class="pto-singup-task-blocks-list">
                                    <div class="pto-singup-task-block">
                                        <?php if($j == 0){ ?>
                                            <h3><?php esc_html_e(get_the_title($signupid)); ?></h3>
                                        <?php } ?>                           
                                        <h4><?php 
                                            if(!empty($saved_dates)){
                                                esc_html_e($saved_dates["post_title"]);
                                            }
                                            else{
                                                esc_html_e(get_the_title($tid));  
                                            }
                                         ?></h4>
                                        <p class="task-desc"><?php print_r($desc); ?></p>                                    
                                    </div>                                    
                                    
                                    <?php 
                                    if(!empty($cpt_custom_fileds)){ ?> 
                                        <div class="pto-singup-task-custom-fields">                                   
                                        <?php 
                                            foreach($cpt_custom_fileds as $cpt_custom_filed){     
                                                $alternet_title = get_post_meta($cpt_custom_filed,"pto_alternate_title",true);
                                                //$instruction = get_post_meta($cpt_custom_filed,"instruction",true);
                                                $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                //$require = get_post_meta($cpt_custom_filed,"pto_field_required",true);
                                                $custom_field_title = "";
                                                if($type == "text-area"){
                                                    $type = "textarea";
                                                }
                                                if($type == "drop-down"){
                                                    $type = "select";
                                                }
                                                if(!empty($alternet_title)){
                                                    $custom_field_title = $alternet_title;
                                                }
                                                else{
                                                    $custom_field_title = get_the_title($cpt_custom_filed);
                                                }
                                                for($c = 0; $c < $task_max_val; $c++){
                                                    $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                    //echo $customfieldkey;
                                                    if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                        $customfieldval = "";
                                                        if($type == "checkbox"){
                                                            $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);
                                                        } 
                                                        else{
                                                            $customfieldval = $get_user_signup_data[$customfieldkey][0];
                                                        }                          
                                                        
                                                        if(!empty($customfieldval)){                                                                    
                                                        ?>
                                                        <p class="pto-custom-field-item"><strong><?php esc_html_e($custom_field_title)?> : </strong> <?php esc_html_e($customfieldval);  ?></p>
                                                        <?php
                                                        }
                                                    }
                                                }
                                            } 
                                            ?>
                                            </div>
                                            <?php
                                        } 
                                        if(!empty($taskdate) || !empty($tasktime)){
                                        ?>
                                        <div class="task-date-time">
                                            <p><?php if(!empty($taskdate)){ ?> <span>*</span>Task Due Date: <?php esc_html_e($taskdate); ?> <?php } if(!empty($tasktime)){ ?> Time: <?php esc_html_e($tasktime); } ?> </p>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                                <?php
                            }
                        } 
                        if($text == "Show Archived"){ ?>
                            <div class="pto-mysignup-actions">
                                <?php
                                    if($current_status == "publish"){
                                ?>
                                <a href="<?php echo esc_url(get_the_permalink($signupid)); ?>?postid=<?php echo intval($post->ID); ?>" class="pto-signup-edit-mysignup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Edit</a>
                                <?php } ?>
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-archive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Archive</a> 
                            </div>                            
                        <?php
                        }else{                         
                        ?>
                            <div class="pto-mysignup-actions">
                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                <a href="#0" class="pto-signup-moveto-unarchive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">UnArchive</a> 
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                endforeach;
            }
            else{
                ?>
                    <div class='no_data'>No data found</div>
                <?php
            }
        }        
        die();
    }
    /**
    * Remove from my sign up 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_remove_my_signup() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
      
        global $wpdb;
        $id = "";  
        $text = "";      
        $table_name = $wpdb->prefix . "signup_orders";
        if(isset($_POST['id'])){
            $id =  intval( $_POST['id'] );  
            $text = sanitize_text_field( $_POST['text'] );
            $current_user_id = get_current_user_id();
            if(!empty($id)){                         
                $all_data =  $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE ID = ".intval($id) );
                if(!empty($all_data)){               
                    foreach($all_data as $key => $post):
                        $get_user_signup_data = unserialize($post->order_info);                     
                        if(!empty($get_user_signup_data)){
                            $total_signup = count($get_user_signup_data["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){ 
                                $signupid = $get_user_signup_data["signup_id"][$i];            
                                $total_task = count($get_user_signup_data["task_id".$signupid]);
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                for($j=0; $j<$total_task; $j++){ 
                                    $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                    $tid = $taskid;
                                    
                                    $task_maxval = $get_user_signup_data["task_max".$taskid][0];
                                    $task_hours_points = $get_user_signup_data["task_hours_points".$taskid][0];
                                    $task_date = $get_user_signup_data["task_date".$taskid][0];
                                    $task_time = $get_user_signup_data["task_time".$taskid][0];
                                    //to fill task availability 
                                    $get_filled = get_post_meta( $tid, "signup_task_availability", true );
                                    if(!empty($get_filled)){
                                        $get_filled = $get_filled - $task_maxval;
                                        update_post_meta( $tid, "signup_task_availability", $get_filled );
                                    }  
                                    // to store max value of task for user
                                    $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                                    $max_key = $signupid."_".$taskid;
                                    $maxval = $get_max_user_task_signup[$max_key];
                                    if(!empty($get_max_user_task_signup)){
                                        $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $task_maxval;

                                         update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                                    } 
                                    // to add task hours/points to user                                 
                                    if(!empty($task_hours_points)){                 
                                        $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                                        if(!empty($get_user_task_hours)){
                                            $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
                                             update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                                        }
                                    }
                                    // to add user to manage volunteers
                                    $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );                                
                                    $selected_date = get_post_meta( $tid,"pto_sign_up_selected_date_time",true);
                                    
                                    if(!empty($selected_date)){
                                        unset($selected_date[$current_user_id][$task_date]);
                                         update_post_meta($tid,"pto_sign_up_selected_date_time",$selected_date);
                                    }
                                    $selected_date = get_post_meta( $tid,"pto_sign_up_selected_date_time",true);                                
                                }
                            }
                        }
                    endforeach;

                  
                    $sql =  $wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".intval($id) ); 
                    $result = $wpdb->query($sql);
                    if($result){ 
                        $all_user_posts_count = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) );
                        ?>
                        <div class="pto-my-signup-personal-signup">
                            <div class="pto-my-signup-total-count">
                                Personal Sign Up Total: <?php echo intval(count($all_user_posts_count)); ?>
                            </div>              
                        </div>  
                        <?php
                        if(!empty($text)){
                            if($text == "Show Archived"){
                                $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'on' order by ID DESC");
                            }
                            else{
                                $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".intval($current_user_id) ." AND status = 'archive' order by ID DESC");
                            }
                        }                   
                        //$all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id = ".$current_user_id ." AND status = 'on' ");   
                        if(!empty($all_user_posts)){ 
                            foreach($all_user_posts as $key => $post): 
                                $signupid = $post->signup_id;
                                $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                            ?>
                                <div class="pto-signup-datewise-block">
                                    <div class="pto-signed-up-date">
                                    <div class="pto-mysignup-date">
                                        <?php echo "Signed Up: ".esc_html_e($post->checkout_date); ?>
                                    </div>
                                    <?php if(!empty($volunteer_after_sign_up)){ ?>
                                        <div class="pto-mysignup-receipt-btn">                                        
                                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval($post->ID); ?>" class="pto-signup-resend-receipt front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary">Re-send receipt</button>
                                        </div>
                                    <?php } ?>
                                    </div>
                                    <?php
                                        $get_user_signup_data = unserialize($post->order_info);                            
                                        if(!empty($get_user_signup_data)){
                                            $total_signup = count($get_user_signup_data["signup_id"]);
                                        for($i=0; $i<$total_signup; $i++){ 
                                            $signupid = $get_user_signup_data["signup_id"][$i];            
                                            $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                            $total_task = count($get_user_signup_data["task_id".$signupid]);
                                            $current_status = get_post_status ( $signupid );
                                            ?>
                                            <div class="pto-mysignup-block">                                
                                            <?php   
                                            for($j=0; $j<$total_task; $j++){ 
                                                $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                                $tid = "";
                                                $sdate = "";
                                                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                                    $taskid_explode = explode("_", $taskid);
                                                    $tid = $taskid_explode[0];
                                                    $sdate = $taskid_explode[1];
                                                    $selected_date_array[$j] = $taskid_explode[1]; 
                                                }
                                                else{
                                                    $tid = $taskid;
                                                }
                                                $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                                $desc = get_post_meta($tid,"tasks_comp_desc",true);
                                                $saved_dates = get_post_meta($tid, "pto_signup_task_edit_single".$sdate, true);
                                                if(!empty($saved_dates)){
                                                    $desc = $saved_dates["tasks_comp_desc"];
                                                }
                                                $cpt_custom_fileds =  get_post_meta( $taskid, "single_task_custom_fields", true );
                                                $datekey = "task_date".$taskid;
                                                $taskdate = "";
                                                $timekey = "task_time".$taskid;
                                                $tasktime = "";
                                                if(array_key_exists($datekey, $get_user_signup_data)){ 
                                                    $taskdate = $get_user_signup_data[$datekey][0];
                                                }
                                                if(array_key_exists($timekey, $get_user_signup_data)){ 
                                                    $tasktime = $get_user_signup_data[$timekey][0];
                                                }
                                                ?>
                                                <div class="pto-singup-task-blocks-list">
                                                <div class="pto-singup-task-block">
                                                    <?php if($j == 0){ ?>
                                                        <h3><?php esc_html_e(get_the_title($signupid)); ?></h3>
                                                    <?php } ?>                                                                        
                                                    <h4><?php 
                                                        if(!empty($saved_dates)){
                                                            esc_html_e($saved_dates["post_title"]);
                                                        }
                                                        else{
                                                            esc_html_e(get_the_title($tid));  
                                                        }
                                                     ?></h4>
                                                    <p class="task-desc"><?php print_r($desc); ?></p>                                    
                                                </div>                                    
                                                <?php 
                                                if(!empty($cpt_custom_fileds)){ ?> 
                                                    <div class="pto-singup-task-custom-fields">                                   
                                                    <?php 
                                                        foreach($cpt_custom_fileds as $cpt_custom_filed){     
                                                            $alternet_title = get_post_meta($cpt_custom_filed,"pto_alternate_title",true);
                                                            //$instruction = get_post_meta($cpt_custom_filed,"instruction",true);
                                                            $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                            //$require = get_post_meta($cpt_custom_filed,"pto_field_required",true);
                                                            $custom_field_title = "";
                                                            if($type == "text-area"){
                                                                $type = "textarea";
                                                            }
                                                            if($type == "drop-down"){
                                                                $type = "select";
                                                            }
                                                            if(!empty($alternet_title)){
                                                                $custom_field_title = $alternet_title;
                                                            }
                                                            else{
                                                                $custom_field_title = get_the_title($cpt_custom_filed);
                                                            }
                                                            for($c = 0; $c < $task_max_val; $c++){
                                                                $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;
                                                                //echo $customfieldkey;
                                                                if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                                    $customfieldval = "";
                                                                    if($type == "checkbox"){
                                                                        $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);
                                                                    } 
                                                                    else{
                                                                        $customfieldval = $get_user_signup_data[$customfieldkey][0];
                                                                    }                          
                                                                    
                                                                    if(!empty($customfieldval)){                                                                    
                                                                    ?>
                                                                    <p class="pto-custom-field-item"><strong><?php esc_html_e($custom_field_title) ?> : </strong> <?php esc_html_e($customfieldval);  ?></p>
                                                                    <?php
                                                                    }
                                                                }
                                                            }
                                                        } 
                                                        ?>
                                                        </div>
                                                        <?php
                                                    } 
                                                    if(!empty($taskdate) || !empty($tasktime)){
                                                    ?>
                                                    <div class="task-date-time">
                                                        <p><?php if(!empty($taskdate)){ ?> <span>*</span>Task Due Date: <?php esc_html_e($taskdate); ?> <?php } if(!empty($tasktime)){ ?> Time: <?php esc_html_e($tasktime); } ?> </p>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                            <?php
                                        }
                                    } 
                                    if($text == "Show Archived"){ ?>
                                        <div class="pto-mysignup-actions">
                                            <?php
                                                if($current_status == "publish"){
                                            ?>
                                            <a href="<?php echo esc_url(get_the_permalink($signupid)); ?>?postid=<?php echo intval($post->ID); ?>" class="pto-signup-edit-mysignup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Edit</a>
                                            <?php } ?>
                                            <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                            <a href="#0" class="pto-signup-moveto-archive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Archive</a> 
                                        </div>                            
                                    <?php
                                    }else{                         
                                    ?>
                                        <div class="pto-mysignup-actions">
                                            <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">Remove From Sign Up</a>
                                            <a href="#0" class="pto-signup-moveto-unarchive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval($post->ID); ?>">UnArchive</a> 
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php
                            endforeach;
                        }
                        else{
                            ?>
                                <div class='no_data'>No data found</div>
                            <?php
                        }
                    } 
                    else{
                        ?>
                            error in removing signup
                        <?php
                        
                    }
                } 
            }
        }        
        die();
    }
    /**
    * Sign up header cart
    * @since    1.0.0
    * @access   public
    **/
   
   
    /**
    * Log out form checkout
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_logout_from_checkout() {
        if( array_key_exists( "pto_signup_tasks_cart", $_SESSION ) ) {
            unset( $_SESSION['pto_signup_tasks_cart'] );
        }
    }
    /**
    * View sign up task volunteer
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_view_signup_tasks_volunteers() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST['singup_id'])){
            $signupid =  intval( $_POST['singup_id'] );
        }
        $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
        ?>
        <div class="table-responsive">
        <table class="wp-list-table widefat fixed striped table-view-list posts">
            <thead>
                <tr>
                    <th><?php esc_html_e('Name',PTO_SIGN_UP_TEXTDOMAIN);?></th>
                    <th><?php esc_html_e('Task/Slot',PTO_SIGN_UP_TEXTDOMAIN);?></th>
                    <th><?php esc_html_e('Date & Time',PTO_SIGN_UP_TEXTDOMAIN);?></th>
                   
                </tr>
            </thead>
            <tbody>
                <?php   
                    global $wpdb;
                    $table_name = $wpdb->prefix . "signup_orders";              
                    $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE signup_id = ".intval($signupid) ." AND status = 'on' order by ID DESC");
                   
                    //$get_manage_volunters = get_post_meta($signupid,"pto_get_manage_volunteers",true);    
                    //print_r($all_user_posts);                 
                    if(!empty($all_user_posts)){
                        
                        foreach($all_user_posts as $userkey => $post){
                            $key = $post->user_id;
                            $get_user_signup_data = unserialize($post->order_info);
                            $task_date = "";
                            $task_time = "";
                            $task_date = $post->checkout_date;
                            $total_task = count($get_user_signup_data["task_id".$signupid]);
                            
                            for($j=0; $j<$total_task; $j++){
                                $ids = $get_user_signup_data["task_id".$signupid][$j];
                                // $task_date = $get_user_signup_data["task_date".$ids][0];
                                // $task_time = $get_user_signup_data["task_time".$ids][0];
                                $task_max_val = $get_user_signup_data["task_max".$ids][0];
                                $task_hours_points = $get_user_signup_data["task_hours_points".$ids][0];
                                $tid = "";
                                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                    $taskid_explode = explode("_", $ids);
                                    $tid = $taskid_explode[0];
                                }
                                else{
                                    $tid = $ids;
                                }
                                $title = get_the_title($tid);
                                ?>
                                <tr>
                                <?php
                                
                                $user = get_user_by("id",$key);
                                ?>
                                    <td><?php echo esc_html($user->display_name); ?></td>
                                    <td><?php echo esc_html($title); ?></td>
                                    <td><?php echo esc_html($task_date); ?>&nbsp; <?php echo esc_html($task_time); ?></td>
                                </tr>
                                <?php
                                
                            }
                           
                        }
                    }                    
                    else{
                        ?>
                        <tr>
                            <td colspan='4'>No volunteers found</td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
        </div>
        <?php
        die();
    }
    /**
    * Remove sign up from checkout
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_remove_signup_checkout() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST['signupid'])){
            $signupid =  intval( $_POST['signupid'] ); 
            if (!session_id()) {
                session_start();
            } 
            $get_user_cart = array();
            $get_user_cart = filter_var_array($_SESSION['pto_signup_tasks_cart']); 
            unset($get_user_cart[$signupid]);
            $_SESSION['pto_signup_tasks_cart'] = $get_user_cart;
        }
        die();
    }
    /**
    * Task sorting 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_tasks_sorting() { 
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        
        if(isset($_POST['sortval']) && isset($_POST['signupid'])){
            $sortval =  sanitize_text_field( $_POST['sortval'] );
            $signupid =  sanitize_text_field( $_POST['signupid'] );
        }
        $selected_dates = "";
        $emptyremoved = "";
        $dcount = 0;
        if(isset($_POST['selected_dates'])){
            $selected_dates = explode(",", sanitize_text_field($_POST['selected_dates']));
            $emptyremoved = array_filter($selected_dates);
            $dcount = count($emptyremoved);
        }
        $c_user_id = get_current_user_id();
        $pto_sign_up_occurrence =  get_post_meta($signupid, "pto_sign_up_occurrence", true);
        
        $specific_day = "";        
        if(array_key_exists("occurrence-specific", $pto_sign_up_occurrence)){
            $specific_day = get_post_meta($signupid,"occurrence_specific_days",true);
        }
        $get_task_slots = get_post_meta( $signupid, "pto_signups_task_slots", true );        
        $post_id = $signupid;        
        $categories_colspan_show =  get_post_meta($post_id,"categories_colspan_show",true);
        $number_of_slots =  get_post_meta($post_id,"number_of_slots",true);
        $cat_colspan =  get_post_meta($post_id, "categories_colspan", true);
        $collapsed = "cat-expand";         
        if(!empty($cat_colspan)){ 
            $collapsed = "cat-collapsed";
        } 
        
        $chkuid = 0;
        if(isset($_REQUEST["uid"])){                                        
            $chkuser = get_userdata( $c_user_id );
            $user_roles = $chkuser->roles;
            $author_id = get_post_field( 'post_author', $post_id );
            $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true);
            if(empty($get_user_req_post)){ 
                $get_user_req_post = array();                                       
            }
            if($c_user_id == $author_id || in_array($c_user_id , $get_user_req_post) || in_array("administrator", $user_roles) || in_array("sign_up_plugin_administrators", $user_roles)){
                $c_user_id = intval($_REQUEST["uid"]);
            }
            else{
                $chkuid = 1;
            }
        }
        if($sortval == "category"){
            //print_r($get_task_slots);
            $categories = array();
            $chk_time = "";
            foreach($get_task_slots as $get_task_slot)
            {
                $post_details = get_post( $get_task_slot );
                $category_detail = get_the_terms( $get_task_slot  , 'TaskCategories' );                
                $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                if(!empty($category_detail)){                                       
                    foreach($category_detail as $category_details){
                        if(array_key_exists( $category_details->term_id, $categories )){
                        }
                        else{
                            $categories[$category_details->term_id] .= $category_details->name;
                        }
                    }
                } 
                if(!empty($single_post_meta)){ 
                    if(array_key_exists("single",$single_post_meta)) {
                        if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single'])){
                            $chk_time .= $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
                        }
                    }
                }               
            }
            //print_r($categories);            
            if(!empty($categories)){
                foreach($categories as $arkey => $arval)
                {
                    $taskcounts = 0;
                    ?>
                    <div class="single-signup-task-list-table pto-signup-cat-text-color pto-signup-cat-background-color <?php esc_html_e($collapsed); ?>">
                        <h3><?php esc_html_e($arval); ?></h3>
                        <div class="table-responsive">
                        <?php if($chkuid == 0){  ?>
                        <table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
                            <thead>
                                <tr>    
                                    <th onclick="sortTable(0)">Task Name</th>                                               
                                    <?php if(!empty($emptyremoved) && array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){ ?>
                                    <th onclick="sortTable(1)">Date</th>
                                    <?php } ?>
                                    <th onclick="sortTable(2)" <?php if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time)){ ?> style="display:none;" <?php } ?>>Time</th>
                                    
                                    <th>Availability</th>
                                    <th>Sign Up</th>                        
                                </tr>
                            </thead>
                            <tbody class="pto-signup-tasks">
                                <?php 
                                    if(!empty($emptyremoved) && array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        //print_r($emptyremoved);
                                        foreach($get_task_slots as $get_task_slot)
                                        {
                                            for($dt=0;$dt<$dcount;$dt++)
                                            {
                                                $filled = 0;
                                                $post_details = get_post( $get_task_slot );
                                                //print_r($post_details);
                                                $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                                //print_r($single_post_meta);
                                                $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );                                        
                                                $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                                                             
                                               
                                                //print_r($category_detail);                                        
                                                $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );                                        
                                                $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                                $avdate = $get_task_slot."_".$emptyremoved[$dt];
                                                $cat_name = "";
                                                $term_id = array();
                                                $sdate = $emptyremoved[$dt];
                                                $saved_dates = get_post_meta($get_task_slot, "pto_signup_task_edit_single".$sdate, true);
                                                if(!empty($saved_dates)){
                                                    $desc = $saved_dates["tasks_comp_desc"];
                                                }
                                              
                                                //print_r($term_id);
                                                if (in_array($arkey, $term_id))
                                                {     
                                                    $current_status = get_post_status ( $get_task_slot );
                                                    if($current_status == "publish"){      
                                                        $taskcounts++;                                 
                                                ?>
                                                    <tr <?php if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){ ?> class="extra-tr" <?php } ?>>  
                                                        <td>
                                                            <?php 
                                                            if(!empty($saved_dates)){
                                                                esc_html_e($saved_dates["post_title"]);
                                                            }
                                                            else{
                                                                esc_html_e($post_details->post_title); 
                                                            } 
                                                            if(!empty($desc)){ ?>
                                                            <a href="#0" class="pto-task-desc" >details</a>
                                                            <div class="pto-task-content pto-modal" style="display:none;">
                                                                <div class="pto-modal-content">
                                                                    <div class="pto-modal-container-header">
                                                                        <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                                                        <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                                    </div>
                                                                    <div class="pto-modal-container">
                                                                        <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                                                        <td><?php esc_html_e(date("F jS Y", strtotime($emptyremoved[$dt]))); ?></td>
                                                        <td <?php if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time)){ ?> style="display:none;" <?php } ?>>
                                                        <?php  
                                                            if(!empty($single_post_meta)){ 
                                                                if(array_key_exists("single",$single_post_meta)){
                                                                    if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                                    {
                                                                        if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                                            esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                                                        }                                                           
                                                                    }
                                                                }
                                                                
                                                            } 
                                                        ?>  
                                                        </td>
                                                      
                                                        <td>
                                                            <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php esc_html_e($emptyremoved[$dt]); ?>"  />
                                                            <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                                esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                                            <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                                            <?php                                                       
                                                                if(!empty($get_filed)){
                                                                    $get_availability = get_post_meta( $get_task_slot, "signup_task_availability".$avdate, true );
                                                                    $diff = 0;
                                                                    if(array_key_exists("single",$get_filed)){
                                                                        $total_volantears = $get_filed['single']["how_money_volunteers"];
                                                                        $total_volantears_sign_ups = $get_filed['single']["how_money_volunteers_sign_ups"];
                                                                        if($total_volantears == "")
                                                                        {
                                                                            $total_volantears = 0;
                                                                        }
                                                                        if($total_volantears_sign_ups == "")
                                                                        {
                                                                            $total_volantears_sign_ups = 0;
                                                                        }
                                                                        //$total = $total_volantears * $total_volantears_sign_ups;
                                                                        $total = $total_volantears;
                                                                        if(!empty($get_availability)){
                                                                            ?>
                                                                            <b>
                                                                                <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                            </b>
                                                                            <?php
                                                                            if($get_availability == $total){
                                                                                $filled = 1;
                                                                                ?>
                                                                                    <span> filled</span>
                                                                                <?php
                                                                            }else{
                                                                                $diff = $total - $get_availability;
                                                                            }
                                                                            
                                                                        }else{
                                                                            ?>
                                                                            <b>
                                                                                0 / <?php echo intval($total); ?>
                                                                            </b>
                                                                            <?php
                                                                        }
                                                                    }else if(array_key_exists("shift",$get_filed)){
                                                                        //print_r($get_filed);
                                                                        $total_volantears= $get_filed['shift']["volunteers_shift"];
                                                                        $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                                                        $shift_meta = $get_filed["shift"];
                                                                        $count = 0;
                                                                        if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                            $shift_start = $shift_meta['first-shift'];
                                                                            $shift_end = $shift_meta['last-end-shift'];
                                                                            $shift_min = $shift_meta['how-long-shift'];
                                                                            $break_time = $shift_meta['between-shift-minutes'];
                                                                            $array_of_time = array();
                                                                            $start_time    = strtotime ($shift_start); 
                                                                            $end_time      = strtotime ($shift_end);
                                                                            $add_mins  = $shift_min * 60;
                                                                            $break_min = $break_time * 60; 
                                                                            $i = 0;                                 
                                                                            while ($start_time <= $end_time) {                                                                                                                                              
                                                                                $array_of_time[$i] = date ("h:i A", $start_time);
                                                                                $start_time += ($add_mins + $break_min);
                                                                                $count++;
                                                                                $i++;
                                                                            }
                                                                        }
                                                                        if($total_volantears == "")
                                                                        {
                                                                            $total_volantears = 0;
                                                                        }
                                                                        if($total_volantears_sign_ups == "")
                                                                        {
                                                                            $total_volantears_sign_ups = 0;
                                                                        }
                                                                        $end_val = strtotime(end($array_of_time));
                                                                        if($end_val == $end_time){
                                                                            if($count != 0){
                                                                                $count = $count - 1;
                                                                            }
                                                                        }                                                       
                                                                        
                                                                        $total = $count * $total_volantears;
                                                                        if(!empty($get_availability)){
                                                                            ?>
                                                                            <b>
                                                                                <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                            </b>
                                                                            <?php
                                                                            if($get_availability == $total){
                                                                                $filled = 1;
                                                                                ?>
                                                                                <span> filled</span>
                                                                                <?php
                                                                            }else{
                                                                                $diff = $total - $get_availability;
                                                                            }
                                                                        }else{
                                                                            ?>
                                                                            <b>
                                                                                0 /  <?php echo intval($total); ?>
                                                                            </b>
                                                                            <?php 
                                                                        }
                                                                    }
                                                                }else{
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
                                                                    $get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time'.$avdate, true ); 
                                                                    if(!empty($get_shift_time)){
                                                                        //print_r($get_shift_time);
                                                                        if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                            $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                            //print_r($current_user_shift);
                                                                        }
                                                                        foreach($get_shift_time as $uid){
                                                                            //print_r($uid);
                                                                            $all_shifts .= $uid;
                                                                        }
                                                                        $shift_meta_time = explode(",", $all_shifts);
                                                                        //print_r($shift_meta_time);
                                                                    }                                                       
                                                                    $usermax = 0;
                                                                    if($c_user_id != 0){
                                                                        $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                                                        if(!empty($get_max_user_task_signup)){
                                                                            $max_key = $signupid."_".$avdate;                                                               
                                                                            if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                                $usermax = $get_max_user_task_signup[$max_key]; 
                                                                                if($diff == 1){
                                                                                }else{
                                                                                    $diff = $total_volantears_sign_ups - $usermax;
                                                                                }
                                                                                if($usermax == $total_volantears_sign_ups){
                                                                                    $diff = 0;
                                                                                }                                                                           
                                                                            }
                                                                        }                                                               
                                                                    }                                                           
                                                                                                                                
                                                                    $max = 1;
                                                                    if($diff != 0 && $diff < $total_volantears_sign_ups){
                                                                        $max = $diff;
                                                                    }
                                                                    else{
                                                                        $max = $total_volantears_sign_ups;
                                                                    }
                                                                    if($total_volantears_sign_ups > $total){
                                                                        $max = $total;
                                                                    }
                                                                    if(!empty($get_availability) && $diff == 0){
                                                                        $max = 0;
                                                                    }                                                           
                                                                ?>
                                                                <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                                <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                                                <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                                                        if(($filled == 1 || $max == 1)){
                                                                            ?>                                                      
                                                                                <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                                    <option value="1">1</option>
                                                                                </select>
                                                                                <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                            <?php
                                                                        }else{
                                                                    ?>
                                                                    <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                                        <?php                                                                   
                                                                            for($i=0; $i<=$max; $i++){ ?>
                                                                            <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                            <?php
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                <?php } }else{ ?>                                                       
                                                                <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                    <option value="1">1</option>
                                                                </select>
                                                                <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                                                <?php } 
                                                                
                                                                if( array_key_exists( "shift", $single_post_meta ) ){
                                                                    $shift_meta = $single_post_meta["shift"];
                                                                    if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                        $shift_count = count($array_of_time);   
                                                                        //print_r($shift_meta_time);                                                                                                                    
                                                                        ?>
                                                                        <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                                            <span class="span-choose-shift">Choose a shift:</span>
                                                                            <ul class="checkbox-list">  
                                                                                <li>
                                                                                    <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                                                    <label class="choose-shift">Choose a shift:</label>
                                                                                </li>
                                                                                <?php                                                                       
                                                                                    $i = 0;                                                                     
                                                                                    while ($i < $shift_count) { 
                                                                                        $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                                        if(!empty($shift_meta_time)){
                                                                                            if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                                ?>
                                                                                                <li>
                                                                                                    <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                    <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                </li>
                                                                                                <?php
                                                                                            }   
                                                                                            elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                            }                                                                           
                                                                                            elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                                $count_values = array_count_values($shift_meta_time);
                                                                                                $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                                if($this_shift_count == $total_volantears){
                                                                                                    ?>
                                                                                                    <li>
                                                                                                        <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                    </li>
                                                                                                    <?php
                                                                                                }
                                                                                                elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                            else{ 
                                                                                                ?>
                                                                                                <li>
                                                                                                    <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                    <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                </li>
                                                                                                <?php
                                                                                            }                                                                               
                                                                                        }
                                                                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                        }
                                                                                        else{
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
                                                                    }else{ 
                                                                    
                                                                    } ?>
                                                                    <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
                                                            </div>  
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                    }
                                                } 
                                            }
                                        }
                                    }
                                    else{
                                        foreach($get_task_slots as $get_task_slot)
                                        {
                                            $filled = 0;
                                            $post_details = get_post( $get_task_slot );
                                            //print_r($post_details);
                                            $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                            //print_r($single_post_meta);
                                            $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );                                        
                                            $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                                                             
                                           
                                            //print_r($category_detail);                                        
                                            $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );                                        
                                            $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                            
                                            $cat_name = "";
                                            $term_id = array();
                                    
                                            
                                            //print_r($term_id);
                                            if (in_array($arkey, $term_id))
                                            {     
                                                $current_status = get_post_status ( $get_task_slot );
                                                if($current_status == "publish"){      
                                                    $taskcounts++;                                 
                                            ?>
                                                <tr <?php if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){ ?> class="extra-tr" <?php } ?>>  
                                                    <td>
                                                        <?php 
                                                        esc_html_e($post_details->post_title); 
                                                        if(!empty($desc)){ ?>
                                                        <a href="#0" class="pto-task-desc" >details</a>
                                                        <div class="pto-task-content pto-modal" style="display:none;">
                                                            <div class="pto-modal-content">
                                                                <div class="pto-modal-container-header">
                                                                    <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                                                    <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                                </div>
                                                                <div class="pto-modal-container">
                                                                    <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                                                    <!--<td <?php //if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){ ?> style="display:none;" <?php //} ?>><?php //if(!empty($specific_day)){ echo date("F jS", strtotime($specific_day)); } ?></td>-->
                                                    <td <?php if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time)){ ?> style="display:none;" <?php } ?>>
                                                    <?php  
                                                        if(!empty($single_post_meta)){ 
                                                            if(array_key_exists("single",$single_post_meta)){
                                                                if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                                {
                                                                    if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                                        esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                                                    }                                                           
                                                                }
                                                            }
                                                            
                                                        } 
                                                    ?>  
                                                    </td>
                                                   
                                                    <td>
                                                        <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php if(!empty($specific_day)){ esc_html_e($specific_day); } ?>"  />
                                                        <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                            esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                                        <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                                        <?php                                                       
                                                            if(!empty($get_filed)){
                                                                $get_availability = get_post_meta( $get_task_slot, "signup_task_availability", true );
                                                                $diff = 0;
                                                                if(array_key_exists("single",$get_filed)){
                                                                    $total_volantears = $get_filed['single']["how_money_volunteers"];
                                                                    $total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    //$total = $total_volantears * $total_volantears_sign_ups;
                                                                    $total = $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                        echo "<b>".intval($get_availability)."/".intval($total)."</b>";
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                            <span> filled</span>
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        }
                                                                        
                                                                    }else{
                                                                        ?>
                                                                        <b>0 /  <?php echo intval($total); ?>
                                                                        <?php
                                                                        
                                                                    }
                                                                }else if(array_key_exists("shift",$get_filed)){
                                                                    //print_r($get_filed);
                                                                    $total_volantears= $get_filed['shift']["volunteers_shift"];
                                                                    $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                                                    $shift_meta = $get_filed["shift"];
                                                                    $count = 0;
                                                                    if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                        $shift_start = $shift_meta['first-shift'];
                                                                        $shift_end = $shift_meta['last-end-shift'];
                                                                        $shift_min = $shift_meta['how-long-shift'];
                                                                        $break_time = $shift_meta['between-shift-minutes'];
                                                                        $array_of_time = array();
                                                                        $start_time    = strtotime ($shift_start); 
                                                                        $end_time      = strtotime ($shift_end);
                                                                        $add_mins  = $shift_min * 60;
                                                                        $break_min = $break_time * 60; 
                                                                        $i = 0;                                 
                                                                        while ($start_time <= $end_time) {                                                                                                                                              
                                                                            $array_of_time[$i] = date ("h:i A", $start_time);
                                                                            $start_time += ($add_mins + $break_min);
                                                                            $count++;
                                                                            $i++;
                                                                        }
                                                                    }
                                                                    if($total_volantears == "")
                                                                    {
                                                                        $total_volantears = 0;
                                                                    }
                                                                    if($total_volantears_sign_ups == "")
                                                                    {
                                                                        $total_volantears_sign_ups = 0;
                                                                    }
                                                                    $end_val = strtotime(end($array_of_time));
                                                                    if($end_val == $end_time){
                                                                        if($count != 0){
                                                                            $count = $count - 1;
                                                                        }
                                                                    }                                                       
                                                                    
                                                                    $total = $count * $total_volantears;
                                                                    if(!empty($get_availability)){
                                                                       ?>
                                                                        <b>
                                                                            <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                        </b>
                                                                       <?php
                                                                        if($get_availability == $total){
                                                                            $filled = 1;
                                                                            ?>
                                                                            <span> filled</span>
                                                                            <?php
                                                                        }else{
                                                                            $diff = $total - $get_availability;
                                                                        }
                                                                    }else{
                                                                        ?>
                                                                        <b>
                                                                            0 / <?php echo intval($total); ?>
                                                                        </b>
                                                                        <?php 
                                                                        
                                                                    }
                                                                }
                                                            }else{
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
                                                                if(!empty($get_shift_time)){
                                                                    //print_r($get_shift_time);
                                                                    if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                        $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                        //print_r($current_user_shift);
                                                                    }
                                                                    foreach($get_shift_time as $uid){
                                                                        //print_r($uid);
                                                                        $all_shifts .= $uid;
                                                                    }
                                                                    $shift_meta_time = explode(",", $all_shifts);
                                                                    //print_r($shift_meta_time);
                                                                }                                                       
                                                                $usermax = 0;
                                                                if($c_user_id != 0){
                                                                    $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                                                    if(!empty($get_max_user_task_signup)){
                                                                        $max_key = $post_id."_".$get_task_slot;                                                             
                                                                        if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                            $usermax = $get_max_user_task_signup[$max_key]; 
                                                                            if($diff == 1){
                                                                            }else{
                                                                                $diff = $total_volantears_sign_ups - $usermax;
                                                                            }
                                                                            if($usermax == $total_volantears_sign_ups){
                                                                                $diff = 0;
                                                                            }                                                                           
                                                                        }
                                                                    }                                                               
                                                                }                                                           
                                                                                                                            
                                                                $max = 1;
                                                                if($diff != 0 && $diff < $total_volantears_sign_ups){
                                                                    $max = $diff;
                                                                }
                                                                else{
                                                                    $max = $total_volantears_sign_ups;
                                                                }
                                                                if($total_volantears_sign_ups > $total){
                                                                    $max = $total;
                                                                }
                                                                if(!empty($get_availability) && $diff == 0){
                                                                    $max = 0;
                                                                }                                                           
                                                            ?>
                                                            <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                            <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                                            <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                                                    if(($filled == 1 || $max == 1)){
                                                                        ?>                                                      
                                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                                <option value="1">1</option>
                                                                            </select>
                                                                            <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                        <?php
                                                                    }else{
                                                                ?>
                                                                <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                                    <?php                                                                   
                                                                        for($i=0; $i<=$max; $i++){ ?>
                                                                        <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                        <?php
                                                                        }
                                                                    ?>
                                                                </select>
                                                                <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                            <?php } }else{ ?>                                                       
                                                            <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                <option value="1">1</option>
                                                            </select>
                                                            <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                                            <?php } 
                                                            
                                                            if( array_key_exists( "shift", $single_post_meta ) ){
                                                                $shift_meta = $single_post_meta["shift"];
                                                                if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                    $shift_count = count($array_of_time);   
                                                                    //print_r($shift_meta_time);                                                                                                                    
                                                                    ?>
                                                                    <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                                        <span class="span-choose-shift">Choose a shift:</span>
                                                                        <ul class="checkbox-list">  
                                                                            <li>
                                                                                <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                                                <label class="choose-shift">Choose a shift:</label>
                                                                            </li>
                                                                            <?php                                                                       
                                                                                $i = 0;                                                                     
                                                                                while ($i < $shift_count) { 
                                                                                    $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                                    if(!empty($shift_meta_time)){
                                                                                        if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }   
                                                                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                        }                                                                           
                                                                                        elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                            $count_values = array_count_values($shift_meta_time);
                                                                                            $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                            if($this_shift_count == $total_volantears){
                                                                                                ?>
                                                                                                <li>
                                                                                                    <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                    <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                                </li>
                                                                                                <?php
                                                                                            }
                                                                                            elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                        else{ 
                                                                                            ?>
                                                                                            <li>
                                                                                                <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                                <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                            </li>
                                                                                            <?php
                                                                                        }                                                                               
                                                                                    }
                                                                                    elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                    }
                                                                                    else{
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
                                                                }else{ 
                                                                
                                                                } ?>
                                                                <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
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
                        <?php }else{
                            ?> 
                                You don't have access to this
                            <?php
                        } 
                        
                        if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){
                            ?>
                            <button type="button" class="signup-show-more button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn">Show More</button>
                            <?php
                        }
                        ?>  
                        </div>
                        
                    </div>
                    <?php
                }
            }
            else{
                ?>
                <div class='sign_up_task'>No task/slot available.</div>
                <?php
            } 
        }
        if($sortval == "task" || empty($sortval) || $sortval == "time" || $sortval == "Sort by" ){
            $arraysort = array();   
            $chk_time = "";  
            $check_cat = ""; 
            $timearray = array();  
            $taskcounts = 0;       
            foreach($get_task_slots as $get_task_slot)
            {
                $post_details = get_post( $get_task_slot );                
                $arraysort[$get_task_slot] = '"'.$post_details->post_title.'"';  
                   
                
                $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                if(!empty($single_post_meta)){ 
                    if(array_key_exists("single",$single_post_meta)) {
                        if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single'])){
                            $chk_time .= $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
                            $timearray[$get_task_slot] =  $single_post_meta['single']['how_money_volunteers_sign_ups-times'];
                        }
                        else{
                            $timearray[$get_task_slot] = "";
                        }
                    }
                    else{
                        $timearray[$get_task_slot] = "";
                    }
                }
            }
            
            if(!empty($sortval) && $sortval == "task"){
                asort($arraysort);
            } 
            if(!empty($sortval) && $sortval == "time"){
                $arraysort = array();
                $arraysort = $timearray;
                asort($arraysort);
            } 
            
            ?>
            <div class="table-responsive">
            <?php if($chkuid == 0){  ?>
                <table id="single-signup-task-list" class="wp-list-table pto-signup-task-background-color pto-signup-task-text-color widefat"> 
                    <thead>
                        <tr>    
                            <th onclick="sortTable(0)">Task Name</th>                                               
                            <?php if(!empty($arraysort) && !empty($emptyremoved)){ ?>
                            <th onclick="sortTable(1)" >Date</th>
                            <?php } ?>
                            <th onclick="sortTable(2)" <?php if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time) ){ ?> style="display:none;" <?php } ?>>Time</th>
                            <?php if(!empty($check_cat)){ ?> 
                          
                            <?php } ?>
                            <th>Availability</th>
                            <th>Sign Up</th>                        
                        </tr>
                    </thead>
                    <tbody class="pto-signup-tasks">
                        <?php
                            
                            if(!empty($arraysort) && !empty($emptyremoved)){
                                
                                foreach($arraysort as $get_task_slot => $value)
                                {
                                    for($dt=0;$dt<$dcount;$dt++)
                                    {
                                        $filled = 0;
                                        $post_details = get_post( $get_task_slot );
                                        //print_r($post_details);
                                        $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                        //print_r($single_post_meta);
                                        $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
                                        $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                     
                                        $category_detail = get_the_terms( $get_task_slot, 'TaskCategories' );
                                        $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
                                        $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                        $sdate = $emptyremoved[$dt];
                                        $saved_dates = get_post_meta($get_task_slot, "pto_signup_task_edit_single".$sdate, true);
                                        if(!empty($saved_dates)){
                                            $desc = $saved_dates["tasks_comp_desc"];
                                        }
                                        $cat_name = "";
                                        if(!empty($category_detail)){                                       
                                            foreach($category_detail as $category_details){
                                                $cat_name .= " ".$category_details->name . "," ;
                                            }
                                        }
                                        $current_status = get_post_status ( $get_task_slot );
                                        if($current_status == "publish"){
                                            $taskcounts++;
                                            ?>
                                            <tr <?php if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){ ?> class="extra-tr" <?php } ?>>  
                                            <td>
                                                <?php 
                                                if(!empty($saved_dates)){
                                                    esc_html_e($saved_dates["post_title"]);
                                                }
                                                else{
                                                    esc_html_e($post_details->post_title); 
                                                } 
                                                if(!empty($desc)){ ?>
                                                <a href="#0" class="pto-task-desc" >details</a>
                                                <div class="pto-task-content pto-modal" style="display:none;">
                                                    <div class="pto-modal-content">
                                                        <div class="pto-modal-container-header">
                                                            <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                                            <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                        </div>
                                                        <div class="pto-modal-container">
                                                            <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                                            <td><?php esc_html_e(date("F jS Y", strtotime($emptyremoved[$dt]))); ?></td>
                                            <td <?php if(!empty($pto_sign_up_occurrence) ){ if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time) ){ ?> style="display:none;" <?php } } ?>>
                                                <?php 
                                                    if(!empty($single_post_meta)){ 
                                                        if(array_key_exists("single",$single_post_meta)){
                                                            if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                            {
                                                                if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                                    esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                                                }                                                           
                                                            }
                                                        }
                                                        
                                                    } 
                                                ?>                                               
                                            </td>
                                           
                                            <td>
                                                <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php esc_html_e($emptyremoved[$dt]); ?>"  />
                                                <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                                    esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                                <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                                <?php                                                       
                                                    if(!empty($get_filed)){
                                                        $avdate = $get_task_slot."_".$emptyremoved[$dt];
                                                        $get_availability = get_post_meta( $get_task_slot, "signup_task_availability".$avdate, true );
                                                        $diff = 0;
                                                        if(array_key_exists("single",$get_filed)){
                                                            $total_volantears = $get_filed['single']["how_money_volunteers"];
                                                            $total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
                                                            if($total_volantears == "")
                                                            {
                                                                $total_volantears = 0;
                                                            }
                                                            if($total_volantears_sign_ups == "")
                                                            {
                                                                $total_volantears_sign_ups = 0;
                                                            }
                                                            //$total = $total_volantears * $total_volantears_sign_ups;
                                                            $total = $total_volantears;
                                                            if(!empty($get_availability)){
                                                                ?>
                                                                    <b>
                                                                        <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                    </b>
                                                                <?php
                                                                if($get_availability == $total){
                                                                    $filled = 1;
                                                                    ?>
                                                                    <span> filled</span>
                                                                    <?php 
                                                                }else{
                                                                    $diff = $total - $get_availability;
                                                                }
                                                            }else{
                                                                ?>
                                                                <b>
                                                                    0 /  <?php echo intval($total); ?> 
                                                                </b>
                                                                <?php
                                                                
                                                            }
                                                        }else if(array_key_exists("shift",$get_filed)){
                                                            //print_r($get_filed);
                                                            $total_volantears= $get_filed['shift']["volunteers_shift"];
                                                            $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                                            $shift_meta = $get_filed["shift"];
                                                            $count = 0;
                                                            if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                                $shift_start = $shift_meta['first-shift'];
                                                                $shift_end = $shift_meta['last-end-shift'];
                                                                $shift_min = $shift_meta['how-long-shift'];
                                                                $break_time = $shift_meta['between-shift-minutes'];
                                                                $array_of_time = array();
                                                                $start_time    = strtotime ($shift_start); 
                                                                $end_time      = strtotime ($shift_end);
                                                                $add_mins  = $shift_min * 60;
                                                                $break_min = $break_time * 60; 
                                                                $i = 0;                                 
                                                                while ($start_time <= $end_time) {                                                                                                                                              
                                                                    $array_of_time[$i] = date ("h:i A", $start_time);
                                                                    $start_time += ($add_mins + $break_min);
                                                                    $count++;
                                                                    $i++;
                                                                }
                                                            }
                                                            if($total_volantears == "")
                                                            {
                                                                $total_volantears = 0;
                                                            }
                                                            if($total_volantears_sign_ups == "")
                                                            {
                                                                $total_volantears_sign_ups = 0;
                                                            }
                                                            $end_val = strtotime(end($array_of_time));
                                                            if($end_val == $end_time){
                                                                if($count != 0){
                                                                    $count = $count - 1;
                                                                }
                                                            }                                                       
                                                            
                                                            $total = $count * $total_volantears;
                                                            if(!empty($get_availability)){
                                                                ?>
                                                                <b>
                                                                    <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                                </b>
                                                                <?php
                                                                if($get_availability == $total){
                                                                    $filled = 1;
                                                                    ?>
                                                                    <span> filled</span>
                                                                    <?php 
                                                                }else{
                                                                    $diff = $total - $get_availability;
                                                                }
                                                            }else{
                                                                ?>
                                                                <b>0/ <?php  echo  intval($total);  ?></b>
                                                                <?php
                                                            }
                                                        }
                                                    }else{
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
                                                        $get_shift_time = get_post_meta( $get_task_slot, 'get_shift_time'.$avdate, true ); 
                                                        if(!empty($get_shift_time)){
                                                            //print_r($get_shift_time);
                                                            if(array_key_exists( $c_user_id, $get_shift_time )){
                                                                $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                                //print_r($current_user_shift);
                                                            }
                                                            foreach($get_shift_time as $uid){
                                                                //print_r($uid);
                                                                $all_shifts .= $uid;
                                                            }
                                                            $shift_meta_time = explode(",", $all_shifts);
                                                            //print_r($shift_meta_time);
                                                        }                                                       
                                                        $usermax = 0;
                                                        if($c_user_id != 0){
                                                            $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                                            //print_r($get_max_user_task_signup);
                                                            if(!empty($get_max_user_task_signup)){
                                                                $max_key = $signupid."_".$avdate;                                                               
                                                                if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                                    $usermax = $get_max_user_task_signup[$max_key]; 
                                                                    if($diff == 1){
                                                                    }else{
                                                                        $diff = $total_volantears_sign_ups - $usermax;
                                                                    }
                                                                    if($usermax == $total_volantears_sign_ups){
                                                                        $diff = 0;
                                                                    }                                                                           
                                                                }
                                                            }                                                               
                                                        }                                                           
                                                                                                                    
                                                        $max = 1;
                                                        if($diff != 0 && $diff < $total_volantears_sign_ups){
                                                            $max = $diff;
                                                        }
                                                        else{
                                                            $max = $total_volantears_sign_ups;
                                                        }
                                                        if($total_volantears_sign_ups > $total){
                                                            $max = $total;
                                                        }
                                                        if(!empty($get_availability) && $diff == 0){
                                                            $max = 0;
                                                        }
                                                        //echo "max: ".$max;                                                            
                                                    ?>
                                                    <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                                    <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                                    <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                                            if(($filled == 1 || $max == 1)){
                                                                ?>                                                      
                                                                    <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                                        <option value="1">1</option>
                                                                    </select>
                                                                    <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                                <?php
                                                            }else{
                                                        ?>
                                                        <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                                            <?php                                                                   
                                                                for($i=0; $i<=$max; $i++){ ?>
                                                                <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                                <?php
                                                                }
                                                            ?>
                                                        </select>
                                                        <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                    <?php } }else{ ?>                                                       
                                                    <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                        <option value="1">1</option>
                                                    </select>
                                                    <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                                    <?php } 
                                                    
                                                    if( array_key_exists( "shift", $single_post_meta ) ){
                                                        $shift_meta = $single_post_meta["shift"];
                                                        if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                            $shift_count = count($array_of_time);   
                                                            //print_r($shift_meta_time);                                                                                                                    
                                                            ?>
                                                            <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                                <span class="span-choose-shift">Choose a shift:</span>
                                                                <ul class="checkbox-list">  
                                                                    <li>
                                                                        <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                                        <label class="choose-shift">Choose a shift:</label>
                                                                    </li>
                                                                    <?php                                                                       
                                                                        $i = 0;                                                                     
                                                                        while ($i < $shift_count) { 
                                                                            $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                                            if(!empty($shift_meta_time)){
                                                                                if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                                    ?>
                                                                                    <li>
                                                                                        <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                    </li>
                                                                                    <?php
                                                                                }   
                                                                                elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                                }                                                                           
                                                                                elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                                    $count_values = array_count_values($shift_meta_time);
                                                                                    $this_shift_count = $count_values[$array_of_time[$i]];
                                                                                    if($this_shift_count == $total_volantears){
                                                                                        ?>
                                                                                        <li>
                                                                                            <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                            <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                        </li>
                                                                                        <?php
                                                                                    }
                                                                                    elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                                else{ 
                                                                                    ?>
                                                                                    <li>
                                                                                        <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                                    </li>
                                                                                    <?php
                                                                                }                                                                               
                                                                            }
                                                                            elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                            }
                                                                            else{
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
                                                        }else{ 
                                                        
                                                        } ?>
                                                        <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
                                                </div>  
                                            </td>
                                            </tr>
                                            <?php 
                                        }
                                    } 
                                }                                
                            }
                            else if(!empty($arraysort))
                            {
                                
                                foreach($arraysort as $get_task_slot => $value)
                                {
                                    $filled = 0;
                                    $post_details = get_post( $get_task_slot );
                                    //print_r($post_details);
                                    $single_post_meta = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );
                                    //print_r($single_post_meta);
                                    $desc = get_post_meta( $get_task_slot, "tasks_comp_desc", true );
                                    $get_filed = get_post_meta( $get_task_slot, "single_tasks_advance_options", true );                                                     
                                    $category_detail = get_the_terms( $get_task_slot, 'TaskCategories' );
                                    $hourscheck = get_post_meta( $get_task_slot, "pto_sign_ups_hour_point", true );
                                    $hourspoint = get_post_meta( $get_task_slot, "pto_sign_ups_hour_points", true );
                                    
                                    $cat_name = "";
                                    if(!empty($category_detail)){                                       
                                        foreach($category_detail as $category_details){
                                            $cat_name .= " ".$category_details->name . "," ;
                                        }
                                    }
                                    $current_status = get_post_status ( $get_task_slot );
                                    if($current_status == "publish"){
                                        $taskcounts++;
                        ?>
                        <tr <?php if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){ ?> class="extra-tr" <?php } ?>>  
                            <td>
                                <?php 
                                esc_html_e($post_details->post_title); 
                                if(!empty($desc)){ ?>
                                <a href="#0" class="pto-task-desc" >details</a>
                                <div class="pto-task-content pto-modal" style="display:none;">
                                    <div class="pto-modal-content">
                                        <div class="pto-modal-container-header">
                                            <span><?php esc_html_e('Task Description',PTO_SIGN_UP_TEXTDOMAIN);?></span>
                                            <span onclick="jQuery('.pto-task-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                        </div>
                                        <div class="pto-modal-container">
                                            <div class="pto-show-task-desc"><?php print_r($desc); ?></div>
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
                            <!--<td <?php //if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){ ?> style="display:none;" <?php //} ?>><?php //if(!empty($specific_day)){ echo date("F jS", strtotime($specific_day)); } ?></td>-->
                            <td <?php if(array_key_exists("occurrence-not-specific", $pto_sign_up_occurrence) || empty($chk_time)){ ?> style="display:none;" <?php } ?>>
                            <?php 
                                if(!empty($single_post_meta)){ 
                                    if(array_key_exists("single",$single_post_meta)){
                                        if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                        {
                                            if(!empty($single_post_meta['single']['how_money_volunteers_sign_ups-times'])){
                                                esc_html_e(date("H:i a", strtotime($single_post_meta['single']['how_money_volunteers_sign_ups-times'])));
                                            }                                                           
                                        }
                                    }
                                    
                                } 
                            ?>                                               
                            </td>
                            <?php if(!empty($check_cat)){ ?>  
                            <td><?php if(!empty($cat_name)) esc_html_e(substr($cat_name, 0, -1)); ?></td>
                            <?php } ?>
                            <td>
                                <input type="hidden" class="sign-up-task-date" name="singup_hidden_date[]" value="<?php if(!empty($specific_day)){ esc_html_e($specific_day); } ?>"  />
                                <input type="hidden" class="sign-up-task-time" name="singup_hidden_time[]" value="<?php if(!empty($single_post_meta)){ if(array_key_exists("single",$single_post_meta)) if(array_key_exists("how_money_volunteers_sign_ups-times",$single_post_meta['single']))
                                    esc_html_e($single_post_meta['single']['how_money_volunteers_sign_ups-times']); } ?>"  />
                                <input type="hidden" name="pto_signup_hours_points[]" class="pto-signup-hours-points" value="<?php echo intval($hourspoint); ?>" />
                                <?php                                                       
                                    if(!empty($get_filed)){
                                        $get_availability = get_post_meta( $get_task_slot, "signup_task_availability", true );
                                        $diff = 0;
                                        if(array_key_exists("single",$get_filed)){
                                            $total_volantears = $get_filed['single']["how_money_volunteers"];
                                            $total_volantears_sign_ups= $get_filed['single']["how_money_volunteers_sign_ups"];
                                            if($total_volantears == "")
                                            {
                                                $total_volantears = 0;
                                            }
                                            if($total_volantears_sign_ups == "")
                                            {
                                                $total_volantears_sign_ups = 0;
                                            }
                                            //$total = $total_volantears * $total_volantears_sign_ups;
                                            $total = $total_volantears;
                                            if(!empty($get_availability)){
                                                ?>
                                                <b>
                                                    <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                </b>
                                                <?php
                                                
                                                if($get_availability == $total){
                                                    $filled = 1;
                                                    ?>
                                                    <span> filled</span>
                                                    <?php
                                                }else{
                                                    $diff = $total - $get_availability;
                                                }
                                                    
                                            }else{
                                                ?>
                                                <b>
                                                    0 / <?php echo intval($total); ?>
                                                </b>
                                                <?php
                                            }
                                        }else if(array_key_exists("shift",$get_filed)){
                                            //print_r($get_filed);
                                            $total_volantears= $get_filed['shift']["volunteers_shift"];
                                            $total_volantears_sign_ups= $get_filed['shift']["volunteers_shift_times"];
                                            $shift_meta = $get_filed["shift"];
                                            $count = 0;
                                            if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                                $shift_start = $shift_meta['first-shift'];
                                                $shift_end = $shift_meta['last-end-shift'];
                                                $shift_min = $shift_meta['how-long-shift'];
                                                $break_time = $shift_meta['between-shift-minutes'];
                                                $array_of_time = array();
                                                $start_time    = strtotime ($shift_start); 
                                                $end_time      = strtotime ($shift_end);
                                                $add_mins  = $shift_min * 60;
                                                $break_min = $break_time * 60; 
                                                $i = 0;                                 
                                                while ($start_time <= $end_time) {                                                                                                                                              
                                                    $array_of_time[$i] = date ("h:i A", $start_time);
                                                    $start_time += ($add_mins + $break_min);
                                                    $count++;
                                                    $i++;
                                                }
                                            }
                                            if($total_volantears == "")
                                            {
                                                $total_volantears = 0;
                                            }
                                            if($total_volantears_sign_ups == "")
                                            {
                                                $total_volantears_sign_ups = 0;
                                            }
                                            $end_val = strtotime(end($array_of_time));
                                            if($end_val == $end_time){
                                                if($count != 0){
                                                    $count = $count - 1;
                                                }
                                            }                                                       
                                            
                                            $total = $count * $total_volantears;
                                            if(!empty($get_availability)){
                                                ?>
                                                <b>
                                                    <?php echo intval($get_availability); ?> / <?php echo intval($total); ?>
                                                </b>
                                                <?php
                                                if($get_availability == $total){
                                                    $filled = 1;
                                                   ?>
                                                    <span> filled</span>
                                                    <?php
                                                }else{
                                                    $diff = $total - $get_availability;
                                                }
                                            }else{
                                               ?>
                                                <b>
                                                    0 / <?php echo intval($total); ?>
                                                </b>
                                                <?php
                                            }
                                        }
                                    }else{
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
                                        if(!empty($get_shift_time)){
                                            //print_r($get_shift_time);
                                            if(array_key_exists( $c_user_id, $get_shift_time )){
                                                $current_user_shift = explode(",", $get_shift_time[$c_user_id]);
                                                //print_r($current_user_shift);
                                            }
                                            foreach($get_shift_time as $uid){
                                                //print_r($uid);
                                                $all_shifts .= $uid;
                                            }
                                            $shift_meta_time = explode(",", $all_shifts);
                                            //print_r($shift_meta_time);
                                        }                                                       
                                        $usermax = 0;
                                        if($c_user_id != 0){
                                            $get_max_user_task_signup = get_user_meta( $c_user_id, 'max_user_task_signup', true );                                                              
                                            if(!empty($get_max_user_task_signup)){
                                                $max_key = $post_id."_".$get_task_slot;                                                             
                                                if(array_key_exists( $max_key, $get_max_user_task_signup )){
                                                    $usermax = $get_max_user_task_signup[$max_key]; 
                                                    if($diff == 1){
                                                    }else{
                                                        $diff = $total_volantears_sign_ups - $usermax;
                                                    }
                                                    if($usermax == $total_volantears_sign_ups){
                                                        $diff = 0;
                                                    }                                                                           
                                                }
                                            }                                                               
                                        }                                                           
                                                                                                    
                                        $max = 1;
                                        if($diff != 0 && $diff < $total_volantears_sign_ups){
                                            $max = $diff;
                                        }
                                        else{
                                            $max = $total_volantears_sign_ups;
                                        }
                                        if($total_volantears_sign_ups > $total){
                                            $max = $total;
                                        }
                                        if(!empty($get_availability) && $diff == 0){
                                            $max = 0;
                                        }                                                           
                                    ?>
                                    <input type="checkbox" class="sign-up-task" <?php if($filled == 1 || $max != 1 || ($max == 1 && array_key_exists("shift", $get_filed))){ ?> style="visibility:hidden;" <?php } ?> id="sign-up-task" name="sign_up_task[]" value="<?php echo intval($post_details->ID); ?>" />
                                    <input type="hidden" class="sign-up-task-hidden" name="singup_hidden_task[]" value=""  />
                                    <?php if($total_volantears_sign_ups != 1 && array_key_exists("single", $get_filed) && $total_volantears_sign_ups != $usermax ){ 
                                            if(($filled == 1 || $max == 1)){
                                                ?>                                                      
                                                    <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                                        <option value="1">1</option>
                                                    </select>
                                                    <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                                <?php
                                            }else{
                                        ?>
                                        <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                                            <?php                                                                   
                                                for($i=0; $i<=$max; $i++){ ?>
                                                <option value="<?php echo intval($i); ?>"><?php echo intval($i); ?></option>
                                                <?php
                                                }
                                            ?>
                                        </select>
                                        <input type="number" name="pto_signup_task_max1[]" min=1 max="<?php echo intval($max); ?>" value="" style="visibility:hidden;" class="pto-singup-task-max-number"  />
                                    <?php } }else{ ?>                                                       
                                    <select name="pto_signup_task_max[]" style="visibility:hidden;" class="pto-singup-task-max-number-select" >
                                        <option value="1">1</option>
                                    </select>
                                    <input type="number" name="pto_signup_task_max1[]" value="" max="<?php echo intval($max); ?>"  style="visibility:hidden;" class="pto-singup-task-max-number" />
                                    <?php } 
                                    
                                    if( array_key_exists( "shift", $single_post_meta ) ){
                                        $shift_meta = $single_post_meta["shift"];
                                        if( array_key_exists( "first-shift", $shift_meta ) && array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                                            $shift_count = count($array_of_time);   
                                            //print_r($shift_meta_time);                                                                                                                    
                                            ?>
                                            <div class="shift-checkbox-list" <?php if($filled == 1){ ?> style="visibility:hidden;" <?php } ?>>
                                                <span class="span-choose-shift">Choose a shift:</span>
                                                <ul class="checkbox-list">  
                                                    <li>
                                                        <input type="checkbox" class="task-shift first-task-shift" style="visibility:hidden;" name="task_shift[]" value="0" />
                                                        <label class="choose-shift">Choose a shift:</label>
                                                    </li>
                                                    <?php                                                                       
                                                        $i = 0;                                                                     
                                                        while ($i < $shift_count) { 
                                                            $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                                   
                                                            if(!empty($shift_meta_time)){
                                                                if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                                    ?>
                                                                    <li>
                                                                        <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                    </li>
                                                                    <?php
                                                                }   
                                                                elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                                }                                                                           
                                                                elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                                    $count_values = array_count_values($shift_meta_time);
                                                                    $this_shift_count = $count_values[$array_of_time[$i]];
                                                                    if($this_shift_count == $total_volantears){
                                                                        ?>
                                                                        <li>
                                                                            <input type="checkbox" class="task-shift" data-label="The logged in user cannot sign up to this shift more than once." disabled name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                            <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                        </li>
                                                                        <?php
                                                                    }
                                                                    elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
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
                                                                else{ 
                                                                    ?>
                                                                    <li>
                                                                        <input type="checkbox" class="task-shift" name="task_shift[]" value="<?php esc_html_e( $array_of_time[ $i ] ); ?>" />
                                                                        <label><?php esc_html_e( $array_of_time[ $i ]."-".$shift_endtime ); ?></label>                                                                                 
                                                                    </li>
                                                                    <?php
                                                                }                                                                               
                                                            }
                                                            elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                                            }
                                                            else{
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
                                        }else{ 
                                        
                                        } ?>
                                        <input type="hidden" value="" name="task_shift_hidden[]" class="task-shift-hidden" />                                       
                                </div>  
                            </td>
                        </tr>
                        <?php } } }else{
                            ?>
                                <div class='sign_up_task'>No task/slot available.</div>;
                            <?php
                        } ?>
                    </tbody>
                </table>
                <?php }else{
                    ?>
                        You don't have access to this
                    <?php
                } 
                if(!empty($categories_colspan_show) && !empty($number_of_slots) && $taskcounts > $number_of_slots){
                    ?>
                    <button type="button" class="signup-show-more button pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn">Show More</button>
                    <?php
                }
                ?>
            </div> 
            <?php
                
            } 
          
        die();
    }
    /**
    * Save user's sign up with tasks
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_save_signup_tasks() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
      

        $myArray = array();
        parse_str($_POST['keyword'], $myArray);
       
        $get_user_cart_em = array();
        $get_user_cart = array();
        
        $current_user_id = get_current_user_id();
        if(!empty($myArray)){
            $sign_up_task = $myArray["sign_up_task"];
            $pto_sign_up = $myArray["pto_sign_up"];
            
            if(!empty($pto_sign_up) && !empty($sign_up_task)){
                if (!session_id()) {
                    session_start();
                }  
                //print_r(session_id());
                if(array_key_exists( "pto_signup_tasks_cart", $_SESSION )){
                    $get_user_cart = filter_var_array($_SESSION['pto_signup_tasks_cart']);
                }
                //$get_user_cart = $_SESSION['pto_signup_tasks_cart']; 
                //print_r($get_user_cart);           
                if(!empty($get_user_cart)){                                    
                    $get_user_cart[$pto_sign_up] = $myArray;
                    $_SESSION['pto_signup_tasks_cart'] = $get_user_cart;
                    //update_user_meta( $current_user_id, "pto_signup_tasks_cart", $get_user_cart );                
                }
                else{    
                    $get_user_cart_em[$pto_sign_up] = $myArray;
                    $_SESSION['pto_signup_tasks_cart'] = $get_user_cart_em; 
                    update_user_meta( $current_user_id, "pto_signup_tasks_cart", $get_user_cart_em );                
                } 
            }
        }
        die();
    }
    /**
    * Edit my sign up 
    * @since    1.0.0
    * @access   public
    **/  
    // public function pto_sign_up_checkout_update() {
    //     @ini_set( 'display_errors', 1 );
    //     if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    //         die ( 'Busted!');
    //     }
    //     global $wpdb;
    //     $response = array();
    //     $uid = 0;
    //     $today =  date("Y-m-d");
    //     if( isset( $_POST['keyword'] ) && isset( $_POST['uid'] ) ) {
    //         $keyword = sanitize_text_field( $_POST['keyword'] );
    //         $uid = intval( $_POST['uid'] );
    //     }
    //     $myArray = array();
    //     parse_str($_POST['keyword'], $myArray);
       
    //     $get_user_signup_data = array();
    //     $table_name = $wpdb->prefix . "signup_orders";
    //     $editid = $myArray["edit_id"];
    //     $all_data =  $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE ID = ".$editid );
    //     if(!empty($all_data)){
    //         foreach($all_data as $keye => $post):
    //             $get_user_signup_data = unserialize($post->order_info);
    //         endforeach;
    //     }
      
    //     if( empty( $get_user_signup_data ) ){
    //         $get_user_signup_data = array();
    //     }
    //     if(is_user_logged_in()){
    //         $current_user_id = $uid;           
    //         $saved_task_ids = array();
    //         if( array_key_exists( "signup_id" , $get_user_signup_data ) ){
    //             $total_signup = count($get_user_signup_data["signup_id"]);    
    //         }
    //         $total_signup = 0;
    //         $pto_sign_up_occurrence = array();
    //         for($i=0; $i<$total_signup; $i++){
    //             $signupid = $get_user_signup_data["signup_id"][$i]; 
    //             $total_task = count($get_user_signup_data["task_id".$signupid]);
    //             $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
    //             $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
    //             $all_task_ids = array();
    //             $all_task_ids = $myArray["task_id".$signupid];
    //             for($j=0; $j<$total_task; $j++){ 
                    
    //                 $hours_points = array();
    //                 $taskid = $get_user_signup_data["task_id".$signupid][$j];
    //                 $saved_task_ids[$j] = $taskid;
    //                 $task_maxval = $get_user_signup_data["task_max".$taskid][0]; 
    //                 $task_date = $get_user_signup_data["task_date".$taskid][0];
    //                 $task_time = $get_user_signup_data["task_time".$taskid][0];
    //                 $task_hours_points = $get_user_signup_data["task_hours_points".$taskid][0];
    //                 if(in_array($taskid, $all_task_ids)){
    //                     $etask_maxval = $myArray["task_max".$taskid][0]; 
    //                     $etask_date = $myArray["task_date".$taskid][0];
    //                     $etask_time = $myArray["task_time".$taskid][0];
    //                     $etask_hours_points = $myArray["task_hours_points".$taskid][0];
    //                     // to add shift time for task and user                         
    //                     $shift_key = $current_user_id;
    //                     $shift_time = array();
    //                     $shift_time[$shift_key] = $etask_time;                        
    //                     $tasktimes = explode(",", $task_time);
    //                     $ttt_id = "";
    //                     $ttt_id = $taskid;
    //                     $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
    //                     if(!empty($get_shift_time)){                            
    //                         if(!empty($tasktimes)){
    //                             $shifttimes = explode(",", $get_shift_time[$shift_key]);
    //                             for($s=0; $s<count($tasktimes); $s++){
    //                                 $stime = $tasktimes[$s];
    //                                 if(!empty($stime)){
    //                                     if (($key = array_search($stime, $shifttimes)) !== false) {
    //                                         unset($shifttimes[$key]);
    //                                     }                                            
    //                                 }
    //                             }                                    
    //                             $get_shift_time[$shift_key] = implode(",", $shifttimes);
    //                             update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
    //                         }
    //                         $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $etask_time;
    //                         update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
    //                     }
    //                     else{
    //                         update_post_meta( $taskid, 'get_shift_time', $shift_time );
    //                     }
    //                     $how_money_volunteers = get_post_meta( $ttt_id, "single_tasks_advance_options", true ); 
    //                     if( empty( $how_money_volunteers ) ){
    //                         $how_money_volunteers = array();
    //                     }
    //                     $task_c = 0;
    //                     $cnt = 0;
    //                     if( array_key_exists( "single" , $how_money_volunteers ) ){
    //                         foreach( $how_money_volunteers as $key => $val){
    //                             if( array_key_exists( "how_money_volunteers" , $val ) ){
    //                                 $task_c = $val['how_money_volunteers'];
    //                             }
    //                         }
    //                         $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                         $get_filled = $get_filled - $task_maxval;
    //                         $get_filled = $get_filled + $etask_maxval;
    //                         if($task_c >= $get_filled){
    //                             $cnt = 1;                               
    //                         }  
    //                     }
    //                     // if($cnt == 1){
    //                     if($etask_maxval != $task_maxval){
    //                         if($cnt == 1){
    //                             $max_task_signuped = array();
    //                             $max_key = $signupid."_".$taskid;
    //                             $max_task_signuped[$max_key] = $task_maxval;
    //                             $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
    //                             if(!empty($get_max_user_task_signup)){                                    
    //                                 $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $task_maxval;
    //                                 $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $etask_maxval;
    //                                 update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
    //                             }
    //                             else{
    //                                 update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
    //                             } 
    //                             // to add task hours/points to user 
    //                             if(!empty($myArray["task_hours_points".$taskid][0])){
    //                                 $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                                 $hours_points[$taskid] = $task_hours_points;
    //                                 $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
    //                                 if(!empty($get_user_task_hours)){
    //                                     $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
    //                                     $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $etask_hours_points;
    //                                     update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
    //                                 }
    //                                 else{
    //                                     update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
    //                                 } 
    //                             } 
    //                             if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
    //                                 $taskid_explode = explode("_", $taskid);
    //                                 $tid = $taskid_explode[0];
    //                                 $tdate = $taskid_explode[1]; 
    //                                 //to fill task availability                            
    //                                 $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
    //                                  if(!empty($get_filled)){
    //                                     $get_filled = $get_filled - $task_maxval;
    //                                     $get_filled = $get_filled + $etask_maxval;
    //                                     update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
    //                                 }
    //                                 else{
    //                                      update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
    //                                 }
    //                             }
    //                             else{
    //                                 //to fill task availability    
    //                                 $how_money_volunteers = get_post_meta( $taskid, "single_tasks_advance_options", true );                        
    //                                 $task_c = 0;
    //                                 $cnt = 0;
    //                                 if( array_key_exists( "single" , $how_money_volunteers ) ){
    //                                     foreach( $how_money_volunteers as $key => $val){
    //                                         if( array_key_exists( "how_money_volunteers" , $val ) ){
    //                                             $task_c = $val['how_money_volunteers'];
    //                                         }
    //                                     }
    //                                     $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                                     $get_filled = $get_filled - $task_maxval;
    //                                     $get_filled = $get_filled + $etask_maxval;
    //                                     if($task_c >= $get_filled){
    //                                         $cnt = 1;                               
    //                                     }  
    //                                 }else{
    //                                     if($etask_maxval != $task_maxval){
    //                                        $cnt = 1; 
    //                                     }
    //                                 }
    //                                 $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                                    
    //                                 if(!empty($get_filled)){
    //                                     $get_filled = $get_filled - $task_maxval;
    //                                     $get_filled = $get_filled + $etask_maxval;
    //                                     if($cnt != 1){
    //                                         $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
                                        
    //                                     }else{
    //                                         update_post_meta( $taskid, "signup_task_availability", $get_filled );    
    //                                     }
    //                                 }
    //                                 else{
    //                                     update_post_meta( $taskid, "signup_task_availability", $task_maxval );
    //                                 }
    //                             }
    //                         }else{
    //                             $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
    //                         }
    //                     }
    //                     else{
    //                         // $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
    //                     }
    //                 }
    //                 else{
    //                     // to add shift time for task and user                         
    //                     $shift_key = $current_user_id; 
    //                     $tasktimes = explode(",", $task_time);
    //                     if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                         $taskid_explode = explode("_", $taskid);
    //                         $tid = $taskid_explode[0];
    //                         $tdate = $taskid_explode[1];
    //                         $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );                        
    //                         if(!empty($get_shift_time) && !empty($tasktimes)){
    //                             $shifttimes = explode(",", $get_shift_time[$shift_key]);
    //                             for($s=0; $s<count($tasktimes); $s++){
    //                                 $stime = $tasktimes[$s];
    //                                 if(!empty($stime)){
    //                                     if (($key = array_search($stime, $shifttimes)) !== false) {
    //                                         unset($shifttimes[$key]);
    //                                     }                                            
    //                                 }
    //                             }                                    
    //                             $get_shift_time[$shift_key] = implode(",", $shifttimes);
    //                             // update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
    //                         }
    //                         // $how_money_volunteers = get_post_meta( $tid, "single_tasks_advance_options", true );                        
    //                         // $task_c = 0;
    //                         // $cnt = 0;
    //                         // if( array_key_exists( "single" , $how_money_volunteers ) ){
    //                         //     foreach( $how_money_volunteers as $key => $val){
    //                         //         if( array_key_exists( "how_money_volunteers" , $val ) ){
    //                         //             $task_c = $val['how_money_volunteers'];
    //                         //         }
    //                         //     }
    //                         //     $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                         //     $get_filled = $get_filled - $task_maxval;
    //                         //     $get_filled = $get_filled + $etask_maxval;
    //                         //     if($task_c >= $get_filled){
    //                         //         $cnt = 1;                               
    //                         //     }  
    //                         // }else{
    //                         //     if($etask_maxval != $task_maxval){
    //                         //        $cnt = 1; 
    //                         //     }
    //                         // }
    //                         //to fill task availability 
    //                         $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
    //                         if(!empty($get_filled)){
    //                             $get_filled = $get_filled - $task_maxval;
    //                             // if($cnt != 1){
    //                             //     // $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
    //                             // }else{
    //                             // }
    //                                 update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );   
                               
    //                         }   
    //                     }
    //                     else{
    //                         $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );                        
    //                         if(!empty($get_shift_time) && !empty($tasktimes)){
    //                             $shifttimes = explode(",", $get_shift_time[$shift_key]);
    //                             for($s=0; $s<count($tasktimes); $s++){
    //                                 $stime = $tasktimes[$s];
    //                                 if(!empty($stime)){
    //                                     if (($key = array_search($stime, $shifttimes)) !== false) {
    //                                         unset($shifttimes[$key]);
    //                                     }                                            
    //                                 }
    //                             }                                    
    //                             $get_shift_time[$shift_key] = implode(",", $shifttimes);
    //                             update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
    //                         }
    //                         // $how_money_volunteers = get_post_meta( $taskid, "single_tasks_advance_options", true );                        
    //                         // $task_c = 0;
    //                         // $cnt = 0;
    //                         // if( array_key_exists( "single" , $how_money_volunteers ) ){
    //                         //     foreach( $how_money_volunteers as $key => $val){
    //                         //         if( array_key_exists( "how_money_volunteers" , $val ) ){
    //                         //             $task_c = $val['how_money_volunteers'];
    //                         //         }
    //                         //     }
    //                         //     $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                         //     $get_filled = $get_filled - $task_maxval;
    //                         //     $get_filled = $get_filled + $etask_maxval;
    //                         //     if($task_c >= $get_filled){
    //                         //         $cnt = 1;                               
    //                         //     }  
    //                         // }else{
    //                         //     if($etask_maxval != $task_maxval){
    //                         //        $cnt = 1; 
    //                         //     }
    //                         // }
    //                         //to fill task availability 
    //                         $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                         if(!empty($get_filled)){
    //                             $get_filled = $get_filled - $task_maxval;
    //                             // if($cnt != 1){
    //                             //     $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
    //                             // }else{
    //                             // }
    //                                 update_post_meta( $taskid, "signup_task_availability", $get_filled );
                               
    //                         }   
    //                     }
                        
    //                     // to store max value of task for user
    //                     $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
    //                     $max_key = $signupid."_".$taskid;
    //                     $maxval = $get_max_user_task_signup[$max_key];
    //                     if(!empty($get_max_user_task_signup)){
    //                         $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $maxval;
    //                         update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
    //                     }      
    //                     // to add task hours/points to user                                 
    //                     if(!empty($task_hours_points)){                 
    //                         $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
    //                         if(!empty($get_user_task_hours)){
    //                             $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
    //                             update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
    //                         }
    //                     }
    //                     // to add user to manage volunteers
    //                     /*$get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );                                
    //                     $selected_date = get_post_meta( $taskid,"pto_sign_up_selected_date_time",true);
                        
    //                     if(!empty($selected_date)){
    //                         unset($selected_date[$current_user_id][$task_date]);
    //                         update_post_meta($taskid,"pto_sign_up_selected_date_time",$selected_date);
    //                     }
    //                     $selected_date = get_post_meta( $taskid,"pto_sign_up_selected_date_time",true);*/
    //                 }
    //             }
    //             if( !array_key_exists( "err" , $response ) ){
    //                 $sql =  $wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".serialize($myArray)."' WHERE ID = ".$editid );            
    //                 $result = $wpdb->query($wpdb->prepare( "UPDATE ". esc_sql($table_name)." SET order_info = '".serialize($myArray)."' WHERE ID = ".intval($editid)));
    //                 if($result){
    //                     $response['suc']  = "updated successfully"; 
    //                 }
    //                 else{
    //                     $response['error']  = "updated successfully";
    //                 }    
    //             }
                
    //             $cur_user_obj = get_user_by('id', $current_user_id);
    //             $cuname = $cur_user_obj->display_name;
    //             // send "Receipt" to volunteer after they sign up 
    //             $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
    //             $signuptitle = get_the_title($signupid);
    //             $to = $cur_user_obj->user_email;
    //             if(!empty($volunteer_after_sign_up)){
    //                 $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
    //                 if(!empty($mailcontent)){
    //                     $arra = array("/{{UserName}}/", "/{{SignupName}}/");
    //                     $arra2 = array($cuname, $signuptitle);                                      
    //                     $mail = preg_replace($arra, $arra2, $mailcontent);
                        
    //                     $subject = 'Signup Update Success';
    //                     $body = $mail;                    
    //                     $headers = array('Content-Type: text/html; charset=UTF-8');                    
    //                     wp_mail( $to, $subject, $body, $headers );
    //                 }
    //             }
                
    //             // send notification to admins 
                                    
    //             $author_id = get_post_field( 'post_author', $signupid );
    //             $user_info = get_userdata($author_id);            
    //             $to = $user_info->user_email;
    //             $admin_name = $user_info->display_name;
    //             $administrators_notifcations = get_option('administrators_notifcations');                     
    //             $arra = array("/{{AdminName}}/", "/{{UserName}}/");
    //             $arra2 = array($admin_name, $cuname);                                      
    //             $mail = preg_replace($arra, $arra2, $administrators_notifcations);
                
    //             $subject = 'Signup Updation';
    //             $body = $mail;                    
    //             $headers = array('Content-Type: text/html; charset=UTF-8');                    
    //             wp_mail( $to, $subject, $body, $headers );
                
    //             $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
    //             if(!empty($notified_users)){                   
                    
    //                 foreach($notified_users as $assign_user)
    //                 {
    //                     $author_obj = get_user_by('id', $assign_user);
    //                     $to = $author_obj->user_email;
    //                     $uname = $author_obj->display_name;
    //                     $arra2 = array($uname, $cuname);                                      
    //                     $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
    //                     $body = $mail;                            
    //                     $headers = array('Content-Type: text/html; charset=UTF-8');                            
    //                     wp_mail( $to, $subject, $body, $headers );
    //                 }
    //             }                 
    //         }
    //         $signupid = $myArray["signup_id"][0];
    //         $total_task = count($myArray["task_id".$signupid]);
           
    //         for($j=0; $j<$total_task; $j++){ 
    //             $hours_points = array();
    //             $taskid = $myArray["task_id".$signupid][$j];
    //             $task_maxval = $myArray["task_max".$taskid][0]; 
    //             $task_date = $myArray["task_date".$taskid][0];
    //             $task_time = $myArray["task_time".$taskid][0];
    //             $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //             $task_old_val = 0;
                
    //             if( array_key_exists( "task_max".$taskid ,$get_user_signup_data ) ){
    //             $task_old_val = $get_user_signup_data["task_max".$taskid][0];
    //             }
    //             if(in_array($taskid, $saved_task_ids)){
    //             }
    //             else{
    //                 $how_money_volunteers = get_post_meta( $taskid, "single_tasks_advance_options", true );  
                     
    //                 $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                 // echo $get_filled;
    //                 if(!empty($get_filled)){
    //                     $get_filled = $get_filled + $task_maxval;
    //                     $get_filled = $get_filled - $task_old_val;
    //                     if( array_key_exists( "single" ,$how_money_volunteers )){
    //                         $tot = $how_money_volunteers['single']['how_money_volunteers'];
                            
    //                         if( $get_filled > $tot ){
    //                             $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
    //                         }else{
                                
    //                              $result = $wpdb->query($wpdb->prepare( "UPDATE ". esc_sql($table_name)." SET order_info = '".serialize($myArray)."' WHERE ID = ".intval($editid)));
    //                              update_post_meta( $taskid, "signup_task_availability", $get_filled );
    //                         }
    //                     }
    //                     // if($cnt != 1){
    //                     //     $response['err'] = "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.";
                        
    //                     // }else{
    //                     // }
                        
    //                 }
    //                 else{
    //                     $result = $wpdb->query($wpdb->prepare( "UPDATE ". esc_sql($table_name)." SET order_info = '".serialize($myArray)."' WHERE ID = ".intval($editid)));
    //                     update_post_meta( $taskid, "signup_task_availability", $task_maxval );
    //                 }
                    
                   
    //                 // to store max value of task for user 
    //                 $max_task_signuped = array();
    //                 $max_key = $signupid."_".$taskid;
    //                 $max_task_signuped[$max_key] = $task_maxval;
    //                 $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
    //                 if(!empty($get_max_user_task_signup)){
    //                     if( array_key_exists( $max_key , $get_max_user_task_signup ) ){
    //                         $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
    //                         update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );    
    //                     }
    //                 }
    //                 else{
    //                     update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
    //                 } 
    //                 // to add task hours/points to user 
    //                 if(!empty($myArray["task_hours_points".$taskid][0])){
    //                     $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                     $hours_points[$taskid] = $task_hours_points;
    //                     $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
    //                     if(!empty($get_user_task_hours)){
    //                         $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
    //                         update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
    //                     }
    //                     else{
    //                         update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
    //                     } 
    //                 }
    //             }
    //         }
    //         if( !array_key_exists( "err" , $response ) ){
    //             $response['suc'] = "Sign Up updated";
    //         }
    //         echo json_encode( $response );
    //     }           
    //     die();
    // }
     public function pto_sign_up_checkout_update() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!'); 
        }
        global $wpdb;
        $uid = 0;
        $today =  date("Y-m-d");
        if( isset( $_POST['keyword'] ) && isset( $_POST['uid'] ) ) {
            // $keyword = esc_attr( $_POST['keyword'] );
            $uid = intval( $_POST['uid'] );
        }
        $myArray = array();
        parse_str($_POST['keyword'], $myArray);
        $get_user_signup_data = array();
        $table_name = $wpdb->prefix . "signup_orders";
        $editid = $myArray["edit_id"];
        $all_data =  $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE ID = ".$editid );
        if(!empty($all_data)){
            foreach($all_data as $keye => $post):
                $get_user_signup_data = unserialize($post->order_info);
            endforeach;
        }
        if(is_user_logged_in()){
            $current_user_id = $uid;           
            $saved_task_ids = array();
            $total_signup = count($get_user_signup_data["signup_id"]);            
            for($i=0; $i<$total_signup; $i++){
                $signupid = $get_user_signup_data["signup_id"][$i]; 
                $total_task = count($get_user_signup_data["task_id".$signupid]);
                $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                $all_task_ids = array();
                $all_task_ids = $myArray["task_id".$signupid];
                for($j=0; $j<$total_task; $j++){ 
                    $hours_points = array();
                    $taskid = $get_user_signup_data["task_id".$signupid][$j];
                    $saved_task_ids[$j] = $taskid;
                    $task_maxval = $get_user_signup_data["task_max".$taskid][0]; 
                    $task_date = $get_user_signup_data["task_date".$taskid][0];
                    $task_time = $get_user_signup_data["task_time".$taskid][0];
                    $task_hours_points = $get_user_signup_data["task_hours_points".$taskid][0];
                    if(in_array($taskid, $all_task_ids)){
                      
                        $etask_maxval = $myArray["task_max".$taskid][0]; 
                        $etask_date = $myArray["task_date".$taskid][0];
                        $etask_time = $myArray["task_time".$taskid][0];
                        $etask_hours_points = $myArray["task_hours_points".$taskid][0];
                            
                        // to add shift time for task and user                         
                        $shift_key = $current_user_id;
                        $shift_time = array();
                        $shift_time[$shift_key] = $etask_time;                        
                        $tasktimes = explode(",", $task_time);
                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                            $taskid_explode = explode("_", $taskid);
                            $tid = $taskid_explode[0];
                            $tdate = $taskid_explode[1];
                            $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                            if(!empty($get_shift_time)){                            
                                if(!empty($tasktimes)){
                                    $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                    for($s=0; $s<count($tasktimes); $s++){
                                        $stime = $tasktimes[$s];
                                        if(!empty($stime)){
                                            if (($key = array_search($stime, $shifttimes)) !== false) {
                                                unset($shifttimes[$key]);
                                            }                                            
                                        }
                                    }                                    
                                    $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                    //update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                                }
                                $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $etask_time;
                                update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                            }
                            else{
                                update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
                            }
                        }
                        else{
                            $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
                            if(!empty($get_shift_time)){                            
                                if(!empty($tasktimes)){
                                    $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                    for($s=0; $s<count($tasktimes); $s++){
                                        $stime = $tasktimes[$s];
                                        if(!empty($stime)){
                                            if (($key = array_search($stime, $shifttimes)) !== false) {
                                                unset($shifttimes[$key]);
                                            }                                            
                                        }
                                    }                                    
                                    $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                    //update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                                }
                                $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $etask_time;
                                update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                            }
                            else{
                                update_post_meta( $taskid, 'get_shift_time', $shift_time );
                            }
                        }
                    
                        // to store max value of task for user 
                        if($etask_maxval != $task_maxval){
                            
                            
                            $max_task_signuped = array();
                            $max_key = $signupid."_".$taskid;
                            $max_task_signuped[$max_key] = $task_maxval;
                            $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                            
                            if(!empty($get_max_user_task_signup)){                                    
                                $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $task_maxval;
                                $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $etask_maxval;
                                update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                            }
                            else{
                                // print_r($max_task_signuped);
                                // echo $max_task_signuped;
                                //update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
                            }
                            // echo $etask_maxval . " == ";
                            // echo $task_maxval;
                           
                            // to add task hours/points to user 
                            if(!empty($myArray["task_hours_points".$taskid][0])){
                                $task_hours_points = $myArray["task_hours_points".$taskid][0];
                                $hours_points[$taskid] = $task_hours_points;
                                $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                                if(!empty($get_user_task_hours)){
                                    $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
                                    $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $etask_hours_points;
                                    update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                                }
                                else{
                                    update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
                                } 
                            } 
                            $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                            $single_tasks_advance_options = get_post_meta( $taskid, "single_tasks_advance_options", true );
                            
                            $total = $single_tasks_advance_options['single']['how_money_volunteers'];
                            
                           
                            if(!empty($get_filled)){
                                $get_filled = $get_filled - $task_maxval;
                                $get_filled = $get_filled + $etask_maxval;
                                if( $get_filled > $total ){
                                    
                                    ?>
                                    er
                                    <?php
                                    die();
                                }else{
                                    update_post_meta( $taskid, "signup_task_availability", $get_filled );
                                }
                            }
                            else{
                                update_post_meta( $taskid, "signup_task_availability", $task_maxval );
                            }
                           
                        }
                    }
                    else{
                        
                        // to add shift time for task and user                         
                        $shift_key = $current_user_id; 
                        $tasktimes = explode(",", $task_time);
                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                            $taskid_explode = explode("_", $taskid);
                            $tid = $taskid_explode[0];
                            $tdate = $taskid_explode[1];
                            $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );                        
                            if(!empty($get_shift_time) && !empty($tasktimes)){
                                $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                for($s=0; $s<count($tasktimes); $s++){
                                    $stime = $tasktimes[$s];
                                    if(!empty($stime)){
                                        if (($key = array_search($stime, $shifttimes)) !== false) {
                                            unset($shifttimes[$key]);
                                        }                                            
                                    }
                                }                                    
                                $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                            }
                            //to fill task availability 
                            $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
                            if(!empty($get_filled)){
                                $get_filled = $get_filled - $task_maxval;
                                update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
                            }   
                        }
                        else{
                            $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );                        
                            if(!empty($get_shift_time) && !empty($tasktimes)){
                                $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                for($s=0; $s<count($tasktimes); $s++){
                                    $stime = $tasktimes[$s];
                                    if(!empty($stime)){
                                        if (($key = array_search($stime, $shifttimes)) !== false) {
                                            unset($shifttimes[$key]);
                                        }                                            
                                    }
                                }                                    
                                $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                            }
                            //to fill task availability 
                            $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                            if(!empty($get_filled)){
                                $get_filled = $get_filled - $task_maxval;
                                update_post_meta( $taskid, "signup_task_availability", $get_filled );
                            }   
                        }
                        
                        // to store max value of task for user
                        $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                        $max_key = $signupid."_".$taskid;
                        $maxval = $get_max_user_task_signup[$max_key];
                        if(!empty($get_max_user_task_signup)){
                            $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $maxval;
                            update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                        }      
                        // to add task hours/points to user                                 
                        if(!empty($task_hours_points)){                 
                            $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                            if(!empty($get_user_task_hours)){
                                $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
                                update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                            }
                        }
                        // to add user to manage volunteers
                        /*$get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );                                
                        $selected_date = get_post_meta( $taskid,"pto_sign_up_selected_date_time",true);
                        
                        if(!empty($selected_date)){
                            unset($selected_date[$current_user_id][$task_date]);
                            update_post_meta($taskid,"pto_sign_up_selected_date_time",$selected_date);
                        }
                        $selected_date = get_post_meta( $taskid,"pto_sign_up_selected_date_time",true);*/
                    }
                }
                //$sql =  $wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".serialize($myArray)."' WHERE ID = ".$editid );            
                $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".esc_sql(serialize($myArray))."' WHERE ID = ".intval($editid)));
                if($result){
                    ?>
                    updated successfully
                    <?php
                }
                else{
                    ?>
                    error
                    <?php
                }
                
                /*if(!empty($get_manage_volunters)){
                    update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
                }
                else{
                    $total_array = array();
                    $total_array[$current_user_id] = $all_task_ids;                        
                    update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
                }*/
                $cur_user_obj = get_user_by('id', $current_user_id);
                $cuname = $cur_user_obj->display_name;
                // send "Receipt" to volunteer after they sign up 
                $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                $signuptitle = get_the_title($signupid);
                $to = $cur_user_obj->user_email;
                if(!empty($volunteer_after_sign_up)){
                    $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
                    if(!empty($mailcontent)){
                        $arra = array("/{{User Name}}/", "/{{Signup Name}}/");
                        $arra2 = array($cuname, $signuptitle);                                      
                        $mail = preg_replace($arra, $arra2, $mailcontent);
                        
                        $subject = 'Signup Update Success';
                        $body = $mail;                    
                        $headers = array('Content-Type: text/html; charset=UTF-8');                    
                        //wp_mail( $to, $subject, $body, $headers );
                    }
                }
                
                // send notification to admins 
                                    
                $author_id = get_post_field( 'post_author', $signupid );
                $user_info = get_userdata($author_id);            
                $to = $user_info->user_email;
                $admin_name = $user_info->display_name;
                $administrators_notifcations = get_option('administrators_notifcations');                     
                $arra = array("/{{Admin Name}}/", "/{{User Name}}/","/{{Full Name}}/");
                $arra2 = array($admin_name, $cuname);                                      
                $mail = preg_replace($arra, $arra2, $administrators_notifcations);
                
                $subject = 'Signup Updation';
                $body = $mail;                    
                $headers = array('Content-Type: text/html; charset=UTF-8');                    
                //wp_mail( $to, $subject, $body, $headers );
                
                $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
                if(!empty($notified_users)){                   
                    
                    foreach($notified_users as $assign_user)
                    {
                        $author_obj = get_user_by('id', $assign_user);
                        $to = $author_obj->user_email;
                        $uname = $author_obj->display_name;
                        $arra2 = array($uname, $cuname);                                      
                        $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
                        $body = $mail;                            
                        $headers = array('Content-Type: text/html; charset=UTF-8');                            
                        //wp_mail( $to, $subject, $body, $headers );
                    }
                }                 
            }
            $signupid = $myArray["signup_id"][0];
            $total_task = count($myArray["task_id".$signupid]);
            for($j=0; $j<$total_task; $j++){ 
                $hours_points = array();
                $taskid = $myArray["task_id".$signupid][$j];
                $task_maxval = $myArray["task_max".$taskid][0]; 
                $task_date = $myArray["task_date".$taskid][0];
                $task_time = $myArray["task_time".$taskid][0];
                $task_hours_points = $myArray["task_hours_points".$taskid][0];
                if(in_array($taskid, $saved_task_ids)){
                }
                else{
                    // to add shift time for task and user                         
                    $shift_key = $current_user_id;
                    $shift_time = array();                        
                    $shift_time[$shift_key] = $task_time;
                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
                        $taskid_explode = explode("_", $taskid);
                        $tid = $taskid_explode[0];
                        $tdate = $taskid_explode[1]; 
                        $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                        if(!empty($get_shift_time)){
                            $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                            update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                        }
                        else{
                            update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
                        } 
                        //to fill task availability 
                        $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
                        if(!empty($get_filled)){
                            $get_filled = $get_filled + $task_maxval;
                            update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
                        }
                        else{
                            update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
                        }
                    }
                    else{
                        $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
                        if(!empty($get_shift_time)){
                            $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                            update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                        }
                        else{
                            update_post_meta( $taskid, 'get_shift_time', $shift_time );
                        } 
                        //to fill task availability 
                        $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                        if(!empty($get_filled)){
                            $get_filled = $get_filled + $task_maxval;
                            update_post_meta( $taskid, "signup_task_availability", $get_filled );
                        }
                        else{
                            update_post_meta( $taskid, "signup_task_availability", $task_maxval );
                        }
                    }
                    
                    // to store max value of task for user 
                    $max_task_signuped = array();
                    $max_key = $signupid."_".$taskid;
                    $max_task_signuped[$max_key] = $task_maxval;
                    $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                    if(!empty($get_max_user_task_signup)){
                        $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
                        update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                    }
                    else{
                        update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
                    } 
                    // to add task hours/points to user 
                    if(!empty($myArray["task_hours_points".$taskid][0])){
                        $task_hours_points = $myArray["task_hours_points".$taskid][0];
                        $hours_points[$taskid] = $task_hours_points;
                        $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                        if(!empty($get_user_task_hours)){
                            $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
                            update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                        }
                        else{
                            update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
                        } 
                    }
                }
            }
            ?>
                Sign Up updated
            <?php
        }           
        die();
    }
    
    /**
    * Save user's sign up
    * @since    1.0.0
    * @access   public
    **/
    // public function pto_sign_up_checkout() {
        
    //     if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    //         die ( 'Busted!');
    //     }
    //     global $wpdb;
    //     $uid = 0;
    //     $today =  date("Y-m-d");
    //     if(isset($_POST['keyword']) && isset($_POST['uid'])){
    //         $keyword =  $_POST['keyword'] ;
    //         $uid = intval( $_POST['uid'] );
    //     }
    //     $myArray = array();
    //     parse_str($_POST['keyword'], $myArray);

    //     $get_user_signup_data_em = array();
    //     $get_user_signup_data = array();
    //     $table_name = $wpdb->prefix . "signup_orders";        
    //     $avachk = 0;        
    //     $signupidschk = array();        
    //     $shifttimechk = array();
    //     $total_signup = count($myArray["signup_id"]);

    //     for($i=0; $i<$total_signup; $i++){
    //         $signupid = $myArray["signup_id"][$i];
    //         $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
    //         if( empty( $pto_sign_up_occurrence ) ){
    //             $pto_sign_up_occurrence = array();
    //         }
            
    //         $total_task = count($myArray["task_id".$signupid]);
    //         for($j=0; $j<$total_task; $j++){
    //             $taskid = $myArray["task_id".$signupid][$j];
    //             if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                    
    //                 $taskid_explode = explode("_", $taskid);
    //                 $tid = $taskid_explode[0];
    //                 $tdate = $taskid_explode[1];
    //             }
    //             else{
    //                 $tid = $taskid;
    //             }
    //             $task_maxval = $myArray["task_max".$taskid][0]; 
              
    //             $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
    //             if(!empty($get_filed)){
    //                 $get_availability = "";
    //                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                     $get_availability = get_post_meta( $tid, "signup_task_availability".$taskid, true );
    //                 }
    //                 else{
    //                     $get_availability = get_post_meta( $tid, "signup_task_availability", true );
    //                 }           
                    
    //                 if(array_key_exists("single",$get_filed)){
    //                     $total_volantears = $get_filed['single']["how_money_volunteers"];
    //                     $total_volantears_sign_ups = $get_filed['single']["how_money_volunteers_sign_ups"];
    //                     if($total_volantears == "")
    //                     {
    //                         $total_volantears = 0;
    //                     }
    //                     if($total_volantears_sign_ups == "")
    //                     {
    //                         $total_volantears_sign_ups = 0;
    //                     }
    //                     $total = $total_volantears;
    //                     $f = intval($total) - intval($get_availability);
    //                     if(!empty($get_availability)){ 
                           
    //                         if( $task_maxval > $f ){
    //                             $avachk = 1;
    //                             $signupidschk[$signupid][$j] = $taskid;
    //                         }
    //                         // if($get_availability == $total){
    //                         //     $avachk = 1;
    //                         //     $signupidschk[$signupid][$j] = $taskid;
    //                         // }else{
    //                         //     // $diff = $total - $get_availability;
    //                         // } 
                            
    //                     }
    //                 }
                   
    //                 if(array_key_exists("shift", $get_filed)){
    //                     $total_volantears = $get_filed['shift']["volunteers_shift"];
    //                     $total_volantears_sign_ups = $get_filed['shift']["volunteers_shift_times"];
    //                     $shift_meta = $get_filed["shift"];
    //                     $count = 0;
    //                     $array_of_time = array();
                        
    //                     if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
    //                         $shift_start = $shift_meta['first-shift'];
    //                         $shift_end = $shift_meta['last-end-shift'];
    //                         $shift_min = $shift_meta['how-long-shift'];
    //                         $break_time = $shift_meta['between-shift-minutes'];                            
    //                         $start_time    = strtotime ($shift_start); 
    //                         $end_time      = strtotime ($shift_end);
    //                         $add_mins  = $shift_min * 60;
    //                         $break_min = $break_time * 60; 
    //                         $i = 0;                                 
    //                         while ($start_time <= $end_time) {                                                                                                                                              
    //                             $array_of_time[$i] = date ("h:i A", $start_time);
    //                             $start_time += ($add_mins + $break_min);
    //                             $count++;
    //                             $i++;
    //                         }
    //                         if($total_volantears == "")
    //                         {
    //                             $total_volantears = 0;
    //                         }
    //                         if($total_volantears_sign_ups == "")
    //                         {
    //                             $total_volantears_sign_ups = 0;
    //                         }
    //                         $end_val = strtotime(end($array_of_time));
    //                         if($end_val == $end_time){
    //                             if($count != 0){
    //                                 $count = $count - 1;
    //                             }
    //                         }
    //                         $total = $count * $total_volantears;
    //                         if(!empty($get_availability)){                            
    //                             if($get_availability == $total){
    //                                 $avachk = 1;
    //                                 $signupidschk[$signupid][$j] = $taskid;                                
    //                             }else{
    //                                 $task_time_array = explode(",", $task_time);
    //                                 $shift_meta_time = array();
    //                                 $all_shifts = "";
    //                                 $current_user_shift = array();
    //                                 $get_shift_time = get_post_meta( $tid, 'get_shift_time', true );
    //                                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                                     $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
    //                                 }
                                                                    
    //                                 if(!empty($get_shift_time)){                                    
    //                                     if(array_key_exists( $uid, $get_shift_time )){
    //                                         $current_user_shift = explode(",", $get_shift_time[$uid]);                                        
    //                                     }
    //                                     foreach($get_shift_time as $usid){
    //                                         $all_shifts .= $usid;
    //                                     }
    //                                     $shift_meta_time = explode(",", $all_shifts);
    //                                     //print_r($shift_meta_time);
    //                                 }
    //                                 $shift_count = count($array_of_time);
    //                                 $i = 0;
    //                                 $disabled_times = "";
    //                                 while ($i < $shift_count) {
    //                                     $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                               
    //                                     if(!empty($shift_meta_time)){
    //                                         if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
    //                                             if(in_array($array_of_time[$i], $task_time_array)){
    //                                                 $avachk = 1;
    //                                                 $signupidschk[$signupid][$j] = $taskid;
    //                                             }
    //                                         }   
    //                                         elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
    //                                         }                                                                           
    //                                         elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
    //                                             $count_values = array_count_values($shift_meta_time);
    //                                             $this_shift_count = $count_values[$array_of_time[$i]];
    //                                             if($this_shift_count == $total_volantears){
    //                                                 if(in_array($array_of_time[$i], $task_time_array)){
    //                                                     $avachk = 1;
    //                                                     $signupidschk[$signupid][$j] = $taskid;
    //                                                 }
    //                                             }
    //                                             elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
    //                                                 if(in_array($array_of_time[$i], $task_time_array)){
    //                                                     $avachk = 1;
    //                                                     $signupidschk[$signupid][$j] = $taskid;
    //                                                 }
    //                                             }
    //                                             else{                                                
    //                                             }
    //                                         }                                                                               
    //                                         else{                                             
    //                                         }                                                                               
    //                                     }
    //                                     elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
    //                                     }
    //                                     else{                                        
    //                                     }                                                                           
    //                                     $i++;   
    //                                 }
    //                             }
    //                         }
    //                         else{                        
                                
    //                         }
    //                     }                        
    //                 }
    //             }
    //         }
    //     }
    //     $error = "Following Task's availability is filled out so please remove this sign up to continue.";
       
    //     if(!empty($signupidschk)){
    //         foreach($signupidschk as $snup => $tsk){
    //             $error .= " Signup name: ".get_the_title($snup)." Task names: ";
    //             $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
    //             foreach($tsk as $tskid){
    //                 if(!empty($tskid)){
    //                     $tdate = "";
    //                     if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                          
    //                         $taskid_explode = explode("_", $tskid);
    //                         $tid = $taskid_explode[0];
    //                         $tdate = $taskid_explode[1];
    //                     }
    //                     else{
    //                         $tid = $tskid;
    //                     }
    //                     $error .= " ".get_the_title($tid)." ".$tdate.",";
    //                 }
    //             }
    //         }
    //         // $err_respose =  substr($error, 0, -1);
    //         esc_html_e(substr($error, 0, -1));
    //     }
    //     else{
    //         if(is_user_logged_in()){
                
    //             $signup_details = "<div>";
    //             $current_user_id = $uid;  
   
    //                 $total_signup = count($myArray["signup_id"]);   
    //                 echo "<pre>";
    //                 print_r($myArray);
    //                 echo "</pre>";
    //                 die();
    //                 for($i=0; $i<$total_signup; $i++){
    //                     $new_myArray = array();
    //                     $signup_details = "<div>";
    //                     $signupid = $myArray["signup_id"][$i]; 
    //                     $signup_name = get_the_title($signupid);
    //                     $signup_details .= "<p><strong>Signup Name: </strong>";
    //                     $signup_details .= "<a href='".get_the_permalink($signupid)."' target='_blank' >".get_the_title($signupid)."</a>";
    //                     //$signup_details .= get_the_title($signupid);
    //                     $signup_details .= "</p>";
    //                     $new_myArray["signup_id"][0] = $signupid;
    //                     $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );    
    //                     // to add signup custom fields
    //                     $signup_custom_info = "";
    //                     foreach($myArray as $akey => $avalue){                        
    //                         $keyarray = explode("_", $akey);
    //                         if(in_array($signupid, $keyarray) && in_array("signup", $keyarray)){
    //                             $vcount = count($avalue);
    //                             $signup_custom_info .= "<p><strong>";
    //                             $signup_custom_info .= get_the_title($keyarray[2]);
    //                             $signup_custom_info .= ": </strong>";
    //                             for($v=0; $v<$vcount; $v++){
    //                                 $new_myArray[$akey][$v] = $avalue[$v];
    //                                 if($v == 0){
    //                                     $signup_custom_info .= $avalue[$v];
    //                                 }
    //                                 else{
    //                                     $signup_custom_info .= ", ";
    //                                     $signup_custom_info .= $avalue[$v];
    //                                 }
    //                             } 
                                
    //                             $signup_custom_info .= "</p>";                           
    //                         }                        
    //                     }
    //                     if(!empty($signup_custom_info)){
    //                         $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
    //                         $signup_details .= $signup_custom_info;
    //                     }
    
    //                     $agreekey = "agree_to_terms_signup".$signupid;
    //                     if(array_key_exists($agreekey, $myArray)){
    //                         $agreetoterms = $myArray[$agreekey][0];
    //                         $new_myArray[$agreekey][0] = $agreetoterms;
    //                     }           
    //                     $total_task = count($myArray["task_id".$signupid]);
    //                     $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
    //                     $all_task_ids = array();
                        
    //                     for($j=0; $j<$total_task; $j++){ 
    //                         $hours_points = array();
    //                         $taskid = $myArray["task_id".$signupid][$j];
    //                         $task_maxval = $myArray["task_max".$taskid][0]; 
    //                         $task_date = $myArray["task_date".$taskid][0];
    //                         $task_time = $myArray["task_time".$taskid][0];
    //                         $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                         $tid = "";
    //                         if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                             $taskid_explode = explode("_", $taskid);
    //                             $tid = $taskid_explode[0];
    //                         }
    //                         else{
    //                             $tid = $taskid;
    //                         }
    //                         $signup_details .= "<p><strong>Task Name:</strong>";
    //                         $signup_details .= get_the_title($tid);
    //                         $signup_details .= "</p>";
    //                         if(!empty($task_date)){
    //                             $signup_details .= "<p><strong>Task Date:</strong>";
    //                             $signup_details .= $task_date;
    //                             $signup_details .= "</p>";
    //                         }
    //                         if(!empty($task_time)){
    //                             $signup_details .= "<p><strong>Task Time:</strong>";
    //                             $signup_details .= $task_time;
    //                             $signup_details .= "</p>";
    //                         }
    //                         $new_myArray["task_id".$signupid][$j] = $taskid;
    //                         $new_myArray["task_max".$taskid][0] = $task_maxval;
    //                         $new_myArray["task_date".$taskid][0] = $task_date;
    //                         $new_myArray["task_time".$taskid][0] = $task_time;
    //                         $new_myArray["task_hours_points".$taskid][0] = $task_hours_points;                        
    
    //                         // to store max value of task for user 
    //                         $max_task_signuped = array();
    //                         $max_key = $signupid."_".$taskid;
    //                         $max_task_signuped[$max_key] = $task_maxval;
    //                         $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
    //                         if(!empty($get_max_user_task_signup)){
    //                             $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
    //                             update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
    //                         }
    //                         else{
    //                             update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
    //                         } 
    
    //                          // to add task hours/points to user 
    //                          if(!empty($myArray["task_hours_points".$taskid][0])){
    //                             $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                             $hours_points[$taskid] = $task_hours_points;
    //                             $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
    //                             if(!empty($get_user_task_hours)){
    //                                 $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
    //                                 update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
    //                             }
    //                             else{
    //                                 update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
    //                             } 
    //                         }
                            
    //                         if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
    //                             $taskid_explode = explode("_", $taskid);
    //                             $tid = $taskid_explode[0];
    //                             $tdate = $taskid_explode[1]; 
    
    //                             // to add task custom fields
    //                             $custom_field_info = "";
    //                             foreach($myArray as $akey => $avalue){                        
    //                                 $keyarray = explode("_", $akey);
    //                                 if(in_array($tid, $keyarray) && in_array($tdate, $keyarray)){
    //                                     $vcount = count($avalue);
    //                                     $custom_field_info .= "<p><strong>";
    //                                     $custom_field_info .= get_the_title($keyarray[2]);
    //                                     $custom_field_info .= ": </strong>";
                                        
    //                                     for($v=0; $v<$vcount; $v++){
    //                                         $new_myArray[$akey][$v] = $avalue[$v];
    //                                         if($v == 0){
    //                                             $custom_field_info .= $avalue[$v];
    //                                         }
    //                                         else{
    //                                             $custom_field_info .= ", ";
    //                                             $custom_field_info .= $avalue[$v];
    //                                         }
    //                                     }  
    //                                     $custom_field_info .= "</p>";                         
    //                                 }                        
    //                             }
    //                             if(!empty($custom_field_info)){
    //                                 $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
    //                                 $signup_details .= $custom_field_info;
    //                             }
    //                             // to add shift time for task and user
    //                             $shift_key = $current_user_id;
    //                             $shift_time = array();                        
    //                             $shift_time[$shift_key] = $task_time;
                                
    //                             $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
    //                             if(!empty($get_shift_time)){
    //                                 $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
    //                                 update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
    //                             }
    //                             else{
    //                                 update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
    //                             } 
    
    //                             //to fill task availability                            
    //                             $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
    //                             if(!empty($get_filled)){
    //                                 $get_filled = $get_filled + $task_maxval;
    //                                 update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
    //                             }
    //                             else{
    //                                 update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
    //                             }
    //                         }
    //                         else{
    
    //                             // to add task custom fields
    //                             $custom_field_info = "";
    //                             foreach($myArray as $akey => $avalue){                        
    //                                 $keyarray = explode("_", $akey);
    //                                 if(in_array($taskid, $keyarray)){
    //                                     $vcount = count($avalue);
    //                                     $custom_field_info .= "<p><strong>";
    //                                     $custom_field_info .= get_the_title($keyarray[2]);
    //                                     $custom_field_info .= ": </strong>";
                                        
    //                                     for($v=0; $v<$vcount; $v++){
    //                                         $new_myArray[$akey][$v] = $avalue[$v];
    //                                         if($v == 0){
    //                                             $custom_field_info .= $avalue[$v];
    //                                         }
    //                                         else{
    //                                             $custom_field_info .= ", ";
    //                                             $custom_field_info .= $avalue[$v];
    //                                         }
                                            
    //                                     }  
    //                                     $custom_field_info .= "</p>";                          
    //                                 }                        
    //                             }
    //                             if(!empty($custom_field_info)){
    //                                 $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
    //                                 $signup_details .= $custom_field_info;
    //                             }
    //                             // to add shift time for task and user                         
    //                             $shift_key = $current_user_id;
    //                             $shift_time = array();                        
    //                             $shift_time[$shift_key] = $task_time;
    //                             $shift_check = get_post_meta( $taskid, "single_tasks_advance_options", true );
    //                             if( array_key_exists( "shift" , $shift_check ) ){
    //                                 $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
    //                                 if(!empty($get_shift_time)){
                                        
    //                                     $same_data = 0;
                                        
    //                                     foreach($get_shift_time as $key => $value){
    //                                         $postion_check = str_contains( trim($value) , trim($task_time) );
    //                                         if( !empty( $postion_check ) ){
    //                                             $same_data = 1;
    //                                         }
    //                                     } 
                                        
                                       
    //                                     if( $same_data != 1 ){
    //                                         if( !empty($get_shift_time[$shift_key]) ){
    //                                             $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
    //                                         }else{
    //                                             $get_shift_time[$shift_key] = $task_time;
    //                                         }
    //                                          update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
    //                                     }else{
    //                                         echo "Alredy Sign up";
    //                                         die();
    //                                     }
                                        
    //                                 }
    //                                 else{
                                        
    //                                      update_post_meta( $taskid, 'get_shift_time', $shift_time );
    //                                 } 
    //                             }
                               
                                
    //                             $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                             //to fill task availability 
    //                             if(!empty($get_filled)){
    //                                 $get_filled = $get_filled + $task_maxval;
    //                                 update_post_meta( $taskid, "signup_task_availability", $get_filled );
    //                             }
    //                             else{
    //                                 update_post_meta( $taskid, "signup_task_availability", $task_maxval );
    //                             }
    //                         }
    //                     }
                      
    //                     $result = $wpdb->query($wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) VALUES (NULL, ".intval($current_user_id).", ".intval($signupid).", '".esc_sql(serialize($new_myArray))."', '".esc_sql($today)."', 'on');" ));
    //                     if(!empty($get_manage_volunters)){
    //                         update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
    //                     }
    //                     else{
    //                         $total_array = array();
    //                         $total_array[$current_user_id] = $all_task_ids;                        
    //                         update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
    //                     }
    //                     /* get last signup details */
    //                     //$lastid = $wpdb->insert_id;    
    //                     $cur_user_obj = get_user_by('id', $current_user_id);
    //                     $cuname = $cur_user_obj->first_name . " " . $cur_user_obj->last_name;
    //                     $first_name = $cur_user_obj->first_name;
    //                     $last_name = $cur_user_obj->last_name;
    //                     if(empty($first_name)){
    //                         $first_name = $user_info->display_name;
    //                     } 
    //                     if(empty($last_name)){
    //                         $last_name = $user_info->display_name;
    //                     }
    //                     $signup_details .= "</div>";
    //                     // send "Receipt" to volunteer after they sign up 
    //                     $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
    //                     $signuptitle = get_the_title($signupid);
    //                     $to = $cur_user_obj->user_email;
    //                     if(!empty($volunteer_after_sign_up)){
    //                         $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
    //                         if(!empty($mailcontent)){
    //                             $arra = array("/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
    //                             $arra2 = array($cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
    //                             $mail = preg_replace($arra, $arra2, $mailcontent);                                
    //                             $subject = 'You have successfully done the signup.';
    //                             $body = $mail;                    
    //                             $headers = array('Content-Type: text/html; charset=UTF-8');   
                                                     
    //                             wp_mail( $to, $subject, $body, $headers );
    //                         }
    //                     }                        
    //                     // send notification to admins                                           
                       
    //                     $author_id = get_post_field( 'post_author', $signupid );
    //                     $user_info = get_userdata($author_id);            
    //                     $to = $user_info->user_email;
    //                     $admin_name = $user_info->display_name;    
    //                     $administrators_notifcations = get_option('administrators_notifcations');                     
    //                     $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
    //                     $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
    //                     $mail = preg_replace($arra, $arra2, $administrators_notifcations);                        
    //                     $subject = 'You have got the new signup details.';
    //                     $body = $mail;                    
    //                     $headers = array('Content-Type: text/html; charset=UTF-8');        
    //                     wp_mail( $to, $subject, $body, $headers );                        
                        
    //                     $administrators_notifcations = get_option( "defult_wording_volunteers" );;                     
    //                     $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
    //                     $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
    //                     $mail = preg_replace($arra, $arra2, $administrators_notifcations);                        
    //                     $subject = 'You have got the new signup details.';
    //                     $body = $mail;                    
    //                     $headers = array('Content-Type: text/html; charset=UTF-8');   
    //                     $user_info = get_userdata(get_current_user_id());            
    //                     $to = $user_info->user_email;
    //                     wp_mail( $to, $subject, $body, $headers );
    //                     $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
    //                     if(!empty($notified_users)){  
    //                         foreach($notified_users as $assign_user)
    //                         {
    //                             $author_obj = get_user_by('id', $assign_user);
    //                             $to = $author_obj->user_email;
    //                             $admin_name = $author_obj->display_name;
    //                             $arra2 = array($admin_name, $cuname, $signup_details, $first_name, $last_name);                                       
    //                             $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
    //                             $body = $mail;                            
    //                             $headers = array('Content-Type: text/html; charset=UTF-8');
    //                             wp_mail( $to, $subject, $body, $headers );
    //                         }
    //                     }                 
    //                 }
    //                 echo "Sign Up added";
    //             //}
    //         }
    //         else{
    //             $signup_details = "<div>";
    //             //echo "not login";
    //             $fname = $myArray['pto_signup_user_fname'];
    //             $lname = $myArray['pto_signup_user_lname'];
    //             $email = $myArray['pto_signup_user_email'];
    //             $password = wp_generate_password( 8, false );
    //             if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                 echo "Invalid Email ID";
    //             }
    //             else{
    //                 if (username_exists($fname) == null && email_exists($email) == false) {
    
    //                     // Create the new user
    //                     $user_id = wp_create_user($fname, $password, $email);
    //                     $user_data = wp_update_user( array( 'ID' => $user_id, 'first_name' => $fname, 'last_name' => $lname ) );
                        
    //                     // Get current user object
    //                     $user = get_user_by('id', $user_id);
        
    //                     // Remove role
    //                     //$user->remove_role('subscriber');
        
    //                     // Add role
    //                     //$user->add_role('own_sign_up');
    //                     echo "User created";
    //                     /* sent pwd to user */
    //                     $to = $email;
    //                     $subject = 'Singup User Password';
    //                     $body = 'Hello '.$fname.'! Yor are successfully registered. Your password is: '.$password;
    //                     $headers = array('Content-Type: text/html; charset=UTF-8');            
    //                     wp_mail( $to, $subject, $body, $headers );
    //                     //$sql =  $wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, order_info, checkout_date, status) 
    //                     //        VALUES (NULL, ".$user_id.", '".serialize($myArray)."', '".$today."', 'on');" );                                   
    //                     //$result = $wpdb->query($sql);
    //                     //if($result){
    //                         $total_signup = count($myArray["signup_id"]);            
    //                         for($i=0; $i<$total_signup; $i++){
    //                             $new_myArray = array();
    //                             $signup_details = "<div>";
    //                             $signupid = $myArray["signup_id"][$i]; 
    //                             $signup_name = get_the_title($signupid);
    //                             $signup_details .= "<p><strong>Signup Name: </strong>";
    //                             $signup_details .= "<a href='".get_the_permalink($signupid)."' target='_blank' >".get_the_title($signupid)."</a>";
    //                             //$signup_details .= get_the_title($signupid);
    //                             $signup_details .= "</p>";
    //                             $new_myArray["signup_id"][0] = $signupid;
    //                             $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
    //                             // to add signup custom fields
    //                             $signup_custom_info = "";
    //                             foreach($myArray as $akey => $avalue){                        
    //                                 $keyarray = explode("_", $akey);
    //                                 if(in_array($signupid, $keyarray) && in_array("signup", $keyarray)){
    //                                     $vcount = count($avalue);
    //                                     $signup_custom_info .= "<p><strong>";
    //                                     $signup_custom_info .= get_the_title($keyarray[2]);
    //                                     $signup_custom_info .= ": </strong>";
    //                                     for($v=0; $v<$vcount; $v++){
    //                                         $new_myArray[$akey][$v] = $avalue[$v];
    //                                         if($v == 0){
    //                                             $signup_custom_info .= $avalue[$v];
    //                                         }
    //                                         else{
    //                                             $signup_custom_info .= ", ";
    //                                             $signup_custom_info .= $avalue[$v];
    //                                         }
        
    //                                     } 
                                        
    //                                     $signup_custom_info .= "</p>";                        
    //                                 }                        
    //                             }
    //                             if(!empty($signup_custom_info)){
    //                                 $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
    //                                 $signup_details .= $signup_custom_info;
    //                             }    
    //                             $agreekey = "agree_to_terms_signup".$signupid;
    //                             if(array_key_exists($agreekey, $myArray)){
    //                                 $agreetoterms = $myArray[$agreekey][0];
    //                                 $new_myArray[$agreekey][0] = $agreetoterms;
    //                             }              
    //                             $total_task = count($myArray["task_id".$signupid]);
    //                             $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
    //                             $all_task_ids = array();
    //                             for($j=0; $j<$total_task; $j++){ 
    //                                 $hours_points = array();
    //                                 $taskid = $myArray["task_id".$signupid][$j];
    //                                 $task_maxval = $myArray["task_max".$taskid][0];                                 
    //                                 $task_date = $myArray["task_date".$taskid][0];
    //                                 $task_time = $myArray["task_time".$taskid][0];
    //                                 $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                                 $tid = "";
    //                                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                                     $taskid_explode = explode("_", $taskid);
    //                                     $tid = $taskid_explode[0];
    //                                 }
    //                                 else{
    //                                     $tid = $taskid;
    //                                 }
    //                                 $signup_details .= "<p><strong>Task Name:</strong>";
    //                                 $signup_details .= get_the_title($tid);
    //                                 $signup_details .= "</p>";
    //                                 if(!empty($task_date)){
    //                                     $signup_details .= "<p><strong>Task Date:</strong>";
    //                                     $signup_details .= $task_date;
    //                                     $signup_details .= "</p>";
    //                                 }
    //                                 if(!empty($task_time)){
    //                                     $signup_details .= "<p><strong>Task Time:</strong>";
    //                                     $signup_details .= $task_time;
    //                                     $signup_details .= "</p>";
    //                                 }
    //                                 $new_myArray["task_id".$signupid][$j] = $taskid;
    //                                 $new_myArray["task_max".$taskid][0] = $task_maxval;
    //                                 $new_myArray["task_date".$taskid][0] = $task_date;
    //                                 $new_myArray["task_time".$taskid][0] = $task_time;
    //                                 $new_myArray["task_hours_points".$taskid][0] = $task_hours_points;
    
    //                                 //to store max value of task for user 
    //                                 $max_task_signuped = array();
    //                                 $max_key = $signupid."_".$taskid;
    //                                 $max_task_signuped[$max_key] = $task_maxval;
    //                                 $get_max_user_task_signup = get_user_meta( $user_id, 'max_user_task_signup', true );
    //                                 if(!empty($get_max_user_task_signup)){
    //                                     $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
    //                                     update_user_meta( $user_id, 'max_user_task_signup', $get_max_user_task_signup );
    //                                 }
    //                                 else{
    //                                     update_user_meta( $user_id, 'max_user_task_signup', $max_task_signuped );
    //                                 }
                                    
    //                                 //to add task hours/points to user 
    //                                 if(!empty($myArray["task_hours_points".$taskid][0])){
    //                                     $task_hours_points = $myArray["task_hours_points".$taskid][0];
    //                                     $hours_points[$taskid] = $task_hours_points;
    //                                     $get_user_task_hours = get_user_meta( $user_id, 'user_task_hours_points', true );
    //                                     if(!empty($get_user_task_hours)){
    //                                         $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
    //                                         update_user_meta( $user_id, 'user_task_hours_points', $get_user_task_hours );
    //                                     }
    //                                     else{
    //                                         update_user_meta( $user_id, 'user_task_hours_points', $hours_points );
    //                                     } 
    //                                 }
    
    //                                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
    //                                     $taskid_explode = explode("_", $taskid);
    //                                     $tid = $taskid_explode[0];
    //                                     $tdate = $taskid_explode[1]; 
    
    //                                     // to add task custom fields
    //                                     $custom_field_info = "";
    //                                     foreach($myArray as $akey => $avalue){                        
    //                                         $keyarray = explode("_", $akey);
    //                                         if(in_array($tid, $keyarray) && in_array($tdate, $keyarray)){
    //                                             $vcount = count($avalue);
    //                                             $custom_field_info .= "<p><strong>";
    //                                             $custom_field_info .= get_the_title($keyarray[2]);
    //                                             $custom_field_info .= ": </strong>";
                                                
    //                                             for($v=0; $v<$vcount; $v++){
    //                                                 $new_myArray[$akey][$v] = $avalue[$v];
    //                                                 if($v == 0){
    //                                                     $custom_field_info .= $avalue[$v];
    //                                                 }
    //                                                 else{
    //                                                     $custom_field_info .= ", ";
    //                                                     $custom_field_info .= $avalue[$v];
    //                                                 }
    //                                             }  
    //                                             $custom_field_info .= "</p>";                         
    //                                         }                        
    //                                     }
    //                                     if(!empty($custom_field_info)){
    //                                         $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
    //                                         $signup_details .= $custom_field_info;
    //                                     }
    //                                     // to add shift time for task and user
    //                                     $shift_key = $user_id;
    //                                     $shift_time = array();                        
    //                                     $shift_time[$shift_key] = $task_time;
                                        
    //                                     $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
    //                                     if(!empty($get_shift_time)){
    //                                         $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
    //                                         update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
    //                                     }
    //                                     else{
    //                                         update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
    //                                     } 
    
    //                                     //to fill task availability                            
    //                                     $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
    //                                     if(!empty($get_filled)){
    //                                         $get_filled = $get_filled + $task_maxval;
    //                                         update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
    //                                     }
    //                                     else{
    //                                         update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
    //                                     }
    //                                 }
    //                                 else{
    //                                     // to add task custom fields
    //                                     $custom_field_info = "";
    //                                     foreach($myArray as $akey => $avalue){                        
    //                                         $keyarray = explode("_", $akey);
    //                                         if(in_array($taskid, $keyarray)){
    //                                             $vcount = count($avalue);
    //                                             $custom_field_info .= "<p><strong>";
    //                                             $custom_field_info .= get_the_title($keyarray[2]);
    //                                             $custom_field_info .= ": </strong>";
    //                                             for($v=0; $v<$vcount; $v++){
    //                                                 $new_myArray[$akey][$v] = $avalue[$v];
    //                                                 if($v == 0){
    //                                                     $custom_field_info .= $avalue[$v];
    //                                                 }
    //                                                 else{
    //                                                     $custom_field_info .= ", ";
    //                                                     $custom_field_info .= $avalue[$v];
    //                                                 }
    //                                             } 
    //                                             $custom_field_info .= "</p>";             
    //                                         }                        
    //                                     }
    //                                     if(!empty($custom_field_info)){
    //                                         $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
    //                                         $signup_details .= $custom_field_info;
    //                                     }
    //                                     //to add shift time for task and user                       
    //                                     $shift_key = $user_id;
    //                                     $shift_time = array();                        
    //                                     $shift_time[$shift_key] = $task_time;                                
    //                                     $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
    //                                     if(!empty($get_shift_time)){
    //                                         $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
    //                                         update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
    //                                     }
    //                                     else{
    //                                         update_post_meta( $taskid, 'get_shift_time', $shift_time );
    //                                     } 
    
    //                                     //to fill task availability 
    //                                     $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
    //                                     // $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
    //                                     if(!empty($get_filled)){
    //                                         $get_filled = $get_filled + $task_maxval;
    //                                         update_post_meta( $taskid, "signup_task_availability", $get_filled );
    //                                     }
    //                                     else{
    //                                         update_post_meta( $taskid, "signup_task_availability", $task_maxval );
    //                                     }
    //                                 }
    
    //                                 //to add user to manage volunteers
    //                                 /*if(!empty($get_manage_volunters)){
    //                                     $get_manage_volunters[$user_id][$taskid] = $taskid;
    //                                 }
    //                                 else{
    //                                     $all_task_ids[$taskid] = $taskid;
    //                                 }
            
    //                                 $old_array = get_post_meta($taskid, "pto_sign_up_selected_date_time", true);
    //                                 if(!empty($old_array))
    //                                 {
    //                                     $arr = array();
    //                                     $arr[$task_date] = array(
    //                                         $task_date => $task_date,
    //                                         $task_time => $task_time
    //                                     );
    //                                     $old_data = $old_array[$user_id];
            
    //                                     if(empty($old_data))
    //                                     {  
    //                                         $old_array[$user_id] = $arr;                        
    //                                         update_post_meta($taskid, "pto_sign_up_selected_date_time", $old_array); 
    //                                     }else{
                                            
    //                                         $new_arr = array_merge($old_data, $arr);
    //                                         $n_arr[$user_id] = $new_arr;
    //                                         update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
    //                                     }
    //                                 }else{
    //                                     $arr = array();
    //                                     $arr[$task_date] = array(
    //                                         $task_date => $task_date,
    //                                         $task_time => $task_time
    //                                     );                            
    //                                     $n_arr[$user_id] = $arr;                                            
    //                                     update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
    //                                 }*/
    //                             }
    
    //                             //$sql =  $wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) 
    //                              //       VALUES (NULL, ".$user_id.", ".$signupid.", '".serialize($new_myArray)."', '".$today."', 'on');" );                                    
    //                             $result = $wpdb->query($wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) VALUES (NULL, ".intval($user_id).", ".intval($signupid).", '".esc_sql(serialize($new_myArray))."', '".esc_sql($today)."', 'on');" ));
    //                             /*if(!empty($get_manage_volunters)){
    //                                 update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
    //                             }
    //                             else{
    //                                 $total_array = array();
    //                                 $total_array[$user_id] = $all_task_ids;                        
    //                                 update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
    //                             }*/
                                
    //                             $cur_user_obj = get_user_by('id', $user_id);
    //                             $cuname = $cur_user_obj->first_name . " " . $cur_user_obj->last_name;
    //                             $first_name = $cur_user_obj->first_name;
    //                             $last_name = $cur_user_obj->last_name;
    //                             if(empty($first_name)){
    //                                 $first_name = $user_info->display_name;
    //                             } 
    //                             if(empty($last_name)){
    //                                 $last_name = $user_info->display_name;
    //                             }
    //                             $signup_details .= "</div>";
        
    //                             // send "Receipt" to volunteer after they sign up 
        
    //                             $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
        
    //                             $signuptitle = get_the_title($signupid);
        
    //                             $to = $cur_user_obj->user_email;
        
    //                             if(!empty($volunteer_after_sign_up)){
        
    //                                 $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
        
    //                                 if(!empty($mailcontent)){
        
    //                                     $arra = array("/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
        
    //                                     $arra2 = array($cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
        
    //                                     $mail = preg_replace($arra, $arra2, $mailcontent);                                
        
    //                                     $subject = 'You have successfully done the signup.';
        
    //                                     $body = $mail;                    
        
    //                                     $headers = array('Content-Type: text/html; charset=UTF-8');                    
        
    //                                     wp_mail( $to, $subject, $body, $headers );
        
    //                                 }
        
    //                             }                        
        
    //                             // send notification to admins                                           
                                
    //                             $author_id = get_post_field( 'post_author', $signupid );
        
    //                             $user_info = get_userdata($author_id);            
        
    //                             $to = $user_info->user_email;
        
    //                             $admin_name = $user_info->display_name;    
        
    //                             $administrators_notifcations = get_option('administrators_notifcations');                     
        
    //                             $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
        
    //                             $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
        
    //                             $mail = preg_replace($arra, $arra2, $administrators_notifcations);                        
        
    //                             $subject = 'You have got the new signup details.';
        
    //                             $body = $mail;                    
        
    //                             $headers = array('Content-Type: text/html; charset=UTF-8');                    
        
    //                             wp_mail( $to, $subject, $body, $headers );                        
        
    //                             $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
        
    //                             if(!empty($notified_users)){  
        
    //                                 foreach($notified_users as $assign_user)
    //                                 {
        
    //                                     $author_obj = get_user_by('id', $assign_user);
        
    //                                     $to = $author_obj->user_email;
        
    //                                     $admin_name = $author_obj->display_name;
        
    //                                     $arra2 = array($admin_name, $cuname, $signup_details, $first_name, $last_name);                                       
        
    //                                     $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
        
    //                                     $body = $mail;                            
        
    //                                     $headers = array('Content-Type: text/html; charset=UTF-8');                            
        
    //                                     wp_mail( $to, $subject, $body, $headers );
        
    //                                 }
        
    //                             }                 
    //                         }
    //                         echo "Sign Up added";                        
    //                         $loginuser = get_user_by('email', $email );        
    //                         // Redirect URL //
    //                         if ( !is_wp_error( $loginuser ) )
    //                         {
    //                             wp_clear_auth_cookie();
    //                             wp_set_current_user ( $loginuser->ID );
    //                             wp_set_auth_cookie ( $loginuser->ID );
    //                         }
    //                     //}                
    //                 }  
    //                 else{
    //                     echo "User already exist";
    //                 }
    //             }             
    //         }
    //     }         
    //     die();
    // }
     public function pto_sign_up_checkout() {
       
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        $uid = 0;
        $today =  date("Y-m-d");
        if(isset($_POST['keyword']) && isset($_POST['uid'])){
            $keyword = sanitize_text_field( $_POST['keyword'] );
            $uid = intval( $_POST['uid'] );
        }
        $uemail = sanitize_text_field($_POST['uemail']);
        $myArray = array();
        parse_str($_POST['keyword'], $myArray);
        $get_user_signup_data_em = array();
        $get_user_signup_data = array();
        $table_name = $wpdb->prefix . "signup_orders";        
        $avachk = 0;        
        $signupidschk = array();        
        $shifttimechk = array();
        $total_signup = count($myArray["signup_id"]);
        // echo "<pre>";
        // print_r($myArray);
        // echo "</pre>";
        for($i=0; $i<$total_signup; $i++){
            $signupid = $myArray["signup_id"][$i];
            $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
            if( empty( $pto_sign_up_occurrence ) ){
                $pto_sign_up_occurrence = array();
            }
            
            $total_task = count($myArray["task_id".$signupid]);
            for($j=0; $j<$total_task; $j++){
                $taskid = $myArray["task_id".$signupid][$j];
                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                    
                    $taskid_explode = explode("_", $taskid);
                    $tid = $taskid_explode[0];
                    $tdate = $taskid_explode[1];
                }
                else{
                    $tid = $taskid;
                }
                $task_maxval = $myArray["task_max".$taskid][0]; 
              
                $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
                if(!empty($get_filed)){
                    $get_availability = "";
                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                        $get_availability = get_post_meta( $tid, "signup_task_availability".$taskid, true );
                    }
                    else{
                        $get_availability = get_post_meta( $tid, "signup_task_availability", true );
                    }           
                    
                    if(array_key_exists("single",$get_filed)){
                        $total_volantears = $get_filed['single']["how_money_volunteers"];
                        $total_volantears_sign_ups = $get_filed['single']["how_money_volunteers_sign_ups"];
                        if($total_volantears == "")
                        {
                            $total_volantears = 0;
                        }
                        if($total_volantears_sign_ups == "")
                        {
                            $total_volantears_sign_ups = 0;
                        }
                        $total = $total_volantears;
                        $f = intval($total) - intval($get_availability);
                        if(!empty($get_availability)){ 
                           
                            if( $task_maxval > $f ){
                                $avachk = 1;
                                $signupidschk[$signupid][$j] = $taskid;
                            }
                            // if($get_availability == $total){
                            //     $avachk = 1;
                            //     $signupidschk[$signupid][$j] = $taskid;
                            // }else{
                            //     // $diff = $total - $get_availability;
                            // } 
                            
                        }
                    }
                   
                    if(array_key_exists("shift", $get_filed)){
                        $total_volantears = $get_filed['shift']["volunteers_shift"];
                        $total_volantears_sign_ups = $get_filed['shift']["volunteers_shift_times"];
                        $shift_meta = $get_filed["shift"];
                        $count = 0;
                        $array_of_time = array();
                        
                        if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta )){
                            $shift_start = $shift_meta['first-shift'];
                            $shift_end = $shift_meta['last-end-shift'];
                            $shift_min = $shift_meta['how-long-shift'];
                            $break_time = $shift_meta['between-shift-minutes'];                            
                            $start_time    = strtotime ($shift_start); 
                            $end_time      = strtotime ($shift_end);
                            $add_mins  = $shift_min * 60;
                            $break_min = $break_time * 60; 
                            $i = 0;                                 
                            while ($start_time <= $end_time) {                                                                                                                                              
                                $array_of_time[$i] = date ("h:i A", $start_time);
                                $start_time += ($add_mins + $break_min);
                                $count++;
                                $i++;
                            }
                            if($total_volantears == "")
                            {
                                $total_volantears = 0;
                            }
                            if($total_volantears_sign_ups == "")
                            {
                                $total_volantears_sign_ups = 0;
                            }
                            $end_val = strtotime(end($array_of_time));
                            if($end_val == $end_time){
                                if($count != 0){
                                    $count = $count - 1;
                                }
                            }
                            $total = $count * $total_volantears;
                            if(!empty($get_availability)){                            
                                if($get_availability == $total){
                                    $avachk = 1;
                                    $signupidschk[$signupid][$j] = $taskid;                                
                                }else{
                                    $task_time_array = explode(",", $task_time);
                                    $shift_meta_time = array();
                                    $all_shifts = "";
                                    $current_user_shift = array();
                                    $get_shift_time = get_post_meta( $tid, 'get_shift_time', true );
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                                    }
                                                                    
                                    if(!empty($get_shift_time)){                                    
                                        if(array_key_exists( $uid, $get_shift_time )){
                                            $current_user_shift = explode(",", $get_shift_time[$uid]);                                        
                                        }
                                        foreach($get_shift_time as $usid){
                                            $all_shifts .= $usid;
                                        }
                                        $shift_meta_time = explode(",", $all_shifts);
                                        //print_r($shift_meta_time);
                                    }
                                    $shift_count = count($array_of_time);
                                    $i = 0;
                                    $disabled_times = "";
                                    while ($i < $shift_count) {
                                        $shift_endtime = date ("h:i A", (strtotime( $array_of_time[ $i ] ) + $add_mins));                                                                                                                                               
                                        if(!empty($shift_meta_time)){
                                            if(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears == 1){
                                                if(in_array($array_of_time[$i], $task_time_array)){
                                                    $avachk = 1;
                                                    $signupidschk[$signupid][$j] = $taskid;
                                                }
                                            }   
                                            elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                            }                                                                           
                                            elseif(in_array($array_of_time[$i], $shift_meta_time) && $total_volantears > 1){
                                                $count_values = array_count_values($shift_meta_time);
                                                $this_shift_count = $count_values[$array_of_time[$i]];
                                                if($this_shift_count == $total_volantears){
                                                    if(in_array($array_of_time[$i], $task_time_array)){
                                                        $avachk = 1;
                                                        $signupidschk[$signupid][$j] = $taskid;
                                                    }
                                                }
                                                elseif(!empty($current_user_shift) && in_array($array_of_time[$i], $current_user_shift)){
                                                    if(in_array($array_of_time[$i], $task_time_array)){
                                                        $avachk = 1;
                                                        $signupidschk[$signupid][$j] = $taskid;
                                                    }
                                                }
                                                else{                                                
                                                }
                                            }                                                                               
                                            else{                                             
                                            }                                                                               
                                        }
                                        elseif(strtotime( $array_of_time[ $i ] ) == $end_time){
                                        }
                                        else{                                        
                                        }                                                                           
                                        $i++;   
                                    }
                                }
                            }
                            else{                        
                                
                            }
                        }                        
                    }
                }
            }
        }
        $error = "Following Task's availability is filled out so please remove this sign up to continue.";
      
        if(!empty($signupidschk)){
            foreach($signupidschk as $snup => $tsk){
                $error .= " Signup name: ".get_the_title($snup)." Task names: ";
                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                foreach($tsk as $tskid){
                    if(!empty($tskid)){
                        $tdate = "";
                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                          
                            $taskid_explode = explode("_", $tskid);
                            $tid = $taskid_explode[0];
                            $tdate = $taskid_explode[1];
                        }
                        else{
                            $tid = $tskid;
                        }
                        $error .= " ".get_the_title($tid)." ".$tdate.",";
                    }
                }
            }
            // $err_respose =  substr($error, 0, -1);
            esc_html_e(substr($error, 0, -1));
        }
        else{
            if(is_user_logged_in()){
                
                $signup_details = "<div>";
                $current_user_id = $uid;  
   
                    $total_signup = count($myArray["signup_id"]);    
                    
                    for($i=0; $i<$total_signup; $i++){
                        $new_myArray = array();
                        $signup_details = "<div>";
                        $signupid = $myArray["signup_id"][$i]; 
                        $signup_name = get_the_title($signupid);
                        $signup_details .= "<p><strong>Signup Name: </strong>";
                        $signup_details .= "<a href='".get_the_permalink($signupid)."' target='_blank' >".get_the_title($signupid)."</a>";
                        //$signup_details .= get_the_title($signupid);
                        $signup_details .= "</p>";
                        $new_myArray["signup_id"][0] = $signupid;
                        $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );    
                        // to add signup custom fields
                        $signup_custom_info = "";
                        
                        foreach($myArray as $akey => $avalue){                        
                            $keyarray = explode("_", $akey);
                            if(in_array($signupid, $keyarray) && in_array("signup", $keyarray)){
                                $vcount = count($avalue);
                                $signup_custom_info .= "<p><strong>";
                                $signup_custom_info .= get_the_title($keyarray[2]);
                                $signup_custom_info .= ": </strong>";
                                for($v=0; $v<$vcount; $v++){
                                    $new_myArray[$akey][$v] = $avalue[$v];
                                    if($v == 0){
                                        $signup_custom_info .= $avalue[$v];
                                    }
                                    else{
                                        $signup_custom_info .= ", ";
                                        $signup_custom_info .= $avalue[$v];
                                    }
                                } 
                                
                                $signup_custom_info .= "</p>";                           
                            }                        
                        }
                        if(!empty($signup_custom_info)){
                            $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                            $signup_details .= $signup_custom_info;
                        }
    
                        $agreekey = "agree_to_terms_signup".$signupid;
                        if(array_key_exists($agreekey, $myArray)){
                            $agreetoterms = $myArray[$agreekey][0];
                            $new_myArray[$agreekey][0] = $agreetoterms;
                        }           
                        $total_task = count($myArray["task_id".$signupid]);
                        $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
                        $all_task_ids = array();
                        
                        for($j=0; $j<$total_task; $j++){ 
                            $hours_points = array();
                            $taskid = $myArray["task_id".$signupid][$j];
                            $task_maxval = $myArray["task_max".$taskid][0]; 
                            $task_date = $myArray["task_date".$taskid][0];
                            $task_time = $myArray["task_time".$taskid][0];
                            $task_hours_points = $myArray["task_hours_points".$taskid][0];
                            $tid = "";
                            if( empty( $pto_sign_up_occurrence ) ){
                                $pto_sign_up_occurrence = array();
                            }
                            if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                $taskid_explode = explode("_", $taskid);
                                $tid = $taskid_explode[0];
                            }
                            else{
                                $tid = $taskid;
                            }
                            $signup_details .= "<p><strong>Task Name:</strong>";
                            $signup_details .= get_the_title($tid);
                            $signup_details .= "</p>";
                            if(!empty($task_date)){
                                $signup_details .= "<p><strong>Task Date:</strong>";
                                $signup_details .= $task_date;
                                $signup_details .= "</p>";
                            }
                            if(!empty($task_time)){
                                $signup_details .= "<p><strong>Task Time:</strong>";
                                $signup_details .= $task_time;
                                $signup_details .= "</p>";
                            }
                            $new_myArray["task_id".$signupid][$j] = $taskid;
                            $new_myArray["task_max".$taskid][0] = $task_maxval;
                            $new_myArray["task_date".$taskid][0] = $task_date;
                            $new_myArray["task_time".$taskid][0] = $task_time;
                            $new_myArray["task_hours_points".$taskid][0] = $task_hours_points;                        
    
                            // to store max value of task for user 
                            $max_task_signuped = array();
                            $max_key = $signupid."_".$taskid;
                            $max_task_signuped[$max_key] = $task_maxval;
                            $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                            if(!empty($get_max_user_task_signup)){
                                $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
                                update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                            }
                            else{
                                update_user_meta( $current_user_id, 'max_user_task_signup', $max_task_signuped );
                            } 
    
                             // to add task hours/points to user 
                             if(!empty($myArray["task_hours_points".$taskid][0])){
                                $task_hours_points = $myArray["task_hours_points".$taskid][0];
                                $hours_points[$taskid] = $task_hours_points;
                                $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                                if(!empty($get_user_task_hours)){
                                    $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
                                    update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                                }
                                else{
                                    update_user_meta( $current_user_id, 'user_task_hours_points', $hours_points );
                                } 
                            }
                            
                            if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
                                $taskid_explode = explode("_", $taskid);
                                $tid = $taskid_explode[0];
                                $tdate = $taskid_explode[1]; 
    
                                // to add task custom fields
                                $custom_field_info = "";
                                foreach($myArray as $akey => $avalue){                        
                                    $keyarray = explode("_", $akey);
                                    if(in_array($tid, $keyarray) && in_array($tdate, $keyarray)){
                                        $vcount = count($avalue);
                                        $custom_field_info .= "<p><strong>";
                                        $custom_field_info .= get_the_title($keyarray[2]);
                                        $custom_field_info .= ": </strong>";
                                        
                                        for($v=0; $v<$vcount; $v++){
                                            $new_myArray[$akey][$v] = $avalue[$v];
                                            if($v == 0){
                                                $custom_field_info .= $avalue[$v];
                                            }
                                            else{
                                                $custom_field_info .= ", ";
                                                $custom_field_info .= $avalue[$v];
                                            }
                                        }  
                                        $custom_field_info .= "</p>";                         
                                    }                        
                                }
                                if(!empty($custom_field_info)){
                                    $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
                                    $signup_details .= $custom_field_info;
                                }
                                // to add shift time for task and user
                                $shift_key = $current_user_id;
                                $shift_time = array();                        
                                $shift_time[$shift_key] = $task_time;
                                
                                $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                                if(!empty($get_shift_time)){
                                    $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                                    update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                                }
                                else{
                                    update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
                                } 
    
                                //to fill task availability                            
                                $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
                                if(!empty($get_filled)){
                                    $get_filled = $get_filled + $task_maxval;
                                    update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
                                }
                                else{
                                    update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
                                }
                            }
                            else{
    
                                // to add task custom fields
                                $custom_field_info = "";
                                foreach($myArray as $akey => $avalue){                        
                                    $keyarray = explode("_", $akey);
                                    if(in_array($taskid, $keyarray)){
                                        $vcount = count($avalue);
                                        $custom_field_info .= "<p><strong>";
                                        $custom_field_info .= get_the_title($keyarray[2]);
                                        $custom_field_info .= ": </strong>";
                                        
                                        for($v=0; $v<$vcount; $v++){
                                            $new_myArray[$akey][$v] = $avalue[$v];
                                            if($v == 0){
                                                $custom_field_info .= $avalue[$v];
                                            }
                                            else{
                                                $custom_field_info .= ", ";
                                                $custom_field_info .= $avalue[$v];
                                            }
                                            
                                        }  
                                        $custom_field_info .= "</p>";                          
                                    }                        
                                }
                                if(!empty($custom_field_info)){
                                    $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
                                    $signup_details .= $custom_field_info;
                                }
                                // to add shift time for task and user                         
                                $shift_key = $current_user_id;
                                $shift_time = array();                        
                                $shift_time[$shift_key] = $task_time;
                                $shift_check = get_post_meta( $taskid, "single_tasks_advance_options", true );
                                if( array_key_exists( "shift" , $shift_check ) ){
                                    $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
                                    if(!empty($get_shift_time)){
                                        
                                        $same_data = 0;
                                        
                                        foreach($get_shift_time as $key => $value){
                                            $postion_check = str_contains( trim($value) , trim($task_time) );
                                            if( !empty( $postion_check ) ){
                                                $same_data = 1;
                                            }
                                        } 
                                        
                                       
                                        if( $same_data != 1 ){
                                            if( !empty($get_shift_time[$shift_key]) ){
                                                $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                                            }else{
                                                $get_shift_time[$shift_key] = $task_time;
                                            }
                                             update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                                        }else{
                                            ?>
                                                Alredy Sign up
                                            <?php
                                            die();
                                        }
                                        
                                    }
                                    else{
                                        
                                         update_post_meta( $taskid, 'get_shift_time', $shift_time );
                                    } 
                                }
                               
                                
                                $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                                //to fill task availability 
                                if(!empty($get_filled)){
                                    $get_filled = $get_filled + $task_maxval;
                                    update_post_meta( $taskid, "signup_task_availability", $get_filled );
                                }
                                else{
                                    update_post_meta( $taskid, "signup_task_availability", $task_maxval );
                                }
                            }
                        }
                      
                        $result = $wpdb->query($wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) VALUES (NULL, ".intval($current_user_id).", ".intval($signupid).", '".esc_sql(serialize($new_myArray))."', '".esc_sql($today)."', 'on');" ));
                        if(!empty($get_manage_volunters)){
                            update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
                        }
                        else{
                            $total_array = array();
                            $total_array[$current_user_id] = $all_task_ids;                        
                            update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
                        }
                        /* get last signup details */
                        //$lastid = $wpdb->insert_id;    
                        $cur_user_obj = get_user_by('id', $current_user_id);
                        $cuname = $cur_user_obj->first_name . " " . $cur_user_obj->last_name;
                        $first_name = $cur_user_obj->first_name;
                        $last_name = $cur_user_obj->last_name;
                        if(empty($first_name)){
                            $first_name = $user_info->display_name;
                        } 
                        if(empty($last_name)){
                            $last_name = $user_info->display_name;
                        }
                        $signup_details .= "</div>";
                        // send "Receipt" to volunteer after they sign up 
                        $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                        $signuptitle = get_the_title($signupid);
                        $to = $cur_user_obj->user_email;
                        if(!empty($volunteer_after_sign_up)){
                            $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
                            if(!empty($mailcontent)){
                                $arra = array("/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                                $arra2 = array($cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
                                $mail = preg_replace($arra, $arra2, $mailcontent);                                
                                $subject = 'You have successfully done the signup.';
                                $body = $mail;                    
                                $headers = array('Content-Type: text/html; charset=UTF-8');   
                                                     
                                wp_mail( $to, $subject, $body, $headers );
                            }
                        }                        
                        // send notification to admins                                           
                       
                        $author_id = get_post_field( 'post_author', $signupid );
                        $user_info = get_userdata($author_id);            
                        $to = $user_info->user_email;
                        $admin_name = $user_info->display_name;    
                        $administrators_notifcations = get_option('administrators_notifcations');                     
                        $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                        $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
                        $mail = preg_replace($arra, $arra2, $administrators_notifcations);                        
                        $subject = 'You have got the new signup details.';
                        $body = $mail;                    
                        $headers = array('Content-Type: text/html; charset=UTF-8');        
                        wp_mail( $to, $subject, $body, $headers );                        
                        
                        $administrators_notifcations = get_option( "defult_wording_volunteers" );;                     
                        $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                        $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
                        $mail = preg_replace($arra, $arra2, $administrators_notifcations);   
                       
                        $subject = 'Sign up successfully completed.';
                        $body = $mail;                    
                        $headers = array('Content-Type: text/html; charset=UTF-8');   
                        $user_info = get_userdata(get_current_user_id());            
                        $to = $user_info->user_email;
                        // wp_mail( $to, $subject, $body, $headers );
                        wp_mail( $uemail, $subject, $body, $headers );
                        $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
                        if(!empty($notified_users)){  
                            $administrators_notifcations = get_option( "administrators_notifcations" );;     
                            foreach($notified_users as $assign_user)
                            {
                                $subject = 'You have got the new signup details.';
                                $author_obj = get_user_by('id', $assign_user);
                                $to = $author_obj->user_email;
                                $admin_name = $author_obj->display_name;
                                $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
                                $arra2 = array($admin_name, $cuname, $signup_details,  $signup_name , $first_name, $last_name);                                       
                                $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
                                $body = $mail;                            
                                $headers = array('Content-Type: text/html; charset=UTF-8');
                                wp_mail( $to, $subject, $body, $headers );
                            }
                        }                 
                    }
                    echo "Sign Up added";
                //}
            }
            else{
                $signup_details = "<div>";
                //echo "not login";
                $fname = $myArray['pto_signup_user_fname'];
                $lname = $myArray['pto_signup_user_lname'];
                $email = $myArray['pto_signup_user_email'];
                $password = wp_generate_password( 8, false );
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    ?>
                        Invalid Email ID
                    <?php 
                }
                else{
                    if (username_exists($fname) == null && email_exists($email) == false) {
    
                        // Create the new user
                        $user_id = wp_create_user($fname, $password, $email);
                        $user_data = wp_update_user( array( 'ID' => $user_id, 'first_name' => $fname, 'last_name' => $lname ) );
                        
                        // Get current user object
                        $user = get_user_by('id', $user_id);
        
                        // Remove role
                        //$user->remove_role('subscriber');
        
                        // Add role
                        //$user->add_role('own_sign_up');
                        ?>
                            User created        
                        <?php
                        /* sent pwd to user */
                        $to = $email;
                        $subject = 'Singup User Password';
                        $body = 'Hello '.$fname.'! Yor are successfully registered. Your password is: '.$password;
                        $headers = array('Content-Type: text/html; charset=UTF-8');            
                        wp_mail( $to, $subject, $body, $headers );
                        //$sql =  $wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, order_info, checkout_date, status) 
                        //        VALUES (NULL, ".$user_id.", '".serialize($myArray)."', '".$today."', 'on');" );                                   
                        //$result = $wpdb->query($sql);
                        //if($result){
                            $total_signup = count($myArray["signup_id"]);            
                            for($i=0; $i<$total_signup; $i++){
                                $new_myArray = array();
                                $signup_details = "<div>";
                                $signupid = $myArray["signup_id"][$i]; 
                                $signup_name = get_the_title($signupid);
                                $signup_details .= "<p><strong>Signup Name: </strong>";
                                $signup_details .= "<a href='".get_the_permalink($signupid)."' target='_blank' >".get_the_title($signupid)."</a>";
                                //$signup_details .= get_the_title($signupid);
                                $signup_details .= "</p>";
                                $new_myArray["signup_id"][0] = $signupid;
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                // to add signup custom fields
                                $signup_custom_info = "";
                                foreach($myArray as $akey => $avalue){                        
                                    $keyarray = explode("_", $akey);
                                    if(in_array($signupid, $keyarray) && in_array("signup", $keyarray)){
                                        $vcount = count($avalue);
                                        $signup_custom_info .= "<p><strong>";
                                        $signup_custom_info .= get_the_title($keyarray[2]);
                                        $signup_custom_info .= ": </strong>";
                                        for($v=0; $v<$vcount; $v++){
                                            $new_myArray[$akey][$v] = $avalue[$v];
                                            if($v == 0){
                                                $signup_custom_info .= $avalue[$v];
                                            }
                                            else{
                                                $signup_custom_info .= ", ";
                                                $signup_custom_info .= $avalue[$v];
                                            }
        
                                        } 
                                        
                                        $signup_custom_info .= "</p>";                        
                                    }                        
                                }
                                if(!empty($signup_custom_info)){
                                    $signup_details .= "<p><strong>Checkout Fields Info</strong></p>";
                                    $signup_details .= $signup_custom_info;
                                }    
                                $agreekey = "agree_to_terms_signup".$signupid;
                                if(array_key_exists($agreekey, $myArray)){
                                    $agreetoterms = $myArray[$agreekey][0];
                                    $new_myArray[$agreekey][0] = $agreetoterms;
                                }              
                                $total_task = count($myArray["task_id".$signupid]);
                                $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
                                $all_task_ids = array();
                                for($j=0; $j<$total_task; $j++){ 
                                    $hours_points = array();
                                    $taskid = $myArray["task_id".$signupid][$j];
                                    $task_maxval = $myArray["task_max".$taskid][0];                                 
                                    $task_date = $myArray["task_date".$taskid][0];
                                    $task_time = $myArray["task_time".$taskid][0];
                                    $task_hours_points = $myArray["task_hours_points".$taskid][0];
                                    $tid = "";
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                    }
                                    else{
                                        $tid = $taskid;
                                    }
                                    $signup_details .= "<p><strong>Task Name:</strong>";
                                    $signup_details .= get_the_title($tid);
                                    $signup_details .= "</p>";
                                    if(!empty($task_date)){
                                        $signup_details .= "<p><strong>Task Date:</strong>";
                                        $signup_details .= $task_date;
                                        $signup_details .= "</p>";
                                    }
                                    if(!empty($task_time)){
                                        $signup_details .= "<p><strong>Task Time:</strong>";
                                        $signup_details .= $task_time;
                                        $signup_details .= "</p>";
                                    }
                                    $new_myArray["task_id".$signupid][$j] = $taskid;
                                    $new_myArray["task_max".$taskid][0] = $task_maxval;
                                    $new_myArray["task_date".$taskid][0] = $task_date;
                                    $new_myArray["task_time".$taskid][0] = $task_time;
                                    $new_myArray["task_hours_points".$taskid][0] = $task_hours_points;
    
                                    //to store max value of task for user 
                                    $max_task_signuped = array();
                                    $max_key = $signupid."_".$taskid;
                                    $max_task_signuped[$max_key] = $task_maxval;
                                    $get_max_user_task_signup = get_user_meta( $user_id, 'max_user_task_signup', true );
                                    if(!empty($get_max_user_task_signup)){
                                        $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
                                        update_user_meta( $user_id, 'max_user_task_signup', $get_max_user_task_signup );
                                    }
                                    else{
                                        update_user_meta( $user_id, 'max_user_task_signup', $max_task_signuped );
                                    }
                                    
                                    //to add task hours/points to user 
                                    if(!empty($myArray["task_hours_points".$taskid][0])){
                                        $task_hours_points = $myArray["task_hours_points".$taskid][0];
                                        $hours_points[$taskid] = $task_hours_points;
                                        $get_user_task_hours = get_user_meta( $user_id, 'user_task_hours_points', true );
                                        if(!empty($get_user_task_hours)){
                                            $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
                                            update_user_meta( $user_id, 'user_task_hours_points', $get_user_task_hours );
                                        }
                                        else{
                                            update_user_meta( $user_id, 'user_task_hours_points', $hours_points );
                                        } 
                                    }
    
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                            
                                        $taskid_explode = explode("_", $taskid);
                                        $tid = $taskid_explode[0];
                                        $tdate = $taskid_explode[1]; 
    
                                        // to add task custom fields
                                        $custom_field_info = "";
                                        foreach($myArray as $akey => $avalue){                        
                                            $keyarray = explode("_", $akey);
                                            if(in_array($tid, $keyarray) && in_array($tdate, $keyarray)){
                                                $vcount = count($avalue);
                                                $custom_field_info .= "<p><strong>";
                                                $custom_field_info .= get_the_title($keyarray[2]);
                                                $custom_field_info .= ": </strong>";
                                                
                                                for($v=0; $v<$vcount; $v++){
                                                    $new_myArray[$akey][$v] = $avalue[$v];
                                                    if($v == 0){
                                                        $custom_field_info .= $avalue[$v];
                                                    }
                                                    else{
                                                        $custom_field_info .= ", ";
                                                        $custom_field_info .= $avalue[$v];
                                                    }
                                                }  
                                                $custom_field_info .= "</p>";                         
                                            }                        
                                        }
                                        if(!empty($custom_field_info)){
                                            $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
                                            $signup_details .= $custom_field_info;
                                        }
                                        // to add shift time for task and user
                                        $shift_key = $user_id;
                                        $shift_time = array();                        
                                        $shift_time[$shift_key] = $task_time;
                                        
                                        $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                                        if(!empty($get_shift_time)){
                                            $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                                            update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                                        }
                                        else{
                                            update_post_meta( $tid, 'get_shift_time'.$taskid, $shift_time );
                                        } 
    
                                        //to fill task availability                            
                                        $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );
                                        if(!empty($get_filled)){
                                            $get_filled = $get_filled + $task_maxval;
                                            update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
                                        }
                                        else{
                                            update_post_meta( $tid, "signup_task_availability".$taskid, $task_maxval );
                                        }
                                    }
                                    else{
                                        // to add task custom fields
                                        $custom_field_info = "";
                                        foreach($myArray as $akey => $avalue){                        
                                            $keyarray = explode("_", $akey);
                                            if(in_array($taskid, $keyarray)){
                                                $vcount = count($avalue);
                                                $custom_field_info .= "<p><strong>";
                                                $custom_field_info .= get_the_title($keyarray[2]);
                                                $custom_field_info .= ": </strong>";
                                                for($v=0; $v<$vcount; $v++){
                                                    $new_myArray[$akey][$v] = $avalue[$v];
                                                    if($v == 0){
                                                        $custom_field_info .= $avalue[$v];
                                                    }
                                                    else{
                                                        $custom_field_info .= ", ";
                                                        $custom_field_info .= $avalue[$v];
                                                    }
                                                } 
                                                $custom_field_info .= "</p>";             
                                            }                        
                                        }
                                        if(!empty($custom_field_info)){
                                            $signup_details .= "<p><strong>Custom Fields Info</strong></p>";
                                            $signup_details .= $custom_field_info;
                                        }
                                        //to add shift time for task and user                       
                                        $shift_key = $user_id;
                                        $shift_time = array();                        
                                        $shift_time[$shift_key] = $task_time;                                
                                        $get_shift_time = get_post_meta( $taskid, 'get_shift_time', true );
                                        if(!empty($get_shift_time)){
                                            $get_shift_time[$shift_key] = $get_shift_time[$shift_key] .','. $task_time;
                                            update_post_meta( $taskid, 'get_shift_time', $get_shift_time );
                                        }
                                        else{
                                            update_post_meta( $taskid, 'get_shift_time', $shift_time );
                                        } 
    
                                        //to fill task availability 
                                        $get_filled = get_post_meta( $taskid, "signup_task_availability", true );
                                        // $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
                                        if(!empty($get_filled)){
                                            $get_filled = $get_filled + $task_maxval;
                                            update_post_meta( $taskid, "signup_task_availability", $get_filled );
                                        }
                                        else{
                                            update_post_meta( $taskid, "signup_task_availability", $task_maxval );
                                        }
                                    }
    
                                    //to add user to manage volunteers
                                    /*if(!empty($get_manage_volunters)){
                                        $get_manage_volunters[$user_id][$taskid] = $taskid;
                                    }
                                    else{
                                        $all_task_ids[$taskid] = $taskid;
                                    }
            
                                    $old_array = get_post_meta($taskid, "pto_sign_up_selected_date_time", true);
                                    if(!empty($old_array))
                                    {
                                        $arr = array();
                                        $arr[$task_date] = array(
                                            $task_date => $task_date,
                                            $task_time => $task_time
                                        );
                                        $old_data = $old_array[$user_id];
            
                                        if(empty($old_data))
                                        {  
                                            $old_array[$user_id] = $arr;                        
                                            update_post_meta($taskid, "pto_sign_up_selected_date_time", $old_array); 
                                        }else{
                                            
                                            $new_arr = array_merge($old_data, $arr);
                                            $n_arr[$user_id] = $new_arr;
                                            update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
                                        }
                                    }else{
                                        $arr = array();
                                        $arr[$task_date] = array(
                                            $task_date => $task_date,
                                            $task_time => $task_time
                                        );                            
                                        $n_arr[$user_id] = $arr;                                            
                                        update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
                                    }*/
                                }
    
                                //$sql =  $wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) 
                                 //       VALUES (NULL, ".$user_id.", ".$signupid.", '".serialize($new_myArray)."', '".$today."', 'on');" );                                    
                                $result = $wpdb->query($wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) VALUES (NULL, ".intval($user_id).", ".intval($signupid).", '".esc_sql(serialize($new_myArray))."', '".esc_sql($today)."', 'on');" ));
                                /*if(!empty($get_manage_volunters)){
                                    update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
                                }
                                else{
                                    $total_array = array();
                                    $total_array[$user_id] = $all_task_ids;                        
                                    update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
                                }*/
                                
                                $cur_user_obj = get_user_by('id', $user_id);
                                $cuname = $cur_user_obj->first_name . " " . $cur_user_obj->last_name;
                                $first_name = $cur_user_obj->first_name;
                                $last_name = $cur_user_obj->last_name;
                                if(empty($first_name)){
                                    $first_name = $user_info->display_name;
                                } 
                                if(empty($last_name)){
                                    $last_name = $user_info->display_name;
                                }
                                $signup_details .= "</div>";
        
                                // send "Receipt" to volunteer after they sign up 
        
                                $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
        
                                $signuptitle = get_the_title($signupid);
        
                                $to = $cur_user_obj->user_email;
        
                                if(!empty($volunteer_after_sign_up)){
        
                                    $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
        
                                    if(!empty($mailcontent)){
        
                                        $arra = array("/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
        
                                        $arra2 = array($cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
        
                                        $mail = preg_replace($arra, $arra2, $mailcontent);                                
        
                                        $subject = 'You have successfully done the signup.';
        
                                        $body = $mail;                    
        
                                        $headers = array('Content-Type: text/html; charset=UTF-8');                    
        
                                        wp_mail( $to, $subject, $body, $headers );
        
                                    }
        
                                }                        
        
                                // send notification to admins                                           
                                
                                $author_id = get_post_field( 'post_author', $signupid );
        
                                $user_info = get_userdata($author_id);            
        
                                $to = $user_info->user_email;
        
                                $admin_name = $user_info->display_name;    
        
                                $administrators_notifcations = get_option('administrators_notifcations');                     
        
                                $arra = array("/{{Admin Name}}/", "/{{Full Name}}/", "/{{Signup Details}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
        
                                $arra2 = array($admin_name, $cuname, $signup_details, $signup_name, $first_name, $last_name);                                      
        
                                $mail = preg_replace($arra, $arra2, $administrators_notifcations);

                                // echo $administrators_notifcations;                        
        
                                $subject = 'You have got the new signup details.';
        
                                $body = $mail;                    
        
                                $headers = array('Content-Type: text/html; charset=UTF-8');                    
        
                                wp_mail( $to, $subject, $body, $headers );                        
        
                                $notified_users = get_post_meta($signupid, "pto_signup_notified_users", true);
        
                                if(!empty($notified_users)){  
        
                                    foreach($notified_users as $assign_user)
                                    {
        
                                        $author_obj = get_user_by('id', $assign_user);
        
                                        $to = $author_obj->user_email;
        
                                        $admin_name = $author_obj->display_name;
        
                                        $arra2 = array($admin_name, $cuname, $signup_details, $first_name, $last_name);                                       
        
                                        $mail = preg_replace($arra, $arra2, $administrators_notifcations);                                                      
        
                                        $body = $mail;                            
        
                                        $headers = array('Content-Type: text/html; charset=UTF-8');                            
        
                                        wp_mail( $to, $subject, $body, $headers );
        
                                    }
        
                                }                 
                            }
                            ?>
                            Sign Up added
                            <?php                      
                            $loginuser = get_user_by('email', $email );        
                            // Redirect URL //
                            if ( !is_wp_error( $loginuser ) )
                            {
                                wp_clear_auth_cookie();
                                wp_set_current_user ( $loginuser->ID );
                                wp_set_auth_cookie ( $loginuser->ID );
                            }
                        //}                
                    }  
                    else{
                        ?>
                            User already exist
                        <?php
                    }
                }             
            }
        }         
        die();
    }
}