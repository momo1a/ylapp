<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Base Model
 *
 * model的基本实现,提供一些model的标准和规范,实现了一些基础通用的操作.
 *
 * @package		Core
 * @subpackage	MY_Model
 * @category	Models
 * @author		momo1a@qq.com
 */
class ZHS_Model extends CI_Model
{

	/**
	 * 用于存储错误信息.
	 *
	 * @var string
	 * @access public
	 */
	public $error = '';

	/**
	 * 表名
	 *
	 * @var string
	 * @access protected
	 */
	public static $table_name = '';

	/**
	 * 数据表主键,如果没有默认使用"id".
	 *
	 * @var string
	 * @access protected
	 */
	protected $key = 'id';

	/**
	 * 数据库连接配置 (string or array)
	 *
	 * @var mixed
	 * @access protected
	 */
	protected $db_con = '';

    /**
     * 主验证规则.
     * 
     * @var ArrayObject
     */
    protected $validation_rules = array();

    /**
     * @var array 仅用于插入的额外验证规则
     */
    protected $insert_validation_rules = array();

    /**
     * 是否跳过数据验证. 
     * 默认跳过,可以调用skip_validation()进行设置.
     */
    protected $skip_validation = TRUE;

    /**
     * 是否返回最后新增的ID
     * 
     * @var bool
     */
    protected $return_insert_id = FALSE;

    //--------------------------------------------------------------------

	/**
	 * MY_Model constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->ini_dbcon();
	}
	
	//---------------------------------------------------------------
	
	public function ini_dbcon()
	{
		// 另外设置的数据库连接
		if ( ! empty($this->db_con))
		{
			$this->db = $this->load->database($this->db_con, TRUE);
		}
		
		if ( ! isset($this->db))
		{
			$this->load->database();
		}
	}
	
	//---------------------------------------------------------------

	/**
	 * 根据主键查询一条数据.
	 * 
	 * 必须保证主键key已经设置
	 *
	 * @param string $id 主键记录.
	 *
	 * @return array OR FALSE.
	 */
	public function find($id)
	{
			$query = $this->db->get_where(static::$table_name, array(static::$table_name . '.' . $this->key => $id));

		
		if ( ! $query->num_rows())
		{
			return FALSE;
		}

		return $query->row_array();
		
	}//end find()

	//---------------------------------------------------------------

	/**
	 * 从数据表查询多条数据.
	 *
	 * @return array OR FALSE.
	 */
	public function find_all()
	{
		$query = $this->db->get(static::$table_name);

		if (!$query->num_rows())
		{
			return FALSE;
		}

		return $query->result_array();
		
	}//end find_all()
	
	//---------------------------------------------------------------

	/**
	 * 根据给定的条件查询多条数据.
	 *
	 * @param mixed  $field	查询的字段也是包含字段和查询值的数组.
	 * @param mixed  $value (可选)查询的条件值.
	 * @param string $type  条件类型 'and' or 'or'.
	 *
	 * @return array OR FALSE.
	 */
	public function find_all_by($field, $value = NULL, $type = 'and')
	{
		// 设置为数组
		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if (strtolower($type) == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		return $this->find_all();
		
	}//end find_all_by()

	//--------------------------------------------------------------------

	/**
	 * 根据条件获取符合条件的第一条数据.
	 *
	 * @param mixed  $field	查询的字段也是包含字段和查询值的数组.
	 * @param mixed  $value (可选)查询的条件值.
	 * @param string $type  条件类型 'and' or 'or'.
	 *
	 * @return array OR FALSE.
	 */
	public function find_by($field, $value = '', $type = 'and')
	{
		if (empty($field) || ( ! is_array($field) && empty($value)))
		{
			$this->error = '没有足够的条件查询数据';
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] ' . $this->error);
			return FALSE;
		}
		
		if ( ! is_array($field))
		{
			$field = array($field => $value);
		}

		if (strtolower($type) == 'or')
		{
			$this->db->or_where($field);
		}
		else
		{
			$this->db->where($field);
		}

		$query = $this->db->get(static::$table_name);

		if ( ! $query->num_rows())
		{
			return FALSE;
		}
		// 返回第一条数据
		return $query->row_array();
		
	}//end find_by()
	
	//---------------------------------------------------------------
	
