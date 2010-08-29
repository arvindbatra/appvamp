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
		  	//echo "theme_$themes<br>";
			$themeArr = explode(",", $themes);
			for($i = 0; $i < count($themeArr); $i++)
			{
				$themeName = $themeArr[$i];
				if($i ==0 )
				{
					define ('TEMPLATEPATH', ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName);

				}
				if (file_exists(ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'functions.php')) {
					include (ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'functions.php');
				}
				if (file_exists(ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'header.php')) {
					include (ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'header.php');
				}
				if (file_exists(ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'index.php')) {
					include (ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'index.php');		 
				}
				if (file_exists(ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'footer.php')) {
					include (ROOT . DS . 'public' . DS . 'views' . DS . 'themes' . DS . $themeName . DS . 'footer.php');
				}
			}
		}


	}



}
