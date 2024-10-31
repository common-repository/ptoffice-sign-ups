<table class="wp-list-table widefat fixed striped table-view-list posts">
	<thead>
		<tr>		
			<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
				<?php esc_html_e( 'Username', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</th>
			<th scope="col" id="Name" class="manage-column column-title column-primary sortable desc">
				<?php esc_html_e( 'Name', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</th>
			<th scope="col" id="Email" class="manage-column column-title column-primary sortable desc">
				<?php esc_html_e( 'Email', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</th>
			<th scope="col" id="notify" class="manage-column column-title column-primary sortable desc">
				<?php esc_html_e( 'Notify of sign ups?', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</th>
			<th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
				<?php esc_html_e( 'role', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<!-- /* get assign user from signup page */ -->
		<?php 
		$post__user_meta = get_post_meta( $post_id, "pto_assign_user_administrator", true );
		$notified_users = array();
		$notified_users = get_post_meta( $post_id, "pto_signup_notified_users", true );	
		if ( !empty( $post__user_meta ) ) {
			foreach ( $post__user_meta as $assign_user ) {
				$author_obj = get_user_by( 'id', $assign_user );
				?>
				<tr>						
					<td class="user_td"><?php esc_html_e( $author_obj->user_login ); ?>
					<span class="remove_siggle_user_cpt_sign_ups" style="color: red;cursor:pointer;" id="<?php echo intval( $author_obj->ID ); ?>" post-id="<?php echo intval( $post_id ); ?>">Remove</span>
				</td>
				<td><?php esc_html_e( $author_obj->display_name ); ?></td>
				<td><?php esc_html_e( $author_obj->user_email ); ?></td>
				<td>
					<input type="checkbox" <?php if ( !empty( $notified_users ) ) { if ( in_array( $author_obj->ID, $notified_users ) ) { echo "checked"; } } ?> name="notifyuser[]" value="<?php echo intval( $author_obj->ID ); ?>" class="pto-signup-notify-admin">
				</td>
				<td>
					<?php 
					$all_roles = ""; 
					foreach ( $author_obj->roles as $key => $value ) {
						$role_name = $value ? wp_roles()->get_names()[ $value ] : '';											
						$all_roles .= $role_name . ", ";
					}
					esc_html_e( substr( $all_roles, 0, -2 ) );
					?>
				</td>
			</tr>
			<?php
		}
	}
	?>
</tbody>
</table>			