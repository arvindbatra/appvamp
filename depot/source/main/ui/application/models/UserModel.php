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














}
