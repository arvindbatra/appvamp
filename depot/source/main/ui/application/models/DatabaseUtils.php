<?php

class DatabaseUtils 
{
	public static function queryAllAppInfo($dbHandle)
	{
		$query = "select * from AppInfo;";
		$result = mysql_query($query, $dbHandle);
		$count = mysql_num_rows($result);
		$numOfFields = mysql_num_fields($result);
		echo "$count<br>";
		echo "$numOfFields<br>";

		$table = array();

		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$appInfo = new AppInfo();
			$appInfo->appName = $row['app_name'];
			$appInfo->appSeller = $row['seller'];
			$table[$i] = $appInfo;

			$i++;
		}

		mysql_free_result($result);
		return $table;
	}
}
