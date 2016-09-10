<?php 
class User_model extends My_Model{

    public static $table_name = 'user';
	
	public function __construct(){
		parent::__construct();
	}
	
	/*
	 * 获取权限列表
	*/
	public function get_acl($role_id){


	}
	
	/*
	 * 用户登录检测
	*/
	public function check_user($username,$password){
        $where =  array('nickname'=>$username,'password'=>$password,'isBackgroundUser'=>1);
        $this->limit(1);
        $res = $this->find_all_by($where);
        return $res;
	}
	
	/*
	 * 用户登录检测 By id
	*/
	public function check_user_by_id($id){

	}


    /**
     * 获取所有后台用户
     * @return array
     */
    public function getAuthList($limit=10,$offset=0){
        $this->where(array('isBackgroundUser'=>1));
        $this->order_by(array('uid'=>'desc'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }

    /**
     * 获取后台用户总数
     */
    public function AuthListCount(){
        $this->where(array('isBackgroundUser'=>1));
        $count = $this->count_all();
        return $count;
    }
	
}
