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

    /**
     * 获取用户信息
     * @param $uid
     */
    public function getUserInfoByUid($uid){
        $uid = intval($uid);
        $res = $this->find_by('uid',$uid);
        return $res;
    }

    /**
     * 修改用户信息
     * @param $uid
     */
    public function updateUserInfoByUid($uid,$data){
        $where = array('uid'=>intval($uid));
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 删除账号
     * @param $uid
     */
    public function delUserByUid($uid){
        $uid = intval($uid);
        $where = array('uid'=>$uid);
        $res = $this->delete_where($where);
        return $res;
    }

    /**
     * 添加后台账户
     * @param $data
     */
    public function addAuth($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 检测列条件是否存在
     * @param $column 列
     * @param $value 值
     */
    public function getRecord($column,$value){
        $where =  array($column=>$value);
        $res = $this->find_by($where);
        return $res;
    }

	
}
