<?php

class DatabaseUtils 
{
	public static function queryAllAppInfo($dbHandle)
	{
		$query = "select * from AppInfo;";
		return self::queryAppInfo($dbHandle, $query);
	}
	
	public static function getPostData($dbHandle, $appName, $appDate)
	{
		$appName = mysql_real_escape_string($appName);
		$appDate = mysql_real_escape_string($appDate);

	  	$query = "select * from AppLine where app_name='$appName' and on_date='$appDate';";
		return self::queryAppLine($dbHandle, $query);
	}	
	
	public static function getPostDataByDate($dbHandle, $startDate, $endDate)
	{
	  	$concat = false;
		$query = "select * from AppLine ";
		if(!empty($startDate) )
		{
			$query .= "where on_date>='$startDate'";
			$concat = true;
		}

		if(!empty($endDate) )
		{
			if($concat)
				$query .= " and ";
			else
				$query .= " where ";

			$query .= " on_date<='$endDate'";
		}
		$query .= " order by on_date desc;";
		return self::queryAppLine($dbHandle, $query);
	}

	//Returns the app for the  date specified or the previous app post that exists";
	public static function getAppPostByDate($dbHandle, $date)
	{
		$query = "select * from AppLine where on_date<='$date' order by on_date desc limit 1";
		return self::queryAppLine($dbHandle, $query);
	}
	
	public static function getPreviousPostsFromDate($dbHandle, $date, $numPosts)
	{
		$date = mysql_real_escape_string($date);
		$numPosts = mysql_real_escape_string($numPosts);
		$query = "select * from AppLine where on_date<'$date' order by on_date desc limit $numPosts";
		return self::queryAppLine($dbHandle, $query);
	}


