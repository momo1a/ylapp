<?php
/**
 * 基因检测model
 * User: momo1a@qq.com
 * Date: 2016/8/10
 * Time: 10:35
 */

class Gene_check_model extends MY_Model
{
    public static $table_name = 'gene_check';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取基因列表
     * @param string $select
     */
    public function getList($select='*'){
        $this->select($select);
        $this->where(array('status'=>1));  //上架的
        $this->order_by(array('id'=>'desc','dateline'=>'desc'));
        $res = $this->find_all();
        return $res;
    }


    /**
     * 获取基因详情
     * @param $geneId
     */
    public function getGeneDetail($geneId,$select='*'){
        $this->where(array('id'=>$geneId));
        $this->select($select);
        $res = $this->find_all();
        return $res;
    }

}