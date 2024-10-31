<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtOTaskSlots {
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
    function init() {
        /* Task slots management cpt */
        add_action( 'init', array( $this, 'pto_sign_up_task_manage_post_type' ) );
        /* Task slots management texonomy */
     
        /* task sloats in all meta boxes */
        add_action( 'add_meta_boxes', array( $this, 'pto_sign_up_taskslots_meta_boxes' ) );
        /* popups design for admin */
        add_action( 'admin_footer', array( $this, 'pto_sign_up_my_admin_footer_function_popups' ) ); 
        /* get all user from meeting in attendee */
     
        /* get all custom fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_custom_fieldss', array( $this, 'pto_sign_up_custom_fieldss' ) );
        add_action( 'wp_ajax_pto_signup_custom_fieldss', array( $this, 'pto_sign_up_custom_fieldss' ) );
        /* get all custom fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_get_custom_fields', array( $this, 'pto_sign_up_get_custom_fields' ) );
        add_action( 'wp_ajax_pto_signup_get_custom_fields', array( $this, 'pto_sign_up_get_custom_fields' ) );
    
        /* custom post save from task slots */
        add_action( 'save_post_tasks-signup', array( $this, 'pto_sign_up_tasks_post_save' ), 20, 2 );        
        /* delete  custom fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_task_custom_fields_delete', array( $this, 'pto_sign_up_task_custom_fields_delete' ) );
        add_action( 'wp_ajax_pto_signup_task_custom_fields_delete', array( $this, 'pto_sign_up_task_custom_fields_delete' ) );
        /* post id in get user id and date time removce */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_remove_user_id_to_post', array( $this, 'pto_sing_up_remove_user_id_to_post' ) );
        add_action( 'wp_ajax_pto_sing_up_remove_user_id_to_post', array( $this, 'pto_sing_up_remove_user_id_to_post' ) );
        /* manage volunteer bulk delete */ 
        add_action( 'wp_ajax_nopriv_pto_manage_volunteer_bulk_delete', array( $this, 'pto_sign_up_manage_volunteer_bulk_delete' ) );
        add_action( 'wp_ajax_pto_manage_volunteer_bulk_delete', array( $this, 'pto_sign_up_manage_volunteer_bulk_delete' ) );
        add_action( 'wp_ajax_nopriv_pto_signup_rec_edit_single', array( $this, 'pto_sign_up_rec_edit_single' ) );
        add_action( 'wp_ajax_pto_signup_rec_edit_single', array( $this, 'pto_sign_up_rec_edit_single' ) );
        add_action( 'wp_ajax_nopriv_pto_signup_load_edit_single_value', array( $this, 'pto_sign_up_load_edit_single_value' ) );
        add_action( 'wp_ajax_pto_signup_load_edit_single_value', array( $this, 'pto_sign_up_load_edit_single_value' ) );
    }
    /**
    * Sign up task CPT
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_manage_post_type() {
        $my_theme = get_option('stylesheet');
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Tasks-Signup', 'Post Type General Name') ,
            'singular_name' => _x('Task', 'Post Type Singular Name') ,
            'menu_name' => __('Tasks-Signup', $my_theme) ,
            'parent_item_colon' => __('Parent task', $my_theme) ,
            'all_items' => __('All Tasks-Signup', $my_theme) ,
            'view_item' => __('View Tasks-Signup', $my_theme) ,
            'add_new_item' => __('Add New task', $my_theme) ,
            'add_new' => __('Add New', $my_theme) ,
            'edit_item' => __('Edit task', $my_theme) ,
            'update_item' => __('Update task', $my_theme) ,
            'search_items' => __('Search task', $my_theme) ,
            'not_found' => __('Not Found', $my_theme) ,
            'not_found_in_trash' => __('Not found in Trash', $my_theme) ,
        );
        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Tasks-Signup', $my_theme) ,
            'description' => __('task news and reviews', $my_theme) ,
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title'                
            ) ,
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies' => array(
                'genres'
            ) ,
            /* A hierarchical CPT is like Pages and can have
             * Parent and child items. A non-hierarchical CPT
             * is like Posts.
            */
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'has_archive' => true,
        );
        // Registering your Custom Post Type
        register_post_type( 'Tasks-Signup', $args );
    }
   
    /**
    * Task/slot meta boxes
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_taskslots_meta_boxes() {
        
        add_meta_box(
            'tasks-description-signups',
            __( 'Add a compelling description', 'sitepoint' ),
           array( $this, 'pto_sign_up_desc' ) , // $callback
            'tasks-signup'
        );
        add_meta_box(
            'tasks-advanced-option-signups',
            __( 'Task/Slot Options', 'sitepoint' ),
           array( $this, 'pto_sign_up_advanced_option' ) , // $callback
            'tasks-signup'
        );
        
    }
    /**
    * Task/slot description
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_desc() {
        global $post;
        
        $content = get_post_meta( $post->ID, "tasks_comp_desc", true );
        $saved_dates = "";
        if( isset( $_GET["rdate"] ) ) {
            $hidden_date = sanitize_text_field($_GET["rdate"]);
            $saved_dates = get_post_meta( $post->ID, "pto_signup_task_edit_single".$hidden_date, true );
        }
        
        if ( !empty( $saved_dates ) ) {
            $content = $saved_dates["tasks_comp_desc"];
            wp_editor( $content, 'tasks_comp_desc', $settings = array(
                'textarea_name' => 'tasks_comp_desc',
                'textarea_rows' => 5
    
            )); 
        }
        else {
            wp_editor( $content, 'tasks_comp_desc', $settings = array(
                'textarea_name' => 'tasks_comp_desc',
                'textarea_rows' => 5
    
            ) ); 
        }                 
    }
    /**
    * Popups in footer
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_my_admin_footer_function_popups() {
        global $post;
        if ( $post != "" ) {
            $post_ids = $post->ID;
            include "adminfooter/pto_admin-footer_tasks.php";
        }        
    }
    /**
    * Sign up advanced options
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_advanced_option() {
        global $post;
        $post_ids = $post->ID;
        include "pto_task_slots_advanced_option/pto_task_slots_advanced_option.php";    
        wp_reset_postdata();
    }
    /**
    * Task/slot advanced options
    * @since    1.0.0
    * @access   public
    **/
    
    /**
    * Delete task/slot custom field
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_custom_fields_delete() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $custom_fid = "";
            $current_post_id = "";
            if ( isset( $_POST['cpt_ids'] ) ) {
                $custom_fid = intval( $_POST['cpt_ids'] );
            }
            if ( isset( $_POST['post_id'] ) ) {
                $current_post_id = intval( $_POST['post_id'] );
            }                
            $cpt_custom_fileds =  get_post_meta( $current_post_id, "single_task_custom_fields", true );
            if ( ( $key = array_search( $custom_fid, $cpt_custom_fileds ) ) !== false ) {               
                unset( $cpt_custom_fileds[ $key ] );
            }        
            update_post_meta( $current_post_id, "single_task_custom_fields", $cpt_custom_fileds );        
            include "pto_task_slots_advanced_option/pto_custom_fileds.php";
            die();
        }
    }
    /**
    * Edit single recurrence task
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_rec_edit_single() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $post_id = "";
            $title = "";
            $rdate = "";
            $cat = "";
            $content = "";
            if ( isset( $_POST['task_id'] ) ) {
                $post_id = intval( $_POST['task_id'] );
            }
            if ( isset( $_POST['title'] ) ) {
                $title = sanitize_text_field( $_POST['title'] );
            }
            if ( isset( $_POST['rdate'] ) ) {
                $rdate = sanitize_text_field( $_POST['rdate'] );
            }
            if ( isset( $_POST['cat'] ) ) {
                $cat = sanitize_text_field( $_POST['cat'] );
            }
            if ( isset( $_POST['content'] ) ) {
                $content = wp_kses_post( $_POST['content'] );
            }
            if ( !empty( $rdate ) && !empty( $post_id ) ) {
                $edit_single_dates = array();            
                $edit_single_dates["post_title"] = $title;
                $edit_single_dates["tasks_comp_desc"] = $content;
                $edit_single_dates["post_cat"] = $cat;
                update_post_meta( $post_id, "pto_signup_task_edit_single".$rdate, $edit_single_dates );
                esc_html_e("post updated");
            }
        }
        die();
    }
    /**
    * Load edit single value
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_load_edit_single_value() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $post_id = "";           
            $rdate = "";           
            $title = "";
            $cat = "";
            if ( isset( $_POST['task_id'] ) ) {
                $post_id = intval( $_POST['task_id'] );
            }
            
            if( isset( $_POST['rdate'] ) ) {
                $rdate = sanitize_text_field( $_POST['rdate'] );
            }
            
            if ( !empty( $rdate ) && !empty( $post_id ) ) {
                $saved_dates = get_post_meta( $post_id, "pto_signup_task_edit_single".$rdate, true );
                if ( !empty( $saved_dates ) ) {
                    $title = $saved_dates["post_title"];
                    $cat = $saved_dates["post_cat"];
                }
                else {
                    $title = get_the_title( $post_id );
                    $cat_id = "";
                    $category_detail = get_the_terms( $post_id, 'TaskCategories' );
                    if ( !empty( $category_detail ) ) {				
                        foreach ( $category_detail as $category_details ) {
                            $cat_id = $category_details->ID;
                        }
                    }
                    $cat = $cat_id;
                }
            }
        }
        $data['post_title'] =  $title;
        $data['post_cat'] =  $cat;
        echo json_encode( $data );
        die();
    }
   
    /**
    * Store sign up custom fields
    * @since    1.0.0
    * @access   public
    **/
     public function pto_sign_up_custom_fieldss() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $ids = intval( $_POST['ids'] );
            $cpt_data = explode( ",", $ids );
            $post_id = intval( $_POST['post_id'] );
            update_post_meta( $post_id, "single_task_custom_fields", $cpt_data );
            include "pto_task_slots_advanced_option/pto_custom_fileds.php";
        }
        die();
    }
    /**
    * Get custom fields
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_custom_fields() {
        $post_id = "";
        if( isset( $_POST['post_id'] ) ) {
            $post_id = intval( $_POST['post_id'] );
        }
        include "pto_task_slots_advanced_option/pto_custom_fileds.php";  
        if( isset( $_POST['post_id'] ) ) {
            die();
        }
    }
    /**
    * Task/slot save post
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_tasks_post_save( $post_id, $post ) {
        if ( $_POST ) {
            if ( isset( $_POST['pto_sign_ups_task_rec_date'] ) ) {
                $hidden_date = sanitize_text_field( $_POST['pto_sign_ups_task_rec_date'] );
                $post_title = sanitize_text_field( $_POST['post_title'] );
                $tasks_comp_desc = sanitize_text_field( $_POST['tasks_comp_desc'] );
                $edit_single_dates = array();                                
                $edit_single_dates["post_title"] = $post_title;
                $edit_single_dates["tasks_comp_desc"] = $tasks_comp_desc;
                update_post_meta( $post_id, "pto_signup_task_edit_single".$hidden_date, $edit_single_dates );      
                
            }
            else {
                if ( isset( $_POST['advanced_option'] ) ) {                    
                    $advanced_option  =  sanitize_text_field( $_POST['advanced_option'] );
                    if ( $advanced_option == "single" ) { 
                        $single_arr = array();
                        if ( isset( $_POST['how_money_volunteers'] ) ) {
                            $single_arr['single']["how_money_volunteers"] = sanitize_text_field($_POST['how_money_volunteers']);
                        }
                        if ( isset( $_POST['how_money_volunteers_sign_ups'] ) ) {
                            $single_arr['single']["how_money_volunteers_sign_ups"] = sanitize_text_field($_POST['how_money_volunteers_sign_ups']);
                        }
                        if ( isset( $_POST['how_money_volunteers_sign_ups-times'] ) ) {
                            $single_arr['single']["how_money_volunteers_sign_ups-times"] = sanitize_text_field($_POST['how_money_volunteers_sign_ups-times']);
                        }
                        update_post_meta( $post_id, "single_tasks_advance_options", $single_arr );                    
                    } 
                }
               
                if ( isset( $_POST['all_sign_up_modual'] ) ) {
                    update_post_meta( $post_id, "all_sign_up_modual", sanitize_text_field($_POST['all_sign_up_modual']) );
                } else {
                    update_post_meta( $post_id, "all_sign_up_modual", "" );
                }
                if ( isset( $_POST['tasks_comp_desc'] ) ) {
                    update_post_meta( $post_id, "tasks_comp_desc", sanitize_text_field($_POST['tasks_comp_desc']) );
                }
            }
            
             wp_enqueue_script("jquery-ui-core");              
            ?>            
            <script type="text/javascript">
                opener.pto_task_cpt_call( "pto_sign_up_compelling_task_section_list", "<?php echo intval( $post_id ); ?>");
                window.close();
            </script>
            <?php
            die();
        }
    }
    /**
    * Manage volunteer bluk delete
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_manage_volunteer_bulk_delete() {   
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        } 
        global $wpdb;
        $table_name = $wpdb->prefix . "signup_orders";     
        $deletedr = 0; 
     
            if ( isset( $_POST ) ) {
                $orderids = explode( ",", sanitize_text_field($_POST['orderids']) );
                $userids = explode( ",", sanitize_text_field($_POST['userids'] ));
                $postids = explode( ",", sanitize_text_field($_POST['taskids'] ));
                $cnumbs = explode( ",", sanitize_text_field($_POST['cnumbs'] ));
                $shiftt = explode( ",", sanitize_text_field($_POST['shiftt'] ));
               
                $signupid = 0;
                $total_order = count( $orderids );
                for ( $o=0; $o<$total_order; $o++ ) {
                    $get_user_signup_data = "";
                    $old_user_customf_data = array();
                    $orderid = $orderids[ $o ];
                    $current_user_id = $userids[ $o ];
                    $taskid = $postids[ $o ];
                    $cnum = $cnumbs[ $o ];
                    $shtime = $shiftt[ $o ];
                    if($orderid != "" && $current_user_id != "" && $taskid != "" && $cnum != ""){
                        $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE ID = ".intval($orderid) );
                        if(!empty($all_user_posts)){
                            
                            foreach($all_user_posts as $userkey => $post){
                                $get_user_signup_data = unserialize($post->order_info);
                                $signupid = $post->signup_id;
                            }
                            if($signupid != 0){
                                
                                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                $tid = "";
                                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                            
                                    
                                    $taskid_explode = explode("_", $taskid);
                                    $tid = $taskid_explode[0];
                                    $tdate = $taskid_explode[1];
                                }
                                else{
                                    $tid = $taskid;
                                }
                                
                                // to store max value of task for user
                                //$signupid = $main_post_id;
                                $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
                                $max_key = $signupid."_".$taskid;
                                $maxval = $get_max_user_task_signup[$max_key];
                                if(!empty($get_max_user_task_signup)){
                                    
                                    //$get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $maxval;
                                    $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - 1;
                                    update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
                                } 
                                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                    
                                    //to fill task availability 
                                    $get_filled = get_post_meta( $tid, 'signup_task_availability'.$taskid, true );
                                    
                                    if(!empty($get_filled)){
                                        
                                        $get_filled = $get_filled - 1;
                                        update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
                                    } 
                                }
                                else{
                                    //to fill task availability 
                                    $get_filled = get_post_meta( $tid, "signup_task_availability", true );
                                    if(!empty($get_filled)){
                                        
                                        $get_filled = $get_filled - 1;
                                        update_post_meta( $tid, "signup_task_availability", $get_filled );
                                    } 
                                }                         
                                // to add task hours/points to user   
                                
                                $task_hours_points = get_post_meta( $tid, "pto_sign_ups_hour_points", true );                              
                                if(!empty($task_hours_points)){                 
                                    
                                    $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
                                    if(!empty($get_user_task_hours)){
                                        
                                        $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
                                        update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
                                    }
                                }
                                
                                // to remove shift time for task and user 
                                $single_post_meta = get_post_meta( $tid, "single_tasks_advance_options", true );  
                                if(!empty($single_post_meta)){ 
                                    
                                    if(array_key_exists("shift", $single_post_meta)) {
                                        $oldshifts = $get_user_signup_data["task_time".$taskid][0];
                                        $oldshiftarray = explode(",", $oldshifts);
                                        $stime = $shtime;
                                        //echo "oldshifts: ".$oldshifts;
                                        //$stime = $oldshiftarray[$cnum];
                                        //echo " stime: ".$stime;
                                        if (($key = array_search($stime, $oldshiftarray)) !== false) {
                                            unset($oldshiftarray[$key]);                                            
                                            $get_user_signup_data["task_time".$taskid][0] = implode(",", $oldshiftarray);
                                        }
                                        $shift_key = $current_user_id;
                                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                            $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
                                            if(!empty($get_shift_time)){
                                                $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                                if(!empty($stime)){
                                                    if (($key = array_search($stime, $shifttimes)) !== false) {
                                                        unset($shifttimes[$key]);
                                                    }                                            
                                                }                                                             
                                                $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                                update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
                                            }
                                        }
                                        else{
                                            $get_shift_time = get_post_meta( $tid, 'get_shift_time', true );
                                            if(!empty($get_shift_time)){
                                                $shifttimes = explode(",", $get_shift_time[$shift_key]);
                                                if(!empty($stime)){
                                                    if (($key = array_search($stime, $shifttimes)) !== false) {
                                                        unset($shifttimes[$key]);
                                                    }                                            
                                                }                                                             
                                                $get_shift_time[$shift_key] = implode(",", $shifttimes);
                                                update_post_meta( $tid, 'get_shift_time', $get_shift_time );
                                            }
                                        } 
                                    }
                                }                     
                                
                                $taskoldmax = $get_user_signup_data["task_max".$taskid][0];
                                $get_user_signup_data["task_max".$taskid][0] = $get_user_signup_data["task_max".$taskid][0] - 1;
                                if(!empty($task_hours_points)){
                                    $get_user_signup_data["task_hours_points".$taskid][0] = $get_user_signup_data["task_hours_points".$taskid][0] - $task_hours_points; 
                                }
                                $taskmaxval = $get_user_signup_data["task_max".$taskid][0];
                                
                                if($taskmaxval == 0){
                                    
                                    $taskids = $get_user_signup_data["task_id".$signupid];
                                    if (($key = array_search($taskid, $taskids)) !== false) {
                                        unset($taskids[$key]);
                                        $taskids = array_values($taskids);
                                        $get_user_signup_data["task_id".$signupid] = $taskids;
                                        $maxkey = "task_max".$taskid;
                                        unset($get_user_signup_data[$maxkey]);
                                        $hourskey = "task_hours_points".$taskid;
                                        unset($get_user_signup_data[$hourskey]);
                                        $datekey = "task_date".$taskid;
                                        unset($get_user_signup_data[$datekey]);
                                        $timekey = "task_time".$taskid;
                                        unset($get_user_signup_data[$timekey]);        
                                    } 
                                }   
                                //custom fields removal
                                
                                $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                                if(!empty($cpt_custom_fileds)){ 
                                    
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
                                        //for($c = 0; $c < $task_max_val; $c++){
                                            $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$cnum;
                                            //echo $customfieldkey;
                                            if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
                                                unset($get_user_signup_data[$customfieldkey]);
                                            }
                                        //}
                                    }
                                    $new_user_customf_data = array();
                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                        foreach($get_user_signup_data as $akey => $avalue){                        
                                            $keyarray = explode("_", $akey);
                                            if(in_array($tid, $keyarray) && in_array("custom", $keyarray) && in_array($tdate, $keyarray)){
                                                $vcount = count($avalue);
                                                for($v=0; $v<$vcount; $v++){
                                                    $new_user_customf_data[$akey][$v] = $avalue[$v];
                                                }                            
                                            }                        
                                        }
                    
                                    }
                                    else{
                                        foreach($get_user_signup_data as $akey => $avalue){                        
                                            $keyarray = explode("_", $akey);
                                            if(in_array($tid, $keyarray) && in_array("custom", $keyarray)){
                                                $vcount = count($avalue);
                                                for($v=0; $v<$vcount; $v++){
                                                    $new_user_customf_data[$akey][$v] = $avalue[$v];
                                                }                            
                                            }                        
                                        }
                    
                                    }
                                    foreach($new_user_customf_data as $akey => $avalue){ 
                                        if(array_key_exists($akey, $get_user_signup_data)){
                                            unset($get_user_signup_data[$akey]);
                                        }               
                                    }
                                    $new_final_array = array();
                                    $d = 0;
                                    $oldkey = "";
                                    $lastkey = "";
                                    $oldplusone = "";
                                    $savefisrtlastkey = "";
                                    foreach($new_user_customf_data as $akey => $avalue){
                                        $keyarray = explode("_", $akey);
                                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                            $lastkey = $keyarray[6];
                                        }
                                        else{
                                            $lastkey = $keyarray[5];
                                        }
                                        if($d == 0){
                                            $savefisrtlastkey = $lastkey;
                                        }
                                        $d++;
                                        //echo "oldkey: ".$oldkey ;
                                        if($savefisrtlastkey != "0"){ 
                                            if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                                $keyarray[6] = $keyarray[6] - 1;
                                            }
                                            else{
                                                $keyarray[5] = $keyarray[5] - 1;
                                            }
                                            
                                            $newkey = implode("_", $keyarray);
                                            $vcount = count($avalue);
                                            for($v=0; $v<$vcount; $v++){
                                                $new_final_array[$newkey][$v] = $avalue[$v];
                                            } 
                                        }
                                        else{
                                            if(!empty($oldkey) || $oldkey == "0"){
                                                $oldplusone = $oldkey + 1;
                                                if($oldkey == $lastkey || $oldplusone == $lastkey){
                                                    $vcount = count($avalue);
                                                    for($v=0; $v<$vcount; $v++){
                                                        $new_final_array[$akey][$v] = $avalue[$v];
                                                    } 
                                                    $oldkey = $lastkey;
                                                }
                                                else{
                                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                                                        $keyarray[6] = $keyarray[6] - 1;
                                                    }
                                                    else{
                                                        $keyarray[5] = $keyarray[5] - 1;
                                                    }
                                                    
                                                    $newkey = implode("_", $keyarray);
                                                    $vcount = count($avalue);
                                                    for($v=0; $v<$vcount; $v++){
                                                        $new_final_array[$newkey][$v] = $avalue[$v];
                                                    } 
                                                }
                                            }
                                            else{
                                                //echo "EMPTY";
                                                $vcount = count($avalue);
                                                for($v=0; $v<$vcount; $v++){
                                                    $new_final_array[$akey][$v] = $avalue[$v];
                                                } 
                                                $oldkey = $lastkey;
                                            }   
                                        }
                                    }
                                    //print_r($new_final_array);
                                    foreach($new_final_array as $akey => $avalue){ 
                                        if(!array_key_exists($akey, $get_user_signup_data)){
                                            //unset($get_user_signup_data[$akey]);
                                            $get_user_signup_data[$akey] = $avalue;
                                        }               
                                    }
                                }
                                //print_r($get_user_signup_data);
                                
                                $taskids = $get_user_signup_data["task_id".$signupid];
                                if(empty($taskids)){
                                    //echo "delete record";
                                    //$sql =  $wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".$orderid ); 
                                    $result = $wpdb->query($wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".intval($orderid) ));
                                    if($result){
                                        $deletedr++;
                                        //echo "deleted successfully";
                                    }
                                    else{
                                        //echo "error";
                                    }
                                }
                                else{
                                    //echo "update record";
                                    //$sql =  $wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".serialize($get_user_signup_data)."' WHERE ID = ".$orderid );            
                                    $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".esc_sql(serialize($get_user_signup_data))."' WHERE ID = ".intval($orderid) ));
                                    if($result){
                                        $deletedr++;
                                        //echo "deleted successfully";
                                    }
                                    else{
                                        //echo "error";
                                    }
                                }
                            }
                        }
                    }
                }
            }
        //}
        esc_html_e($deletedr);
        ?> &nbsp; record deleted
        <?php 
        die();
    }
    /**
    * Remove user from post
    * @since    1.0.0
    * @access   public
    **/
    // public function pto_sing_up_remove_user_id_to_post() {   
    //     if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
    //         die ( 'Busted!');
    //     } 
    //     global $wpdb;
    //     $table_name = $wpdb->prefix . "signup_orders";      
    //     if($_POST)
    //     {
    //         if(isset($_POST['post_id']))
    //         {
    //             $post_id = esc_attr( $_POST['post_id'] );

    //             $orderid = esc_attr( $_POST['orderid'] );

    //             $cnum = esc_attr( $_POST['cnum'] );

    //             $main_post_id = esc_attr( $_POST['main_post_id'] );

    //             $current_user_id = esc_attr( $_POST['user_id'] );

    //             $date = esc_attr( $_POST['date'] );

    //             $taskid = $post_id;
    //             $signupid = $main_post_id;
    //             $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );

    //             if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                            
                    
    //                 $taskid_explode = explode("_", $taskid);
    //                 $tid = $taskid_explode[0];
    //                 $tdate = $taskid_explode[1];
                    
    //             }
    //             else{
    //                 $tid = $taskid;
    //             }
               
                
    //             $selected_date = get_post_meta( $tid,"pto_sign_up_selected_date_time",true);
    //             $get_manage_volunters = get_post_meta( $main_post_id,"pto_get_manage_volunteers",true);
    //             $user_post_meta = get_user_meta( $current_user_id, 'pto_get_manage_volunteers_users_post', true );
                
    //             $signupid = $main_post_id;
    //             $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );
    //             $max_key = $signupid."_".$taskid;
    //             $maxval = $get_max_user_task_signup[$max_key];
    //             if(!empty($get_max_user_task_signup)){
    //                 //$get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $maxval;
    //                 $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - 1;
    //                 update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );
    //             } 
                
    //             if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                 //to fill task availability 
    //                 $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );

    //                 if(!empty($get_filled)){
    //                     $get_filled = $get_filled - 1;
    //                     update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );
    //                 } 
    //             }
    //             else{
    //                 //to fill task availability 
    //                 $get_filled = get_post_meta( $tid, "signup_task_availability", true );
    //                 if(!empty($get_filled)){
    //                     $get_filled = $get_filled - 1;
    //                     update_post_meta( $tid, "signup_task_availability", $get_filled );
    //                 } 
    //             }
    //             // to add task hours/points to user   
    //             $task_hours_points = get_post_meta( $tid, "pto_sign_ups_hour_points", true );                              
    //             if(!empty($task_hours_points)){                 
    //                 $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );
    //                 if(!empty($get_user_task_hours)){
    //                     $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;
    //                     update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );
    //                 }
    //             }
    //             $get_user_signup_data = "";
    //             $old_user_customf_data = array();
    //             $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE ID = ".intval($orderid) );
    //             if(!empty($all_user_posts)){
    //                 foreach($all_user_posts as $userkey => $post){
    //                     $get_user_signup_data = unserialize($post->order_info);
    //                 }
    //             }

    //             // to remove shift time for task and user 
    //             $single_post_meta = get_post_meta( $tid, "single_tasks_advance_options", true );  
    //             if(!empty($single_post_meta)){ 
    //                 if(array_key_exists("shift",$single_post_meta)) {
    //                     $oldshifts = $get_user_signup_data["task_time".$taskid][0];
    //                     $oldshiftarray = explode(",", $oldshifts);
    //                     $stime = $oldshiftarray[$cnum];
    //                     if (($key = array_search($stime, $oldshiftarray)) !== false) {
    //                         unset($oldshiftarray[$key]);
    //                         $get_user_signup_data["task_time".$taskid][0] = implode(",", $oldshiftarray);
    //                     }
    //                     $shift_key = $current_user_id; 
    //                     if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                         $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );
    //                         if(!empty($get_shift_time)){
    //                             $shifttimes = explode(",", $get_shift_time[$shift_key]);
    //                             if(!empty($stime)){
    //                                 if (($key = array_search($stime, $shifttimes)) !== false) {
    //                                     unset($shifttimes[$key]);
    //                                 }                                            
    //                             }                                                             
    //                             $get_shift_time[$shift_key] = implode(",", $shifttimes);
    //                             update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );
    //                         }
    //                     }
    //                     else{
    //                         $get_shift_time = get_post_meta( $tid, 'get_shift_time', true );
    //                         if(!empty($get_shift_time)){
    //                             $shifttimes = explode(",", $get_shift_time[$shift_key]);
    //                             if(!empty($stime)){
    //                                 if (($key = array_search($stime, $shifttimes)) !== false) {
    //                                     unset($shifttimes[$key]);
    //                                 }                                            
    //                             }                                                             
    //                             $get_shift_time[$shift_key] = implode(",", $shifttimes);
    //                             update_post_meta( $tid, 'get_shift_time', $get_shift_time );
    //                         }
    //                     }
    //                 }
    //             }                     
                
                
    //             $taskoldmax = $get_user_signup_data["task_max".$taskid][0];
    //             $get_user_signup_data["task_max".$taskid][0] = $get_user_signup_data["task_max".$taskid][0] - 1;
    //             if(!empty($task_hours_points)){
    //                 $get_user_signup_data["task_hours_points".$taskid][0] = $get_user_signup_data["task_hours_points".$taskid][0] - $task_hours_points; 
    //             }                
    //             $taskmaxval = $get_user_signup_data["task_max".$taskid][0];

    //             if($taskmaxval < 0){
    //                 $taskids = $get_user_signup_data["task_id".$signupid];
                    


    //                 if (($key = array_search($taskid, $taskids)) !== false) {
    //                     unset($taskids[$key]);
    //                     $taskids = array_values($taskids);
    //                     $get_user_signup_data["task_id".$signupid] = $taskids;
    //                     $maxkey = "task_max".$taskid;
    //                     unset($get_user_signup_data[$maxkey]);
    //                     $hourskey = "task_hours_points".$taskid;
    //                     unset($get_user_signup_data[$hourskey]);
    //                     $datekey = "task_date".$taskid;
    //                     unset($get_user_signup_data[$datekey]);
    //                     $timekey = "task_time".$taskid;
    //                     unset($get_user_signup_data[$timekey]);        
    //                 } 
    //             }   
    //             //custom fields removal
    //             $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );

    //             if(!empty($cpt_custom_fileds)){ 
    //                 foreach($cpt_custom_fileds as $cpt_custom_filed){     
    //                     $alternet_title = get_post_meta($cpt_custom_filed,"pto_alternate_title",true);
    //                     //$instruction = get_post_meta($cpt_custom_filed,"instruction",true);
    //                     $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
    //                     //$require = get_post_meta($cpt_custom_filed,"pto_field_required",true);
    //                     $custom_field_title = "";
    //                     if($type == "text-area"){
    //                         $type = "textarea";
    //                     }
    //                     if($type == "drop-down"){
    //                         $type = "select";
    //                     }
    //                     if(!empty($alternet_title)){
    //                         $custom_field_title = $alternet_title;
    //                     }
    //                     else{
    //                         $custom_field_title = get_the_title($cpt_custom_filed);
    //                     }
    //                     //for($c = 0; $c < $task_max_val; $c++){
    //                         $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$cnum;
    //                         //echo $customfieldkey;
    //                         if(array_key_exists($customfieldkey, $get_user_signup_data)){ 
    //                             unset($get_user_signup_data[$customfieldkey]);
    //                         }
    //                     //}
    //                 }
    //                 $new_user_customf_data = array();
    //                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                     foreach($get_user_signup_data as $akey => $avalue){                        
    //                         $keyarray = explode("_", $akey);
    //                         if(in_array($tid, $keyarray) && in_array("custom", $keyarray) && in_array($tdate, $keyarray)){
    //                             $vcount = count($avalue);
    //                             for($v=0; $v<$vcount; $v++){
    //                                 $new_user_customf_data[$akey][$v] = $avalue[$v];
    //                             }                            
    //                         }                        
    //                     }
    
    //                 }
    //                 else{
    //                     foreach($get_user_signup_data as $akey => $avalue){                        
    //                         $keyarray = explode("_", $akey);
    //                         if(in_array($tid, $keyarray) && in_array("custom", $keyarray)){
    //                             $vcount = count($avalue);
    //                             for($v=0; $v<$vcount; $v++){
    //                                 $new_user_customf_data[$akey][$v] = $avalue[$v];
    //                             }                            
    //                         }                        
    //                     }
    
    //                 }
                    
    //                 foreach($new_user_customf_data as $akey => $avalue){ 
    //                     if(array_key_exists($akey, $get_user_signup_data)){
    //                         unset($get_user_signup_data[$akey]);
    //                     }               
    //                 }
    //                 $new_final_array = array();
    //                 $d = 0;
    //                 $oldkey = "";
    //                 $lastkey = "";
    //                 $oldplusone = "";
    //                 $savefisrtlastkey = "";
    //                 foreach($new_user_customf_data as $akey => $avalue){
    //                     $keyarray = explode("_", $akey);
    //                     if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                         $lastkey = $keyarray[6];
    //                     }
    //                     else{
    //                         $lastkey = $keyarray[5];
    //                     }
                        
    //                     if($d == 0){
    //                         $savefisrtlastkey = $lastkey;
    //                     }
    //                     $d++;
    //                     //echo "oldkey: ".$oldkey ;
    //                     if($savefisrtlastkey != "0"){ 
    //                         if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                             $keyarray[6] = $keyarray[6] - 1;
    //                         }
    //                         else{
    //                             $keyarray[5] = $keyarray[5] - 1;
    //                         }
                            
    //                         $newkey = implode("_", $keyarray);
    //                         $vcount = count($avalue);
    //                         for($v=0; $v<$vcount; $v++){
    //                             $new_final_array[$newkey][$v] = $avalue[$v];
    //                         } 
    //                     }
    //                     else{
    //                         if(!empty($oldkey) || $oldkey == "0"){
    //                             $oldplusone = $oldkey + 1;
    //                             if($oldkey == $lastkey || $oldplusone == $lastkey){
    //                                 $vcount = count($avalue);
    //                                 for($v=0; $v<$vcount; $v++){
    //                                     $new_final_array[$akey][$v] = $avalue[$v];
    //                                 } 
    //                                 $oldkey = $lastkey;
    //                             }
    //                             else{
    //                                 if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
    //                                     $keyarray[6] = $keyarray[6] - 1;
    //                                 }
    //                                 else{
    //                                     $keyarray[5] = $keyarray[5] - 1;
    //                                 }
                                    
    //                                 $newkey = implode("_", $keyarray);
    //                                 $vcount = count($avalue);
    //                                 for($v=0; $v<$vcount; $v++){
    //                                     $new_final_array[$newkey][$v] = $avalue[$v];
    //                                 } 
    //                             }
    //                         }
    //                         else{
    //                             //echo "EMPTY";
    //                             $vcount = count($avalue);
    //                             for($v=0; $v<$vcount; $v++){
    //                                 $new_final_array[$akey][$v] = $avalue[$v];
    //                             } 
    //                             $oldkey = $lastkey;
    //                         }   
    //                     }
    //                 }
    //                 //print_r($new_final_array);
    //                 foreach($new_final_array as $akey => $avalue){ 
    //                     if(!array_key_exists($akey, $get_user_signup_data)){
    //                         //unset($get_user_signup_data[$akey]);
    //                         $get_user_signup_data[$akey] = $avalue;
    //                     }               
    //                 }
    //             }
                
    //             $taskids = $get_user_signup_data["task_id".$signupid];
               
    //             if(empty($taskids)){
    //                 //echo "delete record";
    //                 //$sql =  $wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".$orderid ); 
    //                 ;
    //                 $result = $wpdb->query($wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".intval($orderid) ));
                    
    //                 if($result){
    //                     echo "deleted successfully";
    //                 }
    //                 else{
    //                     echo "error";
    //                 }
    //             }
    //             else{
    //                 // echo "<pre>";
    //                 // print_r($get_user_signup_data);
    //                 // echo "</pre>";
    //                 //echo "update record";
    //                 $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".serialize($get_user_signup_data)."' WHERE ID = ".intval($orderid) ));
    //                 if($result){
    //                     echo "deleted successfully";
    //                 }
    //                 else{
    //                     echo "error";
    //                 }
    //             }
    //         }
    //     }
    //     die();
    // }
     public function pto_sing_up_remove_user_id_to_post() {   

        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {

            die ( 'Busted!');

        } 

        global $wpdb;

        $table_name = $wpdb->prefix . "signup_orders";      

        if($_POST)

        {

            if(isset($_POST['post_id']))

            {

                $post_id = intval( $_POST['post_id'] );

                $orderid = intval( $_POST['orderid'] );

                $cnum = intval( $_POST['cnum'] );

                $main_post_id = intval( $_POST['main_post_id'] );

                $current_user_id = intval( $_POST['user_id'] );

                $date = sanitize_text_field( $_POST['date'] );

                $taskid = $post_id;

                $signupid = $main_post_id;

                $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );

                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                            

                    $taskid_explode = explode("_", $taskid);

                    $tid = $taskid_explode[0];

                    $tdate = $taskid_explode[1];

                }

                else{

                    $tid = $taskid;

                }

                $selected_date = get_post_meta( $tid,"pto_sign_up_selected_date_time",true);

                $get_manage_volunters = get_post_meta( $main_post_id,"pto_get_manage_volunteers",true);

                $user_post_meta = get_user_meta( $current_user_id, 'pto_get_manage_volunteers_users_post', true );

                //echo "====";

                

                // to store max value of task for user

                $signupid = $main_post_id;

                $get_max_user_task_signup = get_user_meta( $current_user_id, 'max_user_task_signup', true );

                $max_key = $signupid."_".$taskid;

                $maxval = $get_max_user_task_signup[$max_key];

                if(!empty($get_max_user_task_signup)){

                    //$get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - $maxval;

                    $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] - 1;

                    update_user_meta( $current_user_id, 'max_user_task_signup', $get_max_user_task_signup );

                } 

                

                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                    //to fill task availability 

                    $get_filled = get_post_meta( $tid, "signup_task_availability".$taskid, true );

                    if(!empty($get_filled)){

                        $get_filled = $get_filled - 1;

                        update_post_meta( $tid, "signup_task_availability".$taskid, $get_filled );

                    } 

                }

                else{

                    //to fill task availability 

                    $get_filled = get_post_meta( $tid, "signup_task_availability", true );

                    if(!empty($get_filled)){

                        $get_filled = $get_filled - 1;

                        update_post_meta( $tid, "signup_task_availability", $get_filled );

                    } 

                }



                // to add task hours/points to user   

                $task_hours_points = get_post_meta( $tid, "pto_sign_ups_hour_points", true );                              

                if(!empty($task_hours_points)){                 

                    $get_user_task_hours = get_user_meta( $current_user_id, 'user_task_hours_points', true );

                    if(!empty($get_user_task_hours)){

                        $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] - $task_hours_points;

                        update_user_meta( $current_user_id, 'user_task_hours_points', $get_user_task_hours );

                    }

                }



                $get_user_signup_data = "";

                $old_user_customf_data = array();

                $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE ID = ".intval($orderid) );

                if(!empty($all_user_posts)){

                    foreach($all_user_posts as $userkey => $post){

                        $get_user_signup_data = unserialize($post->order_info);

                    }

                }



                //print_r($get_user_signup_data);





                // to remove shift time for task and user 

                $single_post_meta = get_post_meta( $tid, "single_tasks_advance_options", true );  

                if(!empty($single_post_meta)){ 

                    if(array_key_exists("shift",$single_post_meta)) {

                        $oldshifts = $get_user_signup_data["task_time".$taskid][0];

                        $oldshiftarray = explode(",", $oldshifts);

                        $stime = $oldshiftarray[$cnum];

                        if (($key = array_search($stime, $oldshiftarray)) !== false) {

                            unset($oldshiftarray[$key]);

                            $get_user_signup_data["task_time".$taskid][0] = implode(",", $oldshiftarray);

                        }

                        $shift_key = $current_user_id; 

                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                            $get_shift_time = get_post_meta( $tid, 'get_shift_time'.$taskid, true );

                            if(!empty($get_shift_time)){

                                $shifttimes = explode(",", $get_shift_time[$shift_key]);

                                if(!empty($stime)){

                                    if (($key = array_search($stime, $shifttimes)) !== false) {

                                        unset($shifttimes[$key]);

                                    }                                            

                                }                                                             

                                $get_shift_time[$shift_key] = implode(",", $shifttimes);

                                update_post_meta( $tid, 'get_shift_time'.$taskid, $get_shift_time );

                            }

                        }

                        else{

                            $get_shift_time = get_post_meta( $tid, 'get_shift_time', true );

                            if(!empty($get_shift_time)){

                                $shifttimes = explode(",", $get_shift_time[$shift_key]);

                                if(!empty($stime)){

                                    if (($key = array_search($stime, $shifttimes)) !== false) {

                                        unset($shifttimes[$key]);

                                    }                                            

                                }                                                             

                                $get_shift_time[$shift_key] = implode(",", $shifttimes);

                                update_post_meta( $tid, 'get_shift_time', $get_shift_time );

                            }

                        }

                    }

                }                     

                

                $taskoldmax = $get_user_signup_data["task_max".$taskid][0];

                $get_user_signup_data["task_max".$taskid][0] = $get_user_signup_data["task_max".$taskid][0] - 1;

                if(!empty($task_hours_points)){
                    $get_user_signup_data["task_hours_points".$taskid][0] = $get_user_signup_data["task_hours_points".$taskid][0] - $task_hours_points; 
                }                

                $taskmaxval = $get_user_signup_data["task_max".$taskid][0];

                if($taskmaxval == 0){

                    $taskids = $get_user_signup_data["task_id".$signupid];

                    if (($key = array_search($taskid, $taskids)) !== false) {

                        unset($taskids[$key]);

                        $taskids = array_values($taskids);

                        $get_user_signup_data["task_id".$signupid] = $taskids;

                        $maxkey = "task_max".$taskid;

                        unset($get_user_signup_data[$maxkey]);

                        $hourskey = "task_hours_points".$taskid;

                        unset($get_user_signup_data[$hourskey]);

                        $datekey = "task_date".$taskid;

                        unset($get_user_signup_data[$datekey]);

                        $timekey = "task_time".$taskid;

                        unset($get_user_signup_data[$timekey]);        

                    } 

                }   



                //custom fields removal

                $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );

                if(!empty($cpt_custom_fileds)){ 

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

                        //for($c = 0; $c < $task_max_val; $c++){

                            $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$cnum;

                            //echo $customfieldkey;

                            if(array_key_exists($customfieldkey, $get_user_signup_data)){ 

                                unset($get_user_signup_data[$customfieldkey]);

                            }

                        //}

                    }



                    $new_user_customf_data = array();

                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                        foreach($get_user_signup_data as $akey => $avalue){                        

                            $keyarray = explode("_", $akey);

                            if(in_array($tid, $keyarray) && in_array("custom", $keyarray) && in_array($tdate, $keyarray)){

                                $vcount = count($avalue);

                                for($v=0; $v<$vcount; $v++){

                                    $new_user_customf_data[$akey][$v] = $avalue[$v];

                                }                            

                            }                        

                        }

    

                    }

                    else{

                        foreach($get_user_signup_data as $akey => $avalue){                        

                            $keyarray = explode("_", $akey);

                            if(in_array($tid, $keyarray) && in_array("custom", $keyarray)){

                                $vcount = count($avalue);

                                for($v=0; $v<$vcount; $v++){

                                    $new_user_customf_data[$akey][$v] = $avalue[$v];

                                }                            

                            }                        

                        }

    

                    }

                    

                    foreach($new_user_customf_data as $akey => $avalue){ 

                        if(array_key_exists($akey, $get_user_signup_data)){

                            unset($get_user_signup_data[$akey]);

                        }               

                    }



                    $new_final_array = array();

                    $d = 0;

                    $oldkey = "";

                    $lastkey = "";

                    $oldplusone = "";

                    $savefisrtlastkey = "";

                    foreach($new_user_customf_data as $akey => $avalue){

                        $keyarray = explode("_", $akey);

                        if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                            $lastkey = $keyarray[6];

                        }

                        else{

                            $lastkey = $keyarray[5];

                        }

                        

                        if($d == 0){

                            $savefisrtlastkey = $lastkey;

                        }

                        $d++;

                        //echo "oldkey: ".$oldkey ;

                        if($savefisrtlastkey != "0"){ 

                            if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                                $keyarray[6] = $keyarray[6] - 1;

                            }

                            else{

                                $keyarray[5] = $keyarray[5] - 1;

                            }

                            

                            $newkey = implode("_", $keyarray);

                            $vcount = count($avalue);

                            for($v=0; $v<$vcount; $v++){

                                $new_final_array[$newkey][$v] = $avalue[$v];

                            } 

                        }

                        else{

                            if(!empty($oldkey) || $oldkey == "0"){

                                $oldplusone = $oldkey + 1;

                                if($oldkey == $lastkey || $oldplusone == $lastkey){

                                    $vcount = count($avalue);

                                    for($v=0; $v<$vcount; $v++){

                                        $new_final_array[$akey][$v] = $avalue[$v];

                                    } 

                                    $oldkey = $lastkey;

                                }

                                else{

                                    if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){

                                        $keyarray[6] = $keyarray[6] - 1;

                                    }

                                    else{

                                        $keyarray[5] = $keyarray[5] - 1;

                                    }

                                    

                                    $newkey = implode("_", $keyarray);

                                    $vcount = count($avalue);

                                    for($v=0; $v<$vcount; $v++){

                                        $new_final_array[$newkey][$v] = $avalue[$v];

                                    } 

                                }

                            }

                            else{

                                //echo "EMPTY";

                                $vcount = count($avalue);

                                for($v=0; $v<$vcount; $v++){

                                    $new_final_array[$akey][$v] = $avalue[$v];

                                } 

                                $oldkey = $lastkey;

                            }   

                        }

                    }

                    //print_r($new_final_array);

                    foreach($new_final_array as $akey => $avalue){ 

                        if(!array_key_exists($akey, $get_user_signup_data)){

                            //unset($get_user_signup_data[$akey]);

                            $get_user_signup_data[$akey] = $avalue;

                        }               

                    }

                }



                //print_r($get_user_signup_data);

                

                $taskids = $get_user_signup_data["task_id".$signupid];

                if(empty($taskids)){

                    //echo "delete record";

                    //$sql =  $wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".$orderid ); 

                    $result = $wpdb->query($wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".intval($orderid) ));

                    if($result){

                         esc_html_e("deleted successfully");

                    }

                    else{

                        esc_html_e("error");

                    }

                }

                else{

                    //echo "update record";

                    $sql =  $wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".serialize($get_user_signup_data)."' WHERE ID = ".$orderid );            

                    $result = $wpdb->query($wpdb->prepare( "UPDATE ".$table_name." SET order_info = '".esc_sql(serialize($get_user_signup_data))."' WHERE ID = ".intval($orderid) ));

                    if($result){

                        esc_html_e("deleted successfully");

                    }

                    else{

                        esc_html_e("error");

                    }

                }

            }

        }

        die();

    }
}