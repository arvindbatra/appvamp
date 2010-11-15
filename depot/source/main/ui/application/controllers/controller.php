<?php

include (ROOT . DS . 'application' . DS . 'controllers' . DS .  'utils.php');		 

class Controller {
	protected $_conttollerName;
	protected $_actionName;
	protected $_template;
	protected $_model;
	protected $logger;

	function __construct($qpacket)
	{
		$this->logger = AppLogger::getInstance()->getLogger();
	  	if(isset($packet['controllerName']))
			$this->$_controllerName = $qpacket['controllerName'];

		//The way i understand MVC. 
		//both model and template are initialized with qpacket [Qpacket is a static const packet]
		//controller will call model with whatever actions the controller has and will then call set function with some key and value= the result from model.
		//the set function internally sets it on to template's map.
		
		//DB connection or connection to appServer etc is the business of model. 
		//Controller on the other hand has all the freedom in parsing the data that comes back from model.
		//Question: Not clear if two database reads are needed, should we call model twice, or we call model once and let model return data in more interesting manner. 

	

		//$this->$_model = new AppProcessor($qpacket);
		$this->_template = new Template ();

		foreach($qpacket as $key => &$val) {
			$this->set($key,$val);
		}
		$themeName = 'appvamp';
		$this->set('themes', $themeName);
		$this->set('themeDir',  'views' . DS . 'themes' . DS . $themeName );
		$this->set('viewMode','app-data');
		$plugins = '';
		if(!empty($plugins))
			$this->loadPlugins($plugins);

	}

	function view()
	{
	  	echo "Inside view action<br>";
		//$this->_template->render();
	}


	function set($name, $value) {
	 	$this->_template->set($name, $value);
	}

	function get($key, $defaultValue)
	{
	 	return $this->_template->get($key, $defaultValue);
	}
	
	function __destruct() { 
		$this->_template->render();

	}





	function loadPlugins($plugins)
	{
		if(!empty($plugins)) 
		{
		  	echo "plugin_$plugins<br>";
			$pluginArr = explode(",", $plugins);
			for($i = 0; $i < count($pluginArr); $i++)
			{
				echo "arv_$pluginArr[$i]<br>";
				$pluginName = $pluginArr[$i];

				if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . 'plugins' . DS . $pluginName . DS . 'index.php')) {
				        include (ROOT . DS . 'application' . DS . 'controllers' . DS . 'plugins' . DS .  $pluginName . DS . 'index.php');		 
				}
			}
		}
	}
}

