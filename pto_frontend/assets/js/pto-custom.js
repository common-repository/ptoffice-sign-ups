/* tabination data from database */
jQuery(document).ready(function($) {
	$( function() {
		$( "#tabs" ).tabs();		
	} );
// 	jQuery.ajax({
// 		method:"POST",
// 		url:pto_front_ajax_url.ajax_url,
// 		data:{ 
// 			action:'pto_singup_header_cart',
// 			nonce: pto_front_ajax_url.nonce, 
// 		},
// 		success:function( response ){
// 			console.log(response);
// 			var result = response.split('_');
// 			jQuery( "header:first" ).before( "<div class='pto-header-cart'><a href='"+result[1]+"'><strong class='pto-cart-tasks-count'>"+result[0]+"</strong></a></div>" );		
// 		}
// 	});	
});
/* agree to terms popup */
jQuery(document).on("click", ".checkout-agreetoterm-popup", function(){	
	jQuery(this).parent(".pto-radio-checkbox-wrap").next(".agree-to-terms-content").addClass("pto-modal-open");
});
// let menuadd = jQuery(".pto-cart-signup").html();
// let m_ch = 0;
// jQuery(".main-menu").each( function(){
//     if( m_ch == 0 ){
//         jQuery(this).append(menuadd);    
//     }
//     m_ch++;
// } )
// let m_ch_f = 0;
// jQuery(".navbar-nav").each( function(){
//     if( m_ch == 0 ){
//         jQuery(this).append(menuadd);    
//     }
//     m_ch_f++;
// } )


// jQuery(".primary-menu ,.primary-menu-list,.wp-block-navigation__container").append(menuadd);
// jQuery("#primary-menu, #primary-menu-list").append(menuadd);

