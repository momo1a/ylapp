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
     * @param $postPos 发布位置
     */
    public function getNewsList($limit,$postPos,$select,$recmdToIndex=false,$offset=0,$keyword = ''){
        $where = array('YL_news.state'=>1);
        if($recmdToIndex){
            $this->where('isRecmd',1);
        }
        if($keyword != ''){
            $this->like('title',$keyword);
            $this->or_like('tag',$keyword);
        }
        $this->where('(YL_news.postPos',$postPos);
        $this->or_where('YL_news.postPos','0)',false);
        $this->select($select);
        $this->join('YL_news_category','YL_news_category.cid=YL_news.cid','left');
        $this->order_by(array('createTime'=>'DESC','nid'=>'DESC'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all_by($where);
        return $res;
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
     * 获取客户端首页banner图片
     * @param $position 1 用户端 2 医生端
     */
    public function getClientIndexBanner($position,$select){
        $this->select($select);
        $where = array('isRecmdIndex'=>1,'state'=>1);
        $pos = array($position,0);
        $this->or_where_in('postPos',$pos);
        $this->order_by(array('nid'=>'desc'));
        $res = $this->find_all_by($where);
        //var_dump($this->db->last_query());
        return $res;
    }
}