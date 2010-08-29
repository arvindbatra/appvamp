/* clearTimeOut Keeps the Menu Open */
clearTimeOut = 0;
// Keep the Menu Open if we're highlighting a sub-menu item
function keep_open()
	{clearTimeOut = 0;}
// Begin the closing procedure with a countdown
function close_menu(id)
	{
		clearTimeOut = 1;
		temp_timeout = setTimeout("close_menu_final('"+id+"');", 250);					
	}			
// Do the final menu clearing
function close_menu_final(id)
	{
		// Check whether or not we've scrolled over a menu item
		if(clearTimeOut == 1 && (id.indexOf("sub-menu-") > -1 || id.indexOf("sub-page-menu-") > -1))
			{
				use_id = "#"+id;				
				jQuery(use_id).slideUp({duration: 250});
			}
	}
function switch_slides(current_id, next_id)
	{	
		old_post_id = current_id.replace("image-", "post-");
		new_post_id = next_id.replace("image-", "post-");
		
		jQuery(current_id).fadeOut("fast");
		jQuery(next_id).addClass("floatleft");
		jQuery(old_post_id).slideUp("slow");
		
		setTimeout(
			function()
				{
					jQuery(next_id).fadeIn("slow");
					jQuery(new_post_id).slideDown("slow").addClass("feature-post-content").addClass("clearfix");
					jQuery.busy = 0;
				}
		,100);
	}

