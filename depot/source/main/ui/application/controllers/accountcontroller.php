<?php


class AccountController extends Controller 
{
	private $VERIFICATION_STATUS_UNVERIFIED = 0;
	private $VERIFICATION_STATUS_PENDING = 1;
	private $VERIFICATION_STATUS_ACCEPTED = 2;
	private $VERIFICATION_STATUS_EXPIRED = 3;
	function __construct(&$qpacket)
	{
		parent::__construct($qpacket);
		$this->set('viewMode','user-data');
		if(array_key_exists('attribute_1', $qpacket))
		{
			$action = $qpacket['attribute_1'];
			$this->set('action', $action);
			$qpacket['action'] = $action;
		}
		
		$userInfoStr = $this->get("user_info",'');
		$authType = $this->get("auth_type", "");
		if(isset($userInfoStr) && isset($authType))
		{
			$userInfo = Utils::getOrCreateUserInfo($userInfoStr, $authType);
			if($userInfo != null)
				$this->set('userInfo', $userInfo);
		}

	}
	public function register_user()
	{
		$this->set('title', 'appvamp');
		$themeName = 'simpleResponse';
		$this->set('themes', $themeName);
		$this->set('themeDir',  '/views' . DS . 'themes' . DS . $themeName );

		$this->logger->debug("Inside account controller refund register user");
		$userInfoStr = $this->get("user_info",'');
		$authType = $this->get("auth_type", "");
		$userInfo = Utils::getOrCreateUserInfo($userInfoStr, $authType);
		if($userInfo == null)
		{
			$this->logger->error("Unable to get user information. Returning. " . $userInfoStr);
			return;
		}
		$this->set('userInfo', $userInfo);
	}


	function view()
	{
		$this->set('title', 'appvamp');

		$this->logger->debug("Inside account controller view");
		/*$userInfoStr = $this->get("user_info",'');
		$authType = $this->get("auth_type", "");

		//$userInfo = $this->getOrCreateUserInfo($userModel);
		$userInfo = Utils::getOrCreateUserInfo($userInfoStr, $authType);
		$this->set('userInfo', $userInfo);

		*/
		$userInfo = $this->get('userInfo', null);
		if($userInfo == null)
		{
			$this->logger->error("Unable to get user information. Returning. " );
			return;
		}
		$userModel = new UserModel();
		$userId = $userInfo['id'];
		//get user's apps.
		$this->logger->debug('calling user apps');	
		$relevantAppArr = $userModel->getRelevantAppsForUser($userId);
		$sumPending = 0.0;
		$sumAccepted = 0.0;
		$sumVerified = 0.0;
		$numAccepted = 0;
		$userAppArr = array();
		foreach ($relevantAppArr as $k=>$v)
		{
			$relevantAppInfo = &$relevantAppArr[$k];
			if(strcmp($relevantAppInfo['status'], 'pending') == 0)
				$sumPending += $relevantAppInfo['refund_price'];
			if(strcmp($relevantAppInfo['status'], 'verified') == 0)
				$sumVerified += $relevantAppInfo['refund_price'];

			array_push($userAppArr, $relevantAppInfo);

		}
		$this->set('userAppArr', $userAppArr);
		$acceptedAppsArr = $userModel->getAcceptedApps($userId);
		if(isset($acceptedAppsArr))
		{
			foreach ($acceptedAppsArr as $acceptedApp)
			{
				$numAccepted++;
				$sumAccepted += $acceptedApp['value_received'];			
			}
		}
		
		

/*		
		$userAppArr = $userModel->getUserAppsInAppLine($userId);

		if(isset($userAppArr) && count($userAppArr) > 0)
		{
			foreach($userAppArr as &$userAppInfo) 
			{
			
				
				$verificationStatus = $userAppInfo['verification_status'];
				if($verificationStatus == $this->VERIFICATION_STATUS_VERIFIED)
				{
					$userAppInfo['status'] = 'verified';
					$numVerified = $numVerified + 1;
					$sumVerified += $userAppInfo['refund_price'];
				}
				

				else if($verificationStatus == $this->VERIFICATION_STATUS_UNVERIFIED)
				{
					$status = $this->checkDate($userAppInfo);
					if($status == $this->VERIFICATION_STATUS_EXPIRED)
						$userAppInfo['status'] = 'expired';
				
					else if($status == $this->VERIFICATION_STATUS_PENDING)
					{
						$userAppInfo['status'] = 'pending';
						$sumPending += $userAppInfo['refund_price'];
					}
					else 
						$userAppInfo['status'] = 'unknown';
				}
			}
			$this->set('userAppArr', $userAppArr);
		}
*/
		$this->set('sumPending', $sumPending);
		$this->set('sumVerified', $sumVerified);
		$this->set('sumAccepted', $sumAccepted);
		$this->set('numAccepted', $numAccepted);
	}


