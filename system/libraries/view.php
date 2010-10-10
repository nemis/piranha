<?php defined('INIT') or die('No direct access allowed.');

class View
{
	private $file_name;
	private $base_file;
	
	private $vars = array();
	
	static public function load($filename, $vars=array())
	{
		return new View($filename, $vars);
	}
	
	public function __construct($filename, $vars=false)
	{
		$this->init($filename, $vars);
	}
	
	public function init($file_name, $vars=false)
	{
		$this->base_file = $file_name;
		if (file_exists($f = 'application/views/'.$file_name.'.php'))
			$this->file_name = $f;
		elseif (file_exists($f = $file_name.'.php'))
			$this->file_name = $f;
		elseif (file_exists($f = 'system/templates/views/'.$file_name.'.php'))
			$this->file_name = $f;
		else
			System::view_error($f);
			
		if (is_array($vars))
			$this->vars = $vars;
	}
	
	public function __set($name, $var)
	{
		if (!isset($this->$name))
			$this->vars[$name] = $var;
	}
	
	public function __toString()
	{
		return $this->render();
	}
	
	public function render()
	{
		if (!defined(BASE_URL))
			define(BASE_URL, '');

		extract($this->vars);
		$f = $this->file_name;
		if (file_exists($f))
		{
			ob_start(); 
			include($f);
			$return = ob_get_clean();
		} else {
			System::view_error($file);
		}
		
		return $return;
	}
}