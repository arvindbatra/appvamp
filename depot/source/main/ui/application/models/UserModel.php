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









}
