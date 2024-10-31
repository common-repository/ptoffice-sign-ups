<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoSignupSetting {
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
        /*admin submenu add in project */
        add_action( 'admin_menu', array( $this, 'pto_sign_up_add_submenu_page_to_post_type' ) );
        /* admin role added in users */
        add_action( 'admin_init', array( $this, 'pto_sign_up_roles' ) );
        /* get all user from sign up settng tab */
        add_action( 'wp_ajax_nopriv_pto_sign_up_admin_user_search', array( $this , 'pto_sign_up_admin_user_search' ) );
        add_action( 'wp_ajax_pto_sign_up_admin_user_search', array( $this , 'pto_sign_up_admin_user_search' ) );
        /* add new users signup_setting*/
        add_action( 'wp_ajax_nopriv_pto_sign_ups_new_users_add', array( $this , 'pto_sign_up_new_users_add' ) );
        add_action( 'wp_ajax_pto_sign_ups_new_users_add', array( $this , 'pto_sign_up_new_users_add' ) );
        /* remove users signup_setting*/
        add_action( 'wp_ajax_nopriv_pto_sign_ups_new_users_remove', array( $this , 'pto_sign_up_new_users_remove' ) );
        add_action( 'wp_ajax_pto_sign_ups_new_users_remove', array( $this , 'pto_sign_up_new_users_remove' ) );
        add_filter( "mce_external_plugins", array( $this ,"pto_sign_up_owt_attach_fns_custom_buttons" ) );
        /* admin menu remove in side bar */
        add_action( 'admin_menu', array( $this,'pto_sign_up_remove_menu_items' ) );
        add_filter( 'default_content',  array( $this,'pto_sign_up_t5_preset_editor_content' ), 10, 2 );
        /* remove users signup_setting*/
        add_action( 'wp_ajax_nopriv_user_mail_resend_functionality', array( $this , 'pto_sign_up_user_mail_resend_functionality' ) );
        add_action( 'wp_ajax_user_mail_resend_functionality', array( $this , 'pto_sign_up_user_mail_resend_functionality' ) );
        /* remove users signup_setting*/
        add_action( 'wp_ajax_nopriv_get_page_all_list', array( $this , 'pto_sign_up_get_page_all_list' ) );
        add_action( 'wp_ajax_get_page_all_list', array( $this , 'pto_sign_up_get_page_all_list' ) );
        /* set color settings to default */
        add_action( 'wp_ajax_nopriv_pto_signup_set_default_color', array( $this , 'pto_sign_up_set_default_color' ) );
        add_action( 'wp_ajax_pto_signup_set_default_color', array( $this , 'pto_sign_up_set_default_color' ) );
        /* set widget settings to default */
        add_action( 'wp_ajax_nopriv_pto_signup_set_default_widget_setting', array( $this , 'pto_sign_up_set_default_widget_setting' ) );
        add_action( 'wp_ajax_pto_signup_set_default_widget_setting', array( $this , 'pto_sign_up_set_default_widget_setting' ) );
        /* custom post save from task slots */
        add_action( 'save_post_page', array( $this,'pto_sign_up_page_post_save' ), 20, 2 );  
        /* pto sign ups task status manage volunteers */ 
        add_action( 'wp_ajax_nopriv_pto_task_search_manage', array( $this , 'pto_sign_up_task_search_manage' ) );
        add_action( 'wp_ajax_pto_task_search_manage', array( $this , 'pto_sign_up_task_search_manage' ) );
        /* pto sign ups task status manage volunteers */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_single_user_add', array( $this , 'pto_sign_up_single_user_add' ) );
        add_action( 'wp_ajax_pto_sing_up_single_user_add', array( $this , 'pto_sign_up_single_user_add' ) );
        /* pto view receipt manage volunteers */ 
        add_action( 'wp_ajax_nopriv_pto_manage_volunteer_view_receipt', array( $this , 'pto_sign_up_manage_volunteer_view_receipt' ) );
        add_action( 'wp_ajax_pto_manage_volunteer_view_receipt', array( $this , 'pto_sign_up_manage_volunteer_view_receipt' ) );
        /* hide all sidebar menus for other users except admins */
        add_action( "admin_head", array( $this , "pto_sign_up_hide_sidebar_menu_other_role" ) );
        /* post access denied for signup users */
        add_action('admin_init', array( $this,'pto_sign_up_post_access_denied' ));
        /* restrict post display user wise */
        add_action( 'load-edit.php', array( $this , 'pto_sign_up_posts_for_current_author' ));
        /* give edit access to signup user */
        add_action( 'user_register', array( $this, 'pto_sign_up_registration_save' ), 10, 1 );
        add_action( "init", array( $this, "pto_sign_up_usercap_add" ) );
        /* redirect signup own user to frontend*/
        add_filter( 'login_redirect', array( $this, "pto_sign_up_own_user_redirect" ), 10, 3 );
        /* signup cpt templete override */
        add_filter( 'template_include', array( $this , 'pto_sign_up_custom_single_template_signup') , 99 );
        
        /* accept request */
        add_action( 'wp_ajax_nopriv_pto_sing_up_accept_request', array( $this , 'pto_sign_up_accept_request' ) );
        add_action( 'wp_ajax_pto_sing_up_accept_request', array( $this , 'pto_sign_up_accept_request' ) );
        /* decline request */
        add_action( 'wp_ajax_nopriv_pto_sing_up_decline_request', array( $this , 'pto_sign_up_decline_request' ) );
        add_action( 'wp_ajax_pto_sing_up_decline_request', array( $this , 'pto_sign_up_decline_request' ) );
        /* get tasks of selected signup */
        add_action( 'wp_ajax_nopriv_pto_get_tasks_of_signup', array( $this , 'pto_sign_up_get_tasks_of_signup' ) );
        add_action( 'wp_ajax_pto_get_tasks_of_signup', array( $this , 'pto_sign_up_get_tasks_of_signup' ) );
        /* to save notified admin user for singup */
        add_action( 'wp_ajax_nopriv_pto_signup_save_notify_admin_user', array( $this , 'pto_sign_up_save_notify_admin_user' ) );
        add_action( 'wp_ajax_pto_signup_save_notify_admin_user', array( $this , 'pto_sign_up_save_notify_admin_user' ) );
        /* to remove notified admin user for singup */
        add_action( 'wp_ajax_nopriv_pto_signup_remove_notify_admin_user', array( $this , 'pto_sign_up_remove_notify_admin_user' ) );
        add_action( 'wp_ajax_pto_signup_remove_notify_admin_user', array( $this , 'pto_sign_up_remove_notify_admin_user' ) );
        /* top volunteers sorting ajax */
        add_action( 'wp_ajax_nopriv_pto_signup_volunteers_sorting', array( $this , 'pto_sign_up_volunteers_sorting' ) );
        add_action( 'wp_ajax_pto_signup_volunteers_sorting', array( $this , 'pto_sign_up_volunteers_sorting' ) );
        /* get signup report filter data */
        add_action( 'wp_ajax_nopriv_pto_signup_filter_data', array( $this , 'pto_sign_up_filter_data' ) );
        add_action( 'wp_ajax_pto_signup_filter_data', array( $this , 'pto_sign_up_filter_data' ) );
        /* occurence not on specific day (to hide specific start time on add new task) */
        add_action( 'wp_ajax_nopriv_pto_occurrence_not_specific', array( $this , 'pto_sign_up_occurrence_not_specific' ) );
        add_action( 'wp_ajax_pto_occurrence_not_specific', array( $this , 'pto_sign_up_occurrence_not_specific' ) );
        /* add custom column in signup custom post admin screen */
        add_filter( 'manage_pto-signup_posts_columns', array( $this , 'pto_sign_up_add_custom_column') );
        add_action( 'manage_pto-signup_posts_custom_column', array( $this , 'pto_sign_up_add_custom_column_values'), 10, 2);
        /* send email on user delete */
        add_action( 'delete_user', array( $this , 'pto_sign_up_delete_user') );
        /* body in class add*/
        add_filter('admin_body_class' , array( $this ,'pto_sign_up_admin_body_class' ) );        
        /* rewrite rule flush */
        add_action( 'init',  array( $this, 'pto_sign_up_flush_rewrite_rules' ) );
        /* remove task categories */
        add_action( 'admin_footer', array( $this, 'pto_sign_up_append_taxonomy_descriptions_metabox' ) );
        add_action( 'wp_ajax_nopriv_pto_signup_remove_task_category', array( $this , 'pto_sign_up_remove_task_category' ) );
        add_action( 'wp_ajax_pto_signup_remove_task_category', array( $this , 'pto_sign_up_remove_task_category' ) );
        /* add new user from manage volunteer */
        add_action( 'wp_ajax_nopriv_pto_sing_up_add_new_user', array( $this , 'pto_sign_up_add_new_user' ) );
        add_action( 'wp_ajax_pto_sing_up_add_new_user', array( $this , 'pto_sign_up_add_new_user' ) );
    }
    /**
    * To add new user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_add_new_user() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');    
        }
        $fname = "";
        $lname = "";
        $uemail = "";
        if(isset($_POST)){
            $fname = sanitize_text_field( $_POST["fname"] );
            $lname = sanitize_text_field( $_POST["lname"] );
            $uemail = sanitize_text_field( $_POST["uemail"] );
            if(!empty($fname) && !empty($lname) && !empty($uemail)){
                $password = wp_generate_password( 8, false );
                if (!filter_var( $uemail, FILTER_VALIDATE_EMAIL )) {
                    esc_html_e("Invalid");
                }
                else{
                    if ( username_exists( $fname ) == null && email_exists( $uemail ) == false ) {
                        $user_id = wp_create_user( $fname, $password, $uemail );
                        $user_data = wp_update_user( array( 'ID' => $user_id, 'first_name' => $fname, 'last_name' => $lname ) );
                        $user = get_user_by( 'id', $user_id );
                        $to = $uemail;
                        $u_to = $user->user_email;
                        $subject = 'New User Added';
                        $body = 'Hello '.$fname.'! ';
                        $body .= "<p>Yor are successfully registered.</p>"; 
                        $body .= "<p>Your password is: ".$password."</p>";
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        
                        wp_mail( $to, $subject, $body, $headers );
                        wp_mail( $u_to, $subject, $body, $headers );
                        esc_html_e("Success");
                    }
                    else{
                        esc_html_e("Exist");
                    }
                }
            }
        }
        die();
    }
    /**
    * Remove task category
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_remove_task_category() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');    
        }
        if(isset( $_POST["cat_id"] )){
            $cat_id = intval( $_POST["cat_id"] );
            $taxonomy_name = "TaskCategories";
            wp_delete_term( $cat_id, $taxonomy_name ); 
        }
        die();
    }
    
    /**
    * Append description to taxonomy metabox
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_append_taxonomy_descriptions_metabox() {
        $post_types = array( 'tasks-signup' );          // Array of Accepted Post Types
        $screen     = get_current_screen();     // Get current user screen
        // IF the current post type is in our array
        if( in_array( $screen->post_type, $post_types ) ) {
            $taxonomies = get_object_taxonomies( $screen->post_type, 'objects' );   // Grab all taxonomies for that post type
            // Ensure taxonomies are not empty
            if( ! empty( $taxonomies ) ) :  ?>
                <script type="text/javascript">
                    <?php 
                        foreach( $taxonomies as $taxonomy ) : 
                            $taxonomy_name = sanitize_text_field($taxonomy->name);
                            if($taxonomy_name == "TaskCategories"){
                                $slug = $taxonomy->rewrite["slug"]; 
                                $terms = get_terms( array( 
                                    'taxonomy' => 'TaskCategories', 
                                    'hide_empty' => false                       
                                ) );
                                foreach( $terms as $category ) {
                                ?>
                                var taxonomy_name = '<?php esc_html_e( $taxonomy_name ); ?>';
                                var cat_id = '<?php echo intval( $category->term_id ); ?>';
                                
                                jQuery( 'li#' + taxonomy_name + '-' + cat_id + ' label' ).after( '<span class="del-cat" data-id="'+cat_id+'">Delete</span>' );
                                <?php
                                    
                                }
                            }                    
                        endforeach; 
                    ?>
                </script>   
                <script type="text/javascript">
                <?php foreach( $taxonomies as $taxonomy ) :  ?>
                    var tax_slug = '<?php esc_html_e( $taxonomy->name ); ?>';
                    var tax_desc = '<?php esc_html_e( $taxonomy->description ); ?>';
                    var terms = '<?php esc_html_e( $taxonomy->rewrite["slug"] ); ?>';                    
                <?php endforeach; ?>
                </script>
            <?php endif;
        }
    }
    /**
    * Rewrite rule flush
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_flush_rewrite_rules() {

        if ( !get_option( 'signup_flush_rewrite_rules_flag' ) ) {
            echo "435";
            add_option( 'signup_flush_rewrite_rules_flag', 1 );
            flush_rewrite_rules();
        }
    }
    
    /**
    * Add custom column in sign up admin screen
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_add_custom_column( $columns ) {
        $columns['start_date'] = __( 'Start Date', 'hello' );
        $columns['end_date'] = __( 'End Date' );        
        return $columns;
    }
    /**
    * Add value to custom column
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_add_custom_column_values( $column_name, $post_id ) {
        // Image column
        $time_set = get_post_meta($post_id,"pto_sign_ups_time_set",true);        
        if ($column_name == 'start_date') {
            if(!empty($time_set) && is_array($time_set)){
                esc_html_e( $time_set["opendate"]." ".$time_set["opentime"] );
            }
        }
        if ($column_name == 'end_date') {
            if(!empty($time_set) && is_array($time_set)){
                esc_html_e( $time_set["closedate"]." ".$time_set["closetime"] );
            }
        }
    }
    /**
    * Send email on delete user
    * @since    1.0.0
    * @access   public
    **/
    public function removeHtmlOrScriptTag($htmlString) {
        $htmlString = strip_tags($htmlString);
        // Create a regular expression pattern to match HTML or script tags
        $pattern = '#<script(.*?)>(.*?)</script>#is';

        // Use preg_replace to remove the matched HTML or script tag from the string
        $result = preg_replace($pattern, '', $htmlString);
        // echo $result;
        return $result;
    }
    public function pto_sign_up_delete_user( $user_id ) {
        global $wpdb;        
        $cur_user_obj = get_user_by( 'id', $user_id );
        $cuname = $cur_user_obj->display_name;
        $siteurl = site_url();       
        $to = $cur_user_obj->user_email;
        $subject = "Your account has been removed";
        $mailcontent = "Hello ".$cuname.", ";
        $mailcontent .= "This email is to let you know that your account associated with " .$to. "has been removed from the " . $siteurl . "site. ";
        $mailcontent .= "Please contact the organization if you feel this was done in error.";
        $body = $mailcontent;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers );
    }
    
    /**
    * Signup own user redirection to forntend
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_own_user_redirect( $url, $request, $user ) {        
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            if(in_array( "administrator", $user->roles ) || in_array( "sign_up_plugin_administrators", $user->roles ) ){
            }
            else if(in_array( "own_sign_up", $user->roles )){
                $url = site_url()."/signup";
            }
            else{
                $url = site_url();
            }
        }
        return $url;
    }
    /**
    * Add class to body
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_admin_body_class( $classes ) {
        global $post;
        if( $post !=  "" ) {
            if( $post->post_type == "pto-signup" || $post->post_type == "tasks-signup" || $post->post_type == "pto-custom-fields" ){
                $classes .= "pto-custom-style";
            }
        }
        return $classes;
    }
    /**
    * Show post user wise
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_posts_for_current_author() {
        global $user_ID, $post;
        $posttype = "";
        if(isset($_GET["post_type"])){
            $posttype = sanitize_text_field( $_GET["post_type"] );
        }
        if( !empty( $posttype ) && $posttype == "pto-signup" ){
            $user = new \WP_User( $user_ID );
            $cnt = 0;    
            if(in_array( "administrator", $user->roles ) || in_array( "sign_up_plugin_administrators", $user->roles )){
                $cnt = 1;               
            }
            if($cnt == 0){               
                if ( ! isset( $_GET['author'] ) ) {                    
                    wp_safe_redirect( add_query_arg( 'author', $user_ID ) );
                    exit;
                }    
            }
        }
    }
    /**
    * Sign up report filter
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_filter_data() {  
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        $nomaltable = "";
        $exporttable = "";
        $table_name = $wpdb->prefix . "signup_orders";
        $signup_id = "";
        $task_id = "";
        $user_id = "";
        $from_date = "";
        $to_date = "";
        if(isset( $_POST["signup_id"] )){
            $signup_id = intval( $_POST["signup_id"] );
        } 
        if(isset( $_POST["task_id"] )){
            $task_id = intval( $_POST["task_id"] );
        }
        if(isset( $_POST["user_id"] )){
            $user_id = intval( $_POST["user_id"] );
        }
        $from_date = "";
        $to_date ="";
        if(isset( $_POST["from_date"] )){
            
            $from_date = sanitize_text_field( $_POST["from_date"] );
            if(!empty( $from_date )){
                $from_date = date( "Y-m-d", strtotime( $from_date ) );
            }
        }
        if(isset( $_POST["to_date"] )){
            $to_date = sanitize_text_field( $_POST["to_date"] );
            if(!empty( $to_date )){
                $to_date = date( "Y-m-d", strtotime( $to_date ) );
            } else{
                $to_date = date( "Y-m-d" );
            }               
        }
        $all_user_posts = "";
        if(!empty( $from_date ) && !empty( $to_date ) && !empty( $user_id )){            
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND user_id = ".intval( $user_id )." AND checkout_date between '". esc_sql( $from_date ) ."' and '". esc_sql( $to_date ) ."'" );
        }
        elseif(!empty( $from_date ) && !empty( $to_date )){
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND checkout_date between '".esc_sql( $from_date )."' and '".esc_sql( $to_date )."'" );
        }
        elseif(!empty( $from_date ) && !empty( $user_id )){            
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND checkout_date = '".esc_sql( $from_date )."' AND user_id = ".intval( $user_id ) );
        }
        elseif(!empty( $from_date )){
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND checkout_date = '".esc_sql( $from_date )."'" );
        }
        elseif(!empty( $user_id )){
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE status = 'on' AND user_id = ".intval( $user_id ) );
        }
        else{
            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name. " WHERE status = 'on'" );
        }        
        
        $signups = array();
        $tasks = array();
        if(!empty( $all_user_posts )){

            foreach( $all_user_posts as $key => $post ):
                $checkout_date = $post->checkout_date;
                $user_id = $post->user_id;
                $user_info = get_userdata( $user_id );
                $first_name = $user_info->first_name;
                if(empty( $first_name )){
                    $first_name = $user_info->display_name;
                }
                $last_name = $user_info->last_name;
                $display_name = $user_info->display_name;
                $user_email = $user_info->user_email;
                $get_user_signup_data = unserialize( $post->order_info );
                if(!empty( $get_user_signup_data )){
                    
                    $total_signup = count( $get_user_signup_data["signup_id"] );
                    for( $i=0; $i<$total_signup; $i++ ){
                        $signupid = $get_user_signup_data["signup_id"][$i];                                   
                        $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                        if( empty($pto_sign_up_occurrence) )
                            $pto_sign_up_occurrence = array();
                        $total_task = count( $get_user_signup_data["task_id".$signupid] );
                        for($j=0; $j<$total_task; $j++){ 
                            $taskid = $get_user_signup_data["task_id".$signupid][$j];
                            
                            $sdate = "";
                            if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                        
                                $taskid_explode = explode( "_", $taskid );
                                
                                $tid = $taskid_explode[0];
                                $sdate = $taskid_explode[1];
                            }
                            else{
                                $tid = $taskid;
                            }
                            $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
                            $tasktitle = "";
                            if(!empty($saved_dates)){
                                $tasktitle = $saved_dates["post_title"];
                            }
                            else{
                                $tasktitle = get_the_title( $tid );
                            }
                            $hourspoints = "N/A";
                            if(array_key_exists( "task_hours_points".$taskid, $get_user_signup_data )){
                                $hourspoints = $get_user_signup_data["task_hours_points".$taskid][0];
                            }
                            if(!empty($signup_id) && !empty($task_id)){
                                if($signupid == $signup_id && $tid == $task_id){  
                                    $signups[] = $signupid;
                                    $tasks[] = $taskid;
                                
                                    $nomaltable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                    $nomaltable .= '<td class="column-lname">'.$last_name.'</td>';
                                    $nomaltable .=  '<td class="column-email">'.$user_email.'</td>';
                                    $nomaltable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                    $nomaltable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                    $nomaltable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                                                  
                                }
                            }
                            elseif(!empty( $signup_id )){
                                if($signupid == $signup_id){  
                                    $signups[] = $signupid;
                                    $tasks[] = $taskid;
                                    
                                    $nomaltable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                    $nomaltable .= '<td class="column-lname">'.$last_name.'</td>';
                                    $nomaltable .=  '<td class="column-email">'.$user_email.'</td>';
                                    $nomaltable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                    $nomaltable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                    $nomaltable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                   
                                }                                
                            } 
                            elseif(!empty($task_id)){
                                if($tid == $task_id){  
                                    $signups[] = $signupid;
                                    $tasks[] = $taskid;
                                    $nomaltable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                    $nomaltable .= '<td class="column-lname">'.$last_name.'</td>';
                                    $nomaltable .=  '<td class="column-email">'.$user_email.'</td>';
                                    $nomaltable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                    $nomaltable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                    $nomaltable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                  
                                }                                
                            } 
                            else{
                                $signups[] = $signupid;
                                $tasks[] = $taskid;
                                $nomaltable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                    $nomaltable .= '<td class="column-lname">'.$last_name.'</td>';
                                    $nomaltable .=  '<td class="column-email">'.$user_email.'</td>';
                                    $nomaltable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                    $nomaltable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                    $nomaltable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                    
                            }
                        }
                    }
                }
               
            endforeach;
        }
        else{
            $nomaltable .= '<tr><td colspan="">No data found</td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        $tdcount =0;
        if(!empty($all_user_posts)){
            
            $duplicate_removed = array();
            $duplicate_removed_signups = array();
            if(!empty($signups) && !empty($tasks)){
                $duplicate_removed = array_unique( $tasks );
                $duplicate_removed_signups = array_unique( $signups );
                $exporttable .= '<thead><tr><th scope="col" id="fname" class="manage-column column-fname" >First Name</th>';
                $exporttable .= '<th scope="col" id="lname" class="manage-column column-lname" >Last Name</th>';
                $exporttable .= '<th scope="col" id="email" class="manage-column column-email" >Email</th>';
                $exporttable .= '<th scope="col" id="signup" class="manage-column column-signup" >Sign Up</th>';
                $exporttable .= '<th scope="col" id="task" class="manage-column column-task-slot" >Task/Slot</th>';
                $exporttable .= '<th scope="col" id="task-date" class="manage-column column-task-slot-date" >Task/Slot Date</th>';
                $exporttable .= '<th scope="col" id="task-time" class="manage-column column-task-slot-time" >Task/Slot Time</th>';
                
                $thcount = 0;
                if(!empty($duplicate_removed)){
                    foreach($duplicate_removed as $task_slot){
                        $tid = "";                        
                        $taskid_explode = explode( "_", $task_slot );
                        $tid = $taskid_explode[0];                        
                        $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                        if(!empty($cpt_custom_fileds)){
                            foreach($cpt_custom_fileds as $cpt_custom_filed){   
                                $alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
                                $custom_field_title = $alternet_title;
                                if(empty( $alternet_title )){
                                    $custom_field_title = get_the_title( $cpt_custom_filed );
                                }
                                $exporttable .= '<th class="custom_task_fields" >'.$custom_field_title.'</th>';
                                
                            }
                        }   
                    }
                }
                if(!empty($duplicate_removed_signups)){
                    foreach($duplicate_removed_signups as $signup_ids){
                        $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                        $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                        if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                            foreach($signup_custom_fileds as $signup_custom_filed)
                            {
                                $signup_alternet_title = get_post_meta( $signup_custom_filed, "pto_alternate_title", true );
                                $signup_custom_field_title = $signup_alternet_title;
                                if(empty( $signup_alternet_title )){
                                    $signup_custom_field_title = get_the_title( $signup_custom_filed );
                                }
                                $exporttable .= '<th class="custom_task_fields" >'.$signup_custom_field_title.'</th>';                                        
                            }
                        }
                    }
                }
                $exporttable .= '<th scope="col" id="date" class="manage-column column-date" >Checkout Date</th>';
              
                $exporttable .= '</tr></thead><tbody>';
                foreach( $all_user_posts as $key => $post ):
                    $checkout_date = $post->checkout_date;
    
                    $user_id = $post->user_id;
    
                    $user_info = get_userdata( $user_id );
    
                    $first_name = $user_info->first_name;
    
                    if(empty( $first_name )){
                        $first_name = $user_info->display_name;
                    }
    
                    $last_name = $user_info->last_name;
    
                    $display_name = $user_info->display_name;
    
                    $user_email = $user_info->user_email;
    
                    $get_user_signup_data = unserialize( $post->order_info );
    
                    if(!empty( $get_user_signup_data )){
                        
                        $total_signup = count( $get_user_signup_data["signup_id"] );
    
                        for($i=0; $i<$total_signup; $i++){
    
                            $signupid = $get_user_signup_data["signup_id"][$i];                                   
                            $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                            if( empty( $pto_sign_up_occurrence ) ){
                                $pto_sign_up_occurrence = array();
                            }
                            $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                            $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );
                            $total_task = count( $get_user_signup_data["task_id".$signupid] );
    
                            for($j=0; $j<$total_task; $j++){ 
    
                                $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                $task_date = $get_user_signup_data["task_date".$taskid][0];
                                $task_time = $get_user_signup_data["task_time".$taskid][0];
                                $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                $sdate = "";
                                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){                                        
                                    $taskid_explode = explode( "_", $taskid );
                                    $tid = $taskid_explode[0];
                                    $sdate = $taskid_explode[1];
                                }
                                else{
                                    $tid = $taskid;
                                }
                                $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
                                $tasktitle = "";
                                if(!empty( $saved_dates )){
                                    $tasktitle = $saved_dates["post_title"];
                                }
                                else{
                                    $tasktitle = get_the_title( $tid );
                                }
                                $hourspoints = "N/A";
    
                                if(array_key_exists( "task_hours_points".$taskid, $get_user_signup_data )){
    
                                    $hourspoints = $get_user_signup_data["task_hours_points".$taskid][0];
    
                                }
                                $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
                                if( empty(  $get_filed ) )
                                     $get_filed = array();
                                if(array_key_exists( "shift", $get_filed )){
                                    $timekey = "task_time".$taskid;
                                    $tasktime = "";
                                    if(array_key_exists( $timekey, $get_user_signup_data )){ 
                                        $tasktime = $get_user_signup_data[$timekey][0];
                                    }
                                    $shifttimes = explode( ",", $tasktime );
                                    
                                    $emptyRemoved = array_filter( $shifttimes );
    
                                    $tasktime = implode( ", ", $emptyRemoved );
    
                                    $shifttime = explode( ",", $tasktime );
    
                                }
                                for($m = 0; $m < $task_max_val; $m++){
                                    $shtime = "";
                                    if(!empty($shifttime)){ 
    
                                        $shtime = trim($shifttime[$m]); 
                                        $task_time = $shtime;
                                    }
                                    
                                    if(!empty($signup_id) && !empty($task_id)){
    
                                        if($signupid == $signup_id && $tid == $task_id){  
                                                                            
                                            $exporttable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                            $exporttable .= '<td class="column-lname">'.$last_name.'</td>';
                                            $exporttable .=  '<td class="column-email">'.$user_email.'</td>';
                                            $exporttable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                            $exporttable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                            $exporttable .= '<td class="column-task-slot-date">'.$task_date.'</td>';
                                            $exporttable .= '<td class="column-task-slot-time">'.$task_time.'</td>';
                                            if(!empty($duplicate_removed)){
                                                foreach($duplicate_removed as $task_slot){
                                                    $ctid = "";                                            
                                                    $taskid_explode = explode( "_", $task_slot );
                                                    $ctid = $taskid_explode[0];
                                                    
                                                    $cpt_custom_fileds =  get_post_meta( $ctid, "single_task_custom_fields", true );
                                                    if(!empty($cpt_custom_fileds)){
                                                        foreach($cpt_custom_fileds as $cpt_custom_filed){   
                                                            $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                            if($type == "text-area"){
                                                                $type = "textarea";
                                                            }
            
                                                            if($type == "drop-down"){
                                                                $type = "select";
                                                            }
                                                            $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$task_slot."_".$signupid."_".$m;
                                                            $customfieldval = "";
                                                            if(array_key_exists( $customfieldkey, $get_user_signup_data )){ 
                                                                if($type == "checkbox"){        
                                                                    $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);     
                                                                }       
                                                                else{       
                                                                    $customfieldval = $get_user_signup_data[$customfieldkey][0];        
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty( $customfieldval )){
                                                                $customfieldval = $this->removeHtmlOrScriptTag($customfieldval);
                                                                $exporttable .= $customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                            $tdcount++;
                                                        }
                                                    }
            
                                                }
                                            }
                                            if(!empty($duplicate_removed_signups)){
                                                foreach($duplicate_removed_signups as $signup_ids){
                                                    $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                                    $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                                    if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                                        foreach($signup_custom_fileds as $signup_custom_filed)
                                                        {
                                                            $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                                            if($signup_type == "text-area"){
                                                                $signup_type = "textarea";
                                                            }
            
                                                            if($signup_type == "drop-down"){
                                                                $signup_type = "select";
                                                            }
                                                            $signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$signupid;
                                                            $signup_customfieldval = "";
                                                            if(array_key_exists( $signup_customfieldkey, $get_user_signup_data )){  
                                                                if($signup_type == "checkbox"){     
                                                                    $signup_customfieldval = implode( ",", $get_user_signup_data[ $signup_customfieldkey ] );       
                                                                }       
                                                                else{       
                                                                    $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];        
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty( $signup_customfieldval )){
                                                                $signup_customfieldval = $this->removeHtmlOrScriptTag($signup_customfieldval);
                                                                $exporttable .= $signup_customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                        }
                                                    }
                                                }
                                            }
                                            $exporttable .=  '<td class="column-date">'.$checkout_date.'</td>';
                                            $exporttable .= '</tr>';                               
    
                                        }
                                    }
                                    elseif(!empty($signup_id)){
    
                                        if($signupid == $signup_id){  
                                            
                                            $exporttable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                            $exporttable .= '<td class="column-lname">'.$last_name.'</td>';
                                            $exporttable .=  '<td class="column-email">'.$user_email.'</td>';
                                            $exporttable .=  '<td class="column-signup">'.get_the_title( $signupid ).'</td>';
                                            $exporttable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                            $exporttable .= '<td class="column-task-slot-date">'.$task_date.'</td>';
                                            $exporttable .= '<td class="column-task-slot-time">'.$task_time.'</td>';
                                            if(!empty($duplicate_removed)){
                                                foreach($duplicate_removed as $task_slot){
                                                    $ctid = "";                                            
                                                    $taskid_explode = explode( "_", $task_slot );
                                                    $ctid = $taskid_explode[0];
                                                    
                                                    $cpt_custom_fileds =  get_post_meta( $ctid, "single_task_custom_fields", true );
                                                    if(!empty($cpt_custom_fileds)){
                                                        foreach($cpt_custom_fileds as $cpt_custom_filed){   
                                                            $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                            if($type == "text-area"){
                                                                $type = "textarea";
                                                            }
            
                                                            if($type == "drop-down"){
                                                                $type = "select";
                                                            }
                                                            $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$task_slot."_".$signupid."_".$m;
                                                            $customfieldval = "";
                                                            if(array_key_exists($customfieldkey, $get_user_signup_data)){   
                                                                if($type == "checkbox"){        
                                                                    $customfieldval = implode( ",", $get_user_signup_data[ $customfieldkey ] );     
                                                                }       
                                                                else{       
                                                                    $customfieldval = $get_user_signup_data[$customfieldkey][0];        
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty($customfieldval)){
                                                                $customfieldval = $this->removeHtmlOrScriptTag($customfieldval);
                                                                $exporttable .= $customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                            $tdcount++;
                                                        }
                                                    }
            
                                                }
                                            }
                                            if(!empty($duplicate_removed_signups)){
                                                foreach($duplicate_removed_signups as $signup_ids){
                                                    $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                                    $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                                    if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                                        foreach($signup_custom_fileds as $signup_custom_filed)
                                                        {
                                                            $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                                            if($signup_type == "text-area"){
                                                                $signup_type = "textarea";
                                                            }
            
                                                            if($signup_type == "drop-down"){
                                                                $signup_type = "select";
                                                            }
                                                            $signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$signupid;
                                                            $signup_customfieldval = "";
                                                            if(array_key_exists($signup_customfieldkey, $get_user_signup_data)){    
                                                                if($signup_type == "checkbox"){     
                                                                    $signup_customfieldval = implode(",", $get_user_signup_data[$signup_customfieldkey]);       
                                                                }       
                                                                else{       
                                                                    $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey][0];      
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty($signup_customfieldval)){
                                                                $signup_customfieldval = $this->removeHtmlOrScriptTag($signup_customfieldval);
                                                                $exporttable .= $signup_customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                        }
                                                    }
                                                }
                                            }
                                            $exporttable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                           
                                            
                                        }                                
    
                                    }
                                    elseif(!empty($task_id)){
    
                                        if($tid == $task_id){  
                                            
                                            $exporttable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                            $exporttable .= '<td class="column-lname">'.$last_name.'</td>';
                                            $exporttable .=  '<td class="column-email">'.$user_email.'</td>';
                                            $exporttable .=  '<td class="column-signup">'.get_the_title($signupid).'</td>';
                                            $exporttable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                            $exporttable .= '<td class="column-task-slot-date">'.$task_date.'</td>';
                                            $exporttable .= '<td class="column-task-slot-time">'.$task_time.'</td>';
                                            if(!empty($duplicate_removed)){
                                                foreach($duplicate_removed as $task_slot){
                                                    $ctid = "";                                            
                                                    $taskid_explode = explode("_", $task_slot);
                                                    $ctid = $taskid_explode[0];
                                                    
                                                    $cpt_custom_fileds =  get_post_meta( $ctid, "single_task_custom_fields", true );
                                                    if(!empty($cpt_custom_fileds)){
                                                        foreach($cpt_custom_fileds as $cpt_custom_filed){   
                                                            $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                            if($type == "text-area"){
                                                                $type = "textarea";
                                                            }
            
                                                            if($type == "drop-down"){
                                                                $type = "select";
                                                            }
                                                            $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$task_slot."_".$signupid."_".$m;
                                                            $customfieldval = "";
                                                            if(array_key_exists($customfieldkey, $get_user_signup_data)){   
                                                                if($type == "checkbox"){        
                                                                    $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);     
                                                                }       
                                                                else{       
                                                                    $customfieldval = $get_user_signup_data[$customfieldkey][0];        
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty($customfieldval)){
                                                                $customfieldval = $this->removeHtmlOrScriptTag($customfieldval);
                                                                $exporttable .= $customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                            $tdcount++;
                                                        }
                                                    }
            
                                                }
                                            }
                                            if(!empty($duplicate_removed_signups)){
                                                foreach($duplicate_removed_signups as $signup_ids){
                                                    $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                                    $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                                    if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                                        foreach($signup_custom_fileds as $signup_custom_filed)
                                                        {
                                                            $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                                            if($signup_type == "text-area"){
                                                                $signup_type = "textarea";
                                                            }
            
                                                            if($signup_type == "drop-down"){
                                                                $signup_type = "select";
                                                            }
                                                            $signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$signupid;
                                                            $signup_customfieldval = "";
                                                            if(array_key_exists($signup_customfieldkey, $get_user_signup_data)){    
                                                                if($signup_type == "checkbox"){     
                                                                    $signup_customfieldval = implode(",", $get_user_signup_data[$signup_customfieldkey]);       
                                                                }       
                                                                else{       
                                                                    $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey][0];      
                                                                } 
                                                            }
                                                            $exporttable .= '<td>';
                                                            if(!empty($signup_customfieldval)){
                                                                $signup_customfieldval = $this->removeHtmlOrScriptTag($signup_customfieldval);
                                                                $exporttable .= $signup_customfieldval;
                                                            }
                                                            else{
                                                                $exporttable .= '-';
                                                            }
                                                            
                                                            $exporttable .= '</td>';
                                                        }
                                                    }
                                                }
                                            }
                                            $exporttable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                            
                                        }                              
                                    }
                                    else{                                
                                        $exporttable .= '<tr><td class="column-fname">'.$first_name.'</td>';
                                        $exporttable .= '<td class="column-lname">'.$last_name.'</td>';
                                        $exporttable .=  '<td class="column-email">'.$user_email.'</td>';
                                        $exporttable .=  '<td class="column-signup">'.get_the_title($signupid).'</td>';
                                        $exporttable .=  '<td class="column-task-slot">'.$tasktitle.'</td>';
                                        $exporttable .= '<td class="column-task-slot-date">'.$task_date.'</td>';
                                        $exporttable .= '<td class="column-task-slot-time">'.$task_time.'</td>';
                                        if(!empty($duplicate_removed)){
                                            foreach($duplicate_removed as $task_slot){
                                                $ctid = "";                                            
                                                $taskid_explode = explode("_", $task_slot);
                                                $ctid = $taskid_explode[0];
                                                
                                                $cpt_custom_fileds =  get_post_meta( $ctid, "single_task_custom_fields", true );
                                                if(!empty($cpt_custom_fileds)){
                                                    foreach($cpt_custom_fileds as $cpt_custom_filed){   
                                                        $type = get_post_meta($cpt_custom_filed,"pto_field_type",true);
                                                        if($type == "text-area"){
                                                            $type = "textarea";
                                                        }
        
                                                        if($type == "drop-down"){
                                                            $type = "select";
                                                        }
                                                        $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$task_slot."_".$signupid."_".$m;
                                                        $customfieldval = "";
                                                        if(array_key_exists($customfieldkey, $get_user_signup_data)){   
                                                            if($type == "checkbox"){        
                                                                $customfieldval = implode(",", $get_user_signup_data[$customfieldkey]);     
                                                            }       
                                                            else{       
                                                                $customfieldval = $get_user_signup_data[$customfieldkey][0];        
                                                            } 
                                                        }
                                                        $exporttable .= '<td>';
                                                        if(!empty($customfieldval)){
                                                            $customfieldval = $this->removeHtmlOrScriptTag($customfieldval);
                                                            $exporttable .= $customfieldval;
                                                        }
                                                        else{
                                                            $exporttable .= '-';
                                                        }
                                                        
                                                        $exporttable .= '</td>';
                                                        $tdcount++;
                                                    }
                                                }
        
                                            }
                                        }
                                        if(!empty($duplicate_removed_signups)){
                                            foreach($duplicate_removed_signups as $signup_ids){
                                                $signup_custom_fileds =  get_post_meta( $signup_ids, "single_task_custom_fields_checkout", true );
                                                $checkout_fields_sign_up = get_post_meta( $signup_ids, "checkout_fields_sign_up", true );
                                                if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                                    foreach($signup_custom_fileds as $signup_custom_filed)
                                                    {
                                                        $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                                        if($signup_type == "text-area"){
                                                            $signup_type = "textarea";
                                                        }
        
                                                        if($signup_type == "drop-down"){
                                                            $signup_type = "select";
                                                        }
                                                        $signup_customfieldkey = "signup_".$signup_type."_".$signup_custom_filed."_".$signupid;
                                                        $signup_customfieldval = "";
                                                        if(array_key_exists($signup_customfieldkey, $get_user_signup_data)){    
                                                            if($signup_type == "checkbox"){     
                                                                $signup_customfieldval = implode(",", $get_user_signup_data[$signup_customfieldkey]);       
                                                            }       
                                                            else{       
                                                                $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey][0];      
                                                            } 
                                                        }
                                                        $exporttable .= '<td>';
                                                        if(!empty($signup_customfieldval)){
                                                            $signup_customfieldval = $this->removeHtmlOrScriptTag($signup_customfieldval);
                                                            $exporttable .= $signup_customfieldval;
                                                        }
                                                        else{
                                                            $exporttable .= '-';
                                                        }
                                                        
                                                        $exporttable .= '</td>';
                                                    }
                                                }
                                            }
                                        }
                                        $exporttable .=  '<td class="column-date">'.$checkout_date.'</td></tr>';
                                        
                                    }
    
                                    // $exporttable .= '</tr>';
    
                                }
    
                            }
    
                        }
    
                    }
    
                endforeach;
                $exporttable .= '</tbody>';
            } 
            if( empty(  $exporttable ) ){
                $exporttable .= 'No data found';
            }
        }
        else{
            $exporttable .= 'No data found';
        }
        //print_r($duplicate_removed);
        //print_r($duplicate_removed_signups);
        $data['exporttable'] =  $exporttable;
        if( empty( $nomaltable ) ){
             $nomaltable .= '<tr><td colspan="">No data found</td><td></td><td></td><td></td><td></td><td></td></tr>';
        }
        $data['nomaltable'] =  $nomaltable;
        echo json_encode($data);
        die();
    }
    
    /**
    * Get tasks of selected sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_tasks_of_signup() {  
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST["signup_id"])){
            $signup_id = intval( $_POST["signup_id"] );
        }   
        if(!empty($signup_id)){
            $get_task_slots = get_post_meta( $signup_id, "pto_signups_task_slots", true );             
            ?>
            <option value="">All Tasks/Slots</option>
            <?php
            if(!empty($get_task_slots)){
                foreach($get_task_slots as $get_task_slot)
                {
                    $post_details = get_post( $get_task_slot );
                    ?>
                    <option value="<?php echo intval($get_task_slot); ?>"><?php esc_html_e($post_details->post_title); ?></option>
                    <?php
                }
            }                           
        }
        else{
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
                <option value="">All Tasks/Slots</option>
                <?php
                while ( $result->have_posts() ) {
                    $result->the_post();
                    $post_id =  get_the_ID();
                    $posttitle = get_the_title($post_id);
                    ?>
                    <option value="<?php echo intval($post_id); ?>"><?php esc_html_e($posttitle); ?></option>
                    <?php
                }                   
            }
        }
        die(); 
    }
    /**
    * To save notified admin user for a sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_save_notify_admin_user() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $user_id = "";
        $signup_id = "";    
        if(isset($_POST["user_id"]) && isset($_POST["signup_id"])){
            $user_id = intval( $_POST["user_id"] );
            $signup_id = intval( $_POST["signup_id"] ); 
            if(!empty($user_id) && !empty($signup_id)){           
                $notified_users = get_post_meta($signup_id, "pto_signup_notified_users", true);
                if(!empty($notified_users)){                
                    $users = array();
                    $users[] = $user_id;
                    $notified_users = array_merge($notified_users, $users);
                    
                    update_post_meta($signup_id, "pto_signup_notified_users", $notified_users);                
                }
                else{                
                    $users = array();
                    $users[] = $user_id;
                    update_post_meta($signup_id, "pto_signup_notified_users", $users);               
                }
            } 
        } 
        die();
    }
    /**
    * To remove notified admin user for a sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_remove_notify_admin_user() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $user_id = "";
        $signup_id = "";    
        if(isset($_POST["user_id"]) && isset($_POST["signup_id"])){
            $user_id = intval( $_POST["user_id"] );
            $signup_id = intval( $_POST["signup_id"] ); 
            if(!empty($user_id) && !empty($signup_id)){           
                $notified_users = get_post_meta($signup_id, "pto_signup_notified_users", true);
                if(!empty($notified_users)){                
                    $find_id = array_search($user_id, $notified_users);
                    unset($notified_users[$find_id]);                   
                    update_post_meta($signup_id, "pto_signup_notified_users", $notified_users);                
                }                
            } 
        } 
        die();
    }
    /**
    * to hide start time on task 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_occurrence_not_specific() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $post_id = "";    
        if(isset($_POST["post_id"])){
            $post_id = intval( $_POST["post_id"] );
        }   
        if(!empty($post_id)){
            if(isset($_POST["occurrence_value"]) == "occurrence-not-specific"){
                update_post_meta($post_id, "pto_signup_occur_not_specific", sanitize_text_field($_POST["occurrence_value"]));
            }
            else{
                update_post_meta($post_id, "pto_signup_occur_not_specific", sanitize_text_field($_POST["occurrence_value"]));
            }
        } 
    }
    /**
    * Top volunteers sorting 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_volunteers_sorting() {        
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $sortval = "";    
        if(isset($_POST["sortval"])){
            $sortval = sanitize_text_field( $_POST["sortval"] );
        } 
        global $wpdb;
        $table_name = $wpdb->prefix . "signup_orders";  
        $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " GROUP BY user_id");
        if(!empty($all_user_posts)){ 
            $inc = 1;                
            $volunteers = array();
            foreach($all_user_posts as $key => $post):                    
                $user_id = $post->user_id;
                $user_info = get_userdata($user_id);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;
                $display_name = $user_info->display_name; 
                $user_name = "";
                if(!empty($first_name) && !empty($last_name)){
                    $user_name = $first_name ." ".$last_name;
                }
                else{
                    $user_name = $display_name;
                }
                        
                $this_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE user_id =" .intval($user_id));
                if(!empty($this_user_posts)){
                    $singups = array();
                    $tasks = array();                                        
                    $singupkey = 0;
                    $taskkey = 0;                                        
                    $hourpoint = 0;
                    foreach($this_user_posts as $keys => $posts):                                            
                        $get_user_order_info = unserialize($posts->order_info);                                            
                        if(!empty($get_user_order_info)){
                            $total_signup = count($get_user_order_info["signup_id"]);
                            for($i=0; $i<$total_signup; $i++){
                                $signupid = $get_user_order_info["signup_id"][$i];
                                $singups[$singupkey] = $signupid;
                                $singupkey++;
                                $total_task = count($get_user_order_info["task_id".$signupid]);
                                for($j=0; $j<$total_task; $j++){ 
                                    $taskid = $get_user_order_info["task_id".$signupid][$j];
                                    $tasks[$taskkey] = $taskid;
                                    $taskkey++;
                                    
                                    if(array_key_exists( "task_hours_points".$taskid, $get_user_order_info )){
                                        if(!empty($get_user_order_info["task_hours_points".$taskid][0])){
                                            $hourpoint += $get_user_order_info["task_hours_points".$taskid][0]; 
                                        }                                                                                                                        
                                    }
                                }
                            }                                                
                        }
                        
                    endforeach;
                    
                    $volunteers[$user_id]['user_name'] = $user_name;
                    $volunteers[$user_id]['hours_points'] = $hourpoint;
                    $volunteers[$user_id]['signups'] = count(array_unique($singups));
                    $volunteers[$user_id]['tasks'] = count(array_unique($tasks));
                }                                     
                $inc++;    
                
            endforeach;  
            if( $sortval == "Hours" ) {
                uasort( $volunteers, function( $a, $b ) { return $b['hours_points'] <=> $a['hours_points']; } );
            }                       
            if($sortval == "Tasks"){
                uasort( $volunteers, function( $a, $b ) { return $b['tasks'] <=> $a['tasks']; } );
            }                    
            if($sortval == "Signup"){
                uasort( $volunteers, function( $a, $b ) { return $b['signups'] <=> $a['signups']; } );
            }    
        }
        if(!empty($volunteers)){ 
            
            $inc = 1;                        
            foreach($volunteers as $key => $vol): 
                //print_r($vol);   
                //if($inc <= 5){               
                ?>
                    <tr class="pto-singup-volunteer-block">
                        <td class="pto-signup-volunteer-number">
                            <?php echo intval($inc); ?>
                        </td>
                        <td class="pto-signup-volunteer-name">
                            <?php esc_html_e($vol["user_name"]); ?>
                        </td>
                                                            
                        <td class="pto-signup-volunteer-tasks">
                            <?php esc_html_e($vol["tasks"]);?> Tasks/Slots
                        </td>
                        <td class="pto-signup-volunteer-signups">
                            <?php esc_html_e($vol["signups"]);?> Sign Ups
                        </td> 
                    </tr>
                <?php //}
                $inc++;                           
            endforeach;  
                   
        }
        die();
    }
    /**
    * Accept request
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_accept_request() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST["signup_id"]) && isset($_POST["user_id"])){
            $signup_id = intval( $_POST['signup_id'] );
            $user_id = intval( $_POST["user_id"] );
            $get_user_post = get_user_meta( $user_id, 'assign_post_key', true );
            if(!empty($get_user_post))
            {
                $temp_arr = $get_user_post;
                $temp_arr[$signup_id] = $signup_id;
            }else{ 
                $temp_arr[$signup_id] = $signup_id;
            }  
            update_user_meta( $user_id, 'assign_post_key', $temp_arr );
            $all_users = explode(" ", $user_id);
            $post_meta = get_post_meta( $signup_id, "pto_assign_user_administrator", true );
            if(!empty($post_meta)){                
                $total_arr = array();                
                $total_arr =  array_merge($all_users, $post_meta);                
                update_post_meta( $signup_id, "pto_assign_user_administrator", $total_arr );
            }else{
                $total_arr = array();                
                $total_arr = $all_users;                
                update_post_meta( $signup_id, "pto_assign_user_administrator", $total_arr );
            }
            $get_user_post = get_user_meta( $user_id, 'pto_signup_request_id', true );
            $find_pid = array_search($signup_id, $get_user_post);
            unset($get_user_post[$find_pid]);
            update_user_meta($user_id, "pto_signup_request_id", $get_user_post);    
            $user_info = get_userdata($user_id);
            $to = $user_info->user_email; 
            $fullname = $user_info->first_name ." ". $user_info->last_name;  
            $fname = $user_info->first_name;
            $lname = $user_info->last_name;   
            if(empty($fname)){
                $fname = $user_info->display_name;
            } 
            if(empty($lname)){
                $lname = $user_info->display_name;
            }
            $cur_user_id = get_current_user_id();
            $cur_user_obj = get_user_by('id', $cur_user_id);
            $admin_name =  $cur_user_obj->display_name;     
            $signup_name = "<a href='".get_the_permalink($signup_id)."' target='_blank'>".get_the_title($signup_id)."</a>";
            $request_access_accept = get_option('request_access_accept');
            $arra = array("/{{Full Name}}/", "/{{Admin Name}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/"); 
            $arra2 = array($fullname, $admin_name, $signup_name, $fname, $lname);
            $mail =  preg_replace($arra,$arra2,$request_access_accept);
            $subject = 'Your request approved to access the signup.';
            $body = $mail;
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail( $to, $subject, $body, $headers );
        }
        die();
    }
    /**
    * Decline request
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_decline_request() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST["signup_id"]) && isset($_POST["user_id"])){
            $signup_id = intval( $_POST['signup_id'] );
            $user_id = intval( $_POST["user_id"] );
            $get_user_post = get_user_meta( $user_id, 'pto_signup_request_id', true );
            $find_pid = array_search( $signup_id, $get_user_post );
            unset($get_user_post[$find_pid]);
            update_user_meta($user_id, "pto_signup_request_id", $get_user_post);  
            $user_info = get_userdata($user_id);
            $to = $user_info->user_email; 
            $fullname = $user_info->first_name ." ". $user_info->last_name;  
            $fname = $user_info->first_name;
            $lname = $user_info->last_name;
            if(empty($fname)){
                $fname = $user_info->display_name;
            } 
            if(empty($lname)){
                $lname = $user_info->display_name;
            }     
            $cur_user_id = get_current_user_id();
            $cur_user_obj = get_user_by('id', $cur_user_id);
            $admin_name =  $cur_user_obj->display_name;     
            $signup_name = "<a href='".get_the_permalink($signup_id)."' target='_blank'>".get_the_title($signup_id)."</a>";
            $request_access_decline = get_option('request_access_decline');
            $arra = array("/{{Full Name}}/", "/{{Admin Name}}/", "/{{Signup Name}}/", "/{{First Name}}/", "/{{Last Name}}/"); 
            $arra2 = array($fullname, $admin_name, $signup_name, $fname, $lname);
            $mail =  preg_replace($arra,$arra2,$request_access_decline);
            $subject = 'Your request declined to access the signup.';
            $body = $mail;
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail( $to, $subject, $body, $headers );
        }
        die();
    }
    /**
    * Give edit access to user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_usercap_add() {
        $user = wp_get_current_user();
        if(in_array("own_sign_up",$user->roles)){
            $user->add_cap( 'edit_published_posts');            
        }
    }
    
    /**
    * Sign up registration save
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_registration_save( $user_id ) {         
        $user = get_user_by('id', $user_id);
        if ( in_array( "own_sign_up", $user->roles ) ) {
            $user->add_cap( 'edit_published_posts');
            $user->add_cap( 'create_posts');
            $user->add_cap( 'edit_posts');
            $user->add_cap( 'edit_others_posts');
            $user->add_cap( 'publish_posts');
            $user->add_cap( 'manage_categories');
            $user->add_cap( 'delete_published_posts' );
            $user->add_cap( 'delete_posts' ); 
            $user->add_cap( 'manage_options' ); 
        }             
    }
    /**
    * Hide admin sidebar
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_hide_sidebar_menu_other_role() {    
        global $current_user;
        $user_roles = $current_user->roles;
        $match = 0;
        foreach( $user_roles as $roles ) { 
            if ( $roles == "sign_up_plugin_administrators" || $roles == "administrator" || $roles == "own_sign_up" ) {
                $match = 1;    
            }    
        }
       
        if($match == 0){
            remove_menu_page( 'edit.php' ); // Posts
            remove_menu_page( 'edit.php?post_type=pto-signup' ); // Projects custom Posts
            remove_menu_page( 'index.php' ); // Posts
            remove_menu_page( 'upload.php' ); // Media
            remove_menu_page( 'link-manager.php' ); // Links
            remove_menu_page( 'edit-comments.php' ); // Comments
            remove_menu_page( 'edit.php?post_type=page' ); // Pages
            remove_menu_page( 'plugins.php' ); // Plugins
            remove_menu_page( 'themes.php' ); // Appearance
            remove_menu_page( 'users.php' ); // Users
            remove_menu_page( 'tools.php'); // Tools
            remove_menu_page( 'options-general.php' ); // Settings
        }     
        if(in_array("sign_up_plugin_administrators", $user_roles) && in_array("own_sign_up", $user_roles)){
            
            //remove_menu_page( 'edit.php' ); // Posts
            //remove_menu_page( 'edit.php?post_type=pto-signup' ); // Projects custom Posts
            remove_menu_page( 'index.php' ); // Posts
            //remove_menu_page( 'upload.php' ); // Media
            remove_menu_page( 'link-manager.php' ); // Links
            remove_menu_page( 'edit-comments.php' ); // Comments
            remove_menu_page( 'edit.php?post_type=page' ); // Pages
            //remove_menu_page( 'plugins.php' ); // Plugins
            remove_menu_page( 'themes.php' ); // Appearance
            //remove_menu_page( 'users.php' ); // Users
            remove_menu_page( 'tools.php'); // Tools
            remove_menu_page( 'options-general.php' ); // Settings            
        }
        elseif(in_array("sign_up_plugin_administrators", $user_roles)){
            //remove_menu_page( 'edit.php' ); // Posts
            //remove_menu_page( 'edit.php?post_type=pto-signup' ); // Projects custom Posts
            remove_menu_page( 'index.php' ); // Posts
            //remove_menu_page( 'upload.php' ); // Media
            remove_menu_page( 'link-manager.php' ); // Links
            remove_menu_page( 'edit-comments.php' ); // Comments
            remove_menu_page( 'edit.php?post_type=page' ); // Pages
            //remove_menu_page( 'plugins.php' ); // Plugins
            remove_menu_page( 'themes.php' ); // Appearance
            //remove_menu_page( 'users.php' ); // Users
            remove_menu_page( 'tools.php'); // Tools
            remove_menu_page( 'options-general.php' ); // Settings           
        }
        elseif(in_array("own_sign_up", $user_roles)){           
          
            remove_menu_page( 'edit.php' ); // Posts
            //remove_menu_page( 'edit.php?post_type=pto-signup' ); // Projects custom Posts
            remove_menu_page( 'index.php' ); // Posts
            //remove_menu_page( 'upload.php' ); // Media
            remove_menu_page( 'link-manager.php' ); // Links
            remove_menu_page( 'edit-comments.php' ); // Comments
            remove_menu_page( 'edit.php?post_type=page' ); // Pages
            remove_menu_page( 'plugins.php' ); // Plugins
            remove_menu_page( 'themes.php' ); // Appearance
            remove_menu_page( 'users.php' ); // Users
            remove_menu_page( 'tools.php'); // Tools
            remove_menu_page( 'options-general.php' ); // Settings
            remove_submenu_page( 'edit.php?post_type=pto-signup', 'signup_request' );
            remove_submenu_page( 'edit.php?post_type=pto-signup', 'signup_reports' );
            remove_submenu_page( 'edit.php?post_type=pto-signup', 'signup_setting' );  
            remove_submenu_page( 'edit.php?post_type=pto-signup', 'edit-tags.php?taxonomy=TaskCategories&post_type=tasks-signup' ); 
        }  
        else{} 
    }
    /**
    * Post access denied for sign up user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_post_access_denied() {
        $c_user_id = get_current_user_id();
        $user = get_userdata( $c_user_id );
        $user_roles = $user->roles;
        $crole = array();
        foreach($user_roles as $key => $value){
            $crole[$value] = $value;
        }
        //print_r($crole);
        $type = "";
        if(isset($_GET['post_type']))
        {
            $type = sanitize_text_field($_GET['post_type']);
        }
        if($type == "pto-signup"){
            if(array_key_exists("own_sign_up", $crole) || array_key_exists("administrator", $crole) || array_key_exists("sign_up_plugin_administrators", $crole)){
            }
            else{
               if( !current_user_can('manage_network') ){
                    esc_html_e("Sorry, you are not allowed to edit this item.");
                    die();
            	}
            }
        }
        if(isset($_GET['post']) && isset($_GET['action'])){
            $post_id = intval($_GET['post']);
            $action = sanitize_text_field($_GET['action']);
            if(isset($_GET['postsignup'])){
                $postsignup = intval($_GET['postsignup']);
                $get_task_slots = get_post_meta($postsignup,"pto_signups_task_slots",true);
                //print_r($get_task_slots);
            }
            
            $post_type = get_post_type( $post_id );            
            
            if($action == "edit" && $post_type == "pto-signup"){                
                $author_id = get_post_field( 'post_author', $post_id );
                $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true);
                if(empty($get_user_req_post)){ 
                    $get_user_req_post = array();
                    //print_r($get_user_req_post);
                }
                if(empty($get_task_slots)){ 
                    $get_task_slots = array();
                }                
                if(($c_user_id == $author_id ) || (in_array($post_id , $get_task_slots)) || (in_array($c_user_id , $get_user_req_post)) || array_key_exists("administrator", $crole) || array_key_exists("sign_up_plugin_administrators", $crole) || $crole == "sign_up_plugin_administrators" || $crole == "administrator"){
                    $user->add_cap( 'edit_posts' ); 
                    $user->add_cap( 'edit_others_posts' );
                    $user->add_cap( 'publish_posts' );
                    $user->add_cap( 'manage_categories' );
                    $user->add_cap( 'edit_published_posts' );
                    $user->add_cap( 'manage_options' );
                }else{
                	if( !current_user_can('manage_network') ){
	                    esc_html_e("Sorry, you are not allowed to edit this item.");
	                    die();
                	}
                }
            }            
        } 
        if(isset($_GET['page']) && isset($_GET['sign_ups'])){
            $mpage = sanitize_text_field($_GET['page']);
            $post_id = intval($_GET['sign_ups']);
            if(!empty($mpage) && !empty($post_id)){
                if($mpage == "managevolunteer"){                    
                    $author_id = get_post_field( 'post_author', $post_id );
                    $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true);
                    if(empty($get_user_req_post)){ 
                        $get_user_req_post = array();                   
                    }
                    if($c_user_id == $author_id || in_array($c_user_id , $get_user_req_post) || in_array("sign_up_plugin_administrators", $user_roles)) {
                        $user->add_cap( 'manage_options' );
                    }
                    //die();
                }
            }
        }        
    }
    /**
    * View receipt manage volunteer
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_manage_volunteer_view_receipt() {
        if(isset($_POST["orderid"]) && isset($_POST["taskid"])) {
            $orderid = intval( $_POST["orderid"] );
            $taskid = sanitize_text_field( $_POST["taskid"] );
            $cnum = "";
            $userid = 0;
            if(isset($_POST["cnum"])){
                $cnum = intval( $_POST["cnum"] );
            }
            global $wpdb;
            $table_name = $wpdb->prefix . "signup_orders";
            $signupdate = "";
            $get_user_signup_data = "";
            $all_user_posts = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE ID = ".intval($orderid) );
            if(!empty($all_user_posts)){
                foreach($all_user_posts as $userkey => $post){
                    $get_user_signup_data = unserialize($post->order_info);
                    $signupdate = $post->checkout_date;
                    $userid = $post->user_id;
                }
                $signupid = $get_user_signup_data["signup_id"][0];
                $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
                $pto_sign_up_occurrence =  get_post_meta($signupid, "pto_sign_up_occurrence", true);
                $tid = "";
                $sdate = "";
               
                if(array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence )){
                    $taskid_explode = explode("_", $taskid);
                    $tid = $taskid_explode[0];
                    $sdate = $taskid_explode[1];
                }
                else{
                    $tid = $taskid;
                }

                $saved_dates = get_post_meta($tid, "pto_signup_task_edit_single".$sdate, true);
                $desc = get_post_meta( $tid, "tasks_comp_desc", true );
                if(!empty($saved_dates)){
                    $desc = $saved_dates["tasks_comp_desc"];
                }
                $get_filed = get_post_meta( $tid, "single_tasks_advance_options", true );
                $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                $signup_custom_fileds =  get_post_meta( $signupid, "single_task_custom_fields_checkout", true );
                $checkout_fields_sign_up = get_post_meta( $signupid, "checkout_fields_sign_up", true );                
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
                
                if(array_key_exists("shift", $get_filed)){
                    $shift_meta = $get_filed["shift"];
                    $shift_min = $shift_meta['how-long-shift'];
                    $add_mins  = $shift_min * 60;
                    $shifttimes = explode(",", $tasktime);
                    for($st=0; $st<count($shifttimes); $st++){
                        if(!empty($shifttimes[$st])){
                            $shift_endtime = date ("h:i A", (strtotime($shifttimes[$st]) + $add_mins));
                            $shifttimes[$st] = $shifttimes[$st] . "-" .$shift_endtime;
                        }
                    }
                    $emptyRemoved = array_filter($shifttimes);
                    //print_r($emptyRemoved);
                    $tasktime = implode(", ", $emptyRemoved);
                }
                ?>
                    <div class="pto-modal-container-header">
                        <span>Signed Up: <?php esc_html_e($signupdate); ?></span>                        
                        <span onclick="jQuery('#view-receipt-manage-volunteers').removeClass('pto-modal-open');" class="w3-button w3-display-topright">Close</span>
                    </div>
                    <div class="pto-modal-container">
                        <div class="pto-managev-content">               
                            <div class="pto-managev-left">
                                <div class="pto-managev-signup-name">
                                    <?php esc_html_e(get_the_title($signupid)); ?>
                                </div>
                                <div class="pto-managev-task-name">
                                    <?php 
                                        if(!empty($saved_dates)){
                                            esc_html_e($saved_dates["post_title"]);
                                        }
                                        else{
                                            esc_html_e(get_the_title($tid));  
                                        }
                                     ?>
                                </div>
                                <div class="pto-managev-task-datetime">

                                    <?php  if(!empty($taskdate)){ ?> Task Due Date: <?php esc_html_e($taskdate); ?> <?php } 
                                    if(!empty($tasktime)){ 
                                        ?> Time: 
                                        <?php
                                        if(array_key_exists("shift",$get_filed)){
                                            $shifttime = explode(",", $tasktime);
                                            esc_html_e($shifttime[$cnum]);
                                        }
                                        else{
                                            esc_html_e($tasktime);
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="pto-managev-task-desc">
                                    <?php print_r($desc); ?>
                                </div>
                                <div class="pto-managev-task-custom-fields">
                                    <?php
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
                                                $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$cnum;
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
                                                    <span class="pto-custom-field-item">
                                                        <strong><?php echo esc_html($custom_field_title); ?> :</strong>
                                                        <?php echo esc_html($customfieldval); ?>
                                                        </span>
                                                    <?php
                                                    }
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="pto-managev-right">
                                <?php
                                    if(!empty($signup_custom_fileds) && !empty($checkout_fields_sign_up)){
                                        ?>
                                        <div class="pto-managev-signup-custom-fields">
                                            <?php
                                                $snc = 0;
                                                foreach($signup_custom_fileds as $signup_custom_filed)
                                                {
                                                    $signup_alternet_title = get_post_meta($signup_custom_filed,"pto_alternate_title",true);
                                                    //$signup_instruction = get_post_meta($signup_custom_filed,"instruction",true);
                                                    $signup_type = get_post_meta($signup_custom_filed,"pto_field_type",true);
                                                    //$signup_require = get_post_meta($signup_custom_filed,"pto_field_required",true);
                                                    $signup_custom_field_title = "";
                                                    
                                                    if(!empty($signup_alternet_title)){
                                                        $signup_custom_field_title = $signup_alternet_title;
                                                    }
                                                    else{
                                                        $signup_custom_field_title = get_the_title($signup_custom_filed);
                                                    }
                                                    $estype = $signup_type;
                                                    if($signup_type == "text-area"){
                                                        $estype = "textarea";
                                                    }
                                                    if($signup_type == "drop-down"){
                                                        $estype = "select";
                                                    }
                                                    
                                                    if(!empty($get_user_signup_data)){                                                                    
                                                        $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed."_".$signupid;
                                                        
                                                        if(array_key_exists($signup_customfieldkey, $get_user_signup_data)){ 
                                                            if($signup_type == "checkbox"){                                                            
                                                                $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey];
                                                            } 
                                                            else{
                                                                $signup_customfieldval = $get_user_signup_data[$signup_customfieldkey][0];
                                                            }   
                                                        }
                                                    }
                                                    if(!empty($signup_customfieldval)){
                                                        if($signup_type == "checkbox"){
                                                            $signup_customfieldval = implode(",", $signup_customfieldval);
                                                        }  
                                                        $snc++;                                                                  
                                                        ?>
                                                        <span class="pto-custom-field-item">
                                                            <strong><?php echo esc_html($signup_custom_field_title); ?> :
                                                            </strong>
                                                            <?php echo esc_html($signup_customfieldval); ?>
                                                            </span>
                                                        <?php
                                                    }
                                                }
                                            ?>  
                                        </div>
                                        <?php
                                        if($snc != 0){
                                        ?>
                                        <div class="pto-managev-signup-info">
                                            Sign Up Custom Information
                                        </div>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                    <div class="pto-modal-footer">
                        <?php if(!empty($volunteer_after_sign_up)){ ?>
                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval($post->ID); ?>" class="pto-signup-resend-receipt button button-primary">Re-send receipt</button>
                        <?php } ?>
                        <a href="javascript:void(0)"  cnum="<?php echo intval($cnum); ?>" orderid="<?php echo intval($orderid); ?>" userid="<?php echo intval($userid); ?>" post-id="<?php esc_html_e($taskid); ?>" class="pto-signup-remove-from-signup delete_manage_user_volunters button button-primary" data-id="<?php esc_html_e("id"); ?>" date="<?php esc_html_e($taskdate); ?>">Remove From Sign Up</a>
                    </div>
                <?php
            }
        }
        die();
    }
    /**
    * Add in manage volunteer
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_single_user_add() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if($_POST)
        {
            global $wpdb;
            $today =  date("Y-m-d");
            $userid = intval( $_POST['user_id'] );
            if(isset($_POST['keyword'])){
                $keyword = sanitize_text_field( $_POST['keyword'] );
            }
            $myArray = array();
            parse_str($_POST['keyword'], $myArray);
            $get_user_signup_data_em = array();
            $get_user_signup_data = array();
            $table_name = $wpdb->prefix . "signup_orders";
            $new_myArray = array();
            $signupid = $myArray["signup_id"][0]; 
            $new_myArray["signup_id"][0] = $signupid;
            $total_task = count($myArray["task_id".$signupid]);
            $get_manage_volunters = get_post_meta( $signupid, "pto_get_manage_volunteers", true );
            $all_task_ids = array();
            for($j=0; $j<$total_task; $j++){ 
                $hours_points = array();
                $taskid = $myArray["task_id".$signupid][$j];
                $task_maxval = $myArray["pto_signup_task_max"][$j]; 
                $task_date = $myArray["task_date".$taskid][0];
                $task_time = $myArray["task_time".$taskid][0];
                $task_hours_points = get_post_meta( $taskid, "pto_sign_ups_hour_points", true );
                $new_myArray["task_id".$signupid][$j] = $taskid;
                $new_myArray["task_max".$taskid][0] = $task_maxval;
                $new_myArray["task_date".$taskid][0] = $task_date;
                $new_myArray["task_time".$taskid][0] = $task_time;
                $new_myArray["task_hours_points".$taskid][0] = $task_hours_points * $task_maxval;
                
                // to add shift time for task and user                         
                $shift_key = $userid;
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
                // to store max value of task for user 
                $max_task_signuped = array();
                $max_key = $signupid."_".$taskid;
                $max_task_signuped[$max_key] = $task_maxval;
                $get_max_user_task_signup = get_user_meta( $userid, 'max_user_task_signup', true );
                if(!empty($get_max_user_task_signup)){
                    $get_max_user_task_signup[$max_key] = $get_max_user_task_signup[$max_key] + $task_maxval;
                    update_user_meta( $userid, 'max_user_task_signup', $get_max_user_task_signup );
                }
                else{
                    update_user_meta( $userid, 'max_user_task_signup', $max_task_signuped );
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
                // to add task hours/points to user 
                if(!empty($myArray["task_hours_points".$taskid][0])){
                    $task_hours_points = $myArray["task_hours_points".$taskid][0];
                    $hours_points[$taskid] = $task_hours_points;
                    $get_user_task_hours = get_user_meta( $userid, 'user_task_hours_points', true );
                    if(!empty($get_user_task_hours)){
                        $get_user_task_hours[$taskid] = $get_user_task_hours[$taskid] + $task_hours_points;
                        update_user_meta( $userid, 'user_task_hours_points', $get_user_task_hours );
                    }
                    else{
                        update_user_meta( $userid, 'user_task_hours_points', $hours_points );
                    } 
                }
                // to add user to manage volunteers 
                if(!empty($get_manage_volunters)){
                    $get_manage_volunters[$userid][$taskid] = $taskid;
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
                    $old_data = $old_array[$userid];
                    if(empty($old_data))
                    {  
                        $old_array[$userid] = $arr;                        
                        update_post_meta($taskid, "pto_sign_up_selected_date_time", $old_array); 
                    }else{
                        
                        $new_arr = array_merge($old_data, $arr);
                        $n_arr[$userid] = $new_arr;
                        update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
                    }
                }else{
                    $arr = array();
                    $arr[$task_date] = array(
                        $task_date => $task_date,
                        $task_time => $task_time
                    );                            
                    $n_arr[$userid] = $arr;                                            
                    update_post_meta($taskid, "pto_sign_up_selected_date_time", $n_arr); 
                }
            }
            //$sql =  $wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) 
            //        VALUES (NULL, ".$userid.", ".$signupid.", '".serialize($new_myArray)."', '".$today."', 'on');" );
            
            $result = $wpdb->query($wpdb->prepare( "INSERT INTO " . $table_name . " (ID, user_id, signup_id, order_info, checkout_date, status) VALUES (NULL, ".intval($userid).", ".intval($signupid).", '".esc_sql(serialize($new_myArray))."', '".esc_sql($today)."', 'on');" ));
            if($result){
                esc_html_e("done");
            }
            else{
                esc_html_e("not done");
            }
            if(!empty($get_manage_volunters)){
                update_post_meta( $signupid, "pto_get_manage_volunteers", $get_manage_volunters );
            }
            else{
                $total_array = array();
                $total_array[$userid] = $all_task_ids;                        
                update_post_meta( $signupid, "pto_get_manage_volunteers", $total_array ); 
            }
            $cur_user_obj = get_user_by('id', $userid);
            $cuname = $cur_user_obj->display_name;
            // send "Receipt" to volunteer after they sign up 
            $volunteer_after_sign_up = get_post_meta($signupid, "volunteer_after_sign_up", true);
            $signuptitle = get_the_title($signupid);
            $to = $cur_user_obj->user_email;
            if(!empty($volunteer_after_sign_up)){
                $mailcontent = get_post_meta($signupid, "volunteer_after_setting", true);
                if(!empty($mailcontent)){
                    $arra = array("/{{UserName}}/", "/{{SignupName}}/");
                    $arra2 = array($cuname, $signuptitle);                                      
                    $mail = preg_replace($arra, $arra2, $mailcontent);
                    
                    $subject = 'Signup Success';
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
            $arra = array("/{{AdminName}}/", "/{{UserName}}/");
            $arra2 = array($admin_name, $cuname);                                      
            $mail = preg_replace($arra, $arra2, $administrators_notifcations);
            
            $subject = 'New Signup.';
            $body = $mail;                    
            $headers = array('Content-Type: text/html; charset=UTF-8');                    
            wp_mail( $to, $subject, $body, $headers );
            
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
                    wp_mail( $to, $subject, $body, $headers );
                }
            }
            esc_html_e("signup added");
        }
        die();
    }
    /**
    * Serach task manage volunteer
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_search_manage() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if($_POST) {
            if(isset($_POST['post_id'])) {
                $post_id = intval( $_POST['post_id'] );
                $key_search = sanitize_text_field( $_POST['key_search'] );
                $user_id = intval( $_POST['user_id'] ) ;
                $get_task_slots = get_post_meta( $post_id, "pto_signups_task_slots", true );
                $get_manage_volunters = get_post_meta( $post_id, "pto_get_manage_volunteers", true );            
                foreach( $get_task_slots as $get_task_slot ) {
                    $post_details = get_post( $get_task_slot );
                    $title =  $post_details->post_title;
                    if ( strstr( $title, $key_search ) ) {
                        ?>
                        <div class="task_name" id="<?php echo intval($post_details->ID); ?>"><?php esc_html_e($title);  ?></div>
                        <?php
                    } 
                    //}                
                }
            } 
        }
        die();
    }
    /**
    * SIgn up save post
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_page_post_save() {
        if(isset($_POST['opener-window'])) {
             wp_enqueue_script("jquery-ui-core");              
            ?>
           
            <script type="text/javascript">
                opener.get_page_list();
                window.close();
            </script>
            <?php
            die();
        }        
    }
    /**
    * Editor content add in page
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_t5_preset_editor_content( $content, $post ) {
        if($post->post_type == "page")
        {
            if(isset($_GET['pto-display-all-sing-ups']))
            {
                return '[pto_signup_all_listing]';
            }
            if(isset($_GET['pto-volunteers-sign-ups']))
            {
                return '[pto_signup_my_history]';
            }
            if(isset($_GET['pto-checkout-sign-ups']))
            {
                return '[pto_signup_checkout]';
            }
            if(isset($_GET['pto-post-sign-thank-you']))
            {
                return '[pto_signup_thank_you]';
            }
        }       
        return $content;
    }
    /**
    * Wp editor add menu
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_owt_attach_custom_tinymce_buttons( $buttons ) {
        if (!in_array("editor_console", $buttons)){
             $buttons[] = "editor_console";
        }
        if (!in_array("editor_alert", $buttons)){
             $buttons[] = "editor_alert";
        }
        if (!in_array("editor_popup", $buttons)){
             $buttons[] = "editor_popup";
        }
        if (!in_array("editor_dropdown", $buttons)){
             $buttons[] = "editor_dropdown";
        }  
        return $buttons;
    }
    /**
    * Attach custom button
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_owt_attach_fns_custom_buttons( $plugin_array ) {
        
        $plugin_array["mce_editor_js"] = PTO_SIGN_UP_PLUGIN_DIR ."/assets/js/editor.js";
        return $plugin_array;
    }
    
    /**
    * Add submenu page to sign up post
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_add_submenu_page_to_post_type() {        
        
        add_submenu_page('edit.php?post_type=pto-signup', __('Sign Ups Reports', 'rushhour') , __('Sign Ups Reports', 'rushhour') , 'manage_options', 'signup_reports', array(
            $this, 'pto_sign_up_reports_details'
        ));
        add_submenu_page('edit.php?post_type=pto-signup', __('Sign Ups Settings', 'rushhour') , __('Sign Ups Settings', 'rushhour') , 'manage_options', 'signup_setting', array(
            $this,
            'pto_sign_up_details'
        ));        

        add_menu_page( 
            __( 'Manage Volunteer', 'rushhour' ),
            __( 'Manage Volunteer', 'rushhour' ),
            'manage_options',
            'managevolunteer',
            array($this,'pto_sign_up_add_manage_volanteers')
        );
    }
    /**
    * Manage Volunteers
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_add_manage_volanteers() {
        include "pto_manage_volunteer/pto_manage_volunteer.php";
    }
    /**
    * Sign up details
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_details() {
        include "pto_sign_ups_project_tabs/pto_sign_ups_project_tabs.php";
    }
    /**
    * Sign up request details
    * @since    1.0.0
    * @access   public
    **/
   
    /**
    * Sign up report details
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_reports_details() {
        include "pto_sign_ups_project_tabs/pto_sign_ups_reports.php";
    }
    /**
    * Sign up add role
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_roles() {
        add_image_size( 'pto-signup-image', 1440, 432, true );
        /*  project manager role create */
        add_role('own_sign_up', 'Sign Up Users', array(
            'read'            => true, // Allows a user to read
            'create_posts'      => true, // Allows user to create new posts
            'edit_posts'        => true, // Allows user to edit their own posts
            'edit_others_posts' => true, // Allows user to edit others posts too
            'publish_posts' => true, // Allows the user to publish posts
            'manage_categories' => true, // Allows user to manage post categories
            'edit_published_posts' => true, // Allows user to edit published posts too             
            'delete_posts' => true, //Allows user to delete their own post            
        ));
        add_role(
            'sign_up_plugin_administrators', //  System name of the role.
            __( 'Sign Up Plugin Administrators'  ), // Display name of the role.
             array(
                'read'            => true, // Allows a user to read
                'create_posts'      => true, // Allows user to create new posts
                'edit_posts'        => true, // Allows user to edit their own posts
                'edit_others_posts' => true, // Allows user to edit others posts too
                'publish_posts' => true, // Allows the user to publish posts
                'manage_categories' => true, // Allows user to manage post categories
                'manage_options' => true, // Allows user to manage options
                'edit_published_posts' => true, // Allows user to edit published posts too
                'delete_posts' => true, //Allows user to delete their own post
            )
        );
    }
    /**
    * Search user for sign up admin
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_admin_user_search() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if($_POST)
        {
            if(isset($_POST['search_user']))
            {
                $user_type = sanitize_text_field( $_POST['user_type'] );
                $name = sanitize_text_field( $_POST['search_user'] ) . "*";
                $users_data = new \WP_User_Query(
                    array( 
                        'search' => $name
                    ) 
                );
                
                $users =$users_data->get_results();
                if( empty( $users ) ){
                    $users_data2 = new \WP_User_Query(
                        
                        array(
                            'meta_query' => array(
                            'relation' => 'OR',
                              array(
                                'key' => 'first_name',
                                'value' =>  sanitize_text_field($_POST['search_user']),
                                'compare' => 'LIKE'
                              ),
                            array(
                                'key' => 'last_name',
                                'value' => sanitize_text_field($_POST['search_user']),
                                'compare' => 'LIKE'
                              )
                            )
                          )
                         
                    ); 
                    $users = $users_data2->get_results();   

                }
                
                
                if($users != ""){                            
                    include "pto_sign_ups_project_tabs/pto_sign_ups_admin_search.php";
                }else{
                    esc_html_e("No User Found");
                }
            }
        }
        die();
    }
    /**
    * Add plugin admin role
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_new_users_add() {

        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST['ids'])){
            $ids = sanitize_text_field( $_POST['ids'] );
            $user_ids = explode(",", $ids);
            $user_type = sanitize_text_field( $_POST['user_type'] );
            if($user_type == 2)
            {
                foreach($user_ids as $users)
                {
                    $user = get_user_by('id', $users);
                    $user->add_role('sign_up_plugin_administrators');
                    $role = get_role( 'administrator' );
                    $this->pto_sign_up_user_mail_functionality( $users, "sign_up_plugin_administrators" );
                    // print_r($role);
                    foreach($role->capabilities as $key => $caps)
                    {
                        $user->add_cap( $key ); // admin cpability add
                    }             
                }
            } 
            else if($user_type == 1){
                foreach($user_ids as $users)
                {
                    $user = get_user_by('id', $users);
                    $user->add_role('own_sign_up');
                    $user->add_cap( 'create_posts');
                    $user->add_cap( 'edit_posts');
                    $user->add_cap( 'edit_others_posts');
                    $user->add_cap( 'publish_posts');
                    $user->add_cap( 'manage_categories');                    
                    $user->add_cap( 'edit_published_posts');
                    $user->add_cap( 'delete_published_posts' );
                    $user->add_cap( 'delete_posts' );
                    $this->pto_sign_up_user_mail_functionality( $users, "own_sign_up" );
                }
            }
        }
        include "pto_sign_ups_project_tabs/pto_sign_ups_admin_search_add.php";
    }
    /**
    * Remove sign up user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_new_users_remove() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if(isset($_POST))
        {
            if(isset($_POST['user_id']))
            {
                $user = get_user_by('id', intval($_POST['user_id']));
                $ids = intval( $_POST['type_id'] );
                if($ids == 2){
                    $user->remove_role('sign_up_plugin_administrators');
                    $role = get_role( 'administrator' );
                    foreach($role->capabilities as $key => $caps)
                    {
                        $user->remove_cap( $key ); // admin cpability add
                    } 
                    if(!empty($user->roles)){
                    }               
                    else{
                        $user->add_role('subscriber');
                    }
                }else{
                    $user->remove_role('own_sign_up');
                    //$user->remove_cap( 'add_own_sign_up');
                    $user->remove_cap( 'create_posts');
                    $user->remove_cap( 'edit_posts');
                    $user->remove_cap( 'edit_others_posts');
                    $user->remove_cap( 'publish_posts');
                    $user->remove_cap( 'manage_categories');                    
                    if(!empty($user->roles)){
                    }               
                    else{
                        $user->add_role('subscriber');
                    }
                }                
                $user_type = intval( $_POST['type_id'] );               
                include "pto_sign_ups_project_tabs/pto_sign_ups_admin_search_add.php";                  
            }
            die();
        }
    }
    /**
    * Remove some item from admin left side menu
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_remove_menu_items() {       
        remove_menu_page( 'edit.php?post_type=tasks-signup' );
        remove_menu_page( 'edit.php?post_type=pto-custom-fields' );
        remove_menu_page( 'managevolunteer' );      
    }
    /**
    * User invitation emails
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_user_mail_functionality( $user_id, $user_type ) {
        $user_data = get_user_by("id", $user_id);        
        $fullname = $user_data->first_name ." ". $user_data->last_name;
        $fname = $user_data->first_name;
        $lname = $user_data->last_name;
        if(empty($fname)){
            $fname = $user_info->display_name;
        } 
        if(empty($lname)){
            $lname = $user_info->display_name;
        }
        if($user_type  == "own_sign_up")
        {
            $ownsignup_invitation = get_option('ownsignup_invitation');
            $arra = array("/{{Full Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
            $arra2 = array($fullname, $fname, $lname);
            $subject = 'Signup Own User Invitation';            
            $mail = preg_replace($arra,$arra2,$ownsignup_invitation);
        }else if($user_type == "sign_up_plugin_administrators")
        {
            $administrators_invitation = get_option('administrators_invitation');
            $arra = array("/{{Full Name}}/", "/{{First Name}}/", "/{{Last Name}}/");
            $subject = 'Signup Administrator Invitation';
            $arra2 = array($fullname, $fname, $lname);
            $mail =  preg_replace($arra,$arra2,$administrators_invitation);
        }
        $to = $user_data->user_email;
        $body = $mail;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers );
    }
    /**
    * Color settings to default
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_set_default_color() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $color_arr = array(
            "pto-background-color" => "#0078FD",
            "pto-text-color" => "#ffffff",
            "pto-header-background" => "#0078FD",
            "pto-header-text-color" => "#ffffff",
            "pto-task-header-background-color" => "#0078FD",
            "pto-task-header-text-color"=> "#ffffff"
        );
        update_option( 'pto_color_sign_ups_setting', $color_arr );
    }
    /**
    * Widget settings to default
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_set_default_widget_setting() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $title_text_color = "#0078FD";
        $title_text_size = "18";
        $signup_title = "";
        $no_date_sign_ups = "";
        $repeating_sign_ups = "";
        $sortby_sing_ups = "sort_by_name";
        $sort_type = "sort_ASC";
        update_option( 'title_text_color', $title_text_color );
        update_option( 'title_text_size', $title_text_size );
        update_option( 'signup_title', $signup_title );
        update_option( 'no_date_sign_ups', $no_date_sign_ups );
        update_option( 'repeating_sign_ups', $repeating_sign_ups );
        update_option( 'sortby_sing_ups', $sortby_sing_ups );
        update_option( 'sort_type', $sort_type );
    }
    /**
    * Resend user invitation email
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_user_mail_resend_functionality() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $user_type = "";
        $user_id = "";
        if(isset($_POST['user_id'])){
            $user_id = intval( $_POST['user_id'] );
        }
        if(isset($_POST['user_type']))
        {
            $user_type = sanitize_text_field( $_POST['user_type'] );
        }      
        
        $user_data = get_user_by("id", $user_id);        
        $fname= get_user_meta( $user_id , "first_name"  , true );
        $lname= get_user_meta( $user_id , "last_name"  , true );
        if( empty( $fname )  && empty( $lname )){
            $fullname = $user_data->user_nicename;
        } else{
            $fullname = $fname . "  " . $lname;
        }
        


        if($user_type  == "own_sign_up")
        {
            $ownsignup_invitation = get_option('ownsignup_invitation');
            $arra = array("/{{First Name}}/");
            $arra2 = array($fullname);
            $subject = 'Signup Own User Invitation';
            $mail = preg_replace($arra, $arra2, $ownsignup_invitation);
        }else if($user_type == "sign_up_plugin_administrators")
        {
            $administrators_invitation = get_option('administrators_invitation');
            $arra = array("/{{First Name}}/");
            $subject = 'Signup Administrator Invitation';
            $arra2 = array($fullname);
            $mail =  preg_replace($arra, $arra2, $administrators_invitation);
            
        }
       
        $to = $user_data->user_email;
        $body = $mail;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        wp_mail( $to, $subject, $body, $headers );
    }
    /**
    * Get list of pages
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_page_all_list() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        include "pto_sign_ups_project_tabs/pto_sign_ups_page_listing.php";
        die();
    }
    /**
    * Custom single page for sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_custom_single_template_signup( $template ) {
        // For ID 93, load in file by using it's PATH (not URL)
        global $post;
        if ( !empty( $post ) ) {
            if ( $post->post_type == "pto-signup" ) {
                $file = PTO_SIGN_UP_DIR . 'single-signup.php';
                $template = $file;
            }
        }
        // ALWAYS return the $template, or *everything* will be blank.
        return $template;
    }
}