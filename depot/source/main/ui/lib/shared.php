<?php

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
		$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
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

function route()
{
	setReporting();

	$logger = AppLogger::getInstance()->getLogger();
	
	/**read params and then unregisterGlobals()*/
	$url = '';
	if(isset($_GET['url']))
	{
		$url = $_GET['url'];
	}

	removeMagicQuotes();
	unregisterGlobals();

	$qpacket = array();
	$qpacket["url"] = $url;
	$logger->info('url:' . $url);

	$controllerName = getController($qpacket);
	$controllerName .= 'Controller';
	$qpacket['controllerName'] = $controllerName;
	//echo "controller: $controllerName <br>";
	$dispatch = new $controllerName ($qpacket);
	$action = 'view';
	$dispatch->$action();
}


function getController($qpacket)
{
	$url = $qpacket["url"];

//	if(empty($url)) { 
		$url = "app";
//	}
//	echo "url:" . $url ."<br>";
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
