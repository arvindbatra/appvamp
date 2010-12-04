<?php


require_once('DatabaseHandler.php');
require_once('AppClient.php');


class UserModel
{

	private $dbHandle;
	private $appClient;

	public function __construct()
	{
	  	$this->dbHandle = DatabaseHandler::getInstance()->getHandle();
		$this->appClient = new AppClient(); 
	}

	public function registerUserApps($appJsonObj)
	{
		
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		DatabaseUtils::updateUserAppInfo($dbHandle, $appJsonObj);
	}

	
	public function getOrCreateUser($authId, $authType, $name)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$userInfo = DatabaseUtils::getUserInfoByAuth($dbHandle, $authId, $authType);
		if($userInfo != null)
			return $userInfo;
		
		$logger->info("No user found with authId:". $authId . " authType:" . $authType . " . Adding user");	
		if(!DatabaseUtils::addUserInfo($dbHandle, $authId, $authType, $name))
		{
			$logger->error("Error in inserting user with params " . $authId . " " . $authType . " " . $name); 
			return null;
		}
		$userInfo = DatabaseUtils::getUserInfoByAuth($dbHandle, $authId, $authType);
		return $userInfo;
	}


	public function getUser($userid)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$userInfo = DatabaseUtils::getUserInfoById($dbHandle, $userid);
		return $userInfo;
	}
	
	public function updateUserPaypalAddress($userInfo, $paypalAddress)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::updatePaypalAddressForUser($dbHandle, $userInfo['id'], $paypalAddress);

	}


	public function getAllUserApps($userid)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::getUserAppInfo($dbHandle, $userid);		

	}

	public function getAcceptedApps($userId)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::getAcceptedApps($dbHandle, $userId);		

	}


	public function getRelevantAppsForUser($userId)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();

		$appPostArr = DatabaseUtils::getOngoingAppDeals($dbHandle);
		$userRelevantAppArr = array();
		$appNames = '';
		$first = true;
		for($i=0; $i<count($appPostArr); $i++)
		{
			$appPost = &$appPostArr[$i];
			$appName = trim($appPost['app_name']);
			$appPost['status'] = 'pending';
			$logger->debug("ongoing deal for " . $appName);
			$userRelevantAppArr[strtolower($appName)] = $appPost;
			if($first)
			{
				$appNames .= '\''. $appName . '\'';
				$first = false;
			}
			else
				$appNames .= ",'"  . $appName . '\'';

		}
		$logger->debug('appNames='.$appNames);
		$userApps = DatabaseUtils::getUserAppInfosByAppNames($dbHandle, $userId, $appNames);

		//foreach($userApps as &$userApp)
		for($i=0; $i<count($userApps); $i++)
		{
			$userApp = $userApps[$i];
			$verificationStatus = $userApp['verification_status'];
			$appName = trim(strtolower($userApp['app_name']));
			$logger->debug($appName . ' ' . $verificationStatus);
			$userRelevantAppInfo = &$userRelevantAppArr[$appName];
			if(!isset($userRelevantAppInfo))
			{
				$logger->error("appname not set " . $appName);
				continue;
			}
			$userRelevantAppInfo['userapp_id'] = $userApp['id'];
			if($verificationStatus == 0)
				$userRelevantAppInfo['status'] = 'verified';
			else
				$userRelevantAppInfo['status'] = 'accepted';
		}
		//unset($userApp);
		return $userRelevantAppArr;
	}

	

	public function getUserAppsInAppLine($userid)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$userAppArr = DatabaseUtils::getUserAppInfosInAppLine($dbHandle, $userid);	
		return $userAppArr;
	}


	public function updateUserAppInfoStatus($userId, $idArr, $newStatus)
	{
		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::updateUserAppInfoStatus($dbHandle, $userId, $idArr, $newStatus);

	}
	public function insertUserPayoutTransactions( $userId, $userAppInfoMap, $status)
	{

		$logger = AppLogger::getInstance()->getLogger();
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::insertUserPayoutTransactions($dbHandle, $userId, $userAppInfoMap, $status);
	}

}
