<?php
/**
 * 药品模型
 * User: momo1a@qq.com
 * Date: 2016/10/14
 * Time: 14:57
 */
class  Medicine_model extends MY_Model
{
    public static $table_name = 'medicine';

    public function __construct(){
        parent::__construct();
    }




    public function getAllMedicine($limit = 1000){
        $this->limit($limit);
        return $this->find_all();
    }

    /**
     * 列表
     * @param int $limit
     * @param int $offset
     * @param int $cate
     */
    public function mediList($limit=10,$offset=0,$select='*',$cate=0){
        if($cate != 0){
            $this->where(array('YL_medicine.cid'=>$cate));
        }
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $this->join('YL_medi_category','YL_medi_category.cid=YL_medicine.cid','left');
        $this->order_by(array('id'=>'desc','editTime'=>'desc'));
        return $this->find_all();

    }

    /**
     * 统计
     * @param int $cate
     */
    public function mediCount($cate=0){
        if($cate != 0){
            $this->where(array('cid'=>$cate));
        }

        return $this->count_all();
    }


    /**
     * 添加药品
     * @param $data
     */
    public function mediAdd($data){
        $this->insert($data);
        return $this->db->insert_id();
    }


    /**
     * 获取药品详情
     * @param $mid
     * @return array
     */
    public function getMedicineDetail($mid){
        return $this->find_by(array('id'=>$mid));
    }

    /**
     * 编辑药品
     * @param $mid
     * @param $data
     * @return bool
     */
    public function mediEdit($mid,$data){
        return $this->update(array('id'=>$mid),$data);
    }

    /**
     * 删除药品
     * @param $mid
     */
    public function mediDel($mid){
        return $this->delete_where(array('id'=>$mid));
    }
}