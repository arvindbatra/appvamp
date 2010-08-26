<?php

//require('DatabaseHandler.php');

class AppModel extends DBModel
{

	public function getAllApps()
	{
		$dbHandle = DatabaseHandler::getInstance()->getHandle();

		$appList  = DatabaseUtils::queryAllAppInfo($dbHandle);

		echo "appList recvd<br>";
		for($i = 0; $i < count($appList); $i++)
		{
			$appInfo  = $appList[$i];
			echo "Name:: $appInfo->appName Seller ::::: $appInfo->appSeller <br>";
		}
	}



};
