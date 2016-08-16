<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 用户预约挂号模型
 * Author: momo1a@qq.com
 * Date: 2016/8/12
 * Time: 10:11
 */

class User_reg_num_model extends MY_Model
{
    public static $table_name = 'user_reg_num';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 挂号第一步生成记录
     */
    public function firstStep($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 获取用户预约列表
     * @param $uid
     */
    public function appointList($uid,$select="*"){
        $this->where(array('userId'=>$uid));
        $this->select($select);
        $this->join('YL_doctor_info','YL_doctor_info.uid=YL_user_reg_num.docId','left');
        $this->join('YL_hospital','YL_doctor_info.hid=YL_hospital.hid','left');
        $this->order_by(array('dateline'=>'DESC'));
        $res = $this->find_all();
        return $res;
    }


    /**
     * @param $uid
     * @param $id
     * @param string $select
     */
    public function appointDetail($uid,$id,$select="*"){
        $this->where(array('userId'=>$uid));
        $this->select($select);
        $res = $this->find($id);
        return $res;
    }

    /**
     * 取消预约
     * @param $uid
     * @param $id
     */
    public function appointCancel($uid,$id){
        $where = array('userId'=>$uid,'id'=>$id);
        $data = array('cancelTime'=>time(),'status'=>6);
        $res = $this->update($where,$data);
        return $res;
    }

}