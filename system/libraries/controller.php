<?php defined('INIT') or die('No direct access allowed.');

class Controller
{
	protected $template_file;
	protected $template;
	
	public function __construct()
	{
		$this->template = View::load($this->template_file);
	}
	
	public function __destruct()
	{
		if (defined('AUTO_HEADER'))
		{
			if (AUTO_HEADER)
			{
				$this->template = 
					View::load('html_header')
					.
					$this->template
					.
					View::load('html_footer');
			}
		}
		echo $this->template;
	}
}