	/**
	 * 返回sql执行的结果(1条).
	 * @author 宁天友
	 * @version 2015-5-14 9:38:17
	 * @param string  $sql	查询的字段也是包含字段和查询值的数组.
	 * @param array  $params (可选)查询的条件值.
	 *
	 * @return array.
	 */
	public function row_query($sql, $params = array() ,$reconnect = FALSE )
	{
		if (is_string($sql) && trim($sql) != '')
		{
			$reconnect === TRUE AND $this->db->reconnect();
			$query = $this->db->query($sql, $params);
			$row = $query->row_array();
			$query->free_result();
			$this->db->close();
			return $row;
		}
		return array();
	
	}//end result_query()
	
	//---------------------------------------------------------------
	
	/**
	 * 返回表所有字段
	 * @author 宁天友
	 * @version 2015-5-14 9:38:17
	 * @param string $table 表名
	 *
	 * @return array.
	 */
    public function list_fields($table='') 
    {
    	return $this->db->list_fields($table == '' ? static::$table_name : $table);
    }//end list_fields()
	
	//---------------------------------------------------------------

	/**
	 * 向数据库新增一条数据.
	 *
	 * @param array $data 用于新增到数据库的数据.
	 *
	 * @return bool|mixed 新增的id or FALSE.
	 */
	public function insert($data)
	{
		// 不跳过数据验证
		if ($this->skip_validation === FALSE)
		{
		    $data = $this->validate($data, 'insert');
		    // 数据验证失败
            if ($data === FALSE)
            {
                return FALSE;
            }
		}
		// 前置操作
		$data = $this->trigger('before_insert', $data);

		// Insert it
		$status = $this->db->insert(static::$table_name, $data);

		if ($status == FALSE)
		{
			$this->error = $this->get_db_error_message();
        }
        elseif ($this->return_insert_id)
        {
            $id = $this->db->insert_id();

            $status = $this->trigger('after_insert', $id);
        }

        return $status;
        
	}//end insert()

	//---------------------------------------------------------------

	/**
	 * 批量新增数据.
	 *
	 * @param array $data 用于新增的数组.
	 *
	 * @return bool
	 */
	public function insert_batch($data)
	{
		foreach ($data as $key => &$record)
		{
			$record = $this->trigger('before_insert', $record);
		}

		// Insert it
		$status = $this->db->insert_batch(static::$table_name, $data);

		if ($status === FALSE)
		{
			$this->error = $this->get_db_error_message();
			return FALSE;
		}
		
		return TRUE;
		
	}//end insert_batch()

	//---------------------------------------------------------------

	/**
	 * 更新数据.
	 *
	 * @param mixed	$where	如果是条件不是数组则为是基于primary_key为条件更新数据.
	 * @param array $data	更新的数据.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update($where, $data)
	{
		// 数据验证
		if ($this->skip_validation === FALSE)
		{
		    $data = $this->validate($data);
		    
		    if ($data === FALSE)
		    {
				return FALSE;
            }
		}

		if ( ! is_array($where))
		{
			$where = array($this->key => $where);
		}

		$data = $this->trigger('before_update', $data);
		
		$result = $this->db->update(static::$table_name, $data, $where);
		
		if ($result)
		{
			$this->trigger('after_update', array($data, $result));
			return TRUE;
		}

		return FALSE;
		
	}//end update()

	//---------------------------------------------------------------

	/**
	 * 根据指定的字段为条件更新数据.
	 *
	 * @param string $field 指定的字段.
	 * @param string $value 比配的值.
	 * @param array  $data  更新的数据.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_where($field, $value, $data)
	{
		return $this->update(array($field => $value), $data);
	}//end update_where()

	//---------------------------------------------------------------

	/**
	 * 批量更新数据.
	 *
	 * @param array  $data  更新的数据.
	 * @param string $index 键名
	 * @see CI_DB_active_record->update_batch()
	 *
	 * @return bool TRUE/FALSE
	 */
	public function update_batch($data, $index)
	{
        $result = $this->db->update_batch(static::$table_name, $data, $index);

        return empty($result) ? TRUE : FALSE;
        
	}//end update_batch()
	
	//--------------------------------------------------------------------
	
