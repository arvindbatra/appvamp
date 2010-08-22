<?php



class Template 
{
	protected $variables = array();

	function __construct() 
	{
	
	}

	function set($name, $value)
	{
		$this->variables[$name] = $value;
	}

	function render()
	{
		$this->loadThemes();
	

	}



	function loadThemes()
	{
		extract ($this->variables);
		if(!empty($themes)) 
		{
		  	echo "theme_$themes<br>";
			$themeArr = explode(",", $themes);
			for($i = 0; $i < count($themeArr); $i++)
			{
				$themeName = $themeArr[$i];

				if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'header.php')) {
					include (ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'header.php');
				}
				if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'index.php')) {
				        include (ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'index.php');		 
				}
				if (file_exists(ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'footer.php')) {
					include (ROOT . DS . 'application' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'footer.php');
				}
			}
		}


	}



}
