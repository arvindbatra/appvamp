<?php

include_once(VIEWS_DIR. DS . 'common_functions.php');

/** Check if environment is development and display errors **/

function setReporting() 
{
	if (DEVELOPMENT_ENVIRONMENT == true) {
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
  	}
}



/** Check for Magic Quotes and remove them **/

function stripSlashesDeep($value) {
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
	return $value;
}

function removeMagicQuotes() {
	if ( get_magic_quotes_gpc() ) {
    		$_GET    = stripSlashesDeep($_GET   );
		$_POST   = stripSlashesDeep($_POST  );
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

/** Check register globals and remove them **/

function unregisterGlobals() 
{
	if (ini_get('register_globals')) 
	{
		//$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		$array = array( '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		foreach ($array as $value) 
		{
			foreach ($GLOBALS[$value] as $key => $var) {
				if ($var === $GLOBALS[$key]) 
				{
					unset($GLOBALS[$key]);
				}
			}
		}
	}
}

function curPageURL() {
	$pageURL = 'http';
	if(array_key_exists("HTTPS", $_SERVER)) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function curPageHost() {
	$pageURL = 'http';
	if(array_key_exists("HTTPS", $_SERVER)) {
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"];
	}
	return $pageURL;
}

function route()
{
	setReporting();

	$logger = AppLogger::getInstance()->getLogger();
	$qpacket = array();


	/**read params and then unregisterGlobals()*/
	$qpacket["pageHost"] = curPageHost();
	$qpacket["pageURL"] = curPageURL();
	$url = '';
	if(isset($_GET['url']))
	{
		$url = $_GET['url'];
	}
	foreach ($_SESSION as $key => $value) {
	  	$logger->debug('Get param: ' . $key.'='.$value);
		$qpacket[$key] = urldecode($value);
	}

	foreach ($_GET as $key => $value) {
	  	$logger->debug('Get param: ' . $key.'='.$value);
		$qpacket[$key] = urldecode($value);
	}
	foreach ($_POST as $key => $value) {
	  	$logger->debug('Post param: ' . $key.'='.$value);
		
		$qpacket[$key] = urldecode($value);
	}
	
	$body = @file_get_contents('php://input');
	if(isset($body)) {
		//$logger->debug("body="  . $body);
	  	$logger->debug('body ' . '='. strlen($body));
		$qpacket['postBody'] = $body;
	}
	$qpacket["url"] = $url;
	$action = 'view';
	$qpacket["action"] = $action;
	$urlVars = preg_split("/\//", $url);

	for($i = 0; $i< count($urlVars); $i++)
	{
		$logger->debug('attribute_'. $i . "=" . $urlVars[$i] );
		if(empty($urlVars[$i]))
		  	continue;
		$qpacket['attribute_'.$i] = parse_seo_string($urlVars[$i]);
	}

	removeMagicQuotes();
	unregisterGlobals();

	$allControllers = array("app", "admin","about", "reco", "register","account");
	//$allControllers = array( "reco");

	$controllerName = getController($qpacket);
	if(!in_array($controllerName, $allControllers))
	{
	 	$logger->error("Unknown controller name (" . $controllerName . ")found");
	  	return;
	}


	
	$controllerName .= 'Controller';
	$qpacket['controllerName'] = $controllerName;
	$dispatch = new $controllerName ($qpacket);
	if(array_key_exists('action',$qpacket)) {
		$action = $qpacket['action'];
	}
	$logger->debug("action is " . $action);
	$dispatch->$action();
}


function getController($qpacket)
{
	$url = '';
	
	if(array_key_exists('attribute_0', $qpacket)) {
		$url = $qpacket['attribute_0'];
	}

	if(empty($url)) { 
		//$url = "reco";
		$url = "app";
	}
	return $url;
}


/** Autoload any classes that are required **/

function __autoload($className) 
{
	//echo "Autoload called $className <br>";
	if (file_exists(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php')) {
		require_once(ROOT . DS . 'lib' . DS . strtolower($className) . '.class.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
		require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
 		require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
	} else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php')) {
 		require_once(ROOT . DS . 'application' . DS . 'models' . DS . $className . '.php');
	} else {
	/* Error Generation Code Here */
	}
}



spl_autoload_register('__autoload');

route();
