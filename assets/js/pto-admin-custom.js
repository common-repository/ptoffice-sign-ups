/* tabination data from database */
function numberOnly(input) {
    var regex = /[^0-9]/gi;
    input.value = input.value.replace(regex, "");
}
function step1validation() {
	var error = 0;
    if(jQuery("#opendate").val() == "" || jQuery("#opendate").val() == undefined){
		error = 1;
		jQuery("#opendate").css("border-color", "red");
	}
	else{
		jQuery("#opendate").css("border-color", "#8c8f94");
	}
	if(jQuery("#opentime").val() == "" || jQuery("#opentime").val() == undefined){
		error = 1;
		jQuery("#opentime").css("border-color", "red");
	}
	else{
		jQuery("#opentime").css("border-color", "#8c8f94");
	}				
	if(jQuery("#closedate").val() == "" || jQuery("#closedate").val() == undefined){
		error = 1;
		jQuery("#closedate").css("border-color", "red");
	}
	else{
		jQuery("#closedate").css("border-color", "#8c8f94");
	}	
	if(jQuery("#closetime").val() == "" || jQuery("#closetime").val() == undefined){
		error = 1;
		jQuery("#closetime").css("border-color", "red");
	}
	else{
		jQuery("#closetime").css("border-color", "#8c8f94");
	}
	return error;
}
jQuery( ".post-type-pto-signup a.page-title-action" ).addClass("button button-primary btn_add mt-15px");
var cur_step = "step-1";
jQuery( document ).ready(function() {
	jQuery(".post-type-tasks-signup #publish").val("Save");
	jQuery(".post-type-pto-custom-fields #publish").val("Save");
	
	var btnhtmltask = '<div class="btnwrap"><button class="btnSaveTask button button-primary" type="button">Save</button><button class="btnCancel button button-primary" type="button">Cancel</button></div>';
	jQuery(".post-type-tasks-signup #poststuff").after(btnhtmltask);
	var btnhtmlcustom = '<div class="btnwrap"><button class="btnSaveCustom button button-primary" type="button">Save</button><button class="btnCancel button button-primary" type="button">Cancel</button></div>';
	jQuery(".post-type-pto-custom-fields #poststuff").after(btnhtmlcustom);
});
jQuery(document).on("click", ".btnSaveTask", function(){
	jQuery(".post-type-tasks-signup #publish").removeClass("disabled");	
	jQuery(".post-type-tasks-signup #publish").trigger("click");
});
jQuery(document).on("click", ".btnSaveCustom", function(){
	jQuery(".post-type-pto-custom-fields #publish").removeClass("disabled");	
	jQuery(".post-type-pto-custom-fields #publish").trigger("click");
});
jQuery(document).on("click", ".btnCancel", function(){
	window.close();
});
jQuery(document).ready(function($) {
	$( function() {
		$( "#pto-signup-setting-tabs" ).tabs();
	} );
	$( function() {
		var dateFormat = "mm/dd/yy",
		from = $( "#fromdate" )
			.datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1
			})
			.on( "change", function() {
			to.datepicker( "option", "minDate", getDate( this ) );
			}),
		to = $( "#todate" ).datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			numberOfMonths: 1
		})
		.on( "change", function() {
			from.datepicker( "option", "maxDate", getDate( this ) );
		});
	
		function getDate( element ) {
		var date;
		try {
			date = $.datepicker.parseDate( dateFormat, element.value );
		} catch( error ) {
			date = null;
		}	
		return date;
		}
	});
	var trlength = jQuery("#pto-manage-volunteers").find("tr").length;	
	$('#pto-manage-volunteers').DataTable( {
		dom: 'Bfrtip',
		buttons: [
					{
					
					}			
		]
	} );
	$('#pto-manage-volunteers-export').DataTable( {
		dom: 'Bfrtip',
		buttons: [
					{
						"extend": 'csv',
						"text": 'Export Results',
						"className": 'btn btn-primary btn-sm ml-1 button button-primary',
						"exportOptions": {
							"columns": ':not(.notexport)'
						}
					}			
		]		
	} );
	$('#pto-singup-filter-listing-table').DataTable( {
		dom: 'Bfrtip',
		buttons: [
					{
						"extend": 'csv',
						"text": 'Export Results not',
						"className": 'btn btn-primary btn-sm ml-1 button button-primary'
					}			
		]		
	} );
	$('#pto-singup-filter-listing-table-export').DataTable( {
		dom: 'Bfrtip',
		buttons: [
					{
						"extend": 'csv',
						"text": 'Export Results',
						"className": 'btn btn-primary btn-sm ml-1 button button-primary'
					}			
		]		
	} );
	$('#pto-singup-userwise-sorting').DataTable( {
		dom: 'Bfrtip',
		pageLength: 5,
		buttons: [
					{
					"extend": 'csv',
					"text": 'Export List',
					"className": 'btn btn-primary btn-sm ml-1 button button-primary'
					}			
		]		
	} );
});
 
