<?php 
/* signup setting  filed get */
$pto_display_all_sing_ups = get_option( 'pto_display_all_sing_ups' );
$pto_volunteers_sign_ups = get_option( 'pto_volunteers_sign_ups' );
$pto_checkout_sign_ups = get_option( 'pto_checkout_sign_ups' );
$pto_post_sign_thank_you = get_option( 'pto_post_sign_thank_you' );

$number_of_archive = get_option( 'number_of_archive' );
$signup_title = get_option( 'signup_title' );
$no_date_sign_ups = get_option( 'no_date_sign_ups' );
$repeating_sign_ups = get_option( 'repeating_sign_ups' );
$sortby_sing_ups = get_option( 'sortby_sing_ups' );
$sort_type = get_option( 'sort_type' );
$title_text_size = get_option( 'title_text_size' );
$title_text_color = get_option( 'title_text_color' );
?>
<div class="pto_sign_ups_class_page_create">
	<label><?php esc_html_e( "Set the 'Sign Ups Display' page to display all your published sign ups.", PTO_SIGN_UP_TEXTDOMAIN ); ?>
	<i class="fa fa-info-circle" title="This page will display all your published sign ups and allows you to point volunteers to a page to see how and where they can help out." aria-hidden="true"></i>
</label>
<div class="pto_sign_ups_class_page_create_field">
	<?php
	$args = array( "id"=>"pto-display-all-sing-ups", 'name'=>"pto-display-all-sing-ups", 'show_option_none'=> 'Choose a page', "selected"=>$pto_display_all_sing_ups );
	wp_dropdown_pages( array_map( 'esc_attr', $args ) ); 
	?>
	<a class="button button-primary" href="javascript:void(window.open('<?php echo esc_url( site_url() ). "/wp-admin/post-new.php?post_type=page&pto-display-all-sing-ups"; ?>'))">Create Page</a>
</div>
</div>
<div class="pto_sign_ups_class_page_create">
	<label><?php esc_html_e( "Set the 'Sign Up Receipt' page to display your volunteer's sign up history.", PTO_SIGN_UP_TEXTDOMAIN ); ?>
	<i class="fa fa-info-circle" title="This page will display all your volunteer's sign up receipts. Here they can manage their own sign ups if needed. A user must login to access this page." aria-hidden="true"></i>
</label>
<div class="pto_sign_ups_class_page_create_field">
	<?php
	$args = array( "id"=>"pto-volunteers-sign-ups", 'name'=>"pto-volunteers-sign-ups", 'show_option_none'=> 'Choose a page', "selected"=>$pto_volunteers_sign_ups );
	wp_dropdown_pages( array_map( 'esc_attr', $args ) );
	?>
	<a class="button button-primary" href="javascript:void(window.open('<?php echo esc_url( site_url() ). "/wp-admin/post-new.php?post_type=page&pto-volunteers-sign-ups"; ?>'))" >Create Page</a>
</div>
</div>
<div class="pto_sign_ups_class_page_create">
	<label><?php esc_html_e( "Set the 'Sign Up Checkout' page.", PTO_SIGN_UP_TEXTDOMAIN ); ?>
	<i class="fa fa-info-circle" title="This page will display the final 'checkout' page for your volunteers to finalize their sign up." aria-hidden="true"></i>
</label>
<div class="pto_sign_ups_class_page_create_field">
	<?php
	$args= array( "id"=>"pto-checkout-sign-ups", 'name'=>"pto-checkout-sign-ups", 'show_option_none'=> 'Choose a page', "selected"=>$pto_checkout_sign_ups );
	wp_dropdown_pages( array_map( 'esc_attr', $args ) );
	?>
	<a class="button button-primary" href="javascript:void(window.open('<?php echo esc_url( site_url() ). "/wp-admin/post-new.php?post_type=page&pto-checkout-sign-ups"; ?>'))" >Create Page</a>
</div>
</div>
<div class="pto_sign_ups_class_page_create">
	<label><?php esc_html_e( "Set the 'Post-Successful Sign Up' page to send your volunteers to. i.e. Thank You page.", PTO_SIGN_UP_TEXTDOMAIN ); ?>
	<i class="fa fa-info-circle" title="This page is where your volunteer will be directed to after a successful sign up." aria-hidden="true"></i>
</label>
<div class="pto_sign_ups_class_page_create_field">
	<?php
	$args= array( "id"=>"pto-post-sign-thank-you", 'name'=>"pto-post-sign-thank-you", 'show_option_none'=> 'Choose a page', "selected"=>$pto_post_sign_thank_you );			
	wp_dropdown_pages( array_map( 'esc_attr', $args ) );
	?>
	<a class="button button-primary" href="javascript:void(window.open('<?php echo esc_url( site_url() ). "/wp-admin/post-new.php?post_type=page&pto-post-sign-thank-you"; ?>'))" >Create Page</a>
</div>
</div>