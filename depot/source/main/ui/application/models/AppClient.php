<?php


require_once $GLOBALS['THRIFT_ROOT'].'/Thrift.php';
require_once $GLOBALS['THRIFT_ROOT'].'/protocol/TBinaryProtocol.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TSocket.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/THttpClient.php';
require_once $GLOBALS['THRIFT_ROOT'].'/transport/TBufferedTransport.php';
$GEN_DIR = ROOT . DS . 'application' . DS . 'models' . DS . 'gen-php';
require_once ($GEN_DIR.'/interface/AppServer.php');


class AppClient
{
	private $myLogger;
	private $socket;
	private $transport;
	private $protocol;
	private $client;

	public function __construct()
	{
		$this->myLogger = AppLogger::getInstance()->getLogger();
		$this->connect();

	}


	private function connect()
	{
		try {
			$this->socket = new TSocket(APP_SERVER_HOST, APP_SERVER_PORT);
			$this->transport = new TBufferedTransport($this->socket, 1024, 1024);
			$this->protocol = new TBinaryProtocol($this->transport);
			$this->client = new AppServerClient($this->protocol);

  			$this->transport->open();
		} catch (TException $tx) {
			 $this->myLogger->error( 'TException: '.$tx->getMessage()."\n");
		}
	}


	
		
	public function queryServer($qpacket)
	{
		try {
		  	if(isset($this->client))
			{
				$response = $this->client->query($qpacket);
			  	$this->myLogger->debug( $response);
				return $response;
			}
			else
			{
			 	 $this->myLogger->info("client is not set, returning empty())");
				  return "";
			}
		} catch (TException $tx) {
			$this->myLogger->error( 'TException: '.$tx->getMessage()."\n");
		}
		return "";
	}

	public function __destruct()
	{
	  	try {
			  $this->transport->close();
		} catch (TException $tx) {
			$this->myLogger->error( 'TException: '.$tx->getMessage()."\n");
		}
	}



} //end class
