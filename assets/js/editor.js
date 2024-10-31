jQuery(function(){
	tinymce.PluginManager.add("mce_editor_js",function(editor){
		// adding dropdown to tinyMCE toolbar
		if(editor.id == "agree_to_terms" || editor.id == "signupdescreption" || editor.id == "tasks_comp_desc"){			
		}
		else{
			editor.addButton("editor_dropdown",{
				text: "Insert Field",
				tooltip: "This is placeholder dropdown",
				type:"menubutton",
				menu:[
						{
							text: "{{Full Name}}",
							value: "{{Full Name}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},
						{
							text: "{{First Name}}",
							value: "{{First Name}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},  
						{
							text: "{{Last Name}}",
							value: "{{Last Name}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},								
						{
							text: "{{Admin Name}}",
							value: "{{Admin Name}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},
						{
							text: "{{Signup Name}}",
							value: "{{Signup Name}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},
						{
							text: "{{Signup Details}}",
							value: "{{Signup Details}}",				
							onclick: function(){
								editor.insertContent(this.value());
							}
						},
				]
			});
		}
	});
	function  alert_from_outside(){      
	}
});