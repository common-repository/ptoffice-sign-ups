<?php
namespace ptofficesignup\classes;
class PtoSingUpPlugin {
    /**
    * Constructor for iniation
    * @since    1.0.0
    * @access   public
    **/
    function __construct() {
        // Run this on plugin activation
        register_activation_hook( PTO_SIGN_UP_PLUGIN_WITH_CLASSES__FILE__ ,  [ $this, 'pto_sign_up_create_plugin_active' ] );
    }
    /**
    * Plugin activation creation
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_create_plugin_active() {
        if( is_multisite() ){
            $blogs = get_sites();
            foreach( $blogs as $b ){
                $blog_id =  $b->blog_id; 
                switch_to_blog( $blog_id );
                /* display all sign_ups */
                $display_all_sign_ups = get_page_by_title( 'Signup', 'OBJECT', 'page' );
                //Check if the page already exists
                if(empty($display_all_sign_ups)) {
                    $page_id = wp_insert_post(
                        array(
                            'comment_status' => 'close',
                            'ping_status'    => 'close',
                            'post_author'    => 1,
                            'post_title'     => ucwords('Signup'),
                            'post_name'      => strtolower(str_replace(' ', '-', trim('Signup'))),
                            'post_status'    => 'publish',
                            'post_content'   => '[pto_signup_all_listing]',
                            'post_type'      => 'page',
                            'post_parent'    => 'id_of_the_parent_page_if_it_available'
                        )
                    );
                    update_option( 'pto_display_all_sing_ups', $page_id );
                }
                /* display all sign_ups */
                $my_sing_ups = get_page_by_title( 'My Signup', 'OBJECT', 'page' );
                // Check if the page already exists
                if(empty($my_sing_ups)) {
                    $page_id = wp_insert_post(
                        array(
                            'comment_status' => 'close',
                            'ping_status'    => 'close',
                            'post_author'    => 1,
                            'post_title'     => ucwords('My Signup'),
                            'post_name'      => strtolower(str_replace(' ', '-', trim('My Signup'))),
                            'post_status'    => 'publish',
                            'post_content'   => '[pto_signup_my_history]',
                            'post_type'      => 'page',
                            'post_parent'    => 'id_of_the_parent_page_if_it_available'
                        )
                    );
                    update_option( 'pto_volunteers_sign_ups', $page_id );
                }
                /* pto sign ups checkout */
                $my_sing_ups_checkout = get_page_by_title( 'Pto Signup Checkout', 'OBJECT', 'page' );
                // Check if the page already exists
                if(empty($my_sing_ups_checkout)) {
                    $page_id = wp_insert_post(
                        array(
                            'comment_status' => 'close',
                            'ping_status'    => 'close',
                            'post_author'    => 1,
                            'post_title'     => ucwords('Pto Signup Checkout'),
                            'post_name'      => strtolower(str_replace(' ', '-', trim('Pto Signup Checkout'))),
                            'post_status'    => 'publish',
                            'post_content'   => '[pto_signup_checkout]',
                            'post_type'      => 'page',
                            'post_parent'    => 'id_of_the_parent_page_if_it_available'
                        )
                    );
                    update_option( 'pto_checkout_sign_ups', $page_id );
                }
                /* pto sign ups checkout */
                $my_sing_ups_thanks_you = get_page_by_title( 'Signup Thank You', 'OBJECT', 'page' );
                // Check if the page already exists
                if(empty($my_sing_ups_thanks_you)) {
                    $page_id = wp_insert_post(
                        array(
                            'comment_status' => 'close',
                            'ping_status'    => 'close',
                            'post_author'    => 1,
                            'post_title'     => ucwords('Signup Thank You'),
                            'post_name'      => strtolower(str_replace(' ', '-', trim('Signup Thank You'))),
                            'post_status'    => 'publish',
                            'post_content'   => '[pto_signup_thank_you]',
                            'post_type'      => 'page',
                            'post_parent'    => 'id_of_the_parent_page_if_it_available'
                        )
                    );
                    update_option( 'pto_post_sign_thank_you', $page_id);
                }
                $defult_wording_volunteers = "<p>Hello <strong>{{First Name}},</strong></p>";
                $defult_wording_volunteers .= "<p>Thank you for signing up for the {{Signup Name}} sign up. As a reminder, below are the details of what you signed up for.</p>";
                $defult_wording_volunteers .= "{{Signup Details}}";
                $defult_wording_volunteers .= "Thank You.";
                $administrators_notifcations = "<p><strong>{{Admin Name}},</strong></p>";
                $administrators_notifcations .= "<p>{{Full Name}} has just signed up for the following task/slot on the {{Signup Name}} sign up.</p>";
                $administrators_notifcations .= "{{Signup Details}}";
                $administrators_notifcations .= "Thank You.";
                $administrators_invitation = "<p><strong>{{First Name}},</strong></p>";
                $administrators_invitation .= "<p>You have been added as an administrator to the signup plugin on your organization's website. You will now be able to edit and manage all signups for your organization.</p>";
                $administrators_invitation .= "Thank You.";
                $ownsignup_invitation = "<p><strong>{{First Name}},</strong></p>";
                $ownsignup_invitation .= "<p>You have been added as an administrator to the sign up module. You will now see an “Add new” button on the sign up listing web page that you can click to create your own sign ups.</p>";
                $ownsignup_invitation .= "Thank You.";
                $request_access_accept = "<p><strong>{{First Name}},</strong></p>";
                $request_access_accept .= "<p>Your request for administrative access to the {{Signup Name}} sign up has been approved. You will now see a “Edit” button on the sign up listing web page that you can click to have full editing capabilities of that sign up.</p>";
                $request_access_accept .= "Thank You.";
                $request_access_decline = "<p><strong>{{First Name}},</strong></p>";
                $request_access_decline .= "<p>Your request for administrative access to the {{Signup Name}} sign up has been rejected. Please contact an administrator directly if you feel this was done in error.</p>";
                $request_access_decline .= "Thank You.";
                $add_user_to_signup = "<p><strong>{{First Name}},</strong></p>";
                $add_user_to_signup .= "<p>You have been invited to be an administrator of the {{Signup Name}} sign up. You will now be able to visit the signup listings page and see a “Edit” button where you can click and edit the signup as needed.</p>";
                $add_user_to_signup .= "Thank You.";
                $remove_user_from_signup = "<p><strong>{{First Name}},</strong></p>";
                $remove_user_from_signup .= "<p>You have been removed from the {{Signup Name}} sign up. Please contact an administrator directly if you feel this was done in error.</p>";
                $remove_user_from_signup .= "Thank You.";
                $signup_reminder_to_volunteer = "<p><strong>{{First Name}},</strong></p>";
                $signup_reminder_to_volunteer .= "<p>This is a reminder of your upcoming task/slot for the {{Signup Name}} sign up. Below are the details.</p>";
                $signup_reminder_to_volunteer .= "{{Signup Details}}";
                $signup_reminder_to_volunteer .= "Thank You.";
                update_option( 'defult_wording_volunteers', $defult_wording_volunteers );
                update_option( 'administrators_notifcations', $administrators_notifcations );
                update_option( 'administrators_invitation', $administrators_invitation );
                update_option( 'ownsignup_invitation', $ownsignup_invitation );
                update_option( 'request_access_accept', $request_access_accept );
                update_option( 'request_access_decline', $request_access_decline );
                update_option( 'add_user_to_signup', $add_user_to_signup );
                update_option( 'remove_user_from_signup', $remove_user_from_signup );
                update_option( 'signup_reminder_to_volunteer', $signup_reminder_to_volunteer );
                $this->pto_sign_up_create_signup_table();
            }
        }
        else{
            /* display all sign_ups */
            
            $display_all_sign_ups = get_page_by_title( 'Signup', 'OBJECT', 'page' );
            //Check if the page already exists
            if(empty($display_all_sign_ups)) {
                $page_id = wp_insert_post(
                    array(
                        'comment_status' => 'close',
                        'ping_status'    => 'close',
                        'post_author'    => 1,
                        'post_title'     => ucwords('Signup'),
                        'post_name'      => strtolower(str_replace(' ', '-', trim('Signup'))),
                        'post_status'    => 'publish',
                        'post_content'   => '[pto_signup_all_listing]',
                        'post_type'      => 'page',
                        'post_parent'    => 'id_of_the_parent_page_if_it_available'
                    )
                );
                update_option( 'pto_display_all_sing_ups', $page_id);
            }
            /* display all sign_ups */
            $my_sing_ups = get_page_by_title( 'My Signup', 'OBJECT', 'page' );
            // Check if the page already exists
            if(empty($my_sing_ups)) {
                $page_id = wp_insert_post(
                    array(
                        'comment_status' => 'close',
                        'ping_status'    => 'close',
                        'post_author'    => 1,
                        'post_title'     => ucwords('My Signup'),
                        'post_name'      => strtolower(str_replace(' ', '-', trim('My Signup'))),
                        'post_status'    => 'publish',
                        'post_content'   => '[pto_signup_my_history]',
                        'post_type'      => 'page',
                        'post_parent'    => 'id_of_the_parent_page_if_it_available'
                    )
                );
                update_option( 'pto_volunteers_sign_ups', $page_id );
            }
            /* pto sign ups checkout */
            $my_sing_ups_checkout = get_page_by_title( 'Pto Signup Checkout', 'OBJECT', 'page' );
            // Check if the page already exists
            if(empty($my_sing_ups_checkout)) {
                $page_id = wp_insert_post(
                    array(
                        'comment_status' => 'close',
                        'ping_status'    => 'close',
                        'post_author'    => 1,
                        'post_title'     => ucwords('Pto Signup Checkout'),
                        'post_name'      => strtolower(str_replace(' ', '-', trim('Pto Signup Checkout'))),
                        'post_status'    => 'publish',
                        'post_content'   => '[pto_signup_checkout]',
                        'post_type'      => 'page',
                        'post_parent'    => 'id_of_the_parent_page_if_it_available'
                    )
                );
                update_option( 'pto_checkout_sign_ups', $page_id );
            }
            /* pto sign ups checkout */
            $my_sing_ups_thanks_you = get_page_by_title( 'Signup Thank You', 'OBJECT', 'page' );
            // Check if the page already exists
            if(empty($my_sing_ups_thanks_you)) {
                $page_id = wp_insert_post(
                    array(
                        'comment_status' => 'close',
                        'ping_status'    => 'close',
                        'post_author'    => 1,
                        'post_title'     => ucwords( 'Signup Thank You' ),
                        'post_name'      => strtolower( str_replace( ' ', '-', trim( 'Signup Thank You' ) ) ),
                        'post_status'    => 'publish',
                        'post_content'   => '[pto_signup_thank_you]',
                        'post_type'      => 'page',
                        'post_parent'    => 'id_of_the_parent_page_if_it_available'
                    )
                );
                
                update_option( 'pto_post_sign_thank_you', $page_id );
            }
            $defult_wording_volunteers = "<p>Hello <strong>{{First Name}},</strong></p>";
            $defult_wording_volunteers .= "<p>Thank you for signing up for the {{Signup Name}} sign up. As a reminder, below are the details of what you signed up for.</p>";
            $defult_wording_volunteers .= "{{Signup Details}}";
            $defult_wording_volunteers .= "Thank You.";
            $administrators_notifcations = "<p><strong>{{Admin Name}},</strong></p>";
            $administrators_notifcations .= "<p>{{Full Name}} has just signed up for the following task/slot on the {{Signup Name}} sign up.</p>";
            $administrators_notifcations .= "{{Signup Details}}";
            $administrators_notifcations .= "Thank You.";
            $administrators_invitation = "<p><strong>{{First Name}},</strong></p>";
            $administrators_invitation .= "<p>You have been added as an administrator to the signup plugin on your organization's website. You will now be able to edit and manage all signups for your organization.</p>";
            $administrators_invitation .= "Thank You.";
            $ownsignup_invitation = "<p><strong>{{First Name}},</strong></p>";
            $ownsignup_invitation .= "<p>You have been added as an administrator to the sign up module. You will now see an “Add new” button on the sign up listing web page that you can click to create your own sign ups.</p>";
            $ownsignup_invitation .= "Thank You.";
            $request_access_accept = "<p><strong>{{First Name}},</strong></p>";
            $request_access_accept .= "<p>Your request for administrative access to the {{Signup Name}} sign up has been approved. You will now see a “Edit” button on the sign up listing web page that you can click to have full editing capabilities of that sign up.</p>";
            $request_access_accept .= "Thank You.";
            $request_access_decline = "<p><strong>{{First Name}},</strong></p>";
            $request_access_decline .= "<p>Your request for administrative access to the {{Signup Name}} sign up has been rejected. Please contact an administrator directly if you feel this was done in error.</p>";
            $request_access_decline .= "Thank You.";
            $add_user_to_signup = "<p><strong>{{First Name}},</strong></p>";
            $add_user_to_signup .= "<p>You have been invited to be an administrator of the {{Signup Name}} sign up. You will now be able to visit the signup listings page and see a “Edit” button where you can click and edit the signup as needed.</p>";
            $add_user_to_signup .= "Thank You.";
            $remove_user_from_signup = "<p><strong>{{First Name}},</strong></p>";
            $remove_user_from_signup .= "<p>You have been removed from the {{Signup Name}} sign up. Please contact an administrator directly if you feel this was done in error.</p>";
            $remove_user_from_signup .= "Thank You.";
            $signup_reminder_to_volunteer = "<p><strong>{{First Name}},</strong></p>";
            $signup_reminder_to_volunteer .= "<p>This is a reminder of your upcoming task/slot for the {{Signup Name}} sign up. Below are the details.</p>";
            $signup_reminder_to_volunteer .= "{{Signup Details}}";
            $signup_reminder_to_volunteer .= "Thank You.";
            update_option( 'defult_wording_volunteers', $defult_wording_volunteers );
            update_option( 'administrators_notifcations', $administrators_notifcations );
            update_option( 'administrators_invitation', $administrators_invitation );
            update_option( 'ownsignup_invitation', $ownsignup_invitation );
            update_option( 'request_access_accept', $request_access_accept );
            update_option( 'request_access_decline', $request_access_decline );
            update_option( 'add_user_to_signup', $add_user_to_signup );
            update_option( 'remove_user_from_signup', $remove_user_from_signup );
            update_option( 'signup_reminder_to_volunteer', $signup_reminder_to_volunteer );
            $this->pto_sign_up_create_signup_table();
        }        
    }
    /**
    * Create table on plugin activation
    * @since    1.0.0
    * @access   public
    **/
    function pto_sign_up_create_signup_table() {
        if(is_multisite()){
            global $wpdb;
            $blogs = get_sites();
            foreach( $blogs as $b ){
                $prefixed = $wpdb->get_blog_prefix( $b->blog_id );
                $blog_id = $b->blog_id;
                switch_to_blog( $blog_id );
                /* attrbute table */
                $pto_attribute = $prefixed . 'signup_orders';
                if( $wpdb->get_var( "show tables like '{$pto_attribute}'" ) != $pto_attribute ) {
                    $sql = "CREATE TABLE " . $pto_attribute . " (
                    ID int(10) NOT NULL AUTO_INCREMENT,
                    user_id int(10) NOT NULL,
                    signup_id int(100) NOT NULL,
                    order_info longtext NOT NULL,
                    checkout_date date NOT NULL,
                    status varchar(20) NOT NULL,
                    PRIMARY KEY (ID)
                    );";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta( $sql );                
                }
            }
        }
        else{
            global $wpdb;
            $prefixed = $wpdb->prefix;
            /* attrbute table */
            $pto_attribute = $prefixed . 'signup_orders';
            if( $wpdb->get_var( "show tables like '{$pto_attribute}'" ) != $pto_attribute ) {
                $sql = "CREATE TABLE " . $pto_attribute . " (
                ID int(10) NOT NULL AUTO_INCREMENT,
                user_id int(10) NOT NULL,
                signup_id int(100) NOT NULL,
                order_info longtext NOT NULL,
                checkout_date date NOT NULL,
                status varchar(20) NOT NULL,
                PRIMARY KEY (ID)
                );";
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                dbDelta( $sql );
            }
        }
    }
}