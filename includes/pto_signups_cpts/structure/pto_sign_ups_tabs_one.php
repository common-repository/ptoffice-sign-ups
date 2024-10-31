<?php 
global $post;
$pto_sign_up_signupdescreption = get_post_meta( $post->ID, "pto_sign_up_signupdescreption", true );
$pto_sign_up_address = get_post_meta( $post->ID, "pto_sign_up_address", true );
$pto_sign_ups_time_set = get_post_meta( $post->ID, "pto_sign_ups_time_set", true ); 	
?>
<div class="pto-sign-up-compelling-descreption">
	<div class="pto-sign-up-compelling-desc">
		<div class="pto-sign-up-compelling-descreption-title toggle-click">
			<h4><?php esc_html_e( 'Add a compelling sign up description (optional)', PTO_SIGN_UP_TEXTDOMAIN ); ?> 
			<i class="fa fa-info-circle" title="Add a description that will encourage your users to sign up. We recommend including why this sign up is important or who it might benefit." aria-hidden="true"></i>
		</h4>
	</div>
	<div class="pto-sign-up-compelling-descreption-textarea toggle-box">
		<?php 
		$content = $pto_sign_up_signupdescreption;
		wp_editor( $content, 'signupdescreption', $settings = array(
			'textarea_name' => 'signupdescreption',
			'textarea_rows' => 10
		) );
		?>
	</div>
</div>
<div class="pto-sign-up-compelling-address_areas pto-admin-setting-user-details mt-15px ">
	<div class="pto-sign-up-compelling-descreption-title toggle-click">
		<h4><?php esc_html_e( 'Add a location for this sign up (optional)', PTO_SIGN_UP_TEXTDOMAIN ); ?></h4>
	</div>
	<div class="pto-sign-up-compelling-descreption-textarea toggle-box">		
		<div class="pto_signup_areas">
			<input type="text" name="address1" id="address1" placeholder="Address1" value="<?php if ( is_array( $pto_sign_up_address ) ) { if ( array_key_exists( "address1", $pto_sign_up_address ) ) esc_html_e( $pto_sign_up_address['address1'] ); } ?>">
			<input type="text" name="address2" id="address2" placeholder="Address2" value="<?php if ( is_array( $pto_sign_up_address ) ) { if ( array_key_exists( "address2", $pto_sign_up_address ) ) esc_html_e( $pto_sign_up_address['address2'] ); } ?>">
			<input type="text" name="city" id="city" placeholder="City" value="<?php if ( is_array( $pto_sign_up_address ) ) { if( array_key_exists( "city", $pto_sign_up_address ) ) esc_html_e( $pto_sign_up_address['city'] ); } ?>">
			<input type="text" name="state" id="state" placeholder="State" value="<?php if ( is_array( $pto_sign_up_address ) ) { if( array_key_exists( "state", $pto_sign_up_address ) ) esc_html_e( $pto_sign_up_address['state'] ); } ?>">
			<input type="text" name="postalcode" id="postalcode" placeholder="Postcode" value="<?php if ( is_array( $pto_sign_up_address ) ) { if ( array_key_exists( "postalcode", $pto_sign_up_address ) ) esc_html_e( $pto_sign_up_address['postalcode'] ); } ?>">
		</div>
	</div>
</div>
<?php
$time_set = get_post_meta( $post->ID, "pto_sign_ups_time_set", true );		
?>
<div class="pto-sign-up-compelling-address_live_published pto-admin-setting-user-details mb-15px">
	<div class="pto-sign-up-compelling-live_published-title toggle-click">
		<h4><?php esc_html_e( 'Choose when your sign up should go live / be published', PTO_SIGN_UP_TEXTDOMAIN ); ?>
		<i class="fa fa-info-circle" title="Your sign up will not be seen by pontential volunteers until the 'published' window you set below." aria-hidden="true"></i>
	</h4>
</div>
<div class="pto-sign-up-compelling-live_published_details toggle-box">
	<div class="">
		<input type="radio" name="publish_date" class="publish_checked" value="imediately_publish" checked>		
		<label><?php esc_html_e( 'Open my sign up immediately after I click PUBLISH', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
	</div>
	<div>
		<input type="radio" name="publish_date" value="specifc_publish" class="publish_checked" <?php if(!empty( $pto_sign_ups_time_set )){ esc_html_e("checked"); } ?>>		
		<label><?php esc_html_e( 'Open my sign up at a specific day and time after I click PUBLISH', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
	</div>
	<div class="specific-timezone-pto mt-15px" style="<?php if ( !empty( $pto_sign_ups_time_set ) ) { esc_html_e("display: flex"); } else { esc_html_e("display: none"); } ?>">
		<div class="open-time-zone">
			<label><?php esc_html_e( 'OPEN', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
			<input type="date" name="opendate" id="opendate" value="<?php if ( !empty( $time_set ) ) { esc_html_e( $time_set['opendate'] ); }  ?>"><?php esc_html_e( 'at', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			<input type="time" name="opentime" id="opentime"  value="<?php if ( !empty( $time_set ) ) { esc_html_e( $time_set['opentime'] ); } ?>">
		</div>
		<div class="close-time-zone">
			<label><?php esc_html_e( 'CLOSE', PTO_SIGN_UP_TEXTDOMAIN ); ?></label>
			<input type="date" name="closedate" id="closedate" min="<?php if ( !empty( $time_set ) ) { esc_html_e( $time_set['opendate'] ); }  ?>" value="<?php if ( !empty( $time_set ) ) { esc_html_e( $time_set['closedate'] ); }  ?>"><?php esc_html_e( 'at', PTO_SIGN_UP_TEXTDOMAIN ); ?>
			<input type="time" name="closetime" id="closetime" value="<?php if ( !empty( $time_set ) ) { esc_html_e( $time_set['closetime'] ); } ?>">
		</div>
	</div>
</div>
</div>
</div>