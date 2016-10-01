<?php
/**
 * 疫苗接种model
 * User: momo1a@qq.com
 * Date: 2016/8/10
 * Time: 10:35
 */

class Vaccinum_model extends MY_Model
{
    public static $table_name = 'vaccinum';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 基因列表
     * @param string $keyword
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getList($keyword='',$limit=10,$offset=0){
        if($keyword != '') {
            $this->like(array('name' => $keyword));
        }
        $this->order_by(array('dateline'=>'desc'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
    }


    /**
     * 统计
     * @param string $keyword
     */
    public function viccinumCount($keyword=''){
        if($keyword != '') {
            $this->like(array('name' => $keyword));
        }

        return $this->count_all();
    }
    /**
     * 获取基因详情
     * @param $geneId
     */
    public function getVaccinumDetail($geneId,$select='*'){
        $this->where(array('id'=>$geneId));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }


    /**
     * 添加套餐
     * @param $data
     */
    public function addPackage($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 编辑套餐
     * @param $nid
     * @param $data
     */
    public function editPackage($pid,$data){
        $where = array('id'=>$pid);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 套餐详情
     * @param $nid
     * @param $field
     */
    public function getDetail($pid,$field='*'){
        $where = array('id'=>$pid);
        $this->select($field);
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 删除套餐
     * @param $nid
     * @return bool
     */
    public function delPackage($pid){
        $where = array('id'=>$pid);
        $res = $this->delete_where($where);
        return $res;
    }

}