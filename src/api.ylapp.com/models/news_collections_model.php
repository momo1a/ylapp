<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 资讯收藏model
 * User: momo1a@qq.com
 * Date: 2016/8/5
 * Time: 14:50
 */
class News_collections_model extends MY_Model
{
    public static $table_name = 'news_collections';

    public function __construct(){
        parent::__construct();
    }

    /**
     *
     * @param $uid
     * @param $nid
     */
    public function getCollectionByUidAndNid($uid,$nid){
        $this->where(array('nid'=>$nid,'uid'=>$uid));
        $res = $this->count_all();
        return $res;
    }

    /**
     * 添加收藏
     * @param $data
     */
    public function addCollection($data){
        $this->insert($data);
        return $this->db->insert_id();
    }


    /**
     * 我的收藏
     * @param $uid
     * @param string $select
     */
    public function myCollections($uid,$select='*',$limit=10,$offset=0){
        $this->select($select);
        $this->where(array('YL_news_collections.uid'=>$uid,'YL_news.state'=>1));
        $this->join('YL_news','YL_news.nid=YL_news_collections.nid','left');
        $this->order_by(array('YL_news_collections.id'=>'desc'));
        $this->limit($limit);
        $this->offset($offset);
        $res = $this->find_all();
        return $res;

    }

    /**
     * 删除收藏
     * @param $collId
     * @param $uid
     */
    public function delCollection($collId,$uid){
        $where = array('id'=>$collId,'uid'=>$uid);
        $res = $this->delete_where($where);
        return $res;
    }


}