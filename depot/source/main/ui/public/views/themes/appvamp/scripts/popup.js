/***************************/
//@Author: Adrian "yEnS" Mato Gondelle
//@website: www.yensdesign.com
//@email: yensamg@gmail.com
//@license: Feel free to use it, but keep this credits please!					
/***************************/

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var reloadOnClose = 0;

//loading popup with jQuery magic!
function loadPopup(  popupElem){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		//$("#popupContact").fadeIn("slow");
		$(popupElem).fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(popupElem){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		//$("#popupContact").fadeOut("slow");
		$(popupElem).fadeOut("slow");
		popupStatus = 0;
		if(reloadOnClose == 1) {
			reloadOnClose = 0;
			window.location.reload(true);
		}
	}
}

//centering popup
function centerPopup( popupElem){
	//request data for centering
	//var windowWidth = document.documentElement.clientWidth;
	//var windowHeight = document.documentElement.clientHeight;
	var windowHeight = $(window).height();
	var windowWidth = $(window).width();
	//var popupHeight = $("#popupContact").height();
	//var popupWidth = $("#popupContact").width();
	var popupHeight = $(popupElem).height();
	var popupWidth = $(popupElem).width();
	//centering
	//$("#popupContact").css({
	$(popupElem).css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}

function registerUser(userInfoStr, authType)
{

	$.ajax({
		type: "POST",
		url: "/account/register_user",
		data: "user_info="+userInfoStr+"&auth_type="+authType
	});
}


function updatePaypalAddress() 
{
	$.ajax({
		type: "POST",
		url: "/account/update_paypal_address",
		data: 	"user_id=" + document.getElementById("popupPaypal_user_id").value + 
				"&paypal_email_address=" + document.getElementById("popupPaypal_email_address").value,
		success: function(html){
			if(html == 'true')
				$("#update_paypal_address_response").html("<i>Successfully updated paypal address!</i>");
			else
				$("#update_paypal_address_response").html("Error in updating email address. Please contact admin!");

		}
	});
}

function refundUser(popupElem)
{
	var userId = document.getElementById("user_id");
	var refundAmount = document.getElementById("sum_pending");
	$.ajax({
		type: "POST",
		url: "/account/refund_pending_amount",
		data: "user_id=" + userId.value + "&refund_amount="+refundAmount.value,
		success:function(html) {
			$("#refund_response").html(html);	
		}
	});

}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	var popupElem;
	$("#verify-app").click(function(){
		var verifyAppDiv = $("#verify-app");
		//var loggedin = verifyAppDiv.attr("loggedin");
		popupElem = "#popupContact";
		//if(loggedin != 1) 
		{
			//centering with css
			centerPopup(popupElem);
			//load popup
			loadPopup(popupElem);
		}
		//else
		{
			//$("#submit-verify-app").submit();
		}
	});

	var fbRoot = $("#fb-root");
	if(fbRoot.attr("loggedin") == 1)
	{
		var userInfoStr = document.getElementById("user_info").value;
		var authType = document.getElementById("auth_type").value;
		//registerUser(userInfoStr, authType);
	}

	
	$("#update-paypal").click(function()
	{
		popupElem = "#popupPaypal";
		//centering with css
		centerPopup(popupElem);
		//load popup
		loadPopup(popupElem);
	});

	$("#refund-user").click(function()
	{
		var refundUserDiv = $("#refund-user");
		var refundSum = refundUserDiv.attr("refundSum");
		popupElem = "#popupRefund";
		if(refundSum == 0)
		{
			centerPopup(popupElem);
			loadPopup(popupElem);
			$("#refund_response").html("You do not have any verified moolah at this time.");	
		}
		else 
		{
			reloadOnClose = 1;
			refundUser(popupElem);
			centerPopup(popupElem);
			loadPopup(popupElem);
		}

	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		disablePopup(popupElem);
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup(popupElem);
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup(popupElem);
		}
	});

});