jQuery(document).ready(function()
	{
		/***********************************************/
		/* All functions for the featured posts Widget */
		jQuery.current_selected = jQuery("#first_selected").html();
		jQuery("[id^='ocmx-featured-href-']").click(function()
			{
				use_id = jQuery(this).attr("id").replace("ocmx-featured-href-", "");
				
				old_header = "#feature-post-header-"+jQuery.current_selected;
				old_media = "#feature-post-media-"+jQuery.current_selected;
				header_id = "#feature-post-header-"+use_id;
				media_id = "#feature-post-media-"+use_id;
				
				jQuery("#feature-media-container").slideUp("slow");
				jQuery(media_id+" > object").addClass("no_display");
				
				/* Clear old header */
				jQuery(old_header).addClass("no_display");
				jQuery(header_id).removeClass("no_display");
				
				/* Hide old Media*/
				jQuery(old_media).slideUp("slow");
				jQuery(old_media+" object").addClass("no_display");
				
				setTimeout(function()
					{
						jQuery("#feature-media-container").slideDown("fast");				
						setTimeout(function()
							{	
								jQuery(media_id).slideDown("slow");		
								jQuery(media_id+" > object").removeClass("no_display");
							}
						,1000);
					}
				,150);
				jQuery.current_selected = use_id;
				return false;
			});
		jQuery.current_month = 1;
		jQuery("a[id^='archive-href-']").click(function()
			{
				use_id = jQuery(this).attr("id").replace("archive-href-", "");
				
				old_detail = "#archive-detail-"+jQuery.current_month;
				new_detail = "#archive-detail-"+use_id;
				
				/* Hide old Media*/
				jQuery(old_detail).slideUp("slow");
				jQuery(new_detail).slideDown("slow");
				
				jQuery.current_month = use_id;
				return false;
			});
		
		/********/
		/* Menu */
		jQuery.open_menu = 0;
		jQuery("a[id^='main-menu-item-']").mouseover(function(){
			// Start the timeout to keep the menu open
			keep_open()
			// Create the id to ref the submenu
			sub_menu_id = jQuery(this).attr("id").replace("main-menu-item-", "");
			id = "sub-menu-"+sub_menu_id;
			if(document.getElementById(id))
				{			
					new_sub_menu = "#"+id;
					
					if(jQuery.open_menu !== new_sub_menu)
						{jQuery(".sub-menu-container").slideUp("fast");}
				
					// fade in the submenu
					jQuery(new_sub_menu).addClass("container").slideDown({duration: 100});	
					jQuery.open_menu = new_sub_menu;
				}
		});
		
		jQuery("a[id^='main-menu-page-item-']").mouseover(function(){
			// Start the timeout to keep the menu open
			keep_open()
			// Create the id to ref the submenu
			sub_menu_id = jQuery(this).attr("id").replace("main-menu-page-item-", "");
			id = "sub-page-menu-"+sub_menu_id;
			if(document.getElementById(id))
				{			
					new_sub_menu = "#"+id;
					
					if(jQuery.open_menu !== new_sub_menu)
						{jQuery(".sub-menu-container").slideUp("fast");}
					// fade in the submenu
					jQuery(new_sub_menu).addClass("container").slideDown("2000");	
					jQuery.open_menu = new_sub_menu;
				}
		});
		
		jQuery("[id^='sub-menu-'], [id^='sub-page-menu-']").mouseover(function(){
			// Start the timeout to keep the menu open
			keep_open()														
		});
		jQuery("[id^='sub-menu-'], [id^='main-menu-item-'], [id^='sub-page-menu-'], [id^='main-menu-page-item-']").mouseout(function(){
			// Create the id to ref the submenu
			sub_menu_id = jQuery(this).attr("id");
			if(sub_menu_id.indexOf("main-menu-item-") > -1)
				{sub_menu_id = sub_menu_id.replace("main-menu-item-", "sub-menu-");}
			else if(sub_menu_id.indexOf("main-menu-page-item-") > -1)
				{sub_menu_id = sub_menu_id.replace("main-menu-page-item-", "sub-page-menu-");}
			// Start the cloding process
			close_menu(sub_menu_id);				
		});			
		/********************/
		/* Ajax Comments */
		jQuery("#commentform").submit(function(){return false;});
		
		jQuery("#comments-link").click(function(){			
			jQuery("html").animate({scrollTop: jQuery("#comments").offset().top}, 1000);
			return false;
		});
		jQuery("#comment_submit").live("click", function(){
			// Compile the request location
			post_page = jQuery("#template-directory").html()+"/functions/ocmx_comment_post.php";
			// Compile all the request details
			author = jQuery("#author").attr("value");
			email = jQuery("#email").attr("value");
			url = jQuery("#url").attr("value");
			comment = jQuery("#comment").attr("value");
			twitter = jQuery("#twitter").attr("value");
			email_subscribe = jQuery("#email_subscribe").attr("checked");
			post_id = jQuery("#comment_post_id").attr("value");
			comment_parent_id = jQuery("#comment_parent_id").attr("value");
	
			// Set which area the new comment will end up in
			if(comment_parent_id !== "0" && comment_parent_id !== "")
				{new_comments_id = "#new-reply-"+comment_parent_id;}
			else
				{new_comments_id = "#new_comments";}
			
			if(email == "" || email == "EMail Address"){alert("Please make sure you have entered a valid email address."); return false;}
			
			// Fade out the new comment div so that we can fade it in after posting our new comment
			//jQuery(new_comments_id).fadeOut("fast");
			jQuery("#commment-post-alert").fadeIn("slow");
			// Perform the "Magic" which is just a bit of Ajax
			jQuery.post(post_page, { author: author, email: email, url: url, twitter: twitter, email_subscribe: email_subscribe, comment: comment, comment_post_id: post_id, comment_parent: comment_parent_id}, 
				function(data) {
					if(jQuery.browser.msie)
						{location.reload();}
					else
						{jQuery(new_comments_id).html(jQuery(new_comments_id).html()+" "+data).fadeIn("slow");}
					jQuery("#commment-post-alert").fadeOut("fast");
					jQuery("#comment").attr("value", "");
			});
			return false;
		});
		
		jQuery("a[id^='reply-']").live("click", function(){
			// Create the Comment Id and apply it to the comment form
			comment_id = jQuery(this).attr("id").replace("reply-", "");
			
			// Set which href we're dealing with

			if(jQuery.href_id)
				{
					oldhref = jQuery.href_id;
					jQuery(oldhref).html("Reply");
				}
			jQuery.href_id = "#reply-"+comment_id;
			
			//Set where exactly the comment form will end up
			new_location_id = "#form-placement-"+comment_id;
			
			//Create the Id for the new placement of the comment Form and put it there
			if(jQuery(new_location_id).html().toString().indexOf("Leave") == -1)
				{
					jQuery("#comment_form_container").remove().appendTo(new_location_id);
					jQuery(new_location_id).fadeIn("slow");
					jQuery("#comment_parent_id").attr("value", comment_id);
					// Change href to Cancel
					jQuery(jQuery.href_id).html("Cancel");
				}
			else
				{
					jQuery(new_location_id).fadeOut("fast");
					jQuery("#comment_form_container").remove().appendTo("#original_comment_location");
					jQuery("#comment_parent_id").attr("value", "0");
					// Change href back to Reply
					jQuery(jQuery.href_id).html("Reply");
				}
			setTimeout(function(){jQuery("html").animate({scrollTop: jQuery(".comment-form-content").offset().top}, 1000);}, 500);
			return false;
		});
		jQuery("#contact_form").submit(function(){
			err = "";	
			var theForm = document.getElementById("contact_form");
			var e_value = jQuery("#contact_email").attr("value");
			
			if (jQuery("#contact_name").attr("value") == "" || jQuery("#contact_name").attr("value") == "Name")
				{err = err + "\n - Enter your name.";}
			if(e_value !== "Email Address" && e_value !== "" && e_value.indexOf("@") !== -1 && e_value.indexOf("@.") == -1 && e_value.indexOf("@@") == -1 && ( e_value.indexOf(",") == -1  && e_value.indexOf("/") == -1 && e_value.indexOf("'") == -1 && e_value.indexOf("&") == -1 && e_value.indexOf("%") == -1 ))
				{}
			else
				{err = err + "\n - Enter a valid e-mail address.";}		
			if (jQuery("#contact_subject").attr("value") == "" || jQuery("#contact_subject").attr("value") == "Subject")
				{err = err + "\n - Enter a subject title for your message.";}
			if (jQuery("#contact_message").attr("value") == "" || jQuery("#contact_message").attr("value") == "Your Message")
				{err = err + "\n - Enter a message.";}
	
			if(err !== "")
				{
					err = "Please correct the following: \n" + err;
					alert(err);
					return false
				}			
			else
				{return true;}		  	
		});
		/**********************/
		/* Search Form Clearer */
		search_criteria_id = "search_criteria";
		jQuery("#"+search_criteria_id).focus(function(){
			if(jQuery("#"+search_criteria_id).attr("value") == "Search...")
				{jQuery("#"+search_criteria_id).attr("value", "");}
		});
		
		jQuery("#"+search_criteria_id).blur(function(){
			if(jQuery("#"+search_criteria_id).attr("value") == "")
				{jQuery("#"+search_criteria_id).attr("value", "Search...");}
		});
		
		/************************/
		/* Contact Form Clearer */
		contact_name_id = "contact_name";
		jQuery("#"+contact_name_id).focus(function(){
			if(jQuery("#"+contact_name_id).attr("value") == "Name")
				{jQuery("#"+contact_name_id).attr("value", "");}
		});
		
		jQuery("#"+contact_name_id).blur(function(){
			if(jQuery("#"+contact_name_id).attr("value") == "")
				{jQuery("#"+contact_name_id).attr("value", "Name");}
		});
					
		contact_email_id = "contact_email";
		jQuery("#"+contact_email_id).focus(function(){
			if(jQuery("#"+contact_email_id).attr("value") == "Email Address")
				{jQuery("#"+contact_email_id).attr("value", "");}
		});
		
		jQuery("#"+contact_email_id).blur(function(){
			if(jQuery("#"+contact_email_id).attr("value") == "")
				{jQuery("#"+contact_email_id).attr("value", "Email Address");}
		});
			
		contact_subject_id = "contact_subject";
		jQuery("#"+contact_subject_id).focus(function(){
			if(jQuery("#"+contact_subject_id).attr("value") == "Subject")
				{jQuery("#"+contact_subject_id).attr("value", "");}
		});
		
		jQuery("#"+contact_subject_id).blur(function(){
			if(jQuery("#"+contact_subject_id).attr("value") == "")
				{jQuery("#"+contact_subject_id).attr("value", "Subject");}
		});
		contact_message_id = "contact_message";
		jQuery("#"+contact_message_id).focus(function(){
			if(jQuery("#"+contact_message_id).attr("value") == "Your Message")
				{jQuery("#"+contact_message_id).attr("value", "");}
		});
		
		jQuery("#"+contact_message_id).blur(function(){
			if(jQuery("#"+contact_message_id).attr("value") == "")
				{jQuery("#"+contact_message_id).attr("value", "Your Message");}
		});
		
		/*************************/
		/* Comments Form Clearer */
		search_id = "s";	
		jQuery("#"+search_id).focus(function(){
			if(jQuery("#"+search_id).attr("value") == "Search...")
				{jQuery("#"+search_id).attr("value", "");}
		});
		
		jQuery("#"+search_id).blur(function(){
			if(jQuery("#"+search_id).attr("value") == "")
				{jQuery("#"+search_id).attr("value", "Search...");}
		});
		
		/*************************/
		/* Comments Form Clearer */
		author_id = "author";	
		jQuery("#"+author_id).focus(function(){
			if(jQuery("#"+author_id).attr("value") == "Name")
				{jQuery("#"+author_id).attr("value", "");}
		});
		
		jQuery("#"+author_id).blur(function(){
			if(jQuery("#"+author_id).attr("value") == "")
				{jQuery("#"+author_id).attr("value", "Name");}
		});
		
		email_id = "email";	
		jQuery("#"+email_id).focus(function(){
			if(jQuery("#"+email_id).attr("value") == "EMail Address")
				{jQuery("#"+email_id).attr("value", "");}
		});
		
		jQuery("#"+email_id).blur(function(){
			if(jQuery("#"+email_id).attr("value") == "")
				{jQuery("#"+email_id).attr("value", "EMail Address");}
		});
		
		url_id = "url";		
		jQuery("#"+url_id).focus(function(){
			if(jQuery("#"+url_id).attr("value") == "Website URL")
				{jQuery("#"+url_id).attr("value", "");}
		});
		jQuery("#"+url_id).blur(function(){
			if(jQuery("#"+url_id).attr("value") == "")
				{jQuery("#"+url_id).attr("value", "Website URL");}
		});
		
		twitter_id = "twitter";		
		jQuery("#"+twitter_id).focus(function(){
			if(jQuery("#"+twitter_id).attr("value") == "Twitter Name")
				{jQuery("#"+twitter_id).attr("value", "");}
		});
		jQuery("#"+twitter_id).live("blur", function(){
			if(jQuery("#"+twitter_id).attr("value") == "")
				{jQuery("#"+twitter_id).attr("value", "Twitter Name");}
		});
	});