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

    /**
     * 编辑资讯
     * @param $nid
     * @param $data
     */
    public function editNews($nid,$data){
        $where = array('nid'=>$nid);
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * 获取首页banner图
     * @return array
     */
    public function getBanner(){
        $this->where(array('isRecmdIndex'=>1,'state'=>1));
        $this->order_by(array('nid'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

    /**
     * 删除banner
     * @param $nid
     */
    public function delBanner($nid){
        $where = array('nid'=>$nid);
        $data = array('isRecmdIndex'=>0);
        $res = $this->update($where,$data);
        return $res;
    }


    /**
     * 删除资讯
     * @param $nid
     * @return bool
     */
    public function delNews($nid){
        $where = array('nid'=>$nid);
        $res = $this->delete_where($where);
        return $res;
    }

}