<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoSignupShortcode {
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
        /* Frontend shortcode */
        add_shortcode( 'pto_signup_all_listing', array( $this, 'pto_sign_up_all_listing_handler' ) ); 
        add_shortcode( 'pto_signup_my_listing', array( $this, 'pto_sign_up_my_listing_handler' ) );
        add_shortcode( 'pto_signup_my_history', array( $this, 'pto_sign_up_my_history_handler' ) );
        add_shortcode( 'pto_signup_checkout', array( $this, 'pto_sign_up_checkout_handler' ) );
        add_shortcode( 'pto_signup_thank_you', array( $this, 'pto_sign_up_thank_you_handler' ) );
        /* get all user from sign up settng tab */
        add_action( 'wp_ajax_nopriv_pto_sign_up_search', array( $this , 'pto_sign_up_search' ) );
        add_action( 'wp_ajax_pto_sign_up_search', array( $this , 'pto_sign_up_search' ) );
		
		/* pto sign ups request access */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_request_access', array( $this , 'pto_sign_up_request_access' ) );
        add_action( 'wp_ajax_pto_sing_up_request_access', array( $this , 'pto_sign_up_request_access' ) );   
        
        /* pto sign ups sorting */ 
        add_action( 'wp_ajax_nopriv_pto_sing_up_list_sorting', array( $this , 'pto_sign_up_list_sorting' ) );
        add_action( 'wp_ajax_pto_sing_up_list_sorting', array( $this , 'pto_sign_up_list_sorting' ) ); 
        /* session start */
        add_action( 'init', array( $this, 'pto_sign_up_init_session' ) );    
    } 
    /**
    * Session start
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_init_session() {
        if ( ! session_id() ) {
            session_start();
        }
    }
    
    /**
    * Shortcode for thank you page
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_thank_you_handler() { 
        ob_start();       
        if( array_key_exists( "pto_signup_tasks_cart", $_SESSION ) ) {
            unset( $_SESSION['pto_signup_tasks_cart'] );
        }
        include "pto-color-settings.php";
        ?>
        <div class="main-signup-lists pto-signup-thank-you pto-signup-plugin">
            <div class="container">
                <div class="pto-signup-thank-you-title">
                    <h2>Thank You!</h2>
                </div>
                <div class="pto-signup-thank-you-desc">
                    <p>Thank you for your sign up!</p>
                    <p>You will receive a confirmation email soon!</p>
                </div>
                <div class="pto-signup-back-button">
                    <a href="<?php echo esc_url( site_url() ); ?>/signup/" class="front-primary-btn">Back to Sign Up</a>
                </div>
            </div>
        </div>
        <?php
            return ob_get_clean();
    }
    /**
    * Shortcode for my sign up page
    * @since    1.0.0
    * @access   public
    **/    
    public function pto_sign_up_my_history_handler() {
        ob_start();
        global $wpdb;
        $table_name = $wpdb->prefix . "signup_orders";
        $current_user_id = get_current_user_id();
        include "pto-color-settings.php";        
        ?>
        <div class="pto-signup-plugin">
            <div class="main-signup-lists pto-signup-shortcode pto-custom-style">
                <div class="main-signup-lists-row">
                    <?php
                        if( $current_user_id == 0 ) {
                            ?>
                                <div class='pto-my-signup-not-logged-in'>User is not logged in</div>           
                            <?php
                        } 
                        else{
                            $all_user_posts_count = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE user_id = ".intval( $current_user_id ) );
                            $all_user_posts = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE user_id = ".intval( $current_user_id ) ." AND status = 'on' order by ID DESC" );
                            if ( !empty( $all_user_posts ) ) { ?> 
                                <div class="main-mysignup-listings">
                                    <div class="pto-my-signup-personal-signup">
                                        <div class="pto-my-signup-total-count">
                                            Personal Sign Up Total: <?php echo count( $all_user_posts_count ); ?>
                                        </div>              
                                    </div>
                                    <?php
                                    foreach ( $all_user_posts as $key => $post ): ?>
                                        <div class="pto-signup-datewise-block">                            
                                            <?php
                                                $get_user_signup_data = unserialize( $post->order_info );  
                                                if ( !empty( $get_user_signup_data ) ) {
                                                    $total_signup = count( $get_user_signup_data["signup_id"] );
                                                for ( $i=0; $i<$total_signup; $i++ ) { 
                                                    $signupid = $get_user_signup_data["signup_id"][$i];            
                                                    $total_task = count( $get_user_signup_data["task_id".$signupid] );                                            
                                                    $current_status = get_post_status ( $signupid );
                                                    $pto_sign_up_occurrence = get_post_meta( $signupid, "pto_sign_up_occurrence", true );
                                                    if( empty( $pto_sign_up_occurrence ) ){
                                                        $pto_sign_up_occurrence = array();
                                                    }
                                                    $volunteer_after_sign_up = get_post_meta( $signupid, "volunteer_after_sign_up", true );
                                                    ?>
                                                    <div class="pto-signed-up-date">
                                                        <div class="pto-mysignup-date">
                                                            Signed Up: 
                                                            <span>
                                                                <?php echo esc_html( $post->checkout_date ); ?>
                                                            </span>
                                                        </div>
                                                        <?php
                                                            
                                                            if ( $current_status == "publish" && !empty( $volunteer_after_sign_up ) ) {
                                                        ?>
                                                        <div class="pto-mysignup-receipt-btn">
                                                            <button id="pto-signup-resend-receipt" data-id="<?php echo intval( $post->ID ); ?>" class="pto-signup-resend-receipt front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary">Re-send receipt</button>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="pto-mysignup-block">                                            
                                                    <?php   
                                                    for ( $j=0; $j<$total_task; $j++ ) { 
                                                        $taskid = $get_user_signup_data["task_id".$signupid][$j];
                                                        $tid = "";
                                                        $sdate = "";
                                                        if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {                                            
                                                            $taskid_explode = explode( "_", $taskid );
                                                            $tid = $taskid_explode[0];
                                                            $sdate = $taskid_explode[1];
                                                        }
                                                        else {
                                                            $tid = $taskid;
                                                        }
                                                        
                                                        $task_max_val = $get_user_signup_data["task_max".$taskid][0];
                                                        
                                                        $desc = get_post_meta( $tid, "tasks_comp_desc", true );
                                                        $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$sdate, true );
                                                        if ( !empty( $saved_dates ) ) {
                                                            $desc = $saved_dates["tasks_comp_desc"];
                                                        }
                                                        $cpt_custom_fileds =  get_post_meta( $tid, "single_task_custom_fields", true );
                                                        $datekey = "task_date".$taskid;
                                                        $taskdate = "";
                                                        $timekey = "task_time".$taskid;
                                                        $tasktime = "";
                                                        if ( array_key_exists( $datekey, $get_user_signup_data ) ) { 
                                                            $taskdate = $get_user_signup_data[ $datekey ][0];
                                                        }
                                                        if ( array_key_exists( $timekey, $get_user_signup_data ) ) { 
                                                            $tasktime = $get_user_signup_data[ $timekey ][0];
                                                        }
                                                        $add_mins = 0;
                                                        $single_post_meta = get_post_meta( $tid, "single_tasks_advance_options", true );
                                                        if( empty( $single_post_meta ) )
                                                            $single_post_meta = array();
                                                        if ( array_key_exists( "shift", $single_post_meta ) ) {
                                                            $shift_meta = $single_post_meta["shift"];
                                                            $shift_min = $shift_meta['how-long-shift'];
                                                            $add_mins  = $shift_min * 60;
                                                            $shifttimes = explode( ",", $tasktime );
                                                            for ( $st=0; $st<count( $shifttimes ); $st++ ) {
                                                                if ( !empty( $shifttimes[ $st ] ) ) {
                                                                    $shift_endtime = date ( "h:i A", ( strtotime( $shifttimes[ $st ] ) + $add_mins ) );
                                                                    $shifttimes[ $st ] = $shifttimes[ $st ] . "-" .$shift_endtime;
                                                                }
                                                            }
                                                            $emptyRemoved = array_filter( $shifttimes );                        
                                                            $tasktime = implode( ", ", $emptyRemoved );
                                                        }
                                                        ?>
                                                        <div class="pto-singup-task-blocks-list">
                                                        <div class="pto-singup-task-block">
                                                            <?php if ( $j == 0 ) { ?>
                                                                <h3><?php esc_html_e( get_the_title( $signupid ) ); ?></h3>
                                                            <?php } ?>                
                                                            <h4><?php 
                                                            if ( !empty( $saved_dates ) ) {
                                                                esc_html_e( $saved_dates["post_title"] );
                                                            }
                                                            else{
                                                                esc_html_e( get_the_title( $tid ) );  
                                                            }                                            
                                                            ?></h4>
                                                            <p class="task-desc"><?php print_r( $desc ); ?></p>                                    
                                                        </div>  
                                                        <?php 
                                                        
                                                        if ( !empty( $cpt_custom_fileds ) ) { ?>   
                                                        <div class="pto-singup-task-custom-fields">                                 
                                                        <?php                                             
                                                            foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {     
                                                                $alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
                                                            
                                                                $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );                                                                
                                                                $custom_field_title = "";
                                                                if ( $type == "text-area" ) {
                                                                    $type = "textarea";
                                                                }
                                                                if ( $type == "drop-down" ) {
                                                                    $type = "select";
                                                                }
                                                                if ( !empty( $alternet_title ) ) {
                                                                    $custom_field_title = $alternet_title;
                                                                }
                                                                else {
                                                                    $custom_field_title = get_the_title( $cpt_custom_filed );
                                                                }
                                                                for ( $c = 0; $c < $task_max_val; $c++ ) {
                                                                    $customfieldkey = "custom_".$type."_".$cpt_custom_filed."_".$taskid."_".$signupid."_".$c;                                                                    
                                                                    if ( array_key_exists( $customfieldkey, $get_user_signup_data ) ) { 
                                                                        $customfieldval = "";
                                                                        if( $type == "checkbox" ) {
                                                                            $customfieldval = implode( ",", $get_user_signup_data[ $customfieldkey ] );
                                                                        } 
                                                                        else {
                                                                            $customfieldval = $get_user_signup_data[ $customfieldkey ][0];
                                                                        }                          
                                                                    
                                                                        if ( !empty( $customfieldval ) ) {                                                                    
                                                                        ?>
                                                                        <p class="pto-custom-field-item">
                                                                            <strong>
                                                                                <?php esc_html_e( $custom_field_title ); ?> : 
                                                                            </strong>
                                                                                <?php echo 
                                                                                esc_html_e( $customfieldval );  ?></p>
                                                                        <?php
                                                                        }
                                                                    }
                                                                }
                                                            } 
                                                            ?>
                                                            </div>
                                                            <?php
                                                        } 
                                                        if ( !empty( $taskdate ) || !empty( $tasktime ) ) {
                                                        ?>
                                                        <div class="task-date-time">
                                                            <p><?php if ( !empty( $taskdate ) ) { ?> <span>*</span>Task Due Date: <?php esc_html_e( $taskdate ); ?> <?php } if ( !empty( $tasktime ) ) { ?> Time: <?php esc_html_e( $tasktime ); } ?> </p>
                                                        </div>
                                                        <?php } ?>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                                    <?php
                                                }
                                            } ?>
                                            <div class="pto-mysignup-actions">
                                                <?php
                                                    if ( $current_status == "publish" ) {
                                                ?>
                                                <a href="<?php echo esc_url( get_the_permalink( $signupid ) ); ?>?postid=<?php echo intval( $post->ID ); ?>" class="pto-signup-edit-mysignup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval( $post->ID ); ?>">Edit</a><?php } ?>
                                                <a href="#0" class="pto-signup-remove-from-signup front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval( $post->ID ); ?>">Remove From Sign Up</a>
                                                <a href="#0" class="pto-signup-moveto-archive front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color button button-primary" data-id="<?php echo intval( $post->ID ); ?>">Archive</a> 
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                                <div class="pto-my-signup-show-archived"><a href="#0" class="pto-show-archived pto-signup-btn-text-color pto-signup-btn-background-color front-primary-btn button button-primary">Show Archived</a></div>
                                <?php
                            }      
                            else{
                                echo "<div class='pto-my-signup-not-found'>No data found</div>";
                            }
                            ?>
                            </div>
                                </div>
                            <?php
                        }
                    ?>
                </div>        
        <?php
            return ob_get_clean();
    }
    /**
    * Sorting for sign up listing
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_list_sorting() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( isset( $_POST["sortby"] ) && isset( $_POST["sorttype"] ) ) {
            $sort = sanitize_text_field( $_POST["sorttype"] );
            $sortby = sanitize_text_field( $_POST["sortby"] );
            if ( isset( $_POST["flag"] ) == 1 ) {
                if ( $sortby == "name" ) { $sortby = "date"; }
                else { $sortby = "name"; }
            }
            else {
                if ( $sort == "ASC" ) { $sort = "DESC"; }
                else { $sort = "ASC"; }
            }
            $no_date_sign_ups = get_option( 'no_date_sign_ups' );
	        $repeating_sign_ups = get_option( 'repeating_sign_ups' );
            $title_text_size = get_option( 'title_text_size' );
	        $title_text_color = get_option( 'title_text_color' );
            if ( !empty( $title_text_size ) || !empty( $title_text_color ) ) { ?>
                <style>
                    .signup-post-title {
                        font-size: <?php echo intval( $title_text_size )."px"; ?>;
                        color: <?php esc_html_e( $title_text_color ); ?>;
                    }
                </style>
            <?php
            }
            if ( $sortby == "date" ) {
                $args = array(
                    'post_type'=> 'pto-signup',                                
                    'meta_key' => 'occurrence_specific_days',
                    'orderby' => 'meta_value',
                    'post_status' => 'publish',
                    'order'    => $sort,
                    'posts_per_page' => 7, // this will retrive all the post that is published 
                    'meta_query' => array(
                        array(
                            'key' => 'listing_listing',
                            'value' => 'on',
                            'compare' => '=',
                        ),
                        array(
                            'key' => 'occurrence_specific_days',                                        
                        )
                    )                            
                );
            }
            else{
                $args = array(
                    'post_type'=> 'pto-signup',
                    'orderby'    => 'title',
                    'post_status' => 'publish',
                    'order'    => $sort,
                    'posts_per_page' => 7, // this will retrive all the post that is published 
                    'meta_query' => array(
                        array(
                            'key' => 'listing_listing',
                            'value' => 'on',
                            'compare' => '=',
                        )
                    )                            
                );
            }
            $result = new \WP_Query( $args );  
            if ( $result-> have_posts() ) {
                while ( $result->have_posts() ) {                                 
                    $result->the_post();
                    $post_id =  get_the_ID();
                    $c_user_id = get_current_user_id();
                    $user = get_userdata( $c_user_id );
                
                    $user_roles = $user->roles;
                    foreach ( $user_roles as $key => $value ) {
                        $crole = $value;
                    }
                    $author_id = get_post_field( 'post_author', $post_id );
                    $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator', true );
                    if ( empty( $get_user_req_post ) ) { 
                        $get_user_req_post = array();
                    } 
                    $get_user_post = get_user_meta( $c_user_id, 'pto_signup_request_id', true );
                    if ( empty( $get_user_post ) ) { 
                        $get_user_post = array();
                    } 
                    $specific_day = "";
                    $arykey = "";
                    $get_recurrence_data = "";
                    $pto_sign_up_occurrence = get_post_meta( $post_id, "pto_sign_up_occurrence", true ); 
                    if ( array_key_exists( "occurrence-not-specific", $pto_sign_up_occurrence ) ) {
                        $arykey = "occurrence-not-specific";
                    }
                    if ( array_key_exists( "occurrence-multipal-days", $pto_sign_up_occurrence ) ) {
                        $get_recurrence_data =  get_post_meta( $post_id, "pto_task_recurreence", true );
                        $arykey = "occurrence-multipal-days";
                    }
                    if ( array_key_exists( "occurrence-specific", $pto_sign_up_occurrence ) ) {
                        $specific_day = get_post_meta( $post_id, "occurrence_specific_days", true );
                        $arykey = "occurrence-specific";
                    }                
                    if ( $no_date_sign_ups == "" && $arykey == "occurrence-not-specific" ) {
                    }
                    elseif ( $repeating_sign_ups == "" && $arykey == "occurrence-multipal-days" ) { 
                    }
                    else {                           
                    ?>
                    <li class="single-signup-list">
                        <div class="single-signup-block">
                            <a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>" class="post-title-link">
                                <h5 class="signup-post-title"><?php esc_html_e( the_title() ); ?></h5>
                            </a>
                            <?php if ( !empty( $specific_day ) ) { ?>
                            <span class="signup-date"><?php  esc_html_e( $specific_day ); ?> </span> 
                            <?php } elseif ( !empty( $get_recurrence_data ) ) { ?>
                            <span class="signup-date"> &nbsp;
                                Repeat every 
                                <?php  
                                if ( array_key_exists( "daysofevery", $get_recurrence_data ) ) { 
                                    echo intval( $get_recurrence_data["daysofevery"] ); 
                                }
                                if ( array_key_exists( "to_sign_up_div_repeate_time", $get_recurrence_data ) ) { 
                                    esc_html_e( $get_recurrence_data["to_sign_up_div_repeate_time"] ); 
                                }
                                if ( array_key_exists( "end_data", $get_recurrence_data ) ) { 
                                    $end_data =  $get_recurrence_data["end_data"]; 
                                    if ( array_key_exists( "after", $end_data ) ) {
                                        ?>
                                        , End After &nbsp;
                                        <?php
                                        echo intval( $end_data["after"] );
                                        ?>
                                        &nbsp;occurrences
                                        <?php
                                    }
                                    if ( array_key_exists( "on", $end_data ) ) {
                                        ?>
                                        , End On 
                                        <?php
                                        esc_html_e( $end_data["on"] );
                                    }
                                    if ( array_key_exists( "never", $end_data ) ) {
                                        ?>
                                        , End Never
                                        <?php
                                    }
                                }
                                ?> 
                            </span> 
                            <?php } ?>
                        </div>
                    </li>
                    <?php 
                    }
                }                            
            }
            else{
                ?>
                <div class='no_data'>No data found</div>;
                <?php
            }
            wp_reset_postdata();
        }
        die();
    }		
	/**
    * Request access
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_request_access() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( isset( $_POST["signup_id"] ) ) {
            $signup_id = intval( $_POST['signup_id'] );
            $current_user_id = get_current_user_id();
            $get_user_post = get_user_meta( $current_user_id, 'pto_signup_request_id', true );
            
			if ( !empty( $get_user_post ) ) {
                $get_user_post[ $signup_id ] =  $signup_id;                    
                update_user_meta( $current_user_id, "pto_signup_request_id", $get_user_post );
            } else {
                $total_proj_arr[ $signup_id ] = $signup_id;      
                update_user_meta( $current_user_id, "pto_signup_request_id", $total_proj_arr );
            }
            
            ?>
            done
            <?php
            $signup_title = get_the_title( $signup_id );
            $author_id = get_post_field( 'post_author', $signup_id );
            $user_info = get_userdata( $author_id );            
            $usermail = $user_info->user_email;
            $admin_name = $user_info->display_name;
            $cuser_info = get_userdata( $current_user_id );
            $cuser_name = $cuser_info->display_name;
            $to = $usermail;
            $subject = 'Request access for signup.';
            $body = 'Hello '.$admin_name.'! you have got request from '. $cuser_name .' to access the "'. $signup_title .'" signup.';
            $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    
            wp_mail( $to, $subject, $body, $headers );
        }
        else {
            ?>
            not done
            <?php
        }
        die();
    }
    /**
    * Shortcode for checkout page
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_checkout_handler( $atts , $content = null ) {       
        ob_start();
        include "pto-color-settings.php";
        ?>
        <div class="pto-signup-plugin">
            <div class="main-signup-lists wp-admin pto-custom-style pto-signup-shortcode">
                <div class="main-signup-lists-row">
                    <form class="pto-signup-checkout-frontend" method="post" id="pto-signup-checkout-frontend" >
                        <div class="signup-step2">
                            <div class="pto-checkout-section">
                                <?php 
                                    global $wpdb;
                                    $current_user_id = 0;
                                    if ( isset( $_REQUEST["uid"] ) ) {
                                        $current_user_id = intval($_REQUEST["uid"]); 
                                    }
                                    else{
                                        $current_user_id = get_current_user_id(); 
                                    }                               
                                    $table_name = $wpdb->prefix . "signup_orders"; 
                                    $editid = "";
                                    
                                    $get_user_cart = array();
                                    if ( array_key_exists( "pto_signup_tasks_cart", $_SESSION ) ) {
                                        $get_user_cart = filter_var_array($_SESSION['pto_signup_tasks_cart']);
                                    }
                        
                                    if ( !empty( $get_user_cart ) ) {
                                        ?>
                                        <div class="pto-checkout-account-main">
                                            <div class="pto-custom-fields">
                                                <div class="signup-checkout-title-box">
                                                    <h2>Checkout</h2>
                                                </div>
                                                <p>Please review the information below and complete your checkout</p>
                                                <?php
                                                    $get_user_signup_data = "";
                                                    
                                                    foreach ( $get_user_cart as $key => $val ) { 
                                                        $status = get_post_status( $key );
                                                        if( $status == "publish" ){
                                                            $chkout_sign_up_occurrence = get_post_meta( $key, "pto_sign_up_occurrence", true );
                                                            
                                                            $chkout_specific_day = "";
                                                            $chkout_multiple_days = "";
                                                            $signup_custom_fileds =  get_post_meta( $key, "single_task_custom_fields_checkout", true );
                                                            $agree_to_terms_sign_up = get_post_meta( $key, "agree_to_terms_sign_up", true );
                                                            if ( array_key_exists( "pto_sign_up_edit", $val ) ) {
                                                                $editid = $val["pto_sign_up_edit"];
                                                                $all_data =  $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE ID = ".intval( $editid ) );
                                                                if ( !empty( $all_data ) ) {
                                                                    foreach ( $all_data as $keye => $post ):
                                                                        $get_user_signup_data = unserialize( $post->order_info );
                                                                    endforeach;
                                                                }
                                                            }
                                                            if ( !empty( $chkout_sign_up_occurrence ) ) {
                                                                if ( array_key_exists( "occurrence-specific", $chkout_sign_up_occurrence ) ) {
                                                                    $chkout_specific_day = get_post_meta( $key, "occurrence_specific_days", true );
                                                                    
                                                                }
                                                                if ( array_key_exists( "occurrence-multipal-days", $chkout_sign_up_occurrence ) ) {
                                                                    if ( array_key_exists( "multiple_dates_signup", $val ) ) {
                                                                        $chkout_multiple_days = $val['multiple_dates_signup'];
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <div class="pto-custom-field-block">
                                                                <div class="checkout-info-details">
                                                                    <div class="checkout-info-block-details">
                                                                        <h3><?php  esc_html_e( get_the_title( $key ) ); ?></h3>
                                                                        <input type="hidden" name="edit_id" value="<?php echo intval( $editid ); ?>" />
                                                                        <input type="hidden" name="signup_id[]" value="<?php echo intval( $key ); ?>" />
                                                                        <?php
                                                                            if ( !empty( $chkout_sign_up_occurrence ) ) {
                                                                                if ( array_key_exists( "occurrence-multipal-days", $chkout_sign_up_occurrence ) ) {
                                                                                    ?> 
                                                                                    <input type="hidden" name="selected_dates_<?php esc_html_e( $key ); ?>" value="<?php esc_html_e( implode( ",", $chkout_multiple_days ) ); ?>" />
                                                                                    <?php 
                                                                                } 
                                                                            } 
                                                                        ?>
                                                                    </div>
                                                                    <a href="#0" data-id="<?php echo intval( $key ); ?>" user-id="<?php echo intval( $current_user_id ); ?>" class="pto-signup-checkout-delete"><i class="fas fa-trash-alt"></i></a>
                                                                </div>
                                                                <?php
                                                                    
                                                                    $count_tasks = count( $val["singup_hidden_task"] ); 
                                                                  
                                                                    for ( $i = 0; $i < $count_tasks; $i++ ) { 
                                                                        
                                                                        $taskid = $val["singup_hidden_task"][$i];
                                                                        $shift_time = $val["task_shift_hidden"][$i];
                                                                        $pto_signup_task_max = $val["pto_signup_task_max"][$i];
                                                                        $pto_signup_task_hours = $val["pto_signup_hours_points"][$i];
                                                                        if ( !empty( $pto_signup_task_max ) && !empty( $pto_signup_task_hours ) ) {
                                                                            $pto_signup_task_hours = $pto_signup_task_hours * $pto_signup_task_max;
                                                                        }
                                                                        
                                                                        if ( !empty( $taskid ) ) {
                                                                            
                                                                            $chkout_single_post_meta = get_post_meta( $taskid, "single_tasks_advance_options", true );
                                                                            $cpt_custom_fileds =  get_post_meta( $taskid, "single_task_custom_fields", true );
                                                                            $pto_sign_ups_custom_fileds = get_post_meta( $taskid, "pto_sign_ups_custom_fileds", true );
                                                                            $saved_dates = "";
                                                                            $tid = "";
                                                                            if ( !empty( $chkout_multiple_days ) ) { 
                                                                                $recdate = $val["singup_hidden_date"][$i];
                                                                                $tid = $taskid;
                                                                                $taskid = $taskid."_".$recdate;
                                                                                $saved_dates = get_post_meta( $tid, "pto_signup_task_edit_single".$recdate, true );                                                                    
                                                                            }
                                                                            
                                                                            ?>
                                                                            <div class="checkout-info-block-details">
                                                                                <div class="pto-checkout-task-name">
                                                                                    <h5><?php 
                                                                                    if ( !empty( $saved_dates ) ) {
                                                                                        esc_html_e( $saved_dates["post_title"] );
                                                                                    }
                                                                                    else{
                                                                                        esc_html_e( get_the_title( $taskid ) ); 
                                                                                    }
                                                                                     
                                                                                    ?></h5>
                                                                                </div>   
                                                                                <input type="hidden" name="task_id<?php esc_html_e( $key ); ?>[]" value="<?php esc_html_e( $taskid ); ?>" />
                                                                                <input type="hidden" name="task_max<?php esc_html_e( $taskid ); ?>[]" value="<?php if ( !empty( $pto_signup_task_max ) ) { echo intval( $pto_signup_task_max ); } else { esc_html_e("1"); } ?>" />
                                                                                <input type="hidden" name="task_hours_points<?php esc_html_e( $taskid ); ?>[]" value="<?php if ( !empty( $pto_signup_task_hours ) ) { echo intval( $pto_signup_task_hours ); } ?>" />
                                                                                <div class="pto-task-date-time">
                                                                                    <div class="pto-task-date">
                                                                                        <?php 
                                                                                            if ( !empty( $chkout_specific_day ) ) { esc_html_e( date( "l, F jS", strtotime( $chkout_specific_day ) ) ); }
                                                                                            if ( !empty( $chkout_multiple_days ) ) {
                                                                                                esc_html_e( $recdate );
                                                                                            }
                                                                                        ?>
                                                                                        <input type="hidden" name="task_date<?php esc_html_e( $taskid ); ?>[]" value="<?php if ( !empty( $chkout_specific_day ) ) { esc_html_e( $chkout_specific_day ); } if ( !empty( $chkout_multiple_days ) ) { esc_html_e ( $recdate ); } ?>" />
                                                                                    </div>
                                                                                    <div class="pto-task-time">
                                                                                        <?php 
                                                                                            $tasktime = "";
                                                                                            if ( !empty( $chkout_single_post_meta ) ) { 
                                                                                                if ( array_key_exists( "single", $chkout_single_post_meta ) ) {
                                                                                                    if ( array_key_exists( "how_money_volunteers_sign_ups-times", $chkout_single_post_meta['single'] ) ) { 
                                                                                                        $tasktime = $chkout_single_post_meta['single']['how_money_volunteers_sign_ups-times']; 
                                                                                                        $tat_time =  date ( "h:i A", strtotime($chkout_single_post_meta['single']['how_money_volunteers_sign_ups-times']));
                                                                                                        esc_html_e( $tat_time );
                                                                                                    }
                                                                                                } 
                                                                                                else { 
                                                                                                    if ( !empty( $shift_time ) ) { 
                                                                                                        $tasktime = $shift_time; 
                                                                                                        $t = sanitize_text_field( substr( $shift_time, 0, -1 ) );
                                                                                                        $s_time = date ( "h:i A", strtotime($t));
                                                                                                        ?>
                                                                                                        Shift Time: 
                                                                                                        <?php 
                                                                                                        esc_html_e( $s_time );
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        ?>
                                                                                        <input type="hidden" name="task_time<?php esc_html_e( $taskid ); ?>[]" value="<?php esc_html_e( $tasktime ); ?>" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                                <?php  
                                                                                   
                                                                                    if ( !empty( $cpt_custom_fileds ) && !empty( $pto_sign_ups_custom_fileds ) ) { 
                                                                                        ?>
                                                                                        <div class='pto-signup-task-block'>
                                                                                        <?php
                                                                                        
                                                                                        for ( $c=0; $c<$pto_signup_task_max; $c++) {                                                                               
                                                                                            foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {     
                                                                                                $alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
                                                                                                $instruction = get_post_meta( $cpt_custom_filed, "instruction", true );
                                                                                                $type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
                                                                                                $require = get_post_meta( $cpt_custom_filed, "pto_field_required", true );
                                                                                                $custom_field_title = "";
                                                                                                $customfieldval = "";                                                                           
                                                                                                $etype = $type;
                                                                                                if ( $type == "text-area" ) {
                                                                                                    $etype = "textarea";
                                                                                                }
                                                                                                if ( $type == "drop-down" ) {
                                                                                                    $etype = "select";
                                                                                                }
                                                                                                if ( !empty( $get_user_signup_data ) ) {
                                                                                                    $customfieldkey = "custom_".$etype."_".$cpt_custom_filed."_".$taskid."_".$key."_".$c;                                                                                                
                                                                                                    if ( array_key_exists( $customfieldkey, $get_user_signup_data ) ) { 
                                                                                                        if ( $type == "checkbox" ) {
                                                                                                            
                                                                                                            $customfieldval = $get_user_signup_data[ $customfieldkey ];
                                                                                                        } 
                                                                                                        else{
                                                                                                            $customfieldval = $get_user_signup_data[ $customfieldkey ][0];
                                                                                                        }   
                                                                                                    }
                                                                                                }                                                                                        
                                                                                                if ( $require == "yes" ) {
                                                                                                    $require = "required";
                                                                                                }
                                                                                                else {
                                                                                                    $require = "";
                                                                                                }
                                                                                                if ( !empty( $alternet_title ) ) {
                                                                                                    $custom_field_title = $alternet_title;
                                                                                                }
                                                                                                else {
                                                                                                    $custom_field_title = get_the_title( $cpt_custom_filed );
                                                                                                }
                                                                                                ?>
                                                                                                <div class="custom-field-box">
                                                                                                    <label><?php esc_html_e( $custom_field_title ); 
                                                                                                        if ( !empty( $require ) ) { 
                                                                                                            ?><span class='required-star'>*</span><?php 
                                                                                                        } ?>
                                                                                                    </label>
                                                                                                    <?php  
                                                                                                        if ( $type == "text-area" ) { ?>
                                                                                                            <textarea name="custom_textarea_<?php esc_html_e( $cpt_custom_filed."_".$taskid."_".$key."_".$c ); ?>[]" placeholder="<?php esc_html_e( $instruction ); ?>" <?php esc_html_e( $require ); ?> ><?php esc_html_e( $customfieldval ); ?></textarea>
                                                                                                            <?php
                                                                                                        } 
                                                                                                        elseif ( $type == "drop-down" ) { 
                                                                                                            $select_field_value = get_post_meta( $cpt_custom_filed, "selected_value_field", true ); 
                                                                                                            if ( !empty( $select_field_value ) ) {       
                                                                                                                ?>
                                                                                                                <select name="custom_select_<?php esc_html_e( $cpt_custom_filed."_".$taskid."_".$key."_".$c ); ?>[]" <?php esc_html_e( $require ); ?> >
                                                                                                                    <option value="">--Select Option--</option>
                                                                                                                    <?php
                                                                                                                        $drop_down = $select_field_value["drop-down"];
                                                                                                                        foreach ( $drop_down as $keys => $vals ) { ?>
                                                                                                                            <option value="<?php esc_html_e( $vals ); ?>" <?php if ( $customfieldval == $vals ) { esc_html_e("selected"); } ?> > <?php esc_html_e( $vals ); ?> </option>
                                                                                                                            <?php   
                                                                                                                        }                                                                                                                                        
                                                                                                                    ?>                            
                                                                                                                </select> 
                                                                                                                <?php
                                                                                                            }
                                                                                                        } 
                                                                                                        elseif ( $type == "checkbox" || $type == "radio" ) { 
                                                                                                            $select_field_value = get_post_meta( $cpt_custom_filed, "selected_value_field", true );                                                                                        
                                                                                                            if ( !empty( $select_field_value ) ) {
                                                                                                                $drop_down = $select_field_value[ $type ];
                                                                                                                foreach ( $drop_down as $keys => $vals ) { ?>
                                                                                                                    <div class="pto-radio-checkbox-wrap">
                                                                                                                        <input type="<?php esc_html_e( $type ); ?>" <?php if ( $type == "checkbox" ) { if ( is_array( $customfieldval ) ) { if ( in_array( $vals, $customfieldval ) ) { esc_html_e("checked"); } } } if ( $customfieldval == $vals ) { esc_html_e("checked"); } ?> name="custom_<?php esc_html_e( $type ); ?>_<?php esc_html_e( $cpt_custom_filed."_".$taskid."_".$key."_".$c ); ?>[]" value="<?php esc_html_e( $vals ); ?>" <?php esc_html_e( $require ); ?> /> 
                                                                                                                        <label><?php esc_html_e( $vals ); ?></label>
                                                                                                                    </div>     
                                                                                                                    <?php   
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                        else {
                                                                                                            ?>
                                                                                                            <input value="<?php esc_html_e( $customfieldval ); ?>" type="<?php esc_html_e( $type ); ?>"  <?php esc_html_e( $require ); ?> name="custom_<?php esc_html_e( $type ); ?>_<?php esc_html_e( $cpt_custom_filed."_".$taskid."_".$key."_".$c ); ?>[]" />
                                                                                                            <?php
                                                                                                        }
                                                                                                    ?>
                                                                                                </div>
                                                                                                <?php 
                                                                                            }
                                                                                        }
                                                                                        echo "</div>";
                                                                                    }
                                                                                ?>
                                                                           
                                                                            <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </div>
                                                            <?php 
                                                        }
                                                    }
                                                    $ag = 1;
                                                    foreach ( $get_user_cart as $key => $val ) { 
                                                        $status = get_post_status( $key );
                                                        if( $status == "publish" ){                                                
                                                            $agree_to_terms_sign_up = get_post_meta( $key, "agree_to_terms_sign_up", true );
                                                            if ( !empty( $get_user_signup_data ) ) {                                                                    
                                                                $signup_customfieldkey = "agree_to_terms_signup".$key;
                                                                if ( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) {                                                              
                                                                }
                                                            }
                                                            if ( !empty( $agree_to_terms_sign_up ) ) { 
                                                                $content = get_post_meta( $key, "agree_to_terms", true );
                                                                if ( $ag == 1 ) {
                                                                ?>
                                                                <div class="signup-checkout-title-box">
                                                                    <h2>Agree to terms</h2>
                                                                </div>
                                                                <?php } ?>
                                                                <div class="pto-singup-checkout-agreetoterms"> 
                                                                    <div class="pto-radio-checkbox-wrap">
                                                                        <input type="checkbox" name="agree_to_terms_signup<?php esc_html_e( $key ); ?>[]" value="" required  />
                                                                        <label class="checkout-agreetoterm-popup">I Agree to the Terms <span class="required-star">*</span></label>
                                                                        <h5> (<?php esc_html_e( get_the_title( $key ) ); ?>)</h5>
                                                                    </div>
                                                                    <div class="agree-to-terms-content pto-modal" style="display:none;">
                                                                        <div class="pto-modal-content">    
                                                                            <div class="pto-modal-container-header">
                                                                                <span><?php esc_html_e( 'Agree To Terms',PTO_SIGN_UP_TEXTDOMAIN ); ?></span>
                                                                                <span onclick="jQuery('.agree-to-terms-content').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
                                                                            </div>
                                                                            <div class="pto-modal-container">
                                                                                <?php esc_html_e( $content ); ?>
                                                                            </div>
                                                                            <div class="pto-modal-footer">
                                                                                <input type="button" name="ok" value="Ok" onclick="jQuery('.agree-to-terms-content').removeClass('pto-modal-open');" class="task-recurrence_add_new outline_btn button button-primary">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            $ag++;
                                                        }
                                                    }
                                                ?>
                                            </div>
                                            <div class="pto-signup-account-info">
                                                <div class="signup-checkout-title-box">
                                                    <h2>Account Information</h2>
                                                    <?php
                                                        if ( $current_user_id == 0 ) { ?>
                                                        <div class="pto-signup-already-account">
                                                            Already have an account?
                                                            <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="pto-signup-login-here front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color">Log in here</a>
                                                        </div>
                                                        <?php 
                                                    } 
                                                    else{
                                                        ?>
                                                        <div class="pto-signup-logged-in-not-you">
                                                            Not You?
                                                            <a href="<?php echo esc_url( wp_logout_url() ); ?>" class="pto-signup-logout-here front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color">Log Out</a>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <?php
                                                $first_name = "";
                                                $last_name = "";
                                                $user_email = "";
                                                if ( $current_user_id != 0 ) {
                                                    $user_info = get_userdata( $current_user_id );
                                                    $first_name = $user_info->first_name;
                                                    $last_name = $user_info->last_name;
                                                    $user_email = $user_info->user_email;
                                                }
                                            ?>
                                            <div class="error-msg"></div>
                                            <div class="cust-field">
                                                <label>First Name<span class="required-star">*</span></label>
                                                <input type="text" value="<?php esc_html_e( $first_name ); ?>" <?php if ( $current_user_id != 0 ) { esc_html_e("disabled"); } ?> name="pto_signup_user_fname" <?php if ( $current_user_id == 0 ) { echo "required"; } ?>  />
                                            </div>
                                            <div class="cust-field">
                                                <label>Last Name<span class="required-star">*</span></label>
                                                <input type="text" value="<?php esc_html_e( $last_name ); ?>" <?php if ( $current_user_id != 0 ) { esc_html_e("disabled"); } ?> name="pto_signup_user_lname" <?php if ( $current_user_id == 0 ) { echo "required"; } ?> />
                                            </div>
                                            <div class="cust-field">
                                                <label>Email Address<span class="required-star">*</span></label>
                                                <input type="email" id='pto_signup_user_email' value="<?php esc_html_e( $user_email ); ?>" <?php if ( $current_user_id != 0 ) { esc_html_e("disabled"); } ?> name="pto_signup_user_email" <?php if ( $current_user_id == 0 ) { esc_html_e("required"); } ?> />
                                            </div>
                                            
                                            <div class="pto-signup-customfield-block">
                                                <?php
                                                    foreach ( $get_user_cart as $key => $val ) {                                                         
                                                        $signup_custom_fileds = get_post_meta( $key, "single_task_custom_fields_checkout", true );
                                                        $checkout_fields_sign_up = get_post_meta( $key, "checkout_fields_sign_up", true );                                                        
                                                        if ( !empty( $signup_custom_fileds ) && !empty( $checkout_fields_sign_up ) ) {
                                                            ?>
                                                            <div class="pto-signup-customfield-text">
                                                                <h3><?php esc_html_e( get_the_title( $key ) ); ?></h3>
                                                            </div>
                                                            <?php
                                                            foreach ( $signup_custom_fileds as $signup_custom_filed ) {
                                                                $signup_alternet_title = get_post_meta( $signup_custom_filed, "pto_alternate_title", true );
                                                                $signup_instruction = get_post_meta( $signup_custom_filed, "instruction", true );
                                                                $signup_type = get_post_meta( $signup_custom_filed, "pto_field_type", true );
                                                                $signup_require = get_post_meta( $signup_custom_filed, "pto_field_required", true );
                                                                $signup_custom_field_title = "";
                                                                if ( $signup_require == "yes" ) {
                                                                    $signup_require = "required";
                                                                }
                                                                else {
                                                                    $signup_require = "";
                                                                }
                                                                if ( !empty( $signup_alternet_title ) ) {
                                                                    $signup_custom_field_title = $signup_alternet_title;
                                                                }
                                                                else {
                                                                    $signup_custom_field_title = get_the_title( $signup_custom_filed );
                                                                }
                                                                $estype = $signup_type;
                                                                if ( $signup_type == "text-area" ) {
                                                                    $estype = "textarea";
                                                                }
                                                                if ( $signup_type == "drop-down" ) {
                                                                    $estype = "select";
                                                                }
                                                                
                                                                $signup_customfieldval = "";
                                                                if ( !empty( $get_user_signup_data ) ) {                                                                    
                                                                    // $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed. $key."_";
                                                                    
                                                                    $signup_customfieldkey = "signup_".$estype."_".$signup_custom_filed."_".$key;
                                                                    
                                                                    // echo $signup_customfieldkey;
                                                                    if ( array_key_exists( $signup_customfieldkey, $get_user_signup_data ) ) { 
                                                                        if ( $signup_type == "checkbox" ) {                                                                           
                                                                            $signup_customfieldval = array();
                                                                            $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ];
                                                                        } 
                                                                        else{
                                                                            $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];
                                                                        }   
                                                                    }
                                                                    // if ( array_key_exists( $signup_customfieldkeys, $get_user_signup_data ) ) { 
                                                                        
                                                                    //     if ( $signup_type == "checkbox" ) {                                                                           
                                                                    //         $signup_customfieldval = array();
                                                                    //         $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ];
                                                                    //     } 
                                                                    //     else{
                                                                    //         $signup_customfieldval = $get_user_signup_data[ $signup_customfieldkey ][0];
                                                                    //     }   
                                                                    // }
                                                                } 
                                                                
                                                                ?>
                                                                <div class="custom-field-box">
                                                                    <label><?php esc_html_e( $signup_custom_field_title ); 
                                                                    if ( !empty( $signup_require ) ) { ?><span class='required-star'>*</span> <?php } ?>
                                                                    </label>
                                                                    <?php  
                                                                    if ( $signup_type == "text-area" ) { ?>
                                                                        <textarea name="signup_textarea_<?php echo intval( $signup_custom_filed )."_".intval( $key ); ?>[]" placeholder="<?php esc_html_e( $signup_instruction ); ?>" <?php esc_html_e( $signup_require ); ?> ><?php esc_html_e( $signup_customfieldval ); ?></textarea>
                                                                    <?php
                                                                    } 
                                                                    elseif ( $signup_type == "drop-down" ) { 
                                                                        $select_field_value_signup = get_post_meta( $signup_custom_filed, "selected_value_field", true ); 
                                                                        if ( !empty( $select_field_value_signup ) ) { 
                                                                            ?>
                                                                            <select name="signup_select_<?php echo intval( $signup_custom_filed )."_".intval( $key ); ?>[]" <?php esc_html_e( $signup_require ); ?> >
                                                                                <option value="">--Select Option--</option>
                                                                            <?php
                                                                            $drop_down = $select_field_value_signup["drop-down"];
                                                                            foreach ( $drop_down as $keys => $vals ) { ?>
                                                                                <option value="<?php esc_html_e( $vals ); ?>" <?php if ( $signup_customfieldval == $vals ) { esc_html_e("selected"); } ?>> <?php esc_html_e( $vals ); ?> </option>
                                                                            <?php   
                                                                            }
                                                                                                
                                                                        ?>                            
                                                                        </select> 
                                                                        <?php
                                                                        }
                                                                    } 
                                                                    elseif ( $signup_type == "checkbox" || $signup_type == "radio" ) { 
                                                                        $select_field_value_signup = get_post_meta( $signup_custom_filed, "selected_value_field", true );                                                                        
                                                                        if ( !empty( $select_field_value_signup ) ) {
                                                                            $drop_down = $select_field_value_signup[ $signup_type ];
                                                                            $rdchk = 0;
                                                                            foreach ( $drop_down as $keys => $vals ) {
                                                                                $rand = rand( 0 , 99999 );
                                                                            ?>
                                                                                <div class="pto-radio-checkbox-wrap">
                                                                                    <input id="<?php esc_html_e($vals ."_". $rand); ?>" type="<?php esc_html_e( $signup_type ); ?>" <?php if ( $signup_type == "checkbox" ) { if ( is_array( $signup_customfieldval ) ) { if( in_array( $vals, $signup_customfieldval ) ) { esc_html_e("checked"); } } } if ( $signup_customfieldval == $vals ) { esc_html_e("checked"); } ?> name="signup_<?php esc_html_e( $signup_type ); ?>_<?php echo intval( $signup_custom_filed )."_".intval( $key ); ?>[]" value="<?php esc_html_e( $vals ); ?>" <?php esc_html_e( $signup_require ); ?> /> 
                                                                                    <label for="<?php esc_html_e($vals ."_". $rand); ?>"><?php esc_html_e( $vals ); ?></label> 
                                                                                </div>
                                                                            <?php   
                                                                            }
                                                                        }
                                                                    }
                                                                    else{
                                                                    ?>
                                                                    <input type="<?php esc_html_e( $signup_type ); ?>" value="<?php esc_html_e( $signup_customfieldval ); ?>" placeholder="<?php esc_html_e( $signup_instruction ); ?>" <?php esc_html_e( $signup_require ); ?> name="signup_<?php esc_html_e( $signup_type ); ?>_<?php echo intval( $signup_custom_filed )."_".intval( $key ); ?>[]" />
                                                                    <?php 
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </div>
                                        </div>    
                                        <?php                                           
                                            $pto_post_sign_thank_you = get_option( 'pto_post_sign_thank_you' ); 
                                            $url = "";                                    
                                            if ( !empty( $pto_post_sign_thank_you ) ) {
                                                $url = get_the_permalink( $pto_post_sign_thank_you );
                                            }
                                            else {
                                                $url = site_url()."/signup-thank-you";
                                            }							
                                        ?> 
                                        <div class="pto-signup-checkout-submit">
                                            <?php if ( !empty( $editid ) ) { ?>
                                                <input type="button" uid="<?php echo intval( $current_user_id ); ?>" class="signup-submit button front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" data-url="<?php echo esc_url( $url ); ?>" id="signup-submit-update" name="signup_submit_update" value="Update Sign Up" />
                                            <?php } else { ?>
                                            <input type="button" uid="<?php echo intval( $current_user_id ); ?>" class="signup-submit button front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" data-url="<?php echo esc_url( $url ); ?>" id="signup-submit" name="signup_submit" value="Sign Up" />
                                            <?php } ?>
                                        </div>                                       
                                        <?php                              
                                    }
                                    else {
                                        ?>
                                            <div class="pto-no-signup-checkout">
                                                <p>Please <a href="<?php echo esc_url( site_url() ); ?>/signup/">click here</a> to add new singup.</p>
                                            </div>
                                        <?php
                                    }
                                ?> 
                            </div>	
                        </div>			
	                </form>
                </div>
            </div>	        
            </div>  
        </div>       
        <div class="signup_loader_main">
            <div class="signup_loader_div">
            <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>
              
       
        <?php
            return ob_get_clean();
    }
    /**
    * Shortcode for listing my sign ups
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_my_listing_handler( $atts , $content = null ) {
        ob_start();
        include "pto-color-settings.php";
        ?>
        <div class="pto-signup-plugin">
        
            <div class="main-signup-lists pto-signup-shortcode pto-custom-style">
                <div class="main-signup-lists-row">
                <div class="signup-list" id="tabs">
                    <div class="signup-list-tab-row">
                        <ul class="tabs pto-signup-tabs">
                            <?php if ( is_user_logged_in() ) { ?>
                            <li class="tab-link" ><a href="#my-signup"><?php esc_html_e( 'My Sign Ups', PTO_SIGN_UP_TEXTDOMAIN ); ?></a></li>
                            <?php } ?>
                        </ul>
                        <?php 
                            $cuser_id = get_current_user_id();
                            $role_array = array();
                            if ( $cuser_id != 0 ) {
                                $userdata = get_userdata( $cuser_id );                            
                                foreach ( $userdata->roles as $key => $roles ) {
                                    $role_array[ $roles ] = $roles;
                                }
                            }                            
                            if ( array_key_exists( "own_sign_up", $role_array ) || array_key_exists( "administrator", $role_array ) || array_key_exists( "sign_up_plugin_administrators", $role_array ) ) {
 
                        ?>
                        <a href="<?php echo esc_url( site_url() ); ?>/wp-admin/post-new.php?post_type=pto-signup" class="add-new-btn btn_add front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" ><?php esc_html_e( 'Add new', PTO_SIGN_UP_TEXTDOMAIN ); ?></a>
                        <?php } ?>
                    </div>
                    <div class="tab-data all-signup-show-box">
                    <?php if ( is_user_logged_in() ) { ?>
                    <div id="my-signup" class="tab-content">
                        <ul class="signup-list-block">
                            <?php 
                                $mysignup = 0;
                                $c_user_id = get_current_user_id();
                                $args = array(
                                    'post_type'=> 'pto-signup',
                                    'orderby'    => 'ID',
                                    'post_status' => 'publish',
                                    'order'    => 'DESC',                                    
                                    'posts_per_page' => -1, // this will retrive all the post that is published 
                                    'meta_query' => array(
                                        array(
                                            'key' => 'listing_listing',
                                            'value' => 'on',
                                            'compare' => '=',
                                        )
                                    ) 
                                );
                                $result = new \WP_Query( $args );
                                if ( $result-> have_posts() ) {                                   
                                    while ( $result->have_posts() ) { 
                                        $result->the_post();
                                        $post_id =  get_the_ID();
                                        $c_user_id = get_current_user_id();
                                        $author_id = get_post_field( 'post_author', $post_id );
                                        $crole = "";
                                        if ( $c_user_id != 0 ) {
                                            $user = get_userdata( $c_user_id );
                                            $user_roles = $user->roles;
                                            foreach ( $user_roles as $key => $value ) {
                                                $crole = $value;
                                            }
                                        }                                           
                                        $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator', true );
                                        if ( empty( $get_user_req_post ) ) { 
                                            $get_user_req_post = array();
                                        }
                                        $time_set = get_post_meta( $post_id, "pto_sign_ups_time_set", true );
                                        if ( !empty( $time_set ) ) {
                                            $opentime = $time_set['opendate']." ".$time_set['opentime'];
                                            $opentime = strtotime( $opentime );                                            
                                            $closetime = $time_set['closedate']." ".$time_set['closetime'];                                            
                                            $closetime = strtotime( $closetime );
                                            $timezone_format = _x( 'Y-m-d H:i', 'timezone date format' );
                                            $currenttime =  date_i18n( $timezone_format ); 
                                            $currenttime = strtotime( $currenttime );                                                                                 
                                            if ( $currenttime < $opentime ) {  
                                               
                                                continue;
                                            }
                                            if ( $closetime < $currenttime ) {   
                                                continue;
                                            }
                                        }
                                        if ( ( $c_user_id == $author_id ) || ( in_array( $c_user_id, $get_user_req_post ) ) ) { 
                                            $mysignup = 1;
                                            ?>
                                            <li class="single-signup-list">
                                                <div class="single-signup-block">
                                                <div class="small-signup-banner-img">
                                                    <?php 
                                                        if ( has_post_thumbnail() ) { 
                                                            $imgurl = get_the_post_thumbnail_url( get_the_ID() );
                                                        }
                                                        else{
                                                            $imgurl = PTO_SIGN_UP_PLUGIN_DIR."assets/img/noimg.png";
                                                        }
                                                    ?>
                                                    <img src="<?php echo esc_url( $imgurl ); ?>">                                        
                                                </div>
                                                <div class="single-signup-info edit-url">
                                                    <a href="<?php echo esc_url( site_url() ); ?>/wp-admin/post.php?post=<?php echo intval( $post_id ); ?>&action=edit">
                                                        <input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="signup-access-btn front-primary-btn" value="Edit">
                                                    </a>                                            
                                                    <a href="<?php echo esc_url( get_the_permalink( $post_id ) ); ?>" class="post-title-link"><h5 class="post-title"><?php esc_html_e( the_title() ); ?></h5></a>
                                                </div>
                                                </div>
                                            </li>
                                            <?php 
                                        }
                                    }                        
                                }
                                else{                                   
                                }   
                                if ( $mysignup == 0 ) {
                                    echo "No Sign Up found.";
                                }
                                wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="search-signup-block">
                    <div class="search-signup">
                        <h4 for="search"><?php esc_html_e( 'Sign Up Search', PTO_SIGN_UP_TEXTDOMAIN ); ?></h4>
                        <div class="search-signup-form-box">
                            <div class="cust-field cust-field">
                                <label for="search"><?php esc_html_e( 'Search for a sign up name', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
                                <div class='pto-signup-search'>
                                    <input type="text" id="search-signup" data class="s-signup" name="search-signup">
                                    <i class="fa fa-times close-text" onclick="jQuery('#search-signup').val('')" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="submit" value="Submit" class="search-signup-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="search-signup-btn front-primary-btn">
                        </div>
                    </div>
                </div>
                </div>
            </div>            
        </div>        
        <?php
            return ob_get_clean();
    }
    /**
    * Shortcode for listing all sign ups
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_all_listing_handler( $atts , $content = null ){
        ob_start();
        $view = "";
        if(!empty($atts)){
            if(array_key_exists("list-view" , $atts)){
                $view = $atts["list-view"];
            }
        }
        include "pto-color-settings.php";
        ?>
        <div class="pto-signup-plugin">
        <?php
        //echo $view;
        //if(is_user_logged_in()) {
            if($view == "vertical") { 
                $signup_title = get_option('signup_title');
                $no_date_sign_ups = get_option('no_date_sign_ups');
	            $repeating_sign_ups = get_option('repeating_sign_ups');
                $sortby_sing_ups = get_option('sortby_sing_ups');
                $sort_type = get_option('sort_type');
                $sort = "DESC";
                $sortby = "ID";
                $title_text_size = get_option('title_text_size');
                $title_text_color = get_option('title_text_color');
                if(!empty($title_text_size) || !empty($title_text_color)){ ?>
                    <style>
                        .pto-signup-shortcode h5.signup-post-title{
                            font-size: <?php echo intval($title_text_size)."px"; ?>;
                            color: <?php esc_html_e($title_text_color); ?>;
                        }
                    </style>
                <?php }
                if(!empty($sort_type)){
                    if($sort_type == "sort_ASC"){
                        $sort = "ASC";
                    }
                }
                if(!empty($sortby_sing_ups)){
                    if($sortby_sing_ups == "sort_by_name"){
                        $sortby = "title";
                    }                    
                }
                ?>
                <div class="main-vertical-signup main-signup-lists pto-custom-style pto-signup-shortcode">
                <div class="main-vertical-signup-lists-row">
                    <div class="vertical-signup-list-container">
                        <div class="vertical-signup-list-head">
                            <?php 
                                if($signup_title != ""){
                            ?>
                            <div class="vertical-signup-title">
                                <span><?php esc_html_e($signup_title); ?></span>
                            </div>
                            <?php } ?>
                            <div class="pto_sort d-flex align-center">
                            <div class="pto-sorting">
                                <div class="pto-sortby">
                                    <?php if($sortby_sing_ups == "sort_by_name"){ ?>                        
                                        <a href="#0" class="sort-by  front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sortby="date" >Sort by Date</a>                        
                                    <?php } if($sortby_sing_ups == "sort_by_date"){ ?>
                                        <a href="#0" class="sort-by front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sortby="name" >Sort by Name</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="pto-sorting-type">                        
                                <div class="pto-sort-type">
                                    <?php if($sort_type == "sort_ASC"){ ?>
                                    <a href="#0" class="sort-type   front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sorttype="DESC" >DESC</a>
                                    <?php }else{ ?>
                                    <a href="#0" class="sort-type   front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sorttype="ASC" >ASC</a>
                                    <?php } ?>
                                </div>                                           
                            </div>
                            </div>
                        </div>
                    <div class="vertical-signup-listing">
                    <ul class="vertical-signup">
                    <?php  
                    if(!empty($sortby_sing_ups)){
                       if($sortby_sing_ups == "sort_by_date"){
                            $args = array(
                                'post_type'=> 'pto-signup',                                
                                'meta_key' => 'occurrence_specific_days',
                                'orderby' => 'meta_value',
                                'post_status' => 'publish',
                                'order'    => $sort,
                                'posts_per_page' => 7, // this will retrive all the post that is published 
                                'meta_query' => array(
                                    array(
                                        'key' => 'listing_listing',
                                        'value' => 'on',
                                        'compare' => '=',
                                    ),
                                    array(
                                        'key' => 'occurrence_specific_days',                                        
                                    )
                                )                            
                            );
                        }
                        else{
                            $args = array(
                                'post_type'=> 'pto-signup',
                                'orderby'    => $sortby,
                                'post_status' => 'publish',
                                'order'    => $sort,
                                'posts_per_page' => 7, // this will retrive all the post that is published 
                                'meta_query' => array(
                                    array(
                                        'key' => 'listing_listing',
                                        'value' => 'on',
                                        'compare' => '=',
                                    )
                                )                            
                            );
                        }
                    }
                    else{
                        $args = array(
                            'post_type'=> 'pto-signup',
                            'orderby'    => $sortby,
                            'post_status' => 'publish',
                            'order'    => $sort,
                            'posts_per_page' => 7, // this will retrive all the post that is published 
                            'meta_query' => array(
                                array(
                                    'key' => 'listing_listing',
                                    'value' => 'on',
                                    'compare' => '=',
                                )
                            )                            
                        );
                    }
                    
                    $result = new \WP_Query( $args );
                    
                    if ( $result-> have_posts() ) {
                        while ( $result->have_posts() ) {                                 
                            $result->the_post();
                            $post_id =  get_the_ID();
                          
                            
                            $specific_day = "";
                            $arykey = "";
                            $get_recurrence_data = "";
                            //$time_set = get_post_meta($post_id, "pto_sign_ups_time_set", true);
                            $pto_sign_up_occurrence =  get_post_meta($post_id,"pto_sign_up_occurrence",true); 
                            if(array_key_exists("occurrence-not-specific",$pto_sign_up_occurrence)){
                                $arykey = "occurrence-not-specific";
                            }
                            if(array_key_exists("occurrence-multipal-days",$pto_sign_up_occurrence)){
                                $get_recurrence_data =  get_post_meta($post_id,"pto_task_recurreence",true);
                                $arykey = "occurrence-multipal-days";
                            }
                            if(array_key_exists("occurrence-specific",$pto_sign_up_occurrence)){
                                $specific_day = get_post_meta($post_id,"occurrence_specific_days",true);
                                $arykey = "occurrence-specific";
                            }
                            if($no_date_sign_ups == "" && $arykey == "occurrence-not-specific"){
                            }
                            elseif($repeating_sign_ups == "" && $arykey == "occurrence-multipal-days"){ 
                            }
                            else{                           
                            ?>
                            <li class="single-signup-list">
                                <div class="single-signup-block">
                                <a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="post-title-link"><h5 class="signup-post-title"><?php esc_html_e(the_title()); ?></h5></a>
                                    <?php if(!empty($specific_day)){ ?>
                                    <span class="signup-date"><?php  esc_html_e($specific_day); ?> </span> 
                                    <?php }elseif(!empty($get_recurrence_data)){ ?> 
                                    <span class="signup-date">
                                        Repeat every &nbsp;
                                        <?php  
                                        if(array_key_exists( "daysofevery", $get_recurrence_data )){ 
                                            echo intval($get_recurrence_data["daysofevery"]); 
                                        }
                                        if(array_key_exists( "to_sign_up_div_repeate_time", $get_recurrence_data )){ 
                                            esc_html_e($get_recurrence_data["to_sign_up_div_repeate_time"]); 
                                        }
                                        if(array_key_exists("end_data",$get_recurrence_data)){ 
                                            $end_data =  $get_recurrence_data["end_data"]; 
                                            if(array_key_exists("after",$end_data)){
                                                ?>
                                                , End After 
                                                <?php
                                                echo intval($end_data["after"]);
                                                ?>
                                                occurrences
                                                <?php
                                            }
                                            if(array_key_exists("on",$end_data)){
                                                ?>
                                                , End On 
                                                <?php
                                                esc_html_e($end_data["on"]);
                                            }
                                            if(array_key_exists("never",$end_data)){
                                                ?>
                                                , End Never
                                                <?php
                                            }
                                        }
                                        //print_r($get_recurrence_data); 
                                        ?> 
                                    </span> 
                                    <?php } ?>
                                </div>
                            </li>
                            <?php 
                            }
                        }                            
                    }
                    wp_reset_postdata();
                    ?>
                    </ul>
                    </div>
                    </div>
                </div>
                </div>
            <?php
            } 
            else {
            ?>
            <div class="main-signup-lists pto-signup-shortcode pto-custom-style">
                <div class="main-signup-lists-row">
                <div class="signup-list" id="tabs">
                    <div class="signup-list-tab-row">
                        <ul class="tabs pto-signup-tabs">
                            <li class="tab-link" ><a href="#all-signup"><?php esc_html_e('All Sign Ups',PTO_SIGN_UP_TEXTDOMAIN);?></a></li>
                            <?php if(is_user_logged_in()) { ?>
                            <li class="tab-link" ><a href="#my-signup"><?php esc_html_e('My Sign Ups',PTO_SIGN_UP_TEXTDOMAIN);?></a></li>
                            <?php } ?>
                        </ul>
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
                            $cuser_id = get_current_user_id();
                            $role_array = array();
                            if($cuser_id != 0){
                                $userdata = get_userdata( $cuser_id );                            
                                foreach($userdata->roles as $key => $roles)
                                {
                                    $role_array[$roles] = $roles;
                                }
                            }  
                            ?>
                            <div class='pto_cart_hand'>
                           
                            <div  class='pto-cart-signup' >
                                <a  href='<?php echo esc_url( $url ); ?>' class='pto-cart-tasks-count'><i class="fa fa-hand-pointer-o"></i>
                                     <?php if( $taskcount != 0 ){  ?><span><?php echo intval($taskcount); ?></span> <?php } ?></a>
                            </div>
                           
                            <?php                          
                            if( array_key_exists("own_sign_up", $role_array) || array_key_exists("administrator", $role_array) || array_key_exists("sign_up_plugin_administrators", $role_array)){
                        ?>




                        <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post-new.php?post_type=pto-signup" class="add-new-btn btn_add front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" ><?php esc_html_e('Add new',PTO_SIGN_UP_TEXTDOMAIN);?></a>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="tab-data all-signup-show-box">
                    <div id="all-signup" class="tab-content">
                        <ul class="signup-list-block">
                            <?php 
                                $args = array(
                                    'post_type'=> 'pto-signup',
                                    'orderby'    => 'ID',
                                    'post_status' => 'publish',
                                    'order'    => 'DESC',
                                    'posts_per_page' => -1, // this will retrive all the post that is published 
                                    'meta_query' => array(
                                        array(
                                            'key' => 'listing_listing',
                                            'value' => 'on',
                                            'compare' => '=',
                                        )
                                    )                            
                                );
                                $result = new \WP_Query( $args );                        
                                if ( $result-> have_posts() ) {
                                    while ( $result->have_posts() ) {                                 
                                        $result->the_post();
                                        $post_id =  get_the_ID();
                                        $c_user_id = get_current_user_id();
                                        $crole = "";
                                        $get_user_post = array();
                                        if($c_user_id != 0){
                                            $user = get_userdata( $c_user_id );
                                            
                                            $user_roles = $user->roles;
                                            foreach($user_roles as $key => $value){
                                                $crole = $value;
                                            }
                                            $get_user_post = get_user_meta( $c_user_id, 'pto_signup_request_id', true );
                                            if(empty($get_user_post)){ 
                                                $get_user_post = array();
                                            }
                                        }
                                        
                                        $author_id = get_post_field( 'post_author', $post_id );                                        
                                        
                                        $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true);
                                        
                                        $time_set = get_post_meta($post_id, "pto_sign_ups_time_set", true);
                                        $pto_sign_up_occurrence = get_post_meta( $post_id, "pto_sign_up_occurrence", true );
                                        
                                        if(empty($get_user_req_post)){ 
                                            $get_user_req_post = array();
                                        } 
                                       // print_r($get_user_req_post);
                                        if(!empty($time_set)){
                                            $opentime = $time_set['opendate']." ".$time_set['opentime'];
                                            $opentime = strtotime($opentime);                                            
                                            $closetime = $time_set['closedate']." ".$time_set['closetime'];                                            
                                            $closetime = strtotime($closetime);
                                            //$opendate = $time_set['opendate'];
                                            //$closedate = $time_set['closedate'];
                                            //$currentdate = date('Y-m-d');
                                            $timezone_format = _x('Y-m-d H:i', 'timezone date format' );
                                            $currenttime =  date_i18n( $timezone_format ); 
                                            $currenttime = strtotime($currenttime);  
                                            
                                            if($currenttime < $opentime){  
                                               
                                                continue;
                                            }
                                             
                                            if($closetime < $currenttime){   
                                                //continue;
                                            }
                                           
                                            /*if($currentdate < $opendate && $currenttime < $opentime){                                               
                                                continue;
                                            }
                                            if($closedate < $currentdate && $closetime < $currenttime){                                               
                                                continue;
                                            }*/
                                        }
                                        
                                        ?>
                                        <li class="single-signup-list">
                                            <div class="single-signup-block">
                                                <div class="small-signup-banner-img">
                                                    <?php 
                                                        if ( has_post_thumbnail() ) { 
                                                            $imgurl = get_the_post_thumbnail_url(get_the_ID());
                                                        }
                                                        else{
                                                            $imgurl = PTO_SIGN_UP_PLUGIN_DIR."assets/img/noimg.png";
                                                        }
                                                    ?>
                                                    <img src="<?php echo esc_url($imgurl);  ?>">
                                                </div>
                                                <div class="single-signup-info edit-url">
                                                    <?php  if($c_user_id != 0){ if(($c_user_id == $author_id ) || (in_array($c_user_id , $get_user_req_post)) || (in_array("sign_up_plugin_administrators", $user_roles)) || (in_array("administrator", $user_roles)) || $crole == "sign_up_plugin_administrators" || $crole == "administrator"){ ?>
                                                    <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post.php?post=<?php echo intval($post_id); ?>&action=edit"><input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="signup-access-btn front-primary-btn" value="Edit"></a>
                                                    <?php }else{ 
                                                        $show_request_access_btn = get_option('show_request_access_btn');
                                                        if(!empty($show_request_access_btn)){
                                                        
                                                            if((in_array($post_id, $get_user_post))){ ?>
                                                            <span>Requested</span>
                                                            <?php
                                                            }else{
                                                            ?>
                                                            <a><input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" data-id="<?php echo intval($post_id); ?>" id="signup-access-btn front-primary-btn" value="Request access"></a>
                                                    <?php } } } } ?>
                                                    <a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="post-title-link"><h5 class="post-title"><?php esc_html_e(the_title()); ?></h5></a>
                                                </div>
                                            </div>
                                        </li>
                                        <?php
                                    }                            
                                }
                                else{
                                    echo "No Sign Up found.";
                                } 
                                wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                    <?php if(is_user_logged_in()) { ?>
                    <div id="my-signup" class="tab-content">
                        <ul class="signup-list-block">
                            <?php 
                                $mysignup = 0;
                                $c_user_id = get_current_user_id();
                                $args = array(
                                    'post_type'=> 'pto-signup',
                                    'orderby'    => 'ID',
                                    'post_status' => 'publish',
                                    'order'    => 'DESC',
                                    //'author'   =>   $c_user_id,
                                    'posts_per_page' => -1, // this will retrive all the post that is published 
                                    'meta_query' => array(
                                        array(
                                            'key' => 'listing_listing',
                                            'value' => 'on',
                                            'compare' => '=',
                                        )
                                    ) 
                                );
                                $result = new \WP_Query( $args );
                                if ( $result-> have_posts() ) {                                   
                                    while ( $result->have_posts() ) { 
                                        $result->the_post();
                                        $post_id =  get_the_ID();
                                        $c_user_id = get_current_user_id();
                                        $author_id = get_post_field( 'post_author', $post_id );
                                        $crole = "";
                                        if($c_user_id != 0){
                                            $user = get_userdata( $c_user_id );
                                            $user_roles = $user->roles;
                                            foreach($user_roles as $key => $value){
                                                $crole = $value;
                                            }
                                        }                                           
                                        $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator' ,true);
                                        if(empty($get_user_req_post)){ 
                                            $get_user_req_post = array();
                                        }
                                        $time_set = get_post_meta($post_id, "pto_sign_ups_time_set", true);
                                        if(!empty($time_set)){
                                            $opentime = $time_set['opendate']." ".$time_set['opentime'];
                                            $opentime = strtotime($opentime);                                            
                                            $closetime = $time_set['closedate']." ".$time_set['closetime'];                                            
                                            $closetime = strtotime($closetime);
                                            $timezone_format = _x('Y-m-d H:i', 'timezone date format' );
                                            $currenttime =  date_i18n( $timezone_format ); 
                                            $currenttime = strtotime($currenttime);                                                                                 
                                            if($currenttime < $opentime){  
                                               
                                                continue;
                                            }
                                            // if($closetime < $currenttime){   
                                            //     continue;
                                            // }
                                        }
                                        if(($c_user_id == $author_id ) || (in_array($c_user_id, $get_user_req_post)) ) { 
                                            $mysignup = 1;
                                            ?>
                                            <li class="single-signup-list">
                                                <div class="single-signup-block">
                                                <div class="small-signup-banner-img">
                                                    <?php 
                                                        if ( has_post_thumbnail() ) { 
                                                            $imgurl = get_the_post_thumbnail_url(get_the_ID());
                                                        }
                                                        else{
                                                            $imgurl = PTO_SIGN_UP_PLUGIN_DIR."assets/img/noimg.png";
                                                        }
                                                    ?>
                                                    <img src="<?php echo esc_url($imgurl); ?>">                                        
                                                </div>
                                                <div class="single-signup-info edit-url">
                                                    <a href="<?php echo esc_url(site_url()); ?>/wp-admin/post.php?post=<?php echo intval($post_id); ?>&action=edit"><input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="signup-access-btn front-primary-btn" value="Edit"></a>                                            
                                                    <a href="<?php echo esc_url(get_the_permalink($post_id)); ?>" class="post-title-link"><h5 class="post-title"><?php esc_html_e(the_title()); ?></h5></a>
                                                </div>
                                                </div>
                                            </li>
                                            <?php 
                                        }
                                    }                        
                                }
                                else{
                                    //echo "No Sign Up found.";
                                }   
                                if($mysignup == 0){
                                    ?> 
                                    No Sign Up found 
                                    <?php
                                }
                                wp_reset_postdata();
                            ?>
                        </ul>
                    </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="search-signup-block">
                    <div class="search-signup">
                        <h4 for="search"><?php esc_html_e('Sign Up Search',PTO_SIGN_UP_TEXTDOMAIN);?></h4>
                        <div class="search-signup-form-box">
                            <div class="cust-field cust-field">
                                <label for="search"><?php esc_html_e('Search for a sign up name',PTO_SIGN_UP_TEXTDOMAIN);?></label>
                                <div class='pto-signup-search'>
                                    <input type="text" id="search-signup" data class="s-signup" name="search-signup">
                                    <i class="fa fa-times close-text" onclick="jQuery('#search-signup').val('')" aria-hidden="true"></i>
                                </div>
                            </div>
                            <input type="submit" value="Submit" class="search-signup-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="search-signup-btn front-primary-btn">
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <?php 
            } 
        /*}
        else{
            echo "Please <a href=".site_url()."/wp-admin/>login</a> to view Signups.";
        }*/
        ?>
        </div>        
        <?php
            return ob_get_clean();
    }
    /**
    * Sign up search
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_search() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $allsignuphtml = "";
            $mysignuphtml = "";
            if ( isset( $_POST['search_text'] ) ) {
                $search_text = sanitize_text_field( $_POST['search_text'] ); 
                if ( !empty( $search_text ) ) {
                    $args = array(
                        'post_type'=> 'pto-signup',
                        'orderby'    => 'ID',
                        'post_status' => 'publish',
                        'order'    => 'DESC',
                        'posts_per_page' => -1,
                        's' => $search_text, // this will retrive all the post that is in search keywords 
                        'meta_query' => array(
                            array(
                                'key' => 'listing_listing',
                                'value' => 'on',
                                'compare' => '=',
                            )
                        )                            
                    );
                }
                else {
                    $args = array(
                        'post_type'=> 'pto-signup',
                        'orderby'    => 'ID',
                        'post_status' => 'publish',
                        'order'    => 'DESC',
                        'posts_per_page' => -1,                        
                        'meta_query' => array(
                            array(
                                'key' => 'listing_listing',
                                'value' => 'on',
                                'compare' => '=',
                            )
                        )                            
                    );
                }                               
                $result = new \WP_Query( $args );                 
                if ( $result-> have_posts() ) {
                    while ( $result->have_posts() ) {                                 
                        $result->the_post();
                        $post_id =  get_the_ID();
                        $c_user_id = get_current_user_id();
                        $user = get_userdata( $c_user_id );
                        $user_roles = $user->roles;
                        foreach ( $user_roles as $key => $value ) {
                            $crole = $value;
                        }
                        $author_id = get_post_field( 'post_author', $post_id );
                        $get_user_req_post = get_post_meta( $post_id, 'pto_assign_user_administrator', true );
                        if ( empty( $get_user_req_post ) ) { 
                            $get_user_req_post = array();
                        } 
                        $time_set = get_post_meta( $post_id, "pto_sign_ups_time_set", true );
                        if ( !empty( $time_set ) ) {
                            $opentime = $time_set['opendate']." ".$time_set['opentime'];
                            $opentime = strtotime( $opentime );                                            
                            $closetime = $time_set['closedate']." ".$time_set['closetime'];                                            
                            $closetime = strtotime( $closetime );
                            $timezone_format = _x('Y-m-d H:i', 'timezone date format' );
                            $currenttime =  date_i18n( $timezone_format ); 
                            $currenttime = strtotime( $currenttime );                                                                                 
                            if ( $currenttime < $opentime ) {  
                               
                                continue;
                            }
                            if ( $closetime < $currenttime ) {   
                                continue;
                            }
                        }
                        if ( has_post_thumbnail() ) { 
                            $imgurl = get_the_post_thumbnail_url( get_the_ID() );
                        }
                        else{
                            $imgurl = PTO_SIGN_UP_PLUGIN_DIR."assets/img/noimg.png";
                        }  
                        /* for all signup tab */
                        $allsignuphtml .= '<li class="single-signup-list">';
                        $allsignuphtml .= '<div class="single-signup-block">';
                        $allsignuphtml .= '<div class="small-signup-banner-img"><img src="'. $imgurl .'"></div>';
                        $allsignuphtml .= '<div class="single-signup-info edit-url">';
                        if ( ( $c_user_id == $author_id ) || ( in_array( $c_user_id , $get_user_req_post ) ) || $crole == "sign_up_plugin_administrators" || $crole == "administrator" ) {
                            $allsignuphtml .= '<a href="'. site_url() .'/wp-admin/post.php?post='. $post_id .'&action=edit"><input type="submit" class="signup-access-btn front-primary-btn" id="signup-access-btn front-primary-btn" value="You are a manager"></a>';
                        } else {
                            $show_request_access_btn = get_option( 'show_request_access_btn' );
                            if ( !empty( $show_request_access_btn ) ) {
                                $allsignuphtml .= '<a><input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" data-id="' . $post_id .'" id="signup-access-btn front-primary-btn" value="Request access"></a>';
                            }      
                        }
                        $allsignuphtml .= '<a href="' . get_the_permalink( $post_id ) . '" class="post-title-link"><h5 class="post-title">'.get_the_title( $post_id ).'</h5></a>';
                        $allsignuphtml .= '</div></div></li>'; 
                        /* for my sign up tab */
                        if ( ( $c_user_id == $author_id ) || ( in_array( $c_user_id, $get_user_req_post ) ) ) { 
                            $mysignuphtml .= '<li class="single-signup-list">';
                            $mysignuphtml .= '<div class="single-signup-block">';
                            $mysignuphtml .= '<div class="small-signup-banner-img"><img src="' . $imgurl . '"></div>';                                        
                            $mysignuphtml .= '<div class="single-signup-info edit-url">';
                            $mysignuphtml .= '<a href="' . site_url() . '/wp-admin/post.php?post=' . $post_id . '&action=edit"><input type="submit" class="signup-access-btn front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" id="signup-access-btn front-primary-btn" value="You are a manager"></a>';                                            
                            $mysignuphtml .= '<a href="' . get_the_permalink( $post_id ) . '" class="post-title-link"><h5 class="post-title">' . get_the_title( $post_id ) . '</h5></a>';
                            $mysignuphtml .= '</div></div></li>';                            
                        }
                    }                            
                }
                wp_reset_postdata();               
            }
        }
        $data['all_signup_data'] =  $allsignuphtml;
        $data['my_signup_data'] =  $mysignuphtml;
        echo json_encode( $data );
        die();
    }
}