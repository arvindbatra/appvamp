<?php

require_once (ROOT . DS . 'lib' . DS . 'log4php' . DS . 'Logger.php');
 
class AppLogger {
	protected  $logger;
	private static $instance;

	private function __construct() {
		$this->logger = Logger::getRootLogger();
		//TODO: make appender work
		//$appender = $this->logger->getAppender('R');
		//$appender->setFile('apper.log');
		//echo $appender->getFile();
	}
							      
	public function doSomething() {
		$this->logger->info("Entering application.");
		$this->logger->info("Exiting application.");
	
	}


	public static function getInstance() 
	{

	   if(!isset(self::$instance)) 
	   {
			$c = __CLASS__;
			self::$instance  = new $c;

		}
		return self::$instance;
	}

	public function getLogger()
	{
	 	return $this->logger;
	}



			 



}





// Set up a simple configuration that logs on the console.
Logger::configure(ROOT . DS . 'config' . DS . 'configuration.properties');

 AppLogger::getInstance();
 

