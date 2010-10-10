<?php defined('INIT') or die('No direct access allowed.');

class Model
{
	protected $bean;
	protected $table_name;
	protected $id = 0;
	
	public function __construct($id=false)
	{
		if (empty($name))
		{
			$name = str_replace('_Model', '', get_called_class());
			$name = strtolower($name);
			$this->init($name, $id);
		}
		
		if ($id)
		{
			$this->id = $id;
		}
	}
	
	public function load($name, $id)
	{
		$model = new Model();
		$model->init($name, $id);
		return $model;
	}
	
	public function init($name, $id=false)
	{
		$this->table_name = $name;
		
		if ($id)
		{
			$this->bean = R::load($name, $id );
		} else {
			$this->bean = R::dispense($name);
		}
	}
	
	public function save()
	{
		R::store($this->bean);
	}
	
	public function __get($name)
	{
		if (!isset($this->name))
			return $this->bean->name;
	}
	
	public function __set($name, $value)
	{
		if (!isset($this->name))
			return $this->bean->name = $value;
	}
	
	public function __call($name, $args)
	{
		if (!is_function(array($this, $name)))
		{
			$s = 'return $this->bean->$name('.implode(', ', $args).')';
			eval($s);
		}
	}
}