	public static function queryAppLine($dbHandle, $query)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$logger->debug('Calling query: ' . $query);
		$result = mysql_query( $query, $dbHandle);
		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}
		$count = mysql_num_rows($result);
		$logger->debug("Returned $count rows");

		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$appPost = new AppPost();
			$appPost->appName = $row['app_name'];
			$appId = $row['app_id'];
			$appReviewId = $row['app_review_id'];
			$appPost->createdAt = $row['created_at'];
			$appPost->updatedAt = $row['updated_at'];
			$appPost->onDate = $row['on_date'];
			
			$appInfos = self::queryAppInfoById($dbHandle, $appId);
			if(count($appInfos) >= 0)
			{
				$appPost->appInfo = $appInfos[0];
			}
			$appReviews = self::queryAppReviewsById($dbHandle, $appReviewId);
			if(count($appReviews) > 0)
			{
				$appPost->appReview = $appReviews[0];
			}


			$logger->debug("Found appPost " . $appPost->toString());
			
			$table[$i] = $appPost;

			$i++;
		}

		mysql_free_result($result);
		return $table;
	}

	public static function queryAppInfosByName($dbHandle, $name)
	{
		$name = mysql_real_escape_string($name);
		$query = "select * from AppInfo where app_name like '%$name%';";
		return self::queryAppInfo($dbHandle, $query);

	}

	
	public static function queryAppInfoByName($dbHandle, $name)
	{
		$name = mysql_real_escape_string($name);
		$query = "select * from AppInfo where app_name='$name';";
		return self::queryAppInfo($dbHandle, $query);

	}
	
	public static function queryAppInfoByUrl($dbHandle, $url)
	{
		$url = mysql_real_escape_string($url);
		$query = "select * from AppInfo where orig_link like '%$url%';";
		return self::queryAppInfo($dbHandle, $query);

	}
	
	public static function queryAppInfoById($dbHandle, $id)
	{
		$id = mysql_real_escape_string($id);
		$query = "select * from AppInfo where id=$id;";
		return self::queryAppInfo($dbHandle, $query);

	}

	public static function queryAppReviewsByName($dbHandle, $name)
	{
		$name = mysql_real_escape_string($name);
		$query = "select * from AppReviews where app_name like '%$name%';";
		return self::queryAppReviews($dbHandle, $query);
	}
	public static function queryAppReviewsById($dbHandle, $id)
	{
		$id = mysql_real_escape_string($id);
		$query = "select * from AppReviews where id=$id;";
		return self::queryAppReviews($dbHandle, $query);

	}

	public static function queryAppInfo($dbHandle, $query)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$logger->debug('Calling query ' . $query);
		$result = mysql_query($query, $dbHandle);
		if (!$result) {
			$logger->error('Invalid query: ' . mysql_error());
			return null;
		}
		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}

		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$appInfo = new AppInfo();
			$appInfo->appName = $row['app_name'];
			$appInfo->appSeller = $row['seller'];
			$appInfo->id = $row['id'];
			$appInfo->appExternalId = $row['app_external_id'];
			$appInfo->releaseDate = $row['release_date'];
			$appInfo->price = $row['price'];
			$appInfo->originalLink = $row['orig_link'];
			$appInfo->imageUrl = $row['img_url'];
			$appInfo->requirements = $row['requirements'];
			$appInfo->genre = $row['genre'];
			$appInfo->appRating = $row['app_rating'];
			$appInfo->screenshots = $row['screenshots'];
			$appInfo->language = $row['language'];
			$appInfo->createdAt = $row['created_at'];
			$appInfo->updatedAt = $row['updated_at'];
			$appInfo->description = $row['description'];
			$logger->debug($appInfo->toString());
			$appInfo->clean();
			$table[$i] = $appInfo;

			$i++;
		}

		mysql_free_result($result);
		return $table;
	}


	public static function insertAppReview($dbHandle, $appId, $appName, $reviewer, $reviewTitle, $review)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$review = mysql_real_escape_string($review);
		$query = "insert into AppReviews(app_id, app_name, title, review, reviewer, created_at) values ('$appId', '$appName', '$reviewTitle', '$review', '$reviewer', CURDATE());" ;
		$logger->debug('Calling query ' . $query);
		if(mysql_query($query, $dbHandle))
		  	return true;
		return false;
	}

	public static function insertAppLineRecord($dbHandle, $appId, $appReviewId, $appName, $onDate)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$query = "insert into AppLine(app_id, app_review_id, app_name,on_date, created_at, updated_at ) values ('$appId', '$appReviewId', '$appName', '$onDate', CURDATE(), CURDATE());" ;
		$logger->debug('Calling query ' . $query);
		if(mysql_query($query, $dbHandle))
		  	return true;
		return false;
		

	}


	public static function queryAppReviews($dbHandle, $query)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$logger->debug('Calling query ' . $query);
		$result = mysql_query($query, $dbHandle);

		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}
		$count = mysql_num_rows($result);
		$numOfFields = mysql_num_fields($result);
		//echo "$count<br>";
		//echo "$numOfFields<br>";

		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$appReview = new AppReview();
			$appReview->appName = $row['app_name'];
			$appReview->appId = $row['app_id'];
			$appReview->id = $row['id'];
			$appReview->title = $row['title'];
			$appReview->review = $row['review'];
			$appReview->reviewer = $row['reviewer'];
			$appReview->createdAt = $row['created_at'];
			$logger->debug($appReview->toString());
			$table[$i] = $appReview;

			$i++;
		}

		mysql_free_result($result);
		return $table;


	}

	public static function getAppRecommendations ($dbHandle, $appId)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$query = "select a.* from AppInfo a, AppReco reco where reco.recommended_app_id = a.id and reco.app_id=$appId  order by reco.recommended_app_rank";
		$logger->debug('Calling query ' . $query);
		$result = mysql_query($query, $dbHandle);

		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}

		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$appInfo = new AppInfo();
			$appInfo->appName = $row['app_name'];
			$appInfo->appSeller = $row['seller'];
			$appInfo->id = $row['id'];
			$appInfo->appExternalId = $row['app_external_id'];
			$appInfo->releaseDate = $row['release_date'];
			$appInfo->price = $row['price'];
			$appInfo->originalLink = $row['orig_link'];
			$appInfo->imageUrl = $row['img_url'];
			$appInfo->requirements = $row['requirements'];
			$appInfo->genre = $row['genre'];
			$appInfo->appRating = $row['app_rating'];
			$appInfo->screenshots = $row['screenshots'];
			$appInfo->language = $row['language'];
			$appInfo->createdAt = $row['created_at'];
			$appInfo->updatedAt = $row['updated_at'];
			$appInfo->description = $row['description'];
			$logger->debug($appInfo->toString());
			$appInfo->clean();
			$table[$i] = $appInfo;
			$i++;
		}

		mysql_free_result($result);
		return $table;


	}

	////////////////////User Info ///////////////////////////////
	public static function addUserInfo($dbHandle, $authId, $authType, $name)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$query = "insert into UserInfo(auth_id, auth_type, name, created_at)  values('$authId', '$authType', '$name', NOW())";
		$logger->debug("Executing query" .$query);
		$result = mysql_query($query, $dbHandle);
		if (!$result) {
			$logger->error('Invalid query: ' . mysql_error());
			return false;
		}
		return true;
	}

	public static function getUserInfoByAuth($dbHandle, $authId, $authType)
	{
		$query = "select * from UserInfo where auth_id='$authId' and auth_type='$authType'";
		$result = self::getUserInfo($dbHandle, $query);
		if(count($result) > 0)
			return $result[0];
		return null;
	}
	
	public static function getUserInfoById($dbHandle, $userId)
	{
		$query = "select * from UserInfo where id=$id";
		$result = self::getUserInfo($dbHandle, $query);
		if(count($result) > 0)
			return $result[0];
		return null;
	}

	public static function getUserInfo($dbHandle, $query)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$logger->debug('Calling query ' . $query);
		$result = mysql_query($query, $dbHandle);
		if (!$result) {
			$logger->error('Invalid query: ' . mysql_error());
			return null;
		}
		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}
		$i = 0;	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$table[$i] = $row;
			$i++;
		}
		mysql_free_result($result);
		return $table;

	}


	////////////////////////////////UserAppInfo ////////////////////////
	
	public static function updateUserAppInfo($dbHandle, $jsonObj)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$fbuid = $jsonObj->{'fbuid'};
		$fbname = $jsonObj->{'fbname'};
		$userid = $jsonObj->{'userid'};

		$logger->debug("updating apps for user ". $fbuid . " " . $fbname );
		$installedApps = $jsonObj->{'installedApps'};
		$installedAppMap = array();
		foreach ($installedApps as $k => $v)
		{
			$installedAppMap[$v->{'itemId'} ] = $v;
			$v->already_in_db = 0;
		}
		$appsInDB = self::getUserAppInfo($dbHandle, $fbuid);
		$logger->debug("User" . $fbuid .'('.$fbname.')'.'has ' .count($appsInDB) . ' apps installed');
		foreach ($appsInDB as &$dbapp) {
			$appid = $dbapp['app_external_id'];
			if(array_key_exists($appid, $installedAppMap)) {
				$logger->debug("App found installed" . $dbapp['app_name']);
				$installedAppMap[$appid]->already_in_db = 1;
				//TODO: check apple id compatibility
			}
			else
			{
				$dbapp['present_now'] = 0;
				//TODO: update present_not back in the database
			}
		}
		
		$fbuid = mysql_real_escape_string($fbuid);
		foreach ($installedAppMap as $k => $v)
		{
			if($v->already_in_db != 1)
			{
				$v->AppleID = mysql_real_escape_string($v->AppleID);
				$v->itemId = mysql_real_escape_string ($v->itemId);
				$v->itemName = mysql_real_escape_string ($v->itemName);
				$v->purchaseDate = mysql_real_escape_string ($v->purchaseDate);


				$query = "insert into UserAppInfo (user_id, apple_id, app_external_id, app_name, purchased_date, present_now, verification_status, verified_date, updated_at, created_at)  values ('$userid', '$v->AppleID', '$v->itemId', '$v->itemName', '$v->purchaseDate', 1,0,NOW(), NOW(), NOW())
				";
				$logger->debug("executing query ". $query);
				$result = mysql_query($query, $dbHandle);
				if (!$result) {
					$logger->error('Invalid query: ' . mysql_error());
				}
			} else {
				$logger->debug("Skipping app as it was already found in db for this user" . $v->itemName);
			}

		}
		
		



	}

	public static function getUserAppInfo($dbHandle, $fbuid)
	{
		$fbuid = mysql_real_escape_string($fbuid);
		$query = "select * from UserAppInfo where fbuid = '$fbuid'";
		return self::queryUserAppInfo($dbHandle, $query);
	}

	public static function queryUserAppInfo($dbHandle, $query)
	{
		$logger = AppLogger::getInstance()->getLogger();
		$logger->debug('Calling query ' . $query);
		$result = mysql_query($query, $dbHandle);
		if (!$result) {
			$logger->error('Invalid query: ' . mysql_error());
			return null;
		}
		$table = array();
		if(!isset($result)) {
			$logger->debug("Returned zero results for query: $query");
			return $table;
		}
		$i = 0;	
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$table[$i] = $row;
			$i++;
		}
		mysql_free_result($result);
		return $table;
	}
}
