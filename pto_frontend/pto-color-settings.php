<?php

$get_color_option = get_option('pto_color_sign_ups_setting');

$allcolors = "";

$pto_background_color = "";

$pto_text_color = "";

$pto_cat_background_color = "";

$pto_cat_text_color = "";

$pto_task_background_color = "";

$pto_task_text_color = "";

if(!empty($get_color_option)){

	$allcolors = $get_color_option;

	$pto_background_color = $allcolors['pto-background-color'];

	$pto_text_color = $allcolors['pto-text-color'];

	$pto_cat_background_color = $allcolors['pto-header-background'];

	$pto_cat_text_color = $allcolors['pto-header-text-color'];

	$pto_task_background_color = $allcolors['pto-task-header-background-color'];

	$pto_task_text_color = $allcolors['pto-task-header-text-color'];
    
}
if( empty( $pto_background_color ) ){
    $pto_background_color="#2271b1";
}

?>

<style>

	.pto-signup-plugin .pto-custom-style .pto-signup-btn-background-color{		

		background-color: <?php esc_html_e($pto_background_color). " !important"; ?>;

	}

	.pto-signup-plugin  .pto-signup-thank-you .pto-signup-back-button .front-primary-btn{

		background-color: <?php esc_html_e($pto_background_color). " !important"; ?>;

	}
	.signup_loader_div span{
		background: <?php echo esc_html($pto_background_color). " !important"; ?>;
	}

	.front-primary-btn:hover{

		border-color: <?php esc_html_e($pto_background_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-btn-text-color{		

		color: <?php esc_html_e($pto_text_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-cat-background-color h3{		

		background-color: <?php esc_html_e($pto_cat_background_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-cat-text-color h3{		

		color: <?php esc_html_e($pto_cat_text_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-task-background-color thead{		

		background-color: <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-task-text-color thead th{		

		color: <?php esc_html_e($pto_task_text_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-custom-style .pto-signup-owner .view-volunteers::after{

		color: <?php esc_html_e($pto_background_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-signup-details{

		border-color:  <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	.pto-signup-plugin .pto-singup-task-block{

		border-color:  <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	.pto-signup-plugin  .pto-singup-task-custom-fields{

		border-color:  <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	.main-mysignup-listings .pto-singup-task-blocks-list .task-date-time{

		border-color:  <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	.pto-signup-plugin ::-webkit-scrollbar-thumb{

		background:  <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

	::-webkit-scrollbar-track {

		background: <?php echo "#fff " ; ?>;

	}

	.pto-signup-plugin	ul.checkbox-list{

		scrollbar-color: <?php esc_html_e($pto_task_background_color) ." ". "#fff " ; ?>;

	}

	.multiple-dates.cust-dropdown-contant{

		scrollbar-color: <?php esc_html_e($pto_task_background_color) ." ". "#fff " ; ?>;

	}

	.table-responsive{

		scrollbar-color: <?php esc_html_e($pto_task_background_color) ." ". "#fff " ; ?>;   

	}

	.pto-signup-plugin .pto-custom-style .pto-cart-tasks-count {

		background-color: <?php esc_html_e($pto_task_background_color). " !important"; ?>;

	}

</style>