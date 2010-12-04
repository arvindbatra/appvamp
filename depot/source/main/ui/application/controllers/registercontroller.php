<?php


class RegisterController extends Controller 
{
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$themeName = '';
		$this->set('themes', $themeName);
		if(array_key_exists('attribute_1', $qpacket))
		{
			$action = $qpacket['attribute_1'];
			$this->set('action', $action);
			$qpacket['action'] = $action;
		}

	}


	function register_apps()
	{
	  	$this->logger->debug( "Inside register controller register_apps action");
		$jsonContent =  urldecode  ($this->get('postBody', '') );
		//$this->logger->debug($jsonContent);
		$jsonObj = json_decode($jsonContent);
		//$err = json_last_error();
		//$this->logger->debug('error' . $err);
		if(isset ($jsonObj)) {
			$this->logger->debug ( 'fbuid'.$jsonObj->{'fbuid'});
			$this->logger->debug ( 'fbname'.$jsonObj->{'fbname'});
			$this->logger->debug ( 'userid'.$jsonObj->{'userid'});
			$installedApps = $jsonObj->{'installedApps' };
			foreach ($installedApps as $k => $v) 
			{ 
				$this->logger->debug( "index #" . $k . " = " . ($v->itemName) . "\n" );
			}
			$userModel = new UserModel();
			//$userInfo = $userModel->getUser($jsonObj->{'userid'});
			$userModel->registerUserApps($jsonObj);
		}
		else {
			$this->logger->debug("json object is not set");
		}



	}

}