	/**
	 * 删除数据.
	 *
	 * @example $this->model->delete(1);
	 * @example $this->model->where('key', 'value')->delete();
	 * 
	 * @param mixed $id (可选) 主键值.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete($id = NULL)
	{
		$this->trigger('before_delete', $id);

		if ( ! empty($id))
		{ 
			$this->db->where($this->key, $id);
		}		

		$this->db->delete(static::$table_name);
		
		if ($this->db->affected_rows())
		{
			$this->trigger('after_delete', $id);
			return TRUE;
		}

		$this->error = 'DB Error: ' . $this->get_db_error_message();

		return FALSE;
		
	}//end delete()

	//---------------------------------------------------------------

	/**
	 * 根据指定指定条件删除数据.
	 *
	 * @param mixed/array $data 字符串的条件或者数组
	 *
	 * @example 1) $this->model->delete_where(array( 'key' => 'value', 'key2' => 'value2' ))
	 * @example 2) $this->model->delete_where("`key` = 'value' AND `key2` = 'value2'")
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete_where($where)
	{
		$where = $this->trigger('before_delete', $where);

		$this->db->where($where);

		$this->db->delete(static::$table_name);

		$result = $this->db->affected_rows();

		if ($result)
		{
			$this->trigger('after_delete', $result);

			return $result;
		}

		$this->error = 'DB Error: ' . $this->get_db_error_message();

		return FALSE;
		
	}//end delete_where()

	//---------------------------------------------------------------

	//---------------------------------------------------------------
	// HELPER FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * 查询指定条件的数据是否唯一存在.
	 *
	 * @param string $field	用于查询的字段.
	 * @param string $value 匹配$field的值.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function is_unique($field, $value)
	{
		if (empty($field) || empty($value))
		{
			$this->error = '没有足够的条件来检测数据的唯一性';
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->error);
			return FALSE;
		}

		$this->db->where($field, $value);
		$query = $this->db->get(static::$table_name);

		if ($query && $query->num_rows() == 0)
		{
			return TRUE;
		}

		return FALSE;
		
	}//end is_unique()

	//---------------------------------------------------------------

	/**
	 * 返回表中的行数.
	 *
	 * @return int
	 */
	public function count_all()
	{
		return $this->db->count_all_results(static::$table_name);
	}//end count_all()

	//---------------------------------------------------------------

	/**
	 * 根据条件返回表中的行数.
	 *
	 * @param string/array $field	要查询的字段,可以是数组.
	 * @param string $value			(可选)查询值.
	 *
	 * @example 1) count_by("`key` = 'value' AND `key2` = 'value2'")
	 * @example 2) count_by('key', 'value')
	 * @example 3) count_by(array('key' => 'value', 'key2' => 'value2'))
	 * 
	 * @return bool|int
	 */
	public function count_by($field, $value = NULL)
	{
		if (empty($field) || (!is_array($field) && empty($value)))
		{
			$this->error = '没有足够的条件来统计结果';
			$this->logit('['. get_class($this) .': '. __METHOD__ .'] '. $this->error);
			return FALSE;
		}

		$this->db->where($field, $value);

		return $this->db->count_all_results(static::$table_name);
		
	}//end count_by()

	//---------------------------------------------------------------

	/**
	 * 根据主键获取一条数据的单个字段值.
	 *
	 * @param mixed  $id	查询的主键值.
	 * @param string $field 要获取的字段.
	 *
	 * @return bool|mixed 获取的字段值.
	 */
	public function get_field($id, $field)
	{
		$query = $this->db->select($field)
							->where($this->key, $id)
							->get(static::$table_name);

		if ($query && $query->num_rows() > 0)
		{
			return $query->row()->{$field};
		}

		return FALSE;
		
	}//end get_field()

	//---------------------------------------------------------------

