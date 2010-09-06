<?php

class DatabaseUtils 
{
	public static function queryAllAppInfo($dbHandle)
	{
		$query = "select * from AppInfo;";
		return self::queryAppInfo($dbHandle, $query);
	}
	
	public static function getPostData($dbHandle, $appName)
	{
		$query = "select * from AppLine where app_name='$appName';";
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

	
	public static function queryAppInfoByName($dbHandle, $name)
	{
		$query = "select * from AppInfo where app_name='$name';";
		return self::queryAppInfo($dbHandle, $query);

	}
	
	public static function queryAppInfoByUrl($dbHandle, $url)
	{
		$query = "select * from AppInfo where orig_link like '%$url%';";
		return self::queryAppInfo($dbHandle, $query);

	}
	
	public static function queryAppInfoById($dbHandle, $id)
	{
		$query = "select * from AppInfo where id=$id;";
		return self::queryAppInfo($dbHandle, $query);

	}
	
	public static function queryAppReviewsById($dbHandle, $id)
	{
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



}
