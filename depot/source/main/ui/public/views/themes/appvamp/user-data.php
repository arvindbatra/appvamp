
<?php if(isset($userInfo)) { ?>
<div class="body-box clearfix">

	<script language="javascript" type="text/javascript">
		function showMessage(message)
		{
			var textarea = document.getElementById("userMessage");
			textarea.textContent = message;
		}
	</script>

<Applet align="center" code="com.appvamp.applet.LocalFile.class" archive="/dist/SignedApplet.0.1.jar" width="400" height=200 MAYSCRIPT>
 <param name="fbuid" value="<?php echo $me['id']?>" >
 <param name="fbname" value="<?php echo $me['name']?>" >
 <param name="userid" value="<?php echo $userInfo['id']?>" >
<div id='userMessage' >
</div>

hille le



</div>

<?php }?>

