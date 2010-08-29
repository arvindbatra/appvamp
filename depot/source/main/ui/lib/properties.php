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
	public function exists($index)
	{
		if(array_key_exists($index, $this->vars))
				return true;
		return false;
	}

}

