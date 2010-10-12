<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));


define('FACEBOOK_APP_ID', '156605134369786');
define('FACEBOOK_SECRET', '85d89a470b8f98bbfc1b7d0255915813');

$me = null;

require_once (ROOT . DS . 'lib' . DS . 'bootstrap.php');
$facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
  'cookie' => true, // enable optional cookie support
));

$session = $facebook->getSession();

// Session based API call.
if ($session) {
  try {
    $uid = $facebook->getUser();
    $me = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}


// login or logout url will be needed depending on current user state.
if ($me) {
	  $logoutUrl = $facebook->getLogoutUrl();
} else {
	  $loginUrl = $facebook->getLoginUrl();
}

?>


<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>appvamp.com</title>

	<script language="javascript" type="text/javascript">
	function showApps(appData)
	{
		var jsonObj  = JSON.parse(appData,null);
		var appArr = jsonObj['installedApps'];
		var textarea = document.getElementById("installedApps");
		textarea.value = '';
		for(var i=0;i<appArr.length;i++)
		{
			textarea.value += appArr[i]['itemName'] + '\n';
		}

	}

	function showMessage(message)
	{
		var textarea = document.getElementById("userMessage");
		textarea.textContent = message;
	}


	</script>

</head>

<body>
 <h1>appvamp</h1>
    <p><fb:login-button autologoutlink="true"></fb:login-button></p>
    <p><fb:like></fb:like></p>

    <div id="fb-root"></div>
    <script>
      window.fbAsyncInit = function() {
        FB.init({appId: '156605134369786', 
				status: true, cookie: true,
                 xfbml: true});
		FB.Event.subscribe('auth.login', function() {
			window.location.reload();
		});
      };
		(function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = document.location.protocol +
			'//connect.facebook.net/en_US/all.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
		}());
	</script>

 <?php if ($me): ?>
 <a href="<?php echo $logoutUrl; ?>">
 <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">
 </a>
 <?php else: ?>
 <div>
 Using JavaScript &amp; XFBML: <fb:login-button></fb:login-button>
 </div>
 <div>
 Without using JavaScript &amp; XFBML:
 <a href="<?php echo $loginUrl; ?>">
 <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif">
 </a>
 </div>
 <?php endif ?>

 <h3>Session</h3>
 <?php if ($me): ?>
	 <pre><?php print_r($session); ?></pre>

	 <h3>You</h3>
	 <img src="https://graph.facebook.com/<?php echo $uid; ?>/picture">
	 <?php echo $me['name']; ?>

	 <h3>Your User Object</h3>
	 <pre><?php print_r($me); ?></pre>
 <?php else: ?>
	 <strong><em>You are not Connected.</em></strong>
 <?php endif ?>




applet goes here - 
<br/>
<Applet align="center" code="com.appvamp.applet.LocalFile.class" archive="/dist/SignedApplet.0.1.jar" width="400" height=200 MAYSCRIPT>
 <param name="fbuid" value="<?php echo $me['id']?>" >
 <param name="fbname" value="<?php echo $me['name']?>" >
You do not have java installed.
</applet>
<div id='userMessage' >
</div>

<p>
Installed Apps: <br/>

<TextArea rows="20" cols="100" NAME="installedApps" id="installedApps"> </TextArea>
</p>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<br/>

applet via object interface goes here
<br/>
<object classid="java:com/appvamp/applet/LocalFile.class" type="application/x-java-applet" archive="dist/SignedApplet.jar" height="350" width="550" >
	<param name="code" value="com.appvamp.applet.LocalFile.class" />
	<!-- For Konqueror -->
	<param name="archive" value="dist/applets.jar" />
	<param name="persistState" value="false" />
	<PARAM NAME="MAYSCRIPT" VALUE="true">

	<center>
	<p><strong>Appvamp content requires Java, which your browser does not appear to have.</strong></p>
    <p><a href="http://www.java.com/en/download/index.jsp">Get the latest Java Plug-in.</a></p>
	</center>
 </object>

<br/>
and all looks good
<br/>

</body>

</html>