	/**
	 * 获取数据并生成一个供下拉选项使用的数组.
	 *
	 * 可以传递值和标签名或只是标签名.
	 * @example 1：
	 * $this->model->format_dropdown('field');
	 * 返回:
	 * array('key'=>field,'key'=>field,...)
	 * @example 2：
	 * $this->model->format_dropdown('field1','field2');
	 * 返回:
	 * array('field1'=>field2,'field1'=>field2,...)
	 *
	 * @return array The options for the dropdown.
	 */
	public function format_dropdown()
	{
		$args =& func_get_args();

		if (count($args) == 2)
		{
			list($key, $value) = $args;
		}
		else
		{
			$key = $this->key;
			$value = $args[0];
		}

		$query = $this->db->select(array($key, $value))->get(static::$table_name);

		$options = array();
		foreach ($query->result() as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		$query->free_result();
		
		return $options;
		
	}//end format_dropdown()
	
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !CHAINABLE UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * 排序
	 * 基于CI的重写方法可使用数组
	 *
	 * @param mixed  $field 要排序的字段或者数组.
	 * @param string $order (可选) 排序的方向 ('asc' or 'desc').
	 *
	 * @example 1) order_by("`key` DESC, `key2` ASC")
	 * @example 2) order_by('key', 'DESC')
	 * @example 3) order_by(array('key' => 'DESC', 'key2' => 'ASC'))
	 *
	 * @return object		returns $this 用于链式操作.
	 */
	public function order_by($field, $order = '')
	{
		if ( ! empty($field))
		{
			if (is_string($field))
			{
				$this->db->order_by($field, $order);
			}
			elseif (is_array($field))
			{
				foreach ($field as $f => $o)
				{
					$this->db->order_by($f, $o);
				}
			}
		}

		return $this;
		
	}//end order_by()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// Scope Methods
	//--------------------------------------------------------------------

    /**
     * 设置是否将返回新插入的ID
     *
     * @param bool $return (可选) 默认TRUE
     *
     * @return object		returns $this 用于链式操作
     */
	public function return_insert_id($return = TRUE)
	{
	    $this->return_insert_id = (bool)$return;

	    return $this;
	    
	}//end return_insert_id()
	
	//--------------------------------------------------------------------

    /**
     * 设置是否跳过数据验证
     *
     * @param bool $skip (可选) 默认跳过数据验证
     *
     * @return object    returns $this 用于链式操作
     */
	public function skip_validation($skip = TRUE)
	{
	    $this->skip_validation = $skip;

	    return $this;
	    
	}//end skip_validation()

	//---------------------------------------------------------------
	// !UTILITY FUNCTIONS
	//---------------------------------------------------------------

	/**
	 * 触发model的特定事件
	 * model中方法的access须定义protected,方法名带"_"前缀,
	 * 如：protected function _before_find($data){...}
	 *
	 * @access public
	 * 
	 * @param string 	$event 	要执行的事件
	 * @param mixed 	$data 	需要进行处理的数据.
	 *
	 * @return mixed
	 */
	public function trigger($event, $data = FALSE)
	{
		$method = "_{$event}";
		
		return $data ? $this->{$method}($data) : $this->{$method}();
		
	}//end trigger();
	
	//---------------------------------------------------------------
	
	/**
	 * 查询的前置操作(目前没有使用)
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * @return void
	 */
	protected function _before_find() {} // end _before_find()

	//---------------------------------------------------------------
	
	/**
	 * 查询的后置操作(目前没有使用)
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * @param array $data 查询得到的结果数据
	 * @return mixed
	 */
	protected function _after_find($data)
	{
		return $data;
	}//end _after_find()
	
	//---------------------------------------------------------------
	
	/**
	 * 插入的前置操作
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * @param array $data 要新增的数据
	 * @return mixed
	 */
	protected function _before_insert($data)
	{
		return $data;
	}//end _before_insert()

	//---------------------------------------------------------------
	
	/**
	 * 插入的后置操作
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * 注意：
	 * 如果新增的数据需要返回新增ID,操作调用这个方法
	 * 另：
	 * 批量添加不会调用这个方法
	 *
	 * @param int $id 新增主键ID
	 * @return bool|mixed 可以是新增的ID或者操作失败返回的FALSE
	 */
	protected function _after_insert($id)
	{
		return $id;
	}//end _after_insert()

	//---------------------------------------------------------------
	
	/**
	 * 更新的前置操作
	 * 在数据验证之后调用
	 *
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * 注意：
	 * 批量更新不会调用这个方法
	 *
	 * @param array $data 要更新的数据
	 * @return array 处理过的更新数据
	 */
	protected function _before_update($data)
	{
		return $data;
	}//end _before_update()

	//--------------------------------------------------------------------
	
	/**
	 * 更新的后置操作
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * 注意：
	 * 批量更新不会调用这个方法
	 *
	 * @param array $data 包含更新的数据和更新处理结果,如：array(更新的数据, 处理结果)
	 * @return void
	 */
	protected function _after_update($data) {} //end _after_update()

	//--------------------------------------------------------------------
	
	/**
	 * 删除的前置操作
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * @param array $id 要更新数据ID
	 * @return void
	 */
	protected function _before_delete($data)
	{
		return $data;
	}//end _before_delete()
	
	//---------------------------------------------------------------
	
	/**
	 * 删除的后置操作
	 * 你可以在model中覆盖这个方法,实现自己的操作
	 *
	 * @param bool|int $result 处理结果,一般是处理结果影响的行数
	 * @return void
	 */
	protected function _after_delete($result) {} //end _after_delete()
	
	//--------------------------------------------------------------------
	
	/**
	 * 设置数据库链接
	 * 
	 * @param string $db_con
	 */
	public function set_db_con($db_con)
	{
		if (class_exists('CI_DB') AND isset($this->db))
		{
			$this->db->close();
		}
		$this->db = $this->load->database($db_con, TRUE);
	}
	
	public function reset_db_con()
	{
		
	}
	
	//--------------------------------------------------------------------

    /**
     * 获取模型的验证规则
     *
     * @param String $type	获取规则的类型：'update' or 'insert', 如果是新增insert,会添加定义的额外验证规则($insert_validation_rules)
     *
     * @return array		空的数组或者model的验证规则
     */
    public function get_validation_rules($type = 'update')
    {
        $temp_validation_rules = $this->validation_rules;
        
        if (empty($temp_validation_rules) || ! is_array($temp_validation_rules))
        {
			return array();
        }

        // 如果有额外的insert规则
        if (strtolower($type) == 'insert'
            && is_array($this->insert_validation_rules)
            && ! empty($this->insert_validation_rules)
           )
        {
            // 设置每个验证规则对应的索引位置
            $fieldIndexes = array();
            foreach ($temp_validation_rules as $key => $validation_rule)
            {
                $fieldIndexes[$validation_rule['field']] = $key;
            }

            foreach ($this->insert_validation_rules as $key => $rule)
            {
                if (is_array($rule))
                {
                    $insert_rule = $rule;
                }
                else
                {
                    // 如果$key不是字段名,并且$rule也不数组,那将无法解析这个规则,直接跳过
                    if (is_numeric($key))
                    {
                        continue;
                    }
                    $insert_rule = array(
                        'field' => $key,
                        'rules' => $rule,
                    );
                }

                /*
                 * 如果字段的验证规则已存在与当前的验证规则中,我们更新合并这个证规则
                 * (如果规则不存在就直接替换).
                 */
                if (isset($fieldIndexes[$insert_rule['field']]))
                {
                    $fieldKey = $fieldIndexes[$insert_rule['field']];
                    // 如果该字段的验证规则没有设定
                    if (empty($temp_validation_rules[$fieldKey]['rules']))
                    {
                    	// 直接替换
                        $temp_validation_rules[$fieldKey]['rules'] = $insert_rule['rules'];
                    }
                    else
                    {
                    	// 为这个主规则添加额外的验证规则
                        $temp_validation_rules[$fieldKey]['rules'] .= '|' . $insert_rule['rules'];
                    }
                }
                else
                {
                    // 否则，我们添加了插入规则的验证规则
                    $temp_validation_rules[] = $insert_rule;
                }
            }
        }

        return $temp_validation_rules;
        
    }//end get_validation_rules()

	//--------------------------------------------------------------------

	/**
	 * 验证数据.
	 *
	 * 可验证新增和插入的数据,批量操作不进行验证.
	 *
	 * @param  array	$data	用于验证的数据
	 * @param  string	$type	验证的类型：'update' or 'insert'.
	 * @return array/bool       原来的数据 or FALSE
	 */
	public function validate($data, $type = 'update')
	{
	    if ($this->skip_validation)
	    {
	        return $data;
	    }

        $current_validation_rules = $this->get_validation_rules($type);

        if (empty($current_validation_rules))
        {
            return $data;
        }

        foreach ($data as $key => $val)
        {
            $_POST[$key] = $val;
        }

        $this->load->library('form_validation');

        if (is_array($current_validation_rules))
        {
            $this->form_validation->set_rules($current_validation_rules);
            $valid = $this->form_validation->run();
        }
        else
        {
            $valid = $this->form_validation->run($current_validation_rules);
        }

        if ($valid !== TRUE)
        {
            return FALSE;
        }

        return $data;
        
	}// end validate()

    //--------------------------------------------------------------------

	/**
	 * 获取来与于数据库的错误信息
	 *
	 * @return string
	 */
	protected function get_db_error_message()
	{
		switch ($this->db->platform())
		{
			case 'mysql':
				return mysql_error($this->db->conn_id);
			case 'mysqli':
				return mysqli_error($this->db->conn_id);
			default:
				return $this->db->_error_message();
		}
		
	}//end get_db_error_message()

	//--------------------------------------------------------------------

	/**
	 * 获取当前设置的表名
	 *
	 * @return string static::$table_name (当前model使用的表名)
	 */
	public function get_table()
	{
		return static::$table_name;

	}//end get_table()

	//--------------------------------------------------------------------

	/**
	 * 获取主键名称
	 *
	 * @return string $this->key (当前model设置的表主键)
	 */
	public function get_key()
	{
		return $this->key;

	}//end get_key()

	//--------------------------------------------------------------------

	/**
	 * 将错误日志记录到文件中.
	 * 
	 * 也可以扩展或者重写这个方法,将错误记录发送到控制台,方便进行进一步的处理.
	 *
	 * @param string $message 要写入的记录.
	 * @param string $level   日志级别,按CI log_message方法.
	 *
	 * @access protected
	 *
	 * @return mixed
	 */
	protected function logit($message, $level='debug')
	{
		if (empty($message))
		{
			return FALSE;
		}
		
		log_message($level, $message);

	}//end logit()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
    // CI数据库处理的进一步封装
    //--------------------------------------------------------------------
    // 在model中提供CI更多的数据库操作方法
    //
    // 可以这么调用:
    //      $result = $this->model->select('...')
    //                            ->where('...')
    //                            ->having('...')
    //                            ->get();
    //

    public function select ($select = '*', $escape = NULL) { $this->db->select($select, $escape); return $this; }
    public function select_max ($select = '', $alias = '') { $this->db->select_max($select, $alias); return $this; }
    public function select_min ($select = '', $alias = '') { $this->db->select_min($select, $alias); return $this; }
    public function select_avg ($select = '', $alias = '') { $this->db->select_avg($select, $alias); return $this; }
    public function select_sum ($select = '', $alias = '') { $this->db->select_sum($select, $alias); return $this; }
    public function distinct ($val=TRUE) { $this->db->distinct($val); return $this; }
    public function from ($from) { $this->db->from($from); return $this; }
    public function join($table, $cond, $type = '') { $this->db->join($table, $cond, $type); return $this; }
    public function where($key, $value = NULL, $escape = TRUE) { $this->db->where($key, $value, $escape); return $this; }
    public function or_where($key, $value = NULL, $escape = TRUE) { $this->db->or_where($key, $value, $escape); return $this; }
    public function where_in($key = NULL, $values = NULL) { $this->db->where_in($key, $values); return $this; }
    public function or_where_in($key = NULL, $values = NULL) { $this->db->or_where_in($key, $values); return $this; }
    public function where_not_in($key = NULL, $values = NULL) { $this->db->where_not_in($key, $values); return $this; }
    public function or_where_not_in($key = NULL, $values = NULL) { $this->db->or_where_not_in($key, $values); return $this; }
    public function like($field, $match = '', $side = 'both') { $this->db->like($field, $match, $side); return $this; }
    public function not_like($field, $match = '', $side = 'both') { $this->db->not_like($field, $match, $side); return $this; }
    public function or_like($field, $match = '', $side = 'both') { $this->db->or_like($field, $match, $side); return $this; }
    public function or_not_like($field, $match = '', $side = 'both') { $this->db->or_not_like($field, $match, $side); return $this; }
    public function group_by($by) { $this->db->group_by($by); return $this; }
    public function having($key, $value = '', $escape = TRUE) { $this->db->having($key, $value, $escape); return $this; }
    public function or_having($key, $value = '', $escape = TRUE) { $this->db->or_having($key, $value, $escape); return $this; }
    public function limit($value, $offset = '') { $this->db->limit($value, $offset); return $this; }
    public function offset($offset) { $this->db->offset($offset); return $this; }
    public function set($key, $value = '', $escape = TRUE) { $this->db->set($key, $value, $escape); return $this; }

}//end MY_Model

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */