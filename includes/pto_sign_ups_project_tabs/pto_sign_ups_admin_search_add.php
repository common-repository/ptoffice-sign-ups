<?php
/* search uuser in table structure after add update delete */
$users = get_users();
$html = "";
foreach( $users as $user ) { 
    $role_array = array();
    foreach( $user->roles as $key => $roles ) {
        $role_array[ $roles ] = $roles;
    }
    if ( $user_type == 2 ) {
        if ( array_key_exists( "sign_up_plugin_administrators", $role_array ) ) {
            $author_obj = get_user_by( 'id', $user->ID );             
            $html .= "<tr class='own_user_".$user->ID ."'>";
            $html .= "<td>$user->ID</td>";
            $html .= "<td>".$author_obj->data->user_nicename ."</td>";
            $html .= "<td>" . $author_obj->data->display_name ."</td>";
            $html .= "<td>". $author_obj->data->user_email ."</td>";
            $html .= "<td><span class='delete-user'><a href='javascript:void(0)' attr-type='2' class='delete_user_signup' id='$user->ID'>Delete</a></span>&nbsp;&nbsp;<span class='resend-invitation' user-id='". $user->ID."' type='sign_up_plugin_administrators'><a href='javascript:void(0)'>Resend Invitation</a></span></td>";
            $html .= "</tr>";
        }
    } else if ( $user_type == 1 ) {
        if ( array_key_exists( "own_sign_up", $role_array ) ) {
            $author_obj = get_user_by( 'id', $user->ID );
            $html .= "<tr class='own_user_".$user->ID ."'>";
            $html .= "<td>$user->ID</td>";
            $html .= "<td>".$author_obj->data->user_nicename ."</td>";
            $html .= "<td>" . $author_obj->data->display_name ."</td>";
            $html .= "<td>". $author_obj->data->user_email ."</td>";
            $html .= "<td><span class='delete-user'><a href='javascript:void(0)' attr-type='1' class='delete_user_signup' id='$user->ID'>Delete</a></span>&nbsp;&nbsp;<span class='resend-invitation' user-id='". $user->ID."' type='own_sign_up'><a href='javascript:void(0)'>Resend Invitation</a></span></td>";
            $html .= "</tr>";
        }
    }
}
print_r($html);
?>