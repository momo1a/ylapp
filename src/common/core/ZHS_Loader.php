<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class ZHS_Loader extends CI_Loader
{
	/**
	 * Database Loader
	 * 
	 * 修改：从COMPATH公用文件夹中获取自定义的DB类
	 *
	 * @param	string	the DB credentials
	 * @param	bool	whether to return the DB object
	 * @param	bool	whether to enable active record (this allows us to override the config setting)
	 * @return	object
	 */
	public function database($params = '', $return = FALSE, $active_record = NULL)
	{
		// Grab the super object
		$CI =& get_instance();
	
		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db))
		{
			return FALSE;
		}
	
		// 从COMPATH公用文件夹中获取自定义的DB类
		require_once(COMPATH.'database/DB.php');
	
		if ($return === TRUE)
		{
			return DB($params, $active_record);
		}
	
		// Initialize the db variable.  Needed to prevent
		// reference errors with some configurations
		$CI->db = '';
	
		// Load the DB class
		$CI->db =& DB($params, $active_record);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Model Loader
	 *
	 * This function lets users load and instantiate models.
	 * 
	 * 在加载model之前,先判断一下该model是否已被自动加载(application_helper.php/__autoload),
	 * 如果model已自动加载,则不再滤遍_ci_model_paths进行导入model文件
	 *
	 * @param	string	the name of the class
	 * @param	string	name for the model
	 * @param	bool	database connection
	 * @return	void
	 */
	public function model($model, $name = '', $db_conn = FALSE)
	{
		if (is_array($model))
		{
			foreach ($model as $babe)
			{
				$this->model($babe);
			}
			return;
		}
	
		if ($model == '')
		{
			return;
		}
	
		$path = '';
	
		// Is the model in a sub-folder? If so, parse out the filename and path.
		if (($last_slash = strrpos($model, '/')) !== FALSE)
		{
			// The path is in front of the last slash
			$path = substr($model, 0, $last_slash + 1);
	
			// And the model name behind it
			$model = substr($model, $last_slash + 1);
		}
	
		if ($name == '')
		{
			$name = $model;
		}
	
		if (in_array($name, $this->_ci_models, TRUE))
		{
			return;
		}
	
		$CI =& get_instance();
		if (isset($CI->$name))
		{
			show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		}
	
		$model = strtolower($model);
		
		//---------------修改部分--------------------------
		$autoload_model = ucfirst($model);

		if (class_exists($autoload_model, FALSE))
		{
			// 自动加载时，没有进行new
			$CI->$name = new $autoload_model();
			
			$this->_ci_models[] = $name;
			return;
		}
		
		krsort($this->_ci_model_paths); // 将路径倒序，从APPPATH开始获取model
		//-------------end 修改部分------------------------
	
		foreach ($this->_ci_model_paths as $mod_path)
		{
			if ( ! file_exists($mod_path.'models/'.$path.$model.'.php'))
			{
				continue;
			}
	
			if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
			{
				if ($db_conn === TRUE)
				{
					$db_conn = '';
				}
	
				$CI->load->database($db_conn, FALSE, TRUE);
			}
	
			if ( ! class_exists('CI_Model'))
			{
				load_class('Model', 'core');
			}
	
			require_once($mod_path.'models/'.$path.$model.'.php');
	
			$model = ucfirst($model);
	
			$CI->$name = new $model();
	
			$this->_ci_models[] = $name;
			return;
		}
	
		// couldn't find the model
		show_error('Unable to locate the model you have specified: '.$model);
	}
}