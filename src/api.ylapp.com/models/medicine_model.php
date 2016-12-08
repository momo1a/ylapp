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


    /**
     * 列表
     * @param int $limit
     * @param int $offset
     * @param int $cate
     */
    public function mediList($limit=10,$offset=0,$select='*',$cate=0,$keyword=''){
        if($cate != 0){
            $this->where(array('YL_medicine.cid'=>$cate));
        }
        if($keyword != ''){
            $this->like(array('YL_medicine.name'=>$keyword));
            $this->or_like(array('YL_medicine.outline'=>$keyword));
        }
        $this->select($select);
        $this->limit($limit);
        $this->offset($offset);
        $this->join('YL_medi_category','YL_medi_category.cid=YL_medicine.cid','left');
        $this->order_by(array('id'=>'desc','editTime'=>'desc'));
        return $this->find_all();

    }


    /**
     * 获取药品详情
     * @param $mid
     * @return array
     */
    public function getMedicineDetail($mid,$select='*'){
        $this->select($select);
        return $this->find_by(array('id'=>$mid));
    }

    /**
     * 获取药品banner
     * @param $num  获取数量
     */
    public function getMedicineBanner($num=3){
        $this->limit($num);
        $this->select('id as mid,banner');
        $this->order_by(array('id'=>'desc'));
        return $this->find_all();
    }


}