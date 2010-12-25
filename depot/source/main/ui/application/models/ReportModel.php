<?php

require_once('DatabaseHandler.php');
require_once('AppClient.php');

class ReportModel
{

	private $dbHandle;
	private $appClient;

	public function __construct()
	{
	  	$this->dbHandle = DatabaseHandler::getInstance()->getHandle();
		$this->appClient = new AppClient(); 
	}

	public function getUserPayoutDataReport()
	{
		
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::getUserPayoutDataReport($dbHandle);
	}

}
