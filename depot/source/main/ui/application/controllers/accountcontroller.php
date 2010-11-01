<?php


class AccountController extends Controller 
{
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$this->set('viewMode','user-data');

	}


	function view()
	{
		$this->set('title', 'appvamp');

		$this->logger->debug("Inside account controller view");
		$userInfoStr = $this->get("user_info",'');
		$userModel = new UserModel();
		if(isset($userInfoStr))
		{
			$userInfoJson = json_decode($userInfoStr);
			$err = json_last_error();
			$this->logger->debug('error' . $err);
			$this->logger->debug(json_encode($userInfoJson));
			$authType = $this->get("auth_type", "");
			if(!isset($authType) || !isset($userInfoJson))
			{
				$this->logger->error("Unknown auth type or can't decode json". $userInfoStr);
				//show 404 or somethine.
				return;
			}
			$userInfo = null;

			if(strcmp("facebook", $authType) == 0)
			{
				$name = $userInfoJson->{"name"};
				$authId = $userInfoJson->{"id"};
				$userInfo = $userModel->getOrCreateUser($authId, $authType, $name);
				if($userInfo == null)
				{
					$this->logger->error("Unable to get user information. Returning. " . $userInfoStr);
					return;
				}
				$this->set('userInfo', $userInfo);
			}
		}
	}




}
