<?php


class Properties
{
	private $vars = array();
	public  function __set($index, $value)
	{
           $this->vars[$index] = $value;
	}
	public function __get($index)
	{
	  return $this->vars[$index];
	}
}

echo "properties are loaded\n";
