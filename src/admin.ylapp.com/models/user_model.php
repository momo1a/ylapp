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
     * 获取用户列表
     * @param int $limit
     * @param int $offset
     * @param string $nickname
     * @param string $phone
     * @param int 主要针对医生用户
     * @return array
     */
    public function getUserList($limit=10,$offset=0,$nickname='',$phone='',$userType=1,$select='*',$state = -1){
        $this->where('isBackgroundUser !=',1);
        $this->where('userType',$userType);
        $this->select($select);
        $this->join('YL_money','YL_money.uid=YL_user.uid','left');
        if($userType == 2){
            if($state != -1){
                $this->where(array('YL_doctor_info.state'=>$state));
            }
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        }
        $this->limit($limit);
        $this->offset($offset);
        if(''!= $nickname){
            $this->like('nickname',$nickname);
        }
        if(''!= $phone){
            $this->like('phone',$phone);
        }
        $this->order_by('YL_user.uid','desc');
        $res = $this->find_all();
        return $res;
    }

    /**
     * 获取用户数量
     * @param string $nickname
     * @param string $phone
     */
    public function getUserCount($nickname='',$phone='',$userType=1,$state = -1){
        $this->where('isBackgroundUser !=',1);
        $this->where('userType',$userType);
        if($userType == 2){
            if($state != -1){
                $this->where(array('YL_doctor_info.state'=>$state));
            }
            $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        }
        if(''!= $nickname){
            $this->like('nickname',$nickname);
        }
        if(''!= $phone){
            $this->like('phone',$phone);
        }
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


    /**
     * 设置用户黑名单
     * @param $uid 用户id
     * @param $flag 1 关黑屋 2 解除黑屋
     */
    public function setUserBlank($uid,$flag){
        $where = array('uid'=>intval($uid));
        switch(intval($flag)){
            case 1:
                $data = array('isBlack'=>1);
                break;
            default:
                $data = array('isBlack'=>0);
        }
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * 添加医生账户
     * @param $phone
     * @param $password
     * @param $nickname
     * @param $sex
     * @param $hid
     * @param $officeId
     */
    public function addDoctor($phone,$password,$nickname,$sex,$hid,$officeId){
        $this->db->trans_begin();
        $resF = $this->insert(array('phone'=>$phone,'password'=>$password,'nickname'=>$nickname,'sex'=>$sex,'userType'=>2,'dateline'=>time(),'regIp'=>ip2long($_SERVER['REMOTE_ADDR'])));
        $docId = $this->db->insert_id();
        $resS = $this->db->insert('doctor_info',array('uid'=>$docId,'hid'=>$hid,'officeId'=>$officeId,'sex'=>$sex));
        if (!$resF || !$resS)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }




	
}
