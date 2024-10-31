<?php 
/* search user for type in ajax */
foreach ( $users as $user ) {
    $user_id =  $user->ID;
    $first_name = get_user_meta( $user_id , "first_name" , true );
    $last_name = get_user_meta( $user_id , "last_name" , true );
    $role_array = array(); 
    foreach ( $user->roles as $key => $roles ) {
        $role_array[ $roles ] = $roles;
    }   
    if ( $user_type == 2 ) {
        if ( !array_key_exists( "sign_up_plugin_administrators", $role_array ) ) {
            if ( !array_key_exists( "administrator", $role_array ) ) {
                ?>
                <div class="pto_admin_username">
                    <div class="pto_admin_user_checkbox"><input type="checkbox" class="pto_admin_user <?php echo "checked_" . esc_html_e( $user->ID ); ?>" id="<?php echo intval( $user->ID ); ?>" name="<?php esc_html_e( $user->display_name ); ?>"></div>
                    <div class="pto_admin_user_search">
                        <div class="pto_user_name_admin"><?php esc_html_e( $user->display_name ); ?></div>
                         <?php
                                
                                if( !empty( $first_name ) && !empty( $last_name ) ){
                                    $fullname =  ucfirst($first_name) . "  " . ucfirst($last_name);
                                    ?>
                                        <div class="pto_user_full_name"><?php esc_html_e( $fullname ); ?></div>      
                                    <?php
                                }
                            ?>
                        <div class="pto_user_email_admin"><?php esc_html_e( $user->user_email ); ?></div>
                    </div>
                </div>
                <?php
            }
        }
    } else if ( $user_type == 1 ) {
        if ( !array_key_exists( "sign_up_plugin_administrators", $role_array ) ) { 
            if ( !array_key_exists( "administrator", $role_array ) ) {
                if ( !array_key_exists( "own_sign_up", $role_array ) ) {
                    ?>
                    <div class="pto_admin_username">
                        <div class="pto_admin_user_checkbox">
                            <input type="checkbox" class="pto_sign_up_user checked_<?php esc_html_e( $user->ID ); ?>" id="<?php echo intval( $user->ID ); ?>" name="<?php esc_html_e( $user->display_name ); ?>" />
                        </div>
                        <div class="pto_admin_user_search">
                            <div class="pto_user_name_admin"><?php esc_html_e( $user->display_name ); ?></div>
                            <?php
                                
                                if( !empty( $first_name ) && !empty( $last_name ) ){
                                     $fullname =  ucfirst($first_name) . "  " . ucfirst($last_name);
                                    ?>
                                       <div class="pto_user_full_name"><?php esc_html_e( $fullname ); ?></div>    
                                    <?php
                                }
                            ?>
                            <div class="pto_user_email_admin"><?php esc_html_e( $user->user_email ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
    }
}
?>