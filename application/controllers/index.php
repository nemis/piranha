<?php

class Index_Controller extends Controller
{
	protected $template_file = 'index_test';
	
	public function index()
	{
		$this->template->test = 'test ok!';
	}
	
	public function test($test_text)
	{
		$model = Model::load('test', 1);
		$model->name = 'zmiana nazwy';
		$model->save();
		$this->template->test = $model->name;
	}
}