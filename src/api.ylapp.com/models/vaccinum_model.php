<?php
/**
 * 基因检测model
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
     * 获取疫苗列表
     * @param string $select
     * @
     */
    public function getList($select='*',$type=0,$keyword='',$limit=10,$offset=0){
        if($type != 0){
            $this->where(array('type'=>$type));
        }

        if($keyword != ''){
            $this->like(array('name'=>$keyword));
        }
        $this->select($select);
        $this->where(array('status'=>1));  //上架的
        $this->order_by(array('id'=>'desc','dateline'=>'desc'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;
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

}