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
    public function getNewsList($limit,$postPos,$select,$recmdToIndex=false){
        $where = array('postPos'=>$postPos);
        if($recmdToIndex){
            $this->where('isRecmdIndex',1);
        }
        $this->select($select);
        $this->join('YL_news_category','YL_news_category.cid=YL_news.cid','left');
        $this->order_by(array('createTime'=>'DESC','nid'=>'DESC'));
        $this->limit($limit);
        $res = $this->find_all_by($where);
        return $res;
    }

    /**
     * 资讯详情
     * @param $nid
     * @param $field
     */
    public function getNewsDetail($nid,$field){
        $where = array('nid'=>$nid);
        $this->select($field);
        $res = $this->find_by($where);
        return $res;
    }
}