/*sign up search */
jQuery(document).on("click", ".search-signup-btn", function(){	
	var search_text = jQuery("#search-signup").val();
		jQuery.ajax({
			method:"POST",
			url:pto_front_ajax_url.ajax_url,
			dataType: "json",
			data:{ 
				action: 'pto_sign_up_search', 
				nonce: pto_front_ajax_url.nonce,
				search_text: search_text 
			},
			success:function( data ){
				if(data.all_signup_data != ""){
					jQuery("#all-signup ul").html(data.all_signup_data);
				}
				else{
					jQuery("#all-signup ul").html("No data found.");
				}
				if(data.my_signup_data != ""){
					jQuery("#my-signup ul").html(data.my_signup_data);
				}
				else{
					jQuery("#my-signup ul").html("No data found.");
				}					
			}
		});
});
/* view volunteers */
jQuery(document).on("click", ".view-volunteers", function(){
	var singup_id = jQuery(this).data("id");
	var vhtml = jQuery(".pto-singup-view-volunteer-list").html();
	if(vhtml == ""){
		jQuery(this).addClass("btn_toggle");
		jQuery.ajax({
			method:"POST",
			url:pto_front_ajax_url.ajax_url,
			data:{ 
				action:'pto_view_signup_tasks_volunteers',
				nonce: pto_front_ajax_url.nonce, 
				singup_id: singup_id 
			},
			success:function( response ){			
				console.log(response);			
				jQuery(".pto-singup-view-volunteer-list").html(response);
			}
		});		
	}
	else{
		jQuery(this).removeClass("btn_toggle");
		jQuery(".pto-singup-view-volunteer-list").html("");
	}
	
});
jQuery(document).on("click", ".choose-shift", function(){
	var checkbox = jQuery(this).prev(".task-shift");
	checkbox.parent("li").parent("ul").find(".task-shift").prop("checked", false);			
	checkbox.parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val("");
	checkbox.parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
	checkbox.parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
	checkbox.prev(".task-shift").prop("checked", true);	
});
jQuery(document).on("click", ".span-choose-shift", function(){
	jQuery(this).toggleClass("toggle_shift");
});	
var last_valid_selection = null;
jQuery(document).on("change", ".task-shift", function(){
	if(jQuery(this).is(':checked')){
		var checkval = jQuery(this).val();
		var taskid = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").val();
		
		if(checkval == 0){
			jQuery(this).parent("li").parent("ul").find(".task-shift").prop("checked", false);
			jQuery(this).prop("checked", true);
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val("");
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
		}
		else{
			var hidn_val = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val();
			if(hidn_val == ""){
				hidn_val = checkval;
			}
			else{
				hidn_val += ","+checkval;
			}
			var hdArray = hidn_val.split(',');	
			hidn_val = "";
			for(i = 0; i<hdArray.length; i++){
				if(hdArray[i] != ""){
					hidn_val += hdArray[i] + ",";
				}
			}			
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val(hidn_val);
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", true);
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val(taskid);
		}
	}
	else{
		var checkval = jQuery(this).val();
		var hidn_val = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val();
			if(hidn_val == ""){
				jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
				jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
			}
			else{
				checkval_comma = checkval+",";
				if(hidn_val.includes(checkval_comma)){					
					hidn_val = hidn_val.replace(checkval_comma, '');
					if(hidn_val == ""){
						jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
						jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
					}
				}
				if(hidn_val.includes(checkval)){					
					hidn_val = hidn_val.replace(checkval, '');
					if(hidn_val == ""){
						jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
						jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
					}
				}				
			}
			var hdArray = hidn_val.split(',');	
			hidn_val = "";
			for(i = 0; i<hdArray.length; i++){
				if(hdArray[i] != ""){
					hidn_val += hdArray[i] + ",";
				}
			}			
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").next(".task-shift-hidden").val(hidn_val);
	}
	var count = 0;
	jQuery(this).parent("li").parent("ul").find(".task-shift").each(function() {		
		if(jQuery(this).is(':checked')){
			if(jQuery(this).val() != 0){
				count = count + 1;
			}
		}
	});
	if(count == 0){
		jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").prop("checked", false);
		jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task-hidden").val("");
	}
	jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".pto-singup-task-max-number-select option").val(count);
});
jQuery(document).on("change", ".pto-singup-task-max-number-select", function(){
	var selectval = jQuery(this).val();
	var taskid = jQuery(this).prev(".sign-up-task-hidden").prev(".sign-up-task").val();
	
	if(selectval == 0){
		jQuery(this).prev(".sign-up-task-hidden").prev(".sign-up-task").prop("checked", false);
		jQuery(this).prev(".sign-up-task-hidden").val("");
	}
	else{
		jQuery(this).prev(".sign-up-task-hidden").prev(".sign-up-task").prop("checked", true);
		jQuery(this).prev(".sign-up-task-hidden").val(taskid);
	}
});
/* task listing next/prev */
jQuery(document).on("click", ".signup-tasks-nextBtn", function(){	
	var frm = jQuery("#pto-signup-frontend");
	var locationurl = jQuery(this).data("url");
	var date_length = jQuery(".multiple-dates").length;
	var task_check = "";
	if(date_length != 0){
		var date_chk = "";	
		jQuery(".multiple-dates-signup").each(function(){
			if(jQuery(this).prop("checked")){
				date_chk = jQuery(this).val();
			}
		});
		if(date_chk == ""){
			swal({
				text: "Please check at least one of the date from dropdown.",
				icon: "error",
				button: "Ok",
			});
			return false;
		}
	}
	
	jQuery(".sign-up-task").each(function(){
		if(jQuery(this).prop("checked")){
			task_check = jQuery(this).val();
		}
	});
	if(task_check == ""){
		swal({
			text: "Please check at least one of the task.",
			icon: "error",
			button: "Ok",
		});
	}
	else{
		jQuery.ajax({
			method:"POST",
			url:pto_front_ajax_url.ajax_url,
			data:{ 
				action:'pto_save_signup_tasks',
				nonce: pto_front_ajax_url.nonce,
				keyword: frm.serialize() 
			},
			success:function( response ){			
				console.log(response);			
				location.href = locationurl;
			}
		});
	}		
});
/* update my signup */
jQuery(document).on("click", ".signup-tasks-updateBtn", function(){	
	var frm = jQuery("#pto-signup-frontend");
	var locationurl = jQuery(this).data("url");
	
	var task_check = "";	
	
	jQuery(".sign-up-task").each(function(){
		if(jQuery(this).prop("checked")){
			task_check = jQuery(this).val();
		}
	});
	if(task_check == ""){
		swal({
			text: "Please check at least one of the task.",
			icon: "error",
			button: "Ok",
		});
	}
	else{
		jQuery.ajax({
			method:"POST",
			url:pto_front_ajax_url.ajax_url,
			data:{ 
				action:'pto_update_signup_tasks',
				nonce: pto_front_ajax_url.nonce, 
				keyword: frm.serialize() 
			},
			success:function( response ){			
				console.log(response);			
			}
		});
	}		
});
jQuery(document).on("click", ".pto-task-desc", function(){
	jQuery(this).next(".pto-task-content").addClass("pto-modal-open");	
});
jQuery(document).on("click", ".signup-submit", function(){
	var frm = jQuery("#pto-signup-checkout-frontend");
	var locationurl = jQuery(this).data("url");
	var uid = jQuery(this).attr("uid");
	var flag = 0;
	var btnid = jQuery(this).attr("id");
	jQuery("#pto-signup-checkout-frontend input[type=text]:required").each(function(){				
		if(jQuery(this).val() == ""){ 			
			jQuery(this).css("border-color", "red");
			flag = 1; 
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	jQuery("#pto-signup-checkout-frontend input[type=email]:required").each(function(){	
		var emailid = jQuery(this).val();		
		if(jQuery(this).val() == ""){ 			
			jQuery(this).css("border-color", "red");
			flag = 1; 
		}
		else{
			if(emailid.match(/^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i)){	
				jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");							
			}
			else{
				jQuery(this).css("border-color", "red");
				flag = 2;
			}			
		}
	});
	jQuery("#pto-signup-checkout-frontend input[type=number]:required").each(function(){		
		if(jQuery(this).val() == ""){ 			
			jQuery(this).css("border-color", "red");
			flag = 1; 
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	jQuery("#pto-signup-checkout-frontend textarea:required").each(function(){		
		if(jQuery(this).val() == ""){ 			
			jQuery(this).css("border-color", "red");
			flag = 1; 
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	jQuery("#pto-signup-checkout-frontend select:required").each(function(){
		if(jQuery(this).val() == ""){ 			
			jQuery(this).css("border-color", "red");
			flag = 1; 
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	jQuery("input[type=radio]:required").each(function(){
		var name = jQuery(this).attr("name");
		if(jQuery("input:radio[name='"+name+"']:checked").length == 0){
			jQuery(this).css("border-color", "red");
			flag = 1;
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	jQuery("input[type=checkbox]:required").each(function(){
		var name = jQuery(this).attr("name");
		if(jQuery("input:checkbox[name='"+name+"']:checked").length == 0){
			jQuery(this).css("border-color", "red");
			flag = 1;
		}
		else{
			jQuery(this).css("border-color", "rgba(203,213,225,var(--tw-border-opacity))");
		}
	});
	if(flag == 1){
		swal({
			text: "Please fill in required fields.",
			icon: "error",
			button: "Ok",
		});
	}
	else if(flag == 2){
		swal({
			text: "Invalid Email ID.",
			icon: "error",
			button: "Ok",
		});
	}
	else{	
		jQuery( ".signup_loader_main" ).show();
		if(btnid == "signup-submit-update"){
			
			jQuery.ajax({
				method:"POST",
				url:pto_front_ajax_url.ajax_url,
				// dataType : 'json',
				data:{ 
					action:'pto_signup_checkout_update',
					nonce: pto_front_ajax_url.nonce,
					uid: uid, 
					keyword: frm.serialize() 
				},
				success:function( response ){
					setTimeout(function(){
						 jQuery( ".signup_loader_main" ).hide();
					
						if(response.err){
							swal({
								text: response.err,
								icon: "error",
								button: "Ok",
							});
						}else if(response == "error"){

							swal({

								text: "Something goes wrong with your update.",

								icon: "error",

								button: "Ok",

							});

						}else if(response == "er"){

							swal({

								text: "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you!",

								icon: "error",

								button: "Ok",

							});

						}		
						else{
						    location.href = locationurl;
						}								
					}, 2000)
				   	
				}
			});
		}
		else{
		    let email = jQuery( "#pto_signup_user_email" ).val();
			jQuery.ajax({
				method:"POST",
				url:pto_front_ajax_url.ajax_url,
				data:{ 
					action:'pto_signup_checkout',
					nonce: pto_front_ajax_url.nonce, 
					uid: uid,
					keyword: frm.serialize(),
					uemail:email
				},
				success:function( response ){
				    jQuery( ".signup_loader_main" ).hide();
					if(response == "User already exist"){
						swal({
							text: "Username or Email ID already exists.",
							icon: "error",
							button: "Ok",
						});									
					}
					else if(response == "Invalid Email ID"){
						swal({
							text: "Invalid Email ID.",
							icon: "error",
							button: "Ok",
						});
					}
					else if(response.includes("Following")){
						swal({
							text: "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.",
							icon: "error",
							button: "Ok",
						});
					}else if(response == "Alredy Sign up"){
						swal({
							text: "One or more tasks that you are trying to sign up for are no longer available. This is typically due to someone else signing up while you were in the checkout process. Please revisit the sign up page to see the latest available tasks/slots. Thank you.",
							icon: "error",
							button: "Ok",
						});
					}
					else{
					   setTimeout(function(){
					        location.href = locationurl;
					   }, 2000);
						
					}									
				}
			});
		}
		
	}	
});
jQuery(document).on("click", ".pto-signup-remove-from-signup", function(){
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this signup!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			var id = jQuery(this).data("id");
			var text = jQuery(".pto-show-archived").text();
			jQuery.ajax({
				method:"POST",
				url:pto_front_ajax_url.ajax_url,
				data:{ 
					action:'pto_signup_remove_my_signup',
					nonce: pto_front_ajax_url.nonce, 
					id: id,
					text: text 
				},
				success:function( response ){	
					jQuery(".main-mysignup-listings").html(response);
				}
			});
		} 
	});	
});
jQuery(document).on("click", ".pto-signup-resend-receipt", function(){	
	var id = jQuery(this).data("id");
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_my_singup_resend_receipt',
			nonce: pto_front_ajax_url.nonce, 
			id: id 
		},
		success:function( response ){
			console.log(response);
			if(response == "error"){
				swal({
					text: "Error in sending receipt. (Please set the email content in backend for this signup)",
					icon: "error",
					button: "Ok",
				});
			}
			else{
				swal({
					text: "Receipt sent successfully, Please check your email.",
					icon: "success",
					button: "Ok",
				});		
			}
		}
	});
});
jQuery(document).on("click", ".pto-signup-moveto-archive", function(){
	swal({
		title: "Are you sure you want to archive this sign up?",
		text: "Doing so will remove this sign up data from all reporting pages. This data will reappear if you choose to unarchive the sign up later.",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			var id = jQuery(this).data("id");
			var text = jQuery(".pto-show-archived").text();
			jQuery.ajax({
				method:"POST",
				url:pto_front_ajax_url.ajax_url,
				data:{ 
					action:'pto_signup_to_archive',
					nonce: pto_front_ajax_url.nonce, 
					id: id,
					text: text 
				},
				success:function( response ){	
					jQuery(".main-mysignup-listings").html(response);
				}
			});  
		} 
	});
});
jQuery(document).on("click", ".pto-signup-moveto-unarchive", function(){
	var id = jQuery(this).data("id");
	var text = jQuery(".pto-show-archived").text();
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_signup_to_unarchive',
			nonce: pto_front_ajax_url.nonce, 
			id: id,
			text: text 
		},
		success:function( response ){	
			jQuery(".main-mysignup-listings").html(response);
		}
	});
});
jQuery(document).on("click", ".pto-show-archived", function(){
	var text = jQuery(this).text();
	var thisa = jQuery(this);
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_signup_show_archive_unarchive',
			nonce: pto_front_ajax_url.nonce, 
			text: text 
		},
		success:function( response ){			
			console.log(response);	
			jQuery(".main-mysignup-listings").html(response);
			if(text == "Show Archived"){
				thisa.text("Show Original");
			}
			else{
				thisa.text("Show Archived");
			}
		}
	});
});
jQuery(document).on("click", ".pto-signup-checkout-delete", function(){
	var signupid = jQuery(this).data("id");
	var userid = jQuery(this).data("user-id");
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_remove_signup_checkout',
			nonce: pto_front_ajax_url.nonce, 
			signupid: signupid,
			userid: userid
		},
		success:function( response ){			
			console.log(response);
			if(response == "No Signup"){
				jQuery(".pto-custom-fields").html(response);
				location.reload();				
			}
			else{
				jQuery(".pto-custom-fields").html(response);
				location.reload();				
			}			
		}
	});
});	
jQuery(document).on("change", ".pto-signup-task-sorting", function(){
	var sortval = jQuery(this).val();
	var signupid = jQuery("h2.signup-title").attr("postid");
	var selected_dates = "";
	jQuery(".multiple-dates-signup").each(function(){
		if(jQuery(this).prop("checked")){
			var multiple_dates_signup = jQuery(this).val();
			selected_dates +=  multiple_dates_signup + ",";
		}
	});
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_signup_tasks_sorting',
			nonce: pto_front_ajax_url.nonce, 
			selected_dates: selected_dates,
			sortval: sortval, 
			signupid: signupid
		},
		success:function( response ){			
			jQuery(".pto-signup-task-list").html(response);
		
		}
	});
});
jQuery(document).on("click", ".cat-collapsed h3", function(){
	jQuery(this).parent(".single-signup-task-list-table").addClass("cat-expand");
	jQuery(this).parent(".single-signup-task-list-table").removeClass("cat-collapsed");
});
jQuery(document).on("click", ".cat-expand h3", function(){
	jQuery(this).parent(".single-signup-task-list-table").addClass("cat-collapsed");
	jQuery(this).parent(".single-signup-task-list-table").removeClass("cat-expand");
});
jQuery(document).on("click", ".signup-show-more", function(){
	jQuery(this).prev("table").find(".pto-signup-tasks tr.extra-tr").show();	
	jQuery(this).text("Show Less");
	jQuery(this).addClass("signup-show-less");
	jQuery(this).removeClass("signup-show-more");
});
jQuery(document).on("click", ".signup-show-less", function(){
	jQuery(this).prev("table").find(".pto-signup-tasks tr.extra-tr").hide();	
	jQuery(this).text("Show More");
	jQuery(this).addClass("signup-show-more");
	jQuery(this).removeClass("signup-show-less");
});
jQuery(document).on("click", ".sign-up-task", function(){
	if(jQuery(this).prop("checked")){
		sign_up_task = jQuery(this).val();
		jQuery(this).next(".sign-up-task-hidden").val(sign_up_task);
	}
	else{
		jQuery(this).next(".sign-up-task-hidden").val("");
	}
});
jQuery(document).on("click", ".pto-signup-logout-here", function(){
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_logout_from_checkout', 
			nonce: pto_front_ajax_url.nonce			
		},
		success:function( response ){			
		}
	});
});
jQuery(document).on("click", "#all-dates", function(){
	if(jQuery(this).prop("checked")){
		jQuery(".multiple-dates-signup").prop("checked", true);
	}else{
		jQuery(".multiple-dates-signup").prop("checked", false);
	}
});
jQuery(document).on("click", ".btn-date-done", function(){
	var multiple_dates_signup = "";	
	var selected_dates = "";
	var signupid = jQuery("#pto-sign-up").val();
	var uid = jQuery(this).attr("uid");
	var orderid = jQuery(this).attr("orderid");
	jQuery(".multiple-dates-signup").each(function(){
		if(jQuery(this).prop("checked")){
			multiple_dates_signup = jQuery(this).val();
			selected_dates +=  multiple_dates_signup + ",";
		}
	});
	
	if(multiple_dates_signup == ""){
		jQuery(".pto-signup-task-list").hide();
	}
	else{
		console.log("helloo");
		jQuery(".pto-signup-task-list").show();
		jQuery.ajax({
			method:"POST",
			url:pto_front_ajax_url.ajax_url,
			dataType: "json",
			data:{ 
				action:'pto_sing_up_get_recurrence_task_list', 
				nonce: pto_front_ajax_url.nonce,
				selected_dates: selected_dates,
				signupid: signupid,
				uid: uid,
				orderid: orderid			
			},
			success:function( response ){			
						
				if(response.scat == 1){
					jQuery('.pto-signup-task-sorting').val('category').trigger('change');
				}else{
					jQuery(".pto-signup-task-list").html(response.data.html);
				}
				
			}
		});
	}
	jQuery(".cust-dropdown").removeClass("show-dropdown");
});
jQuery(document).on("click", ".choose-dates", function(){
	if(jQuery(".cust-dropdown").hasClass("show-dropdown")){
		jQuery(".cust-dropdown").removeClass("show-dropdown");
	}
	else{
		jQuery(".cust-dropdown").addClass("show-dropdown");
	}	
});
/* sorting ASC/DESC and sort by date/name for signup listing */
jQuery(document).on("click", ".sort-by", function(){
	var sortby = jQuery(this).attr("sortby");
	var sorttype = jQuery("a.sort-type").attr("sorttype");
	if(sortby == "name"){
		jQuery(this).after('<a href="#0" class="sort-by front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sortby="date">Sort by Date</a>');
		jQuery(this).remove();
	}
	else{
		jQuery(this).after('<a href="#0" class="sort-by front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sortby="name">Sort by Name</a>');
		jQuery(this).remove();
	}
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_list_sorting', 
			nonce: pto_front_ajax_url.nonce,
			sortby:sortby, 
			sorttype:sorttype 
		},
		success:function( response ){
			console.log(response);
			jQuery("ul.vertical-signup").html(response);
		}
	});
});
jQuery(document).on("click", ".sort-type", function(){
	var sorttype = jQuery(this).attr("sorttype");
	var sortby = jQuery("a.sort-by").attr("sortby");
	var flag = 1;
	if(sorttype == "ASC"){
		jQuery(this).after('<a href="#0" class="sort-type front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sorttype="DESC">DESC</a>');
		jQuery(this).remove();
	}
	else{
		jQuery(this).after('<a href="#0" class="sort-type front-primary-btn pto-signup-btn-text-color pto-signup-btn-background-color" sorttype="ASC">ASC</a>');
		jQuery(this).remove();
	}
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_list_sorting', 
			nonce: pto_front_ajax_url.nonce,
			sortby:sortby, 
			sorttype:sorttype, 
			flag:flag 
		},
		success:function( response ){
			console.log(response);
			jQuery("ul.vertical-signup").html(response);
		}
	});
});
/* signup request access */
jQuery(document).on("click",".signup-access-btn",function(){
	let this_signup = jQuery(this);
	let signup_id = jQuery(this).attr("data-id");	
	jQuery.ajax({
		method:"POST",
		url:pto_front_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_request_access', 
			nonce: pto_front_ajax_url.nonce,
			signup_id:signup_id 
		},
		success:function( response ){
			console.log(response);
			if(response == "done"){
				this_signup.parent("a").after( "<span>Requested</span>" );
				this_signup.parent("a").remove();	
			}
			else{
				swal({
					text: "There is some issue in requesting.",
					icon: "error",
					button: "Ok",
				});				
			}						
		}
	});
});

jQuery( document ).on( 'keypress' , "input" , function(){
    // Get the pressed key's character code
    const charCode = event.charCode || event.keyCode;
    console.log(charCode);
    // Define the list of disallowed characters (`,`, `[`, `]`, `{`, `}`)
    const disallowedChars = [44, 91, 93, 123, 125,92,34,60,62];

    // Check if the pressed key is in the disallowed list
    if (disallowedChars.includes(charCode)) {
        swal({
            text: "This character is not allowed. Please try another.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
      event.preventDefault(); // Prevent the character from being entered
    }
});
jQuery( document ).on( 'keypress' , "textarea" , function(){
    // Get the pressed key's character code
    const charCode = event.charCode || event.keyCode;
    console.log(charCode);
    // Define the list of disallowed characters (`,`, `[`, `]`, `{`, `}`)
    const disallowedChars = [44, 91, 93, 123, 125,92,34,60,62];

    // Check if the pressed key is in the disallowed list
    if (disallowedChars.includes(charCode)) {
        swal({
            text: "This character is not allowed. Please try another.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
      event.preventDefault(); // Prevent the character from being entered
    }
});

