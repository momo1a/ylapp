<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 病历备注表模型
 */
class User_illness_history_remarks_model extends MY_Model
{
    public static $table_name = 'user_illness_history_remarks';

    /**
     * 获取对应用户对应病历id的remark记录
     * @param $IllId
     * @param $uid
     */
    public function getRemarksByIllIdAndUid($IllId,$uid){
        $where = array('illId'=>$IllId,'uid'=>$uid);
        $res = $this->find_all_by($where);
        return $res;
    }

    /**
     * 添加备注
     * @param $data
     * @return bool|mixed
     */
    public function addRemark($data){
        $res = $this->insert($data);
        return $res;
    }

    /**
     * 编辑记录
     * @param $remarkId
     * @param $uid
     * @param $illId
     * @param $data
     */
    public function editRemarks($remarkId,$uid,$illId,$data){
        $where = array('id'=>$remarkId,'uid'=>$uid,'illId'=>$illId);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 删除病历记录
     * @param $remarkId
     * @param $uid
     * @return bool
     */
    public function delRemarkById($remarkId,$uid){
        $where = array('id'=>$remarkId,'uid'=>$uid);
        $res = $this->delete_where($where);
        return $res;
    }

    /**
     * 根据remarkId获取到该记录的img
     * @param $id
     */
    public function getImgById($id){
        $where = array('id'=>$id);
        $this->select('img');
        $res = $this->find_by($where);
        if($res){
            return json_decode($res['img'],true);
        }else{
            return false;
        }
    }

    /**
     * 根据病历id获取备注
     * @param $illId
     *
     */
    public function getRemarksByIllId($illId,$select='*'){
        $this->where(array('illId'=>$illId));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }
}
