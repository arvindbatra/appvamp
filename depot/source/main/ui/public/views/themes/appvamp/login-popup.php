<!--center>
	<div id="button"><input type="submit" value="Press me please!" /></div>
</center-->

<script language="javascript" type="text/javascript">
	function showMessage(message)
	{
		var textarea = document.getElementById("userMessage");
		textarea.textContent = message;
	}
</script>

<div id="popupContact">
	<a id="popupContactClose" >x</a>
	<?php if($loggedin == false) { ?>
	<h1>Login required!!</h1>
	<p id="contactArea">
		Please login via facebook to verify app download and manage your account. 
		<p align="center">
			<fb:login-button autologoutlink="true"></fb:login-button>
		</p>
	</p>
	<?php } else {?>
	<h1>Verify apps!</h1>
	<p id="contactArea">
		Appvamp would like to scan apps from your collection.  Please click the button below. Your browser may ask you to confirm security of this app.
		<div class="read-apps">
			<Applet align="center" code="com.appvamp.applet.LocalFile.class" archive="/dist/SignedApplet.0.1.jar" width="150" height="50" MAYSCRIPT>
				 <param name="fbuid" value="<?php echo $me['id']?>" >
			 	<param name="fbname" value="<?php echo $me['name']?>" >
				 <param name="userid" value="<?php echo $userInfo['id']?>" >
			</Applet>
		</div>
	</p>
	<div id='userMessage' >
	</div>

	<br/>
	<br/>
	<div align="right">
		<h5> Proceed to<a href="/account"> my account </a>
	</div>


	<?php } ?>


</div>

<div id="popupPaypal">
	<a id="popupContactClose" onclick="disablePopup('#popupPaypal')">   x</a>
	<?php if(isset($userInfo)) { ?>
		<h1>Update paypal address!!</h1>
		<p id="contactArea">
			Dear <?php echo $userInfo['name'] ?>, Please update your paypal address. 
					<input type="hidden" id="popupPaypal_user_id" value="<?php echo $userInfo['id']?>">
			<p align="center">
				Paypal email address : <INPUT id="popupPaypal_email_address" SIZE="30" value="<?php if(isset($userInfo['paypal_email_address'])) echo $userInfo['paypal_email_address'];?>"/>
			<input type="submit" value="Update" onclick="updatePaypalAddress()"/>
			<br/>
			<div id="update_paypal_address_response">

			</div>
			</p>

		</p>
	<?php } else {?>
		<p id="contactArea">
			Please login before updating your paypal address. 
		</p>
	<?php } ?>
</div>


<div id="popupRefund">
	<a id="popupContactClose" onclick="disablePopup('#popupRefund')">   x</a>
		<h1>Refund Response Message</h1>
	<div id="refund_response">

	</div>
</div>



<div id="backgroundPopup"></div>