	public function refund_pending_amount()
	{
		$this->set('title', 'appvamp');
		$themeName = 'simpleResponse';
		$this->set('themes', $themeName);
		$this->set('themeDir',  '/views' . DS . 'themes' . DS . $themeName );
				$this->set('responseString', "Oh no! I had some trouble processing your payment. Please let me know at support@appvamp.com");

		$this->logger->debug("Inside account controller refund pending amount");
		$userId = $this->get("user_id", '');
		$refundAmount = $this->get("refund_amount", '');

		$userModel = new UserModel();
		$userInfo = $userModel->getUser($userId);
		if($userInfo == NULL)
		{
			$this->logger->error("Unable to get user information. Returning. " . $userId);
			return;
		}
		
		$sumPending = 0.0;
		$ids = array(); 
		
		$relevantAppArr = $userModel->getRelevantAppsForUser($userId);
		foreach ($relevantAppArr as $k=>$v)
		{
			$relevantAppInfo = &$relevantAppArr[$k];
			$this->logger->debug($relevantAppInfo['app_name'].$relevantAppInfo['refund_price']. ' ' . $relevantAppInfo['status']);	
			if(strcmp($relevantAppInfo['status'] , 'verified') == 0)
			{
			$this->logger->debug($relevantAppInfo['app_name'].$relevantAppInfo['refund_price']. ' '. $relevantAppInfo['userapp_id']);	
				$sumPending += $relevantAppInfo['refund_price'];
				$ids[$relevantAppInfo['userapp_id']] = $relevantAppInfo['refund_price'];
			}


		}


/*		$userAppArr = $userModel->getUserAppsInAppLine($userId);
		if(!isset($userAppArr) || count($userAppArr) == 0)
		{
			$this->logger->error("Could not find any apps in appline. Returning. " . $userId);
			return;
		}
		foreach($userAppArr as &$userAppInfo) 
		{
				$verificationStatus = $userAppInfo['verification_status'];
				if($verificationStatus == $this->VERIFICATION_STATUS_VERIFIED)
				{
					#already verified. Do not pay twice !!!
					continue;
				}
				else if($verificationStatus == $this->VERIFICATION_STATUS_UNVERIFIED)
				{
					$status = $this->checkDate($userAppInfo);

					if($status == $this->VERIFICATION_STATUS_PENDING)
					{
						$sumPending += $userAppInfo['refund_price'];
						$ids[$userAppInfo['id']] = $userAppInfo['refund_price'];
					}
				}
		}
*/
		$ret = $userModel->updateUserAppInfoStatus($userId, $ids, $this->VERIFICATION_STATUS_ACCEPTED);
		if($ret == true)
		{
			$payoutReturn = $userModel->insertUserPayoutTransactions($userId, $ids, 'pending');	
			if($payoutReturn == true)
			{
				$this->set('responseString', "Hooray! We have sent a payment of \$" . $sumPending . " to your Paypal account at ". $userInfo['paypal_email_address']);
				return;
			}
			else
			{

				$this->logger->error("Error in inserting payout transaction status for user_id" . $userId . " data:" . implode(",", $ids));
				return;
			}

		}
		else
		{
			$this->logger->error("Error in updating user app info status for user_id" . $userId . " data:" . implode(",", $ids));
			return;
		}

	}













