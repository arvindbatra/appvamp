
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


<title><?php echo $title; ?></title>
<link href="/<?php echo $themeDir?>/style.css" rel="stylesheet" type="text/css" />

<!-- google analytics script -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-18986788-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

<?php $me = null;
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
</head>
<body>
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

<div id="container">
<div class="light-grey">
	<div id="header-container">
	<div class="container-header-dark-normal"><span></span></div>
	<div class="header" >
		<div class="floater">
			<h1> <a href="/"> The <b>AppVamp &nbsp;&nbsp; </b></a><h1>
		</div>
		<div class="like" ><iframe src="http://www.facebook.com/plugins/like.php?href=www.appvamp.com&amp;layout=standard&amp;show_faces=false&amp;width=200&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true"></iframe> 
		</div>
		<div class="generic-info">
			<i>I am the App Vamp!</i> <br/> <br/>
			<div align="center">
	    		
				<a  href="http://www.twitter.com/appvamp"><img src="http://twitter-badges.s3.amazonaws.com/t_logo-a.png" alt="Follow appvamp on Twitter" height="25px"/></a> 
				<!--a name="fb_share" type="name_button" href="http://www.facebook.com/sharer.php" share_url="http://www.facebook.com/pages/AppVamp/160659277290847"-->
				<a name="fb_share" type="icon" href="http://www.facebook.com/sharer.php?u=http%3A%2F%2Fwww.facebook.com%2Fpages%2FAppVamp%2F160659277290847" >
				<img  height="25px" src="http://profile.ak.fbcdn.net/hprofile-ak-snc4/hs624.snc3/27535_20531316728_5553_q.jpg" alt="Share Appvamp on facebook" height="25px"/>
				</a><!--script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script-->
			</div>	
		</div>
	</div>
	<div class="header-box" >
		<ul class="nav">
			<li><a href="/">App of the day</a></li>
			<li><a href="/about/so-just-what-is-a-vamp">So just what is a Vamp ?</a></li>
			<li><a href="/about/the-team">The Team</a></li>
			<li><a href="/about/reach-out-and-touch-me">Reach out and Touch Me</a></li>
		</ul>

		<div class="device-selection" >
			<b>iPhone/iPad</b>
		</div>


	</div>
	<!--div class="container-header-dark-normal"><span></span></div-->

	</div>
</div>
