<div class="pto-project-user-section">
    <div class="pto-project-user-section-desc-details">
        <ul class="pto-project-user-section-desc-details-ul">
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
                $assign_user_ids = get_post_meta( $post_id, "pto_assign_user_administrator", true );
                if ( empty( $assign_user_ids ) ) {
                    $assign_user_ids = array();
                }
                foreach ( $users as $user ) {
                    $role_array = array();
                    foreach ( $user->roles as $key => $roles ) {
                        $role_array[ $roles ] = $roles;
                    }
                    $user_firstname = get_user_meta( $user->ID, 'first_name', true );
                    // get the last name of the user as a string
                    $user_lastname = get_user_meta( $user->ID, 'last_name', true );
                    $username =  $user->user_nicename;
                    if ( !in_array( $user->ID, $assign_user_ids ) ) {
                        ?>
                        <li class="pto_admin_username">
                            <div class="pto_admin_user_checkbox"><input type="checkbox" class="pto_admin_user_signup checked_<?php printf( $user->ID ); ?>" id="<?php  printf( $user->ID ); ?>" name="<?php printf( $user->display_name ); ?>"></div>
                            <div class="pto_admin_user_search">
                                <div class="pto_user_name_admin"><?php echo esc_html_e( $user->display_name ); ?></div>
                                <?php if( !empty( $user_firstname ) || !empty( $user_lastname ) ){
                                    ?>
                                    <div class="pto_user_name_fullname"> <?php echo esc_html_e($user_firstname . " " . $user_lastname); ?> </div>
                                    <?php
                                } ?>
                                <div class="pto_user_email_admin"><?php echo esc_html_e( $user->user_email ); ?></div>
                            </div>
                        </li>
                        <?php
                    }
                }
            ?>
        </ul>
    </div>
</div>