jQuery(document).ready(function() {		
	var flag = 0;
   	var navListItems = jQuery('div.setup-panel div a'),
    allWells = jQuery('.setup-content'),
    allNextBtn = jQuery('.nextBtn');
    prev = jQuery('.prev');
	allSaveBtn = jQuery(".saveBtn");
	stepwizard = jQuery('.stepwizard-step');
    allWells.hide();
    navListItems.click(function(e) {		
		e.preventDefault();
		var $target = jQuery(jQuery(this).attr('href')),
		$item = jQuery(this);
		var cstep = $item.attr("href");
		var date_check = "";
		var occurence_chk = "";
		jQuery(".publish_checked").each(function(){
			if(jQuery(this).prop("checked")){
				date_check = jQuery(this).val();
			}
		});
		jQuery(".occurrence-options").each(function(){
			if(jQuery(this).prop("checked")){
				occurence_chk = jQuery(this).val();
			}
		});
		if(cstep == "#step-1"){	
			jQuery(".stepwizard-step").removeClass("step-active");
			$item.parent(".stepwizard-step").addClass("step-active");
		}
		if(cstep == "#step-2" || cstep == "#step-3"){
			if(jQuery(".step-active").hasClass("step-one")){
				if(date_check == "specifc_publish")
				{
					let err = step1validation();					
					if(err == 1){										
						swal({
							text: "The Specific day and time must be filled in.",
							icon: "warning",
							button: "Ok",
						});					
						return false;
					} 					        
					let post_id = jQuery("#post_ID").val();
					let opendate = jQuery("#opendate").val();
					let opentime = jQuery("#opentime").val();
					let closedate = jQuery("#closedate").val();
					let closetime = jQuery("#closetime").val();
					jQuery.ajax({
						method:"POST",
						url: pto_ajax_url.ajax_url,
						data:{
							action:'pto_sign_ups_time_set',
							nonce: pto_ajax_url.nonce,
							opendate:opendate,
							opentime:opentime,
							closedate:closedate,
							closetime:closetime,
							post_id:post_id
						},
						success:function( response ){					
						}
					});
					var mindate = opendate;
					var maxdate = closedate;
    				jQuery("#occurrence-specific-days").attr("min", mindate);
					jQuery("#occurrence-specific-days").attr("max", maxdate);
				}
			}
			if(jQuery(".step-active").hasClass("step-two")){				
				var tasks = jQuery("tbody.pto-signup-task-slot-list-tbody tr").length;				
				if(tasks > 0){
				}
				else{
					flag = 1;
					swal({
						text: "Please add at least one task!",
						icon: "warning",
						button: "Ok",
					})
					return false;	
				}
				if(occurence_chk == "occurrence-specific")
				{
					var occurrence_specific_days = jQuery("#occurrence-specific-days").val();
					if(occurrence_specific_days == "" || occurrence_specific_days == undefined){
						jQuery("#occurrence-specific-days").css("border-color", "red");
						flag = 1;
						swal({
							text: "The Specific day must be filled in.",
							icon: "warning",
							button: "Ok",
						});
						return false;
					}
					else{
						jQuery("#occurrence-specific-days").css("border-color", "#8c8f94");
					}
				}
			}
			jQuery(".stepwizard-step").removeClass("step-active");
			$item.parent(".stepwizard-step").addClass("step-active");
		}
		if (!$item.hasClass('disabled')) {
			navListItems.removeClass('btn-primary').addClass('btn-default');
			$item.addClass('btn-primary');
			allWells.hide();
			$target.show();
			$target.find('input:eq(0)').focus();
		}
   	});
   	allNextBtn.click(function() {
     	var curStep = jQuery(this).closest(".setup-content"),
		curStepBtn = curStep.attr("id"),
		nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
		curInputs = curStep.find("input[type='text'],input[type='url'],textarea[textarea]"),
		isValid = true;		
		let ids =  jQuery(this).closest(".setup-content").next().attr("id");
		jQuery("."+ ids).addClass("btn-circle");
		if (isValid)      
		var ids_check =jQuery(this).closest(".setup-content").attr("id");
		var date_check = "";
		var occurence_chk = "";
		jQuery(".publish_checked").each(function(){
			if(jQuery(this).prop("checked")){
				date_check = jQuery(this).val();
			}
		});
		jQuery(".occurrence-options").each(function(){
			if(jQuery(this).prop("checked")){
				occurence_chk = jQuery(this).val();
			}
		});
		if( ids_check == "step-2" ){
			var tasks = jQuery("tbody.pto-signup-task-slot-list-tbody tr").length;			
			if(tasks > 0){
			}
			else{
				flag = 1;
				swal({
					text: "Please add at least one task!",
					icon: "warning",
					button: "Ok",
				})
				return false;	
			}
		}
		if(date_check == "specifc_publish")
		{
			if(ids_check == "step-1")
			{   
				let err = step1validation();					
				if(err == 1){										
					swal({
						text: "The Specific day and time must be filled in.",
						icon: "warning",
						button: "Ok",
					});					
					return false;
				}         
				let post_id = jQuery("#post_ID").val();
				let opendate = jQuery("#opendate").val();
				let opentime = jQuery("#opentime").val();
				let closedate = jQuery("#closedate").val();
				let closetime = jQuery("#closetime").val();
				jQuery.ajax({
					method:"POST",
					url:pto_ajax_url.ajax_url,
					data:{
						action:'pto_sign_ups_time_set',
						nonce: pto_ajax_url.nonce,
						opendate:opendate,
						opentime:opentime,
						closedate:closedate,
						closetime:closetime,
						post_id:post_id
					},
					success:function( response ){					
					}
				});  
      		}
     	}
		if(occurence_chk == "occurrence-specific")
		{
			if(ids_check == "step-2")
			{ 
				var occurrence_specific_days = jQuery("#occurrence-specific-days").val();
				if(occurrence_specific_days == "" || occurrence_specific_days == undefined){
					jQuery("#occurrence-specific-days").css("border-color", "red");
					flag = 1;
					swal({
						text: "The Specific day must be filled in.",
						icon: "warning",
						button: "Ok",
					});
					return false;
				}
				else{
					jQuery("#occurrence-specific-days").css("border-color", "#8c8f94");
				}
			}
		}		
       	nextStepWizard.removeAttr('disabled').trigger('click');
		if( ids_check == "step-1" ){
			jQuery(".step-one").removeClass("step-active");
			jQuery(".step-two").addClass("step-active");
		}
		if( ids_check == "step-2" ){
			jQuery(".step-two").removeClass("step-active");
			jQuery(".step-three").addClass("step-active");
		}
   	});
   	prev.click(function() {
		var curStep = jQuery(this).closest(".setup-content"),
		curStepBtn = curStep.attr("id"),
		nextStepWizard = jQuery('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
		curInputs = curStep.find("input[type='text'],input[type='url'],textarea[textarea]"),
		isValid = true;
		var ids_check =jQuery(this).closest(".setup-content").attr("id");
		let ids =  jQuery(this).closest(".setup-content").prev().attr("id");
		jQuery("."+ ids).addClass("btn-circle");
		jQuery("."+ curStepBtn).removeClass("btn-circle");   
		if(isValid)
		nextStepWizard.removeAttr('disabled').trigger('click');
		if( ids_check == "step-3" ){
			jQuery(".step-three").removeClass("step-active");
			jQuery(".step-two").addClass("step-active");
		}
		if( ids_check == "step-2" ){
			jQuery(".step-two").removeClass("step-active");
			jQuery(".step-one").addClass("step-active");
		}
   	});
	allSaveBtn.click(function() {
		jQuery("#publish").trigger("click");
	});
   	jQuery('div.setup-panel div a.btn-primary').trigger('click');
	if(jQuery(".checkout_fields_sign_up").prop("checked")){
		jQuery(".checkout-add-new").show();
		jQuery(".custom-fields-show").show();
	}
	if(jQuery("#agree_to_terms_sign_up").prop("checked")){
		jQuery(".agree-to-terms-show").show();
	}
	if(jQuery("#volunteer-after-sign-up").prop("checked")){
		jQuery(".receipts-after-signup-show").show();
	}
	if(jQuery("#send_reminder").prop("checked")){
		jQuery(".signup-reminder-show").show();
	}
	cur_step = localStorage.getItem('currentstep');
	var cur_url = window.location.href;
	cur_url = cur_url.split("?");
	if(cur_url[1] != "post_type=pto-signup"){
		if(cur_step == "step-2"){
			jQuery(".stepwizard-step.step-two a").trigger("click");	
		}	
		if(cur_step == "step-3"){	
			jQuery(".stepwizard-step.step-three a").trigger("click");	
		}
	}
});
/* toggle boxes on all steps */
jQuery(document).on("click", ".toggle-click", function(){
	jQuery(this).toggleClass("expand");
	jQuery(this).next(".toggle-box").slideToggle();
});
/* bulk action filter tasks */
jQuery(document).on("click", "#checkall-pto-task", function(){
	if(jQuery(this).is(':checked')){
		jQuery(".checkall-pto-task").prop("checked", true);
	}
	else{
		jQuery(".checkall-pto-task").prop("checked", false);
	}	
});
jQuery(document).on("click", "#pto_task_button_filter_apply", function(){
	var bulk = jQuery("#pto-task-select").val();
	var post_id = jQuery("#post_ID").val();
	var task_ids = "";
	var ctab = jQuery(this).attr("ctab");
	jQuery(".checkall-pto-task").each(function() {		
		if(jQuery(this).is(':checked')){
			var chkid = jQuery(this).attr("task-id"); 
			task_ids = task_ids+chkid+",";
		}
	});	
	let msg ="";
	if( bulk == 'publish' ){
		msg = "Are you sure you want publish all this task?";
	} 	
	if( bulk == 'draft' ){
		msg = "Are you sure you want draft all this  task?";
	} 
	if( bulk == 'trash' ){
		let rd_val = "";
		jQuery( ".occurrence-options" ).each( function(){
		    if( jQuery( this ).prop( "checked" ) )  {
		        rd_val = jQuery( this ).val();
		    }
		} )
		if( rd_val == "occurrence-multipal-days" ){
			msg = "Deleting this task/slot will delete ALL tasks/slots for this recurrence. Edit Single to delete a single occurrence. You will still be able to recover this task/slot from the trash bin if needed.";
			
		}else{
			msg = "Are you sure you want to delete all this task?";
		}
		
	} 
	if(task_ids != ""){
		swal({
			text: msg,
			icon: "warning",
			buttons: true,
			dangerMode: true,
		})
	.then((willDelete) => {
		if (willDelete) {
				jQuery.ajax({
					method:"POST",
					url:pto_ajax_url.ajax_url,
					data:{
						action: 'pto_sign_ups_task_bulk_filter',
						nonce: pto_ajax_url.nonce,
						bulk: bulk,
						ctab: ctab,
						task_ids: task_ids,
						post_id: post_id		
					},
					success:function( response ){	
						jQuery("#pto_sign_up_compelling_task_section_list").html(response);				
					}
				});
		}
	});
	
	}	
});
jQuery(document).on("click", "#pto_task_button_filter_month_apply", function(){
	var month = jQuery("#pto-task-select-month").val();
	var post_id = jQuery("#post_ID").val();	
	var ctab = jQuery(this).attr("ctab");
	
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action: 'pto_sign_ups_task_bulk_filter_month',
			nonce: pto_ajax_url.nonce,
			month: month,
			ctab: ctab,			
			post_id: post_id		
		},
		success:function( response ){	
			jQuery("#pto_sign_up_compelling_task_section_list").html(response);				
		}
	});	
});
/* singup report filter */
jQuery(document).on("change", ".volunteers-sorting", function(){
	var sortval = jQuery(this).val();
	var table = jQuery('#pto-singup-userwise-sorting').DataTable();	
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action:'pto_signup_volunteers_sorting', 
			nonce: pto_ajax_url.nonce,
			sortval:sortval 
		},
		success:function( response ){	
			console.log(response);
			table.destroy();
			jQuery(".pto-signup-user-tbody").html(response);
			jQuery('#pto-singup-userwise-sorting').DataTable( {
				dom: 'Bfrtip',
				pageLength: 5,
				buttons: [
							{
							"extend": 'csv',
							"text": 'Export List',
							"className": 'btn btn-primary btn-sm ml-1 button button-primary'
							}			
				]	
			} );
		}
	});
});
jQuery(document).on("change", ".pto-all-signups", function(){
	var signup_id = jQuery(this).val();	
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action:'pto_get_tasks_of_signup', 
			nonce: pto_ajax_url.nonce,
			signup_id:signup_id 
		},
		success:function( response ){	
			console.log(response);
			jQuery(".pto-all-signup-tasks").html(response);
		}
	});
});
jQuery(document).on("click", ".pto-signup-notify-admin", function(){
	var user_id = jQuery(this).val();
	var signup_id = jQuery("#post_ID").val();
	if(jQuery(this).prop("checked")){			
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{ 
				action:'pto_signup_save_notify_admin_user', 
				nonce: pto_ajax_url.nonce,
				user_id:user_id, 
				signup_id:signup_id 
			},
			success:function( response ){	
				console.log(response);				
			}
		});
	}
	else{
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{ 
				action:'pto_signup_remove_notify_admin_user', 
				nonce: pto_ajax_url.nonce,
				user_id:user_id, 
				signup_id:signup_id 
			},
			success:function( response ){	
				console.log(response);
			}
		});
	}
});
jQuery(document).on("click",".pto-signup-report-submit",function(){
	var signup_id = jQuery("#pto-all-signups").val();	
	var task_id = jQuery("#pto-all-signup-tasks").val();
	var user_id = jQuery("#pto-all-signup-users").val();
	var from_date = jQuery("#fromdate").val();
	var to_date = jQuery("#todate").val();
	var table = jQuery('#pto-singup-filter-listing-table').DataTable();
	var table_export = jQuery('#pto-singup-filter-listing-table-export').DataTable();
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		dataType: "json",
		data:{
			action:'pto_signup_filter_data',
			nonce: pto_ajax_url.nonce, 
			signup_id: signup_id, 
			task_id: task_id, 
			user_id: user_id, 
			from_date: from_date, 
			to_date: to_date 
		},
		success:function( response ){	
			table.destroy();
			table_export.destroy();
			// setTimeout(function(){
				// jQuery("#pto-singup-filter-listing").html("");	
				jQuery("#pto-singup-filter-listing").html(response.nomaltable);	
				jQuery('#pto-singup-filter-listing-table').DataTable( {
					dom: 'Bfrtip',
					buttons: [
						{
							"extend": 'csv',
							"text": 'Export Results not',
							"className": 'btn btn-primary btn-sm ml-1 button button-primary'
						}			
					]	
				});
				// let html = "<thead><tr><td>First Name</td><td>Last Name</td><td>Email</td><td>Sign Up</td><td>Task/Slot</td><td>Checkout Date</td></tr></thead><tbody id='pto-singup-filter-listing'></tbody>";
				// jQuery( "#pto-singup-filter-listing-table" ).html( html );


				
				// jQuery("#pto-singup-filter-listing-table-export").html(response.exporttable);

				// if(response.exporttable != ""){

				if( response.exporttable != "No data found" ){
				jQuery("#pto-singup-filter-listing-table-export").html(response.exporttable);
					jQuery('#pto-singup-filter-listing-table-export').DataTable( {
						dom: 'Bfrtip',
						buttons: [
							{
								"extend": 'csv',
								"text": 'Export Results',
								"className": 'btn btn-primary btn-sm ml-1 button button-primary'
							}		
						]		
				
					} );
				}
					
					
				// }
			// },1000);		
			
		}
	});
});
/* send reminder toggle(show/hide)  */
jQuery(document).on("click","#send_reminder",function(){
	if(jQuery(this).prop("checked")){
		jQuery(".signup-reminder-show").show();
	}
	else{
		jQuery(".signup-reminder-show").hide();
	}
});
/* send reciept after signup toggle(show/hide)  */
jQuery(document).on("click","#volunteer-after-sign-up",function(){
	if(jQuery(this).prop("checked")){
		jQuery(".receipts-after-signup-show").show();
		
	}
	else{
		jQuery(".receipts-after-signup-show").hide();
	}
});
/* agree to terms toggle(show/hide)  */
jQuery(document).on("click","#agree_to_terms_sign_up",function(){
	if(jQuery(this).prop("checked")){
		jQuery(".agree-to-terms-show").show();
	}
	else{
		jQuery(".agree-to-terms-show").hide();
	}
});
/* checkout fileds toggle(show/hide)  */
jQuery(document).on("click",".checkout_fields_sign_up",function(){
	if(jQuery(this).prop("checked")){
		jQuery(".signup-checkout-add-new").show();
		jQuery(".custom-fields-show").show();
	}
	else{
		jQuery(".signup-checkout-add-new").hide();
		jQuery(".custom-fields-show").hide();
	}
});
/* accept request */
jQuery(document).on("click",".accepts-req-signup",function(){		
	let signup_id = jQuery(this).attr("data-id");
	let user_id = jQuery(this).attr("data-userid");	
	let this_dec = jQuery(this);
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_accept_request',
			nonce: pto_ajax_url.nonce, 
			user_id:user_id, 
			signup_id:signup_id 
		},
		success:function( response ){
			swal({
				text: "Notification sent to user.",
				icon: "success",
				button: "Ok",
			});
			this_dec.parent("td").parent("tr").remove();		
		}
	});
});
/* decline request */
jQuery(document).on("click",".decline-req-signup",function(){		
	let signup_id = jQuery(this).attr("data-id");
	let user_id = jQuery(this).attr("data-userid");	
	let this_dec = jQuery(this);
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{ 
			action:'pto_sing_up_decline_request', 
			nonce: pto_ajax_url.nonce,
			user_id:user_id, 
			signup_id:signup_id 
		},
		success:function( response ){
			swal({
				text: "Notification sent to user.",
				icon: "success",
				button: "Ok",
			});
			this_dec.parent("td").parent("tr").remove();				
		}
	});
});
/*date range validations */
jQuery(document).on("change","#opendate",function(){
    let mindate = jQuery(this).val();
    jQuery("#closedate").attr("min", mindate);
})
jQuery(document).on("change","#closedate",function(){
    let maxdate = jQuery(this).val();
    jQuery("#opendate").attr("max", maxdate);
})
if(jQuery("#postalcode").length != 0){
	setInputFilter(document.getElementById("postalcode"), function(value) {
		return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
	});
}
function setInputFilter(textbox, inputFilter) {
	["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
		textbox.addEventListener(event, function() {
			if (inputFilter(this.value)) {
			this.oldValue = this.value;
			this.oldSelectionStart = this.selectionStart;
			this.oldSelectionEnd = this.selectionEnd;
			} else if (this.hasOwnProperty("oldValue")) {
			this.value = this.oldValue;
			this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
			} else {
			this.value = "";
			}
		});
	});
}
/* occurence not on specific day */
jQuery(document).on("change",".occurrence-options",function(){
	let occurrence_value = jQuery(this).val();
	let post_id = jQuery("#post_ID").val();
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action:'pto_occurrence_not_specific', 
			nonce: pto_ajax_url.nonce,
			occurrence_value:occurrence_value, 
			post_id:post_id
		},
		success:function( response ){	
			console.log(response);				
		}
	});
})
/*publish option hide show */
jQuery(document).on("click",".publish_checked",function(){
    let publish_check = jQuery(this).val();
    if(publish_check == "specifc_publish"){
        jQuery(".specific-timezone-pto").show();
    }else{
        jQuery(".specific-timezone-pto").hide();
    }
});
/* occurence days set */
jQuery(document).on("click",".occurrence-options",function(){
	let occurrence_value = jQuery(this).val();
	if(occurrence_value == "occurrence-specific"){
		jQuery(".pto-signup-specific-days").show();
	}else{
		jQuery(".pto-signup-specific-days").hide();
	}
})
/* reecurring modal open */
jQuery(document).on("click",".open-recurrence-popup",function(){
  	let rdio_check = "";
    jQuery(".occurrence-options").each(function(){
		if(jQuery(this).prop("checked"))
		{
			rdio_check = jQuery(this).val();
		}
    })
	if(rdio_check != "occurrence-multipal-days")
	{
		swal({
			text: "Set Recurrence option must be selected.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
  	jQuery("#task-recurrence").addClass("pto-modal-open");
})
/* advanced option hide show */
jQuery(document).on("click",".advanced_option",function(){
	let advance_option = jQuery(this).val();
	if(advance_option == "single")
	{
		jQuery("#advanced-option-radio-single").show();
		jQuery("#advanced-option-radio-shift").hide();
	}else if(advance_option == "shift")
	{
		jQuery("#advanced-option-radio-single").hide();
		jQuery("#advanced-option-radio-shift").show();
	}
})
/* custom filed optionfiled hide show */
jQuery("#pto_field_type").change(function(){
	jQuery("#multiple_append_field").hide();
	jQuery("#multiples_button_add").hide();
	jQuery("#multiple_append_field").html("");
	let pto_filed_value = jQuery(this).val();
	if(pto_filed_value == "checkbox" || pto_filed_value == "radio" || pto_filed_value == "drop-down"){
		let html = '<div class="pto_multipalfiled"><input type="text" required name="custom-filed-value[]" placeholder="Enter value"></div>';
		jQuery("#multiple_append_field").show();
		jQuery("#multiples_button_add").show();
		jQuery("#multiple_append_field").append(html);
		jQuery("#selected_value_field").val(pto_filed_value);
	}else{
		jQuery("#multiple_append_field").hide();
		jQuery("#multiples_button_add").hide();
		jQuery("#multiple_append_field").html("");
		jQuery("#selected_value_field").val("");
	}
})
/* add new key value text from custom filed cpt */
function custom_filed_add_in_field(){
	let html = '<div class="pto_multipalfiled"><input type="text" required name="custom-filed-value[]" placeholder="Enter value"><input type="button" name="remove_filed" class="button button-danger" value="remove" onclick="jQuery(this).parent().remove();remove_last_one();"></div>';	
	jQuery(".post-type-pto-custom-fields #multiple_append_field").append(html);
	let i = 0;
	jQuery(".pto_multipalfiled").each(function(){
		i++;
	})
	if(i > 1){
		jQuery("#remove_filed").show();
	}else{
		jQuery("#remove_filed").hide();
	}
}
/* remove last key value from custom filed */
function remove_last_one(){
    let i = 0;
	jQuery(".pto_multipalfiled").each(function(){
		i++;
	})
	if(i > 1){
		jQuery("#remove_filed").show();
	}else{
		jQuery("#remove_filed").hide();
	}
}
/* add task custom filed */ 
jQuery(document).on("click","#single_task_custom_filed_delete",function(){    
    swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this field!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let cpt_ids = jQuery(this).attr("cpt-ids");
			let post_id = jQuery("#post_ID").val();
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_task_custom_fields_delete',
					nonce: pto_ajax_url.nonce,
					cpt_ids:cpt_ids,
					post_id:post_id
				},
				success:function( response ){
					jQuery("#pto_sign_ups_custom_fileds_html").html(response);
				}
			});  
		} 
	});
});
jQuery(document).on("click","#single-custom_filed_delete",function(){    
    swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this field!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let cpt_ids = jQuery(this).attr("cpt-ids");
			let post_id = jQuery("#post_ID").val();
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_get_custom_fields_delete',
					nonce: pto_ajax_url.nonce,
					cpt_ids:cpt_ids,
					post_id:post_id
				},
				success:function( response ){
					jQuery("#pto-sign-up-compelling-visibility-section-details").html(response);
				}
			});  
		} 
	});
});
jQuery(document).on("click","#single-task-cpt-delete",function(){    
	swal({
		title: "Are you sure?",
		text: " You wll still be able to recover this task/slot from the trash bin if needed. ",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let cpt_ids = jQuery(this).attr("cpt-ids");
			let post_id = jQuery("#post_ID").val();			
			var option = jQuery(this).attr("data");	
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_get_task_slots_cpt_delete',
					nonce: pto_ajax_url.nonce,
					cpt_ids:cpt_ids,
					post_id:post_id,
					option: option
				},
				success:function( response ){
					jQuery("#pto_sign_up_compelling_task_section_list").html(response);
				}
			});  
		} 
	});
});
jQuery(document).on("click",".task-slot-pt-filter",function(){ 
	jQuery(".task-slot-pt-filter").removeClass("active-filter");
	jQuery(this).addClass("active-filter");
	var option = jQuery(this).attr("data");	
	var post_id = jQuery("#post_ID").val();	
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action:'pto_signup_show_trash_task_slots',
			nonce: pto_ajax_url.nonce,
			post_id: post_id,
			option: option 				
		},
		success:function( response ){
			jQuery("#pto_sign_up_compelling_task_section_list").html(response);
		}
	});
	return false;
});
/* remove task category */
jQuery(document).on("click",".del-cat",function(){ 
	swal({
		text: "Are you sure you want to delete this category?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {			
			var cat_id = jQuery(this).attr("data-id");
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_remove_task_category',
					nonce: pto_ajax_url.nonce,
					cat_id: cat_id
				},
				success:function( response ){
					window.location.reload(true);
				}
			});
		} 
	});		
});
jQuery(document).on("click","#single-task-cpt-delete-permanent",function(){ 
	swal({
		title: "Are you sure?",
		text: "Once Deleted, you will not able to restore it!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			var option = jQuery(this).attr("data");	
			var cpt_ids = jQuery(this).attr("cpt-ids");
			var post_id = jQuery("#post_ID").val();	
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_signup_delete_permanent_task_slots',
					nonce: pto_ajax_url.nonce,
					post_id:post_id,
					cpt_ids: cpt_ids,
					option: option 				
				},
				success:function( response ){
					jQuery("#pto_sign_up_compelling_task_section_list").html(response);
				}
			});
		} 
	});	
});
jQuery(document).on("click","#single-task-cpt-restore",function(){
	var option = jQuery(this).attr("data");	
	var cpt_ids = jQuery(this).attr("cpt-ids");
	var post_id = jQuery("#post_ID").val();	
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{
			action:'pto_signup_restore_task_slots',
			nonce: pto_ajax_url.nonce,
			post_id:post_id,
			cpt_ids: cpt_ids,
			option: option 				
		},
		success:function( response ){
			jQuery("#pto_sign_up_compelling_task_section_list").html(response);
		}
	});
});	
jQuery(document).on("click", ".choose-dates", function(){
	if(jQuery(".cust-dropdown").hasClass("show-dropdown")){
		jQuery(".cust-dropdown").removeClass("show-dropdown");
	}
	else{
		jQuery(".cust-dropdown").addClass("show-dropdown");
	}
});
jQuery(document).on("click",".add_new_check",function(){
	if(jQuery("#pto_sign_ups_custom_fileds").prop("checked")){    
	}else
	{
		swal({
			text : "Please select the options first.",
			button: "OK",
		})
		return false;
	}
})
jQuery(document).on("click","#pto_sign_ups_custom_fileds",function(){
	if(jQuery(this).prop("checked")){
		jQuery("#pto_sign_ups_custom_fileds_html").show();
		jQuery(".add_new_check").show();
	}else{
		jQuery("#pto_sign_ups_custom_fileds_html").hide();
		jQuery(".add_new_check").hide();
	}
});
jQuery(document).on("click",".pto_manage_user_signup",function(){
	var uid = jQuery(this).attr("id");
	jQuery(".select_users").val(uid);
	var ahref = jQuery(".add-new-volunteer").attr("href");
	if(ahref.includes("?")){		
		var arrayhref = ahref.split("?");
		ahref = arrayhref[0];
	}
	var addhref = ahref + "?uid=" + uid;
	jQuery(".add-new-volunteer").attr("href", addhref);
})
/* user search for manage volunteer */
jQuery("#search-user-for-manage").donetyping(function(){
	if(jQuery(this).val().length > 1){
		let user_name = jQuery(this).val();
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{
				action:'pto_signup_get_manage_user',
				nonce: pto_ajax_url.nonce,
				user_name:user_name
			},
			success:function( response ){
				jQuery(".search_user_for_manage").html(response);
			}
		});  
	}
});
/* user search for single sigun up */
jQuery("#search-user-for-admin-signup").donetyping(function(){
	if(jQuery(this).val().length > 1){
		let user_name = jQuery(this).val();
		let post_id = jQuery("#post_ID").val();
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{
				action:'pto_signup_get_assign_user',
				nonce: pto_ajax_url.nonce,
				user_name:user_name,
				post_id:post_id
			},
			success:function( response ){
				jQuery(".search_user_pto_sign_up").html(response);
			}
		});  
	}
});
/* selected Users */
jQuery(document).on("change",".pto_admin_user",function(){
    let id = jQuery(this).attr("id");
    let name = jQuery(this).attr("name");
    if(jQuery(this).prop("checked")){
        jQuery("#selected_users_admin").append("<div class='seleted_user' id='selected_"+ id +"'><span>"+name+"</span><i class='fa fa-times' aria-hidden='true' onclick='remove_user(\"selected_"+ id +"\");'></i><input type='hidden' name='user_selected' class='ajax_user_pass_id' value='"+ id +"'></div>");
    }else{
        jQuery("#selected_"+id ).remove();      
    }
});
/* singup in single user add */
function remove_user(user_id){
	let explod_id = user_id.split("_");
	let ids = "checked_" + explod_id[1];
	jQuery("."+ids).prop("checked",false);
	jQuery("#"+user_id).remove();
}
/* own signup settings for users */
jQuery(document).on("click",".pto-own-signup-option",function(){
	var checkedval = jQuery(this).val();
	if(checkedval == "all_users"){
		jQuery(".pto-own-setting-toggle").hide("slow");		
	}
	else{
		jQuery(".pto-own-setting-toggle").show("slow");
	}
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{ 
			action:"pto_own_signup_settings_for_users", 
			nonce: pto_ajax_url.nonce,
			checkedval: checkedval 
		},
		success:function( resu ){
			console.log(resu);
			jQuery(".pto-admin-setting-user-table-own tbody").html(resu);			
		}
	});
});
/* add new user ajax call */
/* project setting tab in add user */
jQuery(document).on("click",".add_new_users_signup",function(){
	let ids = "";
	jQuery(".ajax_user_pass_id").each(function(){
		ids += jQuery(this).val() + ","    ;
	})
	let post_id = jQuery("#post_ID").val();
	let newStr = ids.substring(0, ids.length - 1);
	if(newStr != "")
	{
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_ups_new_users_add_get",
				nonce: pto_ajax_url.nonce,
				ids:newStr,
				post_id:post_id
			},
			success:function( resu ){
				jQuery("#assign_users_pto_sign_upas").html(resu);
				jQuery('#add-administarter').removeClass('pto-modal-open');
			}
		});
	}
	else{
		swal({
			text: "Please add correct data.",
			icon: "warning",
			button: "Ok",
		});
	}
});
/* single user remove in single sing on */
jQuery(document).on("click",".remove_siggle_user_cpt_sign_ups",function(){
	swal({	
		text: "Are you sure you want to delete this user from this sign up?",
		icon: "warning",
		
		buttons: {
			cancel: true,
			confirm: "Ok",
			roll: {
			  text: "Ok With Email",
			  value: "email",
			},
		},
		dangerMode: true,	
	})
	.then((willDelete) => {
		
		if (willDelete == true) {
			
			let user_ids = jQuery(this).attr("id");
			let post_id = jQuery(this).attr("post-id");
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_sign_ups_new_users_add_remove',
					nonce: pto_ajax_url.nonce,
					user_ids:user_ids,
					post_id:post_id
				},
				success:function( response ){
					jQuery("#assign_users_pto_sign_upas").html(response);
				}
			});
		}
		if (willDelete == "email") {
			
			let user_ids = jQuery(this).attr("id");
			let post_id = jQuery(this).attr("post-id");
			var email_sent = "yes";
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_sign_ups_new_users_add_remove',
					nonce: pto_ajax_url.nonce,
					user_ids:user_ids,
					post_id:post_id,
					email_sent: email_sent
				},
				success:function( response ){
					jQuery("#assign_users_pto_sign_upas").html(response);
				}
			});
		}
	});
});
/* signup setting admin search */
jQuery("#plugin_admin_search").donetyping(function(){
	let search_user = jQuery(this).val();
	if(search_user != ""){
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{
				action:'pto_sign_up_admin_user_search',
				nonce: pto_ajax_url.nonce,
				search_user:search_user,
				user_type:2
			},
			success:function( response ){
				jQuery("#search_users_admin").html(response);
			}
		});
	}
});
jQuery("#plugin_own_sign_search").donetyping(function(){
	let search_user = jQuery(this).val();
	if(search_user != ""){
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{
				action:'pto_sign_up_admin_user_search', 
				nonce: pto_ajax_url.nonce,
				search_user:search_user, 
				user_type:1 
			},
			success:function( response ){
				console.log(response);
				jQuery("#search_users__own_sign").html(response);
			}
		});  
	}	
});
/*selected user add */
jQuery(document).on("change",".pto_admin_user_signup",function(){
    let id = jQuery(this).attr("id");
    let name = jQuery(this).attr("name");
    if(jQuery(this).prop("checked")){
        jQuery("#selected_user_sign_ups").append("<div class='seleted_user' id='selected_"+ id +"'><span>"+name+"</span><i class='fa fa-times' aria-hidden='true' onclick='remove_user(\"selected_"+ id +"\");'></i><input type='hidden' name='user_selected' class='ajax_user_pass_id' value='"+ id +"'></div>");
    }else{
        jQuery("#selected_"+id ).remove();      
    }
})
jQuery(document).on("change",".pto_sign_up_user",function(){
    let id = jQuery(this).attr("id");
    let name = jQuery(this).attr("name");
    if(jQuery(this).prop("checked")){
        jQuery("#selected_users_own_sign").append("<div class='seleted_user' id='selected_"+ id +"'><span>"+name+"</span><i class='fa fa-times' aria-hidden='true' onclick='remove_user(\"selected_"+ id +"\");'></i><input type='hidden' name='user_selected' class='ajax_user_pass_id' value='"+ id +"'></div>");
    }else{
        jQuery("#selected_"+id ).remove();      
    }
})
/* add role sign ups setting */
jQuery(document).on("click","#pto-plugin-admin-role-add",function(){
	let ids = "";
	jQuery("#selected_users_admin .ajax_user_pass_id").each(function(){
		ids += jQuery(this).val() + ","    ;
	})
	let user_type = 2;
	var newStr = ids.substring(0, ids.length - 1);
	console.log(newStr);
	if(newStr != "")
	{
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_ups_new_users_add",
				nonce: pto_ajax_url.nonce,
				ids:newStr,
				user_type:user_type
			},
			success:function( resu ){
				jQuery('#pto-sign-admin-add').removeClass('pto-modal-open');
				jQuery("#selected_users_admin").html("");
				jQuery("#search_users_admin").html("");
				jQuery("#plugin_admin_search").val("");
				jQuery("table.pto-admin-setting-user-table-project tbody").html(resu);
			}
		});
	}
	else{
		swal({				
			text: "Please enter the correct user.",
			icon: "warning",
			button: "Ok",
		});
	}
})
jQuery(document).on("click","#pto-plugin-own-role-add",function(){
	let ids = "";
	jQuery("#selected_users_own_sign .ajax_user_pass_id").each(function(){
		ids += jQuery(this).val() + ","    ;
	})
	let user_type = 1;
	var newStr = ids.substring(0, ids.length - 1);
	if(newStr != "")
	{
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_ups_new_users_add",
				nonce: pto_ajax_url.nonce,
				ids:newStr,
				user_type:user_type
			},
			success:function( resu ){
				jQuery('#pto-sign-own-add').removeClass('pto-modal-open');
				jQuery("#selected_users_own_sign").html("");
				jQuery("#search_users__own_sign").html("");
				jQuery("#plugin_own_sign_search").val("");
				jQuery(".pto-admin-setting-user-table-own tbody").html(resu);
			}
		});
	}
	else{
		swal({				
			text: "Please enter the correct user.",
			icon: "warning",
			button: "Ok",
		});
	}
});
/* remove Roll */
jQuery(document).on("click",".delete_user_signup",function(){
	swal({
		title: "Are you sure?",
		text: "Once deleted, you will not be able to recover this user!",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) 
		{
			let user_id = jQuery(this).attr("id");
			let type_id = jQuery(this).attr("attr-type");
			jQuery.ajax({
				method:"POST",
				url:pto_ajax_url.ajax_url,
				data:{
					action:'pto_sign_ups_new_users_remove',
					nonce: pto_ajax_url.nonce,
					user_id:user_id,
					type_id:type_id
				},
				success:function( response ){
					if(type_id == 2)
					{
						jQuery("table.pto-admin-setting-user-table-project tbody").html(response);
					}else{
						jQuery(".pto-admin-setting-user-table-own tbody").html(response);
					}
				}
			});  
		} 
	});
})
/* plugin settigns  submits */
jQuery(document).on("click","#plugin_setting_submit",function(){
  	document.plugin_setting_sign_ups.submit();
});
jQuery(document).on("click",".pto-clear-all-color-btn",function(){	
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"pto_signup_set_default_color",
			nonce: pto_ajax_url.nonce
		},
		success:function( resu ){
			swal({				
				text: "Color settings sets to default!",
				icon: "success",
				button: "Ok",
			});
			jQuery("#pto-background-color").val("#0078FD");
			jQuery("#pto-text-color").val("#ffffff");
			jQuery("#pto-header-background").val("#0078FD");
			jQuery("#pto-header-text-color").val("#ffffff");
			jQuery("#pto-task-header-background-color").val("#0078FD");
			jQuery("#pto-task-header-text-color").val("#ffffff");
		}
	});
	return false;
});
jQuery(document).on("click",".pto-clear-all-widget-btn",function(){	
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"pto_signup_set_default_widget_setting",
			nonce: pto_ajax_url.nonce
		},
		success:function( resu ){
			swal({				
				text: "Widget settings sets to default!",
				icon: "success",
				button: "Ok",
			});
			jQuery("#title-text-color").val("#0078FD");
			jQuery("#title-text-size").val("18");
			jQuery("#signup-title").val("");
			jQuery("#no-date-sign-ups").prop("checked", false);
			jQuery("#repeating-sign-ups").prop("checked", false);
			jQuery("#sortby-sing-ups").val("sort_by_name");
			jQuery("#sort-type").val("sort_ASC");
		}
	});
	return false;
});
/* resend email from setting */
jQuery(document).on("click",".resend-invitation",function(){
	let user_id = jQuery(this).attr("user-id");
	let user_type = jQuery(this).attr("type");
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"user_mail_resend_functionality",
			nonce: pto_ajax_url.nonce,
			user_id:user_id,
			user_type:user_type
		},
		success:function( resu ){			
			swal({				
				text: "Email successfully sent!",
				icon: "success",
				button: "Ok",
			});
		}
	});
});
/* manage volunteers */
jQuery(document).on("click",".open-manage-volunteers",function(){
  	jQuery("#manage-volunteers").addClass("pto-modal-open");
});
jQuery(document).on("change", ".check-all-managev", function(){
	if(jQuery(this).is(':checked')){
		jQuery(".managev-checkbox").prop("checked", true);
	}
	else{
		jQuery(".managev-checkbox").prop("checked", false);
	}
});
jQuery(document).on("change", ".pto-managev-bulk-action", function(){
	var bulkval = jQuery(this).val();
	var orderids = "";
	var userids = "";
	var taskids = "";
	var cnumbs = "";
	var shiftt = "";
	if(bulkval == "delselected"){
		jQuery(".managev-checkbox").each(function() {		
			if(jQuery(this).is(':checked')){
				orderids = orderids + jQuery(this).attr("orderid") + ",";
				userids = userids + jQuery(this).attr("userid") + ",";
				taskids = taskids + jQuery(this).attr("post-id") + ",";
				cnumbs = cnumbs + jQuery(this).attr("cnum") + ",";
				shiftt = shiftt + jQuery(this).attr("shiftt") + ",";
			}
		});
		if(orderids != "" && userids != "" && taskids != "" && cnumbs != ""){
			swal({		
				text: "Are you sure you'd like to remove these users from the sign up?",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			})
			.then((willDelete) => {
				if (willDelete) {
					jQuery.ajax({
						method:"POST",  
						url:pto_ajax_url.ajax_url,
						data:{
							action: "pto_manage_volunteer_bulk_delete",
							nonce: pto_ajax_url.nonce,
							orderids: orderids,
							userids: userids,
							taskids: taskids,
							cnumbs: cnumbs,
							shiftt: shiftt
						},
						success:function( resu ){
							console.log(resu);
							window.location.reload(true);			
						}
					}); 
				}else{
					  jQuery(".pto-managev-bulk-action").prop('selectedIndex',0);
				} 
			});	
		}
		else{
			swal({				
				text: "PLease select at least one record.",
				icon: "error",
				button: "Ok",
			});
			jQuery(".pto-managev-bulk-action").prop('selectedIndex',0);
		}
	}
});
jQuery(document).on("click", ".selectit", function(){
	jQuery(".selectit").find('input[type="checkbox"]').prop("checked", false);
	jQuery(this).find('input[type="checkbox"]').prop("checked", true);
});
jQuery(document).on("change", ".task-shift", function(){
	if(jQuery(this).is(':checked')){
		var checkval = jQuery(this).val();
		var taskid = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".sign-up-task").val();
		
		if(checkval == 0){
			jQuery(this).parent("li").parent("ul").find(".task-shift").prop("checked", false);
			jQuery(this).prop("checked", true);
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".task-shift-time").val("");			
		}
		else{
			var hidn_val = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".task-shift-time").val();
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
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".task-shift-time").val(hidn_val);			
		}
	}
	else{
		var checkval = jQuery(this).val();
		var hidn_val = jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".task-shift-time").val();
			if(hidn_val == ""){				
			}
			else{				
				if(hidn_val.includes(checkval)){					
					hidn_val = hidn_val.replace(checkval, '');
					if(hidn_val == ""){						
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
			jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".task-shift-time").val(hidn_val);
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
		
	}
	jQuery(this).parent("li").parent("ul").parent(".shift-checkbox-list").parent(".sign-up-task-shift-block").find(".pto-singup-task-max-number-select option").val(count);
});
jQuery(document).on("click",".view_manage_user_volunters",function(){
	var orderid = jQuery(this).attr("orderid");
	var taskid = jQuery(this).attr("post-id");
	var cnum = jQuery(this).attr("cnum");
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action: "pto_manage_volunteer_view_receipt",
			nonce: pto_ajax_url.nonce,
			orderid: orderid,
			taskid: taskid,
			cnum: cnum
		},
		success:function( resu ){
			jQuery(".pto-managev-popup-content").html(resu);			
		}
	});
	jQuery("#view-receipt-manage-volunteers").addClass("pto-modal-open");
});
jQuery(document).on("click", ".pto-signup-resend-receipt", function(){	
	var id = jQuery(this).data("id");
	jQuery.ajax({
		method:"POST",
		url:pto_ajax_url.ajax_url,
		data:{ 
			action:'pto_my_singup_resend_receipt',
			nonce: pto_ajax_url.nonce, 
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
					text: "Email send successfully.",
					icon: "success",
					button: "Ok",
				});		
			}			
		}
	});
});
/* task search for manage volunteers */
jQuery("#task-search").donetyping(function(){
	let post_id = jQuery("#post_id").val();
	let key_search = jQuery(this).val();
	let user_id = jQuery(".select_users").val();
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"pto_task_search_manage",
			nonce: pto_ajax_url.nonce,
			post_id:post_id,
			key_search:key_search,
			user_id:user_id
		},
		success:function( resu ){
			jQuery(".search-task-pto").html(resu);
			if(jQuery(".seleted_task").length){
				jQuery(".seleted_task").each(function(){
					let ids = jQuery(this).attr("id");
					let explod_id  = ids.split("_");
					if(jQuery("#"+explod_id[1]).length)
					{
					jQuery("#"+explod_id[1]).addClass("alredy_assign"); 
					}
				})
			}
		}
	});
});
/* select task add */
jQuery(document).on("click",".search-task-pto .task_name",function(){
	var post_id = jQuery("#post_id").val();
	var task_id= jQuery(this).attr("id");
	var userid = jQuery(".select_users").val();
	if(jQuery(".task_setting_"+ task_id).length != 0) {
		jQuery(".task_setting_"+ task_id).remove();
	}else{
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_task_get_data",
				nonce: pto_ajax_url.nonce,
				post_id:post_id,
				task_id:task_id,
				userid:userid
			},
			success:function( resu ){	
				if(resu == ""){
					swal({
						text: "This task availability has been filled.",
						icon: "error",
						button: "Ok",
					});
				}		
				jQuery(".selected-task-pto").append(resu);			
			}
		});
	}  
});
jQuery(document).on("change",".select_users",function(){
	jQuery(".selected-task-pto").html("");
	var uid = jQuery(this).val();
	var ahref = jQuery(".add-new-volunteer").attr("href");
	if(ahref.includes("?")){
		var arrayhref = ahref.split("?");
		ahref = arrayhref[0];
	}
	var addhref = ahref + "?uid=" + uid;
	jQuery(".add-new-volunteer").attr("href", addhref);
});
jQuery(document).on("click",".add-new-volunteer",function(){
	var select_users = jQuery(".select_users").val();
	if(select_users == ""){
		swal({
			text: "Please select a user.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
	else{
		jQuery("#manage-volunteers").removeClass("pto-modal-open");
	}	
});
jQuery(document).on("click",".add-new-volunteer-user",function(){
	var fname = jQuery("#pto_signup_user_fname").val();
	var lname = jQuery("#pto_signup_user_lname").val();
	var uemail = jQuery("#pto_signup_user_email").val();
	if(fname == "" || lname == "" || uemail == ""){
		swal({
			text: "Please fill in all the fields.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
	else{
		jQuery.ajax({
			method:"POST",
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sing_up_add_new_user",
				nonce: pto_ajax_url.nonce,
				uemail: uemail,
				fname: fname,
				lname: lname
			},
			success:function( resu ){
				console.log(resu);
				if(resu == "Success"){
					jQuery("#manage-volunteers-add-new-user").removeClass("pto-modal-open");
					swal({
						text: "User added successfully.",				
						icon: "success",				
						buttons: {
							cancel: false,
							confirm: "Ok",							
						},				
					})				
					.then((willDelete) => {
				
						if (willDelete) {	
							jQuery(".open-manage-volunteers").trigger("click");
						} 
				
					});					
				}
				if(resu == "Invalid"){
					swal({
						text: "Invalid Email ID.",
						icon: "error",
						button: "Ok",
					});
				}
				if(resu == "Exist"){
					swal({
						text: "User is already exist.",
						icon: "error",
						button: "Ok",
					});
				}
				
			}
		});
	}
	
});
jQuery(document).on("click",".add-new-user",function(){
	jQuery("#manage-volunteers").removeClass("pto-modal-open");
	jQuery("#manage-volunteers-add-new-user").addClass("pto-modal-open");
	
});
jQuery(document).on("click",".add_new_sign_up_manager",function(){
    var frm =  jQuery("#pto-signup-managev-form");
	var user_id = jQuery(".select_users").val();
	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"pto_sing_up_single_user_add",
			nonce: pto_ajax_url.nonce,
			user_id: user_id,
			keyword: frm.serialize()
		},
		success:function( resu ){
			console.log(resu);
			jQuery("#manage-volunteers").removeClass("pto-modal-open");
			window.location.reload(true);
		}
	});  
});
/* task plublish validation */
jQuery(document).on("click",".post-type-pto-signup #publish",function(){
	
	var cur_url = jQuery(".step-active a").attr("href");
	cur_url = cur_url.split("#");
	
	cur_step = cur_url[1];
	if(cur_step != undefined){
		localStorage.setItem('currentstep', cur_step);
	}
			
	let agree_to_terms = "";
	let number_of_slots = "";
	let date_check = "";
	let occurence_chk = "";
	let send_receipt = "";
	let receipts_section_number = "";
	let send_reminder = "";
	var signuptitle = "";
	signuptitle = jQuery("#title").val();
	if(signuptitle == ""){
		jQuery("#title").css("border-color", "red");
		swal({
			text: "Signup name must be filled in.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
	else{
		jQuery("#title").css("border-color", "#8c8f94");
	}
	if(jQuery("#agree_to_terms_sign_up").prop("checked")){		
		agree_to_terms = tinymce.get('agree_to_terms').getContent();
		if(agree_to_terms  == "" || agree_to_terms == undefined)
		{
			swal({
				text: "Agree to terms field must be filled in.",
				icon: "warning",
				button: "Ok",
			});
			return false;
		}	
	}
	
	if(jQuery("#volunteer-after-sign-up").prop("checked")){		
		send_receipt = tinymce.get('volunteer_after_setting').getContent();
		if(send_receipt  == "" || send_receipt == undefined)
		{
			swal({
				text: "Receipt field must be filled in.",
				icon: "warning",
				button: "Ok",
			});
			return false;
		}	
	}
	if(jQuery("#send_reminder").prop("checked")){		
		receipts_section_number = jQuery("#receipts-section-number").val();
		send_reminder = tinymce.get('volunteer_before_setting').getContent();	
		if(receipts_section_number  == "" || receipts_section_number == undefined || send_reminder  == "" || send_reminder == undefined)
		{
			if(receipts_section_number == "" || receipts_section_number == undefined){
				jQuery("#receipts-section-number").css("border-color", "red");
			}
			else{
				jQuery("#receipts-section-number").css("border-color", "#8c8f94");
			}
			swal({
				text: "Required fields must be filled in.",
				icon: "warning",
				button: "Ok",
			});
		return false;
		}	
	}
	
	jQuery(".publish_checked").each(function(){
		if(jQuery(this).prop("checked")){
			date_check = jQuery(this).val();
		}
	});
	jQuery(".occurrence-options").each(function(){
		if(jQuery(this).prop("checked")){
			occurence_chk = jQuery(this).val();
		}
	});	
	if(jQuery("#categories-colspan-show").prop("checked")) {
		number_of_slots = jQuery("#number-of-slots").val();
		if(number_of_slots  == "" || number_of_slots == undefined)
		{
			jQuery("#number-of-slots").css("border-color", "red");
			swal({
				text: "Required fields must be filled in.",
				icon: "warning",
				button: "Ok",
			});
			return false;
		}
		else{
			jQuery("#number-of-slots").css("border-color", "#8c8f94");
		}
	}		
	if(occurence_chk == "occurrence-specific") {			
		var occurrence_specific_days = jQuery("#occurrence-specific-days").val();
		if(occurrence_specific_days == "" || occurrence_specific_days == undefined){
			swal({
				text: "The Specific day must be filled in.",
				icon: "warning",
				button: "Ok",
			});
			return false;
		}			
	}
	if(date_check == "specifc_publish")	{			 		   
		let err = 0;
		if(jQuery("#opendate").val() == "" || jQuery("#opendate").val() == undefined)
		err =1;
		if(jQuery("#opentime").val() == "" || jQuery("#opentime").val() == undefined)
		err =1;
		if(jQuery("#closedate").val() == "" || jQuery("#closedate").val() == undefined)
		err =1;
		if(jQuery("#closetime").val() == "" || jQuery("#closetime").val() == undefined)
		err =1;
		if(err == 1){
			swal({
				text: "The Specific day and time must be filled in.",
				icon: "warning",
				button: "Ok",
			});
			return false;
		}      		
    }
});
/* task plublish validation */
jQuery(document).on("click",".post-type-pto-custom-fields #publish",function(){
	var title = jQuery("#title").val();
	if(title == ""){
		swal({
			text: "Please add title.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
});
jQuery(".pto-single-task-edit").parent("div").parent(".postbox").css("display", "none");
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}
var url = window.location.href;
var param = "rdate";	
var task_id = jQuery("#post_ID").val();
var rdate = getParameterByName(param, url);
if(rdate != null){
	jQuery.ajax({
		method:"POST", 
		url:pto_ajax_url.ajax_url,
		dataType: "json",
		data:{
			action:"pto_signup_load_edit_single_value",
			nonce: pto_ajax_url.nonce,
			task_id: task_id,			
			rdate: rdate			
		},
		success:function( resu ){
			console.log(resu);
			catid = resu.post_cat;
			task_title = resu.post_title;
			jQuery("#title").val(task_title);
			jQuery(".selectit input").each(function(){
				var catval = jQuery(this).val();
				if(catval == catid){
					jQuery(".selectit input").prop("checked", false);
					jQuery(this).prop("checked", true);
				}
			});
		}
	});
}
/* task plublish validation */
jQuery(document).on("click",".post-type-tasks-signup #publish",function(){
	var url = window.location.href;
	var param = "rdate";	
	var task_id = jQuery("#post_ID").val();
	var cat = "";	
	var rdate = getParameterByName(param, url);
	jQuery(".selectit input").each(function(){
		if(jQuery(this).prop("checked")){
			cat = jQuery(this).val();
		}
	});
	var title = jQuery("#title").val();
	if(title == ""){
		swal({
			text: "Please add task name.",
			icon: "warning",
			button: "Ok",
		});
		return false;
	}
	if(rdate != null){
		
		var content = tinymce.get('tasks_comp_desc').getContent();
		jQuery.ajax({
			method:"POST", 
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_signup_rec_edit_single",
				nonce: pto_ajax_url.nonce,
				task_id: task_id,
				title: title,
				rdate: rdate,
				cat: cat,
				content: content
			},
			success:function( resu ){
				console.log(resu);
				opener.pto_task_cpt_call("pto_sign_up_compelling_task_section_list", task_id);
                window.close();
				
			}
		});
		return false;
	}
	
	let radio_value= "";
	jQuery(".advanced_option").each(function(){
		if(jQuery(this).prop("checked")){
			radio_value = jQuery(this).val();
		}
	});
	
	let err = 0;
	if(radio_value  == "single")
	{
		var vsignup = parseInt(jQuery("#how_money_volunteers_sign_ups").val());
		var totalv = parseInt(jQuery("#how_money_volunteers").val());
		if(jQuery("#how_money_volunteers").val() == "" || jQuery("#how_money_volunteers").val() == undefined)
		err = 1;
		if(jQuery("#how_money_volunteers_sign_ups").val() == "" || jQuery("#how_money_volunteers_sign_ups").val() == undefined)
		err = 1;
		if(jQuery("#how_money_volunteers_sign_ups").val() == "" || jQuery("#how_money_volunteers_sign_ups").val() == undefined)
		err = 1;
		if(vsignup > totalv){
			jQuery("#how_money_volunteers_sign_ups").css("border-color", "red");
				swal({
					text: "The number of a volunteer sign up for this task/slot should not greater than the number of volunteers are needed for this task/slot",
					icon: "warning",
					button: "Ok",
				});
				return false;
		}
		else{
			jQuery("#how_money_volunteers_sign_ups").css("border-color", "#8c8f94");
		}
	}else{
		var fshift = jQuery("#first-shift").val();
		var lshift = jQuery("#last-end-shift").val();
		var howlong = jQuery("#how-long-shift").val();
		var ft = '01-01-1970 '+fshift;
		var lt = '01-01-1970 '+lshift;
		var fd = new Date(ft);
		var ld = new Date(lt);
		var diff = Math.abs(ld - fd);		
		var minutes = Math.floor((diff/1000)/60);
		
		if(howlong > minutes){
			swal({
				text: "The number of shift minutes should not greater than shift start and shift end minutes.",
				icon: "warning",
				button: "Ok",
				});
			return false;
		}
		if(jQuery("#volunteers_shift").val() == "" || jQuery("#volunteers_shift").val() == undefined)
			err = 1;
		if(jQuery("#volunteers_shift_times").val() == "" || jQuery("#volunteers_shift_times").val() == undefined)
			err = 1;
		if(jQuery("#first-shift").val() == "" || jQuery("#first-shift").val() == undefined)
			err = 1;
		if(jQuery("#last-end-shift").val() == "" || jQuery("#last-end-shift").val() == undefined)
			err = 1;
		if(jQuery("#how-long-shift").val() == "" || jQuery("#how-long-shift").val() == undefined)
			err = 1;
		if(jQuery("#between-shift-minutes").val() == "" || jQuery("#between-shift-minutes").val() == undefined)
			err = 1;
	}
	if(err == 1){
		swal({
			text: "Required fields must be filled in.",
			icon: "warning",
			button: "Ok",
			});
		return false;
	}
});
/* post id in get user id and date time removce */ 
jQuery(document).on("click",".delete_manage_user_volunters",function(){	
	swal({
		text: "Are you sure you'd like to remove this user from the sign up?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			let main_post_id = jQuery("#post_id").val();
			let post_id = jQuery(this).attr("post-id");
			let user_id = jQuery(this).attr("userid");
			let date = jQuery(this).attr("date");
			var orderid = jQuery(this).attr("orderid");
			var cnum = jQuery(this).attr("cnum");
			jQuery.ajax({
				method:"POST",  
				url:pto_ajax_url.ajax_url,
				data:{
					action:"pto_sing_up_remove_user_id_to_post",
					nonce: pto_ajax_url.nonce,
					post_id:post_id,
					user_id:user_id,
					orderid:orderid,
					cnum:cnum,
					date:date,
					main_post_id:main_post_id
				},
				success:function( resu ){
					// console.log(resu);
					window.location.reload(true);
				}
			});  
		} 
	});
});
/* resend volunteers  emails */
jQuery(document).on("click",".resend_manage_user_volunters",function(){
  	let user_id = jQuery(this).attr("userid");
   	jQuery.ajax({
		method:"POST",  
		url:pto_ajax_url.ajax_url,
		data:{
			action:"user_mail_functionalitys",
			nonce: pto_ajax_url.nonce,
			user_id:user_id
		},
		success:function( resu ){
		}
  	});  
})
/*This sign up repeats on multiple days */
jQuery(".pto-sign-up-div-repeate-time").change(function(){
    let selected_period = jQuery(this).val();
    if(selected_period == "Weeks"){
        jQuery("#pto-recurring-week").show();
        jQuery("#pto-recurring-month").hide();
    }else if(selected_period == "Month"){
        jQuery("#pto-recurring-week").hide();
        jQuery("#pto-recurring-month").show();
    }else{
        jQuery("#pto-recurring-week").hide();
        jQuery("#pto-recurring-month").hide();
    }
})
/* occurence set */
jQuery(document).on("click",".custom-ocurrence-recureence",function(){
	let curr_val = jQuery(this).val();
	if(curr_val == "on"){
		jQuery("#endogrecurr_on_input_date").removeAttr("disabled")
		jQuery("#endogrecurr_on_input_after_days").attr("disabled","disabled")
	}else if(curr_val == "after"){
		jQuery("#endogrecurr_on_input_date").attr("disabled","disabled")
		jQuery("#endogrecurr_on_input_after_days").removeAttr("disabled")
	} else{
		jQuery("#endogrecurr_on_input_date").attr("disabled","disabled")
		jQuery("#endogrecurr_on_input_after_days").attr("disabled","disabled")	
	}
})
jQuery(document).on("click",".task-recurrence_add_new",function(){
	let start_date = jQuery("#start_input_date").val();
	let daysofevery  = jQuery("#daysofevery").val();
	let to_sign_up_div_repeate_time = jQuery("#to-sign-up-div-repeate-time").val();
	let week_days = "";
	let pto_signup_reucr_month = "";
	let end_times = "";
	let end_data = {};
	var skipped_dates = "";
	jQuery(".multiple-dates-signup").each(function(){
		if(jQuery(this).prop("checked")){
			var multiple_dates_signup = jQuery(this).val();
			skipped_dates +=  multiple_dates_signup + ",";
		}
	});
	if(daysofevery == ""){
		jQuery("#daysofevery").css("border-color", "red");
		swal({
			text: "Required field must be fill in.",
			icon: "error",
			button: "Ok",
		});
		return false;	
	}
	else{
		jQuery("#daysofevery").css("border-color", "#8c8f94");
	}
	if(start_date == ""){
		swal({
			text: "Start Date must be filled in.",
			icon: "warning",
			button: "Ok",
			});
		return false;
	}
	jQuery(".custom-ocurrence-recureence").each(function(){
		if(jQuery(this).prop("checked")){
			if(jQuery(this).val() == "on"){
				end_data["on"] = jQuery("#endogrecurr_on_input_date").val();
			}else if(jQuery(this).val() == "after"){
				end_data.after = jQuery("#endogrecurr_on_input_after_days").val();
			}else if(jQuery(this).val() == "never"){
				end_data.never ="never";
			}
		}
	})
	let post_id = jQuery("#post_ID").val();
	var myJsonString = end_data;
	if(to_sign_up_div_repeate_time == "Weeks")
	{
		jQuery("#pto-days-recurring input").each(function(){
			if(jQuery(this).prop("checked"))
			{
				week_days += jQuery(this).attr("id") + ",";
			}
		})
		if(week_days == ""){			
			swal({
				text: "At least one day must be fill in.",
				icon: "error",
				button: "Ok",
			});
			return false;	
		}
		console.log(week_days);
		week_days = week_days.substring(0, week_days.length - 1);
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_up_step_two",
				nonce: pto_ajax_url.nonce,
				post_id:post_id,
				start_date:start_date,
				skipped_dates: skipped_dates,
				daysofevery:daysofevery,
				to_sign_up_div_repeate_time:to_sign_up_div_repeate_time,
				end_data: myJsonString,
				week_days:week_days,
				
			},
			success:function( resu ){
				console.log(resu);
				jQuery('#task-recurrence').removeClass('pto-modal-open');
			}
		});
  	}else if(to_sign_up_div_repeate_time == "Month")
	{
		pto_signup_reucr_month = jQuery("#pto-signup-reucr-month").val();
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_up_step_two",
				nonce: pto_ajax_url.nonce,
				post_id:post_id,
				start_date:start_date,
				skipped_dates: skipped_dates,
				daysofevery:daysofevery,
				to_sign_up_div_repeate_time:to_sign_up_div_repeate_time,
				end_data:end_data,
				pto_signup_reucr_month:pto_signup_reucr_month
			},
			success:function( resu ){
				jQuery('#task-recurrence').removeClass('pto-modal-open');
			}
		});
  	}else{
		jQuery.ajax({
			method:"POST",  
			url:pto_ajax_url.ajax_url,
			data:{
				action:"pto_sign_up_step_two",
				nonce: pto_ajax_url.nonce,
				post_id:post_id,
				start_date:start_date,
				skipped_dates: skipped_dates,
				daysofevery:daysofevery,
				to_sign_up_div_repeate_time:to_sign_up_div_repeate_time,
				end_data:end_data
			},
			success:function( resu ){
				jQuery('#task-recurrence').removeClass('pto-modal-open');
			}
		});
  	}
})
jQuery( document ).on ( "click" , "#TaskCategories-add-submit" , function(){
	setTimeout(function(){
		jQuery( ".selectit input[ type='checkbox' ]" ).each( function(){
			jQuery(this).prop( "checked" , false );
		} )	
	}, 1000)
} )
setTimeout(function(){
    jQuery('#postimagediv').append('<p style="padding-left:10px" class="banner-design">Please upload this dimension image <b> (1920*600)</b></p>');
}, 100);
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
