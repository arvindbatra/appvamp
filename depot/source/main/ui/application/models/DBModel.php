<?php

require_once('DatabaseHandler.php');
require_once('DatabaseUtils.php');

class DBModel extends BaseModel
{
	
	function __construct()
	{
		$handler = DatabaseHandler::getInstance();

	}

	function __destruct()
	{
		

	}

};
