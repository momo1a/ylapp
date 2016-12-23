<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 用户模型
 */
class User_model extends MY_Model
{
    public static $table_name = 'user';

    /**
     * 用户注册
     * @param $mobile
     * @param $userType
     * @param $pwd
     * @return bool|int|mixed
     */
    public function reg($mobile,$userType,$pwd,$regIp){
        $data = array('phone'=>$mobile,'userType'=>$userType,'password'=>$pwd,'dateline'=>time(),'regIp'=>$regIp,'nickname'=>'用户'.time().rand(100000,999999));
        $res = $this->insert($data);
        return $res;

    }

    /**
     * 获取用户手机号
     * @param $mobile
     * @return array
     */
    public function getUserMobile($mobile){
        $res = $this->find_by('phone',$mobile);
        return $res;
    }

    /**
     * 修改密码
     * @param $uid
     * @param $pwd
     * @return bool
     */
    public function reSettingPwd($uid,$pwd){
        $where = array('uid'=>$uid);
        $data = array('password'=>$pwd);
        $res = $this->update($where,$data);
        return $res;
    }



    /**
     * 修改密码
     * @param $phone  电话号码
     * @param $pwd
     * @return bool
     */
    public function reSettingForgotPwd($phone,$pwd,$flag=false){
        if($flag){
            $data = array('payPassword'=>$pwd);
        }else{
            $data = array('password'=>$pwd);
        }
        $where = array('phone'=>$phone);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     *
     * @param $user  昵称或者手机号
     */
    public function getRecord($column,$value){
        $where =  array($column=>$value);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 依据条件获取一条用户数据
     * @param $condition
     */
    public function getUserCondition($condition){
        return $this->find_by($condition);
    }


    /**
     * 绑定第三方账号
     * @param $flag  绑定唯一标识
     * @param $phone 用户手机号
     * @param $type  绑定类型 1 微信 ，2 qq。。。 默认微信
     */
    public function bindThirdPart($flag,$phone,$type=1){
        switch($type){
            case 1:
                $where = array('phone'=>$phone,'userType'=>1);
                $data = array('isBindWechat'=>1,'wechatOpenid'=>$flag);
                $res = $this->update($where,$data);
                if($res){
                    return $this->find_by($where);
                }else{
                    return false;
                }
                break;
            case 2:
                $where = array('phone'=>$phone,'userType'=>1);
                $data = array('isBindQQ'=>1,'QQOpenid'=>$flag);
                $res = $this->update($where,$data);
                if($res){
                    return $this->find_by($where);
                }else{
                    return false;
                }
                break;
            default:
                exit('绑定类型错误');
        }


    }

    /**
     * 根据uid获取一条用户记录
     * @param $uid
     */
    public function getUserByUid($uid,$select='*'){
        $this->select($select);
        $where = array('uid'=>$uid);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 修改登录信息
     * @param $uid
     */
    public function updateLoginInfo($uid){
        $where = array('uid'=>$uid);
        $data = array('lastLoginTime'=>time(),'lastLoginIp'=>bindec(decbin(ip2long($_SERVER['REMOTE_ADDR']))));
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 获取医生列表
     * @param $officeId  科室id
     * @param $hid      医院id
     * @param $limit    获取条数
     * @param $select   查询字段
     * @return array
     */
    public function getDoctorList($limit,$select,$officeId=0,$hid=0,$keyword='',$offset=0){
        $officeId = intval($officeId);
        $hid = intval($hid);
        if($officeId != 0){
            $this->where('YL_doctor_offices.id',$officeId);
        }
        if($hid != 0){
            $this->where('YL_hospital.hid',$hid);
        }
        if($keyword != ''){
            $this->like('YL_user.nickname',$keyword);
        }
        $where = array('userType'=>2,'YL_doctor_info.state'=>1);
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all_by($where);
        return $res;
    }

    /**
     * 获取医生详情
     * @param $docId
     */
    public function getDoctorDetail($docId,$select){
        $this->select($select,false);
        $this->where(array('YL_user.userType'=>2,'YL_user.uid'=>$docId));
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user.uid','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->join('YL_doctor_offices','YL_doctor_offices.id=YL_doctor_info.officeId','left');
        $res = $this->find_all();
        return $res;
    }



    public function getUserInfoByUid($uid,$column){
        $where = array('uid'=>$uid);
        $res = $this->find_by($where);
        return $res[$column];
    }


    /*用户端一些逻辑*/


    /**
     * 保存（修改）用户信息
     * @param $uid
     * @param $data
     */
    public function saveUserDetail($uid,$data){
        $where = array('uid'=>$uid);
        $res = $this->update($where,$data);
        return $res;
    }


    /*医生端一些逻辑*/

    /**
     * 医生修改个人信息
     * @param $docId
     * @param $data
     */
    public function updateDocInfo($docId,$userData){
        $where = array('uid'=>$docId);
        $res = $this->update($where,$userData);
        return $res;
    }



}
