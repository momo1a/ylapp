<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: momo1a@qq.com
 * Date: 2016/8/5
 * Time: 14:50
 */
class Hospital_model extends MY_Model
{
    public static $table_name = 'hospital';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取医院列表
     * @param $id
     * @param string $keyword
     * @return array
     */
    public function getHospitalList($id=0,$keyword="",$select="*",$limit=500,$offset=0){
        $id = intval($id);
        if($id){
            $this->where(array('hid'=>$id));
        }
        if($keyword != ''){
            $this->like('name',$keyword);
        }
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();

        return $res;
    }


    /**
     * 获取医院数量
     * @param string $keyword
     * @return int
     */
    public function getHospitalCount($keyword = ''){
        if($keyword != ''){
            $this->like('name',$keyword);
        }
        return $this->count_all();
    }

    /**
     * 获取医院详情
     * @param $hid
     */
    public function getHospitalDetail($hid){
        $where = array('hid'=>$hid);
        $res = $this->find_by($where);
        return $res;
    }
}