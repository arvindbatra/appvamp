<?php 


class DatabaseHandler
{

	private static $instance;
	private $myLogger;
	protected $success;
	protected $_dbHandle;

	private function __construct()
	{
		$this->myLogger = AppLogger::getInstance()->getLogger();
		$this->success = $this->connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME); 
		if($this->success)
		{
			 $this->myLogger->info('Database connection initialized');
		}
		else
		{
			$this->myLogger->error("Could not connect to database<br>");
		}
	}

	public function  __destruct ()
	{
	  echo "Destructor DB Handler called <br>\n";
		@mysql_close($this->_dbHandle);
	}

	private function connect($address, $account, $pwd, $name)
	{
		$this->_dbHandle = @mysql_connect($address, $account, $pwd);
		if ($this->_dbHandle != 0) 
		{
			if(mysql_select_db($name, $this->_dbHandle))
			{
				return true;
			}
		  	else
				return false;
		}
		else
			echo "DB handle was 0 ". mysql_error();
	}


	public static function getInstance()
	{
	 	echo "inside get instance<br>";
		if(!isset(self::$instance)) {
		  $c = __CLASS__;
		  self::$instance  = new $c;
		}
		return self::$instance;
	}

	public function getHandle()
	{
		return $this->_dbHandle;
	}

	public function __clone()
	{
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}


}
