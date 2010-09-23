<?php

require_once('DatabaseHandler.php');
require_once('AppClient.php');

class AppModel
{

	private $dbHandle;
	private $appClient;

	public function __construct()
	{
	  	$this->dbHandle = DatabaseHandler::getInstance()->getHandle();
		$this->appClient = new AppClient(); 
	}

	public function getAppPostOfTheDay($date)
	{
		if(!isset($date))
		{
			$date = $today = date("Y-m-d");
		}
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$postArr = DatabaseUtils::getAppPostByDate($dbHandle, $date);
		if(count($postArr) > 0)
		{
		  	return $postArr[0];
		}
		return null;	

	}

	public function getPreviousPostsFromDate($date, $numPosts)
	{
		$dbHandle = DatabaseHandler::getInstance()->getHandle();
		if(!isset($date))
		{
			$date = $today = date("Y-m-d");
		}
		$postArr = DatabaseUtils::getPreviousPostsFromDate($dbHandle, $date, $numPosts);
		return $postArr;
	}

	public function getAppPost($appName, $appDate)
	{

	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$postArr = DatabaseUtils::getPostData($dbHandle, $appName, $appDate);

		if(count($postArr) > 0)
		{
		  	return $postArr[0];
		}
		return null;
/*		echo "appList recvd<br>";
		for($i = 0; $i < count($appList); $i++)
		{
			$appInfo  = $appList[$i];
			echo "Name:: $appInfo->appName Seller ::::: $appInfo->appSeller <br>";
		}
*/
	}

	public function fetchApp($appUrl)
	{
		$qpacket = array();
		$qpacket['action'] = 'fetch_app';
		$qpacket['url'] = $appUrl;

		$resp = $this->appClient->queryServer($qpacket);
		return $resp;

	}
	
	public function getAllAppInfosByName($appName)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appInfoArr = DatabaseUtils::queryAppInfosByName($dbHandle, $appName);
		return $appInfoArr;;
	}


	public function getAppInfo($appName)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appInfoArr = DatabaseUtils::queryAppInfoByName($dbHandle, $appName);
		if(count($appInfoArr) > 0)
		{
		  	return $appInfoArr[0];
		}
		return null;
	}
	public function getAppInfoByUrl($appUrl)
	{
		$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appInfoArr = DatabaseUtils::queryAppInfoByUrl($dbHandle, $appUrl);
		if(count($appInfoArr) > 0)
		{
		  	return $appInfoArr[0];
		}
		return null;
	}
	
	public function getAppInfoById($appId)
	{
		$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appInfoArr = DatabaseUtils::queryAppInfoById($dbHandle, $appId);
		if(count($appInfoArr) > 0)
		{
		  	return $appInfoArr[0];
		}
		return null;
	}

	public function getAppRecommendations($appId)
	{
		$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appInfoArr = DatabaseUtils::getAppRecommendations($dbHandle, $appId);
		return $appInfoArr;
	}


	public function getAppReviews($appName)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appReviewArr =  DatabaseUtils::queryAppReviewsByName($dbHandle, $appName);
		return $appReviewArr;	
	}

	public function insertAppReview($appId, $appName, $reviewer, $reviewTitle, $review)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::insertAppReview($dbHandle, $appId, $appName, $reviewer, $reviewTitle, $review);
		

	}

	public function insertAppLineRecord($appId, $appReviewId, $appName, $onDate)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::insertAppLineRecord($dbHandle, $appId, $appReviewId, $appName, $onDate);
	}	

	public function getAppSchedule($startDate, $endDate)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		return DatabaseUtils::getPostDataByDate($dbHandle, $startDate, $endDate);
	}

	public function getAppReviewById($reviewId)
	{
	  	$dbHandle = DatabaseHandler::getInstance()->getHandle();
		$appReviewArr =  DatabaseUtils::queryAppReviewsById($dbHandle, $reviewId);

		if(count($appReviewArr) > 0)
		{
		  	return $appReviewArr[0];
		}
		return null;

	}

}
