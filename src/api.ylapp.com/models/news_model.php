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
    public function getNewsList($limit,$postPos){
        $where = array('postPos'=>$postPos);
        $this->join('YL_news_category','YL_news_category.cid=YL_news.nid','left');
        $this->limit($limit);
        $res = $this->find_all_by($where);
        return $res;
    }
}