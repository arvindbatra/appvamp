<?php 


class Utils {



	public static function getOrCreateUserInfo(&$userInfoStr, &$authType)
	{
		$logger = AppLogger::getInstance()->getLogger();
		//$userInfoStr = $this->get("user_info",'');
		$userModel = new UserModel();
		if(isset($userInfoStr))
		{
			$logger->debug(json_encode($userInfoStr));
			$userInfoJson = json_decode($userInfoStr);
			$err = json_last_error();
			$logger->debug('error' . $err);
			$logger->debug(json_encode($userInfoJson));
		//	$authType = $this->get("auth_type", "");
			if(!isset($authType) || !isset($userInfoJson))
			{
				$logger->error("Unknown auth type or can't decode json". $userInfoStr);
				return NULL;
			}
			$userInfo = null;

			if(strcmp("facebook", $authType) == 0)
			{
				$name = $userInfoJson->{"name"};
				$authId = $userInfoJson->{"id"};
				$logger->debug("authid:". $authId . " authType". $authType ." name:" . $name);
				$userInfo = $userModel->getOrCreateUser($authId, $authType, $name);
				if($userInfo == null)
				{
					$this->logger->error("Unable to get user information. Returning. " . $userInfoStr);
					return NULL;
				}
				return $userInfo;
			}
			return NULL;
		}
		return NULL;
	}
}
