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
	
}
