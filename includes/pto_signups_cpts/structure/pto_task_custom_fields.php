<?php
global $post;
if ( isset( $_POST['post_id'] ) ) {
	$post_id = intval( $_POST['post_id'] );
} else {
	$post_id = intval($post->ID);
}
$cpt_custom_fileds =  get_post_meta( $post_id, "single_task_custom_fields", true );
if ( !empty( $cpt_custom_fileds ) ) {
	?>
	<div class="pto-signup-custom-fileds pto-sign-up-compelling-task-section_list">
		<div class="pto-signup-custom-fileds-header task-list-header">
			<div class="pto-signup-custom-fileds-title"><?php esc_html_e( 'Field Name', PTO_SIGN_UP_TEXTDOMAIN ); ?></div>
			<div class="pto-signup-custom-fileds-title"><?php esc_html_e( 'Alternate Title', PTO_SIGN_UP_TEXTDOMAIN ); ?></div>
			<div class="pto-signup-custom-fileds-title"><?php esc_html_e( 'Type', PTO_SIGN_UP_TEXTDOMAIN ); ?></div>
			<div class="pto-signup-custom-fileds-title"><?php esc_html_e( 'Required', PTO_SIGN_UP_TEXTDOMAIN ); ?></div>
		</div> 
		<ul id="sortable" class="pto-signup-custom-fileds-ul pto-class-shift-data-cpt">
			<?php
			foreach ( $cpt_custom_fileds as $cpt_custom_filed ) {     
				$alternet_title = get_post_meta( $cpt_custom_filed, "pto_alternate_title", true );
				$instruction = get_post_meta( $cpt_custom_filed, "instruction", true );
				$type = get_post_meta( $cpt_custom_filed, "pto_field_type", true );
				$require = get_post_meta( $cpt_custom_filed, "pto_field_required", true );
				?>
				<li class="ui-state-default" id="<?php echo intval( $cpt_custom_filed ); ?>">
					<div class="pto-signup-custom-fileds-ul-li_div">
						<?php esc_html_e( get_the_title( $cpt_custom_filed ) ); ?>
						<div class="hook-action">
							<span><a href="javascript:void(window.open('<?php esc_html_e( site_url() )."/wp-admin/post.php?post=".intval( $cpt_custom_filed )."&action=edit"; ?>'))"> Edit </a> <span class="pto-separator">|</span> </span>
							<span><a href="javascript:void(0)" id="single_task_custom_filed_delete" cpt-ids="<?php echo intval( $cpt_custom_filed ); ?>"> Delete</a> <span class="pto-separator">|</span> </span>
							<?php
							$url = wp_nonce_url( add_query_arg( array(
								'action' => 'pto_sign_up_duplicate_post_as_draft',
								'post' => $cpt_custom_filed,
							) , 'admin.php' ) , basename(__FILE__) , 'duplicate_nonce' );
							$duplicate_url = '<a href="javascript:void(window.open(\''.$url.'\'))" title="Duplicate this item" rel="permalink">Duplicate</a>';
							?>
							<span><?php  print_r( $duplicate_url ); ?></span>
						</div>
					</div>
					<div class="pto-signup-custom-fileds-ul-li_div">
						<?php esc_html_e( $alternet_title ); ?>
					</div>
					<div class="pto-signup-custom-fileds-ul-li_div">
						<?php esc_html_e( $type ); ?>
					</div>
					<div class="pto-signup-custom-fileds-ul-li_div">
						<?php esc_html_e( $require ); ?>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
}
?>
<script>
	jQuery(function() {
		jQuery( '#sortable' ).sortable({
			update: function( event, ui ) {
				let ids = "";
				let post_id = jQuery("#post_ID").val();
				jQuery("#sortable li").each(function() {
					ids += jQuery(this).attr("id") + ",";
				}); 
				ids = ids.substring(0,ids.length - 1);
				jQuery.ajax({
					method:"POST",
					url:pto_ajax_url.ajax_url,
					data:{
						action:'pto_signup_custom_fieldss',
						nonce: pto_ajax_url.nonce,
						ids:ids,
						post_id:post_id
					},
					success:function( response ) {
					}
				});  
			}
		}).disableSelection();
	});
</script>