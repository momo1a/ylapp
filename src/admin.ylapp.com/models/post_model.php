<?php
/**
 * 帖子model.
 * User: momo1a@qq.com
 * Date: 2016/8/14 0014
 * Time: 下午 1:55
 */

class Post_model extends MY_Model
{

    public static $table_name = 'post';


    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加帖子
     * @param $data
     */
    public function postAdd($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 帖子列表
     * @param string $keyword
     * @param int $state
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function postList($keyword='',$state = -1,$limit=10,$offset=0){
        if($keyword != ''){
            $this->like('postTitle',$keyword);
        }
        if($state != -1){
            $this->where(array('state'=>$state));
        }
        $this->join('YL_user','YL_user.uid=YL_post.postUid','left');
        $this->order_by(array('postTime'=>'desc'));
        $this->limit($limit);
        $this->offset($offset);
        return $this->find_all();
    }


    /**
     * 帖子总数
     * @return int
     */
    public function postCount($keyword = '',$state = -1){
        if($keyword != ''){
            $this->like('postTitle',$keyword);
        }
        if($state != -1){
            $this->where(array('state'=>$state));
        }
        return $this->count_all();
    }




    /**
     * 帖子详情页面
     * @param $postId
     * @param string $select
     * @return array
     */
    public function postDetail($postId,$select='*'){
        $where = array('id'=>$postId);
        $this->select($select);
        $this->join('YL_user','YL_post.postUid=YL_user.uid','left');
        $res = $this->find_by($where);
        return $res;
    }

    /**
     * 设置
     * @param $pid  帖子id
     * @param $field  字段
     * @param $value  值
     */
    public function postSetting($pid,$field,$value){
        $where = array('id'=>$pid);
        $data = array($field=>$value);
        $res = $this->update($where,$data);
        return $res;
    }

    /**
     * 删除帖子
     * @param $pid
     */
    public function postDel($pid){
        $where = array('id'=>$pid);
        $res = $this->delete_where($where);
        return $res;
    }


    //  获取管理员未处理的
    public function getNotDeal($select='*'){
        $this->select($select,false);
        $this->where(array('state'=>0));
        return $this->find_all();
    }

}