<?php
/**
* PTO class for initiating necessary actions and core functions.
*/
/*
* Defining Namespace
*/
namespace ptofficesignup\classes;
class PtoSignUp {
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
        /* Meeting cpt */
        add_action( 'init', array( $this, 'pto_sign_up_cpt' ) );
        /* remove Ghutarn burg editor */
        add_filter( 'use_block_editor_for_post_type',  array( $this, 'pto_sign_up_digwp_disable_gutenberg' ), 10, 2 );
        /* add medta box for tabs */
        add_action( 'add_meta_boxes', array( $this, "pto_sign_up_meta_tabs" ) );
        /* get all task slots  from this post*/
        add_action( 'wp_ajax_nopriv_pto_signup_task_slots_single', array( $this, 'pto_sign_up_task_slots_single' ) );
        add_action( 'wp_ajax_pto_signup_task_slots_single', array( $this, 'pto_sign_up_task_slots_single' ) );
        /* delete  custom fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_get_task_slots_cpt_delete', array( $this, 'pto_sign_up_get_task_slots_cpt_delete' ) );
        add_action( 'wp_ajax_pto_signup_get_task_slots_cpt_delete', array( $this, 'pto_sign_up_get_task_slots_cpt_delete' ) );
        
        /* task/slot bulk action filter */
        add_action( 'wp_ajax_nopriv_pto_sign_ups_task_bulk_filter', array( $this, 'pto_sign_up_task_bulk_filter' ) );
        add_action( 'wp_ajax_pto_sign_ups_task_bulk_filter', array( $this, 'pto_sign_up_task_bulk_filter' ) );
        /* task/slot bulk action filter monthly */
        add_action( 'wp_ajax_nopriv_pto_sign_ups_task_bulk_filter_month', array( $this, 'pto_sign_up_task_bulk_filter_month' ) );
        add_action( 'wp_ajax_pto_sign_ups_task_bulk_filter_month', array( $this, 'pto_sign_up_task_bulk_filter_month' ) );
        /* delete permanent tasks/slots */
        add_action( 'wp_ajax_nopriv_pto_signup_delete_permanent_task_slots', array( $this, 'pto_sign_up_delete_permanent_task_slots' ) );
        add_action( 'wp_ajax_pto_signup_delete_permanent_task_slots', array( $this, 'pto_sign_up_delete_permanent_task_slots' ) );
        /* restore tasks/slots */
        add_action( 'wp_ajax_nopriv_pto_signup_restore_task_slots', array( $this, 'pto_sign_up_restore_task_slots' ) );
        add_action( 'wp_ajax_pto_signup_restore_task_slots', array( $this, 'pto_sign_up_restore_task_slots' ) );
        /* show trash tasks/slots */
        add_action( 'wp_ajax_nopriv_pto_signup_show_trash_task_slots', array( $this, 'pto_sign_up_show_trash_task_slots' ) );
        add_action( 'wp_ajax_pto_signup_show_trash_task_slots', array( $this, 'pto_sign_up_show_trash_task_slots' ) );
        