	public function update_paypal_address()
	{
		$this->set('title', 'appvamp');
		$themeName = 'simpleResponse';
		$this->set('themes', $themeName);
		$this->set('themeDir',  '/views' . DS . 'themes' . DS . $themeName );
		
		$paypalAddrStr = $this->get("paypal_email_address",'');
		$userId = $this->get("user_id", '');
		$this->logger->debug("Inside account controller update paypal address");
		$userModel = new UserModel();
		$userInfo = $userModel->getUser($userId);
		if($userInfo == NULL)
		{
			$this->logger->error("Unable to get user information. Returning. " . $userId);
			return;
		}
		if(empty($paypalAddrStr)) 
		{
			$this->logger->info("Paypal address is empty. returning");
			return;
		}

		$ret = $userModel->updateUserPaypalAddress($userInfo, $paypalAddrStr);
		if(isset($ret) && $ret == true)
		{
			$this->set('responseString', 'true');
		}
		else
			$this->set('responseString', 'false');


	}



/*	public static function getOrCreateUserInfo(&$userInfoStr, &$authType)
	{
		//$userInfoStr = $this->get("user_info",'');
		$userModel = new UserModel();
		if(isset($userInfoStr))
		{
			$userInfoJson = json_decode($userInfoStr);
			$err = json_last_error();
			$this->logger->debug('error' . $err);
			$this->logger->debug(json_encode($userInfoJson));
		//	$authType = $this->get("auth_type", "");
			if(!isset($authType) || !isset($userInfoJson))
			{
				$this->logger->error("Unknown auth type or can't decode json". $userInfoStr);
				return NULL;
			}
			$userInfo = null;

			if(strcmp("facebook", $authType) == 0)
			{
				$name = $userInfoJson->{"name"};
				$authId = $userInfoJson->{"id"};
				$this->logger->debug("authid:". $authId . " authType". $authType ." name:" . $name);
				$userInfo = $userModel->getOrCreateUser($authId, $authType, $name);
				if($userInfo == null)
				{
					$this->logger->error("Unable to get user information. Returning. " . $userInfoStr);
					return NULL;
				}
				$this->set('userInfo', $userInfo);
				$this->logger->debug('arv_' .$userInfo);
				return $userInfo;
			}
			return NULL;
		}
		return NULL;
	}
*/

	private function checkDate($userAppInfo)
	{
		$purchasedDate = $userAppInfo['purchased_date'];
		$phpPurchasedDate = strtotime( $purchasedDate );
		//$verifiedDate = $userAppInfo['verified_date'];
		//$phpVerifiedDate = strtotime( $verifiedDate );
		$onDate = $userAppInfo['on_date'];
		$phpOnDate = strtotime( $onDate );
		$tillDate = $userAppInfo['till_date'];
		$phpTillDate = strtotime( $tillDate );

		$status = $this->VERIFICATION_STATUS_UNVERIFIED;

		//if($phpVerifiedDate > $phpTillDate || $phpVerifiedDate < $phpOnDate)
		if($phpPurchasedDate > $phpTillDate || $phpPurchasedDate < $phpOnDate)
			$status = $this->VERIFICATION_STATUS_EXPIRED;

		//if($phpVerifiedDate >= $phpOnDate && $phpVerifiedDate < $phpTillDate)
		if($phpPurchasedDate >= $phpOnDate && $phpPurchasedDate < $phpTillDate)
			$status = $this->VERIFICATION_STATUS_PENDING;
		return $status;
	}



}
