<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: momo1a@qq.com
 * Date: 2016/8/5
 * Time: 14:50
 */
class News_model extends MY_Model
{
    public static $table_name = 'news';


    public function __construct(){
        parent::__construct();

    }

    /**
     * 获取资讯列表
     * @param $limit
     * @param $offset
     */
    public function getNewsList($limit,$offset,$keyword='',$state=-1,$postPos=0){
        if($keyword != ''){
            $this->like(array('title'=>$keyword));
        }
        if($state != -1){
            $this->where(array('state'=>$state));
        }
        if($postPos != 0){
            $this->where(array('postPos'=>$postPos));
        }
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by(array('nid'=>'desc'));
        return $this->find_all();
    }

    /**
     * 资讯总数
     * @return int
     */
    public function newsCount($keyword='',$state = -1,$postPos = 0){
        if($keyword != ''){
            $this->like(array('title'=>$keyword));
        }
        if($state != -1){
            $this->where(array('state'=>$state));
        }
        if($postPos != 0){
            $this->where(array('postPos'=>$postPos));
        }
        return $this->count_all();
    }

    /**
     * 资讯详情
     * @param $nid
     * @param $field
     */
    public function getNewsDetail($nid,$field='*'){
        $where = array('nid'=>$nid);
        $this->select($field);
        $res = $this->find_by($where);
        return $res;
    }


    /**
     * 添加资讯
     * @param $data
     */
    public function addNews($data){
        $this->insert($data);
        return $this->db->insert_id();
    }
}