        /* get all task slots  from this post*/
        add_action( 'wp_ajax_nopriv_pto_signup_task_slots_single_dragable', array( $this, 'pto_sign_up_task_slots_single_dragable' ) );
        add_action( 'wp_ajax_pto_signup_task_slots_single_dragable', array( $this, 'pto_sign_up_task_slots_single_dragable' ) );
        /* get all checkout fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_custom_fields_checkout', array( $this, 'pto_sign_up_custom_fields_checkout' ) );
        add_action( 'wp_ajax_pto_signup_custom_fields_checkout', array( $this, 'pto_sign_up_custom_fields_checkout' ) );
        add_action( 'wp_ajax_nopriv_pto_signup_custom_fields_checkouts', array( $this, 'pto_sign_up_custom_fields_checkouts' ) );
        add_action( 'wp_ajax_pto_signup_custom_fields_checkouts', array( $this, 'pto_sign_up_custom_fields_checkouts' ) );
        /* signups users get*/
        add_action( 'wp_ajax_nopriv_pto_signup_get_assign_user', array( $this, 'pto_sign_up_get_assign_user' ) );
        add_action( 'wp_ajax_pto_signup_get_assign_user', array( $this, 'pto_sign_up_get_assign_user' ) );
        /* get manage volunteer users */
        add_action( 'wp_ajax_nopriv_pto_signup_get_manage_user', array( $this, 'pto_sign_up_get_manage_user' ) );
        add_action( 'wp_ajax_pto_signup_get_manage_user', array( $this, 'pto_sign_up_get_manage_user' ) );
        /* own signups users settings */
        add_action( 'wp_ajax_nopriv_pto_own_signup_settings_for_users', array( $this, 'pto_sign_up_own_signup_settings_for_users' ) );
        add_action( 'wp_ajax_pto_own_signup_settings_for_users', array( $this, 'pto_sign_up_own_signup_settings_for_users' ) );
        /* add new users signups*/
        add_action( 'wp_ajax_nopriv_pto_sign_ups_new_users_add_get', array( $this, 'pto_sign_up_new_users_add_get' ) );
        add_action( 'wp_ajax_pto_sign_ups_new_users_add_get', array( $this, 'pto_sign_up_new_users_add_get' ) );
        /* add new users signups*/
        add_action( 'wp_ajax_nopriv_pto_sign_ups_new_users_add_remove', array( $this, 'pto_sign_up_new_users_add_remove' ) );
        add_action( 'wp_ajax_pto_sign_ups_new_users_add_remove', array( $this, 'pto_sign_up_new_users_add_remove' ) );
        /* add new users signups*/
        add_action( 'wp_ajax_nopriv_pto_sign_ups_time_set', array( $this, 'pto_sign_up_time_set' ) );
        add_action( 'wp_ajax_pto_sign_ups_time_set', array( $this, 'pto_sign_up_time_set' ) );
        /* add new users signups*/
        add_action( 'wp_ajax_nopriv_pto_task_get_data', array( $this, 'pto_sign_up_task_get_data' ) );
        add_action( 'wp_ajax_pto_task_get_data', array( $this, 'pto_sign_up_task_get_data' ) );
        /* sign up cpt  save data */
        add_action( 'save_post_pto-signup', array( $this, 'pto_sign_up_post_sign_up_save' ), 20, 2 );  
        /* signups set 2 save data */ 
        add_action( 'wp_ajax_nopriv_pto_sign_up_step_two', array( $this, 'pto_sign_up_step_two' ) );
        add_action( 'wp_ajax_pto_sign_up_step_two', array( $this, 'pto_sign_up_step_two' ) );
        /* signup add Manage Volunteers option */
        add_filter( 'post_row_actions', array( $this, 'pto_sign_up_manage_volunteers_post_link' ) , 10, 2 );
        /* delete  custom fileds */
        add_action( 'wp_ajax_nopriv_pto_signup_get_custom_fields_delete', array( $this, 'pto_sign_up_get_custom_fields_delete' ) );
        add_action( 'wp_ajax_pto_signup_get_custom_fields_delete', array( $this, 'pto_sign_up_get_custom_fields_delete' ) );
    }
    /**
    * Custom field delete
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_custom_fields_delete() {
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
    
            $cpt_custom_fileds =  get_post_meta( $current_post_id, "single_task_custom_fields_checkout", true );
            if ( ( $key = array_search( $custom_fid, $cpt_custom_fileds ) ) !== false ) {               
                unset( $cpt_custom_fileds[ $key ] );
            }
            update_post_meta( $current_post_id, "single_task_custom_fields_checkout", $cpt_custom_fileds );           
            include "structure/pto_custom_fileds.php";  
            die();
        }
    }
    /**
    * Manage volunteers post link
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_manage_volunteers_post_link( $actions, $post ) {
        if ( !current_user_can('edit_posts') ) {
            return $actions;
        }
        if ( $post->post_type == "pto-signup" ) {
            $url = "admin.php?page=managevolunteer&sign_ups=".$post->ID;            
            $actions['volunteers'] = '<a href="' . $url . '" title="Manage Volunteers for this item" rel="permalink">Manage Volunteers</a>';
        }
        return $actions;
    }
    /**
    * Sign up save post
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_post_sign_up_save( $post_id, $post ) {
        if ( $_POST ) {
            /* step 1 save data */
            if ( isset( $_POST['signupdescreption'] ) ) 
                update_post_meta( $post_id, "pto_sign_up_signupdescreption", wp_kses_post($_POST['signupdescreption']) );
            else
                update_post_meta( $post_id, "signupdescreption", "" ); 
            if ( isset( $_POST['address1'] ) ) {
                $address= array(
                    "address1" =>  sanitize_text_field($_POST['address1']),
                    "address2" =>  sanitize_text_field($_POST['address2']),
                    "city" =>  sanitize_text_field($_POST['city']),
                    "state" =>  sanitize_text_field($_POST['state']),
                    "postalcode" =>  sanitize_text_field($_POST['postalcode']),
                );
                update_post_meta( $post_id, "pto_sign_up_address", $address );
            }
        
            if ( isset( $_POST['publish_date'] ) ) {
                if ( $_POST['publish_date'] == "imediately_publish" ) {
                    $time_array= array();
                    update_post_meta( $post_id, "pto_sign_ups_time_set", $time_array );
                } else if ( $_POST['publish_date'] == "specifc_publish" ) {
                    $opendate = sanitize_text_field( $_POST['opendate'] );
                    $opentime = sanitize_text_field( $_POST['opentime'] );
                    $closedate = sanitize_text_field( $_POST['closedate'] );
                    $closetime = sanitize_text_field( $_POST['closetime'] );
                    $time_array= array(
                        "opendate" => $opendate,
                        "opentime" => $opentime,
                        "closedate" => $closedate,
                        "closetime" => $closetime
                    );
                    update_post_meta( $post_id, "pto_sign_ups_time_set", $time_array );
                }   
            }   
            /* step 2 save data */         
            if ( isset( $_POST['pto-radios-occurrence'] ) ) {
                if ( $_POST['pto-radios-occurrence'] == "occurrence-not-specific" ) {
                    $pto_sign_up_occurrence = array(
                        sanitize_text_field($_POST['pto-radios-occurrence']) => "occurrence-not-specific",
                    );
                    update_post_meta( $post_id, "pto_task_recurreence", "" );
                } elseif ( $_POST['pto-radios-occurrence'] == "occurrence-specific" ) {
                    $pto_sign_up_occurrence = array(
                        sanitize_text_field($_POST['pto-radios-occurrence']) => "occurrence-specific",
                    );
                    $specific_date = sanitize_text_field( $_POST['occurrence-specific-days'] );
                    update_post_meta( $post_id, "occurrence_specific_days", $specific_date );
                    update_post_meta( $post_id, "pto_task_recurreence", "" );
                } 
                update_post_meta( $post_id, "pto_sign_up_occurrence", $pto_sign_up_occurrence );
            }  
           
            /* step 3 save data */
            if ( isset( $_POST['contact-organizer'] ) ) {
                update_post_meta( $post_id, "contact_organizer", sanitize_text_field($_POST['contact-organizer']) );
            } else {
                update_post_meta( $post_id, "contact_organizer", "" );
            }
           
            
            
            if ( isset( $_POST['number-of-slots'] ) ) {
                $number_of_slots = sanitize_text_field( $_POST['number-of-slots'] );
                update_post_meta( $post_id, "number_of_slots", $number_of_slots );
            } else {
                update_post_meta( $post_id, "number_of_slots", "" );
            }
			if ( isset( $_POST['checkout_fields_sign_up'] ) ) {
                update_post_meta( $post_id, "checkout_fields_sign_up", sanitize_text_field($_POST['checkout_fields_sign_up'] ) );
            } else {
                update_post_meta( $post_id, "checkout_fields_sign_up", "" );
            }
			if ( isset( $_POST['agree_to_terms_sign_up'] ) ) {
                update_post_meta( $post_id, "agree_to_terms_sign_up", sanitize_text_field($_POST['agree_to_terms_sign_up'] ) );
            } else {
                update_post_meta( $post_id, "agree_to_terms_sign_up", "" );
            }
            if ( isset( $_POST['agree_to_terms'] ) ) {
                update_post_meta( $post_id, "agree_to_terms", wp_kses_post($_POST['agree_to_terms'] ) );
            } else {
                update_post_meta( $post_id, "agree_to_terms", "" );
            }
            if ( isset( $_POST['volunteer-after-sign-up'] ) ) {
                update_post_meta( $post_id, "volunteer_after_sign_up", sanitize_text_field($_POST['volunteer-after-sign-up'] ) );
            } else {
                update_post_meta( $post_id, "volunteer_after_sign_up", "" );
            }
           
            if ( isset( $_POST['send_reminder'] ) ) {
                update_post_meta( $post_id, "send_reminder", sanitize_text_field($_POST['send_reminder'] ));
            } else {
                update_post_meta( $post_id, "send_reminder", "" );
            }
            if ( isset( $_POST['volunteer_before_setting'] ) ) {
                update_post_meta( $post_id, "volunteer_before_setting", wp_kses_post($_POST['volunteer_before_setting']) );
            } else {
                update_post_meta( $post_id, "volunteer_before_setting", "" );
            }
            if ( isset( $_POST['receipts-section-number'] ) ) {
                update_post_meta( $post_id, 'receipts_section_number', intval($_POST['receipts-section-number'] ));
            } else {
                update_post_meta( $post_id, 'receipts_section_number', "" );
            }
            if ( isset( $_POST['receipts-section-periad'] ) ) {
                update_post_meta( $post_id, 'receipts_section_periad', sanitize_text_field($_POST['receipts-section-periad'] ) );
            } else {
                update_post_meta( $post_id, 'receipts_section_periad', "" );
            }
            if ( isset( $_POST['listing-listing'] ) ) {
                update_post_meta( $post_id, 'listing_listing', sanitize_text_field($_POST['listing-listing']) );
            } else {
                update_post_meta( $post_id, 'listing_listing', "" );
            }
            if ( isset( $_POST['volunteer-names'] ) ) {
                update_post_meta( $post_id, 'volunteer_names', sanitize_text_field($_POST['volunteer-names']) );
            } else {
                update_post_meta( $post_id, 'volunteer_names', "" );
            }           
        }
    }
    /**
    * Manage volunteer set date time 
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_get_data() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $post_id = intval( $_POST['post_id'] );
            $task_id = intval( $_POST['task_id'] );
            $userid = intval( $_POST['userid'] );
            $pto_sign_up_occurrence =  get_post_meta( $post_id, "pto_sign_up_occurrence", true );
            $filled = 0;
            $diff = 0;
            $total_volantears_sign_ups = 0;
            $single_tasks_advance_options = get_post_meta( $task_id, "single_tasks_advance_options", true );
            $specific_day = "";
            if ( array_key_exists( "occurrence-specific", $pto_sign_up_occurrence ) ) {
                $specific_day = get_post_meta( $post_id, "occurrence_specific_days", true );
            }
            $tasktime = "";
            if ( !empty( $single_tasks_advance_options ) ) { 
                if ( array_key_exists( "single", $single_tasks_advance_options ) ) {
                    if ( array_key_exists( "how_money_volunteers_sign_ups-times", $single_tasks_advance_options['single'] ) )
                    $tasktime = date( "H:i a", strtotime( $single_tasks_advance_options['single']['how_money_volunteers_sign_ups-times'] ) );
                }
            }
            if ( !empty( $single_tasks_advance_options ) ) {
                $get_availability = get_post_meta( $task_id, "signup_task_availability", true );
                
                if ( array_key_exists( "single", $single_tasks_advance_options ) ) {
                    $total_volantears = $single_tasks_advance_options['single']["how_money_volunteers"];
                    $total_volantears_sign_ups = $single_tasks_advance_options['single']["how_money_volunteers_sign_ups"];
                    if ( $total_volantears == "" ) {
                        $total_volantears = 0;
                    }
                    if ( $total_volantears_sign_ups == "" ) {
                        $total_volantears_sign_ups = 0;
                    }                 
                    $total = $total_volantears;
                    if ( !empty( $get_availability ) ) {
                        if ( $get_availability == $total ) {
                            $filled = 1;
                        } else {
                            $diff = $total - $get_availability;
                        }
                         
                    } else {
                    }
                } else if ( array_key_exists( "shift", $single_tasks_advance_options ) ) {                 
                    $total_volantears = $single_tasks_advance_options['shift']["volunteers_shift"];
                    $total_volantears_sign_ups = $single_tasks_advance_options['shift']["volunteers_shift_times"];
                    $shift_meta = $single_tasks_advance_options["shift"];
                    $count = 0;
                    if( array_key_exists( "first-shift", $shift_meta ) &&  array_key_exists( "last-end-shift", $shift_meta ) && array_key_exists( "how-long-shift", $shift_meta ) && array_key_exists( "between-shift-minutes", $shift_meta ) ) {
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
                    if( $total_volantears == "" ) {
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
                        if ( $get_availability == $total ) {
                            $filled = 1;
                        } else {
                            $diff = $total - $get_availability;
                        }
                    } else {
                    }
                }
            } else {
            }
            $shift_meta_time = array();
            $all_shifts = "";
            $current_user_shift = array();
            $get_shift_time = get_post_meta( $task_id, 'get_shift_time', true ); 
            if ( !empty( $get_shift_time ) ) {                
                if ( array_key_exists( $userid, $get_shift_time ) ) {
                    $current_user_shift = explode( ",", $get_shift_time[ $userid ] );        
                }
                foreach ( $get_shift_time as $uid ) {          
                    $all_shifts .= $uid;
                }
                $shift_meta_time = explode( ",", $all_shifts );     
            }	
            $usermax = 0;
            if ( $userid != 0 ) {
                $get_max_user_task_signup = get_user_meta( $userid, 'max_user_task_signup', true );																
                if ( !empty( $get_max_user_task_signup ) ) { 
                    $max_key = $post_id."_".$task_id;																
                    if ( array_key_exists( $max_key, $get_max_user_task_signup ) ) {
                        $usermax = $get_max_user_task_signup[ $max_key ];	
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
            if ( $filled != 1 && $usermax != $total_volantears_sign_ups && array_key_exists( "single", $single_tasks_advance_options ) ) {
                ?>
                <div class="task_setting_<?php echo intval( $task_id ); ?>">                       
                    <input type="hidden" name="task_id<?php echo intval( $post_id ); ?>[]" value="<?php esc_html_e( $task_id ); ?>">  
                    <input type="text" disabled="" class="task_ids" id="<?php echo intval( $task_id ); ?>" name="title" value="<?php esc_html_e( get_the_title( $task_id ) ); ?>">
                    <input type="text" disabled name="task_date" <?php if ( empty( $specific_day ) ) { ?> style="visibility: hidden;" <?php } ?> value="<?php if ( !empty( $specific_day ) ) { esc_html_e( $specific_day ); } ?>" class="open-date" >
                    <input type="text" disabled name="task_time" value="<?php esc_html_e( $tasktime ); ?>" <?php if ( empty( $tasktime ) ) { ?> style="visibility: hidden;" <?php } ?>>
                    <input type="hidden" name="task_date<?php esc_html_e( $task_id ); ?>[]" value="<?php esc_html_e( $specific_day ); ?>" class="open-date" >
                    <input type="hidden" name="task_time<?php esc_html_e( $task_id ); ?>[]" value="<?php esc_html_e( $tasktime ); ?>" >
                    <?php 
                    if ( array_key_exists( "single", $single_tasks_advance_options ) ) {
                    ?>
                    <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" >
                        <?php 																	
                            for ( $i=0; $i<=$max; $i++ ) { ?>
                            <option value="<?php echo intval( $i ); ?>"><?php echo intval( $i ); ?></option>
                            <?php
                            }
                        ?>
                    </select>
                    <?php } ?>
                </div>
                <?php
            }
            else{                
            } 
            if ( array_key_exists( "shift", $single_tasks_advance_options ) && $filled != 1 ) {
            ?>
                <div class="task_setting_<?php echo intval( $task_id ); ?> sign-up-task-shift-block">                       
                    <input type="hidden" name="task_id<?php echo intval( $post_id ); ?>[]" value="<?php echo intval( $task_id ); ?>" class="sign-up-task">  
                    <input type="text" disabled="" class="task_ids" id="<?php echo intval( $task_id ); ?>" name="title" value="<?php esc_html_e( get_the_title( $task_id ) ); ?>">
                    <input type="text" disabled name="task_date" <?php if ( empty( $specific_day ) ) { ?> style="visibility: hidden;" <?php } ?> value="<?php if ( !empty( $specific_day ) ) { esc_html_e( $specific_day ); } ?>" class="open-date" >
                    <input type="text" disabled name="task_time" value="<?php esc_html_e( $tasktime ); ?>" <?php if ( empty( $tasktime ) ) { ?> style="visibility: hidden;" <?php } ?>>
                    <input type="hidden" name="task_date<?php esc_html_e( $task_id ); ?>[]" value="<?php esc_html_e( $specific_day ); ?>" class="open-date" >
                    <input type="hidden" name="task_time<?php esc_html_e( $task_id ); ?>[]" value="<?php esc_html_e( $tasktime ); ?>" class="task-shift-time" >
                    
                    <select name="pto_signup_task_max[]"  class="pto-singup-task-max-number-select" style="visibility: hidden;">
                        <option value="0">0</option>
                    </select>
                    <?php 
                    $shift_meta = $single_tasks_advance_options["shift"];
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
                                        $shift_endtime = date( "h:i A", ( strtotime( $array_of_time[ $i ] ) + $add_mins ) );																																					
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
                                                        <label><?php $tmp = $array_of_time[ $i ]."-".$shift_endtime; esc_html_e( $tmp ); ?></label>																					
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
                                            else{ 
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
                        } ?>
                    </div>
                    <?php
                } else { 
                } 
            die();
        }
    }
    /**
    * Sign up time set
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_time_set() {
        // Check for nonce security      
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $opendate = sanitize_text_field( $_POST['opendate'] );
            $opentime = sanitize_text_field( $_POST['opentime'] );
            $closedate = sanitize_text_field( $_POST['closedate'] );
            $closetime = sanitize_text_field( $_POST['closetime'] );
            $time_array = array(
                "opendate" => $opendate,
                "opentime" => $opentime,
                "closedate" => $closedate,
                "closetime" => $closetime
            );
            $post_id = intval( $_POST['post_id'] );
            update_post_meta( $post_id, "pto_sign_ups_time_set", $time_array );
        }
        die();
    }
    /**
    * Register sign up CPT
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_cpt() {
        $my_theme = get_option( 'stylesheet' );
        // Set UI labels for Custom Post Type
        $labels = array(
            'name' => _x('Sign Ups', 'Post Type General Name') ,
            'singular_name' => _x('SignUp', 'Post Type Singular Name') ,
            'menu_name' => __('Sign Ups', $my_theme) ,
            'parent_item_colon' => __('Parent task', $my_theme) ,
            'all_items' => __('All Sign Ups', $my_theme) ,
            'view_item' => __('View Sign Up', $my_theme) ,
            'add_new_item' => __('Add New Sign Up', $my_theme) ,
            'add_new' => __('Add Sign Up', $my_theme) ,
            'edit_item' => __('Edit Sign Up', $my_theme) ,
            'update_item' => __('Update Sign Up', $my_theme) ,
            'search_items' => __('Search Sign Up', $my_theme) ,
            'not_found' => __('Not Found', $my_theme) ,
            'not_found_in_trash' => __('Not found in Trash', $my_theme) ,
            // Overrides the “Featured Image” label
            'featured_image'        => __( 'Banner Image', $my_theme ),
            // Overrides the “Set featured image” label
            'set_featured_image'    => __( 'Set banner image', $my_theme ),
            // Overrides the “Remove featured image” label
            'remove_featured_image' => _x( 'Remove banner image', $my_theme ),
            // Overrides the “Use as featured image” label
            'use_featured_image'    => _x( 'Use as banner image', $my_theme ),
        );
        // Set other options for Custom Post Type
        $args = array(
            'label' => __('Sign Ups', $my_theme) ,
            'description' => __('Sign Ups news and reviews', $my_theme) ,
            'labels' => $labels,
            // Features this CPT supports in Post Editor
            'supports' => array(
                'title', 'thumbnail', 'author',
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
        register_post_type('pto-signup', $args);
    }
    /**
    * Remove ghuturnburg
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_digwp_disable_gutenberg( $is_enabled, $post_type ) { 
        /* disable ghuturnburg */
    }
    /**
    * Add meta box for sign up
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_meta_tabs() {
        add_meta_box( 'pto_signups_tabs', // $id
        'Pto Signups Tabs', // $title
        array( $this, 'pto_sign_up_tabs' ) , // $callback
        'pto-signup', // $page
        'normal', // $context
        'high'
        // $priority
        );
        add_meta_box('pto_signups_sidebar_tabs', // $id
        'Sign Up Settings', // $title
        array( $this, 'pto_sign_up_sidebar_tabs' ) , // $callback
        'pto-signup', // $page
        'side'
        );
    }
    /**
    * Sign up sidebar tabs
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_sidebar_tabs() {
        global $post;
        $listing_listing = get_post_meta( $post->ID, 'listing_listing', true );
        $volunteer_names = get_post_meta( $post->ID, 'volunteer_names', true );
        ?>
        <input type="checkbox" name="listing-listing" <?php if ( !empty( $listing_listing ) ) echo esc_html_e("checked"); ?>>
        <label>Add sign up to All Sign Ups page <i class="fa fa-info-circle" title="Include this sign up in the list of upcoming sign ups on your website." aria-hidden="true"></i></label><br>
        <input type="checkbox" name="volunteer-names" <?php if ( !empty( $volunteer_names ) ) echo esc_html_e("checked"); ?>>
        <label>Show volunteer names on sign up <i class="fa fa-info-circle" title="Allow potential volunteers to see who signed up for which task/slot." aria-hidden="true"></i></label>
        <?php
    }
    /**
    * Sign up tabs
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_tabs() {
        include "structure/pto_sign_ups_tabs.php";
    }
    /**
    * Add single task slot
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_slots_single() {
       
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $post_id = intval( $_POST['post_id'] );
            $task_cpt_ids = intval( $_POST['task_cpt_ids'] );
            $get_task_slots = get_post_meta( $post_id, "pto_signups_task_slots", true );
            $temp_arry = array();
            if ( !empty( $get_task_slots ) ) {                  
                $cnt = 0;
                foreach ( $get_task_slots as  $get_task_slot ) {
                    if ( $get_task_slot == $task_cpt_ids ) {
                            $cnt =1;
                    }
                }
                if ( $cnt == 0 ) {
                    $get_task_slots[] = $task_cpt_ids;
                    update_post_meta( $post_id, "pto_signups_task_slots", $get_task_slots );
                }
                 
            } else {
                $temp_arry[]  = $task_cpt_ids;
                update_post_meta( $post_id, "pto_signups_task_slots", $temp_arry );
            } 
            include "structure/pto_sign_ups_task_slots_cpt.php";
            die();
        }
    }
    /**
    * Delete single task slot
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_show_trash_task_slots() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {            
            $trash = sanitize_text_field( $_POST['option'] );
            $post_id = intval( $_POST['post_id'] );
            include "structure/pto_sign_ups_task_slots_cpt.php";
            die();
        }
    }
    /**
    * Task/slot delete permanently
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_delete_permanent_task_slots() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if ( $_POST ) {            
            $trash = sanitize_text_field( $_POST['option'] );
            $post_id = intval( $_POST['post_id'] );
            $custom_fid = intval( $_POST['cpt_ids'] );
            $cpt_task_trash = get_post_meta( $post_id, "pto_signups_task_slots", true );
            if ( ( $key = array_search( $custom_fid, $cpt_task_trash ) ) !== false ) {
                unset( $cpt_task_trash[ $key ] );
                $table_name = $wpdb->prefix . "posts";                                 
                $result = $wpdb->query( $wpdb->prepare( "DELETE FROM ".$table_name." WHERE ID = ".intval( $custom_fid ) ) );
                if ( $result ) {
                }
            }
            update_post_meta( $post_id, "pto_signups_task_slots", $cpt_task_trash );
            include "structure/pto_sign_ups_task_slots_cpt.php";
            die();
        }
    }
    /**
    * Restore single task/slot
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_restore_task_slots() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if ( $_POST ) {            
            $trash = sanitize_text_field( $_POST['option'] );
            $post_id = sanitize_text_field( $_POST['post_id'] );
            $custom_fid = sanitize_text_field( $_POST['cpt_ids'] );
            $cpt_task_trash = get_post_meta( $post_id, "pto_signups_task_slots", true );
            if ( ( $key = array_search( $custom_fid, $cpt_task_trash ) ) !== false ) {                
                $table_name = $wpdb->prefix . "posts";                                 
                $result = $wpdb->query( $wpdb->prepare( "UPDATE ".$table_name." SET post_status = 'publish' WHERE ID = ".intval( $custom_fid ) ) );
                if ( $result ) {                    
                }
            }
            update_post_meta( $post_id, "pto_signups_task_slots", $cpt_task_trash );
            include "structure/pto_sign_ups_task_slots_cpt.php";
            die();
        }
    }
    /**
    * Task/slot bulk action filter
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_bulk_filter() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if ( $_POST ) {
            $task_ids = explode( ",", $_POST['task_ids'] );             
            $bulk = sanitize_text_field( $_POST['bulk'] );  
            $post_id = sanitize_text_field( $_POST['post_id'] ); 
            $trash = sanitize_text_field( $_POST['ctab'] );
            $table_name = $wpdb->prefix . "posts";
            for ( $i=0; $i<count( $task_ids ); $i++ ) {
                if( !empty( $task_ids[ $i ] ) ) {
                    $result = $wpdb->query( $wpdb->prepare( "UPDATE ".$table_name." SET post_status = '".esc_sql( $bulk )."' WHERE ID = ".intval( $task_ids[ $i ] ) ) );
                }                
            } 
        }
        include "structure/pto_sign_ups_task_slots_cpt.php";
        die();
    }
    /**
    * Task/slot bulk action filter monthly
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_bulk_filter_month() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if ( $_POST ) {
            $month = sanitize_text_field( $_POST['month'] );  
            $post_id = intval( $_POST['post_id'] ); 
            $trash = sanitize_text_field( $_POST['ctab'] );                                   
        }
        include "structure/pto_sign_ups_task_slots_cpt.php";
        die();
    }
    /**
    * Trash single task/slot
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_task_slots_cpt_delete() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        global $wpdb;
        if ( $_POST ) {
            $custom_fid = "";
            $current_post_id = "";
            $post_id = "";
            $trash = sanitize_text_field( $_POST['option'] );
            if ( isset( $_POST['cpt_ids'] ) ) {
                $custom_fid = intval( $_POST['cpt_ids'] );
            }
            if ( isset( $_POST['post_id'] ) ) {
                $current_post_id = intval( $_POST['post_id'] );
                $post_id = intval( $_POST['post_id'] );
            }            
            $cpt_custom_fileds = get_post_meta( $current_post_id, "pto_signups_task_slots", true );
            if ( ( $key = array_search( $custom_fid, $cpt_custom_fileds ) ) !== false ) {        
                $table_name = $wpdb->prefix . "posts";                
                $result = $wpdb->query( $wpdb->prepare( "UPDATE ".$table_name." SET post_status = 'trash' WHERE ID = ".intval( $custom_fid ) ) );
                if ( $result ) {                    
                }
            }
            update_post_meta( $current_post_id, "pto_signups_task_slots", $cpt_custom_fileds );
            include "structure/pto_sign_ups_task_slots_cpt.php";
            die();
        }
    }
    /**
    * Dragable single task/slot
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_task_slots_single_dragable() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if($_POST)
        {
            $ids = sanitize_text_field( $_POST['ids'] );
            $cpt_data = explode( ",", $ids );
            $post_id = intval( $_POST['post_id'] );
            update_post_meta( $post_id, "pto_signups_task_slots", $cpt_data );
            include "structure/pto_sign_ups_task_slots_cpt.php";
        }
        die();
    } 
    /**
    * Sign up add checkout custom fields
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_custom_fields_checkout() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $ids = sanitize_text_field( $_POST['ids'] );
            $post_id = intval( $_POST['post_id'] );
            $cpt_custom_fileds =  get_post_meta( $post_id, "single_task_custom_fields_checkout", true );
            if ( empty( $cpt_custom_fileds ) ) {
                $cpt_custom_fileds = array();
                array_push( $cpt_custom_fileds, $ids );
            } else {
                $chceck = false;
                foreach ( $cpt_custom_fileds as $filds ) {
                    if ( $filds == $ids ) {
                        $chceck = true;
                    }
                }
                if ( $chceck == false ) {
                    array_push( $cpt_custom_fileds, $ids );
                }
            }
        }        
        update_post_meta( $post_id, "single_task_custom_fields_checkout", $cpt_custom_fileds );
        include "structure/pto_custom_fileds.php";
        die();
    }
    /**
    * Sign up checkout custom fields
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_custom_fields_checkouts() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $ids = sanitize_text_field( $_POST['ids'] );
            $cpt_data = explode( ",", $ids );
            $post_id = intval( $_POST['post_id'] );
            update_post_meta( $post_id, "single_task_custom_fields_checkout", $cpt_data );
            include "structure/pto_custom_fileds.php";
        }
        die();
    }
    /**
    * Sign up search user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_assign_user() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        include "structure/admin/admin_user_search.php";
        die();
    }
    /**
    * Get manage volunteer user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_get_manage_user() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        ?>
        <div class="pto-manage-user-section">
            <div class="pto-manage-user-section-desc-details">
                <ul class="pto-manage-user-section-desc-details-ul">
                    <?php
                        $name = sanitize_text_field( $_POST['user_name'] ) . "*";
                        $users = get_users( array( 'search' => $name ) );
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
                        $post_id = intval( $_POST['post_id'] );
                        foreach ( $users as $user ) {
                            $role_array = array();
                            foreach ( $user->roles as $key => $roles ) {
                                $role_array[ $roles ] = $roles;
                            }
                            $full_name = "";
                            $first_name = get_user_meta( $user->ID  , "first_name" ,true );
                            $last_name = get_user_meta( $user->ID  , "last_name" ,true );
                            $user = get_user_by( "id" , $user->ID );
                            if( empty( $first_name ) && empty( $last_name ) ){
                                $full_name = $user->user_nicename;
                            }else{
                                $full_name = $first_name. " " . $last_name;
                            }

                                ?>
                                <li class="pto_admin_username">
                                    <div class="pto_admin_user_checkbox"><input type="radio" class="pto_manage_user_signup checked_<?php esc_html_e( $user->ID ); ?>" id="<?php echo intval( $user->ID ); ?>" name="pto_signup_uname"></div>
                                    <div class="pto_admin_user_search">
                                        <div class="pto_user_name_admin"><?php esc_html_e( $user->display_name ); ?></div>
                                        <div class="pto_user_fullname_admin"><?php esc_html_e( $full_name ); ?></div>
                                        
                                        <div class="pto_user_email_admin"><?php esc_html_e( $user->user_email ); ?></div>
                                    </div>
                                </li>
                                <?php                           
                            }
                        ?>                   
                </ul>
            </div>
        </div>
        <?php
        die();
    }
        
    /**
    * Own sign up user settings
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_own_signup_settings_for_users() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $checkedval = "";
        if ( isset( $_POST['checkedval'] ) ) {
            $checkedval = sanitize_text_field( $_POST['checkedval'] );
            if ( !empty( $checkedval ) ) {
                if ( $checkedval == "all_users" ) {
                    $blogusers = get_users( array( 'role__not_in' => array( 'own_sign_up', 'sign_up_plugin_administrators', 'administrator' ) ) );
                    if ( !empty($blogusers) ) {
                        foreach ( $blogusers as $users ) {                            
                            $user_id =  $users->ID;
                            $user = get_user_by( 'id', $user_id );
                            $user->add_role( 'own_sign_up' );
                            $user->add_cap( 'create_posts' );
                            $user->add_cap( 'edit_posts' );
                            $user->add_cap( 'edit_others_posts' );
                            $user->add_cap( 'publish_posts' );
                            $user->add_cap( 'manage_categories' );                    
                            $user->add_cap( 'edit_published_posts' );  
                            $user->add_cap( 'upload_files' );
                        }
                    }
                }
                else {
                    $blogusers = get_users( array( 'role__in' => array( 'own_sign_up' ) ) );
                    if ( !empty( $blogusers ) ) {
                        foreach ( $blogusers as $users ) {                            
                            $user_id = $users->ID;
                            $user = get_user_by( 'id', $user_id );
                            $count = count( $user->roles );
                            if ( $count == 1 ) {
                                $user->remove_role( 'own_sign_up' );                            
                                $user->remove_cap( 'create_posts' );
                                $user->remove_cap( 'edit_posts' );
                                $user->remove_cap( 'edit_others_posts' );
                                $user->remove_cap( 'publish_posts' );
                                $user->remove_cap( 'upload_files' );
                                $user->remove_cap( 'manage_categories' );                    
                                if ( !empty( $user->roles ) ) {
                                }               
                                else{
                                    $user->add_role( 'subscriber' );
                                }
                            }
                            else{
                                $user->remove_role( 'own_sign_up' );
                            }                              
                        }
                    }                    
                }
            }
        }
        include "pto_sign_ups_project_tabs/pto_sign_ups_admin_search_add.php";
        die();
    }
    /**
    * Add single sign up user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_new_users_add_get()
    {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $user_ids = sanitize_text_field( $_POST['ids'] );
        $post_id = sanitize_text_field( $_POST['post_id'] );
        $all_users = explode( ",", $user_ids );
        $temp_arr = array();
        foreach( $all_users as $all_user ) {
            $temp_arr = array();
            $get_user_post = get_user_meta( $all_user, 'assign_post_key', true );
            if ( !empty( $get_user_post ) ) {
                $temp_arr = $get_user_post;
                $temp_arr[ $post_id ] = $post_id;
            } else { 
               $temp_arr[ $post_id ] = $post_id;
            }  
            update_user_meta( $all_user, 'assign_post_key', $temp_arr );
            $mailcontent = get_option( 'add_user_to_signup' );
            if ( !empty( $mailcontent ) ) {
                $cur_user_obj = get_user_by( 'id', $all_user );
                $full_name = $cur_user_obj->first_name." ".$cur_user_obj->last_name;;
                $fname = $cur_user_obj->first_name;
                $lname = $cur_user_obj->last_name;
                if ( empty( $fname ) ) {
                    $fname = $cur_user_obj->display_name;
                } 
                if( empty( $lname ) ) {
                    $lname = $cur_user_obj->display_name;
                }
                $to = $cur_user_obj->user_email;
                $signupname = "<a href='".get_the_permalink( $post_id )."' target='_blank'>".get_the_title( $post_id )."</a>";
                $cur_user_id = get_current_user_id();
                $cur_user_obj = get_user_by( 'id', $cur_user_id );
                $author_name =  $cur_user_obj->display_name;
                $arra = array("/{{First Name}}/", "/{{Signup Name}}/", "/{{Admin Name}}/", "/{{Last Name}}/", "/{{Full Name}}/");
                $arra2 = array( $fname, $signupname, $author_name, $lname, $full_name );
                $mail = preg_replace( $arra, $arra2, $mailcontent );
                $subject = 'You have been added to a sign up';
                $body = $mail;   
                $headers = array('Content-Type: text/html; charset=UTF-8'); 
                wp_mail( $to, $subject, $body, $headers ); 
            }
            
        }
        $post_meta = get_post_meta( $post_id, "pto_assign_user_administrator", true );
        if ( !empty( $post_meta ) ) {
            $cnt = 0;
            $total_arr = array();
           if ( !is_array( $all_users ) ) {
                 $total_arr[] =  $all_users;
           } else {
                 $total_arr = array_merge( $all_users, $post_meta );  
           }
           update_post_meta( $post_id, "pto_assign_user_administrator", $total_arr );
        } else {
            $total_arr = array();
           if ( !is_array( $all_users ) ) {
                $total_arr[] =  $all_users;
           } else {
                $total_arr = $all_users;  
           }
           update_post_meta( $post_id, "pto_assign_user_administrator", $total_arr );
        }
        include "structure/user_search_pto_sign_up.php";
        die();
    }
    /**
    * Remove single signup user
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_new_users_add_remove() { 
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        $post_id = intval( $_POST['post_id'] );
        $userid = intval( $_POST['user_ids'] );
        if ( $_POST ) {   
            if ( isset( $_POST['user_ids']) ) {   
                $user_last = get_user_meta( intval($_POST['user_ids']), "assign_post_key", true );
                unset( $user_last[ intval($_POST['post_id']) ] );
                update_user_meta( intval($_POST['user_ids']) , 'assign_post_key', $user_last );               
            }
            if ( isset( $_POST['post_id'] ) ) {
                $post_user_meta = get_post_meta( intval($_POST['post_id']) , "pto_assign_user_administrator", true );
                $key = array_search( intval($_POST['user_ids']), $post_user_meta );                    
                unset( $post_user_meta[ $key ] );
                update_post_meta( intval($_POST['post_id']) , "pto_assign_user_administrator", $post_user_meta );
            } 
            if ( isset( $_POST['email_sent'] ) ) { 
                $mailcontent = get_option( 'remove_user_from_signup' );
                if ( !empty( $mailcontent ) ) {
                    $cur_user_obj = get_user_by( 'id', $userid );
                    $full_name = $cur_user_obj->first_name." ".$cur_user_obj->last_name;;
                    $fname = $cur_user_obj->first_name;
                    $lname = $cur_user_obj->last_name;
                    if ( empty( $fname ) ) {
                        $fname = $cur_user_obj->display_name;
                    } 
                    if ( empty( $lname ) ) {
                        $lname = $cur_user_obj->display_name;
                    }
                    $to = $cur_user_obj->user_email;
                    $signupname = "<a href='".get_the_permalink( $post_id )."' target='_blank'>".get_the_title( $post_id )."</a>";
                    $cur_user_id = get_current_user_id();
                    $cur_user_obj = get_user_by( 'id', $cur_user_id );
                    $author_name =  $cur_user_obj->display_name;
                    $arra = array("/{{First Name}}/", "/{{Signup Name}}/", "/{{Admin Name}}/", "/{{Last Name}}/", "/{{Full Name}}/");
                    $arra2 = array( $fname, $signupname, $author_name, $lname, $full_name );
                    $mail = preg_replace( $arra, $arra2, $mailcontent );
                    $subject = 'You have been removed for a sign up';
                    $body = $mail;   
                    $headers = array('Content-Type: text/html; charset=UTF-8'); 
                    wp_mail( $to, $subject, $body, $headers ); 
                }
            }
        }
        include "structure/user_search_pto_sign_up.php";
        die();
    }
    /**
    * Sign up step two
    * @since    1.0.0
    * @access   public
    **/
    public function pto_sign_up_step_two() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            die ( 'Busted!');
        }
        if ( $_POST ) {
            $tasK_recurring = array();           
            
            $get_period = sanitize_text_field( $_POST['to_sign_up_div_repeate_time'] );
            $tasK_recurring['start_date'] = sanitize_text_field( $_POST['start_date'] );
            $tasK_recurring['skipped_dates'] = sanitize_text_field( $_POST['skipped_dates'] );
            $tasK_recurring['daysofevery'] = sanitize_text_field( $_POST["daysofevery"] );
            $tasK_recurring['to_sign_up_div_repeate_time'] = sanitize_text_field( $_POST['to_sign_up_div_repeate_time'] );
            $tasK_recurring['end_data'] = filter_var_array($_POST['end_data']);
            if ( $get_period  == "Weeks" ) {
                $tasK_recurring['week_days'] = sanitize_text_field( $_POST['week_days'] );
            } else if ( $get_period  == "Month" ) {
                $tasK_recurring['pto_signup_reucr_month'] = sanitize_text_field( $_POST['pto_signup_reucr_month'] );
            }
        }
        $post_id = intval( $_POST['post_id'] );
        update_post_meta( $post_id, "pto_task_recurreence", $tasK_recurring );
        
        die();
    }
}