<?php defined('INIT') or die('No direct access allowed.');

class System
{
	static private $current_uri;
	
	static public function init()
	{
		// Send default text/html UTF-8 header
		header('Content-Type: text/html; charset=UTF-8');
		// start session
		session_start();
		if (file_exists($file = 'application/config/base.php')) require_once($file);
		spl_autoload_register(array('System', 'auto_load'));
		
		// set error handler and exceptions handler
		//set_error_handler(array('System', 'system_error'));
		//set_exception_handler(array('System', 'system_error'));
		
		if (file_exists($file = 'application/config/database.php'))
		{
			require_once($file);
			self::init_db();
		}
	}
	
	static public function init_db()
	{
		// load database system (redbean)
		require_once("system/libraries/rb.php");
		
		$connection_string = 'mysql:dbname='.DB_NAME.';host='.DB_HOST;
		
		R::setup($connection_string, DB_USER, DB_PASSWORD);
		R::debug(true);
	}
	
	static public function auto_load($class)
	{
		if (($suffix = strrpos($class, '_')) > 0)
		{
			$suffix = substr($class, $suffix + 1);
		}
		else
		{
			$suffix = FALSE;
		}

		if (!$suffix)
		{
			$search_dirs = array(
				'system/libraries/',
				'system/single/',
			);
			foreach ($search_dirs as $dir)
			{
				if (file_exists($d = $dir.strtolower($class).'.php'))
				{
					require_once($d);
					return true;
				}
			}
		} else {
			$application_dirs = array(
				'controller' => 'controllers',
				'helper' => 'helpers',
				'view' => 'views',
				'model' => 'models',
			);
			
			$class_without_suffix = str_replace('_'.$suffix, '', $class);
			$app_dir = $application_dirs[strtolower($suffix)];
			$d = 'application/'.$app_dir.'/'.strtolower($class_without_suffix).'.php';
			if (file_exists($d))
			{
				require_once($d);
				return true;
			}
		}
		
		return false;
	}
	
	static public function run()
	{
		if (isset($_SERVER['PATH_INFO']) AND $_SERVER['PATH_INFO'])
		{
			self::$current_uri = $_SERVER['PATH_INFO'];
		}
		elseif (isset($_SERVER['ORIG_PATH_INFO']) AND $_SERVER['ORIG_PATH_INFO'])
		{
			self::$current_uri = $_SERVER['ORIG_PATH_INFO'];
		}
		elseif (isset($_SERVER['PHP_SELF']) AND $_SERVER['PHP_SELF'])
		{
			self::$current_uri = $_SERVER['PHP_SELF'];
		}
		$query = self::$current_uri;
		//$query = explode('index.php', $query);
		//if (count($query) == 1) return self::run_controller();
		
		$query = str_replace('index.php', '', $query);
		
		if (strpos($query, '?') > 0)
			$query = substr($query, 0, strpos($query, '?'));
		
		if (empty($query))
			return self::run_controller();

		$query = explode('/', $query);
		if (isset($query[0]) and ($query[0] == '')) array_shift($query);
		if (count($query) == 2)
		{
			self::run_controller($query[0], $query[1]);
		} elseif (count($query) == 1) {
			self::run_controller($query[0]);
		} elseif (count($query > 2)) {
			$controller = array_shift($query);
			$page = array_shift($query);
			$vars = $query;
			return self::run_controller($controller, $page, $vars);
		}
		return false;
	}
	
	static public function run_controller($controller='index', $page='index', $vars=array())
	{
		$controller = ucfirst($controller).'_Controller';
		$controller = new $controller;
		call_user_func_array(array($controller, $page), $vars);
	}
	
	static public function view_error($file_name)
	{
		return self::template_error($file_name);
	}
	
	static public function template_error($file_name)
	{
		die('Template file not found: '.$file_name);
	}
	
	static public function error($error)
	{
		die($error);
		// disabled
		$error_view = new View('system/templates/views/error');
		$error_view->error = $error;
		die($error_view);
	}
	
	static public function system_error($errno, $errstr=false, $errfile=false, $errline=false)
	{
		$error_view = new View('system/templates/views/error');
		$error_view->error = $errstr;
		$error_view->errno = $errno;
		$error_view->errfile = $errfile;
		$error_view->errline = $errline;
		die($error_view);
	}
}