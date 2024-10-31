<?php 
    global $post;
    $time_set = get_post_meta( $post->ID, "pto_sign_ups_time_set", true );
    $get_recurrence_data = get_post_meta( $post->ID, "pto_task_recurreence", true );
    $week_days = array();
    $week_day = "";
    if ( is_array( $get_recurrence_data ) ) {
        if ( array_key_exists( "week_days", $get_recurrence_data ) ) {
 
            $week_day = $get_recurrence_data['week_days'];
            $week_day = explode( ",", $week_day );
            foreach ( $week_day as $w ) {
                $week_days[ $w ] = $w;
            }            
        }
    }    
?>

<div id="add-administarter" class="pto-modal" style="display:none;">
    <div class="pto-modal-content">
        <div class="pto-modal-container-header">
            <span><?php esc_html_e( 'Add Sign up Administrators', PTO_SIGN_UP_TEXTDOMAIN ); ?>&nbsp; <i class="fa fa-info-circle" title="Begin typing a name, username or email address." aria-hidden="true"></i> </span>

            <span onclick="jQuery('#add-administarter').removeClass('pto-modal-open');" class="w3-button w3-display-topright">&times;</span>
        </div>
        <div class="pto-modal-container">
            <div class="cust-field">
                <input type="search" name="searchuserforadminsignup" id='search-user-for-admin-signup'>
            </div>
            <div class="search_user_pto_sign_up"></div>
            <div id="selected_user_sign_ups"></div>
        </div>
        <div class="pto-modal-footer">
            <input type="button" name="ok" value="+ Add" class="add_new_users_signup outline_btn button button-primary" onclick="">
            <input type="button" name="cancel" value="Cancel" class="add_new outline_btn delete-btn" onclick="jQuery('#add-administarter').removeClass('pto-modal-open');">
        </div>
    </div>
</div>